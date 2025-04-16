CREATE DATABASE IF NOT EXISTS repair_shop;

USE repair_shop;
-- Add this to your existing schema.sql file
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Store hashed passwords
    role ENUM('admin', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert two users (admin and employee)
INSERT INTO users (username, password, role) VALUES
('admin', SHA2('admin123', 256), 'admin'), -- Password: admin123
('clinton', SHA2('clinton123', 256), 'employee'); -- Password: employee123

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    stock INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create sales table
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
-- Add this to your existing schema.sql file
CREATE TABLE repairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    device VARCHAR(100) NOT NULL,
    issue_description TEXT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    repair_cost DECIMAL(10, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);