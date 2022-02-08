<?php
class TemplateController extends BaseController {

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
        $t->url = 'index.php?r=task/template/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('common','seq'), '', 'center');
//        $t->set_header('Type', '', '');
        $t->set_header('Template', '', 'center');
        $t->set_header(Yii::t('routine_type','status'), '', 'center');
        $t->set_header(Yii::t('common','created_on'), '', 'center');
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

        if(count($fields) == 1 && $fields[0] != null ) {
            $args['project_id'] = $fields[0];
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['status'] = '0';
        $list = TaskTemplate::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t,'project_id'=>$args['program_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Tracking Template';
        $this->render('list',array('program_id'=>$program_id));
    }

    /**
     * 添加
     */
    public function actionNew() {
        $this->smallHeader = 'Task Template';
        $project_id = $_REQUEST['project_id'];
        $this->render('method_statement',array('program_id'=>$project_id));
    }

    /**
     * 添加
     */
    public function actionNewStage() {
        $this->smallHeader = 'Add New Stage';
        $template_id = $_REQUEST['template_id'];
        $project_id = $_REQUEST['project_id'];
        $this->render('new_stage',array('template_id'=>$template_id,'project_id'=>$project_id));
    }

    /**
     * 编辑
     */
    public function actionEditStage() {
        $this->smallHeader = 'Edit Stage';
        $stage_id = $_REQUEST['stage_id'];
        $stage_model = TaskStage::model()->findByPk($stage_id);
        $template_id = $stage_model->template_id;
        $project_id = $stage_model->project_id;
        $this->render('new_stage',array('stage_model'=>$stage_model,'stage_id'=>$stage_id,'template_id'=>$template_id,'project_id'=>$project_id));
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

            $r = TaskTemplate::startTemplate($id);
        }
        echo json_encode($r);
    }

    /**
     * 停用
     */
    public function actionStop() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = TaskTemplate::stopTemplate($id);
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

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genStageGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/template/stagegrid';
        $t->updateDom = 'datagrid';
        $t->set_header('S/N', '', 'center');
        $t->set_header('Template Name', '', 'center');
        $t->set_header('Stage', '', 'center');
        $t->set_header('Stage Color', '', 'center');
        $t->set_header('Type', '', 'center');
//        $t->set_header('Order', '', '');
        $t->set_header('Status', '', 'center');
        $t->set_header('Dashboard', '', 'center');
        $t->set_header(Yii::t('common','created_on'), '', 'center');
        $t->set_header(Yii::t('common','action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionStageGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 2 && $fields[0] != null ) {
            $args['template_id'] = $fields[0];
            $args['project_id'] = $fields[1];
        }

        $t = $this->genStageGrid();
        $this->saveUrl();
        $args['status'] = '0';
        $list = TaskStage::queryList($page, $this->pageSize, $args);
        $this->renderPartial('stage_list', array('t' => $t,'template_id'=>$args['template_id'],'project_id'=>$args['project_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionStageList() {
        $template_id = $_REQUEST['id'];
        $project_id = $_REQUEST['project_id'];
        $template_model = TaskTemplate::model()->findByPk($template_id);
        $template_name = $template_model->template_name;
        $this->smallHeader = $template_name;
        $this->render('stagelist',array('template_id'=>$template_id,'project_id'=>$project_id));
    }

}
