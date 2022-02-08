<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfRecordItem extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_record_item';
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

    //添加
    public static function insertItem($args,$item){

        $item_data = json_encode($item);
        $sql = "insert into rf_record_item (check_id,step,location_ref,subject,related_to,valid_time,spec_ref,discipline,item_data) values (:check_id,:step,:location_ref,:subject,:related_to,:valid_time,:spec_ref,:discipline,:item_data)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_INT);
        $command->bindParam(":location_ref", $item['location'], PDO::PARAM_STR);
        $command->bindParam(":subject", $args['subject'], PDO::PARAM_STR);
        $command->bindParam(":related_to", $item['related'], PDO::PARAM_STR);
        $command->bindParam(":valid_time", $args['valid_time'], PDO::PARAM_STR);
        $command->bindParam(":spec_ref", $item['spec_ref'], PDO::PARAM_STR);
        $command->bindParam(":discipline", $args['discipline'], PDO::PARAM_STR);
        $command->bindParam(":item_data", $item_data, PDO::PARAM_STR);
        $rs = $command->execute();

        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
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
        $sql = "select * from rf_record_item
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 根据步骤查详情
     */
    public static function dealListBystep($check_id,$step){
        $sql = "select * from rf_record_item
                 where check_id=:check_id and step=:step ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
}
