INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Main Entrance',239130,79,1),
('Exterior - 1',239130,130,1),
('Lobby',239130,1,2),
('1',239130,15,1),
('2',239130,16,2),
('3',239130,17,3),
('Tangerine Bar',239130,7,3),
('1',239130,152,1),
('2',239130,153,2),
('3',239130,154,3),
('Tangerine Restaurant',239130,28,4),
('1',239130,29,1),
('2',239130,30,2),
('3',239130,31,3),
('4',239130,32,4),
('5',239130,110,5),
('Board Room Chamber  1',239130,131,5),
('1',239130,132,1),
('2',239130,252,2),
('Hibiscus Spa',239130,4,6),
('-',239130,101,1),
('Imperial Banquet Hall',239130,293,7),
('1',239130,297,1),
('1',239130,298,2),
('Matrix Gymnasium',239130,3,8),
('-',239130,80,1),
('Swimming Pool',239130,2,9),
('1',239130,21,1),
('2',239130,92,2),
('3',239130,93,3),
('Studio Room',239130,36,10),
('1',239130,37,1),
('2',239130,38,2),
('3',239130,39,3),
('Bathroom',239130,245,4),
('Premium Studio',239130,480,11),
('1',239130,481,1),
('2',239130,482,2),
('3',239130,483,3),
('4',239130,484,4),
('Suite Room',239130,389,12),
('1',239130,666,1),
('2',239130,667,2),
('3',239130,668,3),
('4',239130,669,4),
('5',239130,670,5),
('6',239130,700,6),
('Premium Suite Room',239130,947,13),
('Living Room',239130,948,1),
('Bathroom',239130,949,2),
('Bedroom - 1',239130,950,3),
('Bedroom - 2',239130,951,4),
('Guest Bathroom',239130,952,5),
('Guest Room - 1',239130,953,6),
('Guest Room - 2',239130,954,7),
('Kitchen',239130,955,8);



DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 239130;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 239130;





INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',239130,79,2,null,null,False,False,null),
(0,'thumb.jpg',239130,130,2,'{"scene": {"view": {"hlookat": "-3.954","vlookat" : "-10.744","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',239130,1,2,null,null,False,False,null),
(0,'thumb.jpg',239130,15,2,'{"scene": {"view": {"hlookat": "-123.608","vlookat" : "0.823","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,16,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',239130,17,2,'{"scene": {"view": {"hlookat": "38.237","vlookat" : "-1.137","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,7,2,null,null,False,False,null),
(0,'thumb.jpg',239130,152,2,'{"scene": {"view": {"hlookat": "-21.977","vlookat" : "0.008","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',239130,153,2,'{"scene": {"view": {"hlookat": "6.559","vlookat" : "0.492","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,154,2,'{"scene": {"view": {"hlookat": "356.743","vlookat" : "-0.513","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,28,2,null,null,False,False,null),
(0,'thumb.jpg',239130,29,2,'{"scene": {"view": {"hlookat": "263.196","vlookat" : "1.139","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,30,2,'{"scene": {"view": {"hlookat": "0.167","vlookat" : "7.871","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,31,2,'{"scene": {"view": {"hlookat": "44.282","vlookat" : "-0.000","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',239130,32,2,'{"scene": {"view": {"hlookat": "-311.980","vlookat" : "2.200","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,110,2,'{"scene": {"view": {"hlookat": "-97.256","vlookat" : "0.792","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,131,2,null,null,False,False,null),
(0,'thumb.jpg',239130,132,2,'{"scene": {"view": {"hlookat": "15.474","vlookat" : "-0.854","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',239130,252,2,'{"scene": {"view": {"hlookat": "46.191","vlookat" : "0.874","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,4,2,null,null,False,False,null),
(0,'thumb.jpg',239130,101,2,'{"scene": {"view": {"hlookat": "549.971","vlookat" : "1.000","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',239130,293,2,null,null,False,False,null),
(0,'thumb.jpg',239130,297,2,'{"scene": {"view": {"hlookat": "179.525","vlookat" : "-0.850","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',239130,298,2,'{"scene": {"view": {"hlookat": "7.379","vlookat" : "1.640","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,3,2,null,null,False,False,null),
(0,'thumb.jpg',239130,80,2,'{"scene": {"view": {"hlookat": "33.065","vlookat" : "-1.914","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',239130,2,2,null,null,False,False,null),
(0,'thumb.jpg',239130,21,2,'{"scene": {"view": {"hlookat": "35.154","vlookat" : "-1.804","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',239130,92,2,'{"scene": {"view": {"hlookat": "-104.525","vlookat" : "-15.922","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,93,2,'{"scene": {"view": {"hlookat": "340.729","vlookat" : "0.438","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',239130,36,2,null,null,False,False,null),
(0,'thumb.jpg',239130,37,2,'{"scene": {"view": {"hlookat": "-18.689","vlookat" : "-0.328","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',239130,38,2,'{"scene": {"view": {"hlookat": "-74.328","vlookat" : "15.439","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,39,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,245,2,'{"scene": {"view": {"hlookat": "-317.848","vlookat" : "-3.544","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,480,2,null,null,False,False,null),
(0,'thumb.jpg',239130,481,2,'{"scene": {"view": {"hlookat": "-14.775","vlookat" : "0.270","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',239130,482,2,'{"scene": {"view": {"hlookat": "-39.163","vlookat" : "2.328","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,483,2,'{"scene": {"view": {"hlookat": "4.591","vlookat" : "0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,484,2,'{"scene": {"view": {"hlookat": "-79.352","vlookat" : "2.505","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,389,2,null,null,False,False,null),
(0,'thumb.jpg',239130,666,2,'{"scene": {"view": {"hlookat": "-43.946","vlookat" : "0.042","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',239130,667,2,'{"scene": {"view": {"hlookat": "332.864","vlookat" : "1.187","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,668,2,'{"scene": {"view": {"hlookat": "-310.238","vlookat" : "2.255","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,669,2,'{"scene": {"view": {"hlookat": "134.574","vlookat" : "2.121","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,670,2,'{"scene": {"view": {"hlookat": "222.716","vlookat" : "-0.538","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,700,2,'{"scene": {"view": {"hlookat": "19.676","vlookat" : "-0.656","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,947,2,null,null,False,False,null),
(0,'thumb.jpg',239130,948,2,'{"scene": {"view": {"hlookat": "-14.094","vlookat" : "-0.819","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,949,2,'{"scene": {"view": {"hlookat": "198.483","vlookat" : "0.028","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,950,2,'{"scene": {"view": {"hlookat": "110.089","vlookat" : "-1.999","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',239130,951,2,'{"scene": {"view": {"hlookat": "59.081","vlookat" : "0.327","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,952,2,'{"scene": {"view": {"hlookat": "-27.608","vlookat" : "-0.001","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,953,2,'{"scene": {"view": {"hlookat": "175.377","vlookat" : "0.095","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,954,2,'{"scene": {"view": {"hlookat": "-385.328","vlookat" : "0.354","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',239130,955,2,'{"scene": {"view": {"hlookat": "-121.289","vlookat" : "-2.479","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 239130
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 239130;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 239130);


