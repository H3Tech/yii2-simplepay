<?php

namespace h3tech\simplePay;

use yii\base\Component;
use yii\base\InvalidConfigException;

use h3tech\simplePay\sdk\SimpleLiveUpdate;
use h3tech\simplePay\sdk\SimpleBackRef;
use h3tech\simplePay\sdk\SimpleIos;
use h3tech\simplePay\sdk\SimpleIpn;
use h3tech\simplePay\sdk\SimpleIdn;
use h3tech\simplePay\sdk\SimpleIrn;

/**
 * @property array $config
 */
class SimplePay extends Component
{
    protected $config;

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $config
     * @throws InvalidConfigException
     */
    public function setConfig($config)
    {
        if (!is_array($config)) {
            throw new InvalidConfigException('The options property must be any array');
        }

        $this->config = $config;
    }

    public function createLiveUpdate($currency = '', $config = null)
    {
        return new SimpleLiveUpdate(is_array($config) ? $config : $this->config, $currency);
    }

    public function createBackRef($currency = '', $config = null)
    {
        return new SimpleBackRef(is_array($config) ? $config : $this->config, $currency);
    }

    public function createIos($currency = '', $orderNumber = 'N/A', $config = null)
    {
        return new SimpleIos(is_array($config) ? $config : $this->config, $currency, $orderNumber);
    }

    public function createIpn($currency = '', $config = null)
    {
        return new SimpleIpn(is_array($config) ? $config : $this->config, $currency);
    }

    public function createIdn($currency = '', $config = null)
    {
        return new SimpleIdn(is_array($config) ? $config : $this->config, $currency);
    }

    public function createIrn($currency = '', $config = null)
    {
        return new SimpleIrn(is_array($config) ? $config : $this->config, $currency);
    }
}
