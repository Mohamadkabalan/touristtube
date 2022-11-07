INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Main Entrance',20971,79,1),
(' - ',20971,130,1),
('Lobby',20971,1,2),
('View 1',20971,15,1),
('View 2',20971,16,2),
('Breakfast Room',20971,6,3),
('View 1',20971,25,1),
('View 2',20971,26,2),
('Salon Garnier',20971,131,4),
('View 1',20971,132,1),
('View 2',20971,252,2),
('View 3',20971,253,3),
('Single room',20971,530,5),
('View 1',20971,531,1),
('View 2',20971,532,2),
('Standard double room',20971,427,6),
('View 1',20971,428,1),
('View 2',20971,429,2),
('Standard twin room',20971,246,7),
('View 1',20971,247,1),
('View 2',20971,248,2),
('Superior Double twin room',20971,139,8),
('View 1',20971,140,1),
('View 2',20971,141,2);



DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20971;


INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20971;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 20971;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',20971,79,2,null,null,False,False),
(0,'thumb.jpg',20971,130,2,'{"scene": {"view": {"hlookat": "-59.296","vlookat" : "-20.014"}}}',null,True,True),
(0,'thumb.jpg',20971,1,2,null,null,False,False),
(0,'thumb.jpg',20971,15,2,'{"scene": {"view": {"hlookat": "53.996","vlookat" : "1.842"}}}',null,True,True),
(0,'thumb.jpg',20971,16,2,'{"scene": {"view": {"hlookat": "1.258","vlookat" : "0.517"}}}',null,True,True),
(0,'thumb.jpg',20971,6,2,null,null,False,False),
(0,'thumb.jpg',20971,25,2,'{"scene": {"view": {"hlookat": "-205.754","vlookat" : "1.481"}}}',null,True,True),
(0,'thumb.jpg',20971,26,2,'{"scene": {"view": {"hlookat": "158.329","vlookat" : "0.826"}}}',null,True,True),
(0,'thumb.jpg',20971,131,2,null,null,False,False),
(0,'thumb.jpg',20971,132,2,'{"scene": {"view": {"hlookat": "-182.249","vlookat" : "-1.177"}}}',null,True,True),
(0,'thumb.jpg',20971,252,2,'{"scene": {"view": {"hlookat": "6.233","vlookat" : "3.090"}}}',null,True,True),
(0,'thumb.jpg',20971,253,2,'{"scene": {"view": {"hlookat": "11.839","vlookat" : "2.067"}}}',null,True,True),
(0,'thumb.jpg',20971,530,2,null,null,False,False),
(0,'thumb.jpg',20971,531,2,'{"scene": {"view": {"hlookat": "-361.856","vlookat" : "5.131"}}}',null,True,True),
(0,'thumb.jpg',20971,532,2,'{"scene": {"view": {"hlookat": "25.058","vlookat" : "-1.202"}}}',null,True,True),
(0,'thumb.jpg',20971,427,2,null,null,False,False),
(0,'thumb.jpg',20971,428,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',20971,429,2,'{"scene": {"view": {"hlookat": "-181.257","vlookat" : "-1.168"}}}',null,True,True),
(0,'thumb.jpg',20971,246,2,null,null,False,False),
(0,'thumb.jpg',20971,247,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',20971,248,2,'{"scene": {"view": {"hlookat": "-158.937","vlookat" : "-0.186"}}}',null,True,True),
(0,'thumb.jpg',20971,139,2,null,null,False,False),
(0,'thumb.jpg',20971,140,2,'{"scene": {"view": {"hlookat": "-63.448","vlookat" : "-1.928"}}}',null,True,True),
(0,'thumb.jpg',20971,141,2,'{"scene": {"view": {"hlookat": "-171.279","vlookat" : "1.384"}}}',null,True,True);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 20971 
AND d.parent_id IS NULL;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20971;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20971);



