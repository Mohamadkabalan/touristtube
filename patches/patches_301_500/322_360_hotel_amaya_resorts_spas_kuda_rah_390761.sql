DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 390761;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 390761;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 390761;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 390761;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',390761,79,1),
('Arrival jetty',390761,130,1),
('1',390761,193,2),
('2',390761,194,3),
('3',390761,195,4),
('Lobby',390761,1,2),
('-',390761,15,1),
('Sea spray',390761,28,3),
('1',390761,29,1),
('2',390761,30,2),
('Private dinning',390761,167,4),
('-',390761,168,1),
('Half lime',390761,173,5),
('1',390761,174,1),
('2',390761,175,2),
('3',390761,176,3),
('4',390761,177,4),
('5',390761,178,5),
('Glow bar',390761,7,6),
('-',390761,152,1),
('Bluez bar',390761,231,7),
('1',390761,232,1),
('2',390761,233,2),
('Dinning area backyard',390761,188,8),
('1',390761,189,1),
('2',390761,190,2),
('Games room',390761,464,9),
('1',390761,465,1),
('2',390761,466,2),
('Pool',390761,2,10),
('1',390761,21,1),
('2',390761,92,2),
('3',390761,93,3),
('Jetty',390761,730,11),
('-',390761,731,1),
('Pavillon',390761,1315,12),
('-',390761,1316,1),
('Amaya spa by mandara',390761,4,13),
('1',390761,101,1),
('2',390761,102,2),
('3',390761,103,3),
('4',390761,104,4),
('5',390761,105,5),
('Fitness Centre',390761,3,14),
('1',390761,80,1),
('2',390761,81,2),
('Nemo club for kids',390761,84,15),
('1',390761,85,1),
('2',390761,86,2),
('Tpv by three',390761,746,16),
('-',390761,747,1),
('Beach suite',390761,1104,17),
('1',390761,1105,1),
('2',390761,1106,2),
('3',390761,1107,3),
('4',390761,1108,4),
('5',390761,1109,5),
('Beach villa',390761,758,18),
('Beach villa - 1',390761,759,1),
('Beach villa - 1st floor - 2',390761,760,2),
('Beach villa - 1st floor - 3',390761,761,3),
('Beach villa - 1st floor - bathroom - 4',390761,762,4),
('Beach villa - 2nd floor - 5',390761,763,5),
('Beach villa - 2nd floor - 6',390761,764,6),
('Beach villa - 2nd floor - 7',390761,765,7),
('Beach villa - 2nd floor - bathroom - 8',390761,766,8),
('Water villa',390761,769,19),
('1',390761,770,1),
('2',390761,771,2),
('Bathroom',390761,772,3),
('Family duplex beach villa',390761,780,20),
('1st floor - 1',390761,781,1),
('1st floor - 2',390761,782,2),
('1st floor - 3',390761,783,3),
('1st floor - 4',390761,784,4),
('1st floor - bathroom',390761,785,5),
('2nd floor - 1',390761,786,6),
('2nd floor - bathroom',390761,787,7),
('Presidental suite',390761,1125,21),
('1',390761,1126,1),
('2',390761,1127,2),
('3',390761,1129,3),
('Bathroom - 1',390761,1130,4),
('Bathroom - 2',390761,1131,5),
('Guest bathroom',390761,1132,6),
('Sea view 1',390761,1133,7),
('Sea view 2',390761,1134,8),
('Water sports',390761,941,22),
('-',390761,942,1),
('Diving center',390761,1005,23),
('1',390761,1006,1),
('2',390761,1007,2),
('Beach volleyball',390761,1016,24),
('-',390761,1017,1);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 390761;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',390761,79,2,null,null,False,False,null),
(0,'thumb.jpg',390761,130,2,'{"scene": {"view": {"hlookat": "380.633","vlookat" : "5.403","fov" : "139.381"}}}',null,False,False,null),
(0,'thumb.jpg',390761,193,2,'{"scene": {"view": {"hlookat": "325.271","vlookat" : "0.576","fov" : "139.265"}}}',null,False,False,null),
(0,'thumb.jpg',390761,194,2,'{"scene": {"view": {"hlookat": "317.914","vlookat" : "-9.656","fov" : "138.659"}}}',null,True,True,1),
(0,'thumb.jpg',390761,195,2,'{"scene": {"view": {"hlookat": "367.485","vlookat" : "0.000","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1,2,null,null,False,False,null),
(0,'thumb.jpg',390761,15,2,'{"scene": {"view": {"hlookat": "348.521","vlookat" : "-0.336","fov" : "139.002"}}}',null,True,True,2),
(0,'thumb.jpg',390761,28,2,null,null,False,False,null),
(0,'thumb.jpg',390761,29,2,'{"scene": {"view": {"hlookat": "345.289","vlookat" : "4.870","fov" : "139.512"}}}',null,True,True,3),
(0,'thumb.jpg',390761,30,2,'{"scene": {"view": {"hlookat": "-0.088","vlookat" : "-6.027","fov" : "139.764"}}}',null,False,False,null),
(0,'thumb.jpg',390761,167,2,null,null,False,False,null),
(0,'thumb.jpg',390761,168,2,'{"scene": {"view": {"hlookat": "6.616","vlookat" : "0.313","fov" : "139.072"}}}',null,True,True,4),
(0,'thumb.jpg',390761,173,2,null,null,False,False,null),
(0,'thumb.jpg',390761,174,2,'{"scene": {"view": {"hlookat": "-133.718","vlookat" : "-0.001","fov" : "139.487"}}}',null,False,False,null),
(0,'thumb.jpg',390761,175,2,'{"scene": {"view": {"hlookat": "314.588","vlookat" : "-2.180","fov" : "139.436"}}}',null,True,True,5),
(0,'thumb.jpg',390761,176,2,'{"scene": {"view": {"hlookat": "340.408","vlookat" : "-30.225","fov" : "139.037"}}}',null,False,False,null),
(0,'thumb.jpg',390761,177,2,'{"scene": {"view": {"hlookat": "532.396","vlookat" : "-27.476","fov" : "139.584"}}}',null,False,False,null),
(0,'thumb.jpg',390761,178,2,'{"scene": {"view": {"hlookat": "274.213","vlookat" : "-6.614","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',390761,7,2,null,null,False,False,null),
(0,'thumb.jpg',390761,152,2,'{"scene": {"view": {"hlookat": "178.834","vlookat" : "1.072","fov" : "139.381"}}}',null,True,True,6),
(0,'thumb.jpg',390761,231,2,null,null,False,False,null),
(0,'thumb.jpg',390761,232,2,'{"scene": {"view": {"hlookat": "19.746","vlookat" : "-1.270","fov" : "139.512"}}}',null,True,True,7),
(0,'thumb.jpg',390761,233,2,'{"scene": {"view": {"hlookat": "336.994","vlookat" : "-0.796","fov" : "139.606"}}}',null,False,False,null),
(0,'thumb.jpg',390761,188,2,null,null,False,False,null),
(0,'thumb.jpg',390761,189,2,'{"scene": {"view": {"hlookat": "20.757","vlookat" : "12.298","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',390761,190,2,'{"scene": {"view": {"hlookat": "360.913","vlookat" : "-4.003","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',390761,464,2,null,null,False,False,null),
(0,'thumb.jpg',390761,465,2,'{"scene": {"view": {"hlookat": "-20.683","vlookat" : "1.296","fov" : "139.139"}}}',null,True,True,8),
(0,'thumb.jpg',390761,466,2,'{"scene": {"view": {"hlookat": "-231.373","vlookat" : "15.477","fov" : "139.381"}}}',null,False,False,null),
(0,'thumb.jpg',390761,2,2,null,null,False,False,null),
(0,'thumb.jpg',390761,21,2,'{"scene": {"view": {"hlookat": "-149.392","vlookat" : "8.995","fov" : "139.321"}}}',null,True,True,9),
(0,'thumb.jpg',390761,92,2,'{"scene": {"view": {"hlookat": "373.475","vlookat" : "-0.085","fov" : "139.295"}}}',null,False,False,null),
(0,'thumb.jpg',390761,93,2,'{"scene": {"view": {"hlookat": "-360.551","vlookat" : "20.745","fov" : "139.462"}}}',null,False,False,null),
(0,'thumb.jpg',390761,730,2,null,null,False,False,null),
(0,'thumb.jpg',390761,731,2,'{"scene": {"view": {"hlookat": "400.289","vlookat" : "23.791","fov" : "139.436"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1315,2,null,null,False,False,null),
(0,'thumb.jpg',390761,1316,2,'{"scene": {"view": {"hlookat": "-147.210","vlookat" : "7.806","fov" : "139.353"}}}',null,False,False,null),
(0,'thumb.jpg',390761,4,2,null,null,False,False,null),
(0,'thumb.jpg',390761,101,2,'{"scene": {"view": {"hlookat": "-4.388","vlookat" : "-3.763","fov" : "139.746"}}}',null,False,False,null),
(0,'thumb.jpg',390761,102,2,'{"scene": {"view": {"hlookat": "364.727","vlookat" : "1.706","fov" : "139.915"}}}',null,False,False,null),
(0,'thumb.jpg',390761,103,2,'{"scene": {"view": {"hlookat": "532.419","vlookat" : "3.747","fov" : "139.324"}}}',null,False,False,null),
(0,'thumb.jpg',390761,104,2,'{"scene": {"view": {"hlookat": "359.861","vlookat" : "1.696","fov" : "139.561"}}}',null,False,False,null),
(0,'thumb.jpg',390761,105,2,'{"scene": {"view": {"hlookat": "2.960","vlookat" : "18.624","fov" : "139.662"}}}',null,True,True,10),
(0,'thumb.jpg',390761,3,2,null,null,False,False,null),
(0,'thumb.jpg',390761,80,2,'{"scene": {"view": {"hlookat": "-266.108","vlookat" : "0.096","fov" : "138.659"}}}',null,True,True,11),
(0,'thumb.jpg',390761,81,2,'{"scene": {"view": {"hlookat": "7.283","vlookat" : "-0.587","fov" : "139.709"}}}',null,False,False,null),
(0,'thumb.jpg',390761,84,2,null,null,False,False,null),
(0,'thumb.jpg',390761,85,2,'{"scene": {"view": {"hlookat": "155.202","vlookat" : "13.898","fov" : "139.608"}}}',null,False,False,null),
(0,'thumb.jpg',390761,86,2,'{"scene": {"view": {"hlookat": "-84.070","vlookat" : "5.272","fov" : "139.670"}}}',null,False,False,null),
(0,'thumb.jpg',390761,746,2,null,null,False,False,null),
(0,'thumb.jpg',390761,747,2,'{"scene": {"view": {"hlookat": "-114.808","vlookat" : "-0.800","fov" : "139.584"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1104,2,null,null,False,False,null),
(0,'thumb.jpg',390761,1105,2,'{"scene": {"view": {"hlookat": "3.638","vlookat" : "-12.253","fov" : "138.786"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1106,2,'{"scene": {"view": {"hlookat": "342.979","vlookat" : "11.696","fov" : "139.226"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1107,2,'{"scene": {"view": {"hlookat": "-2.612","vlookat" : "0.869","fov" : "139.709"}}}',null,True,True,12),
(0,'thumb.jpg',390761,1108,2,'{"scene": {"view": {"hlookat": "346.846","vlookat" : "4.949","fov" : "139.893"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1109,2,'{"scene": {"view": {"hlookat": "357.180","vlookat" : "-0.026","fov" : "139.537"}}}',null,False,False,null),
(0,'thumb.jpg',390761,758,2,null,null,False,False,null),
(0,'thumb.jpg',390761,759,2,'{"scene": {"view": {"hlookat": "174.608","vlookat" : "-8.571","fov" : "136.691"}}}',null,False,False,null),
(0,'thumb.jpg',390761,760,2,'{"scene": {"view": {"hlookat": "174.825","vlookat" : "10.756","fov" : "139.690"}}}',null,True,True,13),
(0,'thumb.jpg',390761,761,2,'{"scene": {"view": {"hlookat": "361.355","vlookat" : "14.018","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',390761,762,2,'{"scene": {"view": {"hlookat": "-362.486","vlookat" : "10.231","fov" : "139.670"}}}',null,False,False,null),
(0,'thumb.jpg',390761,763,2,'{"scene": {"view": {"hlookat": "4.384","vlookat" : "0.375","fov" : "139.462"}}}',null,False,False,null),
(0,'thumb.jpg',390761,764,2,'{"scene": {"view": {"hlookat": "0.863","vlookat" : "0.000","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',390761,765,2,'{"scene": {"view": {"hlookat": "-13.585","vlookat" : "1.504","fov" : "136.649"}}}',null,False,False,null),
(0,'thumb.jpg',390761,766,2,'{"scene": {"view": {"hlookat": "-349.194","vlookat" : "23.455","fov" : "139.925"}}}',null,False,False,null),
(0,'thumb.jpg',390761,769,2,null,null,False,False,null),
(0,'thumb.jpg',390761,770,2,'{"scene": {"view": {"hlookat": "176.954","vlookat" : "8.036","fov" : "139.915"}}}',null,True,True,14),
(0,'thumb.jpg',390761,771,2,'{"scene": {"view": {"hlookat": "-90.259","vlookat" : "2.510","fov" : "139.934"}}}',null,False,False,null),
(0,'thumb.jpg',390761,772,2,'{"scene": {"view": {"hlookat": "93.418","vlookat" : "31.150","fov" : "139.781"}}}',null,False,False,null),
(0,'thumb.jpg',390761,780,2,null,null,False,False,null),
(0,'thumb.jpg',390761,781,2,'{"scene": {"view": {"hlookat": "372.794","vlookat" : "-16.647","fov" : "139.746"}}}',null,False,False,null),
(0,'thumb.jpg',390761,782,2,'{"scene": {"view": {"hlookat": "177.227","vlookat" : "2.585","fov" : "139.499"}}}',null,True,True,15),
(0,'thumb.jpg',390761,783,2,'{"scene": {"view": {"hlookat": "-88.559","vlookat" : "-0.468","fov" : "139.987"}}}',null,False,False,null),
(0,'thumb.jpg',390761,784,2,'{"scene": {"view": {"hlookat": "-90.185","vlookat" : "-5.008","fov" : "139.951"}}}',null,False,False,null),
(0,'thumb.jpg',390761,785,2,'{"scene": {"view": {"hlookat": "183.916","vlookat" : "0.245","fov" : "139.313"}}}',null,False,False,null),
(0,'thumb.jpg',390761,786,2,'{"scene": {"view": {"hlookat": "165.829","vlookat" : "-0.047","fov" : "139.827"}}}',null,False,False,null),
(0,'thumb.jpg',390761,787,2,'{"scene": {"view": {"hlookat": "-350.167","vlookat" : "26.783","fov" : "139.934"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1125,2,null,null,False,False,null),
(0,'thumb.jpg',390761,1126,2,'{"scene": {"view": {"hlookat": "357.965","vlookat" : "1.491","fov" : "139.959"}}}',null,True,True,16),
(0,'thumb.jpg',390761,1127,2,'{"scene": {"view": {"hlookat": "2.711","vlookat" : "2.172","fov" : "138.796"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1129,2,'{"scene": {"view": {"hlookat": "176.921","vlookat" : "2.877","fov" : "139.106"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1130,2,'{"scene": {"view": {"hlookat": "178.951","vlookat" : "6.961","fov" : "139.746"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1131,2,'{"scene": {"view": {"hlookat": "362.366","vlookat" : "2.150","fov" : "139.690"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1132,2,'{"scene": {"view": {"hlookat": "218.670","vlookat" : "1.003","fov" : "139.856"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1133,2,'{"scene": {"view": {"hlookat": "-31.867","vlookat" : "11.791","fov" : "139.295"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1134,2,'{"scene": {"view": {"hlookat": "-9.710","vlookat" : "14.463","fov" : "139.915"}}}',null,False,False,null),
(0,'thumb.jpg',390761,941,2,null,null,False,False,null),
(0,'thumb.jpg',390761,942,2,'{"scene": {"view": {"hlookat": "224.894","vlookat" : "-13.383","fov" : "139.636"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1005,2,null,null,False,False,null),
(0,'thumb.jpg',390761,1006,2,'{"scene": {"view": {"hlookat": "-3.369","vlookat" : "4.634","fov" : "139.436"}}}',null,True,True,17),
(0,'thumb.jpg',390761,1007,2,'{"scene": {"view": {"hlookat": "161.394","vlookat" : "7.752","fov" : "139.200"}}}',null,False,False,null),
(0,'thumb.jpg',390761,1016,2,null,null,False,False,null),
(0,'thumb.jpg',390761,1017,2,'{"scene": {"view": {"hlookat": "-152.795","vlookat" : "0.343","fov" : "139.584"}}}',null,False,False,null);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 390761
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 390761;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 390761);

