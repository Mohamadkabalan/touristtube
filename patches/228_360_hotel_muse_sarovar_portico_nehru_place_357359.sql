DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 357359;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 357359;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 357359;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 357359;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',357359,79,1),
('1',357359,130,1),
('2',357359,193,2),
('3',357359,194,3),
('Lobby',357359,1,2),
('1',357359,15,1),
('2',357359,16,2),
('3',357359,17,3),
('Patio Restaurant',357359,28,3),
('1',357359,29,1),
('2',357359,30,2),
('Conference Hall - Lobby Level',357359,131,4),
('1',357359,132,1),
('Board Room - Basement',357359,293,5),
('1',357359,297,1),
('2',357359,298,2),
('Big Hall - Basement',357359,294,6),
('1',357359,303,1),
('2',357359,304,2),
('3',357359,305,3),
('Gym',357359,3,7),
('1',357359,80,1),
('Large Bedroom',357359,486,8),
('1',357359,487,1),
('2',357359,488,2),
('3',357359,489,3),
('4',357359,491,4),
('Twin Bedroom',357359,626,9),
('1',357359,627,1),
('2',357359,628,2),
('3',357359,629,3),
('4',357359,630,4);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 357359;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',357359,79,2,null,null,False,False,null),
(0,'thumb.jpg',357359,130,2,'{"scene": {"view": {"hlookat": "0.726","vlookat" : "0.000","fov" : "138.447"}}}',null,True,True,1),
(0,'thumb.jpg',357359,193,2,'{"scene": {"view": {"hlookat": "0.963","vlookat" : "0.000","fov" : "139.037"}}}',null,False,False,null),
(0,'thumb.jpg',357359,194,2,'{"scene": {"view": {"hlookat": "1.221","vlookat" : "0.000","fov" : "138.779"}}}',null,True,True,2),
(0,'thumb.jpg',357359,1,2,null,null,False,False,null),
(0,'thumb.jpg',357359,15,2,'{"scene": {"view": {"hlookat": "1.260","vlookat" : "0.000","fov" : "138.740"}}}',null,True,True,3),
(0,'thumb.jpg',357359,16,2,'{"scene": {"view": {"hlookat": "1.221","vlookat" : "0.000","fov" : "138.779"}}}',null,False,False,null),
(0,'thumb.jpg',357359,17,2,'{"scene": {"view": {"hlookat": "-176.462","vlookat" : "0.152","fov" : "139.037"}}}',null,True,True,4),
(0,'thumb.jpg',357359,28,2,null,null,False,False,null),
(0,'thumb.jpg',357359,29,2,'{"scene": {"view": {"hlookat": "3.384","vlookat" : "0.748","fov" : "138.931"}}}',null,True,True,5),
(0,'thumb.jpg',357359,30,2,'{"scene": {"view": {"hlookat": "2.852","vlookat" : "0.065","fov" : "138.967"}}}',null,True,True,6),
(0,'thumb.jpg',357359,131,2,null,null,False,False,null),
(0,'thumb.jpg',357359,132,2,'{"scene": {"view": {"hlookat": "3.672","vlookat" : "4.090","fov" : "138.357"}}}',null,True,True,7),
(0,'thumb.jpg',357359,293,2,null,null,False,False,null),
(0,'thumb.jpg',357359,297,2,'{"scene": {"view": {"hlookat": "1.788","vlookat" : "0.000","fov" : "138.93"}}}',null,True,True,8),
(0,'thumb.jpg',357359,298,2,'{"scene": {"view": {"hlookat": "1.424","vlookat" : "0.000","fov" : "138.576"}}}',null,False,False,null),
(0,'thumb.jpg',357359,294,2,null,null,False,False,null),
(0,'thumb.jpg',357359,303,2,'{"scene": {"view": {"hlookat": "1.862","vlookat" : "0.248","fov" : "139.295"}}}',null,True,True,9),
(0,'thumb.jpg',357359,304,2,'{"scene": {"view": {"hlookat": "1.337","vlookat" : "0.000","fov" : "139.324"}}}',null,False,False,null),
(0,'thumb.jpg',357359,305,2,'{"scene": {"view": {"hlookat": "1.033","vlookat" : "0.000","fov" : "138.967"}}}',null,True,True,10),
(0,'thumb.jpg',357359,3,2,null,null,False,False,null),
(0,'thumb.jpg',357359,80,2,'{"scene": {"view": {"hlookat": "1.646","vlookat" : "0.000","fov" : "138.35"}}}',null,True,True,11),
(0,'thumb.jpg',357359,486,2,null,null,False,False,null),
(0,'thumb.jpg',357359,487,2,'{"scene": {"view": {"hlookat": "3.586","vlookat" : "0.154","fov" : "138.219"}}}',null,True,True,12),
(0,'thumb.jpg',357359,488,2,'{"scene": {"view": {"hlookat": "1.033","vlookat" : "0.000","fov" : "138.967"}}}',null,False,False,null),
(0,'thumb.jpg',357359,489,2,'{"scene": {"view": {"hlookat": "1.182","vlookat" : "0.000","fov" : "138.818"}}}',null,False,False,13),
(0,'thumb.jpg',357359,491,2,'{"scene": {"view": {"hlookat": "1.157","vlookat" : "0.148","fov" : "139.171"}}}',null,False,False,14),
(0,'thumb.jpg',357359,626,2,null,null,False,False,null),
(0,'thumb.jpg',357359,627,2,'{"scene": {"view": {"hlookat": "1.424","vlookat" : "0.000","fov" : "138.576"}}}',null,True,True,15),
(0,'thumb.jpg',357359,628,2,'{"scene": {"view": {"hlookat": "1.553","vlookat" : "0.000","fov" : "138.447"}}}',null,False,False,16),
(0,'thumb.jpg',357359,629,2,'{"scene": {"view": {"hlookat": "1.598","vlookat" : "0.000","fov" : "138.402"}}}',null,False,False,null),
(0,'thumb.jpg',357359,630,2,'{"scene": {"view": {"hlookat": "0.999","vlookat" : "0.000","fov" : "139.002"}}}',null,True,True,17);





DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 357359
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 357359;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 357359);

