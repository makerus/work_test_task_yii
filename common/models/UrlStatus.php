<?php

namespace common\models;

use yii\db\ActiveRecord;

class UrlStatus extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%url_status}}';
    }

    /**
     * Создание записи с url
     * @param string $url
     * @param $statusCode
     */
    public function create(string $url, $statusCode)
    {
        $this->hash_string = md5($url);
        $this->url = $url;
        $this->status = $statusCode;
        $this->query_count = 1;
        $this->created_at = time();
        $this->updated_at = time();
        $this->save();
    }

    /**
     * Обновление url с изменение статуса
     * @param int $statusCode
     */
    public function updateWithStatus(int $statusCode)
    {
        $url = $this->url;
        $count = ++$this->query_count;

        static::updateAll([
            'status' => $statusCode,
            'query_count' => $count,
            'updated_at' => time(),
        ], "url = '${url}'");
    }

    /**
     * Обновление url без изменения статуса
     */
    public function updateWithoutStatus()
    {
        $url = $this->url;
        $count = ++$this->query_count;

        static::updateAll([
            'query_count' => $count,
        ], "url = '${url}'");
    }

    /**
     * Возвращает переданный список url в виде объектов базы данных
     * @param $urls
     * @return \yii\db\ActiveQuery
     */
    public static function getByArrayUrls($urls)
    {
        return UrlStatus::find()
            ->select('url,status,query_count,updated_at')
            ->where(['url' => $urls]);
    }

    /**
     * Возвращает новый массив с единственным полем
     * @param $field
     * @param $records
     * @return array
     */
    public static function getArrayWithField($field, $records)
    {
        $records = clone $records;
        return array_map(function ($url) use ($field) {
            return $url[$field];
        }, $records->asArray()->all());
    }

    /**
     * Возвращает url, который соответствует переданному значению статуса
     * а также находится в пределах необходимого времени
     * @param $beginDate
     * @param $endDate
     * @param int $statusCode
     * @return \yii\db\ActiveQuery
     */
    public static function getRangeDateWithStatus($beginDate, $endDate, int $statusCode)
    {
        return UrlStatus::find()
            ->select('url,status,updated_at')
            ->where(['between', 'updated_at', $beginDate, $endDate])
            ->andWhere(['status' => $statusCode]);
    }

    /**
     * Возвращает url, который находится в пределах необходимого времени
     * @param $beginDate
     * @param $endDate
     * @param int $statusCode
     * @return \yii\db\ActiveQuery
     */
    public static function getRangeDateExpectStatus($beginDate, $endDate, int $statusCode)
    {
        return UrlStatus::find()
            ->select('url,status,updated_at')
            ->where(['between', 'updated_at', $beginDate, $endDate])
            ->andWhere("status != '${statusCode}'");
    }
}