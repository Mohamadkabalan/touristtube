DROP TABLE IF EXISTS `deal_top_attractions`;
CREATE TABLE `deal_top_attractions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `deal_api_supplier_id` bigint(20) NOT NULL,
  `city_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deal_api_supplier` (`deal_api_supplier_id`),
  KEY `city_id` (`city_id`),
  CONSTRAINT `fk_city_id` FOREIGN KEY (`city_id`) REFERENCES `webgeocities` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;


INSERT INTO `deal_top_attractions` (`id`, `name`, `description`, `image_url`, `deal_api_supplier_id`, `city_id`) VALUES
(1, 'Louvre', 'Top attractions in Louvre', '/images/deals/attractions/louvre.jpg', 3, 1818316),
(2, 'Burj Khalifa', 'Top attractions in Burj Khalifa', '/images/deals/attractions/burj-khalifa.jpg', 3, 1060078),
(3, 'Vatican', 'Top attractions in Vatican', '/images/deals/attractions/vatican.jpg', 3, 2211494),
(4, 'Buckingham Palace', 'Top attractions in Buckingham Palace', '/images/deals/attractions/buckingham-palace.jpg', 3, 1829266),
(5, 'Sagrada Familia', 'Top attractions in Sagrada Familia', '/images/deals/attractions/sagrada-familia.jpg', 3, 1736746),
(6, 'Eiffel Tower', 'Top attractions in Eiffel Tower', '/images/deals/attractions/eiffel-tower.jpg', 3, 1818316),
(7, 'Empire State', 'Top attractions in Empire State', '/images/deals/attractions/empire-state.jpg', 3, 839701),
(8, 'Mount Fuji', 'Top attractions in Mount Fuji', '/images/deals/attractions/mount-fuji.jpg', 3, 2229862),
(9, 'Versailles Palace', 'Top attractions in Versailles Palace', '/images/deals/attractions/Versailles-Palace-Paris.jpg', 3, 1818316),
(10, 'London Eye', 'Top attractions in London Eye', '/images/deals/attractions/London-Eye-London.jpg', 3, 1829266),
(11, 'Catacombs Of Paris', 'Top attractions in Catacombs Of Paris', '/images/deals/attractions/Catacombs-of-Paris.jpg', 3, 1818316),
(12, 'Moulin Rouge', 'Top attractions in Moulin Rouge', '/images/deals/attractions/Moulin-Rouge-Paris.jpg', 3, 1818316),
(13, 'Borghese Gallery', 'Top attractions in Borghese Gallery', '/images/deals/attractions/Borghese-Gallery-Rome.jpg', 3, 2211494),
(14, 'Colosseum', 'Top attractions in Colosseum', '/images/deals/attractions/Colosseum-Rome.jpg', 3, 2211494),
(15, 'One World Observatory', 'Top attractions in One World Observatory', '/images/deals/attractions/one-world-observatory-New-York.jpg', 3, 839701),
(16, 'Universal Studios', 'Top attractions in Universal Studios', '/images/deals/attractions/Universal-studios-Singapore.jpg', 3, 2449847),
(17, 'Gardens By The Bay', 'Top attractions in Gardens By The Bay', '/images/deals/attractions/Gardens-by-the-Bay-Singapore.jpg', 3, 2449847),
(18, 'Night Safari', 'Top attractions in Night Safari', '/images/deals/attractions/Night-Safari-Singapore.jpg', 3, 2449847),
(19, 'La Scala Theater', 'Top attractions in La Scala Theater', '/images/deals/attractions/La-Scala-Theater-Milan.jpg', 3, 2224674),
(20, 'The last Supper', 'Top attractions in The last Supper', '/images/deals/attractions/The-last-Supper-Milan.jpg', 3, 2224674),
(21, 'Montserrat Monastery', 'Top attractions in Montserrat Monastery', '/images/deals/attractions/Montserrat-Monastery-Barcelona.jpg', 3, 1736746),
(22, 'Park Guell', 'Top attractions in Park Guellp', '/images/deals/attractions/Park-Guell--Barcelona.jpg', 3, 1736746),
(23, 'Sumo Wrestling', 'Top attractions in Sumo Wrestling', '/images/deals/attractions/Sumo-wrestling-Tokyo.jpg', 3, 2229862);