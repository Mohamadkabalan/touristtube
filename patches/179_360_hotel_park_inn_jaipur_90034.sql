DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 90034;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 90034;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 90034;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 90034;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',90034,79,1),
('1',90034,130,1),
('2',90034,193,2),
('Lobby',90034,1,2),
('1',90034,15,1),
('2',90034,16,2),
('La Italia Bar',90034,7,3),
('1',90034,152,1),
('2',90034,153,2),
('3',90034,154,3),
('Colors - All Day Dining Restaurant',90034,28,4),
('1',90034,29,1),
('2',90034,30,2),
('3',90034,31,3),
('4',90034,110,4),
('5',90034,111,5),
('6',90034,112,6),
('La Italia Restaurant',90034,167,5),
('1',90034,168,1),
('2',90034,169,2),
('3',90034,170,3),
('4',90034,177,4),
('5',90034,178,5),
('6',90034,179,6),
('Crystal Banquet Hall',90034,131,6),
('1',90034,132,1),
('2',90034,252,2),
('Suite Room',90034,159,7),
('1',90034,160,1),
('2',90034,161,2),
('3',90034,493,3),
('4',90034,164,4),
('5',90034,162,5),
('Superior Room - King Size Bed',90034,486,8),
('1',90034,487,1),
('2',90034,488,2),
('3',90034,490,3),
('4',90034,491,4),
('Superior Room - Twin Bed',90034,998,9),
('1',90034,999,1),
('2',90034,1000,2),
('3',90034,1001,3),
('4',90034,1002,4),
('5',90034,1004,5);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 90034;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',90034,79,2,null,null,False,False,null),
(0,'thumb.jpg',90034,130,2,'{"scene": {"view": {"hlookat": "0.784","vlookat" : "0.000","fov" : "139.265"}}}',null,False,False,null),
(0,'thumb.jpg',90034,193,2,'{"scene": {"view": {"hlookat": "1.852","vlookat" : "-0.224","fov" : "138.931"}}}',null,True,True,1),
(0,'thumb.jpg',90034,1,2,null,null,False,False,null),
(0,'thumb.jpg',90034,15,2,'{"scene": {"view": {"hlookat": "3.025","vlookat" : "1.120","fov" : "138.779"}}}',null,False,False,null),
(0,'thumb.jpg',90034,16,2,'{"scene": {"view": {"hlookat": "1.300","vlookat" : "0.000","fov" : "138.700"}}}',null,True,True,2),
(0,'thumb.jpg',90034,7,2,null,null,False,False,null),
(0,'thumb.jpg',90034,152,2,'{"scene": {"view": {"hlookat": "5.344","vlookat" : "1.628","fov" : "138.779"}}}',null,True,True,3),
(0,'thumb.jpg',90034,153,2,'{"scene": {"view": {"hlookat": "-2.410","vlookat" : "0.065","fov" : "138.967"}}}',null,False,False,null),
(0,'thumb.jpg',90034,154,2,'{"scene": {"view": {"hlookat": "-0.135","vlookat" : "0.852","fov" : "138.659"}}}',null,True,True,4),
(0,'thumb.jpg',90034,28,2,null,null,False,False,null),
(0,'thumb.jpg',90034,29,2,'{"scene": {"view": {"hlookat": "4.908","vlookat" : "0.881","fov" : "137.552"}}}',null,True,True,5),
(0,'thumb.jpg',90034,30,2,'{"scene": {"view": {"hlookat": "6.140","vlookat" : "2.715","fov" : "138.779"}}}',null,False,False,null),
(0,'thumb.jpg',90034,31,2,'{"scene": {"view": {"hlookat": "3.478","vlookat" : "1.799","fov" : "138.818"}}}',null,True,True,6),
(0,'thumb.jpg',90034,110,2,'{"scene": {"view": {"hlookat": "2.292","vlookat" : "0.927","fov" : "138.856"}}}',null,False,False,null),
(0,'thumb.jpg',90034,111,2,'{"scene": {"view": {"hlookat": "2.324","vlookat" : "0.401","fov" : "138.659"}}}',null,True,True,7),
(0,'thumb.jpg',90034,112,2,'{"scene": {"view": {"hlookat": "1.997","vlookat" : "0.753","fov" : "138.659"}}}',null,False,False,null),
(0,'thumb.jpg',90034,167,2,null,null,False,False,null),
(0,'thumb.jpg',90034,168,2,'{"scene": {"view": {"hlookat": "3.913","vlookat" : "3.794","fov" : "138.219"}}}',null,False,False,null),
(0,'thumb.jpg',90034,169,2,'{"scene": {"view": {"hlookat": "2.673","vlookat" : "1.094","fov" : "138.967"}}}',null,False,False,null),
(0,'thumb.jpg',90034,170,2,'{"scene": {"view": {"hlookat": "3.469","vlookat" : "0.795","fov" : "138.171"}}}',null,True,True,8),
(0,'thumb.jpg',90034,177,2,'{"scene": {"view": {"hlookat": "1.054","vlookat" : "1.275","fov" : "138.618"}}}',null,False,False,null),
(0,'thumb.jpg',90034,178,2,'{"scene": {"view": {"hlookat": "1.713","vlookat" : "0.598","fov" : "138.779"}}}',null,True,True,9),
(0,'thumb.jpg',90034,179,2,'{"scene": {"view": {"hlookat": "1.300","vlookat" : "0.000","fov" : "138.700"}}}',null,False,False,null),
(0,'thumb.jpg',90034,131,2,null,null,False,False,null),
(0,'thumb.jpg',90034,132,2,'{"scene": {"view": {"hlookat": "0.833","vlookat" : "0.000","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',90034,252,2,'{"scene": {"view": {"hlookat": "1.106","vlookat" : "0.000","fov" : "138.894"}}}',null,True,True,10),
(0,'thumb.jpg',90034,159,2,null,null,False,False,null),
(0,'thumb.jpg',90034,160,2,'{"scene": {"view": {"hlookat": "2.347","vlookat" : "0.451","fov" : "138.491"}}}',null,False,False,null),
(0,'thumb.jpg',90034,161,2,'{"scene": {"view": {"hlookat": "1.221","vlookat" : "0.000","fov" : "138.779"}}}',null,True,True,11),
(0,'thumb.jpg',90034,493,2,'{"scene": {"view": {"hlookat": "1.781","vlookat" : "0.000","fov" : "138.219"}}}',null,False,False,null),
(0,'thumb.jpg',90034,164,2,'{"scene": {"view": {"hlookat": "1.144","vlookat" : "0.000","fov" : "138.856"}}}',null,True,True,12),
(0,'thumb.jpg',90034,162,2,'{"scene": {"view": {"hlookat": "1.926","vlookat" : "0.000","fov" : "138.074"}}}',null,True,True,13),
(0,'thumb.jpg',90034,486,2,null,null,False,False,null),
(0,'thumb.jpg',90034,487,2,'{"scene": {"view": {"hlookat": "1.646","vlookat" : "0.000","fov" : "138.402"}}}',null,False,False,null),
(0,'thumb.jpg',90034,488,2,'{"scene": {"view": {"hlookat": "2.745","vlookat" : "0.227","fov" : "138.402"}}}',null,True,True,14),
(0,'thumb.jpg',90034,490,2,'{"scene": {"view": {"hlookat": "4.090","vlookat" : "1.233","fov" : "138.534"}}}',null,False,False,null),
(0,'thumb.jpg',90034,491,2,'{"scene": {"view": {"hlookat": "0.861","vlookat" : "0.000","fov" : "139.139"}}}',null,True,True,15),
(0,'thumb.jpg',90034,998,2,null,null,False,False,null),
(0,'thumb.jpg',90034,999,2,'{"scene": {"view": {"hlookat": "4.869","vlookat" : "2.825","fov" : "138.491"}}}',null,False,False,null),
(0,'thumb.jpg',90034,1000,2,'{"scene": {"view": {"hlookat": "2.305","vlookat" : "0.409","fov" : "139.171"}}}',null,True,True,16),
(0,'thumb.jpg',90034,1001,2,'{"scene": {"view": {"hlookat": "2.559","vlookat" : "0.000","fov" : "137.441"}}}',null,False,False,null),
(0,'thumb.jpg',90034,1002,2,'{"scene": {"view": {"hlookat": "1.509","vlookat" : "0.000","fov" : "138.491"}}}',null,False,False,null),
(0,'thumb.jpg',90034,1004,2,'{"scene": {"view": {"hlookat": "0.766","vlookat" : "0.000","fov" : "139.234"}}}',null,True,True,17);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 90034
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 90034;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 90034);

