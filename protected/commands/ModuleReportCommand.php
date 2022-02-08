<?php
class ModuleReportCommand extends CConsoleCommand
{
    //php /opt/www-nginx/web/test/ctmgr/protected/yiic modulereport bach
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic modulereport bach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionBach()
    {
//        $program_id = $_GET['program_id'];
//        echo $program_id;
//        exit;
        $program_id = '863';
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
//
        $date = '2019-08';
//
//        ini_set('memory_limit','512M');
        $sql = "select * from ptw_apply_basic where program_id = $program_id and apply_contractor_id = '377' and record_time like '%$date%' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $n = 0;
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('ptw_report', $pro_params)) {
                $params['type'] = $pro_params['ptw_report'];
            } else {
                $params['type'] = 'A';
            }
        }else{
            $params['type'] = 'A';
        }
        foreach($rows as $i => $j){
            $params['id'] = $j['apply_id'];
//            var_dump($params['id']);
            $app_id = 'PTW';
            $check_apply = CheckApply::model()->findByPk($j['apply_id']);
            $step = $check_apply->current_step;
            $deal_type = CheckApplyDetail::dealtypeList($app_id, $j['apply_id'], $step);
            if($deal_type[0]['status'] == 2){
                if($j['save_path'] == ''){
                    $n++;
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        echo 'OK';
                    }
                }
            }else if($deal_type[0]['deal_type'] >= 2 && $deal_type[0]['deal_type']!=8){
                if($j['save_path'] == ''){
                    $n++;
                    $filepath = DownloadPdf::transferDownload($params,$app_id);
                    if(file_exists($filepath)){
                        echo 'OK';
                    }
                }
            }
            if($n==200){
                goto end;
            }
        };
//        $sql = "select * from tbm_meeting_basic where program_id = $program_id and add_conid = '377' and record_time like $date.'%'";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        $n = 0;
//        foreach($rows as $i => $j){
//            if($pro_params != '0') {
//                $pro_params = json_decode($pro_params, true);
//                //判断是否是迁移的
//                if (array_key_exists('tbm_report', $pro_params)) {
//                    $params['type'] = $pro_params['tbm_report'];
//                } else {
//                    $params['type'] = 'A';
//                }
//            }else{
//                $params['type'] = 'A';
//            }
//            $params['id'] = $j['meeting_id'];
//            $app_id = 'TBM';
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
//        $sql = "SELECT * FROM train_apply_basic WHERE program_id = $program_id and add_conid = '377' and record_time like $date.'%' ";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        $n = 0;
//        foreach($rows as $i => $j){
//            if($pro_params != '0') {
//                $pro_params = json_decode($pro_params, true);
//                //判断是否是迁移的
//                if (array_key_exists('train_report', $pro_params)) {
//                    $params['type'] = $pro_params['train_report'];
//                } else {
//                    $params['type'] = 'A';
//                }
//            }else{
//                $params['type'] = 'A';
//            }
//            $params['id'] = $j['training_id'];
//            $app_id = 'TRAIN';
//            if($j['status'] == 1){
//                if($j['save_path'] == ''){
////                    echo $j['training_id'];
////                    exit;
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
//        $sql = "select * from bac_safety_check where root_proid = $program_id and contractor_id = '377' and apply_time like $date.'%'  ";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        $n = 0;
//        foreach($rows as $i => $j){
//            if($pro_params != '0') {
//                $pro_params = json_decode($pro_params, true);
//                //判断是否是迁移的
//                if (array_key_exists('wsh_report', $pro_params)) {
//                    $params['type'] = $pro_params['wsh_report'];
//                } else {
//                    $params['type'] = 'A';
//                }
//            }else{
//                $params['type'] = 'A';
//            }
//            $params['check_id'] = $j['check_id'];
//            $app_id = 'WSH';
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