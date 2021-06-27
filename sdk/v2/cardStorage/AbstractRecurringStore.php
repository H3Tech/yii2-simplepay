<?php

namespace h3tech\simplePay\sdk\v2\cardStorage;

abstract class AbstractRecurringStore
{
    protected $transaction;
    protected $transactionBase;

    function __construct(array $transaction, array $transactionBase)
    {
        $this->transaction = $transaction;
        $this->transactionBase = $transactionBase;
    }

    public function storeTokens()
    {
        if (!isset($this->transaction['tokens']) || count($this->transaction['tokens']) == 0) {
            return;
        }
        $this->saveTokens($this->getTokens());
    }

    public function getTokens()
    {
        $tokens = [];

        foreach ($this->transaction['tokens'] as $token) {
            $tokens[] = [
                'merchant' => $this->transaction['merchant'],
                'orderRef' => $this->transaction['orderRef'],
                'transactionId' => $this->transaction['transactionId'],
                'tokenRegDate' => @date("c", time()),
                'customerEmail' => $this->transactionBase['customerEmail'],
                'token' => $token,
                'until' => $this->transactionBase['recurring']['until'],
                'maxAmount' => $this->transactionBase['recurring']['maxAmount'],
                'currency' => $this->transaction['currency'],
                'tokenState' => 'stored',
            ];
        }

        return $tokens;
    }

    protected function saveTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            $this->saveToken($token);
        }
    }

    abstract protected function saveToken(array $token);
}