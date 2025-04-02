<?php
require_once __DIR__ . '/Formulario.php';

class FormularioGasto extends Formulario {
    public function __construct() {
        parent::__construct('formGasto', [
            'urlRedireccion' => 'gastos.php',
            'class' => 'formulario-gasto'
        ]);
    }
    
    protected $errores = [];
    
    protected function generaCamposFormulario(&$datos) {
        ob_start();
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();
        ?>
        <div class="form-group form-row">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="form-group form-row">
            <label for="tipo">Tipo:</label>
            <select name="tipo" required>
                <option value="Ingreso">Ingreso</option>
                <option value="Gasto">Gasto</option>
            </select>
        </div>
        <div class="form-group form-row">
            <label for="categoria_id">Categoría:</label>
            <select name="categoria_id" id="categoriaSelect" required>
                <option value="">-- Seleccione --</option>
                <?php 
                  $sqlCat = "SELECT id, nombre FROM categorias ORDER BY nombre ASC";
                  $resCat = $conn->query($sqlCat);
                  while($cat = $resCat->fetch_assoc()):
                ?>
                  <option value="<?php echo $cat['id']; ?>">
                    <?php echo htmlspecialchars($cat['nombre']); ?>
                  </option>
                <?php endwhile; ?>
                <option value="otra">Crear nueva categoría</option>
            </select>
            <input type="text" name="categoria_nueva" id="categoriaNueva" placeholder="Escribe nueva categoría" style="display:none;">
        </div>
        <div class="form-group form-row">
            <label for="monto">Monto (€):</label>
            <input type="number" name="monto" step="0.01" required min="0">
        </div>
        <div class="form-group form-row">
            <label for="comentario">Comentario:</label>
            <textarea name="comentario"></textarea>
        </div>
        
        <?php
        return ob_get_clean();
    }
    
    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        session_start();
        $app = Aplicacion::getInstance();
        $conn = $app->getConexionBd();

        $user_id = $_SESSION['user_id'];
        $tipo = $datos['tipo'];
        $monto = floatval($datos['monto']);
        $fecha = $datos['fecha'];
        $comentario = $datos['comentario'] ?? '';

        // Validación: evitar años > 9999 o montos negativos
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
