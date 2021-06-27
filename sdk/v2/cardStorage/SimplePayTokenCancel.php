<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

use h3tech\simplePay\sdk\v2\Base;

/**
 * TokenCancel
 *
 * @category SDK
 * @package  SimplePayV21_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayTokenCancel extends Base
{

    protected $currentInterface = 'tokencancel';

    /**
     * Constructor for tokencancel
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiInterface['tokencancel'] = '/v2/tokencancel';
    }

    /**
     * Run Dorecurring
     *
     * @return array $result API response
     */
    public function runTokenCancel()
    {
        return $this->execApiCall();
    }
}