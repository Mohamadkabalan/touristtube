# Remove rows having location_ids not found in cms_hotel_source
DELETE FROM cms_hotel_city 
WHERE  location_id IN ( 1510, 1945, 23219, 25932, 
                        26439, 37997, 41056, 59174, 
                        80824, 88487, 108262, 112234, 
                        121054, 121484, 139121, 141578, 
                        143063, 165255, 167786, 169384, 
                        171334, 171572, 185544, 186125, 
                        186780, 187036, 193752, 194066, 
                        203462, 213917, 217885, 220349, 
                        221526, 226642, 229112, 234194, 
                        237251, 237571, 257876, 288719, 
                        291197, 325502, 354335, 356121, 
                        364813, 368390, 461016, 480982, 
                        487237, 548725, 611691, 646173, 
                        675104, 683753, 750799, 761011, 
                        786658, 795751, 800439, 801144, 
                        801724, 805379, 816253, 826689, 
                        830689, 840135, 921623, 948518, 
                        1124867, 1127474, 1133346, 1185961, 
                        1190829, 3057245, 3083500, 3088414, 3088601 );

# Start fixing major cities that have a city_id linked to more than 1 location_id
UPDATE cms_hotel_city SET city_name = 'Paris' WHERE location_id = 49551 AND city_id = 1818316;
UPDATE cms_hotel_source SET location_id = 49551 WHERE hotel_id = 101800;
UPDATE cms_hotel_source SET location_id = 49551 WHERE hotel_id = 126409;
DELETE FROM cms_hotel_city WHERE location_id = 223544 AND city_id = 1818316;
DELETE FROM cms_hotel_city WHERE location_id = 416596 AND city_id = 1818316;

UPDATE cms_hotel_city SET city_name = 'London' WHERE location_id = 71302 AND city_id = 1829266;

UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 175700;
UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 175901;
UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 179827;
UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 179841;
UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 179853;
UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 181903;
UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 185620;
UPDATE cms_hotel_source SET location_id = 6879 WHERE hotel_id = 189435;
UPDATE cms_hotel_source SET location_id = 234235 WHERE hotel_id = 86265;
DELETE FROM cms_hotel_city WHERE location_id = 58133 AND city_id = 1060078;

UPDATE cms_hotel_city SET city_name = 'Berlin' WHERE location_id = 55133 AND city_id = 1668543;

UPDATE cms_hotel_city SET city_name = 'Abu Dhabi' WHERE location_id = 113 AND city_id = 1060174;
UPDATE cms_hotel_source SET location_id = 113 WHERE hotel_id = 181520;
UPDATE cms_hotel_source SET location_id = 113 WHERE hotel_id = 193739;
UPDATE cms_hotel_source SET location_id = 113 WHERE hotel_id = 352809;
UPDATE cms_hotel_source SET location_id = 113 WHERE hotel_id = 356012;
UPDATE cms_hotel_source SET location_id = 113 WHERE hotel_id = 358677;
DELETE FROM cms_hotel_city WHERE location_id = 516128 AND city_id = 1060174;
DELETE FROM cms_hotel_city WHERE location_id = 516179 AND city_id = 1060174;
DELETE FROM cms_hotel_city WHERE location_id = 3224153 AND city_id = 1060174;
DELETE FROM cms_hotel_city WHERE location_id = 3324444 AND city_id = 1060174;

UPDATE cms_hotel_city SET city_name = 'Rome' WHERE location_id = 54084 AND city_id = 2211494;

UPDATE cms_hotel_city SET city_id = 1115785 WHERE location_id = 45883;
UPDATE cms_hotel_search_details SET entity_id = 1115785 WHERE entity_id = 1115784 AND entity_type = 71;

UPDATE cms_hotel_city SET city_name = 'Budapest' WHERE location_id = 45531 AND city_id = 1909113;

UPDATE cms_hotel_city SET city_name = 'Istanbul-Fatih' WHERE location_id = 1382868 AND city_id = 774729;
UPDATE cms_hotel_city SET city_id = 785809 WHERE location_id = 1382868;

UPDATE cms_hotel_city SET city_name = 'Oslo' WHERE location_id = 46877 AND city_id = 135571;
UPDATE cms_hotel_city SET city_name = 'Stockholm' WHERE location_id = 53756 AND city_id = 608644;
UPDATE cms_hotel_city SET city_name = 'Beirut' WHERE location_id = 2211 AND city_id = 2330805;

UPDATE cms_hotel_city SET city_id = 38467 WHERE location_id = 22517;
UPDATE cms_hotel_city SET city_id = 26734 WHERE location_id = 260934;
UPDATE cms_hotel_city SET city_id = 2448135 WHERE location_id = 281266;