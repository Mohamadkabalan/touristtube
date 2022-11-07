UPDATE `amadeus_hotel` SET `dupe_pool_id` = '700154771', `address_line_1` = 'Nation Towers, Corniche, P.O. Box 60476', `zip_code` = NULL, `phone` = '694 4444', `fax` = '658 1126', `published` = '1' WHERE `amadeus_hotel`.`id` = 144394;

UPDATE `amadeus_hotel` SET `published` = '-2' WHERE `amadeus_hotel`.`id` = 237540;

UPDATE `amadeus_hotel_source` SET `hotel_id` = '144394' WHERE `amadeus_hotel_source`.`hotel_id` = 237540;