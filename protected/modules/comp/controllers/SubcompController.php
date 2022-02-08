<?php

class SubcompController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('sub_contractor', 'contentHeader');
        $this->bigMenu = Yii::t('sub_contractor', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/subcomp/grid';
        $t->updateDom = 'datagrid';
        //$t->set_header(Yii::t('sub_contractor', 'Contractor_id'), '', '');
        $t->set_header(Yii::t('sub_contractor', 'Contractor_name'), '', '');
        $t->set_header(Yii::t('sub_contractor', 'link_person'), '', '');
        $t->set_header(Yii::t('sub_contractor', 'link_phone'), '', '');
        $t->set_header(Yii::t('sub_contractor', 'Status'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Record Time'), '', '');
        $t->set_header(Yii::t('sub_contractor', 'Action'), '20%', '');
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
        $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_SC;
        $list = Contractor::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->smallHeader = Yii::t('sub_contractor', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 详细
     */
    public function actionDetail() {

        $id = $_POST['id'];
        $msg['status'] = true;

        $model = Contractor::model()->findByPk($id);
        $operator = Operator::model()->find("contractor_id=:contractor_id", array("contractor_id" => $id));

        if ($model) {

            $msg['detail'] .= "<table class='detailtab'>";
            $msg['detail'] .= "<tr class='form-name'>";
            $msg['detail'] .= "<td colspan='4'>" . Yii::t('comp_contractor', 'Base Info') . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('contractor_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->contractor_id) ? $model->contractor_id : "") . "</td>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('contractor_name') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->contractor_name) ? $model->contractor_name : "") . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";

            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('link_person') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->link_person) ? $model->link_person : "") . "</td>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('link_phone') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->link_phone) ? $model->link_phone : "") . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('company_adr') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->company_adr) ? $model->company_adr : "") . "</td>";

            $msg['detail'] .= "</tr>";
             $msg['detail'] .= "<tr class='form-name'>";
            $msg['detail'] .= "<td colspan='4'>" . Yii::t('comp_contractor', 'Login Info') . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('operator_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($operator->operator_id) ? $operator->operator_id : "") . "</td>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('operator_name') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($operator->name) ? $operator->name : "") . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('operator_phone') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($operator->phone) ? $operator->phone : "") . "</td>";
            $msg['detail'] .= "</tr>";
            
            $msg['detail'] .= "</table>";
            print_r(json_encode($msg));
        }
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('sub_contractor', 'smallHeader New');
        $model = new Contractor('create');
        $r = array();

        //echo Contractor['contractor_name'];

        if (isset($_POST['Contractor'])) {

            $args = $_POST['Contractor'];
            $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_SC;
            //var_dump($args);
            $r = Contractor::insertContractor($args);

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

        $this->smallHeader = Yii::t('sub_contractor', 'smallHeader Edit');
        $model = new Contractor('modify');
        $id = trim($_REQUEST['id']);

        $r = array();
        if (isset($_POST['Contractor'])) {
            $args = $_POST['Contractor'];
            $args['contractor_id'] = $id;
            $r = Contractor::updateContractor($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Contractor'];
            }
        }
        $model->_attributes = Contractor::model()->findByPk($id);
        $this->render('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * 注销
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Contractor::logoutContractor($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 重置登录密码
     */
    public function actionResetpwd() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Operator::resetContractorPwd($id);
        }
        echo json_encode($r);
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['subcomp/list'] = str_replace("r=comp/subcomp/grid", "r=comp/subcomp/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
