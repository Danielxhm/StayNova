<?php
session_start();
require_once __DIR__ . '/../src/Repositories/AccommodationRepository.php';

// Seguridad: Solo admins
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../public/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $id = $_POST['id'] ?? null;
    
    // Quitamos espacios en blanco al inicio y final de los datos ingresados por si un dato escrito tiene espacios innecesarios en blanco
    $nombre = trim($_POST['Nombre'] ?? '');
    $descripcion = trim($_POST['Descripcion'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $imagenUrl = trim($_POST['ImagenUrl'] ?? '');
    $amenidades = trim($_POST['amenidades'] ?? '');

    // Reemplaza comas por puntos y asegura que sea un número asi formateo bien el precio
    $precioRaw = $_POST['Precio'] ?? '0';
    $precioClean = str_replace(',', '.', $precioRaw);
    $precio = floatval($precioClean);

    //Asignar campos default si algunos no fueron ingresados y no afectar en la BD por 0 caracteres y en layout
    if ($descripcion === '') $descripcion = 'Descripción pendiente de asignar.';
    if ($ubicacion === '')   $ubicacion = 'Ubicación no especificada';
    if ($amenidades === '')  $amenidades = 'Default';
    if ($imagenUrl === '')   $imagenUrl = 'https://via.placeholder.com/300?text=Sin+Imagen'; // Imagen por defecto válida

    if (empty($nombre)) {
        $_SESSION['error'] = "El nombre es obligatorio.";
        header('Location: ../public/admin.php');
        exit;
    }

    if ($precio <= 0) {
        $_SESSION['error'] = "El precio debe ser mayor a 0. Dato recibido: " . htmlspecialchars($precioRaw);
        header('Location: ../public/admin.php');
        exit;
    }

    $repo = new AccommodationRepository();
    $success = false;

    try {
        if ($action === 'create') {
            $userId = $_SESSION['user_id'];
            $success = $repo->create($nombre, $descripcion, $precio, $ubicacion, $imagenUrl, $amenidades, $userId);
            $mensaje = "Alojamiento creado correctamente.";
        } elseif ($action === 'update' && $id) {
            $success = $repo->update($id, $nombre, $descripcion, $precio, $ubicacion, $imagenUrl, $amenidades);
            $mensaje = "Alojamiento actualizado correctamente.";
        } else {
            throw new Exception("Acción no válida.");
        }

        if ($success) {
            $_SESSION['success'] = $mensaje;
        } else {
            $_SESSION['error'] = "Error desconocido al guardar.";
        }

    } catch (PDOException $e) {
        // Capturamos el error 3819 para dar un mensaje útil
        if ($e->getCode() == '3819') {
            $_SESSION['error'] = "BLOQUEO DE BASE DE DATOS: Una regla interna (Constraint) impide guardar estos datos. Posible causa: Un campo está vacío o el precio no cumple el formato exacto.";
        } else {
            $_SESSION['error'] = "Error BD: " . $e->getMessage();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header('Location: ../public/admin.php');
exit;
?>