
UPDATE cms_hotel SET train_station = TRIM(REPLACE(train_station, '?', '')) WHERE train_station REGEXP '\\?+';

UPDATE cms_hotel SET airport = TRIM(REPLACE(airport, '?', '')) WHERE airport REGEXP '\\?+';

UPDATE cms_hotel SET downtown = TRIM(REPLACE(downtown, '?', '')) WHERE downtown REGEXP '\\?+';


UPDATE cms_hotel SET train_station = NULL, distance_from_train_station = NULL WHERE train_station IS NOT NULL AND LENGTH(TRIM(train_station)) = 0;

UPDATE cms_hotel SET airport = NULL, distance_from_airport = NULL WHERE airport IS NOT NULL AND LENGTH(TRIM(airport)) = 0;

UPDATE cms_hotel SET downtown = NULL, distance_from_downtown = NULL WHERE downtown IS NOT NULL AND LENGTH(TRIM(downtown)) = 0;
