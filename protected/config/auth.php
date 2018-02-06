<?php
  /**
   * конфигурация менеджера авторизации
   */
	
  return array(
  	/*"edit_blog"     => array(
      "type"        => CAuthItem::TYPE_TASK,
      "description" => "Изменение информации",
      "bizRule"     => 'return Yii::app()->user->id==$params["partner"]->user->id;'
    ),*/
    
    User::GUEST     => array(
      "type"        => CAuthItem::TYPE_ROLE,
      "description" => "Гость",
      "bizRule"     => NULL,
      "data"     => NULL,
    ),
    User::USER      => array(
      "type"        => CAuthItem::TYPE_ROLE,
      "description" => "Зарегистрированный пользователь",
      "bizRule"     => NULL,
      "data"     => NULL,
    ),
    User::PARTNER     => array(
      "type"        => CAuthItem::TYPE_ROLE,
      "description" => "Партнер",
      //"children"  => array(User::USER, "create_public", "estimate")
      "bizRule"     => NULL,
      "data"     => NULL,
    ),
    User::OPERATOR => array(
      "type"        => CAuthItem::TYPE_ROLE,
      "description" => "Оператор",
      "bizRule"     => NULL,
      "data"     => NULL,
    ),
    User::ADMIN => array(
      "type"        => CAuthItem::TYPE_ROLE,
      "description" => "Администратор",
	  "bizRule"     => NULL,
      "data"     => NULL,
    )
  );

?>