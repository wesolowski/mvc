DROP DATABASE mvc;

CREATE DATABASE mvc;

USE mvc;

CREATE TABLE category(
	id int AUTO_INCREMENT PRIMARY KEY,
	name varchar(100) NOT NULL UNIQUE
);

INSERT INTO category (name) VALUES ('Media');
INSERT INTO category (name) VALUES ('Food');
INSERT INTO category (name) VALUES ('Clothing');

CREATE TABLE product(
	id int AUTO_INCREMENT PRIMARY KEY,
	name varchar(100) NOT NULL UNIQUE,
	price float,
	description varchar(255)
);

INSERT INTO product (name, price, description) VALUES ('Basic Tee - White', 29.99, 'Color: White, Size: XS - XL');
INSERT INTO product (name, price, description) VALUES ('Basic Tee - Black', 29.99, 'Color: Black, Size: XS - XL');

INSERT INTO product (name, price, description) VALUES ('Strong Coffee Beans', 9.99, NULL);
INSERT INTO product (name, price, description) VALUES ('Instant Coffee', 5.99, NULL);

INSERT INTO product (name, price, description) VALUES ('Titanfall 2', 29.99, NULL);
INSERT INTO product (name, price, description) VALUES ('Mad Max - Fury Road', 8.99, NULL);

CREATE TABLE categoryProduct(
	id int AUTO_INCREMENT PRIMARY KEY,
	categoryId int,
	productId int,
	CONSTRAINT FK_categoryIdProduct FOREIGN KEY (categoryId) REFERENCES category(id),
	CONSTRAINT FK_productIdCategory FOREIGN KEY (productID) REFERENCES product(id)
);

INSERT INTO categoryProduct (categoryId, productId) VALUES (3, 1);
INSERT INTO categoryProduct (categoryId, productId) VALUES (3, 2);

INSERT INTO categoryProduct (categoryId, productId) VALUES (2, 3);
INSERT INTO categoryProduct (categoryId, productId) VALUES (2, 4);

INSERT INTO categoryProduct (categoryId, productId) VALUES (1, 5);
INSERT INTO categoryProduct (categoryId, productId) VALUES (1, 6);

CREATE TABLE user(
	id int AUTO_INCREMENT PRIMARY KEY,
	name varchar(50) UNIQUE,
	password varchar(50)
);

INSERT INTO user (name, password) VALUES ('maxmustermann', '123');