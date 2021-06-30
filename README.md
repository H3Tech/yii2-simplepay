# Yii2 SimplePay
This extension gives you the ability to easily use the SimplePay SDK in your Yii project.

## Installation
The extension can be installed via Composer.

### Adding the repository
Add this repository in your composer.json file, like this:
```
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/H3Tech/yii2-simplepay"
    }
],
```
### Adding dependency
Add an entry for the extension in the require section in your composer.json:
```
"h3tech/yii2-simplepay": "~2.0"
```
After this, you can execute `composer update` in your project directory to install the extension.

### Enabling the component
You can use the classes of the SDK right away. If you would like to configure the SDK globally (not specifying the configuration every time you create an instance of one of the classes), you can use the built-in component.

The component must be enabled in Yii's configuration by adding an entry for it in the components section, for example:
```
'components' => [
    'simplePay' => [
        'class' => 'h3tech\simplePay\SimplePay',
        'sdkConfig' => [
            'EUR_MERCHANT' => '<MERCHANT_ID>',
            'EUR_SECRET_KEY' => '<SECRET_KEY>',
            'URL' => ['/order/status'],
            'URLS_TIMEOUT' => ['/order/status'],
        ],
        'defaultPaymentPageLanguage' => 'HU',
    ],
],
```

Please refer to the [SimplePay SDK documentation](http://simplepartner.hu/download.php?target=dochu) for more information on how to configure the SDK.  
For URL configuration (e.g. URL, URLS_TIMEOUT) you can use a Yii style route which will be processed by a Url::to() call.

You can use the component for example the following way:

```php
use h3tech\simplePay\SimplePay;
use yii\helpers\ArrayHelper;

/** @var SimplePay $simplePay */
$simplePay = Yii::$app->get('simplePay');
$ipn = $simplePay->createSimplePayIpn();

if ($ipn->isIpnSignatureCheck(($body = Yii::$app->request->rawBody))) {
    $message = json_decode($body, true);
    $orderId = ArrayHelper::getValue($message, 'orderRef');
    // TODO: Finalize order
    echo $ipn->runIpnConfirm();
} else {
    echo 'IPN request is not valid';
}
```
Most frequently used Simple classes have a corresponding function in the component to make them easy to use with the global configuration.

The initialization of the Simple class from the above example would look like this without the function of the component:

```php
use h3tech\simplePay\SimplePay;
use h3tech\simplePay\sdk\v2\SimplePayIpn;

/** @var SimplePay $simplePay */
$simplePay = Yii::$app->get('simplePay');

$ipn = new SimplePayIpn();
$ipn->addConfig($simplePay->sdkConfig);
```
Where the ``$config`` is the array of options for the SDK, possibly stored in the application's params.php.