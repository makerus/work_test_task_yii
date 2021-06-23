<?php


namespace backend\controllers;


use backend\exceptions\InvalidRequestJsonStatus;
use common\models\UrlStatus;
use makerus\urlstatus\RequestStatus;
use makerus\urlstatus\UrlContainer;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class CheckStatusController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIndex()
    {
        $body = $this->request->rawBody;

        # Подключаем класс с реализацией CURL
        $requestStatus = new RequestStatus();

        # Проверка типа отправляемых данных
        try {
            if (!$body or $this->request->contentType !== 'application/json') {
                throw new InvalidRequestJsonStatus();
            }
        } catch (InvalidRequestJsonStatus $exception) {
            return $this->asJson(['error' => $exception->getMessage()]);
        }

        # Проверка соответствия ожидаемой структуры и структуры полученной от пользователя
        try {
            $bodyJson = json_decode($body);
            if (!property_exists($bodyJson, 'url')) {
                throw new InvalidRequestJsonStatus();
            }
        } catch (InvalidRequestJsonStatus $exception) {
            return $this->asJson(['error' => $exception->getMessage()]);
        }

        # Создаем контейнер с массивом url элементов полученных из запроса
        $urlContainer = new UrlContainer();
        $urlContainer->fromArray($bodyJson->url);

        # Ищем в базе все переданные url элементы
        $urlStatusRecords = UrlStatus::getByArrayUrls($urlContainer->get());

        # Из полученных объектов получаем массив нужного вида и проверяем, какие элементы уже есть,
        # а какие элементы необходимо добавить
        $onlyUrl = UrlStatus::getArrayWithField('url', $urlStatusRecords);
        $notFoundUrls = $urlContainer->diff($onlyUrl);

        if ($notFoundUrls) {
            # Если таких url элементов нет в базе данных - создаем их
            foreach ($notFoundUrls as $url) {
                $urlStatus = new UrlStatus();
                $statusCode = $requestStatus->getStatus($url); # отправляем запрос по url и получаем статус ответа
                $urlStatus->create($url, $statusCode);
            }
        } else {
            # Если такие url элементы уже есть
            foreach ($urlStatusRecords->all() as $url) {
                $isElapsed10Minute = (time() - $url->updated_at) > (60*10);

                # проверяем, прошло ли 10 минут с последнего обновления
                if ($isElapsed10Minute) {
                    # обновляем статус, увеличиваем значение поля query_count и обновляем время updated_at
                    $statusCode = $requestStatus->getStatus($url->url);
                    $url->updateWithStatus($statusCode);
                } else {
                    # толь увеличиваем значение поля query_count
                    $url->updateWithoutStatus();
                }
            }
        }

        # Формируем ответ
        $response = array();
        foreach ($urlStatusRecords->all() as $record) {
            $response[] = ['url' => $record->url, 'code' => $record->status];
        }
        return $this->asJson(['codes' => $response]);
    }
}