
# CAESARS BLUEWATERS DUBAI

# https://www.touristtube.com/hotel-details-CAESARS+BLUEWATERS+DUBAI-417425


SET @hid = 417425;


DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;
DELETE FROM cms_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;



INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby', @hid, 1, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception - 1', @hid, 15, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception - 2', @hid, 16, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception - 3', @hid, 17, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 18, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 19, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 20, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 90, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby Balcony', @hid, 91, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Gordon Ramsay Hell Kitchen', @hid, 28, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 29, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 30, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 31, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 32, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 110, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 111, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('7', @hid, 112, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('8', @hid, 149, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('9', @hid, 150, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Havana Social Club', @hid, 167, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 168, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 169, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 170, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 171, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 197, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 198, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('7', @hid, 199, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('8', @hid, 354, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('9', @hid, 1445, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('10', @hid, 1446, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Laurel Bar', @hid, 7, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 152, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 153, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 154, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 155, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 156, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Neptune Bar', @hid, 231, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 232, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 233, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 234, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Roman Lounge', @hid, 94, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 95, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 96, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 97, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Zhen Wei', @hid, 173, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 174, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 175, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 176, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 177, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 178, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 179, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Caesars Palace Beach', @hid, 722, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 723, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 724, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 725, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Cove Beach', @hid, 1474, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1475, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1476, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1477, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 1478, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 1479, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 1480, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('7', @hid, 1481, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('8', @hid, 1482, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('9', @hid, 1483, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('10', @hid, 1484, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('11', @hid, 1485, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('12', @hid, 1486, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('13', @hid, 1487, 13);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('14', @hid, 1488, 14);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Empire Club', @hid, 735, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 736, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Meeting Rooms', @hid, 131, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Ballroom', @hid, 132, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Caesars Forum - 1', @hid, 252, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Caesars Forum - 2', @hid, 253, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Caesars Forum - 3', @hid, 254, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Cinema Terrace', @hid, 255, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Garden Terrace - 1', @hid, 1284, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Garden Terrace - 2', @hid, 1285, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Meeting Room - A', @hid, 1351, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Meeting Room - B', @hid, 1352, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Meeting Room - C', @hid, 1353, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Neptune Pool', @hid, 2, 13);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 21, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 92, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 93, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 186, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 187, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 658, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Spa and Gym', @hid, 4, 14);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Couple Treatment Room', @hid, 101, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Fitness Gallery - 1', @hid, 102, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Fitness Gallery - 2', @hid, 103, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Fitness Gallery - 3', @hid, 104, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Iyashi Dome', @hid, 105, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Quartz', @hid, 106, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception', @hid, 671, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Single Treatment Room', @hid, 672, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Rotunda', @hid, 1399, 15);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1400, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1401, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1402, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 1403, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 1404, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 1618, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('7', @hid, 1619, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('8', @hid, 1620, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Palace Deluxe King - Resort View', @hid, 480, 16);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - Bathroom - 1', @hid, 481, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - Bathroom - 2', @hid, 482, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Balcony', @hid, 485, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Palace Deluxe King - Partial Ocean View', @hid, 486, 17);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom', @hid, 487, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - Bathroom', @hid, 488, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 491, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Palace Deluxe King - Ocean View', @hid, 1072, 18);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom', @hid, 1073, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - Bathroom', @hid, 1074, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Balcony', @hid, 1075, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('One Bedroom Ocean Suite', @hid, 159, 19);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living', @hid, 160, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living - Bedroom', @hid, 161, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom', @hid, 164, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 162, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Two Bedroom Ocean Terrace Suite', @hid, 1104, 20);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Area - 1', @hid, 1105, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Area - 2', @hid, 1106, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living - Dinning - 1', @hid, 1107, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living - Dinning - 2', @hid, 1108, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom A', @hid, 1109, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom A', @hid, 1110, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom B - 1', @hid, 1111, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom B - 2', @hid, 1112, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom B', @hid, 1113, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Presidential Suite', @hid, 1115, 21);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Area - 1', @hid, 1116, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Area - 2', @hid, 1117, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Dinning', @hid, 1118, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom A - 1', @hid, 1119, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom A - 2', @hid, 1120, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom A', @hid, 1121, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom B', @hid, 1122, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom B', @hid, 1123, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom C', @hid, 1124, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom C', @hid, 1159, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Balcony', @hid, 1248, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Julius Suite', @hid, 1148, 22);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living - 1', @hid, 1149, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living - 2', @hid, 1150, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Dinning', @hid, 1151, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Kitchen - Dinning', @hid, 1152, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom A - 1', @hid, 1153, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom A - 2', @hid, 1154, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom A', @hid, 1155, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom B', @hid, 1156, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom B', @hid, 1157, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom C', @hid, 1158, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Guest Bathroom', @hid, 1235, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Balcony - 1', @hid, 1236, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Balcony - 2', @hid, 1237, 13);



SELECT hd.hotel_division_id AS div_id, hd.name AS div_name, d.hotel_division_category_id AS cat_id, dc.name AS cat_name 
FROM hotel_to_hotel_divisions hd 
INNER JOIN hotel_divisions d ON (d.id = hd.hotel_division_id) 
INNER JOIN hotel_divisions_categories dc ON (dc.id = d.hotel_division_category_id) 
WHERE hd.hotel_id = @hid 
ORDER BY hd.hotel_division_id ASC;




INSERT INTO hotel_to_hotel_divisions_categories 
(hotel_division_category_id, hotel_id) 
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd 
INNER JOIN hotel_divisions hd ON (hd.id = hhd.hotel_division_id) 
INNER JOIN hotel_divisions_categories hdc ON (hdc.id = hd.hotel_division_category_id) 
WHERE hhd.hotel_id = @hid 
AND NOT EXISTS (SELECT 1 FROM hotel_to_hotel_divisions_categories dcat WHERE dcat.hotel_id = @hid AND dcat.hotel_division_category_id = hd.hotel_division_category_id);




INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 15, 2, '{"scene": {"view": {"hlookat": "14.932","vlookat" : "0.000","fov" : "140.000"}}}', true, true, 1);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 16, 2, '{"scene": {"view": {"hlookat": "-34.431","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 17, 2, '{"scene": {"view": {"hlookat": "158.930","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 18, 2, '{"scene": {"view": {"hlookat": "343.502","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 19, 2, '{"scene": {"view": {"hlookat": "-166.500","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 20, 2, '{"scene": {"view": {"hlookat": "292.424","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 90, 2, '{"scene": {"view": {"hlookat": "-368.997","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 91, 2, '{"scene": {"view": {"hlookat": "329.930","vlookat" : "-0.177","fov" : "140.000"}}}', true, true, 2);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 29, 2, '{"scene": {"view": {"hlookat": "316.254","vlookat" : "0.000","fov" : "140.000"}}}', true, true, 3);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 30, 2, '{"scene": {"view": {"hlookat": "335.931","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 31, 2, '{"scene": {"view": {"hlookat": "146.893","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 32, 2, '{"scene": {"view": {"hlookat": "378.068","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 110, 2, '{"scene": {"view": {"hlookat": "-12.068","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 111, 2, '{"scene": {"view": {"hlookat": "193.570","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 112, 2, '{"scene": {"view": {"hlookat": "-23.997","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 149, 2, '{"scene": {"view": {"hlookat": "11.933","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 150, 2, '{"scene": {"view": {"hlookat": "-106.431","vlookat" : "0.000","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 168, 2, '{"scene": {"view": {"hlookat": "346.608","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 169, 2, '{"scene": {"view": {"hlookat": "-202.498","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 170, 2, '{"scene": {"view": {"hlookat": "195.097","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 171, 2, '{"scene": {"view": {"hlookat": "369.066","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 197, 2, '{"scene": {"view": {"hlookat": "159.069","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 198, 2, '{"scene": {"view": {"hlookat": "343.498","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 199, 2, '{"scene": {"view": {"hlookat": "190.569","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 354, 2, '{"scene": {"view": {"hlookat": "205.599","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1445, 2, '{"scene": {"view": {"hlookat": "167.930","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1446, 2, '{"scene": {"view": {"hlookat": "19.433","vlookat" : "0.000","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 152, 2, '{"scene": {"view": {"hlookat": "-202.430","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 153, 2, '{"scene": {"view": {"hlookat": "191.797","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 154, 2, '{"scene": {"view": {"hlookat": "122.933","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 155, 2, '{"scene": {"view": {"hlookat": "245.931","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 156, 2, '{"scene": {"view": {"hlookat": "135.016","vlookat" : "0.000","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 232, 2, '{"scene": {"view": {"hlookat": "760.657","vlookat" : "-1.633","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 233, 2, '{"scene": {"view": {"hlookat": "386.659","vlookat" : "-3.109","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 234, 2, '{"scene": {"view": {"hlookat": "24.131","vlookat" : "-0.985","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 95, 2, '{"scene": {"view": {"hlookat": "178.327","vlookat" : "11.163","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 96, 2, '{"scene": {"view": {"hlookat": "356.066","vlookat" : "8.748","fov" : "140.000"}}}', true, true, 4);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 97, 2, '{"scene": {"view": {"hlookat": "110.006","vlookat" : "12.707","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 174, 2, '{"scene": {"view": {"hlookat": "-0.984","vlookat" : "-18.855","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 175, 2, '{"scene": {"view": {"hlookat": "-12.081","vlookat" : "22.331","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 176, 2, '{"scene": {"view": {"hlookat": "340.332","vlookat" : "-0.181","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 177, 2, '{"scene": {"view": {"hlookat": "-7.704","vlookat" : "-0.001","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 178, 2, '{"scene": {"view": {"hlookat": "-376.265","vlookat" : "16.741","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 179, 2, '{"scene": {"view": {"hlookat": "195.541","vlookat" : "9.291","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 723, 2, '{"scene": {"view": {"hlookat": "-70.810","vlookat" : "-7.460","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 724, 2, '{"scene": {"view": {"hlookat": "-159.192","vlookat" : "-5.273","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 725, 2, '{"scene": {"view": {"hlookat": "-345.582","vlookat" : "-9.477","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1475, 2, '{"scene": {"view": {"hlookat": "225.354","vlookat" : "2.837","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1476, 2, '{"scene": {"view": {"hlookat": "-2.132","vlookat" : "1.653","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1477, 2, '{"scene": {"view": {"hlookat": "194.344","vlookat" : "6.446","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1478, 2, '{"scene": {"view": {"hlookat": "377.455","vlookat" : "0.994","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1479, 2, '{"scene": {"view": {"hlookat": "533.802","vlookat" : "10.052","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1480, 2, '{"scene": {"view": {"hlookat": "-362.231","vlookat" : "0.017","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1481, 2, '{"scene": {"view": {"hlookat": "-6.343","vlookat" : "-8.641","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1482, 2, '{"scene": {"view": {"hlookat": "-374.670","vlookat" : "4.742","fov" : "140.000"}}}', true, true, 5);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1483, 2, '{"scene": {"view": {"hlookat": "262.750","vlookat" : "0.461","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1484, 2, '{"scene": {"view": {"hlookat": "359.427","vlookat" : "4.617","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1485, 2, '{"scene": {"view": {"hlookat": "-414.076","vlookat" : "-2.921","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1486, 2, '{"scene": {"view": {"hlookat": "358.510","vlookat" : "11.580","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1487, 2, '{"scene": {"view": {"hlookat": "-4.256","vlookat" : "-2.166","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1488, 2, '{"scene": {"view": {"hlookat": "114.140","vlookat" : "-6.665","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 736, 2, '{"scene": {"view": {"hlookat": "-45.819","vlookat" : "2.690","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 132, 2, '{"scene": {"view": {"hlookat": "-196.496","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 252, 2, '{"scene": {"view": {"hlookat": "336.000","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 253, 2, '{"scene": {"view": {"hlookat": "-76.364","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 254, 2, '{"scene": {"view": {"hlookat": "154.430","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 255, 2, '{"scene": {"view": {"hlookat": "136.568","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1284, 2, '{"scene": {"view": {"hlookat": "39.025","vlookat" : "-7.562","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1285, 2, '{"scene": {"view": {"hlookat": "-10.633","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1351, 2, '{"scene": {"view": {"hlookat": "-156.064","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1352, 2, '{"scene": {"view": {"hlookat": "-19.432","vlookat" : "0.000","fov" : "140.000"}}}', true, true, 6);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1353, 2, '{"scene": {"view": {"hlookat": "-7.432","vlookat" : "1.569","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 21, 2, '{"scene": {"view": {"hlookat": "-196.431","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 92, 2, '{"scene": {"view": {"hlookat": "-46.637","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 93, 2, '{"scene": {"view": {"hlookat": "-82.401","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 186, 2, '{"scene": {"view": {"hlookat": "-29.930","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 187, 2, '{"scene": {"view": {"hlookat": "11.931","vlookat" : "-5.931","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 658, 2, '{"scene": {"view": {"hlookat": "-211.570","vlookat" : "0.000","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 101, 2, '{"scene": {"view": {"hlookat": "-10.494","vlookat" : "16.561","fov" : "140.000"}}}', true, true, 7);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 102, 2, '{"scene": {"view": {"hlookat": "-15.085","vlookat" : "0.328","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 103, 2, '{"scene": {"view": {"hlookat": "-19.841","vlookat" : "0.328","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 104, 2, '{"scene": {"view": {"hlookat": "12.626","vlookat" : "0.164","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 105, 2, '{"scene": {"view": {"hlookat": "-7.871","vlookat" : "22.137","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 106, 2, '{"scene": {"view": {"hlookat": "-21.644","vlookat" : "4.262","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 671, 2, '{"scene": {"view": {"hlookat": "-76.575","vlookat" : "6.723","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 672, 2, '{"scene": {"view": {"hlookat": "-5.739","vlookat" : "17.873","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1400, 2, '{"scene": {"view": {"hlookat": "362.236","vlookat" : "-12.425","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1401, 2, '{"scene": {"view": {"hlookat": "9.838","vlookat" : "-8.527","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1402, 2, '{"scene": {"view": {"hlookat": "178.954","vlookat" : "-7.870","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1403, 2, '{"scene": {"view": {"hlookat": "49.389","vlookat" : "-24.646","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1404, 2, '{"scene": {"view": {"hlookat": "262.083","vlookat" : "-16.219","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings)
VALUES
('thumb.jpg', @hid, 1618, 2, '{"scene": {"view": {"hlookat": "19.021","vlookat" : "-22.136","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings)
VALUES
('thumb.jpg', @hid, 1619, 2, '{"scene": {"view": {"hlookat": "163.951","vlookat" : "-24.812","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings)
VALUES
('thumb.jpg', @hid, 1620, 2, '{"scene": {"view": {"hlookat": "11.083","vlookat" : "-7.914","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 481, 2, '{"scene": {"view": {"hlookat": "86.501","vlookat" : "-0.862","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 482, 2, '{"scene": {"view": {"hlookat": "268.745","vlookat" : "-0.790","fov" : "140.000"}}}', true, true, 8);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 485, 2, '{"scene": {"view": {"hlookat": "353.428","vlookat" : "-9.082","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 487, 2, '{"scene": {"view": {"hlookat": "-0.829","vlookat" : "0.664","fov" : "140.000"}}}', true, true, 9);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 488, 2, '{"scene": {"view": {"hlookat": "-16.428","vlookat" : "-0.039","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 491, 2, '{"scene": {"view": {"hlookat": "134.909","vlookat" : "-0.546","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1073, 2, '{"scene": {"view": {"hlookat": "-2.654","vlookat" : "25.379","fov" : "140.000"}}}', true, true, 10);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1074, 2, '{"scene": {"view": {"hlookat": "91.770","vlookat" : "-1.985","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1075, 2, '{"scene": {"view": {"hlookat": "365.481","vlookat" : "-0.787","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 160, 2, '{"scene": {"view": {"hlookat": "-0.332","vlookat" : "33.673","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 161, 2, '{"scene": {"view": {"hlookat": "79.919","vlookat" : "-0.814","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 164, 2, '{"scene": {"view": {"hlookat": "-11.073","vlookat" : "21.703","fov" : "140.000"}}}', true, true, 11);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 162, 2, '{"scene": {"view": {"hlookat": "-36.327","vlookat" : "0.166","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1105, 2, '{"scene": {"view": {"hlookat": "358.706","vlookat" : "16.295","fov" : "140.000"}}}', true, true, 12);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1106, 2, '{"scene": {"view": {"hlookat": "-21.620","vlookat" : "4.197","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1107, 2, '{"scene": {"view": {"hlookat": "310.808","vlookat" : "-0.210","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1108, 2, '{"scene": {"view": {"hlookat": "-24.715","vlookat" : "11.943","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1109, 2, '{"scene": {"view": {"hlookat": "14.158","vlookat" : "1.749","fov" : "140.000"}}}', true, true, 13);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1110, 2, '{"scene": {"view": {"hlookat": "375.335","vlookat" : "0.395","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1111, 2, '{"scene": {"view": {"hlookat": "344.178","vlookat" : "0.987","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1112, 2, '{"scene": {"view": {"hlookat": "189.429","vlookat" : "25.161","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1113, 2, '{"scene": {"view": {"hlookat": "349.394","vlookat" : "10.186","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1116, 2, '{"scene": {"view": {"hlookat": "343.232","vlookat" : "0.311","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1117, 2, '{"scene": {"view": {"hlookat": "-20.057","vlookat" : "1.101","fov" : "140.000"}}}', true, true, 14);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1118, 2, '{"scene": {"view": {"hlookat": "-35.820","vlookat" : "12.804","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1119, 2, '{"scene": {"view": {"hlookat": "-0.684","vlookat" : "31.009","fov" : "140.000"}}}', true, true, 15);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1120, 2, '{"scene": {"view": {"hlookat": "196.453","vlookat" : "1.025","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1121, 2, '{"scene": {"view": {"hlookat": "344.222","vlookat" : "14.942","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1122, 2, '{"scene": {"view": {"hlookat": "360.753","vlookat" : "32.268","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1123, 2, '{"scene": {"view": {"hlookat": "-10.300","vlookat" : "10.304","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1124, 2, '{"scene": {"view": {"hlookat": "0.829","vlookat" : "14.266","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1159, 2, '{"scene": {"view": {"hlookat": "737.709","vlookat" : "0.119","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1248, 2, '{"scene": {"view": {"hlookat": "-30.279","vlookat" : "0.231","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1149, 2, '{"scene": {"view": {"hlookat": "-0.664","vlookat" : "7.630","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1150, 2, '{"scene": {"view": {"hlookat": "-18.412","vlookat" : "0.166","fov" : "140.000"}}}', true, true, 16);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1151, 2, '{"scene": {"view": {"hlookat": "-354.770","vlookat" : "0.299","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1152, 2, '{"scene": {"view": {"hlookat": "24.677","vlookat" : "18.717","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1153, 2, '{"scene": {"view": {"hlookat": "344.481","vlookat" : "-0.096","fov" : "140.000"}}}', true, true, 17);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1154, 2, '{"scene": {"view": {"hlookat": "-1.327","vlookat" : "26.043","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1155, 2, '{"scene": {"view": {"hlookat": "-342.203","vlookat" : "3.900","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1156, 2, '{"scene": {"view": {"hlookat": "92.059","vlookat" : "2.986","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1157, 2, '{"scene": {"view": {"hlookat": "41.469","vlookat" : "1.327","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1158, 2, '{"scene": {"view": {"hlookat": "6.446","vlookat" : "0.894","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1235, 2, '{"scene": {"view": {"hlookat": "-25.624","vlookat" : "29.990","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1236, 2, '{"scene": {"view": {"hlookat": "-41.408","vlookat" : "0.196","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1237, 2, '{"scene": {"view": {"hlookat": "169.629","vlookat" : "-0.154","fov" : "140.000"}}}');





UPDATE cms_hotel SET has_360 = true WHERE id = @hid;

UPDATE cms_hotel SET logo = CONCAT(id, '.png') WHERE id = @hid;

INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location IS NULL, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE hi.hotel_id = @hid AND tt_media_type_id = 2;

