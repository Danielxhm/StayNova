<?php
session_start();
// Si ya está logueado, redirigir
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
    <title>Registrarse - StayNova</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/register.css">
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
                <a href="login.php" class="btn-glass-action">
                    Iniciar Sesión
                </a>
            </div>
        </div>
    </header>

    <main class="auth-container">
        <div class="glass-card auth-card fade-in-up">
            <div class="auth-header">
                <h2>Crea tu cuenta</h2>
                <p>Únete a nuestra comunidad de viajeros exclusivos.</p>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert glass-alert error" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="../handlers/handle_register.php" method="POST">
                
                <div class="form-group">
                    <label for="username"><i class="far fa-user"></i> Usuario</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Elige un nombre de usuario" class="glass-input">
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="far fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="ejemplo@correo.com" class="glass-input">
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required 
                               placeholder="Crea una contraseña segura" class="glass-input">
                        <i class="far fa-eye toggle-password" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-glass" style="font-size: 0.85rem;">
                        <input type="checkbox" required> 
                        Acepto los <a href="#" style="color:var(--primary); text-decoration:none;">Términos y Condiciones</a>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary-glass btn-block">
                    Registrarse <i class="fas fa-user-plus"></i>
                </button>
                
                <div class="auth-footer">
                    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
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