
INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, hdc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions_categories_group hdcg  
INNER JOIN hotel_divisions_categories hdc ON (hdc.name = 'Restaurant' AND hdc.hotel_division_category_group_id = hdcg.id) 
INNER JOIN hotel_divisions hd ON (hd.hotel_division_category_id = hdc.id AND hd.parent_id IS NULL AND hd.name = 'Restaurant 1') 
INNER JOIN (SELECT 11 AS cnt 
UNION SELECT 12  
UNION SELECT 13  
UNION SELECT 14 
UNION SELECT 15
UNION SELECT 16 
UNION SELECT 17 
UNION SELECT 18 
UNION SELECT 19 
UNION SELECT 20) dlist 
WHERE hdcg.name = 'DINING' 
ORDER BY dlist.cnt ASC;



INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT 'Walkways 3', hdc.id AS hotel_division_category_id, NULL AS parent_id 
FROM hotel_divisions_categories_group hdcg  
INNER JOIN hotel_divisions_categories hdc ON (hdc.name = 'Walkways' AND hdc.hotel_division_category_group_id = hdcg.id)  
WHERE hdcg.name = 'AMENITIES';


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, hdc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions_categories_group hdcg  
INNER JOIN hotel_divisions_categories hdc ON (hdc.name = 'Walkways' AND hdc.hotel_division_category_group_id = hdcg.id) 
INNER JOIN hotel_divisions hd ON (hd.hotel_division_category_id = hdc.id AND hd.parent_id IS NULL AND hd.name = 'Walkways 3') 
INNER JOIN (SELECT 1 AS cnt 
UNION SELECT 2  
UNION SELECT 3  
UNION SELECT 4 
UNION SELECT 5
UNION SELECT 6 
UNION SELECT 7 
UNION SELECT 8 
UNION SELECT 9 
UNION SELECT 10) dlist 
WHERE hdcg.name = 'AMENITIES' 
ORDER BY dlist.cnt ASC;


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT 'Walkways 4', hdc.id AS hotel_division_category_id, NULL AS parent_id 
FROM hotel_divisions_categories_group hdcg  
INNER JOIN hotel_divisions_categories hdc ON (hdc.name = 'Walkways' AND hdc.hotel_division_category_group_id = hdcg.id)  
WHERE hdcg.name = 'AMENITIES';



INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, hdc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions_categories_group hdcg  
INNER JOIN hotel_divisions_categories hdc ON (hdc.name = 'Walkways' AND hdc.hotel_division_category_group_id = hdcg.id) 
INNER JOIN hotel_divisions hd ON (hd.hotel_division_category_id = hdc.id AND hd.parent_id IS NULL AND hd.name = 'Walkways 4') 
INNER JOIN (SELECT 1 AS cnt 
UNION SELECT 2  
UNION SELECT 3  
UNION SELECT 4 
UNION SELECT 5
UNION SELECT 6 
UNION SELECT 7 
UNION SELECT 8 
UNION SELECT 9 
UNION SELECT 10) dlist 
WHERE hdcg.name = 'AMENITIES' 
ORDER BY dlist.cnt ASC;


# Check the IDs
# SELECT hd.id, hd.name, hd.parent_id, p_hd.name AS parent_name, hd.hotel_division_category_id FROM hotel_divisions hd INNER JOIN hotel_divisions p_hd ON (p_hd.id = hd.parent_id AND p_hd.name = 'Walkways 4') ORDER BY hd.id;

