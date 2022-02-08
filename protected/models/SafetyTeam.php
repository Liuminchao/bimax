<?php

/**
 * 安全组
 * @author LiuMinchao
 */
class SafetyTeam extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_safety_team';
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

    //安全检查组列表
    public static function queryTeam($check_id) {
        $sql = "select * from bac_safety_team where status = '0' and check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[] = $row['user_id'];
            }
        }else{
            $rs = array();
        }
        return $rs;
    }

    //安全检查组列表
    public static function queryStaff($check_id) {
        $sql = "select * from bac_safety_team where status = '0' and check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[] = $row['user_name'];
            }
        }else{
            $rs = array();
        }
        return $rs;
    }

    //安全检查组
    public static function queryLeader($check_id) {
        $sql = "select * from bac_safety_team where status = '1' and check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[] = $row['user_id'];
            }
        }else{
            $rs = array();
        }
        return $rs;
    }
}
