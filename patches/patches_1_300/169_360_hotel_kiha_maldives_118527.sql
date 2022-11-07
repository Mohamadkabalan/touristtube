DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 118527;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 118527;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 118527;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 118527;



INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',118527,79,1),
('1',118527,130,1),
('2',118527,193,2),
('3',118527,194,3),
('Lobby',118527,1,2),
('1',118527,15,1),
('2',118527,16,2),
('3',118527,17,3),
('Malaafaiy',118527,28,3),
('1',118527,29,1),
('2',118527,30,2),
('Raaveriya Bar',118527,7,4),
('1',118527,152,1),
('2',118527,153,2),
('Undhoali Bar',118527,184,5),
('-',118527,185,1),
('Sangu A La Carte',118527,167,6),
('1',118527,168,1),
('2',118527,169,2),
('Hanifaru Bay - Tea House',118527,8,7),
('-',118527,33,1),
('The Loft - Wine Cellar',118527,222,8),
('1',118527,223,1),
('2',118527,224,2),
('Raaveriya Beach',118527,722,9),
('-',118527,723,1),
('Infinity Pool',118527,2,10),
('1',118527,21,1),
('2',118527,92,2),
('3',118527,186,3),
('Raaveriya Pool',118527,992,11),
('1',118527,993,1),
('2',118527,994,2),
('Spa',118527,4,12),
('1',118527,101,1),
('2',118527,102,2),
('3',118527,103,3),
('4',118527,104,4),
('5',118527,105,5),
('6',118527,106,6),
('7',118527,671,7),
('Sports Centre',118527,3,13),
('1',118527,80,1),
('2',118527,81,2),
('3',118527,82,3),
('Tennis',118527,277,14),
('-',118527,279,1),
('Kids Club ',118527,84,15),
('-',118527,85,1),
('Reserve Beach Villa',118527,758,16),
('1',118527,759,1),
('2',118527,760,2),
('3',118527,761,3),
('4',118527,762,4),
('5',118527,763,5),
('Lagoon Prestige Beach Villa',118527,769,17),
('1',118527,770,1),
('2',118527,771,2),
('3',118527,772,3),
('4',118527,773,4),
('Waterfront Beach Villa With Private Pool',118527,780,18),
('1',118527,781,1),
('2',118527,782,2),
('3',118527,783,3),
('4',118527,784,4),
('5',118527,785,5),
('Sunset Prestige Pavillon Beach Villa',118527,791,19),
('1',118527,792,1),
('2',118527,793,2),
('3',118527,794,3),
('Family Junior Suite',118527,967,20),
('1',118527,968,1),
('2',118527,969,2),
('3',118527,970,3),
('4',118527,971,4),
('5',118527,972,5),
('6',118527,973,6),
('2 Bedroom Family Executive Suite With Pool',118527,975,21),
('1',118527,976,1),
('2',118527,977,2),
('3',118527,978,3),
('4',118527,979,4),
('5',118527,980,5),
('6',118527,981,6),
('Maldivian Suite With Pool',118527,605,22),
('1',118527,606,1),
('2',118527,607,2),
('3',118527,663,3),
('4',118527,608,4),
('5',118527,609,5),
('Water Villas',118527,820,23),
('1',118527,826,1),
('2',118527,827,2),
('3',118527,828,3),
('4',118527,829,4),
('Water Sport',118527,464,24),
('-',118527,465,1),
('Diving Center',118527,941,25),
('-',118527,942,1);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 118527;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',118527,79,2,null,null,False,False,null),
(0,'thumb.jpg',118527,130,2,'{"scene": {"view": {"hlookat": "300.264","vlookat" : "3.448","fov" : "138.123"}}}',null,False,False,null),
(0,'thumb.jpg',118527,193,2,'{"scene": {"view": {"hlookat": "200.495","vlookat" : "9.542","fov" : "138.618"}}}',null,True,True,1),
(0,'thumb.jpg',118527,194,2,'{"scene": {"view": {"hlookat": "266.351","vlookat" : "-3.275","fov" : "137.607"}}}',null,False,False,null),
(0,'thumb.jpg',118527,1,2,null,null,False,False,null),
(0,'thumb.jpg',118527,15,2,'{"scene": {"view": {"hlookat": "172.269","vlookat" : "-5.595","fov" : "138.967"}}}',null,True,True,2),
(0,'thumb.jpg',118527,16,2,'{"scene": {"view": {"hlookat": "901.838","vlookat" : "-8.229","fov" : "133.842"}}}',null,False,False,null),
(0,'thumb.jpg',118527,17,2,'{"scene": {"view": {"hlookat": "-9.649","vlookat" : "4.379","fov" : "139.959"}}}',null,False,False,null),
(0,'thumb.jpg',118527,28,2,null,null,False,False,null),
(0,'thumb.jpg',118527,29,2,'{"scene": {"view": {"hlookat": "-10.580","vlookat" : "8.942","fov" : "139.856"}}}',null,True,True,3),
(0,'thumb.jpg',118527,30,2,'{"scene": {"view": {"hlookat": "-8.696","vlookat" : "9.359","fov" : "136.278"}}}',null,False,False,null),
(0,'thumb.jpg',118527,7,2,null,null,False,False,null),
(0,'thumb.jpg',118527,152,2,'{"scene": {"view": {"hlookat": "542.089","vlookat" : "0.443","fov" : "140.000"}}}',null,True,True,4),
(0,'thumb.jpg',118527,153,2,'{"scene": {"view": {"hlookat": "172.975","vlookat" : "6.639","fov" : "139.813"}}}',null,False,False,null),
(0,'thumb.jpg',118527,184,2,null,null,False,False,null),
(0,'thumb.jpg',118527,185,2,'{"scene": {"view": {"hlookat": "-32.770","vlookat" : "1.225","fov" : "139.999"}}}',null,True,True,5),
(0,'thumb.jpg',118527,167,2,null,null,False,False,null),
(0,'thumb.jpg',118527,168,2,'{"scene": {"view": {"hlookat": "5.468","vlookat" : "15.219","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',118527,169,2,'{"scene": {"view": {"hlookat": "105.949","vlookat" : "13.966","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',118527,8,2,null,null,False,False,null),
(0,'thumb.jpg',118527,33,2,'{"scene": {"view": {"hlookat": "-62.287","vlookat" : "-0.980","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,222,2,null,null,False,False,null),
(0,'thumb.jpg',118527,223,2,'{"scene": {"view": {"hlookat": "-59.149","vlookat" : "3.244","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,224,2,'{"scene": {"view": {"hlookat": "280.214","vlookat" : "-3.820","fov" : "139.987"}}}',null,False,False,null),
(0,'thumb.jpg',118527,722,2,null,null,False,False,null),
(0,'thumb.jpg',118527,723,2,'{"scene": {"view": {"hlookat": "354.356","vlookat" : "-5.382","fov" : "140.000"}}}',null,True,True,6),
(0,'thumb.jpg',118527,2,2,null,null,False,False,null),
(0,'thumb.jpg',118527,21,2,'{"scene": {"view": {"hlookat": "327.079","vlookat" : "-4.990","fov" : "139.987"}}}',null,False,False,null),
(0,'thumb.jpg',118527,92,2,'{"scene": {"view": {"hlookat": "-129.487","vlookat" : "13.647","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,186,2,'{"scene": {"view": {"hlookat": "140.262","vlookat" : "-4.491","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,992,2,null,null,False,False,null),
(0,'thumb.jpg',118527,993,2,'{"scene": {"view": {"hlookat": "4.327","vlookat" : "0.201","fov" : "139.649"}}}',null,False,False,null),
(0,'thumb.jpg',118527,994,2,'{"scene": {"view": {"hlookat": "-19.214","vlookat" : "4.456","fov" : "139.537"}}}',null,False,False,null),
(0,'thumb.jpg',118527,4,2,null,null,False,False,null),
(0,'thumb.jpg',118527,101,2,'{"scene": {"view": {"hlookat": "-190.877","vlookat" : "4.416","fov" : "139.670"}}}',null,True,True,7),
(0,'thumb.jpg',118527,102,2,'{"scene": {"view": {"hlookat": "-198.580","vlookat" : "1.602","fov" : "139.781"}}}',null,False,False,null),
(0,'thumb.jpg',118527,103,2,'{"scene": {"view": {"hlookat": "-2.223","vlookat" : "1.567","fov" : "139.436"}}}',null,False,False,null),
(0,'thumb.jpg',118527,104,2,'{"scene": {"view": {"hlookat": "-2.404","vlookat" : "5.028","fov" : "139.781"}}}',null,False,False,null),
(0,'thumb.jpg',118527,105,2,'{"scene": {"view": {"hlookat": "-13.010","vlookat" : "11.534","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',118527,106,2,'{"scene": {"view": {"hlookat": "-34.146","vlookat" : "1.596","fov" : "139.002"}}}',null,False,False,null),
(0,'thumb.jpg',118527,671,2,'{"scene": {"view": {"hlookat": "-68.646","vlookat" : "-0.141","fov" : "139.072"}}}',null,False,False,null),
(0,'thumb.jpg',118527,3,2,null,null,False,False,null),
(0,'thumb.jpg',118527,80,2,'{"scene": {"view": {"hlookat": "-17.994","vlookat" : "0.600","fov" : "139.139"}}}',null,False,False,null),
(0,'thumb.jpg',118527,81,2,'{"scene": {"view": {"hlookat": "-17.523","vlookat" : "2.273","fov" : "139.649"}}}',null,False,False,null),
(0,'thumb.jpg',118527,82,2,'{"scene": {"view": {"hlookat": "0.330","vlookat" : "0.000","fov" : "139.670"}}}',null,False,False,null),
(0,'thumb.jpg',118527,277,2,null,null,False,False,null),
(0,'thumb.jpg',118527,279,2,'{"scene": {"view": {"hlookat": "-6.885","vlookat" : "5.086","fov" : "139.915"}}}',null,False,False,null),
(0,'thumb.jpg',118527,84,2,null,null,False,False,null),
(0,'thumb.jpg',118527,85,2,'{"scene": {"view": {"hlookat": "-6.725","vlookat" : "5.376","fov" : "139.991"}}}',null,False,False,null),
(0,'thumb.jpg',118527,758,2,null,null,False,False,null),
(0,'thumb.jpg',118527,759,2,'{"scene": {"view": {"hlookat": "173.346","vlookat" : "-3.310","fov" : "139.934"}}}',null,False,False,null),
(0,'thumb.jpg',118527,760,2,'{"scene": {"view": {"hlookat": "-135.234","vlookat" : "-0.822","fov" : "140.000"}}}',null,True,True,8),
(0,'thumb.jpg',118527,761,2,'{"scene": {"view": {"hlookat": "185.940","vlookat" : "-0.240","fov" : "139.915"}}}',null,False,False,null),
(0,'thumb.jpg',118527,762,2,'{"scene": {"view": {"hlookat": "177.764","vlookat" : "1.439","fov" : "139.977"}}}',null,False,False,null),
(0,'thumb.jpg',118527,763,2,'{"scene": {"view": {"hlookat": "-66.550","vlookat" : "7.389","fov" : "139.991"}}}',null,False,False,null),
(0,'thumb.jpg',118527,769,2,null,null,False,False,null),
(0,'thumb.jpg',118527,770,2,'{"scene": {"view": {"hlookat": "376.649","vlookat" : "7.436","fov" : "139.760"}}}',null,True,True,9),
(0,'thumb.jpg',118527,771,2,'{"scene": {"view": {"hlookat": "256.413","vlookat" : "-5.255","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',118527,772,2,'{"scene": {"view": {"hlookat": "267.133","vlookat" : "0.012","fov" : "139.706"}}}',null,False,False,null),
(0,'thumb.jpg',118527,773,2,'{"scene": {"view": {"hlookat": "156.894","vlookat" : "-2.663","fov" : "139.893"}}}',null,False,False,null),
(0,'thumb.jpg',118527,780,2,null,null,False,False,null),
(0,'thumb.jpg',118527,781,2,'{"scene": {"view": {"hlookat": "176.908","vlookat" : "10.112","fov" : "139.842"}}}',null,True,True,10),
(0,'thumb.jpg',118527,782,2,'{"scene": {"view": {"hlookat": "147.590","vlookat" : "0.097","fov" : "139.893"}}}',null,False,False,null),
(0,'thumb.jpg',118527,783,2,'{"scene": {"view": {"hlookat": "-22.904","vlookat" : "-1.215","fov" : "139.991"}}}',null,False,False,null),
(0,'thumb.jpg',118527,784,2,'{"scene": {"view": {"hlookat": "158.691","vlookat" : "0.391","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',118527,785,2,'{"scene": {"view": {"hlookat": "183.123","vlookat" : "11.966","fov" : "136.592"}}}',null,False,False,null),
(0,'thumb.jpg',118527,791,2,null,null,False,False,null),
(0,'thumb.jpg',118527,792,2,'{"scene": {"view": {"hlookat": "0.013","vlookat" : "0.000","fov" : "139.987"}}}',null,True,True,11),
(0,'thumb.jpg',118527,793,2,'{"scene": {"view": {"hlookat": "-205.402","vlookat" : "6.161","fov" : "139.813"}}}',null,False,False,null),
(0,'thumb.jpg',118527,794,2,'{"scene": {"view": {"hlookat": "0.188","vlookat" : "0.000","fov" : "139.813"}}}',null,False,False,null),
(0,'thumb.jpg',118527,967,2,null,null,False,False,null),
(0,'thumb.jpg',118527,968,2,'{"scene": {"view": {"hlookat": "-15.046","vlookat" : "2.584","fov" : "139.797"}}}',null,True,True,12),
(0,'thumb.jpg',118527,969,2,'{"scene": {"view": {"hlookat": "-206.908","vlookat" : "8.761","fov" : "139.606"}}}',null,False,False,null),
(0,'thumb.jpg',118527,970,2,'{"scene": {"view": {"hlookat": "-193.067","vlookat" : "3.126","fov" : "139.670"}}}',null,False,False,null),
(0,'thumb.jpg',118527,971,2,'{"scene": {"view": {"hlookat": "-207.600","vlookat" : "2.034","fov" : "139.512"}}}',null,False,False,null),
(0,'thumb.jpg',118527,972,2,'{"scene": {"view": {"hlookat": "-198.667","vlookat" : "4.528","fov" : "139.709"}}}',null,False,False,null),
(0,'thumb.jpg',118527,973,2,'{"scene": {"view": {"hlookat": "0.416","vlookat" : "0.000","fov" : "139.584"}}}',null,False,False,null),
(0,'thumb.jpg',118527,975,2,null,null,False,False,null),
(0,'thumb.jpg',118527,976,2,'{"scene": {"view": {"hlookat": "-7.048","vlookat" : "2.699","fov" : "140.000"}}}',null,True,True,13),
(0,'thumb.jpg',118527,977,2,'{"scene": {"view": {"hlookat": "12.692","vlookat" : "11.994","fov" : "139.996"}}}',null,False,False,null),
(0,'thumb.jpg',118527,978,2,'{"scene": {"view": {"hlookat": "705.118","vlookat" : "4.704","fov" : "139.719"}}}',null,False,False,null),
(0,'thumb.jpg',118527,979,2,'{"scene": {"view": {"hlookat": "524.159","vlookat" : "0.408","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',118527,980,2,'{"scene": {"view": {"hlookat": "361.954","vlookat" : "10.902","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,981,2,'{"scene": {"view": {"hlookat": "-416.331","vlookat" : "-0.029","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,605,2,null,null,False,False,null),
(0,'thumb.jpg',118527,606,2,'{"scene": {"view": {"hlookat": "-0.050","vlookat" : "-20.870","fov" : "140.000"}}}',null,True,True,14),
(0,'thumb.jpg',118527,607,2,'{"scene": {"view": {"hlookat": "159.871","vlookat" : "-1.498","fov" : "139.462"}}}',null,False,False,null),
(0,'thumb.jpg',118527,663,2,'{"scene": {"view": {"hlookat": "75.564","vlookat" : "0.675","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,608,2,'{"scene": {"view": {"hlookat": "189.109","vlookat" : "8.532","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,609,2,'{"scene": {"view": {"hlookat": "233.831","vlookat" : "1.769","fov" : "139.996"}}}',null,False,False,null),
(0,'thumb.jpg',118527,820,2,null,null,False,False,null),
(0,'thumb.jpg',118527,826,2,'{"scene": {"view": {"hlookat": "380.056","vlookat" : "11.851","fov" : "139.785"}}}',null,True,True,15),
(0,'thumb.jpg',118527,827,2,'{"scene": {"view": {"hlookat": "348.456","vlookat" : "2.550","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',118527,828,2,'{"scene": {"view": {"hlookat": "332.228","vlookat" : "-3.279","fov" : "139.991"}}}',null,False,False,null),
(0,'thumb.jpg',118527,829,2,'{"scene": {"view": {"hlookat": "-1.780","vlookat" : "0.113","fov" : "139.813"}}}',null,False,False,null),
(0,'thumb.jpg',118527,464,2,null,null,False,False,null),
(0,'thumb.jpg',118527,465,2,'{"scene": {"view": {"hlookat": "1.458","vlookat" : "0.208","fov" : "139.690"}}}',null,True,True,16),
(0,'thumb.jpg',118527,941,2,null,null,False,False,null),
(0,'thumb.jpg',118527,942,2,'{"scene": {"view": {"hlookat": "0.440","vlookat" : "0.000","fov" : "139.561"}}}',null,True,True,17);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 118527
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 118527;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 118527);

