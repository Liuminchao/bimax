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
class TaskStage extends CActiveRecord
{
    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '9'; //停用
    const STATUS_HIDE = '1'; //隐藏
    const STATUS_SHOW = '0'; //显示


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'task_stage';
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'condition_id' => 'Condition',
            'condition_name' => 'Condition Name',
            'condition_name_en' => 'Condition Name En',
            'status' => 'Status',
            'record_time' => 'Record Time',
        );
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
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();

        //Template Id
        if ($args['template_id'] != '') {
            $condition.= ( $condition == '') ? ' template_id=:template_id' : ' AND template_id=:template_id';
            $params['template_id'] = $args['template_id'];
        }

        //Stage Name
        if ($args['stage_name'] != '') {
            $condition.= ( $condition == '') ? ' stage_name=:stage_name' : ' AND stage_name=:stage_name';
            $params['stage_name'] = $args['stage_name'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Record Time
        if ($args['record_time'] != '') {
            $condition.= ( $condition == '') ? ' record_time=:record_time' : ' AND record_time=:record_time';
            $params['record_time'] = $args['record_time'];
        }

        $total_num = TaskStage::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'order_id asc';
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
        $rows = TaskStage::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertStage($json,$template_name,$program_id) {
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $list = explode('@',$json);
        foreach($list as $i => $j){
            $data = explode(',',$j);
            $args[$i]['stage_name'] = $data[0];
            $args[$i]['stage_color'] = $data[1];
            $args[$i]['order_id'] = $data[2];
            $args[$i]['clt_type'] = $data[3];
        }
        $rs['template_name'] = $template_name;
        $rs['program_id'] = $root_proid;
//        $rs['clt_type'] = $clt_type;
        $r = TaskTemplate::insertTemplate($rs);
        $template_id = $r['template_id'];
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach($args as $i => $j){
                if($j['order_id'] == ''){
                    $j['order_id'] = 0;
                }
                $exist_data = TaskStage::model()->count('template_id=:template_id and stage_color=:stage_color', array('template_id' => $template_id,'stage_color'=>$j['stage_color']));
                if ($exist_data != 0) {
                    $r['msg'] = 'The stage color already exists under this template。';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $sub_sql = 'INSERT INTO task_stage(stage_name,template_id,project_id,stage_color,order_id,clt_type,status,record_time) VALUES(:stage_name,:template_id,:project_id,:stage_color,:order_id,:clt_type,:status,:record_time);';
                $record_time = date('Y-m-d H:i:s', time());
                $status= '0';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":stage_name", $j['stage_name'], PDO::PARAM_STR);
                $command->bindParam(":template_id",$template_id, PDO::PARAM_INT);
                $command->bindParam(":project_id",$root_proid, PDO::PARAM_INT);
                $command->bindParam(":stage_color",$j['stage_color'], PDO::PARAM_STR);
                $command->bindParam(":order_id",$j['order_id'], PDO::PARAM_INT);
                $command->bindParam(":clt_type",$j['clt_type'], PDO::PARAM_STR);
                $command->bindParam(":status",$status, PDO::PARAM_STR);
                $command->bindParam(":record_time",$record_time, PDO::PARAM_STR);
                $rs = $command->execute();
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

    //修改数据
    public static function EditStage($json,$template_id,$template_name,$program_id,$clt_type) {
        $model = Program::model()->findByPk($program_id);
        $root_proid = $model->root_proid;
        $list = explode('@',$json);
        foreach($list as $i => $j){
            $data = explode(',',$j);
            $args[$i]['stage_name'] = $data[0];
            $args[$i]['stage_color'] = $data[1];
            $args[$i]['order_id'] = $data[2];
            $args[$i]['clt_type'] = $data[3];
        }
        $rs['template_name'] = $template_name;
        $rs['template_id'] = $template_id;
        $rs['clt_type'] = $clt_type;
        $r = TaskTemplate::editTemplate($rs);
        $template_id = $r['template_id'];
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "delete from task_stage where template_id = '".$template_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $re = $command->execute();
//            var_dump($args);
//            exit;
            foreach($args as $i => $j){
                if($j['clt_type'] == 'A'){
                    $order_id = 3;
                }else if($j['clt_type'] == 'B'){
                    $order_id = 2;
                }else if($j['clt_type'] == 'C'){
                    $order_id = 1;
                }else if($j['clt_type'] == 'D'){
                    $order_id = 4;
                }
                $exist_data = TaskStage::model()->count('template_id=:template_id and stage_color=:stage_color', array('template_id' => $template_id,'stage_color'=>$j['stage_color']));
                if ($exist_data != 0) {
                    $r['msg'] = 'The stage color already exists under this template。';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $sub_sql = 'INSERT INTO task_stage(stage_name,template_id,project_id,stage_color,order_id,clt_type,status,record_time) VALUES(:stage_name,:template_id,:project_id,:stage_color,:order_id,:clt_type,:status,:record_time);';
                $record_time = date('Y-m-d H:i:s', time());
                $status= '0';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":stage_name", $j['stage_name'], PDO::PARAM_STR);
                $command->bindParam(":template_id",$template_id, PDO::PARAM_INT);
                $command->bindParam(":project_id",$root_proid, PDO::PARAM_INT);
                $command->bindParam(":stage_color",$j['stage_color'], PDO::PARAM_STR);
                $command->bindParam(":order_id",$j['order_id'], PDO::PARAM_INT);
                $command->bindParam(":clt_type",$j['clt_type'], PDO::PARAM_STR);
                $command->bindParam(":status",$status, PDO::PARAM_STR);
                $command->bindParam(":record_time",$record_time, PDO::PARAM_STR);
                $rs = $command->execute();
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

    //插入数据
    public static function saveStage($stage) {
        $model = Program::model()->findByPk($stage['program_id']);
        $root_proid = $model->root_proid;
        $trans = Yii::app()->db->beginTransaction();
        try {
            if($stage['clt_type'] == 'A'){
                $order_id = 3;
            }else if($stage['clt_type'] == 'B'){
                $order_id = 2;
            }else if($stage['clt_type'] == 'C'){
                $order_id = 1;
            }else if($stage['clt_type'] == 'D'){
                $order_id = 4;
            }
            if($stage['stage_id'] == ''){
                $exist_data = TaskStage::model()->count('template_id=:template_id and stage_color=:stage_color', array('template_id' => $stage['template_id'],'stage_color'=>$stage['stage_color']));
                if ($exist_data != 0) {
                    $r['msg'] = 'The stage color already exists under this template。';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $sub_sql = 'INSERT INTO task_stage(stage_name,template_id,project_id,stage_color,order_id,status,clt_type,record_time) VALUES(:stage_name,:template_id,:project_id,:stage_color,:order_id,:status,:clt_type,:record_time);';
                $record_time = date('Y-m-d H:i:s', time());
                $status= '0';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":stage_name", $stage['stage_name'], PDO::PARAM_STR);
                $command->bindParam(":template_id",$stage['template_id'], PDO::PARAM_INT);
                $command->bindParam(":project_id",$root_proid, PDO::PARAM_INT);
                $command->bindParam(":stage_color",$stage['stage_color'], PDO::PARAM_STR);
                $command->bindParam(":order_id",$stage['order_id'], PDO::PARAM_INT);
                $command->bindParam(":status",$status, PDO::PARAM_STR);
                $command->bindParam(":clt_type",$stage['clt_type'], PDO::PARAM_STR);
                $command->bindParam(":record_time",$record_time, PDO::PARAM_STR);
                $rs = $command->execute();
            }else{
                $stage_model = TaskStage::model()->findByPk($stage['stage_id']);
                $stage_color = $stage_model->stage_color;
                if($stage_color != $stage['stage_color']){
                    $exist_data = TaskStage::model()->count('template_id=:template_id and stage_color=:stage_color', array('template_id' => $stage['template_id'],'stage_color'=>$stage['stage_color']));
                    if ($exist_data != 0) {
                        $r['msg'] = 'The stage color already exists under this template。';
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                }

                $sub_sql = 'UPDATE task_stage SET stage_name=:stage_name,stage_color=:stage_color,order_id=:order_id,clt_type=:clt_type,record_time=:record_time WHERE stage_id = :stage_id and template_id=:template_id and project_id=:project_id;';
                $record_time = date('Y-m-d H:i:s', time());
                $status= '0';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":stage_name", $stage['stage_name'], PDO::PARAM_STR);
                $command->bindParam(":template_id",$stage['template_id'], PDO::PARAM_INT);
                $command->bindParam(":stage_id",$stage['stage_id'], PDO::PARAM_INT);
                $command->bindParam(":project_id",$root_proid, PDO::PARAM_INT);
                $command->bindParam(":stage_color",$stage['stage_color'], PDO::PARAM_STR);
                $command->bindParam(":order_id",$stage['order_id'], PDO::PARAM_INT);
                $command->bindParam(":clt_type",$stage['clt_type'], PDO::PARAM_STR);
                $command->bindParam(":record_time",$record_time, PDO::PARAM_STR);
                $rs = $command->execute();
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

    //详情
    public static function detailList($template_id){

        $sql = "SELECT * FROM task_stage WHERE  template_id = '".$template_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $i => $j){
                $rs[$i]['stage_name'] = $j['stage_name'];
                $rs[$i]['stage_color'] = $j['stage_color'];
                $rs[$i]['order_id'] = $j['order_id'];
                $rs[$i]['tempId'] = time().rand(001,999);
            }
        }
        return $rs;
    }

    //按项目查找模版阶段
    public static function stageByProgram($project_id){
        $sql = "SELECT stage_id,stage_name FROM task_stage WHERE status=0 and project_id='".$project_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['stage_id']] = $row['stage_name'];
            }
        }

        return $rs;
    }

    //按模版ID查找阶段
    public static function queryStage($template_id){
        $sql = "SELECT stage_id,stage_name FROM task_stage WHERE status=0 and template_id='".$template_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['stage_id']] = $row['stage_name'];
            }
        }

        return $rs;
    }

    //按模版ID阶段ID查找任务
    public static function queryTask($template_id,$stage_id){
        $sql = "SELECT task_id,task_name FROM task_list WHERE status=0 and template_id='".$template_id."' and stage_id='".$stage_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['task_id']] = $row['task_name'];
            }
        }

        return $rs;
    }

    //启用
    public static function startStage($id) {

        $model = TaskStage::model()->findByPk($id);

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
    public static function stopStage($id) {

        $model = TaskStage::model()->findByPk($id);

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

                $sql = "update task_list set status = '9' where stage_id = '".$id."'";
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

    //隐藏(Dashboard)
    public static function hideDashboard($id) {

        $model = TaskStage::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->dashboard_flag = self::STATUS_HIDE;
            $result = $model->save();

            if ($result) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Start Type'), self::updateLog($model));
                $r['msg'] = 'Hide Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = 'Hide Error';
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

    //显示(Dashboard)
    public static function showDashboard($id) {

        $model = TaskStage::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model->dashboard_flag = self::STATUS_SHOW;
            $result = $model->save();

            if ($result) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Stop Type'), self::updateLog($model));
                $r['msg'] = 'Show Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = 'Show Error';
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

    //保存 key
    public static function saveKey($key){
        try {
            foreach($key['stage'] as $stage_id => $day){
                $stage_model = TaskStage::model()->findByPk($stage_id);
                $stage_model->plan_day = $day;
                $result = $stage_model->save();
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
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function getTemplateEnd($template_id,$template_start){
        $template_start = Utils::DateToCn($template_start);
        $sql = "SELECT plan_day FROM task_stage WHERE status=0 and template_id=:template_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $total_day = 0;
        if(count($rows)>0){
            foreach ($rows as $i => $j){
                $total_day+=$j['plan_day'];
            }
        }
        $r['template_end'] = Utils::DateToEn(date('Y-m-d',strtotime("{$template_start} +$total_day day")));
        return $r;
    }

    public static function getStageEnd($stage_id,$stage_start){
        $stage_start = Utils::DateToCn($stage_start);
        $sql = "SELECT plan_day FROM task_stage WHERE status=0 and stage_id=:stage_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":stage_id", $stage_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $plan_day = 0;
        if(count($rows)>0){
            foreach ($rows as $i => $j){
                $plan_day+=$j['plan_day'];
            }
        }
        $r['stage_end'] = Utils::DateToEn(date('Y-m-d',strtotime("{$stage_start} +$plan_day day")));
        return $r;
    }

    public static function getStageDay($template_id,$stage_id,$template_start){
        $stage_model = TaskStage::model()->findByPk($stage_id);
        $order_id = $stage_model->order_id;
        $plan_day = $stage_model->plan_day;
        $sql = "SELECT plan_day FROM task_stage WHERE status=0 and order_id <= :order_id and template_id=:template_id and stage_id <> :stage_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
        $command->bindParam(":stage_id", $stage_id, PDO::PARAM_STR);
        $command->bindParam(":order_id", $order_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $total_day = 0;
        if(count($rows)>0){
            foreach ($rows as $i => $j){
                $total_day+=$j['plan_day'];
            }
        }
        $r['stage_start'] = Utils::DateToEn(date('Y-m-d',strtotime("{$template_start} +$total_day day")));
        $r['stage_end'] = Utils::DateToEn(date('Y-m-d',strtotime("{$r['stage_start']} +$plan_day day")));
        return $r;
    }


    //按模版ID查找阶段
    public static function queryStageDetail($template_id,$stage_id,$stage_start){
        $stage_model = TaskStage::model()->findByPk($stage_id);
        $order_id = $stage_model->order_id;
        $sql = "SELECT count(*) as cnt FROM task_stage WHERE status=0 and template_id=:template_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
        $s = $command->queryAll();
        $stage_cnt = $s[0]['cnt'];

        $sql = "SELECT * FROM task_stage WHERE status=0 and template_id=:template_id order by order_id asc";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            $index = 1;
            foreach ($rows as $key => $row) {
                $rs[$index]['name'] = $row['stage_name'];
                $rs[$index]['id'] = $row['stage_id'];
                $rs[$index]['color'] = $row['stage_color'];
                $rs[$index]['plan_day'] = (int)$row['plan_day'];
                $rs[$index]['type'] = '1';
                $rs[$index]['end_date'] ='';
                if($row['stage_id'] == $stage_id){
                    $tag_index = $index;
                }
                $index++;
            }

            $rs[$tag_index]['end_date'] = $stage_start;
            $stage_start_1 = $stage_start;
            $tag_index_1 = $tag_index;

            while (true){
                $tag_index_1 = $tag_index_1 -1;
                if($tag_index_1<1){
                    break;
                }
                $plan_day = $rs[$tag_index_1]['plan_day'];
                $stage_start_1 = date('Y-m-d',strtotime("{$stage_start_1} - $plan_day day"));
                $rs[$tag_index_1]['end_date'] = $stage_start_1;
            };

            $stage_start_2 = $stage_start;
            $tag_index_2 = $tag_index;
            while (true){
                $tag_index_2 = $tag_index_2 +1;
                if($tag_index_2 > $stage_cnt){
                    break;
                }
                $plan_day = $rs[$tag_index_2]['plan_day'];
                $stage_start_2 = date('Y-m-d',strtotime("{$stage_start_2} + $plan_day day"));
                $rs[$tag_index_2]['end_date'] = $stage_start_2;
            };
            $index_new = 1;
            foreach($rs as $i => $j){
                $r[$index_new]['name'] = $j['name'];
                $r[$index_new]['id'] = $j['id'];
                $r[$index_new]['color'] = $j['color'];
                $r[$index_new]['plan_day'] = (int)$j['plan_day'];
                $r[$index_new]['type'] = '1';
                $r[$index_new]['end_date'] =$j['end_date'];
                $index_new++;
                $sql = "SELECT * FROM task_list WHERE status=0 and stage_id=:stage_id";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":stage_id", $j['id'], PDO::PARAM_STR);
                $e = $command->queryAll();
                if(count($e)>0){
                    $end_date = $j['end_date'];
                    foreach($e as $x => $y){
                        $r[$index_new]['name'] = '  '.$y['task_name'];
                        $r[$index_new]['id'] = $y['task_id'];
                        $r[$index_new]['plan_day'] = $y['plan_day'];
                        $r[$index_new]['lag_day'] = $y['lag_day'];
                        $r[$index_new]['type'] = '2';
                        if($x == 0){
                            $r[$index_new]['end_date'] = $j['end_date'];
                        }else{
                            $plan_day = $y['plan_day'];
                            $end_date = date('Y-m-d',strtotime("{$end_date} + $plan_day day"));
                            $r[$index_new]['end_date'] = $end_date;
                        }
                        $index_new++;
                    }
                }
            }

        }else{
            $r = array();
        }

        return $r;
    }
}
