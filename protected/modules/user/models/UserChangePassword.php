<?php

/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action of 'UserController'.
 */
class UserChangePassword extends CFormModel
{
    public $password;
    public $verifyPassword;

    public function rules()
    {
        return array(
            array('password, verifyPassword', 'required'),
            array('password', 'length', 'max' => 128, 'min' => 4, 'message' => UserModule::t("Не правильный пароль (минимум 4 символа).")),
            array('verifyPassword', 'compare', 'compareAttribute' => 'password', 'message' => UserModule::t("Повторяющийся пароль неверный.")),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'password' => UserModule::t("Пароль"),
            'verifyPassword' => UserModule::t("Повторите пароль"),
        );
    }
} 