<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

use h3tech\simplePay\sdk\v2\Base;

/**
 * CardCancel
 *
 * @category SDK
 * @package  SimplePayV21_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayCardCancel extends Base
{
    protected $currentInterface = 'cardcancel';
    protected $returnArray = [];
    public $transactionBase = [
        'salt' => '',
        'cardId' => '',
        'merchant' => '',
    ];

    /**
     * Constructor for cardcancel
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiInterface['cardcancel'] = '/v2/cardcancel';
    }

    /**
     * Run CardCancel
     *
     * @return array $result API response
     */
    public function runCardCancel()
    {
        return $this->execApiCall();
    }
}