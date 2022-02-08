<?php

/**
 * 培训类型
 * @author LiuMinchao
 */
class TrainWorkerTemp extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'train_apply_worker_temp';
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
    public static function queryWorker($training_id) {
        $sql = "select * from train_apply_worker_temp where training_id = '".$training_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['user_name'] = $row['user_name'];
                $rs[$key]['contractor_name'] = $row['contractor_name'];
                $rs[$key]['role_name'] = $row['contractor_name'];
            }
        }else{
            $rs = array();
        }
        return $rs;
    }
}
