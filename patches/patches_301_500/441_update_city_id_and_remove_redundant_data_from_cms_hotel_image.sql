# Update some city_ids
UPDATE cms_hotel_city SET city_id = 2081036 WHERE location_id = 145;
UPDATE cms_hotel_city SET city_id = 2240084 WHERE location_id = 325;
UPDATE cms_hotel_city SET city_id = 1287837 WHERE location_id = 686;

# Remove all images in cms_hotel_image that do not have a FK to cms_hotel
DELETE chi.*
FROM cms_hotel_image chi
LEFT JOIN cms_hotel ch ON (ch.id = chi.hotel_id)
WHERE ch.id IS NULL;