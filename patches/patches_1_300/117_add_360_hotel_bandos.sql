
DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 76302;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 76302;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 76302;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 76302;


INSERT INTO `hotel_to_hotel_divisions` (`name`,`hotel_id`,`hotel_division_id`,`sort_order`) 
VALUES 
('Main Entrance',76302,1,1),
('Arrival',76302,15,1),
('Entrance',76302,16,2),
('Lobby - View 1',76302,17,3),
('Lobby - View 2',76302,18,4),
('Gallery Restaurant',76302,28,2),
('View 1',76302,29,1),
('View 2',76302,30,2),
('View 3',76302,31,3),
('Sand Bar',76302,231,3),
('View 1',76302,232,1),
('View 2',76302,233,2),
('Koun',76302,167,4),
('Entrance',76302,168,1),
('View 1',76302,169,2),
('View 2',76302,170,3),
('View 3',76302,171,4),
('Sea Breeze',76302,8,5),
('View 1',76302,33,1),
('View 2',76302,34,2),
('View 3',76302,35,3),
('View 4',76302,165,4),
('Yumi Yaki',76302,184,6),
(' - ',76302,185,1),
('Pool Bar',76302,2,7),
('View 1',76302,21,1),
('View 2',76302,92,2),
('View 3',76302,186,3),
('View 4',76302,187,4),
('View 5',76302,658,5),
('View 6',76302,659,6),
('Huvan',76302,173,8),
('View 1',76302,174,1),
('View 2',76302,175,2),
('Orchid Spa',76302,4,9),
('View 1',76302,101,1),
('View 2',76302,24,2),
('View 3',76302,102,3),
('View 4',76302,103,4),
('View 5',76302,104,5),
('View 6',76302,105,6),
('View 7',76302,106,7),
('Salon',76302,671,8),
('Water sports',76302,718,10),
('View 1',76302,719,1),
('View 2',76302,720,2),
('View 3',76302,721,3),
('Dive Bandos',76302,714,11),
('View 1',76302,715,1),
('View 2',76302,716,2),
('Bandos Beaches',76302,722,12),
('View 1',76302,723,1),
('View 2',76302,724,2),
('View 3',76302,725,3),
('View 4',76302,726,4),
('View 5',76302,727,5),
('View 6',76302,728,6),
('View 7',76302,729,7),
('Bandos Walkways',76302,730,13),
('View 1',76302,731,1),
('View 2',76302,732,2),
('Clubhouse',76302,735,14),
('View 1',76302,736,1),
('View 2',76302,737,2),
('Lounge',76302,738,3),
('Gym',76302,739,4),
('Kids Club - View 1',76302,740,5),
('Kids Club - View 2',76302,741,6),
('Kids Club - View 3',76302,742,7),
('Tennis Court',76302,802,15),
(' - ',76302,803,1),
('Football Field',76302,285,16),
('View 1',76302,286,1),
('View 2',76302,287,2),
('Volleyball Court',76302,277,17),
('View 1',76302,279,1),
('View 2',76302,280,2),
('Shop',76302,746,18),
('View 1',76302,747,1),
('View 2',76302,748,2),
('View 3',76302,749,3),
('Classic Room',76302,752,19),
('View 1',76302,754,1),
('View 2',76302,755,2),
('Standard Room',76302,36,20),
('View 1',76302,37,1),
('View 2',76302,38,2),
('View 3',76302,39,3),
('View 4',76302,447,4),
('Deluxe Room',76302,133,21),
('View 1',76302,134,1),
('View 2',76302,135,2),
('View 3',76302,136,3),
('View 4',76302,137,4),
('View 5',76302,138,5),
('View 6',76302,713,6),
('Garden Villas',76302,758,22),
('View 1',76302,759,1),
('View 2',76302,760,2),
('View 3',76302,761,3),
('View 4',76302,762,4),
('View 5',76302,763,5),
('Water Villas',76302,769,23),
('View 1',76302,770,1),
('View 2',76302,771,2),
('Jaccuzzi Beach Villas',76302,780,24),
('View 1',76302,781,1),
('View 2',76302,782,2),
('View 3',76302,783,3),
('View 4',76302,784,4),
('View 5',76302,785,5),
('View 6',76302,786,6),
('View 7',76302,787,7),
('Jaccuzzi Pool Villa',76302,791,25),
('View 1',76302,792,1),
('View 2',76302,793,2),
('View 3',76302,794,3),
('View 4',76302,795,4),
('View 5',76302,796,5);

