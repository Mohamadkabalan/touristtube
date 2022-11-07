INSERT INTO `cms_hotel_city` (`id`, `city_name`, `location_id`, `popularity`, `city_id`, `source`) VALUES (NULL, 'Nagpur (Mahārāṣtra)', '31229', '1', NULL, 'hrs');
INSERT INTO `cms_hotel_city` (`id`, `city_name`, `location_id`, `popularity`, `city_id`, `source`) VALUES (NULL, 'Otrokovice (Zlínský)', '79845', '1', NULL, 'hrs');
INSERT INTO `cms_hotel_city` (`id`, `city_name`, `location_id`, `popularity`, `city_id`, `source`) VALUES (NULL, 'Thondorf, Gössendorf (Styria)', '290374', '1', NULL, 'hrs');
INSERT INTO `cms_hotel_city` (`id`, `city_name`, `location_id`, `popularity`, `city_id`, `source`) VALUES (NULL, 'Möderbrugg, Sankt Oswald-Möderbrugg (Styria)', '345449', '1', NULL, 'hrs');
INSERT INTO `cms_hotel_city` (`id`, `city_name`, `location_id`, `popularity`, `city_id`, `source`) VALUES (NULL, 'Niklasdorf (Styria)', '208544', '1', NULL, 'hrs');

UPDATE cms_hotel_city SET city_name = 'Gmina Serock (Masovian Voivodeship)' WHERE location_id = 308001;
UPDATE cms_hotel_city SET city_name = 'Wicklow (Wicklow)' WHERE location_id = 28634;

UPDATE cms_hotel_source SET location_id = 37473 WHERE hotel_id = 205332;
UPDATE cms_hotel_source SET location_id = 6984 WHERE hotel_id = 205233;
UPDATE cms_hotel_source SET location_id = 275989 WHERE hotel_id = 204968;
UPDATE cms_hotel_source SET location_id = 3160297 WHERE hotel_id = 158816;
UPDATE cms_hotel_source SET location_id = 3160297 WHERE hotel_id = 194328;
UPDATE cms_hotel_source SET location_id = 133837 WHERE hotel_id = 152404;
UPDATE cms_hotel_source SET location_id = 151499 WHERE hotel_id = 205252;
UPDATE cms_hotel_source SET location_id = 1188017 WHERE hotel_id = 203352;
UPDATE cms_hotel_source SET location_id = 208294 WHERE hotel_id = 173696;
UPDATE cms_hotel_source SET location_id = 334412 WHERE hotel_id = 153626;
UPDATE cms_hotel_source SET location_id = 308001 WHERE hotel_id = 97632;
UPDATE cms_hotel_source SET location_id = 28634 WHERE hotel_id = 35357;
UPDATE cms_hotel_source SET location_id = 165579 WHERE hotel_id = 16480;
UPDATE cms_hotel_source SET location_id = 598337 WHERE hotel_id = 23225;
UPDATE cms_hotel_source SET location_id = 207972 WHERE hotel_id = 109393;
UPDATE cms_hotel_source SET location_id = 139387 WHERE hotel_id = 145193;