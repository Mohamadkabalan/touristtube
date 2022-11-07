DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 182761;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 182761;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 182761;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 182761;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',182761,79,1),
('1',182761,130,1),
('2',182761,193,2),
('Lobby',182761,1,2),
('1',182761,15,1),
('2',182761,16,2),
('3',182761,17,3),
('4',182761,18,4),
('Mr Wayan - Balinese Cuisine',182761,28,3),
('1',182761,29,1),
('2',182761,30,2),
('3',182761,31,3),
('Wapa\'s Restaurant',182761,167,4),
('1',182761,168,1),
('2',182761,169,2),
('Floating Bale',182761,173,5),
('1',182761,174,1),
('2',182761,175,2),
('Infinity Pool',182761,2,6),
('1',182761,21,1),
('Pool',182761,992,7),
('1',182761,993,1),
('2',182761,994,2),
('Ume Spa',182761,4,8),
('1',182761,101,1),
('2',182761,102,2),
('3',182761,103,3),
('Yoga Studio',182761,464,9),
('1',182761,465,1),
('2',182761,466,2),
('Gym',182761,3,10),
('1',182761,80,1),
('2',182761,81,2),
('The Wedding Terrace',182761,941,11),
('1',182761,942,1),
('Meeting Room',182761,131,12),
('1',182761,132,1),
('2',182761,252,2),
('Open Stage - Ampitheatre',182761,1005,13),
('1',182761,1006,1),
('Rice Fields',182761,956,14),
('1',182761,957,1),
('2',182761,958,2),
('3',182761,959,3),
('The Boutique Shop',182761,746,15),
('1',182761,747,1),
('Lanai Room',182761,486,16),
('1',182761,492,1),
('2',182761,487,2),
('3',182761,488,3),
('4',182761,491,4),
('Di Ume Suite',182761,590,17),
('1',182761,591,1),
('2',182761,592,2),
('3',182761,593,3),
('4',182761,594,4),
('5',182761,596,5),
('Villa With Pool',182761,758,18),
('1',182761,759,1),
('2',182761,760,2),
('3',182761,761,3),
('4',182761,762,4),
('5',182761,763,5),
('6',182761,764,6),
('7',182761,765,7),
('Family Villa With Pool',182761,769,19),
('1',182761,770,1),
('2',182761,771,2),
('3',182761,772,3),
('4',182761,773,4),
('5',182761,774,5),
('6',182761,775,6),
('7',182761,776,7),
('Wapa Villa With Pool',182761,780,20),
('1',182761,781,1),
('Balinese Cooking Lesson',182761,188,21),
('1',182761,189,1),
('2',182761,190,2),
('Balinese Palm Leaf Creation',182761,205,22),
('1',182761,206,1);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 182761;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',182761,79,2,null,null,False,False,null),
(0,'thumb.jpg',182761,130,2,'{"scene": {"view": {"hlookat": "3.846","vlookat" : "0.000","fov" : "138.576"}}}',null,True,True,1),
(0,'thumb.jpg',182761,193,2,'{"scene": {"view": {"hlookat": "1.341","vlookat" : "0.000","fov" : "138.659"}}}',null,False,False,null),
(0,'thumb.jpg',182761,1,2,null,null,False,False,null),
(0,'thumb.jpg',182761,15,2,'{"scene": {"view": {"hlookat": "-8.194","vlookat" : "-0.373","fov" : "138.967"}}}',null,True,True,2),
(0,'thumb.jpg',182761,16,2,'{"scene": {"view": {"hlookat": "-0.757","vlookat" : "0.108","fov" : "138.447"}}}',null,False,False,null),
(0,'thumb.jpg',182761,17,2,'{"scene": {"view": {"hlookat": "2.026","vlookat" : "0.000","fov" : "137.975"}}}',null,False,False,null),
(0,'thumb.jpg',182761,18,2,'{"scene": {"view": {"hlookat": "0.928","vlookat" : "0.000","fov" : "139.072"}}}',null,False,False,null),
(0,'thumb.jpg',182761,28,2,null,null,False,False,null),
(0,'thumb.jpg',182761,29,2,'{"scene": {"view": {"hlookat": "0.889","vlookat" : "0.001","fov" : "138.576"}}}',null,True,True,3),
(0,'thumb.jpg',182761,30,2,'{"scene": {"view": {"hlookat": "-1.063","vlookat" : "0.114","fov" : "137.268"}}}',null,False,False,null),
(0,'thumb.jpg',182761,31,2,'{"scene": {"view": {"hlookat": "1.598","vlookat" : "0.000","fov" : "138.402"}}}',null,False,False,null),
(0,'thumb.jpg',182761,167,2,null,null,False,False,null),
(0,'thumb.jpg',182761,168,2,'{"scene": {"view": {"hlookat": "1.752","vlookat" : "0.000","fov" : "138.266"}}}',null,False,False,null),
(0,'thumb.jpg',182761,169,2,'{"scene": {"view": {"hlookat": "11.352","vlookat" : "0.276","fov" : "138.219"}}}',null,False,False,null),
(0,'thumb.jpg',182761,173,2,null,null,False,False,null),
(0,'thumb.jpg',182761,174,2,'{"scene": {"view": {"hlookat": "2.936","vlookat" : "0.213","fov" : "138.219"}}}',null,True,True,4),
(0,'thumb.jpg',182761,175,2,'{"scene": {"view": {"hlookat": "-0.921","vlookat" : "1.051","fov" : "139.106"}}}',null,False,False,null),
(0,'thumb.jpg',182761,2,2,null,null,False,False,null),
(0,'thumb.jpg',182761,21,2,'{"scene": {"view": {"hlookat": "2.586","vlookat" : "0.000","fov" : "138.074"}}}',null,False,False,null),
(0,'thumb.jpg',182761,992,2,null,null,False,False,null),
(0,'thumb.jpg',182761,993,2,'{"scene": {"view": {"hlookat": "-81.811","vlookat" : "0.520","fov" : "138.818"}}}',null,True,True,5),
(0,'thumb.jpg',182761,994,2,'{"scene": {"view": {"hlookat": "-0.392","vlookat" : "-0.001","fov" : "139.072"}}}',null,False,False,null),
(0,'thumb.jpg',182761,4,2,null,null,False,False,null),
(0,'thumb.jpg',182761,101,2,'{"scene": {"view": {"hlookat": "-4.587","vlookat" : "0.305","fov" : "138.491"}}}',null,True,True,6),
(0,'thumb.jpg',182761,102,2,'{"scene": {"view": {"hlookat": "1.260","vlookat" : "0.000","fov" : "138.740"}}}',null,False,False,null),
(0,'thumb.jpg',182761,103,2,'{"scene": {"view": {"hlookat": "8.354","vlookat" : "0.585","fov" : "139.072"}}}',null,False,False,null),
(0,'thumb.jpg',182761,464,2,null,null,False,False,null),
(0,'thumb.jpg',182761,465,2,'{"scene": {"view": {"hlookat": "-1.608","vlookat" : "0.470","fov" : "139.002"}}}',null,True,True,7),
(0,'thumb.jpg',182761,466,2,'{"scene": {"view": {"hlookat": "-0.120","vlookat" : "0.160","fov" : "139.295"}}}',null,False,False,null),
(0,'thumb.jpg',182761,3,2,null,null,False,False,null),
(0,'thumb.jpg',182761,80,2,'{"scene": {"view": {"hlookat": "2.128","vlookat" : "0.000","fov" : "138.534"}}}',null,True,True,8),
(0,'thumb.jpg',182761,81,2,'{"scene": {"view": {"hlookat": "6.053","vlookat" : "0.365","fov" : "138.402"}}}',null,False,False,null),
(0,'thumb.jpg',182761,941,2,null,null,False,False,null),
(0,'thumb.jpg',182761,942,2,'{"scene": {"view": {"hlookat": "8.193","vlookat" : "0.547","fov" : "138.931"}}}',null,False,False,null),
(0,'thumb.jpg',182761,131,2,null,null,False,False,null),
(0,'thumb.jpg',182761,132,2,'{"scene": {"view": {"hlookat": "5.038","vlookat" : "0.000","fov" : "137.769"}}}',null,False,False,null),
(0,'thumb.jpg',182761,252,2,'{"scene": {"view": {"hlookat": "6.071","vlookat" : "0.213","fov" : "138.219"}}}',null,False,False,null),
(0,'thumb.jpg',182761,1005,2,null,null,False,False,null),
(0,'thumb.jpg',182761,1006,2,'{"scene": {"view": {"hlookat": "4.722","vlookat" : "0.078","fov" : "137.441"}}}',null,False,False,null),
(0,'thumb.jpg',182761,956,2,null,null,False,False,null),
(0,'thumb.jpg',182761,957,2,'{"scene": {"view": {"hlookat": "-2.361","vlookat" : "-0.295","fov" : "138.402"}}}',null,False,False,null),
(0,'thumb.jpg',182761,958,2,'{"scene": {"view": {"hlookat": "1.829","vlookat" : "0.000","fov" : "138.171"}}}',null,False,False,null),
(0,'thumb.jpg',182761,959,2,'{"scene": {"view": {"hlookat": "1.300","vlookat" : "0.000","fov" : "138.70"}}}',null,True,True,9),
(0,'thumb.jpg',182761,746,2,null,null,False,False,null),
(0,'thumb.jpg',182761,747,2,'{"scene": {"view": {"hlookat": "2.429","vlookat" : "0.101","fov" : "138.266"}}}',null,False,False,null),
(0,'thumb.jpg',182761,486,2,null,null,False,False,null),
(0,'thumb.jpg',182761,492,2,'{"scene": {"view": {"hlookat": "2.996","vlookat" : "0.309","fov" : "137.924"}}}',null,True,True,10),
(0,'thumb.jpg',182761,487,2,'{"scene": {"view": {"hlookat": "0.024","vlookat" : "0.053","fov" : "138.491"}}}',null,False,False,null),
(0,'thumb.jpg',182761,488,2,'{"scene": {"view": {"hlookat": "129.611","vlookat" : "2.180","fov" : "138.074"}}}',null,False,False,null),
(0,'thumb.jpg',182761,491,2,'{"scene": {"view": {"hlookat": "-14.094","vlookat" : "0.507","fov" : "137.924"}}}',null,False,False,null),
(0,'thumb.jpg',182761,590,2,null,null,False,False,null),
(0,'thumb.jpg',182761,591,2,'{"scene": {"view": {"hlookat": "2.890","vlookat" : "1.342","fov" : "138.266"}}}',null,True,True,11),
(0,'thumb.jpg',182761,592,2,'{"scene": {"view": {"hlookat": "-0.429","vlookat" : "-0.343","fov" : "138.779"}}}',null,False,False,null),
(0,'thumb.jpg',182761,593,2,'{"scene": {"view": {"hlookat": "-2.290","vlookat" : "-0.252","fov" : "138.659"}}}',null,False,False,null),
(0,'thumb.jpg',182761,594,2,'{"scene": {"view": {"hlookat": "-22.206","vlookat" : "0.309","fov" : "138.795"}}}',null,False,False,null),
(0,'thumb.jpg',182761,596,2,'{"scene": {"view": {"hlookat": "181.976","vlookat" : "1.060","fov" : "138.534"}}}',null,False,False,null),
(0,'thumb.jpg',182761,758,2,null,null,False,False,null),
(0,'thumb.jpg',182761,759,2,'{"scene": {"view": {"hlookat": "5.888","vlookat" : "0.061"}}}',null,False,False,null),
(0,'thumb.jpg',182761,760,2,'{"scene": {"view": {"hlookat": "3.510","vlookat" : "0.835"}}}',null,True,True,12),
(0,'thumb.jpg',182761,761,2,'{"scene": {"view": {"hlookat": "-142.531","vlookat" : "0.247"}}}',null,False,False,null),
(0,'thumb.jpg',182761,762,2,'{"scene": {"view": {"hlookat": "2.001","vlookat" : "0.183"}}}',null,False,False,null),
(0,'thumb.jpg',182761,763,2,'{"scene": {"view": {"hlookat": "1.781","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',182761,764,2,'{"scene": {"view": {"hlookat": "4.437","vlookat" : "0.365"}}}',null,False,False,null),
(0,'thumb.jpg',182761,765,2,'{"scene": {"view": {"hlookat": "4.870","vlookat" : "0.653"}}}',null,False,False,null),
(0,'thumb.jpg',182761,769,2,null,null,False,False,null),
(0,'thumb.jpg',182761,770,2,'{"scene": {"view": {"hlookat": "2.985","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',182761,771,2,'{"scene": {"view": {"hlookat": "5.446","vlookat" : "0.000"}}}',null,True,True,13),
(0,'thumb.jpg',182761,772,2,'{"scene": {"view": {"hlookat": "-5.748","vlookat" : "0.349"}}}',null,False,False,null),
(0,'thumb.jpg',182761,773,2,'{"scene": {"view": {"hlookat": "0.817","vlookat" : "0.051"}}}',null,False,False,null),
(0,'thumb.jpg',182761,774,2,'{"scene": {"view": {"hlookat": "-23.718","vlookat" : "0.868"}}}',null,False,False,null),
(0,'thumb.jpg',182761,775,2,'{"scene": {"view": {"hlookat": "1.382","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',182761,776,2,'{"scene": {"view": {"hlookat": "2.231","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',182761,780,2,null,null,False,False,null),
(0,'thumb.jpg',182761,781,2,'{"scene": {"view": {"hlookat": "5.232","vlookat" : "0.691"}}}',null,True,True,14),
(0,'thumb.jpg',182761,188,2,null,null,False,False,null),
(0,'thumb.jpg',182761,189,2,'{"scene": {"view": {"hlookat": "1.104","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',182761,190,2,'{"scene": {"view": {"hlookat": "-0.302","vlookat" : "0.000"}}}',null,False,False,null),
(0,'thumb.jpg',182761,205,2,null,null,False,False,null),
(0,'thumb.jpg',182761,206,2,'{"scene": {"view": {"hlookat": "2.733","vlookat" : "0.041"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 182761
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 182761;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 182761);

