<?php

namespace h3tech\simplePay;

use h3tech\simplePay\sdk\SimpleOneClick;
use h3tech\simplePay\sdk\v2\SimplePayBack;
use h3tech\simplePay\sdk\v2\SimplePayIpn;
use h3tech\simplePay\sdk\v2\SimplePayQuery;
use h3tech\simplePay\sdk\v2\SimplePayStart;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

use h3tech\simplePay\sdk\SimpleLiveUpdate;
use h3tech\simplePay\sdk\SimpleBackRef;
use h3tech\simplePay\sdk\SimpleIos;
use h3tech\simplePay\sdk\SimpleIpn;
use h3tech\simplePay\sdk\SimpleIdn;
use h3tech\simplePay\sdk\SimpleIrn;

use yii\console\Application;
use yii\helpers\Url;

/**
 * @property array $sdkConfig
 * @property array $defaultPaymentPageLanguage
 * @property array $defaultPaymentMethod
 */
class SimplePay extends Component
{
    const SUPPORTED_LANGUAGES = ['CZ', 'DE', 'EN', 'IT', 'HR', 'HU', 'PL', 'RO', 'SK'];
    const PAYMENT_METHOD_CARD = 'CARD';
    const PAYMENT_METHOD_WIRE = 'WIRE';

    protected $sdkConfig;
    protected $defaultPaymentPageLanguage = 'EN';
    protected $defaultPaymentMethod = 'CARD';

    public function getSdkConfig()
    {
        return $this->sdkConfig;
    }

    public function generateCallbackUrl($routeDefinition)
    {
        return preg_replace('/^\/{2}/', '', Url::to($routeDefinition, true));
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

        if (!(Yii::$app instanceof Application)) {
            foreach ($config as $key => $value) {
                if (($key === 'URL' || preg_match('/^URLS_[A-Z_]+$/', $key)) && is_array($value)) {
                    $config[$key . '_ROUTE'] = $value;
                    $config[$key] = $this->generateCallbackUrl($value);
                }
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

    public static function supportedPaymentMethods()
    {
        return [static::PAYMENT_METHOD_CARD, static::PAYMENT_METHOD_WIRE];
    }

    public function setDefaultPaymentMethod($paymentMethod)
    {
        $paymentMethod = strtoupper($paymentMethod);

        $supportedMethods = static::supportedPaymentMethods();
        if (!in_array($paymentMethod, $supportedMethods)) {
            throw new InvalidConfigException(
                'The default payment method property must have a valid value from the following: '
                . join(', ', $supportedMethods)
            );
        }

        $this->defaultPaymentMethod = $paymentMethod;
    }

    protected function generateConfigArray($config = null)
    {
        return is_array($config) ? array_merge($this->sdkConfig, $config) : $this->sdkConfig;
    }

    public function createLiveUpdate($currency = '', array $config = null)
    {
        return new SimpleLiveUpdate($this->generateConfigArray($config), $currency);
    }

    public function createBackRef($currency = '', array $config = null)
    {
        return new SimpleBackRef($this->generateConfigArray($config), $currency);
    }

    public function createIos($currency = '', $orderNumber = 'N/A', array $config = null)
    {
        return new SimpleIos($this->generateConfigArray($config), $currency, $orderNumber);
    }

    public function createIpn($currency = '', array $config = null)
    {
        return new SimpleIpn($this->generateConfigArray($config), $currency);
    }

    public function createIdn($currency = '', array $config = null)
    {
        return new SimpleIdn($this->generateConfigArray($config), $currency);
    }

    public function createIrn($currency = '', array $config = null)
    {
        return new SimpleIrn($this->generateConfigArray($config), $currency);
    }

    public function createOneClick($currency = '', array $config = null)
    {
        return new SimpleOneClick($this->generateConfigArray($config), $currency);
    }

    /** @return string|array */
    public function processBackRef(array $parameters = null)
    {
        if ($parameters === null) {
            $parameters = Yii::$app->request->queryParams;
        }

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

    public function createSimplePayStart($currency = '', array $config = null)
    {
        $simplePayStart = new SimplePayStart();
        $simplePayStart->addData('currency', $currency);
        $simplePayStart->addConfig($this->generateConfigArray($config));
        return $simplePayStart;
    }

    public function createSimplePayBack(array $config = null)
    {
        $simplePayBack = new SimplePayBack();
        $simplePayBack->addConfig($this->generateConfigArray($config));
        return $simplePayBack;
    }

    public function createSimplePayQuery($merchantId, $transactionId, $orderId, array $config = null)
    {
        $simplePayQuery = new SimplePayQuery();
        $simplePayQuery->addConfig($this->generateConfigArray($config));
        $simplePayQuery->addMerchantOrderId($orderId);
        $simplePayQuery->addSimplePayId($transactionId);
        $simplePayQuery->addConfigData('merchantAccount', $merchantId);
        return $simplePayQuery;
    }

    public function createSimplePayIpn(array $config = null)
    {
        $simplePayIpn = new SimplePayIpn();
        $simplePayIpn->addConfig($this->generateConfigArray($config));
        return $simplePayIpn;
    }
}
