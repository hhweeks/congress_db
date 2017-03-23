show tables;

CREATE TABLE `Legislator` (
  `bioguide_id` VARCHAR(45) NOT NULL,
  `Last Name` VARCHAR(45) NOT NULL,
  `First Name` VARCHAR(45) NOT NULL,
  `birthday` DATE NULL,
  `gender` VARCHAR(1) NULL,
  `wikipedia_id` VARCHAR(90) NULL,
  `govtrack_id` INT NOT NULL,
  `official_full_name` VARCHAR(90) NULL,
  PRIMARY KEY (`bioguide_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Congress`
-- -----------------------------------------------------
CREATE TABLE `Congress` (
  `id` INT NOT NULL,
  `begin` DATE NOT NULL,
  `end` DATE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Bill`
-- -----------------------------------------------------
CREATE TABLE `Bill` (
  `id` VARCHAR(45) NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  `title` VARCHAR(256) NOT NULL,
  `popular_title` VARCHAR(256) NULL,
  `short_title` VARCHAR(256) NULL,
  `status` VARCHAR(45) NULL,
  `introduction_date` DATE NULL,
  `summary` TEXT NULL,
  `congress` INT NOT NULL,
  `number` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Bill_Congress1_idx` (`congress` ASC),
  CONSTRAINT `fk_Bill_Congress1`
    FOREIGN KEY (`congress`)
    REFERENCES `Congress` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Subjects`
-- -----------------------------------------------------
CREATE TABLE `Subjects` (
  `subject` VARCHAR(128) NOT NULL,
  `Bill_id` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`subject`, `Bill_id`),
  INDEX `fk_Subjects_Bill_idx` (`Bill_id` ASC),
  CONSTRAINT `fk_Subjects_Bill`
    FOREIGN KEY (`Bill_id`)
    REFERENCES `Bill` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Chamber`
-- -----------------------------------------------------
CREATE TABLE `Chamber` (
  `id` VARCHAR(1) NOT NULL,
  `name` VARCHAR(60) NOT NULL,
  `num_seats` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Votes`
-- -----------------------------------------------------
CREATE TABLE `Votes` (
  `id` VARCHAR(45) NOT NULL,
  `chamber` VARCHAR(1) NOT NULL,
  `category` VARCHAR(45) NOT NULL,
  `question` VARCHAR(256) NULL,
  `congress` INT NOT NULL,
  `session` YEAR NOT NULL,
  `result` VARCHAR(45) NULL,
  `requires` VARCHAR(45) NULL,
  `number` INT NOT NULL,
  `date` DATE NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  `Bill_id` VARCHAR(45) NULL,
  `Amendment_id` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Votes_Bill1_idx` (`Bill_id` ASC),
  INDEX `fk_Votes_Chamber1_idx` (`chamber` ASC),
  INDEX `fk_Votes_Congress1_idx` (`congress` ASC),
  CONSTRAINT `fk_Votes_Bill1`
    FOREIGN KEY (`Bill_id`)
    REFERENCES `Bill` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Votes_Chamber1`
    FOREIGN KEY (`chamber`)
    REFERENCES `Chamber` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Votes_Congress1`
    FOREIGN KEY (`congress`)
    REFERENCES `Congress` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Legislator_Vote`
-- -----------------------------------------------------
CREATE TABLE `Legislator_Vote` (
  `Votes_id` VARCHAR(45) NOT NULL,
  `bioguide_id` VARCHAR(45) NOT NULL,
  `how_voted` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Votes_id`, `bioguide_id`),
  INDEX `fk_Legislator_Vote_Legislator1_idx` (`bioguide_id` ASC),
  CONSTRAINT `fk_Legislator_Vote_Votes1`
    FOREIGN KEY (`Votes_id`)
    REFERENCES `Votes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Legislator_Vote_Legislator1`
    FOREIGN KEY (`bioguide_id`)
    REFERENCES `Legislator` (`bioguide_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Amendment`
-- -----------------------------------------------------
CREATE TABLE `Amendment` (
  `id` VARCHAR(45) NOT NULL,
  `description` TEXT NULL,
  `purpose` TEXT NULL,
  `status` VARCHAR(45) NULL,
  `introduced_at` DATE NULL,
  `status_at` DATE NULL,
  `type` VARCHAR(45) NOT NULL,
  `Bill_id` VARCHAR(45) NULL,
  `Amendment_id` VARCHAR(45) NULL,
  `congress` INT NOT NULL,
  `number` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Amendment_Bill1_idx` (`Bill_id` ASC),
  INDEX `fk_Amendment_Congress1_idx` (`congress` ASC),
  CONSTRAINT `fk_Amendment_Bill1`
    FOREIGN KEY (`Bill_id`)
    REFERENCES `Bill` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Amendment_Congress1`
    FOREIGN KEY (`congress`)
    REFERENCES `Congress` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `State`
-- -----------------------------------------------------
CREATE TABLE `State` (
  `name` VARCHAR(60) NOT NULL,
  `num_districts` INT NOT NULL,
  `num_representatives` INT NOT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `District`
-- -----------------------------------------------------
CREATE TABLE `District` (
  `number` INT NOT NULL,
  `state` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`number`, `state`),
  INDEX `fk_District_State1_idx` (`state` ASC),
  CONSTRAINT `fk_District_State1`
    FOREIGN KEY (`state`)
    REFERENCES `State` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Session`
-- -----------------------------------------------------
CREATE TABLE `Session` (
  `congress` INT NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  `begin` DATE NOT NULL,
  `end` DATE NOT NULL,
  PRIMARY KEY (`congress`, `type`),
  CONSTRAINT `fk_Session_Congress1`
    FOREIGN KEY (`congress`)
    REFERENCES `Congress` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Term`
-- -----------------------------------------------------
CREATE TABLE `Term` (
  `bioguide_id` VARCHAR(45) NOT NULL,
  `start` DATE NOT NULL,
  `end` DATE NOT NULL,
  `type` VARCHAR(3) NOT NULL,
  `state` VARCHAR(60) NOT NULL,
  `url` VARCHAR(1024) NULL,
  `district` INT NULL,
  `party` VARCHAR(45) NOT NULL,
  `chamber` VARCHAR(1) NOT NULL,
  PRIMARY KEY (`bioguide_id`, `start`, `end`),
  INDEX `fk_Term_District1_idx` (`district` ASC),
  INDEX `fk_Term_State1_idx` (`state` ASC),
  INDEX `fk_Term_Chamber1_idx` (`chamber` ASC),
  CONSTRAINT `fk_Term_Legislator1`
    FOREIGN KEY (`bioguide_id`)
    REFERENCES `Legislator` (`bioguide_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Term_District1`
    FOREIGN KEY (`district`)
    REFERENCES `District` (`number`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Term_State1`
    FOREIGN KEY (`state`)
    REFERENCES `State` (`name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Term_Chamber1`
    FOREIGN KEY (`chamber`)
    REFERENCES `Chamber` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

show tables;

describe Amendment; 
describe Bill;             
describe Chamber;          
describe Congress;         
describe District;         
describe Legislator;       
describe Legislator_Vote;  
describe Session;          
describe State;            
describe Subjects;         
describe Term;             
describe Votes;            

