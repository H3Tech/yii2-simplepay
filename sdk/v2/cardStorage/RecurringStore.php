<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

/**
 * RecurringStore
 *
 * @category SDK
 * @package  SimplePayV21_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class RecurringStore
{
    protected $tokensFolderName = 'recurring';
    public $storingType = 'file';
    public $request ;

    /**
     * Write tokens into file
     *
     * @return void
     */
    public function storeNewTokens()
    {
        if (!isset($this->transaction['tokens']) || count($this->transaction['tokens']) == 0) {
            return false;
        }

        $store = array();
        $counter = 1;
        foreach ($this->transaction['tokens'] as $token) {
            $store[] = array(
                'id' => $counter,
                'merchant' => $this->transaction['merchant'],
                'orderRef' => $this->transaction['orderRef'],
                'transactionId' => $this->transaction['transactionId'],
                'tokenRegDate' => @date("c", time()),
                'customerEmail' => $this->transactionBase['customerEmail'],
                'token' => $token,
                'until' => $this->transactionBase['recurring']['until'],
                'maxAmount' => $this->transactionBase['recurring']['maxAmount'],
                'currency' => $this->transaction['currency'],
                'tokenState' => 'stored'
            );
            $counter++;
        }
        $dataToStore = json_encode($store);
        file_put_contents($this->tokensFolderName . '/' . $this->transaction['transactionId'] . '.tokens', $dataToStore, LOCK_EX);
    }

    /**
     * Get tokens from file
     *
     * @param string $serverData Data from $_SERVER
     *
     * @return string $table HTML table populated with tokens data
     */
    public function getTokens($serverData = '')
    {
        $tokensObj = json_decode(file_get_contents($this->tokensFolderName . '/' . $this->request['rContent']['t'] . '.tokens', true));
        $tokens = $this->convertToArray($tokensObj);

        $table = '';
        foreach ($tokens as $token) {
            $table .= '<b>' . $token['id'] . '</b></br> '
                . '<script>'
                . 'document.write(\'<b>Token:</b><a href="dorecurring.php?browser=\' + browserData + \'&server=' . $serverData . '&token=' . $token['token'] . '&merchant=' . $this->request['rContent']['m'] . '">' . $token['token'] . ' </a></br>\');'
                . '</script>'
                . '<b>Until:</b> ' . $token['until'] . '</br> '
                . '<b>Max. amount:</b> ' . $token['maxAmount'] . '</br> '
                . '<b>Currency:</b> ' . $token['currency'] . ' </br>'
                . '<b>Token:</b> <a href="tokenquery.php?token=' . $token['token'] . '&merchant=' . $this->request['rContent']['m'] .'">CHECK</a></br>'
                . '<b>Token:</b> <a href="tokencancel.php?token=' . $token['token'] . '&merchant=' . $this->request['rContent']['m'] .'">CANCEL</a></br></br>';
        }
        return $table;
    }

    /**
     * Checks token existance
     *
     * @return boolean
     */
    public function isTokenExists()
    {
        if (file_exists($this->tokensFolderName . '/' . $this->request['rContent']['t'] . '.tokens')) {
            return true;
        }
        return false;
    }

    /**
     * Convert object to array
     *
     * @param object $obj Object to transform
     *
     * @return array $new Result array
     */
    protected function convertToArray($obj)
    {
        if (is_object($obj)) {
            $obj = (array) $obj;
        }
        $new = $obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = $this->convertToArray($val);
            }
        }
        return $new;
    }
}