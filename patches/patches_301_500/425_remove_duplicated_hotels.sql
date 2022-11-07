# Duplicate of 372628 
DELETE FROM cms_hotel_image WHERE hotel_id = 394142 AND tt_media_type_id = 1;
DELETE FROM cms_hotel_source WHERE hotel_id = 394142;
DELETE FROM cms_hotel WHERE id = 394142;

# Duplicate of 373034
DELETE FROM cms_hotel_image WHERE hotel_id = 386117 AND tt_media_type_id = 1;
DELETE FROM cms_hotel_source WHERE hotel_id = 386117;
DELETE FROM cms_hotel WHERE id = 386117;
UPDATE cms_hotel SET city_id = 1134806 WHERE id = 373034;

# Duplicate of 404047
DELETE FROM cms_hotel_image WHERE hotel_id = 385680 AND tt_media_type_id = 1;
DELETE FROM cms_hotel_source WHERE hotel_id = 385680;
DELETE FROM cms_hotel WHERE id = 385680;