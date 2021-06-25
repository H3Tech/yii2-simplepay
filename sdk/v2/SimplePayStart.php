<?php

namespace h3tech\simplePay\sdk\v2;

/**
 * Start transaction
 *
 * @category SDK
 * @package  SimplePayV2_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayStart extends Base
{
    protected $currentInterface = 'start';
    public $transactionBase = [
        'salt' => '',
        'merchant' => '',
        'orderRef' => '',
        'currency' => '',
        'sdkVersion' => '',
        'methods' => [],
    ];

    /**
     * Send initial data to SimplePay API for validation
     * The result is the payment link to where website has to redirect customer
     *
     * @return void
     */
    public function runStart()
    {
        $this->execApiCall();
    }
}