<?php

/**
 * Qa文档
 * @author LiuMinchao
 */
class QaDocument extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_checklist_record_document';
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

    //查询详情
    public static function detailList($check_id){

        $sql = "SELECT * FROM qa_checklist_record_document WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
}
