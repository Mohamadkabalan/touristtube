# Add the new hotel
INSERT INTO cms_hotel (id, name, description, address, street, district, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, logo, map_image, popularity, published, normalized_name, minimized_name, downtown, distance_from_downtown, airport, distance_from_airport, train_station,  distance_from_train_station, pre_20160901, g_submitted) VALUES ('417420', 'Heritage Village Resort & Spa, Goa', NULL, NULL, 'Arossim Beach Road, Cansaulim, Arossim, Goa 403712, India', NULL, NULL, 'Old Goa (State of Goa)', '2482363' , '15.335685', '73.896869', 'IND', 'IN', '4', NULL, '', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0');

# Temporarily enter it online as hrs and not tt
INSERT INTO cms_hotel_source(hotel_id, source, source_id, location_id, trustyou_id) VALUES ('417420', 'hrs', '-1', '30361', NULL);

# Fix city_name
UPDATE cms_hotel_city SET city_name = 'Old Goa (State of Goa)' WHERE location_id = 30361 AND city_id = 2482363;

# Fix state_code
UPDATE webgeocities SET state_code = 33 WHERE id = 2482363;