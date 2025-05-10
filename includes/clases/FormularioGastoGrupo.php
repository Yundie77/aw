<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\GastosGrupales;

class FormularioGastoGrupo extends Formulario {
    private $grupo_id;

    public function __construct($grupo_id) {
        $this->grupo_id = $grupo_id;
        parent::__construct('formGastoGrupo', [
            'action' => Aplicacion::getInstance()->resuelve(RUTA_APP . 'grupo_detalles.php?id=' . $grupo_id),
            'class' => 'formulario-gasto-grupo'
        ]);
    }

    protected function generaCamposFormulario(&$datos) {
        ob_start();
        ?>
        <input type="hidden" name="grupo_id" value="<?= $this->grupo_id ?>">
        <div class="form-group form-row">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required max="9999-12-31" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group form-row">
            <label for="monto">Monto (€):</label>
            <input type="number" name="monto" step="0.01" required min="0">
        </div>
        <div class="form-group form-row">
            <label for="comentario">Comentario:</label>
            <textarea name="comentario"></textarea>
        </div>
        <div class="form-group form-row submit-group">
            <button type="submit" class="btn btn-green">Registrar</button>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
    $this->errores = [];

    $conn = Aplicacion::getInstance()->getConexionBd();
    $usuario_id = $_SESSION['user_id'];
    $grupo_id = $this->grupo_id;
    $monto = floatval($datos['monto'] ?? 0);
    $fecha = $datos['fecha'] ?? '';
    $comentario = trim($datos['comentario'] ?? '');

    if ($monto <= 0) {
        $this->errores['monto'] = "El monto debe ser mayor a 0.";
    }

    if (empty($fecha)) {
        $this->errores['fecha'] = "La fecha es obligatoria.";
    }

    if (count($this->errores) === 0) {
        $gastos = new GastosGrupales($conn);
        $exito = $gastos->insertarGastoGrupal($grupo_id, $usuario_id, $monto, $fecha, $comentario);

        if (!$exito) {
            $this->errores['general'] = "No se pudo registrar el gasto.";
        } else {
            // REDIRECCIÓN para evitar reenvío doble
            \es\ucm\fdi\aw\Aplicacion::getInstance()->redirige("grupo_detalles.php?id=" . $grupo_id);
            exit();
        }
    }
}

}
?>
