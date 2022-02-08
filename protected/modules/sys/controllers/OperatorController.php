<?php

/**
 * 操作员管理
 * @author LiuXiaoyuan
 */
class OperatorController extends AuthBaseController {

    const CONTRACTOR_PREFIX = 'CT';   //承包商编号前缀
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('sys_operator', 'contentHeader');
        $this->bigMenu = Yii::t('sys_operator', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=sys/operator/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('sys_operator', 'Operator'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Name'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Phone'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Email'), '', '');
        //$t->set_header(Yii::t('sys_operator', 'Operator Type'), '', '');
        //$t->set_header(Yii::t('sys_operator', 'Last Login IP'), '', '');
        //$t->set_header(Yii::t('sys_operator', 'Last Login Time'), '', '');
        //$t->set_header(Yii::t('sys_operator', 'Unreg Time'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Reg Time'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Status'), '', '');
        //$t->set_header(Yii::t('sys_operator', 'Record Time'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Action'), '20%', '');
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
        $args['operator_type'] = Operator::TYPE_SYSTEM;
        $list = Operator::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $this->smallHeader = Yii::t('sys_operator', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('sys_operator', 'smallHeader New');
        $model = new Operator('create');
        $r = array();

        if (isset($_POST['Operator'])) {

            $args = $_POST['Operator'];
            $args['operator_type'] = Operator::TYPE_SYSTEM;
            $args['role_id'] = Operator::ROLE_SYSTEM;
            $r = Operator::insertOperator($args);

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

        $this->smallHeader = Yii::t('sys_operator', 'smallHeader Edit');
        $model = new Operator('modify');
        $id = trim($_REQUEST['id']);
        $r = array();
        if (isset($_POST['Operator'])) {
            $args = $_POST['Operator'];
            $r = Operator::updateOperator($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Operator'];
            }
        }
        $model->_attributes = Operator::model()->findByPk($id);
        $this->render('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * 注销
     */
    public function actionLogout() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Operator::logoutOperator($id);
        }
        echo json_encode($r);
    }

    /**
     * 重置登录密码
     */
    public function actionResetpwd() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Operator::resetPwd($id);
        }
        echo json_encode($r);
    }
    /**
     * 详细
     */
    public function actionDetail() {

        $id = $_POST['id'];
        $msg['status'] = true;

        $model = Operator::model()->findByPk($id);

        if ($model) {

            $msg['detail'] .= "<table class='detailtab'>";
            if ($model->contractor_id != '') {
                $comp = Contractor::model()->findByPk($model->contractor_id);
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<td class='tname-2'>" . Yii::t('sys_operator', 'contractor_name') . "</td>";
                $msg['detail'] .= "<td class='tvalue-4'>" . (isset($comp->contractor_name) ? $comp->contractor_name : "") . "</td>";
                $msg['detail'] .= "</tr>";
            }
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('last_login_ip') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->last_login_ip) ? $model->last_login_ip : "") . "</td>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('last_login_time') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->last_login_time) ? $model->last_login_time : "") . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('language') . "</td>";
            $lang = Operator::langText();
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->last_language) ? $lang[$model->last_language] : "") . "</td>";

            if ($model->status == Operator::STATUS_DISABLE) {
                $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('unreg_time') . "</td>";
                $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->unreg_time) ? $model->unreg_time : "") . "</td>";
            }

            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "</table>";
        } else {
            $msg['status'] = false;
            $msg['detail'] = Yii::t('common', 'error_info');
        }
        print_r(json_encode($msg));
    }

    /**
     * 修改个人信息
     */
    public function actionPedit() {
        
        $model = new Operator('modify');
        $rs = new Operator('modify');
        $id = Yii::app()->user->id;
        $ra = Operator::model()->findByPk($id);
        $operator_type = $ra->operator_type;
        if($operator_type<>00){
            $contractor_id = Yii::app()->user->contractor_id;
            $seq_id = sprintf('%05s', $contractor_id);
            $operator_id = self::CONTRACTOR_PREFIX . $seq_id;
            $rs->_attributes = Operator::model()->findByPk($operator_id);
        }
//        var_dump($rs->_attributes);
//        exit;
        $r = array();
        $model->_attributes = Operator::model()->findByPk($id);
        $this->renderPartial('pedit', array('model' => $model,'id'=> $id, 'rs' => $rs, 'msg' => $r));
    }

    /**
     * 修改个人基本信息
     */
    public function actionPupdate() {
        $r = array();

        if (isset($_REQUEST['Operator'])) {
            $args = $_REQUEST['Operator'];
            $r = Operator::updateOperatorInfo($args);
            $class = Utils::getMessageType($r['status']);
            $r['class'] = $class[0];
        }

        print_r(json_encode($r));
    }

    /**
     * 修改登录密码
     */
    public function actionPwd() {
        $r = array();
        if (isset($_REQUEST['Operator'])) {
            $args = $_REQUEST['Operator'];
            $r = Operator::updateOperatorPwd($args);
            $class = Utils::getMessageType($r['status']);
            $r['class'] = $class[0];
        }
        print_r(json_encode($r));
    }

    /**
     * 查找承包商编号
     */
    public function actionSearch() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $search_key = $_GET['search_key'];
        $args['contractor_name'] = $search_key;
        $list = Contractor::queryList($page, 10, $args);
        $this->renderPartial('search_list', array('rows' => $list['rows']));
    }
    
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['operator/list'] = str_replace("r=sys/operator/grid", "r=sys/operator/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
