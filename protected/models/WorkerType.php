<?php

/**
 * 工种
 * @author Liuminchao
 */
class WorkerType extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'bac_worker_type';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MeetingWorker the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'worker_type' => Yii::t('comp_ra', 'Worker type'),
        );
    }

    /** 全部工种列表
     * @return type
     */
    public static function getAllType() {
        if (Yii::app()->language == 'zh_CN') {
            $sql = "select * from bac_worker_type ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "select * from bac_worker_type ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }

    /** 工种列表
     * @return type
     */
    public static function getType() {
        if (Yii::app()->language == 'zh_CN') {
            $sql = "select * from bac_worker_type where tag =2";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "select * from bac_worker_type where tag=2";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }

    /** 按类型查找工种列表
     * @return type
     */
    public static function findType($tag) {
        if (Yii::app()->language == 'zh_CN') {
            $sql = "select * from bac_worker_type where tag = '".$tag."'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "select * from bac_worker_type where tag = '".$tag."'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }
}
