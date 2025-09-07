<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/AuthController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class UserController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function handleRequest()
    {
        // Esta función ahora solo maneja las acciones que requieren que el usuario esté autenticado.
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }

        $action = $_POST['action'] ?? $_GET['action'] ?? '';

        switch ($action) {
            case 'create':
                $this->createUser();
                break;
            case 'update':
                $this->updateUser();
                break;
            case 'delete':
                $this->deleteUser();
                break;
            default:
                header('Location: ../views/dashboard.php');
                break;
        }
    }

    public function handleCreateUserFromRegister($name, $email, $password)
    {
        $role = 'user'; // Rol por defecto para el auto-registro
        $success = $this->userModel->create($name, $email, $password, $role);

        if ($success) {
            return ['success' => true, 'error' => null];
        } else {
            return ['success' => false, 'error' => 'Es posible que el correo electrónico ya esté registrado.'];
        }
    }

    /**
     * Maneja la creación de usuarios desde el panel de administrador.
     * Redirige a la vista de la lista de usuarios o muestra un error.
     */
    private function createUser()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if ($name && $email && $password) {
            $success = $this->userModel->create($name, $email, $password, $role);

            if ($success) {
                header('Location: ../views/user_list.php');
                exit();
            } else {
                echo "<div style='text-align:center; margin-top:50px;'>
                    <h3>Error al crear el usuario</h3>
                    <p>Es posible que el correo electrónico ya esté registrado.</p>
                    <a href='../views/user_list.php' class='btn btn-secondary mt-3'>Volver a la lista de usuarios</a>
                  </div>";
                exit();
            }
        } else {
            echo "<div style='text-align:center; margin-top:50px;'>
                    <h3>Error</h3>
                    <p>Todos los campos son obligatorios.</p>
                    <a href='javascript:history.back()' class='btn btn-secondary mt-3'>Volver al formulario</a>
                  </div>";
            exit();
        }
    }

    public function updateUser()
    {
        // Obtener los datos del formulario de actualización
        $id = $_POST['id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';

        // Obtener los datos del usuario autenticado
        $authenticatedUserId = $_SESSION['user_id'] ?? null;
        $authenticatedUserRole = $_SESSION['user_role'] ?? 'usuario'; // Asume 'usuario' si no está definido

        // ⛔️ VALIDACIÓN DE PERMISOS: Solo el administrador o el dueño del perfil pueden actualizar
        if ($authenticatedUserRole !== 'admin' && $authenticatedUserId != $id) {
            header('Location: ../views/access_denied.php'); // O la página que muestre el error
            exit();
        }

        // ✅ Continuar con la actualización si los campos obligatorios están presentes
        if ($id && $name && $email) {
            $this->userModel->update($id, $name, $email, $password, $role);

            if ($authenticatedUserRole === 'admin') {
                header('Location: ../views/user_list.php');
            } else {
                header('Location: ../views/dashboard.php');
            }
            exit();
        } else {
            echo "Nombre y correo son obligatorios.";
        }
    }

    public function deleteUser()
    {
        // Lógica para verificar si el usuario tiene rol de admin
        if ($_SESSION['user_role'] === 'admin') {
            $id = $_GET['id'] ?? '';
            if ($id) {
                $this->userModel->delete($id);
            }
            header('Location: ../views/user_list.php');
        } else {
            header('Location: ../views/dashboard.php');
        }
    }

    public function getUserById($id)
    {
        return $this->userModel->readById($id);
    }
}
