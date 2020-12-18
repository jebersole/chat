<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Message;
use app\models\User;

class ChatController extends Controller
{
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
        $messages = $this->getMessages(false);
        return $this->render('chat', [
            'messages' => $messages,
            'flagged' => false
        ]);
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
            $messages = $this->getMessages(true);
        } else {
            return $this->render('/site/error', [
                'message' => 'У вас нет разрешения на доступ к этому ресурсу.',
                'name' => 'Нет разрешения'
            ]);
        }
        return $this->render('chat', [
            'messages' => $messages,
            'flagged' => true
        ]);
    }

    /**
     * Posts a new message.
     *
     * @return Response
     */
    public function actionAdd()
    {
        list($userId, $response) = $this->initResponse();
        if ($userId) {
            $model = new Message();
            $model->user_id = $userId;
            $model->text = $_POST['text'] ?? '';
            if (!$model->validate()) {
                $response->statusCode = 400;
                $response->data = ['errors' => array_values($model->errors)];
                return $response;
            }
            $this->saveModel($model, $response, 'сообщение');
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
        list($userId, $response) = $this->initResponse();
        if ($userId && User::isAdminById($userId)) {
            $id = (int) $_POST['id'] ?? '';
            $flag = isset($_POST['flagged']) ? boolval($_POST['flagged']) : false;
            $model = Message::findOne($id);
            if (!$model) {
                $response->statusCode = 400;
                $response->data = ['message' => 'Сообщение не найдено.'];
                return $response;
            }
            $model->flagged = $flag;
            $this->saveModel($model, $response, 'флаг');
        } else {
            $this->addForbidden($response);
        }
        return $response;
    }

	/**
	 * @return \yii\db\ActiveRecord[]
	 */
    private function getMessages($flagged = false) {
        return Message::find()
            ->where(['flagged' => $flagged])
            ->with('user')
            ->orderBy('created_at ASC')
            ->all();
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @param Response $response
     * @param string $modelName
     * @return void
     */
    private function saveModel($model, &$response, $modelName = '') {
        if ($model->save()) {
            $response->statusCode = $modelName === 'сообщение' ? 201 : 200;
        } else {
            $response->statusCode = 500;
            $response->data = ['message' => "Невозможно сохранить $modelName."];
        }
    }

    /**
     * @param Response $response
     * @return void
     */
    private function addForbidden(&$response) {
        $response->statusCode = 401;
        $response->data = ['message' => 'Действие запрещено. Вы вошли в систему?'];
    }

    /**
     * @return array
     */
    private function initResponse() {
        $userId = (int) Yii::$app->user->id;
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        return [$userId, $response];
    }

}
