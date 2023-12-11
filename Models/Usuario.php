<?php

namespace models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Usuario
{
    private string|null $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;

    private BaseDatos $db;

    /**
     * @param string|null $id
     * @param string $nombre
     * @param string $apellidos
     * @param string $email
     * @param string $password
     * @param string $rol
     */
    public function __construct(string|null $id, string $nombre, string $apellidos, string $email, string $password, string $rol)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password = $password;
        $this->rol = $rol;
        $this->db = new BaseDatos();
    }

    // Getters and Setters

    /**
     * @return string|null
     */
    public function getId(): string|null
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(string|null $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRol(): string
    {
        return $this->rol;
    }

    /**
     * @param string $rol
     */
    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    /**
     * Cierra la conexión a la base de datos.
     */
    public function desconecta(): void
    {
        $this->db->cierraConexion();
    }

    /**
     * Crea un objeto Usuario a partir de un array de datos.
     *
     * @param array $data
     *
     * @return Usuario
     */
    public static function fromArray(array $data): Usuario
    {
        return new Usuario(
            $data['id'] ?? null,
            $data['nombre'] ?? '',
            $data['apellidos'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['rol'] ?? ''
        );
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @return bool Retorna true si la creación fue exitosa, false en caso contrario.
     */
    public function create(): bool
    {
        $id = NULL;
        $nombre = $this->getNombre();
        $apellidos = $this->getApellidos();
        $email = $this->getEmail();
        $password = $this->getPassword();
        $rol = 'user';

        try {
            $ins = $this->db->prepara("INSERT INTO usuarios (id, nombre, apellidos, email, password, rol) VALUES (:id, :nombre, :apellidos, :email, :password, :rol)");

            $ins->bindValue(':id', $id);
            $ins->bindValue(':nombre', $nombre);
            $ins->bindValue(':apellidos', $apellidos);
            $ins->bindValue(':email', $email);
            $ins->bindValue(':password', $password);
            $ins->bindValue(':rol', $rol);

            $ins->execute();

            $result = true;
        } catch (PDOException $error) {
            $result = false;
        }

        return $result;
    }

    /**
     * Realiza el proceso de inicio de sesión del usuario.
     *
     * @return bool|Usuario Retorna un objeto Usuario si el inicio de sesión fue exitoso, false en caso contrario.
     */
    public function login()
    {
        $result = false;
        $email = $this->email;
        $password = $this->password;

        $usuario = $this->buscaMail($email);

        if ($usuario !== false) {

            $verify = password_verify($password, $usuario->password);

            if ($verify) {
                $result = $usuario;
            }
        }

        return $result;
    }

    /**
     * Obtiene un objeto Usuario basado en la dirección de correo electrónico proporcionada.
     *
     * @param string $email
     *
     * @return Usuario|null Retorna un objeto Usuario si se encuentra, o null si no se encuentra.
     */
    public function getUsuario($email): ?Usuario
    {
        $select = $this->db->prepara("SELECT * FROM usuarios WHERE email=:email");
        $select->bindValue(':email', $email);
        $select->execute();
        $datosUsuario = $this->db->extraer_registro();

        // Implementar lógica para devolver un objeto Usuario
    }

    /**
     * Busca un usuario por su dirección de correo electrónico.
     *
     * @param string $email
     *
     * @return bool|object Retorna un objeto Usuario si se encuentra, o false si no se encuentra.
     */
    public function buscaMail($email): bool|object
    {
        $result = false;
        $cons = $this->db->prepara("SELECT * FROM usuarios WHERE email = :email");
        $cons->bindValue(':email', $email, PDO::PARAM_STR);
        try {
            $cons->execute();
            if ($cons && $cons->rowCount() == 1) {
                $result = $cons->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $err) {
            $result = false;
        }

        return $result;
    }
}
