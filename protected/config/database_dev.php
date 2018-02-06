<?php
return array(
	//'connectionString' => 'mysql:host=localhost;dbname=dostavka_db',
	'connectionString' => 'mysql:host=localhost;dbname=dostavka05_db',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	'tablePrefix' => 'tbl_',
	'class'=>'CDbConnection',

    'enableProfiling'=>true,
    'enableParamLogging' => true,
);