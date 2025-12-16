DROP DATABASE IF EXISTS smart_wallet;
CREATE DATABASE IF NOT EXISTS smart_wallet;
use smart_wallet;

DROP TABLE IF EXISTS incomes;
CREATE TABLE if not exists incomes(
    id int PRIMARY key AUTO_INCREMENT,
    categorie VARCHAR(30) not null,
    montants DECIMAL(10,2) not null check (montants > 0),
    description VARCHAR(35) not null,
    date DATE DEFAULT (CURRENT_DATE)
);

DROP TABLE IF EXISTS expenses;
CREATE TABLE if not exists expenses(
    id int PRIMARY key AUTO_INCREMENT,
    categorie VARCHAR(30) not null,
    montants DECIMAL(10,2) not null check (montants > 0),
    description VARCHAR(35) not null,
    date DATE DEFAULT (CURRENT_DATE)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


insert into incomes (montants, categorie, description) 
values (55.5, "t9diya", "khizo btata"),
(55.5, "t9diya", "khizo btata"),
(55.5, "t9diya", "khizo btata");

select * from incomes;
select * from expenses;
delete from incomes;
select * from users;
show tables;
CREATE DATABASE IF NOT EXISTS finance_manager;