<?php

/**
 * 项目信息管理
 * @author LiuMinchao
 */
class Task extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //结项

    public $subcomp_name; //指派分包公司名
    public $father_model;   //上级节点类
    public $subcomp_sn; //指派分包注册编号
    public $TYPE;   //项目类型
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_task';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'task_id' => Yii::t('task', 'task_id'),
            'task_name' => Yii::t('task', 'task_name'),
            'plan_start_time' => Yii::t('task', 'plan_start_time'),
            'plan_end_time' => Yii::t('task', 'plan_end_time'),
            'plan_work_hour' => Yii::t('task', 'plan_work_hour'),
            'plan_amount' => Yii::t('task', 'plan_amount'),
            'status' => Yii::t('task', 'status'),
            'record_time' => Yii::t('task', 'record_time'),
            'amount_unit' => Yii::t('task','amount_unit'),
            'task_content' => Yii::t('task', 'task_content'),
            'upper_level_task' => Yii::t('task','upper_level_task'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Program the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('proj_project', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('proj_project', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    
    //项目角色
    public static function typeText($key = null) {
        $rs = array(
            'MC' => Yii::t('dboard', 'Menu Project MC'), //总包项目
            'SC' => Yii::t('dboard', 'Menu Project SC'), //分包项目
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-default', //停用
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
//        var_dump($args['program_id']);
//        exit;
        //Task_id
        if ($args['task_id'] != '') {
            $condition.= ( $condition == '') ? ' task_id LIKE :task_id' : ' AND task_id LIKE :task_id';
            $params['task_id'] = '%' .$args['task_id'] . '%';
        }
        
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:program_id' : ' AND program_id=:program_id';
            $params['program_id'] = $args['program_id'];
        }
//        //Program Name
//        if ($args['program_name'] != '') {
//            $condition.= ( $condition == '') ? ' program_name LIKE :program_name' : ' AND program_name LIKE :program_name';
//            $params['program_name'] = '%' . $args['program_name'] . '%';
//        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
//        //Add Operator
//        if ($args['add_operator'] != '') {
//            $condition.= ( $condition == '') ? ' add_operator=:add_operator' : ' AND add_operator=:add_operator';
//            $params['add_operator'] = $args['add_operator'];
//        }
//        //Status
//        if ($args['status'] != '') {
//            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
//            $params['status'] = $args['status'];
//        }
//        //father_proid
//        if ($args['father_proid'] != '') {
//            $condition.= ( $condition == '') ? ' father_proid=:father_proid' : ' AND father_proid=:father_proid';
//            $params['father_proid'] = $args['father_proid'];
//        }
//        //project_type
//        if ($args['project_type'] != '') {
//            if($args['project_type'] == 'MC')
//                $condition.= ( $condition == '') ? ' father_proid=0' : ' AND father_proid=0';
//            elseif($args['project_type'] == 'SC')
//                $condition.= ( $condition == '') ? ' father_proid<>0' : ' AND father_proid<>0';
//        }

        $total_num = Task::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'task_id asc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
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
        $rows = Task::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //导出项目下的所有任务
    public static function taskList($args,$start_date,$end_date) {
	//$condition = '';	
        $condition = "  program_id =  '".$args['program_id']."'";
        if($args['task_id'] != ''){
	    $condition .= " AND task_id = '".$args['task_id']."'";
        }
        if($args['status'] != '')
            $condition .= " AND status = '".$args['status']."'";
//        if($start_date != ''){
//            $condition .="AND DATE_FORMAT('".$args['plan_start_time']."','%Y%m%d') < DATE_FORMAT('".$end_date."','%Y%m%d')";
//        }
//        if($end_date != ''){
//            $condition .="AND DATE_FORMAT('".$args['plan_end_time']."','%Y%m%d') > DATE_FORMAT('".$start_date."','%Y%m%d')";
//        }
        //$condition .= "AND father_taskid <> 0";
        
        $sql = "SELECT *
                 from  bac_task
                 where ".$condition."
                 group by task_id, task_name"; 
//        var_dump($sql);
//        exit;
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
        
    }
    
    
    //查找子任务的一级任务计划时间
    public static function findTask($task_id,$father_taskid){
        $sql = "SELECT plan_start_time,plan_end_time FROM bac_task WHERE task_id='".$father_taskid."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        
        return $rows;
    }
    
    //查找任务的名称
    public static function findName($task_id){
        $sql = 'select task_name from bac_task where task_id='.$row['task_id'];
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();
        return $result;
    }
    
   //按项目查找所有一级任务 
    public static function primaryTask($program_id){
        $sql = "SELECT task_id,task_name FROM bac_task WHERE father_taskid=0 and program_id='".$program_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['task_id']] = $row['task_name'];
            }
        }
//        var_dump($rs);
//        exit;
        return $rs;
    }
    
    
    public static function queryListBySc($page, $pageSize, $args = array()) {

        $condition = '1=1';
        $params = array();


        //Program Name
        if ($args['program_name'] != '') {
            $condition.= ( $condition == '') ? ' program_name LIKE :program_name' : ' AND program_name LIKE :program_name';
            $params['program_name'] = '%' . $args['program_name'] . '%';
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id :contractor_id' : ' AND program_id in (select program_id from bac_program_contractor where contractor_id=:contractor_id)';
            $params['contractor_id'] = $args['contractor_id'];
        }
        $total_num = Program::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'program_id';
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
        $rows = Program::model()->findAll($criteria);
//        var_dump($rows);
//        exit;
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //添加日志
    public static function insertLog($model) {
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('program_id') => $model->program_id,
            $model->getAttributeLabel('task_id') => $model->task_id,
            $model->getAttributeLabel('task_name') => $model->task_name,
            $model->getAttributeLabel('plan_start_time') => $model->plan_start_time,
            $model->getAttributeLabel('plan_end_time') => $model->plan_end_time,
            $model->getAttributeLabel('plan_amount') => $model->plan_amount.$model->amount_unit,
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('task_content') => $statusList[$model->task_content],
            $model->getAttributeLabel('record_time') => $model->record_time,
            //Yii::t('proj_project', 'Assign SC') => self::subcompText(Yii::app()->db->lastInsertID),
        );
    }

    //修改日志
    public static function updateLog($model) {
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('program_id') => $model->program_id,
            $model->getAttributeLabel('task_id') => $model->task_id,
            $model->getAttributeLabel('task_name') => $model->task_name,
            $model->getAttributeLabel('plan_start_time') => $model->plan_start_time,
            $model->getAttributeLabel('plan_end_time') => $model->plan_end_time,
            $model->getAttributeLabel('plan_amount') => $model->plan_amount.$model->amount_unit,
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('task_content') => $statusList[$model->task_content],
            $model->getAttributeLabel('record_time') => $model->record_time,
            //Yii::t('proj_project', 'Assign SC') => self::subcompText($model->program_id),
        );
    }
    //查找指定项目下的子任务最大计划结束时间
    public static function findMaxtime($task_id){
        $sql = "SELECT max(plan_end_time) FROM bac_task WHERE father_taskid = '".$task_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
   
    //查找指定项目下的子任务最小计划开始时间
    public static function findMintime($task_id){
        $sql = "SELECT min(plan_start_time) FROM bac_task WHERE father_taskid = '".$task_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //添加一级任务
    public static function insertTask($args) {

        /*$exist_data = Program::model()->count('program_name=:program_name', array('program_name' => $args['program_name']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('proj_project', 'error_projname_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }*/
        if( $args['task_name']==''){
            $r['msg'] = Yii::t('task', 'error_task_name_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            $model = new Task('create');
            
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
            $model->add_operator = Yii::app()->user->contractor_id;
            
            $model->status = self::STATUS_NORMAL;
            
//            var_dump($args['program_id']);
//            exit;
            if($args['task_id'] == ""){    //一级任务
               $sql = "SELECT max(task_id) FROM bac_task WHERE father_taskid=0 and program_id='".$args['program_id']."'";
               $command = Yii::app()->db->createCommand($sql);
               $rows = $command->queryAll();
               $task_id = $rows[0]['max(task_id)'];
               //var_dump($task_id);
               
               //判断是否已经创建一级任务
               if($task_id){
                   $arr = explode("-",$task_id);
                   $arr[1] = $arr[1]+1;
                   $task_id = implode("-", $arr);
                   //var_dump($task_id);
                   $args['task_id'] = (string)$task_id;
               }else{
                   $task_id = $args['program_id'].'-'.'1';
                   $args['task_id'] = (string)$task_id;
//                   var_dump($task_id);
//                   exit;
               }
//               var_dump($task_id);
//               exit;
                $model->father_taskid = 0;
                $model->task_id = $args['task_id'];
                $model->node_level = 1;
            }
            $model->program_id = $args['program_id'];
            $model->task_name = $args['task_name'];
            //$model->plan_start_time = Utils::DateToCn($args['plan_start_time']);
            //$model->plan_end_time = Utils::DateToCn($args['plan_end_time']);
            //$model->plan_work_hour = $args['plan_work_hour'];
            //$model->plan_amount = $args['plan_amount'];
            //$model->amount_unit = $args['amount_unit'];
            $model->task_content = $args['task_content'];
            $model->status = self::STATUS_NORMAL;
            
            $result = $model->save();
            
            if ($result) {
                //var_dump($rs);
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('task', 'Add Task'), self::insertLog($model));

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    
    //添加子任务
    public static function insertSubtask($args,$plan_start_time,$plan_end_time) {

        /*$exist_data = Program::model()->count('program_name=:program_name', array('program_name' => $args['program_name']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('proj_project', 'error_projname_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }*/
        if( $args['task_name']==''){
            $r['msg'] = Yii::t('task', 'error_task_name_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if( $args['plan_start_time']==''){
            $r['msg'] = Yii::t('task', 'error_work_date_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if( $args['plan_end_time']==''){
            $r['msg'] = Yii::t('task', 'error_work_date_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {          
            $father_model = Task::model()->findByPk($args['task_id']);
            $taskid = $args['task_id'];
            $model = new Task('create');
            
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
            $model->add_operator = Yii::app()->user->contractor_id;
            
            $model->status = self::STATUS_NORMAL;
            
//            var_dump($args['program_id']);
//            exit;
            //子任务
            if($args['task_id'] != ""){ 
                
                $model->father_taskid = $args['task_id'];
                $sql = "SELECT max(task_id) FROM bac_task WHERE father_taskid='".$args['task_id']."' and program_id='".$args['program_id']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                $task_id = $rows[0]['max(task_id)'];
//                var_dump($task_id);
//                exit;
                //判断是否已经创建子任务
                if($task_id){
                   $arr = explode("-",$task_id);
                   $arr[2] = $arr[2]+1;
                   $task_id = implode("-", $arr);
                   //var_dump($task_id);
                   $args['task_id'] = (string)$task_id;
                    
                }else{
                   $task_id = $args['task_id'].'-'.'1';
                   $args['task_id'] = (string)$task_id;
                }
                
                $model->task_id = $args['task_id'];
                $model->node_level = 1;
            }
           
            $model->program_id = $args['program_id'];
            $model->task_name = $args['task_name'];
            $model->plan_start_time = Utils::DateToCn($args['plan_start_time']);
            $model->plan_end_time = Utils::DateToCn($args['plan_end_time']);
            $model->plan_work_hour = $args['plan_work_hour'];
            $model->plan_amount = $args['plan_amount'];
            $model->amount_unit = $args['amount_unit'];
            $model->task_content = $args['task_content'];
            $model->status = self::STATUS_NORMAL;
            
            $result = $model->save();
            //通过开始结束日期得到计划工时
//            $plan_start = strtotime($args['plan_start_time']);
//            $plan_end = strtotime($args['plan_end_time']);
//            $c = $plan_end - $plan_start;
//            $h=ceil($c/3600);

             //父节点的孩子数量+1 添加计划时间
            $max = self::findMaxtime($taskid);
            $min = self::findMintime($taskid);
            if($father_model->plan_start_time){
                $start_time = strtotime($father_model->plan_start_time);
            }
            if($father_model->plan_end_time){
                $end_time = strtotime($father_model->plan_end_time);
            }
//            var_dump($father_taskid);
//            var_dump($min[0]['min(plan_start_time)']);
//            var_dump($min[0]['max(plan_end_time)']);
//            exit;
            $min_start_time = strtotime($min[0]['min(plan_start_time)']);
            $max_end_time = strtotime($max[0]['max(plan_end_time)']);
            if($start_time>$min_start_time || $start_time==''){
                $father_model->plan_start_time = $min[0]['min(plan_start_time)'];
            }
            if($end_time<$max_end_time || $end_time==''){
                $father_model->plan_end_time = $max[0]['max(plan_end_time)'];
            }
            $father_model->plan_work_hour = $father_model->plan_work_hour+$args['plan_work_hour'];
            $father_model->child_cnt = $father_model->child_cnt +1;
            $father_model->save();
            
            
            if ($result) {
                //var_dump($rs);
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('task', 'Add Task'), self::insertLog($model));

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    
    //修改一级任务
    public static function updateTask($args) {
//        var_dump(strtotime($args['plan_start_time']));
//        var_dump($plan_start_time);
//        exit;
        //填写的开始日期必须大于查出来的开始日期
//        if( $plan_start_time!="" && strtotime($args['plan_start_time'])<$plan_start_time){
//            $r['msg'] = Yii::t('task', 'error_date_early');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }    
//        //填写的结束日期必须小于查出来的结束日期
//        if($plan_start_time!="" && strtotime($args['plan_end_time'])>$plan_end_time){
//            $r['msg'] = Yii::t('task', 'error_date_late');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        
        $model = Task::model()->findByPk($args['task_id']);
        
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->task_name = $args['task_name'];
           // $model->plan_start_time = Utils::DateToCn($args['plan_start_time']);
           // $model->plan_end_time = Utils::DateToCn($args['plan_end_time']);
            //$model->plan_work_hour = $args['plan_work_hour'];
            //$model->plan_amount = $args['plan_amount'];
            //$model->amount_unit = $args['amount_unit'];
            $model->task_content = $args['task_content'];
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('task', 'Edit Task'),self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
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

    
    //修改子任务
    public static function updateSubtask($args) {
//        var_dump(strtotime($args['plan_start_time']));
//        var_dump($plan_start_time);
//        exit;
        if( $args['task_name']==''){
            $r['msg'] = Yii::t('task', 'error_task_name_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if( $args['plan_start_time']==''){
            $r['msg'] = Yii::t('task', 'error_work_date_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if( $args['plan_end_time']==''){
            $r['msg'] = Yii::t('task', 'error_work_date_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = Task::model()->findByPk($args['task_id']);
        //计算修改后计划工时的变化
        $hour = $args['plan_work_hour'] - $model->plan_work_hour;
//        var_dump($hour);
//        exit;
        $father_taskid = $model->father_taskid;

        $father_model = Task::model()->findByPk($father_taskid);
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->task_name = $args['task_name'];
            $model->plan_start_time = Utils::DateToCn($args['plan_start_time']);
            $model->plan_end_time = Utils::DateToCn($args['plan_end_time']);
            $model->plan_work_hour = $args['plan_work_hour'];
            $model->plan_amount = $args['plan_amount'];
            $model->amount_unit = $args['amount_unit'];
            $model->task_content = $args['task_content'];
            $result = $model->save();
            
            
             //父节点的孩子数量+1 添加计划时间
            $max = self::findMaxtime($father_taskid);
            $min = self::findMintime($father_taskid);
            if($father_model->plan_start_time){
                $start_time = strtotime($father_model->plan_start_time);
            }
            if($father_model->plan_end_time){
                $end_time = strtotime($father_model->plan_end_time);
            }
            $min_start_time = strtotime($min[0]['min(plan_start_time)']);
            $max_end_time = strtotime($max[0]['max(plan_end_time)']);
            
            if($start_time>$min_start_time || $start_time==''){
                $father_model->plan_start_time = $min[0]['min(plan_start_time)'];
            }
            if($end_time<$max_end_time || $end_time==''){
                $father_model->plan_end_time = $max[0]['max(plan_end_time)'];
            }
            $father_model->plan_work_hour = $father_model->plan_work_hour+$hour;
            $father_model->save();
            
            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('task', 'Edit Task'),self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
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
    public static function startProgram($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Program::model()->findByPk($id);

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
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Start Proj'), self::updateLog($model));
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

    //停用：结项
    public static function stopProgram($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = Program::model()->findByPk($id);
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $cnt = Program::model()->count('status='.self::STATUS_NORMAL.' and father_proid='.$id);
        //判断其下是否有未结项的子项目，有的话不能结项
        if ($cnt <> 0) {
            $r['msg'] = Yii::t('proj_project', 'error_subproj_is_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {

            $model->status = self::STATUS_STOP;
            $result = $model->save();

            if ($result) {
                //项目成员打结项标记：
                ProgramUser::model()->updateAll(array('status'=>'01'), 'program_id=:program_id', array(':program_id'=>$id));
            
                if($model->father_proid == 0){  //总包项目
                    //faceset删除
                    $faceModel = new Face();
                    $rs = $faceModel->FacesetDelete($model->faceset_id);
                }
                else{   //分包项目
                    //faceset中删掉分包项目成员
                    $rows = ProgramUser::model()->findAll(array(
                        'select'=>array('user_id'),
                        'condition' => 'program_id=:program_id',
                        'params' => array(':program_id'=>$id),
                    ));
                    $del_list = array();
                    foreach(($rows) as $key => $row){
                        $del_list[] = $row['user_id'];
                    }
                    FACE::FacesetEditFace($model->faceset_id, $del_list);
                }
                
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Stop Proj'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_stop');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_stop');
                $r['status'] = -11;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    
        //删除
    public static function deleteTask($father_taskid,$task_id) {

//        if ($id == '') {
//            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        
        $model = Program::model()->findByPk($id);
        //判断是否有二级项目
        $sql = "select task_id from bac_task where father_taskid = '".$task_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;
        try {
            if($father_taskid==0 && !empty($rows)){
                $rs=self::model()->deleteByPk($task_id);
                //删除任务下的成员
                foreach($rows as $key=>$r){
                    $sql = "delete from bac_task_user where task_id = '".$r[task_id]."'";
                    $command = Yii::app()->db->createCommand($sql);
                    $re = $command->execute();
                }
                //删除一级任务下面的子任务
                $sql = "delete from bac_task where father_taskid = '".$task_id."'";
                $command = Yii::app()->db->createCommand($sql);
                $re = $command->execute();
                if($re){
                    $r['msg'] = Yii::t('common', 'success_delete');
                    $r['status'] = 1;
                    $r['refresh'] = true;
                }else{
                    $r['msg'] = Yii::t('common', 'error_delete');
                    $r['status'] = -11;
                    $r['refresh'] = false;
                }
            }else{
                $sql = "delete from bac_task_user where task_id = '".$task_id."'";
                $command = Yii::app()->db->createCommand($sql);
                $re = $command->execute();
                
                $rs=self::model()->deleteByPk($task_id);
                if($rs){
                    $r['msg'] = Yii::t('common', 'success_delete');
                    $r['status'] = 1;
                    $r['refresh'] = true;
                }else{
                    $r['msg'] = Yii::t('common', 'error_delete');
                    $r['status'] = -11;
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
    
    /** 得到承包商的项目id
     * @return type
     */
    public static function getProgramId() {

        $program_id = '';

        $sql = "SELECT program_id FROM bac_program WHERE contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $program_id .= $row['program_id'] . ',';
            }
        }
        if ($program_id != '')
            $program_id = substr($program_id, 0, strlen($program_id) - 1);

        return $program_id;
    }
     /** 得到承包商的总包项目id
     * @return type
     */
    public static function getMProgramId() {

        $program_id = '';

        $sql = "SELECT program_id FROM bac_program WHERE contractor_id=:contractor_id and father_proid=0";
        $command = Yii::app()->db->createCommand($sql);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $program_id .= $row['program_id'] . ',';
            }
        }
        if ($program_id != '')
            $program_id = substr($program_id, 0, strlen($program_id) - 1);

        return $program_id;
    }
    
    /**
     * 所有项目列表
     * @return type
     */
    public static function programList($args=array()) {
        
        $condition = '';
        if($args['contractor_id'] != ''){
            $condition .= ($condition!=''?' AND ':'')."contractor_id='".$args['contractor_id']."'";
        }
        if($args['project_type'] == 'MC'){
            $condition .= ($condition!=''?' AND ':'')."father_proid=0";
        }
       
         if($args['status'] == ''){
            $condition .= ($condition!=''?' AND ':'')."status=00";
        }
        $list = array();
        $sql = "SELECT program_id,program_name FROM bac_program ";
        if($condition != '')
            $sql .= 'where '.$condition;
        $sql .= " order by status, program_id"; 
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $list[trim($row['program_id'])] = trim($row['program_name']);
            }
        }
        return $list;
    }

}
