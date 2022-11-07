# Add the new hotel
INSERT INTO cms_hotel (id, name, description, address, street, district, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, logo, map_image, popularity, published, normalized_name, minimized_name, downtown, distance_from_downtown, airport, distance_from_airport, train_station,  distance_from_train_station, pre_20160901, g_submitted) VALUES ('417421', 'Heritage Village Resort & Spa - Manesar', NULL, NULL, 'NH8, Manesar, Gurugram, Haryana 122050, India', NULL, NULL, 'Manesar', '2095809', '28.366109', '76.937877', 'IND', 'IN', '4', NULL, '', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0');

# Temporarily enter it online as hrs and not tt
INSERT INTO cms_hotel_source(hotel_id, source, source_id, location_id, trustyou_id) VALUES ('417421', 'hrs', '-1', '30361', NULL);

# Add the city in cms_hotel_city
INSERT INTO cms_hotel_city(city_name, location_id, popularity, city_id, source) VALUES ('Manesar', '2095809', '1', '2095809', 'tt');