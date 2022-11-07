UPDATE `backend_admin_menu` SET `action`='' WHERE `id`='14';
INSERT INTO `backend_admin_menu` (`name`, `action`, `cls`, `parent_id`, `depth`, `sort_order`) VALUES ('Users List', 'admin/users', 'fa fa-users', '14', '14,24', '800001');
INSERT INTO `backend_admin_menu` (`name`, `action`, `cls`, `parent_id`, `depth`, `sort_order`) VALUES ('Users Group', 'admin/users/groups', 'fa fa-users', '14', '14,25', '800002');
