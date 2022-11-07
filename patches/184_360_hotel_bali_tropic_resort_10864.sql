DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 10864;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 10864;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 10864;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 10864;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',10864,79,1),
('1',10864,130,1),
('2',10864,193,2),
('3',10864,194,3),
('Lobby',10864,1,2),
('1',10864,15,1),
('2',10864,16,2),
('3',10864,17,3),
('4',10864,18,4),
('Soka Coffee Shop',10864,8,3),
('1',10864,33,1),
('2',10864,34,2),
('Ratna',10864,28,4),
('1',10864,29,1),
('2',10864,30,2),
('Outside Dining',10864,167,5),
('1',10864,168,1),
('2',10864,169,2),
('Sunken - Pool Bar',10864,7,6),
('-',10864,152,1),
('Beach',10864,722,7),
('1',10864,723,1),
('2',10864,724,2),
('3',10864,725,3),
('Pool',10864,2,8),
('1',10864,21,1),
('2',10864,92,2),
('3',10864,186,3),
('Spa',10864,4,9),
('1',10864,101,1),
('2',10864,102,2),
('3',10864,103,3),
('4',10864,104,4),
('5',10864,105,5),
('Gym',10864,3,10),
('1',10864,80,1),
('2',10864,81,2),
('Meeting Room',10864,131,11),
('1',10864,132,1),
('2',10864,252,2),
('Wratnala Temple',10864,730,12),
('1',10864,731,1),
('2',10864,732,2),
('Deluxe Room',10864,486,13),
('1',10864,492,1),
('2',10864,487,2),
('3',10864,488,3),
('4',10864,491,4),
('Deluxe Bungalow - Twin Room',10864,36,14),
('1',10864,37,1),
('2',10864,38,2),
('3',10864,39,3),
('4',10864,245,4),
('5',10864,448,5),
('Royal Bungalow - Twin Room',10864,675,15),
('1',10864,687,1),
('2',10864,688,2),
('3',10864,685,3),
('4',10864,683,4),
('5',10864,681,5),
('Suite Bungalow',10864,159,16),
('1',10864,495,1),
('2',10864,164,2),
('3',10864,160,3),
('4',10864,161,4),
('5',10864,162,5),
('Diving Center',10864,464,17),
('1',10864,465,1),
('2',10864,466,2),
('Water Sports',10864,941,18),
('1',10864,942,1),
('2',10864,943,2);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 10864;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',10864,79,2,null,null,False,False,null),
(0,'thumb.jpg',10864,130,2,'{"scene": {"view": {"hlookat": "0.116","vlookat" : "-1.138","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',10864,193,2,'{"scene": {"view": {"hlookat": "4.039","vlookat" : "0.324","fov" : "139.709"}}}',null,False,False,null),
(0,'thumb.jpg',10864,194,2,'{"scene": {"view": {"hlookat": "5.336","vlookat" : "1.223","fov" : "139.649"}}}',null,True,True,1),
(0,'thumb.jpg',10864,1,2,null,null,False,False,null),
(0,'thumb.jpg',10864,15,2,'{"scene": {"view": {"hlookat": "2.406","vlookat" : "0.525","fov" : "139.234"}}}',null,False,False,null),
(0,'thumb.jpg',10864,16,2,'{"scene": {"view": {"hlookat": "1.247","vlookat" : "0.363","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',10864,17,2,'{"scene": {"view": {"hlookat": "-0.515","vlookat" : "0.000","fov" : "139.203"}}}',null,True,True,2),
(0,'thumb.jpg',10864,18,2,'{"scene": {"view": {"hlookat": "1.439","vlookat" : "0.000","fov" : "139.381"}}}',null,False,False,null),
(0,'thumb.jpg',10864,8,2,null,null,False,False,null),
(0,'thumb.jpg',10864,33,2,'{"scene": {"view": {"hlookat": "4.899","vlookat" : "0.215","fov" : "139.037"}}}',null,False,False,null),
(0,'thumb.jpg',10864,34,2,'{"scene": {"view": {"hlookat": "5.849","vlookat" : "0.525","fov" : "139.234"}}}',null,True,True,3),
(0,'thumb.jpg',10864,28,2,null,null,False,False,null),
(0,'thumb.jpg',10864,29,2,'{"scene": {"view": {"hlookat": "6.751","vlookat" : "0.865","fov" : "139.487"}}}',null,True,True,4),
(0,'thumb.jpg',10864,30,2,'{"scene": {"view": {"hlookat": "1.030","vlookat" : "0.175","fov" : "139.462"}}}',null,False,False,null),
(0,'thumb.jpg',10864,167,2,null,null,False,False,null),
(0,'thumb.jpg',10864,168,2,'{"scene": {"view": {"hlookat": "0.598","vlookat" : "0.000","fov" : "139.409"}}}',null,True,True,5),
(0,'thumb.jpg',10864,169,2,'{"scene": {"view": {"hlookat": "0.705","vlookat" : "0.000","fov" : "139.295"}}}',null,False,False,null),
(0,'thumb.jpg',10864,7,2,null,null,False,False,null),
(0,'thumb.jpg',10864,152,2,'{"scene": {"view": {"hlookat": "-1.127","vlookat" : "0.178","fov" : "139.487"}}}',null,True,True,6),
(0,'thumb.jpg',10864,722,2,null,null,False,False,null),
(0,'thumb.jpg',10864,723,2,'{"scene": {"view": {"hlookat": "1.401","vlookat" : "0.092","fov" : "139.584"}}}',null,True,True,7),
(0,'thumb.jpg',10864,724,2,'{"scene": {"view": {"hlookat": "1.069","vlookat" : "0.000","fov" : "138.931"}}}',null,False,False,null),
(0,'thumb.jpg',10864,725,2,'{"scene": {"view": {"hlookat": "0.705","vlookat" : "0.000","fov" : "139.295"}}}',null,False,False,null),
(0,'thumb.jpg',10864,2,2,null,null,False,False,null),
(0,'thumb.jpg',10864,21,2,'{"scene": {"view": {"hlookat": "0.829","vlookat" : "0.000","fov" : "139.171"}}}',null,True,True,8),
(0,'thumb.jpg',10864,92,2,'{"scene": {"view": {"hlookat": "0.619","vlookat" : "0.000","fov" : "139.381"}}}',null,False,False,null),
(0,'thumb.jpg',10864,186,2,'{"scene": {"view": {"hlookat": "0.861","vlookat" : "0.173","fov" : "139.139"}}}',null,False,False,null),
(0,'thumb.jpg',10864,4,2,null,null,False,False,null),
(0,'thumb.jpg',10864,101,2,'{"scene": {"view": {"hlookat": "1.471","vlookat" : "0.181","fov" : "139.512"}}}',null,False,False,null),
(0,'thumb.jpg',10864,102,2,'{"scene": {"view": {"hlookat": "0.591","vlookat" : "0.000","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',10864,103,2,'{"scene": {"view": {"hlookat": "-0.365","vlookat" : "0.167","fov" : "139.381"}}}',null,True,True,9),
(0,'thumb.jpg',10864,104,2,'{"scene": {"view": {"hlookat": "1.247","vlookat" : "0.363","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',10864,105,2,'{"scene": {"view": {"hlookat": "-1.092","vlookat" : "1.256","fov" : "139.781"}}}',null,False,False,null),
(0,'thumb.jpg',10864,3,2,null,null,False,False,null),
(0,'thumb.jpg',10864,80,2,'{"scene": {"view": {"hlookat": "-1.508","vlookat" : "0.130","fov" : "138.894"}}}',null,True,True,10),
(0,'thumb.jpg',10864,81,2,'{"scene": {"view": {"hlookat": "0.463","vlookat" : "0.000","fov" : "139.537"}}}',null,False,False,null),
(0,'thumb.jpg',10864,131,2,null,null,False,False,null),
(0,'thumb.jpg',10864,132,2,'{"scene": {"view": {"hlookat": "0.381","vlookat" : "0.000","fov" : "139.628"}}}',null,True,True,11),
(0,'thumb.jpg',10864,252,2,'{"scene": {"view": {"hlookat": "0.463","vlookat" : "0.000","fov" : "139.537"}}}',null,False,False,null),
(0,'thumb.jpg',10864,730,2,null,null,False,False,null),
(0,'thumb.jpg',10864,731,2,'{"scene": {"view": {"hlookat": "3.037","vlookat" : "0.543","fov" : "138.931"}}}',null,False,False,12),
(0,'thumb.jpg',10864,732,2,'{"scene": {"view": {"hlookat": "0.766","vlookat" : "0.000","fov" : "139.234"}}}',null,False,False,null),
(0,'thumb.jpg',10864,486,2,null,null,False,False,null),
(0,'thumb.jpg',10864,492,2,'{"scene": {"view": {"hlookat": "2.420","vlookat" : "0.772","fov" : "139.561"}}}',null,True,True,13),
(0,'thumb.jpg',10864,487,2,'{"scene": {"view": {"hlookat": "2.422","vlookat" : "0.451","fov" : "139.709"}}}',null,False,False,null),
(0,'thumb.jpg',10864,488,2,'{"scene": {"view": {"hlookat": "0.564","vlookat" : "0.000","fov" : "139.436"}}}',null,False,False,null),
(0,'thumb.jpg',10864,491,2,'{"scene": {"view": {"hlookat": "-1.222","vlookat" : "1.550","fov" : "139.746"}}}',null,False,False,null),
(0,'thumb.jpg',10864,36,2,null,null,False,False,null),
(0,'thumb.jpg',10864,37,2,'{"scene": {"view": {"hlookat": "4.654","vlookat" : "0.499","fov" : "138.700"}}}',null,True,True,14),
(0,'thumb.jpg',10864,38,2,'{"scene": {"view": {"hlookat": "12.909","vlookat" : "1.251","fov" : "139.234"}}}',null,False,False,null),
(0,'thumb.jpg',10864,39,2,'{"scene": {"view": {"hlookat": "104.310","vlookat" : "-0.663","fov" : "139.381"}}}',null,False,False,null),
(0,'thumb.jpg',10864,245,2,'{"scene": {"view": {"hlookat": "-190.292","vlookat" : "1.973","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',10864,448,2,'{"scene": {"view": {"hlookat": "136.299","vlookat" : "0.184","fov" : "139.842"}}}',null,False,False,null),
(0,'thumb.jpg',10864,675,2,null,null,False,False,null),
(0,'thumb.jpg',10864,687,2,'{"scene": {"view": {"hlookat": "0.862","vlookat" : "0.000","fov" : "139.139"}}}',null,False,False,null),
(0,'thumb.jpg',10864,688,2,'{"scene": {"view": {"hlookat": "0.797","vlookat" : "0.000","fov" : "139.203"}}}',null,True,True,15),
(0,'thumb.jpg',10864,685,2,'{"scene": {"view": {"hlookat": "-2.150","vlookat" : "1.153","fov" : "139.690"}}}',null,False,False,null),
(0,'thumb.jpg',10864,683,2,'{"scene": {"view": {"hlookat": "0.963","vlookat" : "0.000","fov" : "139.037"}}}',null,False,False,null),
(0,'thumb.jpg',10864,681,2,'{"scene": {"view": {"hlookat": "-6.808","vlookat" : "1.568","fov" : "139.265"}}}',null,False,False,null),
(0,'thumb.jpg',10864,159,2,null,null,False,False,null),
(0,'thumb.jpg',10864,495,2,'{"scene": {"view": {"hlookat": "-8.262","vlookat" : "1.704","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',10864,164,2,'{"scene": {"view": {"hlookat": "3.604","vlookat" : "0.879","fov" : "139.512"}}}',null,True,True,16),
(0,'thumb.jpg',10864,160,2,'{"scene": {"view": {"hlookat": "-0.307","vlookat" : "0.086","fov" : "139.487"}}}',null,False,False,null),
(0,'thumb.jpg',10864,161,2,'{"scene": {"view": {"hlookat": "-1.836","vlookat" : "0.250","fov" : "139.869"}}}',null,False,False,null),
(0,'thumb.jpg',10864,162,2,'{"scene": {"view": {"hlookat": "0.135","vlookat" : "1.036","fov" : "139.537"}}}',null,False,False,null),
(0,'thumb.jpg',10864,464,2,null,null,False,False,null),
(0,'thumb.jpg',10864,465,2,'{"scene": {"view": {"hlookat": "48.686","vlookat" : "1.827","fov" : "139.203"}}}',null,False,False,null),
(0,'thumb.jpg',10864,466,2,'{"scene": {"view": {"hlookat": "-9.311","vlookat" : "0.798","fov" : "138.491"}}}',null,False,False,null),
(0,'thumb.jpg',10864,941,2,null,null,False,False,null),
(0,'thumb.jpg',10864,942,2,'{"scene": {"view": {"hlookat": "0.735","vlookat" : "0.000","fov" : "139.265"}}}',null,False,False,null),
(0,'thumb.jpg',10864,943,2,'{"scene": {"view": {"hlookat": "-10.734","vlookat" : "0.093","fov" : "139.584"}}}',null,True,True,17);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 10864
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 10864;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 10864);

