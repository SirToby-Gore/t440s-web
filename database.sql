CREATE DATABASE IF NOT EXISTS `the_one_app`;

USE `the_one_app`;

CREATE TABLE `Users` (
    `user_id` VARCHAR(64) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT 0,
    `biometric_token` VARCHAR(255),
    `role` VARCHAR(20) NOT NULL DEFAULT 'user',
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`user_id`)
);

CREATE TABLE `UserDashboards` (
    `dash_board_id` VARCHAR(64) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    `balance` DECIMAL(15,4) NOT NULL DEFAULT 0.00,
    `total_spending` DECIMAL(15,4) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (`dash_board_id`, `user_id`)
);

CREATE TABLE `Transactions` (
    `transaction_id` VARCHAR(64) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(15,4) NOT NULL,
    `type` VARCHAR(10) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255),
    `transaction_date` DATETIME NOT NULL,
    `receipt_image_url` VARCHAR(255),
    PRIMARY KEY (`transaction_id`)
);

CREATE TABLE `Budgets` (
    `budget_id` VARCHAR(64) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `limit_amount` DECIMAL(15,4) NOT NULL,
    `current_spending` DECIMAL(15,4) NOT NULL DEFAULT 0.00,
    `notification_threshold` DECIMAL(15,4) NOT NULL DEFAULT 0.8,
    PRIMARY KEY (`budget_id`)
);

CREATE TABLE `SavingsPots` (
    `saving_id` VARCHAR(64) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `target_amount` DECIMAL(15,4),
    `current_balance` DECIMAL(15,4) NOT NULL DEFAULT 0.00,
    `is_long_term` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`saving_id`)
);

CREATE TABLE `Advisors` (
    `advisor_id` VARCHAR(64) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    `specialization` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`advisor_id`)
);

CREATE TABLE `Clients` (
    `client_id` VARCHAR(64) NOT NULL,
    `advisor_id` VARCHAR(255) NOT NULL,
    `user_id` VARCHAR(255) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'active',
    `notes` TEXT NOT NULL,
    PRIMARY KEY (`client_id`)
);

CREATE TABLE `Reports` (
    `report_id` VARCHAR(255) NOT NULL,
    `manager_id` VARCHAR(255) NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `type` VARCHAR(20) NOT NULL,
    `content` TEXT NOT NULL,
    `generated_at` DATETIME NOT NULL,
    PRIMARY KEY (`report_id`)
);

CREATE TABLE `Tokens` (
    `user_id` VARCHAR(255) NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `created_on` DATETIME NOT NULL,
    PRIMARY KEY (`user_id`, `token`)
);