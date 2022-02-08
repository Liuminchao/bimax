<?php

/**
 * 违规记录(检查)
 * @author LiuMinchao
 */
class ViolationRecord extends CActiveRecord {
    
    
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_violation_record';
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
         
        $sql = "SELECT * FROM bac_violation_record WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
    //根据人员查询违规记录
    public static function recordListByUser($user_id) {
        $sql ="SELECT * FROM bac_violation_record WHERE user_id ='".$user_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
}
