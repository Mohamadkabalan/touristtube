# changes for deal_booking table:
ALTER TABLE  `deal_booking` ADD  `deal_booking_quote_id` int(11) DEFAULT NULL AFTER  `departure_time`;
ALTER TABLE  `deal_booking` ADD  `account_currency_amount` decimal(20,5) NOT NULL DEFAULT '0.00000' AFTER `amount_acc_currency`;
ALTER TABLE `deal_booking` CHANGE `amount_acc_currency` `amount_acc_currency` decimal(20,5) NOT NULL DEFAULT '0.00000';

# changes for deal_city table:
ALTER TABLE  `deal_city` CHANGE  `id`  `id` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE  `deal_city` CHANGE  `country_code`  `country_code` varchar(3) DEFAULT NULL;
ALTER TABLE  `deal_city` CHANGE  `city_code`  `city_code` varchar(50) DEFAULT NULL;
ALTER TABLE  `deal_city` CHANGE  `state`  `state` varchar(100) DEFAULT NULL;

# changes for deal_transfer_booking_details table:
ALTER TABLE `deal_transfer_booking_details` CHANGE `arrival_date` `arrival_date` date DEFAULT NULL;
ALTER TABLE `deal_transfer_booking_details` CHANGE `departure_date` `departure_date` date DEFAULT NULL;
ALTER TABLE `deal_transfer_booking_details` CHANGE `arrival_flight_details` `arrival_flight_details` varchar(255) DEFAULT NULL;
ALTER TABLE `deal_transfer_booking_details` CHANGE `arrival_destination_address` `arrival_destination_address` varchar(255) DEFAULT NULL;
ALTER TABLE `deal_transfer_booking_details` CHANGE `departure_flight_details` `departure_flight_details` varchar(255) DEFAULT NULL;
ALTER TABLE `deal_transfer_booking_details` CHANGE `departure_pickup_address` `departure_pickup_address` varchar(255) DEFAULT NULL;
ALTER TABLE `deal_transfer_booking_details` CHANGE `service_type` `service_type` varchar(45) DEFAULT NULL;
ALTER TABLE `deal_transfer_booking_details` CHANGE `car_model` `car_model` varchar(255) DEFAULT NULL;

--
-- Constraints for table `deal_detail_to_category`
--
ALTER TABLE `deal_detail_to_category`
  ADD CONSTRAINT `deal_category_id` FOREIGN KEY (`deal_category_id`) REFERENCES `deal_category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `deal_details_id` FOREIGN KEY (`deal_details_id`) REFERENCES `deal_details` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `deal_image`
--
ALTER TABLE `deal_image`
  ADD CONSTRAINT `deal_image_ibfk_1` FOREIGN KEY (`deal_detail_id`) REFERENCES `deal_details` (`id`);

--
-- Constraints for table `deal_supplier_type_status`
--
ALTER TABLE `deal_supplier_type_status`
  ADD CONSTRAINT `deal_api_supplier_id` FOREIGN KEY (`deal_api_supplier_id`) REFERENCES `deal_api_supplier` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_deal_type_id` FOREIGN KEY (`deal_type_id`) REFERENCES `deal_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;