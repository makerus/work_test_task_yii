<?php


namespace console\controllers;


use common\models\UrlStatus;
use yii\console\Controller;

class CheckStatusController extends Controller
{
    public function actionStatistics()
    {
        $today = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
        $beginDay = $today->setTime(0, 0, 0, 0)->format('U');
        $endDay = $today->setTime(23, 59, 59)->format('U');
        echo "Статистика за дату: " . $today->format('d.m.y') . PHP_EOL;
        $dayUrlStatus = UrlStatus::getRangeDateExpectStatus($beginDay, $endDay, 200);
        foreach ($dayUrlStatus->all() as $current) {
            echo '[url]: ' . $current->url . ', [status code]: ' . $current->status . PHP_EOL;
        }
    }
}