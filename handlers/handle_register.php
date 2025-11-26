<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Capturar los 3 campos del formulario de registro
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validacion del form
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos';
        header('Location: ../public/register.php');
        exit;
    }

    $userRepository = new UserRepository();
    
    // revisar si el usuario ya existe
    if ($userRepository->findByUsername($username)) {
        $_SESSION['error'] = 'El nombre de usuario ya existe';
        header('Location: ../public/register.php');
        exit;
    }

    // Pasar los 3 argumentos al crear el usuario
    
    $user = $userRepository->createUser($username, $email, $password);
    
    if ($user) {
        // Mostrar el mensaje de registro exitoso y Iniciar sesión al usuario recién registrado
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['is_admin'] = $user->getIsAdmin();
        $_SESSION['success'] = 'Registro exitoso';
        header('Location: ../public/account.php'); // O index.php
    } else {
        // Error (probablemente email duplicado, gracias al try/catch)
        $_SESSION['error'] = 'Error en el registro. Es posible que el email ya esté en uso.';
        header('Location: ../public/register.php');
    }
    exit;
}

// Si alguien trata de acceder sin POST, redirigir
header('Location: ../public/register.php');
exit;