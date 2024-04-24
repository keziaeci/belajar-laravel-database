create database belajar_laravel_database;

use belajar_laravel_database;

create TABLE categories(
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP
) engine innodb;

-- DESC categories;

