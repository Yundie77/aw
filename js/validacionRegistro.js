$(document).ready(function () {

    // Oculta los iconos al cargar la página
    $("#correoOK").hide(); // Oculta el mensaje de correo válido
    $("#correoMal").hide(); // Oculta el mensaje de correo inválido
    $("#userOK").hide(); // Oculta el mensaje de usuario válido
    $("#userMal").hide(); // Oculta el mensaje de usuario inválido
    $("#passwordOK").hide(); // Oculta el mensaje de contraseña válida
    $("#passwordMal").hide(); // Oculta el mensaje de contraseña inválida
    $("#confirmPasswordOK").hide(); // Oculta el mensaje de confirmación de contraseña válida
    $("#confirmPasswordMal").hide(); // Oculta el mensaje de confirmación de contraseña inválida

    // Validación del correo
    $("#campoEmail").change(function () {
        const campo = $("#campoEmail"); // referencia al campo
        campo[0].setCustomValidity(""); // limpia validaciones previas

        // validación HTML5 y dominio con '@'
        const esCorreoValido = campo[0].checkValidity();
        if (esCorreoValido && correoValido(campo.val())) {
            // Se realiza la comprobación AJAX para ver si el correo ya está registrado
            $.get("comprobarCorreo.php?email=" + campo.val(), function (data, status) {
                if (status === "success" && data.trim() === "existe") {
                    // Correo ya registrado: muestra el icono de error y establece un mensaje de validación
                    $("#correoOK").hide();
                    $("#correoMal").show();
                    campo[0].setCustomValidity("El correo ya está registrado.");
                } else {
                    // Correo disponible: muestra el icono de validación y limpia el mensaje de error
                    $("#correoOK").show();
                    $("#correoMal").hide();
                    campo[0].setCustomValidity("");
                }
            });
        } else {
            // Correo inválido: muestra el icono de error y establece mensaje
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

    // Añadir validación y manejo de iconos para el campo de contraseña
    $("#campoPassword").change(function () {
        const campo = $("#campoPassword");
        campo[0].setCustomValidity("");
        if (campo.val().trim() !== "") {
            $("#passwordOK").show();
            $("#passwordMal").hide();
        } else {
            $("#passwordOK").hide();
            $("#passwordMal").show();
            campo[0].setCustomValidity("La contraseña no puede estar vacía.");
            campo[0].reportValidity();
        }
    });

    // Nuevo: Manejador para confirm_password
    $("#confirm_password").change(function () {
        const campo = $("#confirm_password");
        const password = $("#campoPassword");
        campo[0].setCustomValidity(""); // limpiar validaciones previas
        if (campo.val().trim() === "") {
            $("#confirmPasswordOK").hide();
            $("#confirmPasswordMal").show();
            campo[0].setCustomValidity("Debes confirmar la contraseña.");
            campo[0].reportValidity();
        } else if (campo.val() !== password.val()) {
            $("#confirmPasswordOK").hide();
            $("#confirmPasswordMal").show();
            campo[0].setCustomValidity("Las contraseñas no coinciden.");
            campo[0].reportValidity();
        } else {
            $("#confirmPasswordOK").show();
            $("#confirmPasswordMal").hide();
        }
    });

    $("form").submit(function (event) {
        const campoUser = $("#campoUser");
        const campoEmail = $("#campoEmail");
        const campoPassword = $("#campoPassword");
        const campoConfirmPassword = $("#confirm_password");

        let valido = true;

        // Validación de campo usuario vacío
        if (campoUser.val().trim() === "") {
            campoUser[0].setCustomValidity("El nombre de usuario no puede estar vacío.");
            campoUser[0].reportValidity();
            valido = false;
        } else {
            campoUser[0].setCustomValidity("");
        }

        // Validación de campo correo vacío
        if (campoEmail.val().trim() === "") {
            campoEmail[0].setCustomValidity("El correo no puede estar vacío.");
            campoEmail[0].reportValidity();
            valido = false;
        } else {
            campoEmail[0].setCustomValidity("");
        }

        // Limpiar mensajes anteriores para contraseñas
        campoPassword[0].setCustomValidity("");
        campoConfirmPassword[0].setCustomValidity("");

        // Validación de campo vacío contraseña
        if (campoPassword.val().trim() === "") {
            campoPassword[0].setCustomValidity("La contraseña no puede estar vacía.");
            campoPassword[0].reportValidity();
            valido = false;
        }
        // Validación de confirmación vacía
        else if (campoConfirmPassword.val().trim() === "") {
            campoConfirmPassword[0].setCustomValidity("Debes confirmar la contraseña.");
            campoConfirmPassword[0].reportValidity();
            valido = false;
        }
        // Validación de contraseñas que no coinciden
        else if (campoPassword.val() !== campoConfirmPassword.val()) {
            campoConfirmPassword[0].setCustomValidity("Las contraseñas no coinciden.");
            campoConfirmPassword[0].reportValidity();
            valido = false;
        }

        if (!valido) {
            event.preventDefault(); // Cancela el envío del formulario si hay error
        }
    });

    $("#campoPassword").on("input", function () {
        this.setCustomValidity("");
    });
    
    $("#confirm_password").on("input", function () {
        this.setCustomValidity("");
    });
    
    
});
