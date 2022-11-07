DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 184777;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 184777;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 184777;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 184777;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',184777,79,1),
('1',184777,130,1),
('2',184777,193,2),
('Lobby',184777,1,2),
('1',184777,15,1),
('2',184777,16,2),
('3',184777,17,3),
('4',184777,18,4),
('5',184777,19,5),
('6',184777,20,6),
('7',184777,90,7),
('8',184777,91,8),
('Gading Restaurant',184777,28,3),
('1',184777,29,1),
('2',184777,30,2),
('3',184777,31,3),
('Homaya Restaurant',184777,167,4),
('-',184777,168,1),
('Ja\'jan Bistro',184777,173,5),
('-',184777,174,1),
('Ja\'jan By The Beach',184777,188,6),
('-',184777,189,1),
('The Beach',184777,722,7),
('1',184777,723,1),
('2',184777,724,2),
('3',184777,725,3),
('4',184777,726,4),
('Pool',184777,2,8),
('1',184777,21,1),
('2',184777,92,2),
('3',184777,186,3),
('4',184777,187,4),
('Inaya Dewi Spa',184777,4,9),
('1',184777,101,1),
('2',184777,102,2),
('3',184777,103,3),
('4',184777,104,4),
('Tamaya Kids',184777,84,10),
('-',184777,85,1),
('Deluxe Room',184777,568,11),
('1',184777,569,1),
('2',184777,570,2),
('3',184777,572,3),
('Deluxe Pool Access',184777,374,12),
('1',184777,375,1),
('2',184777,379,2),
('3',184777,383,3),
('4',184777,385,4),
('One Bedroom Suite Garden View',184777,159,13),
('1',184777,589,1),
('2',184777,160,2),
('3',184777,164,3),
('4',184777,161,4),
('5',184777,162,5),
('6',184777,495,6),
('One Bedroom Suite Ocean View',184777,212,14),
('1',184777,529,1),
('2',184777,213,2),
('3',184777,214,3),
('4',184777,216,4),
('5',184777,217,5),
('One Bedroom Villa',184777,758,15),
('1',184777,759,1),
('2',184777,760,2),
('3',184777,761,3),
('4',184777,762,4),
('5',184777,763,5),
('Water Sport',184777,464,16),
('1',184777,465,1),
('2',184777,466,2);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 184777;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',184777,79,2,null,null,False,False,null),
(0,'thumb.jpg',184777,130,2,'{"scene": {"view": {"hlookat": "691.465","vlookat" : "7.103","fov" : "139.277"}}}',null,False,False,null),
(0,'thumb.jpg',184777,193,2,'{"scene": {"view": {"hlookat": "-4.922","vlookat" : "-11.367","fov" : "139.781"}}}',null,False,False,null),
(0,'thumb.jpg',184777,1,2,null,null,False,False,null),
(0,'thumb.jpg',184777,15,2,'{"scene": {"view": {"hlookat": "163.182","vlookat" : "-13.461","fov" : "139.874"}}}',null,False,False,null),
(0,'thumb.jpg',184777,16,2,'{"scene": {"view": {"hlookat": "195.469","vlookat" : "-10.253","fov" : "139.814"}}}',null,True,True,1),
(0,'thumb.jpg',184777,17,2,'{"scene": {"view": {"hlookat": "-30.005","vlookat" : "12.430","fov" : "139.431"}}}',null,False,False,null),
(0,'thumb.jpg',184777,18,2,'{"scene": {"view": {"hlookat": "2.434","vlookat" : "0.989","fov" : "139.670"}}}',null,False,False,null),
(0,'thumb.jpg',184777,19,2,'{"scene": {"view": {"hlookat": "0.705","vlookat" : "0.000","fov" : "139.295"}}}',null,False,False,null),
(0,'thumb.jpg',184777,20,2,'{"scene": {"view": {"hlookat": "11655.863","vlookat" : "-8.695","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',184777,90,2,'{"scene": {"view": {"hlookat": "-18.299","vlookat" : "0.427","fov" : "139.728"}}}',null,False,False,null),
(0,'thumb.jpg',184777,91,2,'{"scene": {"view": {"hlookat": "450.042","vlookat" : "1.149","fov" : "139.561"}}}',null,False,False,null),
(0,'thumb.jpg',184777,28,2,null,null,False,False,null),
(0,'thumb.jpg',184777,29,2,'{"scene": {"view": {"hlookat": "-254.948","vlookat" : "-0.866","fov" : "140.000"}}}',null,True,True,2),
(0,'thumb.jpg',184777,30,2,'{"scene": {"view": {"hlookat": "166.927","vlookat" : "-1.167","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,31,2,'{"scene": {"view": {"hlookat": "167.262","vlookat" : "-0.755","fov" : "139.999"}}}',null,False,False,null),
(0,'thumb.jpg',184777,167,2,null,null,False,False,null),
(0,'thumb.jpg',184777,168,2,'{"scene": {"view": {"hlookat": "118.380","vlookat" : "2.217","fov" : "140.000"}}}',null,True,True,3),
(0,'thumb.jpg',184777,173,2,null,null,False,False,null),
(0,'thumb.jpg',184777,174,2,'{"scene": {"view": {"hlookat": "420.931","vlookat" : "8.960","fov" : "139.689"}}}',null,True,True,4),
(0,'thumb.jpg',184777,188,2,null,null,False,False,null),
(0,'thumb.jpg',184777,189,2,'{"scene": {"view": {"hlookat": "-22.026","vlookat" : "-3.334","fov" : "139.649"}}}',null,True,True,5),
(0,'thumb.jpg',184777,722,2,null,null,False,False,null),
(0,'thumb.jpg',184777,723,2,'{"scene": {"view": {"hlookat": "267.198","vlookat" : "1.498","fov" : "139.966"}}}',null,True,True,6),
(0,'thumb.jpg',184777,724,2,'{"scene": {"view": {"hlookat": "-310.908","vlookat" : "7.496","fov" : "139.764"}}}',null,False,False,null),
(0,'thumb.jpg',184777,725,2,'{"scene": {"view": {"hlookat": "-136.939","vlookat" : "6.764","fov" : "139.959"}}}',null,False,False,null),
(0,'thumb.jpg',184777,726,2,'{"scene": {"view": {"hlookat": "-340.140","vlookat" : "-8.244","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',184777,2,2,null,null,False,False,null),
(0,'thumb.jpg',184777,21,2,'{"scene": {"view": {"hlookat": "10.456","vlookat" : "20.339","fov" : "139.542"}}}',null,True,True,7),
(0,'thumb.jpg',184777,92,2,'{"scene": {"view": {"hlookat": "131.966","vlookat" : "-1.138","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,186,2,'{"scene": {"view": {"hlookat": "377.611","vlookat" : "6.940","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,187,2,'{"scene": {"view": {"hlookat": "-11.560","vlookat" : "3.923","fov" : "139.951"}}}',null,False,False,null),
(0,'thumb.jpg',184777,4,2,null,null,False,False,null),
(0,'thumb.jpg',184777,101,2,'{"scene": {"view": {"hlookat": "230.757","vlookat" : "-3.700","fov" : "139.998"}}}',null,False,False,null),
(0,'thumb.jpg',184777,102,2,'{"scene": {"view": {"hlookat": "5.917","vlookat" : "10.055","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',184777,103,2,'{"scene": {"view": {"hlookat": "332.812","vlookat" : "5.199","fov" : "138.812"}}}',null,True,True,8),
(0,'thumb.jpg',184777,104,2,'{"scene": {"view": {"hlookat": "163.460","vlookat" : "6.266","fov" : "139.951"}}}',null,False,False,null),
(0,'thumb.jpg',184777,84,2,null,null,False,False,null),
(0,'thumb.jpg',184777,85,2,'{"scene": {"view": {"hlookat": "54.343","vlookat" : "5.143","fov" : "94.846"}}}',null,True,True,9),
(0,'thumb.jpg',184777,568,2,null,null,False,False,null),
(0,'thumb.jpg',184777,569,2,'{"scene": {"view": {"hlookat": "173.695","vlookat" : "13.613","fov" : "139.951"}}}',null,True,True,10),
(0,'thumb.jpg',184777,570,2,'{"scene": {"view": {"hlookat": "1.901","vlookat" : "0.697","fov" : "139.670"}}}',null,False,False,null),
(0,'thumb.jpg',184777,572,2,'{"scene": {"view": {"hlookat": "370.424","vlookat" : "16.861","fov" : "137.968"}}}',null,True,True,11),
(0,'thumb.jpg',184777,374,2,null,null,False,False,null),
(0,'thumb.jpg',184777,375,2,'{"scene": {"view": {"hlookat": "178.740","vlookat" : "24.227","fov" : "139.881"}}}',null,True,True,12),
(0,'thumb.jpg',184777,379,2,'{"scene": {"view": {"hlookat": "342.865","vlookat" : "-0.731","fov" : "138.064"}}}',null,False,False,null),
(0,'thumb.jpg',184777,383,2,'{"scene": {"view": {"hlookat": "39759.947","vlookat" : "0.448","fov" : "139.943"}}}',null,False,False,null),
(0,'thumb.jpg',184777,385,2,'{"scene": {"view": {"hlookat": "178.632","vlookat" : "0.394","fov" : "138.618"}}}',null,True,True,13),
(0,'thumb.jpg',184777,159,2,null,null,False,False,null),
(0,'thumb.jpg',184777,589,2,'{"scene": {"view": {"hlookat": "193.174","vlookat" : "13.431","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,160,2,'{"scene": {"view": {"hlookat": "2.836","vlookat" : "5.852","fov" : "139.977"}}}',null,True,True,14),
(0,'thumb.jpg',184777,164,2,'{"scene": {"view": {"hlookat": "272.147","vlookat" : "0.479","fov" : "139.577"}}}',null,False,False,null),
(0,'thumb.jpg',184777,161,2,'{"scene": {"view": {"hlookat": "169.355","vlookat" : "0.214","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,162,2,'{"scene": {"view": {"hlookat": "355.676","vlookat" : "10.302","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,495,2,'{"scene": {"view": {"hlookat": "-291.202","vlookat" : "8.524","fov" : "139.893"}}}',null,False,False,null),
(0,'thumb.jpg',184777,212,2,null,null,False,False,null),
(0,'thumb.jpg',184777,529,2,'{"scene": {"view": {"hlookat": "580.516","vlookat" : "4.867","fov" : "139.977"}}}',null,False,False,null),
(0,'thumb.jpg',184777,213,2,'{"scene": {"view": {"hlookat": "272.711","vlookat" : "7.641","fov" : "139.991"}}}',null,False,False,null),
(0,'thumb.jpg',184777,214,2,'{"scene": {"view": {"hlookat": "167.756","vlookat" : "1.624","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,216,2,'{"scene": {"view": {"hlookat": "284.713","vlookat" : "2.292","fov" : "139.842"}}}',null,True,True,15),
(0,'thumb.jpg',184777,217,2,'{"scene": {"view": {"hlookat": "3.899","vlookat" : "0.000","fov" : "139.072"}}}',null,False,False,null),
(0,'thumb.jpg',184777,758,2,null,null,False,False,null),
(0,'thumb.jpg',184777,759,2,'{"scene": {"view": {"hlookat": "172.537","vlookat" : "11.397","fov" : "140.000"}}}',null,False,False,null),
(0,'thumb.jpg',184777,760,2,'{"scene": {"view": {"hlookat": "-15.740","vlookat" : "9.901","fov" : "137.206"}}}',null,False,False,null),
(0,'thumb.jpg',184777,761,2,'{"scene": {"view": {"hlookat": "360.412","vlookat" : "9.305","fov" : "136.525"}}}',null,True,True,16),
(0,'thumb.jpg',184777,762,2,'{"scene": {"view": {"hlookat": "-6.268","vlookat" : "2.564","fov" : "139.959"}}}',null,False,False,null),
(0,'thumb.jpg',184777,763,2,'{"scene": {"view": {"hlookat": "0.379","vlookat" : "0.000","fov" : "139.951"}}}',null,False,False,null),
(0,'thumb.jpg',184777,464,2,null,null,False,False,null),
(0,'thumb.jpg',184777,465,2,'{"scene": {"view": {"hlookat": "-179.282","vlookat" : "1.312","fov" : "139.934"}}}',null,True,True,17),
(0,'thumb.jpg',184777,466,2,'{"scene": {"view": {"hlookat": "291.276","vlookat" : "-10.197","fov" : "139.972"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 184777
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 184777;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 184777);

