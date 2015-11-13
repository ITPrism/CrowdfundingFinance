CREATE TABLE IF NOT EXISTS `#__cffinance_payouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(64) DEFAULT NULL,
  `paypal_first_name` varchar(64) DEFAULT NULL,
  `paypal_last_name` varchar(64) DEFAULT NULL,
  `iban` varchar(64) DEFAULT NULL,
  `bank_account` text,
  `stripe` mediumblob,
  `project_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_cffpayouts_pid` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains data used in the process of payout to project owners.';
