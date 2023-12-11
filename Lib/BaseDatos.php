<?php

namespace Lib;

use PDO;
use PDOException;

class BaseDatos
{
    private $conexion;
    private string $servidor;
    private string $usuario;
    private string $pass;
    private string $base_datos;
    private mixed $resultado;

    function __construct()
    {
        $this->servidor = $_ENV['DB_HOST'];
        $this->usuario = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->base_datos = $_ENV['DB_DATABASE'];
        $this->conexion = $this->conectar();
    }

    private function conectar(): PDO
    {
        try {
            $opciones = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES Utf8",
                PDO::MYSQL_ATTR_FOUND_ROWS => true
            );

            $conexion = new PDO("mysql:host={$this->servidor};dbname={$this->base_datos}", $this->usuario, $this->pass, $opciones);
            return $conexion;
        } catch (\PDOException $e) {
            echo "Ha surgido un error y no se puede conectar a la base de datos. Detalle: " . $e->getMessage();
            exit;
        }
    }

    public function consulta(string $consultasQL): void
    {
        $this->resultado = $this->conexion->query($consultasQL);
    }

    public function extraer_registro(): mixed
    {
        return isset($this->resultado) ? $this->resultado->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function extraer_todos(): array
    {
        return isset($this->resultado) ? $this->resultado->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function filasAfectadas(): int
    {
        return isset($this->resultado) ? $this->resultado->rowCount() : 0;
    }

    public function cierraConexion()
    {
        $this->conexion = null;
    }

    public function close()
    {
        if ($this->conexion !== null) {
            $this->conexion = null;
        }
    }

    public function prepara($pre)
    {
        return $this->conexion->prepare($pre);
    }
}
