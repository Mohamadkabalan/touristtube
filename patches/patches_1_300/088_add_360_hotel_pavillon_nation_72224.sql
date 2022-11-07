INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',72224,556,1),
('-',72224,557,1),
('Lobby',72224,1,2),
('-',72224,15,1),
('1930s Art-Deco - Breakfast Room',72224,6,3),
('View 1',72224,25,1),
('View 2',72224,26,2),
('View 3',72224,27,3),
('Gym (Fitness Room)',72224,3,4),
('-',72224,80,1),
('Salon Vincennes (Conference room)',72224,131,5),
('-',72224,132,1),
('Standard Room',72224,36,6),
('View 1',72224,37,1),
('View 2',72224,38,2),
('Executive Room',72224,144,7),
('View 1',72224,145,1),
('View 2',72224,146,2);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 72224;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 72224;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 72224;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',72224,556,2,null,null,False,False),
(0,'thumb.jpg',72224,557,2,'{"scene": {"view": {"hlookat": "63.557","vlookat" : "-30.968"}}}',null,True,True),
(0,'thumb.jpg',72224,1,2,null,null,False,False),
(0,'thumb.jpg',72224,15,2,'{"scene": {"view": {"hlookat": "160.469","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',72224,6,2,null,null,False,False),
(0,'thumb.jpg',72224,25,2,'{"scene": {"view": {"hlookat": "-4.768","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',72224,26,2,'{"scene": {"view": {"hlookat": "18.973","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',72224,27,2,'{"scene": {"view": {"hlookat": "-162.008","vlookat" : "0.164"}}}',null,True,True),
(0,'thumb.jpg',72224,3,2,null,null,False,False),
(0,'thumb.jpg',72224,80,2,'{"scene": {"view": {"hlookat": "181.541","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',72224,131,2,null,null,False,False),
(0,'thumb.jpg',72224,132,2,'{"scene": {"view": {"hlookat": "-15.39","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',72224,36,2,null,null,False,False),
(0,'thumb.jpg',72224,37,2,'{"scene": {"view": {"hlookat": "22.583","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',72224,38,2,'{"scene": {"view": {"hlookat": "204.129","vlookat" : "7.436"}}}',null,True,True),
(0,'thumb.jpg',72224,144,2,null,null,False,False),
(0,'thumb.jpg',72224,145,2,'{"scene": {"view": {"hlookat": "-8.871","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',72224,146,2,'{"scene": {"view": {"hlookat": "-136.982","vlookat" : "14.922"}}}',null,True,True);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 72224 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 72224;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 72224);


Hotel Beaumarchais ( Confirmed )
Normandy Hotel ( Confirmed )
Pavillon Courcelles ( Confirmed )
Pavillon Opera Bourse ( Confirmed)
Pavillon Opera Grand Boulevard ( Confirmed )
Pavillon Porte de Versailles ( Confirmed )
Pavillon Louvre Rivoli ( Confirmed )
1K ( Confirmed )
Villa Luxembourg ( Confirmed )
Pavillon Villiers Etoile (Confirmed )
Villa Eugenie ( Confirmed )
Villa Lutece Port Royal ( Confirmed )
Villa Montparnasse  ( Confirmed )
