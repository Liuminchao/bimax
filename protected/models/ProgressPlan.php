<?php

/**
 * This is the model class for table "ptw_condition_list".
 *
 * The followings are the available columns in table 'ptw_condition_list':
 * @property string $condition_name
 * @property string $condition_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwTypeList[] $ptwTypeLists
 * @author LiuXiaoyuan
 */
class ProgressPlan extends CActiveRecord
{

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'progress_plan';
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

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        //Role
        if ($args['version_id'] != '') {
            $condition.= ( $condition == '') ? ' version_id=:version_id' : ' AND version_id=:version_id';
            $params['version_id'] = $args['version_id'];
        }

        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = ProgressPlan::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'plan_id ASC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = ProgressPlan::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //查询安全单
    public static function detailList($list_id){

        $sql = "SELECT * FROM qa_form_data_a WHERE  list_id = '".$list_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }

    //表单项
    public static function itemAry($form_id) {
        $sql = "select * from qa_form where form_id = '".$form_id."' order by order_id asc ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['item_id']]['item_title'] = $row['item_title'];
                    $rs[$row['item_id']]['group_name'] = $row['group_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['item_id']]['item_title'] = $row['item_title'];
                    $rs[$row['item_id']]['group_name'] = $row['group_name'];
                }
            }
        }
        return $rs;
    }

    //插入区域数据
    public static function InsertForm($args,$version_id,$project_id){

        $trans = Yii::app()->db->beginTransaction();
        try {


            $exist_data = ProgressPlan::model()->count('plan_id=:plan_id and project_id=:project_id', array('plan_id' => $args['primary_id'],'project_id'=>$project_id));
            if ($exist_data != 0) {
                $sql = " SELECT
                    *
                FROM
                    progress_plan
                WHERE
                     project_id='".$project_id."' and plan_id='".$args['primary_id']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if(count($rows)>0){
                    $status = '0';
                    $record_time = date('Y-m-d H:i:s');
                    $plan_start = substr($rows[0]['plan_start'],0,10);
                    $plan_finish = substr($rows[0]['plan_finish'],0,10);
                    $sub_sql = 'INSERT INTO progress_plan_his(record_id,plan_id,version_id,plan_name,project_id,plan_start,plan_finish,duration_days,father_plan,level,status,record_time) VALUES(:record_id,:plan_id,:version_id,:plan_name,:project_id,:plan_start,:plan_finish,:duration_days,:father_plan,:level,:status,:record_time)';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":record_id", $rows[0]['record_id'], PDO::PARAM_STR);
                    $command->bindParam(":plan_id", $rows[0]['plan_id'], PDO::PARAM_STR);
                    $command->bindParam(":version_id", $rows[0]['version_id'], PDO::PARAM_STR);
                    $command->bindParam(":plan_name", $rows[0]['plan_name'], PDO::PARAM_STR);
                    $command->bindParam(":project_id", $rows[0]['project_id'], PDO::PARAM_STR);
                    $command->bindParam(":plan_start", $plan_start, PDO::PARAM_STR);
                    $command->bindParam(":plan_finish", $plan_finish, PDO::PARAM_STR);
                    $command->bindParam(":duration_days", $rows[0]['duration_days'], PDO::PARAM_STR);
                    $command->bindParam(":father_plan", $rows[0]['parent_id'], PDO::PARAM_STR);
                    $command->bindParam(":level", $rows[0]['level'], PDO::PARAM_STR);
                    $command->bindParam(":status", $rows[0]['status'], PDO::PARAM_STR);
                    $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                    $rs = $command->execute();
                }

                $record_time = date('Y-m-d H:i:s');
                $tag = '1';
                $plan_start = substr($args['start'],0,10);
                $plan_finish = substr($args['finish'],0,10);
                $sub_sql = 'Update progress_plan set version_id = :version_id,plan_name=:plan_name,plan_start=:plan_start,plan_finish=:plan_finish,duration_days=:duration_days,father_plan=:father_plan,level=:level,tag=:tag,record_time=:record_time where project_id=:project_id and plan_id=:plan_id';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":version_id", $version_id, PDO::PARAM_STR);
                $command->bindParam(":plan_name", $args['name'], PDO::PARAM_STR);
                $command->bindParam(":plan_start", $plan_start, PDO::PARAM_STR);
                $command->bindParam(":plan_finish", $plan_finish, PDO::PARAM_STR);
                $command->bindParam(":duration_days", $args['duration'], PDO::PARAM_STR);
                $command->bindParam(":father_plan", $rows[0]['parent_id'], PDO::PARAM_STR);
                $command->bindParam(":level", $args['level'], PDO::PARAM_STR);
                $command->bindParam(":tag", $tag, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                $command->bindParam(":plan_id", $args['primary_id'], PDO::PARAM_STR);
                $rs = $command->execute();
            }else{
                $status = '0';
                $plan_start = substr($args['start'],0,10);
                $plan_finish = substr($args['finish'],0,10);
                $record_time = date('Y-m-d H:i:s');
                $model = new ProgressPlan('create');
                $model->plan_id = $args['primary_id'];
                $model->version_id = $version_id;
//                $model->unique_id = $args['uniqueID'];
                $model->plan_name = $args['name'];
                $model->project_id = $project_id;
                $model->plan_start = $plan_start;
                $model->plan_finish = $plan_finish;
                $model->duration_days = $args['duration'];
                $model->father_plan = (string)$args['parent_id'];
                $model->level = $args['level'];
                $model->status = $status;
                $model->tag = '1';
                $model->record_time = $record_time;
                $result = $model->save();
            }

            $trans->commit();

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }

        return $r;
    }
}
