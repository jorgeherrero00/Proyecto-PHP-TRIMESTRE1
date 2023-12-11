<?php

namespace Models;

use Lib\BaseDatos;
use PDO;

/**
 * Clase ComentariosRutas para manejar operaciones relacionadas con comentarios en rutas.
 */
class ComentariosRutas
{
    /**
     * @var BaseDatos Instancia de la clase BaseDatos para interactuar con la base de datos.
     */
    private $db;

    /**
     * Constructor de la clase ComentariosRutas.
     */
    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    /**
     * Método para insertar comentarios de rutas.
     *
     * @param string $nombre           Nombre del usuario que realiza el comentario.
     * @param int    $idRuta           ID de la ruta a la que se agrega el comentario.
     * @param string $texto            Texto del comentario.
     * @param string $nombreUsuario    Nombre del usuario que realiza el comentario.
     *
     * @return bool Retorna true si la inserción fue exitosa, false si falló.
     */
    public function insertarComentario($nombre, $idRuta, $texto, $nombreUsuario)
    {
        try {
            // Verificar si el usuario ya ha comentado en la ruta en el mismo día
            $stmtVerif = $this->db->prepara('SELECT * FROM rutas_comentarios WHERE id_Ruta = :idRuta AND nombre = :nombreUsuario AND DATE(fecha) = CURDATE()');
            $stmtVerif->bindValue(':idRuta', $idRuta, PDO::PARAM_INT);
            $stmtVerif->bindValue(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
            $stmtVerif->execute();

            if ($stmtVerif->rowCount() > 0) {
                // El usuario ya ha comentado en esta ruta hoy, mostrar un mensaje y no realizar la inserción
                echo "<p class='error-message'>Ya has comentado en esta ruta hoy.</p>";
                return false;
            }

            // La ruta existe y el usuario no ha comentado hoy, ahora insertar el comentario
            $fecha = date('Y-m-d H:i:s');

            $insert = $this->db->prepara('INSERT INTO rutas_comentarios (nombre, id_Ruta, fecha, texto) VALUES (:nombre, :idRuta, :fecha, :texto)');
            $insert->bindValue(':nombre', $nombre);
            $insert->bindValue(':idRuta', $idRuta, PDO::PARAM_INT);
            $insert->bindValue(':fecha', $fecha);
            $insert->bindValue(':texto', $texto);
            $insert->execute();
            $insert->closeCursor();

            $stmtVerif->closeCursor();
            $this->db->close();

            return true;
        } catch (PDOException $err) {
            // Puedes manejar el error de una manera más adecuada si es necesario
            echo $err->getMessage();
            return false;
        }
    }

    /**
     * Método para obtener comentarios por ID de ruta.
     *
     * @param int $ruta_id ID de la ruta.
     *
     * @return array|false Retorna un array de comentarios si la operación fue exitosa, false si falló.
     */
    public function obtenerComentariosPorRuta($ruta_id)
    {
        try {
            $stmt = $this->db->prepara('SELECT * FROM rutas_comentarios WHERE id_Ruta = :idRuta');
            $stmt->bindValue(':idRuta', $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt->closeCursor();
            $this->db->close();

            return $comentarios;
        } catch (PDOException $err) {
            // Puedes manejar el error de una manera más adecuada si es necesario
            echo $err->getMessage();
            return false;
        }
    }

    /**
     * Método para eliminar comentarios por ID de ruta.
     *
     * @param int $ruta_id ID de la ruta.
     */
    public function eliminarComentariosPorRuta($ruta_id)
    {
        try {
            $stmt = $this->db->prepara('DELETE FROM rutas_comentarios WHERE id_Ruta = :idRuta');
            $stmt->bindValue(':idRuta', $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();
            $this->db->close();
        } catch (PDOException $err) {
            // Puedes manejar el error de una manera más adecuada si es necesario
            echo $err->getMessage();
        }
    }
}

?>
