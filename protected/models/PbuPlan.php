<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class PbuPlan extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pbu_info_plan';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //详情
    public static function  pbuInfo($program_id,$uuid){
        $sql = "SELECT * FROM pbu_info_plan WHERE project_id=:project_id and pbu_id=:pbu_id  ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":pbu_id", $uuid, PDO::PARAM_STR);
        $detaillist = $command->queryAll();
        $rs = array();
        if(count($detaillist)>0){
            foreach($detaillist as $i => $j){
                if($i == 0){
                    $template_model = TaskTemplate::model()->findByPk($j['template_id']);
                    $template_name = $template_model->template_name;
                }
                $r = array();
                $stage_model = TaskStage::model()->findByPk($j['stage_id']);
                $r['stage_name'] = $stage_model->stage_name;
                $r['stage_id'] = $j['stage_id'];
                $r['plan_start_date'] = $j['plan_start_date'];
                $r['plan_end_date'] = $j['plan_end_date'];
                $rs[$template_name][] = $r;
            }
        }else{
            return $rs;
        }
        return $rs;
    }

}
