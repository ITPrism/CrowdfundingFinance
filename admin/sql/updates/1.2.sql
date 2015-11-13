CREATE TABLE IF NOT EXISTS `#__cffinance_payouts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(64) DEFAULT NULL,
  `paypal_first_name` varchar(64) DEFAULT NULL,
  `paypal_last_name` varchar(64) DEFAULT NULL,
  `iban` varchar(64) DEFAULT NULL,
  `bank_account` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;