-- MySQL Script generated by MySQL Workbench
-- Fri Nov 10 14:21:47 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema chrrov5db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema chrrov5db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `chrrov5db` DEFAULT CHARACTER SET utf8 ;
USE `chrrov5db` ;

-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_users` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_users` (
  `id` INT(11) NOT NULL,
  `login` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `password` VARCHAR(16) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `email` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `fName` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `sName` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `address` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `city` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `postcode` INT(11) NULL DEFAULT NULL,
  `state` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `country` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `phone` INT(11) NULL DEFAULT NULL,
  `cart_id` INT(11) NULL DEFAULT NULL,
  `orders` INT(11) NULL DEFAULT NULL,
  `permission` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `cart_id_idx` (`cart_id` ASC),
  CONSTRAINT `cart_id`
    FOREIGN KEY (`cart_id`)
    REFERENCES `chrrov5db`.`d0018e_carts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_carts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_carts` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_carts` (
  `id` INT(11) NOT NULL,
  `creation_date` INT(11) NULL DEFAULT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id_idx` (`user_id` ASC),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `chrrov5db`.`d0018e_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_categories` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_categories` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `num_Products` INT(5) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_manufacturers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_manufacturers` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_manufacturers` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(32) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `url` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `description` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_products`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_products` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_products` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `cost` INT(11) NULL DEFAULT NULL,
  `stock` INT(11) NULL DEFAULT NULL,
  `category` INT(11) NULL DEFAULT NULL,
  `manufacturer` INT(11) NULL DEFAULT NULL,
  `shortDesc` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `longDesc` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `rating` INT(11) NULL DEFAULT NULL,
  `numReviews` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `manufacturer_idx` (`manufacturer` ASC),
  INDEX `category_idx` (`category` ASC),
  UNIQUE INDEX `prod_id_UNIQUE` (`id` ASC),
  CONSTRAINT `category`
    FOREIGN KEY (`category`)
    REFERENCES `chrrov5db`.`d0018e_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `manufacturer`
    FOREIGN KEY (`manufacturer`)
    REFERENCES `chrrov5db`.`d0018e_manufacturers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_cart_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_cart_details` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_cart_details` (
  `id` INT(11) NOT NULL,
  `cart` INT(11) NULL DEFAULT NULL,
  `prod` INT(11) NULL DEFAULT NULL,
  `quantity` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `cart_id_idx` (`cart` ASC),
  UNIQUE INDEX `prod_id_idx` (`prod` ASC),
  CONSTRAINT `cart`
    FOREIGN KEY (`cart`)
    REFERENCES `chrrov5db`.`d0018e_carts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `prod`
    FOREIGN KEY (`prod`)
    REFERENCES `chrrov5db`.`d0018e_products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_orders` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_orders` (
  `id` INT(11) NOT NULL,
  `user` INT(11) NULL DEFAULT NULL,
  `status` VARCHAR(16) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  `order_date` DATE NULL DEFAULT NULL,
  `shipped_date` DATE NULL DEFAULT NULL,
  `comment` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id_idx` (`user` ASC),
  CONSTRAINT `user`
    FOREIGN KEY (`user`)
    REFERENCES `chrrov5db`.`d0018e_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `chrrov5db`.`d0018e_order_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `chrrov5db`.`d0018e_order_details` ;

CREATE TABLE IF NOT EXISTS `chrrov5db`.`d0018e_order_details` (
  `id` INT(11) NOT NULL,
  `order_id` INT(11) NULL DEFAULT NULL,
  `prod_id` INT(11) NULL DEFAULT NULL,
  `quantity` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `order_id_idx` (`order_id` ASC),
  INDEX `prod_id_idx` (`prod_id` ASC),
  CONSTRAINT `order_id`
    FOREIGN KEY (`order_id`)
    REFERENCES `chrrov5db`.`d0018e_orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `prod_id`
    FOREIGN KEY (`prod_id`)
    REFERENCES `chrrov5db`.`d0018e_products` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
