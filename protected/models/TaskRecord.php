<?php

/**
 * 任务记录列表
 * @author LiuMinchao
 */
class TaskRecord extends CActiveRecord {


    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'task_record';
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
            self::STATUS_NORMAL => Yii::t('common', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('common', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'bg-success', //正常
            self::STATUS_STOP => ' bg-danger', //停用
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
        $sql = "select t.*,b.name from task_record t left join task_record_model b on t.check_id = b.check_id where ";
        $pro_model =Program::model()->findByPk($args['project_id']);
        $root_proid = $pro_model->root_proid;
        //Program
        if ($args['project_id'] != '') {
            $condition.= ( $condition == '') ? ' t.project_id=:project_id' : ' AND t.project_id=:project_id';
        }

        //Template Id
        if ($args['template_id'] != '') {
            $condition.= ( $condition == '') ? ' t.template_id=:template_id' : ' AND t.template_id=:template_id';
        }
        //Stage Id
        if ($args['stage_id'] != '') {
            $condition.= ( $condition == '') ? ' t.stage_id=:stage_id' : ' AND t.stage_id=:stage_id';
        }

        //Task Id
        if ($args['task_id'] != '') {
            $condition.= ( $condition == '') ? ' t.task_id=:task_id' : ' AND t.task_id=:task_id';
        }

        //Clt Type
        if ($args['clt_type'] != '') {
            $condition.= ( $condition == '') ? ' t.clt_type=:clt_type' : ' AND t.clt_type=:clt_type';
        }

        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= " and t.record_time >='$start_date'";
        }

        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= " and t.record_time <='$end_date 23:59:59'";
        }

        //pbu_name
        if ($args['pbu_name'] != '') {
            $condition.= ( $condition == '') ? ' b.name like :name' : ' AND b.name like :name';
            $args['pbu_name'] = urldecode($args['pbu_name']);
            $pbu_name = '%'.str_replace(' ','', $args['pbu_name']).'%';
        }

        if($condition){
            $condition.= " AND t.status != '-1' ";
        }else{
            $condition.= "  t.status != '-1' ";
        }

