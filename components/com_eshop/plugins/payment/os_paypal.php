<?php
/**
 * @version		1.0.3
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class os_paypal extends os_payment
{
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $config        	
	 */
	public function __construct($params)
	{
        $config = array(
            'type' => 0,
            'show_card_type' => false,
            'show_card_holder_name' => false
        );

        parent::__construct($params, $config);

		$this->mode = $params->get('paypal_mode');
		if ($this->mode)
        {
            $this->url = 'https://www.paypal.com/cgi-bin/webscr';
        }
		else
        {
            $this->url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
		$this->setData('business', $params->get('paypal_id'));
		$this->setData('rm', 2);
		$this->setData('cmd', '_cart');
		$this->setData('upload', '1');
		$this->setData('no_shipping', 1);
		$this->setData('no_note', 1);
		$this->setData('lc', 'US');
		$this->setData('currency_code', $params->get('paypal_currency', 'USD'));
        $this->setData('charset', 'utf-8');
	}
	/**
	 * Process Payment
	 *
	 * @param array $params        	
	 */
	public function processPayment($data)
	{
		$siteUrl = JURI::base();
		$countryInfo = EshopHelper::getCountry($data['payment_country_id']);
		$countProduct = 1;
		foreach ($data['products'] as $product)
		{
			$this->setData('item_name_' . $countProduct, $product['product_name']);
			$this->setData('item_number_' . $countProduct, $product['product_sku']);
			$this->setData('amount_' . $countProduct, $product['price']);
			$this->setData('quantity_' . $countProduct, $product['quantity']);
			$this->setData('weight_' . $countProduct, $product['weight']);
			$countOption = 0;
			foreach ($product['option_data'] as $option)
			{
				$this->setData('on'.$countOption.'_'.$countProduct, $option['option_name']);
				$this->setData('os'.$countOption.'_'.$countProduct, $option['option_value']);
				$countOption++;
			}
			$countProduct++;
		}
		if ($data['discount_amount_cart'])
		{
			$this->setData('discount_amount_cart', $data['discount_amount_cart']);
		}
		$this->setData('currency_code', $data['currency_code']);
		$this->setData('custom', $data['order_id']);
		$this->setData('return', $siteUrl . 'index.php?option=com_eshop&view=checkout&layout=complete');
		$this->setData('cancel_return', $siteUrl . 'index.php?option=com_eshop&view=checkout&layout=cancel&id=' . $data['order_id']);
		$this->setData('notify_url', $siteUrl . 'index.php?option=com_eshop&task=checkout.verifyPayment&payment_method=os_paypal');
		$this->setData('address1', $data['payment_address_1']);
		$this->setData('address2', $data['payment_address_2']);
		$this->setData('city', $data['payment_city']);
		$this->setData('country', $countryInfo->iso_code_2);
		$this->setData('first_name', $data['payment_firstname']);
		$this->setData('last_name', $data['payment_lastname']);
		$this->setData('state', $data['payment_zone_name']);
		$this->setData('zip', $data['payment_postcode']);
		$this->setData('email', $data['email']);
		$this->submitPost();
	}

	/**
	 * Validate the post data from paypal to our server
	 *
	 * @return string
	 */
	protected function validate()
	{
		$errNum = "";
		$errStr = "";
		$urlParsed = parse_url($this->url);
		$host = $urlParsed['host'];
		$path = $urlParsed['path'];
		$postString = '';
		$response = '';
		foreach ($_POST as $key => $value)
		{
			$this->postData[$key] = $value;
			$postString .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}
		$postString .= 'cmd=_notify-validate';
		$fp = fsockopen($host, '80', $errNum, $errStr, 30);
		if (!$fp)
		{
			return false;
		}
		else
		{
			fputs($fp, "POST $path HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: " . strlen($postString) . "\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $postString . "\r\n\r\n");
			while (!feof($fp))
			{
				$response .= fgets($fp, 1024);
			}
			fclose($fp);
		}
        $extraData = "\nIPN Response from Paypal Server:\n " . $response;
        $this->logGatewayData($extraData);
		if ($this->mode)
		{
			if (eregi("VERIFIED", $response))
            {
                return true;
            }
			else
            {
                return false;
            }
		}
		else
		{
			return true;
		}
	}
	/**
	 * Process payment
	 */
	public function verifyPayment()
	{
		$ret = $this->validate();
		$currency = new EshopCurrency();
		if ($ret)
		{
			$row = JTable::getInstance('Eshop', 'Order');
			$id = $this->postData['custom'];
			$amount = $this->postData['mc_gross'];
			if ($amount < 0)
				return false;
			$row->load($id);
			if ($row->order_status_id == EshopHelper::getConfigValue('complete_status_id'))
				return false;
			if ($currency->format($row->total, $row->currency_code, $row->currency_exchanged_value, false) > $amount)
				return false;
			$row->transaction_id = $this->postData['txn_id'];
			$row->order_status_id = EshopHelper::getConfigValue('complete_status_id');
			$row->store();
			EshopHelper::completeOrder($row);
			//Send confirmation email here
			if (EshopHelper::getConfigValue('order_alert_mail'))
			{
				EshopHelper::sendEmails($row);
			}
			return true;
		}
		else
		{
			return false;
		}
	}
}