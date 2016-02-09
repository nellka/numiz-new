<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Админка',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.*'
	),

	'defaultController'=>'site',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			'tablePrefix' => 'tbl_',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=blog',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		 'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
        ),
        'session' => array(
            'autoStart'=>true,
             'cookieParams' => array(
                 'path' => '/',
                 'domain' => '.numizmatik1.ru', // to keep session id visible into subdomains
                 'httpOnly' => true, // change if needed
             ),
         ),
         
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'post/<id:\d+>/<title:.*?>'=>'post/view',
				'posts/<tag:.*?>'=>'post/index',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				/*array(
					 'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'levels'=>'error, warning, trace, profile, info',
				),*/
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
    	// this is displayed in the header section
    	'title'=>'Админеп',
    	// this is used in error pages
    	'adminEmail'=>'nel1@mail.ru',
    	'tmpDir'=>'/tmp/',// DDIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR,
    	// number of posts displayed per page
    	'postsPerPage'=>10,
    	// maximum number of comments that can be displayed in recent comments portlet
    	'recentCommentCount'=>10,	
    	// the copyright information displayed in the footer section
    	'copyrightInfo'=>'Copyright &copy; 2009 by My Company.',
    )	
);