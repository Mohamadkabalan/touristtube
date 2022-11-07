INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Street View',347906,556,1),
('1',347906,557,1),
('2',347906,558,2),
('Lobby Entrance',347906,1,2),
('1',347906,15,1),
('2',347906,16,2),
('3',347906,17,3),
('4',347906,18,4),
('5',347906,19,5),
('6',347906,20,6),
('7',347906,90,7),
('8',347906,91,8),
('Reception',347906,338,3),
('1',347906,339,1),
('Le Gastronome - Breakfast Room',347906,6,4),
('1',347906,25,1),
('2',347906,26,2),
('3',347906,27,3),
('4',347906,602,4),
('Terrace',347906,603,8),
('Cigar Lounge',347906,94,5),
('1',347906,95,1),
('2',347906,96,2),
('3',347906,97,3),
('4',347906,98,4),
('Mexico Lounge',347906,435,6),
('1',347906,436,1),
('2',347906,437,2),
(' The Machiavelli Conference Room',347906,131,7),
('1',347906,132,1),
('2',347906,252,2),
('SOUKOUN Wellness Spa & Gym',347906,4,8),
('1',347906,101,1),
('Gym 1',347906,102,2),
('Gym 2',347906,103,3),
('Gym 3',347906,104,4),
('2',347906,105,5),
('Sauna',347906,106,6),
('Steam',347906,211,7),
('Shower Massage',347906,671,8),
('Massage',347906,273,9),
('Solarium',347906,274,10),
(' Swimming Pool',347906,2,9),
('1',347906,21,1),
('2',347906,92,2),
('3',347906,186,3),
('4',347906,187,4),
('5',347906,658,5),
('6',347906,659,6),
('7',347906,660,7),
('8',347906,661,8),
('Standard Double Room 1',347906,427,10),
('1',347906,428,1),
('2',347906,429,2),
('Bathroom',347906,430,3),
('Standard Double Room 2',347906,705,11),
('1',347906,706,1),
('Bathroom',347906,707,2),
('Junior Suite',347906,212,12),
('1',347906,213,1),
('2',347906,214,2),
('Bathroom',347906,217,5),
('Senior Suite',347906,605,13),
('1',347906,606,1),
('2',347906,607,2),
('3',347906,663,3),
('4',347906,664,4),
('Bedroom - 1',347906,710,5),
('Bedroom - 2',347906,711,6),
('Bathroom',347906,608,7),
('Executive Suite ',347906,10,14),
('1',347906,48,1),
('2',347906,49,2),
('3',347906,336,3),
('4',347906,47,4),
('5',347906,50,5),
('6',347906,51,6),
('7',347906,337,7),
('8',347906,665,8),
('Presidential Suite',347906,355,15),
('1',347906,625,1),
('2',347906,356,2),
('3',347906,359,3),
('Bedroom - 1',347906,361,5),
('Bedroom - 2',347906,362,6),
('Bedroom - 3',347906,364,7),
('Bathroom ',347906,363,8),
('Ambassador Suite',347906,389,16),
('Bedroom - 1',347906,396,1),
('Bedroom - 2',347906,394,2),
('Bedroom - 3',347906,395,3),
('Bathroom',347906,397,4),
('Guest Room',347906,667,5),
('Guest Bathroom',347906,398,6),
('1',347906,668,7),
('2',347906,669,8),
('3',347906,670,9),
('4',347906,700,10),
('5',347906,701,11),
('6',347906,702,12),
('7',347906,703,13),
('Royal Suite',347906,11,17),
('Entrance',347906,52,1),
('1',347906,53,2),
('2',347906,54,3),
('3',347906,55,4),
('4',347906,56,5),
('5',347906,58,6),
('Bathroom - 1',347906,57,7),
('Bathroom - 2',347906,59,8),
('Grand Royal Suite',347906,675,18),
('Entrance',347906,676,1),
('Guest Bathroom',347906,712,2),
('1',347906,677,3),
('2',347906,678,4),
('3',347906,679,5),
('4',347906,680,6),
('Bathroom',347906,682,7),
('Aisle',347906,683,8),
('Closet',347906,684,9),
('Bedroom - 1',347906,685,10),
('Bedroom - 2',347906,686,11),
('Terrace - 1',347906,687,12),
('Terrace - 2',347906,688,13),
('Terrace - 3',347906,689,14);





DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 347906;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 347906;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',347906,556,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,557,2,'{"scene": {"view": {"hlookat": "2.187","vlookat" : "-12.298","fov" : "139.951"}}}',null,True,True,1),
(0,'thumb.jpg',347906,558,2,'{"scene": {"view": {"hlookat": "720.064","vlookat" : "14.978","fov" : "137.394"}}}',null,False,False,null),
(0,'thumb.jpg',347906,1,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,15,2,'{"scene": {"view": {"hlookat": "163.752","vlookat" : "6.467","fov" : "136.045"}}}',null,False,False,null),
(0,'thumb.jpg',347906,16,2,'{"scene": {"view": {"hlookat": "348.469","vlookat" : "9.691","fov" : "139.881"}}}',null,False,False,null),
(0,'thumb.jpg',347906,17,2,'{"scene": {"view": {"hlookat": "-314.333","vlookat" : "9.994","fov" : "135.731"}}}',null,False,False,null),
(0,'thumb.jpg',347906,18,2,'{"scene": {"view": {"hlookat": "-175.182","vlookat" : "8.232","fov" : "139.869"}}}',null,False,False,null),
(0,'thumb.jpg',347906,19,2,'{"scene": {"view": {"hlookat": "240.579","vlookat" : "5.521","fov" : "139.991"}}}',null,True,True,2),
(0,'thumb.jpg',347906,20,2,'{"scene": {"view": {"hlookat": "-188.993","vlookat" : "16.787","fov" : "139.813"}}}',null,False,False,null),
(0,'thumb.jpg',347906,90,2,'{"scene": {"view": {"hlookat": "549.011","vlookat" : "25.122","fov" : "139.972"}}}',null,False,False,null),
(0,'thumb.jpg',347906,91,2,'{"scene": {"view": {"hlookat": "47.534","vlookat" : "11.639","fov" : "135.078"}}}',null,False,False,null),
(0,'thumb.jpg',347906,338,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,339,2,'{"scene": {"view": {"hlookat": "59.187","vlookat" : "3.408","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,6,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,25,2,'{"scene": {"view": {"hlookat": "-11.96","vlookat" : "27.081","fov" : "139.002"}}}',null,True,True,3),
(0,'thumb.jpg',347906,26,2,'{"scene": {"view": {"hlookat": "-459.542","vlookat" : "11.436","fov" : "138.534"}}}',null,False,False,null),
(0,'thumb.jpg',347906,27,2,'{"scene": {"view": {"hlookat": "158.913","vlookat" : "4.622","fov" : "138.879"}}}',null,False,False,null),
(0,'thumb.jpg',347906,602,2,'{"scene": {"view": {"hlookat": "-183.656","vlookat" : "0.869","fov" : "139.324"}}}',null,False,False,null),
(0,'thumb.jpg',347906,603,2,'{"scene": {"view": {"hlookat": "-358.102","vlookat" : "-45.4","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,94,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,95,2,'{"scene": {"view": {"hlookat": "-20.712","vlookat" : "5.607","fov" : "134.347"}}}',null,False,False,null),
(0,'thumb.jpg',347906,96,2,'{"scene": {"view": {"hlookat": "-566.3","vlookat" : "6.35","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',347906,97,2,'{"scene": {"view": {"hlookat": "-185.057","vlookat" : "32.469","fov" : "139.977"}}}',null,True,True,4),
(0,'thumb.jpg',347906,98,2,'{"scene": {"view": {"hlookat": "-80.718","vlookat" : "-1.751","fov" : "139.781"}}}',null,False,False,null),
(0,'thumb.jpg',347906,435,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,436,2,'{"scene": {"view": {"hlookat": "45.068","vlookat" : "9.221","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',347906,437,2,'{"scene": {"view": {"hlookat": "153.648","vlookat" : "7.337","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,131,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,132,2,'{"scene": {"view": {"hlookat": "-5.533","vlookat" : "33.615","fov" : "139.171"}}}',null,False,False,null),
(0,'thumb.jpg',347906,252,2,'{"scene": {"view": {"hlookat": "-49.569","vlookat" : "19.5","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',347906,4,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,101,2,'{"scene": {"view": {"hlookat": "566.288","vlookat" : "-0.373","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,102,2,'{"scene": {"view": {"hlookat": "-492.262","vlookat" : "1.938","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,103,2,'{"scene": {"view": {"hlookat": "111.39","vlookat" : "14.937","fov" : "140"}}}',null,True,True,5),
(0,'thumb.jpg',347906,104,2,'{"scene": {"view": {"hlookat": "108.817","vlookat" : "14.423","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,105,2,'{"scene": {"view": {"hlookat": "-103.142","vlookat" : "3.447","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,106,2,'{"scene": {"view": {"hlookat": "336.404","vlookat" : "16.087","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,211,2,'{"scene": {"view": {"hlookat": "-0.937","vlookat" : "0.474","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,671,2,'{"scene": {"view": {"hlookat": "-1.64","vlookat" : "-5.247","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,273,2,'{"scene": {"view": {"hlookat": "169.736","vlookat" : "28.317","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,274,2,'{"scene": {"view": {"hlookat": "182.379","vlookat" : "4.741","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,2,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,21,2,'{"scene": {"view": {"hlookat": "-180.631","vlookat" : "10.941","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,92,2,'{"scene": {"view": {"hlookat": "-177.135","vlookat" : "21.076","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,186,2,'{"scene": {"view": {"hlookat": "127.788","vlookat" : "9.353","fov" : "139.998"}}}',null,False,False,null),
(0,'thumb.jpg',347906,187,2,'{"scene": {"view": {"hlookat": "-61.695","vlookat" : "0.879","fov" : "139.982"}}}',null,False,False,null),
(0,'thumb.jpg',347906,658,2,'{"scene": {"view": {"hlookat": "3.179","vlookat" : "-5.701","fov" : "139.925"}}}',null,False,False,null),
(0,'thumb.jpg',347906,659,2,'{"scene": {"view": {"hlookat": "349.857","vlookat" : "2.677","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',347906,660,2,'{"scene": {"view": {"hlookat": "-551.804","vlookat" : "-1.689","fov" : "139.072"}}}',null,False,False,null),
(0,'thumb.jpg',347906,661,2,'{"scene": {"view": {"hlookat": "19.711","vlookat" : "-0.538","fov" : "139.951"}}}',null,False,False,null),
(0,'thumb.jpg',347906,427,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,428,2,'{"scene": {"view": {"hlookat": "0.865","vlookat" : "30.6","fov" : "139.499"}}}',null,False,False,null),
(0,'thumb.jpg',347906,429,2,'{"scene": {"view": {"hlookat": "-59.507","vlookat" : "28.669","fov" : "139.994"}}}',null,False,False,null),
(0,'thumb.jpg',347906,430,2,'{"scene": {"view": {"hlookat": "-162.465","vlookat" : "28.2","fov" : "139.628"}}}',null,False,False,null),
(0,'thumb.jpg',347906,705,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,706,2,'{"scene": {"view": {"hlookat": "-6.545","vlookat" : "30.658","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',347906,707,2,'{"scene": {"view": {"hlookat": "-361.768","vlookat" : "37.257","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,212,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,213,2,'{"scene": {"view": {"hlookat": "-383.08","vlookat" : "18.141","fov" : "139.934"}}}',null,True,True,8),
(0,'thumb.jpg',347906,214,2,'{"scene": {"view": {"hlookat": "-181.265","vlookat" : "29.494","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,217,2,'{"scene": {"view": {"hlookat": "-330.917","vlookat" : "40.167","fov" : "138.55"}}}',null,False,False,null),
(0,'thumb.jpg',347906,605,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,606,2,'{"scene": {"view": {"hlookat": "-185.305","vlookat" : "15.339","fov" : "139.966"}}}',null,False,False,null),
(0,'thumb.jpg',347906,607,2,'{"scene": {"view": {"hlookat": "-738.877","vlookat" : "15.006","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,663,2,'{"scene": {"view": {"hlookat": "359.553","vlookat" : "23.803","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,664,2,'{"scene": {"view": {"hlookat": "-115.17","vlookat" : "4.086","fov" : "139.925"}}}',null,False,False,null),
(0,'thumb.jpg',347906,710,2,'{"scene": {"view": {"hlookat": "-399.167","vlookat" : "15.178","fov" : "139.999"}}}',null,True,True,9),
(0,'thumb.jpg',347906,711,2,'{"scene": {"view": {"hlookat": "-361.111","vlookat" : "15.356","fov" : "138.079"}}}',null,False,False,null),
(0,'thumb.jpg',347906,608,2,'{"scene": {"view": {"hlookat": "-387.188","vlookat" : "18.303","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,10,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,48,2,'{"scene": {"view": {"hlookat": "261.644","vlookat" : "13.465","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',347906,49,2,'{"scene": {"view": {"hlookat": "-104.189","vlookat" : "26.191","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,336,2,'{"scene": {"view": {"hlookat": "260.83","vlookat" : "10.197","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,47,2,'{"scene": {"view": {"hlookat": "-110.746","vlookat" : "26.264","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,50,2,'{"scene": {"view": {"hlookat": "-460.839","vlookat" : "17.977","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,51,2,'{"scene": {"view": {"hlookat": "31.512","vlookat" : "16.171","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,337,2,'{"scene": {"view": {"hlookat": "-728.512","vlookat" : "13.333","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,665,2,'{"scene": {"view": {"hlookat": "224.294","vlookat" : "13.283","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,335,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,625,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "139.741"}}}',null,False,False,null),
(0,'thumb.jpg',347906,356,2,'{"scene": {"view": {"hlookat": "373.282","vlookat" : "29.448","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,359,2,'{"scene": {"view": {"hlookat": "-146.692","vlookat" : "6.774","fov" : "138.542"}}}',null,True,True,11),
(0,'thumb.jpg',347906,361,2,'{"scene": {"view": {"hlookat": "-171.149","vlookat" : "21.694","fov" : "139.813"}}}',null,False,False,null),
(0,'thumb.jpg',347906,362,2,'{"scene": {"view": {"hlookat": "-404.809","vlookat" : "16.589","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,364,2,'{"scene": {"view": {"hlookat": "92.765","vlookat" : "16.002","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,363,2,'{"scene": {"view": {"hlookat": "30.609","vlookat" : "15.575","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,389,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,396,2,'{"scene": {"view": {"hlookat": "322.644","vlookat" : "19.565","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,394,2,'{"scene": {"view": {"hlookat": "322.502","vlookat" : "10.916","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,395,2,'{"scene": {"view": {"hlookat": "732.024","vlookat" : "30.879","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,397,2,'{"scene": {"view": {"hlookat": "-336.846","vlookat" : "30.651","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,667,2,'{"scene": {"view": {"hlookat": "31.273","vlookat" : "23.282","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,398,2,'{"scene": {"view": {"hlookat": "30.691","vlookat" : "21.634","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,668,2,'{"scene": {"view": {"hlookat": "-183.223","vlookat" : "10.164","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,669,2,'{"scene": {"view": {"hlookat": "179.805","vlookat" : "14.939","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,670,2,'{"scene": {"view": {"hlookat": "24.479","vlookat" : "6.504","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,700,2,'{"scene": {"view": {"hlookat": "0.617","vlookat" : "25.422","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,701,2,'{"scene": {"view": {"hlookat": "105.451","vlookat" : "5.502","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,702,2,'{"scene": {"view": {"hlookat": "673.524","vlookat" : "21.102","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,703,2,'{"scene": {"view": {"hlookat": "-91.624","vlookat" : "12.599","fov" : "140"}}}',null,True,True,12),
(0,'thumb.jpg',347906,11,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,52,2,'{"scene": {"view": {"hlookat": "-191.441","vlookat" : "17.703","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,53,2,'{"scene": {"view": {"hlookat": "-334.813","vlookat" : "17.983","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,54,2,'{"scene": {"view": {"hlookat": "125.082","vlookat" : "27.599","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,55,2,'{"scene": {"view": {"hlookat": "345.134","vlookat" : "23.605","fov" : "139.999"}}}',null,True,True,13),
(0,'thumb.jpg',347906,56,2,'{"scene": {"view": {"hlookat": "-423.067","vlookat" : "13.766","fov" : "138.779"}}}',null,False,False,null),
(0,'thumb.jpg',347906,58,2,'{"scene": {"view": {"hlookat": "-354.523","vlookat" : "10.911","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,57,2,'{"scene": {"view": {"hlookat": "-332.379","vlookat" : "23.908","fov" : "139.991"}}}',null,False,False,null),
(0,'thumb.jpg',347906,59,2,'{"scene": {"view": {"hlookat": "26.077","vlookat" : "18.885","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,675,2,'{"scene": {"view": {"hlookat": "","vlookat" : "","fov" : ""}}}',null,False,False,null),
(0,'thumb.jpg',347906,676,2,'{"scene": {"view": {"hlookat": "22.471","vlookat" : "14.998","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,712,2,'{"scene": {"view": {"hlookat": "-134.152","vlookat" : "19.132","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,677,2,'{"scene": {"view": {"hlookat": "20.378","vlookat" : "20.625","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,678,2,'{"scene": {"view": {"hlookat": "9.495","vlookat" : "10.549","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',347906,679,2,'{"scene": {"view": {"hlookat": "-605.776","vlookat" : "14.172","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,680,2,'{"scene": {"view": {"hlookat": "234.334","vlookat" : "12.213","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,682,2,'{"scene": {"view": {"hlookat": "161.637","vlookat" : "16.37","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,683,2,'{"scene": {"view": {"hlookat": "-477.645","vlookat" : "7.392","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,684,2,'{"scene": {"view": {"hlookat": "310.112","vlookat" : "8.691","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,685,2,'{"scene": {"view": {"hlookat": "29.168","vlookat" : "18.597","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,686,2,'{"scene": {"view": {"hlookat": "-12.68","vlookat" : "27.557","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,687,2,'{"scene": {"view": {"hlookat": "1482.62","vlookat" : "28.338","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,688,2,'{"scene": {"view": {"hlookat": "-247.943","vlookat" : "7.552","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',347906,689,2,'{"scene": {"view": {"hlookat": "334.12","vlookat" : "8.29","fov" : "138.074"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 347906
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 347906;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 347906);


