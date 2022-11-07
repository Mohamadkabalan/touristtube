DELETE FROM amadeus_hotel_image where hotel_id = 15734 AND tt_media_type_id = 2;
DELETE FROM cms_hotel_image where hotel_id = 15734 AND tt_media_type_id = 2;


INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`)
VALUES
(0,'thumb.jpg',15734,556,2,null,null,False,False),
(0,'thumb.jpg',15734,557,2,'{"scene": {"view": {"hlookat": "-14.298","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,558,2,'{"scene": {"view": {"hlookat": "33.955","vlookat" : "-34.207"}}}',null,True,True),
(0,'thumb.jpg',15734,1,2,null,null,False,False),
(0,'thumb.jpg',15734,15,2,'{"scene": {"view": {"hlookat": "-155.546","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,16,2,'{"scene": {"view": {"hlookat": "-120.304","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,6,2,null,null,False,False),
(0,'thumb.jpg',15734,25,2,'{"scene": {"view": {"hlookat": "140.747","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,26,2,'{"scene": {"view": {"hlookat": "-123.508","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',15734,427,2,null,null,False,False),
(0,'thumb.jpg',15734,428,2,'{"scene": {"view": {"hlookat": "-105.533","vlookat" : "19.867"}}}',null,True,True),
(0,'thumb.jpg',15734,429,2,'{"scene": {"view": {"hlookat": "-76.675","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,430,2,'{"scene": {"view": {"hlookat": "84.235","vlookat" : "1.746"}}}',null,False,False),
(0,'thumb.jpg',15734,431,2,'{"scene": {"view": {"hlookat": "66.642","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,246,2,null,null,False,False),
(0,'thumb.jpg',15734,247,2,'{"scene": {"view": {"hlookat": "126.522","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,251,2,'{"scene": {"view": {"hlookat": "73.328","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',15734,573,2,null,null,False,False),
(0,'thumb.jpg',15734,574,2,'{"scene": {"view": {"hlookat": "43.193","vlookat" : "0.152"}}}',null,True,True),
(0,'thumb.jpg',15734,575,2,'{"scene": {"view": {"hlookat": "-99.826","vlookat" : "1.282"}}}',null,True,True),
(0,'thumb.jpg',15734,576,2,'{"scene": {"view": {"hlookat": "466.816","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,577,2,'{"scene": {"view": {"hlookat": "-396.005","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',15734,578,2,null,null,False,False),
(0,'thumb.jpg',15734,579,2,'{"scene": {"view": {"hlookat": "101.465","vlookat" : "14.731"}}}',null,False,False),
(0,'thumb.jpg',15734,580,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,581,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,582,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,600,2,'{"scene": {"view": {"hlookat": "128.508","vlookat" : "14.594"}}}',null,False,False),
(0,'thumb.jpg',15734,536,2,null,null,False,False),
(0,'thumb.jpg',15734,538,2,'{"scene": {"view": {"hlookat": "-171.085","vlookat" : "6.461"}}}',null,True,True),
(0,'thumb.jpg',15734,539,2,'{"scene": {"view": {"hlookat": "-362.756","vlookat" : "0"}}}',null,True,True),
(0,'thumb.jpg',15734,540,2,'{"scene": {"view": {"hlookat": "2.879","vlookat" : "0"}}}',null,False,False),
(0,'thumb.jpg',15734,541,2,'{"scene": {"view": {"hlookat": "-74.888","vlookat" : "0"}}}',null,False,False);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 15734
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 15734;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 15734);


