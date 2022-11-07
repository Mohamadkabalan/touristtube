DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 417420;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 417420;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417420;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417420;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',417420,556,1),
('1',417420,557,1),
('2',417420,558,2),
('3',417420,559,3),
('4',417420,560,4),
('5',417420,561,5),
('6',417420,562,6),
('Lobby',417420,1,2),
('1',417420,15,1),
('2',417420,16,2),
('3',417420,17,3),
('4',417420,18,4),
('Reception',417420,19,5),
('Casa Rosa Pub',417420,28,3),
('1',417420,29,1),
('2',417420,30,2),
('3',417420,31,3),
('Chutney Restaurant',417420,167,4),
('1',417420,168,1),
('2',417420,169,2),
('3',417420,170,3),
('Forrest Resto Lounge and Bar',417420,231,5),
('1',417420,232,1),
('2',417420,233,2),
('Mango Terrace Restaurant',417420,173,6),
('1',417420,174,1),
('2',417420,175,2),
('3',417420,176,3),
('4',417420,177,4),
('Arossim beach',417420,722,7),
('-',417420,723,1),
('Ayura Ayurvedic Spa',417420,4,8),
('1',417420,101,1),
('2',417420,102,2),
('Checkers',417420,1005,9),
(' -',417420,1006,1),
('Conference hall',417420,131,10),
('-',417420,132,1),
('Eden Gardens',417420,956,11),
('1',417420,957,1),
('2',417420,958,2),
('3',417420,959,3),
('Gym',417420,3,12),
('-',417420,80,1),
('Pool',417420,2,13),
('1',417420,92,1),
('2',417420,93,2),
('3',417420,186,3),
('4',417420,187,4),
('Shopping Arcade',417420,746,14),
('1',417420,747,1),
('2',417420,748,2),
('3',417420,749,3),
('Tennis court',417420,277,15),
('1',417420,279,1),
('2',417420,280,2),
('Walkways',417420,730,16),
('1',417420,731,1),
('2',417420,732,2),
('3',417420,733,3),
('4',417420,734,4),
('Club room',417420,40,17),
('Bedroom',417420,41,1),
('Bathroom',417420,42,2),
('Balcony',417420,43,3),
('Superior room',417420,486,18),
('Bedroom',417420,487,1),
('Bathroom',417420,488,2),
('Balcony',417420,489,3),
('Deluxe room',417420,1072,19),
('Bedroom',417420,1073,1),
('Bathroom',417420,1074,2),
('Balcony',417420,1075,3),
('Suite',417420,1104,20),
('Living room',417420,1105,1),
('Bedroom',417420,1106,2),
('Bathroom',417420,1107,3),
('Balcony',417420,1108,4);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 417420;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',417420,556,2,null,null,False,False,null),
(0,'thumb.jpg',417420,557,2,'{"scene": {"view": {"hlookat": "-31.596","vlookat" : "-8.947","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417420,558,2,'{"scene": {"view": {"hlookat": "492.893","vlookat" : "-4.730","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,559,2,'{"scene": {"view": {"hlookat": "235.837","vlookat" : "-8.772","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,560,2,'{"scene": {"view": {"hlookat": "402.684","vlookat" : "-12.270","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,561,2,'{"scene": {"view": {"hlookat": "378.909","vlookat" : "-4.441","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,562,2,'{"scene": {"view": {"hlookat": "-12.459","vlookat" : "0.656","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,1,2,null,null,False,False,null),
(0,'thumb.jpg',417420,15,2,'{"scene": {"view": {"hlookat": "364.217","vlookat" : "-1.745","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,16,2,'{"scene": {"view": {"hlookat": "206.523","vlookat" : "-0.271","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,17,2,'{"scene": {"view": {"hlookat": "3.856","vlookat" : "5.630","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,18,2,'{"scene": {"view": {"hlookat": "130.668","vlookat" : "15.357","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',417420,19,2,'{"scene": {"view": {"hlookat": "-127.204","vlookat" : "9.868","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,28,2,null,null,False,False,null),
(0,'thumb.jpg',417420,29,2,'{"scene": {"view": {"hlookat": "116.378","vlookat" : "17.343","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',417420,30,2,'{"scene": {"view": {"hlookat": "0.656","vlookat" : "28.355","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,31,2,'{"scene": {"view": {"hlookat": "-414.363","vlookat" : "10.743","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,167,2,null,null,False,False,null),
(0,'thumb.jpg',417420,168,2,'{"scene": {"view": {"hlookat": "-11.524","vlookat" : "23.351","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,169,2,'{"scene": {"view": {"hlookat": "71.458","vlookat" : "23.579","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',417420,170,2,'{"scene": {"view": {"hlookat": "-35.860","vlookat" : "15.313","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,231,2,null,null,False,False,null),
(0,'thumb.jpg',417420,232,2,'{"scene": {"view": {"hlookat": "32.133","vlookat" : "6.231","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',417420,233,2,'{"scene": {"view": {"hlookat": "194.171","vlookat" : "9.959","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,173,2,null,null,False,False,null),
(0,'thumb.jpg',417420,174,2,'{"scene": {"view": {"hlookat": "4.100","vlookat" : "-1.641","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,175,2,'{"scene": {"view": {"hlookat": "155.300","vlookat" : "-1.993","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,176,2,'{"scene": {"view": {"hlookat": "-69.643","vlookat" : "-11.366","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',417420,177,2,'{"scene": {"view": {"hlookat": "-19.808","vlookat" : "6.098","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,722,2,null,null,False,False,null),
(0,'thumb.jpg',417420,723,2,'{"scene": {"view": {"hlookat": "358.423","vlookat" : "-12.118","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',417420,4,2,null,null,False,False,null),
(0,'thumb.jpg',417420,101,2,'{"scene": {"view": {"hlookat": "323.365","vlookat" : "-0.011","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,102,2,'{"scene": {"view": {"hlookat": "-0.984","vlookat" : "23.444","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',417420,1005,2,null,null,False,False,null),
(0,'thumb.jpg',417420,1006,2,'{"scene": {"view": {"hlookat": "339.535","vlookat" : "16.953","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',417420,131,2,null,null,False,False,null),
(0,'thumb.jpg',417420,132,2,'{"scene": {"view": {"hlookat": "353.351","vlookat" : "1.590","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',417420,956,2,null,null,False,False,null),
(0,'thumb.jpg',417420,957,2,'{"scene": {"view": {"hlookat": "402.860","vlookat" : "-0.475","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,958,2,'{"scene": {"view": {"hlookat": "-19.514","vlookat" : "4.221","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,959,2,'{"scene": {"view": {"hlookat": "-27.922","vlookat" : "-1.132","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',417420,3,2,null,null,False,False,null),
(0,'thumb.jpg',417420,80,2,'{"scene": {"view": {"hlookat": "73.300","vlookat" : "6.725","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',417420,2,2,null,null,False,False,null),
(0,'thumb.jpg',417420,92,2,'{"scene": {"view": {"hlookat": "362.945","vlookat" : "2.608","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,93,2,'{"scene": {"view": {"hlookat": "131.508","vlookat" : "10.856","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',417420,186,2,'{"scene": {"view": {"hlookat": "363.417","vlookat" : "-3.174","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,187,2,'{"scene": {"view": {"hlookat": "22.459","vlookat" : "-2.953","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,746,2,null,null,False,False,null),
(0,'thumb.jpg',417420,747,2,'{"scene": {"view": {"hlookat": "21.618","vlookat" : "0.340","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,748,2,'{"scene": {"view": {"hlookat": "-78.982","vlookat" : "1.748","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,749,2,'{"scene": {"view": {"hlookat": "381.057","vlookat" : "-0.307","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,277,2,null,null,False,False,null),
(0,'thumb.jpg',417420,279,2,'{"scene": {"view": {"hlookat": "-2.952","vlookat" : "11.314","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,280,2,'{"scene": {"view": {"hlookat": "42.464","vlookat" : "0.820","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,730,2,null,null,False,False,null),
(0,'thumb.jpg',417420,731,2,'{"scene": {"view": {"hlookat": "-161.569","vlookat" : "-4.030","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,732,2,'{"scene": {"view": {"hlookat": "45.420","vlookat" : "-7.379","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,733,2,'{"scene": {"view": {"hlookat": "115.290","vlookat" : "-0.466","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,734,2,'{"scene": {"view": {"hlookat": "-200.771","vlookat" : "0.145","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,40,2,null,null,False,False,null),
(0,'thumb.jpg',417420,41,2,'{"scene": {"view": {"hlookat": "1.359","vlookat" : "13.873","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',417420,42,2,'{"scene": {"view": {"hlookat": "61.174","vlookat" : "17.447","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,43,2,'{"scene": {"view": {"hlookat": "181.074","vlookat" : "30.826","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,486,2,null,null,False,False,null),
(0,'thumb.jpg',417420,487,2,'{"scene": {"view": {"hlookat": "19.015","vlookat" : "11.974","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',417420,488,2,'{"scene": {"view": {"hlookat": "-31.155","vlookat" : "34.918","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,489,2,'{"scene": {"view": {"hlookat": "-1.149","vlookat" : "-4.921","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,1072,2,null,null,False,False,null),
(0,'thumb.jpg',417420,1073,2,'{"scene": {"view": {"hlookat": "197.506","vlookat" : "0.170","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',417420,1074,2,'{"scene": {"view": {"hlookat": "12.780","vlookat" : "15.075","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,1075,2,'{"scene": {"view": {"hlookat": "6.887","vlookat" : "1.148","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,1104,2,null,null,False,False,null),
(0,'thumb.jpg',417420,1105,2,'{"scene": {"view": {"hlookat": "0.006","vlookat" : "26.749","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,1106,2,'{"scene": {"view": {"hlookat": "-30.593","vlookat" : "13.566","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',417420,1107,2,'{"scene": {"view": {"hlookat": "-43.192","vlookat" : "18.329","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417420,1108,2,'{"scene": {"view": {"hlookat": "0.165","vlookat" : "11.969","fov" : "140"}}}',null,False,False,null);





DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 417420
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 417420;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 417420);

