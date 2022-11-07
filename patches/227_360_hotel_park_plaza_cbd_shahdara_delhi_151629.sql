DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 151629;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 151629;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 151629;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 151629;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',151629,79,1),
('1',151629,130,1),
('2',151629,193,2),
('Lobby',151629,1,2),
('1',151629,15,1),
('2',151629,16,2),
('Chingari',151629,28,3),
('1',151629,29,1),
('2',151629,30,2),
('3',151629,31,3),
('4',151629,32,4),
('5',151629,110,5),
('Promenade',151629,167,4),
('1',151629,168,1),
('2',151629,169,2),
('3',151629,170,3),
('4',151629,171,4),
('Rouge',151629,173,5),
('1',151629,174,1),
('2',151629,175,2),
('3',151629,176,3),
('Sapphire',151629,188,6),
('1',151629,189,1),
('2',151629,190,2),
('3',151629,191,3),
('Shish',151629,205,7),
('1',151629,206,1),
('2',151629,207,2),
('3',151629,208,3),
('Emerald',151629,131,8),
('1',151629,132,1),
('2',151629,252,2),
('3',151629,253,3),
('Gym',151629,3,9),
(' - ',151629,80,1),
('Pool',151629,2,10),
(' - ',151629,21,1),
('Senate',151629,293,11),
('1',151629,297,1),
('2',151629,298,2),
('Spa',151629,4,12),
('1',151629,101,1),
('2',151629,102,2),
('Deluxe room',151629,133,13),
('1',151629,134,1),
('2',151629,138,2),
('Superior room',151629,486,14),
('1',151629,487,1),
('2',151629,488,2),
('Bathroom',151629,489,3),
('Executive room',151629,144,15),
('1',151629,145,1),
('2',151629,146,2),
('3',151629,147,3),
('4',151629,148,4),
('Presidential Suite',151629,1104,16),
('1',151629,1105,1),
('2',151629,1106,2),
('3',151629,1107,3),
('4',151629,1108,4),
('5',151629,1109,5),
('6',151629,1110,6),
('7',151629,1111,7);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 151629;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',151629,79,2,null,null,False,False,null),
(0,'thumb.jpg',151629,130,2,'{"scene": {"view": {"hlookat": "26.402","vlookat" : "-19.998","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',151629,193,2,'{"scene": {"view": {"hlookat": "359.515","vlookat" : "2.704","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,1,2,null,null,False,False,null),
(0,'thumb.jpg',151629,15,2,'{"scene": {"view": {"hlookat": "-45.156","vlookat" : "5.910","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',151629,16,2,'{"scene": {"view": {"hlookat": "362.210","vlookat" : "5.276","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,28,2,null,null,False,False,null),
(0,'thumb.jpg',151629,29,2,'{"scene": {"view": {"hlookat": "11.314","vlookat" : "1.312","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,30,2,'{"scene": {"view": {"hlookat": "163.754","vlookat" : "0.670","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',151629,31,2,'{"scene": {"view": {"hlookat": "-35.407","vlookat" : "2.294","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,32,2,'{"scene": {"view": {"hlookat": "301.836","vlookat" : "5.895","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,110,2,'{"scene": {"view": {"hlookat": "18.362","vlookat" : "12.298","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,167,2,null,null,False,False,null),
(0,'thumb.jpg',151629,168,2,'{"scene": {"view": {"hlookat": "-38.553","vlookat" : "9.828","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',151629,169,2,'{"scene": {"view": {"hlookat": "0.656","vlookat" : "17.544","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,170,2,'{"scene": {"view": {"hlookat": "-30.825","vlookat" : "0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,171,2,'{"scene": {"view": {"hlookat": "1.640","vlookat" : "8.363","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,173,2,null,null,False,False,null),
(0,'thumb.jpg',151629,174,2,'{"scene": {"view": {"hlookat": "19.020","vlookat" : "-1.148","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,175,2,'{"scene": {"view": {"hlookat": "1.804","vlookat" : "-0.328","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,176,2,'{"scene": {"view": {"hlookat": "-110.999","vlookat" : "0.462","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',151629,188,2,null,null,False,False,null),
(0,'thumb.jpg',151629,189,2,'{"scene": {"view": {"hlookat": "369.080","vlookat" : "-0.082","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',151629,190,2,'{"scene": {"view": {"hlookat": "1.640","vlookat" : "0.164","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,191,2,'{"scene": {"view": {"hlookat": "341.423","vlookat" : "-0.855","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,205,2,null,null,False,False,null),
(0,'thumb.jpg',151629,206,2,'{"scene": {"view": {"hlookat": "-1.759","vlookat" : "8.342","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,207,2,'{"scene": {"view": {"hlookat": "1.968","vlookat" : "10.823","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,208,2,'{"scene": {"view": {"hlookat": "39.679","vlookat" : "7.052","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',151629,131,2,null,null,False,False,null),
(0,'thumb.jpg',151629,132,2,'{"scene": {"view": {"hlookat": "2.624","vlookat" : "17.053","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,252,2,'{"scene": {"view": {"hlookat": "365.100","vlookat" : "14.253","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',151629,253,2,'{"scene": {"view": {"hlookat": "5.403","vlookat" : "18.362","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,3,2,null,null,False,False,null),
(0,'thumb.jpg',151629,80,2,'{"scene": {"view": {"hlookat": "342.457","vlookat" : "-0.103","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',151629,2,2,null,null,False,False,null),
(0,'thumb.jpg',151629,21,2,'{"scene": {"view": {"hlookat": "1.441","vlookat" : "17.390","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',151629,293,2,null,null,False,False,null),
(0,'thumb.jpg',151629,297,2,'{"scene": {"view": {"hlookat": "8.773","vlookat" : "5.593","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',151629,298,2,'{"scene": {"view": {"hlookat": "335.956","vlookat" : "0.521","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,4,2,null,null,False,False,null),
(0,'thumb.jpg',151629,101,2,'{"scene": {"view": {"hlookat": "90.440","vlookat" : "26.397","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,102,2,'{"scene": {"view": {"hlookat": "-0.656","vlookat" : "23.920","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',151629,133,2,null,null,False,False,null),
(0,'thumb.jpg',151629,134,2,'{"scene": {"view": {"hlookat": "17.059","vlookat" : "1.801","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',151629,138,2,'{"scene": {"view": {"hlookat": "-380.373","vlookat" : "21.084","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,486,2,null,null,False,False,null),
(0,'thumb.jpg',151629,487,2,'{"scene": {"view": {"hlookat": "-33.604","vlookat" : "16.065","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',151629,488,2,'{"scene": {"view": {"hlookat": "33.448","vlookat" : "24.756","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,489,2,'{"scene": {"view": {"hlookat": "-229.318","vlookat" : "10.972","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,144,2,null,null,False,False,null),
(0,'thumb.jpg',151629,145,2,'{"scene": {"view": {"hlookat": "32.786","vlookat" : "2.787","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,146,2,'{"scene": {"view": {"hlookat": "-0.832","vlookat" : "28.167","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,147,2,'{"scene": {"view": {"hlookat": "-27.545","vlookat" : "15.247","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',151629,148,2,'{"scene": {"view": {"hlookat": "98.820","vlookat" : "17.438","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,1104,2,null,null,False,False,null),
(0,'thumb.jpg',151629,1105,2,'{"scene": {"view": {"hlookat": "27.548","vlookat" : "1.476","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,1106,2,'{"scene": {"view": {"hlookat": "189.113","vlookat" : "0.609","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,1107,2,'{"scene": {"view": {"hlookat": "153.317","vlookat" : "-0.378","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',151629,1108,2,'{"scene": {"view": {"hlookat": "153.466","vlookat" : "-0.988","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,1109,2,'{"scene": {"view": {"hlookat": "168.274","vlookat" : "1.088","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',151629,1110,2,'{"scene": {"view": {"hlookat": "21.972","vlookat" : "20.496","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',151629,1111,2,'{"scene": {"view": {"hlookat": "215.541","vlookat" : "18.774","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 151629
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 151629;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 151629);

