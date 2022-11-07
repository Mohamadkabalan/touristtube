INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',101658,79,1),
('1',101658,130,1),
('2',101658,193,2),
('3',101658,194,3),
('4',101658,195,4),
('Lobby',101658,1,2),
('1',101658,15,1),
('2',101658,16,2),
('3',101658,17,3),
('Garden Brew Café',101658,8,3),
('1',101658,33,1),
('2',101658,34,2),
('Al Nakhla Restaurant',101658,28,4),
('1',101658,29,1),
('2',101658,30,2),
('Club Acacia',101658,167,5),
('1',101658,168,1),
('2',101658,169,2),
('3',101658,170,3),
('4',101658,171,4),
('Swimming Pool and Tides Pool',101658,2,6),
('1',101658,21,1),
('2',101658,92,2),
('Oxygen Gym',101658,3,7),
('1',101658,80,1),
('2',101658,81,2),
('Ozone Spa',101658,4,8),
('1',101658,101,1),
('2',101658,102,2),
('Bedroom Suite',101658,159,9),
('1',101658,160,1),
('2',101658,164,2),
('3',101658,162,3),
('Standard Room With Pool Side View',101658,36,10),
('1',101658,37,1),
('2',101658,38,2),
('Apartment',101658,123,11),
('1',101658,126,1),
('2',101658,124,2),
('3',101658,125,3),
('Standard Twin Room',101658,246,12),
('1',101658,247,1),
('2',101658,248,2);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 101658;





INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',101658,79,2,null,null,False,False,null),
(0,'thumb.jpg',101658,130,2,'{"scene": {"view": {"hlookat": "0.641","vlookat" : "0.000","fov" : "139.381"}}}',null,True,True,1),
(0,'thumb.jpg',101658,193,2,'{"scene": {"view": {"hlookat": "-8.236","vlookat" : "0.583","fov" : "139.709"}}}',null,2,2,null),
(0,'thumb.jpg',101658,194,2,'{"scene": {"view": {"hlookat": "0.428","vlookat" : "-9.930","fov" : "139.764"}}}',null,3,3,null),
(0,'thumb.jpg',101658,195,2,'{"scene": {"view": {"hlookat": "-7.306","vlookat" : "-2.387","fov" : "139.436"}}}',null,4,4,null),
(0,'thumb.jpg',101658,1,2,null,null,False,False,null),
(0,'thumb.jpg',101658,15,2,'{"scene": {"view": {"hlookat": "0.246","vlookat" : "0.000","fov" : "139.764"}}}',null,True,True,null),
(0,'thumb.jpg',101658,16,2,'{"scene": {"view": {"hlookat": "0.394","vlookat" : "0.000","fov" : "139.606"}}}',null,2,2,2),
(0,'thumb.jpg',101658,17,2,'{"scene": {"view": {"hlookat": "0.394","vlookat" : "0.000","fov" : "139.606"}}}',null,3,3,null),
(0,'thumb.jpg',101658,8,2,null,null,False,False,null),
(0,'thumb.jpg',101658,33,2,'{"scene": {"view": {"hlookat": "39.638","vlookat" : "0.740","fov" : "138.618"}}}',null,True,True,3),
(0,'thumb.jpg',101658,34,2,'{"scene": {"view": {"hlookat": "31.688","vlookat" : "4.714","fov" : "139.139"}}}',null,2,2,null),
(0,'thumb.jpg',101658,28,2,null,null,False,False,null),
(0,'thumb.jpg',101658,29,2,'{"scene": {"view": {"hlookat": "121.540","vlookat" : "0.740","fov" : "138.931"}}}',null,True,True,null),
(0,'thumb.jpg',101658,30,2,'{"scene": {"view": {"hlookat": "39.097","vlookat" : "4.024","fov" : "139.106"}}}',null,2,2,4),
(0,'thumb.jpg',101658,167,2,null,null,False,False,null),
(0,'thumb.jpg',101658,168,2,'{"scene": {"view": {"hlookat": "-147.097","vlookat" : "0.910","fov" : "138.074"}}}',null,True,True,null),
(0,'thumb.jpg',101658,169,2,'{"scene": {"view": {"hlookat": "-146.611","vlookat" : "2.819","fov" : "138.266"}}}',null,2,2,null),
(0,'thumb.jpg',101658,170,2,'{"scene": {"view": {"hlookat": "-35.743","vlookat" : "1.811","fov" : "139.353"}}}',null,3,3,5),
(0,'thumb.jpg',101658,171,2,'{"scene": {"view": {"hlookat": "-31.955","vlookat" : "1.300","fov" : "138.894"}}}',null,4,4,null),
(0,'thumb.jpg',101658,2,2,null,null,False,False,null),
(0,'thumb.jpg',101658,21,2,'{"scene": {"view": {"hlookat": "61.791","vlookat" : "-0.530","fov" : "139.139"}}}',null,True,True,6),
(0,'thumb.jpg',101658,92,2,'{"scene": {"view": {"hlookat": "-21.119","vlookat" : "-1.064","fov" : "138.659"}}}',null,2,2,null),
(0,'thumb.jpg',101658,3,2,null,null,False,False,null),
(0,'thumb.jpg',101658,80,2,'{"scene": {"view": {"hlookat": "0.894","vlookat" : "0.000","fov" : "139.106"}}}',null,True,True,7),
(0,'thumb.jpg',101658,81,2,'{"scene": {"view": {"hlookat": "116.405","vlookat" : "4.597","fov" : "139.353"}}}',null,2,2,null),
(0,'thumb.jpg',101658,4,2,null,null,False,False,null),
(0,'thumb.jpg',101658,101,2,'{"scene": {"view": {"hlookat": "3.912","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,8),
(0,'thumb.jpg',101658,102,2,'{"scene": {"view": {"hlookat": "0.351","vlookat" : "0.000","fov" : "139.649"}}}',null,2,2,null),
(0,'thumb.jpg',101658,159,2,null,null,False,False,null),
(0,'thumb.jpg',101658,160,2,'{"scene": {"view": {"hlookat": "-144.302","vlookat" : "-2.596","fov" : "139.265"}}}',null,True,True,9),
(0,'thumb.jpg',101658,164,2,'{"scene": {"view": {"hlookat": "26.855","vlookat" : "1.213","fov" : "139.381"}}}',null,2,2,null),
(0,'thumb.jpg',101658,162,2,'{"scene": {"view": {"hlookat": "31.548","vlookat" : "13.543","fov" : "139.606"}}}',null,3,3,null),
(0,'thumb.jpg',101658,36,2,null,null,False,False,null),
(0,'thumb.jpg',101658,37,2,'{"scene": {"view": {"hlookat": "0.469","vlookat" : "0.000","fov" : "139.537"}}}',null,True,True,10),
(0,'thumb.jpg',101658,38,2,'{"scene": {"view": {"hlookat": "0.372","vlookat" : "0.000","fov" : "139.628"}}}',null,2,2,null),
(0,'thumb.jpg',101658,123,2,null,null,False,False,null),
(0,'thumb.jpg',101658,126,2,'{"scene": {"view": {"hlookat": "0.513","vlookat" : "0.000","fov" : "139.487"}}}',null,True,True,null),
(0,'thumb.jpg',101658,124,2,'{"scene": {"view": {"hlookat": "0.439","vlookat" : "0.000","fov" : "139.561"}}}',null,2,2,11),
(0,'thumb.jpg',101658,125,2,'{"scene": {"view": {"hlookat": "0.439","vlookat" : "0.000","fov" : "139.561"}}}',null,3,3,null),
(0,'thumb.jpg',101658,246,2,null,null,False,False,null),
(0,'thumb.jpg',101658,247,2,'{"scene": {"view": {"hlookat": "-152.437","vlookat" : "-1.145","fov" : "139.709"}}}',null,True,True,12),
(0,'thumb.jpg',101658,248,2,'{"scene": {"view": {"hlookat": "-22.057","vlookat" : "0.431","fov" : "139.265"}}}',null,2,2,null);






DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 101658
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 101658;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 101658);



