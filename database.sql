-- Remove existing database
DROP DATABASE IF EXISTS taskgroup;

-- Create a new database
CREATE DATABASE IF NOT EXISTS taskgroup;

-- Remove existing users
DROP USER IF EXISTS 'tguser'@'localhost';

-- Create a new user and grant privileges
CREATE USER 'tguser'@'localhost' IDENTIFIED BY 'tgpass';
GRANT ALL PRIVILEGES ON taskgroup.* TO 'tguser'@'localhost' WITH GRANT OPTION;

-- Use the newly created database
USE taskgroup;

-- Create tables

-- Users table
CREATE TABLE users (
    username VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    PRIMARY KEY (username)
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Projects table
CREATE TABLE projects (
    project_id INT AUTO_INCREMENT,
    project_name VARCHAR(255) NOT NULL,
    project_description TEXT NULL,
    project_owner VARCHAR(255) NOT NULL,
    project_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (project_id),
    FOREIGN KEY (project_owner) REFERENCES users(username) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;

-- Project members table
CREATE TABLE project_members (
    project_id INT,
    username VARCHAR(255),
    PRIMARY KEY (project_id, username),
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
) ENGINE=INNODB DEFAULT CHARACTER SET = utf8;


-- Example data insertion
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', 'adminpass');
INSERT INTO users (username, email, password) VALUES
('user1', 'user1@example.com', 'user1pass');

-- Insertar más usuarios
INSERT INTO users (username, email, password) VALUES
('maria_garcia', 'maria.garcia@example.com', 'password123'),
('carlos_lopez', 'carlos.lopez@example.com', 'password123'),
('ana_martinez', 'ana.martinez@example.com', 'password123'),
('pedro_rodriguez', 'pedro.rodriguez@example.com', 'password123'),
('laura_sanchez', 'laura.sanchez@example.com', 'password123');

-- Insertar proyectos
INSERT INTO projects (project_name, project_description, project_owner) VALUES
('Desarrollo App Móvil', 'Aplicación de gestión de tareas para iOS y Android', 'admin'),
('Sitio Web Corporativo', 'Rediseño del sitio web principal de la empresa', 'maria_garcia'),
('Campanha Marketing Q4', 'Estrategia de marketing para el último trimestre del año', 'carlos_lopez'),
('Investigación de Mercado', 'Análisis de competencia y tendencias del sector', 'ana_martinez'),
('Migración a Cloud', 'Traslado de servidores locales a infraestructura cloud', 'admin');

-- Insertar miembros en los proyectos
-- Proyecto 1: Desarrollo App Móvil
INSERT INTO project_members (project_id, username) VALUES
(1, 'admin'),
(1, 'maria_garcia'),
(1, 'carlos_lopez'),
(1, 'ana_martinez');

-- Proyecto 2: Sitio Web Corporativo
INSERT INTO project_members (project_id, username) VALUES
(2, 'maria_garcia'),
(2, 'pedro_rodriguez'),
(2, 'laura_sanchez');

-- Proyecto 3: Campaña Marketing Q4
INSERT INTO project_members (project_id, username) VALUES
(3, 'carlos_lopez'),
(3, 'laura_sanchez'),
(3, 'admin');

-- Proyecto 4: Investigación de Mercado
INSERT INTO project_members (project_id, username) VALUES
(4, 'ana_martinez'),
(4, 'pedro_rodriguez');

-- Proyecto 5: Migración a Cloud
INSERT INTO project_members (project_id, username) VALUES
(5, 'admin'),
(5, 'maria_garcia'),
(5, 'carlos_lopez'),
(5, 'ana_martinez'),
(5, 'pedro_rodriguez'),
(5, 'laura_sanchez');