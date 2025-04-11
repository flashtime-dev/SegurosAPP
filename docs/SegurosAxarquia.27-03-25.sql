CREATE TABLE Usuario (
        IdUsuario INT PRIMARY KEY AUTO_INCREMENT,
        IdRol INT,
        Nombre VARCHAR(255),
        Email VARCHAR(255),
        Direccion VARCHAR(500),
        Telefono VARCHAR(15),
        Contraseña VARCHAR(255),
        Estado BOOLEAN,
        FOREIGN KEY (IdRol) REFERENCES Rol (IdRol)
);

CREATE TABLE Subusuario (
        IdSubusuario INT,
        IdUsuarioCreador INT,
        PRIMARY KEY (IdSubusuario, IdUsuarioCreador),
        FOREIGN KEY (IdSubusuario) REFERENCES Usuario (IdUsuario),
        FOREIGN KEY (IdUsuarioCreador) REFERENCES Usuario (IdUsuario)
);

CREATE TABLE Rol (
        IdRol INT PRIMARY KEY AUTO_INCREMENT,
        Nombre VARCHAR(255)
);

CREATE TABLE TipoPermiso (
        IdTipoPermiso INT PRIMARY KEY AUTO_INCREMENT,
        Nombre VARCHAR(255)
);

CREATE TABLE Permiso (
        IdPermiso INT PRIMARY KEY AUTO_INCREMENT,
        IdTipoPermiso INT,
        Descripcion VARCHAR(255),
        FOREIGN KEY (IdTipoPermiso) REFERENCES TipoPermiso (IdTipoPermiso)
);

CREATE TABLE RolPermiso (
        IdRol INT,
        IdPermiso INT,
        PRIMARY KEY (IdRol, IdPermiso),
        FOREIGN KEY (IdRol) REFERENCES Rol (IdRol),
        FOREIGN KEY (IdPermiso) REFERENCES Permiso (IdPermiso)
);

CREATE TABLE Comunidad (
        IdComunidad INT PRIMARY KEY AUTO_INCREMENT,
        Nombre VARCHAR(255),
        CIF VARCHAR(15),
        Direccion VARCHAR(255),
        UbicacionCatastral VARCHAR(255),
        RefCatastral VARCHAR(20),
        Telefono VARCHAR(15)
);

CREATE TABLE UsuarioComunidad (
        IdComunidad INT,
        IdUsuario INT,
        PRIMARY KEY (IdComunidad, IdUsuario),
        FOREIGN KEY (IdComunidad) REFERENCES Comunidad (IdComunidad),
        FOREIGN KEY (IdUsuario) REFERENCES Usuario (IdUsuario)
);

CREATE TABLE Caracteristica (
        IdCaracteristica INT PRIMARY KEY AUTO_INCREMENT,
        IdComunidad INT,
        Tipo ENUM('Edificios de Viviendas','Zonas Comunes','Garajes','Vdas Unifamiliares','Oficinas'),
        NumPlantas INT,
        NumViviendas INT,
        Ascensores BOOLEAN,
        Piscina BOOLEAN,
        NumPlantasSotano INT,
        NumLocales INT,
        Garajes BOOLEAN,
        NumEdificios INT,
        NumOficinas INT,
        PistaDeportiva BOOLEAN,
        M2Ubanizacion INT,
        AñoConstruccion YEAR,
        MetrosConstruidos INT,
        FechaRenovacion DATE,
        FOREIGN KEY (IdComunidad) REFERENCES Comunidad (IdComunidad)
);

CREATE TABLE Contacto (
        IdContacto INT PRIMARY KEY AUTO_INCREMENT,
        IdComunidad INT,
        Nombre VARCHAR(255),
        Cargo VARCHAR(100),
        Piso VARCHAR(50),
        Telefono VARCHAR(15),
        FOREIGN KEY (IdComunidad) REFERENCES Comunidad (IdComunidad)
);

