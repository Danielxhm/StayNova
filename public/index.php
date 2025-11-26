<?php
session_start();
require_once __DIR__ . '/../src/Repositories/AccommodationRepository.php';

// Inicializar repositorio
$accommodationRepository = new AccommodationRepository();
$accommodations = $accommodationRepository->findAll();


// Logica de redirección si intenta reservar sin login
if (isset($_GET['select']) && !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para ver o reservar un alojamiento.";
    header("Location: login.php");
    exit;
}

//Contar Favoritos
$total_favoritos = 0;
if (isset($_SESSION['user_id'])) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $queryFav = "SELECT COUNT(*) FROM favoritos WHERE UsuarioId = :uid";
    $stmtFav = $conn->prepare($queryFav);
    $stmtFav->execute([':uid' => $_SESSION['user_id']]);
    $total_favoritos = $stmtFav->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayNova - Encuentra tu alojamiento perfecto</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Gideon+Roman&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    
    <div class="bg-gradient"></div>

    <header class="glass-header">
        <div class="container header-content">
            <div class="logo">
                <img src="img/logo.png" alt="logo">
                <p>StayNova</p>
            </div>

            <nav class="main-nav">
                <ul>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php" class="active">Inicio</a></li>
                    <li><a href="home.php">Explorar</a></li>
                    <li><a href="account.php">Mis reservas</a></li>
                    <li><a href="favorite.php">Favoritos</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['is_admin']): ?>
                        <li><a href="admin.php" class="admin-pill">Panel Admin</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="auth-actions">
                <?php if(isset($_SESSION['user_id'])): ?>
                <a href="favorite.php" class="btn-icon-glass" title="Ver mis favoritos">
                    <i class="far fa-heart"></i>
                    <span class="badge-count" id="header-fav-count"><?php echo $total_favoritos; ?></span>
                </a>

                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-pill">
                        <span>Hola, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                    </div>
                    <a href="logout.php" class="btn-outline-glass">Salir</a>
                <?php else: ?>
                    <a href="login.php" class="btn-text">Ingresar</a>
                    <a href="register.php" class="btn-primary-glass">Registrarse</a>
                <?php endif; ?>
            </div>
            
            <button class="mobile-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <section class="hero">
        <div class="video-overlay"></div>
        <video autoplay muted loop id="bg-video">
            <source src="img/hero.mp4" type="video/mp4">
        </video>

        <div class="hero-content container fade-in-up">
            <h1>¿Dónde quieres <br><em>quedarte?</em></h1>
            <p>Encuentra tu alojamiento ideal en minutos con experiencias únicas.</p>

            <div class="search-glass">
                <form class="search-form">
                    <div class="search-item">
                        <label><i class="fas fa-map-marker-alt"></i> Ubicación</label>
                        <input type="text" placeholder="¿A dónde vas?">
                    </div>
                    <div class="divider"></div>
                    <div class="search-item">
                        <label><i class="far fa-calendar"></i> Check-in</label>
                        <input type="date">
                    </div>
                    <div class="divider"></div>
                    <div class="search-item">
                        <label><i class="far fa-calendar-check"></i> Check-out</label>
                        <input type="date">
                    </div>
                    <div class="search-item submit-item">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="destinations container">
        <div class="section-header">
            <h2 class="gradient-title">Destinos Populares</h2>
        </div>
        <div class="destinations-grid">
            <div class="dest-card"><img src="img/jakarta.webp"><span>Jakarta</span></div>
            <div class="dest-card"><img src="img/napoles.webp"><span>Nápoles</span></div>
            <div class="dest-card"><img src="img/bali.jpg"><span>Bali</span></div>
            <div class="dest-card"><img src="img/paris.avif"><span>París</span></div>
        </div>
    </section>

    <section class="latest-stays container" id="alojamientos">
        <div class="section-header">
            <h2 class="gradient-title">Alojamientos Destacados</h2>
            <p>Explora nuestra selección exclusiva</p>
        </div>

        <div class="grid-layout">
            <?php foreach (array_slice($accommodations, 0, 3) as $a): ?>
                <article class="glass-card">
                    <div class="card-media">
                        <img src="<?= !empty($a['imagen_url']) ? htmlspecialchars($a['imagen_url']) : 'img/default.jpg' ?>" 
                             alt="Imagen alojamiento">
                        
                        <div class="card-badges">
                            <span class="badge"><i class="fas fa-star"></i> 4.9</span>
                        </div>

                       
                    </div>

                    <div class="card-body">
                        <h3><?= htmlspecialchars($a['name']); ?></h3>
                        <p class="description"><?= htmlspecialchars(substr($a['description'], 0, 90)) . '...'; ?></p>
                        
                        <div class="card-footer">
                            <div class="price">
                                <span class="amount">$<?= htmlspecialchars($a['price']); ?></span>
                                <span class="period">/ noche</span>
                            </div>

                            <?php if (isset($_SESSION['user_id']) && !$_SESSION['is_admin']): ?>
                                <form action="../handlers/handle_select_accommodation.php" method="POST">
                                    <input type="hidden" name="accommodation_id" value="<?= $a['id'] ?>">
                                    <button class="btn-glass-action">Reservar</button>
                                </form>
                            <?php else: ?>
                                <a href="index.php?select=1" class="btn-glass-action">Reservar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
            
    <a href="home.php" class="btn-glass-action" style="padding: 12px 30px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        Ver más <i class="fas fa-arrow-right"></i>
    </a>
        </div>
    </section>


    <footer class="glass-footer">
        <div class="container">
            <p>&copy; 2025 StayNova. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        // Script visual para el botón de favoritos
        function toggleHeart(btn) {
            const icon = btn.querySelector('i');
            btn.classList.toggle('active');
            if (btn.classList.contains('active')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
            }
        }

        // Efecto scroll header
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.glass-header');
            header.classList.toggle('scrolled', window.scrollY > 50);
        });
    </script>
</body>
</html>