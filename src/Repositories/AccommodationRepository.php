<?php
require_once __DIR__ . '/../../config/Database.php';

class AccommodationRepository {
    private $conn;
    private $table_name = "alojamientos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function findAll() {
        $query = "SELECT Id as id, Nombre as name, Descripcion as description, Precio as price, 
                  Ubicacion as ubicacion, ImagenUrl as imagen_url, Amenidades as amenidades 
                  FROM " . $this->table_name . " 
                  WHERE Activo = 1 ORDER BY FechaCreacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nombre, $descripcion, $precio, $ubicacion, $imagenUrl, $amenidades, $userId) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (Nombre, Descripcion, Precio, Ubicacion, ImagenUrl, Amenidades, Activo, FechaCreacion, UsuarioCreador) 
                  VALUES (:nombre, :descripcion, :precio, :ubicacion, :imagen, :amenidades, 1, NOW(), :userId)";

        $stmt = $this->conn->prepare($query);
        
        // Usamos array en execute para mayor seguridad con los tipos de datos
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':ubicacion' => $ubicacion,
            ':imagen' => $imagenUrl,
            ':amenidades' => $amenidades,
            ':userId' => $userId
        ]);
    }

    public function update($id, $nombre, $descripcion, $precio, $ubicacion, $imagenUrl, $amenidades) {
        $query = "UPDATE " . $this->table_name . " 
                  SET Nombre = :nombre, 
                      Descripcion = :descripcion, 
                      Precio = :precio, 
                      Ubicacion = :ubicacion, 
                      ImagenUrl = :imagen, 
                      Amenidades = :amenidades 
                  WHERE Id = :id";

        $stmt = $this->conn->prepare($query);
        
        // Pasamos los valores directamente aquí para evitar conflictos de referencias
        return $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':ubicacion' => $ubicacion,
            ':imagen' => $imagenUrl,
            ':amenidades' => $amenidades,
            ':id' => $id
        ]);
    }

    public function delete($id) {
        $query = "UPDATE " . $this->table_name . " SET Activo = 0 WHERE Id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
    public function getUserAccommodations($userId) {
        // Hacemos un JOIN para traer los datos del alojamiento PERO solo los que están
        // en la tabla 'usuarioalojamientos' vinculados a este usuario.
        $query = "SELECT a.Id, a.Nombre, a.Descripcion, a.Precio, a.Ubicacion, 
                         a.ImagenUrl, a.Amenidades, ua.FechaSeleccion
                  FROM " . $this->table_name . " a
                  INNER JOIN usuarioalojamientos ua ON a.Id = ua.AlojamientoId
                  WHERE ua.UsuarioId = :userId
                  ORDER BY ua.FechaSeleccion DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':userId' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignToUser($userId, $accommodationId) {
        
        $query = "INSERT INTO usuarioalojamientos (UsuarioId, AlojamientoId, FechaSeleccion) 
                  VALUES (:uid, :aid, NOW())";
        
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':uid' => $userId, 
                ':aid' => $accommodationId
            ]);
        } catch (PDOException $e) {
            // Nota: Si el usuario ya tiene este alojamiento guardado, 
            // la restricción 'UniqueUsuarioAlojamiento' hará que esto falle (retorna false).
            return false;
        }
    }

    public function removeUserAccommodation($userId, $accommodationId) {
        $query = "DELETE FROM usuarioalojamientos 
                  WHERE UsuarioId = :uid AND AlojamientoId = :aid";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':uid' => $userId,
            ':aid' => $accommodationId
        ]);
    }

}
?>