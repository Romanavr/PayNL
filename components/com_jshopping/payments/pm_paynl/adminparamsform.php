<?php
/**
@authors: Roman Avr, Alexey Startler
 */
include(dirname(__FILE__)."/language.ru.php");
?>
<div class="col100">
    <fieldset class="adminform">
        <table class="admintable" width =   "100%" >
            <tr>
                <td width="300"><strong>ServiceId:</strong></td>
                <td>
                    <input type="text" name = "pm_params[paynl_serviceid]" class="inputbox" value="<?php echo $params['paynl_serviceid']?>" />
                </td>

                <td>The ID of your service starting with 'SL-' (see <a href="https://admin.pay.nl/websites">https://admin.pay.nl/websites</a> for more information about this ID).</td>
            </tr>
            <tr>
                <td><strong>Token:</strong></td>
                <td>
                    <input type="text"name = "pm_params[paynl_token]" class="inputbox" value="<?php echo $params['paynl_token']?>" />
                </td>

                <td>Your token. Visit <a href="http://admin.pay.nl/my_merchant">http://admin.pay.nl/my_merchant</a> for a list of your API token(s).</td>
            </tr>

            <tr>
                <td class="key">
                    <strong>   <?php echo _JSHOP_TRANSACTION_END;?>: </strong>
                </td>
                <td>
                    <?php
                    print JHTML::_('select.genericlist', $orders->getAllOrderStatus(), 'pm_params[transaction_end_status]', 'class = "inputbox" size = "1"', 'status_id', 'name', $params['transaction_end_status'] );
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
</div>
<div class="clr"></div>