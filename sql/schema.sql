CREATE DATABASE IF NOT EXISTS repair_shop;

USE repair_shop;

-- Users Table
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

-- Products Table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    stock INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255), -- Added image column to store file paths
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sales Table
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Repairs Table
CREATE TABLE repairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    contact VARCHAR(15), -- Added contact column for customer phone number
    device VARCHAR(100) NOT NULL,
    issue_description TEXT NOT NULL,
    repair_cost DECIMAL(10, 2) DEFAULT 0.00,
    amount_paid DECIMAL(10, 2) DEFAULT 0.00, -- Added amount_paid column
    payment_status ENUM('completed', 'not_completed') NOT NULL DEFAULT 'not_completed', -- Added payment_status column
    image VARCHAR(255), -- Added image column to store file paths for repair images
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);