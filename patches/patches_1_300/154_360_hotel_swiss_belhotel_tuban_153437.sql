DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 153437;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 153437;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 153437;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 153437;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',153437,79,1),
('Street View',153437,130,1),
('-',153437,193,2),
('Lobby',153437,1,2),
('-',153437,15,1),
('Reception ',153437,16,2),
('Sunset lounge',153437,28,2),
('1',153437,29,1),
('2',153437,30,2),
('3',153437,31,3),
('Swiss cafe restaurant',153437,167,3),
('1',153437,168,1),
('2',153437,169,2),
('Lila bar',153437,231,4),
(' - ',153437,232,1),
('Private Dining',153437,233,2),
('Pool',153437,2,5),
(' - ',153437,21,1),
('Gym',153437,3,6),
(' - ',153437,80,1),
('The palm spa',153437,4,7),
('Entrance',153437,101,1),
('Foot massage',153437,102,2),
('Single room ',153437,103,3),
('Double room',153437,104,4),
('Banjar ballroom',153437,131,8),
(' - ',153437,132,1),
('Samuan meeting rooms',153437,293,9),
('Entrance ',153437,297,1),
(' -',153437,298,2),
('Cenik - kids club',153437,84,10),
('-',153437,85,1),
('Deluxe room - pool view - king bed',153437,705,11),
('1',153437,706,1),
('2',153437,707,2),
('3',153437,708,3),
('Grand deluxe - twin bed - side view',153437,246,12),
(' - ',153437,247,1),
('Bathroom',153437,251,2),
('Grand deluxe - twin bed - pool view',153437,139,13),
('1',153437,140,1),
('2',153437,141,2),
('3',153437,142,3),
('Grand deluxe - family room',153437,536,14),
('1',153437,538,1),
('2',153437,539,2),
('Bathroom',153437,541,3),
('Triple Room',153437,263,15),
('1',153437,264,1),
('2',153437,265,2),
('Bathroom',153437,266,3),
('Suite',153437,159,16),
('1',153437,160,1),
('2',153437,161,2),
('3',153437,164,3),
('Bathroom',153437,162,4);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 153437;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',153437,79,2,null,null,False,False,null),
(0,'thumb.jpg',153437,130,2,'{"scene": {"view": {"hlookat": "12.300","vlookat" : "-9.183","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',153437,193,2,'{"scene": {"view": {"hlookat": "196.811","vlookat" : "-2.042","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,1,2,null,null,False,False,null),
(0,'thumb.jpg',153437,15,2,'{"scene": {"view": {"hlookat": "-2.066","vlookat" : "-8.140","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',153437,16,2,'{"scene": {"view": {"hlookat": "180.178","vlookat" : "5.315","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,28,2,null,null,False,False,null),
(0,'thumb.jpg',153437,29,2,'{"scene": {"view": {"hlookat": "356.277","vlookat" : "26.492","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',153437,30,2,'{"scene": {"view": {"hlookat": "2.459","vlookat" : "16.232","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,31,2,'{"scene": {"view": {"hlookat": "-4.099","vlookat" : "10.986","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,167,2,null,null,False,False,null),
(0,'thumb.jpg',153437,168,2,'{"scene": {"view": {"hlookat": "-203.545","vlookat" : "0.167","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',153437,169,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "20.824","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,231,2,null,null,False,False,null),
(0,'thumb.jpg',153437,232,2,'{"scene": {"view": {"hlookat": "2.296","vlookat" : "8.527","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',153437,233,2,'{"scene": {"view": {"hlookat": "0.656","vlookat" : "12.134","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,2,2,null,null,False,False,null),
(0,'thumb.jpg',153437,21,2,'{"scene": {"view": {"hlookat": "1.640","vlookat" : "5.247","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,3,2,null,null,False,False,null),
(0,'thumb.jpg',153437,80,2,'{"scene": {"view": {"hlookat": "103.481","vlookat" : "-0.436","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',153437,4,2,null,null,False,False,null),
(0,'thumb.jpg',153437,101,2,'{"scene": {"view": {"hlookat": "58.045","vlookat" : "2.460","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,102,2,'{"scene": {"view": {"hlookat": "-24.921","vlookat" : "-1.476","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,103,2,'{"scene": {"view": {"hlookat": "23.270","vlookat" : "15.756","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',153437,104,2,'{"scene": {"view": {"hlookat": "-28.038","vlookat" : "14.758","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,131,2,null,null,False,False,null),
(0,'thumb.jpg',153437,132,2,'{"scene": {"view": {"hlookat": "-6.887","vlookat" : "1.148","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',153437,293,2,null,null,False,False,null),
(0,'thumb.jpg',153437,297,2,'{"scene": {"view": {"hlookat": "-32.631","vlookat" : "2.296","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,298,2,'{"scene": {"view": {"hlookat": "0.328","vlookat" : "12.462","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',153437,84,2,null,null,False,False,null),
(0,'thumb.jpg',153437,85,2,'{"scene": {"view": {"hlookat": "-121.154","vlookat" : "11.927","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,705,2,null,null,False,False,null),
(0,'thumb.jpg',153437,706,2,'{"scene": {"view": {"hlookat": "14.265","vlookat" : "-0.821","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,707,2,'{"scene": {"view": {"hlookat": "-3.116","vlookat" : "30.332","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',153437,708,2,'{"scene": {"view": {"hlookat": "162.789","vlookat" : "3.262","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,246,2,null,null,False,False,null),
(0,'thumb.jpg',153437,247,2,'{"scene": {"view": {"hlookat": "-35.531","vlookat" : "7.370","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,251,2,'{"scene": {"view": {"hlookat": "11.478","vlookat" : "0.656","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,139,2,null,null,False,False,null),
(0,'thumb.jpg',153437,140,2,'{"scene": {"view": {"hlookat": "-29.678","vlookat" : "0.299","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,141,2,'{"scene": {"view": {"hlookat": "14.100","vlookat" : "21.971","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',153437,142,2,'{"scene": {"view": {"hlookat": "-37.500","vlookat" : "1.984","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,536,2,null,null,False,False,null),
(0,'thumb.jpg',153437,538,2,'{"scene": {"view": {"hlookat": "-9.838","vlookat" : "-0.328","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',153437,539,2,'{"scene": {"view": {"hlookat": "26.891","vlookat" : "-0.001","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,541,2,'{"scene": {"view": {"hlookat": "33.926","vlookat" : "3.279","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,263,2,null,null,False,False,null),
(0,'thumb.jpg',153437,264,2,'{"scene": {"view": {"hlookat": "156.630","vlookat" : "17.506","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',153437,265,2,'{"scene": {"view": {"hlookat": "39.346","vlookat" : "8.035","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,266,2,'{"scene": {"view": {"hlookat": "10.191","vlookat" : "11.080","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,159,2,null,null,False,False,null),
(0,'thumb.jpg',153437,160,2,'{"scene": {"view": {"hlookat": "188.187","vlookat" : "-0.128","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,161,2,'{"scene": {"view": {"hlookat": "-21.172","vlookat" : "32.556","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',153437,164,2,'{"scene": {"view": {"hlookat": "-3.116","vlookat" : "24.924","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',153437,162,2,'{"scene": {"view": {"hlookat": "26.560","vlookat" : "15.078","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 153437
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 153437;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 153437);

