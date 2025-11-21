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

CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT,
    task_title VARCHAR(255) NOT NULL,
    task_description TEXT NULL,
    task_status ENUM('to do', 'completed') DEFAULT 'to do',
    assigned_user VARCHAR(255) NOT NULL,
    project_id INT NOT NULL,
    task_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    task_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (task_id),
    FOREIGN KEY (assigned_user) REFERENCES users(username) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE
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
('Sitio Web Corporativo', 'Rediseño del sitio web principal de la empresa que tendra bla bla bla y la descripcion va a ser mas larga para ver como se  muestra en la vista de detalle y si se muestra bien con el diseño actual', 'maria_garcia'),
('Campanha Marketing Q4', 'Estrategia de marketing para el último trimestre del año', 'carlos_lopez'),
('Investigación de Mercado', 'Análisis de competencia y tendencias del sector', 'ana_martinez'),
('Migración a Cloud', 'Traslado de servidores locales a infraestructura cloud', 'admin'),
('Sistema de Notificaciones', 'Desarrollo de sistema de notificaciones push y email', 'maria_garcia'),
('API RESTful', 'Creación de API para integración con terceros', 'carlos_lopez'),
('App de Recursos Humanos', 'Sistema interno para gestión de empleados', 'ana_martinez'),
('E-commerce Platform', 'Plataforma de ventas online con carrito de compras', 'pedro_rodriguez'),
('Dashboard Analytics', 'Panel de control con métricas y gráficos en tiempo real', 'laura_sanchez'),
('Mobile Wallet', 'Aplicación de billetera digital y pagos móviles', 'admin'),
('CRM Personalizado Titulo Largo Mostrar En Vista Titulo Largo', 'Sistema de gestión de relaciones con clientes', 'maria_garcia'),
('Game Development', 'Desarrollo de juego móvil en Unity', 'carlos_lopez'),
('IoT Home Automation', 'Sistema de automatización del hogar con sensores', 'ana_martinez'),
('Blockchain Explorer', 'Explorador de transacciones para criptomonedas', 'pedro_rodriguez'),
('Social Media Integration', 'Integración con redes sociales y APIs', 'laura_sanchez'),
('Machine Learning Models', 'Desarrollo de modelos predictivos para ventas', 'admin'),
('Video Streaming Service', 'Plataforma de streaming de video bajo demanda', 'maria_garcia'),
('Real Estate Portal', 'Portal web para compra/venta de propiedades', 'carlos_lopez'),
('Fitness Tracking App', 'Aplicación de seguimiento de actividad física', 'ana_martinez');

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

-- Proyecto 6: Sistema de Notificaciones
INSERT INTO project_members (project_id, username) VALUES
(6, 'maria_garcia'),
(6, 'admin'),
(6, 'pedro_rodriguez');

-- Proyecto 7: API RESTful
INSERT INTO project_members (project_id, username) VALUES
(7, 'carlos_lopez'),
(7, 'ana_martinez'),
(7, 'laura_sanchez');

-- Proyecto 8: App de Recursos Humanos
INSERT INTO project_members (project_id, username) VALUES
(8, 'ana_martinez'),
(8, 'admin'),
(8, 'maria_garcia'),
(8, 'carlos_lopez');

-- Proyecto 9: E-commerce Platform
INSERT INTO project_members (project_id, username) VALUES
(9, 'pedro_rodriguez'),
(9, 'laura_sanchez'),
(9, 'admin');

-- Proyecto 10: Dashboard Analytics
INSERT INTO project_members (project_id, username) VALUES
(10, 'laura_sanchez'),
(10, 'carlos_lopez'),
(10, 'ana_martinez');

-- Proyecto 11: Mobile Wallet
INSERT INTO project_members (project_id, username) VALUES
(11, 'admin'),
(11, 'maria_garcia'),
(11, 'carlos_lopez'),
(11, 'ana_martinez'),
(11, 'pedro_rodriguez');

-- Proyecto 12: CRM Personalizado
INSERT INTO project_members (project_id, username) VALUES
(12, 'maria_garcia'),
(12, 'laura_sanchez'),
(12, 'pedro_rodriguez');

