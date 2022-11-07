ALTER TABLE `360_hotel_to_accounts`
    ADD `currency` CHAR(3) NOT NULL,
    ADD `amount` DECIMAL(10,2) NOT NULL;

ALTER TABLE currency CHANGE code code CHAR(3) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;

ALTER TABLE currency ADD CONSTRAINT u_currency_code UNIQUE (code);


ALTER TABLE 360_hotel_to_accounts CHANGE currency currency CHAR(3) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;

ALTER TABLE 360_hotel_to_accounts ADD CONSTRAINT fk_360_hotel_to_accounts_currency FOREIGN KEY (currency) REFERENCES currency (code) on UPDATE CASCADE ON DELETE RESTRICT;
