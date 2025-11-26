<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'login_required']);
    exit;
}

$data = json_decode(file_get_contents("php://input"));
$alojamiento_id = $data->id ?? null;
$user_id = $_SESSION['user_id'];

if (!$alojamiento_id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$database = new Database();
$conn = $database->getConnection();

try {

    // CHECK si ya existe
    $checkQuery = "SELECT Id FROM favoritos 
                   WHERE UsuarioId = :uid AND AlojamientoId = :aid";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bindParam(':uid', $user_id);
    $stmt->bindParam(':aid', $alojamiento_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // ELIMINAR
        $deleteQuery = "DELETE FROM favoritos 
                        WHERE UsuarioId = :uid AND AlojamientoId = :aid";
        $delStmt = $conn->prepare($deleteQuery);
        $delStmt->bindParam(':uid', $user_id);
        $delStmt->bindParam(':aid', $alojamiento_id);
        $delStmt->execute();
        $action = 'removed';
    } else {
        // INSERTAR
        $insertQuery = "INSERT INTO favoritos (UsuarioId, AlojamientoId) 
                        VALUES (:uid, :aid)";
        $insStmt = $conn->prepare($insertQuery);
        $insStmt->bindParam(':uid', $user_id);
        $insStmt->bindParam(':aid', $alojamiento_id);
        $insStmt->execute();
        $action = 'added';
    }

    //Recontar total
    $countQuery = "SELECT COUNT(*) AS total FROM favoritos WHERE UsuarioId = :uid";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bindParam(':uid', $user_id);
    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    echo json_encode([
        'success' => true,
        'action' => $action,
        'total' => $total
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
