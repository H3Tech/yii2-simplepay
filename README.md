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
        "url": "https://github.com/H3Tech/yii2-simplePay"
    }
],
```
### Adding dependency
Add an entry for the extension in the require section in your composer.json:
```
"h3tech/yii2-simplePay": "*"
```
After this, you can execute `composer update` in your project directory to install the extension.

### Enabling the component
You can use the classes of the SDK right away. If you would like to configure the SDK globally (not specifying the configuration every time you create an instance of one of the classes), you can use the built-in component.

The component must be enabled in Yii's configuration by adding an entry for it in the components section, for example:
```
'components' => [
    'simplePay' => [
        'class' => 'h3tech\simplePay\SimplePay',
        'config' => [
            'HUF_MERCHANT' => '<MERCHANT_ID>',
            'HUF_SECRET_KEY' => '<SECRET_KEY>',
            'SANDBOX' => true,

            'BACK_REF' => $_SERVER['HTTP_HOST'] . '/backref.php',
            'TIMEOUT_URL' => $_SERVER['HTTP_HOST'] . '/timeout.php',
            'IRN_BACK_URL' => $_SERVER['HTTP_HOST'] . '/irn.php',
            'IDN_BACK_URL' => $_SERVER['HTTP_HOST'] . '/idn.php',
            'IOS_BACK_URL' => $_SERVER['HTTP_HOST'] . '/ios.php',

            'GET_DATA' => $_GET,
            'POST_DATA' => $_POST,
            'SERVER_DATA' => $_SERVER,

            'LOGGER' => false,
        ],
    ],
],
```

Please refer to the [SimplePay SDK documentation](http://simplepartner.hu/download.php?target=dochu) for more information on how to configure the SDK.

You can use the component for example the following way:
```php
$ipn = Yii::$app->get('simplePay')->createIpn();

if ($ipn->validateReceived()) {
    echo 'IPN request is valid';
} else {
    echo 'IPN request is not valid';
}
```
Every Simple class has a corresponding function in the component to make them easy to use with the global configuration.

The above example would look like this without the component:
```php
$config = Yii::$app->params['simplePayConfig'];
$ipn = new SimpleIpn($config);

if ($ipn->validateReceived()) {
    echo 'IPN request is valid';
} else {
    echo 'IPN request is not valid';
}
```
Where the ``$config`` is the array of options for the SDK, possibly stored in the application's params.php.