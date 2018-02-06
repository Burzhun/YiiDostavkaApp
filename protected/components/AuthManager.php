<?php
  /**
   * AuthManager
   *
   * Класс менеджера авторизации
   */
	
  class AuthManager extends CPhpAuthManager
  {
    /**
     * инициализация:
     * - загрузка конфигурационного файлаж
     * - определение роли пользователя
     *
     * @return void
     * @access public
     */
    public function init()
    {
		if (!$this->authFile)
			$this->authFile = Yii::getPathOfAlias("application.config.auth").".php";
		parent::init();
		
		/** @var $user CWebUser */
		$user = Yii::app()->user;
		
		if (!$user->isGuest)
			$this->assign($user->role, $user->id);
    }
	
    /**
     * загрузка конфигурационного файла
     *
     * @param string $file имя файла
     * @return string[]
     * @access protected
     */
    /*
    protected function loadFromFile($file)
    {
      $items = parent::loadFromFile($file);
      array_walk($items, create_function(
        'array &$item',
        'if (($item["type"] != CAuthItem::TYPE_TASK) && !array_key_exists("bizRule", $item)) $item["bizRule"] = null; if (!array_key_exists("data", $item)) $item["data"] = null;'
      ));
      return $items;
    }
    */
  }

?>