-- Proyecto 13: Game Development
INSERT INTO project_members (project_id, username) VALUES
(13, 'carlos_lopez'),
(13, 'admin'),
(13, 'ana_martinez');

-- Proyecto 14: IoT Home Automation
INSERT INTO project_members (project_id, username) VALUES
(14, 'ana_martinez'),
(14, 'pedro_rodriguez'),
(14, 'laura_sanchez');

-- Proyecto 15: Blockchain Explorer
INSERT INTO project_members (project_id, username) VALUES
(15, 'pedro_rodriguez'),
(15, 'admin'),
(15, 'carlos_lopez');

-- Proyecto 16: Social Media Integration
INSERT INTO project_members (project_id, username) VALUES
(16, 'laura_sanchez'),
(16, 'maria_garcia'),
(16, 'ana_martinez');

-- Proyecto 17: Machine Learning Models
INSERT INTO project_members (project_id, username) VALUES
(17, 'admin'),
(17, 'carlos_lopez'),
(17, 'pedro_rodriguez');

-- Proyecto 18: Video Streaming Service
INSERT INTO project_members (project_id, username) VALUES
(18, 'maria_garcia'),
(18, 'laura_sanchez'),
(18, 'admin'),
(18, 'ana_martinez');

-- Proyecto 19: Real Estate Portal
INSERT INTO project_members (project_id, username) VALUES
(19, 'carlos_lopez'),
(19, 'pedro_rodriguez'),
(19, 'maria_garcia');

-- Proyecto 20: Fitness Tracking App
INSERT INTO project_members (project_id, username) VALUES
(20, 'ana_martinez'),
(20, 'laura_sanchez'),
(20, 'admin'),
(20, 'carlos_lopez');


-- Insertar tareas para los proyectos

-- Proyecto 1: Desarrollo App Móvil
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Diseñar interfaz de usuario', 'Crear wireframes y mockups para la app móvil', 'completed', 'maria_garcia', 1),
('Configurar entorno de desarrollo', 'Instalar React Native y dependencias necesarias', 'completed', 'carlos_lopez', 1),
('Implementar autenticación', 'Sistema de login con JWT tokens', 'to do', 'ana_martinez', 1),
('Desarrollar módulo de tareas', 'CRUD completo para gestión de tareas', 'to do', 'admin', 1),
('Testing de la aplicación', 'Pruebas unitarias y de integración', 'to do', 'maria_garcia', 1);

-- Proyecto 2: Sitio Web Corporativo
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Diseñar layout principal', 'Maquetación de la página de inicio', 'completed', 'pedro_rodriguez', 2),
('Implementar responsive design', 'Asegurar compatibilidad con móviles', 'to do', 'laura_sanchez', 2),
('Optimizar SEO', 'Meta tags y estructura semántica', 'to do', 'maria_garcia', 2),
('Integrar formulario de contacto', 'Conectar con servicio de email', 'to do', 'pedro_rodriguez', 2);

-- Proyecto 3: Campaña Marketing Q4
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Investigar mercado objetivo', 'Análisis demográfico y comportamental', 'completed', 'laura_sanchez', 3),
('Crear contenido para redes', 'Posts para Instagram, Facebook y LinkedIn', 'to do', 'admin', 3),
('Diseñar banners publicitarios', 'Material gráfico para campañas digitales', 'to do', 'carlos_lopez', 3),
('Planificar calendario de publicaciones', 'Cronograma para todo el trimestre', 'to do', 'laura_sanchez', 3);

-- Proyecto 4: Investigación de Mercado
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Recopilar datos de competencia', 'Análisis de productos similares en el mercado', 'completed', 'pedro_rodriguez', 4),
('Crear encuestas online', 'Diseñar cuestionario para potenciales usuarios', 'to do', 'ana_martinez', 4),
('Analizar tendencias del sector', 'Informe sobre nuevas tecnologías emergentes', 'to do', 'pedro_rodriguez', 4);

-- Proyecto 5: Migración a Cloud
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Evaluar proveedores cloud', 'Comparativa AWS vs Azure vs Google Cloud', 'completed', 'maria_garcia', 5),
('Configurar VPC y seguridad', 'Redes y políticas de acceso', 'completed', 'carlos_lopez', 5),
('Migrar bases de datos', 'Transferir datos con mínimo downtime', 'to do', 'ana_martinez', 5),
('Configurar balanceadores de carga', 'Distribución de tráfico entre instancias', 'to do', 'pedro_rodriguez', 5),
('Testing de rendimiento', 'Benchmarks pre y post migración', 'to do', 'laura_sanchez', 5);

