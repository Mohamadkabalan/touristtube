
# Add the new columns
ALTER TABLE amadeus_hotel ADD COLUMN has_360 TINYINT(1) NOT NULL DEFAULT '0' AFTER description;

# Update the values correcly
UPDATE amadeus_hotel h
SET h.has_360 = 1
WHERE h.id IN(
   SELECT DISTINCT hi.hotel_id
   FROM amadeus_hotel_image hi
   WHERE hi.tt_media_type_id = 2
);
