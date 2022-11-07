DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 149740;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 149740;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 149740;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 149740;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('street view',149740,556,1),
(' - ',149740,557,1),
('Lobby',149740,1,2),
('Reception',149740,15,1),
('1',149740,16,2),
('2',149740,17,3),
('Oak Restaurant',149740,28,3),
('1',149740,29,1),
('2',149740,30,2),
('3',149740,31,3),
('4',149740,32,4),
('Oak Restaurant Bar',149740,231,4),
('1',149740,232,1),
('2',149740,233,2),
('Pool',149740,2,5),
('-',149740,21,1),
('Pool Bar',149740,93,2),
('Gym',149740,3,6),
('-',149740,80,1),
('Spa',149740,4,7),
('-',149740,101,1),
('Wooden Deck',149740,730,8),
('1',149740,731,1),
('2',149740,732,2),
('Ballroom',149740,131,9),
('1',149740,132,1),
('2',149740,252,2),
('Deluxe Room - King Bed',149740,133,10),
('-',149740,134,1),
('Bathroom',149740,138,2),
('Deluxe Room -Twin Bed',149740,139,11),
('-',149740,140,1),
('Bathroom',149740,143,2),
('Grand Deluxe - King Bed',149740,546,12),
('1',149740,547,1),
('2',149740,548,2),
('Bathroom',149740,550,2),
('Grand Deluxe - Twin Bed',149740,626,13),
('-',149740,627,1),
('Bathroom',149740,630,2),
('Junior Suite',149740,967,14),
('1',149740,968,1),
('2',149740,969,2),
('Bathroom',149740,970,2),
('Executive Suite',149740,10,15),
('1',149740,47,1),
('2',149740,48,2),
('3',149740,50,3),
('Bathroom',149740,51,4);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 149740;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',149740,556,2,null,null,False,False,null),
(0,'thumb.jpg',149740,557,2,'{"scene": {"view": {"hlookat": "-41.635","vlookat" : "2.284","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',149740,1,2,null,null,False,False,null),
(0,'thumb.jpg',149740,15,2,'{"scene": {"view": {"hlookat": "-2.132","vlookat" : "-10.165","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,16,2,'{"scene": {"view": {"hlookat": "-395.970","vlookat" : "5.563","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',149740,17,2,'{"scene": {"view": {"hlookat": "348.051","vlookat" : "3.822","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,28,2,null,null,False,False,null),
(0,'thumb.jpg',149740,29,2,'{"scene": {"view": {"hlookat": "18.125","vlookat" : "-1.664","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,30,2,'{"scene": {"view": {"hlookat": "-7.182","vlookat" : "5.181","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',149740,31,2,'{"scene": {"view": {"hlookat": "22.217","vlookat" : "-0.969","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,32,2,'{"scene": {"view": {"hlookat": "328.483","vlookat" : "-0.332","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,231,2,null,null,False,False,null),
(0,'thumb.jpg',149740,232,2,'{"scene": {"view": {"hlookat": "196.826","vlookat" : "-0.949","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,233,2,'{"scene": {"view": {"hlookat": "-16.277","vlookat" : "0.503","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',149740,2,2,null,null,False,False,null),
(0,'thumb.jpg',149740,21,2,'{"scene": {"view": {"hlookat": "-15.557","vlookat" : "-2.922","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',149740,93,2,'{"scene": {"view": {"hlookat": "38.533","vlookat" : "-0.656","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,3,2,null,null,false,false,null),
(0,'thumb.jpg',149740,80,2,'{"scene": {"view": {"hlookat": "17.709","vlookat" : "3.935","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',149740,4,2,null,null,False,False,null),
(0,'thumb.jpg',149740,101,2,'{"scene": {"view": {"hlookat": "164.328","vlookat" : "0.631","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',149740,730,2,null,null,False,False,null),
(0,'thumb.jpg',149740,731,2,'{"scene": {"view": {"hlookat": "-4.752","vlookat" : "0.328","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,732,2,'{"scene": {"view": {"hlookat": "-13.080","vlookat" : "-0.817","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',149740,131,2,null,null,False,False,null),
(0,'thumb.jpg',149740,132,2,'{"scene": {"view": {"hlookat": "166.548","vlookat" : "2.992","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,252,2,'{"scene": {"view": {"hlookat": "176.570","vlookat" : "-2.176","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',149740,133,2,null,null,False,False,null),
(0,'thumb.jpg',149740,134,2,'{"scene": {"view": {"hlookat": "-30.004","vlookat" : "2.131","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',149740,138,2,'{"scene": {"view": {"hlookat": "17.872","vlookat" : "-0.492","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,139,2,null,null,False,False,null),
(0,'thumb.jpg',149740,140,2,'{"scene": {"view": {"hlookat": "-17.872","vlookat" : "0.328","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',149740,143,2,'{"scene": {"view": {"hlookat": "45.583","vlookat" : "-0.820","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,546,2,null,null,False,False,null),
(0,'thumb.jpg',149740,547,2,'{"scene": {"view": {"hlookat": "18.364","vlookat" : "-1.125","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',149740,548,2,'{"scene": {"view": {"hlookat": "-32.288","vlookat" : "1.638","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,550,2,'{"scene": {"view": {"hlookat": "-10.213","vlookat" : "-1.388","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,626,2,null,null,False,False,null),
(0,'thumb.jpg',149740,627,2,'{"scene": {"view": {"hlookat": "18.361","vlookat" : "-0.492","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',149740,630,2,'{"scene": {"view": {"hlookat": "331.981","vlookat" : "-3.337","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,967,2,null,null,False,False,null),
(0,'thumb.jpg',149740,968,2,'{"scene": {"view": {"hlookat": "56.729","vlookat" : "0.656","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',149740,969,2,'{"scene": {"view": {"hlookat": "338.924","vlookat" : "-0.774","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,970,2,'{"scene": {"view": {"hlookat": "187.555","vlookat" : "15.973","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,10,2,null,null,False,False,null),
(0,'thumb.jpg',149740,47,2,'{"scene": {"view": {"hlookat": "-15.738","vlookat" : "-1.640","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,48,2,'{"scene": {"view": {"hlookat": "72.805","vlookat" : "0.820","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',149740,50,2,'{"scene": {"view": {"hlookat": "164.638","vlookat" : "1.691","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',149740,51,2,'{"scene": {"view": {"hlookat": "-133.109","vlookat" : "-0.697","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 149740
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 149740;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 149740);

