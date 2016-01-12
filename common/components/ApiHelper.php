<?php

use Facebook\GraphNodes\GraphEdge;

class ApiHelper
{
    public static function toPosts(GraphEdge $postsEdge)
    {
        $posts = [];
        $profileId = null;
        foreach ($postsEdge as $item) {
            $data = [];

            if (empty($profileId)) {
                $id = explode('_', $item->getProperty('id'));
                $profileId = $id[0];
            }

            $attributes = $item->asArray();
            foreach ($attributes as $name => $value) {
                if ($value instanceof DateTime) {
                    $data[$name] = $value->getTimestamp();
                    continue;
                }

                $data[$name] = $value;
            }

            $data['pid'] = $profileId;
            $posts[] = $data;
        }
        return $posts;
    }
}