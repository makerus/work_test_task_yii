<?php
return [
    'components' => [
        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => defined('YII_SQLITE') ? YII_SQLITE : 'mysql:host=mysql_dev;dbname=app_dev',
            'username' => 'app_user',
            'password' => 'app_user',
            'charset' => 'utf8',
        ],
    ],

    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
];
