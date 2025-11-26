<?php
session_start();
require_once __DIR__ . '/../src/Repositories/AccommodationRepository.php';

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$accommodationRepository = new AccommodationRepository();
$userAccommodations = $accommodationRepository->getUserAccommodations($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - StayNova</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/account.css">
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
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="index.php#alojamientos">Explorar</a></li>
                    <li><a href="favorite.php">Favoritos</a></li>
                </ul>
            </nav>

            <div class="auth-actions">
                <div class="user-pill">
                        <span>Hola, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                </div>
                <a href="logout.php" class="btn-outline-glass">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="account-header fade-in-up">
            <h1 class="gradient-title">Bienvenido a tu espacio</h1>
            <p style="color:var(--text-light); font-size:1.1rem;">Gestiona tus próximas aventuras y reservas.</p>
        </div>

        <div class="account-stats fade-in-up delay-1">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($userAccommodations); ?></div>
                <p>Reservas Activas</p>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <p>Estancias Completadas</p>
            </div>
            <div class="stat-card">
                <div class="stat-number"><i class="fas fa-star" style="font-size:2rem; color:#fbbf24;"></i></div>
                <p>Viajero Nivel Plata</p>
            </div>
        </div>

        <div class="section-header">
            <h2 style="font-family:'Playfair Display', serif; font-size:2rem; margin-bottom:1rem;">Mis Reservas</h2>
        </div>

        <?php if (empty($userAccommodations)): ?>
            <div class="glass-card fade-in-up" style="text-align:center; padding:4rem 2rem;">
                <div style="font-size:3rem; color:#cbd5e1; margin-bottom:1rem;">
                    <i class="fas fa-suitcase-rolling"></i>
                </div>
                <h3>No tienes reservas activas</h3>
                <p style="color:var(--text-light); margin-bottom:2rem;">¿Listo para planear tu próxima escapada?</p>
                <a href="index.php#alojamientos" class="btn-primary-glass">
                    Explorar Alojamientos
                </a>
            </div>
        <?php else: ?>
            
            <div class="grid-layout">
                <?php foreach ($userAccommodations as $index => $alojamiento): ?>
                    <article class="glass-card fade-in-up" style="animation-delay: <?php echo $index * 100; ?>ms">
                        <div class="card-media">
                            <img src="<?php echo htmlspecialchars($alojamiento['ImagenUrl']); ?>" 
                                 alt="<?php echo htmlspecialchars($alojamiento['Nombre']); ?>"
                                 loading="lazy">
                            
                            <div class="card-badges">
                                <span class="badge glass-badge" style="background:#10b981;">Confirmado</span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="reservation-date">
                                <i class="far fa-calendar-alt"></i> 
                                Reservado: <?php echo date('d M, Y', strtotime($alojamiento['FechaSeleccion'] ?? 'now')); ?>
                            </div>
                            
                            <h3><?php echo htmlspecialchars($alojamiento['Nombre']); ?></h3>
                            
                            <div class="card-meta" style="margin-bottom:1rem;">
                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($alojamiento['Ubicacion']); ?>
                            </div>
                            
                            <div class="card-footer" style="flex-direction:column; align-items:flex-start; gap:0.5rem;">
                                <div class="price" style="width:100%; display:flex; justify-content:space-between; align-items:center;">
                                    <span class="amount">$<?php echo number_format($alojamiento['Precio'], 0); ?></span>
                                    <span class="period">/ noche</span>
                                </div>
                                
                                <form action="../handlers/handle_cancel_accommodation.php" method="POST" style="width:100%;" onsubmit="return confirm('¿Estás seguro de cancelar esta reserva? Esta acción no se puede deshacer.');">
                                    <input type="hidden" name="accommodation_id" value="<?php echo $alojamiento['Id']; ?>">
                                    <button type="submit" class="btn-danger-glass">
                                        <i class="fas fa-times"></i> Cancelar Reserva
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </main>

    <footer class="glass-footer">
        <div class="container">
            <p>&copy; 2025 StayNova. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        // Efecto scroll header
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.glass-header');
            header.classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
</body>
</html>