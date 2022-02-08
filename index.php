<?php

date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE);
ini_set('allow_call_time_pass_reference','0');
ini_set('short_open_tag','1');
//mb_internal_encoding("UTF-8");


// change the following paths if necessary
$yii=dirname(__FILE__).'/../libs/yii-1.1.16/yii.php';

$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',flase);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();