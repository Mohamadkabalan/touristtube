INSERT INTO `backend_admin_menu` (`id`, `name`, `action`, `cls`, `parent_id`, `depth`) VALUES 
    (7, 'Hotels', 'hotels', 'fa fa-hotel', '0', '7');

UPDATE `backend_admin_menu` SET `name`="Hotels List", `parent_id`=7, `depth`='7,10' WHERE `id`=10;
UPDATE `backend_admin_menu` SET `parent_id`=7, `depth`='7,11' WHERE `id`=11;
UPDATE `backend_admin_menu` SET `parent_id`=7, `depth`='7,8' WHERE `id`=8;
UPDATE `backend_admin_menu` SET `name`="Hotels Allowed Divisions", `parent_id`=7, `depth`='7,12' WHERE `id`=12;
UPDATE `backend_admin_menu` SET `parent_id`=7, `depth`='7,15' WHERE `id`=15;
UPDATE `backend_admin_menu` SET `parent_id`=7, `depth`='7,9' WHERE `id`=9;


UPDATE `backend_admin_menu` SET `depth`='13' WHERE `id`='13';
UPDATE `backend_admin_menu` SET `depth`='14' WHERE `id`='14';
UPDATE `backend_admin_menu` SET `depth`='16' WHERE `id`='16';

