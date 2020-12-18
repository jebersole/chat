<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m201216_125950_init_rbac
 */
class m201216_125950_init_rbac extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        for ($i = 1; $i < 3; $i++) {
            $user = new User();
            $user->username = 'user' . $i;
            $user->password = 'user' . $i;
            $user->email = 'user' . $i . '@something.com';
        }

        $auth->assign($admin, 1);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
