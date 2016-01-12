<?php

class IndexController extends CController
{
    public function actionProfile()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $userDiscovery = new UserDiscovery($_GET['user']);
            $user = $userDiscovery->discovery();
            Yii::app()->end(new EFacebookUserResponse($user), true);
        } catch (\Exception $e) {
            $data = json_encode(['error' => ['message' => $e->getMessage()]]);
            Yii::app()->end(json_encode($data), true);
        }
    }

    public function actionFeed()
    {
        header('Content-Type: application/json; charset=utf-8');
        $params = [
            'limit' => !empty($_GET['limit']) ? (int)$_GET['limit'] : '',
            'since' => !empty($_GET['since']) ? $_GET['since'] : null,
            'until' => !empty($_GET['until']) ? $_GET['until'] : null,
            'fields' => !empty($_GET['fields']) ? $_GET['fields'] : ''
        ];

        try {
            $userDiscovery = new UserDiscovery($_GET['user']);
            $user = $userDiscovery->discovery();

            if (!$user) {
                $data = json_encode(['error' => ['message' => "User '{$_GET['user']}' not found"]]);
                Yii::app()->end(json_encode($data), true);
            }

            $feedDiscovery = new FeedDiscovery($user, $params);
            $response      = new EFacebookFeedResponse(
                $feedDiscovery->discovery(),
                $params
            );

            $response->setDefaultFields(EFacebookFields::getUserDefaultFields($user))
                ->setTimeField($user->feed_time_field);
            Yii::app()->end($response, true);

        } catch (\Exception $e) {
            $data = json_encode(['error' => ['message' => $e->getMessage()]]);
            Yii::app()->end(json_encode($data), true);
        }
    }
}
