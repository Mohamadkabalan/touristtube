
INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT CONCAT(hd.name, ' 1.', dlist.cnt) AS name, hdc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions_categories_group hdcg  
INNER JOIN hotel_divisions_categories hdc ON (hdc.name = 'Garden' AND hdc.hotel_division_category_group_id = hdcg.id) 
INNER JOIN hotel_divisions hd ON (hd.hotel_division_category_id = hdc.id AND hd.parent_id IS NULL AND hd.name = 'Garden') 
INNER JOIN (SELECT 5 AS cnt 
UNION SELECT 6) dlist 
WHERE hdcg.name = 'AMENITIES' 
ORDER BY dlist.cnt ASC;




# Check the IDs
# SELECT hd.id, hd.name, hd.parent_id, p_hd.name AS parent_name, hd.hotel_division_category_id FROM hotel_divisions hd INNER JOIN hotel_divisions p_hd ON (p_hd.id = hd.parent_id AND p_hd.name = 'Garden') ORDER BY hd.id;
