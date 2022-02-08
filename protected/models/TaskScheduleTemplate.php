<?php

/**
 * This is the model class for table "ptw_condition_list".
 *
 * The followings are the available columns in table 'ptw_condition_list':
 * @property string $condition_id
 * @property string $condition_name
 * @property string $condition_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwTypeList[] $ptwTypeLists
 * @author LiuXiaoyuan
 */
class TaskScheduleTemplate extends CActiveRecord
{
    const STATUS_NORMAL = '0'; //正常


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'task_schedule_template';
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PtwCondition the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    public static function SaveScheduleTemplate($schedule){
        $trans = Yii::app()->db->beginTransaction();
        try {
            $block = $schedule['block'];
            $project_id = $schedule['project_id'];
            $template_id = $schedule['template_id'];

            $sql = 'DELETE FROM task_schedule_template WHERE project_id=:project_id and template_id=:template_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":project_id", $project_id, PDO::PARAM_INT);
            $command->bindParam(":template_id", $template_id, PDO::PARAM_INT);
            $command->execute();

            $template_start = Utils::DateToCn($schedule['template_start']);
            $template_end = Utils::DateToCn($schedule['template_end']);
            $stage_id = $schedule['stage_id'];
            $stage_start = Utils::DateToCn($schedule['stage_start']);
            $stage_end = Utils::DateToCn($schedule['stage_end']);
            $level_from = $schedule['level_from'];
            $level_to = $schedule['level_to'];
            $avg_zone = $schedule['avg_part'];
            $avg_level = $schedule['avg_level'];
            $adj_zone = $schedule['adj_part'];
            $adj_level = $schedule['adj_level'];
            $status= '0';
            $sub_sql = 'INSERT INTO task_schedule_template(block,project_id,template_id,template_start,template_end,stage_id,stage_start,stage_end,level_from,level_to,avg_zone,avg_level,adj_zone,adj_level,record_time) VALUES(:block,:project_id,:template_id,:template_start,:template_end,:stage_id,:stage_start,:stage_end,:level_from,:level_to,:avg_zone,:avg_level,:adj_zone,:adj_level,:record_time);';
            $record_time = date('Y-m-d H:i:s', time());
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":block", $block, PDO::PARAM_STR);
            $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
            $command->bindParam(":template_start",$template_start, PDO::PARAM_STR);
            $command->bindParam(":template_end",$template_end, PDO::PARAM_STR);
            $command->bindParam(":stage_id",$stage_id, PDO::PARAM_INT);
            $command->bindParam(":stage_start",$stage_start, PDO::PARAM_STR);
            $command->bindParam(":stage_end",$stage_end, PDO::PARAM_STR);
            $command->bindParam(":level_from",$level_from, PDO::PARAM_INT);
            $command->bindParam(":level_to",$level_to, PDO::PARAM_INT);
            $command->bindParam(":avg_zone",$avg_zone, PDO::PARAM_INT);
            $command->bindParam(":avg_level",$avg_level, PDO::PARAM_INT);
            $command->bindParam(":adj_zone",$adj_zone, PDO::PARAM_INT);
            $command->bindParam(":adj_level",$adj_level, PDO::PARAM_INT);
            $command->bindParam(":record_time",$record_time, PDO::PARAM_STR);
            $rs = $command->execute();

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }

        return $r;
    }

    //查询模板信息
    public static function templateInfo($block,$project_id,$template_id){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT * FROM task_schedule_template WHERE project_id = :project_id and block =:block and template_id=:template_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $root_proid, PDO::PARAM_STR);
        $command->bindParam(":block", $block, PDO::PARAM_STR);
        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
}
