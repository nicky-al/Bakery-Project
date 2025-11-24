-- db/schema.sql
CREATE DATABASE IF NOT EXISTS bakery_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bakery_store;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  category VARCHAR(80),
  stock INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

INSERT INTO users (name, email, password_hash, role)
VALUES ('Admin', 'admin@bakery.local', '$2y$10$1dg0uLZqGbdQ2gA6zKk0cO3Qv9I8q2vzqJt1h6.7K2yT6fXn2J5c6', 'admin')
ON DUPLICATE KEY UPDATE email=email;

INSERT INTO products (name, description, price, image, category, stock) VALUES
('Sourdough Loaf','Crusty artisan sourdough with a chewy crumble.',6.99,'/assets/images/sourdough.jpg','Bread',30),
('Butter Croissant','Warm croissant with soft butter.',3.50,'/assets/images/croissant.jpg','Pastry',50),
('Strawberry Crumble Muffin','Moist muffin packed with strawberries.',2.75,'/assets/images/muffin.jpg','Pastry',40),
('Cinnamon Roll','Soft roll with cinnamon sugar glaze.',3.25,'/assets/images/cinnamon.jpg','Pastry',35);
('Cheese Danish','Flaky, buttery danish filled with smooth cream cheese.',2.25,'/assets/images/danish.jpg','Pastry',35);
('English Muffin','French toast styled griddled muffin served with a side of berries.',4.50,'/assets/images/engmuffin.jpg','Bread',35);
('Everything Bagel','Fresh baked savoury bagel topped with creamy sour cream and onion.',3.75,'/assets/images/bagel.jpg','Bread',35);
('Chocolate Eclair','Rich chocolate eclair shell filled with silky vanilla cream.',2.50,'/assets/images/eclair.jpg','Pastry',35);
('Boston Donut','Soft golden donut filled with cream.',1.50,'/assets/images/donut.jpg','Cake',35);
('Cheesecake','Velvety cheesecake topped with whipped cream and fresh strawberries.',2.25,'/assets/images/cheesecake.jpg','Cake',35);
