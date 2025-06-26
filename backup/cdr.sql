


CREATE TABLE conversaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contacto_id INT,
    agente_id INT,
    estado ENUM('abierta', 'cerrada') DEFAULT 'abierta',
    inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fin DATETIME,
    FOREIGN KEY (contacto_id) REFERENCES contactos(id),
    FOREIGN KEY (agente_id) REFERENCES usuarios(id)
);


CREATE TABLE plantillas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    nombre VARCHAR(100),
    contenido TEXT,
    lenguaje VARCHAR(10) DEFAULT 'es',
    creado_por INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id)
);