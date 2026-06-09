-- ============================================================
--  TecnoSoluciones S.A. — Esquema de base de datos
--  Sistema de Gestión de Proyectos
--  MySQL 8.0+ / MariaDB 10.4+ (XAMPP)
--  Motor: InnoDB — requerido para claves foráneas
--  Codificación: utf8mb4 — soporta tildes y caracteres especiales
--  Generado para el curso Backend Developer Web - SENATI
-- ============================================================

-- Crear la base de datos si no existe y seleccionarla
CREATE DATABASE IF NOT EXISTS tecnosoluciones
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tecnosoluciones;

-- Desactivar verificación de FK durante la creación
-- Se reactiva al final del script
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
--  TABLA 1: persona
--  Clase base compartida entre usuario y cliente.
--  Patrón generalización / especialización.
--  Nunca se instancia directamente desde la app.
-- ============================================================

CREATE TABLE persona (
    id_persona      INT             NOT NULL AUTO_INCREMENT,
    nombre          VARCHAR(100)    NOT NULL,
    apellido        VARCHAR(100)    NOT NULL,
    email           VARCHAR(150)    NOT NULL,
    telefono        VARCHAR(20)     DEFAULT NULL,
    direccion       TEXT            DEFAULT NULL,
    fecha_creacion  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_persona         PRIMARY KEY (id_persona),
    CONSTRAINT uq_persona_email   UNIQUE (email)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLA 2: usuario
--  Especialización de persona. Gestiona acceso al sistema.
--  Relación 1:1 con persona (UNIQUE en id_persona).
-- ============================================================

CREATE TABLE usuario (
    id_usuario      INT             NOT NULL AUTO_INCREMENT,
    id_persona      INT             NOT NULL,
    password_hash   VARCHAR(255)    NOT NULL,
    rol             ENUM('admin','empleado')
                                    NOT NULL DEFAULT 'empleado',
    activo          TINYINT(1)      NOT NULL DEFAULT 1,

    CONSTRAINT pk_usuario           PRIMARY KEY (id_usuario),
    CONSTRAINT uq_usuario_persona   UNIQUE (id_persona),
    CONSTRAINT fk_usuario_persona
        FOREIGN KEY (id_persona)
        REFERENCES persona (id_persona)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLA 3: cliente
--  Especialización de persona. Empresas o contactos externos.
--  Relación 1:1 con persona (UNIQUE en id_persona).
--  Una persona puede tener registro en usuario Y cliente a la vez.
-- ============================================================

CREATE TABLE cliente (
    id_cliente      INT             NOT NULL AUTO_INCREMENT,
    id_persona      INT             NOT NULL,
    empresa         VARCHAR(150)    DEFAULT NULL,
    fecha_registro  DATE            NOT NULL DEFAULT (CURRENT_DATE),

    CONSTRAINT pk_cliente           PRIMARY KEY (id_cliente),
    CONSTRAINT uq_cliente_persona   UNIQUE (id_persona),
    CONSTRAINT fk_cliente_persona
        FOREIGN KEY (id_persona)
        REFERENCES persona (id_persona)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLA 4: proyecto
--  Pertenece a un cliente y fue registrado por un usuario.
--  RESTRICT: no se puede eliminar cliente ni usuario
--  si tienen proyectos asociados.
-- ============================================================

CREATE TABLE proyecto (
    id_proyecto             INT             NOT NULL AUTO_INCREMENT,
    id_cliente              INT             NOT NULL,
    id_usuario_creador      INT             NOT NULL,
    nombre                  VARCHAR(200)    NOT NULL,
    descripcion             TEXT            DEFAULT NULL,
    fecha_inicio            DATE            NOT NULL,
    fecha_fin_estimada      DATE            NOT NULL,
    fecha_fin_real          DATE            DEFAULT NULL,
    estado                  ENUM('pendiente','activo','completado','suspendido')
                                            NOT NULL DEFAULT 'pendiente',
    presupuesto             DECIMAL(12,2)   DEFAULT NULL,

    CONSTRAINT pk_proyecto              PRIMARY KEY (id_proyecto),
    CONSTRAINT fk_proyecto_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES cliente (id_cliente)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT fk_proyecto_usuario_creador
        FOREIGN KEY (id_usuario_creador)
        REFERENCES usuario (id_usuario)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT chk_proyecto_fechas
        CHECK (fecha_fin_estimada >= fecha_inicio),
    CONSTRAINT chk_proyecto_fin_real
        CHECK (fecha_fin_real IS NULL OR fecha_fin_real >= fecha_inicio),
    CONSTRAINT chk_proyecto_presupuesto
        CHECK (presupuesto IS NULL OR presupuesto >= 0)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLA 5: tarea
--  Pertenece a un proyecto.
--  CASCADE: si se elimina el proyecto se eliminan sus tareas.
--  RESTRICT: no se puede eliminar al usuario creador si tiene tareas.
-- ============================================================

CREATE TABLE tarea (
    id_tarea                INT             NOT NULL AUTO_INCREMENT,
    id_proyecto             INT             NOT NULL,
    id_usuario_creador      INT             NOT NULL,
    titulo                  VARCHAR(200)    NOT NULL,
    descripcion             TEXT            DEFAULT NULL,
    prioridad               ENUM('baja','media','alta','urgente')
                                            NOT NULL DEFAULT 'media',
    estado                  ENUM('pendiente','en_progreso','completada','cancelada')
                                            NOT NULL DEFAULT 'pendiente',
    fecha_inicio            DATE            DEFAULT NULL,
    fecha_limite            DATE            NOT NULL,
    fecha_completada        DATE            DEFAULT NULL,

    CONSTRAINT pk_tarea                 PRIMARY KEY (id_tarea),
    CONSTRAINT fk_tarea_proyecto
        FOREIGN KEY (id_proyecto)
        REFERENCES proyecto (id_proyecto)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_tarea_usuario_creador
        FOREIGN KEY (id_usuario_creador)
        REFERENCES usuario (id_usuario)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT chk_tarea_fechas
        CHECK (fecha_inicio IS NULL OR fecha_limite >= fecha_inicio),
    CONSTRAINT chk_tarea_completada
        CHECK (fecha_completada IS NULL
               OR fecha_inicio IS NULL
               OR fecha_completada >= fecha_inicio)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLA 6: asignacion
--  Tabla de unión N:M entre tarea y usuario.
--  Diferencia entre quien CREA la tarea (tarea.id_usuario_creador)
--  y quien la EJECUTA (asignacion.id_usuario).
--  UNIQUE compuesto: un usuario no puede asignarse dos veces
--  a la misma tarea.
-- ============================================================

CREATE TABLE asignacion (
    id_asignacion           INT             NOT NULL AUTO_INCREMENT,
    id_tarea                INT             NOT NULL,
    id_usuario              INT             NOT NULL,
    fecha_asignacion        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega_estimada  DATE            DEFAULT NULL,
    observaciones           TEXT            DEFAULT NULL,

    CONSTRAINT pk_asignacion        PRIMARY KEY (id_asignacion),
    CONSTRAINT uq_asignacion_unica  UNIQUE (id_tarea, id_usuario),
    CONSTRAINT fk_asignacion_tarea
        FOREIGN KEY (id_tarea)
        REFERENCES tarea (id_tarea)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_asignacion_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario (id_usuario)
        ON DELETE RESTRICT
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLA 7: reporte
--  Registra la metadata de cada PDF generado por el sistema.
--  id_proyecto e id_cliente son NULL cuando no aplican.
--  SET NULL: si se elimina el proyecto o cliente, el reporte
--  conserva el historial pero pierde la referencia.
-- ============================================================

CREATE TABLE reporte (
    id_reporte          INT             NOT NULL AUTO_INCREMENT,
    id_usuario          INT             NOT NULL,
    id_proyecto         INT             DEFAULT NULL,
    id_cliente          INT             DEFAULT NULL,
    tipo                ENUM('clientes','proyectos','tareas')
                                        NOT NULL,
    fecha_generacion    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    nombre_archivo      VARCHAR(255)    NOT NULL,
    ruta_archivo        VARCHAR(500)    NOT NULL,

    CONSTRAINT pk_reporte           PRIMARY KEY (id_reporte),
    CONSTRAINT fk_reporte_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuario (id_usuario)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT fk_reporte_proyecto
        FOREIGN KEY (id_proyecto)
        REFERENCES proyecto (id_proyecto)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    CONSTRAINT fk_reporte_cliente
        FOREIGN KEY (id_cliente)
        REFERENCES cliente (id_cliente)
        ON DELETE SET NULL
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  ÍNDICES
--  Para columnas usadas frecuentemente en WHERE, JOIN y ORDER BY.
--  Los índices de FK los crea InnoDB automáticamente,
--  estos son adicionales para filtros y búsquedas de la app.
-- ============================================================

-- usuario
CREATE INDEX idx_usuario_activo         ON usuario   (activo);
CREATE INDEX idx_usuario_rol            ON usuario   (rol);

-- cliente
CREATE INDEX idx_cliente_empresa        ON cliente   (empresa);

-- proyecto
CREATE INDEX idx_proyecto_estado              ON proyecto (estado);
CREATE INDEX idx_proyecto_fecha_fin_estimada  ON proyecto (fecha_fin_estimada);

-- tarea
CREATE INDEX idx_tarea_estado           ON tarea (estado);
CREATE INDEX idx_tarea_prioridad        ON tarea (prioridad);
CREATE INDEX idx_tarea_fecha_limite     ON tarea (fecha_limite);

-- reporte
CREATE INDEX idx_reporte_tipo               ON reporte (tipo);
CREATE INDEX idx_reporte_fecha_generacion   ON reporte (fecha_generacion);


-- Reactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
--  FIN DEL ESQUEMA — tecnosoluciones
-- ============================================================
