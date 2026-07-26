<?php
include '../includes/header.php';

// Redireccionamos si el usuario ya ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$error_mensaje = '';

// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';

    // Validación contra la base de datos
    try {
        $sql = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['rol'] = $usuario['rol']; // Guardamos el rol ('admin' o 'usuario')
            
            // Redirigir según el rol del usuario
            if ($usuario['rol'] === 'admin') {
                header('Location: ' . BASE_URL . 'views/admin/panel.php');
            } else {
                header('Location: ' . BASE_URL . 'index.php');
            }
            exit;
        } else {
            $error_mensaje = 'El correo electrónico o la contraseña son incorrectos.';
        }
    } catch (\PDOException $e) {
        error_log("Error en inicio de sesión: " . $e->getMessage());
        $error_mensaje = 'Ocurrió un error al verificar tu cuenta. Por favor, intenta más tarde.';
    }
}
?>

<main class="main-content login-page-layout">
    <section class="login-section">
        <div class="login-container">
            <div class="login-header">
                <h2>Iniciar Sesión</h2>
                <p>Ingresa tus datos para acceder al panel de administración del refugio.</p>
            </div>

            <?php if (!empty($error_mensaje)): ?>
                <div class="alerta-error">
                    <i class="fa-solid fa-triangle-exclamation alerta-icono"></i>
                    <span class="alerta-texto"><?php echo htmlspecialchars($error_mensaje); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="form-login" id="formLogin">
                <div class="grupo-formulario">
                    <label for="correo"><i class="fa-solid fa-envelope"></i> Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" class="campo-entrada" placeholder="ejemplo@correo.com" required value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">
                </div>

                <div class="grupo-formulario">
                    <label for="contrasena"><i class="fa-solid fa-lock"></i> Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" class="campo-entrada" placeholder="Introduce tu contraseña" required>
                </div>

                <div class="login-opciones">
                    <div class="opcion-recordar">
                        <input type="checkbox" id="recordar" name="recordar">
                        <label for="recordar">Recordar mi cuenta</label>
                    </div>
                    <a href="#" class="enlace-olvido">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="formulario-botones">
                    <button type="submit" class="btn btn-secondary btn-enviar"><i class="fa-solid fa-right-to-bracket"></i> Acceder</button>
                </div>
            </form>

            <div class="login-ayuda">
                <p>¿Aún no tienes cuenta? <a href="<?php echo BASE_URL; ?>views/registro.php" class="enlace-olvido">Regístrate aquí</a></p>
            </div>
        </div>
    </section>
</main>

<?php 
include '../includes/footer.php'; 
?>
