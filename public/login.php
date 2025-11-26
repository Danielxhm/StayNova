<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - StayNova</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="bg-gradient"></div>

    <header class="glass-header">
        <div class="container header-content">
            <div class="logo">
                <a href="index.php" style="text-decoration:none; color:var(--primary);">
                    <i class="fas fa-gem"></i> StayNova
                </a>
            </div>
            <div class="auth-actions">
                <a href="index.php" class="btn-glass-action">
                    <i class="fas fa-arrow-left"></i> Volver al inicio
                </a>
            </div>
        </div>
    </header>

    <main class="auth-container">
        <div class="glass-card auth-card fade-in-up">
            <div class="auth-header">
                <h2>Bienvenido de nuevo</h2>
                <p>Accede para gestionar tus reservas y favoritos.</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert glass-alert error" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-exclamation-circle"></i> 
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="../handlers/handle_login.php" method="POST">
                
                <div class="form-group">
                    <label for="username"><i class="far fa-user"></i> Usuario</label>
                    <input type="text" id="username" name="username" required placeholder="Ingresa tu usuario" class="glass-input">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required placeholder="••••••••" class="glass-input">
                        <i class="far fa-eye toggle-password" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-glass">
                        <input type="checkbox" name="remember"> 
                        <span class="checkmark"></span>
                        Recordarme
                    </label>
                    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-primary-glass btn-block">
                    Iniciar Sesión <i class="fas fa-sign-in-alt"></i>
                </button>

                <div class="auth-footer">
                    <p>¿Aún no tienes cuenta? <a href="register.php">Regístrate gratis</a></p>
                </div>
            </form>
        </div>
    </main>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.querySelector('.toggle-password');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>