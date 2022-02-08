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
class TaskSchedule extends CActiveRecord
{
    const STATUS_NORMAL = '0'; //正常


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'task_schedule';
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

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryMasterList($page, $pageSize, $args = array()) {
//        var_dump($args);
        $condition = '';
        $params = array();
        if(count($args)<= 0){
            $args['project_id'] = '0';
        }

        //project id
        if ($args['project_id'] != '') {
            $model = Program::model()->findByPk($args['project_id']);
            if($model->father_proid == 0) {  //总包项目
                $project_id = $args['project_id'];
            }else{
                $project_id = $model->root_proid;
            }
            $condition.= " a.project_id= '$project_id' ";
        }
        //template_id
        if ($args['template_id'] != '') {
            $template_id = $args['template_id'];
            $condition.= " AND a.template_id='$template_id' ";
        }
        //block
        if ($args['block'] != '') {
            $block = $args['block'];
            $condition.= " AND a.block='$block' ";
        }
        //level
        if ($args['level'] != '') {
            $level = $args['level'];
            $condition.= " AND a.level= '$level' ";
        }
        //part
        if ($args['part'] != '') {
            $part = $args['part'];
            $condition.= " AND a.part='$part' ";
        }


        $sql = "select a.* from 
                    task_schedule a
                where $condition  order by a.block,a.level+0,a.part asc";

//        var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

//        var_dump($sql);
        $count = count($rows);
        $pagedata=array();
        $start=$page*$pageSize; #计算每次分页的开始位置
        if($count>0){
            $pagedata=array_slice($rows,$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;

        return $rs;
    }

    public static function SaveSchedule($schedule,$set){
        $re = TaskScheduleTemplate::SaveScheduleTemplate($schedule);
        $trans = Yii::app()->db->beginTransaction();
        try {
            $block = $schedule['block'];
            $project_id = $schedule['project_id'];
            $template_id = $schedule['template_id'];
            $pbu_tag = $schedule['pbu_tag'];
            $status= '0';
            foreach ($set as $level => $part_list){
                foreach($part_list as $part => $list){
                    foreach($list as $id => $plan_date){
                        $plan_date = Utils::DateToCn($plan_date);
                        $type_list =  explode('_',$id);
                        $type = $type_list[1];
                        if($type == '1'){
                            $stage_id = $type_list[0];
                            $task_id = 0;
                        }
                        if($type == '2'){
                            $task_id = $type_list[0];
                            $task_model = TaskList::model()->findByPk($task_id);
                            $stage_id = $task_model->stage_id;
                        }
                        $query_sql = 'SELECT * FROM task_schedule WHERE block=:block and type=:type and level=:level and part=:part and project_id=:project_id and template_id=:template_id and stage_id=:stage_id';
                        $command = Yii::app()->db->createCommand($query_sql);
                        $command->bindParam(":block", $block, PDO::PARAM_STR);
                        $command->bindParam(":type", $pbu_tag, PDO::PARAM_STR);
                        $command->bindParam(":level", $level, PDO::PARAM_STR);
                        $command->bindParam(":part", $part, PDO::PARAM_STR);
                        $command->bindParam(":project_id",$project_id, PDO::PARAM_INT);
                        $command->bindParam(":template_id",$template_id, PDO::PARAM_INT);
                        $command->bindParam(":stage_id",$stage_id, PDO::PARAM_INT);
                        $rows = $command->queryAll();
                        if(count($rows)>0){
                            $sub_sql = 'UPDATE task_schedule SET plan_date=:plan_date,update_time=:update_time WHERE block=:block and type=:type and level=:level and part=:part and project_id=:project_id and template_id=:template_id and stage_id=:stage_id';
                            $update_time = date('Y-m-d H:i:s', time());
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":block", $block, PDO::PARAM_STR);
                            $command->bindParam(":type", $pbu_tag, PDO::PARAM_STR);
                            $command->bindParam(":level", $level, PDO::PARAM_STR);
                            $command->bindParam(":part", $part, PDO::PARAM_STR);
                            $command->bindParam(":project_id",$project_id, PDO::PARAM_INT);
                            $command->bindParam(":template_id",$template_id, PDO::PARAM_INT);
                            $command->bindParam(":stage_id",$stage_id, PDO::PARAM_INT);
                            $command->bindParam(":plan_date",$plan_date, PDO::PARAM_STR);
                            $command->bindParam(":update_time",$update_time, PDO::PARAM_STR);
                            $rs = $command->execute();
                        }else{
                            $sub_sql = 'INSERT INTO task_schedule(block,type,level,part,project_id,template_id,stage_id,plan_date,record_time) VALUES(:block,:type,:level,:part,:project_id,:template_id,:stage_id,:plan_date,:record_time);';
                            $record_time = date('Y-m-d H:i:s', time());
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":block", $block, PDO::PARAM_STR);
                            $command->bindParam(":type", $pbu_tag, PDO::PARAM_STR);
                            $command->bindParam(":level", $level, PDO::PARAM_STR);
                            $command->bindParam(":part", $part, PDO::PARAM_STR);
                            $command->bindParam(":project_id",$project_id, PDO::PARAM_INT);
                            $command->bindParam(":template_id",$template_id, PDO::PARAM_INT);
                            $command->bindParam(":stage_id",$stage_id, PDO::PARAM_INT);
                            $command->bindParam(":plan_date",$plan_date, PDO::PARAM_INT);
                            $command->bindParam(":record_time",$record_time, PDO::PARAM_STR);
                            $rs = $command->execute();
                        }
                    }
                }
            }



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
}
