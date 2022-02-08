<?php

class EquipmentController extends AuthBaseController {
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    public $layout = '//layouts/main_1';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('device', 'bigMenu');
        $this->bigMenu = Yii::t('device', 'contentHeader');
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=device/equipment/grid';
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
     * 查询
     */
    public function actionGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if(!is_array($args)){
            $args = array();
        }

        if(!array_key_exists('type_no',$args)){
            if($_SESSION['type_no']){
                $args['type_no'] = $_SESSION['type_no'];
            }
        }

        if(!array_key_exists('device_id',$args)){
            if($_SESSION['device_id']){
                $args['device_id'] = $_SESSION['device_id'];
            }
        }

//        var_dump($args);
//        exit;
	    if($args['status'] == ''){
            $args['status'] = '00';
	    }
        $t = $this->genDataGrid();
        $this->saveUrl();
//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $list = Device::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    public function actionList() {
        $this->smallHeader = Yii::t('device', 'smallHeader List');
        if($_GET['q']){
            $args = $_GET['q']; //查询条件
//            var_dump($args);
        }else{
            $args = '';
        }
        $this->render('list',array('args'=>$args));
    }
    
    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('device', 'smallHeader New');
        $model = new Device('create');
        $r = array();
        if (isset($_POST['Device'])) {
//
            $args = $_POST['Device'];
            $old_file = $_POST['File'];
//            var_dump($_FILES['Device']);
//            exit;
//          //判断设备类型不为空
            if($args['type_no']==0){
                $r['status'] = '-1';
                $r['msg'] = Yii::t('device','Error Equipment_type is null');
                $r['refresh'] = false;
                goto end;
            }            
            //判断文件是不是为空
            if ($old_file['device_src'] == ''){
                $r['status'] = '-1';
                $r['msg'] = Yii::t('device','Error Upload_pic is null');
                $r['refresh'] = false;
                goto end;
            }
            if ($old_file['device_src'] <> ''){
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
//		var_dump($rs);
//                exit;
                $r['status'] = '';
                $r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $r['status']  .= $row['code'];
                        $r['msg']  .= $row['msg'].' ';
                    }else{
                        if($key == 'device_src') {
                            $args['device_img'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($r['status'] <> ''){
                    $r['refresh'] = false;
                    goto end;
//                    return $r;
                }
            }
            $r = Device::insertDevice($args);
            end:
//            $args['TYPE'] = 'MC';
//            $args['add_conid'] = Yii::app()->user->getState('contractor_id');
//            $args['add_operator'] = Yii::app()->user->id;
//            $r = Program::insertProgram($args);
//            var_dump($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Device'];
            }
        }

        $this->render('new', array('model' => $model,'msg' => $r));
    }
    /**
     * 修改
     */
    public function actionEdit() {
        $this->smallHeader = Yii::t('device', 'smallHeader Edit');
        $model = new Device('modify');
        $r = array();
//        $device_id = $_REQUEST['device_id'];
        $primary_id = $_REQUEST['primary_id'];
        $device_model = Device::model()->findByPk($primary_id);
        $device_id = $device_model->device_id;
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $qrcode = $device_model['qrcode'];
        if(!$qrcode){
            Device::buildQrCode($contractor_id,$primary_id);
        }

        if (isset($_POST['Device'])) {
            $args = $_POST['Device'];
            $old_file = $_POST['File'];
            
            if ($old_file['device_src'] <> ''){
                $rs = Compress::uploadPicture($old_file, Yii::app()->user->getState('contractor_id'));
//		var_dump($rs);
//                exit;
                $r['status'] = '';
                $r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $r['status']  .= $row['code'];
                        $r['msg']  .= $row['msg'].' ';
                    }else{
                        if($key == 'device_src') {
                            $args['device_img'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($r['status'] <> ''){
                    $r['refresh'] = false;
                    goto end;
//                    return $r;
                }
            }
            $r = Device::updateDevice($args,$device_id);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Device'];
            }
        }
        end:
        $model->_attributes = Device::model()->findByPk($primary_id);

        $this->render('edit', array('model' => $model, 'msg' => $r));
    }
        /**
     * 详细
     */
    public function actionDetail() {
        
    	$id = $_POST['id'];
        $model = Device::model()->find('device_id=:device_id',array(':device_id'=>$id));
        $primary_id = $model->primary_id;
        $program_list = ProgramDevice::DeviceProgramName($primary_id);
        $program_detail = '';
        if($program_list){
            foreach($program_list as $cnt=>$list){
                $program_detail .= $list['program_name'].',';
            }
            $program_detail = substr($program_detail,0,-1);
        }
    	$msg['status'] = true;
        $device_list = DeviceType::deviceList();
    	if ($model) {
    		$msg['detail'] .= "<table class='detailtab'>";
    		$msg['detail'] .= "<tr>";
				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('device_id') . "</td>";
				$msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->device_id) ? $model->device_id : "") . "</td>";
				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('device_name') . "</td>";
				$msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->device_name) ? $model->device_name : "") . "</td>";
				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('type_no') . "</td>";
				$msg['detail'] .= "<td class='tvalue-3 detail_phone'>" . (isset($model->type_no) ? $device_list[$model->type_no]: "") ."</td>";
    		$msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'where the project') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3' colspan='4'>" . $program_detail . "</td>";

            $msg['detail'] .= "</tr>";
			
    		$msg['detail'] .= "<tr>";
				//$msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('permit_startdate') . "</td>";
                //$msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->permit_startdate) ? Utils::DateToEn($model->permit_startdate): "") . "</td>";
				//$msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('permit_enddate') . "</td>";
                //$msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->permit_enddate) ? Utils::DateToEn($model->permit_enddate): "") . "</td>";
				$msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('record_time') . "</td>";
				$msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->record_time) ? Utils::DateToEn($model->record_time) : "") . "</td>";
			$msg['detail'] .= "</tr>";
			
