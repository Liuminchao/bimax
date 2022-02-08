<?php
/**
 * 总包角色管理
 * @author LiuXiaoyuan
 */
class McroleController extends AuthBaseController {

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
        $t->url = 'index.php?r=comp/mcrole/grid';
        $t->updateDom = 'datagrid';
        //$t->set_header(Yii::t('sys_role', 'contractor_type'), '', '');
        //$t->set_header(Yii::t('sys_role', 'role_id'), '', '');
        $t->set_header(Yii::t('sys_role', 'role_name'), '', '');
        //$t->set_header(Yii::t('sys_role', 'role_name_en'), '', '');
        $t->set_header(Yii::t('sys_role', 'team_name'), '', '');
        //$t->set_header(Yii::t('sys_role', 'team_name_en'), '', '');
        $t->set_header(Yii::t('sys_role', 'order'), '', '');
        $t->set_header(Yii::t('sys_role', 'status'), '', '');
        //$t->set_header(Yii::t('sys_role', 'record_time'), '', '');
        //$t->set_header(Yii::t('common', 'action'), '15%', '');
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
        $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = Role::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $this->smallHeader = Yii::t('sys_role', 'RoleList');
        $this->render('list');
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('sys_role', 'RoleNew');
        $model = new Role('create');
        $r = array();

        if (isset($_POST['Role'])) {

            $args = $_POST['Role'];

            $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
            $r = Role::insertRole($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Role'];
            }
        }
        $this->renderPartial('new', array('model' => $model, 'msg' => $r));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('sys_role', 'RoleEdit');
        $model = new Role('modify');
        $r = array();
        $id = $_REQUEST['id'];
        if (isset($_POST['Role'])) {
            $args = $_POST['Role'];
            $r = Role::updateRole($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Role'];
            }
        }
        $model->_attributes = Role::model()->findByPk($id);
        $this->renderPartial('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * 启用
     */
    public function actionStart() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Role::startRole($id);
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

            $r = Role::stopRole($id);
        }
        echo json_encode($r);
    }
    
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['mcrole/list'] = str_replace("r=comp/mcrole/grid", "r=comp/mcrole/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
