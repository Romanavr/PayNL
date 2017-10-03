<?php
/**
@authors: Roman Avr, Alexey Startler
 */
define('_JEXEC',1);
define('JPATH_BASE', dirname(__FILE__). '/../../../../');
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

require_once __DIR__ . '/paynl_api/vendor/autoload.php';

//Set ExchangeURL in administration panel PayNL

$db = JFactory::getDbo();
$query = "SELECT `payment_params` FROM `#__jshopping_payment_method` WHERE `scriptname` = 'pm_paynl' ";
$db->setQuery($query);
$db->query();
$result = $db->loadResult();
$payments_params = explode("\n",$result);

$service_id_configs = explode("=",$payments_params[0]);
$api_token_configs = explode("=",$payments_params[1]);
$transaction_end_status_configs = explode("=",$payments_params[2]);


$service_id = $service_id_configs[1];
$api_token = $api_token_configs[1];
$transaction_end_status = $transaction_end_status_configs[1];



\Paynl\Config::setServiceId($service_id);
\Paynl\Config::setApiToken($api_token);

$transaction = \Paynl\Transaction::getForExchange();
$transactionId = $transaction->getId();

$db = JFactory::getDbo();
$query = "SELECT `order_id` FROM `#__jshopping_payment_transactions` WHERE `transaction_id` = '$transactionId' ";
$db->setQuery($query);
$db->query();
$order_id = $db->loadResult();

if($transaction->isPaid()) {
    $db = JFactory::getDbo();
    $query = "UPDATE `#__jshopping_orders` SET `order_status` = '$transaction_end_status',`order_created` = 1  WHERE `order_id` = '$order_id' ";
    $query1 = "UPDATE `#__jshopping_order_history` SET `order_status_id` = '$transaction_end_status' WHERE `order_id` = '$order_id' ";
    $db->setQuery($query);
    $db->query();
    $db->setQuery($query1);
    $db->query();
}
?>