<?php

namespace Controllers;

use Lib\Pages;
use Models\ComentariosRutas;
use Models\Rutas;

/**
 * Controlador para gestionar los comentarios de las rutas.
 */
class ComentariosRutasController
{
    /**
     * @var ComentariosRutas Modelo de ComentariosRutas.
     */
    private $comentariosRutas;

    /**
     * @var Pages Instancia de la clase Pages para el manejo de las páginas.
     */
    private Pages $pages;

    /**
     * @var Rutas Modelo de Rutas.
     */
    private $ruta;

    /**
     * Constructor de la clase ComentariosRutasController.
     */
    public function __construct()
    {
        $this->comentariosRutas = new ComentariosRutas();
        $this->pages = new Pages();
        $this->ruta = new Rutas();
    }

    /**
     * Muestra la página para crear comentarios y procesa el formulario para agregar comentarios.
     *
     * @return mixed Retorna el resultado del método insertarComentario.
     */
    public function crearComentario()
    {
        $ruta_id = isset($_GET['ruta_id']) ? intval($_GET['ruta_id']) : null;
        $ruta_id = intval($ruta_id);
        $rutaDetalles = $this->ruta->obtenerDetallesRuta($ruta_id);

        $this->pages->render('ruta/comentarRuta', ['Ruta' => $rutaDetalles]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && isset($_POST['texto'])) {
            $nombre = $_POST['nombre'];
            $texto = $_POST['texto'];
            $id = intval($_POST['Ruta']);

            // Verificar si el nombre contiene números
            if (preg_match('/[0-9]/', $nombre)) {
                // El nombre contiene números, mostrar un mensaje y no realizar la inserción
                echo "<p class='error-message'>El nombre no puede contener números.</p>";
            } else {
                // Obtener el nombre de usuario de tu sistema de autenticación

                $resultado = $this->comentariosRutas->insertarComentario($nombre, $id, $texto, $nombre);
                return $resultado;
            }
        }

        $this->mostrarComentarios($ruta_id);
    }

    /**
     * Muestra los comentarios de una ruta específica.
     *
     * @param int|null $ruta_id ID de la ruta.
     */
    public function mostrarComentarios($ruta_id)
    {
        if ($ruta_id !== null) {
            $comentario = $this->comentariosRutas->obtenerComentariosPorRuta($ruta_id);

            if ($comentario !== false) {
                // Renderizar la vista con los comentarios
                $this->pages->render('ruta/mostrarComentarios', ['comentarios' => $comentario]);
            } else {
                echo "Error al obtener los comentarios de la ruta.";
            }
        } else {
            echo "ID de ruta no especificado.";
        }
    }
}

?>
