<?php

class EFacebookFields
{

    /**
     * The available post's fields
     * @return array
     */
    public static function getPostFields()
    {
        return [
            'id',
            'admin_creator',
            'application',
            'caption',
            'created_time',
            'description',
            'feed_targeting',
            'from',
            'full_picture',
            'icon',
            'is_hidden',
            'is_published',
            'link',
            'message',
            'message_tags',
            'name',
            'object_id',
            'picture',
            'place',
            'privacy',
            'properties',
            'shares',
            'source',
            'status_type',
            'story',
            'story_tags',
            'targeting',
            'to',
            'type',
            'updated_time',
            'with_tags'
        ];
    }

    /**
     * @param \User $user
     * @return array
     */
    public static function getUserDefaultFields(\User $user)
    {
        return [
            'story',
            'message',
            $user->feed_time_field,
            'id'
        ];
    }
}
