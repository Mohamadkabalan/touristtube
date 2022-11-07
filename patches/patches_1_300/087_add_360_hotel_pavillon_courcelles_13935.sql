INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',13935,556,1),
('-',13935,557,1),
('Lobby',13935,1,2),
('-',13935,15,1),
('Breakfast Room',13935,6,3),
('View 1',13935,25,1),
('View 2',13935,26,2),
('The Champs-Elysées Meeting Room',13935,131,4),
('View 1',13935,132,1),
('View 2',13935,252,2),
('Standard Studio',13935,36,5),
('View 1',13935,37,1),
('View 2',13935,38,2),
('Bathroom',13935,245,3),
('Deluxe Studio',13935,133,6),
('View 1',13935,134,1),
('Bathroom',13935,138,2),
('2 Bedroom Apartment',13935,13,7),
('View 1',13935,66,1),
('Bathroom',13935,65,2),
('View 2',13935,68,3),
('3 Bedroom Apartment',13935,451,8),
('View 1',13935,453,1),
('Bathroom',13935,455,2);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 13935;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 13935;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 13935;

INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',13935,556,2,null,null,False,False),
(0,'thumb.jpg',13935,557,2,'{"scene": {"view": {"hlookat": "-119.315","vlookat" : "-14.991"}}}',null,True,True),
(0,'thumb.jpg',13935,1,2,null,null,False,False),
(0,'thumb.jpg',13935,15,2,'{"scene": {"view": {"hlookat": "-148.165","vlookat" : "1.184"}}}',null,True,True),
(0,'thumb.jpg',13935,6,2,null,null,False,False),
(0,'thumb.jpg',13935,25,2,'{"scene": {"view": {"hlookat": "-0.298","vlookat" : "0.453"}}}',null,True,True),
(0,'thumb.jpg',13935,26,2,'{"scene": {"view": {"hlookat": "-118.232","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',13935,131,2,null,null,False,False),
(0,'thumb.jpg',13935,132,2,'{"scene": {"view": {"hlookat": "155.243","vlookat" : "2.917"}}}',null,True,True),
(0,'thumb.jpg',13935,252,2,'{"scene": {"view": {"hlookat": "162.292","vlookat" : "13.256"}}}',null,True,True),
(0,'thumb.jpg',13935,36,2,null,null,False,False),
(0,'thumb.jpg',13935,37,2,'{"scene": {"view": {"hlookat": "160.091","vlookat" : "15.04"}}}',null,True,True),
(0,'thumb.jpg',13935,38,2,'{"scene": {"view": {"hlookat": "117.318","vlookat" : "21.059"}}}',null,True,True),
(0,'thumb.jpg',13935,245,2,'{"scene": {"view": {"hlookat": "144.428","vlookat" : "15.363"}}}',null,False,False),
(0,'thumb.jpg',13935,133,2,null,null,False,False),
(0,'thumb.jpg',13935,134,2,null,null,True,True),
(0,'thumb.jpg',13935,138,2,'{"scene": {"view": {"hlookat": "-31.209","vlookat" : "22.335"}}}',null,False,False),
(0,'thumb.jpg',13935,13,2,null,null,False,False),
(0,'thumb.jpg',13935,66,2,'{"scene": {"view": {"hlookat": "146.259","vlookat" : "8.832"}}}',null,True,True),
(0,'thumb.jpg',13935,65,2,'{"scene": {"view": {"hlookat": "28.742","vlookat" : "11.933"}}}',null,True,True),
(0,'thumb.jpg',13935,68,2,'{"scene": {"view": {"hlookat": "31.508","vlookat" : "13.432"}}}',null,True,True),
(0,'thumb.jpg',13935,451,2,null,null,False,False),
(0,'thumb.jpg',13935,453,2,'{"scene": {"view": {"hlookat": "-16.522","vlookat" : "14.938"}}}',null,True,True),
(0,'thumb.jpg',13935,455,2,'{"scene": {"view": {"hlookat": "202.338","vlookat" : "15.47"}}}',null,True,True);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 13935 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 13935;


INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 13935);

