<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../src/Repositories/UserRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';


    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Por favor, complete todos los campos';
        header('Location: ../public/login.php');
        exit;
    }

    $userRepository = new UserRepository();
    $user = $userRepository->findByUsername($username);

    if ($user && password_verify($password, $user->getPassword())) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['is_admin'] = $user->getIsAdmin();
        $_SESSION['success'] = 'Inicio de sesión exitoso';

        if ($user->getIsAdmin()) {
            header('Location: ../public/admin.php'); 
        } else {
            header('Location: ../public/account.php'); 
        }
        exit;
    } else {
        $_SESSION['error'] = 'Credenciales incorrectas';
        header('Location: ../public/login.php');
        exit;
    }
}

// Redirección por defecto si se accede al script sin POST
header('Location: ../public/login.php');
exit;