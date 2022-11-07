# Add the new columns
ALTER TABLE `cms_hotel` ADD `has_360` TINYINT(1) NOT NULL DEFAULT '0' AFTER `distance_from_train_station`;
ALTER TABLE hotel_search_response ADD COLUMN has_360 TINYINT(1) NOT NULL DEFAULT '0' AFTER breakfast;

# Update the values correcly
UPDATE cms_hotel h
SET h.has_360 = 1
WHERE h.id IN(
   SELECT DISTINCT hi.hotel_id
   FROM cms_hotel_image hi
   WHERE hi.tt_media_type_id = 2
);