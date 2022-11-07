# cms_hotel table is only used by HRS
DELETE FROM cms_hotel WHERE src = 'gds';
DELETE FROM cms_hotel WHERE src = 'hotelbeds';

# cms_hotel_source table is only used by HRS
DELETE FROM cms_hotel_source WHERE source = 'amadeus';