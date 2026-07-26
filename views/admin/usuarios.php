<?php
include '../../includes/header.php';

// Validar que el usuario haya iniciado sesión y sea ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

// Consultar la lista de usuarios registrados en la base de datos
try {
    $stmt = $pdo->query("SELECT id, nombre, correo, rol, created_at FROM usuarios ORDER BY id DESC");
    $usuarios = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Error al consultar usuarios: " . $e->getMessage());
    $usuarios = [];
}
?>

<main class="main-content">
    <section class="info-section" style="max-width: 1000px; margin: 0 auto; padding: 2rem 1rem;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <h2><i class="fa-solid fa-users"></i> Lista de Usuarios Registrados</h2>
            <a href="<?php echo BASE_URL; ?>views/admin/panel.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver al Panel Admin
            </a>
        </div>

        <!-- Tarjeta resumen -->
        <div style="background: #ffffff; border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1.5rem;">
            <div style="background: #fff3e0; color: #e65100; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                <i class="fa-solid fa-user-group"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.8rem; color: #333;"><?php echo count($usuarios); ?></h3>
                <p style="margin: 0; color: #666; font-size: 0.95rem;">Total de usuarios registrados en la plataforma</p>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow-x: auto; padding: 1rem;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; color: #555; font-size: 0.9rem;">
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Nombre</th>
                        <th style="padding: 12px;">Correo Electrónico</th>
                        <th style="padding: 12px;">Rol</th>
                        <th style="padding: 12px;">Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 12px; font-weight: bold; color: #666;">#<?php echo $u['id']; ?></td>
                                <td style="padding: 12px; font-weight: 600; color: #333;"><?php echo htmlspecialchars($u['nombre']); ?></td>
                                <td style="padding: 12px; color: #555;"><?php echo htmlspecialchars($u['correo']); ?></td>
                                <td style="padding: 12px;">
                                    <?php if ($u['rol'] === 'admin'): ?>
                                        <span style="background: #ffe0b2; color: #e65100; font-weight: 700; padding: 4px 12px; border-radius: 12px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <i class="fa-solid fa-shield-halved"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span style="background: #e1f5fe; color: #0288d1; font-weight: 700; padding: 4px 12px; border-radius: 12px; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <i class="fa-solid fa-user"></i> Usuario
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 12px; color: #777; font-size: 0.88rem;">
                                    <?php echo date('d/m/Y - h:i A', strtotime($u['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 25px; color: #888;">No hay usuarios registrados aún.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </section>
</main>

<?php
include '../../includes/footer.php';
?>
