<?php

/**
 * 例行检查(其他)类型
 * @author LiuMinchao
 */
class RoutineTypeOther extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_routine_type_other';
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

    //安全等级列表
    public static function levelText() {
        $sql = "select * from bac_safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['safety_level']] = $row['description'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['safety_level']] = $row['description_en'];
                }
            }
        }
        return $rs;
    }
    //安全等级颜色
    public static function levelCss() {
        $rs = array(
            '0' => '#0000FF', //蓝色
            '1' => '#008B00', //绿色
            '2' => '#FFFF00', //黄色
            '3' => '#FFA500', //橙色
            '4' => '#FF0000', //橙色
        );
        return $rs;
    }
}
