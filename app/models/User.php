<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLES = [
        'chatter',
        'admin',
    ];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'username', 'email'], 'required'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public static function tableName()
    {
        return '{{users}}';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function getUsernameById($id)
    {
        $identity = self::findIdentity($id);
        return $identity ? $identity->username : 'guest';
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function isAdminById($id)
    {
        $identity = self::findIdentity($id);
        return $identity && in_array('admin', array_keys(\Yii::$app->authManager->getRolesByUser($id))) ?: false;
    }

    /**
     * Get user's role. Because there are only '2' roles now, this works. If more would be needed,
     * a different implementation for roles would be needed.
     * @return string
     */
    public function getRole() {
        return self::isAdminById($this->id) ? 'admin' : 'chatter';
    }

    /**
     * @param string
     * @return bool
     */
    public function setRole($role) {
        $existingRole = $this->getRole();
        if ($existingRole === $role) {
            return true;
        }
        $adminRole = \Yii::$app->authManager->getRole('admin');
        if ($existingRole === 'admin') {
            $result = \Yii::$app->authManager->revoke($adminRole, $this->id);
        } else { // $existingRole === 'chatter'
            $result = \Yii::$app->authManager->assign($adminRole, $this->id);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public static function getUsers()
    {
        return self::find()
            ->orderBy('username ASC')
            ->all();
    }

}
