<?php
/*
 * @The Name: Paypal
 * @The URI: http://www.paypal.com/
 * @Description: 有了贝宝，您可以通过电子邮件向任何人付款。
 * @Version: 2011.8.15
*/
/* 配置信息 */
$modules['paypal']['config']  = array(
array('name' => 'account','title'=>'Account','type' => 'text',   'value' => ''),
array('name' => 'currency','title'=>'Currency','type' => 'text',   'value' => '')
);
/**
 * 类
 */
class paypal
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function paypal()
    {
    }

    function __construct()
    {
        $this->paypal();
    }

    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function redirect($order, $payment)
    {
    	global $charset;
        $data_order_id      = $order['trade_no'];
        $data_amount        = $order['total_price'];
        $data_return_url    = URL.'purchase.php?code='.basename(__FILE__, '.php');
        $data_pay_account   = $payment['account'];
        $currency_code      = $payment['currency'];
        $data_notify_url    = URL.'purchase.php?code='.basename(__FILE__, '.php');
        $cancel_return      = URL.'purchase.php';

        $def_url  = '<img src="'.STATICURL.'images/loading.gif" /><br /><form style="text-align:center;" name="paysubmit" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_self">' .   // 不能省略
            "<input type='hidden' name='cmd' value='_xclick'>" .                             // 不能省略
            "<input type='hidden' name='business' value='$data_pay_account'>" .                 // 贝宝帐号
            "<input type='hidden' name='item_name' value='".$order['trade_no']."\n".$order['subject']."'>" .                 // payment for
            "<input type='hidden' name='amount' value='$data_amount'>" .                        // 订单金额
            "<input type='hidden' name='currency_code' value='$currency_code'>" .            // 货币
            "<input type='hidden' name='return' value='$data_return_url'>" .                    // 付款后页面
            "<input type='hidden' name='invoice' value='$data_order_id'>" .                      // 订单号
            "<input type='hidden' name='charset' value='utf-8'>" .                              // 字符集
            "<input type='hidden' name='no_shipping' value='1'>" .                              // 不要求客户提供收货地址
            "<input type='hidden' name='no_note' value=''>" .                                  // 付款说明
            "<input type='hidden' name='notify_url' value='$data_notify_url'>" .
            "<input type='hidden' name='rm' value='2'>" .
            "<input type='hidden' name='cancel_return' value='$cancel_return'>" .
            "</form><br /><script>document.forms['paysubmit'].submit();</script>";

        header('Content-Type: text/html; charset='.$charset);
        echo $def_url;
        exit(0);
    }

    /**
     * 响应操作
     */
    function respond()
    {
    	global $order;
        $payment        = $GLOBALS['pdb']->getOne("SELECT config FROM ".$GLOBALS['tb_prefix']."payments WHERE name='paypal'");
        $payment = unserialize($payment);
        $merchant_id    = $payment['account'];               ///获取商户编号

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value)
        {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) ."\r\n\r\n";
        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $order_sn = $_POST['invoice'];
        $memo = !empty($_POST['memo']) ? $_POST['memo'] : '';
        $action_note = $txn_id . '（paypal 交易号）' . $memo;

        if (!$fp)
        {
            fclose($fp);

            return false;
        }
        else
        {
            fputs($fp, $header . $req);
            while (!feof($fp))
            {
                $res = fgets($fp, 1024);
                if (strcmp($res, 'VERIFIED') == 0)
                {
                    // check the payment_status is Completed
                    if ($payment_status != 'Completed' && $payment_status != 'Pending')
                    {
                        fclose($fp);

                        return false;
                    }

                    // check that receiver_email is your Primary PayPal email
                    if ($receiver_email != $merchant_id)
                    {
                        fclose($fp);

                        return false;
                    }

                    // check that payment_amount/payment_currency are correct
                    $sql = "SELECT total_price FROM " . $GLOBALS['tb_prefix'] . "orders WHERE trade_no = '$order_sn'";
                    if ($GLOBALS['pdb']->getOne($sql) != $payment_amount)
                    {
                        fclose($fp);

                        return false;
                    }
                    if ($payment['paypal_currency'] != $payment_currency)
                    {
                        fclose($fp);

                        return false;
                    }

		        	//更新订单状态
		        	$order->checkPayByTradeNo($trade_no, 1);
                    fclose($fp);

                    return true;
                }
                elseif (strcmp($res, 'INVALID') == 0)
                {
                    // log for manual investigation
                    fclose($fp);

                    return false;
                }
            }
        }
    }
}

?>