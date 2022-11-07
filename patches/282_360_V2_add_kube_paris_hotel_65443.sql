DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 65443;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 65443;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 65443;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 65443;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',65443,556,1),
(' - ',65443,557,0),
('Entrance Terrace',65443,79,2),
('View1',65443,130,1),
('View2',65443,193,2),
('View3',65443,194,3),
('View4',65443,195,4),
('Reception',65443,338,3),
(' - ',65443,339,1),
('Lobby - Restaurant',65443,1,4),
('View 1',65443,15,1),
('View 2',65443,16,2),
('View 3',65443,17,3),
('View 4',65443,18,4),
('View 5',65443,19,5),
('View 6',65443,20,6),
('View 7',65443,90,7),
('View 8',65443,91,8),
('View 9',65443,583,9),
('View 10',65443,584,10),
('Breakfast',65443,6,5),
(' - ',65443,25,1),
('Ice Kube Bar',65443,7,6),
('Lobby Entrance',65443,152,1),
('Rooms Entrance',65443,153,2),
('View 1',65443,154,3),
('View 2',65443,155,4),
('View 3',65443,156,5),
('View 4',65443,157,6),
('Meeting Room - Atelier',65443,131,7),
('View 1',65443,132,1),
('View 2',65443,252,2),
('View 3',65443,253,3),
('View 4',65443,254,4),
('Entrance',65443,255,5),
('Gym',65443,3,8),
('View 1',65443,80,1),
('View 2',65443,81,2),
('Bathroom - View 1',65443,82,3),
('Bathroom - View 2',65443,83,4),
('Entrance',65443,585,5),
('M Superior',65443,486,9),
('View 1',65443,487,1),
('View 2',65443,488,2),
('View 3',65443,489,3),
('View 4',65443,490,4),
('View 5',65443,492,5),
('L Room',65443,546,10),
('View 1',65443,547,1),
('View 2',65443,548,2),
('View 3',65443,549,3),
('View 4',65443,587,4),
('View 5',65443,588,5),
('Nordik Suite',65443,159,11),
('View 1',65443,589,1),
('View 2',65443,493,2),
('View 3',65443,162,3),
('View 4',65443,164,4),
('View 5',65443,161,5),
('View 6',65443,495,6),
('XL Junior Suite',65443,212,12),
('View 1',65443,213,1),
('View 2',65443,217,2),
('View 3',65443,214,3),
('View 4',65443,216,4),
('View 5',65443,529,5),
('XXL Terrasse - suite',65443,590,13),
('View 1',65443,591,1),
('View 2',65443,592,2),
('Bathroom',65443,596,3),
('View 3',65443,593,4),
('View 4',65443,594,5),
('View 5',65443,595,6);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 65443;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 65443;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 65443;

INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',65443,556,2,null,null,False,False,null),
(0,'thumb.jpg',65443,557,2,'{"scene": {"view": {"hlookat": "183.299","vlookat" : "-16.261","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',65443,79,2,null,null,False,False,null),
(0,'thumb.jpg',65443,130,2,'{"scene": {"view": {"hlookat": "168.216","vlookat" : "-5.277","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,193,2,'{"scene": {"view": {"hlookat": "-32.246","vlookat" : "18.193","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',65443,194,2,'{"scene": {"view": {"hlookat": "253.180","vlookat" : "16.421","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,195,2,'{"scene": {"view": {"hlookat": "-186.428","vlookat" : "1.016","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,338,2,null,null,False,False,null),
(0,'thumb.jpg',65443,339,2,'{"scene": {"view": {"hlookat": "121.883","vlookat" : "29.743","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',65443,1,2,null,null,False,False,null),
(0,'thumb.jpg',65443,15,2,'{"scene": {"view": {"hlookat": "3.669","vlookat" : "28.462","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,16,2,'{"scene": {"view": {"hlookat": "-0.985","vlookat" : "1.308","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',65443,17,2,'{"scene": {"view": {"hlookat": "-7.051","vlookat" : "36.062","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,18,2,'{"scene": {"view": {"hlookat": "132.208","vlookat" : "32.028","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,19,2,'{"scene": {"view": {"hlookat": "-60.147","vlookat" : "13.612","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,20,2,'{"scene": {"view": {"hlookat": "-146.559","vlookat" : "-4.159","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,90,2,'{"scene": {"view": {"hlookat": "-34.560","vlookat" : "9.834","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,91,2,'{"scene": {"view": {"hlookat": "-29.255","vlookat" : "4.598","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,583,2,'{"scene": {"view": {"hlookat": "4.656","vlookat" : "15.027","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,584,2,'{"scene": {"view": {"hlookat": "-418.738","vlookat" : "5.661","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,6,2,null,null,False,False,null),
(0,'thumb.jpg',65443,25,2,'{"scene": {"view": {"hlookat": "90.789","vlookat" : "2.904","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',65443,7,2,null,null,False,False,null),
(0,'thumb.jpg',65443,152,2,'{"scene": {"view": {"hlookat": "177.572","vlookat" : "-0.677","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',65443,153,2,'{"scene": {"view": {"hlookat": "-187.191","vlookat" : "3.446","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,154,2,'{"scene": {"view": {"hlookat": "-142.749","vlookat" : "14.943","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,155,2,'{"scene": {"view": {"hlookat": "-18.681","vlookat" : "-0.329","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,156,2,'{"scene": {"view": {"hlookat": "2.460","vlookat" : "9.836","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',65443,157,2,'{"scene": {"view": {"hlookat": "-9.027","vlookat" : "20.912","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,131,2,null,null,False,False,null),
(0,'thumb.jpg',65443,132,2,'{"scene": {"view": {"hlookat": "91.154","vlookat" : "11.465","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,252,2,'{"scene": {"view": {"hlookat": "-13.266","vlookat" : "25.725","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',65443,253,2,'{"scene": {"view": {"hlookat": "-159.547","vlookat" : "11.385","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,254,2,'{"scene": {"view": {"hlookat": "171.722","vlookat" : "0.638","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,255,2,'{"scene": {"view": {"hlookat": "26.874","vlookat" : "-1.803","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',65443,3,2,null,null,False,False,null),
(0,'thumb.jpg',65443,80,2,'{"scene": {"view": {"hlookat": "23.276","vlookat" : "13.761","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,81,2,'{"scene": {"view": {"hlookat": "-188.333","vlookat" : "4.092","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',65443,82,2,'{"scene": {"view": {"hlookat": "30.976","vlookat" : "-2.301","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,83,2,'{"scene": {"view": {"hlookat": "132.442","vlookat" : "0.737","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,585,2,'{"scene": {"view": {"hlookat": "22.350","vlookat" : "-0.656","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,486,2,null,null,False,False,null),
(0,'thumb.jpg',65443,487,2,'{"scene": {"view": {"hlookat": "-18.528","vlookat" : "20.166","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',65443,488,2,'{"scene": {"view": {"hlookat": "-60.645","vlookat" : "13.285","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,489,2,'{"scene": {"view": {"hlookat": "-72.953","vlookat" : "0.814","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,490,2,'{"scene": {"view": {"hlookat": "-38.999","vlookat" : "29.333","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,492,2,'{"scene": {"view": {"hlookat": "-31.802","vlookat" : "6.719","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,546,2,null,null,False,False,null),
(0,'thumb.jpg',65443,547,2,'{"scene": {"view": {"hlookat": "102.412","vlookat" : "8.583","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,548,2,'{"scene": {"view": {"hlookat": "-9.018","vlookat" : "14.585","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,549,2,'{"scene": {"view": {"hlookat": "196.340","vlookat" : "8.910","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',65443,587,2,'{"scene": {"view": {"hlookat": "11.310","vlookat" : "19.347","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,588,2,'{"scene": {"view": {"hlookat": "22.300","vlookat" : "21.910","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,159,2,null,null,False,False,null),
(0,'thumb.jpg',65443,589,2,'{"scene": {"view": {"hlookat": "-5.462","vlookat" : "-1.147","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',65443,493,2,'{"scene": {"view": {"hlookat": "162.212","vlookat" : "-0.517","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',65443,162,2,'{"scene": {"view": {"hlookat": "-20.267","vlookat" : "42.878","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,164,2,'{"scene": {"view": {"hlookat": "-30.498","vlookat" : "12.625","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,161,2,'{"scene": {"view": {"hlookat": "-13.933","vlookat" : "11.643","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',65443,495,2,'{"scene": {"view": {"hlookat": "168.813","vlookat" : "0.848","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,212,2,null,null,False,False,null),
(0,'thumb.jpg',65443,213,2,'{"scene": {"view": {"hlookat": "-3.280","vlookat" : "0.156","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,217,2,'{"scene": {"view": {"hlookat": "-50.278","vlookat" : "18.340","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,214,2,'{"scene": {"view": {"hlookat": "167.546","vlookat" : "0.731","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,216,2,'{"scene": {"view": {"hlookat": "58.859","vlookat" : "15.401","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',65443,529,2,'{"scene": {"view": {"hlookat": "-75.421","vlookat" : "26.050","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,590,2,null,null,False,False,null),
(0,'thumb.jpg',65443,591,2,'{"scene": {"view": {"hlookat": "1.354","vlookat" : "6.382","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,592,2,'{"scene": {"view": {"hlookat": "143.948","vlookat" : "12.283","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',65443,596,2,'{"scene": {"view": {"hlookat": "6.231","vlookat" : "28.853","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,593,2,'{"scene": {"view": {"hlookat": "-50.220","vlookat" : "3.659","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,594,2,'{"scene": {"view": {"hlookat": "54.554","vlookat" : "26.107","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',65443,595,2,'{"scene": {"view": {"hlookat": "91.680","vlookat" : "12.133","fov" : "140"}}}',null,False,False,null);



DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 65443
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 65443;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 65443);
