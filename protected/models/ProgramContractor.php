<?php

/**
 * 项目指定分包
 * @author LiuXiaoyuan
 */
class ProgramContractor extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'bac_program_contractor';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProgramContractor the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 根据项目id返回所有指定分包
     * @param type $program_id
     * @return type
     */
    public static function myMcList($program_id) {
        $sql = "SELECT contractor_id FROM bac_program_contractor WHERE program_id=:program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_id'];
            }
        }
        return $rs;
    }

    /**
     * 得到分包的项目id
     * @return type
     */
    public static function getProgramId() {

        $program_id = '';

        $sql = "SELECT program_id FROM bac_program_contractor WHERE contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $program_id .= $row['program_id'] . ',';
            }
        }
        if ($program_id != '')
            $program_id = substr($program_id, 0, strlen($program_id) - 1);
        return $program_id;
    }

}
