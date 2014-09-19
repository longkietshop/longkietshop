<?php
/**
 * @version		1.0.3
 * @package		Joomla
 * @subpackage	EShop
 * @author  Tuan Pham Ngoc
 * @copyright	Copyright (C) 2010 - 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class os_authnet extends os_payment
{
    protected $results = array();
    /**
     * Constructor function
     *
     * @param object $config
     */
    public function __construct($params)
    {
        $config = array(
            'type' => 1,
            'show_card_type' => false,
            'show_card_holder_name' => false
        );
        parent::__construct($params, $config);

        $this->mode = $params->get('authnet_mode', 0);
        if ($this->mode)
        {
            $this->url = "https://secure.authorize.net/gateway/transact.dll";
        }
        else
        {
            $this->url = "https://test.authorize.net/gateway/transact.dll";
        }
        $this->data['x_delim_data'] = "TRUE";
        $this->data['x_delim_char'] = "|";
        $this->data['x_relay_response'] = "FALSE";
        $this->data['x_url'] = "FALSE";
        $this->data['x_version'] = "3.1";
        $this->data['x_method'] = "CC";
        $this->data['x_type'] = "AUTH_CAPTURE";
        $this->data['x_login'] = $params->get('x_login');
        $this->data['x_tran_key'] = $params->get('x_tran_key');
        $this->data['x_invoice_num'] = $this->invoiceNumber();
    }

    /**
     * Process payment with the posted data
     *
     * @param array $data array
     * @return void
     */
    public function processPayment($data)
    {
        $data['x_description'] = JText::sprintf('ESHOP_PAYMENT_FOR_ORDER', $data['order_id']);
        $data['exp_date'] = str_pad($data['exp_month'], 2, '0', STR_PAD_LEFT) . '/' . substr($data['exp_year'], 2, 2);
        $data['amount'] = round($data['total'], 2);
        $testing = $this->mode ? "FALSE" : "TRUE";
        $cc_num = $this->ccNumber($data["card_number"]);
        //Set more parameters for the payment gateway to user
        $authnetValues = array(
            //Payment information
            "x_test_request" => $testing,
            "x_card_num" => $data['card_number'],
            "x_exp_date" => $data['exp_date'],
            "x_card_code" => $data['cvv_code'],
            "x_description" => $data['x_description'],
            "x_amount" => $data['amount'],
            //  ###########3  CUSTOMER DETAILS  ################3
            "x_first_name" => $data['payment_firstname'],
            "x_last_name" => $data['payment_lastname'],
            "x_address" => $data['payment_address_1'],
            "x_city" => $data['payment_city'],
            "x_state" => $data['payment_zone_name'],
            //"x_phone" => $data['phone'],
            "x_zip" => $data['payment_postcode'],
            "x_company" => $data['payment_company'],
            "x_email" => $data['email'],
            "x_country" => $data['payment_country_name'],
            //  ###########3  SHIPPING DETAILS  ################3
            "x_ship_to_first_name" => $data['shipping_firstname'],
            "x_ship_to_last_name" => $data['shipping_lastname'],
            "x_ship_to_address" => $data['shipping_address_1'],
            "x_ship_to_city" => $data['shipping_city'],
            "x_ship_to_state" => $data['state'],
            "x_ship_to_country" => $data['shipping_country_name'],
            "x_ship_to_zip" => $data['shipping_postcode'],
            //"x_ship_to_phone" => $data['phone'],
            "x_ship_to_email" => $data['email'],
            //  ###########3  MERCHANT REQUIRED DETAILS  ################3
            "cc_number" => $cc_num,
            "cc_expdate" => $data['exp_date'],
            "cc_emailid" => $data['email']);
        foreach ($authnetValues as $key => $value)
        {
            $this->setData($key, $value);
        }
        $fields = $this->buildParameters();
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($fields, "& "));
        //Uncomment this line if you get no response from payment gateway
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //If you are using goodaddy hosting, please uncomment the two below lines
        //curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        //curl_setopt ($ch, CURLOPT_PROXY,"http://proxy.shr.secureserver.net:3128");
        $response = curl_exec($ch);
        $this->parseResults($response);
        curl_close($ch);
        if ($this->results[0] == 1)
        {
            $row = JTable::getInstance('Eshop', 'Order');
            $row->load($data['order_id']);
            $row->transaction_id = $this->results[6];
            $row->order_status_id = EshopHelper::getConfigValue('complete_status_id');
            $row->store();
            EshopHelper::completeOrder($row);
            //Send confirmation email here
            if (EshopHelper::getConfigValue('order_alert_mail'))
            {
                EshopHelper::sendEmails($row);
            }
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_eshop&view=checkout&layout=complete'));
        }
        else
        {
            $session = JFactory::getSession();
            $session->set('eshop_payment_error_reason', $this->results[3]);
            JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_eshop&view=checkout&layout=failure'));
        }
    }

    protected function parseResults($response)
    {
        $this->results = explode("|", $response);
    }
    /*
     * Helper function to generate invoice number
     *
     * @param string $prefix
     * @param int $length
     * @return string
     */
    protected function invoiceNumber($prefix = "DC-", $length = 6)
    {
        $chars = "0123456789";
        $invoiceNumber = "";
        srand((double) microtime() * 1000000);
        for ($i = 0; $i < $length; $i++)
        {
            $invoiceNumber .= $chars[rand() % strlen($chars)];
        }
        $invoiceNumber = $prefix . $invoiceNumber;
        return $invoiceNumber;
    }

    /**
     * Generate credit card number
     *
     * @param string $card_num
     * @return string
     */
    protected function ccNumber($card_num)
    {
        $num = strlen($card_num);
        $cc_num = "";
        for ($i = 0; $i <= $num - 5; $i++)
        {
            $cc_num .= "x";
        }
        $cc_num .= "-";
        $cc_num .= substr($card_num, $num - 4, 4);
        return $cc_num;
    }
}