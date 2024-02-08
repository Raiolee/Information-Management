-- Drop database if it exists
DROP DATABASE IF EXISTS vendocentral_db;

-- Create a new database
CREATE DATABASE vendocentral_db;

-- Use the newly created database
USE vendocentral_db;

-- Create a 'users' table
CREATE TABLE users (
    user_id INT(11) UNIQUE AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_fname VARCHAR(30) NOT NULL,
    user_lname VARCHAR(30) NOT NULL,
    user_mobile VARCHAR(15) NOT NULL,
    user_gender ENUM('Male', 'Female', 'Other') NOT NULL,
    user_dob DATE NOT NULL,
    user_address TEXT NOT NULL,
    user_path TEXT NOT NULL,
    user_type ENUM('Admin', 'Supplier', 'Customer') NOT NULL,
    user_status ENUM('Active', 'Inactive') NOT NULL
);

-- Insert users data
INSERT INTO users (user_email, user_password, user_fname, user_lname, user_mobile, user_gender, user_dob, user_address, user_path, user_type, user_status) VALUES
('admin@gmail.com', 'admin123', 'Noe Railey', 'Vierneza', '09951237895', 'Male', '2004-08-23', 'Somewhere down the road', 'images/', 'Admin', 'Active'),
('supplier@gmail.com', 'supplier123', 'Noe Railey', 'Vierneza', '09951237895', 'Male', '2004-08-23', 'Somewhere down the road', 'images/', 'Supplier', 'Active'),
('customer@gmail.com', 'customer123', 'Noe Railey', 'Vierneza', '09951237895', 'Male', '2004-08-23', 'Somewhere down the road', 'images/', 'Customer', 'Active');

-- Create an 'admin' table
CREATE TABLE admin (
    admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNIQUE NOT NULL,
    admin_email VARCHAR(30) UNIQUE NOT NULL,
    admin_password VARCHAR(255) NOT NULL,
    admin_fname VARCHAR(30) NOT NULL,
    admin_lname VARCHAR(30) NOT NULL,
    admin_mobile VARCHAR(15) NOT NULL,
    admin_gender ENUM('Male', 'Female', 'Other') NOT NULL,
    admin_dob DATE NOT NULL,
    admin_address TEXT NOT NULL,
    admin_path TEXT NOT NULL,
    admin_status ENUM('Active', 'Inactive') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Create a 'supplier' table
CREATE TABLE supplier (
    supplier_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNIQUE NOT NULL,
    supplier_email VARCHAR(30) UNIQUE NOT NULL,
    supplier_password VARCHAR(255) NOT NULL,
    supplier_fname VARCHAR(30) NOT NULL,
    supplier_lname VARCHAR(30) NOT NULL,
    supplier_mobile VARCHAR(15) NOT NULL,
    supplier_gender ENUM('Male', 'Female', 'Other') NOT NULL,
    supplier_dob DATE NOT NULL,
    supplier_address TEXT NOT NULL,
    supplier_path TEXT NOT NULL,
    supplier_terms TEXT NOT NULL,
    supplier_status ENUM('Active', 'Inactive') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Create a 'customer' table
CREATE TABLE customer (
    customer_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_email VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(50) NOT NULL,
    customer_mobile VARCHAR(15) NOT NULL,
    customer_address TEXT NOT NULL,
    user_id INT(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Create a 'product' table
CREATE TABLE product (
    product_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(50) NOT NULL,
    product_quantity INT(11) NOT NULL,
    product_price DECIMAL(10 , 2 ) NOT NULL,
    product_description VARCHAR(50) NOT NULL,
    product_path TEXT NOT NULL,
    supplier_user_id INT(11),
    FOREIGN KEY (supplier_user_id) REFERENCES users (user_id)
);

-- Insert product data
INSERT INTO product (product_name, product_quantity, product_price, product_description, product_path, supplier_user_id)
VALUES
    ('hotdogs', 20, 200, 'tender juicy hotdogs', 'productimages/', 2),
    ('Fish', 20, 300, 'Tuna Fish', 'productimages/', 2);

-- Create a 'cart' table
CREATE TABLE cart (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    product_name VARCHAR(255)NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10 , 2 ) NOT NULL,
    product_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product (product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Create an 'invoice' table
CREATE TABLE invoice (
    invoice_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    product_id INT(11) NOT NULL,
    customer_id INT(11) NOT NULL,
    price DECIMAL(10 , 2 ) NOT NULL,
    invoice_date DATE NOT NULL,
    supplier_id INT(11) NOT NULL,
    FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product (product_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Create a 'deliver' table
CREATE TABLE deliver (
    deliver_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    product_id INT(11) NOT NULL,
    customer_id INT(11) NOT NULL,
    supplier_id INT(11) NOT NULL,
    customer_address TEXT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product (product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Create a 'supplier_performance' table
CREATE TABLE supplier_performance (
    supplier_id INT(11),
    supplier_sales DECIMAL(10 , 2 ),
    FOREIGN KEY (supplier_id) REFERENCES supplier (supplier_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Creation of Trigger that would update the other tables
DELIMITER //

CREATE TRIGGER after_insert_user
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    -- Insert into admin table
    IF NEW.user_type = 'Admin' THEN
        INSERT INTO admin (user_id, admin_email, admin_password, admin_fname, admin_lname, admin_mobile, admin_gender, admin_dob, admin_address, admin_path, admin_status)
        VALUES (NEW.user_id, NEW.user_email, NEW.user_password, NEW.user_fname, NEW.user_lname, NEW.user_mobile, NEW.user_gender, NEW.user_dob, NEW.user_address, NEW.user_path, NEW.user_status);
    END IF;

    -- Insert into customer table
    IF NEW.user_type = 'Customer' THEN
        INSERT INTO customer (user_id, customer_email, customer_name, customer_mobile, customer_address) 
        VALUES (NEW.user_id, NEW.user_email, CONCAT(NEW.user_fname, ' ', NEW.user_lname), NEW.user_mobile, NEW.user_address);
    END IF;

    -- Insert into supplier table
    IF NEW.user_type = 'Supplier' THEN
        INSERT INTO supplier (user_id, supplier_email, supplier_password, supplier_fname, supplier_lname, supplier_mobile, supplier_gender, supplier_dob, supplier_address, supplier_path, supplier_terms, supplier_status)
        VALUES (NEW.user_id, NEW.user_email, NEW.user_password, NEW.user_fname, NEW.user_lname, NEW.user_mobile, NEW.user_gender, NEW.user_dob, NEW.user_address, NEW.user_path, '', NEW.user_status);
    END IF;
END//

DELIMITER ;