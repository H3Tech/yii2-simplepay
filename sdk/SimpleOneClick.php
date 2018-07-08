<?php

namespace h3tech\simplePay\sdk;

/**
 *  Copyright (C) 2016 OTP Mobil Kft.
 *
 *  PHP version 5
 *
 *  This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  2018.07.05.
 *  A namespace has been added to this file to make it possible to autoload the class.
 *  This was necessary to make it possible to cleanly integrate the SDK into a Yii2 application.
 *
 * @category SDK
 * @package  Simple_SDK
 * @author   Simple IT <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/simple-pay.php
 *
 */

/**
 * PayU OneClick Payment
 *
 * @category SDK
 * @package  Simple_SDK
 * @author   Simple IT <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/simple-pay.php
 *
 */
class SimpleOneClick extends SimpleTransaction
{
    public $hashData = array();
    public $formData = array();
    public $logger = false;
	public $tokenUrl = '';
	public $commMethod = 'oneclick';
    public $validFields = array(
        "AMOUNT" => array("type" => "single", "paramName" => "amount", "required" => true),             //ORDER_PRICE
        "CURRENCY" => array("type" => "single","required" => true),                                     //PRICES_CURRENCY
        "EXTERNAL_REF" => array("type" => "single", "required" => true),                                //ORDER_REF in LiveUpdate
        "MERCHANT" => array("type" => "single", "paramName" => "merchantId", "required" => true),       //MERCHANT ID
        "METHOD" => array("type" => "single", "required" => true),                                      //Token method
        "REF_NO" => array("type" => "single","required" => true),                                       //TOKEN
        "TIMESTAMP" => array("type" => "single", "required" => true),                                   //ORDER_DATE
        "CANCEL_REASON" => array("type" => "single", "required" => false),                              //optional, only for TOKEN_CANCEL

        "BILL_FNAME" => array("type" => "single", "required" => true),
        "BILL_LNAME" => array("type" => "single", "required" => true),
        "BILL_EMAIL" => array("type" => "single", "required" => true),
        "BILL_PHONE" => array("type" => "single", "required" => true),
        "BILL_ADDRESS" => array("type" => "single", "required" => true),
        "BILL_CITY" => array("type" => "single", "required" => true),
		"BILL_COMPANY" => array("type" => "single"),
		"BILL_FISCALCODE" => array("type" => "single"),
		"BILL_COUNTRYCODE" => array("type" => "single"),
		"BILL_STATE" => array("type" => "single"),
		"BILL_ADDRESS2" => array("type" => "single"),
		"BILL_ZIPCODE" => array("type" => "single"),

        "DELIVERY_ADDRESS" => array("type" => "single", "required" => true),
        "DELIVERY_CITY" => array("type" => "single", "required" => true),
        "DELIVERY_FNAME" => array("type" => "single", "required" => true),
        "DELIVERY_LNAME" => array("type" => "single", "required" => true),
        "DELIVERY_PHONE" => array("type" => "single", "required" => true),
		"DELIVERY_EMAIL" => array("type" => "single"),
		"DELIVERY_COUNTRYCODE" => array("type" => "single"),
		"DELIVERY_STATE" => array("type" => "single"),
		"DELIVERY_ADDRESS2" => array("type" => "single"),
		"DELIVERY_ZIPCODE" => array("type" => "single"),

    );

    //fields for hash
    public $hashFields = array(
        "AMOUNT",
        "BILL_ADDRESS",
        "BILL_CITY",
        "BILL_EMAIL",
        "BILL_FNAME",
        "BILL_LNAME",
        "BILL_PHONE",
        "CANCEL_REASON",
        "CURRENCY",
        "DELIVERY_ADDRESS",
        "DELIVERY_CITY",
        "DELIVERY_FNAME",
        "DELIVERY_LNAME",
        "DELIVERY_PHONE",
        "EXTERNAL_REF",
        "MERCHANT",
        "METHOD",
        "REF_NO",
        "TIMESTAMP",
    );


    /**
     * Constructor of PayUOneClick class
     *
     * @param array $config Configuration array
     *
     * @return void
     *
     */
    public function __construct($config = array(), $currency = '')
    {
        $this->setDefaults(
            array(
                $this->validFields
            )
        );
		$config = $this->merchantByCurrency($config, $currency);
        $this->setup($config);
		if (isset($this->debug_oneclick)) {
			$this->debug = $this->debug_oneclick;
		}
		$this->tokenUrl = $this->defaultsData['BASE_URL'] . $this->defaultsData['OC_URL'];
		$this->debugMessage[] = 'TOKEN URL: ' . $this->tokenUrl;
        $this->fieldData['MERCHANT'] = $this->merchantId;
    }


    /**
     * Sends notification via cURL
     *
     * @return array $result Result
     *
     */
    public function tokenApiCall()
    {
        $fields = $this->createPostArray("SIGN");
		$this->logFunc("OneClick", $fields, $fields['EXTERNAL_REF']);
		$result = $this->startRequest($this->tokenUrl, $fields, 'POST');
		$data = (array) json_decode($result);
		$this->logFunc("OneClick", $data, $fields['EXTERNAL_REF']);
        return $data;
    }

}
