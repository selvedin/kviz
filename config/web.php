<?php
define("IS_MOBILE", preg_match("/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", strtolower(@$_SERVER['HTTP_USER_AGENT'])));
$params = require __DIR__ . '/params.php';
// $db = require __DIR__ . '/db_live.php';
$db = require __DIR__ . '/db.php';
$cache = [
    'class' => 'yii\caching\MemCache',
    'useMemcached' => true, // <--- here
    'servers' => [
        [
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 60,
        ],
    ],
];
if (in_array($_SERVER['SERVER_NAME'], ['localhost', 'quiz.local'])) {

    $cache = [
        'class' => 'yii\caching\ApcCache',
        'keyPrefix' => 'eduApp',       // a unique cache key prefix
        'useApcu' => true,
    ];
}
$config = [
    'id' => 'eduApp',
    'name' => 'Kviz',
    'basePath' => dirname(__DIR__),
    'vendorPath' => '../../vendor',
    'bootstrap' => ['log'],
    'language' => isset($_COOKIE['lng']) ? $_COOKIE['lng'] : 'bs',
    'aliases' => [
        // '@vendor' => dirname(dirname(__DIR__)) . '../../vendor',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $_ENV['COOKIE_KEY'],
        ],
        'cache' => $cache,
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['bookLog'],
                    'logFile' => '@app/runtime/logs/travel.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 50,
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                "pocetna" => "site/home",
                "prijava" => "site/login",
                "registracija" => "site/signup",
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module']
    ],
    'as access' => [
        'class' => \yii\filters\AccessControl::class, //AccessControl::className(),
        'except' => [
            // 'books/index',

        ],
        'rules' => [
            [
                'actions' => [
                    'home',
                    'contact',
                    'cards',
                    'about',
                    'login',
                    'view',
                    'index',
                    'signup',
                    'request-password-reset',
                    'reset-password',
                    'verify-email',
                    'reset-verification-email',
                    'error',
                    'policy',
                    'comingsoon'
                ],
                'allow' => true,
            ],
            [
                // 'actions' => ['logout'], // add all actions to take guest to login page
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
