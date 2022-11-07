UPDATE restaurant r
INNER JOIN cms_hotel h ON h.id=r.hotel_id 
INNER JOIN cms_hotel_source hc ON hc.hotel_id=h.id 
INNER JOIN cms_hotel_city hw ON hw.location_id=hc.location_id and hc.location_id is not null and hc.location_id >0
SET r.city_id = hw.city_id WHERE r.city_id IS NULL;
