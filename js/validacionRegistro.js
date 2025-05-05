<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            // Usuario ya existe
            $("#userMal").show();
            $("#userOK").hide();
            alert("El nombre de usuario ya está reservado.");
        } else {
            // Usuario disponible
            $("#userMal").hide();
            $("#userOK").show();
        }
    }
}
