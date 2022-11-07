
CREATE TABLE hotel_inactive_property 
(source VARCHAR(20) NOT NULL, 
source_id INT(11) NOT NULL, 
deactivation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(), 
CONSTRAINT UNIQUE KEY (source, source_id), 
INDEX IDX_HOTEL_INACTIVE_PROPERTY_SOURCE (source), 
INDEX IDX_HOTEL_INACTIVE_PROPERTY_SID (source_id));

CREATE TABLE hotel_property 
(source VARCHAR(20) NOT NULL, 
source_id INT(11) NOT NULL, 
name VARCHAR(255) NOT NULL, 
street VARCHAR(255) DEFAULT NULL, 
zip_code VARCHAR(50) DEFAULT NULL, 
city VARCHAR(255) DEFAULT NULL, 
location_id BIGINT(20) DEFAULT NULL, 
latitude DECIMAL(10, 6) DEFAULT NULL, 
longitude DECIMAL(10, 6) DEFAULT NULL, 
iso3_country_code CHAR(3) DEFAULT NULL, 
country_code CHAR(2) DEFAULT NULL, 
stars TINYINT(4) NOT NULL DEFAULT 0, 
creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(), 
INDEX IDX_HOTEL_PROP_SOURCE (source), 
INDEX IDX_HOTEL_PROP_SID (source_id), 
INDEX IDX_HOTEL_PROP_3COUNTRY_CODE (iso3_country_code), 
INDEX IDX_HOTEL_PROP_COUNTRY_CODE (country_code), 
INDEX IDX_HOTEL_PROP_CREATION_DATE (creation_date)
);


# Activate all hotels (source_ids) to clean our data and stay up-to-date with HRS total inventory

UPDATE cms_hotel_source SET is_active = 1;

# Deativate all source_ids that are present inside Total_Inventory_toRemove.csv
UPDATE cms_hotel_source hs 
INNER JOIN hotel_inactive_property inp ON (inp.source_id = hs.source_id) 
SET hs.is_active = 0 
WHERE hs.source = 'hrs';
 


# Deactivate all source_ids that are not present in Total_Inventory.csv

UPDATE cms_hotel_source hs 
LEFT JOIN hotel_property inv ON (inv.source_id = hs.source_id) 
SET hs.is_active = 0 
WHERE hs.source = 'hrs' AND inv.source_id IS NULL;


# Activate all hotels with 360

UPDATE cms_hotel_source hs 
INNER JOIN cms_hotel h ON (h.id = hs.hotel_id AND h.has_360) 
SET hs.is_active = 1 
WHERE hs.source = 'hrs';

