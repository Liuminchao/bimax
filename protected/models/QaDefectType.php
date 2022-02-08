<?php

/**
 * Defect Type
 * @author LiuMinchao
 */
class QaDefectType extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 9; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_defect2_type';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('device', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('device', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //已启用
            self::STATUS_DISABLE => ' label-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'region' => Yii::t('proj_project', 'region'),
        );
    }

    //类型
    public static function AllType(){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = " SELECT
                    type_id,type_name,type_name_en
                FROM
                    qa_defect_type 
                WHERE
                    status = '0' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['type_id']]['type_name'] = $row['type_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['type_id']]['type_name'] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }
}
