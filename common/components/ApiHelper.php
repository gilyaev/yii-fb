<?php

use Facebook\GraphNodes\GraphEdge;

class ApiHelper
{
    public static function toPosts(GraphEdge $postsEdge)
    {
        $posts   = [];
        $user_id = null;
        foreach ($postsEdge as $item) {
            $data = [];

            if (empty($user_id)) {
                $id = explode('_', $item->getProperty('id'));
                $user_id = $id[0];
            }

            $attributes = $item->asArray();
            foreach ($attributes as $name => $value) {
                if($value instanceof DateTime) {
                    $data[$name] = $value->getTimestamp();
                    continue;
                }

                $data[$name] = $value;
            }

            $data['uid']  = $user_id;
            $posts[] = $data;
        }
        return $posts;
    }
}