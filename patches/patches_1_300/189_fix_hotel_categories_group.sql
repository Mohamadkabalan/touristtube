#Move some categories from under Activities to Amenities 
UPDATE hotel_divisions_categories dc SET dc.hotel_division_category_group_id = 4
WHERE dc.hotel_division_category_group_id = 5
AND id in (13,19,24,25,27,29);

INSERT INTO `hotel_divisions_categories` (`id`, `name`, `hotel_division_category_group_id`) VALUES (32, 'Activities', '5');
