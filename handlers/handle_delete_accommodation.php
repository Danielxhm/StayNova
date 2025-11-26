<?php
session_start();
require_once __DIR__ . '/../src/Repositories/AccommodationRepository.php';

//Seguridad: Solo admins
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../public/index.php');
    exit;
}

//Para el panel de Admin y eliminar alojamientos del crud 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $repo = new AccommodationRepository();
        if ($repo->delete($id)) {
            $_SESSION['success'] = "Alojamiento eliminado correctamente.";
        } else {
            $_SESSION['error'] = "No se pudo eliminar el alojamiento.";
        }
    } else {
        $_SESSION['error'] = "ID de alojamiento no válido.";
    }
}

header('Location: ../public/admin.php');
exit;
?>