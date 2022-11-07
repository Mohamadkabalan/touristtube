ALTER TABLE `hotel_reservation` CHANGE `auth_token` `reference` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `hotel_reservation` CHANGE `amadeus_details` `details` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


# temporary setup for the testing phase.

# ALTER TABLE hotel_reservation ADD COLUMN reference VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

# ALTER TABLE hotel_reservation ADD COLUMN details TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

# UPDATE hotel_reservation SET reference = auth_token, details = amadeus_details;

