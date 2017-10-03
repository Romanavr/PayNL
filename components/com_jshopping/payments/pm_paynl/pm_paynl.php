<?php
/**
@authors: Roman Avr, Alexey Startler
*/
defined('_JEXEC') or die();

class pm_paynl extends PaymentRoot{


    function showPaymentForm($params, $pmconfigs){
        include(dirname(__FILE__)."/paymentform.php");
    }


    function showAdminFormParams($params)
    {
        $array_params = array('paynl_serviceid', 'paynl_token', 'transaction_end_status');
        foreach ($array_params as $key){
            if (!isset($params[$key])) $params[$key] = '';
        }
        $orders = JModelLegacy::getInstance('orders', 'JshoppingModel');
        include(dirname(__FILE__)."/adminparamsform.php");
    }


    function showEndForm($pmconfigs, $order){
        require_once __DIR__ . '/paynl_api/vendor/autoload.php';
        
        Paynl\Config::setServiceId($pmconfigs['paynl_serviceid']);
        Paynl\Config::setApiToken($pmconfigs['paynl_token']);

        $result = \Paynl\Transaction::start(array(
            'amount' => $order->order_total,
            'returnUrl' => JURI::base(),
            'orderNumber' => $order->order_id
        ));

        $transactionUrl = $result->getRedirectUrl();
        header("Location: $transactionUrl");

        $db = JFactory::getDbo();
        $query = 'INSERT INTO `#__jshopping_payment_transactions` SET `transaction_id` = "'.$result->getTransactionId().'", `order_id` = "'.$order->order_id.'"';
        $db->setQuery($query);
        $db->query();
    }

}