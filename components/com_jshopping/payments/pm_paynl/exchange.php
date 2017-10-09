<?php
/**
@authors: Roman Avr, Alexey Startler
 */

define('_JEXEC',1);
define('JPATH_BASE', dirname(__FILE__). '/../../../../');
define('JPATH_COMPONENT_SITE', JPATH_BASE."/components/com_jshopping");
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

$app = JFactory::getApplication('site');
$app->initialise();

require_once (JPATH_COMPONENT_SITE."/lib/factory.php");
require_once (JPATH_COMPONENT_SITE.'/lib/functions.php');

require_once(JPATH_COMPONENT_SITE."/controllers/checkout.php");

JTable::addIncludePath(JPATH_COMPONENT_SITE.'/tables');
jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE.'/models');

$checkout = JModelLegacy::getInstance('checkout', 'jshop');
$cart = JModelLegacy::getInstance('cart', 'jshop');
$cart->load();
$order = JTable::getInstance('order', 'jshop');


require_once __DIR__ . '/paynl_api/vendor/autoload.php';

$jshopConfig = JSFactory::getConfig();
JSFactory::loadLanguageFile();

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
    $order->load($order_id);
    if (!$order->order_created)
    {
        $order->order_created = 1;
        $order->order_status = $transaction_end_status;
        $order->store();
        $order->changeProductQTYinStock("-");
        $checkout->changeStatusOrder($order->order_id, $transaction_end_status, 0);
        if ($jshopConfig->send_order_email)
        {
            $checkout->sendOrderEmail($order->order_id);
        }
        if ($transaction_end_status && $order->order_status != $transaction_end_status){
            $checkout->changeStatusOrder($order->order_id, $transaction_end_status, 1);
        }
    }
    $cart->clear();
    $checkout->deleteSession();
}
?>