<?php
include '../includes/header.php';

// Consultamos solo los gatos desde la base de datos
try {
    $sql = "SELECT * FROM mascotas WHERE especie = 'Gato' ORDER BY id ASC";
    $stmt = $pdo->query($sql);
    $mascotas = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Error al consultar gatos: " . $e->getMessage());
    $mascotas = [];
}
?>

<main class="main-content">
    <section class="catalogo-section">
        <div class="catalogo-header">
            <h2><i class="fa-solid fa-cat"></i> Nuestros Gatitos en Adopción</h2>
            <p>Conoce a los mininos que están buscando un hogar responsable en Trujillo.</p>
        </div>

        <div class="contenedor-catalogo">
            <?php if (empty($mascotas)): ?>
                <div class="sin-mascotas">
                    <i class="fa-solid fa-cat"></i>
                    <p>No hay gatitos disponibles en este momento.</p>
                </div>
            <?php else: ?>
                <?php foreach ($mascotas as $mascota): ?>
                    <div class="tarjeta-mascota <?php echo !$mascota['disponible'] ? 'en-proceso' : ''; ?>">
                        
                        <div class="mascota-img-container">
                            <img src="<?php echo BASE_URL; ?>public-assets/img/mascotas/<?php echo $mascota['imagen']; ?>" alt="<?php echo $mascota['nombre']; ?>" class="mascota-img">
                            
                            <?php if (!$mascota['disponible']): ?>
                                <span class="badge-estado">En Proceso</span>
                            <?php endif; ?>
                        </div>

                        <div class="mascota-info">
                            <div class="mascota-header-tarjeta">
                                <h3><?php echo $mascota['nombre']; ?></h3>
                                <span class="especie-tag"><i class="fa-solid fa-cat"></i> <?php echo $mascota['especie']; ?></span>
                            </div>
                            
                            <ul class="mascota-detalles-lista">
                                <li><strong>Edad:</strong> <?php echo $mascota['edad']; ?></li>
                                <li><strong>Género:</strong> <?php echo $mascota['genero']; ?></li>
                            </ul>

                            <p class="mascota-descripcion"><?php echo $mascota['descripcion']; ?></p>

                            <div class="mascota-accion">
                                <?php if ($mascota['disponible']): ?>
                                    <a href="<?php echo BASE_URL; ?>views/formulario.php?mascota=<?php echo urlencode($mascota['nombre']); ?>" class="btn btn-secondary btn-adoptar">
                                        <i class="fa-solid fa-heart"></i> Adoptar a <?php echo $mascota['nombre']; ?>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-disabled" disabled>No Disponible</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php 
include '../includes/footer.php'; 
?>
