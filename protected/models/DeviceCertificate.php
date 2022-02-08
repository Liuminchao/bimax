<?php

/**
 * 证书类别
 * @author LiuMinchao
 */
class DeviceCertificate extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_device_certificate';
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
    //设备证书类型
    public static function certificateList(){

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT certificate_name,certificate_type FROM bac_device_certificate WHERE status=0 ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['certificate_type']] = $row['certificate_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT certificate_name_en,certificate_type FROM bac_device_certificate WHERE status=0 ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['certificate_type']] = $row['certificate_name_en'];
                }
            }
        }
        return $rs;

    }
}


