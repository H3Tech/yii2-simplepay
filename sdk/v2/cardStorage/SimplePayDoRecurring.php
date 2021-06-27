<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

use h3tech\simplePay\sdk\v2\Base;
use h3tech\simplePay\sdk\v2\traits\Sca;

/**
 * Recurring
 *
 * @category SDK
 * @package  SimplePayV21_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayDoRecurring extends Base
{
    use Sca;
    protected $currentInterface = 'dorecurring';

    /**
     * Constructor for dorecurring
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiInterface['dorecurring'] = '/v2/dorecurring';
    }

    /**
     * Run Dorecurring
     *
     * @return array $result API response
     */
    public function runDorecurring()
    {
        return $this->execApiCall();
    }
}