# This query will return the id, name and the old logo path of those hotels that have a logo
# IMPORTANT: Save the results of this query since the next one will update the logo field
SELECT id, name, CONCAT("media/360-photos/hotels/", lower(country_code), "/", id, "/", logo) as old_logo_path
FROM cms_hotel
WHERE logo IS NOT NULL;


SELECT "IMPORTANT: Save the results of the above query first since the below will update the logo field";

>>>>>> Added on purpose to break the SQL execution of the below query before saving the results of the first query <<<<<<


# This query will update the logo field of hotels with the new logo filename (id.png)
UPDATE cms_hotel SET logo = CONCAT(id, ".png") WHERE logo IS NOT NULL;