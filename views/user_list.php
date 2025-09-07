<?php
require_once '../config/Database.php';
require_once '../controllers/AuthController.php';
require_once '../models/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear conexión y controladores
$db = new Database();
$auth = new AuthController($db);
$userModel = new User($db);

// Verificar si el usuario está logueado
$auth->requireLogin();

// Obtener usuario actual
$currentUser = $auth->getCurrentUser();

// Verificar si el usuario es administrador
if ($currentUser['role'] !== 'admin') {
    echo "<div style='text-align:center; margin-top:50px;'>
              <h3>Acceso denegado</h3>
              <p>Esta sección es solo para administradores.</p>
          </div>";
    exit;
}

// Obtener todos los usuarios
$users = $userModel->readAll();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Usuarios Registrados</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th><i class="fas fa-id-badge"></i> ID</th>
                    <th><i class="fa fa-user-circle"></i> Nombre</th>
                    <th><i class="fa fa-envelope"></i> Email</th>
                    <th><i class="fa fa-calendar"></i> Fecha de Registro</th>
                    <th><i class="fa fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <a href="user_form.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Editar</a>
                            <a href="../controllers/user_router.php?action=delete&id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')"><i class="fa fa-user-times"></i> Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="user_form.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Crear Nuevo Usuario</a>
        <a href="dashboard.php" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Volver al Dashboard</a>
    </div>
</body>

</html>