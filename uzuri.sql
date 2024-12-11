DROP DATABASE IF EXISTS Uzuri;
CREATE DATABASE Uzuri;
Use Uzuri;

DROP TABLE IF EXISTS offers;
DROP TABLE IF EXISTS bids;
DROP TABLE IF EXISTS users;


CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    item_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id),
    FOREIGN KEY (item_id) REFERENCES items(id)
);


CREATE TABLE offers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  original_offer DECIMAL(10, 2) NOT NULL,
  counter_offer DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
  buyer_id INT NOT NULL,
  seller_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE bids (
  bid_id INT AUTO_INCREMENT PRIMARY KEY,
  buyer_id INT NOT NULL,
  product_id INT NOT NULL,
  bid_amount DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    business_email VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'seller') NOT NULL,
    company_name VARCHAR(150) NOT NULL,        -- Only for sellers
    business_address VARCHAR(150) NOT NULL,    -- Only for sellers
    product VARCHAR(150) NOT NULL,
    admin_notes TEXT NULL,                 -- Only for admins
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE buyers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fname VARCHAR(100) NOT NULL UNIQUE,
  lname VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password1 VARCHAR(255) NOT NULL,
  address TEXT NOT NULL,
  phone VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category ENUM('rare', 'high-end', 'regular') NOT NULL,
    type ENUM('buy-now', 'negotiation', 'best-offer') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    seller_id INT,        -- Foreign Key linking to the user/seller table
    FOREIGN KEY (seller_id) REFERENCES users(id)
);

-- Insert African Foods
INSERT INTO items (name, category, type, price, image_path) VALUES
('Fufu', 'regular', 'buy-now', 10.00, 'assets/images/fufu.jpg'),
('Jollof Rice', 'high-end', 'negotiation', 12.00, 'assets/images/jollof.jpg'),
('Poulet DG', 'rare', 'buy-now', 15.00, 'assets/images/poulet_dg.jpg'),
('Ndolet', 'high-end', 'negotiation', 14.00, 'assets/images/ndolet.jpg'),
('Djankoume', 'regular', 'buy-now', 8.00, 'assets/images/djankoume.jpg'),
('Ayimolou', 'regular', 'buy-now', 10.00, 'assets/images/ayimolou.jpg'),
('Egusi Soup', 'rare', 'best-offer', 15.00, 'assets/images/egusi.jpg'),
('Poisson Braiser', 'high-end', 'buy-now', 20.00, 'assets/images/poisson_braiser.jpg'),
('Taro', 'regular', 'buy-now', 12.00, 'assets/images/taro.jpg'),
('Couscous', 'high-end', 'negotiation', 10.00, 'assets/images/couscous.jpg'),
('Plantain', 'regular', 'buy-now', 3.00, 'assets/images/plantain.jpg'),
('Eba', 'regular', 'buy-now', 9.00, 'assets/images/eba.jpg'),
('Thieboudienne', 'rare', 'negotiation', 16.00, 'assets/images/thieboudienne.jpg'),
('Amala', 'regular', 'buy-now', 11.00, 'assets/images/amala.jpg'),
('Suya', 'regular', 'buy-now', 6.00, 'assets/images/suya.jpg'),
('Yassa', 'high-end', 'buy-now', 14.00, 'assets/images/yassa.jpg'),
('Ugali', 'regular', 'buy-now', 8.00, 'assets/images/ugali.jpg'),
('Chapati', 'regular', 'buy-now', 4.00, 'assets/images/chapati.jpg'),
('Attiéké', 'regular', 'negotiation', 7.00, 'assets/images/attieke.jpg'),
('Maffe', 'regular', 'buy-now', 10.00, 'assets/images/maffe.jpg'),
('Placali', 'regular', 'buy-now', 9.00, 'assets/images/placali.jpg'),
('Moi Moi', 'regular', 'buy-now', 5.00, 'assets/images/moimoi.jpg'),
('Akara', 'regular', 'buy-now', 5.00, 'assets/images/akara.jpg'),
('Koki', 'regular', 'buy-now', 7.00, 'assets/images/koki.jpg'),
('Waakye', 'regular', 'buy-now', 8.00, 'assets/images/waakye.jpg');

-- Insert Clothing
INSERT INTO items (name, category, type, price, image_path) VALUES
('African Print Dress', 'high-end', 'buy-now', 50.00, 'assets/images/african_dress.jpg'),
('Kente Shirt', 'regular', 'buy-now', 30.00, 'assets/images/kente_shirt.jpg'),
('Dashiki', 'regular', 'buy-now', 25.00, 'assets/images/dashiki.jpg'),
('Headwrap', 'regular', 'buy-now', 15.00, 'assets/images/headwrap.jpg');

-- Insert Art
INSERT INTO items (name, category, type, price, image_path) VALUES
('African Mask', 'rare', 'best-offer', 120.00, 'assets/images/african_mask.jpg'),
('Wooden Sculpture', 'high-end', 'negotiation', 80.00, 'assets/images/sculpture.jpg'),
('Traditional Painting', 'regular', 'buy-now', 60.00, 'assets/images/painting.jpg');

-- Insert Jewelry
INSERT INTO items (name, category, type, price, image_path) VALUES
('Beaded Necklace', 'high-end', 'buy-now', 40.00, 'assets/images/beaded_necklace.jpg'),
('African Bracelet', 'regular', 'buy-now', 15.00, 'assets/images/bracelet.jpg'),
('Gold Earrings', 'rare', 'best-offer', 150.00, 'assets/images/gold_earrings.jpg'),
('Anklet', 'regular', 'buy-now', 20.00, 'assets/images/anklet.jpg');
