create database belajar_laravel_database;

use belajar_laravel_database;

create TABLE categories(
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP
) engine innodb;

-- DESC categories;

CREATE TABLE counters (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    counter int NOT NULL DEFAULT 0
) engine innodb;

insert into counters (id,counter) VALUES ('sample',0);

SELECT * FROM counters;