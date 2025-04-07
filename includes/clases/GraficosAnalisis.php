<?php
namespace es\ucm\fdi\aw;

class GraficosAnalisis {
    private $conn;
    
    public function __construct() {
        $app = Aplicacion::getInstance();
        $this->conn = $app->getConexionBd();
    }
    
    public function getGastosMensuales($user_id) {
        $gastos = new Gastos();
        $gastosPorMes = $gastos->getGastosMensualesPorMes($user_id);
        
        $meses = [];
        $datosGastos = [];
        
        foreach ($gastosPorMes as $dato) {
            $timestamp = mktime(0, 0, 0, $dato['mes'], 1);
            $nombreMes = strftime('%B', $timestamp); 
            $datosGastos[] = $dato['total'];
        }
        
        return [
            'labels' => $meses,
            'datos' => $datosGastos
        ];
    }

    public function getComparacionGastos($user_id) {
        $gastos = new Gastos();
        $gastosPorCategoria = $gastos->getGastosPorCategoria($user_id);
        
        $categorias = [];
        $valoresUsuario = [];
        $valoresPromedio = [];
        
        foreach ($gastosPorCategoria as $dato) {
            $categorias[] = $dato['categoria'];
            $valoresUsuario[] = $dato['total'];
            $valoresPromedio[] = $dato['promedio'];
        }
        
        return [
            'categorias' => $categorias,
            'datosUsuario' => $valoresUsuario,
            'datosPromedio' => $valoresPromedio
        ];
    }
    
    public function getIngresosVsGastos($user_id) {
        $gastos = new Gastos();
        $ingresosGastos = $gastos->getIngresosVsGastos($user_id);
        
        $datosFormateados = [];
        
        foreach ($ingresosGastos as $dato) {
            $timestamp = mktime(0, 0, 0, $dato['mes'], 1);
            $nombreMes = strftime('%B', $timestamp);
            
            $datosFormateados[] = [
                'x' => floatval($dato['ingresos']),
                'y' => floatval($dato['gastos']),
                'label' => $nombreMes
            ];
        }
        
        return $datosFormateados;
    }
    
    public function getGastosPorCategoriaPorMes($user_id, $numMeses = 6) {
        $gastos = new Gastos();
        $gastosPorCategoriaMes = $gastos->getGastosPorCategoriaMes($user_id, $numMeses);

        $mesesBarras = [];
        $categorias = [];
        foreach ($gastosPorCategoriaMes as $dato) {
            if (!in_array($dato['categoria'], $categorias)) {
                $categorias[] = $dato['categoria'];
            }
            
            $timestamp = mktime(0, 0, 0, $dato['mes'], 1);
            $nombreMes = strftime('%B', $timestamp);
            if (!in_array($nombreMes, $mesesBarras)) {
                $mesesBarras[] = $nombreMes;
            }
        }
        
        $datosPorCategoria = [];
        foreach ($categorias as $categoria) {
            $datosPorCategoria[$categoria] = [];
            
            foreach ($mesesBarras as $mes) {
                $datosPorCategoria[$categoria][$mes] = 0;
            }
        }

        foreach ($gastosPorCategoriaMes as $dato) {
            $timestamp = mktime(0, 0, 0, $dato['mes'], 1);
            $nombreMes = strftime('%B', $timestamp);
            $datosPorCategoria[$dato['categoria']][$nombreMes] = floatval($dato['total']);
        }
        
        return [
            'meses' => $mesesBarras,
            'categorias' => $categorias,
            'datos' => $datosPorCategoria
        ];
    }
}
?>