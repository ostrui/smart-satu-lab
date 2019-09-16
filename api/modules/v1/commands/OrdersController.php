<?php

namespace app\api\modules\v1\commands;

use yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Orders;
use app\models\OrderProduct;

class OrdersController extends Controller
{
	/**
	 * This command echoes what you have entered as the message.
	 * @param string $message the message to be echoed.
	 * @return int Exit code
	 */
	public function actionIndex($message = 'Hello SmartSatu')
	{
		echo $message . "\n";

		return ExitCode::OK;
	}

	public function actionGet()
	{

		shell_exec('chcp 65001');

		$orders = Orders::find()->asArray()->all();

		if(!$orders) {
			echo 'No Orders' . "\n";

			return ExitCode::UNSPECIFIED_ERROR;
		}


		foreach ($orders as $order) {
			$orderData = OrderProduct::find()->where(['order_id' => $order['id']])->all();
			if (!$orderData) {
				echo 'No Orders Details' . "\n";
				return ExitCode::UNSPECIFIED_ERROR;

			}

			$amount = 0;
			foreach ($orderData as $item) {
				$data['orders'][] = array(
					'name' => $item->name,
					'price' => $item->product->price,
					'qty' => $item->qty,
					'total' => $item->amount,
				);
				$amount += $item->amount;
			}

			$data['amount'] = $amount;
			echo 'Заказ # '.$order['id']. "\n";
			echo "\n";
			echo 'Статус: '. $order['status'] . "\n";
			echo "\n";
			echo 'Детали '. "\n";
			echo "\n";
			foreach ($data['orders'] as $details) {
				echo ' Товар: '.$details['name']. "\n";
				echo ' Цена: '.$details['price']. "\n";
				echo ' Количество: '.$details['qty']. "\n";
				echo ' Итого: '.$details['total']. "\n";
				echo "\n";
			}
			echo 'Сумма: '.$data['amount'];
		}


		return ExitCode::OK;
	}
}