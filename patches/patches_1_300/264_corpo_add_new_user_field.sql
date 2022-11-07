ALTER TABLE `cms_users`
  ADD COLUMN allow_access_to_sub_accounts bool NULL,
  ADD COLUMN allow_access_to_sub_accounts_users bool NULL;