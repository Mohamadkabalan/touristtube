
# applied online only to fix duplicates

DELETE FROM cms_hotel_image WHERE hotel_id = 417403;
DELETE FROM cms_hotel_image WHERE hotel_id = 417404;
DELETE FROM cms_hotel_image WHERE hotel_id = 417405;
DELETE FROM cms_hotel_image WHERE hotel_id = 417406;
DELETE FROM cms_hotel_image WHERE hotel_id = 417407;
DELETE FROM cms_hotel_image WHERE hotel_id = 417408;
DELETE FROM cms_hotel_image WHERE hotel_id = 417409;
DELETE FROM cms_hotel_image WHERE hotel_id = 417410;
DELETE FROM cms_hotel_image WHERE hotel_id = 417411;
DELETE FROM cms_hotel_image WHERE hotel_id = 417412;
DELETE FROM cms_hotel_image WHERE hotel_id = 417413;
DELETE FROM cms_hotel_image WHERE hotel_id = 417414;
DELETE FROM cms_hotel_image WHERE hotel_id = 417415;
DELETE FROM cms_hotel_image WHERE hotel_id = 417416;
DELETE FROM cms_hotel_image WHERE hotel_id = 417417;


SET FOREIGN_KEY_CHECKS = 0;

UPDATE cms_hotel_source SET location_id = 590555 WHERE hotel_id = 204514 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417402 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204514;
UPDATE cms_hotel_source SET location_id = 133093 WHERE hotel_id = 204686 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417403 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204686;
UPDATE cms_hotel_source SET location_id = 824257 WHERE hotel_id = 204687 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417404 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204687;
UPDATE cms_hotel_source SET location_id = 29123 WHERE hotel_id = 204718 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417405 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204718; 
UPDATE cms_hotel_source SET location_id = 128169 WHERE hotel_id = 204880 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417406 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204880;
UPDATE cms_hotel_source SET location_id = 51625 WHERE hotel_id = 204891 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417407 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204891; 
UPDATE cms_hotel_source SET location_id = 108914 WHERE hotel_id = 204965 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417408 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204965;
UPDATE cms_hotel_source SET location_id = 782121 WHERE hotel_id = 204966 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417409 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 204966;
UPDATE cms_hotel_source SET location_id = 29311 WHERE hotel_id = 205002 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417410 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205002; 
UPDATE cms_hotel_source SET location_id = 70161 WHERE hotel_id = 205063 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417411 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205063; 
UPDATE cms_hotel_source SET location_id = 159634 WHERE hotel_id = 205088 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417412 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205088;
UPDATE cms_hotel_source SET location_id = 823254 WHERE hotel_id = 205211 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417413 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205211;
UPDATE cms_hotel_source SET location_id = 20813 WHERE hotel_id = 205329 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417414 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205329; 
UPDATE cms_hotel_source SET location_id = 1812 WHERE hotel_id = 205331 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417415 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205331;  
UPDATE cms_hotel_source SET location_id = 795837 WHERE hotel_id = 205334 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417416 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205334;
UPDATE cms_hotel_source SET location_id = 23808 WHERE hotel_id = 205335 AND source = 'hrs';
DELETE FROM cms_hotel_source WHERE hotel_id = 417417 AND source = 'hrs';
DELETE FROM cms_hotel WHERE id = 205335; 



UPDATE cms_hotel SET id = 204514 WHERE id = 417402;
UPDATE cms_hotel SET id = 204686 WHERE id = 417403;
UPDATE cms_hotel SET id = 204687 WHERE id = 417404;
UPDATE cms_hotel SET id = 204718 WHERE id = 417405;
UPDATE cms_hotel SET id = 204880 WHERE id = 417406;
UPDATE cms_hotel SET id = 204891 WHERE id = 417407;
UPDATE cms_hotel SET id = 204965 WHERE id = 417408;
UPDATE cms_hotel SET id = 204966 WHERE id = 417409;
UPDATE cms_hotel SET id = 205002 WHERE id = 417410;
UPDATE cms_hotel SET id = 205063 WHERE id = 417411;
UPDATE cms_hotel SET id = 205088 WHERE id = 417412;
UPDATE cms_hotel SET id = 205211 WHERE id = 417413;
UPDATE cms_hotel SET id = 205329 WHERE id = 417414;
UPDATE cms_hotel SET id = 205331 WHERE id = 417415;
UPDATE cms_hotel SET id = 205334 WHERE id = 417416;
UPDATE cms_hotel SET id = 205335 WHERE id = 417417;

SET FOREIGN_KEY_CHECKS = 1;

