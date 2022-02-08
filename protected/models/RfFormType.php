<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfFormType extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_form_type';
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
     * 表单列表
     */
    public static function formList($project_id,$type) {
        $sql = "select * from rf_form_type
                 where type=:type and status='0'";
        if($project_id == '2419'){
            $sql.=" and form_id not in ('RF00001','RF00005')";
        }
        $command = Yii::app()->db->createCommand($sql);
//        $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
        $command->bindParam(":type", $type, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

}
