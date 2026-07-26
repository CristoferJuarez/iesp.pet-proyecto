<?php
// Incluimos el header modular (este ya se encarga de jalar config.php)
include '../includes/header.php';

// Capturamos el parámetro de filtrado por especie (Perro / Gato)
$especie_filtrada = isset($_GET['especie']) ? trim($_GET['especie']) : '';

// Consultamos los datos de las mascotas desde la base de datos
try {
    if ($especie_filtrada === 'Perro' || $especie_filtrada === 'Gato') {
        $sql = "SELECT * FROM mascotas WHERE especie = :especie ORDER BY id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':especie' => $especie_filtrada]);
    } else {
        $sql = "SELECT * FROM mascotas ORDER BY id ASC";
        $stmt = $pdo->query($sql);
    }
    $mascotas = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Error al consultar mascotas: " . $e->getMessage());
    $mascotas = [];
}
?>

<main class="main-content">
    <section class="catalogo-section">
        <div class="catalogo-header">
            <h2>Nuestros Pequeños en Adopción</h2>
            <p>Conoce a los peluditos que están buscando un hogar responsable en Trujillo.</p>
        </div>

        <div class="filtros-catalogo">
            <a href="catalogo.php" class="btn-filtro <?php echo empty($especie_filtrada) ? 'activo' : ''; ?>">Todos</a>
            <a href="catalogo.php?especie=Perro" class="btn-filtro <?php echo $especie_filtrada === 'Perro' ? 'activo' : ''; ?>">Perros</a>
            <a href="catalogo.php?especie=Gato" class="btn-filtro <?php echo $especie_filtrada === 'Gato' ? 'activo' : ''; ?>">Gatos</a>
        </div>

        <div class="contenedor-catalogo">
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
                            <span class="especie-tag"><?php echo $mascota['especie']; ?></span>
                        </div>
                        
                        <ul class="mascota-detalles-lista">
                            <li><strong>Edad:</strong> <?php echo $mascota['edad']; ?></li>
                            <li><strong>Género:</strong> <?php echo $mascota['genero']; ?></li>
                        </ul>

                        <p class="mascota-descripcion"><?php echo $mascota['descripcion']; ?></p>

                        <div class="mascota-accion">
                            <?php if ($mascota['disponible']): ?>
                                <a href="<?php echo BASE_URL; ?>views/formulario.php?mascota=<?php echo urlencode($mascota['nombre']); ?>" class="btn btn-secondary btn-adoptar">Adoptar a <?php echo $mascota['nombre']; ?></a>
                            <?php else: ?>
                                <button class="btn btn-disabled" disabled>No Disponible</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php 
// Incluimos el footer modular
include '../includes/footer.php'; 
?>