//    		$msg['detail'] .= "<tr>";
//    	//	$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('team_id') . "</td>";
//    	//	$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->team_id) ? $model->team_id : "") . "</td>";
//				$msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('role_id') . "</td>";
//				$msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->role_id) ? $roleList[$model->role_id] : "") . "</td>";
//
//            $msg['detail'] .= "</tr>";    		
			$msg['detail'] .= "</table>";
    		print_r(json_encode($msg));
    	}
    }
    /**
     * 注销
     */
    public function actionLogout() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Device::logoutDevice($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
     /**
     * 保存查询链接
     */
    private function saveUrl() {
        $a = Yii::app()->session['list_url'];
        $a['device/list'] = str_replace("r=device/equipment/grid", "r=device/equipment/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
    /**
     * 资质证件照列表
     */
    public function actionAttachlist() {
//        var_dump($_SERVER['HTTPS']);
//        exit;
        $device_id = $_GET['device_id'];
        $primary_id = $_GET['primary_id'];
        if($_GET['type']){
            $type = $_GET['type'];
        }else{
            $type = 'mc';
        }
        if($primary_id <> '')
            $father_model = Device::model()->findByPk($primary_id);
//        var_dump($father_model);
//        exit;
        $this->smallHeader = $father_model->device_name;
        $this->contentHeader = Yii::t('device', 'Equipment_certificate');
        $this->bigMenu = $father_model->device_name;
        $this->render('attachlist',array('device_id'=>$device_id,'primary_id'=>$primary_id,'type'=>$type));
    }
    
    /**
     * 资质证件照表头
     */
    private function genAttachDataGrid($device_id,$primary_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=device/equipment/attachgrid&device_id='.$device_id.'&primary_id='.$primary_id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_staff', 'document_name'), '', '');
        $t->set_header(Yii::t('comp_staff', 'commonly_used'), '', '');
        $t->set_header(Yii::t('comp_staff', 'certificate_type'),'','');
        $t->set_header(Yii::t('comp_staff', 'certificate_startdate'),'','');
        $t->set_header(Yii::t('comp_staff', 'certificate_enddate'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Record Time'), '', '');
        $t->set_header(Yii::t('comp_staff', 'Action'), '', '');
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
        if($args['device_id'] == ''){
            $args['device_id'] = $_GET['device_id'];   
        }
        if($args['primary_id'] == ''){
            $args['primary_id'] = $_GET['primary_id'];
        }
        if(isset($_GET['type'])){
            $args['type'] = $_GET['type'];
        }
        if(array_key_exists('type',$args)){
            $type = $args['type'];
        }else{
            $type = 'mc';
        }
        $primary_id = $args['primary_id'];
        $device_id = $args['device_id'];
        
        $t = $this->genAttachDataGrid($device_id,$primary_id);
        //$this->saveUrl();
//        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($_GET['program_id']);
//        exit;
        $list = DeviceInfo::queryList($page, $this->pageSize, $args);

        $this->renderPartial('attach_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'],'device_id'=> $device_id,'curpage' => $list['page_num'],'type'=>$type));
    }
    /**
     * 资质证件上传
     */
    public function actionUpload() {
        $args = $_GET['q'];
        $device_id = $args['device_id'];
        $primary_id = $args['primary_id'];
        $device_model = Device::model()->findByPk($primary_id);
        $device_name = $device_model->device_name;
        $this->smallHeader = $device_name;
//        var_dump($task_id);
//        var_dump($program_id);
//        exit;
        $model = new DeviceInfo('create');
        $this->render('upload',array('model'=> $model,'device_id'=>$device_id,'primary_id'=>$primary_id, '_mode_' => 'insert'));
    }
    /**
     * 资质证件上传（修改）
     */
    public function actionDisplayUpload() {
        $device_id = $_REQUEST['device_id'];
        $id = $_REQUEST['id'];
        $primary_id = $_REQUEST['primary_id'];
        $device_model = Device::model()->findByPk($primary_id);
        $device_name = $device_model->device_name;
        $this->smallHeader = $device_name;
        $model = new DeviceInfo('modify');
        $model->_attributes = DeviceInfo::model()->find('id=:id', array(':id'=>$id));
        $this->render('upload',array('model'=> $model,'device_id'=>$device_id,'id'=>$id,'primary_id'=>$primary_id,'_mode_'=>'edit'));
    }
    /**
     * 资质证件删除
     */
    public function actionDeleteAptitude() {
        $args = array();
        $args['str'] = $_REQUEST['str'];
        $args['device_id'] = $_REQUEST['device_id'];
        $args['type_no'] = $_REQUEST['type_no'];
        $r = DeviceInfo::deleteAttach($args);
        print_r(json_encode($r));
    }
    /**
     * 将上传的图片移动到正式路径下
     */
//    public function actionMovePic() {
//        $file_src = $_REQUEST['file_src'];
//        $r = DeviceInfo::movePic($file_src);
//        print_r(json_encode($r));
//    }
    public function actionMovePic() {
//        $file_src = $_REQUEST['file_src'];
        $args = array();
        $args = $_POST['DeviceInfo'];
        $file_src = $args['tmp_src'];
        $r = DeviceInfo::movePic($file_src);
//        var_dump($args);
//        exit;
        if($r['status'] == 1){
            $args['certificate_photo'] = $r['src'];

            $name = substr($r['src'],25);
            $src = '/opt/www-nginx/web'.$r['src'];
//            var_dump($name);
//            var_dump($src);
//            exit;
            $file_name = explode('.',$name);
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $size = filesize($src)/1024;
            $args['certificate_size'] = sprintf('%.2f',$size);
//            $model->doc_path = substr($upload_file,18);
            $args['certificate_title'] = $file_name[0];
            $args['type'] = $file_name[1];
//            var_dump($args);
//            exit;
            if($args['mode'] == 'insert'){
                $r = DeviceInfo::insertAttach($args);
            }else{
                $r = DeviceInfo::updateAttach($args);
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
     * 设置常用
     */
    public function actionSetused() {
        $id = trim($_REQUEST['id']);
        $certificate_use = trim($_REQUEST['certificate_use']);
        $r = array();
        $r = DeviceInfo::setUsed($id,$certificate_use);
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $certificate_photo = $_REQUEST['certificate_photo'];
        $device_id = $_REQUEST['device_id'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('preview',array('certificate_photo'=>$certificate_photo,'device_id'=>$device_id));
    }
    /**
     * 删除文档
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = DeviceInfo::deleteFile($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $certificate_photo = $_REQUEST['certificate_photo'];
        $filepath = '/opt/www-nginx/web'.$certificate_photo;
        if(file_exists($filepath)){
            $file_name = explode('.',$certificate_photo);
            $show_name = $file_name[0];
            $filepath = '/opt/www-nginx/web'.$certificate_photo;
            $extend = $file_name[1];
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }
    /**
     * 在线预览文件
     */
    public function actionPreviewPrint() {
        $primary_id = $_REQUEST['primary_id'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('print_qrcode',array('primary_id'=>$primary_id));
    }
    /**
     * 轮播图片
     */
    public function actionShowPic() {
        $pic_str = $_REQUEST['pic_str'];
        $device_id = $_REQUEST['device_id'];
        $type_no = $_REQUEST['type_no'];
        $primary_id = $_REQUEST['primary_id'];
        $this->render('pic_show',array('pic_str'=>$pic_str,'device_id'=>$device_id,'type_no'=>$type_no,'primary_id'=>$primary_id));
    }
    /**
     * 编辑资质证件照和内容
     */
    public function actionEditAptitude() {
        $args = array();
        $args = $_POST['DeviceInfo'];
        $file_src = $args['tmp_src'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $args['permit_startdate'] = Utils::DateToCn($args['permit_startdate']);
        $args['permit_enddate'] = Utils::DateToCn($args['permit_enddate']);
//        var_dump($args);
//        exit;
        $r = DeviceInfo::updateAttach($args);
        print_r(json_encode($r));
    }

    /**
     * 设备统计信息
     */
    public function actionStatistics() {
        $this->contentHeader = Yii::t('device', 'Statistics contentHeader');
        $this->smallHeader = Yii::t('device', 'Statistics contentHeader');
        $device_id = $_REQUEST['device_id'];
        $primary_id = $_REQUEST['primary_id'];
        $contractor_id = Yii::app()->user->getState('contractor_id');

        $this->render('statisticslist',array('device_id' => $device_id,'primary_id'=>$primary_id));
    }

    /**
     * 人员信息局部刷新
     */
    public function actionSelfGrid($device_id) {

        $args = array();
        if($device_id == ''){
            $device_id = $_REQUEST['device_id'];
        }
        $contractor_id = Yii::app()->user->contractor_id;
        if($_REQUEST['program_id']){
            $program_id = $_REQUEST['program_id'];
        }else{
            $program_id = '';
        }
        $this->render('statistics_toolBox', array('device_id'=> $device_id,'contractor_id'=>$contractor_id,'program_id'=>$program_id));
    }
    /**
     * 人员入场出场
     */
    public function actionSelfByDate() {
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $device_id = $_REQUEST['device_id'];
        $program_id = $_REQUEST['program_id'];
        $date_info = ProgramDevice::SelfByDate($device_id,$program_id);
//        var_dump($date_info);
//        exit;
        print_r(json_encode($date_info));
    }
    /**
     * PTW按类别统计次数
     */
    public function actionPtwRole() {
        $device_id = $_REQUEST['device_id'];
        $program_id = $_REQUEST['program_id'];
        $ptw_role = ApplyBasic::deviceByType($device_id,$program_id);//PTW按权限统计次数
        print_r(json_encode($ptw_role));
    }

    /**
     * PTW统计总次数
     */
    public function actionPtwCnt() {
        $device_id = $_REQUEST['device_id'];
        $program_id = $_REQUEST['program_id'];
        $ptw_cnt = ApplyBasic::deviceByAll($device_id,$program_id);//PTW按权限统计次数
        print_r(json_encode($ptw_cnt));
    }

    /**
     * INSPECTION按权限/成员统计次数
     */
    public function actionInspectionRole() {
        $device_id = $_REQUEST['device_id'];
        $program_id = $_REQUEST['program_id'];
        $inspection_role = SafetyCheck::deviceByMember($device_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($inspection_role));
    }

    /**
     * INSPECTION统计总次数
     */
    public function actionInspectionCnt() {
        $device_id = $_REQUEST['device_id'];
        $program_id = $_REQUEST['program_id'];
        $inspection_cnt = SafetyCheck::deviceByAll($device_id,$program_id);//INSPECTION按权限统计次数
        print_r(json_encode($inspection_cnt));
    }

    //导出设备信息表
    public static function actionDeviceExport(){
        $args = $_GET['q'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $device = Device::deviceExport($args);
        $certificate_type = DeviceCertificate::certificateList();
        if (count($device) > 0) {
            foreach ($device as $key => $row) {
                $rs[$row['primary_id']] = $row['primary_id'];
            }
        }
        $deviceinfo = DeviceInfo::deviceinfoExport($rs);
//        var_dump($deviceinfo);
//        exit;
//        $rs = ProgramDevice::deviceinfo($args);
        $typeList = DeviceType::deviceList();//设备型号列表

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.'I1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('proj_project_device','project_device_excel'));
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'I2');
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
        $objectPHPExcel->getActiveSheet()->setCellValue('F3',Yii::t('comp_staff','aptitude_content'));
        $objectPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('G3',Yii::t('comp_staff', 'certificate_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('H3',Yii::t('comp_staff', 'certificate_startdate'));
        $objectPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('I3',Yii::t('comp_staff', 'certificate_enddate'));
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
//        var_dump($rs);
//        exit;
        $i = 0;
        $total_num = count($device) + count($deviceinfo); //总行数
        for($p =4;$p<=$total_num+3;$p++) {
            $objectPHPExcel->getActiveSheet()->getRowDimension($p)->setRowHeight(90);
        }
        foreach ($device as $k => $v) {
//            var_dump($device);
//            exit;
            $mergeCells = count($deviceinfo[$v['primary_id']]);
//                static $n = 1;

            if($mergeCells >1){
                $mergeCells =$mergeCells-1;
                $objectPHPExcel->getActiveSheet()->mergeCells('A'.($i+4).':'.'A'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('B'.($i+4).':'.'B'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('C'.($i+4).':'.'C'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('D'.($i+4).':'.'D'.($i+4+$mergeCells));
                $objectPHPExcel->getActiveSheet()->mergeCells('E'.($i+4).':'.'E'.($i+4+$mergeCells));
            }else{
                $mergeCells = 0;
            }
            /*设置表格高度*/

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
            if(!isset($v['qrcode'])){
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
                    $obj_Drawing->setCoordinates(E . ($i+4+$mergeCells));
                    /*设置图片所在单元格的格式*/
                    $obj_Drawing->setOffsetX(30);//30
                    $obj_Drawing->setOffsetY(5);
                    $obj_Drawing->setRotation(40);//40
                    $obj_Drawing->getShadow()->setVisible(true);
                    $obj_Drawing->getShadow()->setDirection(20);//20
                    $obj_Drawing->setWorksheet($objActSheet);
                }
            }
//            $objectPHPExcel->getActiveSheet()->setCellValue(F . ($k + 4),Utils::DateToEn($v['apply_date']));
//            $n++;
            if($deviceinfo[$v['primary_id']]) {
                $t = $i + 4;
                foreach ($deviceinfo[$v['primary_id']] as $e => $j) {
                    $objectPHPExcel->getActiveSheet()->setCellValue(F .$t, $j['certificate_title']);
                    $objectPHPExcel->getActiveSheet()->setCellValue(G .$t, $certificate_type[$j['certificate_type']]);
                    $objectPHPExcel->getActiveSheet()->setCellValue(H .$t, $j['permit_startdate']);
                    $objectPHPExcel->getActiveSheet()->setCellValue(I .$t, $j['permit_enddate']);
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
     * 表头
     * @return SimpleGrid
     */
    private function genConsassGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=device/equipment/consassgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('tbm_meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('device', 'Proposed Audit Date'), '', 'center');
        $t->set_header(Yii::t('device', 'Actual Audit Date'), '', 'center');
        $t->set_header(Yii::t('device', 'Audit Company'), '', 'center');
        $t->set_header(Yii::t('device', 'Remarks'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionConsassGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        if(count($fields) == 3 && $fields[0] != null ) {
//            $args['program_id'] = $fields[0];
//            $args['user_id'] = $fields[1];
//            $args['deal_type'] = $fields[2];
//        }
        $t = $this->genConsassGrid();
        $this->saveUrl();

        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = ConsassAudit::queryList($page, $this->pageSize, $args);
        $this->renderPartial('consass_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionConsassList() {

        $this->smallHeader = Yii::t('device', 'External ConSASS audit summary');
        $this->render('consasslist');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionConsass() {
        $this->render('consass');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionAddConsass() {
        $json = $_REQUEST['json_data'];
        $program_id = $_REQUEST['program_id'];
        $data = json_decode($json);
        $r = ConsassAudit::InsertList($data,$program_id);
        echo json_encode($r);
    }

    /**
     * 修改
     */
    public function actionEditConsass() {
        $this->smallHeader = Yii::t('device', 'External ConSASS audit summary');
        $model = new ConsassAudit('modify');
        $r = array();
        $id = $_REQUEST['id'];
        $consass_model = ConsassAudit::model()->findByPk($id);
        if (isset($_POST['ConsassAudit'])) {
            $args = $_POST['ConsassAudit'];

            $r = ConsassAudit::UpdateList($id,$args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['MeetingPlan'];
            }
        }

        $model->_attributes = ConsassAudit::model()->findByPk($id);

        $this->render('consass_form', array('id'=>$id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionDelConsass() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ConsassAudit::DeleteList($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    //导出PDF
    public function actionExportConsass(){
        $args = $_GET['q'];
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $list = ConsassAudit::exportList($args);

//        var_dump($list[0]->id);
//        exit;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = date('Y');
        $month = date('m');
        $check_id = date('YmdHis').rand(000001,999999);
        $filepath = Yii::app()->params['upload_report_path'].'/Consass' .$check_id .'.pdf';


//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle('External ConSASS Audit Summary');
        $pdf->SetSubject('External ConSASS Audit Summary');
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($args['contractor_id']);
        $contractor_name =$main_model->contractor_name;
        $logo_pic = $main_model->remark;
        if($logo_pic){
            $logo = '/opt/www-nginx/web'.$logo_pic;
            $pdf->SetHeaderData($logo, 20,  'External ConSASS Audit Summary', $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }else{
            $pdf->SetHeaderData('', 0, 'External ConSASS Audit Summary', $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->SetFont('stsongstdlight', '', 14, '', true); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
//        }

        $pdf->AddPage();

        //标题(许可证类型+项目)
        $title_html = "<h2 align=\"center\">Project (项目) : $program_name</h2><br/>";
        $condition_html = '<table border="1"><tr><td width="10%" nowrap="nowrap" align="center">&nbsp;S/N</td><td width="25%" nowrap="nowrap" align="center">&nbsp;Proposed Audit Date</td><td width="25%" nowrap="nowrap" align="center">Actual Audit Date</td><td width="20%" nowrap="nowrap" align="center">Audit Company</td><td width="20%" nowrap="nowrap" align="center">Remarks</td></tr>';

        $u =0;
        foreach($list as $k => $v){
            $u = 1;
            $condition_html .= '<tr><td align="center" >&nbsp;' . $u . '</td><td>&nbsp;' . $v->proposed_date . '</td><td align="center" >&nbsp;' . $v->actual_date . '</td><td>&nbsp;' . $v->audit_company . '</td><td align="center" >&nbsp;' . $v->remarks . '</td></tr>';
            $u++;
        }

        $condition_html .= '</table>';

        $html = $title_html.$condition_html ;
        $pdf->writeHTML($html, true, false, true, false, '');



        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'D');
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }
}

