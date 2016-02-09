<?php
//phpinfo();
//error_reporting(E_ALL^E_NOTICE);

ini_set("display_errors",1);
include("../config.php");


//var_dump($cfg);
//die();
// die('Технические работы');

$yii=$cfg["path"].'/php_lib/framework/yii.php';

$config=dirname(__FILE__).'/protected/config/main.php';
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
