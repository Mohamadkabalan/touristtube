
UPDATE entity_type 
SET entity_type_key = 'SOCIAL_ENTITY_THINGSTODO_DETAILS', name = 'Things to do details', label = 'Things to do details' 
WHERE id = 80;


INSERT INTO entity_type 
(id, entity_type_key, name, label) 
VALUES 
(4, 'SOCIAL_ENTITY_WEBCAM', 'live', 'Tourist Live Cams - Hotels 360');


INSERT INTO main_entity_type 
(id, name, entity_type_id, entity_id, display_order, show_on_home, published) 
VALUES 
(37, 'Tourist Live - Hotels 360', 4, 0, 1000, 9, 1);


UPDATE main_entity_type_list SET city_id = 1818316 WHERE id = 15;
UPDATE main_entity_type_list SET city_id = 1060078 WHERE id = 16;
UPDATE main_entity_type_list SET city_id = 2102463 WHERE id = 17;
UPDATE main_entity_type_list SET city_id = 2211494 WHERE id = 18;




INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (187, 'Grand Millennium Al Barsha', 28, 37, 71637, 1000, 1, 0, 1);            
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (188, 'Gloria', 28, 37, 101581, 990, 1, 0, 1);                                
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (189, 'Al Murooj Rotana Dubai', 28, 37, 67011, 980, 1, 0, 1);                 
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (190, 'Ghaya Grand Hotel & Apartments', 28, 37, 152363, 970, 1, 0, 1);        
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (191, 'Capitol Hotel', 28, 37, 24390, 960, 1, 0, 1);                          
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (192, 'Broadway Hotel', 28, 37, 41322, 950, 1, 0, 1);                         
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (193, 'Mercure Grand Jebel Hafeet Al Ain Hotel', 28, 37, 42429, 940, 1, 0, 1);
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (194, 'FOUR SEASONS BEIRUT', 28, 37, 132722, 930, 1, 0, 1);                   
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (195, 'Kube', 28, 37, 65443, 920, 1, 0, 1);                                   
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (196, 'Villa Beaumarchais', 28, 37, 20078, 910, 1, 0, 1);                     
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (197, 'Pavillon Nation', 28, 37, 72224, 900, 1, 0, 1);                        
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (198, 'Pavillon Opera Bourse', 28, 37, 20961, 890, 1, 0, 1);                  
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (199, 'Normandy', 28, 37, 4850, 880, 1, 0, 1);                                
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (200, 'Pavillon Louvre Rivoli', 28, 37, 20972, 870, 1, 0, 1);                 
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (201, 'Villa Montparnasse', 28, 37, 23066, 860, 1, 0, 1);                     
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (202, 'Le 1K', 28, 37, 52575, 850, 1, 0, 1);                                  
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (203, 'Villa Royale Pigalle', 28, 37, 27417, 840, 1, 0, 1);                   
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (204, 'Villa Luxembourg Residence Hotel', 28, 37, 15734, 830, 1, 0, 1);       
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (205, 'Villa Eugenie', 28, 37, 20965, 820, 1, 0, 1);                          
INSERT INTO main_entity_type_list (id, name, entity_type_id, main_entity_type_id, entity_id, display_order, show_on_home, city_id, published) VALUES (206, 'Pavillon Villiers Etoile', 28, 37, 18003, 810, 1, 0, 1);               


