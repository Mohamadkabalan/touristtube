INSERT INTO `hotel_to_hotel_divisions` (`name`,`hotel_id`,`hotel_division_id`,`sort_order`) 
VALUES 
('Street View',53187,556,1),
('Day 1',53187,557,1),
('Day 2',53187,558,2),
('Sunset 1',53187,559,3),
('Sunset 2',53187,560,4),
('Lobby',53187,1,2),
('View 1',53187,15,1),
('View 2',53187,16,2),
('View 3',53187,17,3),
('View 4',53187,18,4),
('Level 6- Breakfast Room',53187,6,3),
('View 1',53187,25,1),
('View 2',53187,26,2),
('View 3',53187,27,3),
('View 4',53187,602,4),
('View 5',53187,603,5),
('View 6',53187,604,6),
('Rooftop',53187,7,4),
('1st Floor - View 1',53187,152,1),
('1st Floor - View 2',53187,153,2),
('1st Floor - View 3',53187,154,3),
('2nd Floor - View 1',53187,155,4),
('2nd Floor - View 2',53187,156,5),
('2nd Floor - View 3',53187,157,6),
('2nd Floor - View 4',53187,158,7),
('Swimming Pool',53187,2,5),
('View 1',53187,21,1),
('View 2',53187,92,2),
('View 3',53187,186,3),
('Fitness Center - Gym',53187,3,6),
(' -  ',53187,80,1),
('Petit Salon',53187,94,7),
('View 1',53187,95,1),
('View 2',53187,96,2),
('Gallery - Meeting Room',53187,131,8),
('View 1',53187,132,1),
('View 2',53187,252,2),
('View 3',53187,253,3),
('Shopping Arcade',53187,464,9),
('View 1',53187,465,1),
('View 2',53187,466,2),
('Al Hindi Restaurant',53187,28,10),
('View 1',53187,29,1),
('View 2',53187,30,2),
('View 3',53187,31,3),
('Terrace',53187,167,11),
(' - ',53187,168,1),
('Deluxe Room',53187,133,12),
('View 1',53187,134,1),
('View 2',53187,135,2),
('Bathroom',53187,138,3),
('Entrance',53187,137,4),
('Premium Room - King Size Bed',53187,480,13),
('View 1',53187,481,1),
('View 2',53187,482,2),
('Bathroom',53187,484,3),
('Premium Room - Twin Bed',53187,626,14),
('View 1',53187,627,1),
('View 2',53187,628,2),
('View 3',53187,629,3),
('Bathroom',53187,630,4),
('Junior Suite',53187,212,15),
('View 1',53187,216,1),
('View 2',53187,213,2),
('Bathroom',53187,217,3),
('Senior Suite - City View 1',53187,605,16),
('View 1',53187,606,1),
('View 2',53187,607,2),
('Bathroom - View 1',53187,608,3),
('Bathroom - View 2',53187,609,4),
('Senior Suite - City View 2',53187,610,17),
('Living Room View 1',53187,611,1),
('Living Room View 2',53187,612,2),
('Bedroom View 1',53187,613,3),
('Bedroom view 2',53187,614,4),
('Bathroom',53187,615,5),
('Senior Suite - 1 Bedroom',53187,616,18),
('View 1',53187,617,1),
('View 2',53187,618,2),
('Bathroom',53187,619,3),
('Senior Suite - Panoramic Sea View',53187,620,19),
('View 1',53187,621,1),
('View 2',53187,622,2),
('View 3',53187,623,3),
('Bathroom',53187,624,4),
('Presidential Suite',53187,355,20),
('View 1',53187,625,1),
('View 2',53187,359,2),
('View 3',53187,360,3),
('View 4',53187,356,4),
('View 5',53187,361,5),
('View 6',53187,362,6),
('View 7',53187,363,7);

DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 53187;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 53187;

DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 53187;

