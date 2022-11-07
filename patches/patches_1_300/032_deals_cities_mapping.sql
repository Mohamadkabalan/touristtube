#Index added on city name and country code used in queries filtering 
#
ALTER TABLE `deal_city` 
ADD INDEX `idx_deal_city_name` (`city_name` ASC),
ADD INDEX `idx_deal_ctry_code` (`country_code` ASC);



#DEALS CITIES MAPPING WITH TT CITIES TABLE
#

#Mapped with exact match with city name, country code and state code
#
UPDATE `deal_city` SET `city_id`='2482013' WHERE `id`='1709';
UPDATE `deal_city` SET `city_id`='2482011' WHERE `id`='1879';
UPDATE `deal_city` SET `city_id`='2482012' WHERE `id`='2225';

#Mapped with exact match with city name, state code (ca = la)
#
UPDATE `deal_city` SET `city_id`='857036' WHERE `id`='46';


UPDATE `deal_city` SET `city_id`='1295211' WHERE `id`='101';
UPDATE `deal_city` SET `city_id`='1288837' WHERE `id`='102';
UPDATE `deal_city` SET `city_id`='1292386' WHERE `id`='103';
UPDATE `deal_city` SET `city_id`='1289987' WHERE `id`='105';
UPDATE `deal_city` SET `city_id`='1290703' WHERE `id`='107';
UPDATE `deal_city` SET `city_id`='1291072' WHERE `id`='108';

UPDATE `deal_city` SET `city_id`='1291126' WHERE `id`='109';
UPDATE `deal_city` SET `city_id`='1291171' WHERE `id`='111';
UPDATE `deal_city` SET `city_id`='1288063' WHERE `id`='113';
UPDATE `deal_city` SET `city_id`='1292743' WHERE `id`='114';
UPDATE `deal_city` SET `city_id`='1293112' WHERE `id`='115';







