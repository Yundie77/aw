<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Formulario;

class FormularioEditarGastoGrupal extends Formulario {
    private $grupo_id;

    public function __construct($grupo_id) {
        $this->grupo_id = $grupo_id;
        parent::__construct('formEditarGastoGrupal', [
            'action' => Aplicacion::getInstance()->resuelve(RUTA_APP . 'actualizar_gasto_grupal.php?grupo_id=' . $grupo_id),
            'class' => 'formulario-editar-gasto'
        ]);
    }

    protected function generaCamposFormulario(&$datos) {
        // Campos inicialmente vacíos, serán rellenados desde JS
        ob_start();
        ?>
        <input type="hidden" name="id" value="">
        <input type="hidden" name="grupo_id" value="<?= $this->grupo_id ?>">
        <div class="form-group">
            <label for="edit-monto">Monto (€):</label>
            <input type="number" name="monto" id="edit-monto" step="0.01" required min="0" value="">
        </div>
        <div class="form-group">
            <label for="edit-fecha">Fecha:</label>
            <input type="date" name="fecha" id="edit-fecha" required max="9999-12-31" value="">
        </div>
        <div class="form-group">
            <label for="edit-comentario">Comentario:</label>
            <textarea name="comentario" id="edit-comentario"></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-green">Actualizar</button>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $id = intval($datos['id'] ?? 0);
        $monto = floatval($datos['monto'] ?? 0);
        $fecha = trim($datos['fecha'] ?? '');
        $comentario = trim($datos['comentario'] ?? '');
        $usuario_id = $_SESSION['user_id'] ?? null;

        if ($id <= 0 || $monto <= 0 || empty($fecha)) {
            $this->errores['general'] = "Datos inválidos.";
            return;
        }

        $gastos = new GastosGrupales($conn);
        $exito = $gastos->actualizarGastoGrupal($id, $usuario_id, $monto, $fecha, $comentario);

        if (!$exito) {
            $this->errores['general'] = "Error al actualizar el gasto grupal.";
        }
    }
}
