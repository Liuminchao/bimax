<?php

/**
 * 事项类型(QAQC检查)
 * @author LiuMinchao
 */
class QualityItemType extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_qaqc_item_type';
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

    //事项类型列表
    public static function typeText($assess_id) {
        $sql = "select * from bac_qaqc_item_type where assess_id = '".$assess_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['item_id']] = $row['item_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['item_id']] = $row['item_name_en'];
                }
            }
        }
        return $rs;
    }

    //事项全部类型列表
    public static function allText() {
        $sql = "select * from bac_qaqc_item_type ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['item_id']] = $row['item_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['item_id']] = $row['item_name_en'];
                }
            }
        }
        return $rs;
    }
}
