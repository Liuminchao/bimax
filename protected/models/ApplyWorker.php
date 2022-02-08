<?php

/**
 * 许可证申请工人库
 * @author weijuan
 */
class ApplyWorker extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ptw_apply_worker_log';
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
			'wp_no' => 'Wp No',
			'worker_name' => 'Worker Name',
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
        

    public static function getWorkerList($apply_id) {

        $sql = "SELECT a.user_id,b.user_name,b.work_no,b.role_id FROM ptw_apply_worker a,bac_staff b WHERE a.apply_id=:apply_id and a.user_id = b.user_id";
       
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']]['user_name'] = $row['user_name'];
                $rs[$row['user_id']]['work_no'] = $row['work_no'];
				$rs[$row['user_id']]['role_id'] = $row['role_id'];
            }
        }else{
			$rs = array();
		}

        return $rs;
    }
}
