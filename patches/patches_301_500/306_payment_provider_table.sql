CREATE TABLE `payment_provider` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `payment_provider` (`id`, `name`) VALUES (1, 'Areeba');


ALTER TABLE `360_payment` ADD `payment_provider` TINYINT(4) NOT NULL AFTER `payment_type`;