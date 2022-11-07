INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',24994,556,1),
('View 1',24994,557,1),
('View 2',24994,558,2),
('Lobby - Salon Lutece',24994,1,2),
(' - ',24994,15,1),
('Breakfast Room',24994,6,3),
(' - ',24994,25,1),
('The Bar Made Of Pewter',24994,7,4),
(' - ',24994,152,1),
('Standard Room',24994,36,5),
('View 1',24994,37,1),
('View 2',24994,38,2),
('View 3',24994,39,3),
('Duplex',24994,573,6),
('First Floor',24994,574,1),
('Second Floor - View 1',24994,576,2),
('Second Floor - View 2',24994,577,3),
('Junior suite',24994,212,7),
('View 1',24994,213,1),
('View 2',24994,216,2);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 24994;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 24994;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 24994;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',24994,556,2,null,null,False,False),
(0,'thumb.jpg',24994,557,2,'{"scene": {"view": {"hlookat": "37.893","vlookat" : "-12.881"}}}',null,True,True),
(0,'thumb.jpg',24994,558,2,'{"scene": {"view": {"hlookat": "34.659","vlookat" : "1.235"}}}',null,True,True),
(0,'thumb.jpg',24994,1,2,null,null,False,False),
(0,'thumb.jpg',24994,15,2,'{"scene": {"view": {"hlookat": "0.170","vlookat" : "-379.554"}}}',null,True,True),
(0,'thumb.jpg',24994,6,2,null,null,False,False),
(0,'thumb.jpg',24994,25,2,'{"scene": {"view": {"hlookat": "-0.541","vlookat" : "-568.441"}}}',null,True,True),
(0,'thumb.jpg',24994,7,2,null,null,False,False),
(0,'thumb.jpg',24994,152,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',24994,36,2,null,null,False,False),
(0,'thumb.jpg',24994,37,2,'{"scene": {"view": {"hlookat": "1.861","vlookat" : "-27.369"}}}',null,True,True),
(0,'thumb.jpg',24994,38,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',24994,39,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',24994,573,2,null,null,False,False),
(0,'thumb.jpg',24994,574,2,'{"scene": {"view": {"hlookat": "1.056","vlookat" : "57.100"}}}',null,True,True),
(0,'thumb.jpg',24994,576,2,null,null,True,True),
(0,'thumb.jpg',24994,577,2,'{"scene": {"view": {"hlookat": "-2.810","vlookat" : "-67.468"}}}',null,True,True),
(0,'thumb.jpg',24994,212,2,null,null,False,False),
(0,'thumb.jpg',24994,213,2,'{"scene": {"view": {"hlookat": "0.723","vlookat" : "27.624"}}}',null,True,True),
(0,'thumb.jpg',24994,216,2,'{"scene": {"view": {"hlookat": "-0.047","vlookat" : "-202.675"}}}',null,True,True);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 24994 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 24994;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 24994);


