DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 52575;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 52575;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 52575;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 52575;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',52575,556,1),
('-',52575,557,1),
('Main Entrance',52575,79,2),
('-',52575,130,1),
('1K Lobby',52575,1,3),
('1',52575,15,1),
('Pisco Bar',52575,16,2),
('Breakfast',52575,6,4),
('1',52575,25,1),
('2',52575,26,2),
('1K Restaurant ',52575,28,5),
('1',52575,29,1),
('2',52575,30,2),
('3',52575,31,3),
('4',52575,32,4),
('La Mezcaleria ',52575,167,6),
('1',52575,168,1),
('2',52575,169,2),
('Salon Cuzco',52575,293,7),
('1',52575,297,1),
('2',52575,298,2),
('Gym',52575,3,8),
('1',52575,80,1),
('WC',52575,81,2),
('M Superior Room',52575,486,9),
('1',52575,487,1),
('Bathroom',52575,488,2),
('L Deluxe Room ',52575,133,10),
('1',52575,134,1),
('2',52575,135,2),
('Bathroom',52575,138,3),
('XL Junior Suite',52575,212,11),
('1',52575,213,1),
('2',52575,214,2),
('3',52575,215,3),
('Bathroom - 1 ',52575,217,4),
('Bathroom - 2',52575,218,5),
('Pool Suite ',52575,1104,12),
('1',52575,1105,1),
('2',52575,1106,2),
('Bathroom - 1 ',52575,1107,3),
('Bathroom - 2',52575,1108,4),
('3',52575,1109,5),
('4',52575,1110,6),
('5',52575,1111,7);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 52575;




INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',52575,556,2,null,null,False,False,null),
(0,'thumb.jpg',52575,557,2,'{"scene": {"view": {"hlookat": "-148.687","vlookat" : "-22.312","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',52575,79,2,null,null,False,False,null),
(0,'thumb.jpg',52575,130,2,'{"scene": {"view": {"hlookat": "-7.594","vlookat" : "6.922","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',52575,1,2,null,null,False,False,null),
(0,'thumb.jpg',52575,15,2,'{"scene": {"view": {"hlookat": "-18.433","vlookat" : "6.382","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',52575,16,2,'{"scene": {"view": {"hlookat": "-27.465","vlookat" : "2.559","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,6,2,null,null,False,False,null),
(0,'thumb.jpg',52575,25,2,'{"scene": {"view": {"hlookat": "179.760","vlookat" : "2.088","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,26,2,'{"scene": {"view": {"hlookat": "-247.233","vlookat" : "6.279","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',52575,28,2,null,null,False,False,null),
(0,'thumb.jpg',52575,29,2,'{"scene": {"view": {"hlookat": "-17.743","vlookat" : "5.081","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',52575,30,2,'{"scene": {"view": {"hlookat": "-283.729","vlookat" : "0.450","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',52575,31,2,'{"scene": {"view": {"hlookat": "-338.307","vlookat" : "7.818","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,32,2,'{"scene": {"view": {"hlookat": "162.184","vlookat" : "12.073","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,167,2,null,null,False,False,null),
(0,'thumb.jpg',52575,168,2,'{"scene": {"view": {"hlookat": "-54.903","vlookat" : "8.387","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',52575,169,2,'{"scene": {"view": {"hlookat": "12.304","vlookat" : "9.191","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,293,2,null,null,False,False,null),
(0,'thumb.jpg',52575,297,2,'{"scene": {"view": {"hlookat": "-15.732","vlookat" : "1.634","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,298,2,'{"scene": {"view": {"hlookat": "-395.425","vlookat" : "7.084","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',52575,3,2,null,null,False,False,null),
(0,'thumb.jpg',52575,80,2,'{"scene": {"view": {"hlookat": "-22.313","vlookat" : "12.090","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',52575,81,2,'{"scene": {"view": {"hlookat": "-196.329","vlookat" : "2.361","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',52575,486,2,null,null,False,False,null),
(0,'thumb.jpg',52575,487,2,'{"scene": {"view": {"hlookat": "-67.729","vlookat" : "14.449","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',52575,488,2,'{"scene": {"view": {"hlookat": "4.424","vlookat" : "18.197","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,133,2,null,null,False,False,null),
(0,'thumb.jpg',52575,134,2,'{"scene": {"view": {"hlookat": "-2.788","vlookat" : "10.822","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,135,2,'{"scene": {"view": {"hlookat": "-163.284","vlookat" : "15.052","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',52575,138,2,'{"scene": {"view": {"hlookat": "-12.361","vlookat" : "15.030","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,212,2,null,null,False,False,null),
(0,'thumb.jpg',52575,213,2,'{"scene": {"view": {"hlookat": "6.458","vlookat" : "6.832","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,214,2,'{"scene": {"view": {"hlookat": "1.803","vlookat" : "12.790","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',52575,215,2,'{"scene": {"view": {"hlookat": "161.988","vlookat" : "16.679","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',52575,217,2,'{"scene": {"view": {"hlookat": "-24.913","vlookat" : "13.764","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,218,2,'{"scene": {"view": {"hlookat": "-0.526","vlookat" : "14.628","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,1104,2,null,null,False,False,null),
(0,'thumb.jpg',52575,1105,2,'{"scene": {"view": {"hlookat": "-198.694","vlookat" : "7.100","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,1106,2,'{"scene": {"view": {"hlookat": "162.317","vlookat" : "15.402","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',52575,1107,2,'{"scene": {"view": {"hlookat": "-7.293","vlookat" : "29.919","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,1108,2,'{"scene": {"view": {"hlookat": "12.190","vlookat" : "24.672","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',52575,1109,2,'{"scene": {"view": {"hlookat": "-2.405","vlookat" : "3.615","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',52575,1110,2,'{"scene": {"view": {"hlookat": "25.095","vlookat" : "23.606","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',52575,1111,2,'{"scene": {"view": {"hlookat": "-263.977","vlookat" : "22.689","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 52575
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 52575;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 52575);
