<?php

/**
 * 例行检查(其他)类型
 * @author LiuMinchao
 */
class QaFormDataReal extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_form_item_comment';
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

    //根据编号查询步骤
    public static function detailList($check_id,$data_id){

        $sql = "SELECT * FROM qa_form_item_comment WHERE  check_id = '".$check_id."' and data_id = '".$data_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
}
