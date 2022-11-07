DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 105620;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 105620;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 105620;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 105620;



INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street view',105620,556,1),
('1',105620,557,1),
('2',105620,558,2),
('Lobby',105620,1,2),
('-',105620,15,1),
('Reception',105620,16,2),
('The Deck Restaurant',105620,28,3),
('1',105620,29,1),
('2',105620,30,2),
('3',105620,31,3),
('4',105620,32,4),
('Rooftop',105620,167,4),
('1',105620,168,1),
('2',105620,169,2),
('3',105620,170,3),
('Sky pool',105620,2,5),
('1',105620,21,1),
('2',105620,92,2),
('Sky deck',105620,94,6),
('1',105620,95,1),
('2',105620,96,2),
('3',105620,97,3),
('Romeo pool',105620,992,7),
('-',105620,993,1),
('Spa',105620,4,8),
('1',105620,101,1),
('2',105620,102,2),
('3',105620,103,3),
('Legian meeting room',105620,131,9),
('1',105620,132,2),
('2',105620,252,2),
('Superior room',105620,486,10),
('-',105620,487,1),
('Bathroom',105620,491,2),
('Deluxe room',105620,568,11),
('1',105620,569,1),
('2',105620,570,2),
('Bathroom',105620,572,3),
('Deluxe pool access',105620,480,12),
('1',105620,481,1),
('2',105620,482,2),
('Bathroom',105620,484,3),
('Deluxe interconnecting room',105620,578,13),
('Entrance',105620,579,1),
('1',105620,580,2),
('1 - bathroom',105620,581,3),
('2- bathroom',105620,582,4),
('Deluxe suite',105620,605,14),
('1',105620,606,1),
('2',105620,607,2),
('3',105620,663,3),
('Bathroom - 1',105620,608,4),
('Bathroom - 2',105620,609,5);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 105620;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',105620,556,2,null,null,False,False,null),
(0,'thumb.jpg',105620,557,2,'{"scene": {"view": {"hlookat": "-4.755","vlookat" : "-17.545","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,558,2,'{"scene": {"view": {"hlookat": "27.712","vlookat" : "-13.295","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',105620,1,2,null,null,False,False,null),
(0,'thumb.jpg',105620,15,2,'{"scene": {"view": {"hlookat": "169.132","vlookat" : "1.019","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',105620,16,2,'{"scene": {"view": {"hlookat": "2.460","vlookat" : "6.231","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,28,2,null,null,False,False,null),
(0,'thumb.jpg',105620,29,2,'{"scene": {"view": {"hlookat": "150.453","vlookat" : "1.188","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,30,2,'{"scene": {"view": {"hlookat": "2.788","vlookat" : "-6.067","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,31,2,'{"scene": {"view": {"hlookat": "-10.515","vlookat" : "-1.174","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',105620,32,2,'{"scene": {"view": {"hlookat": "1.312","vlookat" : "29.679","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,167,2,null,null,False,False,null),
(0,'thumb.jpg',105620,168,2,'{"scene": {"view": {"hlookat": "19.349","vlookat" : "1.312","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,169,2,'{"scene": {"view": {"hlookat": "3.608","vlookat" : "14.921","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',105620,170,2,'{"scene": {"view": {"hlookat": "209.540","vlookat" : "-1.617","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,2,2,null,null,False,False,null),
(0,'thumb.jpg',105620,21,2,'{"scene": {"view": {"hlookat": "-1.770","vlookat" : "8.882","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',105620,92,2,'{"scene": {"view": {"hlookat": "160.896","vlookat" : "-0.069","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,94,2,null,null,False,False,null),
(0,'thumb.jpg',105620,95,2,'{"scene": {"view": {"hlookat": "222.100","vlookat" : "-1.896","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',105620,96,2,'{"scene": {"view": {"hlookat": "90.165","vlookat" : "-1.806","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,97,2,'{"scene": {"view": {"hlookat": "27.032","vlookat" : "10.812","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,992,2,null,null,False,False,null),
(0,'thumb.jpg',105620,993,2,'{"scene": {"view": {"hlookat": "-0.193","vlookat" : "-6.395","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',105620,4,2,null,null,False,False,null),
(0,'thumb.jpg',105620,101,2,'{"scene": {"view": {"hlookat": "-128.777","vlookat" : "-0.873","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,102,2,'{"scene": {"view": {"hlookat": "-9.508","vlookat" : "22.301","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',105620,103,2,'{"scene": {"view": {"hlookat": "-179.763","vlookat" : "11.236","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,131,2,null,null,False,False,null),
(0,'thumb.jpg',105620,132,2,'{"scene": {"view": {"hlookat": "182.720","vlookat" : "19.535","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',105620,252,2,'{"scene": {"view": {"hlookat": "1.148","vlookat" : "10.986","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,486,2,null,null,False,False,null),
(0,'thumb.jpg',105620,487,2,'{"scene": {"view": {"hlookat": "-168.441","vlookat" : "32.631","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',105620,491,2,'{"scene": {"view": {"hlookat": "13.609","vlookat" : "9.013","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,568,2,null,null,False,False,null),
(0,'thumb.jpg',105620,569,2,'{"scene": {"view": {"hlookat": "18.693","vlookat" : "18.364","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',105620,570,2,'{"scene": {"view": {"hlookat": "21.899","vlookat" : "5.191","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,572,2,'{"scene": {"view": {"hlookat": "14.323","vlookat" : "-1.979","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,480,2,null,null,False,False,null),
(0,'thumb.jpg',105620,481,2,'{"scene": {"view": {"hlookat": "192.379","vlookat" : "7.958","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',105620,482,2,'{"scene": {"view": {"hlookat": "-43.611","vlookat" : "0.983","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,484,2,'{"scene": {"view": {"hlookat": "16.231","vlookat" : "3.111","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,578,2,null,null,False,False,null),
(0,'thumb.jpg',105620,579,2,'{"scene": {"view": {"hlookat": "-92.597","vlookat" : "5.123","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',105620,580,2,'{"scene": {"view": {"hlookat": "-8.691","vlookat" : "21.316","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,581,2,'{"scene": {"view": {"hlookat": "-392.359","vlookat" : "8.916","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,582,2,'{"scene": {"view": {"hlookat": "11.835","vlookat" : "-0.175","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,605,2,null,null,False,False,null),
(0,'thumb.jpg',105620,606,2,'{"scene": {"view": {"hlookat": "20.660","vlookat" : "-1.148","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,607,2,'{"scene": {"view": {"hlookat": "-20.983","vlookat" : "-0.329","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',105620,663,2,'{"scene": {"view": {"hlookat": "-3.608","vlookat" : "26.890","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,608,2,'{"scene": {"view": {"hlookat": "-27.369","vlookat" : "-0.169","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',105620,609,2,'{"scene": {"view": {"hlookat": "-129.301","vlookat" : "6.745","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 105620
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 105620;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 105620);

