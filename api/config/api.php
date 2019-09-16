<?php

$db     = require(__DIR__ . '/../../config/db.php');
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'SmartSatu',
    // Need to get one level up:
    'basePath' => dirname(__DIR__).'/..',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // Enable JSON Input:
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => 'json',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $data = $response->data;
                    // Error handle
                    $error = '';
                    if(!$response->isSuccessful) {
                        if(isset($data['message'])) {
                            $error = $data['message'];
                        } elseif(isset(current($data)['message'])) {
                            $error = current($data)['message'];
                        }
                    }
                    $response->data = [
                        'status' => !empty($response->isSuccessful) ? $response->isSuccessful : 'ER',
                        'code' => $response->statusCode,
                        'error' => $error,
                    ];
                    if($response->isSuccessful) {
                        $response->data['data'] = $data;
                    }
                    // $response->statusCode = 200;
                }
            },
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    // Create API log in the standard log dir
                    // But in file 'api.log':
                    'logFile' => '@app/runtime/logs/api.log',
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/products'],
                    'extraPatterns' => [
                        'POST add-to-cart' => 'add-to-cart',
                        'POST clear-cart' => 'clear-cart',
                        'POST remove-item' => 'remove-item'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/orders'],
                    'extraPatterns' => [
                        'POST save-order' => 'save-order',
                        'POST get-order' => 'get-order',
                        'POST get-orders' => 'get-orders'
                    ]
                ],
            ],
        ],
        'db' => $db,
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\Module',
        ],
    ],
    'params' => $params,
];

return $config;
