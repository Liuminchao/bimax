<?php

class TaskController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'tasklist';
    public $contentHeader = "";
    public $bigMenu = "";
    public $ptype;
    
    public function init() {
        parent::init();
       // $program_id = $_REQUEST['program_id']!='' ?$_REQUEST['program_id']:$_SESSION['program_id'];
        $this->contentHeader = Yii::t('proj_project', 'contentHeader');
        $this->bigMenu = Yii::t('proj_project', 'bigMenu');
        
        $this->ptype = $_GET['ptype'];
        
        if($_GET['ptype'] == 'MC'){
            $this->bigMenu = Yii::t('dboard', 'Menu Project MC');
        }
        elseif($_GET['ptype'] == 'SC'){
            $this->bigMenu = Yii::t('dboard', 'Menu Project SC');
        }
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $program_id = $_REQUEST['program_id'];
//        var_dump($te);
//        exit;
        $t->url = 'index.php?r=proj/task/grid&ptype='.Yii::app()->session['project_type'].'&program_id='.$program_id;
        $ptype = Yii::app()->session['project_type'];
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('task', 'task_id'), '', '');
        //$t->set_header(Yii::t('task', 'program_id'), '', '');
        $t->set_header(Yii::t('task', 'task_name'), '', '');
        $t->set_header(Yii::t('task', 'status'), '', '');
        $t->set_header(Yii::t('task', 'record_time'), '', '');
        //$t->set_header(Yii::t('task', 'plan_amount'), '', '');
        $t->set_header(Yii::t('task', 'plan_amount'), '', '');
        $t->set_header(Yii::t('task', 'plan_start_time'), '', '');
        $t->set_header(Yii::t('task', 'plan_end_time'), '', '');
        $t->set_header(Yii::t('task', 'plan_work_hour'), '', '');
        $t->set_header(Yii::t('task', 'plan_rate'), '', '');
        $t->set_header(Yii::t('common', 'action'), '23%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if($args['program_id'] == ''){
            $args['program_id'] = $_GET['program_id'];
            //$args['program_id'] = $_SESSION['program_id'];
            
        }
        //$args['program_id'] = $_SESSION['program_id'];
        $program_id = $args['program_id'];
        //unset($_SESSION['program_id']);
