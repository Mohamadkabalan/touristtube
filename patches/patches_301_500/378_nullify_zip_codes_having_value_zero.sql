UPDATE cms_hotel SET zip_code = NULL WHERE `zip_code` IN ('0','00','000','0000','00000','000000','0000000','00000000');

UPDATE amadeus_hotel SET zip_code = NULL WHERE `zip_code` IN ('0','00','000','0000','00000','000000','0000000','00000000');