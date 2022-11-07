
ALTER TABLE airport CHANGE airport_code airport_code CHAR(3) NOT NULL;
OPTIMIZE TABLE airport;

ALTER TABLE airline CHANGE code code CHAR(2) NOT NULL;
OPTIMIZE TABLE airline;

ALTER TABLE flight_detail CHANGE airline airline CHAR(2) NOT NULL;
ALTER TABLE flight_detail CHANGE operating_airline operating_airline CHAR(2) NOT NULL;
OPTIMIZE TABLE flight_detail;


# same as flight_detail.fare_calc_line
ALTER TABLE passenger_detail CHANGE fare_calc_line fare_calc_line VARCHAR(1000) DEFAULT NULL;

DROP TABLE IF EXISTS flight_baggage_info;
DROP TABLE IF EXISTS flight_details_selected_search_result;
DROP TABLE IF EXISTS flight_selected_search_result;

CREATE TABLE `flight_selected_search_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `customer_ip` varchar(18) NOT NULL,
  `search_date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `flight_type` varchar(18) NOT NULL,
  `is_pnr_created` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `is_ticket_issued` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `adt_count` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `cnn_count` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `inf_count` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `cabin_selected` CHAR(1) NOT NULL DEFAULT 'Y', 
  PRIMARY KEY (`id`),
  KEY `idx_flight_type` (`flight_type`), 
  KEY `idx_user_id` (`user_id`), 
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `cms_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE, 
  CONSTRAINT `fk_cabin_selected` FOREIGN KEY (`cabin_selected`) REFERENCES `flight_cabin` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE `flight_details_selected_search_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flight_selected_id` int(11) NOT NULL,
  `segment_number` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `from_location` CHAR(3) NOT NULL,
  `to_location` CHAR(3) NOT NULL,
  `is_stop` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
  `terminal_id` VARCHAR(24) DEFAULT NULL,
  `operating_airline` CHAR(2) NOT NULL, 
  `marketing_airline` CHAR(2) NOT NULL, 
  `flight_number` VARCHAR(30) NOT NULL,
  `res_book_design_code` varchar(3) NOT NULL,
  `duration` float(10,2) NOT NULL,
  `fare_basis_code` varchar(30) NOT NULL,
  `fare_calc_line` varchar(1000) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `base_fare` decimal(10,2) NOT NULL,
  `taxes` decimal(10,2) NOT NULL,
  `currency` CHAR(3) CHARACTER SET latin1 NOT NULL,
  `departure_datetime` datetime DEFAULT NULL,
  `arrival_datetime` datetime DEFAULT NULL,
  `type` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flight_selected_id` (`flight_selected_id`),
  KEY `idx_currency` (`currency`),
  KEY `idx_flight_nbr` (`flight_number`),
  KEY `idx_from_location` (`from_location`),
  KEY `idx_to_location` (`to_location`),
  CONSTRAINT `fk_flight_selected_id` FOREIGN KEY (`flight_selected_id`) REFERENCES `flight_selected_search_result` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT, 
  CONSTRAINT `fk_from_location` FOREIGN KEY (`from_location`) REFERENCES `airport` (`airport_code`) ON UPDATE CASCADE ON DELETE RESTRICT, 
  CONSTRAINT `fk_to_location` FOREIGN KEY (`to_location`) REFERENCES `airport` (`airport_code`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE flight_details_selected_search_result 
ADD CONSTRAINT `fk_operating_airline` FOREIGN KEY (`operating_airline`) REFERENCES `airline` (`code`) ON UPDATE CASCADE ON DELETE RESTRICT;
# ERROR 1215 (HY000): Cannot add foreign key constraint

ALTER TABLE flight_details_selected_search_result 
ADD CONSTRAINT `fk_marketing_airline` FOREIGN KEY (`marketing_airline`) REFERENCES `airline` (`code`) ON UPDATE CASCADE ON DELETE RESTRICT;
# ERROR 1215 (HY000): Cannot add foreign key constraint

ALTER TABLE flight_details_selected_search_result 
ADD CONSTRAINT `fk_currency` FOREIGN KEY (`currency`) REFERENCES `currency` (`code`) ON UPDATE CASCADE ON DELETE RESTRICT;
# ERROR 1215 (HY000): Cannot add foreign key constraint


CREATE TABLE `flight_baggage_info` (
    id INT(11) NOT NULL AUTO_INCREMENT, 
    flight_selected_details_id INT(11) NOT NULL, 
    pieces TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, 
    weight DECIMAL(5,2) UNSIGNED NOT NULL DEFAULT 0, 
    unit VARCHAR(5) NOT NULL DEFAULT 'Kg', 
    description VARCHAR(255) DEFAULT NULL, 
    PRIMARY KEY (`id`), 
    CONSTRAINT `fk_flight_selected_details_id` FOREIGN KEY (`flight_selected_details_id`) REFERENCES `flight_details_selected_search_result` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
);

