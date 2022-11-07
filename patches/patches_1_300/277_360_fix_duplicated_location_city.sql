UPDATE cms_hotel_city SET city_name = 'Jaipur (State of Rājasthān)' WHERE location_id = 12038;

UPDATE cms_hotel SET iso3_country_code='IND', country_code='IN' WHERE id = 136672;

DELETE FROM cms_hotel_city WHERE location_id = 12038 AND city_id = 416530;

UPDATE cms_hotel_source SET location_id = 2441949 WHERE hotel_id = 261771 AND location_id = 1245672;

UPDATE cms_hotel_city SET location_id = 2441949 WHERE location_id = 1245672 AND city_id = 2441949;


update hotel_to_hotel_divisions set hotel_division_id = 171 where hotel_id = 90034 AND hotel_division_id = 177;

update hotel_to_hotel_divisions set hotel_division_id = 197 where hotel_id = 90034 AND hotel_division_id = 178;

update hotel_to_hotel_divisions set hotel_division_id = 198 where hotel_id = 90034 AND hotel_division_id = 179;


delete from cms_hotel_image where hotel_id = 90034 and hotel_division_id in (177,178,179);


UPDATE cms_hotel_image SET default_pic = 1 WHERE default_pic > 1;

UPDATE amadeus_hotel_image SET default_pic = 1 WHERE default_pic > 1;

INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',90034,171,2,'{"scene": {"view": {"hlookat": "1.054","vlookat" : "1.275","fov" : "138.618"}}}',null,false,false,4),
(0,'thumb.jpg',90034,197,2,'{"scene": {"view": {"hlookat": "1.713","vlookat" : "0.598","fov" : "138.779"}}}',null,True,True,5),
(0,'thumb.jpg',90034,198,2,'{"scene": {"view": {"hlookat": "1.300","vlookat" : "0.000","fov" : "138.700"}}}',null,false,false,6);


UPDATE cms_hotel_image SET is_featured = 1 WHERE is_featured > 1;
UPDATE amadeus_hotel_image SET is_featured = 1 WHERE is_featured > 1;


update hotel_to_hotel_divisions set hotel_division_id = 518 where hotel_id = 245940 AND hotel_division_id = 516;
update cms_hotel_image set hotel_division_id = 518 where hotel_id = 245940 AND hotel_division_id = 516;


UPDATE SET default_pic = 0, is_featured = 0 WHERE hotel_id = 245940 AND tt_media_type_id = 2 AND hotel_division_id not in (79, 15, 33, 80, 237, 21, 66, 375, 75, 519);


UPDATE cms_hotel SET name = "Canal Central Hotel - Business Bay" WHERE id = 417418;


UPDATE cms_hotel_image SET default_pic = 0, is_featured = 0 WHERE hotel_id = 237249 AND tt_media_type_id = 2 AND hotel_division_id not in (130, 15, 152, 29, 168, 21, 80, 162, 487, 481);

UPDATE cms_hotel_image SET default_pic = 0, is_featured = 0 WHERE hotel_id = 147848 AND tt_media_type_id = 2 AND hotel_division_id not in (130, 15, 33, 152, 29, 80, 101, 21, 85, 531, 140, 481);

UPDATE cms_hotel_image SET default_pic = 0, is_featured = 0 WHERE hotel_id = 231381 AND tt_media_type_id = 2 AND hotel_division_id not in (130, 15, 132, 95, 29, 80, 21, 487, 247, 379, 638, 627);

UPDATE cms_hotel_image SET default_pic = 0, is_featured = 0 WHERE hotel_id = 101658 AND tt_media_type_id = 2 AND hotel_division_id not in (130, 15, 33, 29, 168, 21, 80, 101, 160, 37, 126, 247);

DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND d.parent_id IS NULL
;


DELETE FROM cms_hotel_source WHERE hotel_id = 132548;

DELETE FROM cms_hotel WHERE id = 132548;



DELETE FROM cms_hotel_image WHERE hotel_id = 182761 and tt_media_type_id = 2 AND id in (5764650, 5764706);

