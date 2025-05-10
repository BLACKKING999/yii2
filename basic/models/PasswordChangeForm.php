<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Password change form
 */
class PasswordChangeForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'confirmPassword'], 'required'],
            ['currentPassword', 'validateCurrentPassword'],
            ['newPassword', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Las contraseñas no coinciden.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currentPassword' => 'Contraseña Actual',
            'newPassword' => 'Nueva Contraseña',
            'confirmPassword' => 'Confirmar Contraseña',
        ];
    }

    /**
     * Validates the current password.
     * This method serves as the inline validation for currentPassword.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->currentPassword)) {
                $this->addError($attribute, 'La contraseña actual es incorrecta.');
            }
        }
    }

    /**
     * Changes password.
     *
     * @return bool if password was changed.
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->password = $this->newPassword;
            return $user->save();
        }
        return false;
    }

    /**
     * Finds user by current user ID
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findIdentity(Yii::$app->user->id);
        }

        return $this->_user;
    }
}
