<?php

namespace Controllers;

/**
 * Controlador para manejar errores.
 */
class ErrorController
{
    /**
     * Muestra un mensaje de error 404.
     *
     * @return string Mensaje de error 404.
     */
    public static function show_error404(): string
    {
        return "<p>La página que buscas no existe</p>";
    }
}

?>
