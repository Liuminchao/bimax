<?php

class PbuController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'projlist';
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
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        if($_REQUEST['pbu_tag']){
            $pbu_tag = $_REQUEST['pbu_tag'];
        }else{
            $pbu_tag = '2';
        }
        if($_REQUEST['stage_id']){
            $stage_id = $_REQUEST['stage_id'];
        }else{
            $stage_id = '';
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
        $model = new ProgramBlockChart('modify');
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        $this->smallHeader = 'Block Chart';
        if($block){
            $this->render('block_chart',array('block'=>$block,'project_id'=>$program_id,'args'=>$args,'pbu_tag'=>$pbu_tag,'select_stage_id'=>$stage_id));
        }else{
            $this->render('block_chart',array('project_id'=>$program_id,'args'=>$args,'pbu_tag'=>$pbu_tag,'select_stage_id'=>$stage_id));
//            $this->render('create',array('project_id'=>$program_id,'args'=>$args,'model'=>$model,'pbu_tag'=>$pbu_tag));
        }
    }
    /**
     * 设置项目区域
     */
    public static function actionSetBlock(){
        $args['blockchart'] = $_REQUEST['blockchart'];
        $r = ProgramBlockChart::SetBlock($args);
        print_r(json_encode($r));
    }
    /**
     * 列表
     */
    public function actionCreate() {
        $program_id = $_REQUEST['project_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->smallHeader = 'Setup Location';
        $model = new ProgramBlockChart('modify');
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        $this->render('create',array('project_id'=>$program_id,'args'=>$args,'model'=>$model,'pbu_tag'=>$pbu_tag));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genPbuDataGrid($args) {

        $program_id = $args['project_id'];
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/pbu/pbugrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('PBU Types', '', '');
        $t->set_header('Block', '', '');
        $t->set_header('Level', '', '');
        $t->set_header('Unit No.', '', '');
        $t->set_header('Part/Zone', '', '');
        $t->set_header('System ID', '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionPbuGrid() {
        $fields = func_get_args();
        if($_REQUEST['program_id']){
            $fields[0] = $_REQUEST['program_id'];
        }

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
        $t = $this->genPbuDataGrid($args);
        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = RevitComponent::queryPbuList($page, $this->pageSize, $args);
        $this->renderPartial('pbu_list', array('program_id' => $args['project_id'],'template_id' => $args['template_id'], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionPbuList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
        if($_REQUEST['pbu_tag']){
            $pbu_tag = $_REQUEST['pbu_tag'];
        }else{
            $pbu_tag = '2';
        }
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
        }
        $this->smallHeader = 'Components';
//        $this->layout = '//layouts/main_3';
        $this->render('pbulist',array('program_id'=> $program_id,'args'=>$args,'pbu_tag'=>$pbu_tag));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genStatisticsGrid($args) {

        $program_id = $args['project_id'];
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/pbu/statisticsgrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Level', '', '');
        $t->set_header('Completed (Qty)', '', '');
        $t->set_header('Total (Qty)', '', '');
        $t->set_header('Balance (Qty)', '', '');
        $t->set_header('Completion %', '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionStatisticsGrid() {
        $fields = func_get_args();
        if($_REQUEST['program_id']){
            $fields[0] = $_REQUEST['program_id'];
        }

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
        $t = $this->genStatisticsGrid($args);
        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = RevitComponent::queryStatistics($page, $this->pageSize, $args);
        $this->renderPartial('statistics_list', array('program_id' => $args['project_id'],'template_id' => $args['template_id'], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionStatisticsList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
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
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
        }
        $this->smallHeader = 'Statistics';
//        $this->layout = '//layouts/main_3';
        $this->render('statisticslist',array('project_id'=> $program_id,'args'=>$args,'pbu_tag'=>$pbu_tag,'block'=>$block));
    }

    public function actionChangeUnit() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->renderPartial('change_unit', array('block' => $block,'project_id'=>$project_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionSaveUnit() {
        $project_id = $_REQUEST['project'];
        $block = $_REQUEST['block'];
        $unit_list = $_REQUEST['Unit'];
        $unit_old_list = $_REQUEST['Unit_old'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $r = ProgramBlockChart::SetUnit($project_id,$block,$unit_list,$unit_old_list,$pbu_tag);
        print_r(json_encode($r));
    }

    public function actionChangePart() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->renderPartial('change_part', array('block' => $block,'project_id'=>$project_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionSavePart() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project'];
        $part = $_REQUEST['part'];
        $unit_list = $_REQUEST['Unit'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $r = ProgramBlockChart::SetPart($project_id,$block,$unit_list,$part,$pbu_tag);
        print_r(json_encode($r));
    }

    public function actionSavePbutype() {
        $pbu_tag = $_REQUEST['pbu_tag'];
        $project_id = $_REQUEST['project_id'];
        $pbu_type = $_REQUEST['pbu_type'];
        $unit_list = $_REQUEST['Unit'];
        $r = ProgramBlockChart::SetPbutype($project_id,$unit_list,$pbu_type,$pbu_tag);
        print_r(json_encode($r));
    }

    public function actionRePbutype() {
        $project_id = $_REQUEST['project'];
        $re_pbu_type = $_REQUEST['re_pbu_type'];
        $pbu_type = $_REQUEST['pbu_type'];
        $unit_list = $_REQUEST['Unit'];
        $r = ProgramBlockChart::RePbutype($project_id,$unit_list,$pbu_type,$re_pbu_type);
        print_r(json_encode($r));
    }

    public function actionAssign() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $part = $_REQUEST['part'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->renderPartial('assign', array('part'=>$part,'block' => $block,'project_id'=>$project_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionAllocate() {
        $project_id = $_REQUEST['project_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->renderPartial('allocate', array('project_id'=>$project_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionEditAllocate() {
        $project_id = $_REQUEST['project_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        if($_REQUEST['pbu_type']){
            $pbu_type = $_REQUEST['pbu_type'];
        }else{
            $pbu_type_list = ProgramBlockChart::locationPbutype($project_id,$pbu_tag);
            $pbu_type = $pbu_type_list[0]['pbu_type'];
        }
        $this->renderPartial('edit_manual', array('project_id'=>$project_id,'pbu_type'=>$pbu_type,'pbu_tag'=>$pbu_tag));
    }

    public function actionManual() {
        $project_id = $_REQUEST['project_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->renderPartial('manual', array('project_id'=>$project_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionBim() {
        $project_id = $_REQUEST['project_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->renderPartial('bim', array('project_id'=>$project_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionSaveBim() {
        $project_id = $_REQUEST['project_id'];
        $model_id = $_REQUEST['model_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        //后台执行 非阻塞 异步
        exec('php /opt/www-nginx/web/test/bimax/protected/yiic model exportallpbu --param1='.$project_id.' --param2='.$model_id.' --param3='.$pbu_tag.'  >/dev/null  &');
        $r['status'] = '1';
        print_r(json_encode($r));
    }

    public function actionUpdatePbu() {
        $tag = $_REQUEST['tag'];
        $r = ProgramBlockChart::SetFormal($tag);
        print_r(json_encode($r));
    }

    public function actionUpdateActDate() {
        $pbu_info['project_id'] = $_REQUEST['project_id'];
        $pbu_info['end_date'] = Utils::DateToCn($_REQUEST['end_date']);
        $pbu_info['bak_end_date'] = Utils::DateToCn($_REQUEST['bak_end_date']);
        $pbu_info['stage_id'] = $_REQUEST['stage_id'];
        $pbu_info['pbu_id'] = $_REQUEST['pbu_id'];
        $pbu_info['pbu_tag'] = $_REQUEST['pbu_tag'];
        $r = ProgramBlockChart::UpdateEnddate($pbu_info);
        print_r(json_encode($r));
    }

    public function actionShowTask() {
        $pbu_id = $_REQUEST['pbu_id'];
        $project_id = $_REQUEST['project_id'];
        $stage_id = $_REQUEST['stage_id'];
        $this->renderPartial('show_task', array('project_id'=>$project_id,'pbu_id'=>$pbu_id,'stage_id'=>$stage_id));
//        $r = ProgramBlockChart::ShowTask($pbu_id,$project_id,$stage_id);
//        print_r(json_encode($r));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['qa/defect/typelist'] = str_replace("r=qa/defect/typegrid", "r=qa/defect/typelist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
}
