
# No Lat and Long in the response.
# Reference Link: http://latitude.to/articles-by-country/cn/china/999/xian
# updating XIAN, CHINA
# deal_cityId = 5270
# web_geo_city_id = 1367119
# number of rows affected = 1
# DMS COORDINATES: 34°15 60.00" N 108°55 59.99" E

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
         wc.country_code = 'CN'
         AND wc.name LIKE  'Xi''An'
	 AND wc.state_code = 26
      GROUP BY
         CONCAT( wc.country_code, " - ", wc.name )
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'CN'
   AND dc.city_name LIKE 'Xian'
   AND dc.deal_api_supplier_id = 5;

# updating Hiroshima, JP
# This is nearest to the response:
# Lat: 34.397512290567825
# Long: 132.47691843306578
# deal_cityId = 5285
# web_geo_city_id = 2234531
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
         wc.country_code = 'JP'
         AND wc.name LIKE 'Hiroshima-Shi'
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'JP'
   AND dc.city_name LIKE 'Hiroshima'
   AND dc.deal_api_supplier_id = 5;

# Reference Link https://en.wikipedia.org/wiki/Pattaya
# Phatthaya in Thailand
# deal_cityId = 5313
# web_geo_city_id = 702034
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
         AND wc.name LIKE 'Phatthaya'
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'TH'
   AND dc.city_name LIKE 'Pattaya'
   AND dc.deal_api_supplier_id = 5;


# Reference link: https://it.wikipedia.org/wiki/Provincia_di_Firenze
# deal_cityId = 5406
# web_geo_city_id = 2215931
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
         wc.country_code = 'IT'
         AND wc.name LIKE 'Provincia Di Firenze'
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'IT'
   AND dc.city_name LIKE 'Florence'
   AND dc.deal_api_supplier_id = 5;


# Reference link: https://en.wikipedia.org/wiki/Porto
# coordinates	41°9′0″N 8°36′39″W
# deal_cityId = 5420
# web_geo_city_id = 415726
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
         wc.country_code = 'PT'
         AND wc.name LIKE 'Porto'
		 AND wc.state_code = 17
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'PT'
   AND dc.city_name LIKE 'Oporto'
   AND dc.deal_api_supplier_id = 5;


# Reference link: https://en.wikipedia.org/wiki/Niagara_Falls,_Ontario#Geography
# Niagara Falls, Ontario
# deal_cityId = 5429
# web_geo_city_id = 1292499
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
         wc.country_code = 'CA'
         AND wc.name LIKE 'Niagara-On-The-Lake'
		 AND wc.state_code = 08
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'CA'
   AND dc.city_name LIKE 'Niagara Falls'
   AND dc.deal_api_supplier_id = 5;

# Reference link: https://en.wikipedia.org/wiki/Washington,_D.C
# Niagara Washington, D.C.
# deal_cityId = 5442
# web_geo_city_id = 932856
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
         wc.country_code = 'US'
         AND wc.name LIKE 'Washington'
		 AND wc.state_code LIKE 'WA'
   )
WHERE
   (
      dc.city_id IS NULL
      OR dc.city_id = 0
   )
   AND dc.country_code = 'US'
   AND dc.city_name LIKE 'Washington DC'
   AND dc.deal_api_supplier_id = 5;