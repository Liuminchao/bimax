<?php

/**
 * 违规设备记录(检查)
 * @author LiuMinchao
 */
class ViolationDevice extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_violation_device';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //查询违规记录
    public static function recordList($check_id){

        $sql = "SELECT * FROM bac_violation_device WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
    //根据设备查询违规记录
    public static function recordListByDevice($device_id) {
        $sql ="SELECT * FROM bac_violation_device WHERE device_id ='".$device_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
}
