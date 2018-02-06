<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'import' => array(
        'application.models.*',
    ),
    'components' => array(
        'db' => require_once(
            strripos(dirname(__FILE__), 'openserver') ? dirname(__FILE__) . '/database_dev.php' : dirname(__FILE__) . '/database.php'
        ),
    )
);