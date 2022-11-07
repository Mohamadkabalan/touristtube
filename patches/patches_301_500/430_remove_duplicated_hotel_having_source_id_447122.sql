# Heritage Village Resort an Spa (source_id = 447122)
UPDATE cms_hotel SET city= 'Gurgaon (State of Haryāna)', city_id = 2100635, downtown = 'Gurgaon', distance_from_downtown = '520', airport = 'Indira Gandhi International Airport (DEL)', distance_from_airport = '13510', train_station = 'Delhi - Hazrat Nizamuddin Railway Station', distance_from_train_station = '42580' WHERE id = 417421;

DELETE FROM cms_hotel_image WHERE hotel_id = 92862 AND tt_media_type_id = 1;

DELETE FROM cms_hotel_source WHERE hotel_id = 92862;

DELETE FROM cms_hotel WHERE id = 92862;