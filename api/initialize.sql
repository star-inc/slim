SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `histories`
(
    `_id`          int(11)             NOT NULL,
    `created_time` bigint(20) UNSIGNED NOT NULL,
    `user`         varchar(36)         NOT NULL,
    `method`       text                NOT NULL,
    `resource`     text                NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `levels`
(
    `_id`          int(11)     NOT NULL,
    `display_name` varchar(50) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `levels` (`_id`, `display_name`)
VALUES (1, 'Admin');

CREATE TABLE `products`
(
    `uuid`          varchar(36) NOT NULL,
    `display_name`  varchar(50) NOT NULL,
    `cost_value`    int(11)     NOT NULL,
    `sell_value`    int(11)     NOT NULL,
    `remain_amount` int(11)     NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `providers`
(
    `_id`             int(11)      NOT NULL,
    `display_name`    varchar(50)  NOT NULL,
    `contact_name`    varchar(50)  NOT NULL,
    `contact_phone`   varchar(50)  NOT NULL,
    `contact_address` varchar(255) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE `users`
(
    `uuid`         varchar(36)         NOT NULL,
    `username`     varchar(128)        NOT NULL,
    `password`     varchar(64)         NOT NULL,
    `level`        int(11)             NOT NULL,
    `display_name` text                NOT NULL,
    `created_time` bigint(20) UNSIGNED NOT NULL,
    `address`      varchar(255)        NOT NULL,
    `email`        varchar(320)        NOT NULL,
    `phone`        varchar(50)         NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

INSERT INTO `users` (`uuid`, `username`, `password`, `level`, `display_name`, `created_time`, `address`, `email`,
                     `phone`)
VALUES ('00000000-0000-0000-0000-000000000000', 'admin',
        '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918',
        1, 'Admin', 1637505598, 'Admin', 'root@localhost', '0000000000');


ALTER TABLE `histories`
    ADD PRIMARY KEY (`_id`);

ALTER TABLE `levels`
    ADD PRIMARY KEY (`_id`),
    ADD UNIQUE KEY `display_name` (`display_name`);

ALTER TABLE `products`
    ADD PRIMARY KEY (`uuid`);

ALTER TABLE `providers`
    ADD PRIMARY KEY (`_id`),
    ADD UNIQUE KEY `display_name` (`display_name`);

ALTER TABLE `users`
    ADD PRIMARY KEY (`uuid`),
    ADD UNIQUE KEY `username` (`username`),
    ADD KEY `class` (`level`);


ALTER TABLE `histories`
    MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `levels`
    MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;

ALTER TABLE `providers`
    MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT,
    AUTO_INCREMENT = 1;


ALTER TABLE `users`
    ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`level`) REFERENCES `levels` (`_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;