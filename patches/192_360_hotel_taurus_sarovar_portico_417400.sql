DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 417400;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 417400;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417400;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417400;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance ',417400,79,1),
('-',417400,130,1),
('Lobby - Reception ',417400,1,2),
('1',417400,15,1),
('2',417400,16,2),
('Reception First floor',417400,338,3),
('-',417400,339,1),
('Alfa 63 - Coffee Shop',417400,6,4),
('1',417400,25,1),
('2',417400,26,2),
('3',417400,27,3),
('4',417400,602,4),
('5',417400,603,5),
('Board Room',417400,131,5),
('-',417400,132,1),
('Aries Conference Room',417400,293,6),
('1',417400,297,1),
('2',417400,298,2),
('Standard Room',417400,36,7),
('1',417400,37,1),
('2',417400,38,2),
('Bathroom',417400,245,3),
('Executive suite',417400,10,8),
('1',417400,47,1),
('2',417400,49,2),
('3',417400,51,3),
('Taurus Suite',417400,159,9),
('1',417400,160,1),
('2',417400,161,2),
('3',417400,164,3),
('Bathroom',417400,162,4);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 417400;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',417400,79,2,null,null,False,False,null),
(0,'thumb.jpg',417400,130,2,'{"scene": {"view": {"hlookat": "346.471","vlookat" : "-1.030","fov" : "120"}}}',null,True,True,1),
(0,'thumb.jpg',417400,1,2,null,null,False,False,null),
(0,'thumb.jpg',417400,15,2,'{"scene": {"view": {"hlookat": "901.554","vlookat" : "-7.924","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',417400,16,2,'{"scene": {"view": {"hlookat": "268.801","vlookat" : "3.099","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',417400,338,2,null,null,False,False,null),
(0,'thumb.jpg',417400,339,2,'{"scene": {"view": {"hlookat": "356.631","vlookat" : "4.766","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',417400,6,2,null,null,False,False,null),
(0,'thumb.jpg',417400,25,2,'{"scene": {"view": {"hlookat": "-0.501","vlookat" : "7.312","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',417400,26,2,'{"scene": {"view": {"hlookat": "219.323","vlookat" : "2.630","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',417400,27,2,'{"scene": {"view": {"hlookat": "-4.134","vlookat" : "-3.286","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',417400,602,2,'{"scene": {"view": {"hlookat": "182.434","vlookat" : "14.943","fov" : "132.195"}}}',null,True,True,8),
(0,'thumb.jpg',417400,603,2,'{"scene": {"view": {"hlookat": "-23.986","vlookat" : "4.044","fov" : "129.102"}}}',null,False,False,null),
(0,'thumb.jpg',417400,131,2,null,null,False,False,null),
(0,'thumb.jpg',417400,132,2,'{"scene": {"view": {"hlookat": "359.648","vlookat" : "21.454","fov" : "123.534"}}}',null,False,False,null),
(0,'thumb.jpg',417400,293,2,null,null,False,False,null),
(0,'thumb.jpg',417400,297,2,'{"scene": {"view": {"hlookat": "715.672","vlookat" : "23.067","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',417400,298,2,'{"scene": {"view": {"hlookat": "174.649","vlookat" : "16.624","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417400,36,2,null,null,False,False,null),
(0,'thumb.jpg',417400,37,2,'{"scene": {"view": {"hlookat": "-98.898","vlookat" : "2.801","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',417400,38,2,'{"scene": {"view": {"hlookat": "0.984","vlookat" : "17.373","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',417400,245,2,'{"scene": {"view": {"hlookat": "4.587","vlookat" : "10.853","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',417400,10,2,null,null,False,False,null),
(0,'thumb.jpg',417400,47,2,'{"scene": {"view": {"hlookat": "359.982","vlookat" : "30.507","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',417400,49,2,'{"scene": {"view": {"hlookat": "55.933","vlookat" : "4.277","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',417400,51,2,'{"scene": {"view": {"hlookat": "90.541","vlookat" : "10.894","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',417400,159,2,null,null,False,False,null),
(0,'thumb.jpg',417400,160,2,'{"scene": {"view": {"hlookat": "-17.448","vlookat" : "10.155","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',417400,161,2,'{"scene": {"view": {"hlookat": "-89.045","vlookat" : "8.238","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',417400,164,2,'{"scene": {"view": {"hlookat": "0.656","vlookat" : "30.157","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',417400,162,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "11.796","fov" : "140"}}}',null,False,False,null);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 417400
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 417400;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 417400);

