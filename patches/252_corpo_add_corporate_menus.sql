INSERT INTO `corpo_admin_menu` (`id`, `name`, `order`, `url`, `type`, `parent_id`, `published`, `enable_for_mobile`, `mobile_trigger_method`, `path`) VALUES
(24, 'Flights', 15, '/corporate/flight', 4, NULL, 1, 0, NULL, NULL),
(25, 'Hotels', 16, '/corporate/hotels/search', 4, NULL, 1, 0, NULL, NULL),
(26, 'Username', 17, NULL, 4, NULL, 1, 0, NULL, NULL),
(27, 'List of Sales Persons', 18, '/corporate/definitions/users-sales', 4, 26, 1, 0, NULL, NULL),
(28, 'List of Users', 19, '/corporate/definitions/users', 4, 26, 1, 0, NULL, NULL),
(29, 'Account', 20, '/corporate/admin/account', 4, 26, 1, 0, NULL, NULL),
(30, 'Account Type', 21, '/corporate/admin/accountType', 4, 26, 1, 0, NULL, NULL),
(31, 'Affiliates', 22, '/corporate/admin/account-affiliate', 4, 26, 1, 0, NULL, NULL),
(32, 'Companies ', 23, '/corporate/admin/account-company', 4, 26, 1, 0, NULL, NULL),
(33, 'Agencies ', 24, '/corporate/admin/account-agency', 4, 26, 1, 0, NULL, NULL),
(34, 'Statement of Account', 25, '/corporate/statement-of-account', 4, 26, 1, 0, NULL, NULL),
(35, 'Payments', 26, '/corporate/admin/payment', 4, 26, 1, 0, NULL, NULL),
(36, 'Manage Bookings', 27, '/corporate/corporate-manage-bookings_new', 4, 26, 1, 0, NULL, NULL),
(37, 'All Bookings', 28, '/corporate/reports-allbooking', 4, 26, 1, 0, NULL, NULL),
(38, 'Reports', 29, '/corporate/reports-allbookings', 4, 26, 1, 0, NULL, NULL),
(39, 'Profile Settings', 30, '/corporate/profile-settings', 4, 26, 1, 0, NULL, NULL),
(40, 'Change Password', 31, '/corporate/password/change', 4, 26, 1, 0, NULL, NULL),
(41, 'Logout', 32, '/corporate/logout', 4, 26, 1, 0, NULL, NULL);

INSERT INTO `corpo_admin_menu` (`id`, `name`, `order`, `url`, `type`, `parent_id`, `published`, `enable_for_mobile`, `mobile_trigger_method`, `path`) VALUES
(42, 'User Profiles Menus Permissions', 31, '/corporate/admin/user-profiles-permissions', 4, 26, 1, 0, NULL, NULL);
