UPDATE deal_city dc 
SET dc.city_id = (SELECT wc.id FROM webgeocities wc 
     WHERE dc.country_code = wc.country_code
      AND dc.city_name like wc.name
     GROUP BY CONCAT(wc.country_code, "-", wc.name)
                    HAVING COUNT(wc.id) = 1
                )
WHERE dc.city_id IS NULL
AND dc.deal_api_supplier_id = 5
;

update deal_city set city_id = 1384846 where country_code = 'CN' AND city_code = 'Beijing' AND city_name = 'Beijing' and deal_api_supplier_id = 5;
update deal_city set city_id = 1370934 where country_code = 'CN' AND city_code = 'Shanghai' AND city_name = 'Shanghai' and deal_api_supplier_id = 5;
update deal_city set city_id = 1379679 where country_code = 'CN' AND city_code = 'Hangzhou' AND city_name = 'Hangzhou' and deal_api_supplier_id = 5;



