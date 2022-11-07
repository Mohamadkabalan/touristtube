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


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20961;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20961;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 20961;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',20961,79,2,null,null,False,False),
(0,'thumb.jpg',20961,130,2,'{"scene": {"view": {"hlookat": "-29.577","vlookat" : "-23.026"}}}',null,True,True),
(0,'thumb.jpg',20961,1,2,null,null,False,False),
(0,'thumb.jpg',20961,15,2,'{"scene": {"view": {"hlookat": "-18.474","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',20961,16,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',20961,6,2,null,null,False,False),
(0,'thumb.jpg',20961,25,2,'{"scene": {"view": {"hlookat": "-194.766","vlookat" : "17.515"}}}',null,True,True),
(0,'thumb.jpg',20961,26,2,'{"scene": {"view": {"hlookat": "-188.104","vlookat" : "4.482"}}}',null,True,True),
(0,'thumb.jpg',20961,427,2,null,null,False,False),
(0,'thumb.jpg',20961,428,2,'{"scene": {"view": {"hlookat": "-26.628","vlookat" : "0.109"}}}',null,True,True),
(0,'thumb.jpg',20961,429,2,'{"scene": {"view": {"hlookat": "26.409","vlookat" : "25.143"}}}',null,True,True),
(0,'thumb.jpg',20961,246,2,null,null,False,False),
(0,'thumb.jpg',20961,247,2,'{"scene": {"view": {"hlookat": "16.061","vlookat" : "9.404"}}}',null,True,True),
(0,'thumb.jpg',20961,248,2,'{"scene": {"view": {"hlookat": "-386.997","vlookat" : "22.921"}}}',null,True,True),
(0,'thumb.jpg',20961,249,2,'{"scene": {"view": {"hlookat": "153.187","vlookat" : "22.434"}}}',null,True,True);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 20961 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20961;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20961);