INSERT INTO `amadeus_hotel_image` (`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`) 
VALUES 
(0,'thumb.jpg',53187,557,2,'{"scene": {"view": {"hlookat": "-165.113", "vlookat": "-28.938"}}}','',0,0),
(0,'thumb.jpg',53187,558,2,'{"scene": {"view": {"hlookat": "-202.745", "vlookat": "-0.1270"}}}','',0,0),
(0,'thumb.jpg',53187,559,2,'{"scene": {"view": {"hlookat": "-31.710", "vlookat": "-30.771"}}}','',1,1),
(0,'thumb.jpg',53187,560,2,'{"scene": {"view": {"hlookat": "-20.987", "vlookat": "-26.233"}}}','',0,0),
(0,'thumb.jpg',53187,15,2,'{"scene": {"view": {"hlookat": "-28.396", "vlookat": "0.919"}}}','',0,0),
(0,'thumb.jpg',53187,16,2,'{"scene": {"view": {"hlookat": "-198.404", "vlookat": "-0.331"}}}','',0,0),
(0,'thumb.jpg',53187,17,2,'{"scene": {"view": {"hlookat": "-219.737", "vlookat": "7.461"}}}','',1,1),
(0,'thumb.jpg',53187,18,2,'{"scene": {"view": {"hlookat": "-179.612", "vlookat": "10.042"}}}','',0,0),
(0,'thumb.jpg',53187,25,2,'{"scene": {"view": {"hlookat": "-371.890", "vlookat": "21.923"}}}','',1,1),
(0,'thumb.jpg',53187,26,2,'{"scene": {"view": {"hlookat": "376.736", "vlookat": "-0.162"}}}','',0,0),
(0,'thumb.jpg',53187,27,2,'{"scene": {"view": {"hlookat": "82.547", "vlookat": "-0.031"}}}','',0,0),
(0,'thumb.jpg',53187,602,2,'{"scene": {"view": {"hlookat": "390.915", "vlookat": "17.181"}}}','',0,0),
(0,'thumb.jpg',53187,603,2,'{"scene": {"view": {"hlookat": "-88.581", "vlookat": "-1.384"}}}','',0,0),
(0,'thumb.jpg',53187,604,2,'{"scene": {"view": {"hlookat": "184.512", "vlookat": "4.902"}}}','',0,0),
(0,'thumb.jpg',53187,152,2,'{"scene": {"view": {"hlookat": "-397.006", "vlookat": "-0.929"}}}','',0,0),
(0,'thumb.jpg',53187,153,2,'{"scene": {"view": {"hlookat": "-491.022", "vlookat": "1.081"}}}','',0,0),
(0,'thumb.jpg',53187,154,2,'{"scene": {"view": {"hlookat": "373.352", "vlookat": "-0.645"}}}','',1,1),
(0,'thumb.jpg',53187,155,2,'{"scene": {"view": {"hlookat": "-340.889", "vlookat": "4.480"}}}','',0,0),
(0,'thumb.jpg',53187,156,2,'{"scene": {"view": {"hlookat": "401.732", "vlookat": "0.438"}}}','',0,0),
(0,'thumb.jpg',53187,157,2,'{"scene": {"view": {"hlookat": "-13.463", "vlookat": "-10.654"}}}','',0,0),
(0,'thumb.jpg',53187,158,2,'{"scene": {"view": {"hlookat": "160.108", "vlookat": "-0.599"}}}','',0,0),
(0,'thumb.jpg',53187,21,2,'{"scene": {"view": {"hlookat": "386.751", "vlookat": "9.898"}}}','',0,0),
(0,'thumb.jpg',53187,92,2,'{"scene": {"view": {"hlookat": "-191.114", "vlookat": "-1.675"}}}','',0,0),
(0,'thumb.jpg',53187,186,2,'{"scene": {"view": {"hlookat": "210.675", "vlookat": "0.784"}}}','',1,1),
(0,'thumb.jpg',53187,80,2,'{"scene": {"view": {"hlookat": "-210.123", "vlookat": "-0.047"}}}','',0,0),
(0,'thumb.jpg',53187,95,2,'{"scene": {"view": {"hlookat": "678.765", "vlookat": "-0.424"}}}','',0,0),
(0,'thumb.jpg',53187,96,2,'{"scene": {"view": {"hlookat": "-385.129", "vlookat": "-0.568"}}}','',0,0),
(0,'thumb.jpg',53187,132,2,'{"scene": {"view": {"hlookat": "3967.971", "vlookat": "3.110"}}}','',0,0),
(0,'thumb.jpg',53187,252,2,'{"scene": {"view": {"hlookat": "-197.649", "vlookat": "0.495"}}}','',0,0),
(0,'thumb.jpg',53187,253,2,'{"scene": {"view": {"hlookat": "-35.574", "vlookat": "-1.631"}}}','',0,0),
(0,'thumb.jpg',53187,465,2,'{"scene": {"view": {"hlookat": "-525.686", "vlookat": "0.393"}}}','',0,0),
(0,'thumb.jpg',53187,466,2,'{"scene": {"view": {"hlookat": "332.303", "vlookat": "-0.471"}}}','',0,0),
(0,'thumb.jpg',53187,29,2,'{"scene": {"view": {"hlookat": "303.697", "vlookat": "-0.155"}}}','',0,0),
(0,'thumb.jpg',53187,30,2,'{"scene": {"view": {"hlookat": "-76.789", "vlookat": "0.232"}}}','',0,0),
(0,'thumb.jpg',53187,31,2,'{"scene": {"view": {"hlookat": "-94.869", "vlookat": "-1.078"}}}','',0,0),
(0,'thumb.jpg',53187,168,2,'{"scene": {"view": {"hlookat": "7.379", "vlookat": "-0.000"}}}','',0,0),
(0,'thumb.jpg',53187,134,2,'{"scene": {"view": {"hlookat": "24.194", "vlookat": "2.662"}}}','',0,0),
(0,'thumb.jpg',53187,135,2,'{"scene": {"view": {"hlookat": "-202.739", "vlookat": "2.552"}}}','',1,1),
(0,'thumb.jpg',53187,138,2,'{"scene": {"view": {"hlookat": "9.355", "vlookat": "3.941"}}}','',0,0),
(0,'thumb.jpg',53187,137,2,'{"scene": {"view": {"hlookat": "-70.010", "vlookat": "-1.146"}}}','',0,0),
(0,'thumb.jpg',53187,481,2,'{"scene": {"view": {"hlookat": "-177.437", "vlookat": "0.826"}}}','',1,1),
(0,'thumb.jpg',53187,482,2,'{"scene": {"view": {"hlookat": "310.238", "vlookat": "0.006"}}}','',0,0),
(0,'thumb.jpg',53187,484,2,'{"scene": {"view": {"hlookat": "21.444", "vlookat": "1.236"}}}','',0,0),
(0,'thumb.jpg',53187,627,2,'{"scene": {"view": {"hlookat": "14.600", "vlookat": "17.322"}}}','',1,1),
(0,'thumb.jpg',53187,628,2,'{"scene": {"view": {"hlookat": "167.719", "vlookat": "-1.029"}}}','',0,0),
(0,'thumb.jpg',53187,629,2,'{"scene": {"view": {"hlookat": "387.938", "vlookat": "0.701"}}}','',0,0),
(0,'thumb.jpg',53187,630,2,'{"scene": {"view": {"hlookat": "-110.914", "vlookat": "-1.926"}}}','',0,0),
(0,'thumb.jpg',53187,216,2,'{"scene": {"view": {"hlookat": "87.906", "vlookat": "1.919"}}}','',1,1),
(0,'thumb.jpg',53187,213,2,'{"scene": {"view": {"hlookat": "-172.916", "vlookat": "0.032"}}}','',0,0),
(0,'thumb.jpg',53187,217,2,'{"scene": {"view": {"hlookat": "-214.655", "vlookat": "2.571"}}}','',0,0),
(0,'thumb.jpg',53187,606,2,'{"scene": {"view": {"hlookat": "0.665", "vlookat": "30.273"}}}','',1,1),
(0,'thumb.jpg',53187,607,2,'{"scene": {"view": {"hlookat": "-64.849", "vlookat": "-0.492"}}}','',0,0),
(0,'thumb.jpg',53187,608,2,'{"scene": {"view": {"hlookat": "-383.128", "vlookat": "2.188"}}}','',0,0),
(0,'thumb.jpg',53187,609,2,'{"scene": {"view": {"hlookat": "0", "vlookat": "0"}}}','',0,0),
(0,'thumb.jpg',53187,611,2,'{"scene": {"view": {"hlookat": "-229.098", "vlookat": "1.420"}}}','',0,0),
(0,'thumb.jpg',53187,612,2,'{"scene": {"view": {"hlookat": "126.960", "vlookat": "0.060"}}}','',0,0),
(0,'thumb.jpg',53187,613,2,'{"scene": {"view": {"hlookat": "-27.324", "vlookat": "0.142"}}}','',1,1),
(0,'thumb.jpg',53187,614,2,'{"scene": {"view": {"hlookat": "-183.783", "vlookat": "-0.856"}}}','',0,0),
(0,'thumb.jpg',53187,615,2,'{"scene": {"view": {"hlookat": "-57.627", "vlookat": "-0.143"}}}','',0,0),
(0,'thumb.jpg',53187,617,2,'{"scene": {"view": {"hlookat": "1.751", "vlookat": "-1.315"}}}','',1,1),
(0,'thumb.jpg',53187,618,2,'{"scene": {"view": {"hlookat": "138.518", "vlookat": "1.055"}}}','',0,0),
(0,'thumb.jpg',53187,619,2,'{"scene": {"view": {"hlookat": "-115.083", "vlookat": "-0.760"}}}','',0,0),
(0,'thumb.jpg',53187,621,2,'{"scene": {"view": {"hlookat": "-36.214", "vlookat": "0.657"}}}','',0,0),
(0,'thumb.jpg',53187,622,2,'{"scene": {"view": {"hlookat": "-27.321", "vlookat": "-0.122"}}}','',0,0),
(0,'thumb.jpg',53187,623,2,'{"scene": {"view": {"hlookat": "-136.969", "vlookat": "-0.653"}}}','',0,0),
(0,'thumb.jpg',53187,624,2,'{"scene": {"view": {"hlookat": "-165.405", "vlookat": "-1.557"}}}','',0,0),
(0,'thumb.jpg',53187,625,2,'{"scene": {"view": {"hlookat": "163.907", "vlookat": "-0.072"}}}','',0,0),
(0,'thumb.jpg',53187,359,2,'{"scene": {"view": {"hlookat": "-8.955", "vlookat": "0.334"}}}','',1,1),
(0,'thumb.jpg',53187,360,2,'{"scene": {"view": {"hlookat": "-561.162", "vlookat": "0.066"}}}','',0,0),
(0,'thumb.jpg',53187,356,2,'{"scene": {"view": {"hlookat": "-577.084", "vlookat": "0.002"}}}','',0,0),
(0,'thumb.jpg',53187,361,2,'{"scene": {"view": {"hlookat": "-56.862", "vlookat": "-1.570"}}}','',0,0),
(0,'thumb.jpg',53187,362,2,'{"scene": {"view": {"hlookat": "-203.512", "vlookat": "-0.276"}}}','',1,1),
(0,'thumb.jpg',53187,363,2,'{"scene": {"view": {"hlookat": "23.090", "vlookat": "1.476"}}}','',0,0);














