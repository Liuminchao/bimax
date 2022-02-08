<?php

/**
 * 安全等级(检查)
 * @author LiuMinchao
 */
class AccidentType extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'accident_type';
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

    //意外类型列表
    public static function typeList() {
        $sql = "select * from accident_type where status = 0 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['type_id']] = $row['type_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }else{
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }

    //意外类型列表
    public static function typeAllList() {
        $sql = "select * from accident_type where status = 0 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['type_id']]['type_name'] = $row['type_name'];
                $rs[$row['type_id']]['type_name_en'] = $row['type_name_en'];
                $rs[$row['type_id']]['acci_mode'] = $row['acci_mode'];
                $rs[$row['type_id']]['acci_type'] = $row['acci_type'];
            }
        }
        return $rs;
    }


    //意外类型列表
    public static function ListByAccitype($acci_type) {
        $sql = "select * from accident_type where acci_type = '$acci_type' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
}
