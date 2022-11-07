DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 248902;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 248902;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 248902;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 248902;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior reception',248902,79,1),
('1',248902,130,1),
('2',248902,193,2),
('Lobby',248902,1,2),
('Lobby view ( beach )',248902,15,1),
('Reception - 1',248902,16,2),
('Reception - 2',248902,17,3),
('Café lobby lounge',248902,8,3),
('1',248902,33,1),
('2',248902,34,2),
('3',248902,35,3),
('Gym',248902,3,4),
('1',248902,80,1),
('2',248902,81,2),
('Swimming pool',248902,2,5),
('1',248902,21,1),
('2',248902,92,2),
('1 bedroom beach chalet',248902,14,6),
('1',248902,73,1),
('2',248902,75,2),
('3',248902,74,3),
('Superior city chalet',248902,486,7),
('1',248902,487,1),
('2',248902,488,2),
('3',248902,489,3),
('Deluxe 5 bedroom villas',248902,825,8),
('Exterior',248902,877,1),
('Ground floor - Dining Area',248902,878,2),
('Ground floor -Living Area - 1',248902,879,3),
('Ground floor -Living Area - 2',248902,880,4),
('Ground Floor - Stairs',248902,881,5),
('Ground Floor - Bedroom',248902,882,6),
('Ground Floor - Kitchen',248902,883,7),
('Ground Floor - Bathroom',248902,884,8),
('Ground Floor - Guest  Room',248902,885,9),
('Ground Floor - Guest  Room - Bathroom',248902,886,10),
('Second Floor - King Bedroom - 1',248902,1037,11),
('Second Floor - King Bedroom - 2',248902,1038,12),
('Second Floor - Living Area',248902,1039,13),
('Second Floor - Master Bedroom - 1',248902,1040,14),
('Second Floor - Master Bedroom - 2',248902,1041,15),
('Second Floor - Master Bedroom - 3',248902,1042,16),
('Second Floor - Shared Bathroom - 1',248902,1043,17),
('Second Floor - Twin Bedroom - 2',248902,1044,18),
('Second Floor - Twin Bedroom ',248902,1045,19);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 248902;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',248902,79,2,null,null,False,False,null),
(0,'thumb.jpg',248902,130,2,'{"scene": {"view": {"hlookat": "179.963","vlookat" : "-20.616","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',248902,193,2,'{"scene": {"view": {"hlookat": "-0.407","vlookat" : "0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1,2,null,null,False,False,null),
(0,'thumb.jpg',248902,15,2,'{"scene": {"view": {"hlookat": "-1.499","vlookat" : "-2.626","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',248902,16,2,'{"scene": {"view": {"hlookat": "162.394","vlookat" : "10.703","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,17,2,'{"scene": {"view": {"hlookat": "-24.430","vlookat" : "3.280","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',248902,8,2,null,null,False,False,null),
(0,'thumb.jpg',248902,33,2,'{"scene": {"view": {"hlookat": "-1.515","vlookat" : "-0.322","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,34,2,'{"scene": {"view": {"hlookat": "148.448","vlookat" : "7.603","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,35,2,'{"scene": {"view": {"hlookat": "-13.309","vlookat" : "9.079","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',248902,3,2,null,null,False,False,null),
(0,'thumb.jpg',248902,80,2,'{"scene": {"view": {"hlookat": "180.629","vlookat" : "9.356","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,81,2,'{"scene": {"view": {"hlookat": "363.669","vlookat" : "12.045","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',248902,2,2,null,null,False,False,null),
(0,'thumb.jpg',248902,21,2,'{"scene": {"view": {"hlookat": "16.062","vlookat" : "3.772","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',248902,92,2,'{"scene": {"view": {"hlookat": "183.022","vlookat" : "38.491","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',248902,14,2,null,null,False,False,null),
(0,'thumb.jpg',248902,73,2,'{"scene": {"view": {"hlookat": "183.670","vlookat" : "11.571","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,75,2,'{"scene": {"view": {"hlookat": "2.460","vlookat" : "30.002","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',248902,74,2,'{"scene": {"view": {"hlookat": "202.479","vlookat" : "24.279","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',248902,486,2,null,null,False,False,null),
(0,'thumb.jpg',248902,487,2,'{"scene": {"view": {"hlookat": "-207.367","vlookat" : "28.929","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',248902,488,2,'{"scene": {"view": {"hlookat": "-176.420","vlookat" : "6.686","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,489,2,'{"scene": {"view": {"hlookat": "189.462","vlookat" : "26.213","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,825,2,null,null,False,False,null),
(0,'thumb.jpg',248902,877,2,'{"scene": {"view": {"hlookat": "0.927","vlookat" : "0.328","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',248902,878,2,'{"scene": {"view": {"hlookat": "1.148","vlookat" : "32.621","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,879,2,'{"scene": {"view": {"hlookat": "-12.624","vlookat" : "12.134","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,880,2,'{"scene": {"view": {"hlookat": "182.495","vlookat" : "37.417","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',248902,881,2,'{"scene": {"view": {"hlookat": "-8.690","vlookat" : "8.525","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,882,2,'{"scene": {"view": {"hlookat": "-23.348","vlookat" : "15.133","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',248902,883,2,'{"scene": {"view": {"hlookat": "27.361","vlookat" : "11.144","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,884,2,'{"scene": {"view": {"hlookat": "67.005","vlookat" : "34.957","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,885,2,'{"scene": {"view": {"hlookat": "180.475","vlookat" : "35.755","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',248902,886,2,'{"scene": {"view": {"hlookat": "61.583","vlookat" : "32.174","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1037,2,'{"scene": {"view": {"hlookat": "180.282","vlookat" : "36.936","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',248902,1038,2,'{"scene": {"view": {"hlookat": "160.403","vlookat" : "18.034","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1039,2,'{"scene": {"view": {"hlookat": "193.521","vlookat" : "23.509","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1040,2,'{"scene": {"view": {"hlookat": "149.866","vlookat" : "12.179","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1041,2,'{"scene": {"view": {"hlookat": "158.797","vlookat" : "31.864","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',248902,1042,2,'{"scene": {"view": {"hlookat": "155.260","vlookat" : "0.610","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1043,2,'{"scene": {"view": {"hlookat": "26.034","vlookat" : "32.801","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1044,2,'{"scene": {"view": {"hlookat": "178.767","vlookat" : "22.829","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',248902,1045,2,'{"scene": {"view": {"hlookat": "1.476","vlookat" : "41.807","fov" : "140"}}}',null,True,True,17);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 248902
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 248902;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 248902);

