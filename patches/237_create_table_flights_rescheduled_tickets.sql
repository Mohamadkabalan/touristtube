
CREATE TABLE flights_rescheduled_tickets 
(id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
pnr_id INT(11) NOT NULL, 
FOREIGN KEY fk_flights_rescheduled_tickets_pnr_id (pnr_id) REFERENCES passenger_name_record (id) ON UPDATE CASCADE ON DELETE RESTRICT, 
original_ticket CHAR(13) NOT NULL, 
UNIQUE KEY unique_flights_rescheduled_tickets (pnr_id, original_ticket), 
rescheduled_ticket CHAR(13) NOT NULL, 
reschedule_type TINYINT(1) NOT NULL DEFAULT 1, 
FOREIGN KEY  fk_flights_scheduled_tickets_type (reschedule_type) REFERENCES operation_scope_type (id) ON UPDATE CASCADE ON DELETE RESTRICT, 
direction TINYINT(1) NOT NULL DEFAULT 1, 
FOREIGN KEY  fk_flights_scheduled_tickets_direction (direction) REFERENCES flight_direction (id) ON UPDATE CASCADE ON DELETE RESTRICT, 
scheduled_date DATETIME NOT NULL, 
currency INT(11) NOT NULL DEFAULT 159, 
FOREIGN KEY fk_flights_rescheduled_tickets_currency (currency) REFERENCES currency (id) ON UPDATE CASCADE ON DELETE RESTRICT, 
fee DECIMAL(10, 2) NOT NULL DEFAULT 0, 
operation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
) Engine=InnoDB;
