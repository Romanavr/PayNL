INSERT INTO `#__jshopping_payment_method`
(
	`payment_code`,
	`payment_class`,
	`scriptname`,
	`payment_publish`,
	`tax_id`,
	`payment_type`,
	`price`,
	`price_type`,
	`image`,
	`show_descr_in_email`,
	`name_en-GB`,
	`description_en-GB`
)
VALUES
(
	'PayNL',
	'pm_paynl',
	'pm_paynl',
	1,
	'1',
	2,
	0.00,
	0,
	'',
	0,
	'PayNL',
	''
);

CREATE TABLE IF NOT EXISTS `#__jshopping_payment_transactions`
(
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `transaction_id` varchar(50) NOT NULL,
        `order_id` int(11) NOT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
) AUTO_INCREMENT=1;