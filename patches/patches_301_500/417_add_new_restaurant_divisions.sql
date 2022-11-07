
SET @category_group = 'DINING';

SET @category_name = 'Restaurant';

SET @div_name = 'Restaurant 6';


INSERT INTO hotel_divisions 
(name, hotel_division_category_id) 
SELECT @div_name AS name, dc.id AS hotel_division_category_id 
FROM hotel_divisions_categories dc 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
WHERE dc.name = @category_name;



SET @hd_id = LAST_INSERT_ID();


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 
SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, dc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions hd 
INNER JOIN hotel_divisions_categories dc ON (dc.name = @category_name) 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
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
WHERE hd.id = @hd_id 
ORDER BY dlist.cnt ASC;




SET @div_name = 'Restaurant 7';


INSERT INTO hotel_divisions 
(name, hotel_division_category_id) 
SELECT @div_name AS name, dc.id AS hotel_division_category_id 
FROM hotel_divisions_categories dc 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
WHERE dc.name = @category_name;



SET @hd_id = LAST_INSERT_ID();


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 

SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, dc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions hd 
INNER JOIN hotel_divisions_categories dc ON (dc.name = @category_name) 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
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
WHERE hd.id = @hd_id 
ORDER BY dlist.cnt ASC;




SET @div_name = 'Restaurant 8';


INSERT INTO hotel_divisions 
(name, hotel_division_category_id) 
SELECT @div_name AS name, dc.id AS hotel_division_category_id 
FROM hotel_divisions_categories dc 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
WHERE dc.name = @category_name;



SET @hd_id = LAST_INSERT_ID();


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 

SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, dc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions hd 
INNER JOIN hotel_divisions_categories dc ON (dc.name = @category_name) 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
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
WHERE hd.id = @hd_id 
ORDER BY dlist.cnt ASC;




SET @div_name = 'Restaurant 9';


INSERT INTO hotel_divisions 
(name, hotel_division_category_id) 
SELECT @div_name AS name, dc.id AS hotel_division_category_id 
FROM hotel_divisions_categories dc 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
WHERE dc.name = @category_name;



SET @hd_id = LAST_INSERT_ID();


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 

SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, dc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions hd 
INNER JOIN hotel_divisions_categories dc ON (dc.name = @category_name) 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
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
WHERE hd.id = @hd_id 
ORDER BY dlist.cnt ASC;




SET @div_name = 'Restaurant 10';


INSERT INTO hotel_divisions 
(name, hotel_division_category_id) 
SELECT @div_name AS name, dc.id AS hotel_division_category_id 
FROM hotel_divisions_categories dc 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
WHERE dc.name = @category_name;



SET @hd_id = LAST_INSERT_ID();


INSERT INTO hotel_divisions 
(name, hotel_division_category_id, parent_id) 

SELECT CONCAT(hd.name, '.', dlist.cnt) AS name, dc.id AS hotel_division_category_id, hd.id AS parent_id 
FROM hotel_divisions hd 
INNER JOIN hotel_divisions_categories dc ON (dc.name = @category_name) 
INNER JOIN hotel_divisions_categories_group dcg ON (dcg.name = @category_group AND dcg.id = dc.hotel_division_category_group_id) 
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
WHERE hd.id = @hd_id 
ORDER BY dlist.cnt ASC;
