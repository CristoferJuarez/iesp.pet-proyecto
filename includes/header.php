<?php
require_once dirname(__DIR__) . '/config/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public-assets/css/indexador.css">
</head>
<body>

    <header class="main-header">
        <div class="header-container">
            <a href="<?php echo BASE_URL; ?>index.php" class="logo-link">
                <img src="<?php echo BASE_URL; ?>public-assets/img/logo.png" alt="Logo <?php echo SITE_NAME; ?>" class="logo-img" onerror="this.style.display='none'">
                                                                            <!--Cristofer ahi en "onerror="this.style.display='none'"" lo cambias por el logo con img-->
                <span class="logo-text"><?php echo SITE_NAME; ?></span>
            </a>

            <nav class="nav-menu">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
                    <li class="menu-dropdown">
                        <a href="#" class="dropdown-toggle"><i class="fa-solid fa-paw"></i> Catálogo <i class="fa-solid fa-chevron-down fa-xs"></i></a>
                        <ul class="submenu">
                            <li><a href="<?php echo BASE_URL; ?>mascotas/perros.php"><i class="fa-solid fa-dog"></i> Perros</a></li>
                            <li><a href="<?php echo BASE_URL; ?>mascotas/gatos.php"><i class="fa-solid fa-cat"></i> Gatos</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo BASE_URL; ?>views/formulario.php"><i class="fa-solid fa-file-signature"></i> Postular Adopción</a></li>
                    <li><a href="<?php echo BASE_URL; ?>views/contacto.php"><i class="fa-solid fa-envelope"></i> Contacto</a></li>
                    <li><a href="#"><i class="fa-solid fa-hand-holding-heart"></i> Donaciones</a></li>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <li><span class="user-greeting"><i class="fa-solid fa-circle-user"></i> Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span></li>
                        <li><a href="<?php echo BASE_URL; ?>views/logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>views/login.php"><i class="fa-solid fa-circle-user"></i> Iniciar Sesión</a></li>
                        <li><a href="<?php echo BASE_URL; ?>views/registro.php" class="btn-registro"><i class="fa-solid fa-user-plus"></i> Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>