CREATE TABLE `ml_main_entity_type` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `language` char(2) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(1, 1, 'Endroits à visiter', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(2, 2, 'Meilleurs hôtels du monde', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(3, 3, 'Activités en ville', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(4, 4, 'Photos 360° - Visite virtuelle', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(5, 5, 'Attractions - coupe-file', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(6, 6, 'Paris', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(7, 7, 'Dubaï', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(8, 8, 'Pékin', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(9, 9, 'Rome', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(10, 10, 'New York', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(11, 11, 'Visite virtuelle 360° Paris', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(12, 12, 'Visite virtuelle 360° Dubaï', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(13, 13, 'Visite virtuelle 360° Delhi', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(14, 14, 'Visite virtuelle 360° Rome', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(15, 15, 'Visite virtuelle 360° Athènes', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(16, 16, 'Visite virtuelle 360° Nice', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(17, 17, 'Visite virtuelle 360° Abou Dhabi', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(18, 18, 'Visite virtuelle 360° Ningxia', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(19, 19, 'Visite virtuelle 360° Milan', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(20, 20, 'Activités en ville', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(21, 21, 'Réserver un hotel', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(22, 22, 'Lisbonne', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(23, 23, 'Barcelone', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(24, 24, 'Edinbourg', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(25, 25, 'Berlin', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(26, 26, 'Londres', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(27, 27, 'San Francisco', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(28, 28, 'Istanbul', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(29, 29, 'Abu Dhabi', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(30, 30, 'Marmaris', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(31, 31, 'Varsovie', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(32, 32, 'Tokyo', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(33, 33, 'Meilleures hôtels dans le monde', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(34, 34, 'Attractions - coupe-file', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(35, 35, 'Meilleures destinations', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(36, 36, 'Meilleures attractions', 'fr');
INSERT INTO `ml_main_entity_type` (`id`, `parent_id`, `name`, `language`) VALUES(37, 37, 'Tourist Live - Hotels 360°', 'fr');


ALTER TABLE `ml_main_entity_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parent_id_2` (`parent_id`,`language`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `language` (`language`);


ALTER TABLE `ml_main_entity_type` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;




CREATE TABLE `ml_main_entity_type_list` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `language` char(2) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(1, 1, 'Choses à faire à Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(2, 2, 'Four Seasons Beirut', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(3, 4, 'The Westin Palace Madrid', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(4, 6, 'Grand Millennium Al Barsha', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(5, 7, 'Four Seasons George V Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(6, 11, 'Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(7, 12, 'Londres', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(8, 13, 'Barcelone', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(9, 14, 'Berlin', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(10, 15, 'Tour Eiffel 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(11, 16, 'Burj Khalifa 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(12, 17, 'Tombe de Lodi 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(13, 18, 'Piazza Navona 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(14, 19, 'Choses à faire à Dubaï', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(15, 20, 'Choses à faire à Londres', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(16, 21, 'Choses à faire à New York', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(17, 22, 'Burj Khalifa', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(18, 23, 'Empire State', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(19, 24, 'Vatican', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(20, 25, 'Tour Eiffel', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(21, 46, 'Tour Eiffel 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(22, 47, 'Arc de Triomphe 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(23, 48, 'Champs Elysées 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(24, 49, 'Musée du Louvre 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(25, 50, 'Burj Khalifa 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(26, 51, 'Dubaï Mall 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(27, 52, 'Fontaine de Dubaï 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(28, 53, 'Palm Jumeirah 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(29, 54, 'Tombe de Lodi 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(30, 55, 'National Rail Museum 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(31, 56, 'Humayun\'s Tomb Park 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(32, 57, 'Kalkaji District Park 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(33, 58, 'Piazza Navona 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(34, 59, 'Panthéon', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(35, 60, 'Piazza Venezia 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(36, 61, 'Fontaine de Trevi 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(37, 62, 'Acropolis Théatre de Dionysos 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(38, 63, 'Acropolis Citerne Byzantine 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(39, 64, 'Acropolis Parthenonas 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(40, 65, 'Acropolis Bronze Foundries 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(41, 66, 'Promenade des Anglais 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(42, 67, 'Place Massena 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(43, 68, 'Fontaine du Soleil 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(44, 69, 'Basilique Notre Dame de l’Assomption 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(45, 70, 'Yas Marina 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(46, 71, 'Yas Viceroy 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(47, 72, 'Yas Links 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(48, 73, 'Mosquée Sheikh Zayed 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(49, 74, 'Helan Mountains Yinchuan 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(50, 75, 'China Hui Culture Park Yongning 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(51, 76, 'China Flower Expo Park 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(52, 77, 'Western Xia Tombs Yinchuan 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(53, 78, 'Piazza del Duomo 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(54, 79, 'Palais royal de Milan 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(55, 80, 'Tour Pirelli 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(56, 81, 'Musée d´histoire naturelle de Milan 360°', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(57, 82, 'Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(58, 83, 'Rome', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(59, 84, 'New York', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(60, 85, 'Singapour', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(61, 86, 'Milan', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(62, 87, 'Barcelone', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(63, 88, 'Beyrouth', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(64, 89, 'Pékin', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(65, 90, 'Dubaï', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(66, 91, 'Lisbonne', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(67, 92, 'Istanbul', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(68, 93, 'Athènes', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(69, 94, 'Luxembourg', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(70, 95, 'Berlin', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(71, 96, 'Budapest', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(72, 97, 'Porto', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(73, 98, 'Madrid', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(74, 99, 'San Francisco', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(75, 100, 'New Delhi', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(76, 101, 'Amsterdam', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(77, 102, 'Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(78, 103, 'Londres', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(79, 104, 'Prague', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(80, 105, 'Dubaï', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(81, 106, 'Las Vegas', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(82, 107, 'New York', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(83, 108, 'Barcelone', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(84, 109, 'Pékin', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(85, 110, 'Rome', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(86, 111, 'Berlin', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(87, 112, 'Vienne', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(88, 113, 'Dublin', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(89, 114, 'Athènes', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(90, 115, 'Sydney', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(91, 116, 'Singapour', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(92, 117, 'Abou Dabi', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(93, 118, 'Milan', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(94, 119, 'Monaco', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(95, 120, 'San Francisco', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(96, 121, 'Bali', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(97, 127, 'Grand Millennium Al Barsha', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(98, 128, 'Four Seasons Beirut', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(99, 129, 'Four Seasons George V Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(100, 130, 'The Westin Palace Madrid', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(101, 131, 'LE MEURICE', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(102, 132, 'Emirates Palace Abu Dhabi', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(103, 133, 'BURJ AL ARAB', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(104, 134, 'Nuo Hotel Beijing', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(105, 135, 'The Ritz-Carlton Millenia Singapore', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(106, 136, 'W Maldives', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(107, 137, 'W Barcelone', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(108, 138, 'Cotton House Hotel Autograph Collection', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(109, 139, 'Kube', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(110, 140, 'The Gritti Palace A Luxury Collection Hotel', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(111, 141, 'Castello Di Casole', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(112, 142, 'Claridge\'s', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(113, 143, 'Embassy Suites by Hilton E Peoria Riverfront Conf Center', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(114, 144, 'Le Meridien New Delhi', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(115, 145, 'The Claridges New Delhi', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(116, 146, 'Sofitel Mumbai BKC', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(117, 147, 'Buckingham Palace', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(118, 148, 'Empire State Building', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(119, 149, 'Sagrada Familia', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(120, 150, 'Burj Khalifa', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(121, 151, 'Mont Fuji', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(122, 152, 'Château de Versailles', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(123, 153, 'London Eye', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(124, 154, 'Catacombes de Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(125, 155, 'Moulin Rouge', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(126, 156, 'Galerie Borghèse', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(127, 157, 'Colisée', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(128, 158, 'One World Observatory', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(129, 159, 'Studios Universal', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(130, 160, 'Jardins de la Baie', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(131, 161, 'Night Safari', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(132, 162, 'Théatre La Scala', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(133, 163, 'La Cène', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(134, 164, 'Abbaye de Montserrat', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(135, 165, 'Parc Güell', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(136, 166, 'Sumo', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(137, 167, 'Paris', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(138, 168, 'Rome', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(139, 169, 'New York', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(140, 170, 'Singapour', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(141, 171, 'Milan', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(142, 172, 'Barcelone', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(143, 173, 'Beyrouth', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(144, 174, 'Pékin', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(145, 175, 'Dubaï', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(146, 176, 'Lisbonne', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(147, 177, 'Istanbul', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(148, 178, 'Athènes', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(149, 179, 'Louvre', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(150, 180, 'Burj Khalifa', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(151, 181, 'Vatican', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(152, 182, 'Buckingham Palace', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(153, 183, 'Sagrada Familia', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(154, 184, 'Tour Eiffel', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(155, 185, 'Empire State', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(156, 186, 'Mont Fuji', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(157, 187, 'Grand Millennium Al Barsha', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(158, 188, 'Gloria', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(159, 189, 'Al Murooj Rotana Dubai', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(160, 190, 'Ghaya Grand Hotel & Apartments', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(161, 191, 'Capitol Hotel', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(162, 192, 'Broadway Hotel', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(163, 193, 'Mercure Grand Jebel Hafeet Al Ain Hotel', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(164, 194, 'FOUR SEASONS BEIRUT', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(165, 195, 'Kube', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(166, 196, 'Villa Beaumarchais', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(167, 197, 'Pavillon Nation', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(168, 198, 'Pavillon Opera Bourse', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(169, 199, 'Normandy', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(170, 200, 'Pavillon Louvre Rivoli', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(171, 201, 'Villa Montparnasse', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(172, 202, 'Le 1K', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(173, 203, 'Villa Royale Pigalle', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(174, 204, 'Villa Luxembourg', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(175, 205, 'Villa Eugenie', 'fr');
INSERT INTO `ml_main_entity_type_list` (`id`, `parent_id`, `name`, `language`) VALUES(176, 206, 'Pavillon Villiers Etoile', 'fr');


ALTER TABLE `ml_main_entity_type_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parent_id_2` (`parent_id`,`language`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `language` (`language`);


ALTER TABLE `ml_main_entity_type_list` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

