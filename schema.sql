CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE user (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    registration_date   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email               CHAR(128) UNIQUE NOT NULL,
    name                CHAR(150) NOT NULL,
    password            CHAR(64) NOT NULL,
    avatar              CHAR(255),
    contacts            TEXT
);

CREATE TABLE category (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    name    CHAR(100),
    code    CHAR(50)
);

CREATE TABLE lot (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    date_start  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name        CHAR(100),
    description TEXT,
    img         CHAR(255),
    price_start INT(8),
    date_end    TIMESTAMP,
    bet_step    CHAR(6),
    author_id   INT,
    winner_id   INT,
    category_id INT
);

CREATE TABLE bet (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    date       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id    INT,
    lot_id     INT
);

