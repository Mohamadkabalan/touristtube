DELETE FROM hotel_to_hotel_divisions where hotel_id = '17656' AND hotel_division_id IN(167,168,169,170);

DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 17656 AND hotel_division_id IN(167,168,169,170);

