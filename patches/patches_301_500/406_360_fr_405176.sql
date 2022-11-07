
# Hôtel Bowmann

# https://www.touristtube.com/hotel-details-Hotel+Bowmann-405176


SET @hid = 405176;


DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;
DELETE FROM cms_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;



INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Entrance', @hid, 79, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 130, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 193, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 194, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby', @hid, 1, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception', @hid, 15, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby', @hid, 16, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Restaurant 99 Haussmann and its Garden', @hid, 28, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 29, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 30, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 31, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 32, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('The Bar 99 Haussmann', @hid, 167, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 168, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 169, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Spa', @hid, 4, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 101, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 102, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 103, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Wellness', @hid, 2, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 21, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 92, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Gym', @hid, 3, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 80, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Rooftop - Panoramic View Of Paris', @hid, 1391, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1392, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1393, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1394, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bowmann Room', @hid, 159, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 160, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 164, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 162, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Executive Haussmann - 1', @hid, 10, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 47, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 48, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Executive Haussmann - 2', @hid, 11, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 56, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 58, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 57, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Haussmann Deluxe', @hid, 1072, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1073, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1074, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Haussmann Prestige', @hid, 1104, 13);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1105, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1106, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Junior Suite', @hid, 212, 14);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 216, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 217, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Baron Haussmann Suite - 1', @hid, 1115, 15);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1116, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1117, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1118, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 1119, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 1120, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Baron Haussmann Suite - 2', @hid, 1125, 16);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 1126, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Baron Haussmann Suite - Modern', @hid, 1137, 17);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1138, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1139, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1140, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Eiffel Prestige Suite', @hid, 1148, 18);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1149, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1150, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1151, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 1152, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bowmann Suite With Panoramic Terrace Overlooking Paris', @hid, 1326, 19);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1327, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1328, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1329, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 1330, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 1331, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 1332, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('7', @hid, 1333, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('8', @hid, 1334, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('9', @hid, 1335, 9);


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
('thumb.jpg', @hid, 130, 2, '{"scene": {"view": {"hlookat": "-10.476","vlookat" : "-18.420","fov" : "140.000"}}}', true, true, 1);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 193, 2, '{"scene": {"view": {"hlookat": "161.606","vlookat" : "-2.492","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 194, 2, '{"scene": {"view": {"hlookat": "167.296","vlookat" : "2.393","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 15, 2, '{"scene": {"view": {"hlookat": "171.004","vlookat" : "18.519","fov" : "140.000"}}}', true, true, 2);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 16, 2, '{"scene": {"view": {"hlookat": "105.741","vlookat" : "9.511","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 29, 2, '{"scene": {"view": {"hlookat": "-11.306","vlookat" : "9.675","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 30, 2, '{"scene": {"view": {"hlookat": "217.924","vlookat" : "12.777","fov" : "140.000"}}}', true, true, 3);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 31, 2, '{"scene": {"view": {"hlookat": "171.092","vlookat" : "11.433","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 32, 2, '{"scene": {"view": {"hlookat": "284.983","vlookat" : "9.774","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 168, 2, '{"scene": {"view": {"hlookat": "-8.527","vlookat" : "6.419","fov" : "140.000"}}}', true, true, 4);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 169, 2, '{"scene": {"view": {"hlookat": "9.842","vlookat" : "14.591","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 101, 2, '{"scene": {"view": {"hlookat": "20.824","vlookat" : "24.100","fov" : "140.000"}}}', true, true, 5);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 102, 2, '{"scene": {"view": {"hlookat": "106.839","vlookat" : "11.342","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 103, 2, '{"scene": {"view": {"hlookat": "-10.167","vlookat" : "16.234","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 21, 2, '{"scene": {"view": {"hlookat": "354.979","vlookat" : "-11.076","fov" : "140.000"}}}', true, true, 6);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 92, 2, '{"scene": {"view": {"hlookat": "-19.513","vlookat" : "2.624","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 80, 2, '{"scene": {"view": {"hlookat": "-193.946","vlookat" : "13.761","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1392, 2, '{"scene": {"view": {"hlookat": "-385.707","vlookat" : "-6.735","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1393, 2, '{"scene": {"view": {"hlookat": "-135.284","vlookat" : "0.911","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1394, 2, '{"scene": {"view": {"hlookat": "181.021","vlookat" : "18.353","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 160, 2, '{"scene": {"view": {"hlookat": "-18.529","vlookat" : "2.460","fov" : "140.000"}}}', true, true, 7);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 164, 2, '{"scene": {"view": {"hlookat": "349.295","vlookat" : "-0.897","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 162, 2, '{"scene": {"view": {"hlookat": "470.980","vlookat" : "11.359","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 47, 2, '{"scene": {"view": {"hlookat": "133.017","vlookat" : "22.299","fov" : "140.000"}}}', true, true, 8);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 48, 2, '{"scene": {"view": {"hlookat": "-26.236","vlookat" : "0.984","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 56, 2, '{"scene": {"view": {"hlookat": "178.958","vlookat" : "4.776","fov" : "140.000"}}}', true, true, 9);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 58, 2, '{"scene": {"view": {"hlookat": "9.346","vlookat" : "13.774","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 57, 2, '{"scene": {"view": {"hlookat": "-27.402","vlookat" : "1.467","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1073, 2, '{"scene": {"view": {"hlookat": "7.543","vlookat" : "12.462","fov" : "140.000"}}}', true, true, 10);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1074, 2, '{"scene": {"view": {"hlookat": "156.433","vlookat" : "13.722","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1105, 2, '{"scene": {"view": {"hlookat": "168.229","vlookat" : "1.610","fov" : "140.000"}}}', true, true, 11);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1106, 2, '{"scene": {"view": {"hlookat": "-17.709","vlookat" : "7.543","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 216, 2, '{"scene": {"view": {"hlookat": "-16.234","vlookat" : "2.460","fov" : "140.000"}}}', true, true, 12);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 217, 2, '{"scene": {"view": {"hlookat": "170.542","vlookat" : "7.862","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1116, 2, '{"scene": {"view": {"hlookat": "150.985","vlookat" : "6.958","fov" : "140.000"}}}', true, true, 13);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1117, 2, '{"scene": {"view": {"hlookat": "-14.758","vlookat" : "13.446","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1118, 2, '{"scene": {"view": {"hlookat": "105.249","vlookat" : "11.422","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1119, 2, '{"scene": {"view": {"hlookat": "188.650","vlookat" : "5.527","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1120, 2, '{"scene": {"view": {"hlookat": "172.186","vlookat" : "-8.757","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1126, 2, '{"scene": {"view": {"hlookat": "-9.839","vlookat" : "9.361","fov" : "140.000"}}}', true, true, 14);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1138, 2, '{"scene": {"view": {"hlookat": "-5.247","vlookat" : "-4.099","fov" : "140.000"}}}', true, true, 15);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1139, 2, '{"scene": {"view": {"hlookat": "-11.314","vlookat" : "15.906","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1140, 2, '{"scene": {"view": {"hlookat": "12.298","vlookat" : "5.083","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1149, 2, '{"scene": {"view": {"hlookat": "-11.477","vlookat" : "14.101","fov" : "140.000"}}}', true, true, 16);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1150, 2, '{"scene": {"view": {"hlookat": "-72.804","vlookat" : "10.731","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1151, 2, '{"scene": {"view": {"hlookat": "170.418","vlookat" : "18.560","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1152, 2, '{"scene": {"view": {"hlookat": "33.937","vlookat" : "17.707","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1327, 2, '{"scene": {"view": {"hlookat": "193.341","vlookat" : "4.966","fov" : "140.000"}}}', true, true, 17);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1328, 2, '{"scene": {"view": {"hlookat": "-211.961","vlookat" : "13.059","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1329, 2, '{"scene": {"view": {"hlookat": "358.878","vlookat" : "3.918","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1330, 2, '{"scene": {"view": {"hlookat": "315.822","vlookat" : "2.053","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1331, 2, '{"scene": {"view": {"hlookat": "166.879","vlookat" : "13.886","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1332, 2, '{"scene": {"view": {"hlookat": "-40.319","vlookat" : "0.493","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1333, 2, '{"scene": {"view": {"hlookat": "161.923","vlookat" : "2.837","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1334, 2, '{"scene": {"view": {"hlookat": "162.422","vlookat" : "-0.534","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1335, 2, '{"scene": {"view": {"hlookat": "251.927","vlookat" : "-3.140","fov" : "140.000"}}}');



UPDATE cms_hotel SET has_360 = true WHERE id = @hid;


INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location IS NULL, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE hi.hotel_id = @hid AND tt_media_type_id = 2;

