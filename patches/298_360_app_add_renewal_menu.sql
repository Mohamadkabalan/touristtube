INSERT INTO `backend_admin_menu` (`id`, `name`, `action`, `cls`, `parent_id`, `depth`, `sort_order`) VALUES (NULL, 'Accounts Renewal', 'admin/accounts/renewals/history', 'fa fa-circle-o', '13', '13,18', '700100');

INSERT INTO `backend_admin_menu` (`id`, `name`, `action`, `cls`, `parent_id`, `depth`, `sort_order`) VALUES ('19', 'Accounts Renewal', 'admin/accounts/renewals/history', 'fa fa-circle-o', '13', '13,18', '700200');
UPDATE `backend_admin_menu` SET `name`='Accounts List', `action`='admin/accounts', `parent_id`='13', `depth`='13,18' WHERE `id`='18';
UPDATE `backend_admin_menu` SET `action`='', `depth`='' WHERE `id`='13';

UPDATE `backend_admin_menu` SET `action` = 'admin/application/settings/default' WHERE id = 17;
