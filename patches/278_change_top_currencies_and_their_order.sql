UPDATE currency_rate SET top_currency = 0 WHERE currency_code = 'NZD';
UPDATE currency_rate SET top_currency = 0 WHERE currency_code = 'QAR';
UPDATE currency_rate SET top_currency = 0 WHERE currency_code = 'ZAR';

UPDATE currency_rate SET top_currency = 1, display_order = 1 WHERE currency_code = 'USD';
UPDATE currency_rate SET top_currency = 1, display_order = 2 WHERE currency_code = 'EUR';
UPDATE currency_rate SET top_currency = 1, display_order = 3 WHERE currency_code = 'GBP';
UPDATE currency_rate SET top_currency = 1, display_order = 4 WHERE currency_code = 'AED';
UPDATE currency_rate SET top_currency = 1, display_order = 5 WHERE currency_code = 'CNY';
UPDATE currency_rate SET top_currency = 1, display_order = 6 WHERE currency_code = 'CHF';
UPDATE currency_rate SET top_currency = 1, display_order = 7 WHERE currency_code = 'KWD';
UPDATE currency_rate SET top_currency = 1, display_order = 8 WHERE currency_code = 'AUD';
UPDATE currency_rate SET top_currency = 1, display_order = 9 WHERE currency_code = 'JPY';
UPDATE currency_rate SET top_currency = 1, display_order = 10 WHERE currency_code = 'CAD';
UPDATE currency_rate SET top_currency = 1, display_order = 11 WHERE currency_code = 'INR';
UPDATE currency_rate SET top_currency = 1, display_order = 12 WHERE currency_code = 'SGD';

UPDATE currency_rate SET symbol = 'LBP' WHERE currency_code = 'LBP';
UPDATE currency_rate SET symbol = 'EGP' WHERE currency_code = 'EGP';