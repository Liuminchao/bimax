<?php

class ScheduleController extends AuthBaseController {
    public $defaultAction = 'list';
    public $gridId_1 = 'example1';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";
    public $ptype;
    public $title_rows = 1;
    //public $per_read_cnt = 5;

    public function init() {
        parent::init();
        $this->contentHeader = 'Defect Type';
        $this->bigMenu = 'Defect';

        $this->ptype = $_GET['ptype'];
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genMasterDataOneGrid($args) {

        $program_id = $args['project_id'];
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/schedule/masteronegrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Activities', '', '');
        $t->set_header('Block', '', '');
        $t->set_header('Level', '', '');
        $t->set_header('Part', '', '');
        $t->set_header('Plan End Date', '', '');
        $t->set_header('Actual End Date', '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionMasterOneGrid() {
        $fields = func_get_args();
        if($_REQUEST['program_id']){
            $fields[0] = $_REQUEST['program_id'];
        }

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
        $t = $this->genMasterDataOneGrid($args);
//        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = TaskSchedule::queryMasterList($page, $this->pageSize, $args);
        $this->renderPartial('masterone_list', array('program_id' => $args['project_id'],'template_id' => $args['template_id'], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionMasterList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
        }
        $this->smallHeader = 'View Master Schedule';
//        $this->layout = '//layouts/main_3';
        $this->render('masterlist',array('program_id'=> $program_id,'args'=>$args));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genMasterDataTwoGrid($args) {

        $program_id = $args['project_id'];
        $t = new DataGrid($this->gridId_1);
        $t->url = 'index.php?r=task/schedule/mastertwogrid&program_id='.$program_id;
        $t->updateDom = 'datagrid_1';
        $t->set_header('Activities', '', '');
        $t->set_header('Block', '', '');
        $t->set_header('Level', '', '');
        $t->set_header('Part', '', '');
        $t->set_header('Plan End Date', '', '');
        $t->set_header('Actual End Date', '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionMasterTwoGrid() {
        $fields = func_get_args();
        if($_REQUEST['program_id']){
            $fields[0] = $_REQUEST['program_id'];
        }

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
        $t = $this->genMasterDataTwoGrid($args);
//        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = TaskSchedule::queryMasterList($page, $this->pageSize, $args);
        $this->renderPartial('mastertwo_list', array('program_id' => $args['project_id'],'template_id' => $args['template_id'], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionMasterTwoList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
        }
        $this->smallHeader = 'Master Schedule';
//        $this->layout = '//layouts/main_3';
        $this->render('mastertwolist',array('program_id'=> $program_id,'args'=>$args));
    }

    public function actionKeyActivities(){
        $this->smallHeader = 'Key Activities Cycle';
        $program_id = $_REQUEST['program_id'];
        if($_REQUEST['template_id']){
            $template_id = $_REQUEST['template_id'];
        }else{
            $template_id = '';
        }
        $this->render('key_activities',array('project_id'=>$program_id,'template_id'=>$template_id));
    }

    public function actionSubActivities(){
        $this->smallHeader = 'Sub Activities Cycle';
        $program_id = $_REQUEST['program_id'];
        if($_REQUEST['template_id']){
            $template_id = $_REQUEST['template_id'];
        }else{
            $template_id = '';
        }
        if($_REQUEST['stage_id']){
            $stage_id = $_REQUEST['stage_id'];
        }else{
            $stage_id = '';
        }
        $this->render('sub_activities',array('project_id'=>$program_id,'template_id'=>$template_id,'stage_id'=>$stage_id));
    }

//    public function actionChargeList(){
//        $this->smallHeader = 'Person In Charge';
//        $program_id = $_REQUEST['program_id'];
//        $this->render('person_in_charge',array('project_id'=>$program_id));
//    }

    public function actionMaster(){
        $program_id = $_REQUEST['program_id'];
        if($_REQUEST['pbu_tag']){
            $pbu_tag = $_REQUEST['pbu_tag'];
        }else{
            $pbu_tag = '2';
        }
        if($_REQUEST['block']){
            $block = $_REQUEST['block'];
        }else{
            $block_list = ProgramBlockChart::locationBlockbyType($program_id,$pbu_tag);
            if(count($block_list)>0){
                $block = $block_list[0];
            }else{
                $block = '';
            }
        }
        if($_REQUEST['template_id']){
            $template_id = $_REQUEST['template_id'];
        }else{
            $template_id = '';
        }
        $this->smallHeader = 'Set Master Schedule';
        $this->render('master',array('project_id'=>$program_id,'block'=>$block,'template_id'=>$template_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionSaveKey(){
        $key = $_REQUEST['key'];
        $r = TaskStage::saveKey($key);
        echo json_encode($r);
    }

    public function actionSaveSub(){
        $task = $_REQUEST['task'];
        $sub = $_REQUEST['sub'];
        $r = TaskList::saveSub($task);
        echo json_encode($r);
    }

    public function actionSavePersonincharge(){
        $person = $_REQUEST['Person'];
        $args['program_id'] = $_REQUEST['program_id'];
        $args['block'] = $_REQUEST['block'];
        $args['type'] = $_REQUEST['type'];
        $r = TaskBlockPerson::SavePerson($args,$person);
        echo json_encode($r);
    }

    public function actionGetTemplateEnd(){
        $template_id = $_REQUEST['template_id'];
        $template_start = $_REQUEST['template_start'];
        $r = TaskStage::getTemplateEnd($template_id,$template_start);
        echo json_encode($r);
    }

    public function actionGetStageEnd(){
        $stage_id = $_REQUEST['stage_id'];
        $stage_start = $_REQUEST['stage_start'];
        $r = TaskStage::getStageEnd($stage_id,$stage_start);
        echo json_encode($r);
    }

    public function actionGetStageDay(){
        $template_id = $_REQUEST['template_id'];
        $stage_id = $_REQUEST['stage_id'];
        $template_start = $_REQUEST['template_start'];
        $r = TaskStage::getStageDay($template_id,$stage_id,$template_start);
        echo json_encode($r);
    }

    public function actionStageDetail(){
        $template_id = $_REQUEST['template_id'];
        $stage_id = $_REQUEST['stage_id'];
        $stage_start = Utils::DateToCn($_REQUEST['stage_start']);
        $r = TaskStage::queryStageDetail($template_id,$stage_id,$stage_start);
        echo json_encode($r);
    }

    public function actionTaskDetail(){
        $stage_id = $_REQUEST['stage_id'];
        $r = TaskList::queryTaskDetail($stage_id);
        echo json_encode($r);
    }

    public function actionSaveSchedule(){
        $schedule = $_REQUEST['Schedule'];
        $set = $_REQUEST['Set'];
//        var_dump($schedule);
//        var_dump('-------------------------');
//        var_dump($set);
//        exit;
        $r = TaskSchedule::SaveSchedule($schedule,$set);
        echo json_encode($r);
    }

    public function actionViewSchedule(){
        $program_id = $_REQUEST['program_id'];
        $this->render('master_view',array('project_id'=>$program_id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genChargeGrid($args) {
        $program_id = $args['program_id'];
        $block = $args['block'];
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/schedule/chargegrid&program_id='.$program_id.'&block='.$block;
        $t->updateDom = 'datagrid';
        $t->set_header('Name', '', 'center');
        $t->set_header('View (Web)', '', 'center');
        $t->set_header('View & Edit (Web)', '', 'center');
        $t->set_header('Notification (App)', '', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionChargeGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if($fields[0]){
            $args['program_id'] = $fields[0];
        }
        if($_REQUEST['program_id']){
            $args['program_id'] = $_REQUEST['program_id'];
        }
        if($fields[1]){
            $args['block'] = $fields[1];
        }
        if($_REQUEST['block']){
            $args['block'] = $_REQUEST['block'];
        }
        if($_REQUEST['pbu_tag']){
            $args['pbu_tag'] = $_REQUEST['pbu_tag'];
        }
        $t = $this->genChargeGrid($args);
//        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = ProgramUser::queryListByUser($page, $this->pageSize, $args);
        $this->renderPartial('charge_list', array('args' => $args, 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionChargeList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['program_id']){
                $program_id = $args['program_id'];
            }
            if($args['block']){
                $block = $args['block'];
            }
        }
        if($_REQUEST['pbu_tag']){
            $pbu_tag = $_REQUEST['pbu_tag'];
        }else{
            $pbu_tag = '2';
        }
        if($_REQUEST['block']){
            $block = $_REQUEST['block'];
        }else{
            $block_list = ProgramBlockChart::locationBlockbyType($program_id,$pbu_tag);
            if(count($block_list)>0){
                $block = $block_list[0];
            }else{
                $block = '';
            }
        }
        $this->smallHeader = 'Person In Charge';
//        $this->layout = '//layouts/main_3';
        $this->render('chargelist',array('program_id'=> $program_id,'args'=>$args,'block'=>$block,'pbu_tag'=>$pbu_tag));
    }
}
