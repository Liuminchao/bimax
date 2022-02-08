<?php

/**
 * 化学物品类型
 * @author LiuMinchao
 */
class ChemicalType extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_chemical_type';
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
            self::STATUS_NORMAL => Yii::t('chemical', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('chemical', 'STATUS_DISABLE'),
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
    //查询设备类别
    public static function chemicalList(){

        $sql = "SELECT chemical_type_ch,chemical_type_en,type_no FROM bac_chemical_type WHERE status=00 ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            if (Yii::app()->language == 'zh_CN') {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_no']] = $row['chemical_type_ch'];
                }
            } else if (Yii::app()->language == 'en_US') {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_no']] = $row['chemical_type_en'];
                }
            }else{
                foreach ($rows as $key => $row) {
                    $rs[$row['type_no']] = $row['chemical_type_en'];
                }
            }
        }

        return $rs;

    }
}
