<?php

namespace app\api\modules\v1\commands;

use yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Products;

class ProductsController extends Controller
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
		$products = Products::find()->asArray()->all();

		if(!$products) {
			echo 'No Products' . "\n";

			return ExitCode::UNSPECIFIED_ERROR;
		}

		foreach ($products as $id => $product) {
			echo '# '.$id. ':'. $product['name'] . "\n";
		}


		return ExitCode::OK;
	}
}
