<?php

/**
 * 意外设备
 * @author LiuMinChao
 */
class AccidentDevice extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'accident_device';
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


    public static function getDeviceList($apply_id) {

        $sql = "SELECT device_id,device_name,device_type_ch,device_type_en FROM accident_device_info  WHERE  apply_id=:apply_id ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (Yii::app()->language == 'zh_CN') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['device_id'] = $row['device_id'];
                    $rs[$key]['device_name'] = $row['device_name'];
                    $rs[$key]['device_type'] = $row['device_type_en'];
                    $rs[$key]['device_type_ch'] = $row['device_type_ch'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['device_id'] = $row['device_id'];
                    $rs[$key]['device_name'] = $row['device_name'];
                    $rs[$key]['device_type'] = $row['device_type_en'];
                    $rs[$key]['device_type_ch'] = $row['device_type_ch'];
                }
            }
        }else{
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$key]['device_id'] = $row['device_id'];
                    $rs[$key]['device_name'] = $row['device_name'];
                    $rs[$key]['device_type'] = $row['device_type_en'];
                    $rs[$key]['device_type_ch'] = $row['device_type_ch'];
                }
            }else{
                $rs = array();
            }
        }

        return $rs;
    }
}
