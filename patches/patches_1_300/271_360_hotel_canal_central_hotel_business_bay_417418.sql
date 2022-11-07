DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 417418;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 417418;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417418;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 417418;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',417418,79,1),
('1',417418,130,1),
('2',417418,193,2),
('3',417418,194,3),
('Lobby',417418,1,2),
('Reception',417418,15,1),
('1',417418,16,2),
('2',417418,17,3),
('3',417418,18,4),
("Barista's corner",417418,8,3),
('-',417418,33,1),
('La cruise',417418,28,4),
('1',417418,29,1),
('2',417418,30,2),
('3',417418,31,3),
('4',417418,32,4),
('5',417418,110,5),
('6',417418,111,6),
('Lilly\'s social house',417418,167,5),
('Lilly\'s Backyard',417418,168,1),
('1',417418,169,2),
('2',417418,170,3),
('Starlocks ladies salon',417418,256,6),
('-',417418,257,1),
('Meeting rooms',417418,131,7),
('Meeting Room - A',417418,132,1),
('Meeting Room - B',417418,252,2),
('Gym',417418,3,8),
('-',417418,80,1),
('Wellness valley spa',417418,4,9),
('1',417418,101,1),
('2',417418,102,2),
('Pool',417418,2,10),
('1',417418,21,1),
('2',417418,92,2),
('3',417418,93,3),
('Superior room - city view',417418,486,14),
('Superior Room - City View - Bedroom',417418,487,1),
('Superior Room - City View - Bathroom',417418,491,2),
('Premium room - burj khalifah view',417418,480,13),
('Premium Room - Burj Khalifah View - Bedroom -1',417418,481,1),
('Premium Room - Burj Khalifah View - Bedroom - 2',417418,482,2),
('Premium Room - Burj Khalifah View - Bathroom',417418,484,3),
('Delux room - bay view',417418,568,12),
('Delux Room - Bay View - Bedroom',417418,569,1),
('Delux Room - Bay View - Bathroom',417418,572,2),
('Bay one bedroom suite',417418,159,11),
('Bedroom - 1',417418,164,1),
('Bedroom - 2',417418,262,2),
('Living - Bedroom',417418,160,3),
('Suite - Bathroom',417418,162,4),
('Suite - Guest Bathroom',417418,163,5);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 417418;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',417418,79,2,null,null,False,False,1),
(0,'thumb.jpg',417418,130,2,'{"scene": {"view": {"hlookat": "-11.892","vlookat" : "-38.612","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,193,2,'{"scene": {"view": {"hlookat": "160.988","vlookat" : "-1.358","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',417418,194,2,'{"scene": {"view": {"hlookat": "190.868","vlookat" : "-26.550","fov" : "140"}}}',null,False,False,3),
(0,'thumb.jpg',417418,1,2,null,null,False,False,2),
(0,'thumb.jpg',417418,15,2,'{"scene": {"view": {"hlookat": "-11.840","vlookat" : "-8.799","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,16,2,'{"scene": {"view": {"hlookat": "158.202","vlookat" : "1.703","fov" : "140"}}}',null,False,False,2),
(0,'thumb.jpg',417418,17,2,'{"scene": {"view": {"hlookat": "157.678","vlookat" : "4.075","fov" : "140"}}}',null,False,False,3),
(0,'thumb.jpg',417418,18,2,'{"scene": {"view": {"hlookat": "173.263","vlookat" : "-0.905","fov" : "140"}}}',null,False,False,4),
(0,'thumb.jpg',417418,8,2,null,null,False,False,3),
(0,'thumb.jpg',417418,33,2,'{"scene": {"view": {"hlookat": "-115.717","vlookat" : "8.104","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,28,2,null,null,False,False,4),
(0,'thumb.jpg',417418,29,2,'{"scene": {"view": {"hlookat": "-5.781","vlookat" : "-5.612","fov" : "140"}}}',null,False,False,1),
(0,'thumb.jpg',417418,30,2,'{"scene": {"view": {"hlookat": "46.371","vlookat" : "-2.788","fov" : "140"}}}',null,False,False,2),
(0,'thumb.jpg',417418,31,2,'{"scene": {"view": {"hlookat": "-399.036","vlookat" : "-4.262","fov" : "140"}}}',null,False,False,3),
(0,'thumb.jpg',417418,32,2,'{"scene": {"view": {"hlookat": "-32.636","vlookat" : "4.105","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',417418,110,2,'{"scene": {"view": {"hlookat": "165.407","vlookat" : "1.572","fov" : "140"}}}',null,False,False,5),
(0,'thumb.jpg',417418,111,2,'{"scene": {"view": {"hlookat": "70.017","vlookat" : "-2.920","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',417418,167,2,null,null,False,False,5),
(0,'thumb.jpg',417418,168,2,'{"scene": {"view": {"hlookat": "31.180","vlookat" : "-4.268","fov" : "140"}}}',null,False,False,1),
(0,'thumb.jpg',417418,169,2,'{"scene": {"view": {"hlookat": "20.686","vlookat" : "7.822","fov" : "140"}}}',null,False,False,2),
(0,'thumb.jpg',417418,170,2,'{"scene": {"view": {"hlookat": "-133.605","vlookat" : "3.751","fov" : "140"}}}',null,False,False,3),
(0,'thumb.jpg',417418,256,2,null,null,False,False,6),
(0,'thumb.jpg',417418,257,2,'{"scene": {"view": {"hlookat": "-122.520","vlookat" : "3.370","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,131,2,null,null,False,False,7),
(0,'thumb.jpg',417418,132,2,'{"scene": {"view": {"hlookat": "-18.790","vlookat" : "4.853","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,252,2,'{"scene": {"view": {"hlookat": "161.407","vlookat" : "3.729","fov" : "140"}}}',null,False,False,2),
(0,'thumb.jpg',417418,3,2,null,null,False,False,8),
(0,'thumb.jpg',417418,80,2,'{"scene": {"view": {"hlookat": "152.002","vlookat" : "2.352","fov" : "140"}}}',null,False,False,1),
(0,'thumb.jpg',417418,4,2,null,null,False,False,9),
(0,'thumb.jpg',417418,101,2,'{"scene": {"view": {"hlookat": "-10.291","vlookat" : "11.929","fov" : "140"}}}',null,False,False,1),
(0,'thumb.jpg',417418,102,2,'{"scene": {"view": {"hlookat": "-42.485","vlookat" : "10.019","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',417418,2,2,null,null,False,False,10),
(0,'thumb.jpg',417418,21,2,'{"scene": {"view": {"hlookat": "149.687","vlookat" : "-4.173","fov" : "140"}}}',null,False,False,1),
(0,'thumb.jpg',417418,92,2,'{"scene": {"view": {"hlookat": "144.474","vlookat" : "-2.321","fov" : "140"}}}',null,False,False,2),
(0,'thumb.jpg',417418,93,2,'{"scene": {"view": {"hlookat": "103.637","vlookat" : "4.503","fov" : "140"}}}',null,False,False,3),
(0,'thumb.jpg',417418,486,2,null,null,False,False,14),
(0,'thumb.jpg',417418,487,2,'{"scene": {"view": {"hlookat": "28.856","vlookat" : "8.507","fov" : "140"}}}',null,False,True,1),
(0,'thumb.jpg',417418,491,2,'{"scene": {"view": {"hlookat": "-44.163","vlookat" : "2.035","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',417418,480,2,null,null,False,False,13),
(0,'thumb.jpg',417418,481,2,'{"scene": {"view": {"hlookat": "196.672","vlookat" : "17.600","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,482,2,'{"scene": {"view": {"hlookat": "391.185","vlookat" : "22.291","fov" : "140"}}}',null,False,False,2),
(0,'thumb.jpg',417418,484,2,'{"scene": {"view": {"hlookat": "55.030","vlookat" : "9.178","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',417418,568,2,null,null,False,False,12),
(0,'thumb.jpg',417418,569,2,'{"scene": {"view": {"hlookat": "45.663","vlookat" : "17.008","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,572,2,'{"scene": {"view": {"hlookat": "66.635","vlookat" : "4.287","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',417418,159,2,null,null,False,False,11),
(0,'thumb.jpg',417418,164,2,'{"scene": {"view": {"hlookat": "30.769","vlookat" : "19.693","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',417418,262,2,'{"scene": {"view": {"hlookat": "156.942","vlookat" : "12.531","fov" : "140"}}}',null,False,False,2),
(0,'thumb.jpg',417418,160,2,'{"scene": {"view": {"hlookat": "162.488","vlookat" : "17.840","fov" : "140"}}}',null,False,False,3),
(0,'thumb.jpg',417418,162,2,'{"scene": {"view": {"hlookat": "141.266","vlookat" : "12.597","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',417418,163,2,'{"scene": {"view": {"hlookat": "206.641","vlookat" : "15.529","fov" : "140"}}}',null,False,False,5);



DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 417418
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 417418;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 417418);

