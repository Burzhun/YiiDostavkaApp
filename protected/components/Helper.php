<?php

Class Helper {

	const CASELOWER = 'lower';
	const CASEUPPER = 'upper';

	public static function convertFileToCase($path = false, $case = self::CASELOWER){

		if(!$path){
			return false;
		}

		$files = scandir($path);

		$fruit = array_shift($files); // @TODO не используется, можно удалить?
		$fruit = array_shift($files); // @TODO не используется, можно удалить?

		foreach ($files as $data) {
			if(file_exists($path.$data)){
				rename($path.$data, $path.call_user_func('strto'.$case, $data));
			}
		}

		return true;
	}
}