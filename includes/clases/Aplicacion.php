<?php
namespace es\ucm\fdi\aw;

class Aplicacion
{
    private static $instancia;
    private $bdDatosConexion;
    private $inicializada = false;
    private $conn;
    private $rutaRaizApp;
    private $dirInstalacion;

    private const ATRIBUTOS_PETICION = 'attsPeticion';

    private function __construct() {}

    public static function getInstance()
    {
        if (!self::$instancia instanceof self) {
            self::$instancia = new static();
        }
        return self::$instancia;
    }

    public function init($bdDatosConexion, $rutaApp = '/', $dirInstalacion = __DIR__)
    {
        if (!$this->inicializada) {
            $this->bdDatosConexion = $bdDatosConexion;

            $this->rutaRaizApp = $rutaApp;

            // Eliminamos la última /
            $tamRutaRaizApp = mb_strlen($this->rutaRaizApp);
            if ($tamRutaRaizApp > 0 && mb_substr($this->rutaRaizApp, $tamRutaRaizApp-1, 1) === '/') {
                $this->rutaRaizApp = mb_substr($this->rutaRaizApp, 0, $tamRutaRaizApp - 1);
            }

            // El último separador de la ruta (ya sea el separador específico del sistema o /)
            $this->dirInstalacion = $dirInstalacion;
            $tamDirInstalacion = mb_strlen($this->dirInstalacion);
            if ($tamDirInstalacion > 0) {
                $ultimoChar = mb_substr($this->dirInstalacion, $tamDirInstalacion-1, 1);
                if ($ultimoChar === DIRECTORY_SEPARATOR || $ultimoChar === '/') {
                    $this->dirInstalacion = mb_substr($this->dirInstalacion, 0, $tamDirInstalacion - 1);
                }
            }

            $this->conn = null;
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            /* Se inicializa los atributos asociados a la petición en base a la sesión y se eliminan para que
            * no estén disponibles después de la gestión de esta petición.
            */
            $this->atributosPeticion = $_SESSION[self::ATRIBUTOS_PETICION] ?? [];
            unset($_SESSION[self::ATRIBUTOS_PETICION]);

            $this->inicializada = true;
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

            $conn = new \mysqli($bdHost, $bdUser, $bdPass, $bd);
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
