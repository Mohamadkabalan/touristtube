INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (1350, 'Terrace', '9', '947', '999');

DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 67011;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 67011;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 67011;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 67011;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',67011,79,1),
('1',67011,130,1),
('2',67011,193,2),
('Lobby',67011,1,2),
('1',67011,15,1),
('Reception',67011,16,2),
('Circle café lounge',67011,94,3),
('-',67011,95,1),
('Double decker',67011,28,4),
('1',67011,29,1),
('2',67011,30,2),
('3',67011,31,3),
('4',67011,32,4),
('Pergolas',67011,167,5),
('1',67011,168,1),
('2',67011,169,2),
('3',67011,170,3),
('4',67011,171,4),
('Tabule',67011,173,6),
('1',67011,174,1),
('2',67011,175,2),
('3',67011,176,3),
('Flow swimming pool',67011,2,7),
('1',67011,186,1),
('2',67011,187,2),
('Flow health club & wellness',67011,4,8),
('1',67011,101,1),
('2',67011,103,2),
('3',67011,104,3),
('Massage Room',67011,105,4),
('Gym',67011,3,9),
('-',67011,80,1),
('Center of the complex',67011,464,10),
('-',67011,465,1),
('Residences area',67011,941,11),
('1',67011,942,1),
('2',67011,943,2),
('Tennis court',67011,277,12),
('-',67011,279,1),
('Classic room',67011,752,13),
('1',67011,753,1),
('Bathroom',67011,754,2),
('Premium room',67011,947,14),
('Terrace',67011,1350,1),
('Living',67011,948,2),
('Room A',67011,950,3),
('Room B',67011,951,4),
('Bathroom',67011,949,5),
('Guest Bathroom',67011,952,6),
('Presidential suite',67011,355,15),
('Terrace',67011,367,1),
('Living - 1',67011,356,2),
('Living - 2',67011,359,3),
('Living - 3',67011,360,4),
('Room A',67011,361,5),
('Room A - Bathroom',67011,363,6),
('Room B',67011,364,7),
('Room B - Bathroom',67011,366,8),
('Kitchen',67011,357,9);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 67011;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',67011,79,2,null,null,False,False,null),
(0,'thumb.jpg',67011,130,2,'{"scene": {"view": {"hlookat": "27.464","vlookat" : "-27.424","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',67011,193,2,'{"scene": {"view": {"hlookat": "3.703","vlookat" : "-6.067","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,1,2,null,null,False,False,null),
(0,'thumb.jpg',67011,15,2,'{"scene": {"view": {"hlookat": "-178.562","vlookat" : "9.627","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',67011,16,2,'{"scene": {"view": {"hlookat": "-187.950","vlookat" : "4.339","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,94,2,null,null,False,False,null),
(0,'thumb.jpg',67011,95,2,'{"scene": {"view": {"hlookat": "545.613","vlookat" : "9.182","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',67011,28,2,null,null,False,False,null),
(0,'thumb.jpg',67011,29,2,'{"scene": {"view": {"hlookat": "22.908","vlookat" : "-5.588","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,30,2,'{"scene": {"view": {"hlookat": "531.181","vlookat" : "10.623","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,31,2,'{"scene": {"view": {"hlookat": "-56.078","vlookat" : "9.182","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,32,2,'{"scene": {"view": {"hlookat": "0.347","vlookat" : "-4.427","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',67011,167,2,null,null,False,False,null),
(0,'thumb.jpg',67011,168,2,'{"scene": {"view": {"hlookat": "60.606","vlookat" : "9.171","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,169,2,'{"scene": {"view": {"hlookat": "45.743","vlookat" : "8.362","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',67011,170,2,'{"scene": {"view": {"hlookat": "-130.157","vlookat" : "4.329","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,171,2,'{"scene": {"view": {"hlookat": "-1.879","vlookat" : "-5.590","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,173,2,null,null,False,False,null),
(0,'thumb.jpg',67011,174,2,'{"scene": {"view": {"hlookat": "2860.635","vlookat" : "4.591","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',67011,175,2,'{"scene": {"view": {"hlookat": "179.738","vlookat" : "3.603","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,176,2,'{"scene": {"view": {"hlookat": "520.781","vlookat" : "-1.432","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,2,2,null,null,False,False,null),
(0,'thumb.jpg',67011,186,2,'{"scene": {"view": {"hlookat": "-1.278","vlookat" : "-13.299","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,187,2,'{"scene": {"view": {"hlookat": "-23.448","vlookat" : "-16.398","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',67011,4,2,null,null,False,False,null),
(0,'thumb.jpg',67011,101,2,'{"scene": {"view": {"hlookat": "-176.405","vlookat" : "13.042","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,103,2,'{"scene": {"view": {"hlookat": "147.564","vlookat" : "20.636","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',67011,104,2,'{"scene": {"view": {"hlookat": "663.339","vlookat" : "12.462","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,105,2,'{"scene": {"view": {"hlookat": "-44.266","vlookat" : "30.168","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,3,2,null,null,False,False,null),
(0,'thumb.jpg',67011,80,2,'{"scene": {"view": {"hlookat": "132.806","vlookat" : "7.655","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',67011,464,2,null,null,False,False,null),
(0,'thumb.jpg',67011,465,2,'{"scene": {"view": {"hlookat": "-2.278","vlookat" : "-4.220","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',67011,941,2,null,null,False,False,null),
(0,'thumb.jpg',67011,942,2,'{"scene": {"view": {"hlookat": "-110.563","vlookat" : "-6.192","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,943,2,'{"scene": {"view": {"hlookat": "-725.758","vlookat" : "-1.623","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',67011,277,2,null,null,False,False,null),
(0,'thumb.jpg',67011,279,2,'{"scene": {"view": {"hlookat": "-90.777","vlookat" : "-15.970","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',67011,752,2,null,null,False,False,null),
(0,'thumb.jpg',67011,753,2,'{"scene": {"view": {"hlookat": "347.919","vlookat" : "18.311","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',67011,754,2,'{"scene": {"view": {"hlookat": "0.079","vlookat" : "20.915","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,947,2,null,null,False,False,null),
(0,'thumb.jpg',67011,1350,2,'{"scene": {"view": {"hlookat": "-173.601","vlookat" : "3.430","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',67011,948,2,'{"scene": {"view": {"hlookat": "-383.752","vlookat" : "8.543","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',67011,950,2,'{"scene": {"view": {"hlookat": "-4.575","vlookat" : "13.441","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,951,2,'{"scene": {"view": {"hlookat": "-43.731","vlookat" : "22.713","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,949,2,'{"scene": {"view": {"hlookat": "39.858","vlookat" : "25.156","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,952,2,'{"scene": {"view": {"hlookat": "-15.055","vlookat" : "19.675","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,355,2,null,null,False,False,null),
(0,'thumb.jpg',67011,367,2,'{"scene": {"view": {"hlookat": "407.810","vlookat" : "7.761","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',67011,356,2,'{"scene": {"view": {"hlookat": "-179.607","vlookat" : "-1.803","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',67011,359,2,'{"scene": {"view": {"hlookat": "-142.004","vlookat" : "15.083","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,360,2,'{"scene": {"view": {"hlookat": "125.944","vlookat" : "15.135","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,361,2,'{"scene": {"view": {"hlookat": "-82.451","vlookat" : "16.199","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,363,2,'{"scene": {"view": {"hlookat": "-135.614","vlookat" : "6.117","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,364,2,'{"scene": {"view": {"hlookat": "-31.566","vlookat" : "7.047","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,366,2,'{"scene": {"view": {"hlookat": "-74.056","vlookat" : "21.952","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',67011,357,2,'{"scene": {"view": {"hlookat": "10.864","vlookat" : "18.077","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 67011
AND d.parent_id IS NULL
;


UPDATE cms_hotel set has_360 = 1 where id = 67011;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 67011;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 67011);

