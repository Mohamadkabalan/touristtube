DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 95980;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 95980;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 95980;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 95980;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',95980,79,1),
('1',95980,130,1),
('2',95980,193,2),
('Lobby',95980,1,2),
('-',95980,15,1),
('Reception',95980,16,2),
('Cristal viewz bar and grill',95980,28,3),
('1',95980,29,1),
('2',95980,30,2),
('3',95980,31,3),
('4',95980,32,4),
('Viewz restaurant',95980,167,4),
('1',95980,168,1),
('2',95980,169,2),
('Blendz café',95980,222,5),
('-',95980,223,1),
(' Swimming pool and lounge',95980,992,6),
('1',95980,993,1),
('2',95980,994,2),
('3',95980,995,3),
('Gym',95980,3,7),
('1',95980,80,1),
('2',95980,81,2),
('Cristal lounge',95980,97,8),
('1',95980,95,1),
('2',95980,96,2),
('Jasper',95980,131,9),
('-',95980,132,1),
('Sapphire',95980,293,10),
('-',95980,297,1),
('Amber deluxe',95980,133,11),
('1',95980,134,1),
('Bathroom',95980,138,2),
('Quartz suite',95980,212,12),
('1',95980,213,1),
('2',95980,214,2),
('Bathroom',95980,217,3),
('Emerald suite',95980,517,13),
('1',95980,518,1),
('2',95980,519,2),
('Bathroom',95980,525,3);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 95980;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',95980,79,2,null,null,False,False,null),
(0,'thumb.jpg',95980,130,2,'{"scene": {"view": {"hlookat": "0.820","vlookat" : "-35.774","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',95980,193,2,'{"scene": {"view": {"hlookat": "0.316","vlookat" : "-2.624","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,1,2,null,null,False,False,null),
(0,'thumb.jpg',95980,15,2,'{"scene": {"view": {"hlookat": "-518.527","vlookat" : "2.148","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',95980,16,2,'{"scene": {"view": {"hlookat": "-126.017","vlookat" : "2.106","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',95980,28,2,null,null,False,False,null),
(0,'thumb.jpg',95980,29,2,'{"scene": {"view": {"hlookat": "3.325","vlookat" : "3.444","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',95980,30,2,'{"scene": {"view": {"hlookat": "175.236","vlookat" : "4.336","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,31,2,'{"scene": {"view": {"hlookat": "-115.363","vlookat" : "0.003","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',95980,32,2,'{"scene": {"view": {"hlookat": "30.749","vlookat" : "5.232","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,167,2,null,null,False,False,null),
(0,'thumb.jpg',95980,168,2,'{"scene": {"view": {"hlookat": "145.079","vlookat" : "10.456","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',95980,169,2,'{"scene": {"view": {"hlookat": "90.781","vlookat" : "13.346","fov" : "140"}}}',null,False,False,7),
(0,'thumb.jpg',95980,222,2,null,null,False,False,null),
(0,'thumb.jpg',95980,223,2,'{"scene": {"view": {"hlookat": "390.639","vlookat" : "3.278","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',95980,992,2,null,null,False,False,null),
(0,'thumb.jpg',95980,993,2,'{"scene": {"view": {"hlookat": "-6.459","vlookat" : "25.248","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',95980,994,2,'{"scene": {"view": {"hlookat": "142.781","vlookat" : "9.376","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,995,2,'{"scene": {"view": {"hlookat": "-247.444","vlookat" : "9.733","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,3,2,null,null,False,False,null),
(0,'thumb.jpg',95980,80,2,'{"scene": {"view": {"hlookat": "-891.171","vlookat" : "10.418","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',95980,81,2,'{"scene": {"view": {"hlookat": "-219.162","vlookat" : "2.541","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,97,2,null,null,False,False,null),
(0,'thumb.jpg',95980,95,2,'{"scene": {"view": {"hlookat": "180.535","vlookat" : "1.148","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,96,2,'{"scene": {"view": {"hlookat": "-1.281","vlookat" : "31.328","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',95980,131,2,null,null,False,False,null),
(0,'thumb.jpg',95980,132,2,'{"scene": {"view": {"hlookat": "-182.250","vlookat" : "15.565","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',95980,293,2,null,null,False,False,null),
(0,'thumb.jpg',95980,297,2,'{"scene": {"view": {"hlookat": "363.629","vlookat" : "16.994","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',95980,133,2,null,null,False,False,null),
(0,'thumb.jpg',95980,134,2,'{"scene": {"view": {"hlookat": "1.333","vlookat" : "30.658","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',95980,138,2,'{"scene": {"view": {"hlookat": "4.904","vlookat" : "21.624","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,212,2,null,null,False,False,null),
(0,'thumb.jpg',95980,213,2,'{"scene": {"view": {"hlookat": "-224.885","vlookat" : "13.415","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',95980,214,2,'{"scene": {"view": {"hlookat": "1.313","vlookat" : "28.848","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',95980,217,2,'{"scene": {"view": {"hlookat": "327.611","vlookat" : "18.091","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',95980,517,2,null,null,False,False,null),
(0,'thumb.jpg',95980,518,2,'{"scene": {"view": {"hlookat": "22.625","vlookat" : "19.110","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',95980,519,2,'{"scene": {"view": {"hlookat": "-201.135","vlookat" : "24.058","fov" : "140"}}}',null,True,True,18),
(0,'thumb.jpg',95980,525,2,'{"scene": {"view": {"hlookat": "235.300","vlookat" : "14.985","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 95980
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 95980;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 95980);
