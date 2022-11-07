ALTER TABLE `airport` CHANGE COLUMN `city_id` `city_id` INT(11) NULL DEFAULT 0, 
ADD INDEX `idx_airport_city_id` (`city_id` ASC);

ALTER TABLE `airport` ADD CONSTRAINT `fk_airport_city_id` FOREIGN KEY (`city_id`) REFERENCES `webgeocities` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `airport` ADD INDEX `idx_airport_name` (`name` ASC);


UPDATE airport a
SET a.city_id = (SELECT wc.id 
					FROM webgeocities wc 
					WHERE a.country = wc.country_code AND a.city LIKE wc.name 
					GROUP BY CONCAT(wc.country_code, "-", wc.name) 
                    HAVING COUNT(wc.id) = 1
                )
WHERE (a.city_id IS NULL OR a.city_id = 0) AND a.id != 0;




UPDATE airport a SET a.city_id = 1244360 WHERE a.id = 1216;
UPDATE airport a SET a.city_id = 2437688 WHERE a.id = 4719;
UPDATE airport a SET a.city_id = 1311352 WHERE a.id = 2618;
UPDATE airport a SET a.city_id = 246254 WHERE a.id = 5330;
UPDATE airport a SET a.city_id = 934947 WHERE a.id = 9238;
UPDATE airport a SET a.city_id = 934947 WHERE a.id = 9153;
UPDATE airport a SET a.city_id = 859306 WHERE a.id = 9147;
UPDATE airport a SET a.city_id = 846909 WHERE a.id = 9080;
UPDATE airport a SET a.city_id = 935332 WHERE a.id = 7271;
