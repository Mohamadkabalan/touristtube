ALTER TABLE `cms_thingstodo_details` ADD `slug` VARCHAR(255) NULL AFTER `title`;
ALTER TABLE `cms_thingstodo_details` ADD UNIQUE(`slug`);
ALTER TABLE `cms_thingstodo_details` ADD INDEX(`slug`);