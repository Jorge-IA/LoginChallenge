
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS user_crud_login;
USE user_crud_login;

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Tabla de usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla intermedia para asignar roles a usuarios
CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Insertar roles por defecto
INSERT INTO roles (name) VALUES ('admin'), ('user');

-- Insertar usuarios de ejemplo con contraseñas hasheadas (password: admin123, user123)
INSERT INTO users (name, email, password) VALUES
('Administrador', 'admin@example.com', '$2y$10$e0NRZ9Zz0Z1zZ9Zz0Z1zZ.9Zz0Z1zZ9Zz0Z1zZ9Zz0Z1zZ9Zz0Z1z'), -- hash simulado
('Usuario Normal', 'user@example.com', '$2y$10$e0NRZ9Zz0Z1zZ9Zz0Z1zZ.9Zz0Z1zZ9Zz0Z1zZ9Zz0Z1zZ9Zz0Z1z');

-- Asignar roles a los usuarios
INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1), -- admin
(2, 2); -- user
