

# Shangri-La Dubai

# https://www.touristtube.com/hotel-details-Shangri+La+Dubai-42613


SET @hid = 42613;

DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = @hid;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = @hid;



INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Exterior', @hid, 1061, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1062, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1063, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1064, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby', @hid, 1, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception 1', @hid, 15, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception 2', @hid, 16, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception 3', @hid, 17, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 18, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 19, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Balcony Bar', @hid, 7, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES (' - ', @hid, 152, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Dunes Café', @hid, 8, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 33, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 34, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 35, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 165, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 166, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 219, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('7', @hid, 220, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Hoi An', @hid, 28, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 29, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 30, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 31, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Horizon Club', @hid, 167, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 168, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 169, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 170, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Gym', @hid, 171, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lounge', @hid, 197, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Pool', @hid, 198, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Terrace', @hid, 199, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby Lounge', @hid, 94, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 95, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 96, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 97, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 98, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Shang Palace', @hid, 173, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 174, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 175, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 176, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('CHI Spa', @hid, 4, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception', @hid, 101, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 102, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 103, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Health Club', @hid, 3, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 80, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 81, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Ikandy Ultralounge', @hid, 2, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 21, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 92, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 186, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 187, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Meeting Rooms', @hid, 131, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Al Bader Ballroom - 1', @hid, 132, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Al Bader Ballroom - 2', @hid, 252, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Al Nojoom Ballroom', @hid, 253, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Jihan', @hid, 254, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Nasreen', @hid, 255, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Rayhan', @hid, 1284, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Sawsan', @hid, 1285, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Yasmin and Arjawan', @hid, 1351, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Zaafaran', @hid, 1352, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Deluxe Burj Khalifa View', @hid, 36, 13);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 1', @hid, 37, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 2', @hid, 38, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 39, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Deluxe Sea View', @hid, 133, 14);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 1', @hid, 134, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 2', @hid, 135, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 138, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Horizon Club Deluxe', @hid, 40, 15);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 1', @hid, 41, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 2', @hid, 42, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 3', @hid, 43, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 335, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Horizon Club Executive', @hid, 1104, 16);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 1', @hid, 1105, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 2', @hid, 1106, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 3', @hid, 1107, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 1109, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Horizon Club Premier', @hid, 1115, 17);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 1', @hid, 1116, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 2', @hid, 1117, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 1118, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('One Bedroom Suite', @hid, 1125, 18);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - Living Room', @hid, 1126, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Dining - Living Room', @hid, 1127, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Guest Bathroom', @hid, 1129, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 1130, 4);



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
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1062, 2, '{"scene": {"view": {"hlookat": "-9.759","vlookat" : "-28.619","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1063, 2, '{"scene": {"view": {"hlookat": "41.809","vlookat" : "-33.823","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1064, 2, '{"scene": {"view": {"hlookat": "-17.791","vlookat" : "-27.821","fov" : "140.000"}}}', true, true, 1);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 15, 2, '{"scene": {"view": {"hlookat": "6.024","vlookat" : "5.949","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 16, 2, '{"scene": {"view": {"hlookat": "-42.794","vlookat" : "0.197","fov" : "140.000"}}}', true, true, 2);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 17, 2, '{"scene": {"view": {"hlookat": "40.165","vlookat" : "1.311","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 18, 2, '{"scene": {"view": {"hlookat": "178.036","vlookat" : "1.297","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 19, 2, '{"scene": {"view": {"hlookat": "10.994","vlookat" : "1.967","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 152, 2, '{"scene": {"view": {"hlookat": "36.101","vlookat" : "-1.509","fov" : "140.000"}}}', true, true, 3);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 33, 2, '{"scene": {"view": {"hlookat": "-4.424","vlookat" : "0.372","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 34, 2, '{"scene": {"view": {"hlookat": "-120.092","vlookat" : "3.780","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 35, 2, '{"scene": {"view": {"hlookat": "294.122","vlookat" : "1.039","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 165, 2, '{"scene": {"view": {"hlookat": "108.659","vlookat" : "2.641","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 166, 2, '{"scene": {"view": {"hlookat": "-155.272","vlookat" : "2.109","fov" : "140.000"}}}', true, true, 4);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 219, 2, '{"scene": {"view": {"hlookat": "-376.402","vlookat" : "-1.109","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 220, 2, '{"scene": {"view": {"hlookat": "-13.118","vlookat" : "0.492","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 29, 2, '{"scene": {"view": {"hlookat": "-26.007","vlookat" : "0.809","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 30, 2, '{"scene": {"view": {"hlookat": "-23.940","vlookat" : "-0.328","fov" : "140.000"}}}', true, true, 5);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 31, 2, '{"scene": {"view": {"hlookat": "18.037","vlookat" : "-0.164","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 168, 2, '{"scene": {"view": {"hlookat": "-189.365","vlookat" : "2.074","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 169, 2, '{"scene": {"view": {"hlookat": "191.492","vlookat" : "2.308","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 170, 2, '{"scene": {"view": {"hlookat": "-197.115","vlookat" : "1.467","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 171, 2, '{"scene": {"view": {"hlookat": "49.020","vlookat" : "2.160","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 197, 2, '{"scene": {"view": {"hlookat": "-203.596","vlookat" : "1.870","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 198, 2, '{"scene": {"view": {"hlookat": "-25.568","vlookat" : "0.165","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 199, 2, '{"scene": {"view": {"hlookat": "-13.610","vlookat" : "-0.492","fov" : "140.000"}}}', true, true, 6);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 95, 2, '{"scene": {"view": {"hlookat": "137.225","vlookat" : "1.876","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 96, 2, '{"scene": {"view": {"hlookat": "170.348","vlookat" : "6.116","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 97, 2, '{"scene": {"view": {"hlookat": "-211.076","vlookat" : "0.911","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 98, 2, '{"scene": {"view": {"hlookat": "68.933","vlookat" : "4.904","fov" : "140.000"}}}', true, true, 7);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 174, 2, '{"scene": {"view": {"hlookat": "344.465","vlookat" : "3.751","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 175, 2, '{"scene": {"view": {"hlookat": "-16.335","vlookat" : "-0.257","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 176, 2, '{"scene": {"view": {"hlookat": "1.640","vlookat" : "18.693","fov" : "140.000"}}}', true, true, 8);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 101, 2, '{"scene": {"view": {"hlookat": "-15.085","vlookat" : "4.591","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 102, 2, '{"scene": {"view": {"hlookat": "-21.972","vlookat" : "13.444","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 103, 2, '{"scene": {"view": {"hlookat": "-8.526","vlookat" : "19.348","fov" : "140.000"}}}', true, true, 9);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 80, 2, '{"scene": {"view": {"hlookat": "49.477","vlookat" : "7.268","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 81, 2, '{"scene": {"view": {"hlookat": "-139.609","vlookat" : "7.519","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 21, 2, '{"scene": {"view": {"hlookat": "253.888","vlookat" : "-0.674","fov" : "140.000"}}}', true, true, 10);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 92, 2, '{"scene": {"view": {"hlookat": "-246.385","vlookat" : "1.622","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 186, 2, '{"scene": {"view": {"hlookat": "350.480","vlookat" : "0.417","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 187, 2, '{"scene": {"view": {"hlookat": "-192.633","vlookat" : "-0.427","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 132, 2, '{"scene": {"view": {"hlookat": "-10.430","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 252, 2, '{"scene": {"view": {"hlookat": "-160.568","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 253, 2, '{"scene": {"view": {"hlookat": "-25.567","vlookat" : "0.000","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 254, 2, '{"scene": {"view": {"hlookat": "9.004","vlookat" : "17.933","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 255, 2, '{"scene": {"view": {"hlookat": "-7.426","vlookat" : "11.931"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1284, 2, '{"scene": {"view": {"hlookat": "187.600","vlookat" : "25.567"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1285, 2, '{"scene": {"view": {"hlookat": "5.995","vlookat" : "19.430"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1351, 2, '{"scene": {"view": {"hlookat": "15.067","vlookat" : "10.499"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1352, 2, '{"scene": {"view": {"hlookat": "-13.435","vlookat" : "10.638"}}}', true, true, 11);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 37, 2, '{"scene": {"view": {"hlookat": "29.843","vlookat" : "14.594"}}}', true, true, 12);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 38, 2, '{"scene": {"view": {"hlookat": "-4.754","vlookat" : "16.723"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 39, 2, '{"scene": {"view": {"hlookat": "32.470","vlookat" : "16.388"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 134, 2, '{"scene": {"view": {"hlookat": "-20.659","vlookat" : "3.935"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 135, 2, '{"scene": {"view": {"hlookat": "-14.591","vlookat" : "13.938"}}}', true, true, 13);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 138, 2, '{"scene": {"view": {"hlookat": "-30.003","vlookat" : "14.263"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 41, 2, '{"scene": {"view": {"hlookat": "-23.284","vlookat" : "1.804"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 42, 2, '{"scene": {"view": {"hlookat": "-21.316","vlookat" : "7.707"}}}', true, true, 14);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 43, 2, '{"scene": {"view": {"hlookat": "159.135","vlookat" : "7.407"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 335, 2, '{"scene": {"view": {"hlookat": "106.861","vlookat" : "13.899"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1105, 2, '{"scene": {"view": {"hlookat": "-12.626","vlookat" : "0.984"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1106, 2, '{"scene": {"view": {"hlookat": "-16.725","vlookat" : "-0.164"}}}', true, true, 15);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1107, 2, '{"scene": {"view": {"hlookat": "153.259","vlookat" : "7.788"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1109, 2, '{"scene": {"view": {"hlookat": "103.778","vlookat" : "11.725"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1116, 2, '{"scene": {"view": {"hlookat": "0.001","vlookat" : "5.575"}}}', true, true, 16);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1117, 2, '{"scene": {"view": {"hlookat": "347.724","vlookat" : "25.620"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1118, 2, '{"scene": {"view": {"hlookat": "25.082","vlookat" : "11.802"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1126, 2, '{"scene": {"view": {"hlookat": "-19.840","vlookat" : "4.919"}}}', true, true, 17);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1127, 2, '{"scene": {"view": {"hlookat": "-145.522","vlookat" : "12.709"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1129, 2, '{"scene": {"view": {"hlookat": "20.005","vlookat" : "23.612"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1130, 2, '{"scene": {"view": {"hlookat": "-22.463","vlookat" : "19.676"}}}');




DELETE hi
FROM cms_hotel_image hi
INNER JOIN hotel_divisions d ON (d.id = hi.hotel_division_id AND d.parent_id IS NULL) 
WHERE hi.hotel_id = @hid AND hi.tt_media_type_id = 2;




DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;


INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT hi.user_id, hi.filename, hi.hotel_id, hi.hotel_division_id, hi.tt_media_type_id, COALESCE(hi.location, '') AS location, hi.default_pic, hi.is_featured, hi.media_settings, hi.sort_order 
FROM cms_hotel_image hi 
INNER JOIN amadeus_hotel ah ON (ah.id = @hid) 
WHERE hi.hotel_id = @hid AND hi.tt_media_type_id = 2;


UPDATE cms_hotel SET has_360 = true WHERE id = @hid;

