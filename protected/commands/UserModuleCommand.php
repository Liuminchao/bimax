<?php
class UserModuleCommand extends CConsoleCommand
{
    //php /opt/www-nginx/web/test/idd/protected/yiic usermodule bach
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic modulereport bach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionBach()
    {
//        $program_id = $_GET['program_id'];
//        echo $program_id;
//        exit;

        $filename = "/opt/www-nginx/web/filebase/tmp/20210326_2252.zip";
        $user_id = '2252';
//
//        ini_set('memory_limit','512M');
//        $sql = "select a.* from ptw_apply_basic a,ptw_apply_worker b where b.user_id = $user_id and a.apply_id=b.apply_id";
        echo 'PTW';
        $sql = "SELECT apply_id FROM `ptw_apply_worker` where user_id = '2252'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        foreach($rows as $i => $j){
            $ptw_apply = ApplyBasic::model()->findByPk($j['apply_id']);
            if(is_object($ptw_apply)){
                $params['id'] = $j['apply_id'];

                $params['type'] = $ptw_apply->add_conid;
//            var_dump($params['id']);
                $app_id = 'PTW';
                $check_apply = CheckApply::model()->findByPk($j['apply_id']);
                $step = $check_apply->current_step;
                $deal_type = CheckApplyDetail::dealtypeList($app_id, $j['apply_id'], $step);
                $filepath = DownloadPdf::transferDownload($params,$app_id);
                if (file_exists($filepath)) {
                    $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
                    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                        //如果是Linux系统，需要保证服务器开放了文件写权限
                        exit("文件打开失败!");
                    }
                    echo $j['apply_id'];
                    $zip->addFile($filepath, basename($filepath));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $zip->close();
                }
            }
        };
        echo 'TBM';
        $sql = "select meeting_id from tbm_meeting_worker  b where worker_id = $user_id ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        foreach($rows as $i => $j){
            $tbm_model = Meeting::model()->findByPk($j['meeting_id']);
            if(is_object($tbm_model)){
                $params['id'] = $j['meeting_id'];
                $params['type'] = $tbm_model->main_conid;
                $app_id = 'TBM';
                $n++;
                $filepath = DownloadPdf::transferDownload($params,$app_id);
                if(file_exists($filepath)){
                    $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
                    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                        //如果是Linux系统，需要保证服务器开放了文件写权限
                        exit("文件打开失败!");
                    }
                    echo $j['meeting_id'];
                    $zip->addFile($filepath, basename($filepath));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $zip->close();
                }
            }

        }
        echo 'Train';
        $sql = "SELECT * FROM train_apply_worker  WHERE worker_id = $user_id  ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        foreach($rows as $i => $j){
            $train_model = Train::model()->findByPk($j['training_id']);
            if(is_object($train_model)){
                $params['type'] = 'A';
                $params['id'] = $j['training_id'];
                $app_id = 'TRAIN';
                $n++;
                $filepath = DownloadPdf::transferDownload($params,$app_id);
                if(file_exists($filepath)){
                    $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
                    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                        //如果是Linux系统，需要保证服务器开放了文件写权限
                        exit("文件打开失败!");
                    }
                    echo $j['training_id'];
                    $zip->addFile($filepath, basename($filepath));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $zip->close();
                }
            }
        }
        echo 'BAC';
        $sql = "select * from bac_violation_record  where user_id = $user_id   ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        foreach($rows as $i => $j){
            $safety_model = SafetyCheck::model()->findByPk($j['check_id']);
            if(is_object($safety_model)){
                $params['type'] = 'A';
                $params['check_id'] = $j['check_id'];
                $app_id = 'WSH';
                $n++;
                $filepath = DownloadPdf::transferDownload($params,$app_id);
                if(file_exists($filepath)) {
                    $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
                    if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
                        //如果是Linux系统，需要保证服务器开放了文件写权限
                        exit("文件打开失败!");
                    }
                    echo $j['check_id'];
                    $zip->addFile($filepath, basename($filepath));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $zip->close();
                }
            }
        }
//        $sql = "select * from bac_routine_check where root_proid = $program_id and contractor_id = '377' and apply_time like $date.'%'";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        $n = 0;
//        foreach($rows as $i => $j){
//            $params['check_id'] = $j['check_id'];
//            $app_id = 'ROUTINE';
//            if($j['status'] == 1 || $j['status'] == 2){
//                if($j['save_path'] == ''){
//                    $n++;
//                    $filepath = DownloadPdf::transferDownload($params,$app_id);
//                    if(file_exists($filepath)){
//                        echo 'OK';
//                    }
//                }
//            }
//            if($n==200){
//                goto end;
//            }
//        }
//        $sql = "select * from ra_swp_basic where main_proid = $program_id and contractor_id = '377' ";
//        $n = 0;
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        foreach($rows as $i => $j){
//            $params['ra_swp_id'] = $j['ra_swp_id'];
//            $app_id = 'RA';
//            if($j['status'] == 4 || $j['status'] == 3){
//                if($j['save_path'] == ''){
//                    $n++;
//                    $filepath = DownloadPdf::transferDownload($params,$app_id);
//                    if(file_exists($filepath)){
//                        echo 'OK';
//                    }
//                }
//            }
//            if($n==200){
//                goto end;
//            }
//        }
//        $sql = "select * from accident_basic where root_proid = $program_id and record_time like $date.'%' ";
//        $n = 0;
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        foreach($rows as $i => $j){
//            if($pro_params != '0') {
//                $pro_params = json_decode($pro_params, true);
//                //判断是否是迁移的
//                if (array_key_exists('acci_report', $pro_params)) {
//                    $params['type'] = $pro_params['acci_report'];
//                } else {
//                    $params['type'] = 'A';
//                }
//            }else{
//                $params['type'] = 'A';
//            }
//            $params['id'] = $j['apply_id'];
//            $app_id = 'ACCI';
//            if($j['status'] == 1){
//                if($j['save_path'] == ''){
//                    $n++;
//                    $filepath = DownloadPdf::transferDownload($params,$app_id);
//                    if(file_exists($filepath)){
//                        echo 'OK';
//                    }
//                }
//            }
//            if($n==200){
//                goto end;
//            }
//        }
        end:
        echo 'over';

//        exec("cd /opt/www-nginx/web/filebase/report/pdf/2017/11/ && zip -qr test.zip ./*",$out_put,$result);
//        if($result == 0){
//            exec("pwd",$out);
//            exec("zip -qr test.zip ./*",$out,$rs);
//        }
//        var_dump($out_put);
//        var_dump($result);
//        var_dump($out);
    }
}