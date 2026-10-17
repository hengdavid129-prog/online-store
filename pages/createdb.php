<?php
declare(strict_types=1);
require_once 'classes.php';

$pdo = Tools::connect();

// $pdo->exec('CREATE TABLE roles (
//     id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     role VARCHAR(32) NOt NULL UNIQUE
// ) DEFAULT CHARSET = utf8mb4');
// echo "table roles created";

// $pdo->exec('CREATE TABLE customers (
//     id          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     login       VARCHAR(32) NOT NULL UNIQUE,
//     pass        VARCHAR(255) NOT NULL,
//     roleid      INT, FOREIGN KEY (roleid) REFERENCES roles(id) ON UPDATE CASCADE,
//     discount    INT,
//     total       INT,
//     imagepath   VARCHAR(255)
// ) DEFAULT CHARSET = utf8mb4');
// echo "table customers created";

// ...categories, items, images, sales - same pattern...

// $pdo->exec('CREATE TABLE categories (
//     id          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     category    VARCHAR(32) NOT NULL UNIQUE
// ) DEFAULT CHARSET = utf8mb4');
// echo "table categories created <br>";

// $pdo->exec('CREATE TABLE items (
//     id          INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     itemname    VARCHAR(32) NOT NULL,
//     catid       INT, FOREIGN KEY (catid) REFERENCES categories(id) ON UPDATE CASCADE,
//     pricein     INT,
//     pricesale   INT,
//     info        VARCHAR(255),
//     imagepath   VARCHAR(255),
//     rate        FLOAT,
//     action      INT
// ) DEFAULT CHARSET = utf8mb4');
// echo "table items created <br>";

// the Customer class relies on role ids 1 (Admin) 2 (Customer):
// $pdo->exec("INSERT INTO roles (role) VALUES ('Admin'), ('Customer')");



