DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 237249;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 237249;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 237249;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 237249;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Exterior',237249,79,0),
('1',237249,130,1),
('Lobby',237249,1,0),
('1',237249,15,1),
('2',237249,16,2),
('3',237249,17,3),
('Sunset Bar',237249,7,0),
('1',237249,152,1),
('2',237249,153,2),
('Pavilion',237249,28,0),
('1',237249,29,1),
('2',237249,30,2),
('Jaipur Grill',237249,167,0),
('1',237249,168,1),
('2',237249,169,2),
('3',237249,170,3),
('Swimming Pool',237249,2,0),
('1',237249,21,1),
('2',237249,92,2),
('Fitness Centre',237249,3,0),
('1',237249,80,1),
('Suite Room',237249,159,0),
('1',237249,162,1),
('2',237249,160,2),
('3',237249,161,3),
('4',237249,493,4),
('5',237249,164,5),
('6',237249,262,6),
('7',237249,163,7),
('Superior Room',237249,486,0),
('1',237249,487,1),
('2',237249,488,2),
('Premium Room',237249,480,0),
('1',237249,481,1),
('2',237249,482,2);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 237249;




INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',237249,79,2,null,null,False,False,null),
(0,'thumb.jpg',237249,130,2,'{"scene": {"view": {"hlookat": "-30.162","vlookat" : "-0.182","fov" : "139.728"}}}',null,True,True,1),
(0,'thumb.jpg',237249,1,2,null,null,False,False,null),
(0,'thumb.jpg',237249,15,2,'{"scene": {"view": {"hlookat": "-18.680","vlookat" : "0.159","fov" : "139.561"}}}',null,True,True,2),
(0,'thumb.jpg',237249,16,2,'{"scene": {"view": {"hlookat": "66.671","vlookat" : "1.010","fov" : "139.203"}}}',null,2,2,null),
(0,'thumb.jpg',237249,17,2,'{"scene": {"view": {"hlookat": "205.940","vlookat" : "7.733","fov" : "139.171"}}}',null,3,3,null),
(0,'thumb.jpg',237249,7,2,null,null,False,False,null),
(0,'thumb.jpg',237249,152,2,'{"scene": {"view": {"hlookat": "-23.977","vlookat" : "-0.000","fov" : "139.462"}}}',null,True,True,3),
(0,'thumb.jpg',237249,153,2,'{"scene": {"view": {"hlookat": "-30.318","vlookat" : "-0.092","fov" : "139.584"}}}',null,2,2,null),
(0,'thumb.jpg',237249,28,2,null,null,False,False,null),
(0,'thumb.jpg',237249,29,2,'{"scene": {"view": {"hlookat": "-159.578","vlookat" : "-1.765","fov" : "139.606"}}}',null,True,True,null),
(0,'thumb.jpg',237249,30,2,'{"scene": {"view": {"hlookat": "-25.587","vlookat" : "0.100","fov" : "139.690"}}}',null,2,2,4),
(0,'thumb.jpg',237249,167,2,null,null,False,False,null),
(0,'thumb.jpg',237249,168,2,'{"scene": {"view": {"hlookat": "-49.984","vlookat" : "1.476","fov" : "139.512"}}}',null,True,True,null),
(0,'thumb.jpg',237249,169,2,'{"scene": {"view": {"hlookat": "146.438","vlookat" : "1.762","fov" : "139.813"}}}',null,2,2,5),
(0,'thumb.jpg',237249,170,2,'{"scene": {"view": {"hlookat": "-52.933","vlookat" : "-2.515","fov" : "139.709"}}}',null,3,3,null),
(0,'thumb.jpg',237249,2,2,null,null,False,False,null),
(0,'thumb.jpg',237249,21,2,'{"scene": {"view": {"hlookat": "-26.070","vlookat" : "1.036","fov" : "139.537"}}}',null,True,True,null),
(0,'thumb.jpg',237249,92,2,'{"scene": {"view": {"hlookat": "46.861","vlookat" : "2.965","fov" : "139.690"}}}',null,2,2,6),
(0,'thumb.jpg',237249,3,2,null,null,False,False,null),
(0,'thumb.jpg',237249,80,2,'{"scene": {"view": {"hlookat": "10.048","vlookat" : "1.924","fov" : "139.628"}}}',null,True,True,7),
(0,'thumb.jpg',237249,159,2,null,null,False,False,null),
(0,'thumb.jpg',237249,162,2,'{"scene": {"view": {"hlookat": "33.638","vlookat" : "4.567","fov" : "139.813"}}}',null,True,True,null),
(0,'thumb.jpg',237249,160,2,'{"scene": {"view": {"hlookat": "-21.241","vlookat" : "-0.344","fov" : "139.764"}}}',null,2,2,null),
(0,'thumb.jpg',237249,161,2,'{"scene": {"view": {"hlookat": "-32.460","vlookat" : "-0.984","fov" : "140.000"}}}',null,3,3,null),
(0,'thumb.jpg',237249,493,2,'{"scene": {"view": {"hlookat": "-20.660","vlookat" : "0.164","fov" : "140.000"}}}',null,4,4,null),
(0,'thumb.jpg',237249,164,2,'{"scene": {"view": {"hlookat": "-26.918","vlookat" : "-0.722","fov" : "139.709"}}}',null,5,5,8),
(0,'thumb.jpg',237249,262,2,'{"scene": {"view": {"hlookat": "-28.037","vlookat" : "0.000","fov" : "139.690"}}}',null,6,6,null),
(0,'thumb.jpg',237249,163,2,'{"scene": {"view": {"hlookat": "-20.896","vlookat" : "1.386","fov" : "139.746"}}}',null,7,7,null),
(0,'thumb.jpg',237249,486,2,null,null,False,False,null),
(0,'thumb.jpg',237249,487,2,'{"scene": {"view": {"hlookat": "-21.766","vlookat" : "-0.097","fov" : "139.649"}}}',null,True,True,9),
(0,'thumb.jpg',237249,488,2,'{"scene": {"view": {"hlookat": "-25.301","vlookat" : "0.103","fov" : "139.728"}}}',null,2,2,null),
(0,'thumb.jpg',237249,480,2,null,null,False,False,null),
(0,'thumb.jpg',237249,481,2,'{"scene": {"view": {"hlookat": "3.275","vlookat" : "0.282","fov" : "139.512"}}}',null,True,True,null),
(0,'thumb.jpg',237249,482,2,'{"scene": {"view": {"hlookat": "-19.020","vlookat" : "-1.476","fov" : "140.000"}}}',null,2,2,10);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 237249
AND d.parent_id IS NULL
;



DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 237249;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 237249);
