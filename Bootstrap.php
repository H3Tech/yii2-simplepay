<?php

namespace h3tech\simplePay;

use yii\base\BootstrapInterface;
use yii\base\Application;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->i18n->translations['h3tech/simplePay/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@h3tech/simplePay/messages',
            'fileMap' => [
                'h3tech/simplePay/simplePay' => 'simplePay.php',
            ],
        ];
    }
}
