INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',147848,79,1),
('1',147848,130,1),
('2',147848,193,2),
('3',147848,194,3),
('Lobby',147848,1,0),
('1',147848,15,1),
('2',147848,16,2),
('3',147848,17,3),
('4',147848,18,4),
('5',147848,19,5),
('6',147848,20,6),
('Tea Lounge',147848,8,0),
('1',147848,33,1),
('2',147848,34,2),
('Fizz Bar',147848,7,0),
('1',147848,152,1),
('2',147848,153,2),
('3',147848,154,3),
('4',147848,155,4),
('5',147848,156,5),
('6',147848,157,6),
('7',147848,158,7),
('Lattice',147848,28,0),
('1',147848,29,1),
('2',147848,30,2),
('3',147848,31,3),
('4',147848,32,4),
('5',147848,110,5),
('6',147848,111,6),
('Fitness Centre',147848,3,0),
('1',147848,80,1),
('Spa and Saloon',147848,4,0),
('1',147848,101,1),
('2',147848,102,2),
('3',147848,103,3),
('4',147848,104,4),
('5',147848,105,5),
('Swimming Pool',147848,2,0),
('1',147848,21,1),
('2',147848,92,2),
('3',147848,186,3),
('Kids Activity Zone',147848,84,0),
('1',147848,85,1),
('2',147848,86,2),
('Studio Rooms',147848,530,0),
('1',147848,531,1),
('2',147848,532,2),
('3',147848,533,3),
('4',147848,534,4),
('Deluxe Rooms',147848,139,0),
('1',147848,140,1),
('2',147848,141,2),
('3',147848,142,3),
('Premium Rooms',147848,480,0),
('1',147848,481,1),
('2',147848,482,2),
('3',147848,483,3),
('4',147848,484,4);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 147848;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',147848,79,2,null,null,False,False,null),
(0,'thumb.jpg',147848,130,2,'{"scene": {"view": {"hlookat": "3.396","vlookat" : "-1.395","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',147848,193,2,'{"scene": {"view": {"hlookat": "27.700","vlookat" : "-16.400","fov" : "140"}}}',null,2,2,1),
(0,'thumb.jpg',147848,194,2,'{"scene": {"view": {"hlookat": "0.330","vlookat" : "0.000","fov" : "140"}}}',null,3,3,null),
(0,'thumb.jpg',147848,1,2,null,null,False,False,null),
(0,'thumb.jpg',147848,15,2,'{"scene": {"view": {"hlookat": "-85.841","vlookat" : "1.148","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',147848,16,2,'{"scene": {"view": {"hlookat": "6.480","vlookat" : "2.951","fov" : "140"}}}',null,2,2,null),
(0,'thumb.jpg',147848,17,2,'{"scene": {"view": {"hlookat": "-23.426","vlookat" : "0.475","fov" : "139.982"}}}',null,3,3,null),
(0,'thumb.jpg',147848,18,2,'{"scene": {"view": {"hlookat": "-3.657","vlookat" : "0.138","fov" : "139.951"}}}',null,4,4,2),
(0,'thumb.jpg',147848,19,2,'{"scene": {"view": {"hlookat": "11.610","vlookat" : "3.496","fov" : "140"}}}',null,5,5,null),
(0,'thumb.jpg',147848,20,2,'{"scene": {"view": {"hlookat": "1.568","vlookat" : "0.000","fov" : "139.943"}}}',null,6,6,null),
(0,'thumb.jpg',147848,8,2,null,null,False,False,null),
(0,'thumb.jpg',147848,33,2,'{"scene": {"view": {"hlookat": "3.822","vlookat" : "-0.492","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',147848,34,2,'{"scene": {"view": {"hlookat": "23.818","vlookat" : "1.804","fov" : "140"}}}',null,2,2,null),
(0,'thumb.jpg',147848,7,2,null,null,False,False,null),
(0,'thumb.jpg',147848,152,2,'{"scene": {"view": {"hlookat": "6.653","vlookat" : "0.000","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',147848,153,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,2,2,null),
(0,'thumb.jpg',147848,154,2,'{"scene": {"view": {"hlookat": "14.594","vlookat" : "3.935","fov" : "140"}}}',null,3,3,4),
(0,'thumb.jpg',147848,155,2,'{"scene": {"view": {"hlookat": "0.009","vlookat" : "0.000","fov" : "139.991"}}}',null,4,4,null),
(0,'thumb.jpg',147848,156,2,'{"scene": {"view": {"hlookat": "6.231","vlookat" : "2.296","fov" : "140"}}}',null,5,5,null),
(0,'thumb.jpg',147848,157,2,'{"scene": {"view": {"hlookat": "2.624","vlookat" : "-0.656","fov" : "140"}}}',null,6,6,null),
(0,'thumb.jpg',147848,158,2,'{"scene": {"view": {"hlookat": "1.157","vlookat" : "0.656","fov" : "140"}}}',null,7,7,null),
(0,'thumb.jpg',147848,28,2,null,null,False,False,null),
(0,'thumb.jpg',147848,29,2,'{"scene": {"view": {"hlookat": "2.035","vlookat" : "0.000","fov" : "139.842"}}}',null,True,True,null),
(0,'thumb.jpg',147848,30,2,'{"scene": {"view": {"hlookat": "2.952","vlookat" : "1.148","fov" : "140"}}}',null,2,2,null),
(0,'thumb.jpg',147848,31,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,3,3,5),
(0,'thumb.jpg',147848,32,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,4,4,null),
(0,'thumb.jpg',147848,110,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,5,5,null),
(0,'thumb.jpg',147848,111,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,6,6,null),
(0,'thumb.jpg',147848,3,2,null,null,False,False,null),
(0,'thumb.jpg',147848,80,2,'{"scene": {"view": {"hlookat": "0.788","vlookat" : "0.000","fov" : "139.234"}}}',null,True,True,6),
(0,'thumb.jpg',147848,4,2,null,null,False,False,null),
(0,'thumb.jpg',147848,101,2,'{"scene": {"view": {"hlookat": "-2.221","vlookat" : "0.164","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',147848,102,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,2,2,7),
(0,'thumb.jpg',147848,103,2,'{"scene": {"view": {"hlookat": "-0.164","vlookat" : "0.000","fov" : "140"}}}',null,3,3,null),
(0,'thumb.jpg',147848,104,2,'{"scene": {"view": {"hlookat": "0.028","vlookat" : "0.000","fov" : "139.972"}}}',null,4,4,null),
(0,'thumb.jpg',147848,105,2,'{"scene": {"view": {"hlookat": "111.288","vlookat" : "8.398","fov" : "140"}}}',null,5,5,null),
(0,'thumb.jpg',147848,2,2,null,null,False,False,null),
(0,'thumb.jpg',147848,21,2,'{"scene": {"view": {"hlookat": "0.400","vlookat" : "0.000","fov" : "139.994"}}}',null,True,True,null),
(0,'thumb.jpg',147848,92,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,2,2,8),
(0,'thumb.jpg',147848,186,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,3,3,null),
(0,'thumb.jpg',147848,84,2,null,null,False,False,null),
(0,'thumb.jpg',147848,85,2,'{"scene": {"view": {"hlookat": "0.863","vlookat" : "0.000","fov" : "139.487"}}}',null,True,True,9),
(0,'thumb.jpg',147848,86,2,'{"scene": {"view": {"hlookat": "7.576","vlookat" : "0.328","fov" : "140"}}}',null,2,2,null),
(0,'thumb.jpg',147848,530,2,null,null,False,False,null),
(0,'thumb.jpg',147848,531,2,'{"scene": {"view": {"hlookat": "18.720","vlookat" : "1.312","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',147848,532,2,'{"scene": {"view": {"hlookat": "0.066","vlookat" : "0.000","fov" : "139.934"}}}',null,2,2,10),
(0,'thumb.jpg',147848,533,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,3,3,null),
(0,'thumb.jpg',147848,534,2,'{"scene": {"view": {"hlookat": "90.933","vlookat" : "5.159","fov" : "140"}}}',null,4,4,null),
(0,'thumb.jpg',147848,139,2,null,null,False,False,null),
(0,'thumb.jpg',147848,140,2,'{"scene": {"view": {"hlookat": "15.724","vlookat" : "-0.164","fov" : "140"}}}',null,True,True,null),
(0,'thumb.jpg',147848,141,2,'{"scene": {"view": {"hlookat": "0.000","vlookat" : "0.000","fov" : "140"}}}',null,2,2,11),
(0,'thumb.jpg',147848,142,2,'{"scene": {"view": {"hlookat": "0.057","vlookat" : "0.000","fov" : "139.943"}}}',null,3,3,null),
(0,'thumb.jpg',147848,480,2,null,null,False,False,null),
(0,'thumb.jpg',147848,481,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,True,True,null),
(0,'thumb.jpg',147848,482,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,2,2,null),
(0,'thumb.jpg',147848,483,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,3,3,12),
(0,'thumb.jpg',147848,484,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,4,4,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 147848
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 147848;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 147848);



