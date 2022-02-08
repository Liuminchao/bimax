<?php
class CertExpiryController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('dboard', 'Menu Certexpiry');
        $this->bigMenu = Yii::t('dboard', 'Menu Notification');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($type_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/certexpiry/grid';
        $t->updateDom = 'datagrid';
        if($type_id == 'S'){
            $t->set_header(Yii::t('comp_staff', 'User_name'), '', 'center');
        }else{
            $t->set_header(Yii::t('proj_project_device', 'contentHeader'), '', 'center');
        }
        $t->set_header(Yii::t('license_licensepdf', 'company'), '', 'center');
        $t->set_header(Yii::t('proj_project', 'document'), '', 'center');
        $t->set_header(Yii::t('common', 'expiry_date'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Status'), '', 'center');
//        $t->set_header(Yii::t('comp_staff', 'Action'), '', 'center');
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
            $args['program_id'] = $fields[0];
        }
        $operator_id = Yii::app()->user->id;
        $operator_model = Operator::model()->findByPk($operator_id);
        $operator_role = $operator_model->operator_role;
        if($operator_role == '01'){
            $user = Staff::userByPhone($operator_id);
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $args['user_id'] = $user_model->user_id;
        }else{
            $program_id = $args['program_id'];
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $sql = "SELECT user_id FROM bac_program_user_q where contractor_id = '$contractor_id' and program_id = '$program_id' and check_status in ('11', '20') ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $args['user_id'] = $rows[0]['user_id'];
        }
        if(!$args['type_id']){
            $args['type_id'] = 'S';
        }
        $t = $this->genDataGrid($args['type_id']);
        $list = Certexpiry::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'type_id' => $args['type_id'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('dboard', 'Menu Certexpiry');
        $this->render('list',array('program_id' => $program_id));
    }

}
