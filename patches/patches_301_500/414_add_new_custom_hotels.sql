# CAESARS BLUEWATERS DUBAI
INSERT INTO cms_hotel (id, name, street, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, popularity, published, normalized_name, g_submitted) 
VALUES ('417425', "CAESARS PALACE BLUEWATERS DUBAI", "Caesars Palace, Bluewaters Island - Dubai - United Arab Emirates", NULL, "Dubai", '1060078', '55.120504', '25.080693', 'ARE', 'AE', '5', '1', '1', "CAESARS BLUEWATERS DUBAI", '0');
INSERT INTO cms_hotel_source(hotel_id, source, source_id, location_id) VALUES (417425, 'tt', '-1', '6879');


# CAESARS RESORT BLUEWATERS DUBAI
INSERT INTO cms_hotel (id, name, street, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, popularity, published, normalized_name, g_submitted) 
VALUES ('417426', "CAESARS RESORT BLUEWATERS DUBAI", "Caesars Palace, Bluewaters Island - Dubai - United Arab Emirates", NULL, "Dubai", '1060078', '55.120504', '25.080693', 'ARE', 'AE', '5', '1', '1', "CAESARS RESORT BLUEWATERS DUBAI", '0');
INSERT INTO cms_hotel_source(hotel_id, source, source_id, location_id) VALUES (417426, 'tt', '-1', '6879');


# THE RESIDENCES AT CAESARS PALACE BLUEWATERS DUBAI
INSERT INTO cms_hotel (id, name, street, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, popularity, published, normalized_name, g_submitted) 
VALUES ('417427', "THE RESIDENCES AT CAESARS PALACE BLUEWATERS DUBAI", "Caesars Palace, Bluewaters Island - Dubai - United Arab Emirates", NULL, "Dubai", '1060078', '55.120504', '25.080693', 'ARE', 'AE', '5', '1', '1', "THE RESIDENCES AT CAESARS PALACE BLUEWATERS DUBAI", '0');
INSERT INTO cms_hotel_source(hotel_id, source, source_id, location_id) VALUES (417427, 'tt', '-1', '6879');

# Fixes
UPDATE cms_hotel_city SET city_id = 2454196 WHERE location_id = 288905;
UPDATE cms_hotel_city SET city_name = 'Sankt Johann bei Herberstein (Steiermark)', city_id = 1117390 WHERE location_id = 208812;