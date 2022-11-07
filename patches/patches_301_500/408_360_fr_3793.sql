
# The Westin Paris - Vendome

# https://www.touristtube.com/hotel-details-The+Westin+Paris+Vendome-3793


SET @hid = 3793;


DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;
DELETE FROM cms_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;



INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Entrance', @hid, 79, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 193, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby', @hid, 1, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Reception', @hid, 15, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 16, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 17, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 18, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 19, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('La Terrasse D''été', @hid, 28, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 29, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 30, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 31, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 32, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 110, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 111, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Le First Restaurant', @hid, 167, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 168, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 169, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Tuileries  Bar', @hid, 173, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 174, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 175, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 176, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Spa - Six Senses at Rue de Castiglione Paris', @hid, 4, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 101, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 102, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 103, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 104, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 105, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 106, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Westin Workout - Fitness Studio', @hid, 3, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 80, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 81, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Jardin d''Hiver', @hid, 956, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 957, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 958, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Napoleon Ballroom', @hid, 1399, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1400, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1401, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Aiglon Ballroom', @hid, 1407, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1408, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1409, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Imperial Ballroom', @hid, 1415, 11);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 1416, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Imperial Ballroom + Aiglon Ballroom', @hid, 1423, 12);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 1424, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Salon Concorde', @hid, 131, 13);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 132, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 252, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 253, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Salon Tuileries', @hid, 293, 14);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 297, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Salon Vendome Boardroom', @hid, 294, 15);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 303, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 304, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Salon Castiglione', @hid, 295, 16);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 309, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 310, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Salon Feuillants', @hid, 296, 17);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 315, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Salon Saint Honore', @hid, 329, 18);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 330, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Superior Room', @hid, 486, 19);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 487, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 488, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 489, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Deluxe Room', @hid, 480, 20);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 481, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 482, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 483, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Room with a View 1', @hid, 530, 21);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 531, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 532, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 533, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Room with a View 2', @hid, 536, 22);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 537, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 538, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 539, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 540, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Junior Suite', @hid, 212, 23);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 213, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 214, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 215, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 216, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Junior Suite with a View', @hid, 967, 24);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 968, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 969, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 970, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 971, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 972, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 973, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Prestige Suite', @hid, 159, 25);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 160, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 161, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 162, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 163, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 164, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Ambassador Suite', @hid, 1104, 26);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('1', @hid, 1105, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('2', @hid, 1106, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('3', @hid, 1107, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('4', @hid, 1108, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('5', @hid, 1109, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('6', @hid, 1110, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Royal Suite', @hid, 1115, 27);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Room - 1 ', @hid, 1116, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Room - 2', @hid, 1117, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Room - 3', @hid, 1118, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Room - 4', @hid, 1119, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 1', @hid, 1120, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom - 2', @hid, 1121, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 1122, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Presidential Suite', @hid, 1148, 28);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 1 - 1', @hid, 1149, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 1 - 2 ', @hid, 1150, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 2', @hid, 1151, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 2 - Bathroom', @hid, 1152, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 2 - Living Room', @hid, 1153, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Room - 1 ', @hid, 1154, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Room - 2', @hid, 1155, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Living Room - 3', @hid, 1156, 8);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 3 - Living Room', @hid, 1157, 9);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 3 - 2', @hid, 1158, 10);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bedroom 3 - Bathroom', @hid, 1235, 11);


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
('thumb.jpg', @hid, 193, 2, '{"scene": {"view": {"hlookat": "178.824","vlookat" : "-75.391","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 15, 2, '{"scene": {"view": {"hlookat": "339.896","vlookat" : "1.208","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 16, 2, '{"scene": {"view": {"hlookat": "163.118","vlookat" : "-0.050","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 17, 2, '{"scene": {"view": {"hlookat": "362.510","vlookat" : "28.256","fov" : "140.000"}}}', true, true, 1);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 18, 2, '{"scene": {"view": {"hlookat": "370.397","vlookat" : "-0.063","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 19, 2, '{"scene": {"view": {"hlookat": "200.892","vlookat" : "0.588","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 29, 2, '{"scene": {"view": {"hlookat": "-178.368","vlookat" : "-9.317","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 30, 2, '{"scene": {"view": {"hlookat": "177.450","vlookat" : "7.490","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 31, 2, '{"scene": {"view": {"hlookat": "4.847","vlookat" : "8.329","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 32, 2, '{"scene": {"view": {"hlookat": "360.170","vlookat" : "0.491","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 110, 2, '{"scene": {"view": {"hlookat": "-0.498","vlookat" : "8.460","fov" : "140.000"}}}', true, true, 2);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 111, 2, '{"scene": {"view": {"hlookat": "363.110","vlookat" : "-11.806","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 168, 2, '{"scene": {"view": {"hlookat": "-0.776","vlookat" : "19.372","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 169, 2, '{"scene": {"view": {"hlookat": "-0.507","vlookat" : "9.466","fov" : "140.000"}}}', true, true, 3);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 174, 2, '{"scene": {"view": {"hlookat": "361.661","vlookat" : "18.022","fov" : "130.761"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 175, 2, '{"scene": {"view": {"hlookat": "-15.426","vlookat" : "-0.332","fov" : "140.000"}}}', true, true, 4);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 176, 2, '{"scene": {"view": {"hlookat": "596.712","vlookat" : "1.851","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 101, 2, '{"scene": {"view": {"hlookat": "-36.240","vlookat" : "-0.166","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 102, 2, '{"scene": {"view": {"hlookat": "787.907","vlookat" : "16.586","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 103, 2, '{"scene": {"view": {"hlookat": "253.506","vlookat" : "0.662","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 104, 2, '{"scene": {"view": {"hlookat": "108.476","vlookat" : "21.461","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 105, 2, '{"scene": {"view": {"hlookat": "-4.645","vlookat" : "24.715","fov" : "140.000"}}}', true, true, 5);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 106, 2, '{"scene": {"view": {"hlookat": "354.989","vlookat" : "32.061","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 80, 2, '{"scene": {"view": {"hlookat": "202.129","vlookat" : "16.769","fov" : "140.000"}}}', true, true, 6);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 81, 2, '{"scene": {"view": {"hlookat": "167.278","vlookat" : "0.107","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 957, 2, '{"scene": {"view": {"hlookat": "109.372","vlookat" : "-5.087","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 958, 2, '{"scene": {"view": {"hlookat": "353.505","vlookat" : "-21.336","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1400, 2, '{"scene": {"view": {"hlookat": "181.931","vlookat" : "-22.321","fov" : "140.000"}}}', true, true, 7);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1401, 2, '{"scene": {"view": {"hlookat": "335.460","vlookat" : "0.867","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1408, 2, '{"scene": {"view": {"hlookat": "179.643","vlookat" : "-21.701","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1409, 2, '{"scene": {"view": {"hlookat": "221.882","vlookat" : "-0.009","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1416, 2, '{"scene": {"view": {"hlookat": "153.193","vlookat" : "0.909","fov" : "140.000"}}}', true, true, 8);


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1424, 2, '{"scene": {"view": {"hlookat": "177.571","vlookat" : "-16.947","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 132, 2, '{"scene": {"view": {"hlookat": "156.800","vlookat" : "-0.272","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 252, 2, '{"scene": {"view": {"hlookat": "176.919","vlookat" : "-22.369","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 253, 2, '{"scene": {"view": {"hlookat": "307.709","vlookat" : "0.579","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 297, 2, '{"scene": {"view": {"hlookat": "314.185","vlookat" : "-1.481","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 303, 2, '{"scene": {"view": {"hlookat": "0.995","vlookat" : "29.195","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 304, 2, '{"scene": {"view": {"hlookat": "1.992","vlookat" : "-0.878","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 309, 2, '{"scene": {"view": {"hlookat": "156.998","vlookat" : "15.793","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 310, 2, '{"scene": {"view": {"hlookat": "0.842","vlookat" : "17.902","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 315, 2, '{"scene": {"view": {"hlookat": "181.743","vlookat" : "28.282","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 330, 2, '{"scene": {"view": {"hlookat": "180.530","vlookat" : "26.358","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 487, 2, '{"scene": {"view": {"hlookat": "438.850","vlookat" : "30.198","fov" : "134.804"}}}', true, true, 9);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 488, 2, '{"scene": {"view": {"hlookat": "347.565","vlookat" : "1.051","fov" : "139.353"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 489, 2, '{"scene": {"view": {"hlookat": "35.592","vlookat" : "3.381","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 481, 2, '{"scene": {"view": {"hlookat": "176.760","vlookat" : "4.206","fov" : "139.934"}}}', true, true, 10);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 482, 2, '{"scene": {"view": {"hlookat": "703.828","vlookat" : "5.384","fov" : "139.869"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 483, 2, '{"scene": {"view": {"hlookat": "179.221","vlookat" : "1.111","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 531, 2, '{"scene": {"view": {"hlookat": "534.689","vlookat" : "9.252","fov" : "129.931"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 532, 2, '{"scene": {"view": {"hlookat": "-97.756","vlookat" : "24.776","fov" : "139.972"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 533, 2, '{"scene": {"view": {"hlookat": "196.240","vlookat" : "8.343","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 537, 2, '{"scene": {"view": {"hlookat": "721.203","vlookat" : "0.939","fov" : "132.931"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 538, 2, '{"scene": {"view": {"hlookat": "181.915","vlookat" : "31.044","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 539, 2, '{"scene": {"view": {"hlookat": "142.398","vlookat" : "3.695","fov" : "139.002"}}}', true, true, 11);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 540, 2, '{"scene": {"view": {"hlookat": "332.868","vlookat" : "21.682","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 213, 2, '{"scene": {"view": {"hlookat": "342.407","vlookat" : "7.617","fov" : "139.381"}}}', true, true, 12);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 214, 2, '{"scene": {"view": {"hlookat": "265.815","vlookat" : "-0.166","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 215, 2, '{"scene": {"view": {"hlookat": "90.589","vlookat" : "5.660","fov" : "139.827"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 216, 2, '{"scene": {"view": {"hlookat": "351.658","vlookat" : "0.876","fov" : "139.396"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 968, 2, '{"scene": {"view": {"hlookat": "355.850","vlookat" : "18.074","fov" : "136.781"}}}', true, true, 13);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 969, 2, '{"scene": {"view": {"hlookat": "198.703","vlookat" : "1.169","fov" : "139.537"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 970, 2, '{"scene": {"view": {"hlookat": "352.142","vlookat" : "-0.492","fov" : "138.349"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 971, 2, '{"scene": {"view": {"hlookat": "-151.076","vlookat" : "0.049","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 972, 2, '{"scene": {"view": {"hlookat": "423.898","vlookat" : "1.674","fov" : "138.894"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 973, 2, '{"scene": {"view": {"hlookat": "311.406","vlookat" : "0.600","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 160, 2, '{"scene": {"view": {"hlookat": "85.326","vlookat" : "20.780","fov" : "140.000"}}}', true, true, 14);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 161, 2, '{"scene": {"view": {"hlookat": "379.339","vlookat" : "-0.054","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 162, 2, '{"scene": {"view": {"hlookat": "-58.401","vlookat" : "15.857","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 163, 2, '{"scene": {"view": {"hlookat": "383.816","vlookat" : "20.162","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 164, 2, '{"scene": {"view": {"hlookat": "509.547","vlookat" : "-1.085","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1105, 2, '{"scene": {"view": {"hlookat": "-180.696","vlookat" : "0.490","fov" : "137.873"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1106, 2, '{"scene": {"view": {"hlookat": "471.070","vlookat" : "9.451","fov" : "139.977"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1107, 2, '{"scene": {"view": {"hlookat": "702.732","vlookat" : "0.611","fov" : "138.779"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1108, 2, '{"scene": {"view": {"hlookat": "93.148","vlookat" : "4.885","fov" : "140.000"}}}', true, true, 15);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1109, 2, '{"scene": {"view": {"hlookat": "292.502","vlookat" : "2.189","fov" : "139.537"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1110, 2, '{"scene": {"view": {"hlookat": "12.539","vlookat" : "6.487","fov" : "138.312"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1116, 2, '{"scene": {"view": {"hlookat": "720.644","vlookat" : "0.137","fov" : "140.000"}}}', true, true, 16);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1117, 2, '{"scene": {"view": {"hlookat": "860.801","vlookat" : "-2.443","fov" : "139.996"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1118, 2, '{"scene": {"view": {"hlookat": "162.203","vlookat" : "-6.901","fov" : "139.951"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1119, 2, '{"scene": {"view": {"hlookat": "477.117","vlookat" : "0.076","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1120, 2, '{"scene": {"view": {"hlookat": "136.690","vlookat" : "-0.048","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1121, 2, '{"scene": {"view": {"hlookat": "-41.285","vlookat" : "5.618","fov" : "138.731"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1122, 2, '{"scene": {"view": {"hlookat": "408.309","vlookat" : "24.149","fov" : "140.000"}}}');


INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1149, 2, '{"scene": {"view": {"hlookat": "172.055","vlookat" : "-3.223","fov" : "139.977"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1150, 2, '{"scene": {"view": {"hlookat": "270.095","vlookat" : "1.091","fov" : "139.537"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1151, 2, '{"scene": {"view": {"hlookat": "393.086","vlookat" : "-4.420","fov" : "139.987"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1152, 2, '{"scene": {"view": {"hlookat": "-92.594","vlookat" : "-0.396","fov" : "137.501"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1153, 2, '{"scene": {"view": {"hlookat": "381.836","vlookat" : "3.972","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1154, 2, '{"scene": {"view": {"hlookat": "358.399","vlookat" : "-10.372","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1155, 2, '{"scene": {"view": {"hlookat": "177.992","vlookat" : "-3.837","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1156, 2, '{"scene": {"view": {"hlookat": "363.920","vlookat" : "-1.610","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', @hid, 1157, 2, '{"scene": {"view": {"hlookat": "-11.265","vlookat" : "-0.322","fov" : "140.000"}}}', true, true, 17);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1158, 2, '{"scene": {"view": {"hlookat": "192.620","vlookat" : "-0.284","fov" : "140.000"}}}');

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings) 
VALUES 
('thumb.jpg', @hid, 1235, 2, '{"scene": {"view": {"hlookat": "360.627","vlookat" : "0.520","fov" : "140.000"}}}');




UPDATE cms_hotel SET has_360 = true WHERE id = @hid;


INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location IS NULL, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE hi.hotel_id = @hid AND tt_media_type_id = 2;

