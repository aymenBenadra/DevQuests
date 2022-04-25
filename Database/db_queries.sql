CREATE DATABASE IF NOT EXISTS `devquests`;
USE `devquests`;
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `avatar` varchar(255) NOT NULL,
    `admin` boolean NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE TABLE IF NOT EXISTS `questions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `question` varchar(255) NOT NULL,
    `answer` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE TABLE IF NOT EXISTS `roadmaps` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    /* Number of Hours needed*/
    `time` int(11) NOT NULL,
    `done` boolean NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE TABLE IF NOT EXISTS `modules` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    /* Number of Hours needed*/
    `time` int(11) NOT NULL,
    `done` boolean NOT NULL,
    `roadmap_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`roadmap_id`) REFERENCES `roadmaps`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE TABLE IF NOT EXISTS `nodes` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text NOT NULL,
    /* Number of Hours needed*/
    `time` int(11) NOT NULL,
    `module_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`module_id`) REFERENCES `modules`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
CREATE TABLE IF NOT EXISTS `resources` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL UNIQUE,
    `description` text NOT NULL,
    `link` varchar(255) NOT NULL,
    `visited` boolean NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;