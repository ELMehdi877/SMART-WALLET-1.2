DROP DATABASE IF EXISTS smart_wallet;

CREATE DATABASE IF NOT EXISTS smart_wallet;

use smart_wallet;

#creation de tableau users
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
   fullname VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
    
);

#creation de tableau code_OTP
-- DROP TABLE IF EXISTS code_OTP;

-- CREATE TABLE IF NOT EXISTS code_OTP (
--     id int AUTO_INCREMENT PRIMARY KEY,
--     user_id INT,
--     CONSTRAINT fk_codeOTP_user FOREIGN KEY (user_id) REFERENCES users (id),
--     code INT,
--     created_at DATETIME DEFAULT(CURRENT_TIMESTAMP),
--     expires_at DATETIME DEFAULT(CURRENT_TIMESTAMP)
-- );

#creation de tableau cards
DROP TABLE IF EXISTS cards;

CREATE TABLE IF NOT EXISTS cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    CONSTRAINT fk_cards_user FOREIGN KEY (user_id) REFERENCES users (id),
    bank_name VARCHAR(15) NOT NULL,
    card_name VARCHAR(15) NOT NULL,
    card_color VARCHAR(10) NOT NULL,
    balance DECIMAL(12, 2) CHECK (balance >= 0),
    last_4 INT CHECK (last_4 > 0),
    card_principale BOOLEAN,
    -- operation VARCHAR(30) NOT NULL,
    -- amount DECIMAL(12, 2) CHECK (amount > 0),
    -- description VARCHAR (35) NOT NULL,
    -- category_limite DECIMAL(12, 2),
    -- date_operation DATE DEFAULT(CURRENT_DATE),
    created_at DATETIME DEFAULT(CURRENT_TIMESTAMP)

);


#creation de tableau transfers
DROP Table if EXISTS transfers;

CREATE Table IF NOT EXISTS transfers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT,
    CONSTRAINT fk_transfers_sender FOREIGN KEY (sender_id) REFERENCES users (id) ON DELETE CASCADE,
    receiver_id INT,
    CONSTRAINT fk_transfers_receiver FOREIGN KEY (receiver_id) REFERENCES users (id) ON DELETE CASCADE,
    sender_card_id INT,
    CONSTRAINT fk_transfers_sender_card FOREIGN KEY (sender_card_id) REFERENCES cards (id) ON DELETE CASCADE,
    receiver_card_id INT,
    CONSTRAINT fk_transfers_receiver_card FOREIGN KEY (receiver_card_id) REFERENCES cards (id) ON DELETE CASCADE,
    amount DECIMAL(10, 2) CHECK (amount > 0),
    description VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT(CURRENT_TIMESTAMP)
);

#creation de tableau incomses
DROP TABLE IF EXISTS incomes;
CREATE TABLE if not exists incomes (
    id int PRIMARY key AUTO_INCREMENT,
    user_id INT,
    CONSTRAINT fk_incomes_user FOREIGN KEY (user_id) REFERENCES users (id),
    card_id INT,
    CONSTRAINT fk_incomes_cards FOREIGN KEY (card_id) REFERENCES cards (id) ON DELETE CASCADE,
    amount DECIMAL(10, 2) not null check (amount >= 0),
    description VARCHAR(35) not null,
    income_date DATETIME DEFAULT(CURRENT_TIMESTAMP),
    created_at DATETIME DEFAULT(CURRENT_TIMESTAMP)
);

#creation de tableau category
DROP TABLE IF EXISTS category;
CREATE TABLE if not exists category (
    id int PRIMARY key AUTO_INCREMENT,
    user_id INT,
    CONSTRAINT fk_category_users FOREIGN KEY (user_id) REFERENCES users (id),
    category_name VARCHAR(35) not null,
    monthly_limite DECIMAL(10, 2) not null check (monthly_limite > 0),
    check_recurring BOOLEAN,
    created_at DATETIME DEFAULT(CURRENT_TIMESTAMP)
);


#creation de tableau expenses
DROP TABLE IF EXISTS expenses;
CREATE TABLE if not exists expenses (
    id int PRIMARY key AUTO_INCREMENT,
    user_id INT,
    CONSTRAINT fk_expenses_users FOREIGN KEY (user_id) REFERENCES users (id),
    category_id INT,
    CONSTRAINT fk_expenses_category FOREIGN KEY (category_id) REFERENCES category (id),
    card_id INT,
    CONSTRAINT fk_expenses_cards FOREIGN KEY (card_id) REFERENCES cards (id) ON DELETE CASCADE,
    amount DECIMAL(10, 2) not null check (amount > 0),
    description VARCHAR(35) not null,
    expense_date DATETIME DEFAULT(CURRENT_TIMESTAMP),
    created_at DATETIME DEFAULT(CURRENT_TIMESTAMP)
);

-
insert into
    category_limits (
        user_id,
        category,
        limit_amount
    )
values (1, "mehdi", 45);

insert into
    users (
        fullname,
        email,
        password
    )
values ("mehdi", "mehdi@gmail.com", "mehdi"),
    ("amine", "amine@gmail.com", "amine");

select * from incomes;

select * from expenses;

delete from incomes;

select * from users;

show tables;