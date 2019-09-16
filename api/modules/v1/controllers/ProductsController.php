<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.09.2019
 * Time: 17:42
 */

namespace app\api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use app\api\modules\v1\services\ProductsService;

class ProductsController extends ActiveController
{
    public $modelClass = 'app\models\Products';

    public function actionAddToCart()
    {
        if (Yii::$app->request->post()) {
            $productService = new ProductsService($this, $_POST);
            if(!$cart = $productService->addToCart()) {
                $productService->getJsonError();
                exit();
            }
            $productService->getJsonResponse($cart);
            exit();
        }
    }

    public function actionRemoveItem()
    {
        if (Yii::$app->request->post()) {
            $productService = new ProductsService($this, $_POST);
            if(!$cart = $productService->deleteItemFromCart()) {
                $productService->getJsonError();
                exit();
            }
            $productService->getJsonResponse($cart);
            exit();
        }
    }

    public function actionClearCart()
    {
        $productService = new ProductsService($this, $_POST);
        if(!$productService->clearCart()) {
            $productService->getJsonError();
            exit();
        }

        $message = 'Корзина успешно очищена';
        $productService->getJsonInformMessage($message);
        exit();
    }
}