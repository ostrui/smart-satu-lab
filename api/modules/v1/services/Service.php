<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15.09.2019
 * Time: 11:33
 */

namespace app\api\modules\v1\services;


class Service
{
    public $_error;

    public function getError() {
        return $this->_error;
    }

    public function getJsonError() {
        $response = array('status' => 'ER', 'message' => $this->getError());
        echo json_encode($response,JSON_PRETTY_PRINT);
    }

    public function getJsonResponse($params) {
        $response = array('status' => 'OK', 'message' => $params['message'], 'data' => $params['data']);
        echo json_encode($response,JSON_PRETTY_PRINT);
    }

    public function getJsonInformMessage($message) {
        $response = array('status' => 'OK', 'message' => $message);
        echo json_encode($response,JSON_PRETTY_PRINT);
    }

}