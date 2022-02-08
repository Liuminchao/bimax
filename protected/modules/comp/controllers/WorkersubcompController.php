<?php
class WorkersubcompController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_worker', 'contentHeader');
        $this->bigMenu = Yii::t('comp_worker', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/workersubcomp/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_worker', 'Worker_id'), '', '');
        $t->set_header(Yii::t('comp_worker', 'Worker_name'), '', '');
        $t->set_header(Yii::t('comp_worker', 'Worker_phone'), '', '');
        $t->set_header(Yii::t('comp_worker', 'Status'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Record Time'), '', '');
        $t->set_header(Yii::t('comp_worker', 'Action'), '15%', '');
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
        $args['contractor_type'] =Worker::CONTRACTOR_TYPE_SC;
         $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list =Worker::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->smallHeader = Yii::t('comp_worker', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 详细
     */
    public function actionDetail() {
    
    	$id = $_POST['id'];
    	$msg['status'] = true;
    
    	$model =Worker::model()->findByPk($id);
    
    	if ($model) {
    
    		$msg['detail'] .= "<table class='detailtab'>";
    		$msg['detail'] .= "<tr>";
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('worker_id') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->worker_id) ? $model->worker_id : "") . "</td>";
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('worker_name') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->worker_name) ? $model->worker_name : "") . "</td>";
    		$msg['detail'] .= "</tr>";
    		$msg['detail'] .= "<tr>";
    		
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('worker_phone') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->worker_phone) ? $model->worker_phone : "") . "</td>";
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('wp_no') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->wp_no) ? $model->wp_no : "") . "</td>";
    		$msg['detail'] .= "</tr>";
    		$msg['detail'] .= "</table>";
    		print_r(json_encode($msg));
    	}
    }
    
    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('comp_worker', 'smallHeader New');
        $model = new Worker('create');
        $r = array();
        
        if (isset($_POST['Worker'])) {

            $args = $_POST['Worker'];
            //$args['contractor_type'] =Worker::CONTRACTOR_TYPE_SC;
            //var_dump($args);
            $r =Worker::insertWorker($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Operator'];
            }
        }
        $this->render('new', array('model' => $model, 'msg' => $r));
        
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('comp_worker', 'smallHeader Edit');
        $model = new Worker('modify');
        $id = trim($_REQUEST['id']);
        
        $r = array();
        if (isset($_POST['Worker'])) {
            $args = $_POST['Worker'];
            $args['worker_id'] = $id;
            $r =Worker::updateWorker($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Worker'];
            }
        }
        $model->_attributes =Worker::model()->findByPk($id);
        $this->render('edit', array('model' => $model, 'msg' => $r)); 
    }

    /**
     * 注销
     */
    public function actionLogout() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r =Worker::logoutWorker($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['workersubcomp/list'] = str_replace("r=comp/workersubcomp/grid", "r=comp/workersubcomp/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
