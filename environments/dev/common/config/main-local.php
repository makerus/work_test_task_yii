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

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
