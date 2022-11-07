
INSERT INTO cms_hotel 
(id, name, street, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, normalized_name) 
SELECT 417422 AS id, 'Millennium Atria Business Bay' AS name, 'Al Abraj Street Business Bay' AS street, 'Dubai (Dubai)' AS city, c.id AS city_id, 25.180156 AS latitude, 55.264109 AS longitude, 'ARE' AS iso3_country_code, 'AE' AS country_code, 5 AS stars, normalize_label('Millennium Atria Business Bay') AS normalized_name 
FROM webgeocities c 
WHERE c.country_code = 'AE' AND c.name = 'Dubai';


INSERT INTO cms_hotel_source 
(hotel_id, source, source_id, location_id) 
VALUES 
(417422, 'hrs', 981813, 6879);


# The same (city_id, location_id) combination already exists in cms_hotel_city
