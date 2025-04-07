<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Formulario;

class FormularioEditarGasto extends Formulario {
    public function __construct() {
        parent::__construct('formEditarGasto', [
            'action' => Aplicacion::getInstance()->resuelve(RUTA_APP . 'actualizar_gasto.php'),
            'class' => 'formulario-editar-gasto'
        ]);
    }

    protected function generaCamposFormulario(&$datos) {
        $id = htmlspecialchars($datos['id'] ?? '');
        $tipo = htmlspecialchars($datos['tipo'] ?? '');
        $monto = htmlspecialchars($datos['monto'] ?? '');
        $fecha = htmlspecialchars($datos['fecha'] ?? '');
        $comentario = htmlspecialchars($datos['comentario'] ?? '');

        ob_start();
        ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="edit-tipo">Tipo:</label>
            <select name="tipo" id="edit-tipo" required>
                <option value="Ingreso" <?= $tipo === 'Ingreso' ? 'selected' : '' ?>>Ingreso</option>
                <option value="Gasto" <?= $tipo === 'Gasto' ? 'selected' : '' ?>>Gasto</option>
            </select>
        </div>
        <div class="form-group">
            <label for="edit-monto">Monto (€):</label>
            <input type="number" name="monto" id="edit-monto" step="0.01" required min="0" value="<?= $monto ?>">
        </div>
        <div class="form-group">
            <label for="edit-fecha">Fecha:</label>
            <input type="date" name="fecha" id="edit-fecha" required max="9999-12-31" value="<?= $fecha ?>">
        </div>
        <div class="form-group">
            <label for="edit-comentario">Comentario:</label>
            <textarea name="comentario" id="edit-comentario"><?= $comentario ?></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-green">Actualizar</button>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $id = intval($datos['id']);
        $tipo = trim($datos['tipo']);
        $monto = floatval($datos['monto']);
        $fecha = trim($datos['fecha']);
        $comentario = trim($datos['comentario']);

        if ($id <= 0 || empty($tipo) || $monto <= 0 || empty($fecha)) {
            $this->errores['general'] = "Datos inválidos.";
            return;
        }

        $gastos = new Gastos($conn);
        if (!$gastos->actualizarGasto($id, $_SESSION['user_id'], $tipo, $monto, $fecha, $comentario)) {
            $this->errores['general'] = "Error al actualizar el gasto.";
        }
    }
}
?>
