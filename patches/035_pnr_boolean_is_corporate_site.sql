
ALTER TABLE passenger_name_record ADD is_corporate_site BOOLEAN NOT NULL DEFAULT FALSE AFTER creation_date;
