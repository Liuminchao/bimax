<?php

/**
 * 安全等级(检查)
 * @author LiuMinchao
 */
class SafetyCheckFindings extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_safety_check_findings';
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

    //安全类型列表
    public static function typeText() {
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = "select * from bac_safety_check_findings where contractor_id = $contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['findings_id']] = $row['findings_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['findings_id']] = $row['findings_name_en'];
                }else{
                    $rs[$row['findings_id']] = $row['findings_name_en'];
                }
            }
        }else{
            $sql = "select * from bac_safety_check_findings where contractor_id = 0";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    if (Yii::app()->language == 'zh_CN') {
                        $rs[$row['findings_id']] = $row['findings_name'];
                    }else if (Yii::app()->language == 'en_US') {
                        $rs[$row['findings_id']] = $row['findings_name_en'];
                    }else{
                        $rs[$row['findings_id']] = $row['findings_name_en'];
                    }
                }
            }
        }
        return $rs;
    }

    //安全类型列表
    public static function typeTextByplatform($contractor_id) {
        $sql = "select * from bac_safety_check_findings where contractor_id = $contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['findings_id']] = $row['findings_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['findings_id']] = $row['findings_name_en'];
                }else{
                    $rs[$row['findings_id']] = $row['findings_name_en'];
                }
            }
        }else{
            $sql = "select * from bac_safety_check_findings where contractor_id = 0";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    if (Yii::app()->language == 'zh_CN') {
                        $rs[$row['findings_id']] = $row['findings_name'];
                    }else if (Yii::app()->language == 'en_US') {
                        $rs[$row['findings_id']] = $row['findings_name_en'];
                    }else{
                        $rs[$row['findings_id']] = $row['findings_name_en'];
                    }
                }
            }
        }
        return $rs;
    }

    //安全检查按企业选择类型
    public static function typeByContractor($program_id) {
        $sql = " SELECT
                    a.findings_id, a.findings_name, a.findings_name_en
                FROM
                    bac_safety_check_findings a, bac_program b
                WHERE
                    a.contractor_id = b.main_conid and a.status = '00' and b.root_proid =".$program_id." and b.status='00'
                order by a.findings_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) > 0){
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['findings_id']] = $row['findings_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['findings_id']] = $row['findings_name_en'];
                }
            }
        }else{
            $sql = "select * from bac_safety_check_findings WHERE status = '00' and contractor_id ='0' order by findings_id ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    if (Yii::app()->language == 'zh_CN') {
                        $rs[$row['findings_id']] = $row['findings_name'];
                    }else if (Yii::app()->language == 'en_US') {
                        $rs[$row['findings_id']] = $row['findings_name_en'];
                    }
                }
            }
        }
        return $rs;
    }
}
