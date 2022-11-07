
ALTER TABLE hotel_divisions_categories_group CHANGE COLUMN last_updated last_updated DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE hotel_divisions_categories CHANGE COLUMN last_updated last_updated DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE hotel_to_hotel_divisions ADD created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER sort_order;
ALTER TABLE hotel_to_hotel_divisions CHANGE COLUMN last_updated last_updated DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

UPDATE hotel_to_hotel_divisions SET created_at = DATE_SUB(created_at, INTERVAL 3 DAY) WHERE DATE(created_at) = CURRENT_DATE();


ALTER TABLE amadeus_hotel_image ADD created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER sort_order;
ALTER TABLE amadeus_hotel_image ADD last_updated DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

UPDATE amadeus_hotel_image SET created_at = DATE_SUB(created_at, INTERVAL 3 DAY) WHERE DATE(created_at) = CURRENT_DATE();


ALTER TABLE hotel_to_hotel_divisions_categories ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE hotel_to_hotel_divisions_categories ADD COLUMN last_updated DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP;

UPDATE hotel_to_hotel_divisions_categories SET created_at = DATE_SUB(created_at, INTERVAL 3 DAY) WHERE DATE(created_at) = CURRENT_DATE();
