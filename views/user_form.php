<?php
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/UserController.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Crear conexión y controladores
$db = new Database();
$auth = new AuthController($db);
$userController = new UserController($db);

// Verificar si el usuario está logueado
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Obtener usuario actual
$currentUser = $auth->getCurrentUser();
$isAdmin = $currentUser['role'] === 'admin';

// Si se está editando, obtener los datos del usuario
$editing = false;
$editUser = null;
if (isset($_GET['id'])) {
    $editUser = $userController->getUserById($_GET['id']);
    if ($editUser && ($isAdmin || $editUser['id'] == $currentUser['id'])) {
        $editing = true;
    } else {
        echo "<div style='text-align:center; margin-top:50px;'><h3>No tienes permiso para editar este usuario.</h3></div>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $editing ? "Editar Usuario" : "Crear Usuario"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .password-toggle-container {
            display: flex;
            align-items: center;
            margin-top: 5px;
            cursor: pointer;
            /* Hace que toda esta área sea un elemento en el que se puede hacer clic */
        }

        .password-toggle-container i {
            margin-right: 5px;
            color: #6c757d;
        }
    </style>

</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h2 class="text-center mb-4"><?= $editing ? "Editar Usuario" : "Crear Nuevo Usuario"; ?></h2>
            <form action="../controllers/user_router.php" method="POST">
                <input type="hidden" name="action" value="<?= $editing ? "update" : "create"; ?>">
                <input type="hidden" name="id" value="<?= $editing ? htmlspecialchars($editUser['id']) : ""; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label"><i class="fa fa-user"></i> Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" required
                        value="<?= $editing ? htmlspecialchars($editUser['name']) : ""; ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fa fa-envelope"></i> Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required
                        value="<?= $editing ? htmlspecialchars($editUser['email']) : ""; ?>">
                </div>

                <div class="mb-3">
                    <i class="fa fa-lock"></i> <label for="password" class="form-label"><?= $editing ? "Nueva contraseña (opcional)" : "Contraseña"; ?></label>
                    <input type="password" class="form-control" id="password" name="password" <?= $editing ? "" : "required"; ?>>
                </div>

                <div class="mb-3 password-toggle-container" onclick="document.getElementById('show-password-toggle').click();">
                    <i id="password-toggle-icon" class="fa fa-eye-slash"></i>
                    <input type="checkbox" id="show-password-toggle" class="form-check-input visually-hidden">
                    <label class="form-check-label" for="show-password-toggle">Ver contraseña</label>
                </div>

                <?php if ($isAdmin): ?>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rol</label>
                        <select class="form-select" id="role" name="role">
                            <option value="user" <?= ($editing && $editUser['role'] === 'user') ? "selected" : ""; ?>><i class="fa fa-envelope"></i> Usuario</option>
                            <option value="admin" <?= ($editing && $editUser['role'] === 'admin') ? "selected" : ""; ?>><i class="fa fa-envelope"></i> Administrador</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?= $editing ? "Actualizar" : "Crear"; ?></button>
                <a href="dashboard.php" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Volver al Dashboard</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('show-password-toggle').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            if (this.checked) {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        });
    </script>

</body>

</html>