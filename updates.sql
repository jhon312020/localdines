ALTER TABLE `food_delivery_orders` CHANGE `phone_no` `phone_no` BIGINT(15) NOT NULL;
ALTER TABLE `food_delivery_orders` ADD `sms_sent_time` DATETIME NOT NULL AFTER `delivered_customer`;
ALTER TABLE `food_delivery_orders` CHANGE `status` `status` ENUM('pending','confirmed','cancelled','delivered') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `food_delivery_orders` ADD `delivered_time` DATETIME NOT NULL AFTER `sms_sent_time`;
ALTER TABLE `food_delivery_orders` ADD `order_id` VARCHAR(20) NULL DEFAULT NULL AFTER `delivered_time`;


INSERT INTO `food_delivery_plugin_auth_permissions` (`id`, `parent_id`, `inherit_id`, `key`, `is_shown`) VALUES (NULL, '41', NULL, 'pjAdminOrders_pjActionSaveOrderDespatched', 'T');
INSERT INTO `food_delivery_plugin_auth_users_permissions` (`id`, `user_id`, `permission_id`) VALUES (NULL, '93', '122'), (NULL, '93', '123');

INSERT INTO `food_delivery_plugin_auth_permissions` (`id`, `parent_id`, `inherit_id`, `key`, `is_shown`) VALUES (NULL, '41', NULL, 'pjAdminOrders_pjActionGetProductsForCategory', 'T'), (NULL, '41', NULL, 'pjAdminOrders_pjActionSetSession', 'T'), (NULL, '41', NULL, 'pjAdminOrders_pjActionDelayMessage', 'T')
INSERT INTO `food_delivery_plugin_auth_users_permissions` (`id`, `user_id`, `permission_id`) VALUES (NULL, '93', '124'), (NULL, '93', '125'), (NULL, '93', '126');
INSERT INTO `food_delivery_plugin_auth_users_permissions` (`id`, `user_id`, `permission_id`) VALUES (NULL, '2', '127');

INSERT INTO `food_delivery_plugin_auth_permissions` (`id`, `parent_id`, `inherit_id`, `key`, `is_shown`) VALUES (NULL, '48', NULL, 'pjAdminClients_pjActionCheckPhoneNumber', 'T')
INSERT INTO `food_delivery_plugin_auth_users_permissions` (`id`, `user_id`, `permission_id`) VALUES (NULL, '2', '130')