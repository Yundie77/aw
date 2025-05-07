$(document).ready(function () {


    // Oculta los iconos al cargar la página
    $("#correoOK").hide(); // Oculta el mensaje de correo válido
    $("#correoMal").hide(); // Oculta el mensaje de correo inválido
    $("#userOK").hide(); // Oculta el mensaje de usuario válido
    $("#userMal").hide(); // Oculta el mensaje de usuario inválido

    // Validación del correo
    $("#campoEmail").change(function () {
        const campo = $("#campoEmail"); // referencia al campo
        campo[0].setCustomValidity(""); // limpia validaciones previas

        // validación HTML5 y dominio con '@'
        const esCorreoValido = campo[0].checkValidity();
        if (esCorreoValido && correoValido(campo.val())) {
            // Correo válido
            $("#correoOK").show();
            $("#correoMal").hide();
            campo[0].setCustomValidity(""); // válido
        } else {
            // Correo inválido
            $("#correoOK").hide();
            $("#correoMal").show();
        

            campo[0].setCustomValidity("El correo debe contener '@'.");
        }
    });

    function correoValido(correo) {
        return correo.includes("@");
    }

    // Validación del usuario con AJAX
    $("#campoUser").change(function () {
        const url = "comprobarUsuario.php?user=" + $("#campoUser").val();
        $.get(url, usuarioExiste);
    });

    function usuarioExiste(data, status) {
        if (status === "success") {
            if (data.trim() === "existe") {
                // Usuario ya existe: muestra el icono de error y establece un mensaje de validación
                $("#userMal").show();
                $("#userOK").hide();
                $("#campoUser")[0].setCustomValidity("El nombre de usuario ya está reservado.");
            } else {
                // Usuario disponible: muestra el icono de validación y limpia el mensaje de error
                $("#userMal").hide();
                $("#userOK").show();
                $("#campoUser")[0].setCustomValidity("");
            }
        }
    }
});
