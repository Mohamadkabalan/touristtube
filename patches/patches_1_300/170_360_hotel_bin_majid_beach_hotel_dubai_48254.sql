DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 48254;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 48254;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 48254;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 48254;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',48254,79,1),
('-',48254,130,1),
('Lobby',48254,1,2),
('1',48254,15,1),
('2',48254,16,2),
('3',48254,17,3),
('Al Rahala Restaurant',48254,28,3),
('1',48254,29,1),
('2',48254,30,2),
('Arcadia Café',48254,8,4),
('1',48254,33,1),
('2',48254,34,2),
('Pebbles Sports',48254,222,5),
('1',48254,223,1),
('2',48254,224,2),
('Beach Area',48254,722,6),
('1',48254,723,1),
('2',48254,724,2),
('Swimming Pool Area and Aqua Pool Bar',48254,2,7),
('1',48254,21,1),
('2',48254,92,2),
('Suite',48254,159,8),
('1',48254,160,1),
('2',48254,163,2),
('3',48254,164,3),
('4',48254,162,4),
('Twin Room',48254,246,9),
('1',48254,247,1),
('2',48254,248,2),
('3',48254,251,3),
('Queen Room',48254,530,10),
('1',48254,531,1),
('2',48254,532,2),
('3',48254,535,3);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 48254;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',48254,79,2,null,null,False,False,null),
(0,'thumb.jpg',48254,130,2,'{"scene": {"view": {"hlookat": "0.542","vlookat" : "0.000","fov" : "139.462"}}}',null,True,True,1),
(0,'thumb.jpg',48254,1,2,null,null,False,False,null),
(0,'thumb.jpg',48254,15,2,'{"scene": {"view": {"hlookat": "-0.11","vlookat" : "0.065","fov" : "138.967"}}}',null,True,True,2),
(0,'thumb.jpg',48254,16,2,'{"scene": {"view": {"hlookat": "1.106","vlookat" : "0.000","fov" : "138.894"}}}',null,False,False,null),
(0,'thumb.jpg',48254,17,2,'{"scene": {"view": {"hlookat": "0.829","vlookat" : "0.000","fov" : "139.171"}}}',null,True,True,3),
(0,'thumb.jpg',48254,28,2,null,null,False,False,null),
(0,'thumb.jpg',48254,29,2,'{"scene": {"view": {"hlookat": "2.047","vlookat" : "0.000","fov" : "138.618"}}}',null,True,True,4),
(0,'thumb.jpg',48254,30,2,'{"scene": {"view": {"hlookat": "0.591","vlookat" : "0.000","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',48254,8,2,null,null,False,False,null),
(0,'thumb.jpg',48254,33,2,'{"scene": {"view": {"hlookat": "0.928","vlookat" : "0.000","fov" : "139.072"}}}',null,True,True,5),
(0,'thumb.jpg',48254,34,2,'{"scene": {"view": {"hlookat": "0.829","vlookat" : "0.000","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',48254,222,2,null,null,False,False,null),
(0,'thumb.jpg',48254,223,2,'{"scene": {"view": {"hlookat": "4.435","vlookat" : "0.093","fov" : "138.025"}}}',null,True,True,6),
(0,'thumb.jpg',48254,224,2,'{"scene": {"view": {"hlookat": "2.204","vlookat" : "0.369","fov" : "139.436"}}}',null,False,False,null),
(0,'thumb.jpg',48254,722,2,null,null,False,False,null),
(0,'thumb.jpg',48254,723,2,'{"scene": {"view": {"hlookat": "1.959","vlookat" : "0.000","fov" : "139.381"}}}',null,True,True,7),
(0,'thumb.jpg',48254,724,2,'{"scene": {"view": {"hlookat": "0.894","vlookat" : "0.000","fov" : "139.106"}}}',null,True,True,8),
(0,'thumb.jpg',48254,2,2,null,null,False,False,null),
(0,'thumb.jpg',48254,21,2,'{"scene": {"view": {"hlookat": "1.608","vlookat" : "0.000","fov" : "139.234"}}}',null,True,True,9),
(0,'thumb.jpg',48254,92,2,'{"scene": {"view": {"hlookat": "3.821","vlookat" : "-0.077","fov" : "139.29"}}}',null,True,True,10),
(0,'thumb.jpg',48254,159,2,null,null,False,False,null),
(0,'thumb.jpg',48254,160,2,'{"scene": {"view": {"hlookat": "0.894","vlookat" : "0.000","fov" : "139.106"}}}',null,True,True,11),
(0,'thumb.jpg',48254,163,2,'{"scene": {"view": {"hlookat": "3.242","vlookat" : "2.333","fov" : "139.381"}}}',null,False,False,null),
(0,'thumb.jpg',48254,164,2,'{"scene": {"view": {"hlookat": "0.963","vlookat" : "0.000","fov" : "139.03"}}}',null,True,True,12),
(0,'thumb.jpg',48254,162,2,'{"scene": {"view": {"hlookat": "5.937","vlookat" : "1.448","fov" : "138.49"}}}',null,True,True,13),
(0,'thumb.jpg',48254,246,2,null,null,False,False,null),
(0,'thumb.jpg',48254,247,2,'{"scene": {"view": {"hlookat": "2.332","vlookat" : "0.051","fov" : "138.818"}}}',null,True,True,14),
(0,'thumb.jpg',48254,248,2,'{"scene": {"view": {"hlookat": "3.748","vlookat" : "1.097","fov" : "139.203"}}}',null,False,False,null),
(0,'thumb.jpg',48254,251,2,'{"scene": {"view": {"hlookat": "0.861","vlookat" : "0.000","fov" : "139.139"}}}',null,True,True,15),
(0,'thumb.jpg',48254,530,2,null,null,False,False,null),
(0,'thumb.jpg',48254,531,2,'{"scene": {"view": {"hlookat": "3.313","vlookat" : "0.317","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',48254,532,2,'{"scene": {"view": {"hlookat": "-0.218","vlookat" : "1.115","fov" : "139.234"}}}',null,True,True,16),
(0,'thumb.jpg',48254,535,2,'{"scene": {"view": {"hlookat": "1.182","vlookat" : "0.000","fov" : "138.818"}}}',null,True,True,17);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 48254
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 48254;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 48254);

