<?php

/**
 * 质量检查类型
 * @author LiuMinchao
 */
class QaCheckType extends CActiveRecord {


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_checklist_type';
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

    public static function getFormType() {
        return [
            '0' => 'Inspection',
            'A' => 'Site',
            'B' => 'Fitout',
            'C' => 'Carcass',
        ];
    }

    //检查类型列表
    public static function checkType() {
        $sql = "select * from qa_checklist_type ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (Yii::app()->language == 'zh_CN') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }else{
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }

        return $rs;
    }
    //CHECKLIST按企业选择类型
    public static function typeByContractor($program_id) {
        $sql = " SELECT
                    a.type_id, a.type_name, a.type_name_en
                FROM
                    qa_checklist_type a, bac_program b
                WHERE
                    a.contractor_id = b.main_conid and a.status = '00' and b.root_proid =".$program_id." and b.status='00'
                order by a.form_type";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) > 0){
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['type_id']] = $row['type_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }else{
            $sql = "select * from qa_checklist_type WHERE status = '00' and contractor_id ='0' order by form_type ";
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
        }
        return $rs;
    }
    //根据form_type查出type_id
    public static function idByType($form_type){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = " SELECT
                    type_id,type_name_en
                FROM
                    qa_checklist_type 
                WHERE
                    form_type ='".$form_type."' and contractor_id = '".$contractor_id."' and status = '00'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }else{
            $sql = " SELECT
                    type_id,type_name_en
                FROM
                    qa_checklist_type 
                WHERE
                    form_type ='".$form_type."' and contractor_id = '0' and status = '00'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    if (Yii::app()->language == 'zh_CN') {
                        $rs[$row['type_id']] = $row['type_name_en'];
                    }else if (Yii::app()->language == 'en_US') {
                        $rs[$row['type_id']] = $row['type_name_en'];
                    }
                }
            }
        }
        return $rs;
    }
    //检查单类别
    public static function checkModule() {

        $sql = "SELECT type_id,module FROM bac_routine_check_type WHERE status=00 ";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['type_id']] = $row['module'];
            }
        }
        return $rs;
    }
    //检查种类
    public static function checkKind() {
        if (Yii::app()->language == 'zh_CN') {
            $r['1'] = '设备';
            $r['2'] = '环境';
        } else if (Yii::app()->language == 'en_US') {
            $r['1'] = 'Device';
            $r['2'] = 'Environment';
        }else{
            $r['1'] = 'Device';
            $r['2'] = 'Environment';
        }
        return $r;
    }
    //平台种类+企业种类
    public static function AllType(){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = " SELECT
                    form_type,type_id,type_name_en
                FROM
                    qa_checklist_type 
                WHERE
                    status = '00' and (contractor_id = '0' or contractor_id = '".$contractor_id."') order by form_type";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['type_id']]['type_name'] = $row['type_name_en'];
                    $rs[$row['type_id']]['form_type'] = $row['form_type'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['type_id']]['type_name'] = $row['type_name_en'];
                    $rs[$row['type_id']]['form_type'] = $row['form_type'];
                }
            }
        }
        return $rs;
    }
}