CREATE TABLE Presupuesto (
        IdPresupuesto INT PRIMARY KEY AUTO_INCREMENT,
        IdComunidad INT,
        Fecha DATE,
        Observaciones VARCHAR(1000),
        Comentarios VARCHAR(500),
        PDFPresupuesto VARCHAR(255),
        FOREIGN KEY (IdComunidad) REFERENCES Comunidad (IdComunidad)
);

CREATE TABLE Compañia (
        IdCompañia INT PRIMARY KEY AUTO_INCREMENT,
        Nombre VARCHAR(255),
        Email VARCHAR(255)
);

CREATE TABLE TelefonoCompañia (
        IdTelefono INT PRIMARY KEY AUTO_INCREMENT,
        IdCompañia INT,
        Telefono VARCHAR(15),
        Descripcion VARCHAR(255),
        FOREIGN KEY (IdCompañia) REFERENCES Compañia (IdCompañia)
);

CREATE TABLE GarantiaDefecto (
        IdGarantia INT PRIMARY KEY AUTO_INCREMENT,
        IdCompañia INT,
        Incendio VARCHAR(8),
        DañosElectricos VARCHAR(8),
        Robo VARCHAR(8),
        Cristales VARCHAR(8),
        AguaComun BOOLEAN,
        AguaPrivada BOOLEAN,
        DañosEsteticosComunes INT,
        DañosEsteticosPrivados INT,
        RCDañosAgua BOOLEAN,
        Filtraciones INT,
        Desatascos VARCHAR(50),
        FontaneriaSinDaños INT,
        AveriaMaquinaria VARCHAR(50),
        ControlPlagas VARCHAR(50),
        DefensaJuridica INT,
        API BOOLEAN,
        Franquicia INT,
        RequierePeritacion BOOLEAN,
        Observaciones VARCHAR(1000),
        FOREIGN KEY (IdCompañia) REFERENCES Compañia (IdCompañia)
);

CREATE TABLE DesgloseComparativo (
        IdDesglose INT PRIMARY KEY AUTO_INCREMENT,
        IdCompañia INT,
        IdPresupuesto INT,
        Incendio VARCHAR(8),
        DañosElectricos VARCHAR(8),
        Robo VARCHAR(8),
        Cristales VARCHAR(8),
        AguaComun BOOLEAN,
        AguaPrivada BOOLEAN,
        DañosEsteticosComunes INT,
        DañosEsteticosPrivados INT,
        RCDañosAgua BOOLEAN,
        Filtraciones INT,
        Desatascos VARCHAR(50),
        FontaneriaSinDaños INT,
        AveriaMaquinaria VARCHAR(50),
        ControlPlagas VARCHAR(50),
        DefensaJuridica INT,
        API BOOLEAN,
        Franquicia INT,
        RequierePeritacion BOOLEAN,
        Observaciones VARCHAR(1000),
        PrimaTotal DECIMAL(10, 2),
        CapitalRC INT,
        CapitalCtdo INT,
        CapitalCte INT,
        PDFDesglose VARCHAR(255),
        FOREIGN KEY (IdCompañia) REFERENCES Compañia (IdCompañia),
        FOREIGN KEY (IdPresupuesto) REFERENCES Presupuesto (IdPresupuesto)
);

CREATE TABLE Siniestro (
        IdSiniestro INT PRIMARY KEY AUTO_INCREMENT,
        IdPoliza INT,
        Declaracion TEXT,
        Tramitador VARCHAR(255),
        Expediente VARCHAR(50),
        ExpCia VARCHAR(50),
        ExpAsist VARCHAR(50),
        FechaOcurrencia DATE,
        AutoFecha DATE,
        Adjunto BOOLEAN,
        FOREIGN KEY (IdPoliza) REFERENCES Poliza (IdPoliza)
);

