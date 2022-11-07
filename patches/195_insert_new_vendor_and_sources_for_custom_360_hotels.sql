# Insert a new vendor TT for non-bookable 360 hotels
INSERT INTO `tt_vendors` (`id`, `name`) VALUES ('5', 'TT');

# Insert the needed sources so that the new custom hotels are searchable and appear as a search result (as non-available)
INSERT INTO amadeus_hotel_source(hotel_id, hotel_code, chain, chain_name, property_identifier, source, tt_vendor_id) VALUES (417400, 'TT', 'TT', 'TT', 'TT', 'tt', 5);
INSERT INTO amadeus_hotel_source(hotel_id, hotel_code, chain, chain_name, property_identifier, source, tt_vendor_id) VALUES (347908, 'TT', 'TT', 'TT', 'TT', 'tt', 5);
INSERT INTO amadeus_hotel_source(hotel_id, hotel_code, chain, chain_name, property_identifier, source, tt_vendor_id) VALUES (347907, 'TT', 'TT', 'TT', 'TT', 'tt', 5);
INSERT INTO amadeus_hotel_source(hotel_id, hotel_code, chain, chain_name, property_identifier, source, tt_vendor_id) VALUES (347906, 'TT', 'TT', 'TT', 'TT', 'tt', 5);

# Set address_line_2 to NULL and not empty
UPDATE `amadeus_hotel` SET `address_line_2` = NULL, zip_code = '2501-1305' WHERE `amadeus_hotel`.`id` = 43067;