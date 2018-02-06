<?php
return array(
	//'connectionString' => 'mysql:host=localhost;dbname=dostavka_db',
	'connectionString' => 'mysql:host=localhost;dbname=dost05_db',
	'emulatePrepare' => true,
	//'username' => 'root',
	'username' => 'dost05_db',
	//'password' => '',
	'password' => '3b34aWDi',
	'charset' => 'utf8',
	'tablePrefix' => 'tbl_',
	'class'=>'CDbConnection',

	'enableProfiling'=>true,
    'enableParamLogging' => true,
    'schemaCachingDuration' => '3600',
);