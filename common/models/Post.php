<?php

class Post extends EMongoDocument
{
    public function collectionName()
    {
        return 'posts';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function findByParams(array $params)
    {
        $criteria = new EMongoCriteria();

        if (empty($params['limit'])) {
            $params['limit'] = 25;
        }

        if (!empty($params['uid'])) {
            $criteria->addCondition('uid', $params['uid']);
        }

        if (empty($params['since'])) {
            $criteria->setSort(['created_time' => 'desc']);
        }

        if (!empty($params['since'])) {
            $criteria->addCondition(
                'created_time',
                (int) $params['since'],
                '$gt'
            );

            $criteria->setSort(['created_time' => 'asc']);
        }

        if (!empty($params['until'])) {
            $criteria->addCondition(
                'created_time',
                (int)$params['until'],
                '$lt'
            );
            $criteria->setSort(['created_time' => 'desc']);
        }

        $criteria->setLimit($params['limit']);
        return $this->find($criteria);
    }

    public function batchInsert(array $data, array $options = [])
    {
        $options = CMap::mergeArray(
            $options,
            $this->getDbConnection()->getDefaultWriteConcern()
        );
        $this->getCollection()->batchInsert($data, $options);
    }

    public function asJson($options = 0)
    {
        $attributes = $this->getAttributes();
        unset($attributes['_id'], $attributes['uid']);
        return json_encode($attributes, $options);
    }
}