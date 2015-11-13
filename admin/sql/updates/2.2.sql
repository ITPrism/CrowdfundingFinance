ALTER TABLE `#__cffinance_payouts` CHANGE `id` `project_id` INT(10) UNSIGNED NOT NULL;
ALTER TABLE `#__cffinance_payouts` DROP PRIMARY KEY;
ALTER TABLE `#__cffinance_payouts` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `#__cffinance_payouts` ADD UNIQUE `idx_cffpayouts_pid` (`project_id`);
ALTER TABLE `#__cffinance_payouts` ADD `stripe` MEDIUMBLOB NULL DEFAULT NULL AFTER `bank_account`;
