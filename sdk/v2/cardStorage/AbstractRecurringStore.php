<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

abstract class AbstractRecurringStore
{
    protected $transactionBase;
    protected $returnData;

    function __construct(array $transactionBase, array $returnData)
    {
        $this->transactionBase = $transactionBase;
        $this->returnData = $returnData;
    }

    public function processTokens()
    {
        $this->saveTokens($this->getTokens());
    }

    public function getTokens()
    {
        $tokens = [];

        if (isset($this->returnData['tokens']) || count($this->returnData['tokens']) > 0) {
            foreach ($this->returnData['tokens'] as $token) {
                $tokens[] = [
                    'merchant' => $this->returnData['merchant'],
                    'orderRef' => $this->returnData['orderRef'],
                    'transactionId' => $this->returnData['transactionId'],
                    'tokenRegDate' => @date("c", time()),
                    'customerEmail' => $this->transactionBase['customerEmail'],
                    'token' => $token,
                    'until' => $this->transactionBase['recurring']['until'],
                    'maxAmount' => $this->transactionBase['recurring']['maxAmount'],
                    'currency' => $this->returnData['currency'],
                    'tokenState' => 'stored',
                ];
            }
        }

        return $tokens;
    }

    protected function saveTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            $this->saveToken($token);
        }
    }

    protected function saveToken(array $token)
    {
    }
}