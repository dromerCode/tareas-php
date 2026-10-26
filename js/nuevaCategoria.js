// enseña el campo de nueva categoria si se elige "nueva"
function mostrarNuevaCategoria(select) {
    const caja = document.getElementById("caja_nueva_categoria");
    const campo = document.getElementById("nueva_categoria");

    if (select.value == "nueva") {
        caja.hidden = false;
        campo.required = true;
        campo.focus();
    } else {
        caja.hidden = true;
        campo.required = false;
        campo.value = "";
    }
}
