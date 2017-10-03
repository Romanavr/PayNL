<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.folder');

$query = "delete from #__jshopping_payment_method where payment_class='pm_paynl'";
$db->setQuery($query);
$db->query();
$query = "drop table #__jshopping_payment_transactions ";

JFolder::delete(JPATH_ROOT."/components/com_jshopping/payments/pm_paynl");
?>