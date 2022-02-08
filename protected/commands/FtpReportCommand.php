<?php
class FtpReportCommand extends CConsoleCommand
{
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic ftpreport bach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionBach()
    {
//        $program_id = $_GET['program_id'];
//        echo $program_id;
//        exit;
        $program_id = '1504';
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
//
        $date = date("Y-m-d",strtotime("-1 day"));

        ini_set('memory_limit','512M');
        $sql = "select * from ptw_apply_basic where program_id = $program_id and record_time like '%$date%' ";
//        $sql = "select * from ptw_apply_basic where apply_id = '1560320230877' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        if($pro_params != '0') {
            $ptw_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('ptw_report', $ptw_params)) {
                $params['type'] = $ptw_params['ptw_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['ftp'] = '1';
        foreach($rows as $i => $j){
            $params['id'] = $j['apply_id'];
            $add_operator = explode('|',$j['add_operator']);
//            var_dump($params['id']);
            $app_id = 'PTW';
            $success = '';
            if($add_operator[2] == 2){
                $n++;
                if($j['save_path'] == '') {
                    $filepath = DownloadPdf::transferDownload($params, $app_id);
                    if (file_exists($filepath)) {
                        $success = 'OK';
                        echo $success;
                    }
                }
            }else if($add_operator[1] >= 2 && $add_operator[1]!=8){
                $n++;
                if($j['save_path'] == '') {
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        $success = 'OK';
                        echo $success;
                    }
                }
            }
            if($success == 'OK'){
                $year = substr($j['record_time'],0,4);//年
                $month = substr($j['record_time'],5,2);//月
                $day = substr($j['record_time'],8,2);//日
                $program_id = $j['program_id'];
                $contractor_id = $j['apply_contractor_id'];
                $filepath_array = explode('/',$filepath);
                $count = count($filepath_array);
                $file = $filepath_array[$count-1];
                $remote_file = 'SFTP/REPORT/'.$program_id.'/'.$app_id.'/'.$year.'/'.$month.'/'.$day;
                Ftp::upload($filepath,$remote_file,$file);
            }
            if($n==800){
                goto end;
            }
        };
        $sql = "select * from tbm_meeting_basic where program_id = $program_id  and record_time like '%$date%'";
//        $sql = "select * from tbm_meeting_basic where meeting_id = '1560497627496' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        if($pro_params != '0') {
            $tbm_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('tbm_report', $tbm_params)) {
                $params['type'] = $tbm_params['tbm_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['ftp'] = '1';
        foreach($rows as $i => $j){
            $app_id = 'TBM';
            $params['id'] = $j['meeting_id'];
            $success = '';
            if($j['status'] == 1){
                $n++;
                if($j['save_path'] == ''){
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        $success = 'OK';
                        echo $success;
                    }
                }
            }
            if($success == 'OK'){
                $year = substr($j['record_time'],0,4);//年
                $month = substr($j['record_time'],5,2);//月
                $day = substr($j['record_time'],8,2);//日
                $program_id = $j['program_id'];
                $contractor_id = $j['add_conid'];
                $filepath_array = explode('/',$filepath);
                $count = count($filepath_array);
                $file = $filepath_array[$count-1];
                $remote_file = 'SFTP/REPORT/'.$program_id.'/'.$app_id.'/'.$year.'/'.$month.'/'.$day;
                Ftp::upload($filepath,$remote_file,$file);
            }
            if($n==800){
                goto end;
            }
        }
        $sql = "SELECT * FROM train_apply_basic WHERE program_id = $program_id and record_time like '%$date%'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        if($pro_params != '0') {
            $train_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('train_report', $train_params)) {
                $params['type'] = $train_params['train_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['ftp'] = '1';
        foreach($rows as $i => $j){
            $params['id'] = $j['training_id'];
            $success = '';
            $app_id = 'TRAIN';
            if($j['status'] == 1){
                if($j['save_path'] == ''){
//                    echo $j['training_id'];
//                    exit;
                    $n++;
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        $success = 'OK';
                        echo $success;
                    }
                }
            }
            if($success == 'OK'){
                $year = substr($j['record_time'],0,4);//年
                $month = substr($j['record_time'],5,2);//月
                $day = substr($j['record_time'],8,2);//日
                $program_id = $j['program_id'];
                $contractor_id = $j['add_conid'];
                $filepath_array = explode('/',$filepath);
                $count = count($filepath_array);
                $file = $filepath_array[$count-1];
                $remote_file = 'SFTP/REPORT/'.$program_id.'/TRAINING/'.$year.'/'.$month.'/'.$day;
                Ftp::upload($filepath,$remote_file,$file);
            }
            if($n==800){
                goto end;
            }
        }
        $sql = "select * from bac_safety_check where root_proid = $program_id  and apply_time like '%$date%'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        if($pro_params != '0') {
            $wsh_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('wsh_report', $wsh_params)) {
                $params['type'] = $wsh_params['wsh_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['ftp'] = '1';
        $params['month_tag'] = 0;
        foreach($rows as $i => $j){
            $params['check_id'] = $j['check_id'];
            $success = '';
            $app_id = 'WSH';
            if($j['status'] == 1 || $j['status'] == 2){
                if($j['save_path'] == '' && $j['contractor_id'] != ''){
                    $n++;
                    echo $j['check_id'];
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        $success = 'OK';
                        echo $success;
                    }
                }
            }
            if($success == 'OK'){
                $year = substr($j['apply_time'],0,4);//年
                $month = substr($j['apply_time'],5,2);//月
                $day = substr($j['apply_time'],8,2);//日
                $program_id = $j['root_proid'];
                $filepath_array = explode('/',$filepath);
                $count = count($filepath_array);
                $file = $filepath_array[$count-1];
                $remote_file = 'SFTP/REPORT/'.$program_id.'/INSPECTION/'.$year.'/'.$month.'/'.$day;
                Ftp::upload($filepath,$remote_file,$file);
            }
            if($n==800){
                goto end;
            }
        }
        $sql = "select * from bac_routine_check where root_proid = $program_id and apply_time like '%$date%'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        $params['ftp'] = '1';
        foreach($rows as $i => $j){
            $params['check_id'] = $j['check_id'];
            $success = '';
            $app_id = 'ROUTINE';
            if($j['status'] == 1 || $j['status'] == 2){
                if($j['save_path'] == ''){
                    $n++;
                    echo $params['check_id'];
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        $success = 'OK';
                        echo $success;
                    }
                }
            }
            if($success == 'OK'){
                $year = substr($j['apply_time'],0,4);//年
                $month = substr($j['apply_time'],5,2);//月
                $day = substr($j['apply_time'],8,2);//日
                $program_id = $j['root_proid'];
                $filepath_array = explode('/',$filepath);
                $count = count($filepath_array);
                $file = $filepath_array[$count-1];
                $remote_file = 'SFTP/REPORT/'.$program_id.'/CHECKLIST/'.$year.'/'.$month.'/'.$day;
                Ftp::upload($filepath,$remote_file,$file);
            }
            if($n==800){
                goto end;
            }
        }
//        $sql = "select * from ra_swp_basic where main_proid = $program_id  ";
//        $n = 0;
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        foreach($rows as $i => $j){
//            $params['ra_swp_id'] = $j['ra_swp_id'];
//            $success = '';
//            $app_id = 'RA';
//            if($j['status'] == 4 || $j['status'] == 3){
//                if($j['save_path'] == ''){
//                    $n++;
//                    $filepath = DownloadPdf::transferDownload($params,$app_id);
//                    if(file_exists($filepath)){
//                        $success = 'OK';
//                        echo $success;
//                    }
//                }
//            }
//            if($success == 'OK'){
//                $year = substr($j['record_time'],0,4);//年
//                $month = substr($j['record_time'],5,2);//月
//                $day = substr($j['record_time'],8,2);//日
//                $program_id = $j['program_id'];
//                $contractor_id = $j['add_conid'];
//                $filepath_array = explode('/',$filepath);
//                $count = count($filepath_array);
//                $file = $filepath_array[$count-1];
//                $remote_file = 'SFTP/REPORT/'.$program_id.'/'.$app_id.'/'.$year.'/'.$month.'/'.$day;
//                Ftp::upload($filepath,$remote_file,$file);
//            }
//            if($n==800){
//                goto end;
//            }
//        }
        $sql = "select * from accident_basic where root_proid = $program_id and record_time like '%$date%' ";
//        $sql = "select * from accident_basic where apply_id = '1570686215651'  ";
        $n = 0;
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if($pro_params != '0') {
            $acc_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('acci_report', $acc_params)) {
                $params['type'] = $acc_params['acci_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        $params['ftp'] = '1';
        foreach($rows as $i => $j){
            $params['id'] = $j['apply_id'];
            $success = '';
            $app_id = 'ACCI';
            if($j['status'] == 1){
                if($j['save_path'] == ''){
                    $n++;
                    echo $j['apply_id'];
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        $success = 'OK';
                        echo $success;
                    }
                }
            }
            if($success == 'OK'){
                $year = substr($j['record_time'],0,4);//年
                $month = substr($j['record_time'],5,2);//月
                $day = substr($j['record_time'],8,2);//日
                $program_id = $j['root_proid'];
                $filepath_array = explode('/',$filepath);
                $count = count($filepath_array);
                $file = $filepath_array[$count-1];
                $remote_file = 'SFTP/REPORT/'.$program_id.'/Accident/'.$year.'/'.$month.'/'.$day;
                Ftp::upload($filepath,$remote_file,$file);
            }
            if($n==800){
                goto end;
            }
        }

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