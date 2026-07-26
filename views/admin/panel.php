<?php
include '../../includes/header.php';

// Validar que el usuario haya iniciado sesión y sea ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    // Si no es admin, lo redirigimos al inicio
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
?>

<main class="main-content">
    <section class="info-section">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2><i class="fa-solid fa-user-shield"></i> Panel de Administración</h2>
            <p style="color: #666; font-size: 1.1rem; margin-top: 0.5rem;">
                Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>. Estás en el área privada para administradores.
            </p>
        </div>

        <div class="info-cards" style="max-width: 900px; margin: 0 auto;">
            <div class="card">
                <h3><i class="fa-solid fa-folder-open"></i> Vista Administrativa</h3>
                <p>Esta carpeta <code>views/admin/</code> está protegida y es visible únicamente para el usuario con rol de Administrador.</p>
            </div>
            <div class="card">
                <h3><i class="fa-solid fa-lock"></i> Control de Acceso</h3>
                <p>Si un usuario normal intenta ingresar a esta ruta por la URL, el sistema lo detecta y lo redirige automáticamente al inicio.</p>
            </div>
        </div>
    </section>
</main>

<?php
include '../../includes/footer.php';
?>
