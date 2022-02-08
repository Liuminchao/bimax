<?php
class BachCommand extends CConsoleCommand
{
    //php /opt/www-nginx/web/test/idd/protected/yiic bach dmsuser
    public function actionUserQrcode(){
        $sql = "select qrcode,contractor_id,user_id from bac_staff  ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $value){
            if(!isset($value['qrcode'])){
                Staff::buildQrCode($value['contractor_id'],$value['user_id']);
                echo 'OK';
            }
        }
    }
    public function actionDmsUser(){
        $operator_id = '98738065';
        $user = Staff::userByPhone($operator_id);
        $user_id = $user[0]['user_id'];
        $staff_model =Staff::model()->findByPk($user_id);
        $contractor_id = $staff_model->contractor_id;
        $result = Dms::NewContractor($contractor_id);
        //var_dump($result);
        $result = Dms::NewUser($user_id);
        //var_dump($result);
        if($result['code'] == '100'){
            $staff_model =Staff::model()->findByPk($user_id);
            $staff_model->dms_tag = '1';
            $staff_model->save();
        }
        $program_id = '1261';
        $root_proid = '1261';
//        $result = Dms::AddGroup($root_proid,$user_id);
        $result = Dms::NewUser($user_id,$root_proid);
        //var_dump($result);
        if($result['code'] == '100'){
            $sql = "UPDATE bac_program_user_q SET dms_tag = '1' WHERE user_id = '".$user_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->execute();
        }
    }
    public function actionDeviceQrcode(){
        $sql = "select qrcode,contractor_id,primary_id from bac_device ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $value){
            if(!isset($value['qrcode'])){
                Device::buildQrCode($value['contractor_id'],$value['primary_id']);
                echo 'OK';
            }
        }
    }
    public function actionTest()
    {
        $bca_type= CertificateType::passType();
        $sql = "select a.contractor_id,a.work_no,a.work_pass_type,b.* from bac_staff a,bac_staff_info b where a.user_id =b.user_id and a.status = 0 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $k => $v){
            if($v['bca_photo'] != ''){
                $certificate_type = $bca_type[$v['work_pass_type']];
                if(substr($v['bca_photo'],0,9) == '/filebase')
                    $bca_src =  '/opt/www-nginx/web'.$v['bca_photo'];

                if(file_exists($bca_src)) {
                    $name = substr($v['bca_photo'],25);
                    $file_name = explode('.',$name);
                    $size = filesize($bca_src)/1024;
                    $aptitude_size = sprintf('%.2f',$size);
                    $aptitude_name = $file_name[0];
                    if(array_key_exists('1',$file_name)){
                        $aptitude_type = $file_name[1];
                    }else{
                        $aptitude_type = '';
                    }
                    $model = new UserAptitude('create');
                    try {
                        $model->user_id = $v['user_id'];
                        $model->aptitude_name = $aptitude_name;
                        $model->aptitude_photo = $v['bca_photo'];
                        $model->aptitude_content = $v['work_no'];
                        $model->contractor_id = $v['contractor_id'];
                        $model->permit_startdate = $v['bca_issue_date'];
                        $model->permit_enddate = $v['bca_expire_date'];
                        $model->certificate_type = $certificate_type;
                        $model->aptitude_type = $aptitude_type;
                        $model->aptitude_size = $aptitude_size;
                        $model->save();
                    } catch (Exception $e) {
                        //$trans->rollBack();
                        $r['status'] = -1;
                        $r['msg'] = $e->getmessage();
                        $r['refresh'] = false;
                        return $r;
                    }
                }
            }
            if($v['ppt_photo'] != ''){
                if(substr($v['ppt_photo'],0,9) == '/filebase')
                    $ppt_src =  '/opt/www-nginx/web'.$v['ppt_photo'];

                if(file_exists($ppt_src)) {
                    $name = substr($v['ppt_photo'],25);
                    $file_name = explode('.',$name);
                    $size = filesize($ppt_src)/1024;
                    $aptitude_size = sprintf('%.2f',$size);
                    $aptitude_name = $file_name[0];
                    if(array_key_exists('1',$file_name)){
                        $aptitude_type = $file_name[1];
                    }else{
                        $aptitude_type = '';
                    }
                    $model = new UserAptitude('create');
                    try {
                        $model->user_id = $v['user_id'];
                        $model->aptitude_name = $aptitude_name;
                        $model->aptitude_photo = $v['ppt_photo'];
                        $model->aptitude_content = $v['passport_no'];
                        $model->contractor_id = $v['contractor_id'];
                        $model->permit_startdate = $v['ppt_issue_date'];
                        $model->permit_enddate = $v['ppt_expire_date'];
                        $model->certificate_type = '40';
                        $model->aptitude_type = $aptitude_type;
                        $model->aptitude_size = $aptitude_size;
                        $model->save();
                    } catch (Exception $e) {
                        //$trans->rollBack();
                        $r['status'] = -1;
                        $r['msg'] = $e->getmessage();
                        $r['refresh'] = false;
                        return $r;
                    }
                }
            }
            if($v['csoc_photo'] != ''){
                if(substr($v['csoc_photo'],0,9) == '/filebase')
                    $csoc_src =  '/opt/www-nginx/web'.$v['csoc_photo'];

                if(file_exists($csoc_src)) {
                    $name = substr($v['csoc_photo'],25);
                    $file_name = explode('.',$name);
                    $size = filesize($csoc_src)/1024;
                    $aptitude_size = sprintf('%.2f',$size);
                    $aptitude_name = $file_name[0];
                    if(array_key_exists('1',$file_name)){
                        $aptitude_type = $file_name[1];
                    }else{
                        $aptitude_type = '';
                    }

                    $model = new UserAptitude('create');
                    try {
                        $model->user_id = $v['user_id'];
                        $model->aptitude_name = $aptitude_name;
                        $model->aptitude_photo = $v['csoc_photo'];
                        $model->aptitude_content = $v['csoc_no'];
                        $model->contractor_id = $v['contractor_id'];
                        $model->permit_startdate = $v['csoc_issue_date'];
                        $model->permit_enddate = $v['csoc_expire_date'];
                        $model->certificate_type = '31';
                        $model->aptitude_type = $aptitude_type;
                        $model->aptitude_size = $aptitude_size;
                        $model->save();
                    } catch (Exception $e) {
                        //$trans->rollBack();
                        $r['status'] = -1;
                        $r['msg'] = $e->getmessage();
                        $r['refresh'] = false;
                        return $r;
                    }
                }
            }
        }
    }
    public function  actionParams() {
        $sql = "select * from bac_contractor ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $list){
            $proj_cnt = Program::model()->count('contractor_id=:contractor_id AND status=:status', array('contractor_id' => $list['contractor_id'],'status'=>Program::STATUS_NORMAL));
            $con_model = Contractor::model()->findByPk($list['contractor_id']);
            if($proj_cnt <1){
                $params['pro_cnt'] = 1;
                $json_params = json_encode($params);
                $con_model->params = $json_params;
            }else{
                $params['pro_cnt'] = $proj_cnt;
                $json_params = json_encode($params);
                $con_model->params = $json_params;
            }
            $con_model->save();
        }
    }

    public function actionUpdate(){
        $sql = "select * from ptw_apply_basic where program_id = '482' and record_time >='2018-10-28' and record_time <= '2019-06-27' and status = '4' and add_operator = '6|5|1' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            $step = 4;
            $sql = "select * from bac_check_apply_detail where apply_id=:apply_id and step=:step";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $command->bindParam(":step", $step, PDO::PARAM_STR);
            $r = $command->queryAll();

            $date = substr($r[0]['apply_time'],0,10);
            $date_1 = $date.' 21:45:01';
            $date_2 = $date.' 21:48:03';
            $step_1 = '5';
            $step_2 = '6';

            $sql = 'UPDATE bac_check_apply_detail set apply_time=:apply_time,deal_time=:deal_time WHERE apply_id=:apply_id and step = :step';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_time", $date_1, PDO::PARAM_STR);
            $command->bindParam(":deal_time", $date_1, PDO::PARAM_STR);
            $command->bindParam(":step", $step_1, PDO::PARAM_STR);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $rs = $command->execute();

            $sql = 'UPDATE bac_check_apply_detail set apply_time=:apply_time,deal_time=:deal_time WHERE apply_id=:apply_id and step = :step';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":deal_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":step", $step_2, PDO::PARAM_STR);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $rs = $command->execute();


//            $date = substr($j['record_time'],0,10);
//            $date_1 = $date.' 21:45:01';
//            $date_2 = $date.' 21:48:03';
//            $params_1['app_id'] = 'PTW';
//            $params_1['deal_type'] = '4';
//            $params_1['step'] = '5';
//            $params_1['deal_user_id'] = '14002';
//            $params_1['remark'] = '';
//            $params_1['longitude'] = '103.6767214';
//            $params_1['address'] = '3E Gul Cir, Singapore 629633';
//            $params_1['latitude'] = '1.3125137';
//            $params_1['pic'] = '-1';
//            $params_1['status'] = '1';
//            $params_1['apply_time'] =$date_1;
//            $params_1['deal_time'] = $date_1;
//            $params_1['check_list'] = '';
//            $sql = "INSERT INTO  bac_check_apply_detail (apply_id,app_id,deal_type,step,deal_user_id,remark,longitude,address,latitude,pic,status,apply_time,deal_time,check_list)VALUES(:apply_id,:app_id,:deal_type,:step,:deal_user_id,:remark,:longitude,:address,:latitude,:pic,:status,:apply_time,:deal_time,:check_list)";
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
//            $command->bindParam(":app_id", $params_1['app_id'], PDO::PARAM_STR);
//            $command->bindParam(":deal_type", $params_1['deal_type'], PDO::PARAM_STR);
//            $command->bindParam(":step", $params_1['step'], PDO::PARAM_STR);
//            $command->bindParam(":deal_user_id", $params_1['deal_user_id'], PDO::PARAM_STR);
//            $command->bindParam(":remark", $params_1['remark'], PDO::PARAM_STR);
//            $command->bindParam(":longitude", $params_1['longitude'], PDO::PARAM_STR);
//            $command->bindParam(":address", $params_1['address'], PDO::PARAM_STR);
//            $command->bindParam(":latitude", $params_1['latitude'], PDO::PARAM_STR);
//            $command->bindParam(":pic", $params_1['pic'], PDO::PARAM_STR);
//            $command->bindParam(":status", $params_1['status'], PDO::PARAM_STR);
//            $command->bindParam(":apply_time", $params_1['apply_time'], PDO::PARAM_STR);
//            $command->bindParam(":deal_time", $params_1['deal_time'], PDO::PARAM_STR);
//            $command->bindParam(":check_list", $params_1['check_list'], PDO::PARAM_STR);
//            $rs = $command->execute();
//
//            $params_2['app_id'] = 'PTW';
//            $params_2['deal_type'] = '5';
//            $params_2['step'] = '6';
//            $params_2['deal_user_id'] = '7277';
//            $params_2['remark'] = '';
//            $params_2['longitude'] = '103.6767214';
//            $params_2['address'] = '3E Gul Cir, Singapore 629633';
//            $params_2['latitude'] = '1.3125137';
//            $params_2['pic'] = '-1';
//            $params_2['status'] = '1';
//            $params_2['apply_time'] =$date_2;
//            $params_2['deal_time'] = $date_2;
//            $params_2['check_list'] = '';
//            $sql = "INSERT INTO  bac_check_apply_detail (apply_id,app_id,deal_type,step,deal_user_id,remark,longitude,address,latitude,pic,status,apply_time,deal_time,check_list)VALUES(:apply_id,:app_id,:deal_type,:step,:deal_user_id,:remark,:longitude,:address,:latitude,:pic,:status,:apply_time,:deal_time,:check_list)";
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
//            $command->bindParam(":app_id", $params_2['app_id'], PDO::PARAM_STR);
//            $command->bindParam(":deal_type",$params_2['deal_type'], PDO::PARAM_STR);
//            $command->bindParam(":step", $params_2['step'], PDO::PARAM_STR);
//            $command->bindParam(":deal_user_id", $params_2['deal_user_id'], PDO::PARAM_STR);
//            $command->bindParam(":remark", $params_2['remark'], PDO::PARAM_STR);
//            $command->bindParam(":longitude", $params_2['longitude'], PDO::PARAM_STR);
//            $command->bindParam(":address", $params_2['address'], PDO::PARAM_STR);
//            $command->bindParam(":latitude", $params_2['latitude'], PDO::PARAM_STR);
//            $command->bindParam(":pic", $params_2['pic'], PDO::PARAM_STR);
//            $command->bindParam(":status", $params_2['status'], PDO::PARAM_STR);
//            $command->bindParam(":apply_time", $params_2['apply_time'], PDO::PARAM_STR);
//            $command->bindParam(":deal_time", $params_2['deal_time'], PDO::PARAM_STR);
//            $command->bindParam(":check_list", $params_2['check_list'], PDO::PARAM_STR);
//            $rs = $command->execute();

            $params_3['current_step'] = '6';
            $params_3['result'] = '4';
            $sql = 'UPDATE bac_check_apply set current_step=:current_step,result=:result,end_time=:end_time WHERE apply_id=:apply_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":current_step", $params_3['current_step'], PDO::PARAM_STR);
            $command->bindParam(":result", $params_3['result'], PDO::PARAM_STR);
            $command->bindParam(":end_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $rs = $command->execute();

//            $params_4['add_operator'] = '6|5|1';
//            $params_4['status'] = '4';
//            $sql = 'UPDATE ptw_apply_basic set add_operator=:add_operator,status=:status WHERE apply_id=:apply_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":add_operator", $params_4['add_operator'], PDO::PARAM_STR);
//            $command->bindParam(":status", $params_4['status'], PDO::PARAM_STR);
//            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
//            $rs = $command->execute();

            echo 'OK';
        }
    }


    public function actionDeletepbu()
    {
        $sql = "SELECT * FROM `pbu_info` group by project_id, model_id, pbu_id having count(1) > 1 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        $trans = Yii::app()->db->beginTransaction();
//        try {
//
//            $trans->commit();
//        } catch (Exception $e) {
//            echo '异常:'.$e->getMessage();
//            $trans->rollback();
//        }
        foreach($rows as $i => $pbu){
            echo $pbu['project_id'].' ';
            echo $pbu['model_id'].' ';
            echo $pbu['pbu_id'].' ';
            $model = new PbuInfoDel('create');
            foreach($pbu as $index => $val){
                if($index != 'h_type'){
                    $model->$index = $val;
                }
            }
            $model->save();

            $del_sql = "delete from pbu_info where id in (select a.id from (select id from pbu_info where pbu_id = :pbu_id_1 and project_id =:project_id_1 and model_id = :model_id_1 and id not in (select max(id) from pbu_info where pbu_id = :pbu_id_2 and project_id =:project_id_2 and model_id = :model_id_2)) a)";
            $command = Yii::app()->db->createCommand($del_sql);
            $command->bindParam(":project_id_1", $pbu['project_id'], PDO::PARAM_STR);
            $command->bindParam(":model_id_1", $pbu['model_id'], PDO::PARAM_STR);
            $command->bindParam(":pbu_id_1", $pbu['pbu_id'], PDO::PARAM_STR);
            $command->bindParam(":project_id_2", $pbu['project_id'], PDO::PARAM_STR);
            $command->bindParam(":model_id_2", $pbu['model_id'], PDO::PARAM_STR);
            $command->bindParam(":pbu_id_2", $pbu['pbu_id'], PDO::PARAM_STR);
            $rs = $command->execute();
            echo '完成';
        }
    }

    public function actionPbuPlan(){
        $program_id = '1261';
        $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and status='0' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_STR);
        $pbu_list = $command->queryAll();
        $template_id = '13';
        $sql = "SELECT * FROM task_stage WHERE template_id=:template_id and status='0' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
        $stage_list = $command->queryAll();
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach($pbu_list as $i => $j){
                foreach($stage_list as $x => $y){
                    $record_time = date('Y-m-d H:i:s', time());
                    $status= '0';
                    $model = new PbuPlan('create');
                    $model->project_id = $program_id;
                    $model->model_id = $j['model_id'];
                    $model->pbu_id = $j['pbu_id'];
                    $model->template_id = $y['template_id'];
                    $model->stage_id = $y['stage_id'];
                    if($y['stage_id'] == '43'){
                        $model->plan_start_date = '';
                        $model->plan_end_date = '';
                    }
                    if($y['stage_id'] == '44'){
                        $model->plan_start_date = $j['start_d'];
                        $model->plan_end_date = $j['finish_d'];
                    }
                    if($y['stage_id'] == '45'){
                        $model->plan_start_date = '';
                        $model->plan_end_date = $j['start_e'];
                    }
                    if($y['stage_id'] == '59'){
                        $model->plan_start_date = $j['start_b'];
                        $model->plan_end_date = $j['finish_b'];
                    }
                    if($y['stage_id'] == '60'){
                        $model->plan_start_date = '';
                        $model->plan_end_date = '';
                    }
                    if($y['stage_id'] == '61'){
                        if($j['start_f'] && $j['finish_f']){
                            $model->plan_start_date = $j['start_f'];
                            $model->plan_end_date = $j['finish_f'];
                        }
                        if($j['start_g'] && $j['finish_g']){
                            $model->plan_start_date = $j['start_g'];
                            $model->plan_end_date = $j['finish_g'];
                        }
                    }
                    $model->save();
                }
            }
            $trans->commit();
            echo 'success';
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
    }

    public function actionOperatorMenu(){
        $sql = "select * from bac_operator where operator_id in (select a.user_phone from bac_staff a left join bac_program_user_q b on a.user_id = b.user_id where b.check_status = '11') or operator_role = '00'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $trans = Yii::app()->db->beginTransaction();
        try {
            $menu_list = Menu::appMenuList();
            $status = '00';
            foreach($rows as $i => $j){
                foreach($menu_list as $menu_id => $menu_name){
                    $sql = "insert into bac_operator_menu_q_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":operator_id", $j['operator_id'], PDO::PARAM_STR);
                    $command->bindParam(":menu_id", $menu_id, PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $rs = $command->execute();
                }

                $sql = "insert into bac_operator_menu_q_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                $id = '105';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":operator_id", $j['operator_id'], PDO::PARAM_STR);
                $command->bindParam(":menu_id", $id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->execute();
            }
            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        echo 'Success';
    }
}