CREATE TABLE SiniestroContacto (
        IdContacto INT,
        IdSiniestro INT,
        PRIMARY KEY (IdContacto, IdSiniestro),
        FOREIGN KEY (IdContacto) REFERENCES Contacto (IdContacto),
        FOREIGN KEY (IdSiniestro) REFERENCES Siniestro (IdSiniestro)
);

CREATE TABLE Poliza (
        IdPoliza INT PRIMARY KEY AUTO_INCREMENT,
        IdCompañia INT,
        IdComunidad INT,
        IdAgente INT,
        Alias VARCHAR(20),
        Numero VARCHAR(20),
        FechaEfecto DATE,
        Cuenta VARCHAR(24),
        FormaPago ENUM('Bianual', 'Anual', 'Semestral', 'Trimestral', 'Mensual'),
        PrimaNeta DECIMAL(10, 2),
        PrimaTotal DECIMAL(10, 2),
        PDFPoliza VARCHAR(255),
        Observaciones TEXT,
        Estado ENUM('En Vigor','Anulada','Solicitada','Externa','Vencida'),
        FechaCreacion DATETIME,
        FOREIGN KEY (IdCompañia) REFERENCES Compañia (IdCompañia),
        FOREIGN KEY (IdComunidad) REFERENCES Comunidad (IdComunidad),
        FOREIGN KEY (IdAgente) REFERENCES Agente (IdAgente)
);

CREATE TABLE Recibo (
        IdRecibo INT PRIMARY KEY AUTO_INCREMENT,
        IdPoliza INT,
        NumRecibo VARCHAR(20),
        FechaRecibo DATE,
        Estado ENUM('Pendiente','Pagado','Anulado','Devuelto'),
        PrimaNeta DECIMAL(10, 2),
        PrimaTotal DECIMAL(10, 2),
        Observaciones VARCHAR(1000),
        FOREIGN KEY (IdPoliza) REFERENCES Poliza (IdPoliza)
);

CREATE TABLE ChatPoliza (
        IdChat INT PRIMARY KEY AUTO_INCREMENT,
        IdPoliza INT,
        IdUsuario INT,
        Mensaje VARCHAR(1000),
        FechaCreacion DATETIME,
        Adjunto VARCHAR(255),
        FOREIGN KEY (IdPoliza) REFERENCES Poliza (IdPoliza),
        FOREIGN KEY (IdUsuario) REFERENCES Usuario (IdUsuario)
);

CREATE TABLE AdjuntoPoliza (
        IdAdjunto INT PRIMARY KEY AUTO_INCREMENT,
        IdChat INT,
        Nombre VARCHAR(255),
        URL VARCHAR(255),
        FechaCreacion DATETIME,
        FOREIGN KEY (IdChat) REFERENCES ChatPoliza (IdChat)
);

CREATE TABLE ChatSiniestro (
        IdChat INT PRIMARY KEY AUTO_INCREMENT,
        IdSiniestro INT,
        IdUsuario INT,
        Mensaje VARCHAR(1000),
        FechaCreacion DATETIME,
        Adjunto VARCHAR(255),
        FOREIGN KEY (IdSiniestro) REFERENCES Siniestro (IdSiniestro),
        FOREIGN KEY (IdUsuario) REFERENCES Usuario (IdUsuario)
);

CREATE TABLE AdjuntoSiniestro (
        IdAdjunto INT PRIMARY KEY AUTO_INCREMENT,
        IdSiniestro INT,
        IdChat INT,
        Nombre VARCHAR(255),
        URL VARCHAR(255),
        FechaCreacion DATETIME,
        FOREIGN KEY (IdSiniestro) REFERENCES Siniestro (IdSiniestro),
        FOREIGN KEY (IdChat) REFERENCES ChatSiniestro (IdChat)
);

CREATE TABLE Agente (
        IdAgente INT PRIMARY KEY AUTO_INCREMENT,
        Nombre VARCHAR(255),
        Telefono VARCHAR(15),
        Email VARCHAR(255)
);