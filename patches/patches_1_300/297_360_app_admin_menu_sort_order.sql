
ALTER TABLE `backend_admin_menu` 
CHANGE COLUMN `sort_order` `sort_order` VARCHAR(20) NULL DEFAULT '999' ,
ADD INDEX `bam_sort_order_index` (`sort_order` ASC);

