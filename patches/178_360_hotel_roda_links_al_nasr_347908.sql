DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 347908;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 347908;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 347908;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 347908;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',347908,79,1),
('-',347908,130,1),
('Lobby',347908,1,2),
('1',347908,15,1),
('2',347908,16,2),
('3',347908,17,3),
('Brugges',347908,28,3),
('1',347908,29,1),
('2',347908,30,2),
('3',347908,31,3),
('1971 All Day Dinning',347908,167,4),
('1',347908,168,1),
('2',347908,169,2),
('3',347908,170,3),
('4',347908,171,4),
('Pool and Anees Pool Bar',347908,231,5),
('1',347908,232,1),
('2',347908,233,2),
('Sauna and Massage Room',347908,4,6),
('1',347908,101,1),
('2',347908,102,2),
('Gym',347908,3,7),
('1',347908,80,1),
('2',347908,81,2),
('Classic Twin Room',347908,246,8),
('1',347908,247,1),
('2',347908,251,2),
('Classic King Room',347908,486,9),
('1',347908,487,1),
('2',347908,491,2),
('Deluxe Twin Room',347908,139,10),
('1',347908,140,1),
('2',347908,141,2),
('3',347908,142,3),
('4',347908,143,4),
('Deluxe King Room',347908,568,11),
('1',347908,569,1),
('2',347908,570,2),
('3',347908,572,3),
('Premium Room',347908,480,12),
('1',347908,481,1),
('2',347908,482,2),
('3',347908,484,3),
('Premium Suite',347908,159,13),
('1',347908,589,1),
('2',347908,160,2),
('3',347908,164,3),
('4',347908,162,4),
('5',347908,163,5);






INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 347908;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',347908,79,2,null,null,False,False,null),
(0,'thumb.jpg',347908,130,2,'{"scene": {"view": {"hlookat": "3.051","vlookat" : "0.435","fov" : "139.265"}}}',null,True,True,1),
(0,'thumb.jpg',347908,1,2,null,null,False,False,null),
(0,'thumb.jpg',347908,15,2,'{"scene": {"view": {"hlookat": "3.041","vlookat" : "0.000","fov" : "138.779"}}}',null,True,True,2),
(0,'thumb.jpg',347908,16,2,'{"scene": {"view": {"hlookat": "0.963","vlookat" : "0.000","fov" : "139.037"}}}',null,False,False,null),
(0,'thumb.jpg',347908,17,2,'{"scene": {"view": {"hlookat": "1.106","vlookat" : "0.000","fov" : "138.894"}}}',null,True,True,3),
(0,'thumb.jpg',347908,28,2,null,null,False,False,null),
(0,'thumb.jpg',347908,29,2,'{"scene": {"view": {"hlookat": "0.322","vlookat" : "0.068","fov" : "139.037"}}}',null,True,True,4),
(0,'thumb.jpg',347908,30,2,'{"scene": {"view": {"hlookat": "4.139","vlookat" : "0.294","fov" : "139.002"}}}',null,False,False,null),
(0,'thumb.jpg',347908,31,2,'{"scene": {"view": {"hlookat": "-0.325","vlookat" : "0.066","fov" : "139.002"}}}',null,False,False,null),
(0,'thumb.jpg',347908,167,2,null,null,False,False,null),
(0,'thumb.jpg',347908,168,2,'{"scene": {"view": {"hlookat": "1.034","vlookat" : "0.000","fov" : "138.967"}}}',null,False,False,null),
(0,'thumb.jpg',347908,169,2,'{"scene": {"view": {"hlookat": "1.688","vlookat" : "0.315","fov" : "139.139"}}}',null,True,True,5),
(0,'thumb.jpg',347908,170,2,'{"scene": {"view": {"hlookat": "4.042","vlookat" : "0.764","fov" : "139.265"}}}',null,False,False,null),
(0,'thumb.jpg',347908,171,2,'{"scene": {"view": {"hlookat": "2.184","vlookat" : "0.147","fov" : "139.139"}}}',null,True,True,6),
(0,'thumb.jpg',347908,231,2,null,null,False,False,null),
(0,'thumb.jpg',347908,232,2,'{"scene": {"view": {"hlookat": "1.182","vlookat" : "0.000","fov" : "138.818"}}}',null,False,False,null),
(0,'thumb.jpg',347908,233,2,'{"scene": {"view": {"hlookat": "0.735","vlookat" : "0.000","fov" : "139.265"}}}',null,True,True,7),
(0,'thumb.jpg',347908,4,2,null,null,False,False,null),
(0,'thumb.jpg',347908,101,2,'{"scene": {"view": {"hlookat": "-0.821","vlookat" : "0.000","fov" : "139.002"}}}',null,False,False,null),
(0,'thumb.jpg',347908,102,2,'{"scene": {"view": {"hlookat": "1.466","vlookat" : "0.000","fov" : "138.534"}}}',null,True,True,8),
(0,'thumb.jpg',347908,3,2,null,null,False,False,null),
(0,'thumb.jpg',347908,80,2,'{"scene": {"view": {"hlookat": "3.786","vlookat" : "-0.099","fov" : "138.219"}}}',null,True,True,9),
(0,'thumb.jpg',347908,81,2,'{"scene": {"view": {"hlookat": "1.921","vlookat" : "0.059","fov" : "138.740"}}}',null,False,False,null),
(0,'thumb.jpg',347908,246,2,null,null,False,False,null),
(0,'thumb.jpg',347908,247,2,'{"scene": {"view": {"hlookat": "1.639","vlookat" : "0.000","fov" : "139.353"}}}',null,True,True,10),
(0,'thumb.jpg',347908,251,2,'{"scene": {"view": {"hlookat": "5.822","vlookat" : "0.828","fov" : "139.139"}}}',null,False,False,null),
(0,'thumb.jpg',347908,486,2,null,null,False,False,null),
(0,'thumb.jpg',347908,487,2,'{"scene": {"view": {"hlookat": "3.245","vlookat" : "0.069","fov" : "139.07"}}}',null,True,True,11),
(0,'thumb.jpg',347908,491,2,'{"scene": {"view": {"hlookat": "-0.534","vlookat" : "0.000","fov" : "138.219"}}}',null,False,False,null),
(0,'thumb.jpg',347908,139,2,null,null,False,False,null),
(0,'thumb.jpg',347908,140,2,'{"scene": {"view": {"hlookat": "1.033","vlookat" : "0.000","fov" : "138.967"}}}',null,False,False,null),
(0,'thumb.jpg',347908,141,2,'{"scene": {"view": {"hlookat": "0.797","vlookat" : "0.000","fov" : "139.203"}}}',null,True,True,12),
(0,'thumb.jpg',347908,142,2,'{"scene": {"view": {"hlookat": "-177.345","vlookat" : "0.250","fov" : "138.534"}}}',null,False,False,null),
(0,'thumb.jpg',347908,143,2,'{"scene": {"view": {"hlookat": "2.585","vlookat" : "0.000","fov" : "139.234"}}}',null,True,True,13),
(0,'thumb.jpg',347908,568,2,null,null,False,False,null),
(0,'thumb.jpg',347908,569,2,'{"scene": {"view": {"hlookat": "1.395","vlookat" : "0.000","fov" : "138.618"}}}',null,True,True,14),
(0,'thumb.jpg',347908,570,2,'{"scene": {"view": {"hlookat": "2.503","vlookat" : "0.000","fov" : "137.497"}}}',null,False,False,null),
(0,'thumb.jpg',347908,572,2,'{"scene": {"view": {"hlookat": "1.382","vlookat" : "0.000","fov" : "138.618"}}}',null,False,False,null),
(0,'thumb.jpg',347908,480,2,null,null,False,False,null),
(0,'thumb.jpg',347908,481,2,'{"scene": {"view": {"hlookat": "1.166","vlookat" : "0.000","fov" : "138.856"}}}',null,False,False,null),
(0,'thumb.jpg',347908,482,2,'{"scene": {"view": {"hlookat": "1.424","vlookat" : "0.000","fov" : "138.576"}}}',null,True,True,15),
(0,'thumb.jpg',347908,484,2,'{"scene": {"view": {"hlookat": "-0.480","vlookat" : "0.059","fov" : "138.665"}}}',null,False,False,null),
(0,'thumb.jpg',347908,159,2,null,null,False,False,null),
(0,'thumb.jpg',347908,589,2,'{"scene": {"view": {"hlookat": "0.998","vlookat" : "0.000","fov" : "139.002"}}}',null,False,False,null),
(0,'thumb.jpg',347908,160,2,'{"scene": {"view": {"hlookat": "1.509","vlookat" : "0.000","fov" : "138.491"}}}',null,False,False,null),
(0,'thumb.jpg',347908,164,2,'{"scene": {"view": {"hlookat": "1.829","vlookat" : "0.000","fov" : "138.171"}}}',null,True,True,16),
(0,'thumb.jpg',347908,162,2,'{"scene": {"view": {"hlookat": "1.553","vlookat" : "0.000","fov" : "138.447"}}}',null,False,False,null),
(0,'thumb.jpg',347908,163,2,'{"scene": {"view": {"hlookat": "-1.652","vlookat" : "0.320","fov" : "139.171"}}}',null,True,True,17);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 347908
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 347908;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 347908);

