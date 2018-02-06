<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('editable', dirname(__FILE__) . '/../extensions/x-editable');
$host = ".dostavka05.ru";
if (CHERRY05) {
    $host = "cherry05.ru";
} else {
    if (Yii::app()->session['domain_id'] != 3) {
        $host = $_SERVER['HTTP_HOST'];
    }
}
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => "Доставка05 - доставка еды по Махачкале",
    'sourceLanguage' => 'en',
    'language' => 'ru',
    'defaultController' => 'site',
    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.Config',
        //'application.extensions.phaActiveColumn.*',
        'application.modules.partner.*',
        'application.modules.partner.models.*',
        'application.modules.partner.components.*',
        'application.modules.user.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        //'application.extensions.CAdvancedArBehavior',
        'application.extensions.ZHtml',
        'application.components.*',
        'application.components.helpers.*',
        'application.components.assets.*',
        'application.components.widgets.*',
        //'system.dekart.News.controllers.*',
        //'system.dekart.News.models.*',
        'application.components.behaviors.*',
        'editable.*',
    ),

    'modules' => array(
        'admin',
        'partner',
        'user',
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '1',
            'generatorPaths' => array(
                'application.gii',   // псевдоним пути
                //'bootstrap.gii',
            ),
        ),
    ),
    'controllerMap' => array(
        'min' => array(
            'class' => 'ext.minScript.controllers.ExtMinScriptController'
        ),
    ),
    // application components
    'components' => array(
        'clientScript' => array(
            'class' => 'ext.minScript.components.ExtMinScript',
            //'scriptMap' => array('jquery.js'=>false),
        ),
        'session' => $_SERVER['HTTP_HOST'] != 'dostavka05.local' ? array(
            'autoStart' => true,
            'cookieParams' => array('domain' => $host),
        ) : array(),
        'mobileDetect' => array(
            'class' => 'ext.MobileDetect.MobileDetect'
        ),

        'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            'driver' => 'GD',
            'params' => array('directory' => '/opt/local/bin'),
        ),

        'editable' => array(
            'class' => 'editable.EditableConfig',
            'form' => 'plain',        //form style: 'bootstrap', 'jqueryui', 'plain'
            'mode' => 'popup',            //mode: 'popup' or 'inline'
            'defaults' => array(              //default settings for all editable elements
                'emptytext' => 'Изменить'
            ),
        ),

        'request' => array(
            'enableCookieValidation' => true,
        ),

        "authManager" => array(
            //"class"        => "AuthManager",
            'class' => 'AuthManager',
            "defaultRoles" => array("guest")
        ),

        'user' => array(
            'class' => 'WebUser',
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'identityCookie' => $_SERVER['HTTP_HOST'] != 'dostavka05.local' ? array('domain' => $host) : array()
        ),
        'urlManager' => array(
            'class' => 'application.components.UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '' => CHERRY05 ? 'supplier/view' : 'site/index',
                'gii' => 'gii',
				'test1/'=>'test/index',
				'test1/chart'=>'test/chart',
                'bonus' => 'site/bonus',
                'deletethisgoddamnpartner/<id:\d+>' => 'partners/deletethispartner',
                'testtest' => 'site/testtest',
                'admin' => 'admin/default/index',
                'admin/banner/create' => 'admin/banner/create',
                'app' => 'site/app',
                'api' => 'api',
                'cart' => 'cart',
                'order' => 'order',
                'site' => 'site',
                'partners' => 'partners',
                'restorany/ordert' => 'supplier/ordert',
                'partners/thanks' => 'partners/thanks',
                'restorany/review/<supplerName:[\-\w]+>' => 'supplier/review2',
                'admin/review/togglepub' => 'admin/review/togglepub',
                'restorany/CronPromokod' => 'supplier/CronPromokod',
                'restorany/UsePromoKod' => 'supplier/UsePromokod',
                //'partner/orders/updateOrderInfo'=>'partner/orders/updateOrderInfo',
                'admin/partner_rayon' => 'admin/PartnerRayon',
                'admin/partner_rayon/create' => 'admin/PartnerRayon/create',
                'admin/partner_rayon/update' => 'admin/PartnerRayon/update',

                'admin/config/UpdateAjax' => 'admin/config/UpdateAjax',
                '/SetCityCookie/<id:\d+>' => 'site/SetCityCookie',
                'blog' => 'blog/index',
                'blog/category/<tname:[\-\w]+>' => 'blog/category',
                'blog/<id:\d+>' => 'blog/view',
                '/api2/logout/<token>'=>'api2/logout',
                'pages/<alias:about>' => 'pages/view',
                'pages/<alias:bonus>' => 'pages/view',
                'pages/<alias:partner-page>' => 'pages/view',
                'pages/<alias:company>' => 'pages/view',
                'pages/<alias:sitemap>' => 'pages/view',
                'pages/<alias:contacts>' => 'pages/view',
                'pages/<alias:oplatapay2pay>' => 'pages/view',
                'pages/<alias:oferta>' => 'pages/view',
                'pages/<alias:security>' => 'pages/view',

                'products/food/<id>' => 'products/food/',
                'horeca/' => 'products/horeca/',
                'restorany/' => 'products/food/',
                'magaziny/' => 'products/goods/',
                'magaziny/<id>' => 'products/goods/',
                'admin/pages/create' => 'admin/pages/create',
                'admin/pages/update/<id:\d+>' => 'admin/pages/update',
                'admin/pages/delete/<id:\d+>' => 'admin/pages/delete',
                'admin/partner/UpdateAjax' => 'admin/partner/UpdateAjax',
                'restorany/<supplerName:[\-\w]+>/info' => 'supplier/additionalInformation',
                'admin/statistics/partnersgoods' => 'admin/statistics/partnersgoods',
                'admin/statistics/<action:[\w]+>' => 'admin/statistics/<action>',
                'partner/pay/confirm/id<id:\d+>/' => 'partner/pay/confirm',
                'admin/partner/changeViewPartner' => 'admin/partner/changeViewPartner',
                'admin/partner/checkmenu/<id:\d+>' => 'admin/partner/checkmenu',
                'partner/menu/checkmenu/<id:\d+>' => 'partner/menu/checkmenu',
                'admin/default/orderComments' => 'admin/default/orderComments',
                'admin/partner/id/<id:\d+>/payment' => 'admin/partner/payment',
                'restorany/filterReview' => 'supplier/filterReview',

                'restorany/updateGoodImage' => 'supplier/updateGoodImage',
                'restorany/getImages' => 'supplier/getImages',
                'restorany/<supplerName:[\-\w]+>/review' => 'supplier/review',
                'supplier/<supplerName:[\-\w]+>/review' => 'supplier/review',

                'restorany/<supplerName:[\-\w]+>' => 'supplier/view',
                'review/<supplerName:[\-\w]+>' => 'supplier/review',
                'supplier/<supplerName:[\-\w]+>' => 'supplier/view',
                'restorany/action/<action:[\w]+>' => 'supplier/<action>',
                'restorany/<action>/id/<id:\d+>' => 'supplier/<action>/',
                // '<language:(ru|en|az){0,}>/restorany/<supplerName:[\-\w]+>'=>'supplier/view',

                '<module:user|partner|admin>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:user|partner|admin>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<module:user|partner|admin>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',

                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '/' => 'site/index',

                '<modul:\w+>/<acter:\w+>/id/<id:\d+>/<action:\w+>' => '/<modul>/<acter>/<action>',
                '<modul:\w+>/<acter:\w+>/id/<id:\d+>/<action:\w+>/<actionId:\d+>' => '/<modul>/<acter>/<action>View',
                '<modul:\w+>/<acter:\w+>/id/<id:\d+>/update/<action:\w+>/<actionId:\d+>' => '/<modul>/<acter>/<action>Update',
                '<modul:\w+>/<acter:\w+>/id/<id:\d+>/delete/<action:\w+>/<actionId:\d+>' => '/<modul>/<acter>/<action>Delete',
                '<id>' => 'products/food/',
            ),
        ),

        'db' => require_once(
        YII_DEBUG ? dirname(__FILE__) . '/database_dev.php' : dirname(__FILE__) . '/database.php'
        ),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/errors',
        ),

        'cache' => array(
            'class' => 'system.caching.CFileCache',
        ),

        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',

                ),
                /*array(
                    'class'      => 'CEmailLogRoute',
                    'levels'     => 'error',
                    'emails'     => 'burjunov@yandex.ru',
                ),*/

                /*array(
                    // направляем результаты профайлинга в ProfileLogRoute (отображается
                    // внизу страницы)
                    'class' => 'CProfileLogRoute',
                    'levels' => 'profile',
                    'enabled' => true,
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'levels' => 'error, warning, trace, profile, info',
                ),*/
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'mustafa_urg@mail.ru',
        'azerSite' => 'dostavka.az',
        'dagSite' => 'dostavka05.ru',
    ),
);

