DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 347907;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 347907;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 347907;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 347907;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',347907,79,1),
('1',347907,130,1),
('2',347907,193,2),
('3',347907,194,3),
('Lobby',347907,1,2),
('1',347907,15,1),
('2',347907,16,2),
('3',347907,17,3),
('Artichoke',347907,6,3),
('1',347907,25,1),
('2',347907,26,2),
('Filini',347907,28,4),
('1',347907,29,1),
('2',347907,30,2),
('3',347907,31,3),
('4',347907,32,4),
('Choka - pool bar',347907,7,5),
('1',347907,152,1),
('2',347907,153,2),
('Lookout lounge bar',347907,231,6),
('1',347907,232,1),
('2',347907,233,2),
('3',347907,234,3),
('Wedding pavillon - romantic dinner',347907,167,7),
('1',347907,168,1),
('2',347907,169,2),
('3',347907,170,3),
('4',347907,171,4),
('Pool',347907,2,8),
('1',347907,21,1),
('2',347907,92,2),
('3',347907,93,3),
('4',347907,186,4),
('Spa',347907,4,9),
('Massage room',347907,101,1),
('Massage room - bathroom',347907,102,2),
('Relaxation room - 1',347907,103,3),
('Relaxation room - 2',347907,104,4),
('Beauty salon',347907,105,5),
('Gym',347907,3,10),
('1',347907,80,1),
('2',347907,81,2),
('Yoga room',347907,82,3),
('Meeting room',347907,131,11),
(' - ',347907,132,0),
('Deluxe room',347907,568,12),
('1',347907,569,1),
('2',347907,570,2),
('3',347907,571,3),
('Bathroom',347907,572,4),
('Deluxe ocean view',347907,480,13),
('1',347907,481,1),
('2',347907,482,2),
('3',347907,483,3),
('Bathroom',347907,484,4),
('Deluxe panoramic view',347907,133,14),
('1',347907,134,1),
('2',347907,135,2),
('3',347907,137,3),
('Bathroom',347907,138,4),
('Studio suite',347907,159,15),
('1',347907,160,1),
('2',347907,161,2),
('3',347907,164,3),
('4',347907,162,4),
('Ocean view suite',347907,389,16),
('1',347907,666,1),
('2',347907,667,2),
('3',347907,668,3),
('4',347907,669,4),
('5',347907,670,5),
('6',347907,700,6),
('7',347907,701,7),
('8',347907,702,8),
('9',347907,703,9),
('10',347907,704,10);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 347907;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',347907,79,2,null,null,False,False,null),
(0,'thumb.jpg',347907,130,2,'{"scene": {"view": {"hlookat": "203.046","vlookat" : "-13.730","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,193,2,'{"scene": {"view": {"hlookat": "199.143","vlookat" : "-0.681","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,194,2,'{"scene": {"view": {"hlookat": "-44.783","vlookat" : "-10.951","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,1,2,null,null,False,False,null),
(0,'thumb.jpg',347907,15,2,'{"scene": {"view": {"hlookat": "193.338","vlookat" : "-1.546","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',347907,16,2,'{"scene": {"view": {"hlookat": "-0.001","vlookat" : "-6.559","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,17,2,'{"scene": {"view": {"hlookat": "0.164","vlookat" : "23.586","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,6,2,null,null,False,False,null),
(0,'thumb.jpg',347907,25,2,'{"scene": {"view": {"hlookat": "-0.164","vlookat" : "8.199","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',347907,26,2,'{"scene": {"view": {"hlookat": "54.921","vlookat" : "8.994","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,28,2,null,null,False,False,null),
(0,'thumb.jpg',347907,29,2,'{"scene": {"view": {"hlookat": "301.448","vlookat" : "-0.666","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',347907,30,2,'{"scene": {"view": {"hlookat": "181.381","vlookat" : "-2.178","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,31,2,'{"scene": {"view": {"hlookat": "-140.258","vlookat" : "-7.023","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,32,2,'{"scene": {"view": {"hlookat": "45.690","vlookat" : "-0.273","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,7,2,null,null,False,False,null),
(0,'thumb.jpg',347907,152,2,'{"scene": {"view": {"hlookat": "-2.461","vlookat" : "0.490","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,153,2,'{"scene": {"view": {"hlookat": "60.934","vlookat" : "4.912","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,231,2,null,null,False,False,null),
(0,'thumb.jpg',347907,232,2,'{"scene": {"view": {"hlookat": "359.087","vlookat" : "-6.891","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,233,2,'{"scene": {"view": {"hlookat": "-166.546","vlookat" : "-0.364","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,234,2,'{"scene": {"view": {"hlookat": "-14.720","vlookat" : "-0.165","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',347907,167,2,null,null,False,False,null),
(0,'thumb.jpg',347907,168,2,'{"scene": {"view": {"hlookat": "3.444","vlookat" : "11.150","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',347907,169,2,'{"scene": {"view": {"hlookat": "16.070","vlookat" : "4.591","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,170,2,'{"scene": {"view": {"hlookat": "146.626","vlookat" : "-0.756","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,171,2,'{"scene": {"view": {"hlookat": "-171.140","vlookat" : "-4.340","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,2,2,null,null,False,False,null),
(0,'thumb.jpg',347907,21,2,'{"scene": {"view": {"hlookat": "-24.196","vlookat" : "3.867","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',347907,92,2,'{"scene": {"view": {"hlookat": "97.192","vlookat" : "1.586","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,93,2,'{"scene": {"view": {"hlookat": "-14.088","vlookat" : "-0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,186,2,'{"scene": {"view": {"hlookat": "-39.185","vlookat" : "-2.131","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,4,2,null,null,False,False,null),
(0,'thumb.jpg',347907,101,2,'{"scene": {"view": {"hlookat": "-0.164","vlookat" : "22.465","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',347907,102,2,'{"scene": {"view": {"hlookat": "-31.317","vlookat" : "-0.820","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,103,2,'{"scene": {"view": {"hlookat": "0.164","vlookat" : "18.365","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,104,2,'{"scene": {"view": {"hlookat": "293.023","vlookat" : "3.675","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,105,2,'{"scene": {"view": {"hlookat": "181.660","vlookat" : "21.349","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,3,2,null,null,False,False,null),
(0,'thumb.jpg',347907,80,2,'{"scene": {"view": {"hlookat": "-33.269","vlookat" : "-1.804","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',347907,81,2,'{"scene": {"view": {"hlookat": "14.439","vlookat" : "1.474","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,82,2,'{"scene": {"view": {"hlookat": "27.704","vlookat" : "0.000","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,131,2,null,null,False,False,null),
(0,'thumb.jpg',347907,132,2,'{"scene": {"view": {"hlookat": "3.607","vlookat" : "24.760","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',347907,568,2,null,null,False,False,null),
(0,'thumb.jpg',347907,569,2,'{"scene": {"view": {"hlookat": "16.560","vlookat" : "0.820","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,570,2,'{"scene": {"view": {"hlookat": "6.722","vlookat" : "3.756","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',347907,571,2,'{"scene": {"view": {"hlookat": "-1.804","vlookat" : "24.268","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,572,2,'{"scene": {"view": {"hlookat": "54.552","vlookat" : "-0.910","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,480,2,null,null,False,False,null),
(0,'thumb.jpg',347907,481,2,'{"scene": {"view": {"hlookat": "15.905","vlookat" : "0.328","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',347907,482,2,'{"scene": {"view": {"hlookat": "345.766","vlookat" : "-1.031","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,483,2,'{"scene": {"view": {"hlookat": "177.872","vlookat" : "0.926","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,484,2,'{"scene": {"view": {"hlookat": "-18.361","vlookat" : "-1.969","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,133,2,null,null,False,False,null),
(0,'thumb.jpg',347907,134,2,'{"scene": {"view": {"hlookat": "1.476","vlookat" : "0.656","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',347907,135,2,'{"scene": {"view": {"hlookat": "177.627","vlookat" : "14.521","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,137,2,'{"scene": {"view": {"hlookat": "26.722","vlookat" : "0.326","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,138,2,'{"scene": {"view": {"hlookat": "169.373","vlookat" : "0.159","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,159,2,null,null,False,False,null),
(0,'thumb.jpg',347907,160,2,'{"scene": {"view": {"hlookat": "44.170","vlookat" : "1.475","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,161,2,'{"scene": {"view": {"hlookat": "20.986","vlookat" : "0.164","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',347907,164,2,'{"scene": {"view": {"hlookat": "166.358","vlookat" : "1.705","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,162,2,'{"scene": {"view": {"hlookat": "2.131","vlookat" : "31.317","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,389,2,null,null,False,False,null),
(0,'thumb.jpg',347907,666,2,'{"scene": {"view": {"hlookat": "222.109","vlookat" : "-0.299","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,667,2,'{"scene": {"view": {"hlookat": "33.598","vlookat" : "-0.661","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,668,2,'{"scene": {"view": {"hlookat": "359.073","vlookat" : "2.398","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,669,2,'{"scene": {"view": {"hlookat": "212.740","vlookat" : "-2.026","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,670,2,'{"scene": {"view": {"hlookat": "181.140","vlookat" : "22.524","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,700,2,'{"scene": {"view": {"hlookat": "-16.558","vlookat" : "-0.500","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,701,2,'{"scene": {"view": {"hlookat": "133.602","vlookat" : "17.515","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,702,2,'{"scene": {"view": {"hlookat": "-165.429","vlookat" : "0.733","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',347907,703,2,'{"scene": {"view": {"hlookat": "-3.768","vlookat" : "-0.164","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347907,704,2,'{"scene": {"view": {"hlookat": "1.310","vlookat" : "1.804","fov" : "140"}}}',null,False,False,null);





DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 347907
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 347907;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 347907);

