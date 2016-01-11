<?php

class EFacebookFeedResponse
{
    protected $data;

    protected $params;

    protected $fields = [];

    protected $defaultFields = [
        'story',
        'message',
        'updated_time',
        'created_time',
        'id'
    ];

    public function __construct(array $data = [], array $params = [])
    {
        if (!empty($params['fields'])) {
            $this->fields = explode(',', $params['fields']);
        }

        $this->data = $data;
        $this->params = $params;
    }

    public function asJson($options = 0)
    {
        $return = ['data' => []];

        if (!$this->data) {
            return json_encode($return, $options);
        }

        foreach ($this->data as $item) {
            $return['data'][] = $this->prepareItem($item);
        }

        if (!($host = Yii::app()->params['host'])) {
            $protocol = (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
            $host = "{$protocol}://{$_SERVER['HTTP_HOST']}";
        }

        $params = [];

        if (!empty($this->params['limit'])) {
            $params['limit'] = $this->params['limit'];
        }

        if (!empty($this->fields)) {
            $params['fields'] = implode(',', $this->fields);
        }

        $prevParams = http_build_query(
            CMap::mergeArray(
                $params,
                [
                    'since'      => $this->getSince(),
                    '__previous' => 1
                ]
            )
        );
        $nextParams = http_build_query(
            CMap::mergeArray(
                $params,
                ['until' => $this->getUntil()]
            )
        );

        $endPoint = "{$host}/{$this->getUserId()}/feed";
        $return['paging']['previous'] = "{$endPoint}?{$prevParams}";
        $return['paging']['next'] = "{$endPoint}?{$nextParams}";

        return json_encode($return, $options);
    }

    public function getUserId()
    {
        return reset($this->data)['uid'];
    }

    protected function getUntil()
    {
        return end($this->data)['created_time'];
    }

    protected function getSince()
    {
        return reset($this->data)['created_time'];
    }

    protected function prepareItem($item)
    {
        if ($item instanceof Post) {
            $fields = !empty($this->fields) ? array_merge($this->fields, ['id']) : $this->defaultFields;
            $item  = $item->getAttributes($fields);
        }

        $item = array_filter($item);

        if (!empty($item['created_time'])) {
            $item['created_time'] = gmdate('c', $item['created_time']);
        }

        if (!empty($item['updated_time'])) {
            $item['updated_time'] = gmdate('c', $item['updated_time']);
        }

        return $item;
    }

    public function __toString()
    {
        return $this->asJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}