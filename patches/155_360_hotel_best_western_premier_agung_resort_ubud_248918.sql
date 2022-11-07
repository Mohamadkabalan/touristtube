DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 248918;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 248918;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 248918;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 248918;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',248918,79,1),
('1',248918,130,1),
('2',248918,193,2),
('Lobby',248918,1,2),
('1',248918,15,1),
('2',248918,16,2),
('3',248918,17,3),
('Taman Lotus Restaurant',248918,28,3),
('1',248918,29,1),
('2',248918,30,2),
('3',248918,31,3),
('4',248918,32,4),
('Taman Tirta - Pool Bar',248918,184,4),
('1',248918,185,1),
('Pool',248918,2,5),
('1',248918,21,1),
('2',248918,92,2),
('3',248918,186,3),
('Fitness Center',248918,3,6),
('1',248918,80,1),
('Taman Nirwana Spa',248918,4,7),
('1',248918,101,1),
('2',248918,102,2),
('Deluxe Room',248918,568,8),
('1',248918,569,1),
('2',248918,572,2),
('Deluxe Green View Room',248918,546,9),
('1',248918,547,1),
('2',248918,548,2),
('3',248918,550,3),
('Deluxe Pool View Room',248918,486,10),
('1',248918,492,1),
('2',248918,487,2),
('3',248918,488,3),
('Deluxe Executive Room',248918,480,11),
('1',248918,481,1),
('2',248918,482,2),
('3',248918,483,3),
('4',248918,484,4);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 248918;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',248918,79,2,null,null,False,null),
(0,'thumb.jpg',248918,130,2,'{"scene": {"view": {"hlookat": "2.977","vlookat" : "0.055","fov" : "138.576"}}}',True,True,1),
(0,'thumb.jpg',248918,193,2,'{"scene": {"view": {"hlookat": "6.332","vlookat" : "0.702","fov" : "138.123"}}}',null,False,null),
(0,'thumb.jpg',248918,1,2,null,null,False,null),
(0,'thumb.jpg',248918,15,2,'{"scene": {"view": {"hlookat": "0.744","vlookat" : "0.101","fov" : "138.266"}}}',True,True,2),
(0,'thumb.jpg',248918,16,2,'{"scene": {"view": {"hlookat": "1.926","vlookat" : "0.000","fov" : "138.074"}}}',null,False,null),
(0,'thumb.jpg',248918,17,2,'{"scene": {"view": {"hlookat": "-1.313","vlookat" : "1.818","fov" : "139.002"}}}',null,False,null),
(0,'thumb.jpg',248918,28,2,null,null,False,null),
(0,'thumb.jpg',248918,29,2,'{"scene": {"view": {"hlookat": "2.515","vlookat" : "0.196","fov" : "138.818"}}}',True,True,3),
(0,'thumb.jpg',248918,30,2,'{"scene": {"view": {"hlookat": "1.350","vlookat" : "0.620","fov" : "138.491"}}}',True,True,4),
(0,'thumb.jpg',248918,31,2,'{"scene": {"view": {"hlookat": "0.003","vlookat" : "0.093","fov" : "138.025"}}}',null,False,null),
(0,'thumb.jpg',248918,32,2,'{"scene": {"view": {"hlookat": "175.852","vlookat" : "1.355","fov" : "138.576"}}}',null,False,null),
(0,'thumb.jpg',248918,184,2,null,null,False,null),
(0,'thumb.jpg',248918,185,2,'{"scene": {"view": {"hlookat": "1.177","vlookat" : "0.183","fov" : "138.659"}}}',True,True,5),
(0,'thumb.jpg',248918,2,2,null,null,False,null),
(0,'thumb.jpg',248918,21,2,'{"scene": {"view": {"hlookat": "1.037","vlookat" : "0.187","fov" : "137.821"}}}',True,True,6),
(0,'thumb.jpg',248918,92,2,'{"scene": {"view": {"hlookat": "78.293","vlookat" : "0.107","fov" : "138.402"}}}',True,True,7),
(0,'thumb.jpg',248918,186,2,'{"scene": {"view": {"hlookat": "2.127","vlookat" : "0.000","fov" : "137.873"}}}',null,False,null),
(0,'thumb.jpg',248918,3,2,null,null,False,null),
(0,'thumb.jpg',248918,80,2,'{"scene": {"view": {"hlookat": "-0.427","vlookat" : "1.772","fov" : "138.312"}}}',True,True,8),
(0,'thumb.jpg',248918,4,2,null,null,False,null),
(0,'thumb.jpg',248918,101,2,'{"scene": {"view": {"hlookat": "0.928","vlookat" : "0.000","fov" : "139.072"}}}',True,True,9),
(0,'thumb.jpg',248918,102,2,'{"scene": {"view": {"hlookat": "1.553","vlookat" : "0.000","fov" : "138.447"}}}',True,True,10),
(0,'thumb.jpg',248918,568,2,null,null,False,null),
(0,'thumb.jpg',248918,569,2,'{"scene": {"view": {"hlookat": "-29.942","vlookat" : "2.403","fov" : "138.266"}}}',True,True,11),
(0,'thumb.jpg',248918,572,2,'{"scene": {"view": {"hlookat": "-81.552","vlookat" : "1.813","fov" : "138.740"}}}',null,False,null),
(0,'thumb.jpg',248918,546,2,null,null,False,null),
(0,'thumb.jpg',248918,547,2,'{"scene": {"view": {"hlookat": "1.928","vlookat" : "0.000","fov" : "138.074"}}}',True,True,12),
(0,'thumb.jpg',248918,548,2,'{"scene": {"view": {"hlookat": "-26.259","vlookat" : "0.614","fov" : "137.715"}}}',null,False,null),
(0,'thumb.jpg',248918,550,2,'{"scene": {"view": {"hlookat": "-62.418","vlookat" : "1.661","fov" : "138.402"}}}',null,False,null),
(0,'thumb.jpg',248918,486,2,null,null,False,null),
(0,'thumb.jpg',248918,492,2,'{"scene": {"view": {"hlookat": "1.341","vlookat" : "0.252","fov" : "138.659"}}}',null,False,null),
(0,'thumb.jpg',248918,487,2,'{"scene": {"view": {"hlookat": "6.381","vlookat" : "0.095","fov" : "138.074"}}}',True,True,13),
(0,'thumb.jpg',248918,488,2,'{"scene": {"view": {"hlookat": "159.392","vlookat" : "3.349","fov" : "138.856"}}}',null,False,null),
(0,'thumb.jpg',248918,480,2,null,null,False,null),
(0,'thumb.jpg',248918,481,2,'{"scene": {"view": {"hlookat": "223.621","vlookat" : "2.195","fov" : "138.447"}}}',null,False,null),
(0,'thumb.jpg',248918,482,2,'{"scene": {"view": {"hlookat": "-6.580","vlookat" : "0.326","fov" : "138.659"}}}',True,True,14),
(0,'thumb.jpg',248918,483,2,'{"scene": {"view": {"hlookat": "2.025","vlookat" : "0.000","fov" : "137.975"}}}',null,False,null),
(0,'thumb.jpg',248918,484,2,'{"scene": {"view": {"hlookat": "2.179","vlookat" : "0.000","fov" : "137.821"}}}',null,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 248918
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 248918;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 248918);

