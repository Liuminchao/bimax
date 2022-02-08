<?php

/**
 * 意外-请假单
 * @author LiuMinChao
 */
class AccidentSickLeave extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'accident_sick_leave';
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


    public static function getSickList($apply_id) {

        $sql = "SELECT apply_id,user_name,start_time,end_time,pic FROM accident_sick_leave  WHERE  apply_id=:apply_id ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['user_name'] = $row['user_name'];
                $rs[$key]['start_time'] = $row['start_time'];
                $rs[$key]['end_time'] = $row['end_time'];
                $rs[$key]['pic'] = $row['pic'];
            }
        }else{
            $rs = array();
        }

        return $rs;
    }
}
