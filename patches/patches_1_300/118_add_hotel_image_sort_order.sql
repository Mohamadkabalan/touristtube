UPDATE amadeus_hotel_image
SET default_pic = 1, is_featured = 1
WHERE tt_media_type_id = 2 
AND hotel_id = 261771 and hotel_division_id = 130;

UPDATE amadeus_hotel_image 
SET default_pic = 0, is_featured = 0
WHERE tt_media_type_id = 2 
AND hotel_id = 261771 and hotel_division_id = 193;

