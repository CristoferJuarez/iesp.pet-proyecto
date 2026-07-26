-- 1. Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `refugio_patitas`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE `refugio_patitas`;

-- 2. TABLA: usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id`          INT          AUTO_INCREMENT PRIMARY KEY,
    `nombre`      VARCHAR(100) NOT NULL,
    `correo`      VARCHAR(100) NOT NULL UNIQUE,
    `contrasena`  VARCHAR(255) NOT NULL,                    
    `rol`         ENUM('admin', 'usuario') DEFAULT 'usuario',
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. TABLA: mascotas
CREATE TABLE IF NOT EXISTS `mascotas` (
    `id`          INT          AUTO_INCREMENT PRIMARY KEY,
    `nombre`      VARCHAR(50)  NOT NULL,
    `especie`     ENUM('Perro', 'Gato', 'Otro') NOT NULL,
    `edad`        VARCHAR(30)  NOT NULL,
    `genero`      ENUM('Macho', 'Hembra') NOT NULL,
    `descripcion` TEXT         NOT NULL,
    `imagen`      VARCHAR(150) NOT NULL,                    
    `disponible`  TINYINT(1)   NOT NULL DEFAULT 1,          
    `created_at`  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_especie`    (`especie`),                     
    INDEX `idx_disponible` (`disponible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. TABLA: postulaciones
CREATE TABLE IF NOT EXISTS `postulaciones` (
    `id`               INT          AUTO_INCREMENT PRIMARY KEY,
    `mascota_interes`  VARCHAR(100) NOT NULL DEFAULT 'General',
    `nombre_completo`  VARCHAR(150) NOT NULL,
    `correo`           VARCHAR(100) NOT NULL,
    `telefono`         VARCHAR(20)  NOT NULL,
    `motivo`           TEXT         NOT NULL,
    `estado`           ENUM('Pendiente', 'Aprobado', 'Rechazado') NOT NULL DEFAULT 'Pendiente',
    `created_at`       TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_estado`  (`estado`),
    INDEX `idx_correo`  (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. TABLA: mensajes_contacto
CREATE TABLE IF NOT EXISTS `mensajes_contacto` (
    `id`         INT          AUTO_INCREMENT PRIMARY KEY,
    `nombre`     VARCHAR(150) NOT NULL,
    `correo`     VARCHAR(100) NOT NULL,
    `asunto`     VARCHAR(150) NOT NULL,
    `mensaje`    TEXT         NOT NULL,
    `leido`      TINYINT(1)   NOT NULL DEFAULT 0,           
    `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_leido`  (`leido`),
    INDEX `idx_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. DATOS DE PRUEBA (Seed Data)

-- Usuario de ejemplo
INSERT IGNORE INTO `usuarios` (`nombre`, `correo`, `contrasena`, `rol`) VALUES
('Administrador', 'admin@patitas.org', '$2y$10$RCOtN2CNEH80pu95dP4A8.Dp6IXCqI5LG9kQ9v6iFQasz6HknOzdS', 'admin');

-- Mascotas del catálogo
INSERT IGNORE INTO `mascotas` (`id`, `nombre`, `especie`, `edad`, `genero`, `descripcion`, `imagen`, `disponible`) VALUES
(1, 'Toby',  'Perro', '8 meses', 'Macho',  'Muy juguetón, le encanta correr por la playa de Huanchaco y es súper amigable.', 'toby.jpg',  1),
(2, 'Luna',  'Gato',  '1 año',   'Hembra', 'Tranquila, cariñosa y le fascina dormir cerca de las ventanas soleadas.',         'luna.jpg',  1),
(3, 'Rambo', 'Perro', '2 años',  'Macho',  'Un excelente guardián, fiel y muy obediente. Ideal para familias.',               'rambo.jpg', 0),
(4, 'Mimi',  'Gato',  '3 meses', 'Hembra', 'Una cachorrita llena de energía, rescatada cerca del centro de Trujillo.',        'mimi.jpg',  1);
