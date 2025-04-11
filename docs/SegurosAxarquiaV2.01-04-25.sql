-- CREACIÓN DE TABLAS SIN CLAVES FORÁNEAS
-- Base de datos: SegurosAxarquia
-- Fecha: 25/04/2024
-- Autor: Cristina Vacas López
-- Descripción: Creación de tablas de la base de datos Seguros Axarquía, incluyendo datos de prueba.
-- Se han añadido las claves foráneas mediante ALTER TABLE para evitar problemas de dependencia circular al crear las tablas.

CREATE TABLE Usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rol INT DEFAULT 0 NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    email_verified_at timestamp,
    direccion VARCHAR(500),
    telefono VARCHAR(15),
    password VARCHAR(255) NOT NULL,
    remember_token varchar(100) DEFAULT NULL,
    estado BOOLEAN DEFAULT 0 NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL
);

-- DATOS DE PRUEBA TABLA USUARIOS
INSERT INTO Usuarios (id, id_rol, nombre, email, email_verified_at, direccion, telefono, password, remember_token, estado) VALUES
(1, 1, 'Seguros Axarquía', 'segurosaxarquia@gmail.com', NULL, NULL, NULL, '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', NULL, 1),
(2, 3, 'AXARQUIA', 'info@administraciones-axarquia.es', NULL, NULL, NULL, '$2a$07$azybxcags23425sdg23sdeanQZqjaf6Birm2NvcYTNtJw24CsO5uq', NULL, 0),
(3, 1, 'David', 'www.jaradavid@gmail.com', NULL, NULL, NULL, '$2y$12$AMIOywqS1DroFH.npTdTX..Fm0bR4L8jReE/USQorppkJY3J2Hbku', NULL, 1),
(4, 1, 'Cris', 'cvaclop1911@g.educaand.es', NULL, NULL, NULL, '$2y$12$rgtV3ICWwDBYwZXDItexE.vgJy95F56fCs3EuOthf1LFN/NgujBQ.', NULL, 1);

CREATE TABLE Subusuarios (
    id INT,
    id_usuario_creador INT,
    PRIMARY KEY (id, id_usuario_creador)
);

CREATE TABLE Roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL
);

-- DATOS DE PRUEBA TABLA ROLES
INSERT INTO Roles (id, nombre) VALUES
(0, 'Sin rol'),
(1, 'Superadministrador'),
(2, 'Administrador'),
(3, 'Usuario');

CREATE TABLE TiposPermisos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL
);

-- DATOS DE PRUEBA TABLA TIPOS PERMISOS
INSERT INTO TiposPermisos (id, nombre) VALUES
(0, 'Otros permisos'),
(1, 'Polizas'),
(2, 'Siniestros'),
(3, 'Comunidad'),
(4, 'Usuarios'),
(5, 'Agentes'),
(6, 'Companias'),
(7, 'Presupuestos'),
(8, 'Contactos'),
(9, 'Caracteristicas'),
(10, 'Recibos'),
(11, 'ChatsPolizas'),
(12, 'AdjuntosPolizas'),
(13, 'ChatsSiniestros'),
(14, 'AdjuntosSiniestros');

CREATE TABLE Permisos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_tipo INT,
    nombre VARCHAR(255) NOT NULL
);

-- DATOS DE PRUEBA TABLA PERMISOS
INSERT INTO Permisos (id, id_tipo, nombre) VALUES
(1, 1, 'Editar polizas'),
(2, 1, 'Eliminar polizas'),
(3, 1, 'Crear polizas'),
(4, 1, 'Ver polizas');

CREATE TABLE RolesPermisos (
    id_rol INT,
    id_permiso INT,
    PRIMARY KEY (id_rol, id_permiso)
);

-- DATOS DE PRUEBA TABLA ROLES PERMISOS
INSERT INTO RolesPermisos (id_rol, id_permiso) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4);

CREATE TABLE Comunidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    cif VARCHAR(15) NOT NULL UNIQUE,
    direccion VARCHAR(255),
    ubi_catastral VARCHAR(255),
    ref_catastral VARCHAR(20),
    telefono VARCHAR(15)
);

