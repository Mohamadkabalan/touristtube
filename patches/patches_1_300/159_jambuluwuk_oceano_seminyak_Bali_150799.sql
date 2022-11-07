DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 150799;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 150799;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 150799;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 150799;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',150799,556,1),
('-',150799,557,1),
('Lobby',150799,1,2),
('Reception',150799,15,1),
('1',150799,16,2),
('2',150799,17,3),
('3',150799,18,4),
('Pool access',150799,19,5),
('Business corner',150799,20,6),
('The Club House',150799,28,3),
('1',150799,29,1),
('2',150799,30,2),
('3',150799,31,3),
('Frestro',150799,167,4),
('1',150799,168,1),
('2',150799,169,2),
('3',150799,170,3),
('4',150799,171,4),
('5',150799,197,5),
('Ayung Pool',150799,2,5),
('1',150799,21,1),
('2',150799,92,2),
('Infinity Pool',150799,992,6),
(' - ',150799,993,1),
('Melah Spa',150799,4,7),
('Couples massage room - 1',150799,101,1),
('Couples massage room - 2',150799,102,2),
('Single massage room',150799,103,3),
('Steam room',150799,104,4),
('Plunge pool',150799,105,5),
('Beauty salon',150799,106,6),
('Entrance',150799,671,7),
('Fitness Center',150799,3,8),
('-',150799,80,1),
('Cening Kids Club',150799,84,9),
('1',150799,85,1),
('2',150799,86,2),
('Sadewa - Meeting Room',150799,131,10),
('1',150799,132,1),
('2',150799,252,2),
('Meeting Room Lounge',150799,5,11),
('-',150799,321,1),
('Superior',150799,486,12),
('1',150799,487,1),
('2',150799,488,2),
('Bathroom',150799,491,3),
('Deluxe',150799,133,13),
('1',150799,134,1),
('2',150799,135,2),
('3',150799,136,3),
('4',150799,137,4),
('Bathroom',150799,138,5),
('Deluxe Premiere - Pool Access',150799,480,14),
('1',150799,485,1),
('2',150799,482,2),
('3',150799,483,3),
('4',150799,481,4),
('Bathroom',150799,484,5),
('Junior Suite',150799,967,15),
('1',150799,968,1),
('2',150799,969,2),
('3',150799,970,3),
('4',150799,971,4),
('Bathroom',150799,972,5),
('Jambuluwuk Suite',150799,159,16),
('1',150799,589,1),
('2',150799,160,2),
('3',150799,161,3),
('4',150799,164,4),
('5',150799,262,5),
('Guest bathroom',150799,163,6),
('Master bathroom',150799,162,7);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 150799;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',150799,556,2,null,null,False,False,null),
(0,'thumb.jpg',150799,557,2,'{"scene": {"view": {"hlookat": "5.903","vlookat" : "-12.134","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',150799,1,2,null,null,False,False,null),
(0,'thumb.jpg',150799,15,2,'{"scene": {"view": {"hlookat": "13.767","vlookat" : "-1.313","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',150799,16,2,'{"scene": {"view": {"hlookat": "-13.102","vlookat" : "13.245","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,17,2,'{"scene": {"view": {"hlookat": "-168.496","vlookat" : "3.555","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,18,2,'{"scene": {"view": {"hlookat": "155.926","vlookat" : "-0.626","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,19,2,'{"scene": {"view": {"hlookat": "181.978","vlookat" : "-0.395","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,20,2,'{"scene": {"view": {"hlookat": "61.621","vlookat" : "3.599","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,28,2,null,null,False,False,null),
(0,'thumb.jpg',150799,29,2,'{"scene": {"view": {"hlookat": "-213.247","vlookat" : "9.909","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',150799,30,2,'{"scene": {"view": {"hlookat": "183.151","vlookat" : "3.622","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,31,2,'{"scene": {"view": {"hlookat": "183.935","vlookat" : "26.033","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,167,2,null,null,False,False,null),
(0,'thumb.jpg',150799,168,2,'{"scene": {"view": {"hlookat": "0.492","vlookat" : "-14.429","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,169,2,'{"scene": {"view": {"hlookat": "174.802","vlookat" : "-7.222","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,170,2,'{"scene": {"view": {"hlookat": "74.778","vlookat" : "-1.430","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,171,2,'{"scene": {"view": {"hlookat": "-1.148","vlookat" : "18.034","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,197,2,'{"scene": {"view": {"hlookat": "179.935","vlookat" : "-0.835","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',150799,2,2,null,null,False,False,null),
(0,'thumb.jpg',150799,21,2,'{"scene": {"view": {"hlookat": "24.264","vlookat" : "-6.886","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',150799,92,2,'{"scene": {"view": {"hlookat": "-361.975","vlookat" : "-0.242","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,992,2,null,null,False,False,null),
(0,'thumb.jpg',150799,993,2,'{"scene": {"view": {"hlookat": "178.687","vlookat" : "1.733","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',150799,4,2,null,null,False,False,null),
(0,'thumb.jpg',150799,101,2,'{"scene": {"view": {"hlookat": "178.742","vlookat" : "30.659","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',150799,102,2,'{"scene": {"view": {"hlookat": "1.804","vlookat" : "27.869","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,103,2,'{"scene": {"view": {"hlookat": "36.235","vlookat" : "29.508","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,104,2,'{"scene": {"view": {"hlookat": "163.834","vlookat" : "23.138","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,105,2,'{"scene": {"view": {"hlookat": "11.153","vlookat" : "22.793","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,106,2,'{"scene": {"view": {"hlookat": "62.879","vlookat" : "15.817","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,671,2,'{"scene": {"view": {"hlookat": "198.493","vlookat" : "3.660","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,3,2,null,null,False,False,null),
(0,'thumb.jpg',150799,80,2,'{"scene": {"view": {"hlookat": "-9.254","vlookat" : "10.589","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',150799,84,2,null,null,False,False,null),
(0,'thumb.jpg',150799,85,2,'{"scene": {"view": {"hlookat": "17.217","vlookat" : "15.249","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,86,2,'{"scene": {"view": {"hlookat": "-17.377","vlookat" : "0.984","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,131,2,null,null,False,False,null),
(0,'thumb.jpg',150799,132,2,'{"scene": {"view": {"hlookat": "-0.492","vlookat" : "24.760","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',150799,252,2,'{"scene": {"view": {"hlookat": "189.354","vlookat" : "7.024","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,5,2,null,null,False,False,null),
(0,'thumb.jpg',150799,321,2,'{"scene": {"view": {"hlookat": "43.000","vlookat" : "0.827","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,486,2,null,null,False,False,null),
(0,'thumb.jpg',150799,487,2,'{"scene": {"view": {"hlookat": "0.432","vlookat" : "16.081","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',150799,488,2,'{"scene": {"view": {"hlookat": "-25.574","vlookat" : "8.359","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,491,2,'{"scene": {"view": {"hlookat": "-123.290","vlookat" : "-2.918","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,133,2,null,null,False,False,null),
(0,'thumb.jpg',150799,134,2,'{"scene": {"view": {"hlookat": "5.083","vlookat" : "-4.099","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,135,2,'{"scene": {"view": {"hlookat": "13.118","vlookat" : "5.083","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',150799,136,2,'{"scene": {"view": {"hlookat": "1.447","vlookat" : "28.558","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,137,2,'{"scene": {"view": {"hlookat": "-32.466","vlookat" : "16.397","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,138,2,'{"scene": {"view": {"hlookat": "-37.232","vlookat" : "25.575","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,480,2,null,null,False,False,null),
(0,'thumb.jpg',150799,485,2,'{"scene": {"view": {"hlookat": "164.909","vlookat" : "0.471","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',150799,482,2,'{"scene": {"view": {"hlookat": "-125.233","vlookat" : "2.369","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,483,2,'{"scene": {"view": {"hlookat": "118.557","vlookat" : "11.056","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,481,2,'{"scene": {"view": {"hlookat": "3.905","vlookat" : "3.087","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,484,2,'{"scene": {"view": {"hlookat": "90.009","vlookat" : "7.087","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,967,2,null,null,False,False,null),
(0,'thumb.jpg',150799,968,2,'{"scene": {"view": {"hlookat": "387.276","vlookat" : "28.471","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,969,2,'{"scene": {"view": {"hlookat": "-86.748","vlookat" : "-0.318","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',150799,970,2,'{"scene": {"view": {"hlookat": "4.747","vlookat" : "32.658","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,971,2,'{"scene": {"view": {"hlookat": "-191.214","vlookat" : "4.364","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,972,2,'{"scene": {"view": {"hlookat": "166.884","vlookat" : "10.306","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,159,2,null,null,False,False,null),
(0,'thumb.jpg',150799,589,2,'{"scene": {"view": {"hlookat": "-3.116","vlookat" : "10.822","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,160,2,'{"scene": {"view": {"hlookat": "27.751","vlookat" : "14.280","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,161,2,'{"scene": {"view": {"hlookat": "3.607","vlookat" : "21.642","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,164,2,'{"scene": {"view": {"hlookat": "11.971","vlookat" : "1.312","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,262,2,'{"scene": {"view": {"hlookat": "15.413","vlookat" : "15.903","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',150799,163,2,'{"scene": {"view": {"hlookat": "-113.331","vlookat" : "13.106","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',150799,162,2,'{"scene": {"view": {"hlookat": "-44.669","vlookat" : "5.595","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 150799
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 150799;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 150799);

