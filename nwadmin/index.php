<?php

ini_set("display_errors",1);
include("../new/config.php");
// include Yii bootstrap file
$yii=$cfg["path"].'/php_lib/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/test.php';
require_once($yii);

// create a Web application instance and run
Yii::createWebApplication($config)->run();