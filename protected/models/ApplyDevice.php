<?php

/**
 * 许可证申请设备库
 * @author LiuMinChao
 */
class ApplyDevice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ptw_apply_device';
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

        $sql = "SELECT a.device_id,b.device_name,b.device_content,b.type_no FROM ptw_apply_device a,bac_device b  WHERE  a.apply_id=:apply_id AND a.device_id =b.primary_id AND b.status = 00";
       
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['device_id']]['device_name'] = $row['device_name'];
                $rs[$row['device_id']]['device_content'] = $row['device_content'];
                $rs[$row['device_id']]['type_no'] = $row['type_no'];
            }
        }else{
			$rs = array();
		}

        return $rs;
    }
}
