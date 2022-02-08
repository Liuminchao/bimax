<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfUser extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_record_user';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //创建
    public static function insertList($args,$to_user){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $id = date('Ymd').rand(01,99).date('His');
        $status ='1';
        $step = $args['step']+1;
        $record_time = date("Y-m-d H:i:s");
        foreach($to_user as $i => $to_user_id){
            if($to_user_id){
                $staff_model = Staff::model()->findByPk($to_user_id);
                $to_contractor_id = $staff_model->contractor_id;
                $con_model = Contractor::model()->findByPk($to_contractor_id);
                $to_contractor_name = $con_model->contractor_name;
                $to_name = $staff_model->user_name;
                $type = '1';
                $sql = "insert into rf_record_user (check_id,step,contractor_id,contractor_name,user_id,user_name,type) values (:check_id,:step,:contractor_id,:contractor_name,:user_id,:user_name,:type)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $to_contractor_id, PDO::PARAM_STR);
                $command->bindParam(":contractor_name", $to_contractor_name, PDO::PARAM_STR);
                $command->bindParam(":user_id", $to_user_id, PDO::PARAM_STR);
                $command->bindParam(":user_name", $to_name, PDO::PARAM_STR);
                $command->bindParam(":type", $type, PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }

        if($args['cc'] != 'null'){
            $cc = explode(',',$args['cc']);
            foreach($cc as $j => $cc_id){
                if(is_numeric($cc_id)){
                    $staff_model = Staff::model()->findByPk($cc_id);
                    $cc_name = $staff_model->user_name;
                    $cc_contractor_id = $staff_model->contractor_id;
                    $con_model = Contractor::model()->findByPk($cc_contractor_id);
                    $cc_contractor_name = $con_model->contractor_name;
                }else{
                    $cc_name = $cc_id;
                }
                $type = '2';
//                $tag = '0';
                if($cc_id){
                    $sql = "insert into rf_record_user (check_id,step,contractor_id,contractor_name,user_id,user_name,type) values (:check_id,:step,:contractor_id,:contractor_name,:user_id,:user_name,:type)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                    $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                    $command->bindParam(":contractor_id", $cc_contractor_id, PDO::PARAM_STR);
                    $command->bindParam(":contractor_name", $cc_contractor_name, PDO::PARAM_STR);
                    $command->bindParam(":user_id", $cc_id, PDO::PARAM_STR);
                    $command->bindParam(":user_name", $cc_name, PDO::PARAM_STR);
                    $command->bindParam(":type", $type, PDO::PARAM_STR);
                    $rs = $command->execute();
                }
            }
        }
        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['id'] = $id;
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //提交
    public static function sendList($args,$to_user_id){
        $sql = "DELETE FROM rf_record_user WHERE check_id =:check_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->execute();

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $id = date('Ymd').rand(01,99).date('His');
        $status ='1';
        $step = $args['step']+1;
        $record_time = date("Y-m-d H:i:s");
        if($to_user_id){
            $staff_model = Staff::model()->findByPk($to_user_id);
            $to_name = $staff_model->user_name;
            $type = '1';
            $sql = "insert into rf_record_user (check_id,step,user_id,user_name,type) values (:check_id,:step,:user_id,:user_name,:type)";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
            $command->bindParam(":user_id", $to_user_id, PDO::PARAM_STR);
            $command->bindParam(":user_name", $to_name, PDO::PARAM_STR);
            $command->bindParam(":type", $type, PDO::PARAM_STR);
            $rs = $command->execute();
        }
        if(count($args['cc'])>0){
            $cc = explode(',',$args['cc']);
            foreach($cc as $j => $cc_id){
                if(is_numeric($cc_id)){
                    $staff_model = Staff::model()->findByPk($cc_id);
                    $cc_name = $staff_model->user_name;
                }else{
                    $cc_name = $cc_id;
                }
                $type = '2';
//                $tag = '0';
                $sql = "insert into rf_record_user (check_id,step,user_id,user_name,type) values (:check_id,:step,:user_id,:user_name,:type)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                $command->bindParam(":user_id", $cc_id, PDO::PARAM_STR);
                $command->bindParam(":user_name", $cc_name, PDO::PARAM_STR);
                $command->bindParam(":type", $type, PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }
        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['id'] = $id;
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($check_id) {
        $sql = "select * from rf_record_user
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 某一步to/cc的人
     */
    public static function userList($check_id,$step,$type) {
        $sql = "select * from rf_record_user
                 where check_id=:check_id and step=:step and type=:type ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $command->bindParam(":type", $type, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 全部to/cc的人
     */
    public static function userAllList($check_id,$type) {
        $sql = "select * from rf_record_user
                 where check_id=:check_id and type=:type ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":type", $type, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 某一步to/cc的人
     */
    public static function userListByStep($check_id,$step) {
        $sql = "select * from rf_record_user
                 where check_id=:check_id and step=:step ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 判断最后一步该人员是什么类型
     */
    public static function userListByRecord($check_id,$user_id) {
        $sql = "select * from rf_record_user
                 where check_id=:check_id order by step desc limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $step = $rows[0]['step'];
        $sql = "select * from rf_record_user
                 where check_id=:check_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rs = $command->queryAll();
        $type = '0';
        if(count($rs)>0){
            foreach($rs as $i => $j){
                if($j['user_id'] == $user_id){
                    if($type != '1'){
                        $type = $j['type'];
                    }
                }
            }
        }
        return $type;
    }

    /**
     * 相同check_no 下其他单子to的人
     */
    public static function otherTo($check_id) {
        $rf_model = RfList::model()->findByPk($check_id);
        $check_no = $rf_model->check_no;
        $sql = "select * from rf_record_user 
                 where check_id in (select check_id from rf_record where check_no = :check_no and check_id <> :check_id) and type = '1' and step = '1'";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_no", $check_no, PDO::PARAM_STR);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 相同check_no 下其他group
     */
    public static function otherGroup($check_id) {
        $rf_model = RfList::model()->findByPk($check_id);
        $check_no = $rf_model->check_no;
        $sql = "select * from rf_record where check_no = :check_no and check_id <> :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_no", $check_no, PDO::PARAM_STR);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
}
