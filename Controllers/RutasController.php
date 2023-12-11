<?php

namespace Controllers;

use Lib\Pages;
use Models\Rutas;

/**
 * Clase RutasController para manejar operaciones relacionadas con las rutas.
 */
class RutasController
{
    /**
     * @var Pages Instancia de la clase Pages para renderizar vistas.
     */
    private Pages $pages;

    /**
     * @var Rutas Instancia de la clase Rutas para interactuar con las rutas.
     */
    private Rutas $nuevaRuta;

    /**
     * Constructor de la clase RutasController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
        $this->nuevaRuta = new Rutas();
    }

    /**
     * Obtiene todas las rutas.
     *
     * @return array Arreglo de rutas.
     */
    public static function obtenerRutas(): array
    {
        return Rutas::getAll();
    }

    /**
     * Crea una nueva ruta.
     */
    public function crearRuta()
    {
        // Comprobar si se han enviado los datos del formulario
        if (isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['desnivel']) && isset($_POST['distancia']) && isset($_POST['notas']) && isset($_POST['dificultad'])) {
            // Obtener los datos del formulario
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $desnivel = $_POST['desnivel'];
            $distancia = $_POST['distancia'];
            $notas = $_POST['notas'];
            $dificultad = $_POST['dificultad'];

            // Verificar si el título es una palabra
            if (!ctype_alpha($titulo)) {
                // El título no es una palabra, mostrar un mensaje y no realizar la inserción
                echo "<p class='error-message'>El título debe ser una palabra.</p>";
            } elseif (!is_numeric($desnivel) || !is_numeric($distancia) || !is_numeric($dificultad)) {
                // Verificar si desnivel, distancia y dificultad son números
                echo "<p class='error-message'>El desnivel, la distancia y la dificultad deben ser números.</p>";
            } else {
                // Convertir a números
                $desnivel = intval($desnivel);
                $distancia = floatval($distancia);
                $dificultad = intval($dificultad);

                // Crear la ruta
                $creada = $this->nuevaRuta->crearRuta($titulo, $descripcion, $desnivel, $distancia, $notas, $dificultad);

                // Verificar si la ruta se creó correctamente
                if ($creada) {
                    // Redirigir a la página principal
                    header("Location: " . BASE_URL . 'index.php'); // Reemplaza BASE_URL con la URL de tu página principal
                    exit();
                } else {
                    // Manejar el caso en que la creación de la ruta falló
                    $_SESSION['create_route_error'] = "No se pudo crear la ruta.";
                }
            }
        }

        // Renderizar la página de creación de ruta
        $this->pages->render('ruta/crearRuta');
    }

    /**
     * Borra una ruta.
     */
    public function borrarRuta()
    {
        if (isset($_POST['ruta_id'])) {
            $rutaId = intval($_POST['ruta_id']);

            // Llamar al método correspondiente en la clase Rutas
            $this->nuevaRuta->borrarRuta($rutaId);
            header('Location: ' . BASE_URL . 'index.php');
        }
    }

    /**
     * Modifica una ruta.
     */
    public function modificarRuta()
    {
        if (isset($_GET['ruta_id'])) {
            $rutaId = $_GET['ruta_id'];
            $rutaDetalles = $this->nuevaRuta->obtenerDetallesRuta($rutaId);

            // Renderizar el formulario de modificación con los detalles de la ruta
            $this->pages->render('ruta/modificarRuta', ['rutaDetalles' => $rutaDetalles]);
        } elseif (isset($_POST['ruta_id']) && isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['desnivel']) && isset($_POST['distancia']) && isset($_POST['notas']) && isset($_POST['dificultad'])) {
            // Realizar conversiones
            $id = $_POST['ruta_id'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $desnivel = intval($_POST['desnivel']);
            $distancia = intval($_POST['distancia']);
            $notas = $_POST['notas'];
            $dificultad = intval($_POST['dificultad']);

            // Llamar al método de modificarRuta en la clase Rutas
            $resultado = $this->nuevaRuta->modificarRuta($id, $titulo, $descripcion, $desnivel, $distancia, $notas, $dificultad);

            // Manejar el resultado según sea necesario (por ejemplo, mostrar un mensaje de éxito o error)
            if ($resultado) {
                // Éxito, redirigir a la página principal con un mensaje de éxito
                header("Location: " . BASE_URL . "?success=1");
                exit();
            } else {
                // Error, redirigir a la página principal con un mensaje de error
                header("Location: " . BASE_URL . "?error=1");
                exit();
            }
        }
    }

    /**
     * Muestra todas las rutas.
     */
    public function mostrarTodasLasRutas()
    {
        $todasLasRutas = Rutas::getAll();

        // Renderiza la nueva vista con todas las rutas
        $this->pages->render('ruta/mostrarRuta', ['todasLasRutas' => $todasLasRutas]);
    }

    /**
     * Obtiene la ruta más larga.
     *
     * @return mixed La ruta más larga.
     */
    public static function obtenerRutaMasLarga()
    {
        $ruta = new Rutas();
        $rutaMasLarga = $ruta->getRutaMasLarga();

        return $rutaMasLarga;
    }

    /**
     * Busca rutas.
     */
    public function buscarRutas()
    {
        // Verificar si se ha enviado el formulario de búsqueda
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
            // Sanitizar la entrada del usuario
            $searchTerm = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
            $field = $_POST['field'] ?? 'titulo';

            // Realizar la búsqueda
            $rutasEncontradas = Rutas::buscarRutas($searchTerm, $field);

            // Renderizar la vista con los resultados de la búsqueda
            $this->pages->render('ruta/resultadosBusqueda', ['rutasEncontradas' => $rutasEncontradas]);
        } else {
            // Renderizar el formulario de búsqueda
            $this->pages->render('ruta/buscarRutas');
        }
    }
}

?>
