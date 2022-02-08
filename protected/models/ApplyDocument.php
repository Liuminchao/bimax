<?php

/**
 * PTW文档
 * @author LiuMinchao
 */
class ApplyDocument extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'ptw_document';
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
    public static function detailList($apply_id){

        $sql = "SELECT * FROM ptw_document where apply_id = '".$apply_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
}