SELECT '76302 - deleting from hotel_to_hotel_divisions_categories' AS operation_label;

DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 76302;

SELECT '76302 - populate hotel_to_hotel_divisions_categories' AS operation_label;

INSERT INTO hotel_to_hotel_divisions_categories (hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 76302;

SELECT '76302 - delete from amadeus_hotel_image' AS operation_label;

DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 76302;

SELECT '76302 - populate amadeus_hotel_image' AS operation_label;

INSERT INTO `amadeus_hotel_image` (`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`) 
VALUES 
(0,'thumb.jpg',76302,1,2,NULL,'',0,0),
(0,'thumb.jpg',76302,15,2,'{"scene": {"view": {"hlookat": "-2.856", "vlookat": "-0.233"}}}','',0,0),
(0,'thumb.jpg',76302,16,2,'{"scene": {"view": {"hlookat": "-31.681", "vlookat": "0.777"}}}','',0,0),
(0,'thumb.jpg',76302,17,2,'{"scene": {"view": {"hlookat": "-167.902", "vlookat": "-0.043"}}}','',0,0),
(0,'thumb.jpg',76302,18,2,'{"scene": {"view": {"hlookat": "-15.675", "vlookat": "-0.238"}}}','',0,0),
(0,'thumb.jpg',76302,28,2,NULL,'',0,0),
(0,'thumb.jpg',76302,29,2,'{"scene": {"view": {"hlookat": "-13.592", "vlookat": "-0.523"}}}','',0,0),
(0,'thumb.jpg',76302,30,2,'{"scene": {"view": {"hlookat": "-3.199", "vlookat": "8.956"}}}','',0,0),
(0,'thumb.jpg',76302,31,2,'{"scene": {"view": {"hlookat": "346.987", "vlookat": "2.617"}}}','',0,0),
(0,'thumb.jpg',76302,231,2,NULL,'',0,0),
(0,'thumb.jpg',76302,232,2,'{"scene": {"view": {"hlookat": "-352.196", "vlookat": "1.155"}}}','',0,0),
(0,'thumb.jpg',76302,233,2,'{"scene": {"view": {"hlookat": "30.184", "vlookat": "0.324"}}}','',0,0),
(0,'thumb.jpg',76302,167,2,NULL,'',0,0),
(0,'thumb.jpg',76302,168,2,'{"scene": {"view": {"hlookat": "183.891", "vlookat": "6.500"}}}','',1,1),
(0,'thumb.jpg',76302,169,2,'{"scene": {"view": {"hlookat": "352.981", "vlookat": "1.713"}}}','',0,0),
(0,'thumb.jpg',76302,170,2,'{"scene": {"view": {"hlookat": "20.515", "vlookat": "3.308"}}}','',0,0),
(0,'thumb.jpg',76302,171,2,'{"scene": {"view": {"hlookat": "-142.926", "vlookat": "-0.176"}}}','',0,0),
(0,'thumb.jpg',76302,8,2,NULL,'',0,0),
(0,'thumb.jpg',76302,33,2,'{"scene": {"view": {"hlookat": "393.901", "vlookat": "-0.510"}}}','',0,0),
(0,'thumb.jpg',76302,34,2,'{"scene": {"view": {"hlookat": "42.183", "vlookat": "0.194"}}}','',1,1),
(0,'thumb.jpg',76302,35,2,'{"scene": {"view": {"hlookat": "-37.727", "vlookat": "3.716"}}}','',0,0),
(0,'thumb.jpg',76302,165,2,'{"scene": {"view": {"hlookat": "-1.755", "vlookat": "-7.494"}}}','',0,0),
(0,'thumb.jpg',76302,184,2,NULL,'',0,0),
(0,'thumb.jpg',76302,185,2,'{"scene": {"view": {"hlookat": "-155.926", "vlookat": "-0.671"}}}','',0,0),
(0,'thumb.jpg',76302,2,2,NULL,'',0,0),
(0,'thumb.jpg',76302,21,2,'{"scene": {"view": {"hlookat": "-353.740", "vlookat": "2.131"}}}','',0,0),
(0,'thumb.jpg',76302,92,2,'{"scene": {"view": {"hlookat": "180.344", "vlookat": "-0.287"}}}','',0,0),
(0,'thumb.jpg',76302,186,2,'{"scene": {"view": {"hlookat": "319.516", "vlookat": "-2.984"}}}','',1,1),
(0,'thumb.jpg',76302,187,2,'{"scene": {"view": {"hlookat": "-249.804", "vlookat": "-0.576"}}}','',0,0),
(0,'thumb.jpg',76302,658,2,'{"scene": {"view": {"hlookat": "9.027", "vlookat": "-1.312"}}}','',0,0),
(0,'thumb.jpg',76302,659,2,'{"scene": {"view": {"hlookat": "-2.428", "vlookat": "-4.250"}}}','',0,0),
(0,'thumb.jpg',76302,173,2,NULL,'',0,0),
(0,'thumb.jpg',76302,174,2,'{"scene": {"view": {"hlookat": "-58.380", "vlookat": "0.055"}}}','',0,0),
(0,'thumb.jpg',76302,175,2,'{"scene": {"view": {"hlookat": "186.473", "vlookat": "-1.305"}}}','',1,1),
(0,'thumb.jpg',76302,4,2,NULL,'',0,0),
(0,'thumb.jpg',76302,101,2,'{"scene": {"view": {"hlookat": "-43.971", "vlookat": "-0.020"}}}','',1,1),
(0,'thumb.jpg',76302,24,2,'{"scene": {"view": {"hlookat": "-190.459", "vlookat": "1.385"}}}','',0,0),
(0,'thumb.jpg',76302,102,2,'{"scene": {"view": {"hlookat": "-182.121", "vlookat": "-0.261"}}}','',0,0),
(0,'thumb.jpg',76302,103,2,'{"scene": {"view": {"hlookat": "371.585", "vlookat": "12.057"}}}','',0,0),
(0,'thumb.jpg',76302,104,2,'{"scene": {"view": {"hlookat": "-88.539", "vlookat": "2.156"}}}','',0,0),
(0,'thumb.jpg',76302,105,2,'{"scene": {"view": {"hlookat": "-334.679", "vlookat": "3.341"}}}','',0,0),
(0,'thumb.jpg',76302,106,2,'{"scene": {"view": {"hlookat": "-181.264", "vlookat": "34.206"}}}','',0,0),
(0,'thumb.jpg',76302,671,2,'{"scene": {"view": {"hlookat": "-310.121", "vlookat": "0.453"}}}','',0,0),
(0,'thumb.jpg',76302,718,2,NULL,'',0,0),
(0,'thumb.jpg',76302,719,2,'{"scene": {"view": {"hlookat": "-2.228", "vlookat": "-0.007"}}}','',0,0),
(0,'thumb.jpg',76302,720,2,'{"scene": {"view": {"hlookat": "28.766", "vlookat": "-21.243"}}}','',0,0),
(0,'thumb.jpg',76302,721,2,'{"scene": {"view": {"hlookat": "-12.639", "vlookat": "-6.531"}}}','',0,0),
(0,'thumb.jpg',76302,714,2,NULL,'',0,0),
(0,'thumb.jpg',76302,715,2,'{"scene": {"view": {"hlookat": "9.065", "vlookat": "0.366"}}}','',0,0),
(0,'thumb.jpg',76302,716,2,'{"scene": {"view": {"hlookat": "-427.716", "vlookat": "-1.445"}}}','',0,0),
(0,'thumb.jpg',76302,722,2,NULL,'',0,0),
(0,'thumb.jpg',76302,723,2,'{"scene": {"view": {"hlookat": "104.787", "vlookat": "-6.721"}}}','',1,1),
(0,'thumb.jpg',76302,724,2,'{"scene": {"view": {"hlookat": "-112.710", "vlookat": "0.816"}}}','',0,0),
(0,'thumb.jpg',76302,725,2,'{"scene": {"view": {"hlookat": "-1.639", "vlookat": "-2.765"}}}','',0,0),
(0,'thumb.jpg',76302,726,2,'{"scene": {"view": {"hlookat": "319.811", "vlookat": "5.133"}}}','',0,0),
(0,'thumb.jpg',76302,727,2,'{"scene": {"view": {"hlookat": "-193.714", "vlookat": "3.637"}}}','',0,0),
(0,'thumb.jpg',76302,728,2,'{"scene": {"view": {"hlookat": "63.419", "vlookat": "-10.700"}}}','',0,0),
(0,'thumb.jpg',76302,729,2,'{"scene": {"view": {"hlookat": "351.496", "vlookat": "-3.679"}}}','',0,0),
(0,'thumb.jpg',76302,730,2,NULL,'',0,0),
(0,'thumb.jpg',76302,731,2,'{"scene": {"view": {"hlookat": "229.259", "vlookat": "-4.573"}}}','',0,0),
(0,'thumb.jpg',76302,732,2,'{"scene": {"view": {"hlookat": "-102.564", "vlookat": "-18.889"}}}','',0,0),
(0,'thumb.jpg',76302,735,2,NULL,'',0,0),
(0,'thumb.jpg',76302,736,2,'{"scene": {"view": {"hlookat": "-16.562", "vlookat": "-1.274"}}}','',0,0),
(0,'thumb.jpg',76302,737,2,'{"scene": {"view": {"hlookat": "-367.428", "vlookat": "0.755"}}}','',0,0),
(0,'thumb.jpg',76302,738,2,'{"scene": {"view": {"hlookat": "-150.304", "vlookat": "0.106"}}}','',0,0),
(0,'thumb.jpg',76302,739,2,'{"scene": {"view": {"hlookat": "-192.377", "vlookat": "3.533"}}}','',0,0),
(0,'thumb.jpg',76302,740,2,'{"scene": {"view": {"hlookat": "-21.434", "vlookat": "0.870"}}}','',0,0),
(0,'thumb.jpg',76302,741,2,'{"scene": {"view": {"hlookat": "35.439", "vlookat": "1.205"}}}','',0,0),
(0,'thumb.jpg',76302,742,2,'{"scene": {"view": {"hlookat": "-25.886", "vlookat": "1.618"}}}','',0,0),
(0,'thumb.jpg',76302,802,2,NULL,'',0,0),
(0,'thumb.jpg',76302,803,2,'{"scene": {"view": {"hlookat": "-200.964", "vlookat": "2.057"}}}','',0,0),
(0,'thumb.jpg',76302,285,2,NULL,'',0,0),
(0,'thumb.jpg',76302,286,2,'{"scene": {"view": {"hlookat": "184.484", "vlookat": "-0.685"}}}','',1,1),
(0,'thumb.jpg',76302,287,2,'{"scene": {"view": {"hlookat": "60.194", "vlookat": "-1.475"}}}','',0,0),
(0,'thumb.jpg',76302,277,2,NULL,'',0,0),
(0,'thumb.jpg',76302,279,2,'{"scene": {"view": {"hlookat": "46.080", "vlookat": "-8.527"}}}','',0,0),
(0,'thumb.jpg',76302,280,2,'{"scene": {"view": {"hlookat": "97.902", "vlookat": "7.379"}}}','',0,0),
(0,'thumb.jpg',76302,746,2,NULL,'',0,0),
(0,'thumb.jpg',76302,747,2,'{"scene": {"view": {"hlookat": "160.288", "vlookat": "9.343"}}}','',0,0),
(0,'thumb.jpg',76302,748,2,'{"scene": {"view": {"hlookat": "-130.724", "vlookat": "5.619"}}}','',0,0),
(0,'thumb.jpg',76302,749,2,'{"scene": {"view": {"hlookat": "69.812", "vlookat": "0.538"}}}','',0,0),
(0,'thumb.jpg',76302,752,2,NULL,'',0,0),
(0,'thumb.jpg',76302,754,2,'{"scene": {"view": {"hlookat": "-71.565", "vlookat": "-3.018"}}}','',0,0),
(0,'thumb.jpg',76302,755,2,'{"scene": {"view": {"hlookat": "-185.133", "vlookat": "0.270"}}}','',1,1),
(0,'thumb.jpg',76302,36,2,NULL,'',0,0),
(0,'thumb.jpg',76302,37,2,'{"scene": {"view": {"hlookat": "-445.233", "vlookat": "2.558"}}}','',0,0),
(0,'thumb.jpg',76302,38,2,'{"scene": {"view": {"hlookat": "-18.123", "vlookat": "1.031"}}}','',1,1),
(0,'thumb.jpg',76302,39,2,'{"scene": {"view": {"hlookat": "144.406", "vlookat": "0.786"}}}','',0,0),
(0,'thumb.jpg',76302,447,2,'{"scene": {"view": {"hlookat": "-262.249", "vlookat": "3.094"}}}','',0,0),
(0,'thumb.jpg',76302,133,2,NULL,'',0,0),
(0,'thumb.jpg',76302,134,2,'{"scene": {"view": {"hlookat": "115.258", "vlookat": "1.039"}}}','',0,0),
(0,'thumb.jpg',76302,135,2,'{"scene": {"view": {"hlookat": "-16.196", "vlookat": "0.956"}}}','',1,1),
(0,'thumb.jpg',76302,136,2,'{"scene": {"view": {"hlookat": "149.479", "vlookat": "0.347"}}}','',0,0),
(0,'thumb.jpg',76302,137,2,'{"scene": {"view": {"hlookat": "232.216", "vlookat": "0.049"}}}','',0,0),
(0,'thumb.jpg',76302,138,2,'{"scene": {"view": {"hlookat": "32.711", "vlookat": "1.202"}}}','',0,0),
(0,'thumb.jpg',76302,713,2,'{"scene": {"view": {"hlookat": "-90.878", "vlookat": "-0.599"}}}','',0,0),
(0,'thumb.jpg',76302,758,2,NULL,'',0,0),
(0,'thumb.jpg',76302,759,2,'{"scene": {"view": {"hlookat": "4.998", "vlookat": "-2.128"}}}','',0,0),
(0,'thumb.jpg',76302,760,2,'{"scene": {"view": {"hlookat": "-0.344", "vlookat": "-10.542"}}}','',0,0),
(0,'thumb.jpg',76302,761,2,'{"scene": {"view": {"hlookat": "-112.419", "vlookat": "0.262"}}}','',0,0),
(0,'thumb.jpg',76302,762,2,'{"scene": {"view": {"hlookat": "-38.164", "vlookat": "17.739"}}}','',0,0),
(0,'thumb.jpg',76302,763,2,'{"scene": {"view": {"hlookat": "-182.891", "vlookat": "4.294"}}}','',1,1),
(0,'thumb.jpg',76302,769,2,NULL,'',0,0),
(0,'thumb.jpg',76302,770,2,'{"scene": {"view": {"hlookat": "11.806", "vlookat": "-0.984"}}}','',0,0),
(0,'thumb.jpg',76302,771,2,'{"scene": {"view": {"hlookat": "110.666", "vlookat": "-2.123"}}}','',1,1),
(0,'thumb.jpg',76302,780,2,NULL,'',0,0),
(0,'thumb.jpg',76302,781,2,'{"scene": {"view": {"hlookat": "-410.719", "vlookat": "-6.475"}}}','',1,1),
(0,'thumb.jpg',76302,782,2,'{"scene": {"view": {"hlookat": "352.662", "vlookat": "0.202"}}}','',0,0),
(0,'thumb.jpg',76302,783,2,'{"scene": {"view": {"hlookat": "43.902", "vlookat": "-0.578"}}}','',0,0),
(0,'thumb.jpg',76302,784,2,'{"scene": {"view": {"hlookat": "-131.237", "vlookat": "0.226"}}}','',0,0),
(0,'thumb.jpg',76302,785,2,'{"scene": {"view": {"hlookat": "-223.636", "vlookat": "2.855"}}}','',0,0),
(0,'thumb.jpg',76302,786,2,'{"scene": {"view": {"hlookat": "347.737", "vlookat": "3.369"}}}','',0,0),
(0,'thumb.jpg',76302,787,2,'{"scene": {"view": {"hlookat": "179.231", "vlookat": "4.350"}}}','',0,0),
(0,'thumb.jpg',76302,791,2,NULL,'',0,0),
(0,'thumb.jpg',76302,792,2,'{"scene": {"view": {"hlookat": "15.052", "vlookat": "-5.157"}}}','',0,0),
(0,'thumb.jpg',76302,793,2,'{"scene": {"view": {"hlookat": "-162.106", "vlookat": ".632"}}}','',0,0),
(0,'thumb.jpg',76302,794,2,'{"scene": {"view": {"hlookat": "179.745", "vlookat": "1.557"}}}','',0,0),
(0,'thumb.jpg',76302,795,2,'{"scene": {"view": {"hlookat": "-21.058", "vlookat": "-0.456"}}}','',0,0),
(0,'thumb.jpg',76302,796,2,'{"scene": {"view": {"hlookat": "-181.074", "vlookat": "-12.296"}}}','',1,1);


SELECT '76302 - delete from amadeus_hotel_image for media_type_id 2' AS operation_label;

DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 76302
AND d.parent_id IS NULL;

SELECT '76302 - delete from cms_hotel_image for media_type_id 2' AS operation_label;

DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 76302;

SELECT '76302 - populate cms_hotel_image from amadeus_hotel_image for media_type_id 2' AS operation_label;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 76302);
