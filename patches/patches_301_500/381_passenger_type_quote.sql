
CREATE TABLE passenger_type_quote 
(id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
module_id TINYINT(4) NOT NULL DEFAULT 1, 
CONSTRAINT FK_PASSENGER_TYPE_QUOTE_MODULE FOREIGN KEY (module_id) REFERENCES tt_modules (id) ON DELETE RESTRICT ON UPDATE CASCADE, 
module_transaction_id INT(11) NOT NULL, 
passenger_type CHAR(3) NOT NULL DEFAULT 'ADT', 
CONSTRAINT UNIQUE_PASSENGER_TYPE_QUOTE UNIQUE (module_id, module_transaction_id, passenger_type), 
price_quote JSON NOT NULL
) Engine InnoDB;