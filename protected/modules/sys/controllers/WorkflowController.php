<?php
/**
 * 工作流
 * @author LiuXiaoyuan
 */
class WorkflowController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('sys_workflow', 'contentHeader');
        $app = $_REQUEST['app']!='' ?$_REQUEST['app']:$_SESSION['app'];
        
        if($app == 'PTW'){
            $this->bigMenu = Yii::t('sys_workflow', 'bigMenu');
        }
        else if($app == 'TBM'){
            $this->bigMenu = Yii::t('sys_workflow', 'bigMenu2');
        }
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=sys/workflow/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('sys_workflow', 'No'));
        $t->set_header(Yii::t('sys_workflow', 'Flow Name'));
        $t->set_header(Yii::t('sys_workflow', 'Use Object'));
        $t->set_header(Yii::t('sys_workflow', 'Status'));
        $t->set_header(Yii::t('sys_operator', 'Action'), '30%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        $t = $this->genDataGrid();
        $this->saveUrl();
        if(Yii::app()->user->getState('operator_type') == Operator::TYPE_SYSTEM){
            $args['operator_id'] = Yii::app()->user->id;
        }
        else if(Yii::app()->user->getState('operator_type') == Operator::TYPE_PLATFORM){
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        }
        $args['flow_type'] = $_SESSION['app'];
//        var_dump($args['flow_type']);
//        exit;
        $app_id = 'PTW_CLOSE';

        if (Yii::app()->user->getState('role_id') == Operator::ROLE_SYSTEM) {
            $args['contractor_id'] = "";
        }
        else if (Yii::app()->user->getState('role_id') == Operator::ROLE_COMP) {
            $args['contractor_id'] = Yii::app()->user->contractor_id;
        }
        $rs = Workflow::contractorflowList(Workflow::STATUS_NORMAL,$args['contractor_id'],$app_id);
        if(empty($rs)){
            $contractor_id = Yii::app()->user->contractor_id;
            $operator_id = Yii::app()->user->id;
            Workflow::defaultWorkflow($contractor_id, $operator_id);
        }
        $list = Workflow::queryList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
//        exit;
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $_SESSION['app'] = $_REQUEST['app'];
//        var_dump($_SESSION['app']);
//        exit;
        $this->smallHeader = Yii::t('sys_workflow', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('sys_workflow', 'smallHeader New');
        $model = new Workflow('create');
        $r = array();

        if (isset($_POST['Workflow'])) {

            $args = $_POST['Workflow'];
            $args['flow_type'] = $_SESSION['app'];
            $r = Workflow::insertWorkflow($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Workflow'];
            }
        }
        $this->render('new', array('model' => $model, 'msg' => $r));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('sys_workflow', 'smallHeader Edit');
        $model = new Workflow('modify');
        $r = array();
        $id = $_REQUEST['id'];
        if (isset($_POST['Workflow'])) {
            $args = $_POST['Workflow'];
            $r = Workflow::updateWorkflow($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Workflow'];
            }
        }
        $model->_attributes = Workflow::model()->findByPk($id);
        $this->render('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * 工作流配置
     */
    public function actionSet() {

        $this->smallHeader = Yii::t('sys_workflow', 'smallHeader Set');
        $id = $_REQUEST['id'];
        $model = new Workflow('modify');
        if (isset($_POST['Workflow'])) {
            $args = $_POST['Workflow'];
//            var_dump($args);
//            exit();
            $r = WorkflowDetail::batchInsertWorkflowDetail($args);
        }

        $model->_attributes = Workflow::model()->findByPk($id);
        $step_list = WorkflowDetail::stepList($id);
        //var_dump($step_list);
        $this->render('flow', array('model' => $model, 'msg' => $r, 'step_list' => $step_list, 'step_cnt' => count($step_list)));
    }

    /**
     * 启用
     */
    public function actionStart() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Workflow::startWorkflow($id);
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

            $r = Workflow::stopWorkflow($id);
        }
        echo json_encode($r);
    }

    /**
     * 预览流程图
     */
    public function actionPreview() {
        $id = $_REQUEST['id'];
        $step_list = WorkflowDetail::stepList($id);
        $this->renderPartial('preview', array('step_list' => $step_list));
    }

    /**
     * 节点选择
     */
    public function actionStep() {
        $model = new Workflow('modify');
        $id = $_REQUEST['id'];
        $model->_attributes = Workflow::model()->findByPk($id);

        $this->renderPartial('step', array('model' => $model));
    }


    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['workflow/list'] = str_replace("r=sys/workflow/grid", "r=sys/workflow/list&app=".$_SESSION['app'], $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
