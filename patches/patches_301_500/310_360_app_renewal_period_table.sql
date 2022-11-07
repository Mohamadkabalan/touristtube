
CREATE TABLE `360_renewal_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX index_code
ON `360_renewal_period` (code);

INSERT INTO `360_renewal_period` (`id`, `code`, `name`) VALUES (1, 'm', 'monthly');
INSERT INTO `360_renewal_period` (`id`, `code`, `name`) VALUES (2, 'y', 'yearly');
