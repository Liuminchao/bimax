<?php

/**
 * TBM文档
 * @author LiuMinchao
 */
class TbmDocument extends CActiveRecord {


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

    //TBM文档列表
    public static function queryDocument($id) {
        $sql = "select * from tbm_document where meeting_id = '".$id."' ";
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
}
