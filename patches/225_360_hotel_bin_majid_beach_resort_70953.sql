DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 70953;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 70953;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 70953;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 70953;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',70953,79,1),
('1',70953,130,1),
('2',70953,193,2),
('3',70953,194,3),
('Lobby',70953,1,2),
('1',70953,15,1),
('2',70953,16,2),
('3',70953,17,3),
('4',70953,18,4),
('5',70953,19,5),
('6',70953,20,6),
('Oasis restaurant',70953,28,3),
('1',70953,29,1),
('2',70953,30,2),
('3',70953,31,3),
('Beach area with coconut groove beach bar',70953,7,5),
('1',70953,152,1),
('2',70953,153,2),
('3',70953,154,3),
('4',70953,155,4),
('5',70953,156,5),
('6',70953,157,6),
('7',70953,158,7),
('8',70953,1286,8),
('Cabana pool bar',70953,231,6),
('1',70953,232,1),
('2',70953,233,2),
('3',70953,234,3),
('Swimming pool area',70953,2,4),
('1',70953,21,1),
('2',70953,92,2),
('3',70953,93,3),
('Gym',70953,3,7),
('-',70953,80,1),
('Suite chalet',70953,427,8),
('1',70953,428,1),
('2',70953,429,2),
('3',70953,430,3),
('Bathroom',70953,431,4),
('Premium chalet',70953,246,9),
('1',70953,247,1),
('2',70953,248,2),
('Bathroom',70953,251,3),
('Standard chalet',70953,36,10),
('1',70953,37,1),
('2',70953,38,2),
('3',70953,39,3),
('Bathroom',70953,245,4);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 70953;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',70953,79,2,null,null,False,False,null),
(0,'thumb.jpg',70953,130,2,'{"scene": {"view": {"hlookat": "1.289","vlookat" : "0.000","fov" : "139.537"}}}',null,False,False,null),
(0,'thumb.jpg',70953,193,2,'{"scene": {"view": {"hlookat": "1.247","vlookat" : "1.326","fov" : "139.381"}}}',null,True,True,1),
(0,'thumb.jpg',70953,194,2,'{"scene": {"view": {"hlookat": "0.564","vlookat" : "0.000","fov" : "139.436"}}}',null,False,False,null),
(0,'thumb.jpg',70953,1,2,null,null,False,False,null),
(0,'thumb.jpg',70953,15,2,'{"scene": {"view": {"hlookat": "144.846","vlookat" : "4.791","fov" : "139.409"}}}',null,False,False,null),
(0,'thumb.jpg',70953,16,2,'{"scene": {"view": {"hlookat": "2.531","vlookat" : "2.270","fov" : "139.709"}}}',null,False,False,null),
(0,'thumb.jpg',70953,17,2,'{"scene": {"view": {"hlookat": "0.349","vlookat" : "0.530","fov" : "139.436"}}}',null,True,True,2),
(0,'thumb.jpg',70953,18,2,'{"scene": {"view": {"hlookat": "0.998","vlookat" : "0.000","fov" : "139.002"}}}',null,False,False,null),
(0,'thumb.jpg',70953,19,2,'{"scene": {"view": {"hlookat": "0.254","vlookat" : "0.000","fov" : "139.746"}}}',null,False,False,null),
(0,'thumb.jpg',70953,20,2,'{"scene": {"view": {"hlookat": "167.791","vlookat" : "4.492","fov" : "139.584"}}}',null,False,False,null),
(0,'thumb.jpg',70953,28,2,null,null,False,False,null),
(0,'thumb.jpg',70953,29,2,'{"scene": {"view": {"hlookat": "3.181","vlookat" : "0.194","fov" : "139.606"}}}',null,True,True,3),
(0,'thumb.jpg',70953,30,2,'{"scene": {"view": {"hlookat": "2.012","vlookat" : "1.432","fov" : "139.628"}}}',null,False,False,null),
(0,'thumb.jpg',70953,31,2,'{"scene": {"view": {"hlookat": "120.818","vlookat" : "3.381","fov" : "139.381"}}}',null,False,False,null),
(0,'thumb.jpg',70953,7,2,null,null,False,False,null),
(0,'thumb.jpg',70953,152,2,'{"scene": {"view": {"hlookat": "-1.619","vlookat" : "0.178","fov" : "139.487"}}}',null,False,False,null),
(0,'thumb.jpg',70953,153,2,'{"scene": {"view": {"hlookat": "0.252","vlookat" : "0.925","fov" : "139.584"}}}',null,True,True,4),
(0,'thumb.jpg',70953,154,2,'{"scene": {"view": {"hlookat": "1.271","vlookat" : "0.307","fov" : "139.537"}}}',null,False,False,null),
(0,'thumb.jpg',70953,155,2,'{"scene": {"view": {"hlookat": "0.272","vlookat" : "0.000","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',70953,156,2,'{"scene": {"view": {"hlookat": "0.439","vlookat" : "0.000","fov" : "139.561"}}}',null,False,False,null),
(0,'thumb.jpg',70953,157,2,'{"scene": {"view": {"hlookat": "6.090","vlookat" : "0.554","fov" : "139.649"}}}',null,True,True,5),
(0,'thumb.jpg',70953,158,2,'{"scene": {"view": {"hlookat": "1.033","vlookat" : "0.000","fov" : "138.967"}}}',null,False,False,null),
(0,'thumb.jpg',70953,1286,2,'{"scene": {"view": {"hlookat": "8.522","vlookat" : "2.792","fov" : "139.512"}}}',null,True,True,6),
(0,'thumb.jpg',70953,231,2,null,null,False,False,null),
(0,'thumb.jpg',70953,232,2,'{"scene": {"view": {"hlookat": "-10.354","vlookat" : "1.991","fov" : "139.203"}}}',null,False,False,null),
(0,'thumb.jpg',70953,233,2,'{"scene": {"view": {"hlookat": "-3.421","vlookat" : "0.686","fov" : "139.649"}}}',null,True,True,7),
(0,'thumb.jpg',70953,234,2,'{"scene": {"view": {"hlookat": "0.439","vlookat" : "1.856","fov" : "139.561"}}}',null,False,False,null),
(0,'thumb.jpg',70953,2,2,null,null,False,False,null),
(0,'thumb.jpg',70953,21,2,'{"scene": {"view": {"hlookat": "0.463","vlookat" : "0.000","fov" : "139.537"}}}',null,True,True,8),
(0,'thumb.jpg',70953,92,2,'{"scene": {"view": {"hlookat": "4.856","vlookat" : "2.960","fov" : "139.649"}}}',null,False,False,null),
(0,'thumb.jpg',70953,93,2,'{"scene": {"view": {"hlookat": "152.230","vlookat" : "-0.684","fov" : "139.381"}}}',null,True,True,9),
(0,'thumb.jpg',70953,3,2,null,null,False,False,null),
(0,'thumb.jpg',70953,80,2,'{"scene": {"view": {"hlookat": "2.914","vlookat" : "1.841","fov" : "139.709"}}}',null,True,True,10),
(0,'thumb.jpg',70953,427,2,null,null,False,False,null),
(0,'thumb.jpg',70953,428,2,'{"scene": {"view": {"hlookat": "-0.744","vlookat" : "0.710","fov" : "139.436"}}}',null,False,False,null),
(0,'thumb.jpg',70953,429,2,'{"scene": {"view": {"hlookat": "3.300","vlookat" : "0.277","fov" : "139.487"}}}',null,True,True,11),
(0,'thumb.jpg',70953,430,2,'{"scene": {"view": {"hlookat": "2.098","vlookat" : "0.582","fov" : "139.436"}}}',null,False,False,null),
(0,'thumb.jpg',70953,431,2,'{"scene": {"view": {"hlookat": "105.940","vlookat" : "4.535","fov" : "139.584"}}}',null,True,True,12),
(0,'thumb.jpg',70953,246,2,null,null,False,False,null),
(0,'thumb.jpg',70953,247,2,'{"scene": {"view": {"hlookat": "0.928","vlookat" : "0.000","fov" : "139.072"}}}',null,False,False,null),
(0,'thumb.jpg',70953,248,2,'{"scene": {"view": {"hlookat": "0.439","vlookat" : "0.000","fov" : "139.561"}}}',null,True,True,13),
(0,'thumb.jpg',70953,251,2,'{"scene": {"view": {"hlookat": "76.846","vlookat" : "13.064","fov" : "139.649"}}}',null,True,True,14),
(0,'thumb.jpg',70953,36,2,null,null,False,False,null),
(0,'thumb.jpg',70953,37,2,'{"scene": {"view": {"hlookat": "0.676","vlookat" : "0.000","fov" : "139.324"}}}',null,False,False,null),
(0,'thumb.jpg',70953,38,2,'{"scene": {"view": {"hlookat": "3.881","vlookat" : "0.153","fov" : "139.234"}}}',null,True,True,15),
(0,'thumb.jpg',70953,39,2,'{"scene": {"view": {"hlookat": "148.943","vlookat" : "1.659","fov" : "139.106"}}}',null,True,True,16),
(0,'thumb.jpg',70953,245,2,'{"scene": {"view": {"hlookat": "76.724","vlookat" : "18.428","fov" : "139.436"}}}',null,True,True,17);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 70953
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 70953;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 70953);

