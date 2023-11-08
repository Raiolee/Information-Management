drop database vendocentral_db;

-- Create a new database
CREATE DATABASE vendocentral_db;

-- Use the newly created database
USE vendocentral_db;

-- Create a 'users' table
CREATE TABLE users (
    user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(30) UNIQUE NOT NULL,
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
insert into users (user_email, user_password, user_fname, user_lname, user_mobile, user_gender, user_dob, user_address, user_path, user_type, user_status) values
('noerailey23@gmail.com', 'admin123', 'Noe Railey', 'Vierneza', '09983228946', 'Male', '2004-08-23', 'Santa Rosa', 'images/', 'Admin', 'Active'),
('felicianoec05@gmail.com', 'supplier123', 'Edward', 'Feliciano', '092345678912', 'Male', '2003-06-12', 'Calamba', 'images/', 'Supplier', 'Active')
;

-- Create an 'admin' table
CREATE TABLE admin (
    admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
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
    FOREIGN KEY (admin_id)
        REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert admin data
INSERT INTO admin (admin_id, admin_email, admin_password, admin_fname, admin_lname, admin_mobile, admin_gender, admin_dob, admin_address, admin_path, admin_status)
VALUES
    (1, 'noerailey23@gmail.com', 'admin123', 'Noe Railey', 'Vierneza', '09951237895', 'Male', '2004-08-23', 'Somewhere down the road', 'images', 'Active'),
    (2, 'felicianoec05@gmail.com', 'supplier123', 'Edward', 'Feliciano', '09951237895', 'Male', '2003-05-02', 'Calamba', 'images/', 'Active');

-- Create a 'supplier' table
CREATE TABLE supplier (
    supplier_id INT(11) AUTO_INCREMENT PRIMARY KEY,
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
    FOREIGN KEY (supplier_id)
        REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert supplier data
INSERT INTO supplier (supplier_id, supplier_email, supplier_password, supplier_fname, supplier_lname, supplier_mobile, supplier_gender, supplier_dob, supplier_address, supplier_path, supplier_terms, supplier_status)
VALUES
    (1, 'noerailey23@gmail.com', 'supplier123', 'Noe Railey', 'Vierneza', '09951237895', 'Male', '2004-08-23', 'Somewhere down the road', 'images/', 'terms/' 'Active'),
    (2, 'felicianoec05@gmail.com', 'supplier123', 'Edward', 'Feliciano', '09951237895', 'Male', '2003-05-02', 'Calamba', 'images/', 'terms/' 'Active');
    
-- Create a 'supplier_performance' table
CREATE TABLE supplier_performance (
    supplier_id INT(11) NOT NULL,
    delivery_time INT(11) NOT NULL,
    product_quality_rating DECIMAL(3 , 2 ) NOT NULL,
    PRIMARY KEY (supplier_id),
    FOREIGN KEY (supplier_id)
        REFERENCES supplier (supplier_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert supplier_performance data
INSERT INTO supplier_performance (supplier_id, delivery_time, product_quality_rating)
VALUES
    (1, 2, 4.5),
    (2, 3, 4.2);


-- Create a 'customer' table
CREATE TABLE customer (
    customer_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    customer_name VARCHAR(50) NOT NULL,
    customer_mobile VARCHAR(15) NOT NULL,
    customer_address TEXT NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert customer data
INSERT INTO customer (user_id, customer_name, customer_mobile, customer_address)
VALUES
    (1, 'Nath', '09524568137', 'Santa Rosa'),
    (2, 'Aljon', '09524568137', 'Philippines');

-- Create a 'product' table
CREATE TABLE product (
    product_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(50) NOT NULL,
    product_quantity INT(11) NOT NULL,
    product_price DECIMAL(10 , 2 ) NOT NULL
);

-- Insert product data
INSERT INTO product (product_name, product_quantity, product_price)
VALUES
    ('Hotdog', 3, 60.00),
    ('Fish', 1, 300.00);

-- Create a 'cart' table
CREATE TABLE cart (
    cart_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    total_amount DECIMAL(10 , 2 ) NOT NULL,
    product_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (customer_id)
        REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id)
        REFERENCES product (product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert cart data
INSERT INTO cart (customer_id, product_id, quantity, total_amount, product_path)
VALUES
    (1, 1, 3, 180.00, 'productimages/');

-- Create an 'invoice' table
CREATE TABLE invoice (
    invoice_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    customer_id INT(11) NOT NULL,
    total_bill DECIMAL(10 , 2 ) NOT NULL,
    FOREIGN KEY (customer_id)
        REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert invoice data
INSERT INTO invoice (customer_id, total_bill)
VALUES
    (1, 250.00),
    (2, 300.00);