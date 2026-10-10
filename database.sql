-- Task Management System: database setup
-- Import this file from phpMyAdmin (Import tab) or run it in the MySQL console.

CREATE DATABASE IF NOT EXISTS task_manager
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE task_manager;

CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    first_name  VARCHAR(50)  NOT NULL,
    last_name   VARCHAR(50)  NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        VARCHAR(20)  NOT NULL DEFAULT 'user',
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tasks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    title       VARCHAR(150) NOT NULL,
    description TEXT,
    status      ENUM('pending','completed') NOT NULL DEFAULT 'pending',
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tasks_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Demo accounts (passwords are BCrypt hashes)
--   admin@example.com / Admin@123
--   user@example.com  / User@123
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('Demo', 'Admin', 'admin@example.com', '$2y$10$u0ZtI.iP.OOI34QIxSrd.evpAQdjvRAQRnQzK.cnx2x3YM7r9gJUm', 'admin'),
('Demo', 'User',  'user@example.com',  '$2y$10$HjIhuhFLWCpdjq6QW2Ks1uR5HQhagR5rS3LreSF9VaSDqzMcjOweW', 'user');

INSERT INTO tasks (user_id, title, description, status) VALUES
(2, 'Prepare project report', 'Write the first draft of the final report.', 'pending'),
(2, 'Review pull requests',   'Check the open pull requests before Friday.', 'completed');
