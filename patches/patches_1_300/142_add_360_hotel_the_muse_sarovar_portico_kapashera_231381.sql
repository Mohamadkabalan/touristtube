INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',231381,79,0),
('1',231381,130,1),
('Lobby',231381,1,0),
('1',231381,15,1),
('Crystal Banquet',231381,131,0),
('1',231381,132,1),
('2',231381,252,2),
('3',231381,253,3),
('4',231381,254,4),
('5',231381,255,5),
('Atrium Lounge Restaurant',231381,94,0),
('1',231381,95,1),
('2',231381,96,2),
('Patio Restaurant',231381,28,0),
('1',231381,29,1),
('2',231381,30,2),
('3',231381,31,3),
('4',231381,32,4),
('Gym',231381,3,0),
('1',231381,80,1),
('Swimming Pool',231381,2,0),
('1',231381,21,1),
('Superior King Size Room',231381,486,0),
('1',231381,487,1),
('2',231381,488,2),
('Superior Twin Room',231381,246,0),
('1',231381,247,1),
('2',231381,248,2),
('3',231381,251,3),
('Deluxe Room',231381,374,0),
('1',231381,379,1),
('2',231381,383,2),
('Portico King Size Room',231381,637,0),
('1',231381,638,1),
('2',231381,639,2),
('Portico Twin Room',231381,626,0),
('1',231381,627,1),
('2',231381,628,2);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 231381;





INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',231381,79,2,null,null,False,False,null),
(0,'thumb.jpg',231381,130,2,'{"scene": {"view": {"hlookat": "-23.810","vlookat" : "0.292","fov" : "139.764"}}}',null,True,True,1),
(0,'thumb.jpg',231381,1,2,null,null,False,False,null),
(0,'thumb.jpg',231381,15,2,'{"scene": {"view": {"hlookat": "-144.781","vlookat" : "0.680","fov" : "139.487"}}}',null,True,True,2),
(0,'thumb.jpg',231381,131,2,null,null,False,False,null),
(0,'thumb.jpg',231381,132,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,True,True,3),
(0,'thumb.jpg',231381,252,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,2,2,null),
(0,'thumb.jpg',231381,253,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,3,3,null),
(0,'thumb.jpg',231381,254,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,4,4,null),
(0,'thumb.jpg',231381,255,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,5,5,null),
(0,'thumb.jpg',231381,94,2,null,null,False,False,null),
(0,'thumb.jpg',231381,95,2,'{"scene": {"view": {"hlookat": "-21.322","vlookat" : "3.169","fov" : "139.234"}}}',null,True,True,null),
(0,'thumb.jpg',231381,96,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,2,2,4),
(0,'thumb.jpg',231381,28,2,null,null,False,False,null),
(0,'thumb.jpg',231381,29,2,'{"scene": {"view": {"hlookat": "0.394","vlookat" : "0.000","fov" : "139.628"}}}',null,True,True,null),
(0,'thumb.jpg',231381,30,2,'{"scene": {"view": {"hlookat": "-136.100","vlookat" : "2.912","fov" : "139.265"}}}',null,2,2,null),
(0,'thumb.jpg',231381,31,2,'{"scene": {"view": {"hlookat": "6.856","vlookat" : "4.916","fov" : "139.709"}}}',null,3,3,5),
(0,'thumb.jpg',231381,32,2,'{"scene": {"view": {"hlookat": "77.339","vlookat" : "6.172","fov" : "139.649"}}}',null,4,4,null),
(0,'thumb.jpg',231381,3,2,null,null,False,False,null),
(0,'thumb.jpg',231381,80,2,'{"scene": {"view": {"hlookat": "0.863","vlookat" : "0.000","fov" : "139.324"}}}',null,True,True,6),
(0,'thumb.jpg',231381,2,2,null,null,False,False,null),
(0,'thumb.jpg',231381,21,2,'{"scene": {"view": {"hlookat": "-13.661","vlookat" : "0.335","fov" : "138.740"}}}',null,True,True,7),
(0,'thumb.jpg',231381,486,2,null,null,False,False,null),
(0,'thumb.jpg',231381,487,2,'{"scene": {"view": {"hlookat": "25.874","vlookat" : "0.427","fov" : "139.628"}}}',null,True,True,8),
(0,'thumb.jpg',231381,488,2,'{"scene": {"view": {"hlookat": "-26.578","vlookat" : "0.413","fov" : "139.462"}}}',null,2,2,null),
(0,'thumb.jpg',231381,246,2,null,null,False,False,null),
(0,'thumb.jpg',231381,247,2,'{"scene": {"view": {"hlookat": "27.026","vlookat" : "3.233","fov" : "139.462"}}}',null,True,True,9),
(0,'thumb.jpg',231381,248,2,'{"scene": {"view": {"hlookat": "-28.354","vlookat" : "0.000","fov" : "139.670"}}}',null,2,2,null),
(0,'thumb.jpg',231381,251,2,'{"scene": {"view": {"hlookat": "17.425","vlookat" : "3.235","fov" : "139.628"}}}',null,3,3,null),
(0,'thumb.jpg',231381,374,2,null,null,False,False,null),
(0,'thumb.jpg',231381,379,2,'{"scene": {"view": {"hlookat": "21.203","vlookat" : "2.273","fov" : "139.649"}}}',null,True,True,10),
(0,'thumb.jpg',231381,383,2,'{"scene": {"view": {"hlookat": "24.013","vlookat" : "-0.059","fov" : "138.779"}}}',null,2,2,null),
(0,'thumb.jpg',231381,637,2,null,null,False,False,null),
(0,'thumb.jpg',231381,638,2,'{"scene": {"view": {"hlookat": "23.467","vlookat" : "1.316","fov" : "139.324"}}}',null,True,True,11),
(0,'thumb.jpg',231381,639,2,'{"scene": {"view": {"hlookat": "-16.225","vlookat" : "0.164","fov" : "140.000"}}}',null,2,2,null),
(0,'thumb.jpg',231381,626,2,null,null,False,False,null),
(0,'thumb.jpg',231381,627,2,'{"scene": {"view": {"hlookat": "-26.953","vlookat" : "3.847","fov" : "139.584"}}}',null,True,True,12),
(0,'thumb.jpg',231381,628,2,'{"scene": {"view": {"hlookat": "20.677","vlookat" : "2.274","fov" : "139.487"}}}',null,2,2,null);





DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 231381
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 231381;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 231381);



