
# The Oberoi, Dubai

# https://www.touristtube.com/hotel-details-The+Oberoi+Dubai-140553


SET @hid = 140553;

DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = @hid;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = @hid;



INSERT INTO `touristtube`.`hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',@hid,79,1),
('Exterior - 1',@hid,130,1),
('Exterior - 2',@hid,193,2),
('Exterior - 3',@hid,194,3),
('Lobby',@hid,1,2),
('Lower Lobby - 1',@hid,15,1),
('Lower Lobby - 2',@hid,16,2),
('Lobby - 1',@hid,17,3),
('Lobby - 2',@hid,18,4),
('Lobby - 3',@hid,19,5),
('Lobby - 4',@hid,20,6),
('Lobby - 5',@hid,90,7),
('Reception',@hid,91,8),
('Ananta',@hid,6,3),
('1',@hid,25,1),
('2',@hid,26,2),
('Matto',@hid,28,4),
('1',@hid,29,1),
('2',@hid,30,2),
('3',@hid,31,3),
('4',@hid,32,4),
('Nine7One',@hid,167,5),
('1',@hid,168,1),
('2',@hid,169,2),
('3',@hid,170,3),
('4',@hid,171,4),
('The Courtyard',@hid,197,5),
('The Lobby Bar',@hid,173,6),
('1',@hid,174,1),
('2',@hid,175,2),
('The Lobby Lounge',@hid,188,7),
('1',@hid,189,1),
('2',@hid,190,2),
('3',@hid,191,3),
('Waka Restaurant and Bar',@hid,205,8),
('1',@hid,206,1),
('2',@hid,207,2),
('3',@hid,208,3),
('4',@hid,209,4),
('Gym',@hid,3,9),
('1',@hid,80,1),
('2',@hid,81,2),
('Business Center Reception',@hid,323,10),
('-',@hid,324,1),
('Grand Ballroom',@hid,131,11),
('1',@hid,132,1),
('2',@hid,252,2),
('Pre-Function Area',@hid,253,3),
('Meeting Room',@hid,293,12),
('-',@hid,297,1),
('Pool',@hid,2,13),
('1',@hid,21,1),
('2',@hid,92,2),
('Spa',@hid,4,14),
('1',@hid,101,1),
('2',@hid,102,2),
('Deluxe Room',@hid,36,15),
('Bedroom',@hid,37,1),
('Bathroom',@hid,38,2),
('Deluxe Suite',@hid,159,16),
('Living Room - 1',@hid,160,1),
('Living Room - 2',@hid,161,2),
('Bedroom - 1',@hid,164,3),
('Bedroom - 2',@hid,262,4),
('Bathroom',@hid,162,5),
('Guest Bathroom',@hid,163,6),
('Balcony',@hid,495,7),
('Luxury Suite',@hid,1104,17),
('Living Room - 1',@hid,1105,1),
('Living Room - 2',@hid,1106,2),
('Bedroom - Living ',@hid,1107,3),
('Bedroom',@hid,1108,4),
('Wardrobe - Bathroom',@hid,1109,5),
('Bathroom',@hid,1110,6),
('Guest Bathroom',@hid,1111,7),
('Dining Area - Balcony',@hid,1112,8),
('Balcony',@hid,1113,9),
('Premier Burj View Room',@hid,1115,18),
('Bedroom - 1',@hid,1116,1),
('Bedroom - 2',@hid,1117,2),
('Bathroom',@hid,1118,3),
('Premier Pool View Room ',@hid,1125,19),
('Bedroom - 1',@hid,1126,1),
('Bedroom - 2',@hid,1127,2),
('Bathroom',@hid,1129,3),
('Presidential Suite',@hid,1148,20),
('Living Office',@hid,1149,1),
('Office Area',@hid,1150,2),
('Living Area - Pool',@hid,1151,3),
('Dining Area - 1',@hid,1152,4),
('Dining Area - 2',@hid,1153,5),
('Kitchen',@hid,1154,6),
('Bedroom - 1',@hid,1155,7),
('Bedroom - 2',@hid,1156,8),
('Bathroom - 1',@hid,1157,9),
('Bathroom - 2',@hid,1158,10),
('Guest Bathroom',@hid,1235,11),
('Pool Area',@hid,1236,12);


INSERT INTO hotel_to_hotel_divisions_categories 
(hotel_division_category_id, hotel_id) 
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd 
INNER JOIN hotel_divisions hd ON (hd.id = hhd.hotel_division_id AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = @hid)) 
INNER JOIN hotel_divisions_categories hdc ON (hdc.id = hd.hotel_division_category_id) 
WHERE hhd.hotel_id = @hid;



INSERT INTO `touristtube`.`cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',@hid,79,2,null,null,False,False,1),
(0,'thumb.jpg',@hid,130,2,'{"scene": {"view": {"hlookat": "287.805","vlookat" : "-12.690","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,193,2,'{"scene": {"view": {"hlookat": "354.991","vlookat" : "-29.178","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,194,2,'{"scene": {"view": {"hlookat": "158.209","vlookat" : "-19.844","fov" : "140.000"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,1,2,null,null,False,False,2),
(0,'thumb.jpg',@hid,15,2,'{"scene": {"view": {"hlookat": "396.647","vlookat" : "-0.845","fov" : "140.000"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,16,2,'{"scene": {"view": {"hlookat": "441.255","vlookat" : "-0.869","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,17,2,'{"scene": {"view": {"hlookat": "314.601","vlookat" : "-0.521","fov" : "140.000"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,18,2,'{"scene": {"view": {"hlookat": "61.655","vlookat" : "-0.325","fov" : "140.000"}}}',null,True,True,4),
(0,'thumb.jpg',@hid,19,2,'{"scene": {"view": {"hlookat": "177.590","vlookat" : "0.966","fov" : "140.000"}}}',null,False,False,5),
(0,'thumb.jpg',@hid,20,2,'{"scene": {"view": {"hlookat": "61.750","vlookat" : "2.943","fov" : "140.000"}}}',null,False,False,6),
(0,'thumb.jpg',@hid,90,2,'{"scene": {"view": {"hlookat": "333.547","vlookat" : "-0.935","fov" : "140.000"}}}',null,False,False,7),
(0,'thumb.jpg',@hid,91,2,'{"scene": {"view": {"hlookat": "-11.314","vlookat" : "-2.296","fov" : "140.000"}}}',null,False,False,8),
(0,'thumb.jpg',@hid,6,2,null,null,False,False,3),
(0,'thumb.jpg',@hid,25,2,'{"scene": {"view": {"hlookat": "311.194","vlookat" : "9.180","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,26,2,'{"scene": {"view": {"hlookat": "261.068","vlookat" : "1.826","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,28,2,null,null,False,False,4),
(0,'thumb.jpg',@hid,29,2,'{"scene": {"view": {"hlookat": "175.278","vlookat" : "0.597","fov" : "140.000"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,30,2,'{"scene": {"view": {"hlookat": "-14.913","vlookat" : "26.774","fov" : "140.000"}}}',null,True,True,2),
(0,'thumb.jpg',@hid,31,2,'{"scene": {"view": {"hlookat": "170.625","vlookat" : "0.162","fov" : "140.000"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,32,2,'{"scene": {"view": {"hlookat": "26.892","vlookat" : "26.892","fov" : "140.000"}}}',null,False,False,4),
(0,'thumb.jpg',@hid,167,2,null,null,False,False,5),
(0,'thumb.jpg',@hid,168,2,'{"scene": {"view": {"hlookat": "208.235","vlookat" : "-0.237","fov" : "140.000"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,169,2,'{"scene": {"view": {"hlookat": "52.831","vlookat" : "0.315","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,170,2,'{"scene": {"view": {"hlookat": "325.224","vlookat" : "25.226","fov" : "140.000"}}}',null,True,True,3),
(0,'thumb.jpg',@hid,171,2,'{"scene": {"view": {"hlookat": "-32.744","vlookat" : "0.934","fov" : "140.000"}}}',null,False,False,4),
(0,'thumb.jpg',@hid,197,2,'{"scene": {"view": {"hlookat": "-327.554","vlookat" : "-0.749","fov" : "140.000"}}}',null,False,False,5),
(0,'thumb.jpg',@hid,173,2,null,null,False,False,6),
(0,'thumb.jpg',@hid,174,2,'{"scene": {"view": {"hlookat": "199.781","vlookat" : "-0.546","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,175,2,'{"scene": {"view": {"hlookat": "361.748","vlookat" : "19.911","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,188,2,null,null,False,False,7),
(0,'thumb.jpg',@hid,189,2,'{"scene": {"view": {"hlookat": "-8.603","vlookat" : "0.373","fov" : "140.000"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,190,2,'{"scene": {"view": {"hlookat": "36.222","vlookat" : "-1.970","fov" : "140.000"}}}',null,True,True,2),
(0,'thumb.jpg',@hid,191,2,'{"scene": {"view": {"hlookat": "188.061","vlookat" : "1.918","fov" : "140.000"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,205,2,null,null,False,False,8),
(0,'thumb.jpg',@hid,206,2,'{"scene": {"view": {"hlookat": "196.241","vlookat" : "-0.481","fov" : "140.000"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,207,2,'{"scene": {"view": {"hlookat": "-209.385","vlookat" : "20.593","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,208,2,'{"scene": {"view": {"hlookat": "19.181","vlookat" : "-1.312","fov" : "140.000"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,209,2,'{"scene": {"view": {"hlookat": "207.603","vlookat" : "12.183","fov" : "120.000"}}}',null,False,False,4),
(0,'thumb.jpg',@hid,3,2,null,null,False,False,9),
(0,'thumb.jpg',@hid,80,2,'{"scene": {"view": {"hlookat": "378.759","vlookat" : "-0.294","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,81,2,'{"scene": {"view": {"hlookat": "203.131","vlookat" : "-0.654","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,323,2,null,null,False,False,10),
(0,'thumb.jpg',@hid,324,2,'{"scene": {"view": {"hlookat": "351.189","vlookat" : "0.105","fov" : "140.000"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,131,2,null,null,False,False,11),
(0,'thumb.jpg',@hid,132,2,'{"scene": {"view": {"hlookat": "170.505","vlookat" : "0.341","fov" : "140.000"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,252,2,'{"scene": {"view": {"hlookat": "522.799","vlookat" : "20.787","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,253,2,'{"scene": {"view": {"hlookat": "1.148","vlookat" : "25.416","fov" : "140.000"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,293,2,null,null,False,False,12),
(0,'thumb.jpg',@hid,297,2,'{"scene": {"view": {"hlookat": "-0.492","vlookat" : "28.195","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,2,2,null,null,False,False,13),
(0,'thumb.jpg',@hid,21,2,'{"scene": {"view": {"hlookat": "59.593","vlookat" : "-4.768","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,92,2,'{"scene": {"view": {"hlookat": "180.825","vlookat" : "-2.715","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,4,2,null,null,False,False,14),
(0,'thumb.jpg',@hid,101,2,'{"scene": {"view": {"hlookat": "177.048","vlookat" : "0.787","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,102,2,'{"scene": {"view": {"hlookat": "-17.153","vlookat" : "14.082","fov" : "140.000"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,36,2,null,null,False,False,15),
(0,'thumb.jpg',@hid,37,2,'{"scene": {"view": {"hlookat": "159.365","vlookat" : "16.614","fov" : "140.000"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,38,2,'{"scene": {"view": {"hlookat": "-6.195","vlookat" : "0.675"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,159,2,null,null,False,False,16),
(0,'thumb.jpg',@hid,160,2,'{"scene": {"view": {"hlookat": "172.173","vlookat" : "22.333"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,161,2,'{"scene": {"view": {"hlookat": "366.313","vlookat" : "0.394"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,164,2,'{"scene": {"view": {"hlookat": "180.154","vlookat" : "-0.457"}}}',null,True,True,3),
(0,'thumb.jpg',@hid,262,2,'{"scene": {"view": {"hlookat": "-30.540","vlookat" : "18.537"}}}',null,False,False,4),
(0,'thumb.jpg',@hid,162,2,'{"scene": {"view": {"hlookat": "182.795","vlookat" : "0.872"}}}',null,False,False,5),
(0,'thumb.jpg',@hid,163,2,'{"scene": {"view": {"hlookat": "5.276","vlookat" : "13.837"}}}',null,False,False,6),
(0,'thumb.jpg',@hid,495,2,'{"scene": {"view": {"hlookat": "446.066","vlookat" : "-1.268"}}}',null,False,False,7),
(0,'thumb.jpg',@hid,1104,2,null,null,False,False,17),
(0,'thumb.jpg',@hid,1105,2,'{"scene": {"view": {"hlookat": "176.088","vlookat" : "22.704"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,1106,2,'{"scene": {"view": {"hlookat": "377.273","vlookat" : "25.333"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,1107,2,'{"scene": {"view": {"hlookat": "91.985","vlookat" : "-0.821"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,1108,2,'{"scene": {"view": {"hlookat": "-0.328","vlookat" : "19.511"}}}',null,True,True,4),
(0,'thumb.jpg',@hid,1109,2,'{"scene": {"view": {"hlookat": "273.935","vlookat" : "-0.196"}}}',null,False,False,5),
(0,'thumb.jpg',@hid,1110,2,'{"scene": {"view": {"hlookat": "183.537","vlookat" : "-0.029"}}}',null,False,False,6),
(0,'thumb.jpg',@hid,1111,2,'{"scene": {"view": {"hlookat": "9.596","vlookat" : "-0.997"}}}',null,False,False,7),
(0,'thumb.jpg',@hid,1112,2,'{"scene": {"view": {"hlookat": "181.327","vlookat" : "20.351"}}}',null,False,False,8),
(0,'thumb.jpg',@hid,1113,2,'{"scene": {"view": {"hlookat": "80.426","vlookat" : "0.655"}}}',null,False,False,9),
(0,'thumb.jpg',@hid,1115,2,null,null,False,False,18),
(0,'thumb.jpg',@hid,1116,2,'{"scene": {"view": {"hlookat": "174.254","vlookat" : "5.988"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,1117,2,'{"scene": {"view": {"hlookat": "181.636","vlookat" : "22.001"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,1118,2,'{"scene": {"view": {"hlookat": "174.126","vlookat" : "0.607"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,1125,2,null,null,False,False,19),
(0,'thumb.jpg',@hid,1126,2,'{"scene": {"view": {"hlookat": "195.310","vlookat" : "-0.926"}}}',null,True,True,1),
(0,'thumb.jpg',@hid,1127,2,'{"scene": {"view": {"hlookat": "179.366","vlookat" : "33.576"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,1129,2,'{"scene": {"view": {"hlookat": "290.309","vlookat" : "-0.735"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,1148,2,null,null,False,False,20),
(0,'thumb.jpg',@hid,1149,2,'{"scene": {"view": {"hlookat": "549.348","vlookat" : "-0.485"}}}',null,False,False,1),
(0,'thumb.jpg',@hid,1150,2,'{"scene": {"view": {"hlookat": "177.779","vlookat" : "-0.234"}}}',null,False,False,2),
(0,'thumb.jpg',@hid,1151,2,'{"scene": {"view": {"hlookat": "383.245","vlookat" : "4.785"}}}',null,False,False,3),
(0,'thumb.jpg',@hid,1152,2,'{"scene": {"view": {"hlookat": "372.748","vlookat" : "13.962"}}}',null,False,False,4),
(0,'thumb.jpg',@hid,1153,2,'{"scene": {"view": {"hlookat": "177.860","vlookat" : "16.536"}}}',null,False,False,5),
(0,'thumb.jpg',@hid,1154,2,'{"scene": {"view": {"hlookat": "18.741","vlookat" : "22.162"}}}',null,False,False,6),
(0,'thumb.jpg',@hid,1155,2,'{"scene": {"view": {"hlookat": "-0.986","vlookat" : "26.073"}}}',null,True,True,7),
(0,'thumb.jpg',@hid,1156,2,'{"scene": {"view": {"hlookat": "36.676","vlookat" : "2.523"}}}',null,False,False,8),
(0,'thumb.jpg',@hid,1157,2,'{"scene": {"view": {"hlookat": "361.040","vlookat" : "30.411"}}}',null,False,False,9),
(0,'thumb.jpg',@hid,1158,2,'{"scene": {"view": {"hlookat": "-28.370","vlookat" : "0.490"}}}',null,False,False,10),
(0,'thumb.jpg',@hid,1235,2,'{"scene": {"view": {"hlookat": "12.909","vlookat" : "23.249"}}}',null,False,False,11),
(0,'thumb.jpg',@hid,1236,2,'{"scene": {"view": {"hlookat": "-22.733","vlookat" : "3.028"}}}',null,False,False,12);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON (d.id = i.hotel_division_id AND d.parent_id IS NULL) 
WHERE i.hotel_id = @hid AND i.tt_media_type_id = 2;



DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;


INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE hi.hotel_id = @hid AND tt_media_type_id = 2;


UPDATE cms_hotel SET has_360 = 1 WHERE id = @hid;

