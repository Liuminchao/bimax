<?php

class AssignuserController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $pageSize = 10;
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('proj_project_user', 'contentHeader');
        $this->bigMenu = Yii::t('proj_project_user', 'bigMenu');
    }

    /**
     * 项目下公司列表
     */
    public function actionSubList() {
        $program_id = $_REQUEST['program_id'];
        $program_list = Program::McScProgramList($program_id);
        $company_list = Contractor::compAllList();
        $this->renderPartial('companylist',array('program_id'=>$program_id,'program_list'=>$program_list,'company_list'=>$company_list));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/assignuser/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('proj_project_user', 'num'), '', '');
        $t->set_header(Yii::t('proj_project_user', 'program_name'), '', '');
        $t->set_header(Yii::t('proj_project_user', 'contractor_name'), '', '');
        $t->set_header(Yii::t('proj_project_user', 'status'), '', '');
        $t->set_header(Yii::t('common', 'action'), '15%', '');
        return $t;
    }
    /**
     * 分包权限查看表头
     */
    private function genSubAuthorityGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/assignuser/subauthoritygrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_staff', 'User_name'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'User_phone'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Work_no'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Work_pass_type'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Nation_type'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'qr_code'), '', 'center');
        $t->set_header(Yii::t('proj_project_user', 'entry_date'), '', 'center');
        $t->set_header(Yii::t('proj_project', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '10%', 'center');
        return $t;
    }
     /**
     * 权限设置表头
     * @return SimpleGrid
     */
    private function genAuthorityGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/assignuser/authoritygrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
//        $t->set_header('<label class="select_all"><input type="checkbox" id="checkAll" class="select_all" name="checkAll" onclick="test(this);"></label>', '', '');
        $t->set_header(Yii::t('comp_staff','User_id'),'','none');
        $t->set_header(Yii::t('proj_project_user', 'name'), '', 'center');
//        $t->set_header(Yii::t('proj_project_user', 'ra_role'), '', 'center');
//        $t->set_header(Yii::t('proj_project_user', 'ptw_role'), '', 'center');
//        $t->set_header(Yii::t('proj_project_user', 'wsh_mbr_flag'), '', 'center');
//        $t->set_header(Yii::t('proj_project_user', 'meeting_flag'), '', 'center');
//        $t->set_header(Yii::t('proj_project_user', 'training_flag'), '', 'center');
        $t->set_header('Company', '', 'center');
        $t->set_header('Designation', '', 'center');
        $t->set_header('Robox Role', '', 'center');
