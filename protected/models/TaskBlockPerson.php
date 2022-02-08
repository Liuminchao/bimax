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
class TaskBlockPerson extends CActiveRecord
{
    const STATUS_NORMAL = '0'; //正常


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'task_block_person';
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
    //分页查询某项目下人员(审核状态是-1，10，11，20)
    public static function queryUser($args = array()) {

        $query_sql = 'SELECT * FROM task_block_person WHERE block=:block and type=:type and project_id=:project_id ';
        $command = Yii::app()->db->createCommand($query_sql);
        $command->bindParam(":block", $args['block'], PDO::PARAM_STR);
        $command->bindParam(":type", $args['pbu_tag'], PDO::PARAM_STR);
        $command->bindParam(":project_id",$args['program_id'], PDO::PARAM_INT);
        $rows = $command->queryAll();
        if(count($rows)>0){
            foreach($rows as $i => $j){
                $rs[$j['user_id']]['web_view'] = $j['web_view'];
                $rs[$j['user_id']]['web_edit'] = $j['web_edit'];
                $rs[$j['user_id']]['app'] = $j['app'];
            }
        }else{
            $rs = array();
        }
        return $rs;
    }

    public static function SavePerson($args,$person){

        $trans = Yii::app()->db->beginTransaction();
        try {

            foreach ($person as $user_id => $list){
                $query_sql = 'SELECT * FROM task_block_person WHERE block=:block and type=:type and project_id=:project_id and user_id=:user_id';
                $command = Yii::app()->db->createCommand($query_sql);
                $command->bindParam(":block", $args['block'], PDO::PARAM_STR);
                $command->bindParam(":type", $args['type'], PDO::PARAM_STR);
                $command->bindParam(":project_id",$args['program_id'], PDO::PARAM_INT);
                $command->bindParam(":user_id",$user_id, PDO::PARAM_INT);
                $rows = $command->queryAll();
                $web_view = '0';
                $web_edit = '0';
                $app = '0';
                if($list['web_view'] == 'on'){
                    $web_view = '1';
                }
                if($list['web_edit'] == 'on'){
                    $web_edit = '1';
                }
                if($list['app'] == 'on'){
                    $app = '1';
                }
                if(count($rows)>0){
                    $sub_sql = 'UPDATE task_block_person SET web_view=:web_view,web_edit=:web_edit,app=:app WHERE block=:block and type=:type and project_id=:project_id and user_id=:user_id';
                    $update_time = date('Y-m-d H:i:s', time());
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":web_view", $web_view, PDO::PARAM_STR);
                    $command->bindParam(":web_edit", $web_edit, PDO::PARAM_STR);
                    $command->bindParam(":app", $app, PDO::PARAM_STR);
                    $command->bindParam(":block",$args['block'], PDO::PARAM_INT);
                    $command->bindParam(":type",$args['type'], PDO::PARAM_INT);
                    $command->bindParam(":project_id",$args['program_id'], PDO::PARAM_INT);
                    $command->bindParam(":user_id",$user_id, PDO::PARAM_INT);
                    $rs = $command->execute();
                }else{
                    $sub_sql = 'INSERT INTO task_block_person(block,type,project_id,user_id,web_view,web_edit,app) VALUES(:block,:type,:project_id,:user_id,:web_view,:web_edit,:app);';
                    $record_time = date('Y-m-d H:i:s', time());
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":block",$args['block'], PDO::PARAM_INT);
                    $command->bindParam(":type",$args['type'], PDO::PARAM_INT);
                    $command->bindParam(":project_id",$args['program_id'], PDO::PARAM_INT);
                    $command->bindParam(":user_id",$user_id, PDO::PARAM_INT);
                    $command->bindParam(":web_view", $web_view, PDO::PARAM_STR);
                    $command->bindParam(":web_edit", $web_edit, PDO::PARAM_STR);
                    $command->bindParam(":app", $app, PDO::PARAM_STR);
                    $rs = $command->execute();
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

    public static function querySelf($args = array()) {

        $query_sql = 'SELECT * FROM task_block_person WHERE block=:block and type=:type and project_id=:project_id and user_id=:user_id';
        $command = Yii::app()->db->createCommand($query_sql);
        $command->bindParam(":block", $args['block'], PDO::PARAM_STR);
        $command->bindParam(":type", $args['type'], PDO::PARAM_STR);
        $command->bindParam(":project_id",$args['program_id'], PDO::PARAM_INT);
        $command->bindParam(":user_id",$args['user_id'], PDO::PARAM_INT);
        $rows = $command->queryAll();
        if(count($rows)>0){
            foreach($rows as $i => $j){
                $rs['web_view'] = $j['web_view'];
                $rs['web_edit'] = $j['web_edit'];
                $rs['app'] = $j['app'];
            }
        }else{
            $rs = array();
        }
        return $rs;
    }
}
