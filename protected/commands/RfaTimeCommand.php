<?php
class RfaTimeCommand extends CConsoleCommand
{
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic rfatime bach
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    public function actionBach()
    {
        $date =  date('Y-m-d',strtotime('+3 day'));

        $sql = "select * from rf_record where date_sub(valid_time, interval 3 day) = CURDATE() and status ='0'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            $rf_model = RfList::model()->findByPk($j['check_id']);
            $sql = "select step from rf_record_user
                 where check_id=:check_id group by step order by step desc limit 1";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $j['check_id'], PDO::PARAM_STR);
            $rows = $command->queryAll();
            $step = $rows[0]['step'];
            $rf_user_list = RfUser::userListByStep($j['check_id'],$step);
            $to_user = array();
            $cc_user = array();
            foreach($rf_user_list as $i => $j){
                if($j['type'] == '1'){
                    $to_user[] = $j['user_id'];
                }
                if($j['type'] == '2'){
                    $cc_user[] = $j['user_id'];
                }
            }
            $apply_user_id = $rf_model->apply_user_id;
            $args['check_id'] = $rf_model->check_id;
            $args['check_no'] = $rf_model->check_no;
            $args['subject'] = $rf_model->subject;
            $args['valid_time'] = $rf_model->valid_time;
            foreach($to_user as $i => $to_user_id){
                MailType::sendMail2($to_user_id,$args);
                sleep(8);
            }
            foreach($cc_user as $j => $cc_user_id){
                MailType::sendMail2($cc_user_id,$args);
                sleep(8);
            }
            MailType::sendMail2($apply_user_id,$args);
            sleep(8);
        }

        $sql = "select * from rf_record where valid_time = CURDATE() and status ='0'  ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            $rf_model = RfList::model()->findByPk($j['check_id']);
            $sql = "select step from rf_record_user
                 where check_id=:check_id group by step order by step desc limit 1";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $j['check_id'], PDO::PARAM_STR);
            $rows = $command->queryAll();
            $step = $rows[0]['step'];
//            $step = $rf_model->current_step;
            $rf_user_list = RfUser::userListByStep($j['check_id'],$step);
            $to_user = array();
            $cc_user = array();
            foreach($rf_user_list as $i => $j){
                if($j['type'] == '1'){
                    $to_user[] = $j['user_id'];
                }
                if($j['type'] == '2'){
                    $cc_user[] = $j['user_id'];
                }
            }
            $apply_user_id = $rf_model->apply_user_id;
            $args['check_id'] = $rf_model->check_id;
            $args['check_no'] = $rf_model->check_no;
            $args['subject'] = $rf_model->subject;
            $args['valid_time'] = $rf_model->valid_time;
            $args['apply_user_id'] = $rf_model->apply_user_id;
            foreach($to_user as $i => $to_user_id){
                MailType::sendMail3($to_user_id,$args);
                sleep(8);
            }
            foreach($cc_user as $j => $cc_user_id){
                MailType::sendMail3($cc_user_id,$args);
                sleep(8);
            }
            MailType::sendMail3($apply_user_id,$args);
            sleep(8);
        }

        $sql = "update rf_record set status = '2' where date_sub(valid_time, interval 3 day) < CURDATE() and status ='0'";
        $command = Yii::app()->db->createCommand($sql);
        $r = $command->execute();

        echo 'Success';
    }
}