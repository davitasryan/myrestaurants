create database myrestaurants;

use myrestaurants;

create table `user` (
  id int(11) not null AUTO_INCREMENT,
  name varchar(40) not null,
  age int(3),
  gender int(2),
  occupancy varchar(255),
  avatar varchar(255),
  phone varchar(20),
  email varchar(80) not null,
  password binary(60) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX (email)
)ENGINE = INNODB ;

create table restaurant (
  id int(11) not null AUTO_INCREMENT,
  name varchar(100) not null,
  address VARCHAR(80) NOT NULL ,
  lat FLOAT( 10, 6 ) NOT NULL ,
  lng FLOAT( 10, 6 ) NOT NULL,
  contact varchar(50),
  site varchar(200),
  PRIMARY KEY (id),
  UNIQUE INDEX (lat, lng)
)ENGINE=INNODB;

create table restaurant_images (
  id int(11) not null AUTO_INCREMENT,
  restaurant_id int(11) not null,
  name varchar(100),
  PRIMARY KEY (id),
  FOREIGN KEY (restaurant_id) 
        REFERENCES restaurant(id)
)ENGINE=INNODB;

create table user_favorite_restaurant (
  user_id int(11) not null,
  restaurant_id int(11) not null,
  PRIMARY KEY (user_id, restaurant_id),
  INDEX ufr_user (user_id),
  INDEX ufr_restaurant (restaurant_id),
  FOREIGN KEY (restaurant_id) REFERENCES restaurant(id),
  FOREIGN KEY (user_id) REFERENCES `user`(id)
)ENGINE = INNODB; 