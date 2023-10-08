// Al cargar la página
$(document).ready(function () {
    // Al enviar el formulario de búsqueda
    $("#buscar-form").submit(function (event) {
        // Evitar el comportamiento por defecto
        event.preventDefault();
        // Obtener el valor del campo de texto
        var pais = $("#pais").val();
        // Mostrar el valor del campo de texto en la consola del navegador
        console.log(pais);
        // Enviar una petición al servidor con el nombre del país
        $.ajax({
            url: "buscar.php",
            type: "POST",
            data: {pais: pais},
            dataType: "json",
            success: function (response) {
                // Si se recibió una respuesta exitosa
                // Mostrar el resultado en la consola del navegador
                console.log(response);
                // Vaciar el div del resultado
                $("#resultado").empty();
                // Si se encontró el país
                if (response.encontrado) {
                    // Mostrar su información en una tabla
                    var tabla = $("<table></table>").addClass("table table-striped");
                    tabla.append($("<tr></tr>").append($("<th></th>").text("Nombre")).append($("<td></td>").text(response.nombrePais)));
                    tabla.append($("<tr></tr>").append($("<th></th>").text("Extension")).append($("<td></td>").text(response.extensionPais)));
                    tabla.css("color", "white");
                    tabla.css("font-size", "40px")
                    $("#resultado").append(tabla);
                } else {
                    // Mostrar un formulario para agregar el país
                    var form = $("<form></form>").attr("id", "agregar-form");
                    form.append($("<div></div>").addClass("form-group").append($("<label></label>").attr("for", "nombre").text("Nombre")).append($("<input>").attr("type", "text").addClass("form-control").attr("id", "nombre").attr("name", "nombre").attr("placeholder", "Ejemplo: Bolivia").attr("required", true).val(pais)));
                    form.append($("<div></div>").addClass("form-group").append($("<label></label>").attr("for", "extension").text("Extension")).append($("<input>").attr("type", "text").addClass("form-control").attr("id", "extension").attr("name", "extension").attr("placeholder", "Ejemplo: 123456").attr("required", true)));
                    form.append($("<button></button>").attr("type", "submit").addClass("btn btn-success").text("Agregar"));
                    form.css("color", "white");
                    $("#resultado").append(form);
                }
            },
            error: function (error) {
                // Si se recibió un error
                // Mostrar un mensaje de alerta
                alert(error.responseText);
            }
        });
    });

    // Al enviar el formulario de agregar
    $(document).on("submit", "#agregar-form", function (event) {
        // Evitar el comportamiento por defecto
        event.preventDefault();
        // Obtener los valores de los campos de texto
        var nombre = $("#nombre").val();
        var extension = $("#extension").val();
        // Enviar una petición al servidor con los datos del país
        $.ajax({
            url: "agregar.php",
            type: "POST",
            data: {nombre: nombre, extension: extension},
            dataType: "json",
            success: function (response) {
                // Si se recibió una respuesta exitosa
                // Vaciar el div del resultado
                $("#resultado").empty();
                // Mostrar un mensaje de éxito
                $("#resultado").append($("<p></p>").addClass("alert alert-success").text(response.mensaje));
            },
            error: function (error) {
                // Si se recibió un error
                // Mostrar un mensaje de alerta
                alert(error.responseText);
            }
        });
    });
});
