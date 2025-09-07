<?php
session_start();
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';

$db = new Database();
$auth = new AuthController($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->login($email, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Credenciales inválidas. Si no tienes una cuenta, regístrate.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>

<body>
    <!-- Contenedor para el fondo animado de Vanta.js -->
    <div id="vanta-background"></div>

    <div class="container login-container">
        <div class="card">
            <h3 class="text-center mb-4">Iniciar Sesión</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($error) ?>
                    <hr class="my-2">
                    <p class="mb-0">¿No tienes una cuenta? <br /> <a href="register.php" class="link-register">Regístrate aquí</a></p>
                </div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="usuario@ejemplo.com">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Entrar</button>
            </form>
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
    </script>
</body>

</html>