<?php
/*
 * @The Name: 支付宝
 * @The URI: http://www.alipay.com/
 * @Description: 中国最大的第三方电子支付服务提供商
 * @Version: 2011.3.15
*/
/* 配置信息 */
$modules['alipay']['config']  = array(
array('name' => 'account','title'=>'支付宝收款账号','type' => 'text',   'value' => ''),
array('name' => 'alipay_key','title'=>'交易安全校验码','type' => 'text',   'value' => ''),
array('name' => 'partner_id','title'=>'合作者身份','type' => 'text',   'value' => '')
);
class alipay
{
    var $gateway = 'https://www.alipay.com/cooperate/gateway.do';
    //请更换下列三个值
    var	$alipay_key = "";
    var $partner_id = "";
    var $account = "";//account
    
    function alipay()
    {
    }

    function __construct()
    {
        $this->alipay();
    }

    function redirect($order, $payment_config = array())
    {
    	global $charset;
    	if (!empty($payment_config)) {
    		foreach ($payment_config as $key=>$val) {
    			$this->{$key} = $val;
    		}
    	}
    	$real_method = $payment_config['pay_method'];
    	switch ($real_method){
    		case '0':
    			$service = 'trade_create_by_buyer';
    			break;
    		case '1':
    			$service = 'create_partner_trade_by_buyer';
    			break;
    		case '2':
    			$service = 'create_direct_pay_by_user';
    			break;
    	}
        $parameter = array(
            'service'           => 'create_direct_pay_by_user',
            'payment_type'      => '1',		
            /* 基本参数 */
            '_input_charset'    => $charset,
            //Todo:if not support ? params.
            'notify_url'        => URL.'purchase.php?code='.basename(__FILE__, '.php'),
            'return_url'        => URL.'purchase.php?code='.basename(__FILE__, '.php'),
            'paymethod'    		=> "bankPay",
            'defaultbank'    	=> "CMB",
			'show_url'			=> URL.'service',
            /* 业务参数 */
            'subject'           => empty($order['subject'])?'支付网站服务费':$order['subject'],//商品名称
            'out_trade_no'      => $order['trade_no'],
            'total_fee'         => $order['total_price'],
            'body'				=>	$order['content'],//备注
            /* 买卖双方信息 */
            'partner'           => $this->partner_id,
            'seller_email'      => $this->account
        );

        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }
		$param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $this->alipay_key;
        $url = $this->gateway."?".$param."&sign=".md5($sign)."&sign_type=MD5";
        $sHtml = '<script language="javascript">location.href="'.$url.'";</script><div style="text-align:center">如果不能自动跳转，请点击<a href="'.$url.'" title="">立即支付<a/></div>';
        header('Content-Type: text/html; charset='.$charset);
        echo $sHtml;
        exit(0);
    }

    /**
     * 响应操作
     */
    function respond()
    {
    	global $order;
        if (!empty($_POST))
        {
            foreach($_POST as $key => $data)
            {
                $_GET[$key] = $data;
            }
        }
        $trade_no = str_replace($_GET['subject'], '', $_GET['out_trade_no']);
        $trade_no = trim($trade_no);
		$order_result = $order->getInfoByTradeNo($trade_no);
		if (!$order_result || empty($order_result)){
			return false;
		}
        /* 检查数字签名是否正确 */
        ksort($_GET);
        reset($_GET);

        $sign = '';
        foreach ($_GET AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $this->alipay_key;
        if (md5($sign) != $_GET['sign'])
        {
            return false;
        }
        if (($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') && $_GET['is_success']=='T')
        {
        	//更新订单状态
        	$order->checkPayByTradeNo($trade_no, 1);
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>