DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 53187;
DELETE FROM amadeus_hotel_image WHERE  hotel_id = 53187 AND tt_media_type_id = 2;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 53187;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',53187,556,1),
('Day 1',53187,557,1),
('Day 2',53187,558,2),
('Sunset 1',53187,559,3),
('Sunset 2',53187,560,4),
('Lobby',53187,1,2),
('1',53187,15,1),
('2',53187,16,2),
('3',53187,17,3),
('4',53187,18,4),
('Level 6- Breakfast Room',53187,6,3),
('1',53187,25,1),
('2',53187,26,2),
('3',53187,27,3),
('4',53187,602,4),
('5',53187,603,5),
('6',53187,604,6),
('Rooftop',53187,7,4),
('1st Floor - 1',53187,152,1),
('1st Floor -  2',53187,153,2),
('1st Floor -  3',53187,154,3),
('2nd Floor -  1',53187,155,4),
('2nd Floor -  2',53187,156,5),
('2nd Floor -  3',53187,157,6),
('2nd Floor -  4',53187,158,7),
('Swimming Pool',53187,2,5),
('1',53187,21,1),
('2',53187,92,2),
('3',53187,186,3),
('Fitness Center - Gym',53187,3,6),
(' -  ',53187,80,1),
('Petit Salon',53187,94,7),
('1',53187,95,1),
('2',53187,96,2),
('Gallery - Meeting Room',53187,131,8),
('1',53187,132,1),
('2',53187,252,2),
('3',53187,253,3),
('Shopping Arcade',53187,464,9),
('1',53187,465,1),
('2',53187,466,2),
('Al Hindi Restaurant',53187,28,10),
('1',53187,29,1),
('2',53187,30,2),
('3',53187,31,3),
('Terrace',53187,167,11),
(' - ',53187,168,1),
('Deluxe Room',53187,133,12),
('1',53187,134,1),
('2',53187,135,2),
('Bathroom',53187,138,3),
('Entrance',53187,137,4),
('Premium Room - King Size Bed',53187,480,13),
('1',53187,481,1),
('2',53187,482,2),
('Bathroom',53187,484,3),
('Premium Room - Twin Bed',53187,626,14),
('View 1',53187,627,1),
('View 2',53187,628,2),
('View 3',53187,629,3),
('Bathroom',53187,630,4),
('Junior Suite',53187,212,15),
('1',53187,216,1),
('2',53187,213,2),
('Bathroom',53187,217,3),
('Senior Suite - City View 1',53187,605,16),
('1',53187,606,1),
('2',53187,607,2),
('Bathroom - 1',53187,608,3),
('Bathroom - 2',53187,609,4),
('Senior Suite - City View 2',53187,610,17),
('Living Room - 1',53187,611,1),
('Living Room - 2',53187,612,2),
('Bedroom - 1',53187,613,3),
('Bedroom - 2',53187,614,4),
('Bathroom',53187,615,5),
('Senior Suite - 1 Bedroom',53187,616,18),
('View 1',53187,617,1),
('View 2',53187,618,2),
('Bathroom',53187,619,3),
('Senior Suite - Panoramic Sea View',53187,620,19),
('View 1',53187,621,1),
('View 2',53187,622,2),
('View 3',53187,623,3),
('Bathroom',53187,624,4),
('Presidential Suite',53187,355,20),
('View 1',53187,625,1),
('View 2',53187,359,2),
('View 3',53187,360,3),
('View 4',53187,356,4),
('View 5',53187,361,5),
('View 6',53187,362,6),
('View 7',53187,363,7);




DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 53187;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 53187;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',53187,556,2,null,null,False,False,null),
(0,'thumb.jpg',53187,557,2,'{"scene": {"view": {"hlookat": "-165.113","vlookat" : "-28.938","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,558,2,'{"scene": {"view": {"hlookat": "-202.745","vlookat" : "-0.1270","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,559,2,'{"scene": {"view": {"hlookat": "-31.710","vlookat" : "-30.771","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,560,2,'{"scene": {"view": {"hlookat": "-20.987","vlookat" : "-26.233","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,1,2,null,null,False,False,null),
(0,'thumb.jpg',53187,15,2,'{"scene": {"view": {"hlookat": "-10.155","vlookat" : "-1.480","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,16,2,'{"scene": {"view": {"hlookat": "-28.396","vlookat" : "0.919","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,17,2,'{"scene": {"view": {"hlookat": "-219.737","vlookat" : "7.461","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,18,2,'{"scene": {"view": {"hlookat": "-179.612","vlookat" : "10.042","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,6,2,null,null,False,False,null),
(0,'thumb.jpg',53187,25,2,'{"scene": {"view": {"hlookat": "-436.521","vlookat" : "7.323","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,26,2,'{"scene": {"view": {"hlookat": "376.736","vlookat" : "-0.162","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,27,2,'{"scene": {"view": {"hlookat": "6.535","vlookat" : "13.023","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,602,2,'{"scene": {"view": {"hlookat": "390.915","vlookat" : "17.181","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,603,2,'{"scene": {"view": {"hlookat": "-183.115","vlookat" : "10.404","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,604,2,'{"scene": {"view": {"hlookat": "559.760","vlookat" : "-0.039","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,7,2,null,null,False,False,null),
(0,'thumb.jpg',53187,152,2,'{"scene": {"view": {"hlookat": "-397.006","vlookat" : "-0.929","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,153,2,'{"scene": {"view": {"hlookat": "-491.022","vlookat" : "1.081","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,154,2,'{"scene": {"view": {"hlookat": "373.352","vlookat" : "-0.645","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,155,2,'{"scene": {"view": {"hlookat": "-340.889","vlookat" : "4.480","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,156,2,'{"scene": {"view": {"hlookat": "583.300","vlookat" : "-10.378","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,157,2,'{"scene": {"view": {"hlookat": "96.472","vlookat" : "-10.378","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,158,2,'{"scene": {"view": {"hlookat": "728.620","vlookat" : "-3.739","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,2,2,null,null,False,False,null),
(0,'thumb.jpg',53187,21,2,'{"scene": {"view": {"hlookat": "372.494","vlookat" : "13.009","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,92,2,'{"scene": {"view": {"hlookat": "-35.904","vlookat" : "28.281","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,186,2,'{"scene": {"view": {"hlookat": "265.103","vlookat" : "19.682","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,3,2,null,null,False,False,null),
(0,'thumb.jpg',53187,80,2,'{"scene": {"view": {"hlookat": "344.339","vlookat" : "0.031","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,94,2,null,null,False,False,null),
(0,'thumb.jpg',53187,95,2,'{"scene": {"view": {"hlookat": "1171.150","vlookat" : "0.321","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,96,2,'{"scene": {"view": {"hlookat": "-733.565","vlookat" : "0.537","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,131,2,null,null,False,False,null),
(0,'thumb.jpg',53187,132,2,'{"scene": {"view": {"hlookat": "4143.363","vlookat" : "-3.480","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,252,2,'{"scene": {"view": {"hlookat": "-197.649","vlookat" : "0.495","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,253,2,'{"scene": {"view": {"hlookat": "0.453","vlookat" : "0.812","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,464,2,null,null,False,False,null),
(0,'thumb.jpg',53187,465,2,'{"scene": {"view": {"hlookat": "-329.524","vlookat" : "-0.343","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,466,2,'{"scene": {"view": {"hlookat": "388.041","vlookat" : "2.230","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,28,2,null,null,False,False,null),
(0,'thumb.jpg',53187,29,2,'{"scene": {"view": {"hlookat": "303.697","vlookat" : "-0.155","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,30,2,'{"scene": {"view": {"hlookat": "-76.789","vlookat" : "0.232","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,31,2,'{"scene": {"view": {"hlookat": "510.809","vlookat" : "0.758","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,167,2,null,null,False,False,null),
(0,'thumb.jpg',53187,168,2,'{"scene": {"view": {"hlookat": "7.379","vlookat" : "-0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,133,2,null,null,False,False,null),
(0,'thumb.jpg',53187,134,2,'{"scene": {"view": {"hlookat": "24.194","vlookat" : "2.662","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,135,2,'{"scene": {"view": {"hlookat": "-202.739","vlookat" : "2.552","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,138,2,'{"scene": {"view": {"hlookat": "9.355","vlookat" : "3.941","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,137,2,'{"scene": {"view": {"hlookat": "-175.511","vlookat" : "-7.445","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,480,2,null,null,False,False,null),
(0,'thumb.jpg',53187,481,2,'{"scene": {"view": {"hlookat": "-177.437","vlookat" : "0.826","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,482,2,'{"scene": {"view": {"hlookat": "410.465","vlookat" : "3.028","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,484,2,'{"scene": {"view": {"hlookat": "21.444","vlookat" : "1.236","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,626,2,null,null,False,False,null),
(0,'thumb.jpg',53187,627,2,'{"scene": {"view": {"hlookat": "14.600","vlookat" : "17.322","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,628,2,'{"scene": {"view": {"hlookat": "300.844","vlookat" : "-2.866","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,629,2,'{"scene": {"view": {"hlookat": "387.938","vlookat" : "0.701","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,630,2,'{"scene": {"view": {"hlookat": "-52.703","vlookat" : "-3.026","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,212,2,null,null,False,False,null),
(0,'thumb.jpg',53187,216,2,'{"scene": {"view": {"hlookat": "87.906","vlookat" : "1.919","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,213,2,'{"scene": {"view": {"hlookat": "-207.647","vlookat" : "0.603","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,217,2,'{"scene": {"view": {"hlookat": "-172.846","vlookat" : "2.965","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,605,2,null,null,False,False,null),
(0,'thumb.jpg',53187,606,2,'{"scene": {"view": {"hlookat": "0.665","vlookat" : "30.273","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,607,2,'{"scene": {"view": {"hlookat": "-20.924","vlookat" : "2.645","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,608,2,'{"scene": {"view": {"hlookat": "-383.128","vlookat" : "2.188","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,609,2,'{"scene": {"view": {"hlookat": "54.864","vlookat" : "0.862","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,610,2,null,null,False,False,null),
(0,'thumb.jpg',53187,611,2,'{"scene": {"view": {"hlookat": "-196.795","vlookat" : "-0.564","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,612,2,'{"scene": {"view": {"hlookat": "494.630","vlookat" : "1.917","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,613,2,'{"scene": {"view": {"hlookat": "38.311","vlookat" : "1.067","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,614,2,'{"scene": {"view": {"hlookat": "-209.377","vlookat" : "1.769","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,615,2,'{"scene": {"view": {"hlookat": "-2.172","vlookat" : "-0.981","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,616,2,null,null,False,False,null),
(0,'thumb.jpg',53187,617,2,'{"scene": {"view": {"hlookat": "1.751","vlookat" : "-1.315","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,618,2,'{"scene": {"view": {"hlookat": "522.602","vlookat" : "18.468","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,619,2,'{"scene": {"view": {"hlookat": "8.974","vlookat" : "4.539","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,620,2,null,null,False,False,null),
(0,'thumb.jpg',53187,621,2,'{"scene": {"view": {"hlookat": "-36.214","vlookat" : "0.657","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,622,2,'{"scene": {"view": {"hlookat": "-27.321","vlookat" : "-0.122","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,623,2,'{"scene": {"view": {"hlookat": "-136.969","vlookat" : "-0.653","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,624,2,'{"scene": {"view": {"hlookat": "-165.405","vlookat" : "-1.557","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,355,2,null,null,False,False,null),
(0,'thumb.jpg',53187,625,2,'{"scene": {"view": {"hlookat": "163.907","vlookat" : "-0.072","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,359,2,'{"scene": {"view": {"hlookat": "-8.955","vlookat" : "0.334","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,360,2,'{"scene": {"view": {"hlookat": "-359.095","vlookat" : "16.084","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,356,2,'{"scene": {"view": {"hlookat": "-375.640","vlookat" : "16.373","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,361,2,'{"scene": {"view": {"hlookat": "-305.348","vlookat" : "2.584","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',53187,362,2,'{"scene": {"view": {"hlookat": "-182.031","vlookat" : "3.346","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',53187,363,2,'{"scene": {"view": {"hlookat": "23.090","vlookat" : "1.476","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 53187
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 53187;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 53187);


