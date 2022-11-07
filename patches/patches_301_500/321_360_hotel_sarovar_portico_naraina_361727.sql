DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 361727;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 361727;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 361727;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 361727;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',361727,1061,1),
(' - ',361727,1062,1),
('Lobby',361727,1,2),
(' - ',361727,15,1),
('Café nine Restaurant',361727,28,3),
('1',361727,29,1),
('2',361727,30,2),
('Deluxe room',361727,1072,4),
('1',361727,1073,1),
('2',361727,1074,2),
('Superior room',361727,486,5),
('1',361727,487,1),
('2',361727,488,2),
('Suite',361727,1115,6),
('Entrance',361727,1116,1),
('Living room - 1',361727,1117,2),
('Living room - 2',361727,1118,3),
('Bedroom',361727,1119,4),
('Bathroom',361727,1120,5);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 361727;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',361727,1061,2,null,null,False,False,null),
(0,'thumb.jpg',361727,1062,2,'{"scene": {"view": {"hlookat": "359.498","vlookat" : "-18.061","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',361727,1,2,null,null,False,False,null),
(0,'thumb.jpg',361727,15,2,'{"scene": {"view": {"hlookat": "-22.300","vlookat" : "0.164","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',361727,28,2,null,null,False,False,null),
(0,'thumb.jpg',361727,29,2,'{"scene": {"view": {"hlookat": "353.503","vlookat" : "17.428","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',361727,30,2,'{"scene": {"view": {"hlookat": "-1.968","vlookat" : "22.136","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',361727,1072,2,null,null,False,False,null),
(0,'thumb.jpg',361727,1073,2,'{"scene": {"view": {"hlookat": "-0.328","vlookat" : "20.004","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',361727,1074,2,'{"scene": {"view": {"hlookat": "10.125","vlookat" : "7.333","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',361727,486,2,null,null,False,False,null),
(0,'thumb.jpg',361727,487,2,'{"scene": {"view": {"hlookat": "179.405","vlookat" : "32.385","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',361727,488,2,'{"scene": {"view": {"hlookat": "360.804","vlookat" : "34.877","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',361727,1115,2,null,null,False,False,null),
(0,'thumb.jpg',361727,1116,2,'{"scene": {"view": {"hlookat": "15.052","vlookat" : "1.594","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',361727,1117,2,'{"scene": {"view": {"hlookat": "1.476","vlookat" : "30.164","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',361727,1118,2,'{"scene": {"view": {"hlookat": "1.196","vlookat" : "29.259","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',361727,1119,2,'{"scene": {"view": {"hlookat": "16.647","vlookat" : "14.360","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',361727,1120,2,'{"scene": {"view": {"hlookat": "52.658","vlookat" : "32.327","fov" : "140"}}}',null,True,True,13);



DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 361727
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 361727;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 361727);
