INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',18003,556,1),
('-',18003,557,1),
('Lobby',18003,1,2),
('-',18003,15,1),
('Breakfast Room',18003,6,3),
('View 1',18003,25,1),
('View 2',18003,26,2),
('Single Room',18003,530,4),
('-',18003,531,1),
('Double Room',18003,427,5),
('View 1',18003,428,1),
('View 2',18003,429,2),
('Bathroom',18003,430,3),
('Twin Room',18003,246,6),
('View 1',18003,247,1),
('Bathroom',18003,251,2);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 18003;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 18003;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 18003;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',18003,556,2,null,null,False,False),
(0,'thumb.jpg',18003,557,2,'{"scene": {"view": {"hlookat": "12.205","vlookat" : "-9.315"}}}',null,True,True),
(0,'thumb.jpg',18003,1,2,null,null,False,False),
(0,'thumb.jpg',18003,15,2,'{"scene": {"view": {"hlookat": "43.6","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',18003,6,2,null,null,False,False),
(0,'thumb.jpg',18003,25,2,'{"scene": {"view": {"hlookat": "-154.52","vlookat" : "14.158"}}}',null,True,True),
(0,'thumb.jpg',18003,26,2,'{"scene": {"view": {"hlookat": "-182.388","vlookat" : "15.557"}}}',null,True,True),
(0,'thumb.jpg',18003,530,2,null,null,True,True),
(0,'thumb.jpg',18003,531,2,'{"scene": {"view": {"hlookat": "164.887","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',18003,427,2,null,null,False,False),
(0,'thumb.jpg',18003,428,2,'{"scene": {"view": {"hlookat": "17.932","vlookat" : "97.334"}}}',null,True,True),
(0,'thumb.jpg',18003,429,2,'{"scene": {"view": {"hlookat": "111.376","vlookat" : "32.865"}}}',null,True,True),
(0,'thumb.jpg',18003,430,2,'{"scene": {"view": {"hlookat": "-43.285","vlookat" : "17.839"}}}',null,True,True),
(0,'thumb.jpg',18003,246,2,null,null,False,False),
(0,'thumb.jpg',18003,247,2,'{"scene": {"view": {"hlookat": "174.218","vlookat" : "11.881"}}}',null,True,True),
(0,'thumb.jpg',18003,251,2,'{"scene": {"view": {"hlookat": "-21.064","vlookat" : "18.028"}}}',null,True,True);


DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 18003 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 18003;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 18003);


