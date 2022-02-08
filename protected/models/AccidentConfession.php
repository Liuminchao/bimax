<?php

/**
 * 意外口供
 * @author LiuMinchao
 */
class AccidentConfession extends CActiveRecord {

    const STATUS_AUDITING = '0'; //审批中
    const STATUS_FINISH = '1'; //审批完成
    const STATUS_REJECT = '2'; //审批不通过

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'accident_confession';
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

    public static function getConfessionList($apply_id) {
        $sql = "SELECT user_name,role_name_en,confession FROM accident_confession  WHERE  apply_id=:apply_id ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (Yii::app()->language == 'zh_CN') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['confession'] = $row['confession'];
                    $rs[$key]['user_name'] = $row['user_name'];
                    $rs[$key]['role_name'] = $row['role_name_en'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['confession'] = $row['confession'];
                    $rs[$key]['user_name'] = $row['user_name'];
                    $rs[$key]['role_name'] = $row['role_name_en'];
                }
            }
        }else{
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['confession'] = $row['confession'];
                    $rs[$key]['user_name'] = $row['user_name'];
                    $rs[$key]['role_name'] = $row['role_name_en'];
                }
            }else{
                $rs = array();
            }
        }

        return $rs;
    }
}
