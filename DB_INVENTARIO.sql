CREATE DATABASE APP_INVENTARIO;

USE APP_INVENTARIO;

-- Tabla USUARIO
CREATE TABLE USUARIO (
    EMAIL VARCHAR(200) PRIMARY KEY,
    CONTRASENA VARCHAR(500),
    ROL BOOLEAN
);

-- Tabla PRODUCTO
CREATE TABLE PRODUCTO (
    SN INT PRIMARY KEY,
    NOMBRE VARCHAR(200),
    FECHA_COMPRA DATE,
    PRECIO FLOAT,
    CATEGORIA VARCHAR(200),
    CANTIDAD INT,
    STOCK INT,
    PRESTADO INT,
    MALGASTADO INT
);

-- Inserción de productos en la tabla PRODUCTO con la cantidad, stock, prestado y malgastado corregidos
INSERT INTO PRODUCTO (SN, NOMBRE, FECHA_COMPRA, PRECIO, CATEGORIA, CANTIDAD, STOCK, PRESTADO, MALGASTADO) VALUES
(1, 'MacBook Pro 16"', '2023-05-10', 2500.00, 'Desarrollo Web', 30, 25, 3, 2),
(2, 'Monitor 4K LG 27"', '2022-09-18', 500.00, 'Multimedia', 30, 25, 3, 2),
(3, 'Teclado Mecánico Logitech G Pro', '2023-03-12', 150.00, 'Desarrollo Web', 30, 25, 3, 2),
(4, 'Mouse Inalámbrico Logitech MX Master 3', '2023-02-05', 100.00, 'Desarrollo Web', 30, 25, 3, 2),
(5, 'Servidor DELL PowerEdge R540', '2022-11-01', 4000.00, 'Mantenimiento de Servidores', 30, 25, 3, 2),
(6, 'Cámara Sony Alpha A6400', '2023-01-10', 1200.00, 'Multimedia', 30, 25, 3, 2),
(7, 'Tablet iPad Pro 12.9"', '2023-06-20', 1400.00, 'Desarrollo Web', 30, 25, 3, 2),
(8, 'Micrófono Blue Yeti USB', '2022-12-15', 130.00, 'Multimedia', 30, 25, 3, 2),
(9, 'Servidor NAS Synology DS920+', '2023-07-05', 800.00, 'Mantenimiento de Servidores', 30, 25, 3, 2),
(10, 'Tarjeta Gráfica NVIDIA RTX 3080', '2023-08-30', 1200.00, 'Multimedia', 30, 25, 3, 2);

-- Tabla PRESTAMO
CREATE TABLE PRESTAMO (
    ID_PRESTAMO INT PRIMARY KEY AUTO_INCREMENT,
    EMAIL_PRESTAMISTA VARCHAR(200),
    COD_PRODUCTO INT,
    CANTIDAD INT,
    FECHA_PRESTAMO DATE,
    FECHA_DEVOLUCION DATE,
    USUARIO_PRESTAMO VARCHAR(200),
    ESTADO ENUM('EN ESPERA', 'ACEPTADO', 'DENEGADO', 'ESPERA DEVOLUCION', 'DEVUELTO'),
    -- Clave foránea que refiere a la tabla USUARIO (prestamista)
    CONSTRAINT FK_EMAIL_PRESTAMISTA FOREIGN KEY (EMAIL_PRESTAMISTA) REFERENCES USUARIO(EMAIL),
    -- Clave foránea que refiere a la tabla PRODUCTO
    CONSTRAINT FK_COD_PRODUCTO FOREIGN KEY (COD_PRODUCTO) REFERENCES PRODUCTO(SN),
    -- Clave foránea que refiere a la tabla USUARIO (usuario del préstamo)
    CONSTRAINT FK_USUARIO_PRESTAMO FOREIGN KEY (USUARIO_PRESTAMO) REFERENCES USUARIO(EMAIL)
);