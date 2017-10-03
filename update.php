<?php

$addon = JTable::getInstance('addon', 'jshop');
$addon->loadAlias("pm_paynl");
$addon->set("name","Payment Addon PayNL");
$addon->set("version","1.0.0");
$addon->set("uninstall","/components/com_jshopping/payments/pm_paynl/uninstall.php");
$addon->store();

?>