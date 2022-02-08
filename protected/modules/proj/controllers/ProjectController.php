<?php

class ProjectController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'projlist';
    public $contentHeader = "";
    public $bigMenu = "";
    public $ptype;
    
    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('proj_project', 'contentHeader');
        $this->bigMenu = Yii::t('proj_project', 'bigMenu');
        
        $this->ptype = $_GET['ptype'];
        
        if($_GET['ptype'] == 'MC'){
            $this->contentHeader = Yii::t('proj_project', 'contentHeader');
        }
        elseif($_GET['ptype'] == 'SC'){
            $this->contentHeader = Yii::t('proj_project',  'sub contentHeader');
        }
        
        if($_GET['ptype'] == 'MC'){
            $this->bigMenu = Yii::t('dboard', 'Menu Project');
        }
        elseif($_GET['ptype'] == 'SC'){
            $this->bigMenu = Yii::t('dboard', 'Menu Project');
        }
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/project/grid&ptype='.Yii::app()->session['project_type'];
        $ptype = Yii::app()->session['project_type'];
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('proj_project', 'program_id'), '', 'center');
        //$t->set_header(Yii::t('proj_project', 'program_id'), '', '');
        $t->set_header(Yii::t('proj_project', 'program_name'), '', 'center');
        $t->set_header(Yii::t('proj_project', 'contractor_name'), '', 'center');
        //$t->set_header(Yii::t('proj_project', 'sub_contractor_name'), '', '');
        $t->set_header(Yii::t('proj_project', 'contractor_type'), '', 'center');
        $program_id = Yii::app()->user->getState('program_id');
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        if($root_proid == $program_id){
            $t->set_header(Yii::t('proj_project', 'project_struct'), '', 'center');
            $t->set_header(Yii::t('proj_project','project_region'),'20%','center');
        }else{
//            $t->set_header(Yii::t('proj_project', 'project_decomposition'), '', '');
//            $t->set_header(Yii::t('proj_project','project_region'),'','center');
        }
        $t->set_header(Yii::t('proj_project', 'record_time'), '', 'center');
        $t->set_header(Yii::t('proj_project', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '20%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if($args['project_type'] == '')
            $args['project_type'] = Yii::app()->session['project_type'];

        if($args['program_id'] == '')
            $args['program_id'] = Yii::app()->session['program_id'];
        
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($args);
//        exit;
        $list = Program::queryList($page, $this->pageSize, $args);
//        var_dump($list['total_num']);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'new_cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $ptype = $_GET['ptype'];
        $program_id = $_GET['program_id'];
        //$this->layout = '//layouts/main_model';
        $this->layout = '//layouts/main_new';
        Yii::app()->session['project_type'] = $ptype;
        Yii::app()->session['program_id'] = $program_id;
        Yii::app()->user->setState('program_id', $program_id);
        $this->smallHeader = 'Project Info';
        $this->render('list');
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genAppDataGrid($company_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/info/appgrid&company_id='.$company_id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('sys_app', 'app_id'), '', '');
        $t->set_header(Yii::t('sys_app', 'app_name'), '', '');
        $t->set_header(Yii::t('sys_app', 'open_time'), '', '');
        $t->set_header(Yii::t('sys_app', 'close_time'), '', '');
        $t->set_header(Yii::t('comp_company', 'Status'), '', '');
        $t->set_header(Yii::t('comp_company', 'Record Time'), '', '');
        $t->set_header(Yii::t('comp_company', 'Action'), '20%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionAppGrid($company_id) {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        //$args['status'] = '0';
        $t = $this->genAppDataGrid($company_id);
        $this->saveUrl();
        $args['status'] = 0;
        $args['company_id'] = $company_id;
        $list = CompanyApp::queryList($page, $this->pageSize, $args);
        $this->renderPartial('app_list', array('t' => $t,'company_id'=>$company_id, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

//    /**
//     * 列表
//     */
//    public function actionAppList() {
//        $program_id = $_REQUEST['program_id'];
//        $this->smallHeader = Yii::t('sys_app', 'smallHeader List');
//        $this->render('applist',array('program_id'=>$program_id));
//    }

    /**
     * 表单
     */
    public function actionAppList() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('comp_company', 'smallHeader New');
        $model = new ProgramApp('modify');
        $r = array();

        //echo Company['company_name'];
        //默认勾选模块数组
        $select_list = ProgramApp::myAppList($program_id);
        //模块列表
        $app_list = App::appList();

        $this->renderPartial('app_form', array('model' => $model,'program_id'=>$program_id,'msg' => $r,'select_list' => $select_list,'app_list' => $app_list));
    }
    /**
     * 修改模块权限
     */
    public function actionEditApp() {
        $r = array();
        if (isset($_REQUEST['ProgramApp'])) {
            $args = $_REQUEST['ProgramApp'];
            $r = ProgramApp::editProgramApp($args);
            $class = Utils::getMessageType($r['status']);
            $r['class'] = $class[0];
        }
        print_r(json_encode($r));
    }
    /**
     * 文档列表
     */
    public function actionAttachmentList() {
        $this->smallHeader = Yii::t('comp_document', 'smallHeader List Application');
        $program_id = $_REQUEST['id'];
//        var_dump($program_id);
        $this->render('documentlist',array('program_id'=>$program_id));
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->layout = '//layouts/main_1';
        $this->smallHeader = Yii::t('proj_project', 'smallHeader New');
        $model = new Program('create');
        $r = array();
        $ptype = $_REQUEST['ptype'];

        $this->render('new', array('model' => $model, 'msg' => $r,'ptype' => $ptype));
    }
    /**
     * 添加项目（json）
     */
    public function actionNewProgram() {

        $args = $_REQUEST['Program'];

        $args['add_conid'] = Yii::app()->user->getState('contractor_id');
        $args['add_operator'] = Yii::app()->user->id;
        if($args['construction_start']){
            $args['construction_start'] = Utils::DateToCn($args['construction_start']);
        }
        if($args['construction_end']){
            $args['construction_end'] = Utils::DateToCn($args['construction_end']);
        }
//        var_dump($args);
//        exit;
        $r = Program::insertProgram($args);

        print_r(json_encode($r));
    }
    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('proj_project', 'smallHeader Edit');
        $model = new Program('modify');
        $r = array();
        $id = $_REQUEST['id'];
        $ptype = $_REQUEST['ptype'];

        $model->_attributes = Program::model()->findByPk($id);

        $this->render('edit', array('model' => $model, 'msg' => $r,'ptype' => $ptype));
    }
    /**
     * 修改项目（json）
     */
    public function actionEditProgram() {
        $args = $_POST['Program'];
//            var_dump($args);
//            exit;
        $r = Program::updateProgram($args);
        print_r(json_encode($r));
    }
    /**
     * 子项目
     */
    public function actionSubList() {
       $father_proid = $_GET['father_proid'];
       $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        
       if($father_proid <> '')
            $father_model = Program::model()->findByPk($father_proid);
       $this->smallHeader = 'Contractors';
       $this->contentHeader = Yii::t('proj_project', 'contentHeader');
       $this->bigMenu = $father_model->program_name;
       
       $this->render('sublist', array('father_model'=>$father_model, 'father_proid'=>$father_proid, 'args'=>$args));
    }
    /**
     * 分包商员工表头
     * @return SimpleGrid
     */
    private function genSubStaffGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/project/substaffgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_staff', 'User_id'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'User_name'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'User_phone'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Work_no'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Work_pass_type'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Nation_type'), '', 'center');
        $t->set_header(Yii::t('comp_staff','Role_id'),'','center');
        $t->set_header(Yii::t('comp_staff','loane'),'','center');
        $t->set_header(Yii::t('comp_staff', 'Status'), '', 'center');
        $t->set_header(Yii::t('sys_operator', 'Record Time'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', 'center');
        return $t;
    }
    /**
     * 分包商查询员工
     */
    public function actionSubStaffGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $t = $this->genSubStaffGrid();
        $this->saveSubStaffUrl();
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
//        $criteria->join = 'LEFT JOIN bac_staff b ON b.user_id=t.user_id';
        $list = Staff::queryListByProgram($page, $this->pageSize, $args);
        $this->renderPartial('_substafflist', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 分包商员工表
     */
    public function actionSubStaffList() {
        $contractor_id = $_REQUEST['contractor_id'];
        $root_proid = $_REQUEST['root_proid'];
        $tag = $_REQUEST['tag'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['root_proid']){
                $root_proid = $args['root_proid'];
            }
            if($args['contractor_id']){
                $contractor_id = $args['contractor_id'];
            }
            if($args['tag']){
                $tag = $args['tag'];
            }
        }
        $this->smallHeader = Yii::t('comp_staff', 'smallHeader List');
        $this->render('substafflist',array('contractor_id'=>$contractor_id,'root_proid'=>$root_proid,'tag'=>$tag,'args'=>$args));
    }
    /**
     * 分包商设备表头
     * @return SimpleGrid
     */
    private function genSubDeviceGrid($contractor_id,$program_id,$tag) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/project/subdevicegrid&contractor_id='.$contractor_id.'&program_id='.$program_id .'&tag='.$tag;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('device', 'device_type'), '', 'center');
        $t->set_header(Yii::t('device', 'device_id'), '', 'center');
        $t->set_header(Yii::t('device', 'device_name'), '', 'center');
//        $t->set_header(Yii::t('device', 'permit_startdate'), '', '');
//        $t->set_header(Yii::t('device', 'permit_enddate'), '', '');
        $t->set_header(Yii::t('device', 'status'), '', 'center');
        $t->set_header(Yii::t('device', 'record_time'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', 'center');
        return $t;
    }
    /**
     * 分包商查询设备
     */
    public function actionSubDeviceGrid($contractor_id,$program_id,$tag) {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if($args['status'] == ''){
            $args['status'] = '00';
        }
        $t = $this->genSubDeviceGrid($contractor_id,$program_id,$tag);
        $this->saveUrl();
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = $contractor_id;
        $args['program_id'] = $program_id;
        $args['tag'] = $tag;
//        var_dump($args);
        $list = Device::queryListByProgram($page, $this->pageSize, $args);
//        var_dump($list);
//        exit;
        $this->renderPartial('_subdevicelist', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 分包商设备表
     */
    public function actionSubDeviceList() {
        $contractor_id = $_REQUEST['contractor_id'];
        $program_id = $_REQUEST['program_id'];
        $tag = $_REQUEST['tag'];
        $this->smallHeader = Yii::t('device', 'smallHeader List');
        $this->render('subdevicelist',array('contractor_id'=>$contractor_id,'program_id'=>$program_id,'tag'=>$tag));
    }
    /**
     * 
     * 项目分解
     */
    
    public function actionProList() {
        
       $father_proid = $_GET['father_proid'];
       if($father_proid <> '')
            $father_model = Program::model()->findByPk($father_proid);
       $this->smallHeader = $father_model->program_name;
       $this->contentHeader = Yii::t('proj_project', 'contentHeader');
       $this->bigMenu = $father_model->program_name;
       
       $this->render('prolist', array('father_model'=>$father_model, 'father_proid'=>$father_proid));
    }
    
    private function genSubDataGrid($father_proid) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/project/subgrid&ptype='.Yii::app()->session['project_type'].'&father_proid='.$father_proid;
        $t->updateDom = 'datagrid';
        //$t->set_header(Yii::t('proj_project', 'num'), '', '');
        $t->set_header(Yii::t('proj_project', 'program_id'), '', 'center');
//        $t->set_header(Yii::t('proj_project', 'program_name'), '', 'center');
        //$t->set_header(Yii::t('proj_project', 'contractor_name'), '', '');
        $t->set_header(Yii::t('proj_project', 'sub_contractor_name'), '', 'center');
        //$t->set_header(Yii::t('proj_project', 'in_staffs'), '', 'center');
        //$t->set_header(Yii::t('proj_project', 'out_staffs'), '', 'center');
        //$t->set_header(Yii::t('proj_project', 'in_devices'), '', 'center');
        //$t->set_header(Yii::t('proj_project', 'out_devices'), '', 'center');
        $t->set_header("Appointed<br/>(Members)", '', 'center');
        $t->set_header("Pending<br/>(Members)", '', 'center');
//        $t->set_header("Appointed<br/>(Equipment)", '', 'center');
//        $t->set_header("Pending<br/>(Equipment)", '', 'center');
        
        $t->set_header(Yii::t('proj_project', 'record_time'), '', 'center');
        $t->set_header(Yii::t('proj_project', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '13%', 'center');
        return $t;
    }
    /**
     * 子项目查询
     */
    public function actionSubGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if($args['father_proid'] == ''){
            $args['father_proid'] = $_GET['father_proid'];
        }
        if($args['father_proid'] == ''){
            $args['father_proid'] = $fields[0];
        }
        
        //var_dump($args);
        $t = $this->genSubDataGrid($args['father_proid']);
        $this->saveSubUrl();
        
        if($args['father_proid'] != ''){
            $list = Program::queryList($page, $this->pageSize, $args);
        }
    
        $this->renderPartial('_sublist', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    
    /**
     * 子项目添加
     */
    public function actionSubNew() {
        
        $father_proid = $_REQUEST['father_proid'];
        if($father_proid <> '')
            $father_model = Program::model()->findByPk($father_proid);
        
        $this->smallHeader = 'Create Subcon';
        $this->contentHeader = Yii::t('proj_project', 'smallHeader New');
        $this->bigMenu = $father_model->program_name;
       
        $model = new Program('create');
        $r = array();
       
        if (isset($_POST['Program'])) {

            $args = $_POST['Program'];
            $args['father_model'] = $father_model;
            $args['TYPE'] = 'SC';             //var_dump($args);
            $args['add_conid'] = Yii::app()->user->getState('contractor_id');
            $args['add_operator'] = Yii::app()->user->id;
//            $r = Program::insertProgram($args);
            $exist_data = Program::model()->count('contractor_id=:contractor_id and root_proid=:root_proid and status = 00', array('contractor_id' => $args['contractor_id'],'root_proid' => $father_proid));
            if ($exist_data != 0) {
                $r['msg'] = 'The subcontract already exists under this program';
                $r['status'] = -1;
                $r['refresh'] = false;
                goto end;
            }else{
                $r = Program::insertProgram($args);
            }
            end:
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Program'];
            }
        }
        
        $this->render('subnew', array('model' => $model, 'father_proid'=>$father_proid, 'msg' => $r));
    }
    /**
     * 子项目修改
     */
    public function actionSubEdit() {
        
        $model = new Program('modify');
        $r = array();
        $id = $_REQUEST['id'];
        if (isset($_POST['Program'])) {
            $args = $_POST['Program'];
            $pro_model = Program::model()->findByPk($id);
            $father_proid = $pro_model->father_proid;
            if($father_proid <> '')
                $father_model = Program::model()->findByPk($father_proid);

            $args['program_name'] = $father_model->program_name;
//            var_dump($args);
//            exit;
            $r = Program::updateProgram($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Program'];
            }
        }
        $model->_attributes = Program::model()->findByPk($id);
        $rs = Contractor::model()->findByPk($model->contractor_id);
        $model->subcomp_sn = $rs['company_sn'];
        $model->subcomp_name = $rs['contractor_name'];

        $this->smallHeader = 'Edit Subcon';
        $this->contentHeader = Yii::t('proj_project', 'smallHeader Edit');
        $this->bigMenu = $father_model->program_name;
        
        $this->render('subedit', array('model' => $model, 'msg' => $r));
    }
    /**
     * 区域选择
     */
    public function actionSelectRegion() {
        $model = new ProgramRegion('modify');
        $program_id = $_REQUEST['program_id'];

        $this->renderPartial('region_step', array('model' => $model));
    }
    /**
     *项目组织图
     */
    public function actionStruct() {
        $program_id = $_REQUEST['id'];
        $program_name = $_REQUEST['name'];
        $father_model = Program::model()->findByPk($program_id);
        $this->smallHeader = $father_model->program_name;
        $this->contentHeader = Yii::t('proj_project', 'struct');
        $this->bigMenu = $father_model->program_name;
        // 调用
//        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=36.671997,117.161818&key=AIzaSyAhcLgIHv_6VXLS9Kyt4GTTPlsZF_srA4o&language=zh-CN';
//        $header = array();
//
//        $response = self::Transfer('山东省济南市纬一路','');
//        var_dump($response);
//        exit;
        $this->render('switch', array('program_id' => $program_id,'program_name' => $program_name));
    }
    /*
     * 经纬度转换
     */
    private function Transfer($address, $city='') {

//        $url = 'http://api.map.baidu.com/geocoder/v2/';
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        $data = array(
//            'ak'      => 'E4805d16520de693a3fe707cdc962045',
            'key'      => 'AIzaSyAhcLgIHv_6VXLS9Kyt4GTTPlsZF_srA4o',
            'language' => 'zh-CN',
            'latlng'   => '36.671997,117.161818',
//            'callback' => null,
//            'output'   => 'json',
//            'address'  => $address,
//            'city'    => $city,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_REFERER, 'http://developer.baidu.com/map/index.php?title=webapi/guide/webservice-geocoding');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret  = curl_exec($ch);
        curl_close($ch);
        $ret  =  json_decode($ret, true);
//        $_coord['lat'] = $ret['result']['location']['lat'];
//        $_coord['lng'] = $ret['result']['location']['lng'];

        return $ret;

    }
    /**
     *总包项目区域
     */
    public function actionSetMcRegion() {
        $program_id = trim($_REQUEST['program_id']);
        $ptype = trim($_REQUEST['ptype']);
        $regionlist = ProgramRegion::regionList($program_id);
        $block_cnt = count($regionlist);
        $model = new ProgramRegion('modify');
        if($program_id <> '')
            $father_model = Program::model()->findByPk($program_id);
        
        if (isset($_POST['ProgramRegion'])) {
            $args = $_POST['ProgramRegion'];
//            unset($args['program_id']);
            $r = ProgramRegion::InsertMcRegion($args,$program_id);
//            var_dump($r);
//            exit;
            if ($r['refresh'] == false) {
                Yii::app()->user->setFlash('error', 'Successfully added!');
            }else{
                Yii::app()->user->setFlash('success', 'Add failed!');
                $this->refresh();
            }
        }
        $this->smallHeader = 'Project Block';
        $this->contentHeader = Yii::t('proj_project', 'project_region');
        $this->bigMenu = $father_model->program_name;
        $this->render('region_mc_flow_old', array('block_cnt'=>$block_cnt,'model'=>$model,'regionlist'=>$regionlist, 'msg' => $r,'program_id'=> $program_id,'ptype'=>$ptype));
    }
    /**
     *分包项目区域
     */
    public function actionSetScRegion() {
        $program_id = trim($_REQUEST['program_id']);
        $root_proid = trim($_REQUEST['root_proid']);
        $ptype = trim($_REQUEST['ptype']);
        $mc_regionlist = ProgramRegion::locationShow($root_proid);//总包区域
        $sc_regionlist = ProgramRegion::locationShow($program_id);//分包区域
        $location_list = ProgramRegion::regionList($root_proid);//总包区域固定位置
        $location_block = ProgramRegion::locationBlock($root_proid);//总包区域位置关系
        $model = new ProgramRegion('modify');
        if($program_id <> '')
            $father_model = Program::model()->findByPk($program_id);

        if (isset($_POST['ProgramRegion'])) {
            $args = $_POST['ProgramRegion'];
            unset($args['program_id']);
//            var_dump($args);
//            exit();
            $r = ProgramRegion::InsertScRegion($args,$program_id);

            if ($r['refresh'] == false) {
                Yii::app()->user->setFlash('error', 'Successfully added!');
            }else{
                Yii::app()->user->setFlash('success', 'Add failed!');
                $this->refresh();
            }
        }
        $this->smallHeader = $father_model->program_name;
        $this->contentHeader = Yii::t('proj_project', 'project_region');
        $this->bigMenu = $father_model->program_name;
        $this->render('region_sc_flow', array('model'=>$model,'sc_regionlist'=>$sc_regionlist,'mc_regionlist'=>$mc_regionlist,'location_list'=>$location_list,'location_block'=>$location_block, 'msg' => $r,'program_id'=> $program_id,'ptype'=>$ptype));
    }

    /**
     * 设置项目区域
     */
    public static function actionSetRegion(){
        $args['program'] = $_REQUEST['Program'];
        $args['block'] = $_REQUEST['block'];
        $args['level'] = $_REQUEST['level'];
//        var_dump($_REQUEST['level']);
//        exit;
//        $str = $_REQUEST['str'];
//        $region['program_id'] = $_REQUEST['program_id'];
//        $region['tag'] = $_REQUEST['tag'];
//        $region['location'] = $_REQUEST['location'];
//        $arr = explode(",",$str);
        $r = ProgramRegion::InsertRegion_New($args);
        print_r(json_encode($r));
    }
    /**
     * 启用
     */
    public function actionStart() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Program::startProgram($id);
        }
        echo json_encode($r);
    }
    
    /**
     * 停用：结项
     */
    public function actionStop() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Program::stopProgram($id);
        }
        echo json_encode($r);
    }
    /**
     * 删除
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Program::deleteProgram($id);
        }
        echo json_encode($r);
    }
    
    /**
     * 详细
     */
    public function actionDetail() {

        $program_id = $_POST['id'];

        $model = Program::model()->findByPk($program_id);
        //var_dump($model);
        if($model->father_proid == 0){
            $father_model = $model;
            unset($model);
        }
        else
            $father_model = Program::model()->findByPk($model->father_proid);
        
        $msg = array();
        if ($father_model) {
            
            $status_list = Program::statusText();   //状态
            $compList = Contractor::compAllList(); //所有承包商
            
            $msg['detail'] .= "<table class='detailtab'>";
            $msg['detail'] .= "<tr class='form-name'>";
            $msg['detail'] .= "<td colspan='4'>".Yii::t('dboard', 'Menu Project MC')."</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'program_name')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . $father_model->program_name ."</td>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'program_id')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . $father_model->program_id ."</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";          
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'contractor_name')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . $compList[$father_model->contractor_id] . "</td>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'status')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . $status_list[$father_model->status] ."</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'record_time')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . Utils::DateToEn($father_model->record_time) . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'program_content')."：</td>";
            $msg['detail'] .= "<td class='tvalue-4' colspan='3'>" . $father_model->program_content . "</td>";
            $msg['detail'] .= "</tr>";
            
            if($model){
                $msg['detail'] .= "<tr class='form-name'>";
                $msg['detail'] .= "<td colspan='4'>".Yii::t('dboard', 'Menu Project SC')."</td>";
                $msg['detail'] .= "</tr>";
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'program_name')."：</td>";
                $msg['detail'] .= "<td class='tvalue-4'>" . $model->program_name ."</td>";
                $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'program_id')."：</td>";
                $msg['detail'] .= "<td class='tvalue-4'>" . $model->program_id ."</td>";
                $msg['detail'] .= "</tr>";
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'sub_contractor_name')."：</td>";
                $msg['detail'] .= "<td class='tvalue-4'>" . $compList[$model->contractor_id] . "</td>";
                $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'status')."：</td>";
                $msg['detail'] .= "<td class='tvalue-4'>" . $status_list[$model->status] ."</td>";
                $msg['detail'] .= "</tr>";
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'record_time')."：</td>";
                $msg['detail'] .= "<td class='tvalue-4'>" .Utils::DateToEn($model->record_time) . "</td>";
                $msg['detail'] .= "</tr>";
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<td class='tname-2'>".Yii::t('proj_project', 'program_content')."：</td>";
                $msg['detail'] .= "<td class='tvalue-4' colspan='3'>" . $model->program_content . "</td>";
                $msg['detail'] .= "</tr>";
            }
            
            $msg['status'] = true;
            $msg['detail'] .= "</table>";
        }
        else {
            $msg['status'] = false;
            $msg['detail'] = Yii::t('common', 'The request failed');
        }
        print_r(json_encode($msg));
        
    }
    /**
     * Word,Excel转PDF
     */
    public function actionWordtoPdf() {
        $this->render('wordtopdf');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDocumentGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/project/documentgrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
//        $t->set_header('文档编号', '', '');
        $t->set_header(Yii::t('comp_document', 'document_name'), '', '');
        $t->set_header(Yii::t('comp_document', 'commonly_used'), '', '');
        $t->set_header(Yii::t('comp_document', 'label'), '', '');
        $t->set_header(Yii::t('comp_document', 'upload_time'),'','');
        $t->set_header(Yii::t('common', 'action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionDocumentGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if(count($fields) == 1 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
        }
        if($_GET['program_id']){
            if(!$fields[0]){
                $args['program_id'] = $_GET['program_id'];
            }
        }
//        var_dump($args);
//        if(!$args['program_id']){
//            $args['program_id'] = $program_id;
//        }
//        $args['program_id'] = $_REQUEST['program_id'];
        $args['type'] = 4;
        $t = $this->genDocumentGrid($args['program_id']);
//        $this->saveUrl();
        $list = Document::queryList($page, $this->pageSize, $args);
        $this->renderPartial('document_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }


    /**
     * 展示
     */
    public function actionShow() {
        $this->render('show');
    }
    /**
     * 上传
     */
    public function actionUpload() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('document_upload',array('program_id'=>$program_id));
    }
    /**
     * 来源
     */
    public function actionSource() {
        $sql = "SELECT label_id,label_name,label_name_en FROM bac_document_label where type=4";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        $i = 1;
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$i]['id'] = $row['label_id'];
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$i]['name'] = $row['label_name'];
                }else{
                    $rs[$i]['name'] = $row['label_name_en'];
                }
                $i++;
            }
        }
        print_r( json_encode($rs,true));
