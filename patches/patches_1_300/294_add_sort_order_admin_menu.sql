
ALTER TABLE `backend_admin_menu` 
ADD COLUMN `sort_order` INT NULL DEFAULT 999 AFTER `depth`;


UPDATE `backend_admin_menu` SET `sort_order`='100' WHERE `id`='1';
UPDATE `backend_admin_menu` SET `sort_order`='200' WHERE `id`='2';
UPDATE `backend_admin_menu` SET `sort_order`='200100' WHERE `id`='5';
UPDATE `backend_admin_menu` SET `sort_order`='200100100' WHERE `id`='6';
UPDATE `backend_admin_menu` SET `sort_order`='300' WHERE `id`='7';
UPDATE `backend_admin_menu` SET `sort_order`='300400' WHERE `id`='8';
UPDATE `backend_admin_menu` SET `sort_order`='300100' WHERE `id`='10';
UPDATE `backend_admin_menu` SET `sort_order`='300200' WHERE `id`='12';
UPDATE `backend_admin_menu` SET `sort_order`='300300' WHERE `id`='11';
UPDATE `backend_admin_menu` SET `sort_order`='300500' WHERE `id`='15';
UPDATE `backend_admin_menu` SET `sort_order`='300500' WHERE `id`='9';
UPDATE `backend_admin_menu` SET `sort_order`='700' WHERE `id`='13';
UPDATE `backend_admin_menu` SET `sort_order`='800' WHERE `id`='14';
UPDATE `backend_admin_menu` SET `sort_order`='900' WHERE `id`='16';
