UPDATE cms_hotel_city SET city_name = 'Dublin (Dublin)', location_id = 3070980 WHERE city_id = 2081665;
UPDATE cms_hotel_city SET city_name = 'Florence (Tuscany)' where location_id = 131987;
UPDATE cms_hotel_city SET location_id = 142583 WHERE city_id = 2449862;
DELETE FROM cms_hotel_city WHERE location_id = 142583 AND city_id = 1804427;
UPDATE cms_hotel_search_details SET published = 1 WHERE entity_id = 1653366;
UPDATE cms_hotel_city SET city_name = 'Vilnius' WHERE location_id = 27641;
UPDATE cms_hotel_city SET city_name = 'Belgrade' WHERE location_id = 34714;
UPDATE cms_hotel_city SET city_id = 476139 WHERE location_id = 97830;
DELETE FROM cms_hotel_city WHERE location_id = 363225;
UPDATE cms_hotel_source SET location_id = 97830 WHERE hotel_id = 152264;