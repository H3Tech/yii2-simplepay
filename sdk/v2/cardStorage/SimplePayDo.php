<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

use h3tech\simplePay\sdk\v2\Base;
use h3tech\simplePay\sdk\v2\traits\Sca;

class SimplePayDo extends Base
{
    use Sca;

    protected $currentInterface = 'do';
    protected $returnArray = [];
    public $transactionBase = [
        'salt' => '',
        'orderRef' => '',
        'customerEmail' => '',
        'merchant' => '',
        'currency' => '',
    ];

    /**
     * Constructor for do
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiInterface['do'] = '/v2/do';
    }

    /**
     * Run Do
     *
     * @return array $result API response
     */
    public function runDo()
    {
        return $this->execApiCall();
    }
}