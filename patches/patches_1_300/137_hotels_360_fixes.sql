

UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"fov": "140", "hlookat": "359.553", "vlookat": "23.803"}}}' where hotel_id = 347906 and hotel_division_id = 663;
UPDATE `cms_hotel_image` SET `media_settings`='{"scene": {"view": {"fov": "140", "hlookat": "359.553", "vlookat": "23.803"}}}' where hotel_id = 347906 and hotel_division_id = 663;

UPDATE `hotel_to_hotel_divisions` SET `name`='Dvar' where hotel_id = 237677 and hotel_division_id = 188;
UPDATE `hotel_to_hotel_divisions` SET `name`='Zeppelin Bar' where hotel_id = 237677 and hotel_division_id = 7;


UPDATE `amadeus_hotel_image` SET `default_pic`='1', `is_featured`='1' WHERE hotel_id = 76302 and hotel_division_id = 15 AND tt_media_type_id = 2;
UPDATE `amadeus_hotel_image` SET `default_pic`='0', `is_featured`='0' WHERE hotel_id = 76302 and hotel_division_id = 168 AND tt_media_type_id = 2;
UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"fov": "140", "hlookat": "345.059", "vlookat": "1.537"}}}' WHERE tt_media_type_id = 2 AND hotel_id = 76302 and hotel_division_id = 175;

UPDATE `cms_hotel_image` SET `default_pic`='1', `is_featured`='1' WHERE hotel_id = 76302 and hotel_division_id = 15 AND tt_media_type_id = 2;
UPDATE `cms_hotel_image` SET `default_pic`='0', `is_featured`='0' WHERE hotel_id = 76302 and hotel_division_id = 168 AND tt_media_type_id = 2;
UPDATE `cms_hotel_image` SET `media_settings`='{"scene": {"view": {"fov": "140", "hlookat": "345.059", "vlookat": "1.537"}}}' WHERE tt_media_type_id = 2 AND hotel_id = 76302 and hotel_division_id = 175;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 347906;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 347906);


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 76302;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 76302);



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 237677;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 237677);



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 239130;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 239130);



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 287893;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 287893);



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 244707;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 244707);

