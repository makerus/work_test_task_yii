<?php


namespace backend\controllers;


use backend\soap\UrlStatusSoap;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SoapEndpointController extends Controller
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
                    'index' => ['get', 'post'],
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
        $path = dirname(__DIR__).'/web/wsdl/url_status.wsdl';

        # если обращаемся по url soap-endpoint?wsdl
        if ($this->request->getIsGet() and $this->request->get('wsdl', false) !== false) {
            # показываем wsdl
            ob_start(null, 0, PHP_OUTPUT_HANDLER_CLEANABLE);
            $this->response->format = $this->response::FORMAT_XML;
            require_once($path);
            $this->response->send();
        }

        # если обращаемся по url soap-endpoint
        if ($this->request->getIsPost()) {
            # обрабатываем как запрос к soap сервису
            ob_start(null, 0, PHP_OUTPUT_HANDLER_CLEANABLE);
            $soap = new \SoapServer($path, [
                'soap_version' => SOAP_1_2,
                'cache_wsdl' => WSDL_CACHE_NONE
            ]);

            $soap->setClass(UrlStatusSoap::class);
            $soap->handle();
        }
    }
}