# Hampshire-Hotel Voncken-Valkenburg
UPDATE cms_hotel_source SET location_id = 2901001 WHERE source_id = 8004;

# Parkhotel Valkenburg
UPDATE cms_hotel_source SET location_id = 2901001 WHERE source_id = 8006;

# Fontana Resort Bad Nieuweschans
UPDATE cms_hotel_source SET location_id = 2900348 WHERE source_id = 11299;

# De Pelikaan Texel
UPDATE cms_hotel_source SET location_id = 2900674 WHERE source_id = 13783;

# Hotel Juliana
UPDATE cms_hotel_source SET location_id = 2901001 WHERE source_id = 33311;

# Orion
UPDATE cms_hotel_source SET location_id = 2900329 WHERE source_id = 33800;

# Walram
UPDATE cms_hotel_source SET location_id = 2901001 WHERE source_id = 40871;

# Hampshire Hotel - Holthurnsche Hof
UPDATE cms_hotel_source SET location_id = 2900798 WHERE source_id = 41854;

# Hotel Kasteel Geulzicht
UPDATE cms_hotel_source SET location_id = 2901001 WHERE source_id = 42926;

# Hotel de Klok
UPDATE cms_hotel_source SET location_id = 2900977 WHERE source_id = 42937;

# Hotel Ameland
UPDATE cms_hotel_source SET location_id = 2900977 WHERE source_id = 42938;

# Hotel en Restaurant de Fortuna
UPDATE cms_hotel_source SET location_id = 2901004 WHERE source_id = 42954;

# Gr8 Hotel Sevenum
UPDATE cms_hotel_source SET location_id = 2900330 WHERE source_id = 42969;

# Landgoed Hotel Tatenhove
UPDATE cms_hotel_source SET location_id = 2900674 WHERE source_id = 42972;

# Sandton Chateau De Raay
UPDATE cms_hotel_source SET location_id = 2900683 WHERE source_id = 44169;

# Hotel de Oorsprong
UPDATE cms_hotel_source SET location_id = 2900976 WHERE source_id = 44175;

# Hotel de l''Empereur Valkenburg
UPDATE cms_hotel_source SET location_id = 2901001 WHERE source_id = 44887;


INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Valkenburg aan de Geul (Limburg)", 2901001, 128679, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Oldambt (Groningen)", 2900348, 130116, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Texel (Noord-Holland)", 2900674, 126542, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Kaag en Braassem (Zuid-Holland)", 2900329, 132115, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Berg en Dal, Ubbergen (Gelderland)", 2900798, 130288, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Ameland (Friesland)", 2900977, 130360, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Edam-Volendam (Noord-Holland)", 2901004, 130633, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Horst aan de Maas (Limburg)", 2900330, 132124, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Peel en Maas (Limburg)", 2900683, 132445, "hrs");
INSERT INTO cms_hotel_city(city_name, location_id, city_id, source) VALUES ("Skarsterlân (Friesland)", 2900976, 132188, "hrs");
