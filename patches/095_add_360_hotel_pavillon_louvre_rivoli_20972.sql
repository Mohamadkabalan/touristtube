INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',20972,556,1),
(' - ',20972,557,1),
('Lobby',20972,1,2),
(' -',20972,15,1),
('Breakfast Room',20972,6,3),
('View 1',20972,25,1),
('View 2',20972,26,2),
('View 3',20972,27,3),
('Single Room',20972,530,4),
('1 - View 1',20972,531,1),
('1 - View 2',20972,532,2),
('Bathroom',20972,535,3),
('Single Room 2',20972,542,5),
('2 - View 1',20972,543,1),
('2 - View 2',20972,544,2),
('Double Room',20972,427,6),
('View 1',20972,428,1),
('Bathroom',20972,429,2),
('View 3',20972,430,3),
('Twin Room',20972,246,7),
('View 1',20972,247,1),
('View 2',20972,248,2),
('Triple Room',20972,263,8),
('View 1',20972,264,1),
('View 2',20972,265,2),
('Family Room ',20972,536,9),
('View 1',20972,538,1),
('View 2',20972,539,2),
('View 3',20972,540,3),
('View 4',20972,541,4),
('Entrance',20972,537,5);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20972;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20972;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 20972;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',20972,556,2,null,null,False,False),
(0,'thumb.jpg',20972,557,2,'{"scene": {"view": {"hlookat": "-33.992","vlookat" : "-11.022"}}}',null,True,True),
(0,'thumb.jpg',20972,1,2,null,null,False,False),
(0,'thumb.jpg',20972,15,2,'{"scene": {"view": {"hlookat": "156.862","vlookat" : "0.211"}}}',null,True,True),
(0,'thumb.jpg',20972,6,2,null,null,False,False),
(0,'thumb.jpg',20972,25,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',20972,26,2,'{"scene": {"view": {"hlookat": "147.580","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20972,27,2,'{"scene": {"view": {"hlookat": "-46.748","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,530,2,null,null,False,False),
(0,'thumb.jpg',20972,531,2,'{"scene": {"view": {"hlookat": "185.429","vlookat" : "29.502"}}}',null,True,True),
(0,'thumb.jpg',20972,532,2,'{"scene": {"view": {"hlookat": "-161.717","vlookat" : "7.871"}}}',null,False,False),
(0,'thumb.jpg',20972,535,2,'{"scene": {"view": {"hlookat": "-137.227","vlookat" : "0.000"}}}',null,False,False),
(0,'thumb.jpg',20972,542,2,null,null,False,False),
(0,'thumb.jpg',20972,543,2,'{"scene": {"view": {"hlookat": "11.611","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,544,2,'{"scene": {"view": {"hlookat": "-26.738","vlookat" : "0.103"}}}',null,True,True),
(0,'thumb.jpg',20972,427,2,null,null,False,False),
(0,'thumb.jpg',20972,428,2,'{"scene": {"view": {"hlookat": "157.387","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,429,2,'{"scene": {"view": {"hlookat": "168.765","vlookat" : "8.932"}}}',null,True,True),
(0,'thumb.jpg',20972,430,2,'{"scene": {"view": {"hlookat": "399.925","vlookat" : "32.483"}}}',null,False,False),
(0,'thumb.jpg',20972,246,2,null,null,False,False),
(0,'thumb.jpg',20972,247,2,'{"scene": {"view": {"hlookat": "156.062","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,248,2,'{"scene": {"view": {"hlookat": "21.141","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,263,2,null,null,False,False),
(0,'thumb.jpg',20972,264,2,'{"scene": {"view": {"hlookat": "13.319","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,265,2,'{"scene": {"view": {"hlookat": "-25.511","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,536,2,null,null,False,False),
(0,'thumb.jpg',20972,538,2,'{"scene": {"view": {"hlookat": "-38.257","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,539,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',20972,540,2,'{"scene": {"view": {"hlookat": "-151.291","vlookat" : "0.000"}}}',null,True,True),
(0,'thumb.jpg',20972,541,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',20972,537,2,'{"scene": {"view": {"hlookat": "334.923","vlookat" : "0.128"}}}',null,False,False);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 20972 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20972;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20972);


