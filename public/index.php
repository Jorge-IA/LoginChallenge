<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$db = new Database(); // Instancia de la conexión
$auth = new AuthController($db); // ✅ Se pasa la conexión al controlador

if ($auth->isLoggedIn()) {
    header('Location: ../views/dashboard.php');
} else {
    header('Location: ../views/login.php');
}
exit;
?>
