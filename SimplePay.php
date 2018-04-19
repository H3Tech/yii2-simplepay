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

use yii\helpers\Url;

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
    public function setConfig(array $config)
    {
        if (!is_array($config)) {
            throw new InvalidConfigException('The options property must be any array');
        }

        foreach ($config as $key => $value) {
            if (($key === 'BACK_REF' || preg_match('/^[A-Z_]+_URL$/', $key)) && is_array($value)) {
                $config[$key] = preg_replace('/^\/{2}/', '', Url::to($value, ''));
            }
        }

        $this->config = array_merge([
            'SANDBOX' => true,
            'PROTOCOL' => 'http',
            'CURL' => true,
            'GET_DATA' => $_GET,
            'POST_DATA' => $_POST,
            'SERVER_DATA' => $_SERVER,
            'LOGGER' => false,
        ], $config);
    }

    public function createLiveUpdate($currency = '', array $config = null)
    {
        return new SimpleLiveUpdate(is_array($config) ? $config : $this->config, $currency);
    }

    public function createBackRef($currency = '', array $config = null)
    {
        return new SimpleBackRef(is_array($config) ? $config : $this->config, $currency);
    }

    public function createIos($currency = '', $orderNumber = 'N/A', array $config = null)
    {
        return new SimpleIos(is_array($config) ? $config : $this->config, $currency, $orderNumber);
    }

    public function createIpn($currency = '', array $config = null)
    {
        return new SimpleIpn(is_array($config) ? $config : $this->config, $currency);
    }

    public function createIdn($currency = '', array $config = null)
    {
        return new SimpleIdn(is_array($config) ? $config : $this->config, $currency);
    }

    public function createIrn($currency = '', array $config = null)
    {
        return new SimpleIrn(is_array($config) ? $config : $this->config, $currency);
    }

    /** @return string|array */
    public function processBackRef(array $parameters)
    {
        $orderCurrency = isset($parameters['order_currency']) ? $parameters['order_currency'] : 'N/A';
        $backref = $this->createBackRef($orderCurrency);
        $backref->order_ref = isset($parameters['order_ref']) ? $parameters['order_ref'] : 'N/A';

        if (isset($parameters['err'])) {
            $result = $parameters['err'];
        } else {
            $backref->checkResponse();
            $result = $backref->backStatusArray;
        }

        return $result;
    }
}
