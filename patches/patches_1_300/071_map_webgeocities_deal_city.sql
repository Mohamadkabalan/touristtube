# World Airport Transfer API (id=4): 0 Results
# Touristtube API (id=2): 0 Results

# New version City discovery API = 5
# number of rows affected: 37

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         dc.country_code = wc.country_code
         AND dc.city_name LIKE wc.name
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.deal_api_supplier_id = 5;

# OLD City discovery API = 3
# number of rows affected: 528

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         dc.country_code = wc.country_code
         AND dc.city_name LIKE wc.name
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.deal_api_supplier_id = 3;


# AmericanTours International(ATI) API = 1
# number of rows affected: 1367

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         dc.country_code = wc.country_code
         AND dc.city_name LIKE wc.name
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.deal_api_supplier_id = 1;



# Updating each cities
# Cant make use of one SQL since conditions vary for each cities
# So I checked each remaining cities and research on their respective geography

# updating Langkawi MY
# deal_cityId = 5296
# web_geo_city_id = 51312
# number of rows affected = 1
UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'MY'
         AND wc.name LIKE '%Langkawi%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'MY'
   AND dc.city_name LIKE 'Langkawi'
   AND dc.deal_api_supplier_id = 5;


# updating Penang MY
# deal_cityId = 5298
# web_geo_city_id = 62132
# number of rows affected = 1
UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'MY'
         AND wc.name LIKE '%Penang%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'MY'
   AND dc.city_name LIKE 'Penang'
   AND dc.deal_api_supplier_id = 5;

# updating Tagbilaran PH
# deal_cityId = 5304
# web_geo_city_id = 251702
# number of rows affected = 1
UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'PH'
         AND wc.name LIKE '%Tagbilaran%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'PH'
   AND dc.city_name LIKE 'Tagbilaran'
   AND dc.deal_api_supplier_id = 5;


# REFERENCE LINK: https://en.wikipedia.org/wiki/Ko_Samui
# updating Samui TH
# deal_cityId = 5308
# web_geo_city_id = 689866
# number of rows affected = 1
UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'TH'
         AND wc.name LIKE 'Ko Samui'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'TH'
   AND dc.city_name LIKE 'Samui'
   AND dc.deal_api_supplier_id = 5;

# updating ChiangMai TH
# deal_cityId = 5310
# web_geo_city_id = 711719
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'TH'
         AND wc.name LIKE '%ChiangMai%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'TH'
   AND dc.city_name LIKE 'ChiangMai'
   AND dc.deal_api_supplier_id = 5;


# updating ChiangRai TH
# deal_cityId = 5315
# web_geo_city_id = 736766
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'TH'
         AND wc.name LIKE '%ChiangRai%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'TH'
   AND dc.city_name LIKE 'ChiangRai'
   AND dc.deal_api_supplier_id = 5;


# updating Ho Chi Minh VN
# deal_cityId = 5318
# web_geo_city_id = 988452
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'VN'
         AND wc.name LIKE '%Ho Chi Minh%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'VN'
   AND dc.city_name LIKE 'Ho Chi Minh'
   AND dc.deal_api_supplier_id = 5;


# updating Hai Phong VN
# deal_cityId = 5322
# web_geo_city_id = 988924
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'VN'
         AND wc.name LIKE '%Hai Phong%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'VN'
   AND dc.city_name LIKE 'Hai Phong'
   AND dc.deal_api_supplier_id = 5;

# Reference link: https://en.wikipedia.org/wiki/Alice_Springs
# updating Alice Springs AU
# deal_cityId = 5324
# web_geo_city_id = 1135777
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'AU'
         AND wc.name LIKE 'Alice'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'AU'
   AND dc.city_name LIKE 'Alice Springs'
   AND dc.deal_api_supplier_id = 5;


# updating Whitsunday Island AU
# deal_cityId = 5333
# web_geo_city_id = 1136061
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'AU'
         AND wc.name LIKE 'Whitsunday'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'AU'
   AND dc.city_name LIKE 'Whitsunday Island'
   AND dc.deal_api_supplier_id = 5;


# updating Munich DE
# deal_cityId = 5355
# web_geo_city_id = 1631679
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'DE'
         AND wc.name LIKE '%Munich%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'DE'
   AND dc.city_name LIKE 'Munich'
   AND dc.deal_api_supplier_id = 5;

# updating Majorca ES
# deal_cityId = 5370
# web_geo_city_id = 1742947
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'ES'
         AND wc.name LIKE '%Majorca%'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'ES'
   AND dc.city_name LIKE 'Majorca'
   AND dc.deal_api_supplier_id = 5;

# Reference Link: https://en.wikipedia.org/wiki/Galway
# updating Galway IE
# deal_cityId = 5404
# web_geo_city_id = 2086779
# number of rows affected = 1

UPDATE
   deal_city dc
SET
   dc.city_id =
   (
      SELECT
         wc.id
      FROM
         webgeocities wc
      WHERE
         wc.country_code = 'IE'
         AND wc.name LIKE 'Galway City'
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'IE'
   AND dc.city_name LIKE 'Galway'
   AND dc.deal_api_supplier_id = 5;