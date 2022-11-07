INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View ',52575,556,1),
('-',52575,557,1),
('Main Entrance',52575,79,2),
('-',52575,130,1),
('Lobby ',52575,1,3),
('View 1 ',52575,17,1),
('Pisco Bar',52575,18,2),
('Breakfast',52575,6,4),
('View 1 ',52575,25,1),
('View 2',52575,26,2),
('1K Restaurant ',52575,28,5),
('View 1 ',52575,29,1),
('View 2',52575,30,2),
('View 3',52575,31,3),
('View 4',52575,32,0),
('La Mezcaleria',52575,94,6),
('View 1 ',52575,95,1),
('View 2',52575,96,2),
('Salon Cuzco',52575,131,7),
('View 1 ',52575,132,1),
('View 2',52575,252,2),
('Gym ',52575,3,8),
('View 1 ',52575,80,1),
('View 2',52575,81,2),
('M Superior Room',52575,486,9),
('-',52575,487,1),
('Bathroom',52575,488,2),
('L Deluxe Room',52575,36,10),
('View 1 ',52575,37,1),
('View 2',52575,38,2),
('View 3',52575,39,3),
('XL Junior Suite',52575,212,11),
('View 1 ',52575,216,1),
('View 2',52575,213,2),
('View 3',52575,214,3),
('Bathroom ',52575,217,4),
('Bathroom View 2',52575,449,5),
('Pool Suite ',52575,159,12),
('View 1 ',52575,160,1),
('View 2',52575,164,2),
('View 3',52575,162,3),
('View 4',52575,161,4),
('View 5',52575,163,5),
('View 6',52575,495,6),
('View 7',52575,496,7);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 52575;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 52575;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 52575;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',52575,556,2,null,null,False,False),
(0,'thumb.jpg',52575,557,2,'{"scene": {"view": {"hlookat": "200.865","vlookat" : "-12.982"}}}',null,True,True),
(0,'thumb.jpg',52575,79,2,null,null,False,False),
(0,'thumb.jpg',52575,130,2,'{"scene": {"view": {"hlookat": "377.337","vlookat" : "2.119"}}}',null,False,False),
(0,'thumb.jpg',52575,1,2,null,null,False,False),
(0,'thumb.jpg',52575,17,2,'{"scene": {"view": {"hlookat": "169.753","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,18,2,'{"scene": {"view": {"hlookat": "-36.002","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,6,2,null,null,False,False),
(0,'thumb.jpg',52575,25,2,'{"scene": {"view": {"hlookat": "-184.43","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,26,2,'{"scene": {"view": {"hlookat": "140.994","vlookat" : "-2.837"}}}',null,True,True),
(0,'thumb.jpg',52575,28,2,null,null,False,False),
(0,'thumb.jpg',52575,29,2,'{"scene": {"view": {"hlookat": "-401.864","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,30,2,'{"scene": {"view": {"hlookat": "425.864","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,31,2,'{"scene": {"view": {"hlookat": "139.501","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,32,2,'{"scene": {"view": {"hlookat": "-24.304","vlookat" : "0.01"}}}',null,True,True),
(0,'thumb.jpg',52575,94,2,null,null,False,False),
(0,'thumb.jpg',52575,95,2,'{"scene": {"view": {"hlookat": "-425.322","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,96,2,'{"scene": {"view": {"hlookat": "380.426","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,131,2,null,null,False,False),
(0,'thumb.jpg',52575,132,2,'{"scene": {"view": {"hlookat": "-352.253","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,252,2,'{"scene": {"view": {"hlookat": "712.118","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,3,2,null,null,False,False),
(0,'thumb.jpg',52575,80,2,'{"scene": {"view": {"hlookat": "-20.402","vlookat" : "-11.994"}}}',null,True,True),
(0,'thumb.jpg',52575,81,2,'{"scene": {"view": {"hlookat": "91.609","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,486,2,null,null,False,False),
(0,'thumb.jpg',52575,487,2,'{"scene": {"view": {"hlookat": "-390.718","vlookat" : "-0.337"}}}',null,True,True),
(0,'thumb.jpg',52575,488,2,'{"scene": {"view": {"hlookat": "366.075","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,36,2,null,null,False,False),
(0,'thumb.jpg',52575,37,2,'{"scene": {"view": {"hlookat": "277.153","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,38,2,'{"scene": {"view": {"hlookat": "331.411","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,39,2,'{"scene": {"view": {"hlookat": "-71.431","vlookat" : "7.204"}}}',null,False,False),
(0,'thumb.jpg',52575,212,2,null,null,False,False),
(0,'thumb.jpg',52575,216,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,213,2,'{"scene": {"view": {"hlookat": "86.556","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,214,2,'{"scene": {"view": {"hlookat": "-64.604","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,217,2,'{"scene": {"view": {"hlookat": "-77.014","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,449,2,'{"scene": {"view": {"hlookat": "-389.622","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,159,2,null,null,False,False),
(0,'thumb.jpg',52575,160,2,'{"scene": {"view": {"hlookat": "-17.093","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,164,2,'{"scene": {"view": {"hlookat": "6.653","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,162,2,'{"scene": {"view": {"hlookat": "172.624","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,161,2,'{"scene": {"view": {"hlookat": "174.294","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',52575,163,2,'{"scene": {"view": {"hlookat": "163.302","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',52575,495,2,'{"scene": {"view": {"hlookat": "17.816","vlookat" : "29.977"}}}',null,False,False),
(0,'thumb.jpg',52575,496,2,'{"scene": {"view": {"hlookat": "146.052","vlookat" : "33.691"}}}',null,True,True);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 52575 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 52575;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 52575);


