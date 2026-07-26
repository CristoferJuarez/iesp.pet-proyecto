<?php
include '../../includes/header.php';

// Validar que el usuario haya iniciado sesión y sea ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // Si no es admin, lo redirigimos al inicio
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Consultar totales para el resumen del panel
$total_usuarios = 0;
$total_postulaciones = 0;
$total_mascotas = 0;

try {
    // Total Usuarios
    $stmtUser = $pdo->query("SELECT COUNT(*) AS total FROM usuarios");
    $total_usuarios = $stmtUser->fetch()['total'];

    // Total Postulaciones
    $stmtPost = $pdo->query("SELECT COUNT(*) AS total FROM postulaciones");
    $total_postulaciones = $stmtPost->fetch()['total'];

    // Total Mascotas
    $stmtMasc = $pdo->query("SELECT COUNT(*) AS total FROM mascotas");
    $total_mascotas = $stmtMasc->fetch()['total'];
} catch (\PDOException $e) {
    error_log("Error al obtener contadores del panel admin: " . $e->getMessage());
}
?>

<main class="main-content">
    <section class="info-section" style="max-width: 1000px; margin: 0 auto; padding: 2rem 1rem;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2><i class="fa-solid fa-user-shield"></i> Panel del Administrador</h2>
            <p style="color: #666; font-size: 1.05rem; margin-top: 0.5rem;">
                Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>. Gestión y control del sistema.
            </p>
        </div>

        <!-- Tarjetas de resumen -->
        <div class="info-cards" style="margin-bottom: 2.5rem;">
            
            <div class="card" style="text-align: center; padding: 1.8rem; border-top: 4px solid #e65100;">
                <div style="font-size: 2.2rem; color: #e65100; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 style="font-size: 2rem; margin: 0; color: #333;"><?php echo $total_usuarios; ?></h3>
                <p style="color: #666; margin: 0.5rem 0 1.2rem 0; font-size: 0.95rem;">Usuarios Registrados</p>
                <a href="<?php echo BASE_URL; ?>views/admin/usuarios.php" class="btn btn-secondary" style="font-size: 0.85rem; padding: 6px 14px;">
                    <i class="fa-solid fa-list-check"></i> Ver Lista Completa
                </a>
            </div>

            <div class="card" style="text-align: center; padding: 1.8rem; border-top: 4px solid #0288d1;">
                <div style="font-size: 2.2rem; color: #0288d1; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h3 style="font-size: 2rem; margin: 0; color: #333;"><?php echo $total_postulaciones; ?></h3>
                <p style="color: #666; margin: 0.5rem 0 1.2rem 0; font-size: 0.95rem;">Postulaciones Recibidas</p>
                <span style="font-size: 0.8rem; color: #888;">Registradas en sistema</span>
            </div>

            <div class="card" style="text-align: center; padding: 1.8rem; border-top: 4px solid #2e7d32;">
                <div style="font-size: 2.2rem; color: #2e7d32; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-paw"></i>
                </div>
                <h3 style="font-size: 2rem; margin: 0; color: #333;"><?php echo $total_mascotas; ?></h3>
                <p style="color: #666; margin: 0.5rem 0 1.2rem 0; font-size: 0.95rem;">Mascotas en Catálogo</p>
                <span style="font-size: 0.8rem; color: #888;">Gestionadas en BD</span>
            </div>

        </div>

    </section>
</main>

<?php
include '../../includes/footer.php';
?>
