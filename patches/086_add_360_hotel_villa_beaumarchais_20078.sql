INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',20078,556,1),
('View 1',20078,557,1),
('View 2',20078,558,2),
('Entrance',20078,79,2),
(' - ',20078,130,0),
('Lobby',20078,1,3),
(' - ',20078,15,1),
('Breakfast Room',20078,6,4),
('View 1',20078,25,1),
('View 2',20078,26,2),
('View 3',20078,27,3),
('View 2',20078,16,2),
('The Bar and the Indoor Garden',20078,7,5),
('View 1',20078,152,1),
('View 2',20078,153,2),
('View 3',20078,154,3),
('Cave',20078,597,6),
(' - ',20078,598,0),
('Gym',20078,3,7),
('View 1',20078,80,1),
('View 2',20078,81,2),
('Salon Figaro- Conference room',20078,131,8),
('View 1',20078,132,1),
('View 2',20078,252,2),
('Double or Twin Standard room - Classic',20078,36,9),
('View 1',20078,37,1),
('View 2',20078,38,2),
('View 3',20078,39,3),
('View 4',20078,447,4),
('Double or Twin Standard room',20078,427,10),
('View 1',20078,428,1),
('View 2',20078,429,2),
('View 3',20078,430,3),
('View 4',20078,431,4),
('Double or Twin Superior Room',20078,486,11),
('View 1',20078,487,1),
('View 2',20078,488,2),
('View 3',20078,489,3),
('Bathroom',20078,491,4),
('Deluxe Room',20078,568,12),
('View 1',20078,569,1),
('View 2',20078,570,2),
('View 3',20078,571,3),
('Junior Suite',20078,212,13),
('View 1',20078,213,1),
('View 2',20078,214,2),
('View 3',20078,215,3),
('View 4',20078,216,4),
('Bathroom',20078,217,5);



DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20078;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20078;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 20078;

INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',20078,556,2,null,null,False,False),
(0,'thumb.jpg',20078,557,2,'{"scene": {"view": {"hlookat": "244.117","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,558,2,'{"scene": {"view": {"hlookat": "180.034","vlookat" : "-17.321"}}}',null,True,True),
(0,'thumb.jpg',20078,79,2,null,null,False,False),
(0,'thumb.jpg',20078,130,2,'{"scene": {"view": {"hlookat": "346.986","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,1,2,null,null,False,False),
(0,'thumb.jpg',20078,15,2,'{"scene": {"view": {"hlookat": "435.621","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,6,2,null,null,False,False),
(0,'thumb.jpg',20078,25,2,'{"scene": {"view": {"hlookat": "311.953","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,26,2,'{"scene": {"view": {"hlookat": "-16.954","vlookat" : "39.061"}}}',null,False,False),
(0,'thumb.jpg',20078,27,2,'{"scene": {"view": {"hlookat": "93.180","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,16,2,'{"scene": {"view": {"hlookat": "363.810","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,7,2,null,null,False,False),
(0,'thumb.jpg',20078,152,2,'{"scene": {"view": {"hlookat": "155.819","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,153,2,'{"scene": {"view": {"hlookat": "170.945","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,154,2,'{"scene": {"view": {"hlookat": "164.497","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,597,2,null,null,False,False),
(0,'thumb.jpg',20078,598,2,'{"scene": {"view": {"hlookat": "-17.243","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,3,2,null,null,False,False),
(0,'thumb.jpg',20078,80,2,'{"scene": {"view": {"hlookat": "530.769","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,81,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',20078,131,2,null,null,False,False),
(0,'thumb.jpg',20078,132,2,'{"scene": {"view": {"hlookat": "-184.068","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,252,2,'{"scene": {"view": {"hlookat": "-51.238","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,36,2,null,null,False,False),
(0,'thumb.jpg',20078,37,2,'{"scene": {"view": {"hlookat": "384.882","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,38,2,'{"scene": {"view": {"hlookat": "157.914","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,39,2,'{"scene": {"view": {"hlookat": "-93.088","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,447,2,'{"scene": {"view": {"hlookat": "516.646","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,427,2,null,null,False,False),
(0,'thumb.jpg',20078,428,2,'{"scene": {"view": {"hlookat": "156.386","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,429,2,'{"scene": {"view": {"hlookat": "365.810","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,430,2,'{"scene": {"view": {"hlookat": "-44.182","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,431,2,'{"scene": {"view": {"hlookat": "-1.292","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,486,2,null,null,False,False),
(0,'thumb.jpg',20078,487,2,'{"scene": {"view": {"hlookat": "339.195","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,488,2,'{"scene": {"view": {"hlookat": "302.172","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,489,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',20078,491,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',20078,568,2,null,null,False,False),
(0,'thumb.jpg',20078,569,2,'{"scene": {"view": {"hlookat": "-10.012","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,570,2,'{"scene": {"view": {"hlookat": "323.390","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,571,2,'{"scene": {"view": {"hlookat": "162.626","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,212,2,null,null,False,False),
(0,'thumb.jpg',20078,213,2,'{"scene": {"view": {"hlookat": "169.263","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,214,2,'{"scene": {"view": {"hlookat": "90.927","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,215,2,'{"scene": {"view": {"hlookat": "-104.056","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20078,216,2,'{"scene": {"view": {"hlookat": "-354.462","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20078,217,2,'{"scene": {"view": {"hlookat": "389.084","vlookat" : "0.000"}}}',null,False,False);


DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 20078 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20078;


INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20078);

