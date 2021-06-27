<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

use h3tech\simplePay\sdk\v2\Base;

/**
 * TokenQuery
 *
 * @category SDK
 * @package  SimplePayV21_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayTokenQuery extends Base
{

    protected $currentInterface = 'tokenquery';

    /**
     * Constructor for tokenquery
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiInterface['tokenquery'] = '/v2/tokenquery';
    }

    /**
     * Run Dorecurring
     *
     * @return array $result API response
     */
    public function runTokenQuery()
    {
        return $this->execApiCall();
    }
}