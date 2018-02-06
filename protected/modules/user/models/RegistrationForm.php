<?php

/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user registration form data. It is used by the 'registration' action of 'UserController'.
 */
class RegistrationForm extends User
{
    public $verifyPassword;
    public $verifyCode;

    public function rules()
    {
        $rules = array(
            array('name, pass, phone', 'required'),
            array('name', 'length', 'max' => 20, 'min' => 3, 'message' => UserModule::t("Некорректное имя (длина должна быть от 3 до 20 символов).")),
            array('pass', 'length', 'max' => 128, 'min' => 4, 'message' => UserModule::t("Некорректный пароль (минимум должно быть 4 символа).")),
            array('email', 'email'),
            //array('name', 'unique', 'message' => UserModule::t("Этот ник уже присуствует на сайте.")),
            //array('email', 'unique', 'message' => UserModule::t("Пользователь с данным emailom уже зарегистрирован.")),
            array('email', 'isemail', 'message' => UserModule::t("Пользователь с данным emailom уже зарегистрирован.")),
            array('verifyPassword', 'compare', 'compareAttribute' => 'pass', 'message' => UserModule::t("Пароли не совпадают.")),
            array('name', 'match', 'pattern' => '/^[А-Яа-яA-Za-z0-9_\s]+$/u', 'message' => UserModule::t("Некорректное имя. В имени допускаются буквы, цифры и знак '_'.")),
            array('phone', 'unique_phone', 'message' => UserModule::t("Пользователь с данным номером телефона уже зарегистрирован.")),
            array('phone', 'correct_phone', 'message' => UserModule::t("Вы ввели некорректный номер телефона")),
            array('network,phone_confirmed,password_confirmed', 'safe'),
        );
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form')
            return $rules;
        else
            array_push($rules, array('verifyCode', 'captcha', 'allowEmpty' => TRUE));
        return $rules;
    }

    public function isemail()
    {
        if($this->email==''){
            return true;
        }
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('network', '', true, 'AND');
        $criteria->addSearchCondition('email', $this->email, true, 'AND');
        $user = User::model()->find($criteria);
        if (null !== $user) {
            if($user->status==0){
                return $this->addError('email', 'Пользователь с такой почтой уже зарегистрирован, но аккаунт еще не активирован.На почту была отправлена ссылка для активации.');
            }else{
                return $this->addError('email', 'Пользователь с такой почтой уже зарегистрирован.');
            }
        } else {
            return true;
        }
    }

    public function unique_phone()
    {
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('network', '', true, 'AND');
        $criteria->addSearchCondition('phone', $this->phone, true, 'AND');
        $user = User::model()->find("network='' and phone='".$this->phone."'");
        if (null !== $user) {
            return $this->addError('phone', 'Пользователь с данным номером телефона уже зарегистрирован.');
        } else {
            return true;
        }
    }

    public function correct_phone()
    {
        if(Order::FormatPhone($this->phone)===null){
            return $this->addError('phone', 'Вы ввели некорректный номер телефона');
        }else {
            return true;
        }
    }
}