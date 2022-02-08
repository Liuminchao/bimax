<?php

class UsercompController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_user', 'contentHeader');
        $this->bigMenu = Yii::t('comp_user', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/usercomp/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_user', 'User_id'), '', '');
        $t->set_header(Yii::t('comp_user', 'User_name'), '', '');
        $t->set_header(Yii::t('comp_user', 'User_phone'), '', '');
        $t->set_header(Yii::t('comp_user', 'Status'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Record Time'), '', '');
        $t->set_header(Yii::t('comp_user', 'Action'), '15%', '');
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
        $args['contractor_type'] = User::CONTRACTOR_TYPE_MC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = User::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->smallHeader = Yii::t('comp_user', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 详细
     */
    public function actionDetail() {
    
    	$id = $_POST['id'];
    	$msg['status'] = true;
    
    	//$model = User::model()->findByPk($id);
        $model = User::model()->find('user_id=:user_id',array(':user_id'=>$id));

    	if ($model) {
    
    		$msg['detail'] .= "<table class='detailtab'>";
    		$msg['detail'] .= "<tr>";
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('user_id') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->user_id) ? $model->user_id : "") . "</td>";
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('user_name') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->user_name) ? $model->user_name : "") . "</td>";
    		$msg['detail'] .= "</tr>";
    		$msg['detail'] .= "<tr>";
    		
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('user_phone') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->user_phone) ? $model->user_phone : "") . "</td>";
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('team_id') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->team_id) ? $model->team_id : "") . "</td>";
    		$msg['detail'] .= "</tr>";
    		$msg['detail'] .= "<tr>";
    		$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('role_id') . "</td>";
    		$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->role_id) ? $model->role_id	 : "") . "</td>";
    		
    		$msg['detail'] .= "</tr>";
    		$msg['detail'] .= "</table>";
    		print_r(json_encode($msg));
    	}
    }
    
    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('comp_user', 'smallHeader New');
        $model = new User('create');
        $r = array();
        
        if (isset($_POST['User'])) {

            $args = $_POST['User'];
            //$args['contractor_type'] = User::CONTRACTOR_TYPE_MC;
            //var_dump($args);
            $r = User::insertUser($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['User'];
            }
        }
        $roleList = Role::roleByTypeList(User::CONTRACTOR_TYPE_MC);
        $myRoleList = array();
        $this->render('new', array('model' => $model, 'msg' => $r, 'roleList' => $roleList, 'myRoleList' => $myRoleList));
        
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('comp_user', 'smallHeader Edit');
        $model = new User('modify');
        $id = trim($_REQUEST['id']);
        
        $r = array();
        if (isset($_POST['User'])) {
            $args = $_POST['User'];
            $args['user_id'] = $id;
            $r = User::updateUser($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['User'];
            }
        }
        $model->_attributes = User::model()->find('user_id=:user_id', array(':user_id'=>$id));
        //$this->render('edit', array('model' => $model, 'msg' => $r));
        $roleList = Role::roleByTypeList(User::CONTRACTOR_TYPE_MC);
        if ($model->role_id != '') {
            $myRoleList = explode(",", $model->role_id);
            $myRoleList = array_flip($myRoleList);
        }
        $this->render('edit', array('model' => $model, 'msg' => $r, 'roleList' => $roleList, 'myRoleList' => $myRoleList));
        
    }

    /**
     * 注销
     */
    public function actionLogout() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = User::logoutUser($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['maincomp/list'] = str_replace("r=comp/usercomp/grid", "r=comp/usercomp/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
