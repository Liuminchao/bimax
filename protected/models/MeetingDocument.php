<?php

/**
 * 培训类型
 * @author LiuMinchao
 */
class MeetingDocument extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'tbm_document';
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
    public static function detailList($meeting_id){

        $sql = "SELECT * FROM tbm_document where meeting_id = '".$meeting_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
}
