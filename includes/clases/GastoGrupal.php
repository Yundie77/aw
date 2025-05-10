<?php
namespace es\ucm\fdi\aw;

class GastoGrupal {
    public $grupo_id;
    public $usuario_id;
    public $usuario_nombre;
    public $monto;
    public $fecha;
    public $comentario;

    public function __construct($row) {
        $this->grupo_id = $row['grupo_id'];
        $this->usuario_id = $row['usuario_id'];
        $this->usuario_nombre = $row['nombre'] ?? null; // Por si haces un JOIN con usuarios
        $this->monto = $row['monto'];
        $this->fecha = $row['fecha'];
        $this->comentario = $row['comentario'];
    }
}
?>