//        $rs[0]['id'] = 'null';
//        $rs[0]['name'] = '无';
    }
    /**
     * 将上传的图片移动到正式路径下
     */
    public function actionMove() {
        $file_src = $_REQUEST['file_src'];
        $args['program_id'] = $_REQUEST['program_id'];
        $args['type'] = 4;
        $r = Document::movePic($file_src,$args);
        print_r(json_encode($r));
    }
    /**
     * 压缩测试
     */
    public function actionTest()
    {
        $sql = "select doc_path from bac_document where type = 1 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
        $test = array();
//        $test = array_slice($rows, 0, 1);
//        var_dump($test);
        $filename = "/opt/www-nginx/web/filebase/platform/bak.zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
                if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                    //如果是Linux系统，需要保证服务器开放了文件写权限
                    exit("文件打开失败!");
                }
            foreach ($rows as $cnt => $val) {
//            for($i=0;$i<=count($rows);$i++){
//                var_dump(basename($rows[$i]['doc_path']));
//                exit;
//                if (file_exists($val['doc_path'])) {
                    $zip->addFile('/opt/www-nginx/web'.$val['doc_path'], basename($val['doc_path']));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
//                }
            }
//            var_dump($zip);
            echo "文件数 : ".$zip->numFiles;
            $zip->close();
        }

        if (file_exists($filename) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $file = fopen($filename, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/zip");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        $name = "baz".".zip";
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filename));
        fclose($file);
        if (!unlink($filename))
        {
            echo ("Error deleting ");
        }
        else
        {
            echo ("Deleted successed");
        }
