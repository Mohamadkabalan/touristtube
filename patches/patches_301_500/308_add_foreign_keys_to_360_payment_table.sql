ALTER TABLE `360_payment` ADD CONSTRAINT `fk_constraint_status` FOREIGN KEY (`status`) REFERENCES `payment_status` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `360_payment` ADD CONSTRAINT `fk_constraint_payment_type` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `360_payment` ADD CONSTRAINT `fk_constraint_payment_provider` FOREIGN KEY (`payment_provider`) REFERENCES `payment_provider` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;