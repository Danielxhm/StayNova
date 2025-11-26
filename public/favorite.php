<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

// Consultar SOLO los favoritos del usuario
$query = "SELECT a.* FROM alojamientos a
          JOIN favoritos f ON a.Id = f.AlojamientoId
          WHERE f.UsuarioId = :uid
          ORDER BY f.FechaCreacion DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(':uid', $_SESSION['user_id']);
$stmt->execute();
$mis_favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Favoritos - StayNova</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="bg-gradient"></div>

    <header class="glass-header">
        <div class="container header-content">
            <div class="logo"><a href="index.php" style="text-decoration:none; color:var(--primary);"><i class="fas fa-gem"></i> StayNova</a></div>
            <div class="auth-actions">
                 <a href="index.php" class="btn-glass-action">Volver al Inicio</a>
            </div>
        </div>
    </header>

    <main style="padding-top: 120px;">
        <div class="container">
            <h2 class="gradient-text" style="margin-bottom: 30px;">Mis Favoritos (<?php echo count($mis_favoritos); ?>)</h2>

            <?php if (count($mis_favoritos) === 0): ?>
                <div class="glass-card" style="text-align:center; padding: 3rem;">
                    <i class="far fa-heart" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <h3>Aún no tienes favoritos</h3>
                    <p>Vuelve al inicio y dale amor a los lugares que te gusten.</p>
                    <a href="index.php" class="btn-primary-glass" style="display:inline-block; margin-top:1rem;">Explorar</a>
                </div>
            <?php else: ?>
                <div class="grid-layout">
                    <?php foreach ($mis_favoritos as $a): ?>
                        <article class="property-card glass-card">
                            <div class="card-media">
                                <img src="<?php echo htmlspecialchars($a['ImagenUrl']); ?>" alt="Imagen">
                                <button class="btn-favorite-float active" 
                                        onclick="toggleFavorite(<?php echo $a['Id']; ?>, this)"
                                        style="color: #ff4757;">
                                    <i class="fas fa-heart" style="color: #ff4757;"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($a['Nombre']); ?></h3>
                                <p class="card-meta"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($a['Ubicacion']); ?></p>
                                <div class="card-footer">
                                    <span class="amount">$<?php echo number_format($a['Precio'], 0); ?></span>
                                    <a href="detalle.php?id=<?php echo $a['Id']; ?>" class="btn-glass-action">Ver</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Logica para eliminar visualmente de la lista
        async function toggleFavorite(id, btn) {
            if(!confirm('¿Quitar de favoritos?')) return;
            
            const response = await fetch('../handlers/toggle_favorite.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id: id })
            });
            const result = await response.json();
            
            if (result.success && result.action === 'removed') {
                // Eliminar la tarjeta del DOM con una animación
                const card = btn.closest('article');
                card.style.transform = 'scale(0.9)';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            }
        }
    </script>
</body>
</html>