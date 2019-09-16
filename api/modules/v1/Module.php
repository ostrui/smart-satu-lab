<?php

namespace app\api\modules\v1;

use yii\base\BootstrapInterface;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{

    const JSON_HEADER = 'Content-Type: application/json';
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\api\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        header(self::JSON_HEADER);
    }

	public function bootstrap($app)
	{
		if ($app instanceof \yii\console\Application) {
			$this->controllerNamespace = 'app\api\modules\v1\commands';
		}
	}
}
