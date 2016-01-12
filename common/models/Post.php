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
        $criteria    = new EMongoCriteria();
        $sortedField = isset($params['sorted_field']) ? $params['sorted_field'] : 'created_time';

        if (empty($params['limit'])) {
            $params['limit'] = 25;
        }

        if (!empty($params['pid'])) {
            $criteria->addCondition('pid', $params['pid']);
        }

        if (empty($params['since'])) {
            $criteria->setSort([$sortedField => 'desc']);
        }

        if (!empty($params['since'])) {
            $criteria->addCondition(
                $sortedField,
                (int) $params['since'],
                '$gt'
            );

            $criteria->setSort([$sortedField => 'asc']);
        }

        if (!empty($params['until'])) {
            $criteria->addCondition(
                $sortedField,
                (int)$params['until'],
                '$lt'
            );
            $criteria->setSort([$sortedField=> 'desc']);
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
        unset($attributes['_id'], $attributes['pid']);
        return json_encode($attributes, $options);
    }
}
