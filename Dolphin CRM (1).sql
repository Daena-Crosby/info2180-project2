DROP DATABASE IF EXISTS dolphin_crm;

CREATE DATABASE dolphin_crm;

USE dolphin_crm;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(75) NOT NULL,
    created_at DATETIME
);

CREATE TABLE Contacts (
	id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(75) NOT NULL,
    firstname VARCHAR(75) NOT NULL,
    lastname VARCHAR(75) NOT NULL,
    email VARCHAR(75) NOT NULL,
    telephone VARCHAR(75) NOT NULL,
    company VARCHAR(75) NOT NULL,
    type VARCHAR(75) NOT NULL,
	assigned_to INT,
    created_by INT,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE Notes(
	id INT AUTO_INCREMENT PRIMARY KEY,
	contact_id INT,
    comment Text NOT NULL,
    created_at DATETIME,
    created_by INT
);

INSERT INTO Users (firstname, lastname, password, email, role, created_at)
VALUES ('Admin', 'User', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@project2.com', 'Admin', NOW());