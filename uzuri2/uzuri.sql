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
    product_kind ENUM('food', 'clothings', 'artifacts') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    seller_id INT,        -- Foreign Key linking to the user/seller table
    FOREIGN KEY (seller_id) REFERENCES users(id)
);

-- Insert African Foods
INSERT INTO items (name, category, type, product_kind, price, image_path, seller_id) VALUES
('Fufu', 'regular', 'buy-now', 'food', 10.00, 'assets/images/fufu.jpg', 3),
('Jollof Rice', 'high-end', 'negotiation', 'food', 12.00, 'assets/images/jollof.jpg', 3),
('Poulet DG', 'rare', 'buy-now','food', 15.00, 'assets/images/poulet_dg.jpg', 2),
('Ndolet', 'high-end', 'negotiation','food', 14.00, 'assets/images/ndolet.jpg', 2),
('Djankoume', 'regular', 'buy-now','food', 8.00, 'assets/images/djankoume.jpg', 2),
('Ayimolou', 'regular', 'buy-now','food', 10.00, 'assets/images/ayimolou.jpg', 2),
('Egusi Soup', 'rare', 'best-offer','food', 15.00, 'assets/images/egusi.jpg', 3),
('Poisson Braiser', 'high-end', 'buy-now','food', 20.00, 'assets/images/poisson_braiser.jpg', 2),
('Taro', 'regular', 'buy-now', 'food', 12.00, 'assets/images/taro.jpg', 2),
('Couscous', 'high-end', 'negotiation','food', 10.00, 'assets/images/couscous.jpg', 3),
('Plantain', 'regular', 'buy-now','food', 3.00, 'assets/images/plantain.jpg', 3),
('Eba', 'regular', 'buy-now','food', 9.00, 'assets/images/eba.jpg', 3),
('Thieboudienne', 'rare', 'negotiation','food', 16.00, 'assets/images/thieboudienne.jpg', 2),
('Amala', 'regular', 'buy-now','food', 11.00, 'assets/images/amala.jpg', 3),
('Suya', 'regular', 'buy-now','food', 6.00, 'assets/images/suya.jpg', 3),
('Yassa', 'high-end', 'buy-now','food', 14.00, 'assets/images/yassa.jpg', 2),
('Ugali', 'regular', 'buy-now','food', 8.00, 'assets/images/ugali.jpg', 3),
('Chapati', 'regular', 'buy-now','food', 4.00, 'assets/images/chapati.jpg', 2),
('Attiéké', 'regular', 'negotiation','food', 7.00, 'assets/images/attieke.jpg', 2),
('Maffe', 'regular', 'buy-now', 'food', 10.00, 'assets/images/maffe.jpg', 2),
('Placali', 'regular', 'buy-now','food', 9.00, 'assets/images/placali.jpg', 2),
('Moi Moi', 'regular', 'buy-now', 'food', 5.00, 'assets/images/moimoi.jpg', 3),
('Akara', 'regular', 'buy-now', 'food', 5.00,'assets/images/akara.jpg', 3),
('Koki', 'regular', 'buy-now', 'food', 7.00, 'assets/images/koki.jpg', 2),
('Waakye', 'regular', 'buy-now', 'food', 8.00,  'assets/images/waakye.jpg', 2);

-- Insert Clothing
INSERT INTO items (name, category, type, product_kind, price, image_path, seller_id) VALUES
('African Print Dress', 'high-end', 'buy-now', 'clothings', 50.00, 'assets/images/african_dress.jpg', 1),
('Kente Shirt', 'regular', 'buy-now','clothings', 30.00, 'assets/images/kente_shirt.jpg', 1),
('Dashiki', 'regular', 'buy-now','clothings', 25.00, 'assets/images/dashiki.jpg', 1),
('Headwrap', 'regular', 'buy-now','clothings', 15.00, 'assets/images/headwrap.jpg', 1);

-- Insert Art
INSERT INTO items (name, category, type, product_kind, price, image_path, seller_id) VALUES
('African Mask', 'rare', 'best-offer','Artifacts', 120.00, 'assets/images/african_mask.jpg', 4),
('Wooden Sculpture', 'high-end', 'negotiation', 'Artifacts', 80.00, 'assets/images/sculpture.jpg', 4),
('Traditional Painting', 'regular', 'buy-now','Artifacts', 60.00, 'assets/images/painting.jpg', 4);

-- Insert Jewelry
INSERT INTO items (name, category, type, product_kind, price, image_path, seller_id) VALUES
('Beaded Necklace', 'high-end', 'buy-now', 'Artifacts', 40.00, 'assets/images/necklace.jpg', 4),
('African Bracelet', 'regular', 'buy-now', 'Artifacts', 15.00, 'assets/images/bracelet.jpg', 4),
('Gold Earrings', 'rare', 'best-offer', 'Artifacts', 150.00, 'assets/images/earrings.jpg', 4),
('Anklet', 'regular', 'buy-now','Artifacts', 20.00, 'assets/images/anklet.jpg', 4);
