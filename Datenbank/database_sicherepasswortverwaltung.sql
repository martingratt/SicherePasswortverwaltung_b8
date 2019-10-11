CREATE TABLE `users` ( 
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL ,
  `salt` VARCHAR(255) NOT NULL,
  `passwort` VARCHAR(255) NOT NULL,
  `loginattempt` INT,
  PRIMARY KEY (`id`)
)
CREATE TABLE `attempts` (
  `ip` varchar(20) NOT NULL,
  `when` datetime NOT NULL,
  PRIMARY KEY (`ip`)
) 