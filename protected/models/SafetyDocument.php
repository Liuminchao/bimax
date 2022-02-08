<?php

/**
 * 安全检查文档
 * @author LiuMinchao
 */
class SafetyDocument extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_safety_document';
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

    //安全检查文档列表
    public static function queryDocument($id) {
        $sql = "select * from bac_safety_document where check_id = '".$id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key] = $row['doc_name'];
            }
        }else{
            $rs = array();
        }
        return $rs;
    }

    //查询详情
    public static function detailList($check_id){

        $sql = "SELECT * FROM bac_safety_document where check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
}
