DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 17656;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 17656;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 17656;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 17656;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',17656,79,1),
('-',17656,130,1),
('Lobby',17656,1,2),
('Reception',17656,15,1),
('-',17656,16,2),
('Au Bar Cafe & Bar Lounge',17656,7,3),
('1',17656,152,1),
('2',17656,153,2),
('3',17656,154,3),
('Garden',17656,155,4),
('Terraza Café',17656,8,4),
('-',17656,33,1),
('Ras Beirut',17656,28,5),
('-',17656,29,1),
('Chez Zakhia',17656,167,6),
('1',17656,168,1),
('2',17656,169,2),
('3',17656,170,3),
('Beach',17656,722,7),
('1',17656,723,1),
('2',17656,724,2),
('3',17656,725,3),
('4',17656,726,4),
('Island',17656,464,8),
('1',17656,465,1),
('2',17656,466,2),
('3',17656,467,3),
('Spa ',17656,4,9),
('1',17656,101,1),
('2',17656,102,2),
('3',17656,103,3),
('Turkish Bath',17656,104,4),
('Massage Single Room',17656,105,5),
('Beauty Salon',17656,256,10),
('-',17656,257,1),
('Beryte Ballroom',17656,131,11),
('1',17656,132,1),
('2',17656,252,2),
('3',17656,253,3),
('Elissar Room',17656,293,12),
('1',17656,297,1),
('2',17656,298,2),
('Multimedia Room',17656,294,13),
('1',17656,303,1),
('2',17656,304,2),
('Adonis Room',17656,295,14),
('1',17656,309,1),
('2',17656,310,2),
('Ishtar Room',17656,296,15),
('1',17656,315,1),
('2',17656,316,2),
('Classic City View',17656,752,16),
('1',17656,753,1),
('2',17656,754,2),
('Bathroom',17656,755,3),
('Deluxe Sea View',17656,133,17),
('-',17656,134,1),
('Bathroom',17656,138,2),
('Superior Room',17656,486,18),
('-',17656,487,1),
('Bathroom',17656,491,2),
('Business Suite City View',17656,10,19),
('1',17656,47,1),
('2',17656,48,2),
('Bathroom',17656,51,3),
('Premium Suite Sea View',17656,947,20),
('1',17656,948,1),
('2',17656,949,2),
('3',17656,950,3),
('Bathroom',17656,951,4),
('Panoramic Suite Sea View',17656,389,21),
('1',17656,394,1),
('2',17656,395,2),
('3',17656,396,3),
('Bathroom',17656,397,4),
('Riviera Suite Sea View',17656,11,22),
('1',17656,52,1),
('2',17656,53,2),
('3',17656,54,3),
('Bathroom - 1',17656,57,4),
('Bathroom - 2',17656,59,5);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 17656;



INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',17656,79,2,null,null,False,False,null),
(0,'thumb.jpg',17656,130,2,'{"scene": {"view": {"hlookat": "1.327","vlookat" : "9.405","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,1,2,null,null,False,False,null),
(0,'thumb.jpg',17656,15,2,'{"scene": {"view": {"hlookat": "-0.045","vlookat" : "17.053","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,16,2,'{"scene": {"view": {"hlookat": "-360.301","vlookat" : "20.016","fov" : "140"}}}',null,True,True,1),
(0,'thumb.jpg',17656,7,2,null,null,False,False,null),
(0,'thumb.jpg',17656,152,2,'{"scene": {"view": {"hlookat": "-30.492","vlookat" : "8.852","fov" : "140"}}}',null,True,True,2),
(0,'thumb.jpg',17656,153,2,'{"scene": {"view": {"hlookat": "-106.615","vlookat" : "6.013","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,154,2,'{"scene": {"view": {"hlookat": "4.263","vlookat" : "2.624","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,155,2,'{"scene": {"view": {"hlookat": "185.136","vlookat" : "7.519","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,8,2,null,null,False,False,null),
(0,'thumb.jpg',17656,33,2,'{"scene": {"view": {"hlookat": "-175.997","vlookat" : "8.258","fov" : "140"}}}',null,True,True,3),
(0,'thumb.jpg',17656,28,2,null,null,False,False,null),
(0,'thumb.jpg',17656,29,2,'{"scene": {"view": {"hlookat": "252.671","vlookat" : "5.586","fov" : "140"}}}',null,True,True,4),
(0,'thumb.jpg',17656,167,2,null,null,False,False,null),
(0,'thumb.jpg',17656,168,2,'{"scene": {"view": {"hlookat": "341.650","vlookat" : "-0.317","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',17656,169,2,'{"scene": {"view": {"hlookat": "-352.956","vlookat" : "9.881","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,170,2,'{"scene": {"view": {"hlookat": "-180.494","vlookat" : "-8.230","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,722,2,null,null,False,False,null),
(0,'thumb.jpg',17656,723,2,'{"scene": {"view": {"hlookat": "295.561","vlookat" : "0.479","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',17656,724,2,'{"scene": {"view": {"hlookat": "-180.177","vlookat" : "0.036","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,725,2,'{"scene": {"view": {"hlookat": "-173.855","vlookat" : "6.356","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,726,2,'{"scene": {"view": {"hlookat": "-88.399","vlookat" : "-0.696","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,464,2,null,null,False,False,null),
(0,'thumb.jpg',17656,465,2,'{"scene": {"view": {"hlookat": "-349.648","vlookat" : "19.113","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',17656,466,2,'{"scene": {"view": {"hlookat": "-24.731","vlookat" : "2.151","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,467,2,'{"scene": {"view": {"hlookat": "-424.382","vlookat" : "3.938","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,4,2,null,null,False,False,null),
(0,'thumb.jpg',17656,101,2,'{"scene": {"view": {"hlookat": "377.312","vlookat" : "13.885","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,102,2,'{"scene": {"view": {"hlookat": "344.332","vlookat" : "6.632","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,103,2,'{"scene": {"view": {"hlookat": "-431.189","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,104,2,'{"scene": {"view": {"hlookat": "180.992","vlookat" : "16.370","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,105,2,'{"scene": {"view": {"hlookat": "176.216","vlookat" : "20.225","fov" : "140"}}}',null,True,True,8),
(0,'thumb.jpg',17656,256,2,null,null,False,False,null),
(0,'thumb.jpg',17656,257,2,'{"scene": {"view": {"hlookat": "-175.420","vlookat" : "22.861","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,131,2,null,null,False,False,null),
(0,'thumb.jpg',17656,132,2,'{"scene": {"view": {"hlookat": "349.406","vlookat" : "0.013","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,252,2,'{"scene": {"view": {"hlookat": "150.850","vlookat" : "1.650","fov" : "140"}}}',null,True,True,9),
(0,'thumb.jpg',17656,253,2,'{"scene": {"view": {"hlookat": "-812.085","vlookat" : "-3.323","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,293,2,null,null,False,False,null),
(0,'thumb.jpg',17656,297,2,'{"scene": {"view": {"hlookat": "188.096","vlookat" : "7.056","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',17656,298,2,'{"scene": {"view": {"hlookat": "-89.053","vlookat" : "21.283","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,294,2,null,null,False,False,null),
(0,'thumb.jpg',17656,303,2,'{"scene": {"view": {"hlookat": "0.496","vlookat" : "31.647","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,304,2,'{"scene": {"view": {"hlookat": "-112.268","vlookat" : "17.615","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,295,2,null,null,False,False,null),
(0,'thumb.jpg',17656,309,2,'{"scene": {"view": {"hlookat": "-575.013","vlookat" : "-0.248","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,310,2,'{"scene": {"view": {"hlookat": "-182.147","vlookat" : "31.098","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,296,2,null,null,False,False,null),
(0,'thumb.jpg',17656,315,2,'{"scene": {"view": {"hlookat": "-99.315","vlookat" : "17.622","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,316,2,'{"scene": {"view": {"hlookat": "174.875","vlookat" : "10.835","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,752,2,null,null,False,False,null),
(0,'thumb.jpg',17656,753,2,'{"scene": {"view": {"hlookat": "358.901","vlookat" : "34.524","fov" : "140"}}}',null,True,True,11),
(0,'thumb.jpg',17656,754,2,'{"scene": {"view": {"hlookat": "346.089","vlookat" : "17.708","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,755,2,'{"scene": {"view": {"hlookat": "-180.665","vlookat" : "10.636","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,133,2,null,null,False,False,null),
(0,'thumb.jpg',17656,134,2,'{"scene": {"view": {"hlookat": "716.090","vlookat" : "19.690","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',17656,138,2,'{"scene": {"view": {"hlookat": "0.244","vlookat" : "16.567","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,486,2,null,null,False,False,null),
(0,'thumb.jpg',17656,487,2,'{"scene": {"view": {"hlookat": "12.570","vlookat" : "17.021","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',17656,491,2,'{"scene": {"view": {"hlookat": "728.804","vlookat" : "22.748","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,10,2,null,null,False,False,null),
(0,'thumb.jpg',17656,47,2,'{"scene": {"view": {"hlookat": "354.953","vlookat" : "22.182","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',17656,48,2,'{"scene": {"view": {"hlookat": "275.633","vlookat" : "0.580","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,51,2,'{"scene": {"view": {"hlookat": "1001.255","vlookat" : "8.218","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,947,2,null,null,False,False,null),
(0,'thumb.jpg',17656,948,2,'{"scene": {"view": {"hlookat": "-172.935","vlookat" : "9.497","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,949,2,'{"scene": {"view": {"hlookat": "-90.415","vlookat" : "21.465","fov" : "140"}}}',null,True,True,15),
(0,'thumb.jpg',17656,950,2,'{"scene": {"view": {"hlookat": "1074.494","vlookat" : "15.548","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,951,2,'{"scene": {"view": {"hlookat": "-15.871","vlookat" : "3.278","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,389,2,null,null,False,False,null),
(0,'thumb.jpg',17656,394,2,'{"scene": {"view": {"hlookat": "1079.224","vlookat" : "15.049","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',17656,395,2,'{"scene": {"view": {"hlookat": "-472.662","vlookat" : "9.206","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,396,2,'{"scene": {"view": {"hlookat": "245.227","vlookat" : "10.615","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,397,2,'{"scene": {"view": {"hlookat": "89.527","vlookat" : "19.761","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,11,2,null,null,False,False,null),
(0,'thumb.jpg',17656,52,2,'{"scene": {"view": {"hlookat": "-84.645","vlookat" : "24.162","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',17656,53,2,'{"scene": {"view": {"hlookat": "13.775","vlookat" : "18.857","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,54,2,'{"scene": {"view": {"hlookat": "-51.853","vlookat" : "11.703","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,57,2,'{"scene": {"view": {"hlookat": "-102.498","vlookat" : "20.037","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',17656,59,2,'{"scene": {"view": {"hlookat": "18.146","vlookat" : "21.771","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 17656
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 17656;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 17656);

