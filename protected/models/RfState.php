<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfState extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_state';
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

    /**
     * 详情
     */
    public static function dealList($contractor_id) {
        $sql = "select * from rf_state
                 where contractor_id=:contractor_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }


}
