CREATE TABLE `booking`
(
    `id`     int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `cid`    int(11)             NOT NULL,
    `status` ENUM ('OCZEKUJĄCA', 'POTWIERDZONA', 'ANULOWANA') DEFAULT 'OCZEKUJĄCA',
    `notes`  varchar(500)                               DEFAULT NULL
);

CREATE TABLE `customer`
(
    `cid`      int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `fullname` varchar(100)        NOT NULL,
    `email`    varchar(50)         NOT NULL,
    `password` varchar(150)        NOT NULL,
    `phone`    varchar(25) DEFAULT NULL
);

CREATE TABLE `pricing`
(
    `pricing_id`  int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `booking_id`  int(11) NOT NULL,
    `hours`      int(11) NOT NULL,
    `total_price` double  NOT NULL,
    `booked_date` DATE NOT NULL
);

CREATE TABLE administrator
(
    `adminId`  INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `fullname` VARCHAR(100) DEFAULT NULL,
    `password` VARCHAR(100)    NOT NULL,
    `email`    VARCHAR(30)     NOT NULL UNIQUE,
    `phone`    VARCHAR(25)  DEFAULT NULL
);

CREATE TABLE `reservation`
(
    `id`          int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `start`       varchar(30)         NOT NULL,
    `end`         varchar(30)         NOT NULL,
    `type`        ENUM ('Joga','Pilates','Zumba','Trening obwodowy','Body pump','FitBall','Aerobik','Aqua Aerobik','Zdrowy kręgosłup','FitBoxing','Kalistenika','Brzuch + stretch')              DEFAULT 'Joga',
    `requirement` ENUM ('Grupowy', 'Indywidualny') DEFAULT 'Grupowy',
    `cadre`       ENUM ('Brak', 'Trener personalny','Instruktor') DEFAULT 'Brak',
    `service`     ENUM ('Brak', 'Szatnia', 'Masaż', 'Dietetyk', 'Sauna', 'Basen') DEFAULT 'Brak',
    `memo`    varchar(500)                                     DEFAULT NULL,
    `timestamp`   timestamp           NOT NULL                     DEFAULT CURRENT_TIMESTAMP,
    `hash`        varchar(100)                                     DEFAULT NULL
);

--ograniczenia--
ALTER TABLE `booking`
    ADD CONSTRAINT `booking_customer__fk` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`) ON DELETE CASCADE;


ALTER TABLE `reservation`
    ADD CONSTRAINT `reservation_booking__fk` FOREIGN KEY (`id`) REFERENCES `booking` (`id`) ON DELETE CASCADE;


ALTER TABLE `pricing`
    ADD CONSTRAINT `pricing_booking__fk` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE;