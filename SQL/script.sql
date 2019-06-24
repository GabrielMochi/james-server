-- MySQL Script generated by MySQL Workbench
-- Fri Jun 21 20:23:57 2019
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema james
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `james` ;

-- -----------------------------------------------------
-- Schema james
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `james` DEFAULT CHARACTER SET utf8 ;
USE `james` ;

-- -----------------------------------------------------
-- Table `james`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `james`.`user` ;

CREATE TABLE IF NOT EXISTS `james`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(90) NOT NULL,
  `firstname` VARCHAR(90) NOT NULL,
  `lastname` VARCHAR(90) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(90) NOT NULL,
  `profilePhoto` VARCHAR(255) NOT NULL DEFAULT '/assets/user/profile/default_avatar.png',
  `type` ENUM('ADMIN', 'USER') NOT NULL DEFAULT 'USER',
  `activate` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `james`.`video`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `james`.`video` ;

CREATE TABLE IF NOT EXISTS `james`.`video` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `thumbnailPhoto` VARCHAR(255) NOT NULL,
  `likes` INT NOT NULL DEFAULT 0,
  `dislikes` INT NOT NULL DEFAULT 0,
  `views` INT NOT NULL DEFAULT 0,
  `userId` INT NOT NULL,
  PRIMARY KEY (`id`, `userId`),
  INDEX `fk_video_user_idx` (`userId` ASC),
  CONSTRAINT `fk_video_user`
    FOREIGN KEY (`userId`)
    REFERENCES `james`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `james`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `james`.`category` ;

CREATE TABLE IF NOT EXISTS `james`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(90) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `james`.`category_has_video`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `james`.`category_has_video` ;

CREATE TABLE IF NOT EXISTS `james`.`category_has_video` (
  `categoryId` INT NOT NULL,
  `videoId` INT NOT NULL,
  `videoUserId` INT NOT NULL,
  PRIMARY KEY (`categoryId`, `videoId`, `videoUserId`),
  INDEX `fk_category_has_video_video1_idx` (`videoId` ASC, `videoUserId` ASC),
  INDEX `fk_category_has_video_category1_idx` (`categoryId` ASC),
  CONSTRAINT `fk_category_has_video_category1`
    FOREIGN KEY (`categoryId`)
    REFERENCES `james`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_category_has_video_video1`
    FOREIGN KEY (`videoId` , `videoUserId`)
    REFERENCES `james`.`video` (`id` , `userId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Insert default values
-- -----------------------------------------------------

INSERT INTO `user` (
	username, firstname,
	lastname, email, password,
    type
) VALUES (
	'gmochi56', 'Gabriel',
    'Mochi',  'gmochi56@icloud.com',
    '123g', 'ADMIN'
);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
