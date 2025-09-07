<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$db = new Database();
$auth = new AuthController($db); // ✅ Pasar la conexión correctamente
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = $auth->getCurrentUser();
$role = $user['role'] ?? 'usuario';
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema CRUD</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title">¡Hola, <?php echo htmlspecialchars($user['name']); ?>!</h3>
                <p class="card-text">Tu rol es: <strong><?php echo htmlspecialchars($role); ?></strong></p>

                <?php if ($role === 'admin'): ?>
                    <a href="user_list.php" class="btn btn-success"><i class="fas fa-users"></i> Gestionar Usuarios</a>
                    <a href="user_form.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Crear Nuevo Usuario</a>
                <?php else: ?>
                    <a href="user_form.php?id=<?php echo $user['id']; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar Mi Perfil</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>