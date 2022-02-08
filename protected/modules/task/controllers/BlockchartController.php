<?php
/**
 * BlockChart管理
 * @author LiuXiaoyuan
 */
class BlockchartController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('sys_role', 'contentHeader');
        $this->bigMenu = Yii::t('sys_role', 'bigMenu Mc');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/blockchart/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('Block', '', '');
        $t->set_header('Type', '', '');
        $t->set_header('S_val', '', '');
        $t->set_header('T_val', '', '');
        $t->set_header('Record Time', '', '');
        $t->set_header('Action', '', '');
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
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = BlockChart::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $project_id = $_REQUEST['project_id'];
        $this->smallHeader = Yii::t('sys_role', 'RoleList');
        $this->render('list',array('project_id'=>$project_id));
    }

    /**
     * 添加
     */
    public function actionNew() {

        $project_id = $_REQUEST['project_id'];
        $this->smallHeader = 'Add';
        $model = new BlockChart('create');
        $r = array();

        if (isset($_POST['BlockChart'])) {

            $args = $_POST['BlockChart'];

            //    $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
            $r = BlockChart::insertBlockChart($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Role'];
            }
        }
        $this->renderPartial('new', array('model' => $model, 'msg' => $r,'project_id'=>$project_id));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('sys_role', 'RoleEdit');
        $model = new BlockChart('modify');
        $r = array();
        $id = $_REQUEST['id'];
        if (isset($_POST['BlockChart'])) {
            $args = $_POST['BlockChart'];
            $r = BlockChart::updateBlockChart($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Role'];
            }
        }
        $model->_attributes = Role::model()->findByPk($id);
        $this->renderPartial('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['mcrole/list'] = str_replace("r=comp/role/grid", "r=comp/role/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 删除
     */
    public function actionDelete() {
        $id = $_POST['id'];
        if($id == ''){
            print_r(json_encode(array()));
        }

        $rows = BlockChart::deleteInfo($id);

        print_r(json_encode($rows));
    }

    /**
     * 根据block查询pbu_type
     */
    public function actionQueryPbuType() {
        $block = $_POST['block'];
        $project_id = $_POST['project_id'];
        if($block == ''){
            print_r(json_encode(array()));
        }
        $args['block'] = $block;
        $args['project_id'] = $project_id;
        $rows = BlockChart::pbutypeList($args);

        print_r(json_encode($rows));
    }

}
