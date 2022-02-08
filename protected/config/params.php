<?php
$server_name = $_SERVER['SERVER_NAME'];
$tag = $_SERVER['HTTPS'];
//if($tag == 'on'){
$http = 'https://';
$upload_url = 'https://shell.cmstech.sg/appupload';
//}else{
//$upload_url = 'http://47.88.139.53/appupload';
//}
return array(
    'version'   =>  require(dirname(__FILE__) . '/version.php'),

    'debug_log_path'    =>  './tmp',
    'upload_file_path' => "/opt/www-nginx/web/filebase/tmp",//必须为本机地址
    //'upload_file_path' => "/Users/weijuan/webroot/cms/ctmgr/tmp",
    //'upload_file_path' => "./webuploads",
    //'upload_file_path' => "./files",
    //'upload_url' => "http://47.88.139.53/upload",
    'upload_url' => $upload_url,
    'test_upload_url' => "http://t_cmstech.aoepos.cn/upload",
    'upload_tmp_path' => "/opt/www-nginx/web/filebase/tmp",
    'upload_data_path' => "/opt/www-nginx/web/filebase/data",
    'upload_record_path' => "/opt/www-nginx/web/filebase/record",
//    'upload_report_path' => "/opt/www-nginx/web/filebase/report",
    'upload_report_path' => "/opt/www-nginx/web/report_tmp/bimax",
    'upload_program_path' => "/opt/www-nginx/web/filebase/program",
    'upload_platform_path' => "/opt/www-nginx/web/filebase/platform",
    'upload_company_path' => "/opt/www-nginx/web/filebase/company",
    'upload_application_path' => "/opt/www-nginx/web/filebase/application",
    //'upload_file_path' => "./webuploads",
    'face_img_size'   =>  200,  //200K
    'attend_params' => array(   //考勤配置
        'base_url' => 'http://'.$_SERVER["HTTP_HOST"].'/test/app_attendance', //attendance
        //'base_url' => 'http://localhost:8080/cms/app_attendance', //attendance
        'base_url' => 'https://shell.cmstech.sg/test/app_attendance', //attendance

        'from' => 'ctmgr',
        'key' => 'ctmgr',
    ),
    'faceall' => array( //飞搜配置
        'api_key' => 'EY95oszYZvPlxE26lYlI0Hx7obcrfAkhjB7TZtfM',
        'api_secret' => 'TE06mxGVEgaas4lXyqw7AlTcc3N3fniGERSeQotn',
        'version' => 'v2',
    ),
);