
INSERT INTO cms_hotel 
(id, name, street, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, normalized_name) 
SELECT 417423 AS id, 'Millennium Place Dubai Marina' AS name, 'Al Marsa Street' AS street, '215373' AS zip_code, 'Dubai (Dubai)' AS city, c.id AS city_id, 25.071064 AS latitude, 55.136334 AS longitude, 'ARE' AS iso3_country_code, 'AE' AS country_code, 4 AS stars, normalize_label('Millennium Place Dubai Marina') AS normalized_name 
FROM webgeocities c 
WHERE c.country_code = 'AE' AND c.name = 'Dubai';


INSERT INTO cms_hotel_source 
(hotel_id, source, source_id, location_id) 
VALUES 
(417423, 'hrs', 999201, 6879);


# The same (city_id, location_id) combination already exists in cms_hotel_city, so there
