<?php

/**
 * 任务列表
 * @author LiuMinchao
 */
class TaskList extends CActiveRecord {

    const STATUS_DRAFT = '-1'; //进行中
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_COMPLETED = '1'; //已完成
    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '9'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'task_list';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ONGOING => 'Ongoing',
            self::STATUS_COMPLETED => 'Completed',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'bg-default',
            self::STATUS_ONGOING => 'bg-info', //进行中
            self::STATUS_COMPLETED => ' bg-success', //已完成
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

        //Program
        if ($args['project_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $args['project_id'];
        }
        //Template Id
        if ($args['template_id'] != '') {
            $condition.= ( $condition == '') ? ' template_id=:template_id' : ' AND template_id=:template_id';
            $params['template_id'] = $args['template_id'];
        }
        //Stage Id
        if ($args['stage_id'] != '') {
            $condition.= ( $condition == '') ? ' stage_id=:stage_id' : ' AND stage_id=:stage_id';
            $params['stage_id'] = $args['stage_id'];
        }
        //Record Time
        if ($args['record_time'] != '') {
            $condition.= ( $condition == '') ? ' record_time=:record_time' : ' AND record_time=:record_time';
            $params['record_time'] = $args['record_time'];
        }

        $args['status'] = '0';
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = TaskList::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'template_id';
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
        $rows = TaskList::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 添加记录
     */
    public  static function insertBasic($task) {

        $model = new TaskList('create');

        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->task_name = $task['task_name'];
            $model->project_id = $task['program_id'];
            $model->template_id = $task['template_id'];
            $model->stage_id = $task['stage_id'];
            if($task['checklist']){
                $checklist_id = implode(",",$task['checklist']);
                $model->checklist_id = $checklist_id;
            }else{
                $model->checklist_id = '';
            }
            $model->order_id = '0';
            $model->status = self::STATUS_ONGOING;
            $model->record_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            if ($result) {
                $trans->commit();
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
            else {
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    /**
     * 修改记录
     */
    public  static function updateBasic($task) {
        $model = TaskList::model()->findByPk($task['task_id']);

        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->task_name = $task['task_name'];
            $model->project_id = $task['program_id'];
            $model->template_id = $task['template_id'];
            $model->stage_id = $task['stage_id'];
            if($task['checklist']){
                $checklist_id = implode(",",$task['checklist']);
                $model->checklist_id = $checklist_id;
            }else{
                $model->checklist_id = '';
            }
            $model->order_id = '0';
            $model->status = self::STATUS_ONGOING;
            $model->record_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            if ($result) {
                $trans->commit();
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
            else {
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //启用
    public static function startTemplate($id) {

        $model = TaskTemplate::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = self::STATUS_NORMAL;
            $result = $model->save();

            if ($result) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Start Type'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_start');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //详情
    public static function  detailList($template_id){
        $sql = "SELECT * FROM task_stage WHERE template_id = '".$template_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $detaillist = $command->queryAll();
        return $detaillist;
    }
    //停用
    public static function stopTemplate($id) {

        $model = TaskTemplate::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = self::STATUS_STOP;
            $result = $model->save();
            if ($result) {
                $sql = "update task_stage set status = '9' where template_id = '".$id."'";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute();

                $sql = "update task_list set status = '9' where template_id = '".$id."'";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute();
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Stop Type'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_logout');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_logout');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //启用
    public static function startTask($id) {

        $model = TaskList::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = self::STATUS_NORMAL;
            $result = $model->save();

            if ($result) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Start Type'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_start');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //停用
    public static function stopTask($id) {

        $model = TaskList::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = self::STATUS_STOP;
            $result = $model->save();
            if ($result) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Stop Type'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_logout');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_logout');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //按项目查找任务
    public static function taskByProgram($project_id){
        $sql = "SELECT task_id,task_name FROM task_list WHERE status=0 and project_id='".$project_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['task_id']] = $row['task_name'];
            }
        }

        return $rs;
    }

    //按项目查找任务
    public static function taskByStage($stage_id){
        $sql = "SELECT task_id,task_name FROM task_list WHERE status=0 and stage_id='".$stage_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['task_id']] = $row['task_name'];
            }
        }

        return $rs;
    }

    //保存 key
    public static function saveSub($task){
        try {
            if(count($task['plan_day'])>0){
                $task_total = 0;
                foreach($task['plan_day'] as $task_id => $day){
                    if($day == ''){
                        $r['msg'] = 'Please fill in the date for the task';
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    $task_model = TaskList::model()->findByPk($task_id);
                    $task_model->plan_day = $day;
                    $task_total = $task_total+$day;
                    $result = $task_model->save();
                }
                $template_id = $task_model->template_id;
                $stage_list = TaskStage::queryStage($template_id);
                $template_day = 0;
                foreach ($stage_list as $stage_id => $stage_name){
                    $stage_model = TaskStage::model()->findByPk($stage_id);
                    $plan_day = $stage_model->plan_day;
                    $template_day = $template_day+$plan_day;
                }
                if($task_total>$template_day){
                    $r['msg'] = 'The total no. of days for Sub Activities Cycle shall not exceed the total no. of days for Key Activities Cycle';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
            }

            if(count($task['lag_day'])>0){
                foreach($task['lag_day'] as $task_id => $day){
                    $task_model = TaskList::model()->findByPk($task_id);
                    $task_model->lag_day = $day;
                    $result = $task_model->save();
                }
            }

            if ($result) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Stop Type'), self::updateLog($model));
                $r['msg'] = 'Set Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = 'Set Error';
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //按模版ID查找阶段
    public static function queryTaskDetail($stage_id){
        $sql = "SELECT task_id,task_name FROM task_list WHERE status=0 and stage_id=:stage_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":stage_id", $stage_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['task_id']]['task_name'] = $row['task_name'];
                $rs[$row['task_id']]['plan_day'] = $row['plan_day'];
            }
        }else{
            $rs = array();
        }

        return $rs;
    }
}
