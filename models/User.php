<?php
require_once __DIR__ . '/../config/Database.php';

class User
{
    private $conn;
    private $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db->getConnection();
    }


// Create a new user with a role
public function create($name, $email, $password, $role)
{
    $this->conn->beginTransaction();

    try {
        // 1. Insertar el nuevo usuario en la tabla 'users'
        $sql = "INSERT INTO {$this->table} (name, email, password, created_at) VALUES (:name, :email, :password, NOW())";
        $stmt = $this->conn->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // 2. Obtener el ID del usuario recién creado
        $newUserId = $this->conn->lastInsertId();

        // 3. Obtener el ID del rol
        $sqlRole = "SELECT id FROM roles WHERE name = :role LIMIT 1";
        $stmtRole = $this->conn->prepare($sqlRole);
        $stmtRole->bindParam(':role', $role);
        $stmtRole->execute();
        $roleId = $stmtRole->fetchColumn();

        // 4. Insertar la relación en la tabla 'user_roles'
        $sqlUserRole = "INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)";
        $stmtUserRole = $this->conn->prepare($sqlUserRole);
        $stmtUserRole->bindParam(':user_id', $newUserId);
        $stmtUserRole->bindParam(':role_id', $roleId);
        $stmtUserRole->execute();

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollBack();
        return false;
    }
}

    // Read all users
    public function readAll()
    {
        $sql = "SELECT id, name, email, created_at FROM {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  // Read user by ID
    public function readById($id)
    {
        $sql = "SELECT users.id, users.name, users.email, users.created_at, roles.name AS role
                FROM users
                LEFT JOIN user_roles ON users.id = user_roles.user_id
                LEFT JOIN roles ON user_roles.role_id = roles.id
                WHERE users.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user
    public function update($id, $name, $email, $password = null)
    {
        if ($password) {
            $sql = "UPDATE {$this->table} SET name = :name, email = :email, password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $hashedPassword);
        } else {
            $sql = "UPDATE {$this->table} SET name = :name, email = :email WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Delete user
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Authenticate user
    public function authenticate($email, $password)
    {
        $sql = "SELECT users.id, users.name, users.email, users.password, roles.name AS role
            FROM users
            LEFT JOIN user_roles ON users.id = user_roles.user_id
            LEFT JOIN roles ON user_roles.role_id = roles.id
            WHERE users.email = :email LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
