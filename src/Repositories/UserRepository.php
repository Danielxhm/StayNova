<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Factories/UserFactory.php';
require_once __DIR__ . '/../Models/User.php'; 

class UserRepository {
    private $conn;
    private $table_name = "Usuarios"; 
    public function __construct() {
        $database = new Database();
      
        $this->conn = $database->getConnection();
    }

    /**
     * @return User|null
     */
    public function findByUsername(string $username): ?User {
        $query = "SELECT Id, UserName, PasswordHash, Rol 
                  FROM " . $this->table_name . " 
                  WHERE UserName = :username 
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return UserFactory::createUser(
                (int)$row['Id'], 
                $row['UserName'], 
                $row['PasswordHash'], 
                strtolower($row['Rol']) === 'administrador'
            );
        }
        return null;
    }

    /**
     * @return User|null
     */
    public function createUser(string $username, string $email, string $password, string $rol = 'Usuario'): ?User {
        
        $query = "INSERT INTO " . $this->table_name . " (UserName, Email, PasswordHash, Rol)
                  VALUES (:username, :email, :password, :rol)";
        
        try {
            $stmt = $this->conn->prepare($query);

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':rol', $rol);

            if (!$stmt->execute()) {
                return null;
            }

            $lastId = $this->conn->lastInsertId();
            
            return UserFactory::createUser(
                (int)$lastId, 
                $username, 
                $hashed_password, 
                strtolower($rol) === 'administrador'
            );

        } catch (PDOException $e) {
            // Error 23000 es duplicado (username o email)
            if ($e->getCode() == 23000) {
                // No hacer nada, solo devolver null
            } else {
                
            }
            return null;
        }
    }

    /**
     * @return User|null
     */
    public function findById(int $id): ?User {
        $query = "SELECT Id, UserName, PasswordHash, Rol 
                  FROM " . $this->table_name . " 
                  WHERE Id = :id LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return UserFactory::createUser(
                (int)$row['Id'], 
                $row['UserName'], 
                $row['PasswordHash'], 
                strtolower($row['Rol']) === 'administrador'
            );
        }
        return null;
    }
}