
INSERT INTO hotel_divisions_categories 
(name, hotel_division_category_group_id) 
SELECT 'Terrace' AS name, hdcg.id AS hotel_division_category_group_id 
FROM hotel_divisions_categories_group hdcg 
WHERE hdcg.name = 'AMENITIES';

SET @hdc_id = LAST_INSERT_ID();


INSERT INTO hotel_divisions
(name, hotel_division_category_id) 
SELECT 'Terrace' AS name, @hdc_id AS hotel_division_category_id;


SET @hd_id = LAST_INSERT_ID();


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT CONCAT(hd.name, ' 1.', dlist.cnt) AS name, @hdc_id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions hd 
INNER JOIN (SELECT 1 AS cnt 
UNION SELECT 2 
UNION SELECT 3 
UNION SELECT 4 
UNION SELECT 5) dlist 
WHERE hd.id = @hd_id 
ORDER BY dlist.cnt ASC;


SELECT id, name, parent_id, hotel_division_category_id FROM hotel_divisions WHERE (id = @hd_id OR parent_id = @hd_id);

# Check the IDs
# SELECT hd.id, hd.name, hd.parent_id, p_hd.name AS parent_name, hd.hotel_division_category_id FROM hotel_divisions hd INNER JOIN hotel_divisions p_hd ON (p_hd.id = hd.parent_id AND p_hd.name = 'Terrace') ORDER BY hd.id;

