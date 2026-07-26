<?php
include '../includes/header.php';

$mensaje_exito = '';
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre_contacto']) ? trim($_POST['nombre_contacto']) : '';
    $correo = isset($_POST['correo_contacto']) ? trim($_POST['correo_contacto']) : '';
    $asunto = isset($_POST['asunto_contacto']) ? trim($_POST['asunto_contacto']) : '';
    $mensaje = isset($_POST['mensaje_contacto']) ? trim($_POST['mensaje_contacto']) : '';

    if (empty($nombre) || empty($correo) || empty($asunto) || empty($mensaje)) {
        $mensaje_error = 'Por favor, complete todos los campos obligatorios.';
    } else {
        try {
            $sql = "INSERT INTO mensajes_contacto (nombre, correo, asunto, mensaje) VALUES (:nombre, :correo, :asunto, :mensaje)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':asunto' => $asunto,
                ':mensaje' => $mensaje
            ]);
            $mensaje_exito = '¡Tu mensaje ha sido enviado con éxito! Nos comunicaremos contigo pronto. ✉️';
        } catch (\PDOException $e) {
            error_log("Error al insertar mensaje de contacto: " . $e->getMessage());
            $mensaje_error = 'No se pudo enviar el mensaje en este momento. Por favor, intenta más tarde.';
        }
    }
}
?>

<main class="main-content">
    <section class="contacto-section">
        <div class="contacto-header">
            <h2>Ponte en Contacto con Nosotros</h2>
            <p>¿Tienes alguna duda sobre el proceso de adopción o quieres apoyarnos? ¡Estamos aquí para ayudarte!</p>
        </div>

        <div class="contacto-grid">
            <div class="contacto-info-caja">
                <h3>Información de Contacto</h3>
                <p>Si prefieres, puedes visitarnos o escribirnos directamente a nuestros canales oficiales:</p>
                
                <ul class="lista-contacto-detalles">
                    <li>
                        <strong>Dirección:</strong><br>
                        Jr. Independencia 456, Centro Histórico, Trujillo, Perú
                    </li>
                    <li>
                        <strong>Teléfono / WhatsApp:</strong><br>
                        +51 945 123 456
                    </li>
                    <li>
                        <strong>Correo Electrónico:</strong><br>
                        contacto@refugiopatitas.org
                    </li>
                    <li>
                        <strong>Horario de Atención:</strong><br>
                        Lunes a Sábado: 9:00 AM - 6:00 PM<br>
                        Domingos (Previa Cita): 10:00 AM - 2:00 PM
                    </li>
                </ul>
            </div>

            <div class="contacto-formulario-caja">
                <h3>Envíanos un Mensaje</h3>

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

                <form action="" method="POST" class="form-contacto">
                    <div class="grupo-formulario">
                        <label for="nombre_contacto">Nombre Completo:</label>
                        <input type="text" id="nombre_contacto" name="nombre_contacto" class="campo-entrada" placeholder="Tu nombre" required value="<?php echo (!empty($mensaje_error)) ? htmlspecialchars($nombre) : ''; ?>">
                    </div>

                    <div class="grupo-formulario">
                        <label for="correo_contacto">Correo Electrónico:</label>
                        <input type="email" id="correo_contacto" name="correo_contacto" class="campo-entrada" placeholder="tu@correo.com" required value="<?php echo (!empty($mensaje_error)) ? htmlspecialchars($correo) : ''; ?>">
                    </div>

                    <div class="grupo-formulario">
                        <label for="asunto_contacto">Asunto:</label>
                        <input type="text" id="asunto_contacto" name="asunto_contacto" class="campo-entrada" placeholder="Ej. Donaciones, Voluntariado, Consulta" required value="<?php echo (!empty($mensaje_error)) ? htmlspecialchars($asunto) : ''; ?>">
                    </div>

                    <div class="grupo-formulario">
                        <label for="mensaje_contacto">Mensaje:</label>
                        <textarea id="mensaje_contacto" name="mensaje_contacto" class="campo-entrada area-texto" placeholder="Escribe tu consulta aquí..." required><?php echo (!empty($mensaje_error)) ? htmlspecialchars($mensaje) : ''; ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-enviar">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php
include '../includes/footer.php'; 
?>