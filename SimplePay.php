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
 * @property array $sdkConfig
 * @property array $defaultPaymentPageLanguage
 * @property array $defaultPaymentMethod
 */
class SimplePay extends Component
{
    const SUPPORTED_LANGUAGES = ['CZ', 'DE', 'EN', 'IT', 'HR', 'HU', 'PL', 'RO', 'SK'];
    const SUPPORTED_PAYMENT_METHODS = ['CCVISAMC', 'WIRE'];

    protected $sdkConfig;
    protected $defaultPaymentPageLanguage = 'EN';
    protected $defaultPaymentMethod = 'CCVISAMC';

    public function getSdkConfig()
    {
        return $this->sdkConfig;
    }

    /**
     * @param $config
     * @throws InvalidConfigException
     */
    public function setSdkConfig(array $config)
    {
        if (!is_array($config)) {
            throw new InvalidConfigException('The options property must be any array');
        }

        foreach ($config as $key => $value) {
            if (($key === 'BACK_REF' || preg_match('/^[A-Z_]+_URL$/', $key)) && is_array($value)) {
                $config[$key] = preg_replace('/^\/{2}/', '', Url::to($value, ''));
            }
        }

        $this->sdkConfig = array_merge([
            'SANDBOX' => true,
            'PROTOCOL' => 'http',
            'CURL' => true,
            'GET_DATA' => $_GET,
            'POST_DATA' => $_POST,
            'SERVER_DATA' => $_SERVER,
            'LOGGER' => false,
        ], $config);
    }

    public function getDefaultPaymentPageLanguage()
    {
        return $this->defaultPaymentPageLanguage;
    }

    public function setDefaultPaymentPageLanguage($paymentPageLanguage)
    {
        $paymentPageLanguage = strtoupper($paymentPageLanguage);

        if (!in_array($paymentPageLanguage, static::SUPPORTED_LANGUAGES)) {
            throw new InvalidConfigException(
                'The default payment page language property must have a valid value from the following: '
                . join(', ', static::SUPPORTED_LANGUAGES)
            );
        }

        $this->defaultPaymentPageLanguage = $paymentPageLanguage;
    }

    public function getDefaultPaymentMethod()
    {
        return $this->defaultPaymentMethod;
    }

    public function setDefaultPaymentMethod($paymentMethod)
    {
        $paymentMethod = strtoupper($paymentMethod);

        if (!in_array($paymentMethod, static::SUPPORTED_PAYMENT_METHODS)) {
            throw new InvalidConfigException(
                'The default payment method property must have a valid value from the following: '
                . join(', ', static::SUPPORTED_PAYMENT_METHODS)
            );
        }

        $this->defaultPaymentMethod = $paymentMethod;
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
            $result = ['error' => $parameters['err']];
        } else {
            $backref->checkResponse();
            $result = $backref->backStatusArray;
        }

        return $result;
    }

    public function getValidPaymentPageLanguage($language)
    {
        $language = strtoupper($language);
        return in_array($language, static::SUPPORTED_LANGUAGES) ? $language : $this->defaultPaymentPageLanguage;
    }
}
