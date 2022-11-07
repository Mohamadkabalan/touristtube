DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 19843;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 19843;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 19843;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 19843;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Hotel Main Entrance Exterior',19843,556,1),
('-',19843,557,1),
('Lobby',19843,1,2),
('Reception Area',19843,15,1),
('-',19843,16,2),
('Gazebo Lobby Lounge - 1',19843,17,3),
('Gazebo Lobby Lounge - 2',19843,18,4),
('Facilities & Services At The Lobby',19843,19,5),
('Top View Lobby',19843,20,6),
('Anees Pool Bar',19843,7,3),
('-',19843,152,1),
('Roda Discovery Club',19843,8,4),
('1',19843,33,1),
('2',19843,34,2),
('Mawal Shisha Café',19843,222,5),
('1',19843,223,1),
('2',19843,224,2),
('3',19843,225,3),
('Roda Grill',19843,28,6),
('1',19843,29,1),
('2',19843,30,2),
('3',19843,31,3),
('Makan',19843,167,7),
('1',19843,168,1),
('2',19843,169,2),
('3',19843,170,3),
('4',19843,171,4),
('5',19843,197,5),
('6',19843,198,6),
('Swimming Pool and Kids Pool',19843,2,8),
('-',19843,21,1),
('Spa',19843,4,9),
('Sauna and Jacuzzi',19843,273,1),
('Treatment Rooms',19843,211,2),
('Gym',19843,3,10),
('1',19843,80,1),
('2',19843,81,2),
('3',19843,82,3),
('Conference and Event Facilities (Meeting Room 1)',19843,131,11),
('-',19843,132,1),
('Conference and Event Facilities (Meeting Room 2)',19843,293,12),
('-',19843,297,1),
('Conference and Event Facilities (VIP)',19843,294,13),
('-',19843,303,1),
('Table Tennis',19843,718,14),
('-',19843,719,1),
('Tennis Court',19843,802,15),
('-',19843,803,1),
('Classic Room - Twin Bed',19843,752,16),
('1',19843,753,1),
('2',19843,754,2),
('Bathroom',19843,755,3),
('Executive Premium Room ',19843,144,17),
('1',19843,145,1),
('2',19843,146,2),
('Bathroom',19843,148,3),
('Premium Room - King Bed',19843,480,18),
('1',19843,481,1),
('2',19843,482,2),
('Bathroom',19843,484,3),
('Classic Suite',19843,159,19),
('1',19843,160,1),
('2',19843,164,2),
('Bathroom',19843,162,3),
('Executive Suite',19843,10,20),
('1',19843,665,1),
('2',19843,49,2),
('3',19843,47,3),
('Bathroom',19843,51,4),
('Premium Suite',19843,605,21),
('1',19843,606,1),
('2',19843,607,2),
('3',19843,663,3),
('Bathroom',19843,608,4);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 19843;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',19843,556,2,null,null,False,False,null),
(0,'thumb.jpg',19843,557,2,'{"scene": {"view": {"hlookat": "0.009","vlookat" : "-24.667","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',19843,1,2,null,null,False,False,null),
(0,'thumb.jpg',19843,15,2,'{"scene": {"view": {"hlookat": "2.671","vlookat" : "1.282","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,16,2,'{"scene": {"view": {"hlookat": "90.117","vlookat" : "-2.691","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,17,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,18,2,'{"scene": {"view": {"hlookat": "2.457","vlookat" : "-0.107","fov" : "140.000"}}}',null,True,True,2),
(0,'thumb.jpg',19843,19,2,'{"scene": {"view": {"hlookat": "2.030","vlookat" : "0.320","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,20,2,'{"scene": {"view": {"hlookat": "1.282","vlookat" : "20.402","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,7,2,null,null,False,False,null),
(0,'thumb.jpg',19843,152,2,'{"scene": {"view": {"hlookat": "-17.127","vlookat" : "4.343","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,8,2,null,null,False,False,null),
(0,'thumb.jpg',19843,33,2,'{"scene": {"view": {"hlookat": "-171.370","vlookat" : "10.784","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,34,2,'{"scene": {"view": {"hlookat": "-0.534","vlookat" : "16.076","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,222,2,null,null,False,False,null),
(0,'thumb.jpg',19843,223,2,'{"scene": {"view": {"hlookat": "91.224","vlookat" : "7.628","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,224,2,'{"scene": {"view": {"hlookat": "186.028","vlookat" : "17.417","fov" : "140.000"}}}',null,True,True,3),
(0,'thumb.jpg',19843,225,2,'{"scene": {"view": {"hlookat": "-75.586","vlookat" : "15.334","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,28,2,null,null,False,False,null),
(0,'thumb.jpg',19843,29,2,'{"scene": {"view": {"hlookat": "361.518","vlookat" : "20.195","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,30,2,'{"scene": {"view": {"hlookat": "-8.332","vlookat" : "8.439","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,31,2,'{"scene": {"view": {"hlookat": "4.743","vlookat" : "20.926","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,167,2,null,null,False,False,null),
(0,'thumb.jpg',19843,168,2,'{"scene": {"view": {"hlookat": "2.155","vlookat" : "-0.352","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,169,2,'{"scene": {"view": {"hlookat": "292.626","vlookat" : "12.646","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,170,2,'{"scene": {"view": {"hlookat": "245.714","vlookat" : "18.152","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,171,2,'{"scene": {"view": {"hlookat": "2.136","vlookat" : "11.216","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,197,2,'{"scene": {"view": {"hlookat": "-28.616","vlookat" : "16.236","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,198,2,'{"scene": {"view": {"hlookat": "329.879","vlookat" : "18.156","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,2,2,null,null,False,False,null),
(0,'thumb.jpg',19843,21,2,'{"scene": {"view": {"hlookat": "35.420","vlookat" : "22.698","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,4,2,null,null,False,False,null),
(0,'thumb.jpg',19843,273,2,'{"scene": {"view": {"hlookat": "-520.531","vlookat" : "7.763","fov" : "140.000"}}}',null,True,True,4),
(0,'thumb.jpg',19843,211,2,'{"scene": {"view": {"hlookat": "23.014","vlookat" : "30.459","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,3,2,null,null,False,False,null),
(0,'thumb.jpg',19843,80,2,'{"scene": {"view": {"hlookat": "143.414","vlookat" : "7.059","fov" : "140.000"}}}',null,True,True,5),
(0,'thumb.jpg',19843,81,2,'{"scene": {"view": {"hlookat": "-227.752","vlookat" : "2.802","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,82,2,'{"scene": {"view": {"hlookat": "3.458","vlookat" : "8.193","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,131,2,null,null,False,False,null),
(0,'thumb.jpg',19843,132,2,'{"scene": {"view": {"hlookat": "182.625","vlookat" : "9.406","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,293,2,null,null,False,False,null),
(0,'thumb.jpg',19843,297,2,'{"scene": {"view": {"hlookat": "-179.599","vlookat" : "1.390","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,294,2,null,null,False,False,null),
(0,'thumb.jpg',19843,303,2,'{"scene": {"view": {"hlookat": "-21.230","vlookat" : "-0.677","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,718,2,null,null,False,False,null),
(0,'thumb.jpg',19843,719,2,'{"scene": {"view": {"hlookat": "1.963","vlookat" : "0.109","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,802,2,null,null,False,False,null),
(0,'thumb.jpg',19843,803,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "1.863","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,752,2,null,null,False,False,null),
(0,'thumb.jpg',19843,753,2,'{"scene": {"view": {"hlookat": "-12.783","vlookat" : "18.943","fov" : "140.000"}}}',null,True,True,6),
(0,'thumb.jpg',19843,754,2,'{"scene": {"view": {"hlookat": "29.351","vlookat" : "14.265","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,755,2,'{"scene": {"view": {"hlookat": "-11.011","vlookat" : "10.677","fov" : "140.000"}}}',null,True,True,7),
(0,'thumb.jpg',19843,144,2,null,null,False,False,null),
(0,'thumb.jpg',19843,145,2,'{"scene": {"view": {"hlookat": "155.706","vlookat" : "13.645","fov" : "140.000"}}}',null,True,True,8),
(0,'thumb.jpg',19843,146,2,'{"scene": {"view": {"hlookat": "29.024","vlookat" : "17.545","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,148,2,'{"scene": {"view": {"hlookat": "-217.744","vlookat" : "13.780","fov" : "140.000"}}}',null,True,True,9),
(0,'thumb.jpg',19843,480,2,null,null,False,False,null),
(0,'thumb.jpg',19843,481,2,'{"scene": {"view": {"hlookat": "16.399","vlookat" : "8.652","fov" : "140.000"}}}',null,True,True,10),
(0,'thumb.jpg',19843,482,2,'{"scene": {"view": {"hlookat": "160.245","vlookat" : "9.179","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,484,2,'{"scene": {"view": {"hlookat": "-184.754","vlookat" : "7.397","fov" : "140.000"}}}',null,True,True,11),
(0,'thumb.jpg',19843,159,2,null,null,False,False,null),
(0,'thumb.jpg',19843,160,2,'{"scene": {"view": {"hlookat": "-24.308","vlookat" : "8.156","fov" : "140.000"}}}',null,True,True,12),
(0,'thumb.jpg',19843,164,2,'{"scene": {"view": {"hlookat": "128.861","vlookat" : "9.661","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,162,2,'{"scene": {"view": {"hlookat": "157.094","vlookat" : "7.876","fov" : "140.000"}}}',null,True,True,13),
(0,'thumb.jpg',19843,10,2,null,null,False,False,null),
(0,'thumb.jpg',19843,665,2,'{"scene": {"view": {"hlookat": "22.547","vlookat" : "5.546","fov" : "140.000"}}}',null,True,True,14),
(0,'thumb.jpg',19843,49,2,'{"scene": {"view": {"hlookat": "-23.285","vlookat" : "19.349","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,47,2,'{"scene": {"view": {"hlookat": "50.007","vlookat" : "13.606","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,51,2,'{"scene": {"view": {"hlookat": "71.161","vlookat" : "11.135","fov" : "140.000"}}}',null,True,True,15),
(0,'thumb.jpg',19843,605,2,null,null,False,False,null),
(0,'thumb.jpg',19843,606,2,'{"scene": {"view": {"hlookat": "-28.461","vlookat" : "9.882","fov" : "140.000"}}}',null,True,True,16),
(0,'thumb.jpg',19843,607,2,'{"scene": {"view": {"hlookat": "0.820","vlookat" : "17.873","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,663,2,'{"scene": {"view": {"hlookat": "34.105","vlookat" : "16.560","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',19843,608,2,'{"scene": {"view": {"hlookat": "-6.393","vlookat" : "4.100","fov" : "140.000"}}}',null,True,True,17);





DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 19843
AND d.parent_id IS NULL
;


UPDATE cms_hotel set has_360 = 1 where id = 19843;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 19843;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 19843);

