DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 361060;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 361060;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 361060;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 361060;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance ',361060,79,1),
('1',361060,130,1),
('2',361060,193,2),
('Lobby - Reception ',361060,1,2),
('1',361060,15,1),
('2',361060,16,2),
('3',361060,17,3),
('Twenty Ten Restaurant',361060,6,3),
('1',361060,25,1),
('2',361060,26,2),
('3',361060,27,3),
('Rooftop',361060,28,4),
('1',361060,29,1),
('2',361060,30,2),
('Board Room',361060,131,5),
('-',361060,132,1),
('Ashtan 1 and 2',361060,293,6),
('1',361060,297,1),
('2',361060,298,2),
('Superior Twin',361060,486,7),
('1',361060,487,1),
('2',361060,488,2),
('Bathroom',361060,491,3),
('Superior Double',361060,546,8),
('1',361060,547,1),
('2',361060,548,2),
('Bathroom',361060,550,3),
('Premium Room',361060,480,9),
('1',361060,481,1),
('2',361060,482,2),
('Bathroom',361060,484,3);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 361060;



INSERT INTO `cms_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',361060,79,2,null,null,False,False,null),
(0,'thumb.jpg',361060,130,2,'{"scene": {"view": {"hlookat": "-18.643","vlookat" : "-30.403","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',361060,193,2,'{"scene": {"view": {"hlookat": "156.109","vlookat" : "2.981","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',361060,1,2,null,null,False,False,null),
(0,'thumb.jpg',361060,15,2,'{"scene": {"view": {"hlookat": "-32.939","vlookat" : "-25.006","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',361060,16,2,'{"scene": {"view": {"hlookat": "168.832","vlookat" : "-14.430","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',361060,17,2,'{"scene": {"view": {"hlookat": "-93.940","vlookat" : "-17.720","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',361060,6,2,null,null,False,False,null),
(0,'thumb.jpg',361060,25,2,'{"scene": {"view": {"hlookat": "-37.152","vlookat" : "-4.088","fov" : "113.847"}}}',null,True,True,4),
(0,'thumb.jpg',361060,26,2,'{"scene": {"view": {"hlookat": "-152.091","vlookat" : "7.377","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',361060,27,2,'{"scene": {"view": {"hlookat": "-23.589","vlookat" : "1.935","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',361060,28,2,null,null,False,False,null),
(0,'thumb.jpg',361060,29,2,'{"scene": {"view": {"hlookat": "5.525","vlookat" : "7.713","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',361060,30,2,'{"scene": {"view": {"hlookat": "-397.616","vlookat" : "-9.130","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',361060,131,2,null,null,False,False,null),
(0,'thumb.jpg',361060,132,2,'{"scene": {"view": {"hlookat": "149.732","vlookat" : "6.086","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',361060,293,2,null,null,False,False,null),
(0,'thumb.jpg',361060,297,2,'{"scene": {"view": {"hlookat": "102.459","vlookat" : "2.583","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',361060,298,2,'{"scene": {"view": {"hlookat": "-59.433","vlookat" : "-0.639","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',361060,486,2,null,null,False,False,null),
(0,'thumb.jpg',361060,487,2,'{"scene": {"view": {"hlookat": "-17.520","vlookat" : "6.218","fov" : "138.527"}}}',null,True,True,11),
(0,'thumb.jpg',361060,488,2,'{"scene": {"view": {"hlookat": "-72.289","vlookat" : "0.328","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',361060,491,2,'{"scene": {"view": {"hlookat": "-38.483","vlookat" : "-7.898","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',361060,546,2,null,null,False,False,null),
(0,'thumb.jpg',361060,547,2,'{"scene": {"view": {"hlookat": "109.760","vlookat" : "3.793","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',361060,548,2,'{"scene": {"view": {"hlookat": "-243.176","vlookat" : "5.076","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',361060,550,2,'{"scene": {"view": {"hlookat": "154.045","vlookat" : "6.986","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',361060,480,2,null,null,False,False,null),
(0,'thumb.jpg',361060,481,2,'{"scene": {"view": {"hlookat": "-173.343","vlookat" : "5.874","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',361060,482,2,'{"scene": {"view": {"hlookat": "-216.356","vlookat" : "-7.002","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',361060,484,2,'{"scene": {"view": {"hlookat": "-149.737","vlookat" : "2.520","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM cms_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 361060
AND d.parent_id IS NULL
;


UPDATE cms_hotel set has_360 = 1 where id = 361060;


DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 361060;

INSERT INTO amadeus_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 361060);

