# Sync the city names between hotel_property and cms_hotel_city
UPDATE cms_hotel_city hc 
INNER JOIN hotel_property hp ON (hp.location_id = hc.location_id AND hp.source = hc.source) 
SET hc.city_name = hp.city;

# Cleanup some duplicated entries
DELETE FROM cms_hotel_city WHERE city_name = 'Gianyar (Bali)' AND location_id = 1911956;
DELETE FROM cms_hotel_city WHERE city_name = 'Manesar' AND location_id = 2095809;

# Update some city_ids inside cms_hotel_city
UPDATE cms_hotel_city SET city_id = 839594 WHERE location_id = 1730;
UPDATE cms_hotel_city SET city_id = 2082369 WHERE location_id = 1788;
UPDATE cms_hotel_city SET city_id = 2082358 WHERE location_id = 1847;
UPDATE cms_hotel_city SET city_id = 2082276 WHERE location_id = 2833;
UPDATE cms_hotel_city SET city_id = 1288380 WHERE location_id = 3262;
UPDATE cms_hotel_city SET city_id = 1831678 WHERE location_id = 3333;		
UPDATE cms_hotel_city SET city_id = 2086033 WHERE location_id = 3377;		
UPDATE cms_hotel_city SET city_id = 951390 WHERE location_id = 3503;		
UPDATE cms_hotel_city SET city_id = 1736175 WHERE location_id = 4110;		
UPDATE cms_hotel_city SET city_id = 417526 WHERE location_id = 4288;		
UPDATE cms_hotel_city SET city_id = 1324739 WHERE location_id = 4360;		
UPDATE cms_hotel_city SET city_id = 2225648 WHERE location_id = 4605;		
UPDATE cms_hotel_city SET city_id = 1832673 WHERE location_id = 6079;		
UPDATE cms_hotel_city SET city_id = 2081648 WHERE location_id = 6967;		
UPDATE cms_hotel_city SET city_id = 1831950 WHERE location_id = 7506;		
UPDATE cms_hotel_city SET city_id = 2081605 WHERE location_id = 7559;		
UPDATE cms_hotel_city SET city_id = 1865125 WHERE location_id = 8092;		
UPDATE cms_hotel_city SET city_id = 2215838 WHERE location_id = 8367;		
UPDATE cms_hotel_city SET city_id = 1831171 WHERE location_id = 9762;
UPDATE cms_hotel_city SET city_id = 2096948 WHERE location_id = 3325440;
UPDATE cms_hotel_city SET city_id = 1834444 WHERE location_id = 3325204;
UPDATE cms_hotel_city SET city_id = 1764319 WHERE location_id = 3324713;
UPDATE cms_hotel_city SET city_id = 1046458 WHERE location_id = 3184056;