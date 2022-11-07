
# Sofitel Dubai Jumeirah Beach

# https://www.touristtube.com/hotel-details-Sofitel+Dubai+Jumeirah+Beach-85344




SET @hid = 85344;

DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = @hid;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = @hid;
DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;
DELETE FROM cms_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;


INSERT INTO `touristtube`.`hotel_to_hotel_divisions`
(`name`, `hotel_id`, `hotel_division_id`, `sort_order`)
VALUES
('Entrance', @hid, 79, 1),
('Exterior', @hid, 130, 1),
('Lobby', @hid, 1, 2),
('1', @hid, 15, 1),
('2', @hid, 16, 2),
('3', @hid, 17, 3),
('Reception', @hid, 18, 4),
('Plantation Restaurant and Bar', @hid, 167, 3),
('Plantation Bar', @hid, 168, 1),
('2', @hid, 169, 2),
('3', @hid, 170, 3),
('Café Concierge', @hid, 8, 4),
('-', @hid, 33, 1),
('Club Millesime', @hid, 28, 5),
('1', @hid, 29, 1),
('2', @hid, 30, 2),
('Business Centre', @hid, 323, 6),
('-', @hid, 325, 1),
('Infini Pool', @hid, 2, 7),
('1', @hid, 21, 1),
('2', @hid, 92, 2),
('3', @hid, 186, 3),
('4', @hid, 187, 4),
('5', @hid, 658, 5),
('Gym', @hid, 3, 8),
('1', @hid, 80, 1),
('2', @hid, 81, 2),
('Meeting Rooms', @hid, 131, 9),
('Antibes', @hid, 132, 1),
('Cannes', @hid, 252, 2),
('Menton', @hid, 253, 3),
('Superior King Room', @hid, 486, 10),
('Bedroom - Bathroom', @hid, 487, 1),
('Balcony', @hid, 492, 2),
('Superior Twin Room', @hid, 246, 11),
('Bedroom - Bathroom', @hid, 247, 1),
('Balcony', @hid, 248, 2),
('Luxury Room', @hid, 480, 12),
('Bedroom - 1', @hid, 481, 1),
('Bedroom - 2', @hid, 482, 2),
('Bathroom', @hid, 484, 4),
('Balcony', @hid, 485, 5),
('Junior Suite', @hid, 967, 13),
('Bedroom - Living', @hid, 968, 1),
('Bathroom', @hid, 969, 2),
('Balcony', @hid, 970, 3),
('Prestige Suite', @hid, 159, 14),
('Bedroom', @hid, 164, 1),
('Living - Bedroom', @hid, 160, 2),
('Bathroom', @hid, 162, 3),
('Guest Bathroom', @hid, 163, 5),
('Balcony', @hid, 495, 6),
('Imperial Suite View', @hid, 1104, 15),
('-', @hid, 1105, 1);




INSERT INTO hotel_to_hotel_divisions_categories 
(hotel_division_category_id, hotel_id) 
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd 
INNER JOIN hotel_divisions hd ON (hd.id = hhd.hotel_division_id AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = @hid)) 
INNER JOIN hotel_divisions_categories hdc ON (hdc.id = hd.hotel_division_category_id) 
WHERE hhd.hotel_id = @hid;






