INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',244707,79,1),
(' - ',244707,130,1),
('Lobby',244707,1,2),
('1',244707,15,1),
('2',244707,16,2),
('3',244707,17,3),
('Flavours-All Day Dining Restaurant',244707,28,3),
('1',244707,29,1),
('2',244707,30,2),
('3',244707,31,3),
('Jaipur Adda Bar Restaurant',244707,167,4),
('1',244707,168,1),
('2',244707,169,2),
('3',244707,170,3),
('4',244707,171,4),
('5',244707,197,5),
('6',244707,198,6),
('7',244707,199,7),
('Imperial Banquet Hall',244707,293,5),
('-',244707,298,1),
('Superior Room',244707,486,6),
('1',244707,487,1),
('2',244707,488,2),
('Bathroom',244707,491,3),
('Deluxe Room',244707,568,7),
('1',244707,569,1),
('2',244707,570,2),
('3',244707,571,3),
('4',244707,572,4);


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 244707;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',244707,79,2,null,null,False,False,null),
(0,'thumb.jpg',244707,130,2,'{"scene": {"view": {"hlookat": "0.328","vlookat" : "-32.301","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',244707,1,2,null,null,False,False,null),
(0,'thumb.jpg',244707,15,2,'{"scene": {"view": {"hlookat": "335.749","vlookat" : "0.697","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',244707,16,2,'{"scene": {"view": {"hlookat": "414.342","vlookat" : "2.046","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,17,2,'{"scene": {"view": {"hlookat": "-379.164","vlookat" : "-0.690","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,28,2,null,null,False,False,null),
(0,'thumb.jpg',244707,29,2,'{"scene": {"view": {"hlookat": "13.281","vlookat" : "-0.492","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,30,2,'{"scene": {"view": {"hlookat": "144.149","vlookat" : "-0.699","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',244707,31,2,'{"scene": {"view": {"hlookat": "397.807","vlookat" : "-1.575","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,167,2,null,null,False,False,null),
(0,'thumb.jpg',244707,168,2,'{"scene": {"view": {"hlookat": "92.782","vlookat" : "1.096","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,169,2,'{"scene": {"view": {"hlookat": "-14.134","vlookat" : "0.066","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,170,2,'{"scene": {"view": {"hlookat": "-37.550","vlookat" : "-0.820","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,171,2,'{"scene": {"view": {"hlookat": "6.395","vlookat" : "0.492","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,197,2,'{"scene": {"view": {"hlookat": "-31.666","vlookat" : "-0.563","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,198,2,'{"scene": {"view": {"hlookat": "-135.094","vlookat" : "1.729","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',244707,199,2,'{"scene": {"view": {"hlookat": "6.021","vlookat" : "-0.635","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,297,2,null,null,False,False,null),
(0,'thumb.jpg',244707,298,2,'{"scene": {"view": {"hlookat": "0.985","vlookat" : "4.549","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',244707,486,2,null,null,False,False,null),
(0,'thumb.jpg',244707,487,2,'{"scene": {"view": {"hlookat": "375.847","vlookat" : "0.620","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',244707,488,2,'{"scene": {"view": {"hlookat": "-222.552","vlookat" : "7.796","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,491,2,'{"scene": {"view": {"hlookat": "156.539","vlookat" : "27.901","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,568,2,null,null,False,False,null),
(0,'thumb.jpg',244707,569,2,'{"scene": {"view": {"hlookat": "19.015","vlookat" : "-1.312","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',244707,570,2,'{"scene": {"view": {"hlookat": "-162.372","vlookat" : "18.669","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,571,2,'{"scene": {"view": {"hlookat": "-59.675","vlookat" : "-0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',244707,572,2,'{"scene": {"view": {"hlookat": "9.107","vlookat" : "5.601","fov" : "140"}}}',null,False,False,null);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 244707
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 244707;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 244707);



