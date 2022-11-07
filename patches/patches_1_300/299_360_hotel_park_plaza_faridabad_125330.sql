DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 125330;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 125330;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 125330;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 125330;

UPDATE `cms_hotel` SET `iso3_country_code`='IND', `country_code`='IN' WHERE `id`='125330';


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',125330,79,1),
('-',125330,130,1),
('Lobby',125330,1,2),
('1',125330,15,1),
('2',125330,16,2),
('Geoffrey bar',125330,231,3),
('1',125330,232,1),
('2',125330,233,2),
('3',125330,234,3),
('4',125330,235,4),
('5',125330,236,5),
('Veranda',125330,6,4),
('1',125330,25,1),
('2',125330,26,2),
('3',125330,27,3),
('4',125330,602,4),
('5',125330,603,5),
('Spa',125330,4,5),
('1',125330,101,1),
('2',125330,102,2),
('3',125330,103,3),
('4',125330,104,4),
('Gym',125330,3,6),
('1',125330,80,1),
('2',125330,81,2),
('Swimming pool',125330,2,7),
('-',125330,21,1),
('Banquet hall',125330,131,8),
('1',125330,132,1),
('2',125330,252,2),
('3',125330,253,3),
('Superior twin',125330,246,9),
('1',125330,247,1),
('Bathroom',125330,251,2),
('Deluxe room',125330,568,10),
('1',125330,569,1),
('Bathroom',125330,572,2),
('Superior king',125330,486,11),
('1',125330,487,1),
('Bathroom',125330,491,2),
('Suite',125330,616,12),
('1',125330,617,1),
('2',125330,618,2),
('Bathroom',125330,619,3);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 125330;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',125330,79,2,null,null,False,False,null),
(0,'thumb.jpg',125330,130,2,'{"scene": {"view": {"hlookat": "160.195","vlookat" : "-22.052","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',125330,1,2,null,null,False,False,null),
(0,'thumb.jpg',125330,15,2,'{"scene": {"view": {"hlookat": "323.081","vlookat" : "-5.898","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',125330,16,2,'{"scene": {"view": {"hlookat": "0.806","vlookat" : "-0.768","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',125330,231,2,null,null,False,False,null),
(0,'thumb.jpg',125330,232,2,'{"scene": {"view": {"hlookat": "361.525","vlookat" : "1.272","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,233,2,'{"scene": {"view": {"hlookat": "-1.350","vlookat" : "-0.559","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',125330,234,2,'{"scene": {"view": {"hlookat": "-117.134","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,235,2,'{"scene": {"view": {"hlookat": "-89.085","vlookat" : "4.027","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',125330,236,2,'{"scene": {"view": {"hlookat": "269.655","vlookat" : "21.428","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,6,2,null,null,False,False,null),
(0,'thumb.jpg',125330,25,2,'{"scene": {"view": {"hlookat": "-8.221","vlookat" : "0.281","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',125330,26,2,'{"scene": {"view": {"hlookat": "-360.492","vlookat" : "6.704","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,27,2,'{"scene": {"view": {"hlookat": "-268.518","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,602,2,'{"scene": {"view": {"hlookat": "-88.430","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,603,2,'{"scene": {"view": {"hlookat": "-174.251","vlookat" : "-6.889","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,4,2,null,null,False,False,null),
(0,'thumb.jpg',125330,101,2,'{"scene": {"view": {"hlookat": "359.708","vlookat" : "8.717","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,102,2,'{"scene": {"view": {"hlookat": "1.271","vlookat" : "47.371","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',125330,103,2,'{"scene": {"view": {"hlookat": "319.481","vlookat" : "6.257","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',125330,104,2,'{"scene": {"view": {"hlookat": "-356.692","vlookat" : "14.611","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,3,2,null,null,False,False,null),
(0,'thumb.jpg',125330,80,2,'{"scene": {"view": {"hlookat": "136.525","vlookat" : "4.880","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',125330,81,2,'{"scene": {"view": {"hlookat": "70.876","vlookat" : "1.640","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,2,2,null,null,False,False,null),
(0,'thumb.jpg',125330,21,2,'{"scene": {"view": {"hlookat": "-380.122","vlookat" : "0.130","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',125330,131,2,null,null,False,False,null),
(0,'thumb.jpg',125330,132,2,'{"scene": {"view": {"hlookat": "-360.650","vlookat" : "25.050","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',125330,252,2,'{"scene": {"view": {"hlookat": "52.038","vlookat" : "7.379","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',125330,253,2,'{"scene": {"view": {"hlookat": "-108.018","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,246,2,null,null,False,False,null),
(0,'thumb.jpg',125330,247,2,'{"scene": {"view": {"hlookat": "19.376","vlookat" : "11.713","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',125330,251,2,'{"scene": {"view": {"hlookat": "-85.416","vlookat" : "19.511","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,568,2,null,null,False,False,null),
(0,'thumb.jpg',125330,569,2,'{"scene": {"view": {"hlookat": "21.935","vlookat" : "3.469","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',125330,572,2,'{"scene": {"view": {"hlookat": "31.134","vlookat" : "21.315","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,486,2,null,null,False,False,null),
(0,'thumb.jpg',125330,487,2,'{"scene": {"view": {"hlookat": "14.757","vlookat" : "11.954","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',125330,491,2,'{"scene": {"view": {"hlookat": "-77.551","vlookat" : "14.920","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',125330,616,2,null,null,False,False,null),
(0,'thumb.jpg',125330,617,2,'{"scene": {"view": {"hlookat": "-18.600","vlookat" : "8.597","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',125330,618,2,'{"scene": {"view": {"hlookat": "-20.825","vlookat" : "4.919","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',125330,619,2,'{"scene": {"view": {"hlookat": "18.641","vlookat" : "11.358","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 125330
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 125330;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 125330);