-- Proyecto 6: Sistema de Notificaciones
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Integrar Firebase Cloud Messaging', 'Configurar servicio para notificaciones push', 'completed', 'admin', 6),
('Diseñar plantillas de email', 'Templates HTML para notificaciones por correo', 'to do', 'pedro_rodriguez', 6),
('Implementar sistema de preferencias', 'Usuarios eligen qué notificaciones recibir', 'to do', 'maria_garcia', 6);

-- Proyecto 7: API RESTful
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Definir especificación OpenAPI', 'Documentar endpoints y modelos de datos', 'completed', 'ana_martinez', 7),
('Implementar autenticación OAuth2', 'Sistema seguro de acceso a la API', 'to do', 'carlos_lopez', 7),
('Desarrollar endpoints de usuarios', 'CRUD para gestión de usuarios', 'to do', 'laura_sanchez', 7);

-- Proyecto 8: App de Recursos Humanos
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Diseñar base de datos', 'Modelo ER para empleados y departamentos', 'completed', 'maria_garcia', 8),
('Implementar módulo de nóminas', 'Cálculo automático de salarios', 'to do', 'admin', 8),
('Desarrollar dashboard de analytics', 'Métricas de personal y rendimiento', 'to do', 'carlos_lopez', 8);

-- Proyecto 9: E-commerce Platform
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Configurar pasarela de pago', 'Integración con Stripe/PayPal', 'completed', 'laura_sanchez', 9),
('Implementar carrito de compras', 'Gestión de productos y cantidades', 'to do', 'admin', 9),
('Desarrollar panel de administración', 'Gestión de productos y pedidos', 'to do', 'pedro_rodriguez', 9);

-- Proyecto 10: Dashboard Analytics
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Configurar conexión a bases de datos', 'Conexión a múltiples fuentes de datos', 'completed', 'carlos_lopez', 10),
('Implementar gráficos interactivos', 'Usando Chart.js o similar', 'to do', 'ana_martinez', 10),
('Diseñar sistema de alertas', 'Notificaciones por métricas anómalas', 'to do', 'laura_sanchez', 10);

-- Más tareas para otros proyectos...
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Desarrollar wallet digital', 'Implementar billetera para criptomonedas', 'to do', 'maria_garcia', 11),
('Integrar API de blockchain', 'Conexión con nodos Ethereum', 'to do', 'carlos_lopez', 11),
('Diseñar CRM personalizado', 'Arquitectura de módulos y relaciones', 'completed', 'laura_sanchez', 12),
('Implementar pipeline CI/CD', 'Automatización de despliegues', 'to do', 'admin', 13),
('Configurar sensores IoT', 'Conexión dispositivos hardware con cloud', 'to do', 'pedro_rodriguez', 14),
('Desarrollar smart contracts', 'Contratos inteligentes para blockchain', 'to do', 'ana_martinez', 15);

-- Tareas adicionales mezclando estados y usuarios
INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id) VALUES
('Revisar documentación técnica', 'Actualizar manual de usuario y API docs', 'completed', 'laura_sanchez', 16),
('Optimizar consultas de base de datos', 'Mejorar performance de queries lentas', 'to do', 'carlos_lopez', 17),
('Implementar sistema de recomendación', 'Algoritmos de machine learning', 'to do', 'admin', 17),
('Configurar CDN para streaming', 'Distribución global de contenido', 'completed', 'maria_garcia', 18),
('Integrar mapas interactivos', 'API de Google Maps para propiedades', 'to do', 'pedro_rodriguez', 19),
('Desarrollar tracker de actividad', 'Sensores de movimiento y GPS', 'to do', 'ana_martinez', 20),
('Testing de seguridad', 'Penetration testing y vulnerabilidades', 'to do', 'admin', 5),
('Diseñar logo y branding', 'Identidad visual para la aplicación', 'completed', 'laura_sanchez', 1);