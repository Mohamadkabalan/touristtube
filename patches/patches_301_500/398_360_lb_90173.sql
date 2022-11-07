

# Le Gray

# https://www.touristtube.com/hotel-details-Le+Gray-90173

SET @hid = 90173;


DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;
DELETE FROM cms_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;


INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Entrance', @hid, 79, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Main Entrance - 1', @hid, 130, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Pedestrian Entrance - 1', @hid, 193, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Pedestrian Entrance - 2', @hid, 194, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby', @hid, 1, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Lobby - 1', @hid, 15, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Lobby - 2', @hid, 16, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Reception', @hid, 17, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Gordon’s Café', @hid, 8, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace', @hid, 33, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 34, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 35, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Indigo on The Roof', @hid, 28, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Buffet Breakfast - 1', @hid, 29, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Buffet Breakfast - 2', @hid, 30, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Buffet Breakfast - 3', @hid, 31, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Buffet Breakfast - 4', @hid, 32, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Dining', @hid, 110, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 1', @hid, 111, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 2', @hid, 112, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 3', @hid, 149, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 4', @hid, 150, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 5', @hid, 151, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 6', @hid, 1356, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 7', @hid, 1357, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 8', @hid, 1358, 13);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace - 9', @hid, 1359, 14);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Cherry on the Rooftop', @hid, 167, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 168, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 169, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 170, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Lobby Lounge', @hid, 188, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 189, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 190, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 191, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 192, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Cigar Lounge', @hid, 205, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Library Corner', @hid, 206, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lounge', @hid, 207, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Rooftop Pool', @hid, 2, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Pool', @hid, 21, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('PureGray Spa', @hid, 4, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Reception', @hid, 101, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Chill Out Lounge', @hid, 102, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Single Treatment Room', @hid, 103, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Couples Room', @hid, 104, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('PureGray Gym', @hid, 3, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 80, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Business Centre', @hid, 323, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 324, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 325, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Le Grand Salon', @hid, 131, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Business Setup', @hid, 132, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Dinner Setup', @hid, 252, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Muse Room', @hid, 293, 13);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('U Shape Meeting - 1', @hid, 297, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('U Shape Meeting - 2', @hid, 298, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Press Conference', @hid, 299, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Boardroom', @hid, 294, 14);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 303, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Meeting Room', @hid, 295, 15);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 309, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 310, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Screening Room', @hid, 464, 16);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 465, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Foyer', @hid, 730, 17);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 731, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 732, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 733, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 734, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 1183, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Atrium - Exhibition Area', @hid, 1315, 18);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Atrium - 1', @hid, 1316, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Atrium - 2', @hid, 1317, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The 6th Floor', @hid, 1366, 19);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Entrance', @hid, 1367, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Cigar Lounge Entrance', @hid, 1368, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Indigo On The Roof Entrance', @hid, 1369, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Cherry On The Rooftop Entrance', @hid, 1370, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Rooms Floors', @hid, 1377, 20);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Second Floor', @hid, 1378, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Third Floor', @hid, 1379, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Deluxe Room', @hid, 36, 21);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom', @hid, 37, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 38, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Premium Room', @hid, 480, 22);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom', @hid, 481, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 484, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Corner Suite', @hid, 159, 23);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom', @hid, 164, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Space', @hid, 160, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 162, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace Suite', @hid, 1104, 24);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom and Living', @hid, 1105, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View', @hid, 1106, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 1107, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Executive Suite', @hid, 1115, 25);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom and Living', @hid, 1116, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View', @hid, 1117, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 1118, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Le Gray Suite', @hid, 1125, 26);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Space and Terrace', @hid, 1126, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Master Bedroom', @hid, 1127, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Master Bathroom', @hid, 1129, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Master Walk In Dressing', @hid, 1130, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Second Bedroom', @hid, 1131, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Second Bathroom', @hid, 1132, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Guest Toilet', @hid, 1133, 7);



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
('thumb.jpg', @hid, 130, 2, '{"scene": {"view": {"hlookat": "43.453","vlookat" : "-10.330","fov" : "140.000"}}}', true, true, 1);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 193, 2, '{"scene": {"view": {"hlookat": "1.381","vlookat" : "-26.454","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 194, 2, '{"scene": {"view": {"hlookat": "-1.476","vlookat" : "-17.217","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 15, 2, '{"scene": {"view": {"hlookat": "-210.695","vlookat" : "0.220","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 16, 2, '{"scene": {"view": {"hlookat": "-107.974","vlookat" : "6.683","fov" : "140.000"}}}', true, true, 2);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 17, 2, '{"scene": {"view": {"hlookat": "11.637","vlookat" : "6.299","fov" : "139.951"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 33, 2, '{"scene": {"view": {"hlookat": "157.177","vlookat" : "6.696","fov" : "140.000"}}}', true, true, 3);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 34, 2, '{"scene": {"view": {"hlookat": "30.006","vlookat" : "1.639","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 35, 2, '{"scene": {"view": {"hlookat": "164.493","vlookat" : "3.939","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 29, 2, '{"scene": {"view": {"hlookat": "183.895","vlookat" : "0.456","fov" : "139.813"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 30, 2, '{"scene": {"view": {"hlookat": "-113.545","vlookat" : "16.972","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 31, 2, '{"scene": {"view": {"hlookat": "-347.665","vlookat" : "16.302","fov" : "139.973"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 32, 2, '{"scene": {"view": {"hlookat": "1.984","vlookat" : "3.419","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 110, 2, '{"scene": {"view": {"hlookat": "12.440","vlookat" : "6.880","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 111, 2, '{"scene": {"view": {"hlookat": "314.973","vlookat" : "24.666","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 112, 2, '{"scene": {"view": {"hlookat": "-23.612","vlookat" : "17.381","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 149, 2, '{"scene": {"view": {"hlookat": "-141.419","vlookat" : "26.297","fov" : "139.921"}}}', true, true, 4);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 150, 2, '{"scene": {"view": {"hlookat": "-135.681","vlookat" : "14.460","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 151, 2, '{"scene": {"view": {"hlookat": "2.446","vlookat" : "1.222","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1356, 2, '{"scene": {"view": {"hlookat": "148.083","vlookat" : "5.355","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1357, 2, '{"scene": {"view": {"hlookat": "503.971","vlookat" : "-0.622","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1358, 2, '{"scene": {"view": {"hlookat": "218.435","vlookat" : "0.343","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1359, 2, '{"scene": {"view": {"hlookat": "493.842","vlookat" : "19.217","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 168, 2, '{"scene": {"view": {"hlookat": "-106.086","vlookat" : "3.884","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 169, 2, '{"scene": {"view": {"hlookat": "127.011","vlookat" : "-0.190","fov" : "139.977"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 170, 2, '{"scene": {"view": {"hlookat": "234.038","vlookat" : "2.701","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 189, 2, '{"scene": {"view": {"hlookat": "531.399","vlookat" : "21.753","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 190, 2, '{"scene": {"view": {"hlookat": "155.292","vlookat" : "11.630","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 191, 2, '{"scene": {"view": {"hlookat": "344.782","vlookat" : "5.589","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 192, 2, '{"scene": {"view": {"hlookat": "179.019","vlookat" : "-11.415","fov" : "139.447"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 206, 2, '{"scene": {"view": {"hlookat": "231.103","vlookat" : "29.974","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 207, 2, '{"scene": {"view": {"hlookat": "181.648","vlookat" : "9.462","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 21, 2, '{"scene": {"view": {"hlookat": "-97.393","vlookat" : "1.016","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 101, 2, '{"scene": {"view": {"hlookat": "-23.285","vlookat" : "0.820","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 102, 2, '{"scene": {"view": {"hlookat": "15.035","vlookat" : "26.530","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 103, 2, '{"scene": {"view": {"hlookat": "-48.956","vlookat" : "26.440","fov" : "139.893"}}}', true, true, 5);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 104, 2, '{"scene": {"view": {"hlookat": "360.430","vlookat" : "33.959","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 80, 2, '{"scene": {"view": {"hlookat": "-20.397","vlookat" : "8.412","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 324, 2, '{"scene": {"view": {"hlookat": "14.316","vlookat" : "13.418","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 325, 2, '{"scene": {"view": {"hlookat": "4.976","vlookat" : "24.881","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 132, 2, '{"scene": {"view": {"hlookat": "164.808","vlookat" : "3.681","fov" : "140.000"}}}', true, true, 6);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 252, 2, '{"scene": {"view": {"hlookat": "-14.265","vlookat" : "5.247","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 297, 2, '{"scene": {"view": {"hlookat": "119.524","vlookat" : "4.721","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 298, 2, '{"scene": {"view": {"hlookat": "85.477","vlookat" : "25.279","fov" : "139.951"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 299, 2, '{"scene": {"view": {"hlookat": "-414.984","vlookat" : "22.401","fov" : "139.996"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 303, 2, '{"scene": {"view": {"hlookat": "176.368","vlookat" : "33.992","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 309, 2, '{"scene": {"view": {"hlookat": "-178.933","vlookat" : "31.426","fov" : "139.991"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 310, 2, '{"scene": {"view": {"hlookat": "357.891","vlookat" : "35.968","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 465, 2, '{"scene": {"view": {"hlookat": "181.608","vlookat" : "27.989","fov" : "139.881"}}}', true, true, 7);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 731, 2, '{"scene": {"view": {"hlookat": "448.655","vlookat" : "24.063","fov" : "139.925"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 732, 2, '{"scene": {"view": {"hlookat": "-288.668","vlookat" : "-0.955","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 733, 2, '{"scene": {"view": {"hlookat": "-1.493","vlookat" : "-0.080","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 734, 2, '{"scene": {"view": {"hlookat": "7.480","vlookat" : "1.646","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1183, 2, '{"scene": {"view": {"hlookat": "81.987","vlookat" : "9.674","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1316, 2, '{"scene": {"view": {"hlookat": "-3.139","vlookat" : "-15.319","fov" : "140.000"}}}', true, true, 8);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1317, 2, '{"scene": {"view": {"hlookat": "-106.682","vlookat" : "1.149","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1367, 2, '{"scene": {"view": {"hlookat": "182.524","vlookat" : "7.801","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1368, 2, '{"scene": {"view": {"hlookat": "382.701","vlookat" : "7.121","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1369, 2, '{"scene": {"view": {"hlookat": "-28.531","vlookat" : "0.492","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1370, 2, '{"scene": {"view": {"hlookat": "112.760","vlookat" : "2.785","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1378, 2, '{"scene": {"view": {"hlookat": "-10.879","vlookat" : "1.579","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1379, 2, '{"scene": {"view": {"hlookat": "65.077","vlookat" : "2.762","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 37, 2, '{"scene": {"view": {"hlookat": "-1.682","vlookat" : "35.777","fov" : "140.000"}}}', true, true, 9);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 38, 2, '{"scene": {"view": {"hlookat": "-141.867","vlookat" : "21.444","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 481, 2, '{"scene": {"view": {"hlookat": "17.545","vlookat" : "13.282","fov" : "140.000"}}}', true, true, 10);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 484, 2, '{"scene": {"view": {"hlookat": "-160.307","vlookat" : "16.890","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 164, 2, '{"scene": {"view": {"hlookat": "180.551","vlookat" : "34.738","fov" : "140.000"}}}', true, true, 11);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 160, 2, '{"scene": {"view": {"hlookat": "176.101","vlookat" : "10.620","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 162, 2, '{"scene": {"view": {"hlookat": "101.383","vlookat" : "17.704","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1105, 2, '{"scene": {"view": {"hlookat": "52.797","vlookat" : "13.445","fov" : "140.000"}}}', true, true, 12);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1106, 2, '{"scene": {"view": {"hlookat": "20.332","vlookat" : "5.575","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1107, 2, '{"scene": {"view": {"hlookat": "17.381","vlookat" : "10.494","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1116, 2, '{"scene": {"view": {"hlookat": "-15.085","vlookat" : "22.128","fov" : "140.000"}}}', true, true, 13);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1117, 2, '{"scene": {"view": {"hlookat": "172.226","vlookat" : "12.651","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1118, 2, '{"scene": {"view": {"hlookat": "9.018","vlookat" : "14.593","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1126, 2, '{"scene": {"view": {"hlookat": "174.560","vlookat" : "12.731","fov" : "140.000"}}}', true, true, 14);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1127, 2, '{"scene": {"view": {"hlookat": "7.706","vlookat" : "8.363","fov" : "140.000"}}}', true, true, 15);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1129, 2, '{"scene": {"view": {"hlookat": "211.526","vlookat" : "20.893","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1130, 2, '{"scene": {"view": {"hlookat": "292.498","vlookat" : "16.228","fov" : "140.000"}}}', true, true, 16);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1131, 2, '{"scene": {"view": {"hlookat": "154.961","vlookat" : "7.856","fov" : "140.000"}}}', true, true, 17);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1132, 2, '{"scene": {"view": {"hlookat": "40.493","vlookat" : "17.053","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1133, 2, '{"scene": {"view": {"hlookat": "18.741","vlookat" : "26.966","fov" : "140.000"}}}');




UPDATE cms_hotel SET has_360 = true WHERE id = @hid;


INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location IS NULL, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE hi.hotel_id = @hid AND tt_media_type_id = 2;