INSERT INTO `touristtube`.`cms_hotel_image`
(`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `media_settings`, `location`, `default_pic`, `is_featured`, `sort_order`)
VALUES
(0, 'thumb.jpg', @hid, 79, 2, NULL, NULL, false, false, 1),
(0, 'thumb.jpg', @hid, 130, 2, '{"scene": {"view": {"hlookat": "-0.164", "vlookat" : "-16.889", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 1, 2, NULL, NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 15, 2, '{"scene": {"view": {"hlookat": "340.610", "vlookat" : "-0.052", "fov" : "140.000"}}}', NULL, false, false, 1),
(0, 'thumb.jpg', @hid, 16, 2, '{"scene": {"view": {"hlookat": "-67.853", "vlookat" : "0.986", "fov" : "134.494"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 17, 2, '{"scene": {"view": {"hlookat": "392.068", "vlookat" : "-0.219", "fov" : "140.000"}}}', NULL, true, true, 3),
(0, 'thumb.jpg', @hid, 18, 2, '{"scene": {"view": {"hlookat": "124.339", "vlookat" : "14.980", "fov" : "140.000"}}}', NULL, false, false, 4),
(0, 'thumb.jpg', @hid, 167, 2, NULL, NULL, false, false, 3),
(0, 'thumb.jpg', @hid, 168, 2, '{"scene": {"view": {"hlookat": "2.951", "vlookat" : "-3.115", "fov" : "140.000"}}}', NULL, false, false, 1),
(0, 'thumb.jpg', @hid, 169, 2, '{"scene": {"view": {"hlookat": "9.839", "vlookat" : "0.328", "fov" : "140.000"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 170, 2, '{"scene": {"view": {"hlookat": "1.147", "vlookat" : "0.820", "fov" : "140.000"}}}', NULL, false, false, 3),
(0, 'thumb.jpg', @hid, 8, 2, NULL, NULL, false, false, 4),
(0, 'thumb.jpg', @hid, 33, 2, '{"scene": {"view": {"hlookat": "169.358", "vlookat" : "4.790", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 28, 2, NULL, NULL, false, false, 5),
(0, 'thumb.jpg', @hid, 29, 2, '{"scene": {"view": {"hlookat": "-37.543", "vlookat" : "26.017", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 30, 2, '{"scene": {"view": {"hlookat": "93.494", "vlookat" : "14.977", "fov" : "140.000"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 323, 2, NULL, NULL, false, false, 6),
(0, 'thumb.jpg', @hid, 325, 2, '{"scene": {"view": {"hlookat": "354.061", "vlookat" : "-0.433", "fov" : "140.000"}}}', NULL, false, false, 1),
(0, 'thumb.jpg', @hid, 2, 2, NULL, NULL, false, false, 7),
(0, 'thumb.jpg', @hid, 21, 2, '{"scene": {"view": {"hlookat": "-15.576", "vlookat" : "-0.328", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 92, 2, '{"scene": {"view": {"hlookat": "5.088", "vlookat" : "0.820", "fov" : "140.000"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 186, 2, '{"scene": {"view": {"hlookat": "170.606", "vlookat" : "0.100", "fov" : "140.000"}}}', NULL, false, false, 3),
(0, 'thumb.jpg', @hid, 187, 2, '{"scene": {"view": {"hlookat": "0.984", "vlookat" : "0.000", "fov" : "140.000"}}}', NULL, false, false, 4),
(0, 'thumb.jpg', @hid, 658, 2, '{"scene": {"view": {"hlookat": "-8.855", "vlookat" : "-0.164", "fov" : "140.000"}}}', NULL, false, false, 5),
(0, 'thumb.jpg', @hid, 3, 2, NULL, NULL, false, false, 8),
(0, 'thumb.jpg', @hid, 80, 2, '{"scene": {"view": {"hlookat": "130.674", "vlookat" : "0.187", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 81, 2, '{"scene": {"view": {"hlookat": "578.054", "vlookat" : "-0.299", "fov" : "140.000"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 131, 2, NULL, NULL, false, false, 9),
(0, 'thumb.jpg', @hid, 132, 2, '{"scene": {"view": {"hlookat": "0.656", "vlookat" : "6.887", "fov" : "140.000"}}}', NULL, false, false, 1),
(0, 'thumb.jpg', @hid, 252, 2, '{"scene": {"view": {"hlookat": "-0.164", "vlookat" : "9.510", "fov" : "140.000"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 253, 2, '{"scene": {"view": {"hlookat": "0.164", "vlookat" : "5.739", "fov" : "140.000"}}}', NULL, false, false, 3),
(0, 'thumb.jpg', @hid, 486, 2, NULL, NULL, false, false, 10),
(0, 'thumb.jpg', @hid, 487, 2, '{"scene": {"view": {"hlookat": "156.492", "vlookat" : "2.573", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 492, 2, '{"scene": {"view": {"hlookat": "-27.179", "vlookat" : "-0.094", "fov" : "140.000"}}}', NULL, true, true, 2),
(0, 'thumb.jpg', @hid, 246, 2, NULL, NULL, false, false, 11),
(0, 'thumb.jpg', @hid, 247, 2, '{"scene": {"view": {"hlookat": "173.559", "vlookat" : "33.677", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 248, 2, '{"scene": {"view": {"hlookat": "-36.563", "vlookat" : "3.704", "fov" : "140.000"}}}', NULL, true, true, 2),
(0, 'thumb.jpg', @hid, 480, 2, NULL, NULL, false, false, 12),
(0, 'thumb.jpg', @hid, 481, 2, '{"scene": {"view": {"hlookat": "16.011", "vlookat" : "18.516", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 482, 2, '{"scene": {"view": {"hlookat": "165.898", "vlookat" : "15.618", "fov" : "140.000"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 484, 2, '{"scene": {"view": {"hlookat": "-159.105", "vlookat" : "23.544", "fov" : "140.000"}}}', NULL, false, false, 4),
(0, 'thumb.jpg', @hid, 485, 2, '{"scene": {"view": {"hlookat": "165.739", "vlookat" : "0.183", "fov" : "140.000"}}}', NULL, false, false, 5),
(0, 'thumb.jpg', @hid, 967, 2, NULL, NULL, false, false, 13),
(0, 'thumb.jpg', @hid, 968, 2, '{"scene": {"view": {"hlookat": "16.722", "vlookat" : "-0.821", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 969, 2, '{"scene": {"view": {"hlookat": "163.576", "vlookat" : "-0.044", "fov" : "140.000"}}}', NULL, false, false, 2),
(0, 'thumb.jpg', @hid, 970, 2, '{"scene": {"view": {"hlookat": "27.959", "vlookat" : "1.286", "fov" : "140.000"}}}', NULL, true, true, 3),
(0, 'thumb.jpg', @hid, 159, 2, NULL, NULL, false, false, 14),
(0, 'thumb.jpg', @hid, 164, 2, '{"scene": {"view": {"hlookat": "-0.328", "vlookat" : "34.267", "fov" : "140.000"}}}', NULL, true, true, 1),
(0, 'thumb.jpg', @hid, 160, 2, '{"scene": {"view": {"hlookat": "34.242", "vlookat" : "0.073", "fov" : "140.000"}}}', NULL, true, true, 2),
(0, 'thumb.jpg', @hid, 162, 2, '{"scene": {"view": {"hlookat": "-1.638", "vlookat" : "35.415", "fov" : "140.000"}}}', NULL, false, false, 3),
(0, 'thumb.jpg', @hid, 163, 2, '{"scene": {"view": {"hlookat": "-27.208", "vlookat" : "19.450", "fov" : "140.000"}}}', NULL, false, false, 5),
(0, 'thumb.jpg', @hid, 495, 2, '{"scene": {"view": {"hlookat": "-21.808", "vlookat" : "-1.148", "fov" : "140.000"}}}', NULL, true, true, 6),
(0, 'thumb.jpg', @hid, 1104, 2, NULL, NULL, false, false, 15), 
(0, 'thumb.jpg', @hid, 1105, 2, '{"scene": {"view": {"hlookat": "-0.652","vlookat" : "-1.968","fov" : "140.000"}}}', NULL, true, true, 7);




DELETE hi
FROM cms_hotel_image hi
INNER JOIN hotel_divisions d ON (d.id = hi.hotel_division_id AND d.parent_id IS NULL) 
WHERE hi.hotel_id = @hid AND hi.tt_media_type_id = 2;



DELETE FROM amadeus_hotel_image WHERE hotel_id = @hid AND tt_media_type_id = 2;


INSERT INTO amadeus_hotel_image 
(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order) 
SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is NULL, "", location), default_pic, is_featured, media_settings, sort_order
FROM cms_hotel_image hi 
WHERE hi.hotel_id = @hid AND tt_media_type_id = 2;


UPDATE cms_hotel SET has_360 = 1 WHERE id = @hid;
