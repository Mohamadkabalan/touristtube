UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"hlookat": "101.929", "vlookat": "0"}}}' WHERE hotel_id = 65443 AND hotel_division_id = 593;
UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"hlookat": "62.934", "vlookat": "0"}}}' WHERE hotel_id = 65443 AND hotel_division_id = 594;
UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"hlookat": "88.400", "vlookat": "0"}}}' WHERE hotel_id = 65443 AND hotel_division_id = 595;
UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"hlookat": "0", "vlookat": "0"}}}' WHERE hotel_id = 65443 AND hotel_division_id = 591;
UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"hlookat": "0", "vlookat": "0"}}}' WHERE hotel_id = 65443 AND hotel_division_id = 592;
UPDATE `amadeus_hotel_image` SET `media_settings`='{"scene": {"view": {"hlookat": "0", "vlookat": "0"}}}' WHERE hotel_id = 65443 AND hotel_division_id = 596;


UPDATE `hotel_to_hotel_divisions` SET `sort_order`='1' WHERE `hotel_id`= 65443 AND hotel_division_id = 591;
UPDATE `hotel_to_hotel_divisions` SET `sort_order`='2' WHERE `hotel_id`= 65443 AND hotel_division_id = 592;
UPDATE `hotel_to_hotel_divisions` SET `sort_order`='3' WHERE `hotel_id`= 65443 AND hotel_division_id = 596;
UPDATE `hotel_to_hotel_divisions` SET `sort_order`='4' WHERE `hotel_id`= 65443 AND hotel_division_id = 593;
UPDATE `hotel_to_hotel_divisions` SET `sort_order`='5' WHERE `hotel_id`= 65443 AND hotel_division_id = 594;
UPDATE `hotel_to_hotel_divisions` SET `sort_order`='6' WHERE `hotel_id`= 65443 AND hotel_division_id = 595;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 65443;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 65443);

