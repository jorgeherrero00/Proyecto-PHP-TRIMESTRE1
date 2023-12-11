<?php

namespace Controllers;

use Models\Usuario;
use Lib\Pages;
use Utils\Utils;

/**
 * Clase UsuarioController para manejar operaciones relacionadas con usuarios.
 */
class UsuarioController
{
    /**
     * @var Pages Instancia de la clase Pages para renderizar vistas.
     */
    private Pages $pages;

    /**
     * Constructor de la clase UsuarioController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Método para registrar un nuevo usuario.
     */
    public function registro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['data'])) {
                $registrado = $_POST['data'];

                // Hash de la contraseña usando Bcrypt
                $registrado['password'] = password_hash($registrado['password'], PASSWORD_BCRYPT, ['cost' => 4]);

                $usuario = Usuario::fromArray($registrado);

                $save = $usuario->create();

                if ($save) {
                    $_SESSION['register'] = "complete";
                } else {
                    $_SESSION['register'] = "failed";
                }
            } else {
                $_SESSION['register'] = "failed";
            }
        }

        $this->pages->render('/usuario/registro');
    }

    /**
     * Método para mostrar la página de identificación.
     */
    public function identifica()
    {
        $this->pages->render('usuario/login');
    }

    /**
     * Método para realizar el inicio de sesión.
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['data'])) {
                $auth = $_POST['data'];

                $usuario = Usuario::fromArray($auth);

                $identity = $usuario->login();

                if ($identity) {
                    $_SESSION['login'] = $identity;
                } else {
                    $_SESSION['login'] = "failed";
                }
            } else {
                $_SESSION['login'] = "failed";
            }

            $usuario->desconecta();
        }

        $this->pages->render('/usuario/login');
    }

    /**
     * Método para cerrar la sesión del usuario.
     */
    public function logout()
    {
        Utils::deleteSession('login');
        header("Location: " . BASE_URL);
    }
}

?>
