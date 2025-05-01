function cargarUsuarios() {
  $.get("usuarios_listar.php", function (data) {
    $("#usuarios-container").html(data);
  });
}

function toggleEstado(id) {
  $.post("usuarios_toggle_estado.php", { id: id }, function () {
    cargarUsuarios();
  }, "json");
}

function bloquearUsuario(id) {
  $.post("usuarios_bloquear.php", { id: id }, function () {
    cargarUsuarios();
  }, "json");
}

$(document).ready(function () {
  cargarUsuarios();

  $(document).on('click', '.btn-toggle-estado', function () {
    const id = $(this).data("id");
    toggleEstado(id);
  });

  $(document).on('click', '.btn-bloquear', function () {
    const id = $(this).data("id");
    bloquearUsuario(id);
  });

  $(document).on("keyup", "#buscarUsuario", function () {
    const filtro = $(this).val().toLowerCase();
    $("#tablaUsuarios tbody tr").each(function () {
      const textoFila = $(this).text().toLowerCase();
      $(this).toggle(textoFila.includes(filtro));
    });
  });
});
