<?php
session_start();
require_once __DIR__ . '/../src/Repositories/AccommodationRepository.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para guardar alojamientos.";
    header("Location: ../public/login.php");
    exit;
}

if (!isset($_POST['accommodation_id'])) {
    $_SESSION['error'] = "Solicitud inválida.";
    header("Location: ../public/home.php");
    exit;
}

$repo = new AccommodationRepository();

if ($repo->assignToUser($_SESSION['user_id'], $_POST['accommodation_id'])) {
    $_SESSION['success'] = "Alojamiento guardado correctamente.";
} else {
    $_SESSION['error'] = "Error al guardar alojamiento.";
}

header("Location: ../public/account.php");
exit;
