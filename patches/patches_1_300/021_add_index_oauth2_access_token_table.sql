#013 add index oauth2_access_token table
#client_id, token, user_id

CREATE INDEX access_token_index_1
ON `oauth2_access_token` (client_id, token, user_id);
