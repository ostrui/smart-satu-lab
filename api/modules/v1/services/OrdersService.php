<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15.09.2019
 * Time: 17:08
 */

namespace app\api\modules\v1\services;
use app\models\Orders;
use app\models\OrderProduct;
use Yii;

class OrdersService extends Service
{

    private $_controller;
    private $_data;
    public $_error;

    public function __construct($controller, $data)
    {
        $this->_controller = $controller;
        $this->_data = $data;
    }

    public function getOrder() {

        $orderId = $this->_data['id'];

        if(!is_numeric($orderId)) {
            $this->_error = 'Переданный идентификационный номер заказа не является числом';
            return false;
        }

        $order = Orders::findOne($orderId);
        if(!$order) {
            $this->_error = 'Заказ не найден.';
            return false;
        }
        $orderData = OrderProduct::find()->where(['order_id' => $orderId])->all();
        if(!$orderData) {
            $this->_error = 'Детали заказа не найдены.';
            return false;
        }

        $amount = 0;

        foreach ($orderData as $item) {
            $data['products'][] = array(
                'name' => $item->name,
                'price' => $item->product->price,
                'qty' => $item->qty,
                'total' => $item->amount,
            );
            $amount += $item->amount;
        }

        $data['orderStatus'] = $order->status;
        $data['amount'] = $amount;

        $response = array(
            'message' => 'Заказ № '.$orderId,
            'data' => $data
        );

        return $response;

    }

    public function getOrders() {

        $orders = Orders::find()->all();
        if(!$orders) {
            $this->_error = 'Заказов нет.';
            return false;
        }

        foreach ($orders as $order) {
            $orderData = OrderProduct::find()->where(['order_id' => $order->id])->all();
            if ($orderData) {
                $amount = 0;

                foreach ($orderData as $item) {
                    $data['orders'][$order->id]['products'][] = array(
                        'name' => $item->name,
                        'price' => $item->product->price,
                        'qty' => $item->qty,
                        'total' => $item->amount,
                    );
                    $amount += $item->amount;
                }

                $data['orders'][$order->id]['orderStatus'] = $order->status;
                $data['orders'][$order->id]['amount'] = $amount;
            }
        }

        $data['orders'] = array_values($data['orders']);

        $response = array(
            'message' => 'Список заказов',
            'data' => $data
        );

        return $response;

    }

    public function saveOrder() {

        $session = Yii::$app->session;
        $session->open();
        $orders = new Orders();
        if(isset($session['cart'])){
            if(!$orders->save()){
                $this->_error = 'Заказ не сохранен';
                return false;
            }
            if (!$this->saveOrderData($session['cart'], $orders->id)) {
                $this->_error = 'Заказ не сохранен в связанную таблицу.';
                return false;
            } else {
                unset($session['cart']);
            }
        } else {
            $this->_error = 'Корзина пуста. Заказ не может быть размещен.';
            return false;
        }
        $session->close();

        $message = 'Заказ успешно размещен.';
        $orderData = OrderProduct::find()->where(['order_id' => $orders->id])->all();

        foreach ($orderData as $item) {
            $data['order'][] = array(
                'name' => $item->name,
                'qty' => $item->qty,
                'total' => $item->amount
            );

        }

        $response = array(
            'message' => $message,
            'data' => $data
        );

        return $response;

    }

    public function saveOrderData($data, $orderId) {

        foreach($data as $id => $item){
            $order = new OrderProduct();
            $order->order_id = $orderId;
            $order->product_id = $id;
            $order->name = $item['name'];
            $order->qty = $item['qty'];
            $order->amount = $item['total'];
            if(!$order->save()){
                $this->_error = 'Заказ не сохранен';
                return false;
            }
        }

        return true;

    }

}