//        var_dump($_GET['program_id']);
//        exit;
        if($args['project_type'] == '')
            $args['project_type'] = Yii::app()->session['project_type'];
        
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($_GET['program_id']);
//        exit;
        $list = Task::queryList($page, $this->pageSize, $args);
        
        $this->renderPartial('_tasklist', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'],'program_id'=> $program_id, 'curpage' => $list['page_num']));
    }

 

    /**
     * 添加一级任务
     */
    public function actionNew() {

        
        $program_id = $_REQUEST['program_id'];
//        var_dump($program_id);
//        exit;
        $this->smallHeader = Yii::t('proj_project', 'smallHeader New');
        $model = new Task('create');
        $r = array();
        $father_model = Task::primaryTask($program_id);

//
//        $this->render('new', array('model' => $model, 'msg' => $r));
        $this->renderPartial('new', array('program_id' => $program_id,'model' => $model, 'msg' => $r));
    }
    
    public function actionTnew() {
        
        $this->smallHeader = Yii::t('proj_project', 'smallHeader New');
        $model = new Task('create');
        $r = array();
        $father_model = Task::primaryTask($program_id);
        if (isset($_REQUEST['Task'])) {
//            var_dump($_REQUEST['Task']);
//            exit;
            $args = $_REQUEST['Task'];
//            var_dump($args);
//            exit;
            //$args['TYPE'] = 'MC';
            //$args['program_id'] = $program_id;
            $args['father_model'] = $father_model;
            if($args['task_id']){
                $task_model = Task::model()->findByPk($args['task_id']);
                $plan_start_time = strtotime($task_model->plan_start_time);
                $plan_end_time = strtotime($task_model->plan_end_time);
            }
            $r = Task::insertTask($args,$plan_start_time,$plan_end_time);
            }
        //print_r($r);
        print_r(json_encode($r));
    }
    /**
     * 添加子任务
     */
    public function actionNewSubtask() {

        
        $program_id = $_REQUEST['program_id'];
        $task_id = $_REQUEST['task_id'];
//        var_dump($task_id);
//        exit;
        $this->smallHeader = Yii::t('task', 'smallHeader Subtask New');
        $model = new Task('create');
        $r = array();
        $father_model = Task::primaryTask($program_id);

//
//        $this->render('new', array('model' => $model, 'msg' => $r));
        $this->renderPartial('newsubtask', array('program_id' => $program_id,'task_id' => $task_id, 'model' => $model, 'msg' => $r));
    }
    
     public function actionSnew() {
        
        $this->smallHeader = Yii::t('task', 'smallHeader Subtask New');
        $model = new Task('create');
        $r = array();
//        var_dump($program_id);
//        exit;
        
        if (isset($_REQUEST['Task'])) {
//            var_dump($_REQUEST['Task']);
//            exit;
            $args = $_REQUEST['Task'];
            $father_model = Task::primaryTask($args['program_id']);
//            var_dump($args);
//            exit;
            //$args['TYPE'] = 'MC';
            //$args['program_id'] = $program_id;
            $args['father_model'] = $father_model;
            if($args['task_id']){
                $task_model = Task::model()->findByPk($args['task_id']);
                $plan_start_time = strtotime($task_model->plan_start_time);
                $plan_end_time = strtotime($task_model->plan_end_time);
            }
            $r = Task::insertSubtask($args,$plan_start_time,$plan_end_time);
            }
        //print_r($r);
        print_r(json_encode($r));
    }
    /**
     * 修改一级任务
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('proj_project', 'smallHeader Edit');
        $model = new Task('modify');
        $r = array();
        $task_id = $_REQUEST['task_id'];
        $father_taskid = $_REQUEST['father_taskid'];
        $model->_attributes = Task::model()->findByPk($task_id);
//        var_dump($father_taskid);
//        exit;
        $this->renderPartial('edit', array('model' => $model,'task_id'=> $task_id,'father_taskid'=> $father_taskid, 'msg' => $r));
    }
    
    public function actionTedit() {
        
        $this->smallHeader = Yii::t('proj_project', 'smallHeader Edit');
        $model = new Task('modify');
        $r = array();
        $task_id = $_REQUEST['task_id'];
        $father_taskid = $_REQUEST['father_taskid'];
//        var_dump($task_id);
//        var_dump($father_taskid);
//        exit;
        if (isset($_REQUEST['Task'])) {
            
            $args = $_REQUEST['Task'];
            $args['task_id'] = $task_id;
            
            if($father_taskid<>0){
                $rs = Task::findTask($task_id, $father_taskid);
//                var_dump($rs[0]['plan_start_time']);
//                exit;
            }

            //$plan_start_time = strtotime($rs[0]['plan_start_time']);
            //$plan_end_time = strtotime($rs[0]['plan_end_time']);
            
            $r = Task::updateTask($args);
           
        }
        print_r(json_encode($r));
    }
    
    /**
     * 修改子任务
     */
    public function actionEditSubtask() {

        $this->smallHeader = Yii::t('proj_project', 'smallHeader Edit');
        $model = new Task('modify');
        $r = array();
        $task_id = $_REQUEST['task_id'];
        $father_taskid = $_REQUEST['father_taskid'];
        $model->_attributes = Task::model()->findByPk($task_id);
//        var_dump($father_taskid);
//        exit;
        $this->renderPartial('editsubtask', array('model' => $model,'task_id'=> $task_id,'father_taskid'=> $father_taskid, 'msg' => $r));
    }
    
    public function actionSedit() {
        
        $this->smallHeader = Yii::t('proj_project', 'smallHeader Edit');
        $model = new Task('modify');
        $r = array();
        $arr = $_REQUEST['Task'];
        $task_id = $arr['task_id'];
        $father_taskid = $arr['father_taskid'];
        
        if (isset($_REQUEST['Task'])) {
            
            $args = $_REQUEST['Task'];
            $args['task_id'] = $task_id;
            
            if($father_taskid<>0){
                $rs = Task::findTask($task_id, $father_taskid);
//                var_dump($rs[0]['plan_start_time']);
//                exit;
            }

            //$plan_start_time = strtotime($rs[0]['plan_start_time']);
            //$plan_end_time = strtotime($rs[0]['plan_end_time']);
            
            $r = Task::updateSubtask($args);
           
        }
        print_r(json_encode($r));
    }
    
    
    /**
     * 
     * 任务列表
     */
    
    public function actionTaskList() {
       $program_id = $_GET['program_id'];
       if($program_id==''){
            $args = $_GET['q'];
            $program_id = $args['program_id'];
       }
//       var_dump($program_id);
//       exit;
//       if($father_proid <> '')
//            $father_model = Program::model()->findByPk($father_proid);
       if($program_id <> '')
            $father_model = Program::model()->findByPk($program_id);
       $this->smallHeader = $father_model->program_name;
       $this->contentHeader = Yii::t('proj_project', 'sub contentHeader');
       $this->bigMenu = $father_model->program_name;
       
       $this->render('tasklist', array('father_model'=>$father_model, 'program_id'=>$program_id));
    }
    
    /**
     * 设置员工
     */
    
    public function actionSet() {
        $program_id = $_GET['program_id'];
        $task_id = $_GET['task_id'];
//        $plan_start_time = $_GET['plan_start_time'];
//        $plan_end_time = $_GET['plan_end_time'];

        
        $this->smallHeader = Yii::t('task', 'smallHeader Set');
        $model = new TaskUser('create');
        $r = array();
       
        $staff_List = (array)TaskUser::userListByRole($program_id);
        //获取当前日期
        //$date= date('Y-m-d',time());
        
        $select_List = (array)TaskUser::myUserList($task_id);
//        var_dump($select_List);
//        exit;
        $this->renderPartial('set', array('model' => $model, 'msg' => $r, 'staff_List' => $staff_List, 'select_List' => $select_List,'program_id' => $program_id,'task_id' => $task_id));
    }
    
    public function actionTset() {
        
        $program_id = $_REQUEST['program_id'];
        $task_id = $_REQUEST['task_id'];
        //$plan_start_time = $_REQUEST['plan_start_time'];
        //$plan_end_time = $_REQUEST['plan_end_time'];
         
        $this->smallHeader = $task_model->task_name;
       
        $model = new TaskUser('modify');
        
        $r = array();
        if (isset($_REQUEST['TaskUser'])) {
            //var_dump($_POST['Program']);
            $args = $_REQUEST['TaskUser'];
//            var_dump($args);
//            exit;
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');

            //获取时间段
            //$tmp = TaskUser::changeDay($args);

            $r = TaskUser::updateTaskUser($args);
            
        }
        
        print_r(json_encode($r));
    }
    
    /**
     * 删除
     */
    public function actionDelete() {
        $father_taskid = trim($_REQUEST['father_taskid']);
        $task_id = trim($_REQUEST['task_id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Task::deleteTask($father_taskid,$task_id);
        }
        echo json_encode($r);
    }
    
    /**
     * 详细
     */
    public function actionDetail() {

        $task_id = $_POST['id'];

        $model = Task::model()->findByPk($task_id);
        //var_dump($model);
//        if($model->father_proid == 0){
//            $father_model = $model;
//            unset($model);
//        }
//        else
//            $father_model = Program::model()->findByPk($model->father_proid);
        
        $msg = array();
        if ($model) {
            
            $status_list = Task::statusText();   //状态
            $compList = Contractor::compAllList(); //所有承包商
            
            $msg['detail'] .= "<table class='detailtab'>";
            $msg['detail'] .= "<tr class='form-name'>";
            if($model->father_taskid){
                $msg['detail'] .= "<td colspan='4'>".Yii::t('task', 'second_task')."</td>";
            }else{
                $msg['detail'] .= "<td colspan='4'>".Yii::t('task', 'primary_task')."</td>";
            }
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('task', 'task_name')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . $model->task_name ."</td>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('task', 'task_id')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . $model->task_id ."</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";          
             $msg['detail'] .= "<td class='tname-2'>".Yii::t('task', 'record_time')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . Utils::DateToEn($model->record_time) . "</td>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('task', 'status')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . $status_list[$model->status] ."</td>";
            $msg['detail'] .= "</tr>";            
            $msg['status'] = true;
            $msg['detail'] .= "</table>";
        }
        else {
            $msg['status'] = false;
            $msg['detail'] = Yii::t('common', 'The request failed');
        }
        print_r(json_encode($msg));
        
    }
    
    /**
     * 保存查询链接
     */
    private function saveUrl() {
//        var_dump($_SERVER["QUERY_STRING"]);
//        exit;
        $a = Yii::app()->session['list_url'];
        $a['project/list'] = str_replace("r=proj/task/grid", "r=proj/task/tasklist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
//                var_dump($a);
//        exit;
    }

}
