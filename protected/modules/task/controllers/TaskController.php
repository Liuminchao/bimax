<?php
class TaskController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = 'Task';
        $this->bigMenu = 'Task Template';
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/task/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('Id', '', '');
        $t->set_header('Task Name', '', '');
        $t->set_header('Template Name', '', '');
        $t->set_header('Stage Name', '', '');
        $t->set_header('Status', '', '');
        $t->set_header(Yii::t('common','record_time'), '', '');
        $t->set_header(Yii::t('common','action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 3 && $fields[0] != null ) {
            $args['project_id'] = $fields[0];
            $args['template_id'] = $fields[1];
            $args['stage_id'] = $fields[2];
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['status'] = '0';
        $list = TaskList::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t,'project_id'=>$args['project_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['project_id'];
        $template_id = $_REQUEST['template_id'];
        $stage_id = $_REQUEST['stage_id'];
        $this->smallHeader = 'Task List';
        $this->render('list',array('program_id'=>$program_id,'template_id'=>$template_id,'stage_id'=>$stage_id));
    }

    /**
     * 添加
     */
    public function actionNew() {
        $this->smallHeader = 'Add Task';
        $project_id = $_REQUEST['program_id'];
        $template_id = $_REQUEST['template_id'];
        $stage_id = $_REQUEST['stage_id'];
        $this->render('method_statement',array('program_id'=>$project_id,'template_id'=>$template_id,'stage_id'=>$stage_id));
    }

    /**
     * 编辑
     */
    public function actionEdit() {
        $this->smallHeader = 'Edit Task';
        $task_id = $_REQUEST['task_id'];
        $task_model = TaskList::model()->findByPk($task_id);
        $project_id = $task_model->project_id;
        $template_id = $task_model->template_id;
        $stage_id = $task_model->stage_id;
        $this->render('method_statement',array('task_model'=>$task_model,'task_id'=>$task_id,'program_id'=>$project_id,'template_id'=>$template_id,'stage_id'=>$stage_id));
    }

    /**
     * 添加申请记录
     */
    public function actionInsert() {
        $task = $_REQUEST['task'];
        $r = TaskList::insertBasic($task);
        print_r(json_encode($r));
    }
    /**
     * 编辑申请记录
     */
    public function actionUpdate() {
        $task = $_REQUEST['task'];
        $r = TaskList::updateBasic($task);
        print_r(json_encode($r));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionMethod() {
        $this->smallHeader = 'Task Template';
        $template_id = $_REQUEST['id'];
        $task_model = TaskTemplate::model()->findByPk($template_id);
        $project_id = $task_model->project_id;
        $detail_list = TaskTemplate::detailList($template_id);
        $this->render('method_statement',array('template_id'=>$template_id,'program_id'=>$project_id,'detail_list'=>$detail_list,'task_model'=>$task_model));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionSaveMethod() {

        $json = $_REQUEST['json_data'];
        $program_id = $_REQUEST['program_id'];
        $template_name = $_REQUEST['template_name'];
//        $clt_type = $_REQUEST['clt_type'];
        $mode = $_REQUEST['mode'];
        if($mode == 'edit'){
            $template_id = $_REQUEST['template_id'];
            $r = TaskStage::EditStage($json,$template_id,$template_name,$program_id);
        }else{
            $r = TaskStage::insertStage($json,$template_name,$program_id);
        }
        echo json_encode($r);
    }


    /**
     * 启用
     */
    public function actionStart() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = TaskList::startTask($id);
        }
        echo json_encode($r);
    }

    /**
     * 停用
     */
    public function actionStopTask() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = TaskList::stopTask($id);
        }
        echo json_encode($r);
    }

    /**
     * 启用
     */
    public function actionStartTask() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = TaskList::startTask($id);
        }
        echo json_encode($r);
    }


    
     /**
     * 保存查询链接
     */
    private function saveUrl() {
        $a = Yii::app()->session['list_url'];
        $a['type/list'] = str_replace("r=task/template/grid", "r=task/template/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    private function saveRecordUrl($clt_type) {
        $a = Yii::app()->session['list_url'];
        if($clt_type == 'A'){
            $a['task/recordlist'] = str_replace("r=task/task/recordgrid", "r=task/task/recordalist", $_SERVER["QUERY_STRING"]);
        }
        if($clt_type == 'B'){
            $a['task/recordlist'] = str_replace("r=task/task/recordgrid", "r=task/task/recordblist", $_SERVER["QUERY_STRING"]);
        }
        if($clt_type == 'C'){
            $a['task/recordlist'] = str_replace("r=task/task/recordgrid", "r=task/task/recordclist", $_SERVER["QUERY_STRING"]);
        }
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 查询阶段
     */
    public function actionQueryStage() {
        $template_id = $_POST['template_id'];

        $rows = TaskStage::queryStage($template_id);

        print_r(json_encode($rows));
    }

    /**
     * 查询任务
     */
    public function actionQueryTask() {
        $template_id = $_POST['template_id'];
        $stage_id = $_POST['stage_id'];
        $rows = TaskStage::queryTask($template_id,$stage_id);

        print_r(json_encode($rows));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genRecordGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/task/recordgrid';
        $t->updateDom = 'datagrid';
//        $t->set_header('Id', '', '');
        $t->set_header('Template Name', '', 'center');
        $t->set_header('Stage Name', '', 'center');
        $t->set_header('Task', '', 'center');
//        $t->set_header('Start Date', '', '');
//        $t->set_header('End Date', '', '');
        $t->set_header('Element', '', '');
        $t->set_header('Created By', '', 'center');
//        $t->set_header('Remarks', '', '');
        $t->set_header('Status', '', 'center');
        $t->set_header('Date & Time', '', 'center');
        $t->set_header(Yii::t('common','action'), '10%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionRecordGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        if(count($fields) == 1 && $fields[0] != null ) {
//            $args['project_id'] = $fields[0];
//        }
        $t = $this->genRecordGrid();
        $this->saveRecordUrl($args['clt_type']);
        $args['status'] = '0';
        $list = TaskRecord::queryList($page, $this->pageSize, $args);
        $this->renderPartial('record_list', array('t' => $t,'project_id'=>$args['project_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionRecordCList() {
        $program_id = $_REQUEST['program_id'];
        $clt_type = 'C';
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
            if($args['type_id']){
                $clt_type = $args['clt_type'];
            }
        }
        $this->smallHeader = 'Tasks';
        $this->render('records',array('program_id'=>$program_id,'clt_type'=>$clt_type,'args'=>$args));
    }

    /**
     * 列表
     */
    public function actionRecordBList() {
        $program_id = $_REQUEST['program_id'];
        $clt_type = 'B';
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
            if($args['type_id']){
                $clt_type = $args['clt_type'];
            }
        }
        $this->smallHeader = 'Tasks';
        $this->render('records',array('program_id'=>$program_id,'clt_type'=>$clt_type,'args'=>$args));
    }

    /**
     * 列表
     */
    public function actionRecordAList() {
        $program_id = $_REQUEST['program_id'];
        $clt_type = 'A';
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
            if($args['type_id']){
                $clt_type = $args['clt_type'];
            }
        }
        $this->smallHeader = 'Tasks';
        $this->render('records',array('program_id'=>$program_id,'clt_type'=>$clt_type,'args'=>$args));
    }

    /**
     * 下载附件列表
     */
    public function actionDownloadAttachment() {
        $check_id = $_REQUEST['check_id'];
        $documen_list = QaDocument::detailList($check_id); //记录
        $this->renderPartial('download_attachment', array('check_id'=>$check_id,'documen_list'=>$documen_list));
    }

    /**
     * 详情
     */
    public function actionRecordByModel() {
//        $data = '[{"uid":"FFFF0233","name":"zhangsan"},{"uid":"FFFF0234","name":"lisi"}]';
//        $result = json_decode($data,true);
//        var_dump($result);
//        exit;
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Model Task Record';
        $this->contentHeader = 'Model Task Record';
        $this->render('model_record_list',array('program_id' => $program_id));
    }

    /**
     * 查询任务
     */
    public function actionQueryProgressModel() {
        $template_id = $_REQUEST['template_id'];
        $program_id = $_REQUEST['program_id'];
        $model_id = $_REQUEST['model_id'];
        $rows = TaskRecordModel::progressByModel($program_id,$model_id,$template_id);
        print_r(json_encode($rows));
    }

    /**
     * 查询任务
     */
    public function actionQueryStageModel() {
        $stage_id = $_REQUEST['stage_id'];
        $program_id = $_REQUEST['program_id'];
        $model_id = $_REQUEST['model_id'];
        $rows = TaskRecordModel::stageByModel($program_id,$model_id,$stage_id);
        print_r(json_encode($rows));
    }

    /**
     * 查询任务
     */
    public function actionQueryCntProgressModel() {
        $template_id = $_REQUEST['template_id'];
        $program_id = $_REQUEST['program_id'];
        $model_list = $_REQUEST['model_list'];
        $rows = TaskRecordModel::cntByModel($program_id,$model_list,$template_id);

        print_r(json_encode($rows));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genRecordUuidGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/task/recorduuidgrid';
        $t->updateDom = 'datagrid';
        $t->set_header('Id', '', '');
        $t->set_header('Task Name', '', '');
        $t->set_header('Template Name', '', '');
        $t->set_header('Stage Name', '', '');
        $t->set_header('Start Date', '', '');
        $t->set_header('End Date', '', '');
        $t->set_header('Remarks', '', '');
        $t->set_header('Status', '', '');
        $t->set_header(Yii::t('common','record_time'), '', '');
        $t->set_header(Yii::t('common','action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionRecordUuidGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 1 && $fields[0] != null ) {
            $args['project_id'] = $fields[0];
        }

        $t = $this->genRecordUuidGrid();
        $this->saveUrl();
        $args['status'] = '0';
        $list = TaskRecordModel::queryList($page, $this->pageSize, $args);
        $this->renderPartial('record_list', array('t' => $t,'project_id'=>$args['program_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 查询
     */
    public function actionWorkflow() {
        $check_id = $_REQUEST['check_id'];
        $this->renderPartial('workflow', array('check_id' => $check_id));
    }

}
