# Deativate all source_ids that are present inside Total_Inventory_toRemove.csv
UPDATE cms_hotel_source chs 
INNER JOIN hrs_total_inventory_to_remove htitr ON (chs.source_id = htitr.source_id) 
SET chs.is_active = 0 
WHERE chs.source = 'hrs';


# Deactivate all source_ids that are not present anymore in Total_Inventory.csv
UPDATE cms_hotel_source chs 
LEFT JOIN hrs_total_inventory inv ON (inv.source_id = chs.source_id) 
SET chs.is_active = 0 
WHERE chs.source = 'hrs' AND inv.source_id IS NULL;


# Activate all hotels that are re-added(if any) in Total_Inventory.csv
UPDATE cms_hotel_source chs 
INNER JOIN hrs_total_inventory inv ON (chs.source_id = inv.source_id) 
SET chs.is_active = 1 
WHERE chs.source = 'hrs';


# Activate all hotels with 360 regardless of their presence in Total_Inventory.csv or Total_Inventory_toRemove.csv
UPDATE cms_hotel_source chs 
INNER JOIN cms_hotel h ON (h.id = chs.hotel_id AND h.has_360) 
SET chs.is_active = 1;