<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '新加坡CBA',
    //'language' => 'en_US',
    //'language' => 'zh_CN',
    // preloading 'log' component
    'preload' => array('log'),
    'runtimePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '/runtime',
    //'runtimePath'=>'/usr/local/webapp/1430/runtime',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.widgets.*',
    ),
    //配置模块信息
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => 'index.php?r=site/login',
        ),
        'authManager' => array(
            'class' => 'CPhpAuthManager',
        ),
        /* 本机 */
         'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1;port=3306; dbname=cmsdb',
            'emulatePrepare' => true,
            'enableProfiling' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ),
        /*'db' => array(
             'connectionString' => 'mysql:host=115.28.201.162;port=3306; dbname=cmsdb',
             'emulatePrepare' => true,
             'enableProfiling' => true,
             'username' => 'cmsdb',
             'password' => 'cmsdb2015',
             'charset' => 'utf8',
         ),*/
//        'db' => array(
//            'connectionString' => 'mysql:host=rm-gs51693z4l4s7l46p.mysql.singapore.rds.aliyuncs.com;port=3306; dbname=cmsdb2',
//            'emulatePrepare' => true,
//            'enableProfiling' =>true,
//            'username' => 'cmsdb',
//            'password' => 'cmsdb@2015',
//            'charset'  => 'utf8',
//        ),
        // uncomment the following to use a MySQL database
        'errorHandler' => array(
            // use 'site/error' action to display errors
            //'errorAction'=>'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info',
                ),
            ),
        ),
        // "redis" => array(
        //     "class" => "ext.redis.ARedisConnection",
        //     "hostname" => "192.168.22.35",
        //     "port" => 6379,
        // ),
        'fcache'=>array(
            'class'=>'system.caching.CFileCache'
        ),
    ),
    'modules' => array(
        'sys' => array(),
        'comp' => array(),
        'proj' => array(),
        'payroll' => array(),
        'workflow' => array(),
        'device' => array(),
        'license' => array(),
        'tbm' => array(),
        'wsh' => array(),
        'train' => array(),
        'routine' => array(),
        'document' => array(),
        'ra' => array(),
        'statistics' => array(),
        'accidents' => array(),
        'meet' => array(),
        'quality' => array(),
        'qa' => array(),
        'rf' => array(),
        'task' => array(),
        'transmittal' => array(),
        'pbu' => array(),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
