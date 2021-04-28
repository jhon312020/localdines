ALTER TABLE `food_delivery_orders` CHANGE `phone_no` `phone_no` BIGINT(15) NOT NULL;
ALTER TABLE `food_delivery_orders` ADD `sms_sent_time` DATETIME NOT NULL AFTER `delivered_customer`;
ALTER TABLE `food_delivery_orders` CHANGE `status` `status` ENUM('pending','confirmed','cancelled','delivered') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `food_delivery_orders` ADD `delivered_time` DATETIME NOT NULL AFTER `sms_sent_time`;
ALTER TABLE `food_delivery_orders` ADD `order_id` VARCHAR(20) NULL DEFAULT NULL AFTER `delivered_time`;