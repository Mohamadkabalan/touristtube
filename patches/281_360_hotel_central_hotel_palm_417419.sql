DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 417419;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 417419;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417419;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417419;



INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',417419,1061,1),
('1',417419,1062,1),
('2',417419,1063,2),
('Lobby',417419,1,2),
('Reception',417419,15,1),
('1',417419,16,2),
('2',417419,17,3),
('Barista corner',417419,28,3),
('- ',417419,29,1),
('Choi',417419,167,4),
('1',417419,168,1),
('2',417419,169,2),
('3',417419,170,3),
('Hush beach',417419,173,5),
('1',417419,174,1),
('2',417419,175,2),
('La maison',417419,188,6),
('1',417419,189,1),
('2',417419,190,2),
('3',417419,191,3),
('4',417419,192,4),
('4',417419,461,5),
('Ya bahr',417419,205,7),
('1',417419,206,1),
('2',417419,207,2),
('Beach',417419,722,8),
('-',417419,723,1),
('Gift shop',417419,746,9),
('-',417419,747,1),
('Gym',417419,3,10),
('1',417419,80,1),
('2',417419,81,2),
('3',417419,82,3),
('Meeting rooms',417419,131,11),
('A',417419,132,1),
('B',417419,252,2),
('Pool',417419,992,12),
('1',417419,993,1),
('2',417419,994,2),
('Salon',417419,256,13),
('-',417419,257,1),
('Spa',417419,4,14),
('-',417419,101,1),
('Superior double room',417419,546,15),
('Bedroom',417419,547,1),
('Bathroom',417419,548,2),
('Superior twin room',417419,246,16),
('Bedroom',417419,247,1),
('Bathroom',417419,248,2),
('Premium room - sea view',417419,480,0),
('Bedroom',417419,481,1),
('Bathroom',417419,482,2),
('Balcony',417419,485,3),
('Premium twin room',417419,626,17),
('Bedroom',417419,627,1),
('Bathroom',417419,628,2),
('Balcony',417419,629,3),
('Royal suite',417419,1115,18),
('Entrance',417419,1116,1),
('Bedroom - Living',417419,1117,2),
('Bathroom',417419,1118,3),
('Guest bathroom',417419,1119,4),
('Balcony',417419,1120,5),
('Palm - one bedroom suite',417419,1104,19),
('Entrance',417419,1105,1),
('Living room',417419,1106,2),
('Bedroom',417419,1107,3),
('Bedroom balcony',417419,1108,4),
('Bathroom',417419,1109,5),
('Guest Bathroom',417419,1110,6),
('Balcony',417419,1111,7);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 417419;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',417419,1061,2,null,null,False,False,null),
(0,'thumb.jpg',417419,1062,2,'{"scene": {"view": {"hlookat": "-1.640","vlookat" : "-24.429","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417419,1063,2,'{"scene": {"view": {"hlookat": "1.148","vlookat" : "-12.134","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1,2,null,null,False,False,null),
(0,'thumb.jpg',417419,15,2,'{"scene": {"view": {"hlookat": "-10.658","vlookat" : "-4.919","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,16,2,'{"scene": {"view": {"hlookat": "-319.530","vlookat" : "0.871","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,17,2,'{"scene": {"view": {"hlookat": "-0.046","vlookat" : "-25.135","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',417419,28,2,null,null,False,False,null),
(0,'thumb.jpg',417419,29,2,'{"scene": {"view": {"hlookat": "3.444","vlookat" : "10.002","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,167,2,null,null,False,False,null),
(0,'thumb.jpg',417419,168,2,'{"scene": {"view": {"hlookat": "206.480","vlookat" : "4.966","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,169,2,'{"scene": {"view": {"hlookat": "80.846","vlookat" : "-0.328","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',417419,170,2,'{"scene": {"view": {"hlookat": "200.656","vlookat" : "-0.065","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,173,2,null,null,False,False,null),
(0,'thumb.jpg',417419,174,2,'{"scene": {"view": {"hlookat": "237.380","vlookat" : "2.946","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,175,2,'{"scene": {"view": {"hlookat": "233.555","vlookat" : "-9.423","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',417419,188,2,null,null,False,False,null),
(0,'thumb.jpg',417419,189,2,'{"scene": {"view": {"hlookat": "-0.492","vlookat" : "-6.559","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,190,2,'{"scene": {"view": {"hlookat": "-35.855","vlookat" : "20.926","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',417419,191,2,'{"scene": {"view": {"hlookat": "-49.676","vlookat" : "17.997","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,192,2,'{"scene": {"view": {"hlookat": "157.380","vlookat" : "-0.014","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,461,2,'{"scene": {"view": {"hlookat": "1.370","vlookat" : "-9.752","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,205,2,null,null,False,False,null),
(0,'thumb.jpg',417419,206,2,'{"scene": {"view": {"hlookat": "-175.399","vlookat" : "19.796","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',417419,207,2,'{"scene": {"view": {"hlookat": "249.000","vlookat" : "3.011","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,722,2,null,null,False,False,null),
(0,'thumb.jpg',417419,723,2,'{"scene": {"view": {"hlookat": "169.848","vlookat" : "0.985","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,746,2,null,null,False,False,null),
(0,'thumb.jpg',417419,747,2,'{"scene": {"view": {"hlookat": "51.152","vlookat" : "35.736","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,3,2,null,null,False,False,null),
(0,'thumb.jpg',417419,80,2,'{"scene": {"view": {"hlookat": "33.064","vlookat" : "17.362","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,81,2,'{"scene": {"view": {"hlookat": "7.552","vlookat" : "0.819","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,82,2,'{"scene": {"view": {"hlookat": "0.328","vlookat" : "-8.340","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,131,2,null,null,False,False,null),
(0,'thumb.jpg',417419,132,2,'{"scene": {"view": {"hlookat": "0.328","vlookat" : "33.777","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,252,2,'{"scene": {"view": {"hlookat": "1.115","vlookat" : "38.806","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,992,2,null,null,False,False,null),
(0,'thumb.jpg',417419,993,2,'{"scene": {"view": {"hlookat": "491.744","vlookat" : "10.930","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',417419,994,2,'{"scene": {"view": {"hlookat": "-181.173","vlookat" : "32.348","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,256,2,null,null,False,False,null),
(0,'thumb.jpg',417419,257,2,'{"scene": {"view": {"hlookat": "301.757","vlookat" : "14.629","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',417419,4,2,null,null,False,False,null),
(0,'thumb.jpg',417419,101,2,'{"scene": {"view": {"hlookat": "214.879","vlookat" : "23.358","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',417419,546,2,null,null,False,False,null),
(0,'thumb.jpg',417419,547,2,'{"scene": {"view": {"hlookat": "-66.151","vlookat" : "31.634","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',417419,548,2,'{"scene": {"view": {"hlookat": "55.858","vlookat" : "24.846","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,246,2,null,null,False,False,null),
(0,'thumb.jpg',417419,247,2,'{"scene": {"view": {"hlookat": "-42.139","vlookat" : "42.625","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',417419,248,2,'{"scene": {"view": {"hlookat": "64.469","vlookat" : "34.847","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,480,2,null,null,False,False,null),
(0,'thumb.jpg',417419,481,2,'{"scene": {"view": {"hlookat": "-45.248","vlookat" : "24.260","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',417419,482,2,'{"scene": {"view": {"hlookat": "-51.806","vlookat" : "14.729","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,485,2,'{"scene": {"view": {"hlookat": "-47.496","vlookat" : "18.813","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',417419,626,2,null,null,False,False,null),
(0,'thumb.jpg',417419,627,2,'{"scene": {"view": {"hlookat": "43.448","vlookat" : "36.234","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',417419,628,2,'{"scene": {"view": {"hlookat": "-59.334","vlookat" : "25.302","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,629,2,'{"scene": {"view": {"hlookat": "0.082","vlookat" : "3.930","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1115,2,null,null,False,False,null),
(0,'thumb.jpg',417419,1116,2,'{"scene": {"view": {"hlookat": "15.479","vlookat" : "1.490","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1117,2,'{"scene": {"view": {"hlookat": "-1.840","vlookat" : "29.775","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',417419,1118,2,'{"scene": {"view": {"hlookat": "-15.748","vlookat" : "22.780","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1119,2,'{"scene": {"view": {"hlookat": "1.118","vlookat" : "27.150","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1120,2,'{"scene": {"view": {"hlookat": "0.071","vlookat" : "28.113","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1104,2,null,null,False,False,null),
(0,'thumb.jpg',417419,1105,2,'{"scene": {"view": {"hlookat": "14.657","vlookat" : "-0.586","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1106,2,'{"scene": {"view": {"hlookat": "-26.565","vlookat" : "36.652","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1107,2,'{"scene": {"view": {"hlookat": "-52.737","vlookat" : "32.761","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',417419,1108,2,'{"scene": {"view": {"hlookat": "-197.710","vlookat" : "4.226","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1109,2,'{"scene": {"view": {"hlookat": "-0.000","vlookat" : "35.569","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1110,2,'{"scene": {"view": {"hlookat": "15.886","vlookat" : "13.926","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417419,1111,2,'{"scene": {"view": {"hlookat": "-1.804","vlookat" : "21.963","fov" : "140"}}}',null,True,True,17);



DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 417419
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 417419;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 417419);

