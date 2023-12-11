<?php

namespace Models;

use Lib\BaseDatos;
use PDO;
use Models\ComentariosRutas;

/**
 * Clase Rutas para manejar operaciones relacionadas con rutas.
 */
class Rutas
{
    /**
     * @var int ID de la ruta.
     */
    private $id;

    /**
     * @var string Título de la ruta.
     */
    private $titulo;

    /**
     * @var string Descripción de la ruta.
     */
    private $descripcion;

    /**
     * @var int Desnivel de la ruta.
     */
    private $desnivel;

    /**
     * @var float Distancia de la ruta.
     */
    private $distancia;

    /**
     * @var string Notas adicionales de la ruta.
     */
    private $notas;

    /**
     * @var int Dificultad de la ruta.
     */
    private $dificultad;

    /**
     * @var BaseDatos Instancia de la clase BaseDatos para interactuar con la base de datos.
     */
    private $db;

    /**
     * Constructor de la clase Rutas.
     */
    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    /**
     * Getter for $id.
     *
     * @return int Retorna el ID de la ruta.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter for $id.
     *
     * @param int $id ID de la ruta.
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Getter for $titulo.
     *
     * @return string Retorna el título de la ruta.
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Setter for $titulo.
     *
     * @param string $titulo Título de la ruta.
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * Getter for $descripcion.
     *
     * @return string Retorna la descripción de la ruta.
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Setter for $descripcion.
     *
     * @param string $descripcion Descripción de la ruta.
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Getter for $desnivel.
     *
     * @return int Retorna el desnivel de la ruta.
     */
    public function getDesnivel()
    {
        return $this->desnivel;
    }

    /**
     * Setter for $desnivel.
     *
     * @param int $desnivel Desnivel de la ruta.
     */
    public function setDesnivel($desnivel)
    {
        $this->desnivel = $desnivel;
    }

    /**
     * Getter for $distancia.
     *
     * @return float Retorna la distancia de la ruta.
     */
    public function getDistancia()
    {
        return $this->distancia;
    }

    /**
     * Setter for $distancia.
     *
     * @param float $distancia Distancia de la ruta.
     */
    public function setDistancia($distancia)
    {
        $this->distancia = $distancia;
    }

    /**
     * Getter for $notas.
     *
     * @return string Retorna las notas adicionales de la ruta.
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * Setter for $notas.
     *
     * @param string $notas Notas adicionales de la ruta.
     */
    public function setNotas($notas)
    {
        $this->notas = $notas;
    }

    /**
     * Getter for $dificultad.
     *
     * @return int Retorna la dificultad de la ruta.
     */
    public function getDificultad()
    {
        return $this->dificultad;
    }

    /**
     * Setter for $dificultad.
     *
     * @param int $dificultad Dificultad de la ruta.
     */
    public function setDificultad($dificultad)
    {
        $this->dificultad = $dificultad;
    }


  
    public static function getAll(){

        $ruta = new Rutas();
        $ruta->db->consulta("SELECT * FROM rutas ORDER BY id DESC");
        $rutas = $ruta->db->extraer_todos();
        $ruta->db->cierraConexion();
        return $rutas;
    }

    public function crearRuta($titulo,$descripcion,$desnivel,$distancia,$notas,$dificultad){
        $ruta = new Rutas();
        try {
            $stmt = $ruta->db->prepara('SELECT * FROM rutas where titulo=:titulo');
            $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount()==0){
                $insert = $ruta->db->prepara('INSERT INTO rutas (titulo, descripcion, desnivel, distancia, notas, dificultad) VALUES (:titulo, :descripcion, :desnivel, :distancia, :notas, :dificultad)');
                $insert->bindValue(':titulo', $titulo);
                $insert->bindValue(':descripcion', $descripcion);
                $insert->bindValue(':desnivel', $desnivel);
                $insert->bindValue(':distancia', $distancia);
                $insert->bindValue(':notas', $notas);
                $insert->bindValue(':dificultad', $dificultad);
                $insert->execute();
                $insert->closeCursor();
            }

            $stmt->closeCursor();
            $ruta->db->close();
            $resultado = true;

        } catch (PDOException $err){
            $resultado=false;
        }

        return $resultado;    
    }

    // En el modelo Rutas