-- DATOS DE PRUEBA TABLA COMUNIDADES
INSERT INTO Comunidades (id, nombre, cif, direccion, ubi_catastral, ref_catastral, telefono) VALUES
(1, 'Comunidad de Propietarios Verdiales 16', 'H29420825', 'Verdiales 16', 'Verdiales', '2777301VF0627N', '952 123 456'),
(2, 'Comunidad de Propietarios Verdiales 12', 'H29589348', 'Verdiales 12', 'Verdiales', NULL, NULL),
(3, 'Com Prop edf Farosur', 'H92226497', NULL, NULL, NULL, NULL),
(4, 'Com Prop Nuestra señora de los hogares 2', 'H92523547', NULL, NULL, NULL, NULL),
(5, 'Com prop Altos del Tomillar', 'H92943356', NULL, NULL, NULL, NULL);

CREATE TABLE UsuariosComunidades (
    id_usuario INT,
    id_comunidad INT,
    PRIMARY KEY (id_usuario, id_comunidad)
);

-- DATOS DE PRUEBA TABLA USUARIOS COMUNIDADES
INSERT INTO UsuariosComunidades (id_comunidad, id_usuario) VALUES
(1, 2),
(2, 2),
(3, 2);

CREATE TABLE Caracteristicas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_comunidad INT NOT NULL,
    tipo ENUM('Edificios de Viviendas','Zonas Comunes','Garajes','Vdas Unifamiliares','Oficinas') NOT NULL,
    num_plantas INT DEFAULT 0 NOT NULL,
    num_viviendas INT DEFAULT 0 NOT NULL,
    ascensores BOOLEAN DEFAULT 0 NOT NULL,
    piscina BOOLEAN DEFAULT 0 NOT NULL,
    num_plantas_sotano INT DEFAULT 0 NOT NULL,
    num_locales INT DEFAULT 0 NOT NULL,
    garajes BOOLEAN DEFAULT 0 NOT NULL,
    num_edificios INT DEFAULT 0 NOT NULL,
    num_oficinas INT DEFAULT 0 NOT NULL,
    pista_deportiva BOOLEAN DEFAULT 0 NOT NULL,
    m2_urbanizacion INT DEFAULT 0 NOT NULL,
    anio_construccion YEAR DEFAULT 0 NOT NULL,
    metros_construidos INT DEFAULT 0 NOT NULL,
    fecha_renovacion DATE DEFAULT NULL
);

-- DATOS DE PRUEBA TABLA CARACTERISTICAS
INSERT INTO Caracteristicas (id, id_comunidad, tipo, num_plantas, num_viviendas, ascensores, piscina, num_plantas_sotano, num_locales, garajes, num_edificios, num_oficinas, pista_deportiva, m2_urbanizacion, anio_construccion, metros_construidos) VALUES
(1, 1, 'Edificios de Viviendas', 3, 12, 1, 1, 1, 0, 1, 1, 0, 0, 2000, 2005, 1500),
(2, 2, 'Edificios de Viviendas', 4, 20, 2, 1, 1, 0, 1, 1, 0, 0, 3000, 2010, 2500),
(3, 3, 'Zonas Comunes', 2, 10, 1, 0, 1, 0, 1, 1, 0, 1, 1500, 2015, 1200),
(4, 4, 'Garajes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, 'Vdas Unifamiliares', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

CREATE TABLE Contactos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_comunidad INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    cargo VARCHAR(100),
    piso VARCHAR(50),
    telefono VARCHAR(15) NOT NULL
);

CREATE TABLE Presupuestos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_comunidad INT NOT NULL,
    fecha DATE NOT NULL,
    observaciones VARCHAR(1000) NOT NULL,
    comentarios VARCHAR(500),
    pdf_presupuesto VARCHAR(255) NOT NULL
);

CREATE TABLE Companias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    url_logo VARCHAR(255) NOT NULL
);

