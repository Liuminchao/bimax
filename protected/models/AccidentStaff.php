<?php

/**
 * 意外人员
 * @author LiuMinChao
 */
class AccidentStaff extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'accident_staff_info';
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'apply_id' => 'Apply',
            'contractor_id' => 'Contractor',
            'program_id' => 'Program',
            'device_id' => 'Device Id',
            'device_name' => 'Device Name',
            'status' => 'Status',
            'record_time' => 'Record Time',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyWorkerLog the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public static function getStaffList($apply_id) {
        $sql = "SELECT user_id,user_name,role_name,role_name_en,staff_data,company_name FROM accident_staff_info  WHERE  apply_id=:apply_id ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (Yii::app()->language == 'zh_CN') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['user_id'] = $row['user_id'];
                    $rs[$key]['user_name'] = $row['user_name'];
                    $rs[$key]['role_name'] = $row['role_name'];
                    $rs[$key]['staff_data'] = $row['staff_data'];
                    $rs[$key]['company_name'] = $row['company_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['user_id'] = $row['user_id'];
                    $rs[$key]['user_name'] = $row['user_name'];
                    $rs[$key]['role_name'] = $row['role_name_en'];
                    $rs[$key]['staff_data'] = $row['staff_data'];
                    $rs[$key]['company_name'] = $row['company_name'];
                }
            }
        }else{
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['user_id'] = $row['user_id'];
                    $rs[$key]['user_name'] = $row['user_name'];
                    $rs[$key]['role_name'] = $row['role_name_en'];
                    $rs[$key]['staff_data'] = $row['staff_data'];
                    $rs[$key]['company_name'] = $row['company_name'];
                }
            }else{
                $rs = array();
            }
        }

        return $rs;
    }
}
