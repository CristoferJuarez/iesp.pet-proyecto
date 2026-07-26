<?php
include '../../includes/header.php';

// Validar que el usuario haya iniciado sesión y sea ADMIN
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$mensaje_exito = '';
$mensaje_error = '';

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $especie = isset($_POST['especie']) ? trim($_POST['especie']) : '';
    $edad = isset($_POST['edad']) ? trim($_POST['edad']) : '';
    $genero = isset($_POST['genero']) ? trim($_POST['genero']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $disponible = isset($_POST['disponible']) ? (int)$_POST['disponible'] : 1;

    // Validación básica de campos requeridos
    if (empty($nombre) || empty($especie) || empty($edad) || empty($genero) || empty($descripcion)) {
        $mensaje_error = 'Por favor, complete todos los campos obligatorios del formulario.';
    } elseif (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        $mensaje_error = 'Es obligatorio seleccionar una foto para la mascota.';
    } else {
        // Manejo y validación de la foto subida
        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_name = $_FILES['imagen']['name'];
        $file_size = $_FILES['imagen']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $max_tamano = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file_ext, $extensiones_permitidas)) {
            $mensaje_error = 'Formato de imagen no válido. Solo se permiten archivos JPG, JPEG, PNG y WEBP.';
        } elseif ($file_size > $max_tamano) {
            $mensaje_error = 'La imagen sobrepasa el tamaño máximo permitido (5 MB).';
        } else {
            // Nombre único para la imagen para evitar sobreescrituras
            $nombre_imagen_limpio = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($file_name, PATHINFO_FILENAME));
            $nuevo_nombre_imagen = time() . '_' . $nombre_imagen_limpio . '.' . $file_ext;

            // Ruta de destino de las imágenes en public-assets/img/mascotas/
            $directorio_destino = dirname(dirname(__DIR__)) . '/public-assets/img/mascotas/';

            if (!file_exists($directorio_destino)) {
                mkdir($directorio_destino, 0777, true);
            }

            $ruta_completa_destino = $directorio_destino . $nuevo_nombre_imagen;

            if (move_uploaded_file($file_tmp, $ruta_completa_destino)) {
                try {
                    // Insertar en la base de datos
                    $sql = "INSERT INTO mascotas (nombre, especie, edad, genero, descripcion, imagen, disponible) 
                            VALUES (:nombre, :especie, :edad, :genero, :descripcion, :imagen, :disponible)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':especie' => $especie,
                        ':edad' => $edad,
                        ':genero' => $genero,
                        ':descripcion' => $descripcion,
                        ':imagen' => $nuevo_nombre_imagen,
                        ':disponible' => $disponible
                    ]);

                    $mensaje_exito = '¡Mascota "' . htmlspecialchars($nombre) . '" registrada exitosamente con su fotografía!';
                    // Limpiar variables tras éxito
                    $nombre = $especie = $edad = $genero = $descripcion = '';
                } catch (\PDOException $e) {
                    error_log("Error al registrar mascota: " . $e->getMessage());
                    $mensaje_error = 'Ocurrió un problema al guardar los datos de la mascota en la base de datos.';
                }
            } else {
                $mensaje_error = 'Error al subir la imagen al servidor. Verifique los permisos de carpeta.';
            }
        }
    }
}
?>

<!-- Carga directa de hoja de estilos del administrador por garantía de caché -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>public-assets/css/admin_registrar_mascota.css?v=<?php echo time(); ?>">

