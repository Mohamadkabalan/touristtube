SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE corpo_admin_menu DROP COLUMN `type`;
ALTER TABLE corpo_admin_menu ADD COLUMN `type` INT DEFAULT 1;

TRUNCATE TABLE `corpo_admin_menu`;

INSERT INTO `corpo_admin_menu` (`id`, `name`, `order`, `url`, `type`, `parent_id`, `published`, `enable_for_mobile`, `mobile_trigger_method`, `path`, `onclick`) VALUES
(1, 'Flights', 1, '/corporate/flight', 1, NULL, 1, 0, NULL, ',1,', NULL),
(2, 'Hotels', 2, '/corporate/hotels/search', 1, NULL, 1, 0, NULL, ',2,', NULL),
(3, 'Tourist Tube', 3, NULL, 1, NULL, 1, 0, NULL, ',3,', NULL),
(4, 'Accounts', 4, '/corporate/admin/account', 1, 3, 1, 0, NULL, ',3,4,', NULL),
(5, 'Payments', 5, '/corporate/admin/payment', 1, 3, 1, 0, NULL, ',3,5,', NULL),
(6, 'User Profiles', 6, '/corporate/admin/userProfiles', 1, 3, 1, 0, NULL, ',3,6,', NULL),
(7, 'Account Types', 7, '/corporate/admin/accountType', 1, 3, 1, 0, NULL, ',3,7,', NULL),
(8, 'Welcome', 8, NULL, 1, NULL, 1, 0, NULL, ',8,', NULL),
(9, 'Sales Persons', 9, '/corporate/definitions/users-sales', 1, 8, 1, 0, NULL, ',8,9,', NULL),
(10, 'Users', 10, '/corporate/definitions/users', 1, 8, 1, 0, NULL, ',8,10,', NULL),
(11, 'Affiliates', 11, '/corporate/account/affiliate', 1, 8, 1, 0, NULL, ',8,11,', NULL),
(12, 'Companies', 12, '/corporate/account/company', 1, 8, 1, 0, NULL, ',8,12,', NULL),
(13, 'Agencies', 13, '/corporate/account/agency', 1, 8, 1, 0, NULL, ',8,13,', NULL),
(14, 'Statement of Account', 14, '/corporate/statement-of-account', 1, 8, 1, 0, NULL, ',8,14,', NULL),
(15, 'Manage Bookings', 15, '/corporate/account/manage-bookings', 1, 8, 1, 0, NULL, ',8,15,', NULL),
(16, 'All Bookings', 16, '/corporate/reports-allbooking', 1, 8, 1, 0, NULL, ',8,16,', NULL),
(17, 'Reports', 17, '/corporate/reports-allbookings', 1, 8, 1, 0, NULL, ',8,17,', NULL),
(18, 'Profile Settings', 18, '/corporate/profile-settings', 1, 8, 1, 0, NULL, ',8,18,', NULL),
(19, 'Change Password', 19, '#', 1, 8, 1, 0, NULL, ',8,19,', 'changePasword(\'/app_dev.php/corporate/password/change\')'),
(20, 'Logout', 20, '/corporate/logout', 1, 8, 1, 0, NULL, ',8,20,', NULL);

SET FOREIGN_KEY_CHECKS=1;



UPDATE `corpo_admin_menu` SET `order`='10' WHERE `id`='3';
UPDATE `corpo_admin_menu` SET `order`='20' WHERE `id`='8';
UPDATE `corpo_admin_menu` SET `order`='11' WHERE `id`='4';
UPDATE `corpo_admin_menu` SET `order`='12' WHERE `id`='5';
UPDATE `corpo_admin_menu` SET `order`='13' WHERE `id`='6';
UPDATE `corpo_admin_menu` SET `order`='14' WHERE `id`='7';
UPDATE `corpo_admin_menu` SET `order`='21' WHERE `id`='9';
UPDATE `corpo_admin_menu` SET `order`='22' WHERE `id`='10';
UPDATE `corpo_admin_menu` SET `order`='23' WHERE `id`='11';
UPDATE `corpo_admin_menu` SET `order`='24' WHERE `id`='12';
UPDATE `corpo_admin_menu` SET `order`='25' WHERE `id`='13';
UPDATE `corpo_admin_menu` SET `order`='26' WHERE `id`='14';
UPDATE `corpo_admin_menu` SET `order`='27' WHERE `id`='15';
UPDATE `corpo_admin_menu` SET `order`='28' WHERE `id`='16';
UPDATE `corpo_admin_menu` SET `order`='29' WHERE `id`='17';
UPDATE `corpo_admin_menu` SET `order`='30' WHERE `id`='18';
UPDATE `corpo_admin_menu` SET `order`='31' WHERE `id`='19';
UPDATE `corpo_admin_menu` SET `order`='32' WHERE `id`='20';

INSERT INTO `corpo_admin_menu` (`id`, `name`, `order`, `url`, `parent_id`, `published`, `enable_for_mobile`, `mobile_trigger_method`, `path`, `onclick`, `cls`) VALUES
(21, 'About Us', 33, '/corporate/about-us', 8, 1, 0, NULL, ',8,21,', NULL, 'visible-xs'), 
(22, 'Contact', 34, '/corporate/contact-us', 8, 1, 0, NULL, ',8,22,', NULL, 'visible-xs'), 
(23, 'Terms of Service', 35, '/corporate/terms-and-conditions', 8, 1, 0, NULL, ',8,23,', NULL, 'visible-xs'), 
(24, 'Privacy Policy', 36, '/corporate/privacy-policy', 8, 1, 0, NULL, ',8,24,', NULL, 'visible-xs');
