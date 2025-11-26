<?php
session_start();
require_once __DIR__ . '/../src/Repositories/AccommodationRepository.php';

//Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

//Verificar datos
if (!isset($_POST['accommodation_id'])) {
    $_SESSION['error'] = "Error al identificar el alojamiento.";
    header("Location: ../public/account.php");
    exit;
}

//Ejecutar eliminación
$repo = new AccommodationRepository();
$result = $repo->removeUserAccommodation($_SESSION['user_id'], $_POST['accommodation_id']);

if ($result) {
    $_SESSION['success'] = "Reserva cancelada correctamente.";
} else {
    $_SESSION['error'] = "No se pudo cancelar la reserva.";
}

//Volver al perfil
header("Location: ../public/account.php");
exit;