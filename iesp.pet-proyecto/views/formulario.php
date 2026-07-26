<?php
include '../includes/header.php';

// Capturamos el nombre de la mascota desde la URL
$mascota_seleccionada = isset($_GET['mascota']) ? htmlspecialchars($_GET['mascota']) : '';

$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = isset($_POST['nombre_completo']) ? trim($_POST['nombre_completo']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';
    $mascota_interes = isset($_POST['mascota_interes']) ? trim($_POST['mascota_interes']) : '';

    if (empty($nombre_completo) || empty($correo) || empty($telefono) || empty($motivo)) {
        $mensaje_error = 'Por favor, complete todos los campos requeridos.';
    } else {
        try {
            $sql = "INSERT INTO postulaciones (mascota_interes, nombre_completo, correo, telefono, motivo) 
                    VALUES (:mascota_interes, :nombre_completo, :correo, :telefono, :motivo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':mascota_interes' => $mascota_interes ?: 'General',
                ':nombre_completo' => $nombre_completo,
                ':correo' => $correo,
                ':telefono' => $telefono,
                ':motivo' => $motivo
            ]);
            $mensaje_exito = '¡Tu postulación ha sido enviada con éxito! Nos pondremos en contacto contigo pronto. 🐾';
            $mascota_seleccionada = ''; 
        } catch (\PDOException $e) {
            error_log("Error al insertar postulación: " . $e->getMessage());
            $mensaje_error = 'Ocurrió un error al registrar tu postulación. Por favor, inténtalo de nuevo.';
        }
    }
}
?>

<main class="main-content">
    <section class="formulario-section">
        <div class="formulario-header">
            <h2>Formulario de Postulación de Adopción</h2>
            <?php if (!empty($mascota_seleccionada)): ?>
                <p>Estás iniciando el proceso para adoptar a: <strong class="resaltar-mascota"><?php echo $mascota_seleccionada; ?></strong> 🐾</p>
            <?php else: ?>
                <p>Por favor, completa tus datos para iniciar el proceso de adopción responsable.</p>
            <?php endif; ?>
        </div>

        <div class="form-contenedor-caja">
            <?php if (!empty($mensaje_exito)): ?>
                <div class="alerta-exito">
                    <span class="alerta-icono"></span>
                    <span class="alerta-texto"><?php echo htmlspecialchars($mensaje_exito); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensaje_error)): ?>
                <div class="alerta-error">
                    <span class="alerta-icono"></span>
                    <span class="alerta-texto"><?php echo htmlspecialchars($mensaje_error); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="form-adopcion" id="formAdopcion">
                
                <input type="hidden" name="mascota_interes" value="<?php echo $mascota_seleccionada; ?>">

                <div class="grupo-formulario">
                    <label for="nombre_completo">Nombre Completo:</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="campo-entrada" placeholder="Ej. Juan Pérez" required value="<?php echo (!empty($mensaje_error)) ? htmlspecialchars($nombre_completo) : ''; ?>">
                </div>

                <div class="grupo-formulario">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" class="campo-entrada" placeholder="ejemplo@correo.com" required value="<?php echo (!empty($mensaje_error)) ? htmlspecialchars($correo) : ''; ?>">
                </div>

                <div class="grupo-formulario">
                    <label for="telefono">Número de Teléfono / WhatsApp:</label>
                    <input type="tel" id="telefono" name="telefono" class="campo-entrada" placeholder="Ej. 945123456" required value="<?php echo (!empty($mensaje_error)) ? htmlspecialchars($telefono) : ''; ?>">
                </div>

                <div class="grupo-formulario">
                    <label for="motivo">¿Por qué deseas adoptar a esta mascota?</label>
                    <textarea id="motivo" name="motivo" class="campo-entrada area-texto" placeholder="Cuéntanos un poco sobre el espacio que tienes en casa y tu experiencia con animalitos..." required><?php echo (!empty($mensaje_error)) ? htmlspecialchars($motivo) : ''; ?></textarea>
                </div>

                <div class="formulario-botones">
                    <button type="submit" class="btn btn-secondary btn-enviar">Enviar Postulación</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php 
    include '../includes/footer.php'; 
?>