<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.09.2019
 * Time: 20:33
 */

namespace app\api\modules\v1\services;
use app\models\Products;
use Yii;

class ProductsService extends Service
{
    private $_controller;
    private $_data;
    public $_error;

    public function __construct($controller, $data)
    {
        $this->_controller = $controller;
        $this->_data = $data;
    }

    public function addToCart()
    {

        $productId = $this->_data['id'];
        $qty = $this->_data['qty'];
        if(!is_numeric($productId)) {
            $this->_error = 'Переданный идентификационный номер товара не является числом';
            return false;
        }
        $product = Products::findOne($productId);
        if(!$product) {
            $this->_error = 'Невозможно добавить в корзину. Товар не найден.';
            return false;
        }
        $session = Yii::$app->session;
        $session->open();
        if(isset($_SESSION['cart'][$product->id])){
            $_SESSION['cart'][$product->id]['qty'] += $qty;
            $_SESSION['cart'][$product->id]['total'] += $product->price * $qty;
        } else {
            $_SESSION['cart'][$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $qty,
                'total' => $product->price * $qty,
            ];
        }
        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $product->price : $qty * $product->price;
        $session->close();

        $data = array(
            'cart' => array_values($_SESSION['cart']),
            'cart.qty' => $_SESSION['cart.qty'],
            'cart.sum' => number_format($_SESSION['cart.sum'], 2)
        );

        $response = array(
            'message' => 'Товар '.$product->name.' успешно добавлен в корзину',
            'data' => $data
        );

        return $response;

    }

    public function deleteItemFromCart()
    {

        $productId = $this->_data['id'];
        $qty = $this->_data['qty'];
        if(!is_numeric($productId)) {
            $this->_error = 'Переданный идентификационный номер товара не является числом';
            return false;
        }

        $session = Yii::$app->session;
        $session->open();
        if(!isset($_SESSION['cart'][$productId])){
            $this->_error = 'В корзине нет товара, который Вы хотите удалить.';
            return false;
        }

        $message = 'Товар '.$_SESSION['cart'][$productId]['name'].' в кол-ве '.$qty.' удален из корзины';
        $_SESSION['cart.qty'] -= $qty;
        $_SESSION['cart.sum'] -= $_SESSION['cart'][$productId]['price'] * $qty;

        if($_SESSION['cart'][$productId]['qty'] <= $qty) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId]['qty'] -= $qty;
            $_SESSION['cart'][$productId]['total'] -= $_SESSION['cart'][$productId]['price'] * $qty;
        }

        if(empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
            unset($_SESSION['cart.qty']);
            unset($_SESSION['cart.sum']);
        } else {
            $data = array(
                'cart' => array_values($_SESSION['cart']),
                'cart.qty' => $_SESSION['cart.qty'],
                'cart.sum' => number_format($_SESSION['cart.sum'], 2)
            );
        }
        $session->close();

        $response = array(
            'message' => $message,
            'data' => $data
        );

        return $response;

    }

    public function clearCart()
    {

        $session =Yii::$app->session;
        $session->open();
        $session->remove('cart');
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        $session->close();

        if(isset($_SESSION['cart']) || isset($_SESSION['cart.qty']) || isset($_SESSION['cart.sum'])) {
            $this->_error = 'Возникла ошибка. Корзина не очищена.';
            return false;
        }

        return true;

    }



}