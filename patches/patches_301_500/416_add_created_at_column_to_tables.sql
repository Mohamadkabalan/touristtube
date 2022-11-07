ALTER TABLE hotel_divisions ADD created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER sort_order;

ALTER TABLE hotel_divisions_categories ADD created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER hotel_division_category_group_id;

ALTER TABLE hotel_divisions_categories_group ADD created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER sort_order;