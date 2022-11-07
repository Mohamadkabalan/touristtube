DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 4850;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 4850;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 4850;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 4850;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',4850,556,1),
('View 1',4850,557,1),
('View 2',4850,558,2),
('View 3',4850,560,3),
('View 4',4850,561,4),
('Entrance',4850,79,2),
('View 1',4850,130,1),
('View 2',4850,193,2),
('View 3',4850,194,3),
('Reception',4850,338,3),
(' - ',4850,339,1),
('Lobby',4850,1,4),
(' - ',4850,15,1),
('Breakfast',4850,6,5),
('View 1',4850,25,1),
('View 2',4850,26,2),
('Il Palazzo',4850,464,6),
(' - ',4850,465,1),
('Le Bar Du Normandy',4850,7,7),
('View 1',4850,152,1),
('View 2',4850,153,2),
('View 3',4850,154,3),
('Palais Royal',4850,131,8),
('View 1',4850,132,1),
('View 2',4850,252,2),
('Salon Argenteuil',4850,293,9),
(' - ',4850,297,1),
('Single Room',4850,530,10),
('View 1',4850,531,1),
('Bathroom',4850,532,2),
('Single Superior Room',4850,486,11),
('View 1',4850,487,1),
('View 2',4850,488,2),
('Bathroom',4850,489,3),
('Standard Double Room',4850,427,12),
('View 1',4850,428,1),
('Bathroom',4850,429,2),
('Standard Twin Room',4850,246,13),
('View 1',4850,247,1),
('Bathroom',4850,248,2),
('View 2',4850,249,3),
('Superior Double Room -310',4850,546,14),
('View 1',4850,547,1),
('View 2',4850,548,2),
('View 3',4850,549,3),
('Bathroom',4850,550,4),
('Superior Double Room 2',4850,551,15),
('View 1',4850,552,1),
('Bathroom',4850,553,2),
('View 3',4850,554,3),
('Superior Twin Room',4850,563,16),
('View 1',4850,564,1),
('View 2',4850,565,2),
('Bathroom',4850,567,3),
('Deluxe Double Room',4850,568,17),
('View 1',4850,569,1),
('Bathroom',4850,570,2),
('View 3',4850,571,3),
('Deluxe Twin Room',4850,139,18),
('View 1',4850,140,1),
('View 2',4850,141,2),
('Triple Room',4850,263,19),
('View 1',4850,264,1),
('Bathroom',4850,265,2),
('View 2',4850,266,3),
('Junior Suite',4850,212,19),
('View 1',4850,213,1),
('View 2',4850,214,2),
('Bathroom',4850,217,3);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 4850;




INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',4850,556,2,null,null,False,False,null),
(0,'thumb.jpg',4850,557,2,'{"scene": {"view": {"hlookat": "627.061","vlookat" : "-15.140","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,558,2,'{"scene": {"view": {"hlookat": "-59.738","vlookat" : "-30.806","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,560,2,'{"scene": {"view": {"hlookat": "43.949","vlookat" : "-15.195","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',4850,561,2,'{"scene": {"view": {"hlookat": "353.171","vlookat" : "-11.663","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,79,2,null,null,False,False,null),
(0,'thumb.jpg',4850,130,2,'{"scene": {"view": {"hlookat": "3794.622","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,193,2,'{"scene": {"view": {"hlookat": "-2.563","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,2),
(0,'thumb.jpg',4850,194,2,'{"scene": {"view": {"hlookat": "-303.429","vlookat" : "-0.001","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,338,2,null,null,False,False,null),
(0,'thumb.jpg',4850,339,2,'{"scene": {"view": {"hlookat": "399.795","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,3),
(0,'thumb.jpg',4850,1,2,null,null,False,False,null),
(0,'thumb.jpg',4850,15,2,'{"scene": {"view": {"hlookat": "-205.602","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,4),
(0,'thumb.jpg',4850,6,2,null,null,False,False,null),
(0,'thumb.jpg',4850,25,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "140.000"}}}',null,True,True,5),
(0,'thumb.jpg',4850,26,2,'{"scene": {"view": {"hlookat": "200.605","vlookat" : "-0.309","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,464,2,null,null,False,False,null),
(0,'thumb.jpg',4850,465,2,'{"scene": {"view": {"hlookat": "-180.705","vlookat" : "-1.337","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,7,2,null,null,False,False,null),
(0,'thumb.jpg',4850,152,2,'{"scene": {"view": {"hlookat": "-377.680","vlookat" : "0.103","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,153,2,'{"scene": {"view": {"hlookat": "384.194","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,154,2,'{"scene": {"view": {"hlookat": "436.081","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,6),
(0,'thumb.jpg',4850,131,2,null,null,False,False,null),
(0,'thumb.jpg',4850,132,2,'{"scene": {"view": {"hlookat": "686.915","vlookat" : "-5.644","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,252,2,'{"scene": {"view": {"hlookat": "766.206","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,293,2,null,null,False,False,null),
(0,'thumb.jpg',4850,297,2,'{"scene": {"view": {"hlookat": "368.998","vlookat" : "0.001","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,530,2,null,null,False,False,null),
(0,'thumb.jpg',4850,531,2,'{"scene": {"view": {"hlookat": "688.006","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,7),
(0,'thumb.jpg',4850,532,2,'{"scene": {"view": {"hlookat": "385.188","vlookat" : "7.466","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,486,2,null,null,False,False,null),
(0,'thumb.jpg',4850,487,2,'{"scene": {"view": {"hlookat": "612.785","vlookat" : "2.962","fov" : "140.000"}}}',null,True,True,8),
(0,'thumb.jpg',4850,488,2,'{"scene": {"view": {"hlookat": "47.306","vlookat" : "-1.419","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,489,2,'{"scene": {"view": {"hlookat": "-27.033","vlookat" : "5.284","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,427,2,null,null,False,False,null),
(0,'thumb.jpg',4850,428,2,'{"scene": {"view": {"hlookat": "312.731","vlookat" : "15.496","fov" : "140.000"}}}',null,True,True,9),
(0,'thumb.jpg',4850,429,2,'{"scene": {"view": {"hlookat": "-381.204","vlookat" : "23.242","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,246,2,null,null,False,False,null),
(0,'thumb.jpg',4850,247,2,'{"scene": {"view": {"hlookat": "-46.792","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,10),
(0,'thumb.jpg',4850,248,2,'{"scene": {"view": {"hlookat": "157.824","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,249,2,'{"scene": {"view": {"hlookat": "-98.141","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,546,2,null,null,False,False,null),
(0,'thumb.jpg',4850,547,2,'{"scene": {"view": {"hlookat": "13.890","vlookat" : "-0.313","fov" : "140.000"}}}',null,True,True,11),
(0,'thumb.jpg',4850,548,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,549,2,'{"scene": {"view": {"hlookat": "53.247","vlookat" : "-0.422","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,550,2,'{"scene": {"view": {"hlookat": "-150.957","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,551,2,null,null,False,False,null),
(0,'thumb.jpg',4850,552,2,'{"scene": {"view": {"hlookat": "-33.390","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,12),
(0,'thumb.jpg',4850,553,2,'{"scene": {"view": {"hlookat": "-66.022","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,554,2,'{"scene": {"view": {"hlookat": "-175.509","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,563,2,null,null,False,False,null),
(0,'thumb.jpg',4850,564,2,'{"scene": {"view": {"hlookat": "187.339","vlookat" : "6.231","fov" : "140.000"}}}',null,True,True,13),
(0,'thumb.jpg',4850,565,2,'{"scene": {"view": {"hlookat": "-342.178","vlookat" : "8.824","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,567,2,'{"scene": {"view": {"hlookat": "157.733","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,568,2,null,null,False,False,null),
(0,'thumb.jpg',4850,569,2,'{"scene": {"view": {"hlookat": "380.640","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,14),
(0,'thumb.jpg',4850,570,2,'{"scene": {"view": {"hlookat": "105.307","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,571,2,'{"scene": {"view": {"hlookat": "450.682","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,139,2,null,null,False,False,null),
(0,'thumb.jpg',4850,140,2,'{"scene": {"view": {"hlookat": "203.162","vlookat" : "6.485","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,141,2,'{"scene": {"view": {"hlookat": "-426.618","vlookat" : "9.779","fov" : "140.000"}}}',null,True,True,15),
(0,'thumb.jpg',4850,263,2,null,null,False,False,null),
(0,'thumb.jpg',4850,264,2,'{"scene": {"view": {"hlookat": "5.702","vlookat" : "1.206","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,265,2,'{"scene": {"view": {"hlookat": "159.759","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,266,2,'{"scene": {"view": {"hlookat": "-171.582","vlookat" : "0.000","fov" : "140.000"}}}',null,True,True,16),
(0,'thumb.jpg',4850,212,2,null,null,False,False,null),
(0,'thumb.jpg',4850,213,2,'{"scene": {"view": {"hlookat": "-67.933","vlookat" : "17.760","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',4850,214,2,'{"scene": {"view": {"hlookat": "-23.253","vlookat" : "11.228","fov" : "140.000"}}}',null,True,True,17),
(0,'thumb.jpg',4850,217,2,'{"scene": {"view": {"hlookat": "-56.674","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 4850
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 4850;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 4850);
