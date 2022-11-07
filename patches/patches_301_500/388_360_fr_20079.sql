

# Villa Opéra Drouot

# https://www.touristtube.com/hotel-details-Villa+Opera+Drouot-20079


SET @hid = 20079;

DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = @hid;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = @hid;



INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Street View', @hid, 79, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 130, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Lobby', @hid, 1, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('-', @hid, 15, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Breakfast Room', @hid, 6, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 1', @hid, 25, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 2', @hid, 26, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Single Room', @hid, 530, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 1', @hid, 531, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 535, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Double or Twin Standard Room', @hid, 427, 5);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 1', @hid, 428, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 2', @hid, 429, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 430, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Deluxe Room', @hid, 133, 6);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 1', @hid, 134, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 2', @hid, 136, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 138, 3);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 3', @hid, 135, 4);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Junior Suite', @hid, 212, 7);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 1', @hid, 213, 1);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('View 2', @hid, 214, 2);
INSERT INTO hotel_to_hotel_divisions (name, hotel_id, hotel_division_id, sort_order) VALUES ('Bathroom', @hid, 217, 3);



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
('thumb.jpg', 20079, 130, 2, '{"scene": {"view": {"hlookat": "-170.316","vlookat" : "-18.960","fov" : "140.000"}}}', true, true, 1);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 15, 2, '{"scene": {"view": {"hlookat": "18.362","vlookat" : "1.639","fov" : "140.000"}}}', true, true, 2);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 25, 2, '{"scene": {"view": {"hlookat": "-37.057","vlookat" : "12.953","fov" : "140.000"}}}', true, true, 3);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 26, 2, '{"scene": {"view": {"hlookat": "346.293","vlookat" : "-1.715","fov" : "140.000"}}}', true, true, 4);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 531, 2, '{"scene": {"view": {"hlookat": "34.270","vlookat" : "15.086","fov" : "140.000"}}}', true, true, 5);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 535, 2, '{"scene": {"view": {"hlookat": "-161.168","vlookat" : "20.707","fov" : "140.000"}}}', true, true, 6);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 428, 2, '{"scene": {"view": {"hlookat": "152.795","vlookat" : "15.312","fov" : "140.000"}}}', true, true, 7);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 429, 2, '{"scene": {"view": {"hlookat": "218.561","vlookat" : "26.314","fov" : "140.000"}}}', true, true, 8);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 430, 2, '{"scene": {"view": {"hlookat": "-193.479","vlookat" : "28.848","fov" : "140.000"}}}', true, true, 9);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 134, 2, '{"scene": {"view": {"hlookat": "-14.758","vlookat" : "31.973","fov" : "140.000"}}}', true, true, 10);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 136, 2, '{"scene": {"view": {"hlookat": "3.940","vlookat" : "7.211","fov" : "140.000"}}}', true, true, 11);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 138, 2, '{"scene": {"view": {"hlookat": "207.154","vlookat" : "15.466","fov" : "140.000"}}}', true, true, 12);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 135, 2, '{"scene": {"view": {"hlookat": "18.664","vlookat" : "7.699","fov" : "140.000"}}}', true, true, 13);

INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 213, 2, '{"scene": {"view": {"hlookat": "138.831","vlookat" : "14.384","fov" : "140.000"}}}', true, true, 14);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 214, 2, '{"scene": {"view": {"hlookat": "32.051","vlookat" : "18.726","fov" : "140.000"}}}', true, true, 15);
INSERT INTO cms_hotel_image 
(filename, hotel_id, hotel_division_id, tt_media_type_id, media_settings, default_pic, is_featured, sort_order) 
VALUES 
('thumb.jpg', 20079, 217, 2, '{"scene": {"view": {"hlookat": "-19.348","vlookat" : "14.914","fov" : "140.000"}}}', true, true, 16);





DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;


INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location IS NULL, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE hi.hotel_id = @hid AND tt_media_type_id = 2;


UPDATE cms_hotel SET has_360 = true WHERE id = @hid;

