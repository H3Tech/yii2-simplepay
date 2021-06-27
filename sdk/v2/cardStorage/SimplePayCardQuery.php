<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

use h3tech\simplePay\sdk\v2\Base;

/**
 * CardQuery
 *
 * @category SDK
 * @package  SimplePayV21_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayCardQuery extends Base
{
    protected $currentInterface = 'cardquery';
    protected $returnArray = [];
    public $transactionBase = [
        'salt' => '',
        'cardId' => '',
        'history' => false,
        'merchant' => '',
    ];

    /**
     * Constructor for cardquery
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiInterface['cardquery'] = '/v2/cardquery';
    }

    /**
     * Run CardQuery
     *
     * @return array $result API response
     */
    public function runCardQuery()
    {
        return $this->execApiCall();
    }
}
