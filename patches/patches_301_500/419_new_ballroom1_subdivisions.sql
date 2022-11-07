
SET @category_group = 'AMENITIES';
SET @category_name = 'Ballroom';
SET @div_name = 'Ballroom 1';

INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, hd.hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions hd 
INNER JOIN hotel_divisions_categories dc ON (dc.name = @category_name AND dc.id = hd.hotel_division_category_id) 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
INNER JOIN (SELECT 6 AS cnt 
UNION SELECT 7 
UNION SELECT 8 
UNION SELECT 9
UNION SELECT 10) dlist 
WHERE hd.name = @div_name 
ORDER BY dlist.cnt ASC;


SELECT dcg.id AS dcg_id, dcg.name AS dcg_name, d.hotel_division_category_id AS cat_id, dc.name AS cat_name, 
p_d.id AS parent_div_id, d.id AS div_id, d.name AS div_name 
FROM hotel_divisions p_d 
LEFT JOIN hotel_divisions d ON (d.parent_id = p_d.id) 
INNER JOIN hotel_divisions_categories dc ON (dc.id = p_d.hotel_division_category_id) 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.id = dc.hotel_division_category_group_id) 
WHERE p_d.name = @div_name AND p_d.parent_id IS NULL 
ORDER BY d.id;

