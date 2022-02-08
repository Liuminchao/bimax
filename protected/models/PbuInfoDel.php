<?php

/**
 * RevitModel
 *
 * @author liuxy
 */
class PbuInfoDel extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pbu_info_del';
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

}