<?php

/**
 * 总包组织机构管理
 * @author LiuXiaoyuan
 */
class McstructController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_mcstruct', 'contentHeader');
        $this->bigMenu = Yii::t('comp_mcstruct', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/mcstruct/grid';
        $t->updateDom = 'datagrid';
        //$t->set_header('Contractor', '', '');
        $t->set_header(Yii::t('comp_mcstruct', 'team_id'), '', '');
        $t->set_header(Yii::t('comp_mcstruct', 'team_name'), '', '');
        $t->set_header(Yii::t('comp_mcstruct', 'link_people'), '', '');
        $t->set_header(Yii::t('comp_mcstruct', 'link_phone'), '', '');
        $t->set_header(Yii::t('comp_mcstruct', 'status'), '', '');
        //$t->set_header(Yii::t('comp_mcstruct', 'record_time'), '', '');
        $t->set_header(Yii::t('common', 'action'), '15%', '');
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
        $args['contractor_id'] == Yii::app()->user->getState('contractor_id');
        $list = ContractorStruct::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $this->smallHeader = Yii::t('comp_mcstruct', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('comp_mcstruct', 'smallHeader New');
        $model = new ContractorStruct('create');
        $r = array();

        if (isset($_POST['ContractorStruct'])) {

            $args = $_POST['ContractorStruct'];
            $r = ContractorStruct::insertContractorStruct($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['ContractorStruct'];
            }
        }
        $this->renderPartial('new', array('model' => $model, 'msg' => $r));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('comp_mcstruct', 'smallHeader Edit');
        $model = new ContractorStruct('modify');
        $r = array();
        if (isset($_POST['ContractorStruct'])) {
            $args = $_POST['ContractorStruct'];
            $r = ContractorStruct::updateContractorStruct($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['ContractorStruct'];
            }
        }
        $model->_attributes = ContractorStruct::model()->findByPk($id);
        $this->renderPartial('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * 删除
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = ContractorStruct::deleteContractorStruct($id);
        }
        echo json_encode($r);
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['mcstruct/list'] = str_replace("r=comp/mcstruct/grid", "r=comp/mcstruct/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
