<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// login estricto, redirigir si no esta logueado
 if(!$userId){
    header("Location: login.php");
    exit;
}

$database = new Database();
$conn = $database->getConnection();

// Obtener Alojamientos
$query = "SELECT Id, Nombre, Descripcion, Precio, Ubicacion, ImagenUrl, Amenidades FROM alojamientos WHERE Activo = 1 ORDER BY FechaCreacion DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$alojamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener IDs de Favoritos del usuario actual (para pintar los corazones al cargar)
$favoritos_ids = [];
$total_favoritos = 0;

$favoritos_ids = []; // Array vacío por defecto
$total_favoritos = 0;

if ($userId) {
    
    // (Buscamos los favoritos del usuario logueado con su ID)
    $favQuery = "SELECT AlojamientoId FROM favoritos WHERE UsuarioId = :uid";
    
    $favStmt = $conn->prepare($favQuery);
    $favStmt->bindParam(':uid', $userId);
    $favStmt->execute();
    
    // Esto nos devuelve un array simple: [1, 5, 8, 12]
    $favoritos_ids = $favStmt->fetchAll(PDO::FETCH_COLUMN);
    $total_favoritos = count($favoritos_ids);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayNova - Alojamientos Exclusivos</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="bg-gradient"></div>

    <header class="glass-header">
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-gem"></i> StayNova
            </div>

            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="active">Inicio</a></li>
                    <li><a href="#alojamientos">Explorar</a></li>
                    <li><a href="account.php">Mis reservas</a></li>
                    <li><a href="favorite.php">Favoritos</a></li>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['is_admin']): ?>
                        <li><a href="admin.php" class="admin-link">Panel Admin</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="auth-actions">
                <a href="favorite.php" class="btn-icon-glass" title="Ver mis favoritos">
                    <i class="far fa-heart"></i>
                    <span class="badge-count" id="header-fav-count"><?php echo $total_favoritos; ?></span>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                        <span class="user-name">Hola, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                        <a href="logout.php" class="btn-outline-glass">Salir</a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn-text">Ingresar</a>
                    <a href="register.php" class="btn-primary-glass">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container hero-container">
            <div class="hero-text fade-in-up">
                <h1>Encuentra tu lugar <br><em>extraordinario</em></h1>
                <p>Alojamientos únicos verificados para experiencias inolvidables.</p>
            </div>
            <div class="search-glass fade-in-up delay-1">
                <form class="search-form" action="index.php" method="GET">
                    <div class="search-item">
                        <label><i class="fas fa-map-pin"></i> Ubicación</label>
                        <input type="text" name="q" placeholder="¿A dónde vas?">
                    </div>
                    <div class="divider"></div>
                    <div class="search-item submit-item">
                        <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <main id="alojamientos">
        <div class="container">
            <div class="section-header">
                <h2 class="gradient-text">Alojamientos Destacados</h2>
                <p>Selección exclusiva para viajeros exigentes</p>
            </div>

            <div class="grid-layout">
                <?php foreach ($alojamientos as $index => $a): ?>
                    <?php 
                        // Verificar si este alojamiento está en favoritos
                        $isFav = in_array($a['Id'], $favoritos_ids);
                        $heartClass = $isFav ? 'fas' : 'far'; // Solido o borde
                        $activeClass = $isFav ? 'active' : '';
                        $colorStyle = $isFav ? 'style="color: #ff4757;"' : '';
                    ?>
                    
                    <article class="property-card glass-card fade-in-up" style="animation-delay: <?php echo $index * 100; ?>ms">
                        <div class="card-media">
                            <img src="<?php echo htmlspecialchars($a['ImagenUrl']); ?>" 
                                 alt="<?php echo htmlspecialchars($a['Nombre']); ?>" 
                                 loading="lazy">
                            
                            <div class="card-badges">
                                <span class="badge glass-badge"><i class="fas fa-star"></i> 4.9</span>
                            </div>

                            <button class="btn-favorite-float <?php echo $activeClass; ?>" 
                                    onclick="toggleFavorite(<?php echo $a['Id']; ?>, this)"
                                    aria-label="Añadir a favoritos">
                                <i class="<?php echo $heartClass; ?> fa-heart" <?php echo $colorStyle; ?>></i>
                            </button>
                        </div>
                        
                        <div class="card-body">
                            <div class="card-meta">
                                <span class="location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($a['Ubicacion']); ?></span>
                            </div>
                            
                            <h3><?php echo htmlspecialchars($a['Nombre']); ?></h3>
                            
                            <div class="amenities-list">
                                <?php 
                                $amenidades = array_slice(explode(',', $a['Amenidades']), 0, 3);
                                foreach ($amenidades as $am): ?>
                                    <span class="amenity-pill"><?php echo trim($am); ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="card-footer">
                                <div class="price">
                                    <span class="amount">$<?php echo number_format($a['Precio'], 0); ?></span>
                                    <span class="period">/ noche</span>
                                </div>
                                <form action="../handlers/handle_select_accommodation.php" method="POST">
                                    <input type="hidden" name="accommodation_id" value="<?= $a['Id']; ?>">
                                    <button type="submit" class="btn-glass-action">Reservar</button>
                                </form>

                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer class="glass-footer">
        <div class="container">
            <div class="footer-copy">
                &copy; <?php echo date('Y'); ?> StayNova. Todos los derechos reservados.
            </div>
        </div>
    </footer>

<script>
    // respuesta instantánea al dar click en el corazon de favoritos
    async function toggleFavorite(id, btn) {
        const icon = btn.querySelector('i');
        const badge = document.getElementById('header-fav-count');
        let currentCount = parseInt(badge.textContent) || 0;

        // Verificamos si actualmente está activo para invertirlo
        const isActive = btn.classList.contains('active');

        if (isActive) {
            // esta ya marcado en  rojo como favorito indicativo -> LO DESACTIVAMOS VISUALMENTE
            btn.classList.remove('active');
            icon.classList.replace('fas', 'far'); // Cambia a corazón vacío
            icon.style.color = ''; // Quita el color rojo
            
            // Restamos 1 al contador visualmente (sin esperar a la base de datos)
            badge.textContent = Math.max(0, currentCount - 1);
        } else {
            // no esta marcado en rojo osea como favorito -> LO ACTIVAMOS VISUALMENTE
            btn.classList.add('active');
            icon.classList.replace('far', 'fas'); // Cambia a corazón lleno
            icon.style.color = '#ff4757'; // Pone color rojo
            
          
            btn.style.transform = 'scale(1.3)';
            setTimeout(() => btn.style.transform = 'scale(1)', 200);

            // Sumamos 1 al contador visualmente
            badge.textContent = currentCount + 1;
        }

        // enviar a la base de datos (En segundo plano) ---
        try {
            const response = await fetch('../handlers/toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });

            const result = await response.json();

            // Si el usuario no estaba logueado, redirigir
            if (result.message === 'login_required') {
                window.location.href = 'login.php';
                return;
            }

            // Si funciono, actualizamos el contador con el dato REAL de la BD
            // para asegurarnos de que esté sincronizado perfectamente.
            if (result.success) {
                badge.textContent = result.total;
            } else {
                // SI FALLA, REVERTIMOS (Rollback)
                console.error("Error al guardar:", result.message);
                alert("Hubo un problema al guardar tu favorito.");
            }

        } catch (error) {
            console.error('Error de conexión:', error);
        }
    }
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.glass-header');
        if (header) {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }
    });
</script>
</body>
</html>