<?php
    include 'includes/header.php';
?>

<main class="main-content">
    
    <section class="hero-section">
        <div class="hero-container">
            <h1>Bienvenidos a <?php echo SITE_NAME; ?></h1>
            <p class="hero-tagline">Cambiando vidas patita a patita aquí en Trujillo.</p>
            <p class="hero-description">
                Somos un refugio dedicado al rescate, rehabilitación y adopción responsable de animalitos en situación de abandono. 
                Aquí encontrarás a tu próximo compañero de aventuras.
            </p>
            <div class="hero-buttons">
                <a href="<?php echo BASE_URL; ?>views/catalogo.php" class="btn btn-primary">Ver Mascotas en Adopción</a>
                <a href="<?php echo BASE_URL; ?>views/formulario.php" class="btn btn-secondary">Postular para Adoptar</a>
            </div>
        </div>
    </section>

    <section class="info-section">
        <h2>¿Por qué adoptar de forma responsable?</h2>
        <div class="info-cards">
            <div class="card">
                <h3>Salvas una vida</h3>
                <p>Le das una segunda oportunidad a un perrito o gatito trujillano que lo necesita.</p>
            </div>
            <div class="card">
                <h3>Proceso Formal</h3>
                <p>Evaluamos cada perfil con nuestro formulario dinámico para asegurar el bienestar de la mascota.</p>
            </div>
            <div class="card">
                <h3>Apoyo Constante</h3>
                <p>Te orientamos en todo el proceso de adaptación de tu nuevo engreído en casa.</p>
            </div>
        </div>
    </section>

</main>

<?php
    include 'includes/footer.php';
?>