<?php
include '../includes/header.php';

// Redirigir al inicio
if (isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$error_mensaje = '';
$mensaje_exito = '';

// Procesamiento del formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre     = isset($_POST['nombre'])     ? trim($_POST['nombre'])     : '';
    $correo     = isset($_POST['correo'])     ? trim($_POST['correo'])     : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena']      : '';
    $confirmar  = isset($_POST['confirmar'])  ? $_POST['confirmar']       : '';

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($contrasena) || empty($confirmar)) {
        $error_mensaje = 'Por favor, completa todos los campos.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error_mensaje = 'El correo electrónico no tiene un formato válido.';
    } elseif (strlen($contrasena) < 6) {
        $error_mensaje = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($contrasena !== $confirmar) {
        $error_mensaje = 'Las contraseñas no coinciden.';
    } else {
        try {
            // Verificar si el correo ya está registrado
            $check = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo LIMIT 1");
            $check->execute([':correo' => $correo]);

            if ($check->fetch()) {
                $error_mensaje = 'Este correo electrónico ya está registrado. ¿Deseas <a href="' . BASE_URL . 'views/login.php">iniciar sesión</a>?';
            } else {
                // Cifrar la contraseña y registrar al nuevo usuario
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (:nombre, :correo, :contrasena, 'usuario')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre'     => $nombre,
                    ':correo'     => $correo,
                    ':contrasena' => $hash,
                ]);

                // Iniciar sesión automáticamente tras el registro (rol usuario por defecto)
                $_SESSION['usuario_id'] = $pdo->lastInsertId();
                $_SESSION['usuario']    = $nombre;
                $_SESSION['correo']     = $correo;
                $_SESSION['rol']        = 'usuario';
                header('Location: ' . BASE_URL . 'index.php');
                exit;
            }
        } catch (\PDOException $e) {
            error_log("Error en registro: " . $e->getMessage());
            $error_mensaje = 'Ocurrió un error al crear tu cuenta. Por favor, intenta más tarde.';
        }
    }
}
?>

<main class="main-content login-page-layout">
    <section class="login-section">
        <div class="login-container">

            <div class="login-header">
                <h2><i class="fa-solid fa-user-plus"></i> Crear Cuenta</h2>
                <p>Únete a nuestra comunidad y ayuda a darle un hogar a un amiguito.</p>
            </div>

            <?php if (!empty($error_mensaje)): ?>
                <div class="alerta-error">
                    <i class="fa-solid fa-triangle-exclamation alerta-icono"></i>
                    <span class="alerta-texto"><?php echo $error_mensaje; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="form-login" id="formRegistro">

                <div class="grupo-formulario">
                    <label for="nombre"><i class="fa-solid fa-user"></i> Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" class="campo-entrada"
                        placeholder="Ej. María García" required
                        value="<?php echo (!empty($error_mensaje)) ? htmlspecialchars($nombre) : ''; ?>">
                </div>

                <div class="grupo-formulario">
                    <label for="correo"><i class="fa-solid fa-envelope"></i> Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" class="campo-entrada"
                        placeholder="ejemplo@correo.com" required
                        value="<?php echo (!empty($error_mensaje)) ? htmlspecialchars($correo) : ''; ?>">
                </div>

                <div class="grupo-formulario">
                    <label for="contrasena"><i class="fa-solid fa-lock"></i> Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" class="campo-entrada"
                        placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="grupo-formulario">
                    <label for="confirmar"><i class="fa-solid fa-lock"></i> Confirmar Contraseña:</label>
                    <input type="password" id="confirmar" name="confirmar" class="campo-entrada"
                        placeholder="Repite tu contraseña" required>
                </div>

                <div class="formulario-botones">
                    <button type="submit" class="btn btn-primary btn-enviar">
                        <i class="fa-solid fa-user-plus"></i> Registrarme
                    </button>
                </div>
            </form>

            <div class="login-ayuda">
                <p>¿Ya tienes una cuenta? <a href="<?php echo BASE_URL; ?>views/login.php" class="enlace-olvido">Inicia sesión aquí</a></p>
            </div>

        </div>
    </section>
</main>

<?php
include '../includes/footer.php';
?>
