# REFERENCES:
# LINK: Timezone, Latitude and Longitude: https://worldtime.io/
# DB TABLES: states, timezone, webgeocities

# THINGS TO NOTE:
# Independent State: Monaco: https://en.wikipedia.org/wiki/Monaco#Geography
# New Zeland State: https://www.newzealand.com/int/northland/


# LAST INSERTED ID: 2482126


/* INSERT QUERY */
INSERT INTO `webgeocities`(`country_code`, `state_code`, `accent`, `name`, `latitude`, `longitude`, `timezoneid`, `order_display`, `from_source`, `popularity`) VALUES
('NZ', 'F6', 'Bay of Islands', 'Bay of Islands',-35.181293, 174.199209, 'Pacific/Auckland', 0, 'new', 1),
('NZ', 'G1', 'Waitomo', 'Waitomo',-38.260916, 175.112533, 'Pacific/Auckland', 0, 'new', 1),
('NZ', 'E7', 'Waiheke Island', 'Waiheke Island', -36.797886, 175.112791, 'Pacific/Auckland', 0, 'new', 1),
('CH', 'LU', 'Lucerne', 'Lucerne', 47.045656, 8.308236, 'Europe/Zurich', 0, 'new', 1),
('DE', 07, 'Cologne', 'Cologne', 50.95057, 6.973333, 'Europe/Berlin', 0, 'new', 1),
('ES', 51, 'Costa del Sol', 'Costa del Sol', 36.4470411,-5.1302181, 'Europe/Madrid', 0, 'new', 1),
('FR', 'NULL', 'Monaco', 'Monaco', 43.8306245, 0.655407, 'Europe/Paris', 0, 'new',1),
('IT', 08, 'Cinque Terre', 'Cinque Terre', 44.1736962, 9.6332821, 'Europe/Rome', 0, 'new', 1),
('US', 'HI', 'Oahu', 'Oahu', 21.4389123, -158.00005, 'Pacific/Honolulu', 0, 'new', 1);

# this is for the new City discovery API = 5
# updating remaining cities
# number of rows affected: 9

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