-- DATOS DE PRUEBA TABLA COMPAÑIAS 
INSERT INTO Companias (id, nombre, url_logo) VALUES
(1, 'Allianz', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Allianz.png'),
(2, 'Axa', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Axa.png'),
(3, 'Caser', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Caser.png'),
(4, 'Fiact', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Fiact.png'),
(5, 'Generali', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Generali.png'),
(6, 'GeneraliOn', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/GeneraliOn.png'),
(7, 'Helvetia', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Helvetia.png'),
(8, 'Mapfre', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Mapfre.png'),
(9, 'MGS', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/MGS.png'),
(10, 'Mutua de propietarios', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Mutua.png'),
(11, 'Ocaso', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Ocaso.png'),
(12, 'Occident', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Occident.png'),
(13, 'Pelayo', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Pelayo.png'),
(14, 'Preventiva', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Preventiva.png'),
(15, 'Reale', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Reale.png'),
(16, 'Santa Lucía', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/SantaLucia.png'),
(17, 'Zurich', 'https://segurosaxarquia.com/wp-content/uploads/2024/12/Zurich.png'),
(18, 'Patria Hispana', 'https://segurosaxarquia.com/wp-content/uploads/2025/01/Patria.png');

CREATE TABLE TelefonosCompanias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_companias INT NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    descripcion VARCHAR(255) NOT NULL
);

-- DATOS DE PRUEBA TABLA TELEFONOS COMPAÑIAS
INSERT INTO TelefonosCompanias (id, id_companias, telefono, descripcion) VALUES
(1, 1, '638930465', 'Asistencia'),
(2, 1, '913255258', 'Allianz'),
(3, 1, '900103080', 'Diversos'),
(4, 2, '917286710', 'Asistencia'),
(5, 3, '915955455', 'Asistencia'),
(6, 4, '912721923', 'Asistencia'),
(7, 5, '913601423', 'Asistencia'),
(8, 6, '900243657', 'Asistencia'),
(9, 7, '913939057', 'Asistencia'),
(10, 7, '917697258', 'Control de Plagas'),
(11, 8, '918365365', 'Asistencia'),
(12, 9, '917572404', 'Asistencia'),
(13, 10, '918271530', 'Asistencia'),
(14, 11, '917039009', 'Asistencia'),
(15, 12, '932220212', 'Asistencia'),
(16, 13, '915200500', 'Asistencia'),
(17, 14, '900203010', 'Asistencia'),
(18, 14, '915160500', 'Asistencia Fijo'),
(19, 15, '914547400', 'Asistencia'),
(20, 16, '900242020', 'Asistencia'),
(21, 17, '934165046', 'Asistencia'),
(22, 18, '911774558', 'Asistencia');

CREATE TABLE GarantiasDefectos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_companias INT NOT NULL,
    incendio VARCHAR(8),
    daños_electricos VARCHAR(8),
    robo VARCHAR(8),
    cristales VARCHAR(8),
    agua_comun BOOLEAN,
    agua_privada BOOLEAN,
    danios_esteticos_comunes INT,
    danios_esteticos_privados INT,
    rc_daños_agua BOOLEAN,
    filtraciones INT,
    desatascos VARCHAR(50),
    fontaneria_sin_danios INT,
    averia_maquinaria VARCHAR(50),
    control_plagas VARCHAR(50),
    defensa_juridica INT,
    tiene_api BOOLEAN,
    franquicia INT,
    requiere_peritacion BOOLEAN,
    observaciones VARCHAR(1000)
);

-- DATOS DE PRUEBA TABLA GARANTIAS DEFECTOS
INSERT INTO GarantiasDefectos (
    id, id_companias, incendio, daños_electricos, robo, cristales, agua_comun, agua_privada, 
    danios_esteticos_comunes, danios_esteticos_privados, rc_daños_agua, filtraciones, desatascos, 
    fontaneria_sin_danios, averia_maquinaria, control_plagas, defensa_juridica, tiene_api, franquicia, 
    requiere_peritacion, observaciones
) VALUES
(1, 1, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, NULL, TRUE, NULL, '1 al año máx 500€', NULL, NULL, '1 al año', 6000, TRUE, 250, TRUE, 'No atienden a perjudicados. Éstos deben dar parte a su seguro privado y luego reclamar al comunitario. Incluye Rc Junta Rectora, cubre las tuberías vistas, subterráneas.'),
(2, 2, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, NULL, TRUE, NULL, '2 al año máx 500€', NULL, NULL, '1 al año', 6000, TRUE, 150, FALSE, 'Incluye Rc Junta Rectora, cubre tuberías vistas e incluye hasta 300€ en la reparación de tuberías corrosivas y presenta adelanto de cuotas.'),
(3, 3, '100%', '30.000€', '100%', '5.000€', TRUE, FALSE, 3000, NULL, TRUE, 2, '600€ siniestro y año', 250, NULL, '1 al año', 3000, FALSE, NULL, FALSE, 'Rc Junta Rectora, cubre tuberías vistas, hasta 300€ en caso de corrosión, exceso de consumo de agua. Dos filtraciones por portal. Reclamación de impagados. Las tuberías exteriores subterráneas están EXCLUIDAS.'),
(4, 4, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, NULL, TRUE, NULL, '600€ siniestro y año', NULL, NULL, '1 al año', 3000, TRUE, NULL, FALSE, 'Incluye Rc Junta Rectora, cubre tuberías vistas e incluye hasta 300€ en la reparación de tuberías corrosivas y presenta adelanto de cuotas.'),
(5, 5, '100%', '100%', '100%', '3.000€', TRUE, FALSE, 3000, NULL, TRUE, NULL, '500€ por siniestro 1000€ año', NULL, NULL, '1 al año', 6000, TRUE, NULL, FALSE, 'Incluye Rc Junta Rectora, cubre tuberías vistas e incluye hasta 300€ en la reparación de tuberías corrosivas y presenta adelanto de cuotas.'),
(6, 6, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, NULL, TRUE, 500, '600€ año por portal', 10000, NULL, '1 al año', 6100, TRUE, 120, FALSE, 'Incluye Rc junta rectora, cubre tuberías vistas y tuberías subterráneas.'),
(7, 7, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, NULL, TRUE, NULL, 'ilimitados 600€ por atasco', NULL, NULL, 'Ilimitados', 6000, TRUE, 180, TRUE, 'Incluye Rc Junta Rectora, cubre tuberías vistas e incluye hasta 300€ en la reparación de tuberías corrosivas. Tiene tanto atascos ilimitados como control de plagas ilimitado y presenta adelanto de cuotas.'),
(8, 8, '100%', '100%', '100%', '100%', TRUE, FALSE, 6000, 1800, TRUE, NULL, '600€ Siniestro y año', NULL, NULL, '1 al año', 4500, TRUE, NULL, FALSE, 'Incluye Rc Junta Rectora, hasta 300€ en caso de corrosión y exceso de consumo de agua. No cubre tuberías subterráneas como riego o piscina.'),
(9, 9, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, 3000, TRUE, NULL, '600€ Siniestro y año', NULL, NULL, '1 al año', 6100, FALSE, 150, FALSE, 'Incluye daños en buzones hasta 300€.'),
(10, 10, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, 3000, TRUE, NULL, '500€ Siniestro 1000€ año', NULL, NULL, '1 al año', 6000, TRUE, NULL, TRUE, 'Rc Junta Rectora, Exceso consumo de agua 2.000 € / Cubre tuberías vistas / Roturas privativas / Se cubre limpieza de grafitis.'),
(11, 11, '100%', '100%', '100%', '100%', TRUE, FALSE, 6000, 2000, TRUE, TRUE, '1 al año/ max 1500€ por portal', 750, NULL, 'Ilimitados', 6000, TRUE, 300, TRUE, 'Incluye Rc Junta Rectora, exceso de consumo de agua y adelanto de cuotas.'),
(12, 12, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, 1500, TRUE, NULL, '600€ Siniestro y año', NULL, NULL, '1 al año', 6000, TRUE, NULL, FALSE, 'Incluye Rc Junta Rectora, cubre tuberías vistas, tuberías subterráneas y exceso de consumo de agua.'),
(13, 13, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, 3000, TRUE, NULL, '500€ Siniestro 1000€ año', NULL, NULL, '1 al año', 6000, TRUE, NULL, TRUE, 'Rc Junta Rectora, Exceso consumo de agua 2.000 € / Cubre tuberías vistas / Roturas privativas / Se cubre limpieza de grafitis.'),
(14, 14, '100%', '100%', '100%', '100%', TRUE, FALSE, 1500, 600, TRUE, NULL, '600€ Siniestro y año', NULL, NULL, '1 al año', 6000, TRUE, NULL, TRUE, 'Incluye Rc Junta Rectora, exceso de consumo de agua y adelanto de cuotas.'),
(15, 15, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, 3000, TRUE, TRUE, 'Ilimitados 600€ siniestro', 300, 2500, '1 al año', 6000, TRUE, 200, FALSE, 'Rc Junta Rectora, cubre tuberías vistas, hasta 300€ en la reparación de tuberías corrosivas, exceso de consumo de agua y adelanto de cuotas.'),
(16, 16, '100%', '10%', '10%', '10%', TRUE, FALSE, 3000, 600, TRUE, NULL, '1 al año por portal al 100%', NULL, NULL, '1 al año', 5000, FALSE, 400, FALSE, 'Rc Junta Rectora, Desatasco no reembolsable, atendidos por la compañía. No tuberías subterráneas. Los daños eléctricos no cubren ascensores. Exceso de consumo de agua.'),
(17, 17, '100%', '100%', '100%', '100%', TRUE, FALSE, 3000, 3000, TRUE, NULL, '500€ al año', NULL, NULL, '1 al año', 6000, TRUE, 150, TRUE, 'Incluimos ayuda a tuberías corrosivas. Incluye Rc Junta Rectora y tuberías subterráneas.');

CREATE TABLE DesglosesComparativos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_companias INT NOT NULL,
    id_presupuesto INT NOT NULL,
    incendio VARCHAR(8),
    daños_electricos VARCHAR(8),
    robo VARCHAR(8),
    cristales VARCHAR(8),
    agua_comun BOOLEAN,
    agua_privada BOOLEAN,
    danios_esteticos_comunes INT,
    danios_esteticos_privados INT,
    rc_daños_agua BOOLEAN,
    filtraciones INT,
    desatascos VARCHAR(50),
    fontaneria_sin_danios INT,
    averia_maquinaria VARCHAR(50),
    control_plagas VARCHAR(50),
    defensa_juridica INT,
    tiene_api BOOLEAN,
    franquicia INT,
    requiere_peritacion BOOLEAN,
    observaciones VARCHAR(1000),
    prima_total DECIMAL(10, 2),
    capital_rc INT,
    capital_ctdo INT,
    capital_cte INT,
    pdf_desglose VARCHAR(255)
);

CREATE TABLE Siniestros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_poliza INT NOT NULL,
    declaracion TEXT NOT NULL,
    tramitador VARCHAR(255),
    expediente VARCHAR(50) NOT NULL,
    exp_cia VARCHAR(50),
    exp_asist VARCHAR(50),
    fecha_ocurrencia DATE,
    adjunto BOOLEAN
);

CREATE TABLE SiniestrosContactos (
    id_contacto INT,
    id_siniestro INT,
    PRIMARY KEY (id_contacto, id_siniestro)
);

CREATE TABLE Polizas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_compania INT NOT NULL,
    id_comunidad INT NOT NULL,
    id_agente INT NOT NULL,
    alias VARCHAR(20),
    numero VARCHAR(20) NOT NULL,
    fecha_efecto DATE NOT NULL,
    cuenta VARCHAR(24),
    forma_pago ENUM('Bianual', 'Anual', 'Semestral', 'Trimestral', 'Mensual') NOT NULL,
    prima_neta DECIMAL(10, 2) NOT NULL,
    prima_total DECIMAL(10, 2) NOT NULL,
    pdf_poliza VARCHAR(255),
    observaciones TEXT,
    estado ENUM('En Vigor','Anulada','Solicitada','Externa','Vencida') NOT NULL
);

-- DATOS DE PRUEBA TABLA POLIZAS
INSERT INTO Polizas (id, id_compania, id_comunidad, id_agente, alias, numero, fecha_efecto, cuenta, forma_pago, prima_neta, prima_total, pdf_poliza, observaciones, estado) VALUES
(1, 15, 1, 1, '111', '2200009156', '2023-02-01', '0081 7430 48 0001218325', 'Trimestral', 1100.00, 1160.00, NULL, NULL, 'En Vigor'),
(2, 16, 2, 1, '154', '137161', '2024-11-26', '0081 7431 99 0001744177', 'Semestral', 1000.00, 1200.00, NULL, NULL, 'En Vigor'),
(3, 8, 3, 1, '466', '2000004146', '2020-03-11', '0081 7431 96 0001744683', 'Anual', 1193.00, 1374.00, NULL, NULL, 'En Vigor'),
(4, 15, 4, 1, '486', '2000008685', '2021-12-04', NULL , 'Anual', 250.00 ,341.00 ,NULL ,NULL ,'En Vigor'),
(5 ,15 ,5 ,1 ,'487' ,'2000008683' ,'2021-06-04' ,'3023 0417 77 5654735207' ,'Uníco/No Renueva' ,2200.00 ,2433.00 ,NULL ,NULL ,'Anulada');

CREATE TABLE Recibos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_poliza INT NOT NULL,
    num_recibo VARCHAR(20),
    fecha_recibo DATE NOT NULL,
    estado ENUM('Pendiente','Pagado','Anulado','Devuelto') NOT NULL,
    prima_neta DECIMAL(10, 2) NOT NULL,
    prima_total DECIMAL(10, 2) NOT NULL,
    observaciones VARCHAR(1000)
);

-- DATOS DE PRUEBA TABLA RECIBOS
INSERT INTO Recibos (id, id_poliza, num_recibo, fecha_recibo, estado, prima_neta, prima_total, observaciones) VALUES
(1, 1, '0', '2025-02-01', 'Pagado', 241.91, 250.00, NULL),
(2, 2, '0', '2024-11-26', 'Pendiente', 200.00, 220.00, NULL),
(3, 3, '0', '2024-03-11', 'Pendiente', 200.00, 220.00, NULL),
(4, 4, '0', '2024-12-04', 'Pendiente', 200.00, 220.00, NULL),
(5 ,5 ,'0' ,'2024-06-04' ,'Pendiente' ,200.00 ,220.00 ,NULL);

CREATE TABLE ChatsPolizas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_poliza INT NOT NULL,
    id_usuario INT NOT NULL,
    mensaje VARCHAR(1000) NOT NULL,
    adjunto BOOLEAN DEFAULT 0 NOT NULL
);

CREATE TABLE AdjuntosPolizas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_chat INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    url_adjunto VARCHAR(255) NOT NULL
);

CREATE TABLE ChatsSiniestros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_siniestro INT NOT NULL,
    id_usuario INT NOT NULL,
    mensaje VARCHAR(1000) NOT NULL,
    adjunto BOOLEAN DEFAULT 0 NOT NULL
);

CREATE TABLE AdjuntosSiniestros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_siniestro INT NOT NULL,
    id_chat INT,
    nombre VARCHAR(255) NOT NULL,
    url_adjunto VARCHAR(255) NOT NULL
);

CREATE TABLE Agentes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    telefono VARCHAR(15),
    email VARCHAR(255)
);

-- DATOS DE PRUEBA TABLA AGENTES
INSERT INTO Agentes (id, nombre, telefono, email) VALUES
(1,'Seguros Axarquía','616311488',NULL);

-- AGREGAR CLAVES FORÁNEAS MEDIANTE ALTER TABLE

ALTER TABLE Usuarios
    ADD CONSTRAINT fk_usuarios_id_rol FOREIGN KEY (id_rol) REFERENCES Roles (id_rol);

ALTER TABLE Subusuarios
    ADD CONSTRAINT fk_subusuarios_id FOREIGN KEY (id) REFERENCES Usuarios (id),
    ADD CONSTRAINT fk_subusuarios_id_usuario_creador FOREIGN KEY (id_usuario_creador) REFERENCES Usuarios (id);

ALTER TABLE Permisos
    ADD CONSTRAINT fk_permisos_id_tipo FOREIGN KEY (id_tipo) REFERENCES TiposPermisos (id);

ALTER TABLE RolesPermisos
    ADD CONSTRAINT fk_rolespermisos_id FOREIGN KEY (id) REFERENCES Roles (id_rol),
    ADD CONSTRAINT fk_rolespermisos_id_permiso FOREIGN KEY (id_permiso) REFERENCES Permisos (id);

ALTER TABLE UsuariosComunidades
    ADD CONSTRAINT fk_usuarioscomunidades_id_comunidad FOREIGN KEY (id_comunidad) REFERENCES Comunidades (id),
    ADD CONSTRAINT fk_usuarioscomunidades_id_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios (id);

ALTER TABLE Caracteristicas
    ADD CONSTRAINT fk_caracteristicas_id_comunidad FOREIGN KEY (id_comunidad) REFERENCES Comunidades (id);

ALTER TABLE Contactos
    ADD CONSTRAINT fk_contactos_id_comunidad FOREIGN KEY (id_comunidad) REFERENCES Comunidades (id);

ALTER TABLE Presupuestos
    ADD CONSTRAINT fk_presupuestos_id_comunidad FOREIGN KEY (id_comunidad) REFERENCES Comunidades (id);

ALTER TABLE TelefonosCompanias
    ADD CONSTRAINT fk_telefonoscompanias_id_compania FOREIGN KEY (id_companias) REFERENCES Companias (id);

ALTER TABLE GarantiasDefectos
    ADD CONSTRAINT fk_garantiasdefectos_id_compania FOREIGN KEY (id_companias) REFERENCES Companias (id);

ALTER TABLE DesglosesComparativos
    ADD CONSTRAINT fk_desglosescomparativos_id_compania FOREIGN KEY (id_companias) REFERENCES Companias (id),
    ADD CONSTRAINT fk_desglosescomparativos_id_presupuesto FOREIGN KEY (id_presupuesto) REFERENCES Presupuestos (id);

ALTER TABLE Siniestros
    ADD CONSTRAINT fk_siniestros_id_poliza FOREIGN KEY (id_poliza) REFERENCES Polizas (id);

ALTER TABLE SiniestrosContactos
    ADD CONSTRAINT fk_siniestroscontactos_id_contacto FOREIGN KEY (id_contacto) REFERENCES Contactos (id),
    ADD CONSTRAINT fk_siniestroscontactos_id_siniestro FOREIGN KEY (id_siniestro) REFERENCES Siniestros (id);

ALTER TABLE Polizas
    ADD CONSTRAINT fk_polizas_id_compania FOREIGN KEY (id_compania) REFERENCES Companias (id),
    ADD CONSTRAINT fk_polizas_id_comunidad FOREIGN KEY (id_comunidad) REFERENCES Comunidades (id),
    ADD CONSTRAINT fk_polizas_id_agente FOREIGN KEY (id_agente) REFERENCES Agentes (id);

ALTER TABLE Recibos
    ADD CONSTRAINT fk_recibos_id_poliza FOREIGN KEY (id_poliza) REFERENCES Polizas (id);

ALTER TABLE ChatsPolizas
    ADD CONSTRAINT fk_chatspolizas_id_poliza FOREIGN KEY (id_poliza) REFERENCES Polizas (id),
    ADD CONSTRAINT fk_chatspolizas_id_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios (id);

ALTER TABLE AdjuntosPolizas
    ADD CONSTRAINT fk_adjuntos_polizas_id_chat FOREIGN KEY (id_chat) REFERENCES ChatsPolizas (id);

ALTER TABLE ChatsSiniestros
    ADD CONSTRAINT fk_chatssiniestros_id_siniestro FOREIGN KEY (id_siniestro) REFERENCES Siniestros (id),
    ADD CONSTRAINT fk_chatssiniestros_id_usuario FOREIGN KEY (id_usuario) REFERENCES Usuarios (id);

ALTER TABLE AdjuntosSiniestros
    ADD CONSTRAINT fk_adjuntossiniestros_id_siniestro FOREIGN KEY (id_siniestro) REFERENCES Siniestros (id),
    ADD CONSTRAINT fk_adjuntossiniestros_id_chat FOREIGN KEY (id_chat) REFERENCES ChatsSiniestros (id);
