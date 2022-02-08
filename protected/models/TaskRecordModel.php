<?php

/**
 * 任务记录模型列表
 * @author LiuMinchao
 */
class TaskRecordModel extends CActiveRecord {


    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'task_record_model';
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
        $program_id = $args['program_id'];
        $model_id = $args['model_id'];
        $template_id = $args['template_id'];
        $uuid = $args['uuid'];
        $order = " order by check_id asc";
        $select = "SELECT
            a.*
        FROM
            task_record a
        JOIN task_record_model b ON a.check_id = b.check_id
        WHERE 
            a.project_id = '".$program_id."' and a.template_id = '".$template_id."' and b.model_id = '".$model_id."' and b.guid = '".$uuid."'";
        $sql = $select.$condition.$order;
        $command = Yii::app()->db->createCommand($sql);
        $retdata = $command->queryAll();
        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($retdata);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($retdata,$start,$pageSize);
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

    //查询某一模型的进度（构件组，颜色）
    public static function progressByModel($project_id,$model_id,$template_id){
        $sql = "SELECT
            stage_id, stage_name, stage_color, order_id, '0' as guid_count, ''as guid_list
        FROM
            task_stage
        WHERE
            template_id = '".$template_id."' and status = '0'
        ORDER BY
            order_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $r = array();
                $stage_id = $row['stage_id'];
                $sql = "SELECT
                distinct a.guid
            FROM
                task_record_model a
            JOIN task_record b ON a.check_id = b.check_id
            WHERE 
                a.model_id = '".$model_id."' and b.project_id = '".$project_id."' and b.status = '1' and b.stage_id = '".$stage_id."' ";
                $command = Yii::app()->db->createCommand($sql);
                $rs = $command->queryAll();
                foreach ($rs as $i => $k){
                    $r[] = $k['guid'];
                }
//                $s = implode(",", $rs);
                $rows[$key]['guid_list'] = $r;
            }
        }
        return $rows;
    }

    //根据阶段查询某一模型的进度（构件组，颜色）
    public static function stageByModel($project_id,$model_id,$stage_id){

        $sql = "SELECT
                distinct a.guid
            FROM
                task_record_model a
            JOIN task_record b ON a.check_id = b.check_id
            WHERE 
                a.model_id = '".$model_id."' and b.project_id = '".$project_id."' and b.status = '1' and b.stage_id = '".$stage_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        $model = TaskStage::model()->findByPk($stage_id);
        $stage_color = $model->stage_color;
        $rows[0]['guid_list'] = $rs;
        $rows[0]['stage_color'] = $stage_color;
        return $rows;
    }

    //查询某一模型的进度（构件个数，阶段值）
    public static function cntByModel($project_id,$model_list,$template_id){
        $pro_model = Program::model()->findByPk($project_id);
        $project_id = $pro_model->root_proid;
        $sql = "SELECT
            stage_id, stage_name, stage_color, order_id, '0' as guid_count, ''as guid_list
        FROM
            task_stage
        WHERE
            template_id = '".$template_id."' and status = '0'
        ORDER BY
            order_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $model_array =  explode(',',$model_list);
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $r = array();
                $guid_cnt = 0;
                foreach ($model_array as $i => $j) {
                    $model_info =  explode('_',$j);
                    $model_id = $model_info[0];
                    $version = $model_info[1];
                    $stage_id = $row['stage_id'];
                    $sql = "SELECT
                    count(distinct a.guid) as guid_cnt,b.stage_id
                FROM
                    task_record_model a
                JOIN task_record b ON a.check_id = b.check_id
                WHERE 
                    a.model_id = '" . $model_id . "'  and b.project_id = '" . $project_id . "' and b.status = '1' and b.stage_id = '" . $stage_id . "' ";
                    $command = Yii::app()->db->createCommand($sql);
                    $rs = $command->queryAll();

                    $sql = "SELECT
                    distinct a.guid
                FROM
                    task_record_model a
                JOIN task_record b ON a.check_id = b.check_id
                WHERE 
                    a.model_id = '" . $model_id . "'  and b.project_id = '" . $project_id . "' and b.status = '1' and b.stage_id = '" . $stage_id . "' ";

                    $command = Yii::app()->db->createCommand($sql);
                    $re = $command->queryAll();
                    if (count($re) > 0) {
                        foreach ($re as $i => $k) {
                            $r[] = $k['guid'];
                        }
                    }
                    $guid_cnt+= $rs[0]['guid_cnt'];
                }
                $rows[$key]['guid_list'] = $r;
                $rows[$key]['guid_cnt'] = $guid_cnt;
                $rows[$key]['stage_id'] = $row['stage_id'];
                $rows[$key]['stage_name'] = $row['stage_name'];
                $rows[$key]['stage_color'] = $row['stage_color'];
            }
        }
        return $rows;
    }

    //查询某一模型的进度（构件个数，阶段值）
    public static function cntByBlock($project_id,$template_id){
        $block_sql = "select block,model_id
        from pbu_info
        where project_id = '".$project_id."' and status = '0'
        group by block
        order by block";
        $command = Yii::app()->db->createCommand($block_sql);
        $block_rows = $command->queryAll();

        $pro_model = Program::model()->findByPk($project_id);
        $project_id = $pro_model->root_proid;

        $stage_list = TaskStage::queryStage($template_id);

        if (count($block_rows) > 0) {
            $r = array();
            foreach ($block_rows as $i => $j) {
                $model_id = $j['model_id'];
                $block = $j['block'];
                $key = 0;
                $rows = array();
                if(count($stage_list)>0){
                    foreach($stage_list as $stage_id => $stage_name) {
                        $sql = "SELECT
                                    count(distinct a.guid) as guid_cnt,b.stage_id
                                FROM
                                    task_record_model a
                                JOIN task_record b ON a.check_id = b.check_id
                                WHERE 
                                    a.model_id = '" . $model_id . "' and a.block = '".$block."'  and b.project_id = '" . $project_id . "' and b.status = '1' and b.stage_id = '" . $stage_id . "' ";
                        $command = Yii::app()->db->createCommand($sql);
                        $rs = $command->queryAll();

                        $rows[$key]['cnt'] = $rs[0]['guid_cnt'];
                        $rows[$key]['id'] =  $stage_id;
                        $rows[$key]['name'] = $stage_name;
                        $rows[$key]['type'] = '1';
                        $key++;
                        $task_list = TaskList::taskByStage($stage_id);
                        if(count($task_list)>0){
                            foreach ($task_list as $task_id => $task_name){
                                $sql = "SELECT
                                    count(distinct a.guid) as guid_cnt,b.task_id
                                FROM
                                    task_record_model a
                                JOIN task_record b ON a.check_id = b.check_id
                                WHERE 
                                    a.model_id = '" . $model_id . "' and a.block = '".$block."'  and b.project_id = '" . $project_id . "' and b.status = '1' and b.task_id = '" . $task_id . "' ";
                                $command = Yii::app()->db->createCommand($sql);
                                $re = $command->queryAll();

                                $rows[$key]['cnt'] = $re[0]['guid_cnt'];
                                $rows[$key]['id'] =  $task_id;
                                $rows[$key]['name'] = $task_name;
                                $rows[$key]['type'] = '2';
                                $key++;
                            }
                        }
                    }
                }
//                var_dump($rows);
//                exit;
                $r[$block] = $rows;
            }
            $data['block'] = $r;
            $data['total'] = $key-1;
        }
        return $data;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryQaList($args = array()) {

        $condition = '';
        $params = array();
        $program_id = $args['program_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $program_id = $pro_model->root_proid;
        $template_id = $args['template_id'];
        $uuid = $args['uuid'];
        $uuid_list = explode(',',$uuid);
        foreach($uuid_list as $m =>$n){
            $uuid_info = explode('_',$n);
            $model_id = $uuid_info[0];
            $version = $uuid_info[1];
            $uuid = $uuid_info[2];
            $order = " order by check_id asc";
            $select = "SELECT
            a.*,b.guid
        FROM
            task_record a
        JOIN task_record_model b ON a.check_id = b.check_id
        WHERE 
            a.project_id = '".$program_id."' and a.template_id = '".$template_id."' and b.model_id = '".$model_id."' and b.guid = '".$uuid."' and b.version = '".$version."' ";

            $sql = $select.$order;
            $command = Yii::app()->db->createCommand($sql);
            $data = $command->queryAll();
            foreach($data as $x => $y){
                $retdata[] = $y;
            }
        }

        if(count($retdata)>0){
            $t = 0;
            $r = array();
            foreach($retdata as $i => $j){
                $stage_model = TaskStage::model()->findByPk($j['stage_id']);
                $task_model = TaskList::model()->findByPk($j['task_id']);
                $component_model = RevitComponent::model()->find('pbu_id=:pbu_id', array(':pbu_id' => $j['guid']));
                $block = $component_model['block'];
                $level = $component_model['level'];
                $unit = $component_model['unit_nos'];
//                if($component_model['unit_type']){
//                    $unit.='-'.$component_model['unit_type'];
//                }
                $name = $component_model['pbu_name'];
                $stage_name = $stage_model->stage_name;
                $task_name = $task_model->task_name;
                if($j['link_check_id']){
                    $model = QaCheck::model()->findByPk($j['link_check_id']);
                    $sql = "select * from qa_form_data where check_id = '".$j['link_check_id']."'";
                    $command = Yii::app()->db->createCommand($sql);
                    $rs = $command->queryAll();
                    if(count($rs)>0){
                        foreach($rs as $x => $y){
                            $r[$t]['check_id'] = $y['check_id'];
                            $r[$t]['data_id'] = $y['data_id'];
                            $r[$t]['task_name'] = $task_name;
                            $r[$t]['stage_name'] = $stage_name;
                            $r[$t]['title'] = $model->title;
                            $r[$t]['block'] = $block;
                            $r[$t]['level'] = $level;
                            $r[$t]['unit'] = $unit;
                            $r[$t]['name'] = $name;
                            $r[$t]['apply_time'] = Utils::DateToEn($j['record_time']);
                            $r[$t]['status'] = $j['status'];
                            $r[$t]['status_txt'] = TaskModel::statusText($j['status']);
                            $r[$t]['status_css'] = TaskModel::statusCss($j['status']);
                            $form_model = QaFormData::model()->findByPk($y['data_id']);
                            $r[$t]['form_title'] = $form_model->form_title;
                            $t++;
                        }
                    }else{
                        $r[$t]['check_id'] = $j['link_check_id'];
                        $r[$t]['data_id'] = '';
                        $r[$t]['task_name'] = $task_name;
                        $r[$t]['stage_name'] = $stage_name;
                        $r[$t]['title'] = $model->title;
                        $r[$t]['block'] = $block;
                        $r[$t]['level'] = $level;
                        $r[$t]['unit'] = $unit;
                        $r[$t]['name'] = $name;
                        $r[$t]['apply_time'] = Utils::DateToEn($j['record_time']);
                        $r[$t]['status'] = $j['status'];
                        $r[$t]['status_txt'] = TaskModel::statusText($j['status']);
                        $r[$t]['status_css'] = TaskModel::statusCss($j['status']);
                        $r[$t]['form_title'] = '';
                        $t++;
                    }
                }else{
                    $r[$t]['check_id'] = '';
                    $r[$t]['data_id'] = '';
                    $r[$t]['task_name'] = $task_name;
                    $r[$t]['stage_name'] = $stage_name;
                    $r[$t]['title'] = '';
                    $r[$t]['block'] = $block;
                    $r[$t]['level'] = $level;
                    $r[$t]['unit'] = $unit;
                    $r[$t]['name'] = $name;
                    $r[$t]['apply_time'] = Utils::DateToEn($j['record_time']);
                    $r[$t]['status'] = $j['status'];
                    $r[$t]['status_txt'] = TaskModel::statusText($j['status']);
                    $r[$t]['status_css'] = TaskModel::statusCss($j['status']);
                    $r[$t]['form_title'] = '';
                    $t++;
                }
            }
        }
        return $r;
    }

    public static function QaByModel($check_id){
        $sql = "select * from task_record where link_check_id=:link_check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":link_check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $element = '';
        if(count($rows)>0){
            foreach($rows as $i => $j){
                $sql_1 = "select * from task_record_model where check_id=:check_id";
                $command = Yii::app()->db->createCommand($sql_1);
                $command->bindParam(":check_id", $j['check_id'], PDO::PARAM_STR);
                $rs = $command->queryAll();
                if(count($rs)>0){
                    foreach ($rs as $x => $y){
                        $element.=$y['name'].' ';
                    }
                }
            }
        }
        return $element;
    }

    public static function TaskByModel($check_id){
        $element = '';
        $sql = "select * from task_record_model where check_id=:check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rs = $command->queryAll();
        if(count($rs)>0){

            foreach ($rs as $x => $y){
                $element.=$y['name'].' ';
            }
        }
        return $element;
    }
}
