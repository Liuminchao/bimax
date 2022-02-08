<?php

/**
 * RevitModel
 *
 * @author liuxy
 */
class StatisticPbuInfo extends CActiveRecord {



    //根据模板 查询当前构件在哪个阶段
    public static function BlockData($args){

        $pbu_id = $args['pbu_id'];
        $model_id = $args['model_id'];
        $project_id = $args['project_id'];
        $contractor_id = Yii::app()->user->contractor_id;
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $template_id = $args['template_id'];
        $sql = "SELECT a.*,b.stage_name FROM task_component_stats a 
                  LEFT JOIN task_stage b ON a.stage_id = b.stage_id 
                  where a.project_id=:project_id and a.template_id = :template_id and a.latest_flag = '1' and a.model_id =:model_id and a.guid = :guid";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $root_proid, PDO::PARAM_STR);
        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
        $command->bindParam(":model_id", $model_id, PDO::PARAM_STR);
        $command->bindParam(":guid", $pbu_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if(count($rows)>0){
            $stage_name = $rows[0]['stage_name'];
        }else{
            $stage_name = 'Not Start';
        }

        return $stage_name;
    }

}