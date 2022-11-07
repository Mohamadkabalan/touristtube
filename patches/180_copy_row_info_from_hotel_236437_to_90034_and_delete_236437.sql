# Update hotel #90034 from #236437
UPDATE amadeus_hotel
SET dupe_pool_id = 700141493,
property_name = 'PARK INN JAIPUR JAI SINGH HWY',
address_line_1 = 'A 28 C 3 SAWAI JAI SINGH HIGHW',
address_line_2 = 'BANI PARK RAJASTHAN',
latitude = '26.922470',
longitude = '75.793980',
phone = '4151000',
fax = '4151000',
stars = '4',
self_rating = 'S',
location = 'N',
transportation = 'T',
popularity = '1',
published = '1',
old_id = NULL
WHERE id = 90034;

UPDATE amadeus_hotel_source SET hotel_id = 90034 WHERE hotel_id = 236437;
UPDATE amadeus_hotel_image SET hotel_id = 90034 WHERE hotel_id = 236437;

# Remove the old #236437 record
DELETE FROM amadeus_hotel WHERE id = 236437;