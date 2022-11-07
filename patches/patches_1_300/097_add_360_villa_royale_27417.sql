INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View ',27417,556,1),
('View 1',27417,557,1),
('View 2',27417,558,2),
('Lobby Entrance',27417,1,2),
('-',27417,15,1),
('Breakfast',27417,6,3),
('-',27417,25,1),
('Double Room',27417,427,4),
('View 1',27417,428,1),
('View 2',27417,429,2),
('View 3',27417,430,3),
('Bathroom',27417,431,4),
('Deluxe Room',27417,568,5),
('View 1',27417,569,1),
('View 2',27417,570,2),
('View 3',27417,571,3),
('Junior Suite',27417,212,6),
('View 1',27417,213,1),
('View 2',27417,214,2),
('View 3',27417,216,3),
('View 4',27417,217,4);



DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 27417;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 27417;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 27417;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',27417,556,2,null,null,False,False),
(0,'thumb.jpg',27417,557,2,'{"scene": {"view": {"hlookat": "571.668","vlookat" : "-26.694"}}}',null,True,True),
(0,'thumb.jpg',27417,558,2,'{"scene": {"view": {"hlookat": "-21.706","vlookat" : "-19.609"}}}',null,False,False),
(0,'thumb.jpg',27417,1,2,null,null,False,False),
(0,'thumb.jpg',27417,15,2,'{"scene": {"view": {"hlookat": "259.888","vlookat" : "2.612"}}}',null,True,True),
(0,'thumb.jpg',27417,6,2,null,null,False,False),
(0,'thumb.jpg',27417,25,2,'{"scene": {"view": {"hlookat": "444.479","vlookat" : "12.468"}}}',null,True,True),
(0,'thumb.jpg',27417,427,2,null,null,False,False),
(0,'thumb.jpg',27417,428,2,'{"scene": {"view": {"hlookat": "525.49","vlookat" : "-0.475"}}}',null,True,True),
(0,'thumb.jpg',27417,429,2,'{"scene": {"view": {"hlookat": "0.31","vlookat" : "5.375"}}}',null,True,True),
(0,'thumb.jpg',27417,430,2,'{"scene": {"view": {"hlookat": "178.538","vlookat" : "10.074"}}}',null,True,True),
(0,'thumb.jpg',27417,431,2,'{"scene": {"view": {"hlookat": "321.842","vlookat" : "4.741"}}}',null,True,True),
(0,'thumb.jpg',27417,568,2,null,null,False,False),
(0,'thumb.jpg',27417,569,2,'{"scene": {"view": {"hlookat": "173.06","vlookat" : "-3.443"}}}',null,True,True),
(0,'thumb.jpg',27417,570,2,'{"scene": {"view": {"hlookat": "0.405","vlookat" : "-8.552"}}}',null,True,True),
(0,'thumb.jpg',27417,571,2,'{"scene": {"view": {"hlookat": "357.038","vlookat" : "11.66"}}}',null,True,True),
(0,'thumb.jpg',27417,212,2,null,null,False,False),
(0,'thumb.jpg',27417,213,2,'{"scene": {"view": {"hlookat": "195.565","vlookat" : "-0.164"}}}',null,True,True),
(0,'thumb.jpg',27417,214,2,'{"scene": {"view": {"hlookat": "88.725","vlookat" : "0.491"}}}',null,True,True),
(0,'thumb.jpg',27417,216,2,'{"scene": {"view": {"hlookat": "2.086","vlookat" : "12.866"}}}',null,True,True),
(0,'thumb.jpg',27417,217,2,'{"scene": {"view": {"hlookat": "169.501","vlookat" : "0"}}}',null,True,True);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 27417 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 27417;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 27417);


