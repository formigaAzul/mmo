drop database if exists master;

-- create a database called master
create database master;

-- switch to the master database
use master;

-- create a table for the masterserver - atavism users (main account)
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `birthdate` date DEFAULT NULL,
  `activated` tinyint(1) DEFAULT NULL,
  `suspended` tinyint(1) DEFAULT NULL,
  `activation_key` varchar(32) DEFAULT NULL,
  `current_world_id` int(11) NOT NULL DEFAULT '-1',
  `created_at` datetime DEFAULT NULL,
  `last_modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `username_2` (`username`)
);

CREATE TABLE `account_character` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `character_id` bigint(20) NOT NULL,
  `world_server_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

-- create a table of registered developers
create table developer (
    dev_id INT NOT NULL AUTO_INCREMENT, 
    email VARCHAR(64) NOT NULL UNIQUE,
    company VARCHAR(64), 
    password VARCHAR(64),
    size VARCHAR(64),
    skill VARCHAR(64),
    prior VARCHAR(64), 
    genre VARCHAR(64), 
    idea TEXT,
    INDEX (email), 
    PRIMARY KEY (dev_id));

-- to create a table of world servers
CREATE TABLE `world` (
  `world_id` int(11) NOT NULL AUTO_INCREMENT,
  `dev_id` int(11) DEFAULT '-1',
  `world_name` varchar(64) DEFAULT NULL,
  `pretty_name` varchar(64) DEFAULT NULL,
  `description` text,
  `server_name` varchar(64) DEFAULT NULL,
  `server_port` int(11) DEFAULT NULL,
  `public` int(11) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `patcher_URL` varchar(255) DEFAULT NULL,
  `media_URL` varchar(255) DEFAULT NULL,
  `logo_URL` varchar(255) DEFAULT NULL,
  `detail_URL` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `population` int(11) NOT NULL DEFAULT '0',
  `max_population` int(11) NOT NULL DEFAULT '100',
  `server_type` varchar(64) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `last_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`world_id`),
  UNIQUE KEY `world_name` (`world_name`),
  KEY `world_name_2` (`world_name`)
);
