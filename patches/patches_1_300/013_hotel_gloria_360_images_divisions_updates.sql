# Modifying division sorting and naming
#
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Entrance' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '79';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Lobby' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '1';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Le Grand Cafe', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '8';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'La Terrace Restaurant', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '28';
UPDATE `hotel_to_hotel_divisions` SET `hotel_division_id` = '152' WHERE `id` = '122' AND `hotel_id` = '101581';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Pool', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '2';
INSERT INTO `hotel_to_hotel_divisions` (`name`, `hotel_id`, `hotel_division_id`, `sort_order`) VALUES ('Pool Bar', '101581', '7', '6');
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Spa', `sort_order` = '7' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '4';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Gym', `sort_order` = '8' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '3';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Squash', `sort_order` = '9' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '107';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Sky Lounge', `sort_order` = '10' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '94';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Kids Playground', `sort_order` = '11' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '84';
UPDATE `hotel_to_hotel_divisions` SET `name` = '1 Bed Apt Modern', `sort_order` = '12' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '118';
UPDATE `hotel_to_hotel_divisions` SET `name` = '1 Bed Apt Californian', `sort_order` = '13' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '14';
UPDATE `hotel_to_hotel_divisions` SET `name` = '2 Bed Apt Modern', `sort_order` = '14' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '123';


# Modifying scene name
#
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '130';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '15';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '16';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 3', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '17';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 4', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '18';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 5', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '19';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Shops 1', `sort_order` = '6' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '20';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Shops 2', `sort_order` = '7' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '90';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Shops 3', `sort_order` = '8' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '91';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '33';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '34';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '29';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '30';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 3', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '31';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 4', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '32';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 5', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '110';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 6', `sort_order` = '6' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '111';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 7', `sort_order` = '7' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '112';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '21';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '92';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '152';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Entrance View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '101';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Entrance View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '102';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '103';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '104';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 3', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '105';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 4', `sort_order` = '6' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '106';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '80';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '81';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 3', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '82';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 4', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '83';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '108';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '109';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '95';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '96';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 3', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '97';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 4', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '98';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 5', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '99';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Meeting Room', `sort_order` = '6' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '100';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '86';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '87';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 3', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '88';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 4', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '85';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'View 5', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '89';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Dining/Living', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '119';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bedroom', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '121';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Kitchen', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '120';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bathroom', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '122';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Dining/Living', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '75';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Living Room', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '76';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bedroom/Balcony', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '73';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bathroom', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '74';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Kitchen', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '78';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Dining/Living', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '124';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bedroom', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '129';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bathroom 1', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '125';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Balcony/Kitchen', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '127';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Kitchen', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '126';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bathroom 2', `sort_order` = '6' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '128';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Dining/Living View 1', `sort_order` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '66';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Dining/Living View 2', `sort_order` = '2' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '68';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bedroom 1', `sort_order` = '3' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '67';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bedroom 2', `sort_order` = '5' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '114';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bathroom 1', `sort_order` = '4' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '65';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Bathroom 2', `sort_order` = '6' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '69';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Kitchen', `sort_order` = '7' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '70';
UPDATE `hotel_to_hotel_divisions` SET `sort_order` = '8' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '70';
UPDATE `hotel_to_hotel_divisions` SET `name` = 'Balcony', `sort_order` = '7' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '115';


# Assign category to be allowed for this hotel
#
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('101581', '7');


# Change image orders and priorities
#
UPDATE `amadeus_hotel_image` SET `hotel_division_id` = '152' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '152';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '112';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '29';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '33';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '34';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '152';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '104';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '100';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '89';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '121';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '129';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '68';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '85';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '95';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '108';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '65';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '119';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '124';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1', `is_featured` = '1' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '106';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0', `is_featured` = '0' WHERE `hotel_id` = '101581' AND `hotel_division_id` = '104';


# Apply image changes on CMS_HOTEL table
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 101581;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 101581);

