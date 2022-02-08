<?php

class StaffController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
//    public $layout = '//layouts/main_1';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_staff', 'contentHeader');
        $this->bigMenu = Yii::t('comp_staff', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/staff/grid';
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
     * 查询
     */
    public function actionGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if($args['status'] == ''){
            $args['status'] = '0';
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = Staff::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 违规记录查询表头
     * @return SimpleGrid
     */
    private function violDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/staff/violationgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_safety', 'check_id'), '', '');
        $t->set_header(Yii::t('comp_safety', 'safety_level'), '', '');
        $t->set_header(Yii::t('comp_safety', 'violation_record'), '', '');
        $t->set_header(Yii::t('comp_safety', 'apply_time'), '', '');
        $t->set_header(Yii::t('comp_safety', 'violation_photo'), '', '');
        return $t;
    }
    /**
     * 个人违规记录查询
     */
    public function actionViolationGrid($user_id) {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
//        $args = $_GET['q']; //查询条件
        $args['user_id'] = $user_id;
        $t = $this->violDataGrid();
//        $this->saveUrl();
        $check_list = ViolationRecord::recordListByUser($user_id);
        $check_id = '';
        foreach($check_list as $cnt => $list){
            $check_id == '' ? $check_id = $list['check_id'] : $check_id .= ',' .$list['check_id'];
        }
//        var_dump($check_id);
//        exit;
        $args['check_id'] = $check_id;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = SafetyCheck::queryListByUser($page, $this->pageSize, $args);
        $this->renderPartial('check_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->smallHeader = Yii::t('comp_staff', 'smallHeader List');
        $this->render('list');
    }
    /**
     * 图片上传(nginx)
     */
    public function actionTest(){
        $this->render('test');
    }
    /**
     * 详细
     */
    public function actionDetail() {

        $id = $_POST['id'];
        $program_list = ProgramUser::UserProgramName($id);
        $program_detail = '';
        if($program_list){
            foreach($program_list as $cnt=>$list){
                $program_detail .= $list['program_name'].',';
            }
            $program_detail = substr($program_detail,0,-1);
        }

        $msg['status'] = true;
        $roleList = Role::roleList();
        $model = Staff::model()->find('user_id=:user_id',array(':user_id'=>$id));
        if ($model) {

            $msg['detail'] .= "<table class='detailtab'>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('user_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->user_id) ? $model->user_id : "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('user_name') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->user_name) ? $model->user_name : "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('user_phone') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3 detail_phone'>" . (isset($model->user_phone) ? "$model->user_phone" : "") ."</td>";
            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('work_pass_type') . "</td>";
            $msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->work_pass_type) ? $model->work_pass_type: "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('nation_type') . "</td>";
            $msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->nation_type) ? $model->nation_type: "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('work_no') . "</td>";
            $msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->work_no) ? $model->work_no    : "") . "</td>";
            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            //	$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('team_id') . "</td>";
            //	$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->team_id) ? $model->team_id : "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('role_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->role_id) ? $roleList[$model->role_id] : "") . "</td>";

            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'where the project') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3' colspan='4'>" . $program_detail . "</td>";

            $msg['detail'] .= "</tr>";

            if($model->loaned_status == Staff::LOANED_STATUS_YES){
                $comp_model = Contractor::model()->findByPk($model->original_conid);
                $loaned_model = Contractor::model()->findByPk($model->contractor_id);
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'original_company') . "</td>";
                $msg['detail'] .= "<td class='tvalue-3'>" . $comp_model->contractor_name . "</td>";
                $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'loane_company') . "</td>";
                $msg['detail'] .= "<td class='tvalue-3'>" . $loaned_model->contractor_name . "</td>";
                $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'loane_time') . "</td>";
                $msg['detail'] .= "<td class='tvalue-3'>" . $model->loaned_time . "</td>";
                $msg['detail'] .= "</tr>";
            }


            $msg['detail'] .= "</table>";
            print_r(json_encode($msg));
        }
    }
    /**
     * 图片上传
     */
    public function uploadPicture(&$files,$new_file_name,$path){
        $files = $files["StaffInfo"];//Staff
        $flag['refresh'] = true;
        $type = substr($files['name']["face_img"],strpos($files['name']["face_img"],".")+1,strlen($files['name']["face_img"]));
        $file_suffix = substr($files['name']['face_img'], strpos($files['name']['face_img'], '.'));
        //var_dump($file_suffix);
        $file_id = date('YmdHis');
        //var_dump($file_id);
        $new_file_name = $file_id . $file_suffix;
        //var_dump(Yii::app()->baseUrl);
        //exit;
        $path = Yii::app()->params['upload_file_path'] ;
//        <?php echo Yii::app()->request->baseUrl;

        $rs = $this->checkPicture($files,$type);
        $uploadPath = $path.'/'.$new_file_name;

        if ($rs['refresh']){
            if (move_uploaded_file($files['tmp_name']["face_img"], $uploadPath)) {
                $flag['msg'] = "上传成功!";
                $flag["file_path"] = $uploadPath;
                $flag['status'] = 0;
            } else {
                $flag['msg'] = "上传失败";
                $flag['status'] = -1;
            }
        } else {
            $flag['msg'] = $rs['msg'];
            $flag['status'] = -1;
        }
        return $flag;
    }

    /**
     * 检查上传的文件是否合法
     * @param type $files $_FILES['文件名']
     * @return boolean
     */
    public function checkPicture(&$files,$type) {
        $type_array = array("jpg","jpeg","png","bmp", "JPG");
        $flag['refresh'] = true;
        $size = Yii::app()->params['face_img_size'];
        if ($files['size'] ["face_img"] > $size*1024) {
            $flag['msg'] = Yii::t('comp_staff', 'Upload file greater');
            $flag['refresh'] = false;
        }
        if (!in_array(strval($type),$type_array)) {
            $flag['msg'] = Yii::t('comp_staff', 'Upload format wrong');
            $flag['refresh'] = false;
        }
        return $flag;
    }
    /**
     * 员工基本信息标签
     */
    public function actionTabs() {
        $roleList = Role::roleByTypeList(Staff::CONTRACTOR_TYPE_SC);
        $myRoleList = array();
        $r = array();
        $mode = $_REQUEST['mode'];
        $user_id = $_REQUEST['user_id'];
        $title = $_REQUEST['title'];
        $program_id = $_REQUEST['program_id'];
//        var_dump($title);
        if($mode == 'insert'){
            $model = new Staff('create');
            $infomodel = new StaffInfo('create');
            $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
        }else{
            if($title == '1'){
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
            }else{
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader Edit');
            }
            $model = new Staff('modify');
            $infomodel = new StaffInfo('modify');
            $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
            $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));

            $contractor_id = Yii::app()->user->getState('contractor_id');
            if(!isset($model->qrcode)){
                Staff::buildQrCode($contractor_id,$user_id);
            }
            if($model->role_id){
                $roleinfo = Role::model()->findByPk($model->role_id);
                $model->team_id = $roleinfo['team_name_en'];
            }
        }
        //基本信息添加
        if (isset($_POST['Staff'])) {
            $args = $_POST['Staff'];
            $info = $_POST['StaffInfo'];
            $old_file = $_POST['File'];
            $model->_attributes = $args;
            //判断文件是不是为空
//            if ($old_file['face_src'] == ''&& $args['user_id']==''){
//                $r['status'] = '-1';
//                $r['msg'] = Yii::t('comp_staff','Error Upload_pic is null');
//                $r['refresh'] = false;
//                goto end;
//            }
            if ($old_file['face_src'] <> ''){
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
                $r['status'] = '';
                $r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $r['status']  .= $row['code'];
                        $r['msg']  .= $row['msg'].' ';
                    }else{
                        if($key == 'face_src') {
                            $infoargs['face_img'] = $row['upload_file'];
                        }
                    }
                }//var_dump($r);
                if($r['status'] <> ''){
                    $r['refresh'] = false;
                    goto end;
//                    return $r;
                }
            }
            $infoargs['name_cn'] = $info['name_cn'];
            $infoargs['skill'] = $info['skill'];
            $infoargs['service_date'] = Utils::DateToCn($info['service_date']);
            $infoargs['bca_pass_type'] = $args['work_pass_type'];
            $infoargs['bca_pass_no'] = $args['work_no'];
            $infoargs['bca_company'] = $info['bca_company'];
            $infoargs['bca_levy_rate'] = $info['bca_levy_rate'];
            if($args['user_id']<>''){
                $user_id = $args['user_id'];
                $r = Staff::updateStaff($args,$infoargs);
                if($r['status'] == 1){	//修改成功
                    $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $args['user_id']));
                    $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $args['user_id']));
                    if($model->role_id){
                        $roleinfo = Role::model()->findByPk($model->role_id);
                        $model->team_id = $roleinfo['team_name_en'];
                    }
                    $r['msg'] = Yii::t('common', 'success_update');
                    $mode = 'edit';
                }
            }else{
                $r = Staff::insertStaff($args,$infoargs);
                if($r['status'] == 1){	//添加成功
                    $user_id = $r['user_id'];
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    Staff::buildQrCode($contractor_id,$user_id);

                    $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $r['user_id']));
                    $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $r['user_id']));
                    $r['msg'] .= "    ".Yii::t('comp_staff', 'continue').' [ '."<a href='index.php?r=proj/staff/tabs&mode=insert&title=1&program_id=$program_id'>".Yii::t('comp_staff', 'Add Staff')."</a> ]";
                    $mode = 'edit';
                    if($model->role_id){
                        $roleinfo = Role::model()->findByPk($model->role_id);
                        $model->team_id = $roleinfo['team_name_en'];
                    }
