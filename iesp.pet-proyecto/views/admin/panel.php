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
            <div class="card" style="border: 2px solid #8c6239; background-color: #fdfbf7;">
                <h3><i class="fa-solid fa-plus-circle" style="color: #8c6239;"></i> Registrar Nueva Mascota</h3>
                <p>Agrega un nuevo perro, gato u otro animal al refugio completando su nombre, especie, edad, descripción y subiendo su fotografía.</p>
                <div style="margin-top: 1.25rem;">
                    <a href="<?php echo BASE_URL; ?>views/admin/registrar_mascota.php" class="btn btn-primary" style="background-color: #8c6239; color: #fff; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-paw"></i> Ir al Formulario de Registro
                    </a>
                </div>
            </div>
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