public function borrarRuta($id)
{
    // Obtener los comentarios asociados a la ruta
    $comentariosRutas = new ComentariosRutas();
    $comentariosRutas->eliminarComentariosPorRuta($id);

    // Eliminar la ruta
    $stmt = $this->db->prepara('DELETE FROM rutas WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();
    $this->db->close();
}
/**
 * Método para modificar una ruta existente.
 *
 * @param int    $id          ID de la ruta a modificar.
 * @param string $titulo      Nuevo título de la ruta.
 * @param string $descripcion Nueva descripción de la ruta.
 * @param int    $desnivel    Nuevo desnivel de la ruta.
 * @param float  $distancia   Nueva distancia de la ruta.
 * @param string $notas       Nuevas notas de la ruta.
 * @param int    $dificultad  Nueva dificultad de la ruta.
 *
 * @return bool Retorna true si la modificación fue exitosa, false si falló o ya existe otra ruta con el mismo título.
 */
public function modificarRuta($id, $titulo, $descripcion, $desnivel, $distancia, $notas, $dificultad)
{
    /**
     * @var Rutas $ruta Instancia de la clase Rutas.
     */
    $ruta = new Rutas();

    try {
        // Verificar si el nuevo título ya existe en otras rutas
        $stmt = $ruta->db->prepara('SELECT * FROM rutas WHERE titulo = :titulo AND id != :id');
        $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // No hay otras rutas con el mismo título, realizar la actualización
            $update = $ruta->db->prepara('UPDATE rutas SET titulo = :titulo, descripcion = :descripcion, desnivel = :desnivel, distancia = :distancia, notas = :notas, dificultad = :dificultad WHERE id = :id');
            $update->bindValue(':titulo', $titulo);
            $update->bindValue(':descripcion', $descripcion);
            $update->bindValue(':desnivel', $desnivel);
            $update->bindValue(':distancia', $distancia);
            $update->bindValue(':notas', $notas);
            $update->bindValue(':dificultad', $dificultad);
            $update->bindValue(':id', $id);
            $update->execute();

            $update->closeCursor();
            $resultado = true;
        } else {
            // Ya existe otra ruta con el mismo título, mostrar un mensaje o manejar según sea necesario
            echo "<p class='error-message'>Ya existe otra ruta con el mismo título.</p>";
            $resultado = false;
        }

        $stmt->closeCursor();
        $ruta->db->close();
    } catch (PDOException $err) {
        $resultado = false;
    }

    return $resultado;
}

/**
 * Método para obtener los detalles de una ruta por su ID.
 *
 * @param int $rutaId ID de la ruta.
 *
 * @return array|false Retorna un array con los detalles de la ruta o false si falla.
 */
public function obtenerDetallesRuta($rutaId)
{
    try {
        // Utilizar una consulta preparada para evitar inyección de SQL
        $stmt = $this->db->prepara('SELECT * FROM rutas WHERE id = :id');
        $stmt->bindValue(':id', $rutaId, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener los detalles de la ruta
        $detalles = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cerrar el cursor y la conexión
        $stmt->closeCursor();

        return $detalles;
    } catch (PDOException $err) {
        // Manejar errores, por ejemplo, loguear el error o lanzar una excepción
        return false;
    }
}

/**
 * Método para obtener la ruta más larga.
 *
 * @return array|false Retorna un array con los detalles de la ruta más larga o false si falla.
 */
public function getRutaMasLarga()
{
    $stmt = $this->db->prepara('SELECT * FROM rutas ORDER BY distancia DESC LIMIT 1');
    $stmt->execute();
    
    // Obtener el resultado
    $rutaMasLarga = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmt->closeCursor();
    $this->db->cierraConexion();

    return $rutaMasLarga;
}



/**
 * Método para buscar rutas según un término de búsqueda y un campo específico.
 *
 * @param string $searchTerm Término de búsqueda.
 * @param string $field      Campo en el que se realizará la búsqueda (p. ej., 'titulo', 'descripcion').
 *
 * @return array Retorna un array con las rutas encontradas que coinciden con el término de búsqueda y el campo especificado.
 */
public static function buscarRutas($searchTerm, $field)
{
    /**
     * @var Rutas $ruta Instancia de la clase Rutas.
     */
    $ruta = new Rutas();

    $allowedFields = ['titulo', 'descripcion'];  // Add more fields as needed

    // Validate the selected field
    if (!in_array($field, $allowedFields)) {
        // Use a default field if the selected field is not allowed
        $field = 'titulo';
    }

    $stmt = $ruta->db->prepara("SELECT * FROM rutas WHERE $field LIKE :searchTerm");
    $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
    $stmt->execute();

    // Fetch all the results
    $rutasEncontradas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt->closeCursor();
    $ruta->db->cierraConexion();

    return $rutasEncontradas;
}


}

?>
