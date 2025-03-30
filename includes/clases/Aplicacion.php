<?php

class Aplicacion
{
    private static $instancia;
    private $bdDatosConexion;
    private $inicializada = false;
    private $conn;

    private function __construct() {}

    public static function getInstance()
    {
        if (!self::$instancia instanceof self) {
            self::$instancia = new static();
        }
        return self::$instancia;
    }

    public function init($bdDatosConexion)
    {
        if (!$this->inicializada) {
            $this->bdDatosConexion = $bdDatosConexion;
            $this->inicializada = true;
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
        }
    }

    public function getConexionBd()
    {
        if (!$this->inicializada) {
            echo "Aplicación no inicializada.";
            exit();
        }

        if (!$this->conn) {
            $bdHost = $this->bdDatosConexion['host'];
            $bdUser = $this->bdDatosConexion['user'];
            $bdPass = $this->bdDatosConexion['pass'];
            $bd = $this->bdDatosConexion['bd'];

            $conn = new mysqli($bdHost, $bdUser, $bdPass, $bd);
            if ($conn->connect_errno) {
                echo "Error de conexión a la BD ({$conn->connect_errno}): {$conn->connect_error}";
                exit();
            }
            if (!$conn->set_charset("utf8mb4")) {
                echo "Error al configurar la BD ({$conn->errno}): {$conn->error}";
                exit();
            }
            $this->conn = $conn;
        }
        return $this->conn;
    }
}
