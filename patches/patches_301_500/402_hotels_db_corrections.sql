UPDATE cms_hotel SET name = 'Comfort Hotel Cuiaba' WHERE id = 191565;
# # # # #
UPDATE cms_hotel_source SET location_id = 598496 WHERE source_id = 765124;
# # # # #
UPDATE cms_hotel_city SET city_name = 'Cuiabá (Mato Grosso)' WHERE location_id = 598496;
UPDATE cms_hotel_city SET city_name = 'Guangzhou (Guangdong Province)' WHERE location_id = 49102;
UPDATE cms_hotel_city SET city_name = 'El Calafate (Provincia de Santa Cruz)' WHERE location_id = 7315;
# # # # #
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES("Leeuwarderadeel (Friesland)", 2900806, 128770, "hrs");
# # # # #

# Add the new hotel
INSERT INTO cms_hotel (id, name, description, address, street, district, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, logo, map_image, popularity, published, normalized_name, minimized_name, downtown, distance_from_downtown, airport, distance_from_airport, train_station,  distance_from_train_station, pre_20160901, g_submitted) VALUES ('417424', 'Fairfield by Marriott Guangzhou Tianhe Park', NULL, NULL, '277 West Road, Zhongshan Avenue', NULL, '510000', 'Guangzhou (Guangdong Province)', '1380298', '23.131179', '113.370029', 'CHN', 'CN', '4', NULL, '', '1', '1', 'Fairfield by Marriott Guangzhou Tianhe Park', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0');

# Add the source
INSERT INTO cms_hotel_source(hotel_id, source, source_id, location_id, trustyou_id) VALUES ('417424', 'hrs', '972881', '49102', NULL);