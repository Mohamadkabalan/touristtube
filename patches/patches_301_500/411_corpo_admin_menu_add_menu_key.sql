
ALTER TABLE corpo_admin_menu ADD COLUMN menu_key VARCHAR(50) AFTER name;

UPDATE corpo_admin_menu SET menu_key = CONCAT('KEY_', UPPER(REPLACE(name, ' ', '_')));

ALTER TABLE corpo_admin_menu CHANGE menu_key menu_key VARCHAR(50) NOT NULL;
