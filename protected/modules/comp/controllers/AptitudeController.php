<?php

class AptitudeController extends AuthBaseController {
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_aptitude', 'contentHeader');
        $this->bigMenu = Yii::t('comp_staff', 'bigMenu');
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/aptitude/grid';
        $t->updateDom = 'datagrid';
		$t->set_header(Yii::t('comp_staff', 'User_id'), '', '');
        $t->set_header(Yii::t('comp_staff', 'User_name'), '', '');
        $t->set_header(Yii::t('comp_staff', 'User_phone'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Work_no'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Work_pass_type'), '', '');
//        $t->set_header(Yii::t('comp_staff', 'Nation_type'), '', '');
        $t->set_header(Yii::t('comp_staff','Role_id'),'','');
        $t->set_header(Yii::t('comp_aptitude','paper'),'','');
        $t->set_header(Yii::t('comp_staff','issue_date'),'','');
        $t->set_header(Yii::t('comp_staff','expire_date'),'','');
        $t->set_header(Yii::t('comp_aptitude','Days'),'','');
//        $t->set_header(Yii::t('comp_staff', 'Status'), '', '');
        return $t;
    }
    /**
     * 查询
     */
    public function actionGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
//        exit;
        if($args['info'] !== '') {
            $info = $args['info'];
        }
	if($args['status'] == ''){
            $args['status'] = '0';
	}	
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = Staff::queryinfoList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'info'=> $info, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    public function actionList() {
        $this->smallHeader = Yii::t('comp_aptitude', 'smallHeader List');
        $this->render('list');
//        var_dump('hello world!');
//        exit;
    }
     /**
     * 保存查询链接
     */
    private function saveUrl() {
        $a = Yii::app()->session['list_url'];
        $a['aptitude/list'] = str_replace("r=comp/aptitude/grid", "r=comp/aptitude/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
}

