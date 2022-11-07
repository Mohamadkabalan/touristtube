CREATE TABLE `ml_intrestedin` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `parent_id` INT(11) NOT NULL,
 `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
 `language` CHAR(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `parent_id_2` (`parent_id`,`language`),
 KEY `parent_id` (`parent_id`),
 KEY `language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ml_intrestedin` (`id`, `parent_id`, `title`, `language`) VALUES 
(NULL, '1', 'Dîner', 'fr'), 
(NULL, '2', 'Trouver Des Destinations', 'fr'), 
(NULL, '3', 'Rencontre', 'fr'), 
(NULL, '4', 'Amitiés', 'fr'), 
(NULL, '5', 'Photos et Vidéos', 'fr'), 
(NULL, '6', 'Expression Libre', 'fr'), 
(NULL, '7', 'Se Faire Des Amis', 'fr'), 
(NULL, '8', 'Voyages', 'fr');

INSERT INTO `ml_intrestedin` (`id`, `parent_id`, `title`, `language`) VALUES 
(NULL, '1', 'डाइनिंग', 'in'), 
(NULL, '2', 'गंतव्य स्थान ढूँढना', 'in'), 
(NULL, '3', 'डेटिंग', 'in'), 
(NULL, '4', 'दोस्ती', 'in'), 
(NULL, '5', 'फोटो और वीडियोस', 'in'), 
(NULL, '6', 'आत्म अभिव्यक्ति', 'in'), 
(NULL, '7', 'सामाजिकता', 'in'), 
(NULL, '8', 'यात्रा', 'in');