//        //实例化类
//        $zip = new ZipArchive();
////需要打开的zip文件,文件不存在将会自动创建
//        $filename = "/opt/www-nginx/web/filebase/application/bak.zip";
//
//        if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
//            //如果是Linux系统，需要保证服务器开放了文件写权限
//            exit("文件打开失败!");
//        }
//
////将一段字符串添加到压缩文件中,test.txt文件会自动创建
//        $zip->addFromString("test.txt", "你好 , 世界");
//
////输出加入的文件数 , 这里应该是 2
//        echo "文件数 : ".$zip->numFiles;
//
////关闭文件
//        $zip->close();
    }
    /**
     * 设置标签
     */
    public function actionSettags() {
        $doc_id = $_REQUEST['doc_id'];
        $label_id = $_REQUEST['value'];
        $rs = Document::SetTag($doc_id,$label_id);
        echo json_encode($rs);
    }
    /**
     * 保存查询链接
     */
    private function saveDocUrl() {

        $a = Yii::app()->session['list_url'];
        $a['document/list'] = str_replace("r=document/company/documentgrid", "r=document/platform/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    /**
     * 设置常用
     */
    public function actionSetused() {
        $doc_id = trim($_REQUEST['doc_id']);
        $doc_use = trim($_REQUEST['doc_use']);
        $r = array();
        $r = Document::setUsed($doc_id,$doc_use);
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $doc_path = $_REQUEST['doc_path'];
        $doc_id = $_REQUEST['doc_id'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('preview',array('doc_path'=>$doc_path,'doc_id'=>$doc_id));
    }
    /**
     * 删除文档
     */
    public function actionDel() {
        $doc_id = trim($_REQUEST['doc_id']);
        $doc_path = trim($_REQUEST['doc_path']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Document::deleteFile($doc_id,$doc_path);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $doc_path = $_REQUEST['doc_path'];
        $rows = Document::queryFile($doc_path);
        if(count($rows)>0){
            $show_name = $rows[0]['doc_name'];
            $filepath = '/opt/www-nginx/web'.$rows[0]['doc_path'];
            $extend = $rows[0]['doc_type'];
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }
    /**
     * 考勤设置
     */
    public function actionSetAttendance() {
//        $ptype = $_REQUEST['ptype'];
//        if($ptype == 'MC'){
            $program_id = $_REQUEST['program_id'];
//        }else if($ptype == 'SC'){
//            $scprogram_id = $_REQUEST['program_id'];
//            $sc_model = Program::model()->findByPk($scprogram_id);
//            $program_id = $sc_model->root_proid;
//        }
        $model = new Program('modify');
        $model->_attributes = Program::model()->findByPk($program_id);
//        var_dump($model);
        $this->renderPartial('set_attendance',array('model'=>$model,'program_id'=>$program_id));
    }
    /*
     * 更新考勤设置
     */
    public function actionUpdateAttendance(){
        $args = $_POST['Program'];
//        var_dump($args['tbm_sign']);
//        exit;
        $program_list = Program::Mc_ScProgramList($args['program_id']);
        $sql = "update bac_program set start_sign = :start_sign,faceapp_sign = :tbm_sign where program_id = :program_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":start_sign", $args['start_sign'], PDO::PARAM_INT);
        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
        $command->bindParam(":tbm_sign", $args['tbm_sign'], PDO::PARAM_STR);
        $command->execute();
        //分包项目添加标识
        foreach($program_list as $n => $list){
//            var_dump($list['program_id']);
//            var_dump($args['start_sign']);
//            var_dump($args['tbm_sign']);
//            exit;
            $sc_model = Program::model()->findByPk($list['program_id']);
            $sc_model->start_sign = $args['start_sign'];
            $sc_model->faceapp_sign = $args['tbm_sign'];
            $result = $sc_model->save();
//            $sql = "update bac_program set start_sign = :start_sign and faceapp_sign = :tbm_sign where program_id = :program_id ";
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":start_sign", $args['start_sign'], PDO::PARAM_INT);
//            $command->bindParam(":program_id", $list['program_id'], PDO::PARAM_INT);
//            $command->bindParam(":tbm_sign", $args['tbm_sign'], PDO::PARAM_STR);
//            $command->execute();
        }
        $r['msg'] = Yii::t('common', 'success_update');
        $r['status'] = 1;
        $r['refresh'] = true;
        echo json_encode($r);
    }

    /**
     * 参数设置
     */
    public function actionSetParams() {

        $program_id = $_REQUEST['program_id'];
        $model = new Program('modify');
        $model->_attributes = Program::model()->findByPk($program_id);
//        var_dump($model);
        $this->renderPartial('set_params',array('model'=>$model,'program_id'=>$program_id));
    }

    /*
     * 更新模块设置
     */
    public function actionUpdateParams(){
        $args = $_POST['Program'];
        $r = Program::updateProgramParams($args);
        echo json_encode($r);
    }

    /**
     * 参数设置
     */
    public function actionSetReport() {

        $program_id = $_REQUEST['program_id'];
        $model = new Program('modify');
        $model->_attributes = Program::model()->findByPk($program_id);
//        var_dump($model);
        $this->renderPartial('set_report',array('model'=>$model,'program_id'=>$program_id));
    }

    /*
     * 更新报告设置
     */
    public function actionUpdateReport(){
        $args = $_POST['Program'];
        $r = Program::updateProgramReport($args);
        echo json_encode($r);
    }

    /**
     * 修改faceset
     */
    public function actionSetFaceset() {
        $args = $_POST['Program'];
        $r = Program::setFaceset($args);
        print_r(json_encode($r));
    }
    //编辑faceset中的人脸(ajax)
    public function actionEditFaceset() {
        $args['start_cnt'] = $_POST['start_cnt'];
        $args['faceset_id'] = $_POST['faceset_id'];
        $args['program_id'] = $_POST['program_id'];
        $args['cnt'] = $_POST['cnt'];
//        var_dump($args);
//        exit;
        $r = Program::editFaceset($args);
        print_r(json_encode($r));
    }
    //添加faceset中的人脸(ajax)
    public function actionAddFaceset() {
        $args['start_cnt'] = $_POST['start_cnt'];
        $args['faceset_id'] = $_POST['faceset_id'];
        $args['program_id'] = $_POST['program_id'];
//        var_dump($args);
//        exit;
        $r = Program::addFaceset($args);
        print_r(json_encode($r));
    }
    //删除faceset中的人脸(ajax)
    public function actionDeleteFaceset() {
        $args['start_cnt'] = $_POST['start_cnt'];
        $args['faceset_id'] = $_POST['faceset_id'];
        $args['program_id'] = $_POST['program_id'];
//        var_dump($args);
//        exit;
        $r = Program::deleteFaceset($args);
        print_r(json_encode($r));
    }
    /**
     * 同步更新faceset
     */
    public function actionUpdateFaceSet() {

        $r['program_id'] = $_REQUEST['program_id'];

//        Program::UpdateFceSet($ptype,$program_id);
        $model = Program::model()->findByPk($r['program_id']);
        $r['faceset_id'] = $model->faceset_id;
        $fa_method = new Face();
        $result = $fa_method::GetFacesetInfo($r['faceset_id']);
        //老的face_id集合
        foreach($result->faces as $cnt => $face){
            $face_old[] = $face;
        }
//        var_dump(count($face_old));
//        exit;
        //新的face_id集合
        $mc_face = ProgramUser::ProgramFaceid($r['program_id']);

        $del_list = array_diff($face_old,$mc_face);//要删去的
        $r['del_count'] = count($del_list);
        $add_list = array_diff($mc_face,$face_old);//要添加的
        $r['add_count'] = count($add_list);
        $r['start_cnt'] = (int)0;
        //获取最新的总包以及分包已入场人员的face_id集合 A
        //与返回的集合B 取交集 得到C
        //A 与 C 的差集得到 D  $add_list
        //B 与 C 的差集得到 E $del_list

        echo json_encode($r);
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['project/list'] = str_replace("r=proj/project/grid", "r=proj/project/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    private function saveSubStaffUrl() {

        $a = Yii::app()->session['list_url'];
        $a['proj/project/substafflist'] = str_replace("r=proj/project/substaffgrid", "r=proj/project/substafflist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 保存子查询链接
     */
    private function saveSubUrl() {

        $a = Yii::app()->session['list_url'];
        $a['project/sublist'] = str_replace("r=proj/project/subgrid", "r=proj/project/sublist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 下载EPSS
     */
    public function actionDownloadEpss() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('download_epss',array('program_id'=>$program_id));
    }

    /**
     * ModelView
     */
    public function actionShowView() {
        $url='https://roboxz.cmstech.sg/dmsapi/filecache/select?uid=860&gid=212&show_radio=1';
        $this->renderPartial('show_view',array('url'=>$url));
    }

    /**
     * 同步workforce人员
     */
    public function actionSyncWorkforce() {
        $program_id = $_REQUEST['program_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $contractor_id = $pro_model->contractor_id;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $root_model = Program::model()->findByPk($root_proid);
        $bes_company_id = $con_model->bes_company_id;
        $bes_project_id = $root_model->bes_project_id;
        $url = 'https://w.beehives.sg/admin/web/index.php?r=manpower/member&app_id=APPP001q&bes_company_id='.$bes_company_id."&bes_project_id=".$bes_project_id."&sc_program_id=".$program_id;
        $this->renderPartial('sync_workforce',array('url'=>$url));
        //https://w.beehives.sg/test/admin/web/index.php?r=manpower/member&app_id=APPP001&bes_company_id=C000031&bes_project_id=P000007&sc_program_id=P000007
    }

    public function actionUploadFile() {
        $project_id = $_REQUEST['project_id'];
        $index = $_REQUEST['index'];
        $son_index = $_REQUEST['son_index'];
        $draw_id = $_REQUEST['draw_id'];
        $this->renderPartial('block_upload', array('project_id' => $project_id,'index'=>$index,'son_index'=>$son_index,'draw_id'=>$draw_id));
    }

    public function actionPdfToPng() {
        $project_id = $_REQUEST['project_id'];
        //后台执行 非阻塞 异步
        $pic_list = exec('php72 /opt/www-nginx/web/test/bimax/protected/yiic pdftopng batch --param1='.$project_id.'  ');
        $r['status'] = '1';
        print_r(json_encode($r));
    }

    public function actionAddDraw(){
        $args['drawing_path'] = $_REQUEST['file_path'];
        $args['project_id'] = $_REQUEST['project_id'];
        $r = ProgramDrawing::addProgramDrawing($args);
        if($r['status'] == '1'){
            exec('php72 /opt/www-nginx/web/test/bimax/protected/yiic pdftopng batch2 --param1='.$r['drawing_id'].' --param2='.$args['project_id'].' >/dev/null  &');
        }
        print_r(json_encode($r));
    }

    /**
     * 在线预览文件
     */
    public function actionPreviewDraw() {
        $draw_id = $_REQUEST['draw_id'];
        $drawing_model = ProgramDrawing::model()->findByPk($draw_id);
        $drawing_path = $drawing_model->drawing_path;
        $this->renderPartial('preview_draw',array('drawing_path'=>$drawing_path));
    }
}
