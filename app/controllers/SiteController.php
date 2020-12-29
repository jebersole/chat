<?php

namespace app\controllers;

use app\models\User;
use app\helpers\MessageHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Get a list of users and roles
     *
     * @return string
     */
    public function actionListUsers()
    {
        $userId = (int) Yii::$app->user->id;
        if ($userId && User::isAdminById($userId)) {
            $users = User::getUsers();
            return $this->render('/site/users', [
                'users' => $users,
                'title' => 'Пользователи',
                'empty' => 'Пользователи не найдены.'
            ]);
        }
        return $this->render('/site/error', [
            'message' => MessageHelper::UNAUTHORIZED,
            'name' => MessageHelper::UNAUTHORIZED_SHORT
        ]);
    }

    /**
     * Change an user's role
     *
     * @return Response
     */
    public function actionChangeUserRole()
    {
        $request = Yii::$app->request;
        $userId = (int) Yii::$app->user->id;
        $response = Yii::$app->response;
        if ($userId && User::isAdminById($userId)) {
            $id = (int) $request->post('id');
            $role = $request->post('role');
            $model = User::findOne($id);
            if (!$model || !in_array($role, User::ROLES)) {
                $response->statusCode = 400;
                return $response;
            }
            if (!$model->setRole($role)) {
                $response->statusCode = 500;
            }
        } else {
            $response->statusCode = 401;
        }
        return $response;
    }

}
