INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',18004,556,1),
('View 1',18004,557,1),
('Lobby',18004,1,2),
(' - ',18004,15,1),
('Breakfast',18004,6,3),
('View 1',18004,25,1),
('View 2',18004,26,2),
('Single Room',18004,530,4),
(' - ',18004,531,1),
('Double Room',18004,427,5),
('View 1',18004,428,1),
('View 2',18004,429,2),
('Twin Room',18004,246,6),
('View 1',18004,247,1),
('View 2',18004,248,2),
('Bathroom',18004,249,3),
('Triple Room',18004,263,7),
('View 1',18004,264,1),
('Bathroom',18004,265,2);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 18004;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 18004;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 18004;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',18004,556,2,null,null,False,False),
(0,'thumb.jpg',18004,557,2,'{"scene": {"view": {"hlookat": "117.457","vlookat" : "-16.798"}}}',null,True,True),
(0,'thumb.jpg',18004,1,2,null,null,False,False),
(0,'thumb.jpg',18004,15,2,'{"scene": {"view": {"hlookat": "510.661","vlookat" : "0.599"}}}',null,True,True),
(0,'thumb.jpg',18004,6,2,null,null,False,False),
(0,'thumb.jpg',18004,25,2,'{"scene": {"view": {"hlookat": "405.128","vlookat" : "0.010"}}}',null,True,True),
(0,'thumb.jpg',18004,26,2,'{"scene": {"view": {"hlookat": "342.885","vlookat" : "-0.505"}}}',null,True,True),
(0,'thumb.jpg',18004,530,2,null,null,False,False),
(0,'thumb.jpg',18004,531,2,'{"scene": {"view": {"hlookat": "751.095","vlookat" : "12.621"}}}',null,True,True),
(0,'thumb.jpg',18004,427,2,null,null,False,False),
(0,'thumb.jpg',18004,428,2,'{"scene": {"view": {"hlookat": "354.325","vlookat" : "7.520"}}}',null,True,True),
(0,'thumb.jpg',18004,429,2,'{"scene": {"view": {"hlookat": "89.508","vlookat" : "24.536"}}}',null,True,True),
(0,'thumb.jpg',18004,246,2,null,null,False,False),
(0,'thumb.jpg',18004,247,2,'{"scene": {"view": {"hlookat": "321.009","vlookat" : "7.705"}}}',null,True,True),
(0,'thumb.jpg',18004,248,2,'{"scene": {"view": {"hlookat": "-32.516","vlookat" : "28.836"}}}',null,True,True),
(0,'thumb.jpg',18004,249,2,'{"scene": {"view": {"hlookat": "177.821","vlookat" : "6.780"}}}',null,True,True),
(0,'thumb.jpg',18004,263,2,null,null,False,False),
(0,'thumb.jpg',18004,264,2,'{"scene": {"view": {"hlookat": "348.686","vlookat" : "37.100"}}}',null,True,True),
(0,'thumb.jpg',18004,265,2,'{"scene": {"view": {"hlookat": "1.265","vlookat" : "8.348"}}}',null,True,True);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 18004 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 18004;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 18004);



