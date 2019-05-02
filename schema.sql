CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE user (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    registration_date   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email               VARCHAR(128) UNIQUE NOT NULL,
    name                VARCHAR(150) NOT NULL,
    password            CHAR(64) NOT NULL,
    avatar              VARCHAR(255),
    contacts            TEXT
);

CREATE TABLE category (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100),
    img_key         CHAR(50)
);

CREATE TABLE lot (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    date_start  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name        VARCHAR(100),
    description TEXT,
    img         VARCHAR(255),
    price_start INT,
    date_end    TIMESTAMP,
    bet_step    INT,
    author_id   INT,
    winner_id   INT,
    category_id INT
);

CREATE TABLE bet (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    date        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id     INT,
    user_price  INT,
    lot_id      INT
);

