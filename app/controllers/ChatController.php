<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Message;
use app\models\User;
use app\helpers\MessageHelper;

class ChatController extends Controller
{

    const FAILED_SAVE = 'Невозможно сохранить %s.';
    const FLAG_MODEL = 'флаг';
    const MESSAGE_MODEL = 'сообщение';
    const MESSAGE_NOT_FOUND = 'Сообщение не найдено.';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Lists unflagged messages.
     *
     * @return string
     */
    public function actionList()
    {
        $data = $this->getData(false);
        $messages = Message::getMessages(false);
        return $this->render('chat', [
            'messages' => $messages,
            'showFlagged' => false,
            'data' => $data
        ]);
    }

    /**
     * Get data for chat view.
     *
     * @return array
     */
    private function getData($showFlagged)
    {
        $userId = Yii::$app->user->id;
        $data = [
            'currentUsername' => User::getUsernameById($userId),
            'currentIsAdmin' => User::isAdminById($userId),
        ];
        $messageType = $showFlagged ? 'flagged' : 'chat';
        foreach (['confirm', 'title', 'empty'] as $key) {
            $data[$key] = Message::$$messageType[$key];
        }
        return $data;
    }

    /**
     * Lists flagged messages.
     *
     * @return string
     */
    public function actionFlagged()
    {
        $userId = (int) Yii::$app->user->id;
        if ($userId && User::isAdminById($userId)) {
            $messages = Message::getMessages(true);
        } else {
            return $this->render('/site/error', [
                'message' => MessageHelper::UNAUTHORIZED,
                'name' => MessageHelper::UNAUTHORIZED_SHORT
            ]);
        }
        $data = $this->getData(true);
        return $this->render('chat', [
            'messages' => $messages,
            'showFlagged' => true,
            'data' => $data
        ]);
    }

    /**
     * Posts a new message.
     *
     * @return Response
     */
    public function actionAdd()
    {
        $request = Yii::$app->request;
        list($userId, $response) = $this->initResponse();
        if ($userId) {
            $model = new Message();
            $model->user_id = $userId;
            $model->text = $request->post('text');
            if (!$model->validate()) {
                $response->statusCode = 400;
                $response->data = ['errors' => array_values($model->errors)];
                return $response;
            }
            $this->saveModel($model, $response, self::MESSAGE_MODEL);
        } else {
            $this->addForbidden($response);
        }
        return $response;
    }

    /**
     * Flags/unflags a message.
     *
     * @return Response
     */
    public function actionFlag()
    {
        $request = Yii::$app->request;
        list($userId, $response) = $this->initResponse();
        if ($userId && User::isAdminById($userId)) {
            $id = (int) $request->post('id');
            $flag = $request->post('flagged') ? boolval($request->post('flagged')) : false;
            $model = Message::findOne($id);
            if (!$model) {
                $response->statusCode = 400;
                $response->data = ['message' => self::MESSAGE_NOT_FOUND];
                return $response;
            }
            $model->flagged = $flag;
            $this->saveModel($model, $response, self::FLAG_MODEL);
        } else {
            $this->addForbidden($response);
        }
        return $response;
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @param Response $response
     * @param string $modelName
     * @return void
     */
    private function saveModel($model, &$response, $modelName = '')
    {
        if ($model->save()) {
            $response->statusCode = $modelName === self::MESSAGE_MODEL ? 201 : 200;
            $response->data = ['id' => $model->id ?: 0];
        } else {
            $response->statusCode = 500;
            $response->data = ['message' => sprintf(self::FAILED_SAVE, $modelName)];
        }
    }

    /**
     * @param Response $response
     * @return void
     */
    private function addForbidden(&$response)
    {
        $response->statusCode = 401;
        $response->data = ['message' => MessageHelper::UNAUTHORIZED];
    }

    /**
     * @return array
     */
    private function initResponse() {
        $userId = (int) Yii::$app->user->id;
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        return [$userId, $response];
    }

}
