/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

create database vendocentral_db;

CREATE TABLE customer (
    customer_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    customer_name VARCHAR(20) DEFAULT NULL,
    customer_mobile VARCHAR(20) NOT NULL,
    KEY user_id (user_id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8;


INSERT INTO customer (user_id, customer_name, customer_mobile) VALUES
(1, 'Nath', '09524568137'),
(2, 'Aljon', '09524568137');


CREATE TABLE invoice (
    invoice_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    customer_id INT(11) NOT NULL,
    total_bill DOUBLE DEFAULT NULL,
    KEY user_id (customer_id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8;


INSERT INTO invoice (invoice_id, customer_id, total_bill) VALUES
(1, 1, 250),
(2, 2, 300);


CREATE TABLE product (
    product_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    customer_id INT(11) DEFAULT NULL,
    product_name VARCHAR(20) DEFAULT NULL,
    product_quantity DOUBLE DEFAULT NULL,
    product_price DOUBLE DEFAULT NULL,
    product_desc TEXT,
    image_details TEXT,
    KEY customer_id (customer_id)
)  ENGINE=INNODB DEFAULT CHARSET=UTF8;


INSERT INTO product (customer_id, product_name, product_quantity, product_price, product_desc, image_details) VALUES
(1, 'Hotdog', 3, 60, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.","Lorem Ipsum is simply dummy text of the printing and typesetting industry.
     Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
     It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets
     containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    "https://picsum.photos/200/200"),
    
(2, 'Fish', 1, 300, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.","Lorem Ipsum is simply dummy text of the printing and typesetting industry.
     Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
     It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets
     containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    "https://picsum.photos/200/200");


CREATE TABLE users (
    user_id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_email VARCHAR(30) NOT NULL,
    user_pass VARCHAR(30) NOT NULL,
    user_first VARCHAR(30) NOT NULL,
    user_last VARCHAR(30) NOT NULL,
    user_mobile VARCHAR(30) NOT NULL,
    user_gender VARCHAR(30) NOT NULL,
    user_dob DATE NOT NULL,
    user_address TEXT NOT NULL,
    user_path TEXT NOT NULL,
    user_type VARCHAR(30) NOT NULL,
    user_role VARCHAR(30) NOT NULL
)  ENGINE=INNODB DEFAULT CHARSET=UTF8;

INSERT INTO users (user_email, user_pass, user_first, user_last, user_mobile, user_gender, user_dob, user_address, user_path, user_type, user_role) VALUES
('noerailey23@gmail.com', 'admin123', 'Noe Railey', 'Viernza', '09951237895', 'Male', '2004-08-23', 'Somewhere down the road', 'images/pic.jpg', 'Admin', 'Active'),
('felicianoec05@gmail.com', 'supplier123', 'Edward', 'Feliciano', '09951237895', 'Male', '2003-05-02', 'Calamba', 'images/Cat Pics/PXL_20230506_121406818(2).jpg', 'Supplier', 'Active');

ALTER TABLE customer
  ADD CONSTRAINT customer FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE invoice
  ADD CONSTRAINT invoice FOREIGN KEY (customer_id) REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE;
  
  ALTER TABLE product
  ADD CONSTRAINT product FOREIGN KEY (customer_id) REFERENCES customer (customer_id) ON DELETE CASCADE ON UPDATE CASCADE;
  
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
