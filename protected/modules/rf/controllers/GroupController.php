<?php
class GroupController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = 'Group';
        $this->bigMenu = 'Group List';
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=rf/group/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('No.', '', '');
        $t->set_header('Group Name', '', '');
        $t->set_header('Status', '', '');
        $t->set_header(Yii::t('common','record_time'), '', '');
        $t->set_header(Yii::t('common','action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        //$fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
				//var_dump($args);
        //if(count($fields) == 1 && $fields[0] != null ) {
        //    $args['project_id'] = $fields[0];
        //}
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['status'] = '0';
        $list = RfGroup::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t,'project_id'=>$args['program_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
    		$program_id = $_REQUEST['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        if ($program_id == '') {
        	$program_id = $args['program_id'];
        }
        $this->smallHeader = 'Group Setting';
        $this->render('list',array('program_id'=>$program_id, 'args'=>$args));
    }

    /**
     * 添加
     */
    public function actionNew() {
        $this->smallHeader = 'Group';
        $project_id = $_REQUEST['project_id'];
        $this->render('method_statement',array('program_id'=>$project_id));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionMethod() {
        $this->smallHeader = 'Group User';
        $group_id = $_REQUEST['id'];
        $group_model = RfGroup::model()->findByPk($group_id);
        $group_name = $group_model->group_name;
        $project_id = $group_model->project_id;
        $detail_list = RfGroupUser::detailList($group_id);
        $this->render('method_statement',array('group_id'=>$group_id,'group_name'=>$group_name,'program_id'=>$project_id,'detail_list'=>$detail_list));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionSaveMethod() {

        $json = $_REQUEST['json_data'];
        $program_id = $_REQUEST['program_id'];
        $group_name = $_REQUEST['group_name'];
        $mode = $_REQUEST['mode'];
        if($mode == 'edit'){
            $group_id = $_REQUEST['group_id'];
            $r = RfGroupUser::EditDetail($json,$group_id,$group_name,$program_id);
        }else{
            $r = RfGroupUser::insertDetail($json,$group_name,$program_id);
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

            $r = TaskTemplate::startTemplate($id);
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

            $r = RfGroup::stopGroup($id);
        }
        echo json_encode($r);
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['rf/group/list'] = str_replace("r=rf/group/grid", "r=rf/group/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genStageGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/template/stagegrid';
        $t->updateDom = 'datagrid';
        $t->set_header('Id', '', '');
        $t->set_header('Template Name', '', '');
        $t->set_header('Stage Name', '', '');
        $t->set_header('Stage Color', '', '');
        $t->set_header('Type', '', '');
        $t->set_header('Status', '', '');
        $t->set_header(Yii::t('common','record_time'), '', '');
        $t->set_header(Yii::t('common','action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionStageGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 2 && $fields[0] != null ) {
            $args['template_id'] = $fields[0];
            $args['project_id'] = $fields[1];
        }

        $t = $this->genStageGrid();
        $this->saveUrl();
        $args['status'] = '0';
        $list = TaskStage::queryList($page, $this->pageSize, $args);
        $this->renderPartial('stage_list', array('t' => $t,'template_id'=>$args['template_id'],'project_id'=>$args['project_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionStageList() {
        $template_id = $_REQUEST['id'];
        $project_id = $_REQUEST['project_id'];
        $template_model = TaskTemplate::model()->findByPk($template_id);
        $template_name = $template_model->template_name;
        $this->smallHeader = $template_name;
        $this->render('stagelist',array('template_id'=>$template_id,'project_id'=>$project_id));
    }

    /**
     * 人员列表
     */
    public function actionUserList() {
        $group_id = $_REQUEST['group_id'];
        $group_id = substr($group_id,5);
        $r = RfGroupUser::userList($group_id);
        print_r(json_encode($r));
    }

    /**
     *总包项目区域
     */
    public function actionSetMcRegion() {
        $self_program_id = trim($_REQUEST['program_id']);
        $pro_model = Program::model()->findByPk($self_program_id);
        $program_id = $pro_model->root_proid;
        $rfa_regionlist = RfNoSet::regionList($program_id,'2');
        $rfi_regionlist = RfNoSet::regionList($program_id,'1');
        $model = new ProgramRegion('modify');
        if($program_id <> '')
            $father_model = Program::model()->findByPk($program_id);


        //$this->smallHeader = $father_model->program_name;
        $this->contentHeader = Yii::t('proj_project', 'project_region');
        $this->bigMenu = $father_model->program_name;
        $this->renderPartial('rfa_no_set', array('model'=>$model,'rfa_regionlist'=>$rfa_regionlist,'rfi_regionlist'=>$rfi_regionlist,'program_id'=> $program_id,'self_program_id'=>$self_program_id,'type'=>'1'));
    }

    /**
     * 设置项目区域
     */
    public static function actionSetRegion(){
        $rf = $_REQUEST['rf'];
        $rfa = $_REQUEST['rfa'];
        $rfi = $_REQUEST['rfi'];
        $r = RfNoSet::InsertRegion($rf,$rfa,$rfi);
        print_r(json_encode($r));
    }

}
