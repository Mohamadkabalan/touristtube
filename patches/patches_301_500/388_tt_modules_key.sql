
ALTER TABLE tt_modules ADD COLUMN module_key VARCHAR(20) DEFAULT NULL;

UPDATE tt_modules SET module_key = 'TT_MODULE_FLIGHTS' WHERE name = 'Flights';

UPDATE tt_modules SET module_key = 'TT_MODULE_HOTELS' WHERE name = 'Hotels';

UPDATE tt_modules SET module_key = 'TT_MODULE_DEALS' WHERE name = 'Deals';

UPDATE tt_modules SET module_key = 'TT_MODULE_ACCOUNTING' WHERE name = 'Accounting';

UPDATE tt_modules SET module_key = 'TT_MODULE_360_APP' WHERE name = '360Application';

ALTER TABLE tt_modules MODIFY COLUMN module_key VARCHAR(20) NOT NULL;
