<?php

/*
	Author: Restart Business
	Url: https://restart.com.kg
	Copyright: © 2021 Restart Business.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
defined('_JEXEC') or die();

class pm_rk extends PaymentRoot{

	function showPaymentForm($params, $pmconfigs)
	{
		include(dirname(__FILE__) . '/paymentform.php');
	}

	function showAdminFormParams($params)
	{
		$jmlThisDocument = &JFactory::getDocument();
		$pm_method = $this->getPmMethod();
		$hosturl = JURI::root();

		if ($jmlThisDocument->language == 'en-gb')
		{
			include(JPATH_SITE . '/administrator/components/com_jshopping/lang/en-GB_rk.php');
		}
		else
		{
			include(JPATH_SITE . '/administrator/components/com_jshopping/lang/ru-RU_rk.php');
		}

		$array_params = array(
			'merchant_url',
			'merchant_id',
			'secret_key1',
			'log_file',
			'transaction_end_status',
			'transaction_pending_status',
			'transaction_failed_status',
			'success_url',
			'fail_url',
			'status_url'
		);

		if (!isset($params['merchant_url']) || empty($params['merchant_url']))
		{
			$params['merchant_url'] = '//pay.roskassa.net/';
		}

		$success_url = $hosturl . 'index.php?option=com_jshopping&controller=checkout&task=step7&act=return&js_paymentclass=' . $pm_method->payment_class;
		$fail_url = $hosturl . 'index.php?option=com_jshopping&controller=checkout&task=step7&act=cancel&js_paymentclass=' . $pm_method->payment_class;
		$status_url = $hosturl . 'index.php?option=com_jshopping&controller=checkout&task=step7&act=notify&js_paymentclass=' . $pm_method->payment_class;

		foreach ($array_params as $key)
		{
			if (!isset($params[$key]))
			{
				$params[$key] = '';
			}
		}
		$orders = JSFactory::getModel('orders', 'JshoppingModel');
		include(dirname(__FILE__) . '/adminparamsform.php');
	}

	function checkTransaction($pmconfigs, $order, $act)
	{
		$jshopConfig = JSFactory::getConfig();
		$request = $_POST;
		$jmlThisDocument = &JFactory::getDocument();
		// echo '<pre>';
		// print_r($request);
		// echo '</pre>';
		// exit;
		if (isset($request['sign']))
		{
			$err = false;
			$message = '';

			if ($jmlThisDocument->language == 'en-gb')
			{
				include(JPATH_SITE . '/administrator/components/com_jshopping/lang/en-GB_rk.php');
			}
			else
			{
				include(JPATH_SITE . '/administrator/components/com_jshopping/lang/ru-RU_rk.php');
			}

			// запись логов

			$log_text =
			"--------------------------------------------------------\n" .
			"id you	shoop   	" . $request["shop_id"] . "\n" .
			"amount				" . $request["amount"] . "\n" .
			"mercant order id	" . $request["order_id"] . "\n" .
			"currency			" . $request["currency"] . "\n" .
			"sign				" . $request["sign"] . "\n\n";

			$log_file = $pmconfigs['log_file'];

			if (!empty($log_file))
			{
				file_put_contents($_SERVER['DOCUMENT_ROOT'] . $log_file, $log_text, FILE_APPEND);
			}

			$sign = $request['sign'];
			unset($request['sign']);

			ksort($request);
			$str = http_build_query($request);
			$sign_hash = md5($str . $pmconfigs['secret_key1']);

			if (!$err) {
				$order_curr = strtoupper($order->currency_code_iso);
				$order_curr = ($order_curr == 'RUR') ? 'RUB' : $order_curr;
				$order_amount = number_format($order->order_total, 2, '.', '');
                // проверка суммы и валюты
				if ($request['amount'] != $order_amount) {
					$message .= _JSHOP_RK_MSG_WRONG_AMOUNT . "\n";
					$err = true;
				}
                // проверка статуса
				if (!$err) {
					if ($sign == $sign_hash) {
						echo 'YES';
						if ($order->order_status != $pmconfigs['transaction_end_status']) {
							return array(1, $request['order_id']);
						}
						else {
							return false;
						}
					}
					else {
						$message .= _JSHOP_RK_MSG_HASHES_NOT_EQUAL . "\n";
						$err = true;
					}
				}
			}

			if ($err)
			{
				$to = $pmconfigs['email_err'];
				if (!empty($to))
				{
					$message = _JSHOP_RK_MSG_ERR_REASONS . "\n\n" . $message . "\n" . $log_text;
					$headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" . 
					"Content-type: text/plain; charset=utf-8 \r\n";
					mail($to, _JSHOP_RK_MSG_SUBJECT, $message, $headers);
				}
				echo $request['order_id'] . '|error|' . $message;
				return array(3, $request['order_id']);
			}
		}
	}

	function showEndForm($pmconfigs, $order)
	{
		$jshopConfig = JSFactory::getConfig();
//$pmconfigs['transaction_end_status']
		$m_url = $pmconfigs['merchant_url'];
		$m_shop = $pmconfigs['merchant_id'];
		$m_orderid = $order->order_id;
		$m_amount = number_format($order->order_total, 2, '.', '');
		$m_curr = strtoupper($order->currency_code_iso);
		$m_curr = ($m_curr == 'RUR') ? 'RUB' : $m_curr;
		$m_key = $pmconfigs['secret_key1'];

		if (empty($_REQUEST['lang']))
		{
			$m_lang = 'ru';
		}
		else
		{
			$m_lang = $_REQUEST['lang'];
		}
		$arHash = array(
			'shop_id'=>$m_shop,
			'amount'=>$m_amount,
			'currency'=>$m_curr,
			'order_id'=>$m_orderid,
			'test'=>1
		);
		ksort($arHash);
		$str = http_build_query($arHash);
		$sign = md5($str . $m_key);
		?>

		<html>
		<head>
			<meta http-equiv="content-type" content="text/html; charset=UTF-8" />          
		</head>        
		<body>
			<form action="<?php echo $m_url; ?>" id="paymentform" name="paymentform" method="GET">
				<input type="hidden" name="shop_id" value="<?php echo $m_shop; ?>">
				<input type="hidden" name="amount" value="<?php echo $m_amount; ?>">
				<input type="hidden" name="order_id" value="<?php echo $m_orderid; ?>">
				<input type="hidden" name="sign" value="<?php echo $sign; ?>">
				<input type="hidden" name="currency" value="<?php echo $m_curr; ?>">
				<input type="hidden" name="test" value="1">
			</form>
			<?php print _JSHOP_REDIRECT_TO_PAYMENT_PAGE ?>
			<br>
			<script type="text/javascript">document.getElementById('paymentform').submit();</script>
		</body>
		</html>
		<?php
		die();
	}
	function getUrlParams($pmconfigs){
		$params = array();
		$params['order_id'] = JFactory::getApplication()->input->getInt('order_id');
		$params['hash'] = "";
		$params['checkHash'] = 0;
		$params['checkReturnParams'] = $pmconfigs['checkdatareturn'];
		return $params;
	}
}