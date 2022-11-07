DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 132722;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 132722;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 132722;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 132722;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',132722,79,1),
('1',132722,130,1),
('2',132722,193,2),
('3',132722,194,3),
('Lobby',132722,1,2),
('1',132722,15,1),
('Reception',132722,16,2),
('The Grill',132722,6,3),
('1',132722,25,1),
('2',132722,26,2),
('3',132722,27,3),
('The Bar',132722,7,4),
('1',132722,152,1),
('2',132722,153,2),
('3',132722,154,3),
('The Roof',132722,8,5),
('-',132722,33,1),
('Pool',132722,2,6),
('1',132722,21,1),
('2',132722,92,2),
('Spa',132722,4,7),
('Reception',132722,101,1),
('Single Room Massage',132722,102,2),
('Thai Massage Room',132722,103,3),
('Couple Massage Room',132722,104,4),
('Relaxing Room - 1',132722,105,5),
('Relaxing Room - 2',132722,106,6),
('Sauna - Steam',132722,671,7),
('Lockers',132722,672,8),
('Gym',132722,3,8),
('1',132722,80,1),
('2',132722,81,2),
('Boardroom Lounge',132722,131,9),
('-',132722,132,1),
('Corniche Boardroom',132722,293,10),
('-',132722,297,1),
('Promenade Boardroom',132722,294,11),
('1',132722,303,1),
('2',132722,304,2),
('3',132722,305,3),
('Executive Boardroom',132722,295,12),
('1',132722,309,1),
('2',132722,310,2),
('Marina Boardroom',132722,296,13),
('1',132722,315,1),
('2',132722,316,2),
('Deluxe Twin Room',132722,139,14),
('1',132722,140,1),
('2',132722,141,2),
('Bathroom',132722,143,3),
('Deluxe Double Room',132722,568,15),
('1',132722,569,1),
('Bathroom',132722,572,2),
('Superior Room City View',132722,486,16),
('1',132722,487,1),
('Bathroom',132722,491,2),
('Premium Room',132722,480,17),
('1',132722,481,1),
('2',132722,482,2),
('Bathroom',132722,484,3),
('Suite City View ',132722,9,18),
('1',132722,44,1),
('Bathroom',132722,45,2),
('Balcony',132722,46,3),
('Deluxe Suite',132722,10,19),
('1',132722,47,1),
('2',132722,48,2),
('Bathroom',132722,51,3),
('Premier Suite',132722,517,20),
('Balcony',132722,518,1),
('Living',132722,519,2),
('Bedroom',132722,520,3),
('Bathroom',132722,521,4),
('Guest Bathroom',132722,522,5),
('Residential Suite',132722,502,21),
('1',132722,503,1),
('2',132722,504,2),
('3',132722,505,3),
('Bedroom',132722,506,4),
('Bathroom',132722,509,5),
('Guest Bathroom',132722,510,6),
('Diplomatic Double Bedroom Suite',132722,470,22),
('Balcony',132722,471,1),
('Living 1',132722,472,2),
('Living 2',132722,473,3),
('Bedroom',132722,474,4),
('Master Bathroom',132722,475,5),
('Kitchen',132722,476,6),
('Guest Bathroom',132722,478,7);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 132722;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',132722,79,2,null,null,False,False,null),
(0,'thumb.jpg',132722,130,2,'{"scene": {"view": {"hlookat": "120.514","vlookat" : "-0.057","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,193,2,'{"scene": {"view": {"hlookat": "90.969","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,194,2,'{"scene": {"view": {"hlookat": "374.911","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,1,2,null,null,False,False,null),
(0,'thumb.jpg',132722,15,2,'{"scene": {"view": {"hlookat": "334.861","vlookat" : "1.312","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,16,2,'{"scene": {"view": {"hlookat": "-222.807","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,6,2,null,null,False,False,null),
(0,'thumb.jpg',132722,25,2,'{"scene": {"view": {"hlookat": "1790.2","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,26,2,'{"scene": {"view": {"hlookat": "152.541","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,27,2,'{"scene": {"view": {"hlookat": "365.326","vlookat" : "32.57","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,7,2,null,null,False,False,null),
(0,'thumb.jpg',132722,152,2,'{"scene": {"view": {"hlookat": "715.117","vlookat" : "0.011","fov" : "120"}}}',null,True,True,1),
(0,'thumb.jpg',132722,153,2,'{"scene": {"view": {"hlookat": "357.993","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,154,2,'{"scene": {"view": {"hlookat": "354.669","vlookat" : "7.525","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,8,2,null,null,False,False,null),
(0,'thumb.jpg',132722,33,2,'{"scene": {"view": {"hlookat": "-487.212","vlookat" : "-3.649","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,2,2,null,null,False,False,null),
(0,'thumb.jpg',132722,21,2,'{"scene": {"view": {"hlookat": "372.005","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,92,2,'{"scene": {"view": {"hlookat": "212.103","vlookat" : "0","fov" : "120"}}}',null,True,True,2),
(0,'thumb.jpg',132722,4,2,null,null,False,False,null),
(0,'thumb.jpg',132722,101,2,'{"scene": {"view": {"hlookat": "155.177","vlookat" : "-0.103","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,102,2,'{"scene": {"view": {"hlookat": "224.14","vlookat" : "22.5","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,103,2,'{"scene": {"view": {"hlookat": "-24.845","vlookat" : "7.547","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,104,2,'{"scene": {"view": {"hlookat": "79.852","vlookat" : "14.469","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',132722,105,2,'{"scene": {"view": {"hlookat": "0.328","vlookat" : "21.973","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,106,2,'{"scene": {"view": {"hlookat": "-93.628","vlookat" : "14.748","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,671,2,'{"scene": {"view": {"hlookat": "-80.529","vlookat" : "4.003","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,672,2,'{"scene": {"view": {"hlookat": "632.961","vlookat" : "2.363","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,3,2,null,null,False,False,null),
(0,'thumb.jpg',132722,80,2,'{"scene": {"view": {"hlookat": "-5.482","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,81,2,'{"scene": {"view": {"hlookat": "206.028","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,131,2,null,null,False,False,null),
(0,'thumb.jpg',132722,132,2,'{"scene": {"view": {"hlookat": "3365.971","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,293,2,null,null,False,False,null),
(0,'thumb.jpg',132722,297,2,'{"scene": {"view": {"hlookat": "302.589","vlookat" : "-0.103","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,294,2,null,null,False,False,null),
(0,'thumb.jpg',132722,303,2,'{"scene": {"view": {"hlookat": "535.971","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,304,2,'{"scene": {"view": {"hlookat": "175.3","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,305,2,'{"scene": {"view": {"hlookat": "-420.715","vlookat" : "0.186","fov" : "120"}}}',null,True,True,4),
(0,'thumb.jpg',132722,295,2,null,null,False,False,null),
(0,'thumb.jpg',132722,309,2,'{"scene": {"view": {"hlookat": "181.739","vlookat" : "3.198","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',132722,310,2,'{"scene": {"view": {"hlookat": "211.347","vlookat" : "21.214","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,296,2,null,null,False,False,null),
(0,'thumb.jpg',132722,315,2,'{"scene": {"view": {"hlookat": "-360.648","vlookat" : "0","fov" : "126.682"}}}',null,False,False,null),
(0,'thumb.jpg',132722,316,2,'{"scene": {"view": {"hlookat": "1439.094","vlookat" : "20.825","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,139,2,null,null,False,False,null),
(0,'thumb.jpg',132722,140,2,'{"scene": {"view": {"hlookat": "167.57","vlookat" : "0.155","fov" : "138.628"}}}',null,True,True,6),
(0,'thumb.jpg',132722,141,2,'{"scene": {"view": {"hlookat": "466.93","vlookat" : "0","fov" : "132.363"}}}',null,False,False,null),
(0,'thumb.jpg',132722,143,2,'{"scene": {"view": {"hlookat": "823.685","vlookat" : "1.556","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,568,2,null,null,False,False,null),
(0,'thumb.jpg',132722,569,2,'{"scene": {"view": {"hlookat": "194.202","vlookat" : "0.713","fov" : "120"}}}',null,True,True,7),
(0,'thumb.jpg',132722,572,2,'{"scene": {"view": {"hlookat": "130.14","vlookat" : "3.486","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',132722,486,2,null,null,False,False,null),
(0,'thumb.jpg',132722,487,2,'{"scene": {"view": {"hlookat": "9.224","vlookat" : "0.492","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',132722,491,2,'{"scene": {"view": {"hlookat": "-11.314","vlookat" : "8.853","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,480,2,null,null,False,False,null),
(0,'thumb.jpg',132722,481,2,'{"scene": {"view": {"hlookat": "-416.092","vlookat" : "-0.262","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,482,2,'{"scene": {"view": {"hlookat": "180.798","vlookat" : "10.708","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',132722,484,2,'{"scene": {"view": {"hlookat": "-173.066","vlookat" : "11.161","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,9,2,null,null,False,False,null),
(0,'thumb.jpg',132722,44,2,'{"scene": {"view": {"hlookat": "551.28","vlookat" : "10.121","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',132722,45,2,'{"scene": {"view": {"hlookat": "89.688","vlookat" : "12.012","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,46,2,'{"scene": {"view": {"hlookat": "597.341","vlookat" : "3.068","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,10,2,null,null,False,False,null),
(0,'thumb.jpg',132722,47,2,'{"scene": {"view": {"hlookat": "-166.703","vlookat" : "13.056","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,48,2,'{"scene": {"view": {"hlookat": "8.363","vlookat" : "6.723","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',132722,51,2,'{"scene": {"view": {"hlookat": "-100.182","vlookat" : "7.049","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,517,2,null,null,False,False,null),
(0,'thumb.jpg',132722,518,2,'{"scene": {"view": {"hlookat": "-28.798","vlookat" : "8.161","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,519,2,'{"scene": {"view": {"hlookat": "-80.806","vlookat" : "0","fov" : "138.38"}}}',null,True,True,12),
(0,'thumb.jpg',132722,520,2,'{"scene": {"view": {"hlookat": "-9.538","vlookat" : "0.587","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,521,2,'{"scene": {"view": {"hlookat": "-448.602","vlookat" : "6.525","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,522,2,'{"scene": {"view": {"hlookat": "-361.224","vlookat" : "25.323","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,502,2,null,null,False,False,null),
(0,'thumb.jpg',132722,503,2,'{"scene": {"view": {"hlookat": "16.631","vlookat" : "-0.416","fov" : "127.033"}}}',null,True,True,13),
(0,'thumb.jpg',132722,504,2,'{"scene": {"view": {"hlookat": "162.617","vlookat" : "2.788","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,505,2,'{"scene": {"view": {"hlookat": "171.286","vlookat" : "9.506","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,506,2,'{"scene": {"view": {"hlookat": "183.852","vlookat" : "19.254","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,509,2,'{"scene": {"view": {"hlookat": "351.747","vlookat" : "12.197","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,510,2,'{"scene": {"view": {"hlookat": "14.979","vlookat" : "21.254","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,470,2,null,null,False,False,null),
(0,'thumb.jpg',132722,471,2,'{"scene": {"view": {"hlookat": "-38.42","vlookat" : "2.012","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,472,2,'{"scene": {"view": {"hlookat": "-11.925","vlookat" : "0","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',132722,473,2,'{"scene": {"view": {"hlookat": "3.698","vlookat" : "20.557","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,474,2,'{"scene": {"view": {"hlookat": "15.25","vlookat" : "2.788","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,475,2,'{"scene": {"view": {"hlookat": "202.948","vlookat" : "7.058","fov" : "125.544"}}}',null,False,False,null),
(0,'thumb.jpg',132722,476,2,'{"scene": {"view": {"hlookat": "-9.502","vlookat" : "4.264","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',132722,478,2,'{"scene": {"view": {"hlookat": "-0.256","vlookat" : "22.126","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 132722
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 132722;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 132722);

