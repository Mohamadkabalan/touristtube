INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',23066,79,1),
('-',23066,130,1),
('Lobby',23066,1,2),
('-',23066,15,1),
('The Bar next to the Lobby',23066,7,3),
('-',23066,152,1),
('Breakfast Room',23066,6,4),
('-',23066,26,1),
('Single Room',23066,530,5),
('View 1',23066,531,1),
('View 2',23066,532,2),
('View 3',23066,535,3),
('Bathroom',23066,533,4),
('Double or Twin Standard Room',23066,246,6),
('-',23066,247,1),
('Bathroom',23066,251,2),
('Junior Suite',23066,212,7),
('-',23066,216,1),
('Bathroom',23066,217,2);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 23066;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 23066;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 23066;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',23066,79,2,null,null,False,False),
(0,'thumb.jpg',23066,130,2,'{"scene": {"view": {"hlookat": "-65.698","vlookat" : "-27.629"}}}',null,True,True),
(0,'thumb.jpg',23066,1,2,null,null,False,False),
(0,'thumb.jpg',23066,15,2,'{"scene": {"view": {"hlookat": "1062.467","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',23066,7,2,null,null,False,False),
(0,'thumb.jpg',23066,152,2,'{"scene": {"view": {"hlookat": "21.067","vlookat" : "9.004"}}}',null,True,True),
(0,'thumb.jpg',23066,6,2,null,null,False,False),
(0,'thumb.jpg',23066,26,2,'{"scene": {"view": {"hlookat": "337.89","vlookat" : "4.557"}}}',null,True,True),
(0,'thumb.jpg',23066,530,2,null,null,False,False),
(0,'thumb.jpg',23066,531,2,'{"scene": {"view": {"hlookat": "334.515","vlookat" : "8.998"}}}',null,True,True),
(0,'thumb.jpg',23066,532,2,'{"scene": {"view": {"hlookat": "-359.674","vlookat" : "16.093"}}}',null,True,True),
(0,'thumb.jpg',23066,535,2,'{"scene": {"view": {"hlookat": "158.292","vlookat" : "21.437"}}}',null,True,True),
(0,'thumb.jpg',23066,533,2,'{"scene": {"view": {"hlookat": "83.932","vlookat" : "28.5"}}}',null,True,True),
(0,'thumb.jpg',23066,246,2,null,null,False,False),
(0,'thumb.jpg',23066,247,2,'{"scene": {"view": {"hlookat": "-268.609","vlookat" : "9.063"}}}',null,True,True),
(0,'thumb.jpg',23066,251,2,null,null,True,True),
(0,'thumb.jpg',23066,212,2,null,null,False,False),
(0,'thumb.jpg',23066,216,2,'{"scene": {"view": {"hlookat": "-164.907","vlookat" : "6.062"}}}',null,True,True),
(0,'thumb.jpg',23066,217,2,'{"scene": {"view": {"hlookat": "194.86","vlookat" : "6"}}}',null,True,True);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 23066 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 23066;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 23066);


