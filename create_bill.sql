CREATE TABLE Bill (
  id VARCHAR(45) NOT NULL,
  type VARCHAR(45) NOT NULL,
  title VARCHAR(256) NOT NULL,
  popular_title VARCHAR(256) NULL,
  short_title VARCHAR(256) NULL,
  status VARCHAR(45) NULL,
  introduction_date DATE NULL,
  summary TEXT NULL,
  congress INT NOT NULL,
  number INT NOT NULL,
  PRIMARY KEY (id));