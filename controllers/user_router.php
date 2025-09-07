<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/UserController.php';

// Inicia la sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear una instancia de la base de datos
$db = new Database();

// Crear una instancia del controlador de usuario
$userController = new UserController($db);

// Llamar al método del controlador que maneja la solicitud
$userController->handleRequest();
