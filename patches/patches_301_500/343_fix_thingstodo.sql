

UPDATE cms_thingstodo t 
INNER JOIN webgeocities c ON (c.id = t.city_id) 
SET t.state_code = c.state_code;



UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 13) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.city_id = t.city_id, ttd.state = t.state_code 
WHERE ttd.id = 136;



UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 14) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.city_id = t.city_id, ttd.state = t.state_code 
WHERE ttd.id = 143;



UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 6) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.city_id = t.city_id 
WHERE ttd.id = 259;



DELETE FROM cms_thingstodo_details WHERE id = 671;



UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 184) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.city_id = t.city_id, ttd.state = t.state_code 
WHERE ttd.id IN (1410, 1412);



UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 115) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.city_id = t.city_id, ttd.state = t.state_code 
WHERE ttd.id = 2957;


UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 118) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.city_id = t.city_id, ttd.state = t.state_code 
WHERE ttd.id = 2965;


UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 553) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.city_id = t.city_id, ttd.state = t.state_code 
WHERE ttd.id IN (3112, 3113, 3114, 3115, 3116);


UPDATE cms_thingstodo_details ttd 
INNER JOIN cms_thingstodo t ON (t.id = 643) 
SET ttd.parent_id = t.id, ttd.country = t.country_code, ttd.state = t.state_code 
WHERE ttd.id = 4199;

