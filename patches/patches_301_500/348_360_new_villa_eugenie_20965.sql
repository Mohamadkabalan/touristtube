DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 20965;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20965;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 20965;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 20965;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',20965,556,1),
('View 1',20965,557,1),
('View 2',20965,558,2),
('View 3',20965,559,3),
('View 4',20965,560,4),
('View 5',20965,561,5),
('Lobby',20965,1,2),
('View 1',20965,15,1),
('View 2',20965,16,2),
('Lounge',20965,94,3),
('View 1',20965,95,1),
('View 2',20965,96,2),
('Breakfast Room',20965,6,4),
('View 1',20965,25,1),
('View 2',20965,26,2),
('Salon Napoleon - Meeting Rom',20965,131,5),
('View 1',20965,132,1),
('View 2',20965,252,2),
('Standard Room',20965,36,6),
('View 1',20965,37,1),
('View 2',20965,38,2),
('Bathroom',20965,245,3),
('Superior Room',20965,486,7),
('View 1',20965,487,1),
('Bathroom',20965,491,2);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20965;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',20965,556,2,null,null,False,False,null),
(0,'thumb.jpg',20965,557,2,'{"scene": {"view": {"hlookat": "-161.234","vlookat" : "-1.654","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',20965,558,2,'{"scene": {"view": {"hlookat": "163.500","vlookat" : "-10.468","fov" : "140.000"}}}',null,True,True,2),
(0,'thumb.jpg',20965,559,2,'{"scene": {"view": {"hlookat": "-1.703","vlookat" : "-22.757","fov" : "140.000"}}}',null,True,True,3),
(0,'thumb.jpg',20965,560,2,'{"scene": {"view": {"hlookat": "-209.902","vlookat" : "-24.857","fov" : "140.000"}}}',null,True,True,4),
(0,'thumb.jpg',20965,561,2,'{"scene": {"view": {"hlookat": "-75.250","vlookat" : "-24.043","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',20965,1,2,null,null,False,False,null),
(0,'thumb.jpg',20965,15,2,'{"scene": {"view": {"hlookat": "78.419","vlookat" : "0.821","fov" : "140.000"}}}',null,True,True,5),
(0,'thumb.jpg',20965,16,2,'{"scene": {"view": {"hlookat": "-48.092","vlookat" : "-1.824","fov" : "140.000"}}}',null,True,True,6),
(0,'thumb.jpg',20965,94,2,null,null,False,False,null),
(0,'thumb.jpg',20965,95,2,'{"scene": {"view": {"hlookat": "526.945","vlookat" : "0.821","fov" : "140.000"}}}',null,True,True,7),
(0,'thumb.jpg',20965,96,2,'{"scene": {"view": {"hlookat": "-205.298","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,8),
(0,'thumb.jpg',20965,6,2,null,null,False,False,null),
(0,'thumb.jpg',20965,25,2,'{"scene": {"view": {"hlookat": "-0.652","vlookat" : "1.556","fov" : "140.000"}}}',null,True,True,9),
(0,'thumb.jpg',20965,26,2,'{"scene": {"view": {"hlookat": "-175.492","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,10),
(0,'thumb.jpg',20965,131,2,null,null,False,False,null),
(0,'thumb.jpg',20965,132,2,'{"scene": {"view": {"hlookat": "315.671","vlookat" : "2.621","fov" : "140.000"}}}',null,True,True,11),
(0,'thumb.jpg',20965,252,2,'{"scene": {"view": {"hlookat": "-177.147","vlookat" : "6.018","fov" : "140.000"}}}',null,True,True,12),
(0,'thumb.jpg',20965,36,2,null,null,False,False,null),
(0,'thumb.jpg',20965,37,2,'{"scene": {"view": {"hlookat": "-197.466","vlookat" : "0.197","fov" : "140.000"}}}',null,True,True,13),
(0,'thumb.jpg',20965,38,2,'{"scene": {"view": {"hlookat": "306.353","vlookat" : "27.136","fov" : "140.000"}}}',null,True,True,14),
(0,'thumb.jpg',20965,245,2,'{"scene": {"view": {"hlookat": "71.348","vlookat" : "13.354","fov" : "140.000"}}}',null,True,True,15),
(0,'thumb.jpg',20965,486,2,null,null,False,False,null),
(0,'thumb.jpg',20965,487,2,'{"scene": {"view": {"hlookat": "520.273","vlookat" : "4.488","fov" : "140.000"}}}',null,True,True,16),
(0,'thumb.jpg',20965,491,2,'{"scene": {"view": {"hlookat": "223.338","vlookat" : "13.433","fov" : "140.000"}}}',null,True,True,17);





DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 20965
AND d.parent_id IS NULL
;


UPDATE cms_hotel set has_360 = 1 where id = 20965;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20965;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20965);

