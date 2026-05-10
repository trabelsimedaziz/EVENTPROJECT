CREATE DATABASE IF NOT EXISTS iitdata;
USE iitdata;

CREATE TABLE IF NOT EXISTS account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    event_date DATE,
    total_seats INT,
    available_seats INT,
    status VARCHAR(20) DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_id INT,
    customer_name VARCHAR(100),
    seats_booked INT,
    FOREIGN KEY (user_id) REFERENCES account(id),
    FOREIGN KEY (event_id) REFERENCES event(id)
);

CREATE TABLE IF NOT EXISTS request (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    event_name VARCHAR(100),
    event_date DATE,
    status VARCHAR(20) DEFAULT 'pending',
    price DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (user_id) REFERENCES account(id)
);

-- Seed admin account
INSERT INTO account (email, password, role) VALUES ('admin@test.com', 'admin123', 'admin');
