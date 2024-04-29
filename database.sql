create database belajar_laravel_database;

use belajar_laravel_database;

create TABLE categories(
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP
) engine innodb;

-- DESC categories;
SELECT * FROM categories;

CREATE TABLE counters (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    counter int NOT NULL DEFAULT 0
) engine innodb;

insert into counters (id,counter) VALUES ('sample',0);

SELECT * FROM counters;

CREATE TABLE products (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    price INT NOT NULL,
    category_id VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    constraint fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id) 
    -- ON DELETE CASCADE
) engine innodb;

SELECT * FROM products;