//        $t->set_header(Yii::t('proj_project_user', 'second_role'), '', 'center');
//        $t->set_header(Yii::t('proj_project_user', 'third_role'), '', 'center');
        $t->set_header(Yii::t('proj_project', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }
    
    /**
     * 设备表头
     * @return SimpleGrid
     */
    private function genDeviceGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/assignuser/devicegrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        
        $t->set_header(Yii::t('device', 'device_type'), '', 'center');
        $t->set_header(Yii::t('device', 'device_id'), '', 'center');
        $t->set_header(Yii::t('device', 'device_name'), '', 'center');
        $t->set_header(Yii::t('device', 'status'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Action'), '15%', 'center');
        return $t;
    }
    /**
     * 设备查询
     */
    public function actionDeviceGrid($program_id) {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $args['program_id'] = $program_id;
        $t = $this->genDeviceGrid($program_id);
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = ProgramDevice::queryListByDevice($page, $this->pageSize, $args);
        $arry = ProgramDevice::queryByDevice($args);
        $this->renderPartial('device_list', array('t' => $t, 'program_id' => $program_id,'rows' => $list['rows'],'arry'=>$arry, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 设备列表
     */
    public function actionDeviceList() {
        $this->smallHeader = Yii::t('device', 'smallHeader List');
        $this->contentHeader = Yii::t('proj_project_device', 'contentHeader');
        $ptype = $_REQUEST['ptype'];
        $program_id = $_REQUEST['id'];
        $program_name = $_REQUEST['name'];
        $this->render('devicelist',array('ptype'=>$ptype,'program_id'=>$program_id,'program_name'=>$program_name));
    }
    /**
     * 分包设备表头
     * @return SimpleGrid
     */
    private function genSubDeviceGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/assignuser/subdevicegrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';

        $t->set_header(Yii::t('device', 'device_type'), '', 'center');
        $t->set_header(Yii::t('device', 'device_id'), '', 'center');
        $t->set_header(Yii::t('device', 'device_name'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'qr_code'), '', 'center');
        $t->set_header(Yii::t('proj_project_user', 'entry_date'), '', 'center');
        $t->set_header(Yii::t('device', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '10%', 'center');
        return $t;
    }
    /**
     * 分包设备查询
     */
    public function actionSubDeviceGrid($program_id) {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $args['program_id'] = $program_id;
        $pro_model = Program::model()->findByPk($program_id);
        $contractor_id = $pro_model->contractor_id;
        $t = $this->genSubDeviceGrid($program_id);
        $this->saveUrl();
//        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = ProgramDevice::querySubListByDevice($page, $this->pageSize, $args);
        $arry = ProgramDevice::queryByDevice($args);

        $this->renderPartial('sub_device_list', array('t' => $t, 'program_id' => $program_id,'contractor_id'=>$contractor_id,'rows' => $list['rows'],'arry'=>$arry, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 分包设备列表
     */
    public function actionSubDeviceList() {
        $this->smallHeader = Yii::t('device', 'smallHeader List');
        $this->contentHeader = Yii::t('proj_project_device', 'contentHeader');
        $ptype = $_REQUEST['ptype'];
        $program_id = $_REQUEST['id'];
        $program_name = $_REQUEST['name'];
        $this->render('subdevicelist',array('ptype'=>$ptype,'program_id'=>$program_id,'program_name'=>$program_name));
    }
    /**
     * 分包权限查询
     */
    public function actionSubAuthorityGrid($program_id) {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $args['program_id'] = $program_id;
        $t = $this->genSubAuthorityGrid($program_id);
        //$this->saveSubUrl();

//        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        //var_dump($args);
        $list = ProgramUser::querySubListByUser($page, $this->pageSize, $args);
//        ProgramUser::UpateProgramRole($program_id);
        $arry = ProgramUser::queryByUser($args);
        //var_dump($list['rows']);
        $this->renderPartial('sub_authority_list', array('t' => $t, 'program_id' => $program_id,'arry'=>$arry,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /**
     * 权限查询
     */
    public function actionAuthorityGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
//        $args['program_id'] = $program_id;
        $t = $this->genAuthorityGrid($args['program_id']);
        $this->saveAuthorityUrl();
//        var_dump($this->pageSize);
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = ProgramUser::queryListByUser($page, $this->pageSize, $args);
//        ProgramUser::UpateProgramRole($program_id);
        $arry = ProgramUser::queryByUser($args);
        $this->renderPartial('authority_list', array('t' => $t, 'program_id' => $args['program_id'],'arry'=>$arry,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
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
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = Program::queryListBySc($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 权限列表
     */
    public function actionSubAuthorityList() {
        $program_id = $_REQUEST['id'];
        $program_name = $_REQUEST['name'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        //var_dump($args);
        if($program_id <> '')
            $program_model = Program::model()->findByPk($program_id);
        $this->contentHeader = Yii::t('proj_project_user', 'authority_set');
        $this->smallHeader = $program_model->program_name;
        $contractor_id = $program_model->contractor_id;
        $contractor_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $contractor_model->contractor_name;
        $this->smallHeader = Yii::t('proj_project_user', 'smallHeader List');
        $this->smallHeader = $contractor_name;
        $this->render('subauthoritylist', array('args' => $args, 'program_id' => $program_id));
    }

    /**   
     * 权限列表
     */
    public function actionAuthorityList() {
        $program_id = $_REQUEST['id'];
        $program_name = $_REQUEST['name'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['program_id']){
                $program_id = $args['program_id'];
            }
        }
        if($program_id <> '')
            $program_model = Program::model()->findByPk($program_id);

        $this->contentHeader = Yii::t('proj_project_user', 'authority_set');
        $this->smallHeader = 'Manpower & Staffing';
        //$this->smallHeader = Yii::t('proj_project_user', 'smallHeader List');
        $this->render('authoritylist', array('program_id' => $program_id,'args'=>$args));
    }
    
    
    /**
     * 列表
     */
    public function actionList() {

        $this->smallHeader = Yii::t('proj_project_user', 'smallHeader List');
        $this->render('list');
    }
    
    /**
     * 标签页
     */
    public function actionTab() {
        $program_id = $_GET['id'];
        $ptype = $_GET['ptype'];
        if($program_id <> '')
            $program_model = Program::model()->findByPk($program_id);
        $this->smallHeader = $program_model->program_name;
        $this->contentHeader = Yii::t('proj_project_user', 'smallHeader List');
        $this->bigMenu = $program_model->program_name;
        
        $this->render('tabs',array('id'=>$program_id,'ptype'=>$ptype));
    }
    /**
     * 提交人员入场申请
     */
    public function actionUserApply() {
        
        $program_id = $_GET['id'];
        $mc_program_id = $_GET['program_id'];
//        $subcon_type = $_GET['subcon_type'];
        if($program_id <> '')
            $program_model = Program::model()->findByPk($program_id);
            $contractor_id = $program_model->contractor_id;
            $contractor_model = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_model->contractor_name;

        $this->smallHeader = $contractor_name;
        $this->contentHeader = Yii::t('proj_project_user', 'smallHeader Member List');
        $this->bigMenu = $program_model->program_name;
       
        $model = new Program('modify');
        
        $r = array();
        $contractor_id = $program_model->contractor_id;//承包商编号
        $root_proid = $program_model->root_proid;//总包项目ID
        //默认勾选人员数组
        $select_List = (array)ProgramUser::myUserListBySuccess($program_id, $contractor_id);
        //var_dump($select_List);
        if (isset($_POST['Program'])) {
            $args = $_POST['Program'];
            if($mc_program_id == $program_id){
                $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            }else{
                $mc_program_model = Program::model()->findByPk($program_id);
                $args['contractor_id'] = $mc_program_model->contractor_id;
            }
            if($args['program_id']==""){
                $args['program_id']=$program_id;
            }

            $r = ProgramUser::SubmitApplicationsUser($args);
            $msg = $r;
            //var_dump($r);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Program'];
                Yii::app()->user->setFlash('error', '申请失败!');
            }else{
                Yii::app()->user->setFlash('success', '申请成功!');
                $this->refresh();
            }
        }
        
        $staff_List = (array)User::userListByRole($contractor_id,$root_proid);
          
        //$this->render('tabs', array('model' => $program_model, 'msg' => $r,'_mode_'=>'edit','staff_List' => $staff_List, 'select_List' => $select_List));
        $this->render('user_form', array('model' => $model,'mc_program_id'=>$mc_program_id,'program_id' => $program_id,'staff_List' => $staff_List, 'select_List' => $select_List));
    }
    
    /**
     * 提交设备入场申请
     */
    public function actionDeviceApply() {
        
        $program_id = $_GET['id'];
//        var_dump($program_id);
//        exit;
//        $subcon_type = $_GET['subcon_type'];
        if($program_id <> '')
            $program_model = Program::model()->findByPk($program_id);
       
        $this->smallHeader = $program_model->program_name;
        $this->contentHeader = Yii::t('proj_project_device', 'contentHeader');
        $this->bigMenu = $program_model->program_name;
       
        $model = new Program('modify');
        
        $r = array();
        $contractor_id = $program_model->contractor_id;
        //默认勾选设备数组
        $select_List = (array)ProgramDevice::myDeviceListBySuccess($program_id, $contractor_id);
        if (isset($_POST['Device'])) {
            $args = $_POST['Device'];
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            if($args['program_id']==""){
                $args['program_id']=$program_id;
            }
            $r = ProgramDevice::SubmitApplicationsDevice($args);
            $msg = $r;
            //var_dump($r);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Program'];
                Yii::app()->user->setFlash('error', 'Apply error!');
            }else{
                Yii::app()->user->setFlash('success', 'Apply Sccess!');
                $this->refresh();
            }
        }
        
        $type_list = (array)DeviceType::deviceList();
        $device_list  = (array)Device::deviceList($contractor_id);
        //$this->render('tabs', array('model' => $program_model, 'msg' => $r,'_mode_'=>'edit','staff_List' => $staff_List, 'select_List' => $select_List));
        $this->render('device_form', array('model' => $model,'program_id' => $program_id,'device_list' => $device_list,'type_list'=>$type_list, 'select_List' => $select_List));
    }
    
    /**
     * 删除
     */
    public function actionStart() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = Program::deleteProgram($id);
        }
        echo json_encode($r);
    }
    
    /**
     * 申请人员入场
     */
    public function actionEntranceUser() {
        $str = trim($_REQUEST['list']);
        $program_id = $_REQUEST['program_id'];
        $row = explode('|',$str);
//        $cnt = count($row);
//        var_dump($row);
//        exit;
//        $r = array();
//        $r['program_id'] = $program_id;
//        $r['cnt'] = $cnt;
//        $r['start_cnt'] = (int)0;
        if ($_REQUEST['confirm']) {
            $r = ProgramUser::EntranceUser($program_id,$row);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 人员申请入场更新faceid
     */
    public function actionEntranceUserFace() {
        $str = trim($_REQUEST['list']);
        $program_id = $_REQUEST['program_id'];
        $row = explode('|',$str);
        $start_cnt = $_REQUEST['start_cnt'];
        $cnt = $_REQUEST['cnt'];
        $user_list = array_slice($row,$start_cnt,$cnt);
        $r = ProgramUser::EntranceUser($program_id,$user_list,$start_cnt,$cnt);
    }
    /**
     * 申请人员出场
     */
    public function actionLeaveUser() {
        $program_id = trim($_REQUEST['program_id']);
        $user_id = trim($_REQUEST['user_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramUser::LeaveUser($program_id,$user_id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 申请人员批量出场
     */
    public function actionBatchLeaveUser() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['id'];
        $r = array();
        $user_list = explode('|',$tag);
        if ($program_id) {
            $r = ProgramUser::BatchLeaveUser($program_id,$user_list);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 删除申请人员
     */
    public function actionDeleteUser() {
        $program_id = trim($_REQUEST['program_id']);
        $user_id = trim($_REQUEST['user_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramUser::DeleteUser($program_id,$user_id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 申请设备批量出场
     */
    public function actionBatchLeaveDevice() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['id'];
        $r = array();
        $device_list = explode('|',$tag);
        if ($program_id) {
            $r = ProgramDevice::BatchLeaveDevice($program_id,$device_list);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 设备恢复可见
     */
    public function actionVisibleDevice() {
        $program_id = trim($_REQUEST['program_id']);
        $primary_id = trim($_REQUEST['primary_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramDevice::VisibleDevice($program_id,$primary_id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 设备恢复不可见
     */
    public function actionInvisibleDevice() {
        $program_id = trim($_REQUEST['program_id']);
        $primary_id = trim($_REQUEST['primary_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramDevice::InvisibleDevice($program_id,$primary_id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 申请设备入场
     */
    public function actionEntranceDevice() {
        $str = trim($_REQUEST['list']);
        $program_id = $_REQUEST['program_id'];
        $row = explode('|',$str);
//        var_dump($row);
//        exit;
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramDevice::EntranceDevice($program_id,$row);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 申请设备出场
     */
    public function actionLeaveDevice() {
        $program_id = trim($_REQUEST['program_id']);
        $primary_id = trim($_REQUEST['primary_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramDevice::LeaveDevice($program_id,$primary_id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 删除申请设备
     */
    public function actionDeleteDevice() {
        $program_id = trim($_REQUEST['program_id']);
        $primary_id = trim($_REQUEST['primary_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramDevice::DeleteDevice($program_id,$primary_id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 设置权限
     */
    public function actionSetAuthority() {
        $args['user_id'] = $_REQUEST['user_id'];
        $args['program_id'] = $_REQUEST['program_id'];
        $args['name'] = $_REQUEST['name'];
        $args['pk'] = $_REQUEST['pk'];
        $args['value'] = $_REQUEST['value'];
//        var_dump($args);
//        exit;
        $rs = ProgramUser::SetAuthority($args);
        echo json_encode($rs);
    }
    /*
     * 设置select2数据源
     */
    public function actionGetSource() {
        $args = array();
        $args = Role::roleselectList();
        
        print_r( json_encode($args,true));
    }
    /**
     * 培训人角色数据源
     */
    public function actionGetTrainSource() {
        if (Yii::app()->language == 'zh_CN') {
            $training_flag = array("0" => "否", "1" => "发起者","2" => "批准者");
        }else{
            $training_flag = array("0" => "No","1" => "Conducting","2" => "Approver");
        }
        print_r( json_encode($training_flag,true));
    }
    /**
     * 会议人角色数据源
     */
    public function actionGetMeetingSource() {
        if (Yii::app()->language == 'zh_CN') {
            $meeting_flag = array("0" => "否", "1" => "发起者","2" => "批准者");
        }else{
            $meeting_flag = array("0" => "No","1" => "Conducting","2" => "Approver");
        }
        print_r( json_encode($meeting_flag,true));
    }
    /**
      * 会议人角色数据源
     */
    public function actionGetWshSource() {
        if (Yii::app()->language == 'zh_CN') {
            $wsh_mbr_flag = array("0" => "否", "1" => "是");
        }else{
            $wsh_mbr_flag = array("0" => "No", "1" => "Yes");
        }
        print_r( json_encode($wsh_mbr_flag,true));
    }
    /**
     * 许可证角色数据源
     */
    public function actionGetptwSource() {
        $program_id = $_REQUEST['program_id'];
        $program_model = Program::model()->findByPk($program_id);
        if($program_model->params != '0'){
            $params = json_decode($program_model->params,true);
        }else{
            $params['ptw_mode'] = 'A';
        }

//        var_dump($params);
        if (Yii::app()->language == 'zh_CN') {
            if($params['ptw_mode'] == 'A'){
                $ptw_role = array("0" => "否", "1" => "申请者","2" => "审批者","3" => "批准者");
            }else if($params['ptw_mode'] == 'B'){
                $ptw_role = array("0" => "否", "1" => "申请者","2" => "审批者","3" => "批准者","4" => '审批者2');
            }else if($params['ptw_mode'] == 'C'){
                $ptw_role = array("0" => "否", "1" => "申请者","2" => "审批者","3" => "批准者","5" => '检查者');
            }
        }else{
            if($params['ptw_mode'] == 'A'){
                $ptw_role = array("0" => "No","1" => "Applicant","2" => "Assessor","3" => "Approver");
            }else if($params['ptw_mode'] == 'B'){
                $ptw_role = array("0" => "No","1" => "Applicant","2" => "Assessor","3" => "Approver","4" => 'Assessor2');
            }else if($params['ptw_mode'] == 'C'){
                $ptw_role = array("0" => "No","1" => "Applicant","2" => "Assessor","3" => "Approver","5" => 'Checker');
            }

        }
//        var_dump($ptw_role);
//        exit;
        print_r( json_encode($ptw_role,true));
    }
    /**
     * 风险评估角色数据源
     */
    public function actionGetraSource() {
        if (Yii::app()->language == 'zh_CN') {
            $ra_role = array("0" => "否", "1" => "批准者","2" => "领导","3" => "成员");
        }else{
            $ra_role = array("0" => "No","1" => "Approver","2" => "Leader","3" => "Member");
        }
        print_r( json_encode($ra_role,true));
    }
    /**
     * 下载人员PDF（新）
     */
    public static function actionDownloadStaff() {
        $program_id = $_REQUEST['program_id'];
        $user_id = $_REQUEST['user_id'];
        $params['program_id'] = $program_id;
        $params['user_id'] = $user_id;
        $app_id = 'USER';
        DownloadPdf::transferDownload($params,$app_id);
    }

    /**
     * EPSS设置
     */
    public  function actionEpss(){
        $program_id = $_REQUEST['program_id'];
        $user_id = $_REQUEST['user_id'];

        if($program_id <> '')
            $program_model = Program::model()->findByPk($program_id);

        if($user_id <> '')
            $staff_model = Staff::model()->findByPk($user_id);

        $this->smallHeader = $program_model->program_name.' '.$staff_model->user_name;
        $this->contentHeader = 'EPSS';
        $this->bigMenu = $program_model->program_name;

        $model = new ProgramUser('modify');
        $model->_attributes = ProgramUser::model()->find('user_id=:user_id and program_id=:program_id', array(':user_id' => $user_id,':program_id' => $program_id));
        $r = array();
        $contractor_id = $program_model->contractor_id;
        //默认勾选设备数组
        if (isset($_POST['ProgramUser'])) {
            $args = $_POST['ProgramUser'];
            $r = ProgramUser::SubmitApplicationsEpss($args);
            $msg = $r;
            echo "<script language=JavaScript> location.replace(location.href);</script>";
            //var_dump($r);
        }

        $this->render('epss_form', array('model' => $model,'program_id' => $program_id,'user_id' => $user_id,'msg'=>$msg));
    }

    /**
     * 根据角色组查询角色
     */
    public function actionQueryTeam() {
        $team_id = $_POST['teamid'];
        if($team_id == ''){
            print_r(json_encode(array()));
        }

        $rows = EpssRole::roleListByTeam($team_id);

        print_r(json_encode($rows));
    }

    /**
     * 下载新人员入场PDF
     */
    public static function actionDownloadUser() {
        $program_id = $_REQUEST['program_id'];
        $user_id = $_REQUEST['user_id'];
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $arry['contractor_id'] = $contractor_id;
        $program_list = Program::programAllList($arry);//项目列表
        $program_name = $program_list[$program_id];//项目名称
        $staff_model = Staff::model()->findByPk($user_id);//员工信息
        $staffinfo_model = StaffInfo::model()->findByPk($user_id);//员工资质信息
        $roleList = Role::roleList();//岗位列表
        $teamList = Role::teamList();//团队列表
        $roleList['null'] = 'No';
        $qrcode =  $staff_model->qrcode;
        $home_id_photo = $staffinfo_model->home_id_photo;
        $bca_photo = $staffinfo_model->bca_photo;
        $csoc_photo = $staffinfo_model->csoc_photo;
        $ppt_photo = $staffinfo_model->ppt_photo;
        $face_img = $staffinfo_model->face_img;
        $programuser_list = ProgramUser::PersonelAuthority($user_id, $program_id);//项目成员信息
        $authority_list = ProgramUser::AllRoleList();
        //var_dump($programuser_model);
        $approve_id = $programuser_list[0]['entrance_apply_id'];//入场审批编号
        $approve_info = CheckApplyDetail::dealList($approve_id);
        $user_list = Staff::userAllList();//员工姓名
        $photo_list =  StaffInfo::staffinfoPhoto($user_id);
        $contractor_list = Contractor::compList();//承包商名称
        $lang = "_en";
        $showtime=Utils::DateToEn(date("Y-m-d"));//当前时间
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_file_path'] . '/USER' . $user_id . $lang . '.pdf';
        $pdf_title = 'User' . $user_id . $lang . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads' . '/PTW' . $id . $lang . '.pdf';
         //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
//        var_dump($filepath);
//        exit;
        $title = Yii::t('proj_project_user', 'pdf_title');
        $header_title = Yii::t('proj_project_user','header_title');
        ///opt/www-nginx/web/test/ctmgr

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $main_model->contractor_name;
        $logo_pic = $main_model->remark;
        if($logo_pic){
            $logo = '/opt/www-nginx/web'.$logo_pic;
            $pdf->SetHeaderData($logo, 20, $header_title, $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }else{
            $pdf->SetHeaderData('', 0, $header_title, $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体   

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体   
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距   
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
        //设置分页   
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode   
        $pdf->setFontSubsetting(true);
        //设置字体   
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //英文
        }
        $pdf->AddPage();
        //员工信息
        $staff_html =
             '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td colspan="2"><h5 align="center">'.$title.'</h5></td></tr><tr><td width="30%">'.Yii::t('proj_project_user', 'personel_name').'</td><td width="70%">'.$user_list[$user_id].'</td></tr><tr><td width="30%">'
            . Yii::t('proj_project_user', 'company_name') . '</td><td width="70%" height="30px">'. $contractor_list[$contractor_id].'</td></tr><tr><td width="30%">'
            . Yii::t('proj_project', 'program_name') . '</td><td width="70%">'. $program_name.'</td></tr><tr><td width="30%">'
            . Yii::t('proj_project_user', 'bca_pass_no') . '</td><td width="70%">'.$staff_model->work_no.'</td></tr><tr><td width="30%">'
            . Yii::t('proj_project_user', 'Group') . '</td><td width="70%">'.$teamList[$stuff_model->role_id].'</td></tr><tr><td width="30%">'
            . Yii::t('proj_project_user', 'Role_id') .'</td><td width="70%">'.$roleList[$stuff_model->role_id].'</td></tr>';
        //拍照记录
        $personel_html = '<tr><td width="30%" height="120px">'
            . Yii::t('proj_project_user', 'personel_photo') . '</td><td width="70%"></td></tr>';
        $personel_x = 80;
        if($face_img){
            $pdf->Image($face_img, $personel_x, 85, 30, 30, 'JPG', '', '',  false, 300, '', false, false, 0, false, false, false);
        }
        if($qrcode){
            $pdf->Image($qrcode, $personel_x+35, 86, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 0, false, false, false);
        }
        //风险评估职责
        $ra_role = $programuser_list[0]['ra_role'];
        $rarole_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'ra_role') .'</td><td width="70%">'
            .$authority_list['ra_role'][$ra_role].'</td></tr>' ;
        //许可证成员
        $ptw_role = $programuser_list[0]['ptw_role'];    
        $ptwrole_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'ptw_role') .'</td><td width="70%">'
            .$authority_list['ptw_role'][$ptw_role].'</td></tr>';
        //安全委员会委员 
        $wsh_mbr_flag = $programuser_list[0]['wsh_mbr_flag'];
        $wsh_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'wsh_mbr_flag') .'</td><td width="70%">'
            .$authority_list['wsh_mbr_flag'][$wsh_mbr_flag].'</td></tr>' ;
        //举行会议人
        $meeting_flag = $programuser_list[0]['meeting_flag'];
        $meeting_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'meeting_flag') .'</td><td width="70%">'
            .$authority_list['meeting_flag'][$meeting_flag].'</td></tr>' ;
        //举行培训人
        $training_flag = $programuser_list[0]['training_flag'];
        $training_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'training_flag') . '</td><td width="70%">'
            .$authority_list['training_flag'][$training_flag].'</td></tr>' ;
        //第一角色
        $program_role = explode('|',$programuser_list[0]['program_role']);
        $firstrole_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'first_role') . '</td><td width="70%">'
            .$roleList[$program_role[0]].'</td></tr>' ;
        //第二角色
        $secondrole_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'second_role') . '</td><td width="70%">'
            .$roleList[$program_role[1]].'</td></tr>' ;
        //第三角色
        $thirdrole_html = '<tr><td width="30%">'
            . Yii::t('proj_project_user', 'third_role') . '</td><td width="70%">'
            .$roleList[$program_role[2]].'</td></tr></table>' ;

        //提交时间
        $submit_html = '<tr><td width="25%">'
            . Yii::t('proj_project_user', 'Submitted on') . '</td><td width="75%">'
            . Utils::DateToEn($programuser_list[0]['record_time']) . '</td></tr>';

        //批准时间
        $approved_on_html = '<tr><td width="25%">'
            . Yii::t('proj_project_user', 'Approved on') . '</td><td width="75%">'
            . Utils::DateToEn($programuser_list[0]['apply_date']) . '</td></tr>';

        //批准人
        $approved_by_html = '<tr><td width="25%">'
            . Yii::t('proj_project_user', 'Approved by') . '</td><td width="75%">'
            . $user_list[$approve_info[0]['deal_user_id']] . '</td></tr></table>';

        $html = $staff_html . $personel_html . $rarole_html . $ptwrole_html . $wsh_html . $meeting_html . $training_html  . $firstrole_html . $secondrole_html . $submit_html .$approved_on_html .$approved_by_html;

        $pdf->writeHTML($html, true, false, true, false, '');

        $img_num = 0;//检验页码标志

        //身份证照片
        $home_html = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td width="25%" height="150px">'
            . Yii::t('proj_project_user', 'hpme_id_photo') .'</td><td width="75%"></td></tr>';
        $x = 30;
        $y_1 = 30;//第一张y的位置
        $y_2 = 150;//第二张y的位置
        //$home_id_photo
        if($home_id_photo){
            $pdf->AddPage();//再加一页
            $img_num = $img_num +1;
            $pdf->Image($home_id_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
        }
        //护照照片
        $ppt_html = '<tr><td width="25%" height="150px">'
            . Yii::t('proj_project_user', 'ppt_photo') .'</td><td width="75%"></td></tr>';
        //$ppt_photo
        if($ppt_photo){
            if($img_num%2  == 0 ) {
                $pdf->AddPage();//再加一页
                $img_num = $img_num + 1;
                $pdf->Image($ppt_photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
            }else{
                $img_num = $img_num +1;
                $pdf->Image($ppt_photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
            }
        }
        //安全证照片
        $csoc_html = '<tr><td width="25%" height="150px">'
            . Yii::t('proj_project_user', 'csoc_photo') .'</td><td width="75%"></td></tr>';
        //$csoc_photo
        if($csoc_photo){
            if($img_num%2  == 0 ){
                $pdf->AddPage();//再加一页
                $img_num = $img_num +1;
                $pdf->Image($csoc_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }else{
                $img_num = $img_num +1;
                $pdf->Image($csoc_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }

        }
        //准证照片
        $bca_html = '<tr><td width="25%" height="150px">'
            . Yii::t('proj_project_user', 'bca_photo') .'</td><td width="75%"></td></tr></table>';
        //$bca_photo
        if($bca_photo){
            if($img_num%2  == 0 ){
                $pdf->AddPage();//再加一页
                $img_num = $img_num +1;
                $pdf->Image($bca_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }else{
                $img_num = $img_num +1;
                $pdf->Image($bca_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            }
        }
        $aptitude_list =UserAptitude::queryAll($user_id);//人员证书
        if($aptitude_list){
            foreach($aptitude_list as $cnt => $list){
                $aptitude = explode('|',$list['aptitude_photo']);
                foreach($aptitude as $i => $photo){
                    $file = explode('.',$photo);
                    if($file[1] != 'pdf') {
                        if ($img_num % 2 == 0) {
                            $pdf->AddPage();//再加一页
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        } else {
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        }
                    }
                }
            }
        }
        $html_2 = $home_html . $ppt_html . $csoc_html  .  $bca_html;

//        $pdf->writeHTML($html_2, true, false, true, false, '');
        //输出PDF
        $pdf->Output($pdf_title, 'I');

        //$pdf->Output($filepath, 'F'); //保存到指定目录
        //Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }

    /**
     * 人员批量
     */
    public static function actionUserBatch() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['id'];
        $curpage = $_REQUEST['curpage'];
        $array = explode('|',$tag);
//        var_dump($array);
//        exit;
        foreach($array as $cnt => $user_id) {
            if($user_id != '') {
//        $user_id = $_REQUEST['user_id'];
                $contractor_id = Yii::app()->user->getState('contractor_id');
                $arry['contractor_id'] = $contractor_id;
                $program_list = Program::programAllList($arry);//项目列表
                $program_name = $program_list[$program_id];//项目名称
                $staff_model = Staff::model()->findByPk($user_id);//员工信息
                $staffinfo_model = StaffInfo::model()->findByPk($user_id);//员工资质信息
                $roleList = Role::roleList();//岗位列表
                $roleList['null'] = 'No';
                $qrcode =  $staff_model->qrcode;
                $home_id_photo = $staffinfo_model->home_id_photo;
                $bca_photo = $staffinfo_model->bca_photo;
                $csoc_photo = $staffinfo_model->csoc_photo;
                $ppt_photo = $staffinfo_model->ppt_photo;
                $face_img = $staffinfo_model->face_img;
                $programuser_list = ProgramUser::PersonelAuthority($user_id, $program_id);//项目成员信息
                $authority_list = ProgramUser::AllRoleList();
                //var_dump($programuser_model);
                $approve_id = $programuser_list[0]['entrance_apply_id'];//入场审批编号
                $approve_info = CheckApplyDetail::dealList($approve_id);
                $user_list = Staff::userAllList();//员工姓名
                $photo_list = StaffInfo::staffinfoPhoto($user_id);
                $contractor_list = Contractor::compList();//承包商名称
                $lang = "_en";
                $showtime = Utils::DateToEn(date("Y-m-d"));//当前时间
                if (Yii::app()->language == 'zh_CN') {
                    $lang = "_zh"; //中文
                }
                //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
                $filepath = Yii::app()->params['upload_tmp_path'] . '/USER' . $user_id . $lang . '.pdf';
                $file_res[] = $filepath;
                $pdf_title = 'User' . $user_id . $lang . '.pdf';
                $title = Yii::t('proj_project_user', 'pdf_title');

                $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
                require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
                $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
                // 设置文档信息
                $pdf->SetCreator(Yii::t('login', 'Website Name'));
                $pdf->SetAuthor(Yii::t('login', 'Website Name'));
                $pdf->SetTitle($title);
                $pdf->SetSubject($title);
                //$pdf->SetKeywords('PDF, LICEN');
                // 设置页眉和页脚信息
                $main_model = Contractor::model()->findByPk($contractor_id);
                $contractor_name = $main_model->contractor_name;
                $header_title = Yii::t('proj_project_user','header_title');
                $logo_pic = $main_model->remark;
                if($logo_pic){
                    $logo = '/opt/www-nginx/web'.$logo_pic;
                    $pdf->SetHeaderData($logo, 20, $header_title, $contractor_name, array(0, 64, 255), array(0, 64, 128));
                }else{
                    $pdf->SetHeaderData('', 0, $header_title, $contractor_name, array(0, 64, 255), array(0, 64, 128));
                }
                $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

                // 设置页眉和页脚字体

                if (Yii::app()->language == 'zh_CN') {
                    $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
                } else if (Yii::app()->language == 'en_US') {
                    $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
                }

                $pdf->setFooterFont(Array('helvetica', '', '8'));

                //设置默认等宽字体
                $pdf->SetDefaultMonospacedFont('courier');

                //设置间距
                $pdf->SetMargins(15, 27, 15);
                $pdf->SetHeaderMargin(5);
                $pdf->SetFooterMargin(10);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                //设置分页
                $pdf->SetAutoPageBreak(TRUE, 25);
                //set image scale factor
                $pdf->setImageScale(1.25);
                //set default font subsetting mode
                $pdf->setFontSubsetting(true);
                //设置字体
                if (Yii::app()->language == 'zh_CN') {
                    $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
                } else if (Yii::app()->language == 'en_US') {
                    $pdf->SetFont('droidsansfallback', '', 14, '', true); //英文
                }

                $pdf->AddPage();
                //员工信息
                $staff_html =
                    '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td colspan="2"><h5 align="center">' . $title . '</h5></td></tr><tr><td width="25%">' . Yii::t('proj_project_user', 'personel_name') . '</td><td width="75%">' . $user_list[$user_id] . '</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project_user', 'company_name') . '</td><td width="75%">' . $contractor_list[$contractor_id] . '</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project', 'program_name') . '</td><td width="75%">' . $program_name . '</td></tr><tr><td width="25%">'
                    . Yii::t('comp_staff', 'bca_pass_no') . '</td><td width="75%">' . $staff_model->work_no . '</td></tr><tr><td width="25%">'
                    . Yii::t('comp_staff', 'Role_id') . '</td><td width="75%">' . $roleList[$staff_model->role_id] . '</td></tr>';

                //拍照记录
                $personel_html = '<tr><td width="25%" height="120px">'
                    . Yii::t('proj_project_user', 'personel_photo') . '</td><td width="75%"></td></tr>';
                $personel_x = 75;
                if ($face_img) {
                    $pdf->Image($face_img, $personel_x, 85, 30, 30, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                }
                if($qrcode){
                    $pdf->Image($qrcode, $personel_x+35, 85, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 0, false, false, false);
                }
                //风险评估职责
                $ra_role = $programuser_list[0]['ra_role'];
                $rarole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'ra_role') . '</td><td width="75%">'
                    . $authority_list['ra_role'][$ra_role] . '</td></tr>';
                //许可证成员
                $ptw_role = $programuser_list[0]['ptw_role'];
                $ptwrole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'ptw_role') . '</td><td width="75%">'
                    . $authority_list['ptw_role'][$ptw_role] . '</td></tr>';
                //安全委员会委员
                $wsh_mbr_flag = $programuser_list[0]['wsh_mbr_flag'];
                $wsh_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'wsh_mbr_flag') . '</td><td width="75%">'
                    . $authority_list['wsh_mbr_flag'][$wsh_mbr_flag] . '</td></tr>';
                //举行会议人
                $meeting_flag = $programuser_list[0]['meeting_flag'];
                $meeting_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'meeting_flag') . '</td><td width="75%">'
                    . $authority_list['meeting_flag'][$meeting_flag] . '</td></tr>';
                //举行培训人
                $training_flag = $programuser_list[0]['training_flag'];
                $training_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'training_flag') . '</td><td width="75%">'
                    . $authority_list['training_flag'][$training_flag] . '</td></tr>';
                //项目角色
                $program_role = explode('|', $programuser_list[0]['program_role']);
//                $mainrole_html = '<tr><td width="25%">'
//                    . Yii::t('proj_project_user', 'main_role') . '</td><td width="75%">'
//                    . $roleList[$program_role[0]] . '</td></tr>';
                //第一角色
                $firstrole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'first_role') . '</td><td width="75%">'
                    . $roleList[$program_role[1]] . '</td></tr>';
                //第二角色
                $secondrole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'second_role') . '</td><td width="75%">'
                    . $roleList[$program_role[2]] . '</td></tr>';

                //提交时间
                $submit_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'Submitted on') . '</td><td width="75%">'
                    . Utils::DateToEn($programuser_list[0]['record_time']) . '</td></tr>';

                //批准时间
                $approved_on_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'Approved on') . '</td><td width="75%">'
                    . Utils::DateToEn($programuser_list[0]['apply_date']) . '</td></tr>';

                //批准人
                $approved_by_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'Approved by') . '</td><td width="75%">'
                    . $user_list[$approve_info[0]['deal_user_id']] . '</td></tr></table>';

                $html = $staff_html . $personel_html . $rarole_html . $ptwrole_html . $wsh_html . $meeting_html . $training_html  . $firstrole_html . $secondrole_html . $submit_html .$approved_on_html .$approved_by_html;

                $pdf->writeHTML($html, true, false, true, false, '');

                $img_num = 0;//检验页码标志

                //身份证照片
                $home_html = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'hpme_id_photo') .'</td><td width="75%"></td></tr>';
                $x = 30;
                $y_1 = 30;//第一张y的位置
                $y_2 = 150;//第二张y的位置
                //$home_id_photo
                if($home_id_photo){
                    $pdf->AddPage();//再加一页
                    $img_num = $img_num +1;
                    $pdf->Image($home_id_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                }
                //护照照片
                $ppt_html = '<tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'ppt_photo') .'</td><td width="75%"></td></tr>';
                //$ppt_photo
                if($ppt_photo){
                    if($img_num%2  == 0 ) {
                        $pdf->AddPage();//再加一页
                        $img_num = $img_num + 1;
                        $pdf->Image($ppt_photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                    }else{
                        $img_num = $img_num +1;
                        $pdf->Image($ppt_photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                    }
                }
                //安全证照片
                $csoc_html = '<tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'csoc_photo') .'</td><td width="75%"></td></tr>';
                //$csoc_photo
                if($csoc_photo){
                    if($img_num%2  == 0 ){
                        $pdf->AddPage();//再加一页
                        $img_num = $img_num +1;
                        $pdf->Image($csoc_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }else{
                        $img_num = $img_num +1;
                        $pdf->Image($csoc_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }

                }
                //准证照片
                $bca_html = '<tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'bca_photo') .'</td><td width="75%"></td></tr></table>';
                //$bca_photo
                if($bca_photo){
                    if($img_num%2  == 0 ){

                        $pdf->AddPage();//再加一页
                        $img_num = $img_num +1;
                        $pdf->Image($bca_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }else{
                        $img_num = $img_num +1;
                        $pdf->Image($bca_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }
                }
                $aptitude_list =UserAptitude::queryAll($user_id);//人员证书
                if($aptitude_list){
                    foreach($aptitude_list as $cnt => $list){
                        $aptitude = explode('|',$list['aptitude_photo']);
                        foreach($aptitude as $i => $photo){
                            $file = explode('.',$photo);
                            if($file[1] != 'pdf') {
                                if ($img_num % 2 == 0) {
                                    $pdf->AddPage();//再加一页
                                    $img_num = $img_num + 1;
                                    $pdf->Image($photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                                } else {
                                    $img_num = $img_num + 1;
                                    $pdf->Image($photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                                }
                            }
                        }
                    }
                }
                $html_2 = $home_html . $ppt_html . $csoc_html  .  $bca_html;

                //输出PDF
                $pdf->Output($filepath, 'F'); //保存到指定目录

            }
        }
        $filename = "/opt/www-nginx/web/filebase/tmp/bak".$curpage.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            foreach ($file_res as $cnt => $path) {
                $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            }
            $zip->close();
        }
        foreach ($file_res as $cnt => $path) {
            unlink($path);
        }
        $r['filename'] = $filename;
        echo json_encode($r);
    }
    /**
     * 下载入场压缩包
     */
    public static function actionCompress(){
        $filename = $_REQUEST['filename'];
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
        $name = basename($filename);
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

    }
    /**
     * 下载设备PDF（新）
     */
    public static function actionDownloadEquipment() {
        $program_id = $_REQUEST['program_id'];
        $primary_id = $_REQUEST['primary_id'];
        $params['program_id'] = $program_id;
        $params['primary_id'] = $primary_id;
        $app_id = 'DEVICE';
        DownloadPdf::transferDownload($params,$app_id);
    }
    /**
     * 下载新设备入场PDF
     */
    public static function actionDownloadDevice() {

        $program_id = $_REQUEST['program_id'];
        $device_id = $_REQUEST['primary_id'];
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $arry['contractor_id'] = $contractor_id;
        $program_list = Program::programAllList($arry);//项目列表
        $program_name = $program_list[$program_id];//项目名称
        $device_model = Device::model()->findByPk($device_id);//设备信息
//        $device_model = Device::model()->find('device_id=:device_id', array(':device_id' => $device_id));
        $type_no = $device_model->type_no;
        $devicetype_model = DeviceType::model()->findByPk($type_no);//设备类型信息
        $device_img = $device_model->device_img;//设备图片
        $qrcode = $device_model->qrcode;//设备二维码
        $device_list = ProgramDevice::PersonelDevice($device_id, $program_id);//项目设备信息
        //var_dump($programuser_model);
        $device_name = $device_model->device_name;//设备名称
        $device_id = $device_model->device_id;//设备编号
        $device_type = $devicetype_model->device_type_en;//设备型号
        $device_startdate = $device_model->permit_startdate;//设备许可证开始日期
        $device_enddate = $device_model->permit_enddate;//设备许可证结束日期
        $contractor_list = Contractor::compList();//承包商名称列表
        $lang = "_en";
        $showtime=Utils::DateToEn(date("Y-m-d"));//当前时间
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_file_path'] . '/Device' . $device_id . $lang . '.pdf';
        $pdf_title = 'Device' . $device_id . $lang . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads' . '/PTW' . $id . $lang . '.pdf';
         //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
//        var_dump($filepath);
//        exit;
        $title = Yii::t('proj_project_device', 'pdf_title');
        ///opt/www-nginx/web/test/ctmgr

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        $device_detail = Yii::t('proj_project_device','device_detail');
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $main_model->contractor_name;
        $logo_pic = $main_model->remark;
        if($logo_pic){
            $logo = '/opt/www-nginx/web'.$logo_pic;
            $pdf->SetHeaderData($logo, 20, $device_detail, $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }else{
            $pdf->SetHeaderData('', 0, $device_detail, $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体   

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体   
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距   
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
        //设置分页   
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode   
        $pdf->setFontSubsetting(true);
        //设置字体   
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //英文
        }

        $pdf->AddPage();
        //信息翻译
        if (Yii::app()->language == 'zh_CN') {
            
        } else if (Yii::app()->language == 'en_US') {
            
        }
        //设备信息
        $device_html = 
             '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td colspan="2"><h5 align="center">'.$title.'</h5></td></tr><tr><td width="25%">'.Yii::t('proj_project_device', 'device_id').'</td><td width="75%">'.$device_id.'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project_device', 'device_name') . '</td><td width="75%">'. $device_name.'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project_device', 'device_type') . '</td><td width="75%">'. $device_type.'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project_user', 'company_name') . '</td><td width="75%">'. $contractor_list[$contractor_id].'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project', 'program_name') . '</td><td width="75%">'. $program_name.'</td></tr>';
        //设备照片
        $photo_html = '<tr><td width="25%" height="120px">'
            . Yii::t('proj_project_device', 'device_img') .'</td><td width="75%"></td></tr></table>';
        $info_x = 75;
        if($device_img){ 
            $pdf->Image($device_img, $info_x, 91, 30, 30, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            $info_x+=35;
        }
        if($qrcode){
            $pdf->Image($qrcode, $info_x+28, 87, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
        }
        //许可证开始日期
        $startdate_html = '<tr><td width="25%">'
            . Yii::t('proj_project_device', 'start_date') .'</td><td width="75%">'
            .Utils::DateToEn($device_startdate).'</td></tr>' ;

        //许可证结束日期
        $enddate_html = '<tr><td width="25%">'
            . Yii::t('proj_project_device', 'end_date') . '</td><td width="75%">'
            .Utils::DateToEn($device_enddate).'</td></tr>' ;
            
        
        $html = $device_html . $photo_html;

        $pdf->writeHTML($html, true, false, true, false, '');
        $img_num = 0;//检验页码标志
        $x = 30;
        $y_1 = 30;//第一张y的位置
        $y_2 = 150;//第二张y的位置
        $aptitude_list =DeviceInfo::queryAll($device_id);//人员证书
        if($aptitude_list){
            foreach($aptitude_list as $cnt => $list){
                $aptitude = explode('|',$list['certificate_photo']);
                foreach($aptitude as $i => $photo){
                    $file = explode('.',$photo);
                    if($file[1] != 'pdf') {
                        if ($img_num % 2 == 0) {
                            $pdf->AddPage();//再加一页
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        } else {
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        }
                    }
                }
            }
        }
        //输出PDF   
       // $pdf->Output($filepath, 'I');
        $pdf->Output($pdf_title,'D');
        //$pdf->Output($filepath, 'F'); //保存到指定目录
        //Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }

    /**
     * 设备批量
     */
    public static function actionDeviceBatch() {
        $tag = $_REQUEST['tag'];
        $program_id = $_REQUEST['id'];
        $curpage = $_REQUEST['curpage'];
        $array = explode('|',$tag);
        $normal = 0;
//        var_dump($array);
//        exit;
        foreach($array as $cnt => $primary_id) {
            if($primary_id != '') {
//        $user_id = $_REQUEST['user_id'];
                $contractor_id = Yii::app()->user->getState('contractor_id');
                $arry['contractor_id'] = $contractor_id;
                $program_list = Program::programAllList($arry);//项目列表
                $program_name = $program_list[$program_id];//项目名称
                $device_model = Device::model()->findByPk($primary_id);//设备信息
                $device_id = $device_model->device_id;
//                $device_model = Device::model()->find('device_id=:device_id', array(':device_id' => $device_id));
                $type_no = $device_model->type_no;
                $devicetype_model = DeviceType::model()->findByPk($type_no);//设备类型信息
                $device_img = $device_model->device_img;//设备图片
                $qrcode = $device_model->qrcode;//二维码图片
                $device_list = ProgramDevice::PersonelDevice($device_id, $program_id);//项目设备信息
                //var_dump($programuser_model);
                $device_name = $device_model->device_name;//设备名称
                $device_id = $device_model->device_id;//设备编号
                $device_type = $devicetype_model->device_type_en;//设备型号
                $device_startdate = $device_model->permit_startdate;//设备许可证开始日期
                $device_enddate = $device_model->permit_enddate;//设备许可证结束日期
                $contractor_list = Contractor::compList();//承包商名称列表
                $lang = "_en";
                $showtime=Utils::DateToEn(date("Y-m-d"));//当前时间
                if (Yii::app()->language == 'zh_CN') {
                    $lang = "_zh"; //中文
                }
                //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
                $filepath = Yii::app()->params['upload_file_path'] . '/Device' . $device_name . $lang . '.pdf';
                $pdf_title = 'Device' . $device_id . $lang . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads' . '/PTW' . $id . $lang . '.pdf';
                //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
//        var_dump($filepath);
//        exit;
                $normal++;
                $filepath = Yii::app()->params['upload_tmp_path'] . '/DEVICE' . $normal . $lang . '.pdf';
                $file_res[] = $filepath;
                $title = Yii::t('proj_project_device', 'pdf_title');
                ///opt/www-nginx/web/test/ctmgr

                $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
                require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
                $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
                // 设置文档信息
                $pdf->SetCreator(Yii::t('login', 'Website Name'));
                $pdf->SetAuthor(Yii::t('login', 'Website Name'));
                $pdf->SetTitle($title);
                $pdf->SetSubject($title);
                //$pdf->SetKeywords('PDF, LICEN');
                // 设置页眉和页脚信息
                $main_model = Contractor::model()->findByPk($contractor_id);
                $contractor_name = $main_model->contractor_name;
                $device_detail = Yii::t('proj_project_device','device_detail');
                $logo_pic = $main_model->remark;
                if($logo_pic){
                    $logo = '/opt/www-nginx/web'.$logo_pic;
                    $pdf->SetHeaderData($logo, 20, $device_detail, $contractor_name, array(0, 64, 255), array(0, 64, 128));
                }else{
                    $pdf->SetHeaderData('', 0, $device_detail, $contractor_name, array(0, 64, 255), array(0, 64, 128));
                }
                $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

                // 设置页眉和页脚字体

                if (Yii::app()->language == 'zh_CN') {
                    $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
                } else if (Yii::app()->language == 'en_US') {
                    $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
                }

                $pdf->setFooterFont(Array('helvetica', '', '8'));

                //设置默认等宽字体
                $pdf->SetDefaultMonospacedFont('courier');

                //设置间距
                $pdf->SetMargins(15, 27, 15);
                $pdf->SetHeaderMargin(5);
                $pdf->SetFooterMargin(10);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                //设置分页
                $pdf->SetAutoPageBreak(TRUE, 25);
                //set image scale factor
                $pdf->setImageScale(1.25);
                //set default font subsetting mode
                $pdf->setFontSubsetting(true);
                //设置字体
                if (Yii::app()->language == 'zh_CN') {
                    $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
                } else if (Yii::app()->language == 'en_US') {
                    $pdf->SetFont('droidsansfallback', '', 14, '', true); //英文
                }

                $pdf->AddPage();
                //信息翻译
                if (Yii::app()->language == 'zh_CN') {

                } else if (Yii::app()->language == 'en_US') {

                }
                //设备信息
                $device_html =
                    '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td colspan="2"><h5 align="center">'.$title.'</h5></td></tr><tr><td width="25%">'.Yii::t('proj_project_device', 'device_id').'</td><td width="75%">'.$device_id.'</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project_device', 'device_name') . '</td><td width="75%">'. $device_name.'</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project_device', 'device_type') . '</td><td width="75%">'. $device_type.'</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project_user', 'company_name') . '</td><td width="75%">'. $contractor_list[$contractor_id].'</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project', 'program_name') . '</td><td width="75%">'. $program_name.'</td></tr>';
                //设备照片
                $photo_html = '<tr><td width="25%" height="120px">'
                    . Yii::t('proj_project_device', 'device_img') .'</td><td width="75%"></td></tr></table>';
                $info_x = 75;
                if($device_img){
                    $pdf->Image($device_img, $info_x, 91, 30, 30, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    $info_x+=35;
                }
                if($qrcode){
                    $pdf->Image($qrcode, $info_x+28, 87, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                }
                //许可证开始日期
                $startdate_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_device', 'start_date') .'</td><td width="75%">'
                    .Utils::DateToEn($device_startdate).'</td></tr>' ;

                //许可证结束日期
                $enddate_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_device', 'end_date') . '</td><td width="75%">'
                    .Utils::DateToEn($device_enddate).'</td></tr>' ;


                $html = $device_html . $photo_html;

                $pdf->writeHTML($html, true, false, true, false, '');
                $img_num = 0;//检验页码标志
                $x = 30;
                $y_1 = 30;//第一张y的位置
                $y_2 = 150;//第二张y的位置
                $aptitude_list =DeviceInfo::queryAll($device_id);//人员证书
                if($aptitude_list){
                    foreach($aptitude_list as $cnt => $list){
                        $aptitude = explode('|',$list['certificate_photo']);
                        foreach($aptitude as $i => $photo){
                            $file = explode('.',$photo);
                            if($file[1] != 'pdf') {
                                if ($img_num % 2 == 0) {
                                    $pdf->AddPage();//再加一页
                                    $img_num = $img_num + 1;
                                    $pdf->Image($photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                                } else {
                                    $img_num = $img_num + 1;
                                    $pdf->Image($photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                                }
                            }
                        }
                    }
                }

                //输出PDF
                $pdf->Output($filepath, 'F'); //保存到指定目录

            }
        }
        $filename = "/opt/www-nginx/web/filebase/tmp/device".$curpage.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            foreach ($file_res as $cnt => $path) {
                $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            }
            $zip->close();
        }
        foreach ($file_res as $cnt => $path) {
            unlink($path);
        }
        $r['filename'] = $filename;
        echo json_encode($r);
    }
    //检查项目成员情况
    public static function actionStaffInfo(){
        $args = $_REQUEST['q'];
//        var_dump($args);
//        exit;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $rs = ProgramUser::staffinfo($args);
        $r['count'] = count($rs);
        echo json_encode($r);
    }
    //检查项目成员情况
    public static function actionDeviceInfo(){
        $args = $_REQUEST['q'];
//        var_dump($args);
//        exit;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $rs = ProgramDevice::deviceinfo($args);
        $r['count'] = count($rs);
        echo json_encode($r);
    }
    //导出员工信息表
    public static function actionStaffExport(){
        $args = $_GET['q'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $rs = ProgramUser::staffinfo($args);
        $authority_list = ProgramUser::AllRoleList();//角色列表
        $roleList = Role::roleList();//岗位列表
        $category = Staff::Category();
//        var_dump($rs);
//        exit;

//        if (count($rs) <= 0) {
//            header("Content-type:text/html;charset=utf-8");
//            echo "<script>alert('".Yii::t('proj_project_user','error_project_user_null')."');</script>";
//        }
//        var_dump($staffinfo);
//        var_dump($staff);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.'N1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('proj_project_user','project_user_excel'));
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'M2');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',Yii::t('proj_project_user','program_name').'：'.date("d M Y"));
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3',Yii::t('comp_staff','Face_img'));
        $objectPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',Yii::t('comp_staff','User_name'));
        $objectPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('C3',Yii::t('comp_staff','User_phone'));
        $objectPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('D3',Yii::t('comp_staff','Work_no'));
        $objectPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('E3',Yii::t('comp_staff','Work_pass_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('F3',Yii::t('proj_project_user','expiry_date'));
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('G3',Yii::t('proj_project_user','csoc_date'));
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('H3',Yii::t('comp_staff','category'));
        $objectPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('I3',Yii::t('comp_staff','qr_code'));
        $objectPHPExcel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('J3',Yii::t('comp_staff','Nation_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('K3',Yii::t('proj_project_user','ra_role'));
        $objectPHPExcel->getActiveSheet()->getStyle('L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('L3',Yii::t('proj_project_user','ptw_role'));
        $objectPHPExcel->getActiveSheet()->getStyle('M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('M3',Yii::t('proj_project_user','wsh_mbr_flag'));
        $objectPHPExcel->getActiveSheet()->getStyle('N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('N3',Yii::t('proj_project_user','meeting_flag'));
        $objectPHPExcel->getActiveSheet()->getStyle('O3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('O3',Yii::t('proj_project_user','training_flag'));
        $objectPHPExcel->getActiveSheet()->getStyle('P3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('P3',Yii::t('proj_project_user','first_role'));
        $objectPHPExcel->getActiveSheet()->getStyle('Q3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('Q3',Yii::t('proj_project_user','second_role'));
        $objectPHPExcel->getActiveSheet()->getStyle('R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('R3',Yii::t('proj_project_user','third_role'));
        $objectPHPExcel->getActiveSheet()->getStyle('S3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('S3',Yii::t('proj_project_user','entry_date'));
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
//        var_dump($rs);
//        exit;
        foreach ($rs as $k => $v) {
            $expiry_date = UserAptitude::queryExpiryDate($v['user_id']);
            $csoc_date = UserAptitude::queryCsocDate($v['user_id']);
//                static $n = 1;
            /*设置表格高度*/
            $objectPHPExcel->getActiveSheet()->getRowDimension($k+4)->setRowHeight(90);
                if($v['face_img'] !=''){
                    if(substr($v['face_img'],0,1) != '.') {
                        $face_img = '/opt/www-nginx/web' . $v['face_img'];
                        if(file_exists($face_img)) {
                            /*实例化excel图片处理类*/
                            $objDrawing = new PHPExcel_Worksheet_Drawing();
                            /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
                            $objDrawing->setPath('/opt/www-nginx/web' . $v['face_img']);
                            /*设置图片高度*/
                            $objDrawing->setHeight(20);
                            /*设置图片宽度*/
                            $objDrawing->setWidth(80);
//                      //自适应
//                       $objDrawing->setResizeProportional(true);
                            /*设置图片要插入的单元格*/
                            $objDrawing->setCoordinates(A . ($k + 4));
                            /*设置图片所在单元格的格式*/
                            $objDrawing->setOffsetX(30);//30
                            $objDrawing->setOffsetY(5);
                            $objDrawing->setRotation(40);//40
                            $objDrawing->getShadow()->setVisible(true);
                            $objDrawing->getShadow()->setDirection(20);//20
                            $objDrawing->setWorksheet($objActSheet);
                        }
                    }
            }

            $objectPHPExcel->getActiveSheet()->setCellValue(B . ($k + 4),$v['user_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue(C . ($k + 4),$v['user_phone']);
            $objectPHPExcel->getActiveSheet()->setCellValue(D . ($k + 4),$v['work_no']);
            $objectPHPExcel->getActiveSheet()->setCellValue(E . ($k + 4),$v['work_pass_type']);
            $objectPHPExcel->getActiveSheet()->setCellValue(F . ($k + 4),$expiry_date);
            $objectPHPExcel->getActiveSheet()->setCellValue(G . ($k + 4),$csoc_date);
            $objectPHPExcel->getActiveSheet()->setCellValue(H . ($k + 4),$category[$v['category']]);
            if(isset($v['qrcode'])){
                Staff::buildQrCode($args['contractor_id'],$v['user_id']);
            }
            if ($v['qrcode'] !=''){
                $qrcode_path = '/opt/www-nginx/web' . $v['qrcode'];
                if(file_exists($qrcode_path)) {
                    /*实例化excel图片处理类*/
                    $obj_Drawing = new PHPExcel_Worksheet_Drawing();
                    /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
                    $obj_Drawing->setPath('/opt/www-nginx/web' . $v['qrcode']);
                    /*设置图片高度*/
                    $obj_Drawing->setHeight(20);
                    /*设置图片宽度*/
                    $obj_Drawing->setWidth(80);
//                      //自适应
//                       $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $obj_Drawing->setCoordinates(I . ($k + 4));
                    /*设置图片所在单元格的格式*/
                    $obj_Drawing->setOffsetX(30);//30
                    $obj_Drawing->setOffsetY(5);
                    $obj_Drawing->setRotation(40);//40
                    $obj_Drawing->getShadow()->setVisible(true);
                    $obj_Drawing->getShadow()->setDirection(20);//20
                    $obj_Drawing->setWorksheet($objActSheet);
                }
            }
            $objectPHPExcel->getActiveSheet()->setCellValue(J . ($k + 4),$v['nation_type']);
            $objectPHPExcel->getActiveSheet()->setCellValue(K . ($k + 4),$authority_list['ra_role'][$v['ra_role']]);
            $objectPHPExcel->getActiveSheet()->setCellValue(L . ($k + 4),$authority_list['ptw_role'][$v['ptw_role']]);
            $objectPHPExcel->getActiveSheet()->setCellValue(M . ($k + 4),$authority_list['wsh_mbr_flag'][$v['wsh_mbr_flag']]);
            $objectPHPExcel->getActiveSheet()->setCellValue(N . ($k + 4),$authority_list['meeting_flag'][$v['meeting_flag']]);
            $objectPHPExcel->getActiveSheet()->setCellValue(O . ($k + 4),$authority_list['training_flag'][$v['training_flag']]);
            $program_role = explode('|',$v['program_role']);
            $objectPHPExcel->getActiveSheet()->setCellValue(P . ($k + 4),$roleList[$program_role[0]]);
            $objectPHPExcel->getActiveSheet()->setCellValue(Q . ($k + 4),$roleList[$program_role[1]]);
            $objectPHPExcel->getActiveSheet()->setCellValue(R. ($k + 4),$roleList[$program_role[2]]);
            $objectPHPExcel->getActiveSheet()->setCellValue(S . ($k + 4),Utils::DateToEn($v['record_time']));
//            $n++;
        }
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Employee information table-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }


    //导出设备信息表
    public static function actionDeviceExport(){
        $args = $_GET['q'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $rs = ProgramDevice::deviceinfo($args);
        $certificate_type = DeviceCertificate::certificateList();
        if (count($rs) > 0) {
            foreach ($rs as $key => $row) {
                $s[$row['primary_id']] = $row['primary_id'];
            }
        }
        $deviceinfo = DeviceInfo::deviceinfoExport($s);
        $typeList = DeviceType::deviceList();//设备型号列表
//        var_dump($rs);
//        exit;

//        if (count($rs) <= 0) {
//            header("Content-type:text/html;charset=utf-8");
//            echo "<script>alert('".Yii::t('proj_project_user','error_project_user_null')."');</script>";
//        }
//        var_dump($staffinfo);
//        var_dump($staff);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.'J1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('proj_project_device','project_device_excel'));
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'D2');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',Yii::t('proj_project_user','program_name').'：'.date("d M Y"));
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3',Yii::t('device','device_img'));
        $objectPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',Yii::t('device','device_name'));
        $objectPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('C3',Yii::t('device','device_id'));
        $objectPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('D3',Yii::t('device','device_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('E3',Yii::t('comp_staff','qr_code'));
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('F3',Yii::t('proj_project_user','entry_date'));
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('G3',Yii::t('comp_staff','aptitude_content'));
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('H3',Yii::t('comp_staff', 'certificate_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('I3',Yii::t('comp_staff', 'certificate_startdate'));
        $objectPHPExcel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('J3',Yii::t('comp_staff', 'certificate_enddate'));
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
//        var_dump($rs);
//        exit;
        $i = 0;
        $total_num = count($rs) + count($deviceinfo); //总行数
        for($p =4;$p<=$total_num+3;$p++) {
            $objectPHPExcel->getActiveSheet()->getRowDimension($p)->setRowHeight(90);
        }
        foreach ($rs as $k => $v) {
//                static $n = 1;
            $mergeCells = count($deviceinfo[$v['primary_id']]);
            if($mergeCells >1){
                $mergeCells =$mergeCells-1;
                $objectPHPExcel->getActiveSheet()->mergeCells('A'.($i+4).':'.'A'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('B'.($i+4).':'.'B'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('C'.($i+4).':'.'C'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('D'.($i+4).':'.'D'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('E'.($i+4).':'.'E'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('F'.($i+4).':'.'F'.($i+4+$mergeCells));
            }else{
                $mergeCells = 0;
            }
            /*设置表格高度*/
            $objectPHPExcel->getActiveSheet()->getRowDimension($i+4)->setRowHeight(90);
            if($v['device_img'] !=''){
                if(substr($v['device_img'],0,1) != '.') {
                    /*实例化excel图片处理类*/
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
                    $objDrawing->setPath('/opt/www-nginx/web' . $v['device_img']);
                    /*设置图片高度*/
                    $objDrawing->setHeight(20);
                    /*设置图片宽度*/
                    $objDrawing->setWidth(80);
//                      //自适应
//                       $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $objDrawing->setCoordinates(A . ($i + 4+$mergeCells));
                    /*设置图片所在单元格的格式*/
                    $objDrawing->setOffsetX(30);//30
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setRotation(40);//40
                    $objDrawing->getShadow()->setVisible(true);
                    $objDrawing->getShadow()->setDirection(20);//20
                    $objDrawing->setWorksheet($objActSheet);
                }
            }

            $objectPHPExcel->getActiveSheet()->setCellValue(B . ($i + 4),$v['device_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue(C . ($i + 4),$v['device_id']);
            $objectPHPExcel->getActiveSheet()->setCellValue(D . ($i + 4),$typeList[$v['type_no']]);
            if(isset($v['qrcode'])){
                Device::buildQrCode($args['contractor_id'],$v['primary_id']);
            }
            if ($v['qrcode'] !=''){
                $qrcode_path = '/opt/www-nginx/web' . $v['qrcode'];
                if(file_exists($qrcode_path)) {
                    /*实例化excel图片处理类*/
                    $obj_Drawing = new PHPExcel_Worksheet_Drawing();
                    /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
                    $obj_Drawing->setPath('/opt/www-nginx/web' . $v['qrcode']);
                    /*设置图片高度*/
                    $obj_Drawing->setHeight(20);
                    /*设置图片宽度*/
                    $obj_Drawing->setWidth(80);
//                      //自适应
//                       $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $obj_Drawing->setCoordinates(E . ($i + 4+$mergeCells));
                    /*设置图片所在单元格的格式*/
                    $obj_Drawing->setOffsetX(30);//30
                    $obj_Drawing->setOffsetY(5);
                    $obj_Drawing->setRotation(40);//40
                    $obj_Drawing->getShadow()->setVisible(true);
                    $obj_Drawing->getShadow()->setDirection(20);//20
                    $obj_Drawing->setWorksheet($objActSheet);
                }
            }
            $objectPHPExcel->getActiveSheet()->setCellValue(F . ($i + 4),Utils::DateToEn($v['apply_date']));
//            $n++;
            if($deviceinfo[$v['primary_id']]) {
                $t = $i + 4;
                foreach ($deviceinfo[$v['primary_id']] as $e => $j) {
                    $objectPHPExcel->getActiveSheet()->setCellValue(G .$t, $j['certificate_title']);
                    $objectPHPExcel->getActiveSheet()->setCellValue(H .$t, $certificate_type[$j['certificate_type']]);
                    $objectPHPExcel->getActiveSheet()->setCellValue(I .$t, Utils::DateToEn($j['permit_startdate']));
                    $objectPHPExcel->getActiveSheet()->setCellValue(J .$t, Utils::DateToEn($j['permit_enddate']));
                    $t=$t+1;
                }
            }
            $i = $i + $mergeCells +1;
        }
//        exit;
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Equipment information table-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {
        $a = Yii::app()->session['list_url'];
        $a['assignuser/list'] = str_replace("r=proj/assignuser/grid", "r=proj/assignuser/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }


    private function saveAuthorityUrl() {
        $a = Yii::app()->session['list_url'];
        $a['assignuser/authoritylist'] = str_replace("r=proj/assignuser/authoritygrid", "r=proj/assignuser/authoritylist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    //private function saveSubUrl() {
    //    $a = Yii::app()->session['list_url'];
    //    $a['proj/assignuser/subauthoritylist'] = str_replace("r=proj/assignuser/subauthoritygrid", "r=proj/assignuser/subauthoritylist", $_SERVER["QUERY_STRING"]);
    //    Yii::app()->session['list_url'] = $a;
    //}

    //添加人员
    public function actionAddPerson(){
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('add_person',array('program_id'=>$program_id));
    }

    /**
     * 人员入场
     */
    public function actionApplyUser() {
        $program_id = $_REQUEST['program_id'];
        $user_id = $_REQUEST['user_id'];
        if ($user_id) {
            $r = ProgramUser::ApplyUser($program_id,$user_id);
        }
        //var_dump($r);
        echo json_encode($r);
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
        //        $url = 'http://roboxz.cmstech.sg/dmsapi/filecache/web?';
//        $url.='uid='.$user_id;
//        $url.='&gid='.$project_id;
//        $url.='&gid='.$login_program_id;
//        $url.='&files=';
//        $url.='https://shell.cmstech.sg'.$path;
        $this->renderPartial('sync_workforce',array('url'=>$url));
    }

    /**
     * 设为 Robox User
     */
    public function actionHideRobox() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramUser::setRoboxUser($id);
        }
        echo json_encode($r);
    }

    /**
     * 设为 Robox Admin
     */
    public function actionShowRobox() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ProgramUser::setRoboxAdmin($id);
        }
        echo json_encode($r);
    }
}
