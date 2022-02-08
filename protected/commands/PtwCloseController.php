<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/2
 * Time: 0:12
 * 处理的表  bac_check_apply_detail
 * bac_checl_apply
 * ptw_apply_basic
 *
 * 关闭审批  14002


关闭批准  7277


地点  3E Gul Cir, Singapore 629633


经度  103.6767214


纬度  1.3125137


时间
2019-05-17 21:45:01
2019-05-17 21:58:03

步骤  4,5
 *
 */
class PtwCloseController extends CConsoleCommand
{
    public function actionUpdate(){
        $sql = "select * from ptw_apply_basic where apply_id = '1540689290831' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            $date = substr($j['record_time'],0,10);
            $date_1 = $date.' 21:45:01';
            $date_2 = $date.' 21:48:03';
            $sql = "INSERT INTO  bac_check_apply_detail (apply_id,app_id,deal_type,step,deal_user_id,remark,longitude,address,latitude,pic,status,apply_time,deal_time,check_list)VALUES(:apply_id,:app_id,:deal_type,:step,:deal_user_id,:remark,:longitude,:address,:latitude,:pic,:status,:apply_time,:deal_time,:check_list)";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $command->bindParam(":app_id", 'PTW', PDO::PARAM_STR);
            $command->bindParam(":deal_type", '4', PDO::PARAM_STR);
            $command->bindParam(":step", '5', PDO::PARAM_STR);
            $command->bindParam(":deal_user_id", '14002', PDO::PARAM_STR);
            $command->bindParam(":remark", '', PDO::PARAM_STR);
            $command->bindParam(":longitude", '103.6767214', PDO::PARAM_STR);
            $command->bindParam(":address", '3E Gul Cir, Singapore 629633', PDO::PARAM_STR);
            $command->bindParam(":latitude", '1.3125137', PDO::PARAM_STR);
            $command->bindParam(":pic", '-1', PDO::PARAM_STR);
            $command->bindParam(":status", '1', PDO::PARAM_STR);
            $command->bindParam(":apply_time", $date_1, PDO::PARAM_STR);
            $command->bindParam(":deal_time", $date_1, PDO::PARAM_STR);
            $command->bindParam(":check_list", '', PDO::PARAM_STR);

            $sql = "INSERT INTO  bac_check_apply_detail (apply_id,app_id,deal_type,step,deal_user_id,remark,longitude,address,latitude,pic,status,apply_time,deal_time,check_list)VALUES(:apply_id,:app_id,:deal_type,:step,:deal_user_id,:remark,:longitude,:address,:latitude,:pic,:status,:apply_time,:deal_time,:check_list)";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $command->bindParam(":app_id", 'PTW', PDO::PARAM_STR);
            $command->bindParam(":deal_type", '5', PDO::PARAM_STR);
            $command->bindParam(":step", '6', PDO::PARAM_STR);
            $command->bindParam(":deal_user_id", '7277', PDO::PARAM_STR);
            $command->bindParam(":remark", '', PDO::PARAM_STR);
            $command->bindParam(":longitude", '103.6767214', PDO::PARAM_STR);
            $command->bindParam(":address", '3E Gul Cir, Singapore 629633', PDO::PARAM_STR);
            $command->bindParam(":latitude", '1.3125137', PDO::PARAM_STR);
            $command->bindParam(":pic", '-1', PDO::PARAM_STR);
            $command->bindParam(":status", '1', PDO::PARAM_STR);
            $command->bindParam(":apply_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":deal_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":check_list", '', PDO::PARAM_STR);
            $rs = $command->execute();

            $sql = 'UPDATE bac_check_apply set current_step=:current_step,result=:result,end_time=:end_time WHERE apply_id=:apply_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":current_step", '6', PDO::PARAM_STR);
            $command->bindParam(":result", '4', PDO::PARAM_STR);
            $command->bindParam(":end_time", $date_2, PDO::PARAM_STR);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $rs = $command->execute();

            $sql = 'UPDATE ptw_apply_basic set add_operator=:add_operator,status=:status WHERE apply_id=:apply_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":add_operator", '6|5|1', PDO::PARAM_STR);
            $command->bindParam(":status", '4', PDO::PARAM_STR);
            $command->bindParam(":apply_id", $j['apply_id'], PDO::PARAM_STR);
            $rs = $command->execute();
        }
    }
}