<main class="main-content">
    <div class="admin-registrar-container">
        <!-- Banner de Encabezado Admin -->
        <div class="admin-banner-header">
            <span class="admin-badge-tag"><i class="fa-solid fa-user-shield"></i> Área de Administración</span>
            <h1><i class="fa-solid fa-shield-cat"></i> Registrar Nueva Mascota</h1>
            <p>Completa la información requerida y sube la foto oficial de la mascota para incorporarla al catálogo de adopción.</p>
        </div>

        <!-- Mensajes de Alerta -->
        <?php if (!empty($mensaje_exito)): ?>
            <div class="alerta-exito">
                <span class="alerta-icono"><i class="fa-solid fa-circle-check"></i></span>
                <span class="alerta-texto"><?php echo $mensaje_exito; ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensaje_error)): ?>
            <div class="alerta-error">
                <span class="alerta-icono"><i class="fa-solid fa-triangle-exclamation"></i></span>
                <span class="alerta-texto"><?php echo htmlspecialchars($mensaje_error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Tarjeta con Formulario -->
        <div class="admin-card-form">
            <form action="" method="POST" enctype="multipart/form-data" id="formRegistrarMascota">
                
                <div class="admin-form-grid">
                    <!-- Nombre de la Mascota -->
                    <div class="admin-form-group">
                        <label for="nombre"><i class="fa-solid fa-tag"></i> Nombre de la Mascota *</label>
                        <input type="text" id="nombre" name="nombre" class="admin-input" placeholder="Ej. Pelusa, Firulais, Max..." required value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">
                    </div>

                    <!-- Especie -->
                    <div class="admin-form-group">
                        <label for="especie"><i class="fa-solid fa-paw"></i> Especie *</label>
                        <select id="especie" name="especie" class="admin-select" required>
                            <option value="" disabled <?php echo empty($especie) ? 'selected' : ''; ?>>-- Seleccionar Especie --</option>
                            <option value="Perro" <?php echo (isset($especie) && $especie === 'Perro') ? 'selected' : ''; ?>>Perro 🐶</option>
                            <option value="Gato" <?php echo (isset($especie) && $especie === 'Gato') ? 'selected' : ''; ?>>Gato 🐱</option>
                            <option value="Otro" <?php echo (isset($especie) && $especie === 'Otro') ? 'selected' : ''; ?>>Otro 🐾</option>
                        </select>
                    </div>

                    <!-- Edad -->
                    <div class="admin-form-group">
                        <label for="edad"><i class="fa-solid fa-hourglass-half"></i> Edad *</label>
                        <input type="text" id="edad" name="edad" class="admin-input" placeholder="Ej. 6 meses, 2 años..." required value="<?php echo isset($edad) ? htmlspecialchars($edad) : ''; ?>">
                    </div>

                    <!-- Género -->
                    <div class="admin-form-group">
                        <label for="genero"><i class="fa-solid fa-venus-mars"></i> Género *</label>
                        <select id="genero" name="genero" class="admin-select" required>
                            <option value="" disabled <?php echo empty($genero) ? 'selected' : ''; ?>>-- Seleccionar Género --</option>
                            <option value="Macho" <?php echo (isset($genero) && $genero === 'Macho') ? 'selected' : ''; ?>>Macho ♂</option>
                            <option value="Hembra" <?php echo (isset($genero) && $genero === 'Hembra') ? 'selected' : ''; ?>>Hembra ♀</option>
                        </select>
                    </div>

                    <!-- Estado de Disponibilidad -->
                    <div class="admin-form-group full-width">
                        <label for="disponible"><i class="fa-solid fa-circle-info"></i> Estado para Adopción *</label>
                        <select id="disponible" name="disponible" class="admin-select" required>
                            <option value="1" <?php echo (!isset($disponible) || $disponible == 1) ? 'selected' : ''; ?>>Disponible para adopción</option>
                            <option value="0" <?php echo (isset($disponible) && $disponible == 0) ? 'selected' : ''; ?>>En Proceso / Reservado</option>
                        </select>
                    </div>

                    <!-- Descripción -->
                    <div class="admin-form-group full-width">
                        <label for="descripcion"><i class="fa-solid fa-align-left"></i> Descripción y Rasgos Característicos *</label>
                        <textarea id="descripcion" name="descripcion" class="admin-textarea" placeholder="Describe su temperamento, salud, vacunas, historia o gustos..." required><?php echo isset($descripcion) ? htmlspecialchars($descripcion) : ''; ?></textarea>
                    </div>

                    <!-- Subir Fotografía de la Mascota -->
                    <div class="admin-form-group full-width">
                        <label><i class="fa-solid fa-camera"></i> Fotografía de la Mascota *</label>
                        
                        <div class="image-upload-wrapper" id="uploadWrapper">
                            <input type="file" id="imagen" name="imagen" class="image-upload-input" accept="image/jpeg, image/png, image/webp" required>
                            <div class="upload-placeholder">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span class="primary-text">Haz clic o arrastra la imagen aquí</span>
                                <span class="secondary-text">Formatos permitidos: JPG, JPEG, PNG, WEBP (Máx. 5 MB)</span>
                            </div>
                        </div>

                        <!-- Vista Previa de la Imagen Seleccionada -->
                        <div class="image-preview-box" id="previewBox">
                            <img src="" id="previewImg" alt="Vista previa de la mascota">
                            <button type="button" class="image-preview-remove" id="btnRemoveImg" title="Eliminar o cambiar foto">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="admin-form-actions">
                    <a href="<?php echo BASE_URL; ?>views/admin/panel.php" class="btn-admin-cancel">
                        <i class="fa-solid fa-arrow-left"></i> Volver al Panel
                    </a>
                    <button type="submit" class="btn-admin-submit">
                        <i class="fa-solid fa-plus"></i> Guardar Mascota
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- JavaScript para Previsualización Interactiva de la Imagen -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputImagen = document.getElementById('imagen');
    const uploadWrapper = document.getElementById('uploadWrapper');
    const previewBox = document.getElementById('previewBox');
    const previewImg = document.getElementById('previewImg');
    const btnRemoveImg = document.getElementById('btnRemoveImg');

    if (inputImagen) {
        inputImagen.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewBox.style.display = 'block';
                    uploadWrapper.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (btnRemoveImg) {
        btnRemoveImg.addEventListener('click', function() {
            inputImagen.value = '';
            previewImg.src = '';
            previewBox.style.display = 'none';
            uploadWrapper.style.display = 'block';
        });
    }

    // Soporte para Drag and Drop en el contenedor
    if (uploadWrapper) {
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadWrapper.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadWrapper.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadWrapper.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                uploadWrapper.classList.remove('dragover');
            }, false);
        });
    }
});
</script>

<?php
include '../../includes/footer.php';
?>
