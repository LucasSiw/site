-- MariaDB Script
-- Thu Jun 13 18:55:11 2024
-- Model: New Model    Version: 1.0
-- MariaDB Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema tbApetrecho
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `tbApetrecho`;

CREATE SCHEMA IF NOT EXISTS `tbApetrecho` DEFAULT CHARACTER SET utf8;
USE `tbApetrecho`;

-- -----------------------------------------------------
-- Table `tbApetrecho`.`tbEstados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbApetrecho`.`tbEstados`;

CREATE TABLE IF NOT EXISTS `tbApetrecho`.`tbEstados` (
  `bdCodUF` INT NOT NULL AUTO_INCREMENT,
  `bdEstDescricao` VARCHAR(200) NULL,
  `bdEstIBGE` VARCHAR(45) NULL,
  PRIMARY KEY (`bdCodUF`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `tbApetrecho`.`tbCidade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbApetrecho`.`tbCidade`;

CREATE TABLE IF NOT EXISTS `tbApetrecho`.`tbCidade` (
  `bdCodCidade` INT NOT NULL AUTO_INCREMENT,
  `bdCidNome` VARCHAR(200) NULL,
  `bdCodUF` INT NOT NULL,
  PRIMARY KEY (`bdCodCidade`),
  INDEX `fk_tbCidade_tbEstados_idx` (`bdCodUF` ASC),
  CONSTRAINT `fk_tbCidade_tbEstados`
    FOREIGN KEY (`bdCodUF`)
    REFERENCES `tbApetrecho`.`tbEstados` (`bdCodUF`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `tbApetrecho`.`tbEndereco`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbApetrecho`.`tbEndereco`;

CREATE TABLE IF NOT EXISTS `tbApetrecho`.`tbEndereco` (
  `bdCodEndereco` INT NOT NULL AUTO_INCREMENT,
  `bdEndDescricao` VARCHAR(200) NULL,
  `bdEndNumero` INT NULL,
  `bdEndCEP` VARCHAR(10) NULL,
  `bdCodCidade` INT NOT NULL,
  PRIMARY KEY (`bdCodEndereco`),
  INDEX `fk_tbEndereco_tbCidade1_idx` (`bdCodCidade` ASC),
  CONSTRAINT `fk_tbEndereco_tbCidade1`
    FOREIGN KEY (`bdCodCidade`)
    REFERENCES `tbApetrecho`.`tbCidade` (`bdCodCidade`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `tbApetrecho`.`tbUsuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbApetrecho`.`tbUsuario`;

CREATE TABLE IF NOT EXISTS `tbApetrecho`.`tbUsuario` (
  `bdCodUsuario` INT NOT NULL AUTO_INCREMENT,
  `bdAluNome` VARCHAR(250) NULL,
  `bdAluTelefone` VARCHAR(18) NULL,
  `bdAluCPFCNPJ` VARCHAR(14) NULL,
  `bdAluEmail` VARCHAR(250) NULL,
  `bdAluSenha` VARCHAR(250) NULL,
  PRIMARY KEY (`bdCodUsuario`)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `tbApetrecho`.`tbProduto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbApetrecho`.`tbProduto` (

  `bdCodProduto` INT NOT NULL AUTO_INCREMENT,
  `bdProdDescricao` VARCHAR(250) NULL,
  `bdProdValor` DECIMAL(10,2) NULL,
  `bdProdImagem` VARCHAR(255) NULL, -- Novo campo para a imagem do produto
  `bdCodUsuario` INT NOT NULL,
  PRIMARY KEY (`bdCodProduto`),
  INDEX `fk_tbProduto_tbUsuario1_idx` (`bdCodUsuario` ASC),
  CONSTRAINT `fk_tbProduto_tbUsuario1`
    FOREIGN KEY (`bdCodUsuario`)
    REFERENCES `tbApetrecho`.`tbUsuario` (`bdCodUsuario`)
) ENGINE=InnoDB;

ALTER TABLE tbUsuario ADD COLUMN email_verificado TINYINT(1) DEFAULT 0;
ALTER TABLE tbUsuario ADD COLUMN token_verificacao VARCHAR(255);


