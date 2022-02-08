<?php

class ReportController extends AuthBaseController {
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('report', 'bigMenu');
        $this->bigMenu = Yii::t('report', 'contentHeader');
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=report/report/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('license_licensepdf', 'apply_id'), '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'program_name'), '', 'center');
        $t->set_header(Yii::t('report', 'report_date'), '', 'center');
        $t->set_header(Yii::t('report', 'status'), '', 'center');
        $t->set_header(Yii::t('report', 'record_time'), '', 'center');
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
//        var_dump($args);
//        exit;
	if($args['status'] == ''){
            $args['status'] = '00';
	}	
        $t = $this->genDataGrid();
        $this->saveUrl();
//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        var_dump($args);
        
        $list = Report::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'info'=> $info, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    public function actionList() {

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
//        var_dump($time);
//        exit;
        //报告路径存入数据库

//        $filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';

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
        $pdf->SetTitle('lalala');
        $pdf->SetSubject('lalala');


//        $logo_pic = 'tcpdf_signature.png';
        // 设置页眉和页脚信息

            $pdf->SetHeaderData('', 0,  'Application No.(申请编号) : ' . $apply->apply_id, $main_conid_name, array(0, 64, 255), array(0, 64, 128));

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
        $content = 'http://t.cmstech.aoepos.cn/opt/www-nginx/web/filebase/data/qrcode/146/user/3626.png';
        $pic_html = "<table border='1'>
<tr><td>1</td><td>2</td><td>3</td></tr>
<tr>
</tr>
<tr><td>7</td><td>8</td><td>9</td></tr>
</table>";
        $pdf->Image($content, 16, 16, 30, 23, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        $html_1 = $pic_html ;
//        var_dump($html_1);
//        exit;
        $pdf->writeHTML($html_1, true, false, true, false, '');

        //输出PDF
        $pdf->Output($filepath, 'I');

//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }


    /**
     * 添加计划
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('report', 'Add Report');
        $model = new Report('create');
        $r = array();
        if (isset($_POST['Report'])) {
            $args = $_POST['Report'];
            $r = Report::InsertList($args);

        }

        $this->render('_form', array('model' => $model,'msg' => $r,'_mode_'=>'insert'));
    }
    /**
     * 修改
     */
    public function actionEdit() {
        $this->smallHeader = Yii::t('report', 'Edit Report');
        $model = new MeetingPlan('modify');
        $r = array();
        $id = $_REQUEST['id'];
        $report_model = Report::model()->findByPk($id);
        if (isset($_POST['Report'])) {
            $args = $_POST['Report'];

            $r = Report::UpdateList($id,$args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Report'];
            }
        }

        $model->_attributes = Report::model()->findByPk($id);

        $this->render('_form', array('id'=>$id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionDelPlan() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = MeetingPlan::DeleteList($id);
        }
        //var_dump($r);
        echo json_encode($r);
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

}

