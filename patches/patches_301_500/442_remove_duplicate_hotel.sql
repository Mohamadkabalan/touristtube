# Remove duplicated hotel (keep the one tied to 360 images). "Taurus Sarovar Portico Hotel" is part of HRS.
UPDATE cms_hotel_source SET source = 'hrs', source_id = 782197, location_id = 287553 WHERE hotel_id = 417400;

UPDATE cms_hotel_image SET hotel_id = 417400 WHERE hotel_id = 195627;
DELETE FROM cms_hotel_source WHERE hotel_id = 195627;
DELETE FROM cms_hotel WHERE id = 195627;