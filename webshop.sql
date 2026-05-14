CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
);

CREATE TABLE carts (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL UNIQUE,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE products (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	description TEXT,
	price DECIMAL(10,2) NOT NULL,
	stock INT DEFAULT 0,
	image VARCHAR(255),
	category VARCHAR(50)
);

CREATE TABLE cart_items (
	id INT AUTO_INCREMENT PRIMARY KEY,
	cart_id INT NOT NULL,
	product_id INT NOT NULL,
	quantity INT DEFAULT 1,
	FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
	FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE VIEW product_by_category AS
SELECT * FROM products
WHERE stock > 0
ORDER BY category, name;

INSERT INTO products (name, description, price, stock, category, image) VALUES
('ASUS Vivobook 15', 'Intel i5, 8GB RAM, 512GB SSD', 280090, 10, 'notebook', 'images/products/asus_vivobook.webp'),
('LENOVO IdeaPad Slim 3', 'AMD Ryzen 5, 16GB RAM', 199989, 5, 'notebook', 'images/products/lenovo_ideapad.webp'),
('ACER Nitro V', 'Intel i7, RTX 4060, 16GB RAM', 389990, 3, 'notebook', 'images/products/acer_nitro.webp');