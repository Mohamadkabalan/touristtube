DELETE FROM amadeus_hotel_image where hotel_id = 43067 AND tt_media_type_id = 2;
DELETE FROM cms_hotel_image where hotel_id = 43067 AND tt_media_type_id = 2;


# INSERT INTO `hotel_to_hotel_divisions`
# (`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
# VALUES
# ('4',43067,945,4),
# ('5',43067,946,5),
# ('6',43067,986,6),
# ('7',43067,987,7),
# ('8',43067,988,8);



INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 43067;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',43067,556,2,null,null,False,False,null),
(0,'thumb.jpg',43067,558,2,null,null,True,True,1),
(0,'thumb.jpg',43067,557,2,'{"scene": {"view": {"hlookat": "357.437","vlookat" : "-1.169","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,1,2,null,null,False,False,null),
(0,'thumb.jpg',43067,15,2,'{"scene": {"view": {"hlookat": "-253.232","vlookat" : "-0.163","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,16,2,'{"scene": {"view": {"hlookat": "-181.161","vlookat" : "6.24","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,17,2,'{"scene": {"view": {"hlookat": "0.833","vlookat" : "-6.721","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,18,2,'{"scene": {"view": {"hlookat": "-295.85","vlookat" : "0.285","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,6,2,null,null,False,False,null),
(0,'thumb.jpg',43067,25,2,'{"scene": {"view": {"hlookat": "321.497","vlookat" : "0.021","fov" : "132.959"}}}',null,False,False,null),
(0,'thumb.jpg',43067,26,2,'{"scene": {"view": {"hlookat": "-79.026","vlookat" : "0.267","fov" : "137.23"}}}',null,False,False,null),
(0,'thumb.jpg',43067,28,2,null,null,False,False,null),
(0,'thumb.jpg',43067,29,2,'{"scene": {"view": {"hlookat": "-547.047","vlookat" : "0.03","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,30,2,'{"scene": {"view": {"hlookat": "-176.864","vlookat" : "-17.249","fov" : "126.319"}}}',null,False,False,null),
(0,'thumb.jpg',43067,31,2,'{"scene": {"view": {"hlookat": "53.006","vlookat" : "2.116","fov" : "135.826"}}}',null,False,False,null),
(0,'thumb.jpg',43067,32,2,'{"scene": {"view": {"hlookat": "49.168","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,167,2,null,null,False,False,null),
(0,'thumb.jpg',43067,168,2,'{"scene": {"view": {"hlookat": "361.079","vlookat" : "-2.764","fov" : "120.205"}}}',null,False,False,null),
(0,'thumb.jpg',43067,169,2,'{"scene": {"view": {"hlookat": "-102.289","vlookat" : "0.002","fov" : "121.505"}}}',null,False,False,null),
(0,'thumb.jpg',43067,170,2,'{"scene": {"view": {"hlookat": "458.127","vlookat" : "-3.948","fov" : "135.072"}}}',null,True,True,2),
(0,'thumb.jpg',43067,173,2,null,null,False,False,null),
(0,'thumb.jpg',43067,174,2,'{"scene": {"view": {"hlookat": "4.39","vlookat" : "-0.069","fov" : "125.964"}}}',null,True,True,3),
(0,'thumb.jpg',43067,175,2,'{"scene": {"view": {"hlookat": "4.233","vlookat" : "-4.853","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,941,2,null,null,False,False,null),
(0,'thumb.jpg',43067,942,2,'{"scene": {"view": {"hlookat": "46.395","vlookat" : "2.623","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,943,2,'{"scene": {"view": {"hlookat": "18.211","vlookat" : "-0.975","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,944,2,'{"scene": {"view": {"hlookat": "-76.244","vlookat" : "-0.485","fov" : "136.717"}}}',null,False,False,null),
(0,'thumb.jpg',43067,945,2,'{"scene": {"view": {"hlookat": "215.136","vlookat" : "6.563","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,946,2,'{"scene": {"view": {"hlookat": "642.004","vlookat" : "14.751","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,986,2,'{"scene": {"view": {"hlookat": "62.483","vlookat" : "11.314","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,987,2,'{"scene": {"view": {"hlookat": "-321.866","vlookat" : "11.853","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,988,2,'{"scene": {"view": {"hlookat": "-325.705","vlookat" : "1.701","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,4,2,null,null,False,False,null),
(0,'thumb.jpg',43067,101,2,'{"scene": {"view": {"hlookat": "-16.077","vlookat" : "-0.001","fov" : "120"}}}',null,True,True,4),
(0,'thumb.jpg',43067,102,2,'{"scene": {"view": {"hlookat": "-357.342","vlookat" : "26.305","fov" : "130"}}}',null,False,False,null),
(0,'thumb.jpg',43067,103,2,'{"scene": {"view": {"hlookat": "-6.835","vlookat" : "29.939","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,104,2,'{"scene": {"view": {"hlookat": "4.126","vlookat" : "27.997","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,105,2,'{"scene": {"view": {"hlookat": "-48.365","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,106,2,'{"scene": {"view": {"hlookat": "-150.126","vlookat" : "0.406","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,671,2,'{"scene": {"view": {"hlookat": "151.649","vlookat" : "3.218","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,672,2,'{"scene": {"view": {"hlookat": "156.936","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,673,2,'{"scene": {"view": {"hlookat": "-17.667","vlookat" : "-0.119","fov" : "136.516"}}}',null,False,False,null),
(0,'thumb.jpg',43067,674,2,'{"scene": {"view": {"hlookat": "-74.117","vlookat" : "-0.499","fov" : "133.153"}}}',null,False,False,null),
(0,'thumb.jpg',43067,690,2,'{"scene": {"view": {"hlookat": "-135.286","vlookat" : "10.663","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,691,2,'{"scene": {"view": {"hlookat": "82.44","vlookat" : "2.505","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,277,2,null,null,False,False,null),
(0,'thumb.jpg',43067,279,2,'{"scene": {"view": {"hlookat": "136.963","vlookat" : "23.028","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,294,2,null,null,False,False,null),
(0,'thumb.jpg',43067,303,2,'{"scene": {"view": {"hlookat": "359.468","vlookat" : "-9.37","fov" : "126.389"}}}',null,True,True,5),
(0,'thumb.jpg',43067,304,2,'{"scene": {"view": {"hlookat": "-364.483","vlookat" : "-7.235","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,305,2,'{"scene": {"view": {"hlookat": "-5.517","vlookat" : "9.903","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,131,2,null,null,False,False,null),
(0,'thumb.jpg',43067,132,2,'{"scene": {"view": {"hlookat": "36.971","vlookat" : "9.413","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,252,2,'{"scene": {"view": {"hlookat": "-184.067","vlookat" : "2.527","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,253,2,'{"scene": {"view": {"hlookat": "2.001","vlookat" : "0.164","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,295,2,null,null,False,False,null),
(0,'thumb.jpg',43067,309,2,'{"scene": {"view": {"hlookat": "-1.958","vlookat" : "12.289","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,296,2,null,null,False,False,null),
(0,'thumb.jpg',43067,315,2,'{"scene": {"view": {"hlookat": "-34.366","vlookat" : "5.574","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,316,2,'{"scene": {"view": {"hlookat": "180.068","vlookat" : "26.038","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,329,2,null,null,False,False,null),
(0,'thumb.jpg',43067,330,2,'{"scene": {"view": {"hlookat": "-47.754","vlookat" : "10.356","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,808,2,null,null,False,False,null),
(0,'thumb.jpg',43067,809,2,'{"scene": {"view": {"hlookat": "-15.113","vlookat" : "12.876","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,293,2,null,null,False,False,null),
(0,'thumb.jpg',43067,297,2,'{"scene": {"view": {"hlookat": "-344.03","vlookat" : "-0.529","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,814,2,null,null,False,False,null),
(0,'thumb.jpg',43067,815,2,'{"scene": {"view": {"hlookat": "-33.867","vlookat" : "10.156","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,239,2,null,null,False,False,null),
(0,'thumb.jpg',43067,240,2,'{"scene": {"view": {"hlookat": "-179.099","vlookat" : "5.572","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,36,2,null,null,False,False,null),
(0,'thumb.jpg',43067,37,2,'{"scene": {"view": {"hlookat": "359.602","vlookat" : "0","fov" : "140"}}}',null,True,True,6),
(0,'thumb.jpg',43067,38,2,'{"scene": {"view": {"hlookat": "719.61","vlookat" : "-0.445","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,39,2,'{"scene": {"view": {"hlookat": "176.758","vlookat" : "5.109","fov" : "122.164"}}}',null,False,False,null),
(0,'thumb.jpg',43067,486,2,null,null,False,False,null),
(0,'thumb.jpg',43067,487,2,'{"scene": {"view": {"hlookat": "0.338","vlookat" : "1.138","fov" : "140"}}}',null,True,True,7),
(0,'thumb.jpg',43067,488,2,'{"scene": {"view": {"hlookat": "0.148","vlookat" : "11.927","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,489,2,'{"scene": {"view": {"hlookat": "-0.178","vlookat" : "13.016","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,139,2,null,null,False,False,null),
(0,'thumb.jpg',43067,140,2,'{"scene": {"view": {"hlookat": "7.154","vlookat" : "10.751","fov" : "120"}}}',null,True,True,8),
(0,'thumb.jpg',43067,143,2,'{"scene": {"view": {"hlookat": "181.295","vlookat" : "13.651","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,568,2,null,null,False,False,null),
(0,'thumb.jpg',43067,569,2,'{"scene": {"view": {"hlookat": "-20.771","vlookat" : "4.112","fov" : "131.014"}}}',null,True,True,9),
(0,'thumb.jpg',43067,570,2,'{"scene": {"view": {"hlookat": "19.192","vlookat" : "7.375","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,572,2,'{"scene": {"view": {"hlookat": "0.272","vlookat" : "14.562","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,212,2,null,null,False,False,null),
(0,'thumb.jpg',43067,213,2,'{"scene": {"view": {"hlookat": "183.438","vlookat" : "4.433","fov" : "140"}}}',null,True,True,10),
(0,'thumb.jpg',43067,214,2,'{"scene": {"view": {"hlookat": "16.575","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,215,2,'{"scene": {"view": {"hlookat": "-7.603","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,216,2,'{"scene": {"view": {"hlookat": "174.072","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,217,2,'{"scene": {"view": {"hlookat": "1.699","vlookat" : "14.869","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,967,2,null,null,False,False,null),
(0,'thumb.jpg',43067,968,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,True,True,11),
(0,'thumb.jpg',43067,969,2,'{"scene": {"view": {"hlookat": "232.701","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,971,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,972,2,'{"scene": {"view": {"hlookat": "2.315","vlookat" : "9.183","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,975,2,null,null,False,False,null),
(0,'thumb.jpg',43067,976,2,'{"scene": {"view": {"hlookat": "1.595","vlookat" : "13.847","fov" : "135"}}}',null,True,True,12),
(0,'thumb.jpg',43067,977,2,'{"scene": {"view": {"hlookat": "-386.126","vlookat" : "0.001","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,978,2,'{"scene": {"view": {"hlookat": "5.125","vlookat" : "4.587","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,979,2,'{"scene": {"view": {"hlookat": "-3.341","vlookat" : "29.67","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,980,2,'{"scene": {"view": {"hlookat": "-4.401","vlookat" : "10.212","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,981,2,'{"scene": {"view": {"hlookat": "-450.591","vlookat" : "19.7","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,451,2,null,null,False,False,null),
(0,'thumb.jpg',43067,452,2,'{"scene": {"view": {"hlookat": "782.972","vlookat" : "0.008","fov" : "140"}}}',null,True,True,13),
(0,'thumb.jpg',43067,453,2,'{"scene": {"view": {"hlookat": "-93.249","vlookat" : "0.593","fov" : "137.189"}}}',null,False,False,null),
(0,'thumb.jpg',43067,455,2,'{"scene": {"view": {"hlookat": "-177.313","vlookat" : "0.126","fov" : "137.566"}}}',null,False,False,null),
(0,'thumb.jpg',43067,456,2,'{"scene": {"view": {"hlookat": "-40.976","vlookat" : "1.967","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,457,2,'{"scene": {"view": {"hlookat": "-2.873","vlookat" : "21.703","fov" : "138.628"}}}',null,False,False,null),
(0,'thumb.jpg',43067,458,2,'{"scene": {"view": {"hlookat": "-179.943","vlookat" : "26.341","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,454,2,'{"scene": {"view": {"hlookat": "8.595","vlookat" : "12.025","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,573,2,null,null,False,False,null),
(0,'thumb.jpg',43067,574,2,'{"scene": {"view": {"hlookat": "-90.539","vlookat" : "10.765","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,575,2,'{"scene": {"view": {"hlookat": "0.7","vlookat" : "0.416","fov" : "140"}}}',null,True,True,14),
(0,'thumb.jpg',43067,576,2,'{"scene": {"view": {"hlookat": "-31.767","vlookat" : "9.028","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,577,2,'{"scene": {"view": {"hlookat": "4.418","vlookat" : "18.066","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,389,2,null,null,False,False,null),
(0,'thumb.jpg',43067,394,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,True,True,15),
(0,'thumb.jpg',43067,395,2,'{"scene": {"view": {"hlookat": "0","vlookat" : "0","fov" : "120"}}}',null,False,False,null),
(0,'thumb.jpg',43067,398,2,'{"scene": {"view": {"hlookat": "148.322","vlookat" : "0","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,666,2,'{"scene": {"view": {"hlookat": "-188.546","vlookat" : "15.513","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,470,2,null,null,False,False,null),
(0,'thumb.jpg',43067,471,2,'{"scene": {"view": {"hlookat": "0.376","vlookat" : "19.31","fov" : "140"}}}',null,True,True,16),
(0,'thumb.jpg',43067,472,2,'{"scene": {"view": {"hlookat": "-128.195","vlookat" : "8.314","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,473,2,'{"scene": {"view": {"hlookat": "182.67","vlookat" : "25.081","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,474,2,'{"scene": {"view": {"hlookat": "28.977","vlookat" : "9.832","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,475,2,'{"scene": {"view": {"hlookat": "77.137","vlookat" : "8.21","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,500,2,'{"scene": {"view": {"hlookat": "-22.133","vlookat" : "12.954","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,675,2,null,null,False,False,null),
(0,'thumb.jpg',43067,676,2,'{"scene": {"view": {"hlookat": "174.058","vlookat" : "0.005","fov" : "140"}}}',null,True,True,17),
(0,'thumb.jpg',43067,677,2,'{"scene": {"view": {"hlookat": "-23.259","vlookat" : "11.955","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,678,2,'{"scene": {"view": {"hlookat": "-12.79","vlookat" : "11.806","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,679,2,'{"scene": {"view": {"hlookat": "-221.778","vlookat" : "0.238","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,680,2,'{"scene": {"view": {"hlookat": "-177.923","vlookat" : "25.189","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,681,2,'{"scene": {"view": {"hlookat": "-204.201","vlookat" : "-0.094","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,682,2,'{"scene": {"view": {"hlookat": "1.214","vlookat" : "1.025","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,683,2,'{"scene": {"view": {"hlookat": "-165.405","vlookat" : "5.476","fov" : "140"}}}',null,False,False,null),
(0,'thumb.jpg',43067,684,2,'{"scene": {"view": {"hlookat": "52.495","vlookat" : "23.942","fov" : "140"}}}',null,False,False,null);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 43067
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 43067;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 43067);


