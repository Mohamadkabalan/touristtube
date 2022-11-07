DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 20961;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20961;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 20961;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 20961;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',20961,79,1),
('View 1',20961,130,1),
('Lobby',20961,1,2),
('View 1',20961,15,1),
('View 2',20961,16,2),
('Breakfast',20961,6,3),
('View 1',20961,25,1),
('View 2',20961,26,2),
('Double Standard Room',20961,427,4),
('-',20961,428,1),
('Bathroom',20961,429,2),
('Twin Superior Room',20961,246,5),
('View 1',20961,247,1),
('View 2',20961,248,2),
('Bathroom',20961,249,3);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20961;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',20961,79,2,null,null,False,False,null),
(0,'thumb.jpg',20961,130,2,'{"scene": {"view": {"hlookat": "-54.720","vlookat" : "-22.904","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',20961,1,2,null,null,False,False,null),
(0,'thumb.jpg',20961,15,2,'{"scene": {"view": {"hlookat": "339.810","vlookat" : "-1.038","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',20961,16,2,'{"scene": {"view": {"hlookat": "18.183","vlookat" : "29.889","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',20961,6,2,null,null,False,False,null),
(0,'thumb.jpg',20961,25,2,'{"scene": {"view": {"hlookat": "-3.778","vlookat" : "0.533","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',20961,26,2,'{"scene": {"view": {"hlookat": "-126.884","vlookat" : "18.313","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',20961,427,2,null,null,False,False,null),
(0,'thumb.jpg',20961,428,2,'{"scene": {"view": {"hlookat": "-12.723","vlookat" : "-0.406","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',20961,429,2,'{"scene": {"view": {"hlookat": "17.029","vlookat" : "12.122","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',20961,246,2,null,null,False,False,null),
(0,'thumb.jpg',20961,247,2,'{"scene": {"view": {"hlookat": "29.620","vlookat" : "20.450","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',20961,248,2,'{"scene": {"view": {"hlookat": "-0.735","vlookat" : "37.117","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',20961,249,2,'{"scene": {"view": {"hlookat": "140.167","vlookat" : "27.202","fov" : "140"}}}',null,True,True,10);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 20961
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20961;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20961);
