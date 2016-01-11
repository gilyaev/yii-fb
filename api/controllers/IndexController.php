<?php

class IndexController extends CController
{
    public function actionProfile()
    {
        header('Content-Type: application/json; charset=utf-8');
        $alias = (string)strtolower($_GET['user']);
        $user = User::model()->findOne(['alias' => $alias]);

        if ($user) {
            Yii::app()->end(new EFacebookUserResponse($user), true);
        }

        $fb = Yii::app()->facebook;
        try {
            $data = $fb->getProfile($alias);
        } catch (Exception $e) {
            Yii::app()->end(
                json_encode(
                    [
                        'error' => ['message' => $e->getMessage()]
                    ]
                ),
                true
            );
        }

        $user = User::model()->findOne(['id' => $data['id']]);
        if (!$user) {
            $user = new User();
        }
        $data['alias'] = $alias;
        foreach ($data as $attr => $value) {
            $user->setAttribute($attr, $value);
        }
        $user->save();
        Yii::app()->end(new EFacebookUserResponse($user), true);
    }

    public function actionFeed()
    {
        header('Content-Type: application/json; charset=utf-8');
        $params = [
            'limit'  => !empty($_GET['limit']) ? (int)$_GET['limit'] : '',
            'since'  => !empty($_GET['since']) ? $_GET['since'] : null,
            'until'  => !empty($_GET['until']) ? $_GET['until'] : null,
            'fields' => !empty($_GET['fields']) ? $_GET['fields'] : ''
        ];
        try {
            $feedDiscovery = new FeedDiscovery($_GET['user'], $params);
            $feed = $feedDiscovery->discovery();
        } catch (\Exception $e) {
            $data = json_encode(['error' => ['message' => $e->getMessage()]]);
            Yii::app()->end(json_encode($data), true);
        }

        Yii::app()->end(new EFacebookFeedResponse($feed, $params), true);
    }
}
