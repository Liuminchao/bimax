<?php

/**
 * 会议类型
 * @author LiuMinchao
 */
class MeetingType extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_meeting_type';
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

    //培训类型列表
    public static function typeText() {
        $sql = "select * from bac_meeting_type ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['type_id']] = $row['type_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }
}
