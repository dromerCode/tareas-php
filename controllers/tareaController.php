<?php
require_once __DIR__ . '/../repositories/TareaRepository.php';
require_once __DIR__ . '/../repositories/UsuarioRepository.php';

// Sin sesión no se puede ver ninguna tarea
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?page=login");
    exit;
}
$tareaRepo = new TareaRepository($db);
$usuarioRepo = new UsuarioRepository($db);
$miId = $_SESSION['id_usuario'];
$esAdmin = ($_SESSION['rol'] ?? '') === 'admin';
$mensaje = '';

// Editar y borrar: solo quien la creó o el admin
function puedeEditar($tarea, $miId, $esAdmin){
    if ($esAdmin) {
        return true;
    }
    return $tarea->getIdCreador() == $miId;
}

if ($page == 'nueva_tarea') {
    if (isset($_POST['titulo'])) {
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        // Los campos vacíos se guardan como NULL
        $fecha = $_POST['fecha_limite'];
        if ($fecha == '') {
            $fecha = null;
        }
        $asignado = $_POST['id_asignado'];
        if ($asignado == '') {
            $asignado = null;
        }

        if ($titulo == '') {
            $mensaje = 'El título es obligatorio';
        } else {
            $tarea = new Tarea(null, $titulo, $descripcion, 'pendiente', $fecha, $miId, $asignado);
            $tareaRepo->save($tarea);
            header("Location: index.php?page=tareas&ver=creadas");
            exit;
        }
    }
    $usuarios = $usuarioRepo->findAll();
    require __DIR__ . '/../views/nuevaTareaView.phtml';

} elseif ($page == 'editar_tarea') {
    $tarea = $tareaRepo->findById((int)($_GET['id'] ?? 0));
    if (!$tarea || !puedeEditar($tarea, $miId, $esAdmin)) {
        header("Location: index.php?page=tareas");
        exit;
    }

    if (isset($_POST['titulo'])) {
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $fecha = $_POST['fecha_limite'];
        if ($fecha == '') {
            $fecha = null;
        }
        $asignado = $_POST['id_asignado'];
        if ($asignado == '') {
            $asignado = null;
        }

        if ($titulo == '') {
            $mensaje = 'El título es obligatorio';
        } else {
            $tarea = new Tarea($tarea->getId(), $titulo, $descripcion, $_POST['estado'], $fecha, $tarea->getIdCreador(), $asignado);
            $tareaRepo->update($tarea);
            header("Location: index.php?page=tareas&ver=creadas");
            exit;
        }
    }
    $usuarios = $usuarioRepo->findAll();
    require __DIR__ . '/../views/editarTareaView.phtml';

} elseif ($page == 'borrar_tarea') {
    $tarea = $tareaRepo->findById((int)($_POST['id_tarea'] ?? 0));
    if ($tarea && puedeEditar($tarea, $miId, $esAdmin)) {
        $tareaRepo->delete($tarea->getId());
    }
    // Volvemos a la misma pestaña desde la que se borró
    header("Location: index.php?page=tareas&ver=" . urlencode($_POST['ver'] ?? 'mias'));
    exit;

} elseif ($page == 'aceptar_tarea') {
    // Se puede aceptar si es tuya o si no es de nadie (y así te la quedas)
    $tarea = $tareaRepo->findById((int)($_POST['id_tarea'] ?? 0));
    if ($tarea && $tarea->getEstado() == 'pendiente') {
        if ($tarea->getIdAsignado() == $miId || $tarea->getIdAsignado() == null) {
            $tareaRepo->aceptar($tarea->getId(), $miId);
        }
    }
    header("Location: index.php?page=tareas");
    exit;

} elseif ($page == 'completar_tarea') {
    // Solo la completa quien la tiene asignada y ya la había aceptado
    $tarea = $tareaRepo->findById((int)($_POST['id_tarea'] ?? 0));
    if ($tarea && $tarea->getEstado() == 'aceptada' && $tarea->getIdAsignado() == $miId) {
        $tareaRepo->completar($tarea->getId());
    }
    header("Location: index.php?page=tareas");
    exit;

} else {
    // Lista de tareas: ?ver= elige cuáles
    $ver = $_GET['ver'] ?? 'mias';
    if ($ver == 'creadas') {
        $tareas = $tareaRepo->findCreadasPor($miId);
    } elseif ($ver == 'sin_asignar') {
        $tareas = $tareaRepo->findSinAsignar();
    } elseif ($ver == 'todas' && $esAdmin) {
        $tareas = $tareaRepo->findAll();
    } else {
        $ver = 'mias';
        $tareas = $tareaRepo->findAsignadasA($miId);
    }
    require __DIR__ . '/../views/tareasView.phtml';
}
