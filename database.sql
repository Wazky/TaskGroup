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



-- Example data insertion
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', 'adminpass');
INSERT INTO users (username, email, password) VALUES
('user1', 'user1@example.com', 'user1pass');