        if ($_REQUEST['q_order'] == '') {
            $order = ' order by t.record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $sql.= $condition.' '.$order;
        $command = Yii::app()->db->createCommand($sql);

        //Program
        if ($args['project_id'] != '') {
            $command->bindParam(":project_id", $root_proid, PDO::PARAM_STR);
        }

        //Template Id
        if ($args['template_id'] != '') {
            $command->bindParam(":template_id", $args['template_id'], PDO::PARAM_STR);
        }
        //Stage Id
        if ($args['stage_id'] != '') {
            $command->bindParam(":stage_id", $args['stage_id'], PDO::PARAM_STR);
        }

        //Task Id
        if ($args['task_id'] != '') {
            $command->bindParam(":task_id", $args['task_id'], PDO::PARAM_STR);
        }

        //Clt Type
        if ($args['clt_type'] != '') {
            $command->bindParam(":clt_type", $args['clt_type'], PDO::PARAM_STR);
        }

        //pbu_name
        if ($args['pbu_name'] != '') {
            $command->bindParam(":name", $pbu_name, PDO::PARAM_STR);
        }


        $data = $command->queryAll();

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($data);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($data,$start,$pageSize);
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

    //插入数据
    public static function insertTemplate($template_name,$program_id) {


        if ($template_name == '') {
            $r['msg'] = 'Template Nmae is not Null';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = new TaskTemplate('create');
        $trans = $model->dbConnection->beginTransaction();
        try {
            $record_time = date('Y-m-d H:i:s', time());
            $status= '0';
            $model->template_name = $template_name;
            $model->project_id = $program_id;
            $model->status = $status;
            $model->record_time = $record_time;
            $result = $model->save();//var_dump($result);exit;
            $id = $model->template_id;

            if ($result) {
                $trans->commit();
                $r['template_id'] = $id;
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
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }

        return $r;
    }

    //修改数据
    public static function editTemplate($template_name,$template_id) {


        if ($template_name == '') {
            $r['msg'] = 'Template Nmae is not Null';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = TaskTemplate::model()->findByPk($template_id);
        $trans = $model->dbConnection->beginTransaction();
        try {
            $record_time = date('Y-m-d H:i:s', time());
            $model->template_name = $template_name;
            $model->record_time = $record_time;
            $result = $model->save();//var_dump($result);exit;

            if ($result) {
                $trans->commit();
                $r['template_id'] = $template_id;
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
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
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

    //详情
    public static function  QadetailList($check_id){
        $sql = "SELECT * FROM task_record WHERE link_check_id = '".$check_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $detaillist = $command->queryAll();
        return $detaillist;
    }

    //上传记录
    public static function uploadRecord($task) {
        $record_time = date("Y-m-d H:i:s");
        $task_json = json_encode($task);
        $txt = '['.$record_time.']'.'  '.'构件记录批量上传参数: '.$task_json;
        RfList::write_log($txt);

        $stage_model = TaskStage::model()->findByPk($task['stage_id']);
        $pro_model = Program::model()->findByPk($task['project_id']);
        $task_model = TaskList::model()->findByPk($task['task_id']);
        $form_id = $task_model->checklist_id;
        $project_name = $pro_model->program_name;
        $contractor_id = $pro_model->contractor_id;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $template_id = $stage_model->template_id;
        $trans = Yii::app()->db->beginTransaction();
        $record_status = '0';
        $user_id = '56207';
        $approve_user_id = '56216';
        try {
            list($s1, $s2) = explode(' ', microtime());
            $check_id_1 = (float)sprintf('%.0f',(floatval($s1) + floatval($s2)) * 1000);
            $task_pic = '';
            $task_remarks = '';
            $record_time = date("Y-m-d H:i:s");
            $record_time = Utils::randomDate($task['start_date'].' 09:30:00',$task['start_date'].' 15:00:00',true);
            $link_check_id = '';
            //添加task_record
            $sub_sql = 'INSERT INTO task_record (check_id,project_id,task_id,stage_id,template_id,status,contractor_id,user_id,start_date,end_date,pic,remarks,update_time,record_time,link_check_id,clt_type,na_flag) VALUES(:check_id,:project_id,:task_id,:stage_id,:template_id,:status,:contractor_id,:user_id,:start_date,:end_date,:pic,:remarks,:update_time,:record_time,:link_check_id,:clt_type,:na_flag)';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":check_id", $check_id_1, PDO::PARAM_STR);
            $command->bindParam(":project_id", $task['project_id'], PDO::PARAM_STR);
            $command->bindParam(":task_id", $task['task_id'], PDO::PARAM_STR);
            $command->bindParam(":stage_id", $task['stage_id'], PDO::PARAM_STR);
            $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
            $command->bindParam(":status", $record_status, PDO::PARAM_STR);
            $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
            $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $command->bindParam(":start_date", $task['start_date'], PDO::PARAM_STR);
            $command->bindParam(":end_date", $task['end_date'], PDO::PARAM_STR);
            $command->bindParam(":pic", $task_pic, PDO::PARAM_STR);
            $command->bindParam(":remarks", $task_remarks, PDO::PARAM_STR);
            $command->bindParam(":update_time", $record_time, PDO::PARAM_STR);
            $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
            $command->bindParam(":link_check_id", $link_check_id, PDO::PARAM_STR);
            $command->bindParam(":clt_type", $task['phase'], PDO::PARAM_STR);
            $command->bindParam(":na_flag", $task['na_flag'], PDO::PARAM_STR);
            $rs = $command->execute();

            $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and pbu_id=:pbu_id and status='0' ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":project_id", $task['project_id'], PDO::PARAM_STR);
            $command->bindParam(":pbu_id", $task['pbu_id'], PDO::PARAM_STR);
            $pbu = $command->queryAll();
            //添加task_record_model
            $default_data = '';
            $record_time = Utils::randomDate($task['start_date'].' 15:00:00',$task['start_date'].' 16:00:00',true);
            $sub_sql = 'INSERT INTO task_record_model (check_id,model_id,guid,name,block,level,unit,id,system,category,floor,version,record_time) VALUES(:check_id,:model_id,:guid,:name,:block,:level,:unit,:id,:system,:category,:floor,:version,:record_time)';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":check_id", $check_id_1, PDO::PARAM_STR);
            $command->bindParam(":model_id", $pbu[0]['model_id'], PDO::PARAM_STR);
            $command->bindParam(":guid", $task['pbu_id'], PDO::PARAM_STR);
            $command->bindParam(":name", $pbu[0]['pbu_name'], PDO::PARAM_STR);
            $command->bindParam(":block", $pbu[0]['block'], PDO::PARAM_STR);
            $command->bindParam(":level", $pbu[0]['level'], PDO::PARAM_STR);
            $command->bindParam(":unit", $pbu[0]['unit_nos'], PDO::PARAM_STR);
            $command->bindParam(":id", $default_data, PDO::PARAM_STR);
            $command->bindParam(":system", $default_data, PDO::PARAM_STR);
            $command->bindParam(":category", $default_data, PDO::PARAM_STR);
            $command->bindParam(":floor", $default_data, PDO::PARAM_STR);
            $command->bindParam(":version", $pbu[0]['version'], PDO::PARAM_STR);
            $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
            $rs = $command->execute();

            $txt = '['.$record_time.']'.'  '.'构件记录批量上传:task_record'.' task_id: '.$check_id_1;
            RfList::write_log($txt);
            //查询这个构件在这个项目，阶段下有没有数据
            $status_sql = "SELECT * FROM task_component_stats WHERE project_id = :project_id and model_id = :model_id and guid = :guid and stage_id = :stage_id ";
            $command = Yii::app()->db->createCommand($status_sql);
            $command->bindParam(":project_id", $task['project_id'], PDO::PARAM_STR);
            $command->bindParam(":model_id", $pbu[0]['model_id'], PDO::PARAM_STR);
            $command->bindParam(":guid", $task['pbu_id'], PDO::PARAM_STR);
            $command->bindParam(":stage_id", $task['stage_id'], PDO::PARAM_STR);
            $component_status = $command->queryAll();

            $latest_flag = '1';
            $status_arr = self::is_stage_complete($task,$pbu[0]['model_id']);
            if(count($component_status)>0){
                $start_date = $component_status[0]['start_date'];
                $end_date = $component_status[0]['end_date'];
                $id = $component_status[0]['id'];
                //如果这个任务开始时间早于这条记录得开始时间，就更新
                if($task['start_date'] < $start_date){
                    $update_status_sql = "UPDATE
                        task_component_stats
                    SET
                        start_date = :start_date, start_task_id = :start_task_id, start_check_id = :start_check_id
                    WHERE
                        id = :id";
                    $command = Yii::app()->db->createCommand($update_status_sql);
                    $command->bindParam(":start_date", $task['start_date'], PDO::PARAM_STR);
                    $command->bindParam(":start_task_id", $task['task_id'], PDO::PARAM_STR);
                    $command->bindParam(":start_check_id", $check_id_1, PDO::PARAM_STR);
                    $command->bindParam(":id", $id, PDO::PARAM_STR);
                    $rs = $command->execute();
                }
                //如果这个任务结束时间晚于这条记录得结束时间，就更新
                if($task['end_date'] >= $end_date){
                    $sql = "UPDATE
                                task_component_stats
                            SET
                                complete_num = complete_num + 1, status = :status,
                                latest_task_id = :latest_task_id, latest_check_id = :latest_check_id, end_date = :end_date, latest_flag = :latest_flag, user_id = :user_id
                            WHERE
                                id = :id";
                    $command->bindParam(":status", $status_arr['status'], PDO::PARAM_STR);
                    $command->bindParam(":latest_task_id", $task['task_id'], PDO::PARAM_STR);
                    $command->bindParam(":latest_check_id", $check_id_1, PDO::PARAM_STR);
                    $command->bindParam(":end_date", $task['end_date'], PDO::PARAM_STR);
                    $command->bindParam(":latest_flag", $latest_flag, PDO::PARAM_STR);
                    $command->bindParam(":user_id", $approve_user_id, PDO::PARAM_STR);
                    $command->bindParam(":id", $id, PDO::PARAM_STR);
                    $command->execute();
                }else{
                    $sql = "UPDATE
                                task_component_stats
                            SET
                                complete_num = complete_num + 1, status = :status, latest_flag = :latest_flag
                            WHERE
                                id = :id";
                    $command->bindParam(":status", $status_arr['status'], PDO::PARAM_STR);
                    $command->bindParam(":latest_flag", $latest_flag, PDO::PARAM_STR);
                    $command->bindParam(":id", $id, PDO::PARAM_STR);
                    $command->execute();
                }
            }else{
                //不是NA得记录
                if($task['na_flag'] != '1'){
                    $complete_num = 1;
                    $insert_status = '1';
                    $latest_flag = '1';
                    $insert_status_sql = "INSERT INTO
                        task_component_stats(project_id, model_id, guid, template_id, stage_id, start_date, start_task_id, start_check_id, end_date, latest_task_id, latest_check_id, user_id, complete_num, status, latest_flag)
                    VALUES
                        (:project_id, :model_id, :guid, :template_id, :stage_id, :start_date, :start_task_id, :start_check_id, :end_date, :latest_task_id, :latest_check_id, :user_id, :complete_num, :status, :latest_flag)";
                    $command = Yii::app()->db->createCommand($insert_status_sql);
                    $command->bindParam(":project_id", $task['project_id'], PDO::PARAM_STR);
                    $command->bindParam(":model_id", $pbu[0]['model_id'], PDO::PARAM_STR);
                    $command->bindParam(":guid", $task['pbu_id'], PDO::PARAM_STR);
                    $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
                    $command->bindParam(":stage_id", $task['stage_id'], PDO::PARAM_STR);
                    $command->bindParam(":start_date", $task['start_date'], PDO::PARAM_STR);
                    $command->bindParam(":start_task_id", $task['task_id'], PDO::PARAM_STR);
                    $command->bindParam(":start_check_id", $check_id_1, PDO::PARAM_STR);
                    $command->bindParam(":end_date", $task['end_date'], PDO::PARAM_STR);
                    $command->bindParam(":latest_task_id", $task['task_id'], PDO::PARAM_STR);
                    $command->bindParam(":latest_check_id", $check_id_1, PDO::PARAM_STR);
                    $command->bindParam(":user_id", $approve_user_id, PDO::PARAM_STR);
                    $command->bindParam(":complete_num", $complete_num, PDO::PARAM_STR);
                    $command->bindParam(":status", $status_arr['status'], PDO::PARAM_STR);
                    $command->bindParam(":latest_flag", $latest_flag, PDO::PARAM_STR);
                    $rs = $command->execute();
                    $txt = '['.$record_time.']'.'  '.'构件记录批量上传:task_component_stats insert'.' check_id: '.$check_id_1;
                    RfList::write_log($txt);
                }
            }

            //查询是否还有其他标有最新阶段标识得记录
            $sql = "SELECT * FROM task_component_stats WHERE template_id = :template_id and model_id = :model_id and guid = :guid and latest_flag = '1' and id <> :id";
            $command = Yii::app()->db->createCommand($status_sql);
            $command->bindParam(":template_id", $task['template_id'], PDO::PARAM_STR);
            $command->bindParam(":model_id", $pbu[0]['model_id'], PDO::PARAM_STR);
            $command->bindParam(":guid", $task['pbu_id'], PDO::PARAM_STR);
            $command->bindParam(":id", $id, PDO::PARAM_STR);
            $s = $command->queryAll();
            if(count($s) == 0){
                $flag = '1';
            }else{
                //如果有得话,把前一个阶段得latest_flag置为0
                $front_stage_id = $s[0]['stage_id'];
                $front_id = $s[0]['id'];
                $sql = "SELECT * FROM task_stage WHERE stage_id in (:front_stage_id,:stage_id) and status = '0' ORDER BY clt_type desc, order_id";
                $command->bindParam(":front_stage_id", $front_stage_id, PDO::PARAM_STR);
                $command->bindParam(":stage_id", $task['stage_id'], PDO::PARAM_STR);
                $ss = $command->queryAll();
                //排前面，new_stage排后面为最新
                if($ss[0]['stage_id'] == $front_stage_id){
                    $sql = "UPDATE task_component_stats SET latest_flag = '0' WHERE id = :id";
                    $command->bindParam(":id", $front_id, PDO::PARAM_STR);
                    $command->execute();
                }
            }

            $check_id = '';
            if($form_id){
                $qa_model = QaChecklist::model()->findByPk($form_id);
                $form_name_en = $qa_model->form_name_en;
                $type_id = $qa_model->type_id;
                list($s1, $s2) = explode(' ', microtime());
                $check_id = (float)sprintf('%.0f',(floatval($s1) + floatval($s2)) * 1000);
                $title = $stage_model->stage_name;
                $current_step = 5;
                $drawing_pic = '';
                $qa_record_status = '-1';
                $qa_remark = '';
                $apply_time =  Utils::randomDate($task['start_date'].' 16:30:00',$task['start_date'].' 17:30:00',true);
                $save_path = '';
                $update_time = '';
                $close_time = '';
                $sub_sql = 'INSERT INTO qa_checklist_record (check_id, title, current_step, contractor_id, contractor_name, project_id, project_name, block, secondary_region, location, drawing_pic, status, remark, apply_user_id, apply_time, save_path,  close_time, clt_type, task_id) VALUES(:check_id, :title, :current_step, :contractor_id, :contractor_name, :project_id, :project_name, :block, :secondary_region, :location, :drawing_pic, :status, :remark, :apply_user_id, :apply_time, :save_path, :close_time, :clt_type, :task_id)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":title", $title, PDO::PARAM_STR);
                $command->bindParam(":current_step", $current_step, PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                $command->bindParam(":contractor_name", $contractor_name, PDO::PARAM_STR);
                $command->bindParam(":project_id", $task['project_id'], PDO::PARAM_STR);
                $command->bindParam(":project_name", $project_name, PDO::PARAM_STR);
                $command->bindParam(":block", $pbu[0]['block'], PDO::PARAM_STR);
                $command->bindParam(":secondary_region", $pbu[0]['level'], PDO::PARAM_STR);
                $command->bindParam(":location", $pbu[0]['unit_nos'], PDO::PARAM_STR);
                $command->bindParam(":drawing_pic", $drawing_pic, PDO::PARAM_STR);
                $command->bindParam(":status", $qa_record_status, PDO::PARAM_STR);
                $command->bindParam(":remark", $qa_remark, PDO::PARAM_STR);
                $command->bindParam(":apply_user_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":apply_time", $apply_time, PDO::PARAM_STR);
                $command->bindParam(":save_path", $save_path, PDO::PARAM_STR);
                $command->bindParam(":close_time", $close_time, PDO::PARAM_STR);
                $command->bindParam(":clt_type", $task['phase'], PDO::PARAM_STR);
                $command->bindParam(":task_id", $task['task_id'], PDO::PARAM_STR);
                $rs = $command->execute();

                $txt = '['.$record_time.']'.'  '.'构件记录批量上传 qa_checklist_record:'.' check_id: '.$check_id;
                RfList::write_log($txt);

                $member_status = '0';
                $member_step = '3';
                $member_type = '1';
                $sub_sql = 'INSERT INTO qa_checklist_record_member (check_id,user_id,type,step,status) VALUES(:check_id,:user_id,:type,:step,:status)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":user_id", $approve_user_id, PDO::PARAM_STR);
                $command->bindParam(":type", $member_type, PDO::PARAM_STR);
                $command->bindParam(":step", $member_step, PDO::PARAM_STR);
                $command->bindParam(":status", $member_status, PDO::PARAM_STR);
                $rs = $command->execute();

                $sql = "SELECT * FROM qa_form WHERE form_id=:form_id and status='0' ";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":form_id", $form_id, PDO::PARAM_STR);
                $form_list = $command->queryAll();
                if(count($form_list)>0){
                    foreach($form_list as $x => $y){
                        $item['item_id'] = $y['item_id'];
                        $item['item_value'] = 'YES';
                        $item['status'] = '2';
                        $item['rs_approval'] = 'Y';
                        $item['remarks'] = '';
                        $form_data[] = $item;
                    }
                }
                $form_data = json_encode($form_data);
                $detail_data_id = '';
                $pic = '';
                $remark = '';
                $deal_type = '1';
                $step = 1;
                $latitude = '';
                $longitude = '';
                $address = '';
                $detail_form_data = '';
                $sub_sql = 'INSERT INTO qa_checklist_record_detail (check_id,pic,remark,deal_type,user_id,step,latitude,longitude,address,record_time,data_id,form_data) VALUES(:check_id,:pic,:remark,:deal_type,:user_id,:step,:latitude,:longitude,:address,:record_time,:data_id,:form_data)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":pic", $pic, PDO::PARAM_STR);
                $command->bindParam(":remark", $remark, PDO::PARAM_STR);
                $command->bindParam(":deal_type", $deal_type, PDO::PARAM_STR);
                $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":step", $step, PDO::PARAM_STR);
                $command->bindParam(":latitude", $latitude, PDO::PARAM_STR);
                $command->bindParam(":longitude", $longitude, PDO::PARAM_STR);
                $command->bindParam(":address", $address, PDO::PARAM_STR);
                $command->bindParam(":record_time", $apply_time, PDO::PARAM_STR);
                $command->bindParam(":data_id", $detail_data_id, PDO::PARAM_STR);
                $command->bindParam(":form_data", $detail_form_data, PDO::PARAM_STR);
                $rs = $command->execute();

                $status = '3';
                $record_time_2 =  Utils::randomDate($task['start_date'].' 17:30:00',$task['start_date'].' 18:30:00',true);
                $sub_sql = 'INSERT INTO qa_form_data (check_id, project_id, form_title, form_id, form_data, type_id, user_id, status, record_time) VALUES(:check_id, :project_id, :form_title, :form_id, :form_data, :type_id, :user_id, :status, :record_time)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":project_id", $task['project_id'], PDO::PARAM_STR);
                $command->bindParam(":form_title", $form_name_en, PDO::PARAM_STR);
                $command->bindParam(":form_id", $form_id, PDO::PARAM_STR);
                $command->bindParam(":form_data", $form_data, PDO::PARAM_STR);
                $command->bindParam(":type_id", $type_id, PDO::PARAM_STR);
                $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time_2, PDO::PARAM_STR);
                $rs = $command->execute();
                $data_id = Yii::app()->db->getLastInsertID();

                $deal_type = '2';
                $step = 2;
                $sub_sql = 'INSERT INTO qa_checklist_record_detail (check_id,pic,remark,deal_type,user_id,step,latitude,longitude,address,record_time,data_id,form_data) VALUES(:check_id,:pic,:remark,:deal_type,:user_id,:step,:latitude,:longitude,:address,:record_time,:data_id,:form_data)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":pic", $pic, PDO::PARAM_STR);
                $command->bindParam(":remark", $remark, PDO::PARAM_STR);
                $command->bindParam(":deal_type", $deal_type, PDO::PARAM_STR);
                $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":step", $step, PDO::PARAM_STR);
                $command->bindParam(":latitude", $latitude, PDO::PARAM_STR);
                $command->bindParam(":longitude", $longitude, PDO::PARAM_STR);
                $command->bindParam(":address", $address, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time_2, PDO::PARAM_STR);
                $command->bindParam(":data_id", $data_id, PDO::PARAM_STR);
                $command->bindParam(":form_data", $detail_form_data, PDO::PARAM_STR);
                $rs = $command->execute();

                $deal_type = '91';
                $step = 3;
                $record_time_3 =  Utils::randomDate($task['start_date'].' 18:30:00',$task['start_date'].' 19:30:00',true);
                $sub_sql = 'INSERT INTO qa_checklist_record_detail (check_id,pic,remark,deal_type,user_id,step,latitude,longitude,address,record_time,data_id,form_data) VALUES(:check_id,:pic,:remark,:deal_type,:user_id,:step,:latitude,:longitude,:address,:record_time,:data_id,:form_data)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":pic", $pic, PDO::PARAM_STR);
                $command->bindParam(":remark", $remark, PDO::PARAM_STR);
                $command->bindParam(":deal_type", $deal_type, PDO::PARAM_STR);
                $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":step", $step, PDO::PARAM_STR);
                $command->bindParam(":latitude", $latitude, PDO::PARAM_STR);
                $command->bindParam(":longitude", $longitude, PDO::PARAM_STR);
                $command->bindParam(":address", $address, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time_3, PDO::PARAM_STR);
                $command->bindParam(":data_id", $detail_data_id, PDO::PARAM_STR);
                $command->bindParam(":form_data", $detail_form_data, PDO::PARAM_STR);
                $rs = $command->execute();

                $deal_type = '4';
                $step = 4;
                $record_time_4 =  Utils::randomDate($task['start_date'].' 19:30:00',$task['start_date'].' 20:30:00',true);
                $sub_sql = 'INSERT INTO qa_checklist_record_detail (check_id,pic,remark,deal_type,user_id,step,latitude,longitude,address,record_time,data_id,form_data) VALUES(:check_id,:pic,:remark,:deal_type,:user_id,:step,:latitude,:longitude,:address,:record_time,:data_id,:form_data)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":pic", $pic, PDO::PARAM_STR);
                $command->bindParam(":remark", $remark, PDO::PARAM_STR);
                $command->bindParam(":deal_type", $deal_type, PDO::PARAM_STR);
                $command->bindParam(":user_id", $approve_user_id, PDO::PARAM_STR);
                $command->bindParam(":step", $step, PDO::PARAM_STR);
                $command->bindParam(":latitude", $latitude, PDO::PARAM_STR);
                $command->bindParam(":longitude", $longitude, PDO::PARAM_STR);
                $command->bindParam(":address", $address, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time_4, PDO::PARAM_STR);
                $command->bindParam(":data_id", $data_id, PDO::PARAM_STR);
                $command->bindParam(":form_data", $form_data, PDO::PARAM_STR);
                $rs = $command->execute();

                $deal_type = '5';
                $step = 5;
                $record_time_5 =  Utils::randomDate($task['end_date'].' 20:30:00',$task['start_date'].' 21:30:00',true);
                $sub_sql = 'INSERT INTO qa_checklist_record_detail (check_id,pic,remark,deal_type,user_id,step,latitude,longitude,address,record_time,data_id,form_data) VALUES(:check_id,:pic,:remark,:deal_type,:user_id,:step,:latitude,:longitude,:address,:record_time,:data_id,:form_data)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":pic", $pic, PDO::PARAM_STR);
                $command->bindParam(":remark", $remark, PDO::PARAM_STR);
                $command->bindParam(":deal_type", $deal_type, PDO::PARAM_STR);
                $command->bindParam(":user_id", $approve_user_id, PDO::PARAM_STR);
                $command->bindParam(":step", $step, PDO::PARAM_STR);
                $command->bindParam(":latitude", $latitude, PDO::PARAM_STR);
                $command->bindParam(":longitude", $longitude, PDO::PARAM_STR);
                $command->bindParam(":address", $address, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time_5, PDO::PARAM_STR);
                $command->bindParam(":data_id", $detail_data_id, PDO::PARAM_STR);
                $command->bindParam(":form_data", $detail_form_data, PDO::PARAM_STR);
                $rs = $command->execute();

                if($task['attach_id']){
                    $doc_list = explode('/',$task['attach_id']);
                    $doc_cnt = count($doc_list);
                    $doc_id = $doc_list[$doc_cnt-1];
                    $doc_path = '';
                    $doc_info = explode('.',$task['attach_name']);
                    $record_time =  Utils::randomDate($task['start_date'].' 18:30:00',$task['start_date'].' 20:30:00',true);
                    $sub_sql = 'INSERT INTO qa_checklist_record_document (check_id, doc_id, doc_name, doc_path, doc_type, status, user_id, record_time) VALUES(:check_id, :doc_id, :doc_name, :doc_path, :doc_type, :status, :user_id, :record_time)';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                    $command->bindParam(":doc_id", $doc_id, PDO::PARAM_STR);
                    $command->bindParam(":doc_name", $doc_info[0], PDO::PARAM_STR);
                    $command->bindParam(":doc_path", $doc_path, PDO::PARAM_STR);
                    $command->bindParam(":doc_type", $doc_info[1], PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                    $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                    $rs = $command->execute();
                }

                $finish_status = '1';

                $sub_sql = 'UPDATE task_record SET link_check_id = :link_check_id,update_time = :update_time,status=:finish_status  WHERE check_id = :check_id';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id_1, PDO::PARAM_STR);
                $command->bindParam(":link_check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":update_time", $record_time_5, PDO::PARAM_STR);
                $command->bindParam(":finish_status", $finish_status, PDO::PARAM_STR);
                $rs = $command->execute();

            }else{
                $finish_status = '1';
                $record_time_5 =  Utils::randomDate($task['end_date'].' 20:30:00',$task['start_date'].' 21:30:00',true);
                $sub_sql = 'UPDATE qa_checklist_record SET update_time = :update_time,close_time = :close_time,status=:finish_status  WHERE check_id = :check_id';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":update_time", $record_time_5, PDO::PARAM_STR);
                $command->bindParam(":close_time", $record_time_5, PDO::PARAM_STR);
                $command->bindParam(":finish_status", $finish_status, PDO::PARAM_STR);
                $rs = $command->execute();
            }

            $sub_sql = 'INSERT INTO task_upload_log (check_id,link_check_id) VALUES(:check_id,:link_check_id)';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":check_id", $check_id_1, PDO::PARAM_STR);
            $command->bindParam(":link_check_id", $check_id, PDO::PARAM_STR);
            $rs = $command->execute();

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
            $trans->commit();
        }catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $txt = '['.$record_time.']'.'  '.'构件记录批量上传失败: '.$e->getMessage();
            RfList::write_log($txt);
            $trans->rollback();
        }
        return $r;
    }

    public static function is_stage_complete($task,$model_id) {
        $sql = " SELECT
            a.task_id, ifnull(d.check_id, '') as check_id
        FROM
            task_list a
        LEFT JOIN
            (SELECT b.check_id, b.stage_id, b.task_id
               FROM task_record b
               JOIN task_record_model c ON b.check_id = c.check_id
              WHERE b.stage_id = :stage_id_1 and b.status = '1' and c.model_id = :model_id and c.guid = :guid
            ) d ON a.task_id = d.task_id
        WHERE
            a.stage_id = :stage_id_2 and a.status = '0'
        GROUP BY
            a.task_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":stage_id_1", $task['stage_id'], PDO::PARAM_STR);
        $command->bindParam(":model_id", $model_id, PDO::PARAM_STR);
        $command->bindParam(":guid", $task['pbu_id'], PDO::PARAM_STR);
        $command->bindParam(":stage_id_2", $task['stage_id'], PDO::PARAM_STR);
        $rows = $command->queryAll();
        $complete_num = 0;
        $status = '0';
        if(count($rows)>0){
            $cnt = count($rows);
            foreach ($rows as $i => $j){
                if($j['check_id'] != ''){
                    $complete_num++;
                }
            }
            if($complete_num == $cnt){
                $status = '1';
            }
        }
        $r['status'] = $status;
        $r['complete_num'] = $complete_num;
        return $r;
    }

    //上传qa文档
    public static function uploadQaDocument($args) {
        $trans = Yii::app()->db->beginTransaction();
        try {
            $doc_id = '';
            $name = substr($args['file_src'],38);
            $file_name = explode('.',$name);
            $sub_sql = 'INSERT INTO qa_checklist_record_document (record_id, check_id, doc_id, doc_name, doc_path, doc_type, status, user_id, record_time) VALUES(:record_id, :check_id, :doc_id, :doc_name, :doc_path, :doc_type, :status, :user_id, :record_time)';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":doc_id", $doc_id, PDO::PARAM_STR);
            $command->bindParam(":doc_name", $pbu['block'], PDO::PARAM_STR);
            $command->bindParam(":doc_path", $pbu['level'], PDO::PARAM_STR);
            $command->bindParam(":doc_type", $pbu['unit_nos'], PDO::PARAM_STR);
            $command->bindParam(":status", $pbu['part'], PDO::PARAM_STR);
            $command->bindParam(":user_id", $pbu['unit_type'], PDO::PARAM_STR);
            $command->bindParam(":record_time", $pbu['pbu_type'], PDO::PARAM_STR);
            $rs = $command->execute();
        }catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
    }
}
