<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Categorias;
use es\ucm\fdi\aw\Gastos;

class FormularioGasto extends Formulario {
    public function __construct() {
        parent::__construct('formGasto', [
            'action' => Aplicacion::getInstance()->resuelve(RUTA_APP . 'gastos.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve(RUTA_APP . 'gastos.php'),
            'class' => 'formulario-gasto'
        ]);
    }
    
    protected $errores = [];
    
    protected function generaCamposFormulario(&$datos) {
        // Funcion proporcioanada por chatGPT: explicada en gastos.php
        ob_start();
        $conn = Aplicacion::getInstance()->getConexionBd();
        $user_id = $_SESSION['user_id'];
        $categoriasObj = new Categorias($conn);
        $categorias = $categoriasObj->getAll($user_id);
        ?>
        <div class="form-group form-row">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required max="9999-12-31" value="<?php echo date('Y-m-d'); ?>">
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
                <?php foreach ($categorias as $cat): ?>
                  <option value="<?php echo $cat['id']; ?>">
                    <?php echo htmlspecialchars($cat['nombre']); ?>
                  </option>
                <?php endforeach; ?>
                <option value="otra">Crear nueva categoría</option>
            </select>
            <input type="text" name="categoria_nueva" id="categoriaNueva" placeholder="Escribe nueva categoría" style="display:none;">
            <script src="<?= RUTA_JS ?>categorias.js"></script>
            
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
        // Funcion proporcioanada por chatGPT: explicada en gastos.php
        return ob_get_clean();
    }
    
    protected function procesaFormulario(&$datos) {
        $this->errores = [];
        $conn = Aplicacion::getInstance()->getConexionBd();

        $user_id = $_SESSION['user_id'];
        $tipo = $datos['tipo'];
        $monto = floatval($datos['monto']);
        $fecha = $datos['fecha'];
        $comentario = $datos['comentario'] ?? '';

        // Revisamos la categoría elegida
        $categoria_id = $datos['categoria_id'] ?? '';

        if ($categoria_id === 'otra') {
            $categoriaNueva = trim($datos['categoria_nueva'] ?? '');
            if ($categoriaNueva !== '') {
                $categoria_id = Categorias::create($categoriaNueva, $user_id);
                if (!$categoria_id) {
                    $this->errores['general'] = "Error al crear la nueva categoría.";
                    return;
                }
            } else {
                $this->errores['categoria_nueva'] = "No se ha especificado una nueva categoría.";
                return;
            }
        } else {
            $categoria_id = intval($categoria_id);
        }

        $gastosObj = new Gastos($conn);
        $result = $gastosObj->insertarGasto($user_id, $tipo, $categoria_id, $monto, $fecha, $comentario);

        if (!$result) {
            $this->errores['general'] = "Error al registrar el gasto.";
        }
    }
}
?>
