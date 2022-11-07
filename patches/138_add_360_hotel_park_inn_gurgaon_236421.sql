INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',236421,79,1),
('1',236421,130,1),
('2',236421,193,2),
('Lobby',236421,1,2),
('1',236421,15,1),
('2',236421,16,2),
('3',236421,17,3),
('Bar Lounge',236421,231,3),
('1',236421,232,1),
('2',236421,233,2),
('3',236421,234,3),
('4',236421,235,4),
('Cafe 55',236421,8,4),
('1',236421,33,1),
('2',236421,34,2),
('3',236421,35,3),
('4',236421,165,4),
('5',236421,166,5),
('Cardio Center',236421,3,5),
('1',236421,80,1),
('2',236421,81,2),
('Grand Regency',236421,131,6),
('1',236421,132,1),
('2',236421,252,2),
('Regency 1 and 2 Combined',236421,293,7),
('1',236421,297,1),
('2',236421,298,2),
('Superior King Size Room',236421,486,8),
('-',236421,487,1),
('Bathroom',236421,488,2),
('Superior Twin Size Room',236421,626,9),
('1',236421,627,1),
('2',236421,628,2),
('3',236421,629,3),
('Bathroom',236421,630,4),
('Deluxe Room',236421,568,10),
('1',236421,569,1),
('2',236421,570,2),
('3',236421,571,3),
('Bathroom',236421,572,4);


select * FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 236421;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 236421;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',236421,79,2,null,null,False,False,null),
(0,'thumb.jpg',236421,130,2,'{"scene": {"view": {"hlookat": "0.820","vlookat" : "-19.182","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,193,2,'{"scene": {"view": {"hlookat": "2.788","vlookat" : "-1.968","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,1,2,null,null,False,False,null),
(0,'thumb.jpg',236421,15,2,'{"scene": {"view": {"hlookat": "612.470","vlookat" : "4.482","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,16,2,'{"scene": {"view": {"hlookat": "3.772","vlookat" : "-0.492","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,17,2,'{"scene": {"view": {"hlookat": "125.630","vlookat" : "-0.314","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,231,2,null,null,False,False,null),
(0,'thumb.jpg',236421,232,2,'{"scene": {"view": {"hlookat": "352.092","vlookat" : "-0.158","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,233,2,'{"scene": {"view": {"hlookat": "135.479","vlookat" : "5.044","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,234,2,'{"scene": {"view": {"hlookat": "39.565","vlookat" : "0.294","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,235,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "-1.640","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,8,2,null,null,False,False,null),
(0,'thumb.jpg',236421,33,2,'{"scene": {"view": {"hlookat": "6.010","vlookat" : "1.870","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,34,2,'{"scene": {"view": {"hlookat": "522.448","vlookat" : "-0.603","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,35,2,'{"scene": {"view": {"hlookat": "151.082","vlookat" : "0.032","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,165,2,'{"scene": {"view": {"hlookat": "-23.665","vlookat" : "-0.435","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,166,2,'{"scene": {"view": {"hlookat": "67.376","vlookat" : "3.604","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,3,2,null,null,False,False,null),
(0,'thumb.jpg',236421,80,2,'{"scene": {"view": {"hlookat": "20.664","vlookat" : "-0.984","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,81,2,'{"scene": {"view": {"hlookat": "19.339","vlookat" : "-0.655","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,131,2,null,null,False,False,null),
(0,'thumb.jpg',236421,132,2,'{"scene": {"view": {"hlookat": "3.935","vlookat" : "0.492","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,252,2,'{"scene": {"view": {"hlookat": "152.647","vlookat" : "2.138","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,293,2,null,null,False,False,null),
(0,'thumb.jpg',236421,297,2,'{"scene": {"view": {"hlookat": "1.640","vlookat" : "-0.164","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,298,2,'{"scene": {"view": {"hlookat": "-196.713","vlookat" : "7.457","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,486,2,null,null,False,False,null),
(0,'thumb.jpg',236421,487,2,'{"scene": {"view": {"hlookat": "-19.841","vlookat" : "-0.164","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,488,2,'{"scene": {"view": {"hlookat": "-13.493","vlookat" : "-0.040","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,626,2,null,null,False,False,null),
(0,'thumb.jpg',236421,627,2,'{"scene": {"view": {"hlookat": "16.223","vlookat" : "0.164","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,628,2,'{"scene": {"view": {"hlookat": "-37.366","vlookat" : "2.455","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,629,2,'{"scene": {"view": {"hlookat": "-58.958","vlookat" : "1.309","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,630,2,'{"scene": {"view": {"hlookat": "-10.303","vlookat" : "0.820","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,568,2,null,null,False,False,null),
(0,'thumb.jpg',236421,569,2,'{"scene": {"view": {"hlookat": "14.099","vlookat" : "-0.328","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',236421,570,2,'{"scene": {"view": {"hlookat": "-22.126","vlookat" : "0.819","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,571,2,'{"scene": {"view": {"hlookat": "-44.717","vlookat" : "1.470","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',236421,572,2,'{"scene": {"view": {"hlookat": "28.695","vlookat" : "0.984","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 236421
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 236421;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 236421);



