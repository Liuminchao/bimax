<?php
class MailCommand extends CConsoleCommand
{
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic rfatime bach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionSend($param1)
    {
        $check_id = $param1;
        $args['check_id'] = $check_id;
        $rf_model = RfList::model()->findByPk($check_id);
        $sql = "select step from rf_record_user
                 where check_id=:check_id group by step order by step desc limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $to_user = RfUser::userList($check_id,$rows[0]['step'],'1');
        $cc_user = RfUser::userList($check_id,$rows[0]['step'],'2');
        if(count($to_user)>0){
            foreach($to_user as $i => $j){
                $to[] = $j['user_id'];
            }
        }

        if(count($cc_user)>0){
            foreach($cc_user as $x => $y){
                $cc[] = $y['user_id'];
            }
        }
        $cc = array_unique($cc);
        $record_time = date('Y-m-d H:i:s');
        foreach($to as $i => $to_user_id){
            $r = MailType::sendMail($to_user_id,$to,$cc,$args);
            sleep(8);
            $txt = '['.$record_time.']'.'  '.'RF Mail'.': '.json_encode($r);
            RfList::write_log($txt);
        }
        if(count($cc)>0){
            foreach($cc as $j => $cc_user_id){
                $r = MailType::sendMail($cc_user_id,$to,$cc,$args);
                sleep(8);
                $txt = '['.$record_time.']'.'  '.'RF Mail'.': '.json_encode($r);
                RfList::write_log($txt);
            }
        }
    }

    public function actionComment($param1,$param2)
    {
        $check_id = $param1;
        $add_user = $param2;
        $args['check_id'] = $check_id;
        $args['add_user'] = $add_user;
        $rf_model = RfList::model()->findByPk($check_id);
        $step = $rf_model->current_step;
        $user = Staff::userByPhone($add_user);
        $to_list = RfUser::userAllList($args['check_id'],'1');
        $cc_list = RfUser::userAllList($args['check_id'],'2');
        $apply_user_id = $rf_model->apply_user_id;
        foreach ($to_list as $x => $y){
            $to_user[] = $y['user_id'];
        }
//                $to_user = $apply_user_id;
        array_push($to_user,$apply_user_id);
        $to_user = array_unique($to_user);
        foreach ($cc_list as $i => $j){
            if($add_user != $j['user_id']){
                $cc_user[] = $j['user_id'];
            }
        }
        if(is_array($cc_user)){
            $cc_user = array_unique($cc_user);
        }

        foreach($to_user as $m => $to_user_id){
            $r = MailType::sendMail4($to_user_id,$to_user,$cc_user,$args);
            sleep(8);
        }

        if(count($cc_user)>0){
            foreach($cc_user as $n => $cc_user_id){
                $r = MailType::sendMail4($cc_user_id,$to_user,$cc_user,$args);
                sleep(8);
            }
        }
    }

    public function actionTransSend($param1)
    {
        $check_id = $param1;
        $args['check_id'] = $check_id;
        $trans_model = TransmittalRecord::model()->findByPk($check_id);
        $user_list = TransmittalUser::userListByStep($check_id,1);
        $to_user = '';
        foreach($user_list as $i => $j){
            if($j['type'] == '1'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $user_name = $user_model->user_name;
                $to_user.=$user_name.' ';
                $to[] = $j['user_id'];
            }
            if($j['type'] == '2'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $cc[] = $j['user_id'];
            }
        }
        $cc = array_unique($cc);
        $record_time = date('Y-m-d H:i:s');
        foreach($to as $i => $to_user_id){
            $r = MailType::sendTransMail($to_user_id,$to,$cc,$args);
            $txt = '['.$record_time.']'.'  '.'Transmittal Send Mail'.': '.json_encode($r);
            RfList::write_log($txt);
        }
        if(count($cc)>0){
            foreach($cc as $j => $cc_user_id){
                $r = MailType::sendTransMail($cc_user_id,$to,$cc,$args);
                $txt = '['.$record_time.']'.'  '.'Transmittal Send Mail'.': '.json_encode($r);
                RfList::write_log($txt);
            }
        }
    }

    public function actionTransReceive($param1,$param2)
    {
        $check_id = $param1;
        $args['check_id'] = $check_id;
        $args['add_user'] = $param2;
        $trans_model = TransmittalRecord::model()->findByPk($check_id);
        $apply_user_id = $trans_model->apply_user_id;
        $user_list = TransmittalUser::userListByStep($check_id,1);
        $to_user = '';
        foreach($user_list as $i => $j){
            if($j['type'] == '2'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $cc[] = $j['user_id'];
            }
        }
        $cc = array_unique($cc);
        $record_time = date('Y-m-d H:i:s');
        $to[] = $apply_user_id;
        $r = MailType::receiveTransMail($apply_user_id,$to,$cc,$args);
        $txt = '['.$record_time.']'.'  '.'Transmittal Receive Mail'.': '.json_encode($r);
        RfList::write_log($txt);
        if(count($cc)>0){
            foreach($cc as $j => $cc_user_id){
                $r = MailType::receiveTransMail($cc_user_id,$to,$cc,$args);
                $txt = '['.$record_time.']'.'  '.'Transmittal Receive Mail'.': '.json_encode($r);
                RfList::write_log($txt);
            }
        }
    }
}