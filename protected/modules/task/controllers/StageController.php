<?php
class StageController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('license_condition', 'contentHeader');
        $this->bigMenu = Yii::t('license_condition', 'bigMenu');
    }
    
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($id) {
        $model = PtwType::model()->findByPk($id);
        $contractor_id = $model->contractor_id;
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=license/condition/grid&type_id='.$id;
        $t->updateDom = 'datagrid';
        $t->set_header('Condition', '', '');
        $t->set_header('Condition Name', '', '');
        $t->set_header('Condition Name En', '', '');
        $t->set_header('Status', '', '');
        $t->set_header('Record Time', '', '');
        if($contractor_id != 0){
            $t->set_header('Action', '15%', '');
        }
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
            $id = $fields[0];
        }
        if($_GET['template_id']){
            $id = $_GET['template_id'];
        }
        $t = $this->genDataGrid($id);
        $this->saveUrl();

        $args['template_id'] = $id;
        $args['status'] = '0';
        $list = TaskStage::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t,'id'=>$id, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $id = $_REQUEST['id'];
        $this->smallHeader = 'Stage';
        $this->render('list',array('id'=>$id));
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('license_condition', 'smallHeader New');
        $model = new PtwCondition('create');
        $r = array();
        $type_id = $_REQUEST['type_id'];
        if (isset($_POST['PtwCondition'])) {

            $args = $_POST['PtwCondition'];
            $r = PtwCondition::insertPtwCondition($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['PtwCondition'];
            }
        }
        $this->render('new', array('model' => $model, 'msg' => $r, 'type_id'=>$type_id));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('license_condition', 'smallHeader Edit');
        $r = array();
        $condition_id = $_REQUEST['id'];
        $type_id = $_REQUEST['type_id'];
        $model = new PtwCondition('modify');
        if (isset($_POST['PtwCondition'])) {
            $args = $_POST['PtwCondition'];
            $args['condition_id'] = $condition_id;
            $r = PtwCondition::updatePtwCondition($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['PtwCondition'];
            }
        }
        $model->_attributes = PtwCondition::model()->findByPk($condition_id);
        $this->renderPartial('edit', array('model' => $model,'condition_id'=>$condition_id,'type_id'=>$type_id, 'msg' => $r));
    }

    /*
     * 添加
     */
    public function actionNewStage(){
        $args = $_REQUEST['TaskStage'];
        $r = TaskStage::saveStage($args);
        print_r(json_encode($r));
    }

    /*
     * 编辑
     */
    public function actionEditStage(){
        $args = $_REQUEST['TaskStage'];
        $r = TaskStage::saveStage($args);
        print_r(json_encode($r));
    }

    /*
     * 设置日期
     */
    public function actionNewCondition(){
        $args = $_REQUEST['PtwCondition'];
        $r = PtwCondition::setPtwCondition($args);
        print_r(json_encode($r));
    }

    /**
     * 删除
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = PtwCondition::deletePtwCondition($id);
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

            $r = TaskStage::startStage($id);
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

            $r = TaskStage::stopStage($id);
        }
        echo json_encode($r);
    }

    /**
     * 隐藏(Dashboard)
     */
    public function actionHideDashboard() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = TaskStage::hideDashboard($id);
        }
        echo json_encode($r);
    }

    /**
     * 显示(Dashboard)
     */
    public function actionShowDashboard() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = TaskStage::showDashboard($id);
        }
        echo json_encode($r);
    }

     /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['condition/list'] = str_replace("r=license/condition/grid", "r=license/condition/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
