<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.09.2019
 * Time: 18:55
 */

namespace app\api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\api\modules\v1\services\OrdersService;

class OrdersController extends ActiveController
{
    public $modelClass = 'app\models\Orders';

    public function actionSaveOrder()
    {
        $ordersService = new OrdersService($this, $_POST);
        if(!$response = $ordersService->saveOrder()) {
            $ordersService->getJsonError();
            exit();
        }

        $ordersService->getJsonResponse($response);
        exit();
    }

    public function actionGetOrders()
    {
        $ordersService = new OrdersService($this, $_POST);
        if(!$response = $ordersService->getOrders()) {
            $ordersService->getJsonError();
            exit();
        }

        $ordersService->getJsonResponse($response);
        exit();
    }

    public function actionGetOrder()
    {
        $ordersService = new OrdersService($this, $_POST);
        if(!$response = $ordersService->getOrder()) {
            $ordersService->getJsonError();
            exit();
        }

        $ordersService->getJsonResponse($response);
        exit();
    }
}