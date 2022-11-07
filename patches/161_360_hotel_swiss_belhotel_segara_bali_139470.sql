DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 139470;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 139470;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 139470;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 139470;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',139470,79,1),
('1',139470,130,1),
('2',139470,193,2),
('Lobby',139470,1,2),
('1',139470,15,1),
('2',139470,16,2),
('3',139470,17,3),
('4',139470,18,4),
('Café Laguna',139470,8,3),
('1',139470,33,1),
('2',139470,34,2),
('3',139470,35,3),
('Private Romantic Dinner',139470,28,4),
('1',139470,29,1),
('2',139470,30,2),
('Ocean Pool Bar',139470,7,5),
('1',139470,152,1),
('Lounge Bar',139470,94,6),
('1',139470,95,1),
('Ocean Pool ',139470,2,7),
('1',139470,21,1),
('2',139470,92,2),
('3',139470,186,3),
('4',139470,187,4),
('5',139470,22,5),
('Waterfall Pool',139470,992,8),
('1',139470,993,1),
('2',139470,994,2),
('3',139470,995,3),
('Serene Spa',139470,4,9),
('1',139470,101,1),
('2',139470,102,2),
('3',139470,103,3),
('4',139470,104,4),
('Yoga Spot',139470,464,10),
('1',139470,465,1),
('2',139470,466,2),
('Gym',139470,3,11),
('1',139470,80,1),
('Bualu Meeting Room',139470,131,12),
('1',139470,132,1),
('Superior Pool View - Queen Bed',139470,486,13),
('1',139470,492,1),
('2',139470,487,2),
('3',139470,491,3),
('Superior Pool View - Twin Bed',139470,246,14),
('1',139470,247,1),
('2',139470,248,2),
('3',139470,249,3),
('4',139470,251,4),
('Premier Pool View - King Bed',139470,480,15),
('1',139470,485,1),
('2',139470,481,2),
('3',139470,482,3),
('4',139470,484,4),
('Premier Pool View - Twin Bed',139470,626,16),
('1',139470,627,1),
('2',139470,628,2),
('3',139470,629,3),
('4',139470,630,4),
('Duplex Pool View',139470,573,17),
('1',139470,574,1),
('2',139470,575,2),
('3',139470,576,3),
('4',139470,577,4),
('Laguna Pool Access',139470,998,18),
('1',139470,999,1),
('2',139470,1000,2),
('3',139470,1004,3);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 139470;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',139470,79,2,null,null,False,False,null),
(0,'thumb.jpg',139470,130,2,'{"scene": {"view": {"hlookat": "-2.313","vlookat" : "0.143","fov" : "139.106"}}}',null,False,False,null),
(0,'thumb.jpg',139470,193,2,'{"scene": {"view": {"hlookat": "0.829","vlookat" : "0.000","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',139470,1,2,null,null,False,False,null),
(0,'thumb.jpg',139470,15,2,'{"scene": {"view": {"hlookat": "3.490","vlookat" : "5.214","fov" : "138.491"}}}',null,True,True,1),
(0,'thumb.jpg',139470,16,2,'{"scene": {"view": {"hlookat": "2.791","vlookat" : "0.000","fov" : "137.209"}}}',null,False,False,null),
(0,'thumb.jpg',139470,17,2,'{"scene": {"view": {"hlookat": "1.033","vlookat" : "0.000","fov" : "138.967"}}}',null,False,False,null),
(0,'thumb.jpg',139470,18,2,'{"scene": {"view": {"hlookat": "1.688","vlookat" : "0.000","fov" : "138.312"}}}',null,False,False,null),
(0,'thumb.jpg',139470,8,2,null,null,False,False,null),
(0,'thumb.jpg',139470,33,2,'{"scene": {"view": {"hlookat": "2.011","vlookat" : "0.080","fov" : "137.552"}}}',null,False,False,null),
(0,'thumb.jpg',139470,34,2,'{"scene": {"view": {"hlookat": "-7.219","vlookat" : "4.104","fov" : "138.856"}}}',null,False,False,null),
(0,'thumb.jpg',139470,35,2,'{"scene": {"view": {"hlookat": "1.341","vlookat" : "0.000","fov" : "138.659"}}}',null,True,True,2),
(0,'thumb.jpg',139470,28,2,null,null,False,False,null),
(0,'thumb.jpg',139470,29,2,'{"scene": {"view": {"hlookat": "2.031","vlookat" : "0.000","fov" : "137.975"}}}',null,False,False,null),
(0,'thumb.jpg',139470,30,2,'{"scene": {"view": {"hlookat": "-16.653","vlookat" : "4.847","fov" : "138.779"}}}',null,True,True,3),
(0,'thumb.jpg',139470,7,2,null,null,False,False,null),
(0,'thumb.jpg',139470,152,2,'{"scene": {"view": {"hlookat": "3.318","vlookat" : "-0.117","fov" : "138.659"}}}',null,False,False,null),
(0,'thumb.jpg',139470,94,2,null,null,False,False,null),
(0,'thumb.jpg',139470,95,2,'{"scene": {"view": {"hlookat": "2.977","vlookat" : "1.633","fov" : "138.171"}}}',null,False,False,null),
(0,'thumb.jpg',139470,2,2,null,null,False,False,null),
(0,'thumb.jpg',139470,21,2,'{"scene": {"view": {"hlookat": "3.743","vlookat" : "2.161","fov" : "139.537"}}}',null,True,True,4),
(0,'thumb.jpg',139470,92,2,'{"scene": {"view": {"hlookat": "-15.157","vlookat" : "-0.195","fov" : "138.266"}}}',null,False,False,null),
(0,'thumb.jpg',139470,186,2,'{"scene": {"view": {"hlookat": "-112.337","vlookat" : "0.686","fov" : "136.717"}}}',null,False,False,null),
(0,'thumb.jpg',139470,187,2,'{"scene": {"view": {"hlookat": "-134.484","vlookat" : "0.594","fov" : "137.662"}}}',null,False,False,null),
(0,'thumb.jpg',139470,22,2,'{"scene": {"view": {"hlookat": "-158.113","vlookat" : "2.398","fov" : "135.311"}}}',null,False,False,null),
(0,'thumb.jpg',139470,992,2,null,null,False,False,null),
(0,'thumb.jpg',139470,993,2,'{"scene": {"view": {"hlookat": "-58.542","vlookat" : "0.650","fov" : "138.740"}}}',null,True,True,5),
(0,'thumb.jpg',139470,994,2,'{"scene": {"view": {"hlookat": "-34.515","vlookat" : "0.326","fov" : "136.968"}}}',null,False,False,null),
(0,'thumb.jpg',139470,995,2,'{"scene": {"view": {"hlookat": "-36.285","vlookat" : "3.811","fov" : "138.312"}}}',null,False,False,null),
(0,'thumb.jpg',139470,4,2,null,null,False,False,null),
(0,'thumb.jpg',139470,101,2,'{"scene": {"view": {"hlookat": "2.297","vlookat" : "0.000","fov" : "137.769"}}}',null,False,False,null),
(0,'thumb.jpg',139470,102,2,'{"scene": {"view": {"hlookat": "2.179","vlookat" : "0.000","fov" : "137.821"}}}',null,True,True,6),
(0,'thumb.jpg',139470,103,2,'{"scene": {"view": {"hlookat": "24.766","vlookat" : "4.747","fov" : "138.025"}}}',null,False,False,null),
(0,'thumb.jpg',139470,104,2,'{"scene": {"view": {"hlookat": "107.220","vlookat" : "11.510","fov" : "139.037"}}}',null,False,False,null),
(0,'thumb.jpg',139470,464,2,null,null,False,False,null),
(0,'thumb.jpg',139470,465,2,'{"scene": {"view": {"hlookat": "8.770","vlookat" : "0.605","fov" : "138.447"}}}',null,False,False,null),
(0,'thumb.jpg',139470,466,2,'{"scene": {"view": {"hlookat": "-33.621","vlookat" : "0.900","fov" : "138.931"}}}',null,True,True,7),
(0,'thumb.jpg',139470,3,2,null,null,False,False,null),
(0,'thumb.jpg',139470,80,2,'{"scene": {"view": {"hlookat": "2.974","vlookat" : "0.000","fov" : "137.029"}}}',null,True,True,8),
(0,'thumb.jpg',139470,131,2,null,null,False,False,null),
(0,'thumb.jpg',139470,132,2,'{"scene": {"view": {"hlookat": "3.173","vlookat" : "0.044","fov" : "137.975"}}}',null,False,False,null),
(0,'thumb.jpg',139470,486,2,null,null,False,False,null),
(0,'thumb.jpg',139470,492,2,'{"scene": {"view": {"hlookat": "0.801","vlookat" : "0.816","fov" : "136.321"}}}',null,True,True,9),
(0,'thumb.jpg',139470,487,2,'{"scene": {"view": {"hlookat": "-29.657","vlookat" : "0.106","fov" : "137.029"}}}',null,False,False,null),
(0,'thumb.jpg',139470,491,2,'{"scene": {"view": {"hlookat": "-23.250","vlookat" : "0.327","fov" : "138.340"}}}',null,False,False,null),
(0,'thumb.jpg',139470,246,2,null,null,False,False,null),
(0,'thumb.jpg',139470,247,2,'{"scene": {"view": {"hlookat": "-0.116","vlookat" : "0.576","fov" : "138.312"}}}',null,True,True,10),
(0,'thumb.jpg',139470,248,2,'{"scene": {"view": {"hlookat": "3.516","vlookat" : "0.340","fov" : "138.779"}}}',null,False,False,null),
(0,'thumb.jpg',139470,249,2,'{"scene": {"view": {"hlookat": "-32.756","vlookat" : "1.386","fov" : "138.818"}}}',null,False,False,null),
(0,'thumb.jpg',139470,251,2,'{"scene": {"view": {"hlookat": "-25.095","vlookat" : "0.306","fov" : "137.821"}}}',null,False,False,null),
(0,'thumb.jpg',139470,480,2,null,null,False,False,null),
(0,'thumb.jpg',139470,485,2,'{"scene": {"view": {"hlookat": "1.346","vlookat" : "0.384","fov" : "136.522"}}}',null,True,True,11),
(0,'thumb.jpg',139470,481,2,'{"scene": {"view": {"hlookat": "-3.071","vlookat" : "0.281","fov" : "137.662"}}}',null,False,False,null),
(0,'thumb.jpg',139470,482,2,'{"scene": {"view": {"hlookat": "-18.789","vlookat" : "2.772","fov" : "138.618"}}}',null,False,False,null),
(0,'thumb.jpg',139470,484,2,'{"scene": {"view": {"hlookat": "1.734","vlookat" : "0.000","fov" : "138.266"}}}',null,False,False,null),
(0,'thumb.jpg',139470,626,2,null,null,False,False,null),
(0,'thumb.jpg',139470,627,2,'{"scene": {"view": {"hlookat": "1.878","vlookat" : "0.000","fov" : "138.123"}}}',null,True,True,12),
(0,'thumb.jpg',139470,628,2,'{"scene": {"view": {"hlookat": "1.643","vlookat" : "0.000","fov" : "138.357"}}}',null,False,False,null),
(0,'thumb.jpg',139470,629,2,'{"scene": {"view": {"hlookat": "21.068","vlookat" : "6.644"}}}',null,False,False,null),
(0,'thumb.jpg',139470,630,2,'{"scene": {"view": {"hlookat": "3.521","vlookat" : "0.443"}}}',null,False,False,null),
(0,'thumb.jpg',139470,573,2,null,null,False,False,null),
(0,'thumb.jpg',139470,574,2,'{"scene": {"view": {"hlookat": "1.040","vlookat" : "1.008"}}}',null,True,True,13),
(0,'thumb.jpg',139470,575,2,'{"scene": {"view": {"hlookat": "1.781","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',139470,576,2,'{"scene": {"view": {"hlookat": "1.466","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',139470,577,2,'{"scene": {"view": {"hlookat": "1.424","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',139470,998,2,null,null,False,False,null),
(0,'thumb.jpg',139470,999,2,'{"scene": {"view": {"hlookat": "1.935","vlookat" : "0.000"}}}',null,True,True,14),
(0,'thumb.jpg',139470,1000,2,'{"scene": {"view": {"hlookat": "-1.649","vlookat" : "0.054"}}}',null,False,False,null),
(0,'thumb.jpg',139470,1004,2,'{"scene": {"view": {"hlookat": "0.152","vlookat" : "0.669"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 139470
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 139470;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 139470);

