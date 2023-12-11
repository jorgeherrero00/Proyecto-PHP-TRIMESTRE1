<?php

namespace Controllers;

/**
 * Controlador frontal que maneja las solicitudes y dirige el flujo de la aplicación.
 */
class FrontController
{
    /**
     * Método principal que se encarga de enrutar las solicitudes a los controladores y acciones correspondientes.
     */
    public static function main()
    {
        if (isset($_GET['controller'])) {
            $nombre_controlador = "Controllers\\" . $_GET["controller"] . "Controller";
        } else {
            $nombre_controlador = 'Controllers\\' . CONTROLLER_DEFAULT;
        }

        if (class_exists($nombre_controlador)) {
            $controlador = new $nombre_controlador();

            if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                $action = $_GET['action'];
                $controlador->$action();
            } elseif (!isset($_GET['controller']) && !isset($_GET['action'])) {
                $action_default = ACTION_DEFAULT;
                $controlador->$action_default();
            } else {
                echo ErrorController::show_error404();
            }
        } else {
            echo ErrorController::show_error404();
        }
    }
}

?>
