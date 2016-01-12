<?php

class IndexController extends CController
{
    public function actionProfile()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $profileDiscovery = new ProfileDiscovery($_GET['profile']);
            $profile = $profileDiscovery->discovery();
            Yii::app()->end(new EFacebookProfileResponse($profile), true);
        } catch (\Exception $e) {
            $data = json_encode(['error' => ['message' => $e->getMessage()]]);
            Yii::app()->end(json_encode($data), true);
        }
    }

    /**
     * get facebook profiles feed
     */
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
            $profileDiscovery = new ProfileDiscovery($_GET['profile']);
            $profile = $profileDiscovery->discovery();

            if (!$profile) {
                $data = json_encode(['error' => ['message' => "Profile '{$_GET['profile']}' not found"]]);
                Yii::app()->end(json_encode($data), true);
            }

            if (!empty($params['until']) && !empty($profile->first_post_date)) {
                if ($params['until'] >= $profile->first_post_date) {
                    Yii::app()->end(new EFacebookFeedResponse([]), true);
                }
            }

            $feedDiscovery = new FeedDiscovery($profile, $params);
            $feed          = $feedDiscovery->discovery();

            if (empty($feed)) {
                $profile->updateFirstPostDate();
            }

            $response = new EFacebookFeedResponse($feed, $params);

            $response->setDefaultFields(EFacebookFields::getProfileDefaultFields($profile))
                ->setTimeField($profile->feed_time_field);
            Yii::app()->end($response, true);

        } catch (\Exception $e) {
            $data = json_encode(['error' => ['message' => $e->getMessage()]]);
            Yii::app()->end(json_encode($data), true);
        }
    }
}
