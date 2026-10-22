// Enseña el campo para escribir la categoría solo cuando en el desplegable
// se elige "+ Nueva categoría...". Lo usan la vista principal y la de editar.
function mostrarNuevaCategoria(select) {
    const caja = document.getElementById("caja_nueva_categoria");
    const campo = document.getElementById("nueva_categoria");

    if (select.value == "nueva") {
        caja.hidden = false;
        // Si se ha elegido "nueva", hay que escribir el nombre
        campo.required = true;
        campo.focus();
    } else {
        caja.hidden = true;
        campo.required = false;
        campo.value = "";
    }
}
