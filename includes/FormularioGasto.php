<?php
require_once __DIR__ . '/Formulario.php';

class FormularioGasto extends Formulario {
    protected $errores = [];
    protected function generaCamposFormulario(&$datos) {
        // Funcion proporcioanada por chatGPT: explicada en gastos.php
        ob_start();
        ?>
        <div class="form-row">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-row">
            <label for="tipo">Tipo:</label>
            <select name="tipo" required>
                <option value="Ingreso">Ingreso</option>
                <option value="Gasto">Gasto</option>
            </select>
        </div>
        <div class="form-row">
            <label for="monto">Monto (€):</label>
            <input type="number" name="monto" step="0.01" required min="0">
        </div>
        <div class="form-row">
            <label for="comentario">Comentario:</label>
            <textarea name="comentario"></textarea>
        </div>
        <?php
        return ob_get_clean();
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        session_start();
        require_once __DIR__ . '/Aplicacion.php';
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();

        $user_id = $_SESSION['user_id'];
        $tipo = $datos['tipo'];
        $monto = floatval($datos['monto']);
        $fecha = $datos['fecha'];
        $comentario = $datos['comentario'] ?? '';

        // Validación: evitar anios > 9999 o montos negativos
        if ($monto < 0 || intval(date('Y', strtotime($fecha))) > 9999) {
            $this->errores['monto'] = "Datos inválidos en fecha o monto.";
            return;
        }

        $sql = "INSERT INTO gastos (usuario_id, tipo, monto, fecha, comentario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdss", $user_id, $tipo, $monto, $fecha, $comentario);

        if (!$stmt->execute()) {
            $this->errores['general'] = "Error al registrar el gasto.";
        }
        $stmt->close();
    }
}
?>
