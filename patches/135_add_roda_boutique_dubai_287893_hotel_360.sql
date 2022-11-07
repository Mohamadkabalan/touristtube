
INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Villa Exterior',287893,79,1),
('1',287893,130,1),
('2',287893,193,2),
('3',287893,194,3),
('4',287893,195,4),
('Garden',287893,956,2),
('1',287893,957,1),
('2',287893,958,2),
('3',287893,959,3),
('Lobby',287893,1,3),
('Reception Area - 1',287893,15,1),
('Reception Area - 2',287893,16,2),
('Reception Area - 3',287893,17,3),
('Reception Area - 4',287893,18,4),
('Reception Area - 5',287893,19,5),
('Reception Area - 6',287893,20,6),
('Gym',287893,3,4),
('1',287893,80,1),
('2',287893,81,2),
('Villa Interior Second Level King Bedroom',287893,637,5),
('1',287893,638,0),
('2',287893,639,0),
('3',287893,640,0),
('Villa Interior Second Level Living Room',287893,758,6),
('1',287893,759,1),
('2',287893,760,2),
('Balcony',287893,761,3),
('Villa Interior Second Level Master Bedroom',287893,769,7),
('1',287893,770,1),
('2',287893,771,2),
('3',287893,772,3),
('Bathroom',287893,773,4);


DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 287893;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 287893;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',287893,79,2,null,null,False,False,null),
(0,'thumb.jpg',287893,130,2,'{"scene": {"view": {"hlookat": "26.088","vlookat" : "-7.705","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',287893,193,2,'{"scene": {"view": {"hlookat": "47.519","vlookat" : "-6.028","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,194,2,'{"scene": {"view": {"hlookat": "345.724","vlookat" : "-11.255","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,195,2,'{"scene": {"view": {"hlookat": "19.921","vlookat" : "2.390","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,956,2,null,null,False,False,null),
(0,'thumb.jpg',287893,957,2,'{"scene": {"view": {"hlookat": "154.189","vlookat" : "18.220","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,958,2,'{"scene": {"view": {"hlookat": "-35.081","vlookat" : "2.942","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',287893,959,2,'{"scene": {"view": {"hlookat": "-66.643","vlookat" : "0.150","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,1,2,null,null,False,False,null),
(0,'thumb.jpg',287893,15,2,'{"scene": {"view": {"hlookat": "-4.576","vlookat" : "0.343","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,16,2,'{"scene": {"view": {"hlookat": "359.271","vlookat" : "0.388","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,17,2,'{"scene": {"view": {"hlookat": "173.905","vlookat" : "-0.002","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,18,2,'{"scene": {"view": {"hlookat": "199.886","vlookat" : "-2.701","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,19,2,'{"scene": {"view": {"hlookat": "32.890","vlookat" : "0.999","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',287893,20,2,'{"scene": {"view": {"hlookat": "317.494","vlookat" : "-2.465","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,3,2,null,null,False,False,null),
(0,'thumb.jpg',287893,80,2,'{"scene": {"view": {"hlookat": "59.054","vlookat" : "0.968","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,81,2,'{"scene": {"view": {"hlookat": "16.506","vlookat" : "-0.734","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',287893,637,2,null,null,False,False,null),
(0,'thumb.jpg',287893,638,2,'{"scene": {"view": {"hlookat": "-25.511","vlookat" : "0.861","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,639,2,'{"scene": {"view": {"hlookat": "2.302","vlookat" : "-0.978","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',287893,640,2,'{"scene": {"view": {"hlookat": "237.010","vlookat" : "9.145","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,758,2,null,null,False,False,null),
(0,'thumb.jpg',287893,759,2,'{"scene": {"view": {"hlookat": "-40.419","vlookat" : "2.579","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',287893,760,2,'{"scene": {"view": {"hlookat": "-19.513","vlookat" : "29.184","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,761,2,'{"scene": {"view": {"hlookat": "244.838","vlookat" : "-0.971","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,769,2,null,null,False,False,null),
(0,'thumb.jpg',287893,770,2,'{"scene": {"view": {"hlookat": "-11.466","vlookat" : "1.378","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,771,2,'{"scene": {"view": {"hlookat": "343.464","vlookat" : "-0.209","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',287893,772,2,'{"scene": {"view": {"hlookat": "-98.695","vlookat" : "-1.515","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',287893,773,2,'{"scene": {"view": {"hlookat": "4.633","vlookat" : "-0.634","fov" : "140"}}}',null,False,False,null);



DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 287893
AND d.parent_id IS NULL
;


DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 287893;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 287893);