//                    var_dump($user_id);
//                    exit;
                }
            }
            end:

        }

        $this->render('_form',array('title' => $title,'model' => $model,'infomodel' => $infomodel,'user_id' => $user_id,'program_id'=>$program_id,'_mode_' => $mode, 'msg' => $r, 'roleList' => $roleList, 'myRoleList' => $myRoleList));
    }
    /**
     * 员工个人信息标签
     */
    public function actionPerTabs() {
        $user_id = $_REQUEST['user_id'];
        $mode = $_REQUEST['mode'];
        $title = $_REQUEST['title'];
        $old_file = $_POST['File'];
        $program_id = $_REQUEST['program_id'];
        if($mode == 'insert'){
            $model = new Staff('create');
            $infomodel = new StaffInfo('create');
            $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
        }else{
            $model = new Staff('modify');
            $infomodel = new StaffInfo('modify');
            if($title == '1'){
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
            }else{
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader Edit');
            }
        }
        if($user_id==''){
            $per_r['msg'] = Yii::t('comp_staff', 'user_id is null');
            $per_r['status'] = -1;
            $per_r['refresh'] = false;
            goto end;
        }
        if ($_POST['Tag']['tag_id']=='per') {
            $per_r = self::newPer($old_file);
            if($per_r['status']==1){
                $user_id = $per_r['user_id'];
                $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
                $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
            }
        }
        $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
        $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
        end:
        $this->render('_perform', array('title' => $title,'model' => $model,'infomodel' =>$infomodel,'user_id' => $user_id,'program_id'=>$program_id,'_mode_' => $mode,'msg' => $per_r));
    }


    /**
     * 员工护照信息标签
     */
    public function actionPassTabs() {
        $user_id = $_REQUEST['user_id'];
        $mode = $_REQUEST['mode'];
        $title = $_REQUEST['title'];
        $old_file = $_POST['File'];
        if($mode == 'insert'){
            $model = new Staff('create');
            $infomodel = new StaffInfo('create');
            $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
        }else{
            $model = new Staff('modify');
            $infomodel = new StaffInfo('modify');
            if($title == '1'){
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
            }else{
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader Edit');
            }
        }
        if($user_id ==''){
            $pass_r['msg'] = Yii::t('comp_staff', 'user_id is null');
            $pass_r['status'] = -1;
            $pass_r['refresh'] = false;
            goto end;
        }
        if ($_POST['Tag']['tag_id']=='pass') {
            $pass_r = self::newPass($old_file);
            if($pass_r['status'] == 1){	//成功
                $pass_r['msg'] = Yii::t('common', 'success_set');
                $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
                $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
            }
        }
        $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
        $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
        end:
        $this->render('passtabs', array('title' => $title,'model' => $model,'infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => $mode,'msg' => $pass_r));
    }
    /**
     * 员工工作准证标签
     */
    public function actionBcaTabs() {
        $user_id = $_REQUEST['user_id'];
        $mode = $_REQUEST['mode'];
        $title = $_REQUEST['title'];
        $old_file = $_POST['File'];
        if($mode == 'insert'){
            $model = new Staff('create');
            $infomodel = new StaffInfo('create');
            $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
        }else{
            $model = new Staff('modify');
            $infomodel = new StaffInfo('modify');
            if($title == '1'){
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
            }else{
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader Edit');
            }
        }
        if($user_id ==''){
            $bca_r['msg'] = Yii::t('comp_staff', 'user_id is null');
            $bca_r['status'] = -1;
            $bca_r['refresh'] = false;
            goto end;
        }
        if ($_POST['Tag']['tag_id']=='bca') {
            $bca_r = self::newBca($old_file);
            if($bca_r['status'] == 1){	//成功
                $bca_r['msg'] = Yii::t('common', 'success_set');
                $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
                $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
            }
        }
        $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
        $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
        end:
        $this->render('bcatabs', array('title' => $title,'model' => $model,'infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => $mode,'msg' => $bca_r));
    }
    /**
     * 员工安全证标签
     */
    public function actionCsocTabs() {
        $user_id = $_REQUEST['user_id'];
        $mode = $_REQUEST['mode'];
        $title = $_REQUEST['title'];
        $old_file = $_POST['File'];
        if($mode == 'insert'){
            $model = new Staff('create');
            $infomodel = new StaffInfo('create');
            $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
        }else{
            $model = new Staff('modify');
            $infomodel = new StaffInfo('modify');
            if($title == '1'){
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
            }else{
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader Edit');
            }
        }
        if($user_id ==''){
            $csoc_r['msg'] = Yii::t('comp_staff', 'user_id is null');
            $csoc_r['status'] = -1;
            $csoc_r['refresh'] = false;
            goto end;
        }
        if ($_POST['Tag']['tag_id']=='csoc'){
            $csoc_r = self::newCsoc($old_file);
            if($csoc_r['status'] == 1){	//成功
                $csoc_r['msg'] = Yii::t('common', 'success_set');
                $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
                $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
            }
        }
        $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
        $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
        end:
        $this->render('csoctabs', array('title' => $title,'model' => $model,'infomodel' =>$infomodel,'user_id' => $user_id,'_mode_' => $mode,'msg' => $csoc_r));
    }
    /**
     * 员工保险标签
     */
    public function actionInsTabs() {
        $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $mode = $_REQUEST['mode'];
        $title = $_REQUEST['title'];
        if($mode == 'insert'){
            $model = new Staff('create');
            $infomodel = new StaffInfo('create');
        }else{
            $model = new Staff('modify');
            $infomodel = new StaffInfo('modify');
            if($title == '1'){
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader New');
            }else{
                $this->smallHeader = Yii::t('comp_staff', 'smallHeader Edit');
            }
        }
        if($user_id ==''){
            $ins_r['msg'] = Yii::t('comp_staff', 'user_id is null');
            $ins_r['status'] = -1;
            $ins_r['refresh'] = false;
            goto end;
        }else{
            $ins_r = self::newIns();
        }
        if($ins_r['status'] == 1){	//成功
            $ins_r['msg'] = Yii::t('common', 'success_set');
            $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
            $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
        }
        $model->_attributes = Staff::model()->find('user_id=:user_id', array(':user_id' => $user_id));
        $infomodel->_attributes = StaffInfo::model()->find('user_id=:user_id',array(':user_id'=> $user_id));
        end:
        $this->render('_insform', array('title' => $title,'model' => $model,'infomodel' =>$infomodel,'user_id' => $user_id,'program_id'=>$program_id,'_mode_' => $mode,'msg' => $ins_r));
    }
    /*
     * 个人违规记录标签
     */
    public function actionViolTabs() {
        $user_id = $_REQUEST['user_id'];
        $this->render('violtabs',array('user_id' => $user_id));
    }
    /*
     * 设置个人信息
     */
    private function newPer($old_file) {

        if(isset($_POST['StaffInfo']['user_id'])){
            $infoargs = $_POST['StaffInfo'];
            $args['user_id'] = $infoargs['user_id'];
            if ($old_file['home_id_src'] <> ''){
                //上传文件
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
                $_r['status'] = '';
                $_r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $_r['status']  .= $row['code'];
                        $_r['msg']  .= $row['msg'].' ';
                        $_r['user_id'].= $args['user_id'];
                    }else{
                        if($key == 'home_id_src') {
                            $infoargs['home_id_photo'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($_r['status'] <> ''){
                    $_r['refresh'] = false;
                    return $_r;
                }
            }
            $infoargs['birth_date'] = Utils::DateToCn($infoargs['birth_date']);
//                var_dump($infoargs);
//                var_dump($args);
//                exit;
            $_r = StaffInfo::updateStaffInfo($args,$infoargs);
            if($_r['status'] == 1){	//成功
                $_r['msg'] = Yii::t('common', 'success_set');
            }
        }
        return $_r;
    }
    /*
     * 设置护照信息
     */
    private function newPass($old_file) {
//        var_dump($_POST['StaffInfo']);
//        exit;

        if(isset($_POST['StaffInfo']['user_id'])){
            $infoargs = $_POST['StaffInfo'];
            $args['user_id'] = $infoargs['user_id'];
            if ($old_file['ppt_src'] <> ''){
                //上传文件
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
                $_r['status'] = '';
                $_r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $_r['status']  .= $row['code'];
                        $_r['msg']  .= $row['msg'].' ';
                        $_r['user_id'].= $args['user_id'];
                    }else{
                        if($key == 'ppt_src') {
                            $infoargs['ppt_photo'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($_r['status'] <> ''){
                    $_r['refresh'] = false;
                    return $_r;
                }
            }
            $infoargs['ppt_issue_date'] = Utils::DateToCn($infoargs['ppt_issue_date']);
            $infoargs['ppt_expire_date'] = Utils::DateToCn($infoargs['ppt_expire_date']);
//                var_dump($infoargs);
////                var_dump($args);
//                exit;
            $_r = StaffInfo::updateStaffInfo($args,$infoargs);
            if($_r['status'] == 1){	//成功
                $_r['msg'] = Yii::t('common', 'success_set');
            }
        }
        return $_r;
    }
    /*
     * 设置Bca信息
     */
    private function newBca($old_file) {

        if(isset($_POST['StaffInfo']['user_id'])){
            $infoargs = $_POST['StaffInfo'];
            $args['user_id'] = $infoargs['user_id'];
//            if ($_FILES['StaffInfo']['name']['bca_photo'] == ''){
//                $_r['status'] = '-1';
//                $_r['msg'] = Yii::t('comp_staff','Error Upload_pic is null');
//                $_r['refresh'] = false;
//                $_r['user_id'] = $args['user_id'];
//                return $_r;
//            }
            if ($old_file['bca_src'] <> ''){
                //上传文件
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
                $_r['status'] = '';
                $_r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $_r['status']  .= $row['code'];
                        $_r['msg']  .= $row['msg'].' ';
                        $_r['user_id'].= $args['user_id'];
                    }else{
                        if($key == 'bca_src') {
                            $infoargs['bca_photo'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($_r['status'] <> ''){
                    $_r['refresh'] = false;
                    return $_r;
                }
            }
            $infoargs['bca_issue_date'] = Utils::DateToCn($infoargs['bca_issue_date']);
            $infoargs['bca_expire_date'] = Utils::DateToCn($infoargs['bca_expire_date']);
            $infoargs['bca_apply_date'] = Utils::DateToCn($infoargs['bca_apply_date']);
//                var_dump($infoargs);
//                var_dump($args);
//                exit;
            $_r = StaffInfo::updateStaffInfo($args,$infoargs);
            if($_r['status'] == 1){	//成功
                $_r['msg'] = Yii::t('common', 'success_set');
            }
        }
        return $_r;
    }
    /*
    * 设置安全证信息
    */
    private function newCsoc($old_file) {
//        var_dump($_POST['StaffInfo']);
//        exit;
        if(isset($_POST['StaffInfo']['user_id'])){
            $infoargs = $_POST['StaffInfo'];
            $args['user_id'] = $infoargs['user_id'];
            if ($old_file['csoc_src'] <> ''){
                //上传文件
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
                $_r['status'] = '';
                $_r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $_r['status']  .= $row['code'];
                        $_r['msg']  .= $row['msg'].' ';
                        $_r['user_id'].= $args['user_id'];
                    }else{
                        if($key == 'csoc_src') {
                            $infoargs['csoc_photo'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($_r['status'] <> ''){
                    $_r['refresh'] = false;
                    return $_r;
                }
            }
            $infoargs['csoc_issue_date'] = Utils::DateToCn($infoargs['csoc_issue_date']);
            $infoargs['csoc_expire_date'] = Utils::DateToCn($infoargs['csoc_expire_date']);
//                var_dump($infoargs);
//                var_dump($args);
//                exit;
            $_r = StaffInfo::updateStaffInfo($args,$infoargs);
            if($_r['status'] == 1){	//成功
                $_r['msg'] = Yii::t('common', 'success_set');
            }
        }
        return $_r;
    }
    /*
    * 设置保险信息
    */
    private function newIns() {
//        var_dump($_POST['StaffInfo']);
//        exit;
        if(isset($_POST['StaffInfo']['user_id'])){
            $infoargs = $_POST['StaffInfo'];
            $args['user_id'] = $infoargs['user_id'];

            $infoargs['ins_scy_issue_date'] = Utils::DateToCn($infoargs['ins_scy_issue_date']);
            $infoargs['ins_scy_expire_date'] = Utils::DateToCn($infoargs['ins_scy_expire_date']);
            $infoargs['ins_med_issue_date'] = Utils::DateToCn($infoargs['ins_med_issue_date']);
            $infoargs['ins_med_expire_date'] = Utils::DateToCn($infoargs['ins_med_expire_date']);
            $infoargs['ins_adt_issue_date'] = Utils::DateToCn($infoargs['ins_adt_issue_date']);
            $infoargs['ins_adt_expire_date'] = Utils::DateToCn($infoargs['ins_adt_expire_date']);
            $_r = StaffInfo::updateStaffInfo($args,$infoargs);
            if($_r['status'] == 1){	//成功
                $_r['msg'] = Yii::t('common', 'success_set');
            }
        }
        return $_r;
    }

    /**
     * 借调
     */
    public function actionLoane() {
        $this->smallHeader = Yii::t('comp_staff', 'smallHeader Loane');
        $this->contentHeader = Yii::t('comp_staff', 'contentHeader_lone');
        $model = new Staff('modify');
        $id = trim($_REQUEST['id']);

        $r = array();
        if  (isset($_POST['Staff'])) {
            $args = $_POST['Staff'];

            //$args['user_id'] = $id;
            //var_dump($args);
            $r = Staff::LoaneStaff($args, $_POST['type']);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Staff'];
            }
        }

        $model->_attributes = Staff::model()->findByPk($id);
        $comp_model = Contractor::model()->findByPk($model->original_conid);

        if($model->loaned_status == Staff::LOANED_STATUS_NO){
            $this->render('loane', array('model' => $model, 'comp_model'=>$comp_model, 'msg' => $r));
        }
        if($model->loaned_status == Staff::LOANED_STATUS_YES){
            $loane_model = Contractor::model()->findByPk($model->contractor_id);
            $this->render('loane_back', array('model' => $model, 'comp_model'=>$comp_model, 'loane_model'=>$loane_model, 'msg' => $r));
        }

    }
    /**
     * 加入白名单
     */
    public function actionWhitelist() {
        $model = new Staff('modify');
        $id = trim($_REQUEST['id']);
        $model->_attributes = Staff::model()->findByPk($id);
        $name = $_REQUEST['name'];
        $this->renderPartial('whitelist', array('model'=>$model,'id'=>$id,'name'=>$name));
    }
    public function actionaddWhite() {
        $args = array();
        $args = $_REQUEST['Staff'];
//        var_dump($args);
//        exit;
        $r = Staff::addWhite($args);
        echo json_encode($r);
    }
    /**
     * 注销
     */
    public function actionLogout() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Staff::logoutStaff($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['staff/list'] = str_replace("r=comp/staff/grid", "r=comp/staff/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 资质证件照列表
     */
    public function actionAttachlist() {
        $user_id = $_GET['user_id'];
        if($_GET['type']){
            $type = $_GET['type'];
        }else{
            $type = 'mc';
        }
        if($user_id <> '')
            $father_model = Staff::model()->findByPk($user_id);
//        var_dump($father_model);
//        exit;
        $this->smallHeader = $father_model->user_name;
        $this->contentHeader = Yii::t('comp_staff', 'Qualification Certificate');
        $this->bigMenu = $father_model->user_name;
        $this->render('attachlist',array('user_id'=>$user_id,'type'=>$type));
    }

    /**
     * 资质证件照表头
     */
    private function genAttachDataGrid($user_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/staff/attachgrid&user_id='.$user_id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_staff', 'document_name'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'commonly_used'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'certificate_type'),'','center');
        $t->set_header(Yii::t('comp_staff', 'certificate_startdate'),'','center');
        $t->set_header(Yii::t('comp_staff', 'certificate_enddate'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Record Time'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', 'center');
//        return $t;
        return $t;
    }

    /**
     * 资质证件照查询
     */
    public function actionAttachGrid() {

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        $args = array();
        if($args['user_id'] == ''){
            $args['user_id'] = $_GET['user_id'];
        }
        if(isset($_GET['type'])){
            $args['type'] = $_GET['type'];
        }
        if(array_key_exists('type',$args)){
            $type = $args['type'];
        }else{
            $type = 'mc';
        }
        $user_id = $args['user_id'];

        $t = $this->genAttachDataGrid($user_id);
        //$this->saveUrl();
//        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($_GET['program_id']);
//        exit;
//        var_dump($args);
//        exit;
        $list = UserAptitude::queryList($page, $this->pageSize, $args);
        $this->renderPartial('attach_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'],'user_id'=> $user_id,'curpage' => $list['page_num'],'type'=>$type));
    }
    /**
     * 资质证件上传
     */
    public function actionUpload() {
        $args = $_GET['q'];
        $user_id = $args['user_id'];
        $user_model = Staff::model()->findByPk($user_id);
        $user_name = $user_model->user_name;
        $this->smallHeader = $user_name;
        $this->contentHeader = Yii::t('comp_staff', 'Qualification Certificate');
//        var_dump($task_id);
//        var_dump($program_id);
//        exit;
        $model = new UserAptitude('create');
        $this->render('upload',array('model'=> $model,'user_id'=>$user_id, '_mode_' => 'insert'));
    }
    /**
     * 资质证件上传(修改)
     */
    public function actionDisplayUpload() {
        $aptitude_id = $_REQUEST['aptitude_id'];
        $user_id = $_REQUEST['user_id'];
        $user_model = Staff::model()->findByPk($user_id);
        $user_name = $user_model->user_name;
        $this->smallHeader = $user_name;
        $this->contentHeader = Yii::t('comp_staff', 'Qualification Certificate');
        $model = new UserAptitude('modify');
        $model->_attributes = UserAptitude::model()->find('aptitude_id=:aptitude_id', array(':aptitude_id' => $aptitude_id));
        $this->render('upload',array('model'=> $model,'aptitude_id'=>$aptitude_id,'user_id'=>$user_id, '_mode_' => 'edit'));
    }
    /**
     * 资质证件删除
     */
    public function actionDeleteAptitude() {
        $args = array();
        $args['str'] = $_REQUEST['str'];
        $args['user_id'] = $_REQUEST['user_id'];
        $r = UserAptitude::deleteAttach($args);
        print_r(json_encode($r));
    }
//    /**
//     * 将上传的图片移动到正式路径下
//     */
//    public function actionMovePic() {
//        $file_src = $_REQUEST['file_src'];
//        $r = UserAptitude::movePic($file_src);
//        print_r(json_encode($r));
//    }
    /**
     * 将上传的证件移动到正式路径下
     */
    public function actionMovePic() {
//        $file_src = $_REQUEST['file_src'];
        $args = array();
        $args = $_POST['UserAptitude'];
        $file_src = $args['tmp_src'];
        $r = UserAptitude::movePic($file_src);
//        var_dump($args);
//        exit;
        if($r['status'] == 1){
            $args['aptitude_photo'] = $r['src'];

            $name = substr($r['src'],25);
            $src = '/opt/www-nginx/web'.$r['src'];
//            var_dump($name);
//            var_dump($src);
//            exit;
            $file_name = explode('.',$name);
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $size = filesize($src)/1024;
            $args['aptitude_size'] = sprintf('%.2f',$size);
//            $model->doc_path = substr($upload_file,18);
//            $args['aptitude_name'] = $file_name[0];
            $args['aptitude_type'] = $file_name[1];
//            var_dump($args);
//            exit;
            if($args['mode'] == 'insert'){
                $r = UserAptitude::insertAttach($args);
            }else{
                $r = UserAptitude::updateAttach($args);
            }

            print_r(json_encode($r));
        }else{
            print_r(json_encode($r));
        }

    }
    /**
     * 删除上传图片
     */
    public function actionDelPic() {
        $src = $_REQUEST['src'];
        $r = UserAptitude::deletePic($src);
        print_r(json_encode($r));
    }
    /**
     * 添加资质证件照和内容
     */
    public function actionEditAptitude() {
        $args = array();
        $args = $_POST['UserAptitude'];
        $file_src = $args['tmp_src'];

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $args['permit_startdate'] = Utils::DateToCn($args['permit_startdate']);
        $args['permit_enddate'] = Utils::DateToCn($args['permit_enddate']);
        $r = UserAptitude::updateAttach($args);
        print_r(json_encode($r));
    }

    /**
     * 设置常用
     */
    public function actionSetused() {
        $aptitude_id = trim($_REQUEST['aptitude_id']);
        $aptitude_use = trim($_REQUEST['aptitude_use']);
        $r = array();
        $r = UserAptitude::setUsed($aptitude_id,$aptitude_use);
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $aptitude_photo = $_REQUEST['aptitude_photo'];
        $aptitude_id = $_REQUEST['aptitude_id'];
        $this->renderPartial('preview',array('aptitude_photo'=>$aptitude_photo,'aptitude_id'=>$aptitude_id));
    }
    /**
     * 删除文档
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = UserAptitude::deleteFile($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $aptitude_photo = $_REQUEST['aptitude_photo'];
        $rows = UserAptitude::queryFile($aptitude_photo);
        if(count($rows)>0){
            $show_name = $rows[0]['aptitude_name'];
            $filepath = '/opt/www-nginx/web'.$rows[0]['aptitude_photo'];
            $extend = $rows[0]['aptitude_type'];
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }
    /**
     * 轮播图片
     */
    public function actionShowPic() {
        $pic_str = $_REQUEST['pic_str'];
        $user_id = $_REQUEST['user_id'];
        $this->render('pic_show',array('pic_str'=>$pic_str,'user_id'=>$user_id));
    }
    /**
     * 根据工作准证类型查询国籍类型
     */
    public function actionQueryNationType() {
        $work_pass_type = $_POST['work_pass_type'];
        if($work_pass_type == ''){
            print_r(json_encode(array()));
        }

        $rows = Staff::NationType($work_pass_type);

        print_r(json_encode($rows));
    }
    /**
     * 在线预览文件
     */
    public function actionPreviewPrint() {
        $user_id = $_REQUEST['user_id'];
        $qrcode_path = $_REQUEST['qrcode_path'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('print_qrcode',array('user_id'=>$user_id,'qrcode_path'=>$qrcode_path));
    }
    /**
     * 二维码生成界面
     */
    public function actionQrcode() {
//        $this->contentHeader = Yii::t('comp_staff', 'qr_code');
        $this->smallHeader = Yii::t('comp_staff', 'qr_code');
        $user_id = $_REQUEST['user_id'];
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] .'/qrcode/'.$contractor_id.'/';
        $PNG_WEB_DIR = 'img/staff/';
        //include "qrlib.php";
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'phpqrcode'.DIRECTORY_SEPARATOR.'qrlib.php';
        require_once($tcpdfPath);
        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);


        $filename = $PNG_TEMP_DIR.$contractor_id.'.png';

//processing form input
//remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 4;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


        if ($user_id) {
//    $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
            $filename = $PNG_TEMP_DIR.$user_id.'.png';
            QRcode::png($user_id, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//            var_dump(1);
        } else {
            $filename = $PNG_TEMP_DIR.'error.png';
            QRcode::png('error', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//            var_dump(2);
        }
        $this->render('qrcode_list',array('user_id' => $user_id,'PNG_TEMP_DIR'=>$PNG_TEMP_DIR));
    }

    /**
     * 人员统计信息
     */
    public function actionStatistics() {
        $this->contentHeader = Yii::t('comp_staff', 'Statistics contentHeader');
        $this->smallHeader = Yii::t('comp_staff', 'statistics');
        $user_id = $_REQUEST['user_id'];
        $contractor_id = Yii::app()->user->getState('contractor_id');

        $this->render('statisticslist',array('user_id' => $user_id));
    }
    /**
     * 人员信息局部刷新
     */
    public function actionSelfGrid($user_id) {

        $args = array();
        if($user_id == ''){
            $user_id = $_REQUEST['user_id'];
        }
        $contractor_id = Yii::app()->user->contractor_id;
        if($_REQUEST['program_id']){
            $program_id = $_REQUEST['program_id'];
            $basic['ptw_set_cnt'] = ApplyBasic::findBySet($user_id,$program_id);//PTW按权限统计次数
            $basic['ptw_member_cnt'] = ApplyBasic::findByMember($user_id,$program_id);//PTW按成员统计次数
            $basic['ptw_all_cnt'] = ApplyBasic::findByAll($user_id,$program_id);//PTW统计总次数
            $basic['tbm_set_cnt'] = Meeting::findBySet($user_id,$program_id);//TBM按权限统计次数
            $basic['tbm_member_cnt'] = Meeting::findByMember($user_id,$program_id);//TBM按成员统计次数
            $basic['tbm_all_cnt'] = Meeting::findByAll($user_id,$program_id);//TBM统计总次数
            $basic['train_set_cnt'] = Train::trainBySet($user_id,$program_id);//TRAIN按权限统计次数
            $basic['train_member_cnt'] = Train::trainByMember($user_id,$program_id);//TRAIN按成员统计次数
            $basic['train_all_cnt'] = Train::trainByAll($user_id,$program_id);//TRAIN统计总次数
            $basic['meeting_set_cnt'] = Train::meetingBySet($user_id,$program_id);//MEETING按权限统计次数
            $basic['meeting_member_cnt'] = Train::meetingByMember($user_id,$program_id);//MEETING按成员统计次数
            $basic['meeting_all_cnt'] = Train::meetingByAll($user_id,$program_id);//MEETING统计总次数
            $basic['inspection_apply_cnt'] = SafetyCheck::findByApply($user_id,$program_id);//Inspection按申请权限统计次数
            $basic['inspection_charge_cnt'] = SafetyCheck::findByCharge($user_id,$program_id);//Inspection按负责人权限统计次数
            $basic['inspection_member_cnt'] = SafetyCheck::findByMember($user_id,$program_id);//Inspection按成员统计次数
            $basic['inspection_all_cnt'] = SafetyCheck::findByAll($user_id,$program_id);//Inspection统计总次数
            $basic['accident_set_cnt'] = AccidentBasic::accidentBySet($user_id,$program_id);//Accident按权限统计次数
            $basic['accident_member_cnt'] = AccidentBasic::accidentByMember($user_id,$program_id);//Accident按成员统计次数
            $basic['accident_all_cnt'] = AccidentBasic::accidentByAll($user_id,$program_id);//Accident统计总次数
            $basic['entrance_leave'] = ProgramUser::SelfByDate($user_id,$program_id);//进出场记录
            $this->render('statistics_toolBox', array('user_id'=> $user_id,'contractor_id'=>$contractor_id,'program_id'=>$program_id,'basic' => $basic));
        }else{
            $program_id = '';
            $this->render('statistics_toolBox', array('user_id'=> $user_id,'contractor_id'=>$contractor_id,'program_id'=>$program_id));
        }

    }
    /**
     * 人员项目相关信息
     */
    public function actionSelf() {
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $self_info = ProgramUser::SelfByPro($contractor_id,$user_id,$program_id);//人员所在项目的信息
//        var_dump($self_info);
//        exit;
        print_r(json_encode($self_info));
    }
    /**
     * 人员入场出场
     */
    public function actionSelfByDate() {
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $date_info = ProgramUser::SelfByDate($user_id,$program_id);
        print_r(json_encode($date_info));
    }
    /**
 * PTW按权限/成员统计次数
 */
    public function actionPtwRole() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $ptw_role = ApplyBasic::findBySummary($user_id,$program_id);//PTW按权限统计次数
        print_r(json_encode($ptw_role));
    }

    /**
     * PTW统计总次数
     */
    public function actionPtwCnt() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $ptw_cnt = ApplyBasic::cntBySummary($user_id,$program_id);//PTW按权限统计次数
        print_r(json_encode($ptw_cnt));
    }
    /**
     * TBM按权限/成员统计次数
     */
    public function actionTbmRole() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $tbm_role = Meeting::findBySummary($user_id,$program_id);//TBM按权限统计次数
        print_r(json_encode($tbm_role));
    }

    /**
     * TBM统计总次数
     */
    public function actionTbmCnt() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $tbm_cnt = Meeting::cntBySummary($user_id,$program_id);//TBM按权限统计次数
        print_r(json_encode($tbm_cnt));
    }
    /**
     * TRAIN按权限/成员统计次数
     */
    public function actionTrainRole() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $module_type= $_REQUEST['module_type'];
        $train_role = Train::findBySummary($user_id,$program_id,$module_type);//TRAIN按权限统计次数
        print_r(json_encode($train_role));
    }

    /**
     * TRAIN统计总次数
     */
    public function actionTrainCnt() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $module_type= $_REQUEST['module_type'];
        $train_cnt = Train::cntBySummary($user_id,$program_id,$module_type);//TRAIN按权限统计次数
        print_r(json_encode($train_cnt));
    }

    /**
     * INSPECTION按权限/成员统计次数
     */
    public function actionInspectionRole() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $inspection_role = SafetyCheck::findBySummary($user_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($inspection_role));
    }

    /**
     * INSPECTION统计总次数
     */
    public function actionInspectionCnt() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $inspection_cnt = SafetyCheck::cntBySummary($user_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($inspection_cnt));
    }

    /**
     * Accident按权限/成员统计次数
     */
    public function actionAccidentRole() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $accident_role = AccidentBasic::findBySummary($user_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($accident_role));
    }

    /**
     * Accident统计总次数
     */
    public function actionAccidentCnt() {
        $user_id = $_REQUEST['user_id'];
        $program_id = $_REQUEST['program_id'];
        $accident_cnt = AccidentBasic::cntBySummary($user_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($accident_cnt));
    }

    public function actionQueryUser() {
        $args['user_name'] = $_REQUEST['user_name'];
        $args['user_phone'] = $_REQUEST['user_phone'];
        $args['user_work_no'] = $_REQUEST['user_work_no'];
        $args['program_id'] = $_REQUEST['program_id'];
        $r = Staff::QueryUser($args);
        echo json_encode($r);
    }
}
