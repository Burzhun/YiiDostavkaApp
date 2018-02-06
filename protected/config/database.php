<?php
return array(
	//'connectionString' => 'mysql:host=localhost;dbname=dostavka_db',
	'connectionString' => 'mysql:host=localhost;dbname=',
	'emulatePrepare' => true,
	//'username' => 'root',
	'username' => '',
	//'password' => '',
	'password' => '',
	'charset' => 'utf8',
	'tablePrefix' => 'tbl_',
	'class'=>'CDbConnection',

	'enableProfiling'=>true,
    'enableParamLogging' => true,
    'schemaCachingDuration' => '3600',
);