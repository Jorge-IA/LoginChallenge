<?php
session_start();
require_once '../config/Database.php';
require_once '../controllers/UserController.php';

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $userController = new UserController($db);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && $email && $password) {
        $result = $userController->handleCreateUserFromRegister($name, $email, $password);

        if ($result['success']) {
            $isSuccess = true;
            $message = '¡Registro exitoso! Ya puedes iniciar sesión.';
        } else {
            $message = $result['error'];
        }
    } else {
        $message = 'Todos los campos son obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Nuevo Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">

    <style>
        .password-toggle-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 5px;
            cursor: pointer;
        }

        .password-toggle-container i {
            margin-right: 5px;
            color: #6c757d;
        }
    </style>

</head>

<body>
    <!-- Contenedor para el fondo animado de Vanta.js -->
    <div id="vanta-background"></div>

    <div class="container register-container">
        <div class="card">
            <h3 class="text-center mb-4">Crear Cuenta</h3>
            <?php if ($message): ?>
                <div class="alert <?= $isSuccess ? 'alert-success' : 'alert-danger'; ?> text-center">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Tu nombre completo">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="usuario@ejemplo.com">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                    </div>
                    <br />
                    <div class="password-toggle-container text-center" onclick="document.getElementById('show-password-toggle').click();">
                        <i id="password-toggle-icon" class="fa fa-eye-slash"></i>
                        <input type="checkbox" id="show-password-toggle" class="form-check-input visually-hidden">
                        <label class="form-check-label" for="show-password-toggle">Ver contraseña</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Registrarme</button>
            </form>
            <hr class="my-4">
            <div class="text-center">
                <a href="login.php" class="link-login">Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Vanta.js y sus dependencias -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script>
        // Inicialización de la animación de Vanta.js
        window.addEventListener('load', function() {
            VANTA.NET({
                el: "#vanta-background",
                mouseControls: true,
                touchControls: true,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: 0x47a9ef,
                backgroundColor: 0x111111,
                points: 10.00,
                maxDistance: 15.00,
                spacing: 15.00
            });
        });

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