# Add the hotel ID for unique index considerations
ALTER TABLE `amadeus_hotel_image` DROP INDEX `U_HOTEL_IMAGE_HOTELDPI_IMCAT_FILE`, ADD UNIQUE INDEX `U_HOTEL_IMAGE_HOTELDPI_IMCAT_FILE` (`filename` ASC, `dupe_pool_id` ASC, `location` ASC, `hotel_division_id` ASC, `hotel_id` ASC);
