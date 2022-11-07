DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 13935;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 13935;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 13935;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 13935;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',13935,79,1),
('-',13935,130,1),
('Lobby',13935,1,2),
('-',13935,15,1),
('Breakfast room',13935,6,3),
('1',13935,25,1),
('2',13935,26,2),
('The Champs-Elysées meeting room',13935,131,4),
('-',13935,132,1),
('Standard studio',13935,36,5),
('1',13935,37,1),
('2',13935,38,2),
('Bathroom',13935,245,3),
('Deluxe studio',13935,133,6),
('1',13935,134,1),
('Bathroom',13935,138,2),
('2 Bedroom apartment',13935,13,7),
('1',13935,66,1),
('2',13935,68,2),
('Bathroom',13935,65,3),
('3 Bedroom Apartment',13935,212,8),
('1',13935,213,1),
('Bathroom',13935,217,2);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 13935;




INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',13935,79,2,null,null,False,False,null),
(0,'thumb.jpg',13935,130,2,'{"scene": {"view": {"hlookat": "-127.315","vlookat" : "-26.931","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',13935,1,2,null,null,False,False,null),
(0,'thumb.jpg',13935,15,2,'{"scene": {"view": {"hlookat": "-167.789","vlookat" : "19.019","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',13935,6,2,null,null,False,False,null),
(0,'thumb.jpg',13935,25,2,'{"scene": {"view": {"hlookat": "-20.279","vlookat" : "14.786","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',13935,26,2,'{"scene": {"view": {"hlookat": "-241.294","vlookat" : "10.777","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',13935,131,2,null,null,False,False,null),
(0,'thumb.jpg',13935,132,2,'{"scene": {"view": {"hlookat": "-0.929","vlookat" : "32.241","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',13935,36,2,null,null,False,False,null),
(0,'thumb.jpg',13935,37,2,'{"scene": {"view": {"hlookat": "156.875","vlookat" : "10.021","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',13935,38,2,'{"scene": {"view": {"hlookat": "-242.718","vlookat" : "9.162","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',13935,245,2,'{"scene": {"view": {"hlookat": "141.196","vlookat" : "10.796","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',13935,133,2,null,null,False,False,null),
(0,'thumb.jpg',13935,134,2,'{"scene": {"view": {"hlookat": "-435.598","vlookat" : "19.087","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',13935,138,2,'{"scene": {"view": {"hlookat": "-100.179","vlookat" : "15.586","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',13935,13,2,null,null,False,False,null),
(0,'thumb.jpg',13935,66,2,'{"scene": {"view": {"hlookat": "111.002","vlookat" : "9.831","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',13935,68,2,'{"scene": {"view": {"hlookat": "19.019","vlookat" : "22.791","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',13935,65,2,'{"scene": {"view": {"hlookat": "5.247","vlookat" : "27.697","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',13935,212,2,null,null,False,False,null),
(0,'thumb.jpg',13935,213,2,'{"scene": {"view": {"hlookat": "-442.401","vlookat" : "2.971","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',13935,217,2,'{"scene": {"view": {"hlookat": "-138.503","vlookat" : "12.458","fov" : "140"}}}',null,True,True,15);





DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 13935
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 13935;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 13935);
