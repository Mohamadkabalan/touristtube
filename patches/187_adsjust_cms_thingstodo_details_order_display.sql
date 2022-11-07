
UPDATE cms_thingstodo_details SET order_display = 1 WHERE LENGTH(description) > 0 AND order_display = 0;

UPDATE cms_thingstodo_details SET order_display = 0 WHERE LENGTH(description) = 0;
