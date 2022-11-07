CREATE TABLE `corpo_user_profile_menus_permission` (
  `id` int(11) NOT NULL,
  `user_profile_id` int(11) NOT NULL,
  `corpo_menu_id` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `corpo_user_profile_menus_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_corpo_menu_id` (`corpo_menu_id`),
  ADD KEY `fk_updated_by` (`updated_by`),
  ADD KEY `fk_user_profile_id` (`user_profile_id`);

  ALTER TABLE `corpo_user_profile_menus_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `corpo_user_profile_menus_permission`
  ADD CONSTRAINT `fk_corpo_menu_id` FOREIGN KEY (`corpo_menu_id`) REFERENCES `corpo_admin_menu` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `cms_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_profile_id` FOREIGN KEY (`user_profile_id`) REFERENCES `corpo_user_profiles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;