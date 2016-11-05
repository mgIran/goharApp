<?php
return array(
    'onBeginRequest'=>create_function('$event', 'return ob_start("ob_gzhandler");'),
    'onEndRequest'=>create_function('$event', 'return ob_end_flush();'),
	'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'گوهر',
    'timeZone' => 'Asia/Tehran',
    'theme' => 'abound',
    'language' => 'fa_ir',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
        'application.vendor.*',
        'application.models.*',
		'application.components.*',
		'application.modules.users.models.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
        'admins',
        'users',
        'setting',
        'tickets',
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
            'class' => 'WebUser',
		),
		'iWebSms' => array(
				'class' => 'ext.iWebSms.prman',
				'url' => 'http://212.16.76.90/ws/sms.asmx?WSDL',
				'userId' => '35329',
				'password' => '#Tarsim*kashani%8585!'
		),
        'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
        ),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
            'appendParams'=>true,
			'rules'=>array(
//				 REST patterns
				array('api/downloadApp', 'pattern'=>'api/downloadApp/<token:\w+>', 'verb'=>'POST'),
				array('api/list', 'pattern'=>'api/list/<model:\w+>', 'verb'=>'GET'),
				array('api/view', 'pattern'=>'api/view/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
				array('api/update', 'pattern'=>'api/update/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
				array('api/delete', 'pattern'=>'api/delete/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
				array('api/create', 'pattern'=>'api/create/<model:\w+>', 'verb'=>'POST'),
				array('<module>/<controller>/create', 'pattern'=>'api/<module:\w+>/<controller:\w+>/create', 'verb'=>'POST'),
				array('api/<action>', 'pattern'=>'api/<action:\w+>', 'verb'=>'POST'),
				array('api/<action>', 'pattern'=>'api/<action:\w+>', 'verb'=>'GET'),
				'<action:(login|logout|register|dashboard)>' => 'users/public/<action>',
                '<module:\w+>/<id:\d+>/<title:(.*)>'=>'<module>/manage/view',
				'<module:\w+>/<id:\d+>'=>'<module>/manage/view',
				'<module:\w+>/<controller:\w+>'=>'<module>/<controller>/index',
				'<module:\w+>/<controller:\w+>/<id:\d+>/<title:\w+>'=>'<module>/<controller>/view',
				'<controller:\w+>/<id:\d+>/<title:(.*)>'=>'<controller>/view',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>'=>'<module>/<controller>/<action>/view',
				'<module:\w+>/<controller:\w+>/<action:\w+>/*'=>'<module>/<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
				'<module:\w+>/<title:(.*)>/<id:\d+>/*'=>'<module>/manage/view',
				'<module:\w+>/<title:(.*)>/<id:\d+>'=>'<module>/manage/view',
            ),
		),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels'=>'error, warning, trace, info',
                    'categories'=>'application.*',
                ),
                // uncomment the following to show log messages on web pages
                array(
                    'class' => 'CWebLogRoute',
                    'enabled' => YII_DEBUG,
                    'levels'=>'error, warning, trace, info',
                    'categories'=>'application.*',
                    'showInFireBug' => true,
                ),
			),
		),
        'clientScript'=>array(
            //'class'=>'ext.minScript.components.ExtMinScript',
            'coreScriptPosition' => CClientScript::POS_HEAD,
            'defaultScriptFilePosition' => CClientScript::POS_END,
        ),
    ),
    'controllerMap' => array(
        'min' => array(
            'class' =>'ext.minScript.controllers.ExtMinScriptController',
        )
    ),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'pardis@avayeshahir.com',
		'no-reply-email' => 'noreply@avayeshahir.com',
		'mailTheme'=>
				'<div style="display: block;width: 100%;"><h2 style="margin-bottom:0;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;display: block;width: 100%;background-color: #0b3762;line-height:60px;color:#fff;font-size: 24px;text-align: right;padding-right: 50px">پردیس آوای شهیر<span style="font-size: 14px;color:#f0f0f0"> - موسسه فرهنگی هنری زبان</span></h2></div>
             <div style="display: inline-block;width: 100%;font-family:tahoma;line-height: 28px;">
                <div style="direction:rtl;display:block;overflow:hidden;border:1px solid #efefef;text-align: center;padding:15px;">{MessageBody}</div>
             </div>',
	),
);
