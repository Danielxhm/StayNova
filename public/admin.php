<?php
session_start();
//Verificar permisos de administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../src/Repositories/AccommodationRepository.php';

$accommodationRepository = new AccommodationRepository();
$accommodations = $accommodationRepository->findAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayNova - Panel de Administrador</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="bg-gradient"></div>

    <header class="glass-header">
        <div class="container header-content">
            <div class="logo">
                <a href="index.php" style="text-decoration:none; color:var(--primary);">
                    <i class="fas fa-shield-alt"></i> Admin Panel
                </a>
            </div>
            <div class="auth-actions">
                <a href="index.php" class="btn-glass-action">Ver Sitio</a>
                <a href="logout.php" class="btn-outline-glass">Salir</a>
            </div>
        </div>
    </header>

    <main class="container admin-layout">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert glass-alert success">
                <i class="fas fa-check"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert glass-alert error" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                <i class="fas fa-times"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="stats-grid fade-in-up">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-home"></i></div>
                <div>
                    <h3><?= count($accommodations) ?></h3>
                    <span style="color:var(--text-light)">Alojamientos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color:#10b981; background:rgba(16,185,129,0.1)"><i class="fas fa-users"></i></div>
                <div>
                    <h3>Admin</h3>
                    <span style="color:var(--text-light)"><?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?></span>
                </div>
            </div>
        </div>

        <div class="admin-table-container fade-in-up delay-1">
            <div class="section-header" style="text-align:left; display:flex; justify-content:space-between; align-items:center;">
                <h2>Gestión de Alojamientos</h2>
                <button class="btn-primary-glass" onclick="openModal('create')">
                    <i class="fas fa-plus"></i> Nuevo Alojamiento
                </button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accommodations as $a): ?>
                    <tr>
                        <td>#<?= $a['id'] ?></td>
                        <td>
                            <img src="<?= !empty($a['imagen_url']) ? htmlspecialchars($a['imagen_url']) : 'img/default.jpg' ?>" 
                                 style="width:50px; height:50px; border-radius:8px; object-fit:cover;">
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($a['name']) ?></strong><br>
                            <small style="color:#888"><?= htmlspecialchars($a['ubicacion']) ?></small>
                        </td>
                        <td>$<?= number_format($a['price'], 2) ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-sm btn-edit" onclick='openModal("edit", <?= json_encode($a, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn-sm btn-delete" onclick="confirmDelete(<?= $a['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="accommodationModal" class="modal-overlay">
        <div class="modal-glass">
            <div class="modal-header">
                <h3 id="modalTitle">Nuevo Alojamiento</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            
            <form id="adminForm" method="POST" action="../handlers/handle_save_accommodation.php">
                <input type="hidden" name="id" id="inputId">
                <input type="hidden" name="action" id="inputAction" value="create">

                <div class="form-group">
                    <label>Nombre del Alojamiento</label>
                    <input type="text" name="Nombre" id="inputName" required>
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="Descripcion" id="inputDesc" rows="3" required></textarea>
                </div>

                <div class="form-group" style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                    <div>
                        <label>Precio</label>
                        <input type="number" name="Precio" id="inputPrice" step="0.01" required>
                    </div>
                    <div>
                        <label>Ubicación</label>
                        <input type="text" name="ubicacion" id="inputLocation">
                    </div>
                </div>

                <div class="form-group">
                    <label>URL de Imagen</label>
                    <input type="url" name="ImagenUrl" id="inputImage" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label>Amenidades (separadas por comas)</label>
                    <input type="text" name="amenidades" id="inputAmenities" placeholder="Wifi, Piscina, etc.">
                </div>

                <button type="submit" class="btn-primary-glass" style="width:100%">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <form id="deleteForm" action="../handlers/handle_delete_accommodation.php" method="POST" style="display:none;">
        <input type="hidden" name="id" id="deleteInputId">
    </form>

    <script>
        const modal = document.getElementById('accommodationModal');
        const form = document.getElementById('adminForm');
        
        function openModal(mode, data = null) {
            modal.classList.add('active');
            
            if (mode === 'create') {
                document.getElementById('modalTitle').innerText = 'Nuevo Alojamiento';
                document.getElementById('inputAction').value = 'create';
                form.reset();
                document.getElementById('inputId').value = '';
            } else {
                document.getElementById('modalTitle').innerText = 'Editar Alojamiento';
                document.getElementById('inputAction').value = 'update';
                
                // Rellenar datos
                // NOTA: Estas propiedades (data.name, data.description) vienen del alias en el Repositorio (findAll)
                document.getElementById('inputId').value = data.id;
                document.getElementById('inputName').value = data.name;
                document.getElementById('inputDesc').value = data.description;
                document.getElementById('inputPrice').value = data.price;
                document.getElementById('inputLocation').value = data.ubicacion;
                document.getElementById('inputImage').value = data.imagen_url;
                document.getElementById('inputAmenities').value = data.amenidades;
            }
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        function confirmDelete(id) {
            if(confirm('¿Estás seguro de eliminar este alojamiento?')) {
                document.getElementById('deleteInputId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>