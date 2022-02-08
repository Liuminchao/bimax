<?php
/**
 * 质量检查
 * @author LiuMinChao
 */
class QainspectionController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_routine', 'contentHeader');
        $this->bigMenu = Yii::t('comp_routine', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($args) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/qainspection/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('S/N', '', 'center');
//        $t->set_header('Type', '', 'center');
//        $t->set_header('Stage', '', 'center');
//        $t->set_header('Trade', '', 'center');
//        $t->set_header('Component Name', '', 'center');
//        $t->set_header(Yii::t('comp_qa', 'check_type'), '', 'center');
//        $t->set_header(Yii::t('comp_qa', 'form_type'), '', 'center');
        if($args['program_id'] == '2419'){
            $t->set_header('Inspection No.', '', 'center');
        }
        $t->set_header('Title', '', 'center');

        $t->set_header(Yii::t('comp_qa', 'applicant_name'), '', 'center');
        $t->set_header('Company', '', 'center');
//        $t->set_header(Yii::t('comp_routine', 'check_kind'), '', '');
//        $t->set_header(Yii::t('comp_routine', 'violation_record'), '', '');
//        $t->set_header(Yii::t('comp_routine', 'apply_date'), '', '');
        $t->set_header(Yii::t('comp_qa', 'date_of_application'), '', 'center');
        $t->set_header(Yii::t('comp_qa', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '10%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        var_dump($args);
//        exit;

        $t = $this->genDataGrid($args);
        if($args['clt_type'] == '01'){
            $this->saveCsUrl();
        }else if($args['clt_type'] == '02'){
            $this->saveArUrl();
        }else if($args['clt_type'] == '03'){
            $this->saveMeUrl();
        }else if($args['clt_type'] == '04'){
            $this->saveGeneralUrl();
        }
//        $args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = QaCheck::queryList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('_list', array('t' => $t, 'program_id'=>$args['program_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }


    /**
     * 列表
     */
    public function actionArList() {
        $program_id = $_REQUEST['program_id'];
        $clt_type = '02';
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['program_id']){
                $program_id = $args['program_id'];
            }
            if($args['clt_type']){
                $clt_type = $args['clt_type'];
            }
        }
        $this->smallHeader = Yii::t('comp_qa', 'smallHeader List');
        $this->render('list',array('program_id'=>$program_id,'clt_type'=>$clt_type, 'args'=>$args));
    }

    /**
     * 列表
     */
    public function actionCsList() {
        $program_id = $_REQUEST['program_id'];
        $clt_type = '01';
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['program_id']){
                $program_id = $args['program_id'];
            }
            if($args['clt_type']){
                $clt_type = $args['clt_type'];
            }
        }
        $this->smallHeader = Yii::t('comp_qa', 'smallHeader List');
        $this->render('list',array('program_id'=>$program_id,'clt_type'=>$clt_type, 'args'=>$args));
    }

    /**
     * 列表
     */
    public function actionMeList() {
        $program_id = $_REQUEST['program_id'];
        $clt_type = '03';
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['program_id']){
                $program_id = $args['program_id'];
            }
            if($args['clt_type']){
                $clt_type = $args['clt_type'];
            }
        }
        $this->smallHeader = Yii::t('comp_qa', 'smallHeader List');
        $this->render('list',array('program_id'=>$program_id,'clt_type'=>$clt_type, 'args'=>$args));
    }

    /**
     * 列表
     */
    public function actionGeneralList() {
        $program_id = $_REQUEST['program_id'];
        $clt_type = '04';
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['program_id']){
                $program_id = $args['program_id'];
            }
            if($args['clt_type']){
                $clt_type = $args['clt_type'];
            }
        }
        $this->smallHeader = Yii::t('comp_qa', 'smallHeader List');
        $this->render('list',array('program_id'=>$program_id,'clt_type'=>$clt_type, 'args'=>$args));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genCheckDataGrid($args) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/qainspection/checkgrid';
        $t->updateDom = 'datagrid';
        $t->set_header('S/N', '', 'center');
        $t->set_header('Type', '', 'center');
        $t->set_header('Stage', '', 'center');
//        $t->set_header('Trade', '', 'center');
        $t->set_header('Element', '', 'center');
//        $t->set_header(Yii::t('comp_qa', 'check_type'), '', 'center');
//        $t->set_header(Yii::t('comp_qa', 'form_type'), '', 'center');
        if($args['program_id'] == '2419'){
            $t->set_header('Inspection No.', '', 'center');
        }
        $t->set_header('Title', '', 'center');

        $t->set_header(Yii::t('comp_qa', 'applicant_name'), '', 'center');
        $t->set_header('Company', '', 'center');
//        $t->set_header(Yii::t('comp_routine', 'check_kind'), '', '');
//        $t->set_header(Yii::t('comp_routine', 'violation_record'), '', '');
//        $t->set_header(Yii::t('comp_routine', 'apply_date'), '', '');
        $t->set_header(Yii::t('comp_qa', 'date_of_application'), '', 'center');
        $t->set_header(Yii::t('comp_qa', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionCheckGrid($check_id) {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        var_dump($args);
//        exit;
        $args['check_id'] = $check_id;
        $t = $this->genCheckDataGrid($args);
        if($args['clt_type'] == '01'){
            $this->saveCsUrl();
        }else if($args['clt_type'] == '02'){
            $this->saveArUrl();
        }else if($args['clt_type'] == '03'){
            $this->saveMeUrl();
        }else if($args['clt_type'] == '04'){
            $this->saveGeneralUrl();
        }

//        $args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = QaCheck::queryListByTask($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('check_list', array('t' => $t, 'program_id'=>$args['program_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }


    /**
     * 列表
     */
    public function actionChecklist() {
        $check_id = $_REQUEST['check_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        $this->smallHeader = Yii::t('comp_qa', 'smallHeader List');
        $this->render('checklist',array('check_id'=>$check_id,'args'=>$args));
    }

    /**
     * 按承包商查询表单类型
     */
    public function actionQueryForm() {
        $type_id = $_POST['type_id'];
        $program_id = $_POST['program_id'];
        $rows = QaChecklist::formByType($type_id,$program_id);

        print_r(json_encode($rows));
    }

    /**
     * 导出安全报告界面
     */
    public function actionExport() {
//        $model = new SafetyCheck('create');
        $contractor_id = $_REQUEST['contractor_id'];
//        $args = $_GET['q']; //查询条件
//        $r = SafetyCheckDetail::detailAllList($args);
//        var_dump($args);
//        var_dump($contractor_id);
//        exit;
        $this->renderPartial('export', array('contractor_id' => $contractor_id));
    }
    /**
     * 下载PDF
     */
    public static function actionDownloadPdf() {
        $check_id = $_REQUEST['check_id'];
        $params['check_id'] = $check_id;
        $app_id = 'QA';
        $check_list = RoutineCheck::detailList($check_id);//例行检查单
        $title = $app_id.$check_id;//文件名
//        if($check_list[0]['save_path']){
//            $file_path = $check_list[0]['save_path'];
//            $filepath = '/opt/www-nginx/web'.$file_path;
//        }else{
//            $filepath = DownloadPdf::transferDownload($params,$app_id);
//        }
        DownloadPdf::transferDownload($params,$app_id);
//        Utils::Download($filepath, $title, 'pdf');
    }

    /**
     * 按承包商查询PTW类型
     */
    public function actionQueryType() {
        $program_id = $_POST['program_id'];

        $rows = QaCheckType::typeByContractor($program_id);

        print_r(json_encode($rows));
    }
    /**
     * 保存查询链接
     */
    private function saveArUrl() {
        $a = Yii::app()->session['list_url'];
        $a['qainspection/list'] = str_replace("r=qa/qainspection/grid", "r=qa/qainspection/arlist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    private function saveCsUrl() {
        $a = Yii::app()->session['list_url'];
        $a['qainspection/list'] = str_replace("r=qa/qainspection/grid", "r=qa/qainspection/cslist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    private function saveMeUrl() {
        $a = Yii::app()->session['list_url'];
        $a['qainspection/list'] = str_replace("r=qa/qainspection/grid", "r=qa/qainspection/melist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    private function saveGeneralUrl() {
        $a = Yii::app()->session['list_url'];
        $a['qainspection/list'] = str_replace("r=qa/qainspection/grid", "r=qa/qainspection/generallist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    /**
     * 预览流程图
     */
    public function actionPreview() {
        $check_id = $_REQUEST['check_id'];
        $check_list = RoutineCheck::detailList($check_id);//例行检查单
        $detail_list = RoutineCheckDetail::detailList($check_id);//例行检查单详情
        $status_list = RoutineCheck::statusText(); //状态text
//        var_dump($detail_list);
//        exit;
        $this->renderPartial('preview',array('check_list' => $check_list,'detail_list'=>$detail_list,'status_list'=>$status_list));
    }

    /**
     * 项目统计图表
     */
    public function actionProjectChart() {
        $this->smallHeader = Yii::t('dboard', 'Project Statistical Charts');
        $platform = $_REQUEST['platform'];
        $program_id = $_REQUEST['program_id'];
        if(!is_null($platform)){
            $this->layout = '//layouts/main_2';
            $this->render('statistical_project_dash',array('program_id'=>$program_id,'platform'=>$platform));
        }else{
            $this->render('statistical_project',array('program_id'=>$program_id));
        }
    }
    /**
     * 公司统计图表
     */
    public function actionCompanyChart() {
        $this->smallHeader = Yii::t('dboard', 'Company Statistical Charts');
        $this->render('statistical_company');
    }
    /**
     *查询违规次数（项目）
     */
    public function actionCntByProject() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = QaCheck::AllCntList($args);
        print_r(json_encode($r));
    }
    /**
     *查询违规次数（公司）
     */
    public function actionCntByCompany() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RoutineCheck::CompanyCntList($args);
        print_r(json_encode($r));
    }


    /**
     * 下载列表
     */
    public function actionDownloadPreview() {
        $check_id = $_REQUEST['check_id'];
        $form_data_list = QaFormData::detailList($check_id); //记录
        $this->renderPartial('download_preview', array('check_id'=>$check_id,'form_data_list'=>$form_data_list));
    }

    /**
     * 下载附件列表
     */
    public function actionDownloadAttachment() {
        $check_id = $_REQUEST['check_id'];
        $documen_list = QaDocument::detailList($check_id); //记录
        $this->renderPartial('download_attachment', array('check_id'=>$check_id,'documen_list'=>$documen_list));
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $record_id = $_REQUEST['record_id'];
        $qa_document = QaDocument::model()->findByPk($record_id);
        $doc_path = $qa_document->doc_path;
        $show_name = $qa_document->doc_name;
        $filepath = '/opt/www-nginx/web'.$doc_path;
        $extend = $qa_document->doc_type;
        Utils::Download($filepath, $show_name, $extend);
        return;
    }

    /**
     * 上传
     */
    public function actionUpload() {
        $check_id = $_REQUEST['check_id'];
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('upload',array('check_id'=>$check_id,'program_id'=>$program_id));
    }

    /**
     * 将上传的图片移动到正式路径下
     */
    public function actionMove() {
        $args = $_REQUEST['qa'];
        $r = QaDocument::movePic($args);
        print_r(json_encode($r));
    }

    //导出excel报告
    public static function actionQaExport(){
        $check_id = $_GET['check_id'];
        $data_id = $_GET['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $check_model = QaCheck::model()->findByPk($check_id);
        $project_id = $check_model->project_id;
        $form_id = $form_model->form_id;
        $app_id = 'QA';
        $params['check_id'] = $check_id;
        $params['data_id'] = $data_id;
        if($_GET['tag']){
            $params['tag'] = $_GET['tag'];
        }
        $form_list = array(
            'FORM0002' => 'actionQaBoredPileExport',
            'C1-CS-02.1' => 'actionCs01Export',
            'HDB-CS-02.2' => 'actionCs02Export',
            'C1-CS-04' => 'actionCs04Export',
            'C1-CS-06' => 'actionCs06Export',
            'EPE-CS-01' => 'actionEPECs01Export',
            'EPE-CS-02' => 'actionEPECs02Export',
            'EPE-CS-03' => 'actionEPECs03Export',
            'EPE-CS-04' => 'actionEPECs04Export',
            'EPE-CS-05' => 'actionEPECs05Export',
            'EPE-CS-06' => 'actionEPECs06Export',
//            'EPE-CS-07' => 'actionEPECs07Export',
            'EPE-CS-08' => 'actionEPECs08Export',
            'EPE-CS-09' => 'actionEPECs09Export',
            'EPE-CS-10' => 'actionEPECs10Export',
            'EPE-ME-01' => 'actionEPEMe01Export',
            'EPE-ME-02' => 'actionEPEMe02Export',
            'EPE-ME-03' => 'actionEPEMe03Export',
            'EPE-ME-04' => 'actionEPEMe04Export',
            'EPE-ME-05' => 'actionEPEMe05Export',
            'EPE-ME-06' => 'actionEPEMe06Export',
            'N6C4C5-CS-01.1.1' => 'actionN6CCS01Export',
            'N6C4C5-CS-01.2.1' => 'actionN6CCS02Export',
            'N6C4C5-ER-CS02.1' => 'actionN6CCS03Export',
        );
        if($project_id == '1661'){
            QaCheck::downloaddefaultPDF($params,$data_id);
        }else if($project_id == '1261') {
            if($form_id == 'FR-PBU-02' || $form_id == 'FR-PBU-03' || $form_id == 'FR-PBU-04' || $form_id == 'FR-PBU-05' || $form_id == 'FR-PBU-06' || $form_id == 'FR-PBU-07' || $form_id == 'FR-PBU-08'){
                QaCheck::downloadflorencePDF_1($params,$data_id);
            }else{
                QaCheck::downloadflorencePDF_2($params,$data_id);
            }
        }else if($project_id == '1609'){
            QaCheck::downloadwsfPDF($params,$data_id);
//                self::actionQaWsfExport($check_id,$data_id);
        }else if(array_key_exists($form_id, $form_list)) {
            self::$form_list[$form_id]($params,$data_id);
        }else{
//            self::actionQaDefaultExport($check_id,$data_id);
            QaCheck::downloaddefaultPDF($params,$data_id);
        }
    }

    //导出excel默认报告
    public static function actionQaDefaultExport($check_id,$data_id){

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detailRecord($check_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_tag++;
                    if($prepare_tag == 1){
                        $prepare['signature_path'] = $signature_path;
                        $prepare['user_name'] =$user_name;
                        $prepare['contractor_name'] = $contractor_name;
                        $prepare['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
            $checked_tag = 0;
            if($v['deal_type'] == '3'){
                if($checked_count <= $v['step']){
                    $checked_tag++;
                    if($checked_tag == 1){
                        $checked['signature_path'] =$signature_path;
                        $checked['user_name'] =$user_name;
                        $checked['contractor_name'] = $contractor_name;
                        $checked['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
            $approved_tag = 0;
            if($v['deal_type'] == '4'){
                if($checked_count <= $v['step']){
                    $approved_tag++;
                    if($approved_tag == 1){
                        $approved['signature_path'] =$signature_path;
                        $approved['user_name'] =$user_name;
                        $approved['contractor_name'] = $contractor_name;
                        $approved['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
        }
//        var_dump($detail);
//        exit;
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        $objectPHPExcel->getDefaultStyle()->getFont()->setName( 'Times New Roman');
        $objectPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        //报表头的输出

        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.'C2');
        $objectPHPExcel->getActiveSheet()->getStyle('A1'.':'.'C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',$data_title);
        //Times New Roman
        $objectPHPExcel->getActiveSheet()->getStyle( 'A1')->getFont()->setName( 'Times New Roman');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A1')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(31.5);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('1')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('2')->setWidth(40);
        $objStyleA1 = $objActSheet->getStyle('A1');
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objStyleA1->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//        $objectPHPExcel->getActiveSheet()->getStyle('A1'.':'.'I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(16);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3',Yii::t('comp_qa','export_project'));
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->mergeCells('B3'.':'.'C3');
        $objectPHPExcel->getActiveSheet()->getStyle('B3'.':'.'C3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',$project_name);
        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A4',Yii::t('comp_qa','export_block'));
        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->mergeCells('B4'.':'.'C4');
        $objectPHPExcel->getActiveSheet()->setCellValue('B4',$block.' '.$secondary_region);
        $objectPHPExcel->getActiveSheet()->getStyle('B4'.':'.'C4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $y =0;
        $x = 0;
        $tmp = '';
        foreach($data as $group_name => $res){
            $y++;
            foreach ($res as $k => $v) {
//                $group_name = $item[$v['item_id']]['group_name'];
                if($tmp != $group_name){
                    $tmp = $group_name;
                    if($y > 1){
                        $objectPHPExcel->getActiveSheet()->mergeCells(A . ($x + 5).':'.C . ($x + 5));
                        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 5))->getFont()->setName( 'Times New Roman');
                        $objectPHPExcel->getActiveSheet()->setCellValue(A . ($x + 5),$tmp);
                        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 5))->getAlignment()->setWrapText(true);
                        $objectPHPExcel->getActiveSheet()->getStyle( A . ($x + 5))->getFont()->setBold(true);
                        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 5).':'.B . ($x + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $x++;
                    }
                }
//                static $n = 1;
                /*设置表格高度*/
//            $objectPHPExcel->getActiveSheet()->getRowDimension($k+4)->setRowHeight(90);
                $objectPHPExcel->getActiveSheet()->setCellValue(A . ($x + 5),$item[$v['item_id']]['item_title']);
                $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 5))->getFont()->setName( 'Times New Roman');
                $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 5))->getAlignment()->setWrapText(true);
                $objectPHPExcel->getActiveSheet()->setCellValue(B . ($x + 5),$v['item_value']);
                $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                if($v['remarks']){
                    $objectPHPExcel->getActiveSheet()->setCellValue(C . ($x + 5),$v['remarks']);
                }
                $x++;

            }
        }

        //->setName( 'Times New Roman');
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+13),Yii::t('comp_qa','Prepared By'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 13).':'.C . ($x + 13));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+13),$prepare['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 13))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 13).':'.C . ($x + 13))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+14),Yii::t('comp_qa','Signature'));
        $objectPHPExcel->getActiveSheet()->getStyle(A .($x+14))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getRowDimension($x+14)->setRowHeight(50);
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 14).':'.C . ($x + 14));
        if($prepare['signature_path']){
            if(file_exists($prepare['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($prepare['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates(B .($x+14));
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
//        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+19),$prepare['contractor_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 14))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 14).':'.C . ($x + 14))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+15),Yii::t('comp_qa','rep_time'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 15).':'.C . ($x + 15));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+15),$prepare['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 15))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 15).':'.C . ($x + 15))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+17),Yii::t('comp_qa','Checked By'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 17).':'.C . ($x + 17));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+17),$checked['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 17))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 17).':'.C . ($x + 17))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+18),Yii::t('comp_qa','Signature'));
        $objectPHPExcel->getActiveSheet()->getStyle(A .($x+18))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getRowDimension($x+18)->setRowHeight(50);
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 18).':'.C . ($x + 18));
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 18).':'.C . ($x + 18))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        if($checked['signature_path']){
            if(file_exists($checked['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates(B .($x+18));
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
//        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+22),$checked['contractor_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 18))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 18).':'.C . ($x + 18))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+19),Yii::t('comp_qa','Date & Time'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 19).':'.C . ($x + 19));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+19),$checked['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 19))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 19).':'.C . ($x + 19))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+21),Yii::t('comp_qa','Approved By'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 21).':'.C . ($x + 21));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+21),$approved['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 21))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 21).':'.C . ($x + 21))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+22),Yii::t('comp_qa','Signature'));
        $objectPHPExcel->getActiveSheet()->getStyle(A .($x+22))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getRowDimension($x+22)->setRowHeight(50);
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 22).':'.C . ($x + 22));
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 22).':'.C . ($x + 22))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        if($approved['signature_path']){
            if(file_exists($approved['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approved['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates(B .($x+22));
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
//        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+25),$approved['contractor_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 22))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 22).':'.C . ($x + 22))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+23),Yii::t('comp_qa','Date & Time'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 23).':'.C . ($x + 23));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+23),$approved['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 23))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 23).':'.C . ($x + 23))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objectPHPExcel->getActiveSheet()->mergeCells('A'.($x + 25).':'.'C'.($x + 25));
        $objectPHPExcel->getActiveSheet()->getStyle('A'.($x + 25).':'.'C'.($x + 25))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($x + 25),'Photos & Remarks');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A' . ($x + 25))->getFont()->setBold(true);
        $objStyleA1 = $objActSheet->getStyle('A' . ($x + 25));
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $normal_tag = $x + 26;
        foreach($record_detail as $i => $j){
//            var_dump($j['pic']);
            $record_time = Utils::DateToEn($j['record_time']);
            $apply_user_name = $staff_list[$j['user_id']];
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . $normal_tag,'Date：'.$record_time);
            $objectPHPExcel->getActiveSheet()->getStyle('A' . $normal_tag)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($normal_tag+1),'Name：'.$apply_user_name);
            $objectPHPExcel->getActiveSheet()->getStyle('A' . ($normal_tag+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($normal_tag+2),'Remarks：');
            $objectPHPExcel->getActiveSheet()->getStyle('A' . ($normal_tag+2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->mergeCells('A'.($normal_tag+3).':'.'A'.($normal_tag+6));
            $objectPHPExcel->getActiveSheet()->getStyle('A'.($normal_tag+3).':'.'A'.($normal_tag+6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($normal_tag+3),$j['remark']);
            $objectPHPExcel->getActiveSheet()->getStyle('A'.($normal_tag+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if($j['pic'] != '-1' && $j['pic'] != ''){
                $pic = explode('|', $j['pic']);
                $num=1;
                foreach ($pic as $key => $content) {
                    if(file_exists($content)) {
                        $a = chr(65+$num);
                        $objectPHPExcel->getActiveSheet()->mergeCells($a.$normal_tag.':'.$a.($normal_tag + 6));
                        $objectPHPExcel->getActiveSheet()->getStyle($a.$normal_tag.':'.$a.($normal_tag + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $num++;
                        /*实例化excel图片处理类*/
                        $objDrawing = new PHPExcel_Worksheet_Drawing();
                        /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
//                        var_dump($content);
//                        exit;
                        $objDrawing->setPath($content);

                        /*设置图片高度*/
                        $objDrawing->setHeight(130);
                        /*设置图片宽度*/
                        $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                        /*设置图片要插入的单元格*/
                        $objDrawing->setCoordinates($a . $normal_tag);
                        /*设置图片所在单元格的格式*/
                        $objDrawing->setOffsetX(30);//30
                        $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                        $objDrawing->getShadow()->setVisible(true);
                        $objDrawing->getShadow()->setDirection(20);//20
                        $objDrawing->setWorksheet($objActSheet);
                    }
                }
            }else{
                $objectPHPExcel->getActiveSheet()->mergeCells('B'.$normal_tag.':'.'C'.($normal_tag + 6));
                $objectPHPExcel->getActiveSheet()->setCellValue('B' . $normal_tag,'N/A');
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$normal_tag.':'.'C'.($normal_tag + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$normal_tag)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$normal_tag)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }
            $normal_tag++;
            $normal_tag = $normal_tag+6;
        }
        //        foreach($detail as $key => $val){
//            $user_model = Staff::model()->findByPk($val['user_id']);
//            $contractor_id = $user_model->contractor_id;
//            $con_model = Contractor::model()->findByPk($contractor_id);
//
//            if($val['deal_type'] == '1'){
//
//            }else if ($val['deal_type'] == '2'){
//
//            }else if($val['deal_type'] == '3'){
//
//            }
//        }
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.$data_title.'-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }


    //导出QaBoredPile报告
    public static function actionQaBoredPileExport($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detailRecord($check_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_tag++;
                    if($prepare_tag == 1){
                        $prepare['signature_path'] = $signature_path;
                        $prepare['user_name'] =$user_name;
                        $prepare['contractor_name'] = $contractor_name;
                        $prepare['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
            $checked_tag = 0;
            if($v['deal_type'] == '3'){
                if($checked_count <= $v['step']){
                    $checked_tag++;
                    if($checked_tag == 1){
                        $checked['signature_path'] =$signature_path;
                        $checked['user_name'] =$user_name;
                        $checked['contractor_name'] = $contractor_name;
                        $checked['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
            $approved_tag = 0;
            if($v['deal_type'] == '4'){
                if($checked_count <= $v['step']){
                    $approved_tag++;
                    if($approved_tag == 1){
                        $approved['signature_path'] =$signature_path;
                        $approved['user_name'] =$user_name;
                        $approved['contractor_name'] = $contractor_name;
                        $approved['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
        }
//        var_dump($detail);
//        exit;
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/NC BP Record.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'NC BP Record' );
        $colIndex_1 = 'G';
        $colIndex_2 = 'H';
        $colIndex_3 = 'I';
        $rowIndex_1 = 7;
        $rowIndex_2 = 5;
        $rowIndex_3 = 9;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 7;$rowIndex_1<=49;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
//                var_dump($cell);
//                exit;
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
            for($rowIndex_2 = 5;$rowIndex_2<=27;$rowIndex_2++){
                $addr_2 = $colIndex_2.$rowIndex_2;
                $cell = $currentSheet->getCell($addr_2)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_2,$j['item_value']);
                }
            }
            for($rowIndex_3 = 9;$rowIndex_3<=27;$rowIndex_3++){
                $addr_3 = $colIndex_3.$rowIndex_3;
                $cell = $currentSheet->getCell($addr_3)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_3,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('C5',$project_name);
        $objActSheet->setCellValue('K43',$form_data[57]['item_value']);
        $objActSheet->setCellValue('K45',$form_data[58]['item_value']);
        $objActSheet->setCellValue('J47',$form_data[59]['item_value']);
        $objActSheet->setCellValue('K48',$form_data[60]['item_value']);
        $objActSheet->setCellValue('K49',$form_data[61]['item_value']);

        $objActSheet->setCellValue('A51',$prepare['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle('A51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        if($prepare['signature_path']){
            if(file_exists($prepare['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($prepare['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A53');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('A57',$prepare['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle('A57')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objActSheet->setCellValue('G51',$checked['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle('G51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        if($checked['signature_path']){
            if(file_exists($checked['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('G53');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G57',$checked['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle('G57')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objActSheet->setCellValue('L51',$approved['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle('L51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        if($approved['signature_path']){
            if(file_exists($approved['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approved['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('L53');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('L57',$approved['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle('L57')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        //导出
        $filename = 'Bore Pile Record-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出wsf报告
    public static function actionQaWsfExport($check_id,$data_id){

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detailRecord($check_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_tag++;
                    if($prepare_tag == 1){
                        $prepare['signature_path'] = $signature_path;
                        $prepare['user_name'] =$user_name;
                        $prepare['contractor_name'] = $contractor_name;
                        $prepare['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
            $checked_tag = 0;
            if($v['deal_type'] == '3'){
                if($checked_count <= $v['step']){
                    $checked_tag++;
                    if($checked_tag == 1){
                        $checked['signature_path'] =$signature_path;
                        $checked['user_name'] =$user_name;
                        $checked['contractor_name'] = $contractor_name;
                        $checked['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
            $approved_tag = 0;
            if($v['deal_type'] == '4'){
                if($checked_count <= $v['step']){
                    $approved_tag++;
                    if($approved_tag == 1){
                        $approved['signature_path'] =$signature_path;
                        $approved['user_name'] =$user_name;
                        $approved['contractor_name'] = $contractor_name;
                        $approved['record_time'] = Utils::DateToEn($v['record_time']);
                    }
                }
            }
        }
//        var_dump($detail);
//        exit;
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
        foreach ($form_data as $i => $j){
            switch ($item[$j['item_id']]['item_title']) {
                case 'Rev.0:':{
                    $info['rev'] = $j['item_value'];
                    break;
                }
                case 'Date:':{
                    $info['date'] = $j['item_value'];
                    break;
                }
                case 'Doc.No:': {
                    $info['doc'] = $j['item_value'];
                    break;
                }
                case 'Location/GL:': {
                    $info['location'] = $j['item_value'];
                    break;
                }
                case 'Drawing Reference :': {
                    $info['draw'] = $j['item_value'];
                    break;
                }
                case 'Form Ref No:':{
                    $info['ref'] = $j['item_value'];
                    break;
                }
                default: {
                    break;
                }
            }
        }
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objectPHPExcel = $objReader->load("./template/excel/WSF.xlsx");

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        $objectPHPExcel->getDefaultStyle()->getFont()->setName( 'Times New Roman');
        $objectPHPExcel->getDefaultStyle()->getFont()->setSize(12);
        //报表头的输出 Times New Roman
        $objectPHPExcel->getActiveSheet()->mergeCells('B1'.':'.'B3');
        $objectPHPExcel->getActiveSheet()->getStyle('B1'.':'.'B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',$data_title);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B1')->getFont()->setName( 'Times New Roman');
        $objectPHPExcel->getActiveSheet()->getStyle( 'B1')->getFont()->setBold(true);
        $objStyleA1 = $objActSheet->getStyle('B1');
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objStyleA1->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('C1', $info['ref']);
        $objectPHPExcel->getActiveSheet()->setCellValue('C3', 'Rev. 0:   '.$info['rev']);
        $objectPHPExcel->getActiveSheet()->setCellValue('C4', 'Date. :   '.$info['date']);
        $objectPHPExcel->getActiveSheet()->setCellValue('C5', 'Doc. No.:   '.$info['doc']);
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(16);
        $objectPHPExcel->getActiveSheet()->setCellValue('A4', 'Project :   '.$project_name);
        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A5',' Location / GL :  '.$info['location']);
        $objectPHPExcel->getActiveSheet()->setCellValue('A6',' Drawing Reference :  '.$info['draw']);


        $y =0;
        $x = 0;
        $tmp = '';
        foreach($data as $group_name => $res){
            $y++;
            foreach ($res as $k => $v) {
//                $group_name = $item[$v['item_id']]['group_name'];
                if($tmp != $group_name){
                    $tmp = $group_name;
                    if($y > 1){
                        $objectPHPExcel->getActiveSheet()->mergeCells(A . ($x + 8).':'.C . ($x + 8));
                        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 8))->getFont()->setName( 'Times New Roman');
                        $objectPHPExcel->getActiveSheet()->setCellValue(A . ($x + 8),$tmp);
                        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 8))->getAlignment()->setWrapText(true);
                        $objectPHPExcel->getActiveSheet()->getStyle( A . ($x + 8))->getFont()->setBold(true);
                        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 8).':'.C . ($x + 8))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $x++;
                    }
                }
//                static $n = 1;
                /*设置表格高度*/
                if($item[$v['item_id']]['item_title'] != 'Form Ref No:' &&  $item[$v['item_id']]['item_title'] != 'Rev.0:'){
                    $objectPHPExcel->getActiveSheet()->mergeCells(A . ($x + 8).':'.B . ($x + 8));
                    if(strlen($item[$v['item_id']]['item_title']) > 70){
                        $objStyleA1 = $objActSheet->getStyle(A . ($x + 8));
                        $objStyleA1->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $objStyleA1 = $objActSheet->getStyle(C . ($x + 8));
                        $objStyleA1->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $objectPHPExcel->getActiveSheet()->getRowDimension($x+8)->setRowHeight(40);
                    }
                    $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 8).':'.B . ($x + 8))->getAlignment()->setWrapText(true);
                    $objectPHPExcel->getActiveSheet()->setCellValue(A . ($x + 8),$item[$v['item_id']]['item_title']);
                    $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 8).':'.B . ($x + 8))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objectPHPExcel->getActiveSheet()->getStyle(C . ($x + 8))->getFont()->setName( 'Times New Roman');
                    $objectPHPExcel->getActiveSheet()->getStyle(C . ($x + 8))->getAlignment()->setWrapText(true);
                    $objectPHPExcel->getActiveSheet()->setCellValue(C . ($x + 8),$v['item_value']);
                    $objectPHPExcel->getActiveSheet()->getStyle(C . ($x + 8))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objectPHPExcel->getActiveSheet()->getStyle(C . ($x + 8))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objectPHPExcel->getActiveSheet()->getStyle(C . ($x + 8))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//                if($v['remarks']){
//                    $objectPHPExcel->getActiveSheet()->setCellValue(C . ($x + 8),$v['remarks']);
//                }
                    $x++;
                }
            }
        }

        //->setName( 'Times New Roman');
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+9),Yii::t('comp_qa','Prepared By'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 9).':'.C . ($x + 9));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+9),$prepare['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 9))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 9).':'.C . ($x + 9))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+10),Yii::t('comp_qa','Signature'));
        $objectPHPExcel->getActiveSheet()->getStyle(A .($x+10))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getRowDimension($x+10)->setRowHeight(50);
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 10).':'.C . ($x + 10));
        if($prepare['signature_path']){
            if(file_exists($prepare['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($prepare['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates(B .($x+10));
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
//        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+19),$prepare['contractor_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 10))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 10).':'.C . ($x + 10))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+11),Yii::t('comp_qa','rep_time'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 11).':'.C . ($x + 11));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+11),$prepare['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 11))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 11).':'.C . ($x + 11))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+13),Yii::t('comp_qa','Checked By'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 13).':'.C . ($x + 13));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+13),$checked['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 13))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 13).':'.C . ($x + 13))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+14),Yii::t('comp_qa','Signature'));
        $objectPHPExcel->getActiveSheet()->getStyle(A .($x+14))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getRowDimension($x+14)->setRowHeight(50);
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 14).':'.C . ($x + 14));
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 14).':'.C . ($x + 14))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        if($checked['signature_path']){
            if(file_exists($checked['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates(B .($x+14));
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
//        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+22),$checked['contractor_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 14))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 14).':'.C . ($x + 14))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+15),Yii::t('comp_qa','Date & Time'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 15).':'.C . ($x + 15));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+15),$checked['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 15))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 15).':'.C . ($x + 15))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+17),Yii::t('comp_qa','Approved By'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 17).':'.C . ($x + 17));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+17),$approved['user_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 17))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 17).':'.C . ($x + 17))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+18),Yii::t('comp_qa','Signature'));
        $objectPHPExcel->getActiveSheet()->getStyle(A .($x+18))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getRowDimension($x+18)->setRowHeight(50);
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 18).':'.C . ($x + 18));
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 18).':'.C . ($x + 18))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        if($approved['signature_path']){
            if(file_exists($approved['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approved['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates(B .($x+18));
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
//        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+25),$approved['contractor_name']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 18))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 18).':'.C . ($x + 18))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue(A .($x+19),Yii::t('comp_qa','Date & Time'));
        $objectPHPExcel->getActiveSheet()->mergeCells(B . ($x + 19).':'.C . ($x + 19));
        $objectPHPExcel->getActiveSheet()->setCellValue(B .($x+19),$approved['record_time']);
        $objectPHPExcel->getActiveSheet()->getStyle(A . ($x + 19))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle(B . ($x + 19).':'.C . ($x + 19))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $objectPHPExcel->getActiveSheet()->mergeCells('A'.($x + 21).':'.'C'.($x + 21));
        $objectPHPExcel->getActiveSheet()->getStyle('A'.($x + 21).':'.'C'.($x + 21))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($x + 21),'Photos & Remarks');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A' . ($x + 21))->getFont()->setBold(true);
        $objStyleA1 = $objActSheet->getStyle('A' . ($x + 21));
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $normal_tag = $x + 22;
        foreach($record_detail as $i => $j){
//            var_dump($j['pic']);
            $record_time = Utils::DateToEn($j['record_time']);
            $apply_user_name = $staff_list[$j['user_id']];
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . $normal_tag,'Date：'.$record_time);
            $objectPHPExcel->getActiveSheet()->getStyle('A' . $normal_tag)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($normal_tag+1),'Name：'.$apply_user_name);
            $objectPHPExcel->getActiveSheet()->getStyle('A' . ($normal_tag+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($normal_tag+2),'Remarks：');
            $objectPHPExcel->getActiveSheet()->getStyle('A' . ($normal_tag+2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->mergeCells('A'.($normal_tag+3).':'.'A'.($normal_tag+6));
            $objectPHPExcel->getActiveSheet()->getStyle('A'.($normal_tag+3).':'.'A'.($normal_tag+6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('A'.($normal_tag+3))->getAlignment()->setWrapText(true);
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($normal_tag+3),$j['remark']);
            $objectPHPExcel->getActiveSheet()->getStyle('A'.($normal_tag+3))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            if($j['pic'] != '-1' && $j['pic'] != ''){
                $pic = explode('|', $j['pic']);
                $num=1;
                foreach ($pic as $key => $content) {
                    if(file_exists($content)) {
                        $a = chr(65+$num);
                        $objectPHPExcel->getActiveSheet()->mergeCells($a.$normal_tag.':'.$a.($normal_tag + 6));
                        $objectPHPExcel->getActiveSheet()->getStyle($a.$normal_tag.':'.$a.($normal_tag + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $num++;
                        /*实例化excel图片处理类*/
                        $objDrawing = new PHPExcel_Worksheet_Drawing();
                        /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
//                        var_dump($content);
//                        exit;
                        $objDrawing->setPath($content);

                        /*设置图片高度*/
                        $objDrawing->setHeight(130);
                        /*设置图片宽度*/
                        $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                        /*设置图片要插入的单元格*/
                        $objDrawing->setCoordinates($a . $normal_tag);
                        /*设置图片所在单元格的格式*/
                        $objDrawing->setOffsetX(30);//30
                        $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                        $objDrawing->getShadow()->setVisible(true);
                        $objDrawing->getShadow()->setDirection(20);//20
                        $objDrawing->setWorksheet($objActSheet);
                    }
                }
            }else{
                $objectPHPExcel->getActiveSheet()->mergeCells('B'.$normal_tag.':'.'C'.($normal_tag + 6));
                $objectPHPExcel->getActiveSheet()->setCellValue('B' . $normal_tag,'N/A');
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$normal_tag.':'.'C'.($normal_tag + 6))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$normal_tag)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$normal_tag)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }
            $normal_tag++;
            $normal_tag = $normal_tag+6;
        }
        $objActSheet->getStyle('C5')->getFont()->setSize(12);
        //        foreach($detail as $key => $val){
//            $user_model = Staff::model()->findByPk($val['user_id']);
//            $contractor_id = $user_model->contractor_id;
//            $con_model = Contractor::model()->findByPk($contractor_id);
//
//            if($val['deal_type'] == '1'){
//
//            }else if ($val['deal_type'] == '2'){
//
//            }else if($val['deal_type'] == '3'){
//
//            }
//        }
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.$data_title.'-'.date("d M Y").'.xls"');
        $objWriter = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007'); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionCs01Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        $tag_1 = 0;
        $tag_2 = 0;
        $tag_3 = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_tag++;
                    if($prepare_tag == 1){
                        $prepare['signature_path'] = $signature_path;
                        $prepare['user_name'] =$user_name;
                        $prepare['contractor_name'] = $contractor_name;
                        $prepare['record_time'] = Utils::DateToEn($v['record_time']);
                        $prepare['remark'] = $v['remark'];
                    }
                }
            }
            if($data_id == $v['data_id']){
                $form_data = json_decode($v['form_data'],true);
                if($form_data[5]['item_value'] != '' && $form_data[6]['item_value'] != '' && $tag_1 != 1){
                    $tag_1 = 1;
                    $checked[1]['signature_path'] =$signature_path;
                    $checked[1]['user_name'] =$user_name;
                    $checked[1]['contractor_name'] = $contractor_name;
                    $checked[1]['record_time'] = Utils::DateToEn($v['record_time']);
                    $checked[1]['remark'] = $v['remark'];
                }
                if($form_data[10]['item_value'] != '' && $form_data[11]['item_value'] != '' && $tag_2 != 1){
                    $tag_2 = 1;
                    $checked[2]['signature_path'] =$signature_path;
                    $checked[2]['user_name'] =$user_name;
                    $checked[2]['contractor_name'] = $contractor_name;
                    $checked[2]['record_time'] = Utils::DateToEn($v['record_time']);
                    $checked[2]['remark'] = $v['remark'];
                }
                if($form_data[14]['item_value'] != '' && $form_data[15]['item_value'] != '' && $tag_3 != 1){
                    $tag_3 = 1;
                    $checked[3]['signature_path'] =$signature_path;
                    $checked[3]['user_name'] =$user_name;
                    $checked[3]['contractor_name'] = $contractor_name;
                    $checked[3]['record_time'] = Utils::DateToEn($v['record_time']);
                    $checked[3]['remark'] = $v['remark'];
                }
            }
        }
//        var_dump($checked);
//        exit;
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/CS-02-1-Checklist for In-Situ Structural Works Cover and Anchorage.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        $objActSheet->setCellValue('C4',$project_name);
        $objActSheet->setCellValue('O4',$form_data[0]['item_value']);
        $objActSheet->setCellValue('C5',$form_data[1]['item_value']);
        $objActSheet->setCellValue('G5',$form_data[2]['item_value']);
        $objActSheet->setCellValue('I6',$form_data[3]['item_value']);

        $objActSheet->setCellValue('H9',$form_data[4]['item_value']);
        $objActSheet->setCellValue('H11',$form_data[5]['item_value']);
        $objActSheet->setCellValue('H13',$form_data[6]['item_value']);
        $objActSheet->setCellValue('H15',$form_data[7]['item_value']);
        $objActSheet->setCellValue('H17',$form_data[8]['item_value']);
        $objActSheet->setCellValue('H19',$form_data[9]['item_value']);
        $objActSheet->setCellValue('H21',$form_data[10]['item_value']);
        $objActSheet->setCellValue('H23',$form_data[11]['item_value']);
        $objActSheet->setCellValue('H25',$form_data[12]['item_value']);
        $objActSheet->setCellValue('H27',$form_data[13]['item_value']);
        $objActSheet->setCellValue('H30',$form_data[14]['item_value']);
        $objActSheet->setCellValue('H33',$form_data[15]['item_value']);
        $objActSheet->setCellValue('H36',$form_data[16]['item_value']);
        $objActSheet->setCellValue('H39',$form_data[17]['item_value']);
        $objActSheet->setCellValue('H42',$form_data[18]['item_value']);

//        $objActSheet->setCellValue('A51',$prepare['user_name']);
//        $objectPHPExcel->getActiveSheet()->getStyle('A51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objActSheet->setCellValue('J10',$checked[1]['remark']);
        if($checked[1]['signature_path']){
            if(file_exists($checked[1]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked[1]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('J14');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('J18','Name &  Date & Time：  '.$checked[1]['user_name'].'  '.substr($checked[1]['record_time'],0,11));
        $objectPHPExcel->getActiveSheet()->getStyle('J18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//        $objActSheet->setCellValue('G51',$checked['user_name']);
//        $objectPHPExcel->getActiveSheet()->getStyle('G51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objActSheet->setCellValue('J20',$checked[2]['remark']);
        if($checked[2]['signature_path']){
            if(file_exists($checked[2]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked[2]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('J23');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('J26','Name &  Date & Time：  '.$checked[2]['user_name'].'   '.substr($checked[2]['record_time'],0,11));
        $objectPHPExcel->getActiveSheet()->getStyle('J26')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//        $objActSheet->setCellValue('L51',$approved['user_name']);
//        $objectPHPExcel->getActiveSheet()->getStyle('L51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objActSheet->setCellValue('J28',$checked[3]['remark']);
        if($checked[3]['signature_path']){
            if(file_exists($checked[3]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked[3]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('J32');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('J37','Name &  Date & Time：  '.$checked[3]['user_name'].'  '.substr($checked[3]['record_time'],0,11));
        $objectPHPExcel->getActiveSheet()->getStyle('J37')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        //导出
        $filename = 'CS-02-1-Checklist for In-Situ Structural Works Cover and Anchorage-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionCs02Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detailRecord($check_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_tag++;
                    if($prepare_tag == 1){
                        $prepare['signature_path'] = $signature_path;
                        $prepare['user_name'] =$user_name;
                        $prepare['contractor_name'] = $contractor_name;
                        $prepare['record_time'] = Utils::DateToEn($v['record_time']);
                        $prepare['remark'] = $v['remark'];
                    }
                }
            }
            if($v['data_id'] == $data_id){
                if($v['deal_type'] == '3'){
                    $checked[$checked_count]['signature_path'] =$signature_path;
                    $checked[$checked_count]['user_name'] =$user_name;
                    $checked[$checked_count]['contractor_name'] = $contractor_name;
                    $checked[$checked_count]['record_time'] = Utils::DateToEn($v['record_time']);
                    $checked[$checked_count]['remark'] = $v['remark'];
                    $checked_count++;
                }
                $approved_tag = 0;
                if($v['deal_type'] == '4'){
                    if($approved_count <= $v['step']){
                        $approved_tag++;
                        if($approved_tag == 1){
                            $approved['signature_path'] =$signature_path;
                            $approved['user_name'] =$user_name;
                            $approved['contractor_name'] = $contractor_name;
                            $approved['record_time'] = Utils::DateToEn($v['record_time']);
                            $approved['remark'] = $v['remark'];
                        }
                    }
                }
            }
        }
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/CS-02.2-Checklist for In-Situ Structural Works Cover and Anchorage.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }
        $objActSheet->setCellValue('C4',$project_name);
        $objActSheet->setCellValue('O4',$form_data[0]['item_value']);
        $objActSheet->setCellValue('C5',$form_data[1]['item_value']);
        $objActSheet->setCellValue('G5',$form_data[2]['item_value']);
        $objActSheet->setCellValue('H6',$form_data[3]['item_value']);

        $objActSheet->setCellValue('H33',$form_data[25]['item_value']);
        $objActSheet->setCellValue('H34',$form_data[26]['item_value']);
        $objActSheet->setCellValue('H35',$form_data[27]['item_value']);

//        $objActSheet->setCellValue('G51',$checked['user_name']);
//        $objectPHPExcel->getActiveSheet()->getStyle('G51')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objActSheet->setCellValue('K20','Name/Date/Time: '.$checked[1]['user_name'].'  '.substr($checked[1]['record_time'],0,11));
        $objActSheet->setCellValue('K11',$checked[1]['remark']);
        if($checked[1]['signature_path']){
            if(file_exists($checked[1]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked[1]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('K15');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        if(!empty($checked[2])){
            $objActSheet->setCellValue('K28','Name/Date/Time: '.$checked[2]['user_name'].'  '.substr($checked[2]['record_time'],0,11));
            $objActSheet->setCellValue('K22',$checked[2]['remark']);
            if($checked[2]['signature_path']){
                if(file_exists($checked[2]['signature_path'])) {
                    /*实例化excel图片处理类*/
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath($checked[2]['signature_path']);
                    /*设置图片高度*/
                    $objDrawing->setHeight(130);
                    /*设置图片宽度*/
                    $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $objDrawing->setCoordinates('K24');
                    /*设置图片所在单元格的格式*/
                    $objDrawing->setOffsetX(30);//30
                    $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                    $objDrawing->getShadow()->setVisible(true);
                    $objDrawing->getShadow()->setDirection(20);//20
                    $objDrawing->setWorksheet($objActSheet);
                }
            }
        }

        if(!empty($checked[3])){
            $objActSheet->setCellValue('K38','Name/Date/Time: '.$checked[3]['user_name'].'  '.substr($checked[0]['record_time'],0,11));
            $objActSheet->setCellValue('K30',$checked[3]['remark']);
            if($checked[3]['signature_path']){
                if(file_exists($checked[3]['signature_path'])) {
                    /*实例化excel图片处理类*/
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath($checked[3]['signature_path']);
                    /*设置图片高度*/
                    $objDrawing->setHeight(130);
                    /*设置图片宽度*/
                    $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $objDrawing->setCoordinates('K34');
                    /*设置图片所在单元格的格式*/
                    $objDrawing->setOffsetX(30);//30
                    $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                    $objDrawing->getShadow()->setVisible(true);
                    $objDrawing->getShadow()->setDirection(20);//20
                    $objDrawing->setWorksheet($objActSheet);
                }
            }
        }

        $objActSheet->setCellValue('A42',$prepare['user_name']);
        if($prepare['signature_path']){
            if(file_exists($prepare['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($prepare['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('D42');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('I44',substr($prepare['record_time'],0,11));
        $objActSheet->setCellValue('N44',substr($prepare['record_time'],12,19));

        $objActSheet->setCellValue('K47',$approved['remark']);
        if($approved['signature_path']){
            if(file_exists($approved['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approved['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A47');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('A51','Name/Date/Time: '.$approved['user_name'].'  '.$approved['record_time']);

        //导出
        $filename = 'CS-02.2-Checklist for In-Situ Structural Works Cover and Anchorage-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionCs04Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        $submit_1 = 0;
        $submit_2 = 0;
        $submit_3 = 0;
        $submit_4 = 0;
        $submit_5 = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            if($data_id = $v['data_id']){
                $form_data = json_decode($v['form_data'],true);
                if($form_data[30]['item_value'] != '' && $submit_1 != 1){
                    $submit_1 =1;
                    $submit[0]['signature_path'] = $signature_path;
                    $submit[0]['record_time'] = $v['record_time'];
                }
                if($form_data[31]['item_value'] != '' && $submit_2 != 1){
                    $submit_2 =1;
                    $submit[1]['signature_path'] = $signature_path;
                    $submit[1]['record_time'] = $v['record_time'];
                }
                if($form_data[32]['item_value'] != '' && $submit_3 != 1){
                    $submit_3 = 1;
                    $submit[2]['signature_path'] = $signature_path;
                    $submit[2]['record_time'] = $v['record_time'];
                }
                if($form_data[33]['item_value'] != '' && $submit_4 != 1){
                    $submit_4 =1;
                    $submit[3]['signature_path'] = $signature_path;
                    $submit[3]['record_time'] = $v['record_time'];
                }
                if($form_data[34]['item_value'] != '' && $submit_5 != 1){
                    $submit_5 = 1;
                    $submit[4]['signature_path'] = $signature_path;
                    $submit[4]['record_time'] = $v['record_time'];
                }
            }
//            if($v['deal_type'] == '2'){
//                $submit_form_data = json_decode($v['form_data'],true);
//                $submit['signature_path'] = $signature_path;
//                $submit['record_time'] = $v['record_time'];
//            }

            if($v['deal_type'] == '3'){
                $inspect_form_data = json_decode($v['form_data'],true);
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/Checklist for Site Concreting report look.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('C4',$project_name);
        $objActSheet->setCellValue('O4',$form_data[0]['item_value']);
        $objActSheet->setCellValue('C5',$form_data[1]['item_value']);
        $objActSheet->setCellValue('G5',$form_data[2]['item_value']);
        $objActSheet->setCellValue('C6',$form_data[3]['item_value']);

        $objActSheet->setCellValue('F7',$form_data[4]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[5]['item_value']);
        $objActSheet->setCellValue('F9',$form_data[6]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[7]['item_value']);
        $objActSheet->setCellValue('F11',$form_data[8]['item_value']);
        $objActSheet->setCellValue('D14',$form_data[9]['item_value']);
        $objActSheet->setCellValue('D16',$form_data[10]['item_value']);
        $objActSheet->setCellValue('D18',$form_data[11]['item_value']);
        $objActSheet->setCellValue('G14',$form_data[12]['item_value']);
        $objActSheet->setCellValue('G16',$form_data[13]['item_value']);
        $objActSheet->setCellValue('G18',$form_data[14]['item_value']);
        $objActSheet->setCellValue('J14',$form_data[15]['item_value']);
        $objActSheet->setCellValue('J16',$form_data[16]['item_value']);
        $objActSheet->setCellValue('J18',$form_data[17]['item_value']);
        $objActSheet->setCellValue('M14',$form_data[18]['item_value']);
        $objActSheet->setCellValue('M16',$form_data[19]['item_value']);
        $objActSheet->setCellValue('M18',$form_data[20]['item_value']);
        $objActSheet->setCellValue('P14',$form_data[21]['item_value']);
        $objActSheet->setCellValue('P16',$form_data[22]['item_value']);
        $objActSheet->setCellValue('P18',$form_data[23]['item_value']);
        $objActSheet->setCellValue('D20',$form_data[24]['item_value']);
        $objActSheet->setCellValue('D22',$form_data[25]['item_value']);
        $objActSheet->setCellValue('D24',$form_data[26]['item_value']);
        $objActSheet->setCellValue('G20',$form_data[27]['item_value']);
        $objActSheet->setCellValue('G22',$form_data[28]['item_value']);
        $objActSheet->setCellValue('G24',$form_data[29]['item_value']);

//        $objActSheet->setCellValue('E35',$submit_form_data[30]['remarks']);
//        $objActSheet->setCellValue('E37',$submit_form_data[31]['remarks']);
//        $objActSheet->setCellValue('E39',$submit_form_data[32]['remarks']);
//        $objActSheet->setCellValue('E41',$submit_form_data[33]['remarks']);
//        $objActSheet->setCellValue('E43',$submit_form_data[34]['remarks']);
//        $objActSheet->setCellValue('I35',$inspect_form_data[30]['remarks']);
//        $objActSheet->setCellValue('I37',$inspect_form_data[31]['remarks']);
//        $objActSheet->setCellValue('I39',$inspect_form_data[32]['remarks']);
//        $objActSheet->setCellValue('I41',$inspect_form_data[33]['remarks']);
//        $objActSheet->setCellValue('I43',$inspect_form_data[34]['remarks']);
        if($submit[0]['signature_path']){
            $objActSheet->setCellValue('E35',substr($submit[0]['record_time'],0,11));
            if(file_exists($submit[0]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit[0]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('G35');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        if($submit[1]['signature_path']){
            $objActSheet->setCellValue('E37',substr($submit[1]['record_time'],0,11));
            if(file_exists($submit[1]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit[1]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('G37');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        if($submit[2]['signature_path']){
            $objActSheet->setCellValue('E39',substr($submit[2]['record_time'],0,11));
            if(file_exists($submit[2]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit[2]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('G39');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        if($submit[3]['signature_path']){
            $objActSheet->setCellValue('E41',substr($submit[3]['record_time'],0,11));
            if(file_exists($submit[3]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit[3]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('G41');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        if($submit[4]['signature_path']){
            $objActSheet->setCellValue('E43',substr($submit[4]['record_time'],0,11));
            if(file_exists($submit[4]['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit[4]['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('G43');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }

        for($inspect_index=35;$inspect_index<=43;$inspect_index=$inspect_index+2){
            $objActSheet->setCellValue('I'.$inspect_index,substr($inspect['record_time'],0,11));
            if($inspect['signature_path']){
                if(file_exists($inspect['signature_path'])) {
                    /*实例化excel图片处理类*/
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath($inspect['signature_path']);
                    /*设置图片高度*/
                    $objDrawing->setHeight(130);
                    /*设置图片宽度*/
                    $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $objDrawing->setCoordinates('K'.$inspect_index);
                    /*设置图片所在单元格的格式*/
                    $objDrawing->setOffsetX(30);//30
                    $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                    $objDrawing->getShadow()->setVisible(true);
                    $objDrawing->getShadow()->setDirection(20);//20
                    $objDrawing->setWorksheet($objActSheet);
                }
            }
        }

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('M35');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(9);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(23);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('M40',$approve['user_name']);
        $objActSheet->setCellValue('M43',substr($approve['record_time'],0,11));
        //导出
        $filename = 'Checklist for Site Concreting report look-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionCs06Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detailRecord($check_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_tag++;
                    if($prepare_tag == 1){
                        $prepare['signature_path'] = $signature_path;
                        $prepare['user_name'] =$user_name;
                        $prepare['contractor_name'] = $contractor_name;
                        $prepare['record_time'] = Utils::DateToEn($v['record_time']);
                        $prepare['remark'] = $v['remark'];
                    }
                }
            }
            $checked_tag = 0;
            if($v['data_id'] == $data_id){
                if($v['deal_type'] == '3'){
                    if($checked_count <= $v['step']){
                        $checked_tag++;
                        if($checked_tag == 1){
                            $checked['signature_path'] =$signature_path;
                            $checked['user_name'] =$user_name;
                            $checked['contractor_name'] = $contractor_name;
                            $checked['record_time'] = Utils::DateToEn($v['record_time']);
                            $checked['remark'] = $v['remark'];
                        }
                    }
                }
                $approved_tag = 0;
                if($v['deal_type'] == '4'){
                    if($approved_count <= $v['step']){
                        $approved_tag++;
                        if($approved_tag == 1){
                            $approved['signature_path'] =$signature_path;
                            $approved['user_name'] =$user_name;
                            $approved['contractor_name'] = $contractor_name;
                            $approved['record_time'] = Utils::DateToEn($v['record_time']);
                            $approved['remark'] = $v['remark'];
                        }
                    }
                }
            }
        }
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/CS-06-Post-Concreting Checklist.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }
        $objActSheet->setCellValue('C4',$project_name);
        $objActSheet->setCellValue('D6',$form_data[0]['item_value']);
        $objActSheet->setCellValue('M6',$form_data[1]['item_value']);
        $objActSheet->setCellValue('C5',$form_data[2]['item_value']);
        $objActSheet->setCellValue('K5',$form_data[3]['item_value']);
        $objActSheet->setCellValue('B7',$form_data[4]['item_value']);

        $objActSheet->setCellValue('B30',$form_data[12]['item_value']);
        $objActSheet->setCellValue('B32',$form_data[16]['item_value']);
        $objActSheet->setCellValue('B34',$form_data[20]['item_value']);
        $objActSheet->setCellValue('B36',$form_data[24]['item_value']);
        $objActSheet->setCellValue('B38',$form_data[28]['item_value']);
        $objActSheet->setCellValue('B40',$form_data[32]['item_value']);
        $objActSheet->setCellValue('B42',$form_data[36]['item_value']);
        $objActSheet->setCellValue('F30',$form_data[13]['item_value']);
        $objActSheet->setCellValue('F32',$form_data[17]['item_value']);
        $objActSheet->setCellValue('F34',$form_data[21]['item_value']);
        $objActSheet->setCellValue('F36',$form_data[25]['item_value']);
        $objActSheet->setCellValue('F38',$form_data[29]['item_value']);
        $objActSheet->setCellValue('F40',$form_data[33]['item_value']);
        $objActSheet->setCellValue('F42',$form_data[37]['item_value']);
        $objActSheet->setCellValue('J30',$form_data[14]['item_value']);
        $objActSheet->setCellValue('J32',$form_data[18]['item_value']);
        $objActSheet->setCellValue('J34',$form_data[22]['item_value']);
        $objActSheet->setCellValue('J36',$form_data[26]['item_value']);
        $objActSheet->setCellValue('J38',$form_data[30]['item_value']);
        $objActSheet->setCellValue('J40',$form_data[34]['item_value']);
        $objActSheet->setCellValue('J42',$form_data[38]['item_value']);
        $objActSheet->setCellValue('P30',$form_data[15]['item_value']);
        $objActSheet->setCellValue('P32',$form_data[19]['item_value']);
        $objActSheet->setCellValue('P34',$form_data[23]['item_value']);
        $objActSheet->setCellValue('P36',$form_data[27]['item_value']);
        $objActSheet->setCellValue('P38',$form_data[31]['item_value']);
        $objActSheet->setCellValue('P40',$form_data[35]['item_value']);
        $objActSheet->setCellValue('P42',$form_data[39]['item_value']);


        if($prepare['signature_path']){
            if(file_exists($prepare['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($prepare['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A51');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(6);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('A54','Name & Signature of     '.$prepare['user_name']);
        $objActSheet->setCellValue('A55','Builder\'s Site Rep/ Date  '.substr($prepare['record_time'],0,11));

        if($checked['signature_path']){
            if(file_exists($checked['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($checked['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('G51');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(6);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G54','Name & Signature of     '.$checked['user_name']);
        $objActSheet->setCellValue('G55','Builder\'s Site Rep/ Date  '.substr($checked['record_time'],0,11));

        if($approved['signature_path']){
            if(file_exists($approved['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approved['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('M51');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(6);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('M54','Name & Signature of     '.$approved['user_name']);
        $objActSheet->setCellValue('M55','Resident Engineer/ Date    '.substr($approved['record_time'],0,11));

        //导出
        $filename = 'CS-06-Post-Concreting Checklist'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs01Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/P01 checklist for Final PPVC prior for installation.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[6]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[10]['item_value']);
        $objActSheet->setCellValue('A21',$form_data[11]['item_value']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A25');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B25', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C25');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E25', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F25');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G25', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'P01 checklist for Final PPVC prior for installation-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs02Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/P02-checklist for PPVC Installation on site.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[6]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C29',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C31',$form_data[22]['item_value']);
        $objActSheet->setCellValue('A33',$form_data[23]['item_value']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);
        $objActSheet->setCellValue('E18',$inspect['record_time']);
        $objActSheet->setCellValue('E20',$inspect['record_time']);
        $objActSheet->setCellValue('E21',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E26',$inspect['record_time']);
        $objActSheet->setCellValue('E27',$inspect['record_time']);
        $objActSheet->setCellValue('E28',$inspect['record_time']);
        $objActSheet->setCellValue('E29',$inspect['record_time']);
        $objActSheet->setCellValue('E30',$inspect['record_time']);
        $objActSheet->setCellValue('E31',$inspect['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);
        $objActSheet->setCellValue('G18',$approve['record_time']);
        $objActSheet->setCellValue('G20',$approve['record_time']);
        $objActSheet->setCellValue('G21',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G26',$approve['record_time']);
        $objActSheet->setCellValue('G27',$approve['record_time']);
        $objActSheet->setCellValue('G28',$approve['record_time']);
        $objActSheet->setCellValue('G29',$approve['record_time']);
        $objActSheet->setCellValue('G30',$approve['record_time']);
        $objActSheet->setCellValue('G31',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A37');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B37', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C37');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E37', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F37');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G37', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'P02-checklist for PPVC Installation on site-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs03Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/S01-checklist for excavation, lean concrete & backfilling.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A8','Inspection Items: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('E8','Drawing Ref: '.$form_data[4]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[6]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[19]['item_value']);
        $objActSheet->setCellValue('A31',$form_data[20]['item_value']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E18',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E25',$inspect['record_time']);
        $objActSheet->setCellValue('E26',$inspect['record_time']);
        $objActSheet->setCellValue('E28',$inspect['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G18',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G25',$approve['record_time']);
        $objActSheet->setCellValue('G26',$approve['record_time']);
        $objActSheet->setCellValue('G28',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A35');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B35', $submit['user_name'].' '.substr($submit['record_time'],0,11));

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C35');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E35', $inspect['user_name'].' '.substr($inspect['record_time'],0,11));

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F35');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G35', $approve['user_name'].' '.substr($approve['record_time'],0,11));
        //导出
        $filename = 'S01-checklist for excavation, lean concrete & backfilling-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs04Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/S02-checklist for structural works( precast concrete instllation).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[6]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C29',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C32',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C33',$form_data[22]['item_value']);
        $objActSheet->setCellValue('C37',$form_data[23]['item_value']);
        $objActSheet->setCellValue('A40',$form_data[23]['item_value']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E18',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E25',$inspect['record_time']);
        $objActSheet->setCellValue('E26',$inspect['record_time']);
        $objActSheet->setCellValue('E27',$inspect['record_time']);
        $objActSheet->setCellValue('E28',$inspect['record_time']);
        $objActSheet->setCellValue('E29',$inspect['record_time']);
        $objActSheet->setCellValue('E32',$inspect['record_time']);
        $objActSheet->setCellValue('E33',$inspect['record_time']);
        $objActSheet->setCellValue('E37',$inspect['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G18',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G25',$approve['record_time']);
        $objActSheet->setCellValue('G26',$approve['record_time']);
        $objActSheet->setCellValue('G27',$approve['record_time']);
        $objActSheet->setCellValue('G28',$approve['record_time']);
        $objActSheet->setCellValue('G29',$approve['record_time']);
        $objActSheet->setCellValue('G32',$approve['record_time']);
        $objActSheet->setCellValue('G33',$approve['record_time']);
        $objActSheet->setCellValue('G37',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A44');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B44', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C44');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E44', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F44');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G44', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'S02-checklist for structural works( precast concrete instllation)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs05Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/S03-checklist for structural works (prepour).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1','Form No: '.$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2','Ref No: '.$form_data[1]['item_value']);
        $objActSheet->setCellValue('A5','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A6','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F6',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A7','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('A8','Anticipated Inspection Date/Time: '.$form_data[6]['item_value']);

        $objActSheet->setCellValue('C12',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C13',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C14',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[22]['item_value']);
        $objActSheet->setCellValue('C31',$form_data[23]['item_value']);
        $objActSheet->setCellValue('C32',$form_data[24]['item_value']);
        $objActSheet->setCellValue('A34',$form_data[25]['item_value']);

        $objActSheet->setCellValue('E12',$inspect['record_time']);
        $objActSheet->setCellValue('E13',$inspect['record_time']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);
        $objActSheet->setCellValue('E18',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E20',$inspect['record_time']);
        $objActSheet->setCellValue('E21',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E26',$inspect['record_time']);
        $objActSheet->setCellValue('E27',$inspect['record_time']);
        $objActSheet->setCellValue('E28',$inspect['record_time']);
        $objActSheet->setCellValue('E30',$inspect['record_time']);
        $objActSheet->setCellValue('E31',$inspect['record_time']);
        $objActSheet->setCellValue('E32',$inspect['record_time']);

        $objActSheet->setCellValue('G12',$approve['record_time']);
        $objActSheet->setCellValue('G13',$approve['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);
        $objActSheet->setCellValue('G18',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G20',$approve['record_time']);
        $objActSheet->setCellValue('G21',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G26',$approve['record_time']);
        $objActSheet->setCellValue('G27',$approve['record_time']);
        $objActSheet->setCellValue('G28',$approve['record_time']);
        $objActSheet->setCellValue('G30',$approve['record_time']);
        $objActSheet->setCellValue('G31',$approve['record_time']);
        $objActSheet->setCellValue('G32',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A39');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('A38', $submit['user_name'].' '.substr($submit['record_time'],0,11));

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C39');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('C38', $inspect['user_name'].' '.substr($inspect['record_time'],0,11));

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F39');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('F38', $approve['user_name'].' '.substr($approve['record_time'],0,11));
        //导出
        $filename = 'S03-checklist for structural works (prepour)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs06Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/S04-checklist for structural work( post pour).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1','Form No: '.$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2','Ref No: '.$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A7','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F7',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A8','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('A9','Anticipated Inspection Date/Time: '.$form_data[6]['item_value']);

        $objActSheet->setCellValue('C13',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C14',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[17]['item_value']);
        $objActSheet->setCellValue('A31',$form_data[18]['item_value']);

        $objActSheet->setCellValue('E13',$inspect['record_time']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E20',$inspect['record_time']);
        $objActSheet->setCellValue('E21',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E25',$inspect['record_time']);

        $objActSheet->setCellValue('G13',$approve['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G20',$approve['record_time']);
        $objActSheet->setCellValue('G21',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G25',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A36');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('A35', $submit['user_name'].' '.substr($submit['record_time'],0,11));

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C36');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('C35', $inspect['user_name'].' '.substr($inspect['record_time'],0,11));

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F36');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('F35', $approve['user_name'].' '.substr($approve['record_time'],0,11));
        //导出
        $filename = 'S04-checklist for structural work( post pour)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs08Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/S06-checklist for structural work (PBU- fabrication).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[6]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C29',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C31',$form_data[22]['item_value']);
        $objActSheet->setCellValue('C33',$form_data[23]['item_value']);
        $objActSheet->setCellValue('C35',$form_data[24]['item_value']);
        $objActSheet->setCellValue('C36',$form_data[25]['item_value']);
        $objActSheet->setCellValue('C37',$form_data[26]['item_value']);
        $objActSheet->setCellValue('C39',$form_data[27]['item_value']);
        $objActSheet->setCellValue('C40',$form_data[28]['item_value']);
        $objActSheet->setCellValue('C41',$form_data[29]['item_value']);
        $objActSheet->setCellValue('C42',$form_data[30]['item_value']);
        $objActSheet->setCellValue('C43',$form_data[31]['item_value']);
        $objActSheet->setCellValue('C44',$form_data[32]['item_value']);
        $objActSheet->setCellValue('C45',$form_data[33]['item_value']);
        $objActSheet->setCellValue('C46',$form_data[34]['item_value']);
        $objActSheet->setCellValue('C47',$form_data[35]['item_value']);
        $objActSheet->setCellValue('C48',$form_data[36]['item_value']);
        $objActSheet->setCellValue('A50',$form_data[37]['item_value']);

        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);
        $objActSheet->setCellValue('E18',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E21',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E25',$inspect['record_time']);
        $objActSheet->setCellValue('E26',$inspect['record_time']);
        $objActSheet->setCellValue('E27',$inspect['record_time']);
        $objActSheet->setCellValue('E29',$inspect['record_time']);
        $objActSheet->setCellValue('E30',$inspect['record_time']);
        $objActSheet->setCellValue('E31',$inspect['record_time']);
        $objActSheet->setCellValue('E33',$inspect['record_time']);
        $objActSheet->setCellValue('E35',$inspect['record_time']);
        $objActSheet->setCellValue('E36',$inspect['record_time']);
        $objActSheet->setCellValue('E37',$inspect['record_time']);
        $objActSheet->setCellValue('E39',$inspect['record_time']);
        $objActSheet->setCellValue('E40',$inspect['record_time']);
        $objActSheet->setCellValue('E41',$inspect['record_time']);
        $objActSheet->setCellValue('E42',$inspect['record_time']);
        $objActSheet->setCellValue('E43',$inspect['record_time']);
        $objActSheet->setCellValue('E44',$inspect['record_time']);
        $objActSheet->setCellValue('E45',$inspect['record_time']);
        $objActSheet->setCellValue('E46',$inspect['record_time']);
        $objActSheet->setCellValue('E47',$inspect['record_time']);
        $objActSheet->setCellValue('E48',$inspect['record_time']);

        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);
        $objActSheet->setCellValue('G18',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G21',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G25',$approve['record_time']);
        $objActSheet->setCellValue('G26',$approve['record_time']);
        $objActSheet->setCellValue('G27',$approve['record_time']);
        $objActSheet->setCellValue('G29',$approve['record_time']);
        $objActSheet->setCellValue('G30',$approve['record_time']);
        $objActSheet->setCellValue('G31',$approve['record_time']);
        $objActSheet->setCellValue('G33',$approve['record_time']);
        $objActSheet->setCellValue('G35',$approve['record_time']);
        $objActSheet->setCellValue('G36',$approve['record_time']);
        $objActSheet->setCellValue('G37',$approve['record_time']);
        $objActSheet->setCellValue('G39',$approve['record_time']);
        $objActSheet->setCellValue('G40',$approve['record_time']);
        $objActSheet->setCellValue('G41',$approve['record_time']);
        $objActSheet->setCellValue('G42',$approve['record_time']);
        $objActSheet->setCellValue('G43',$approve['record_time']);
        $objActSheet->setCellValue('G44',$approve['record_time']);
        $objActSheet->setCellValue('G45',$approve['record_time']);
        $objActSheet->setCellValue('G46',$approve['record_time']);
        $objActSheet->setCellValue('G47',$approve['record_time']);
        $objActSheet->setCellValue('G48',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A54');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B54', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C54');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E54', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F54');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G54', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'S06-checklist for structural work (PBU- fabrication)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs09Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/S07-checklist for structural ponding test.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[6]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[12]['item_value']);
        $objActSheet->setCellValue('A23',$form_data[13]['item_value']);

        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);
        $objActSheet->setCellValue('E18',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);

        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);
        $objActSheet->setCellValue('G18',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A27');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B27', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C27');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E27', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F27');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G27', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'S07-checklist for structural ponding test-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPECs10Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/S08-checklist for Final PBU.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[3]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[4]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[5]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[6]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[7]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[16]['item_value']);
        $objActSheet->setCellValue('A25',$form_data[17]['item_value']);

        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);
        $objActSheet->setCellValue('E18',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E20',$inspect['record_time']);
        $objActSheet->setCellValue('E21',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);

        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);
        $objActSheet->setCellValue('G18',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G20',$approve['record_time']);
        $objActSheet->setCellValue('G21',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A29');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B29', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C29');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E29', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F29');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G29', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'S08-checklist for Final PBU-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPEMe01Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/ME01-sanitary (basement & Common area).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1','Form No: '.$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2','Ref No: '.$form_data[1]['item_value']);
        $objActSheet->setCellValue('A4','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('F4',$form_data[3]['item_value']);
        $objActSheet->setCellValue('A5','Structural Element: '.$form_data[4]['item_value']);
        $objActSheet->setCellValue('F5',$form_data[5]['item_value']);
        $objActSheet->setCellValue('A6','Date/Time of Notification: '.$form_data[6]['item_value']);
        $objActSheet->setCellValue('F6',$form_data[7]['item_value']);

        $objActSheet->setCellValue('C11',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C12',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C13',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C14',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[22]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[23]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[24]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[25]['item_value']);
        $objActSheet->setCellValue('C31',$form_data[26]['item_value']);
        $objActSheet->setCellValue('C32',$form_data[27]['item_value']);
        $objActSheet->setCellValue('C33',$form_data[28]['item_value']);
        $objActSheet->setCellValue('C34',$form_data[29]['item_value']);
        $objActSheet->setCellValue('C35',$form_data[30]['item_value']);
        $objActSheet->setCellValue('C36',$form_data[31]['item_value']);
        $objActSheet->setCellValue('C37',$form_data[32]['item_value']);
        $objActSheet->setCellValue('C38',$form_data[33]['item_value']);
        $objActSheet->setCellValue('A40',$form_data[34]['item_value']);

        $objActSheet->setCellValue('E11',$inspect['record_time']);
        $objActSheet->setCellValue('E12',$inspect['record_time']);
        $objActSheet->setCellValue('E13',$inspect['record_time']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);
        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E20',$inspect['record_time']);
        $objActSheet->setCellValue('E21',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E25',$inspect['record_time']);
        $objActSheet->setCellValue('E26',$inspect['record_time']);
        $objActSheet->setCellValue('E27',$inspect['record_time']);
        $objActSheet->setCellValue('E28',$inspect['record_time']);
        $objActSheet->setCellValue('E30',$inspect['record_time']);
        $objActSheet->setCellValue('E31',$inspect['record_time']);
        $objActSheet->setCellValue('E32',$inspect['record_time']);
        $objActSheet->setCellValue('E33',$inspect['record_time']);
        $objActSheet->setCellValue('E34',$inspect['record_time']);
        $objActSheet->setCellValue('E35',$inspect['record_time']);
        $objActSheet->setCellValue('E36',$inspect['record_time']);
        $objActSheet->setCellValue('E37',$inspect['record_time']);
        $objActSheet->setCellValue('E38',$inspect['record_time']);

        $objActSheet->setCellValue('G11',$approve['record_time']);
        $objActSheet->setCellValue('G12',$approve['record_time']);
        $objActSheet->setCellValue('G13',$approve['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);
        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G20',$approve['record_time']);
        $objActSheet->setCellValue('G21',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G25',$approve['record_time']);
        $objActSheet->setCellValue('G26',$approve['record_time']);
        $objActSheet->setCellValue('G27',$approve['record_time']);
        $objActSheet->setCellValue('G28',$approve['record_time']);
        $objActSheet->setCellValue('G30',$approve['record_time']);
        $objActSheet->setCellValue('G31',$approve['record_time']);
        $objActSheet->setCellValue('G32',$approve['record_time']);
        $objActSheet->setCellValue('G33',$approve['record_time']);
        $objActSheet->setCellValue('G34',$approve['record_time']);
        $objActSheet->setCellValue('G35',$approve['record_time']);
        $objActSheet->setCellValue('G36',$approve['record_time']);
        $objActSheet->setCellValue('G37',$approve['record_time']);
        $objActSheet->setCellValue('G38',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A45');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('A44', $submit['user_name'].' '.substr($submit['record_time'],0,11));

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C45');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('C44', $inspect['user_name'].' '.substr($inspect['record_time'],0,11));

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F45');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('F44', $approve['user_name'].' '.substr($approve['record_time'],0,11));
        //导出
        $filename = 'ME01-sanitary (basement & Common area)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPEMe02Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/ME02-plumbing instlln (basement & common area).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1','Form No: '.$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2','Ref No: '.$form_data[1]['item_value']);
        $objActSheet->setCellValue('A5','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('E5',$form_data[3]['item_value']);
        $objActSheet->setCellValue('A6','Structural Element: '.$form_data[4]['item_value']);
        $objActSheet->setCellValue('E6',$form_data[5]['item_value']);
        $objActSheet->setCellValue('A7','Date/Time of Notification: '.$form_data[6]['item_value']);
        $objActSheet->setCellValue('A8','Date/Time Inspection Required: '.$form_data[7]['item_value']);

        $objActSheet->setCellValue('C12',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C13',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C14',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[13]['item_value']);

        $objActSheet->setCellValue('C19',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[22]['item_value']);

        $objActSheet->setCellValue('C29',$form_data[23]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[24]['item_value']);
        $objActSheet->setCellValue('C31',$form_data[25]['item_value']);
        $objActSheet->setCellValue('C32',$form_data[26]['item_value']);
        $objActSheet->setCellValue('C33',$form_data[27]['item_value']);
        $objActSheet->setCellValue('C34',$form_data[28]['item_value']);
        $objActSheet->setCellValue('C35',$form_data[29]['item_value']);

        $objActSheet->setCellValue('A37',$form_data[30]['item_value']);

        $objActSheet->setCellValue('E12',$inspect['record_time']);
        $objActSheet->setCellValue('E13',$inspect['record_time']);
        $objActSheet->setCellValue('E14',$inspect['record_time']);
        $objActSheet->setCellValue('E15',$inspect['record_time']);
        $objActSheet->setCellValue('E16',$inspect['record_time']);
        $objActSheet->setCellValue('E17',$inspect['record_time']);

        $objActSheet->setCellValue('E19',$inspect['record_time']);
        $objActSheet->setCellValue('E20',$inspect['record_time']);
        $objActSheet->setCellValue('E21',$inspect['record_time']);
        $objActSheet->setCellValue('E22',$inspect['record_time']);
        $objActSheet->setCellValue('E23',$inspect['record_time']);
        $objActSheet->setCellValue('E24',$inspect['record_time']);
        $objActSheet->setCellValue('E25',$inspect['record_time']);
        $objActSheet->setCellValue('E26',$inspect['record_time']);
        $objActSheet->setCellValue('E27',$inspect['record_time']);

        $objActSheet->setCellValue('E29',$inspect['record_time']);
        $objActSheet->setCellValue('E30',$inspect['record_time']);
        $objActSheet->setCellValue('E31',$inspect['record_time']);
        $objActSheet->setCellValue('E32',$inspect['record_time']);
        $objActSheet->setCellValue('E33',$inspect['record_time']);
        $objActSheet->setCellValue('E34',$inspect['record_time']);
        $objActSheet->setCellValue('E35',$inspect['record_time']);

        $objActSheet->setCellValue('G12',$approve['record_time']);
        $objActSheet->setCellValue('G13',$approve['record_time']);
        $objActSheet->setCellValue('G14',$approve['record_time']);
        $objActSheet->setCellValue('G15',$approve['record_time']);
        $objActSheet->setCellValue('G16',$approve['record_time']);
        $objActSheet->setCellValue('G17',$approve['record_time']);

        $objActSheet->setCellValue('G19',$approve['record_time']);
        $objActSheet->setCellValue('G20',$approve['record_time']);
        $objActSheet->setCellValue('G21',$approve['record_time']);
        $objActSheet->setCellValue('G22',$approve['record_time']);
        $objActSheet->setCellValue('G23',$approve['record_time']);
        $objActSheet->setCellValue('G24',$approve['record_time']);
        $objActSheet->setCellValue('G25',$approve['record_time']);
        $objActSheet->setCellValue('G26',$approve['record_time']);
        $objActSheet->setCellValue('G27',$approve['record_time']);

        $objActSheet->setCellValue('G29',$approve['record_time']);
        $objActSheet->setCellValue('G30',$approve['record_time']);
        $objActSheet->setCellValue('G31',$approve['record_time']);
        $objActSheet->setCellValue('G32',$approve['record_time']);
        $objActSheet->setCellValue('G33',$approve['record_time']);
        $objActSheet->setCellValue('G34',$approve['record_time']);
        $objActSheet->setCellValue('G35',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A42');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('A41', $submit['user_name'].' '.substr($submit['record_time'],0,11));

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C42');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('C41', $inspect['user_name'].' '.substr($inspect['record_time'],0,11));

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F42');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('F41', $approve['user_name'].' '.substr($approve['record_time'],0,11));
        //导出
        $filename = 'ME02-plumbing instlln (basement & common area)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPEMe03Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/ME03-Gas instlln (basement & common area).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('F6',$form_data[3]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[4]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[5]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[6]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[7]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[22]['item_value']);
        $objActSheet->setCellValue('C29',$form_data[23]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[24]['item_value']);
        $objActSheet->setCellValue('A32',$form_data[25]['item_value']);

        $objActSheet->setCellValue('C14',$inspect['record_time']);
        $objActSheet->setCellValue('C15',$inspect['record_time']);
        $objActSheet->setCellValue('C16',$inspect['record_time']);
        $objActSheet->setCellValue('C17',$inspect['record_time']);
        $objActSheet->setCellValue('C18',$inspect['record_time']);
        $objActSheet->setCellValue('C19',$inspect['record_time']);
        $objActSheet->setCellValue('C20',$inspect['record_time']);
        $objActSheet->setCellValue('C21',$inspect['record_time']);
        $objActSheet->setCellValue('C22',$inspect['record_time']);
        $objActSheet->setCellValue('C23',$inspect['record_time']);
        $objActSheet->setCellValue('C24',$inspect['record_time']);
        $objActSheet->setCellValue('C25',$inspect['record_time']);
        $objActSheet->setCellValue('C26',$inspect['record_time']);
        $objActSheet->setCellValue('C27',$inspect['record_time']);
        $objActSheet->setCellValue('C28',$inspect['record_time']);
        $objActSheet->setCellValue('C29',$inspect['record_time']);
        $objActSheet->setCellValue('C30',$inspect['record_time']);

        $objActSheet->setCellValue('C14',$approve['record_time']);
        $objActSheet->setCellValue('C15',$approve['record_time']);
        $objActSheet->setCellValue('C16',$approve['record_time']);
        $objActSheet->setCellValue('C17',$approve['record_time']);
        $objActSheet->setCellValue('C18',$approve['record_time']);
        $objActSheet->setCellValue('C19',$approve['record_time']);
        $objActSheet->setCellValue('C20',$approve['record_time']);
        $objActSheet->setCellValue('C21',$approve['record_time']);
        $objActSheet->setCellValue('C22',$approve['record_time']);
        $objActSheet->setCellValue('C23',$approve['record_time']);
        $objActSheet->setCellValue('C24',$approve['record_time']);
        $objActSheet->setCellValue('C25',$approve['record_time']);
        $objActSheet->setCellValue('C26',$approve['record_time']);
        $objActSheet->setCellValue('C27',$approve['record_time']);
        $objActSheet->setCellValue('C28',$approve['record_time']);
        $objActSheet->setCellValue('C29',$approve['record_time']);
        $objActSheet->setCellValue('C30',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A36');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B36', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C36');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E36', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F36');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G36', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'ME03-Gas instlln (basement & common area)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPEMe04Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/ME04-electrical instlln (basement & common area).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('F6',$form_data[3]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[4]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[5]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[6]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[7]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[17]['item_value']);

        $objActSheet->setCellValue('C25',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C29',$form_data[22]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[23]['item_value']);
        $objActSheet->setCellValue('C31',$form_data[24]['item_value']);

        $objActSheet->setCellValue('C33',$form_data[25]['item_value']);
        $objActSheet->setCellValue('C34',$form_data[26]['item_value']);
        $objActSheet->setCellValue('C35',$form_data[27]['item_value']);
        $objActSheet->setCellValue('C36',$form_data[28]['item_value']);
        $objActSheet->setCellValue('C37',$form_data[29]['item_value']);
        $objActSheet->setCellValue('C38',$form_data[30]['item_value']);
        $objActSheet->setCellValue('C39',$form_data[31]['item_value']);

        $objActSheet->setCellValue('C41',$form_data[32]['item_value']);
        $objActSheet->setCellValue('C42',$form_data[33]['item_value']);
        $objActSheet->setCellValue('C43',$form_data[34]['item_value']);

        $objActSheet->setCellValue('A45',$form_data[35]['item_value']);

        $objActSheet->setCellValue('C14',$inspect['record_time']);
        $objActSheet->setCellValue('C15',$inspect['record_time']);
        $objActSheet->setCellValue('C16',$inspect['record_time']);
        $objActSheet->setCellValue('C17',$inspect['record_time']);
        $objActSheet->setCellValue('C18',$inspect['record_time']);
        $objActSheet->setCellValue('C19',$inspect['record_time']);
        $objActSheet->setCellValue('C20',$inspect['record_time']);
        $objActSheet->setCellValue('C21',$inspect['record_time']);
        $objActSheet->setCellValue('C22',$inspect['record_time']);
        $objActSheet->setCellValue('C23',$inspect['record_time']);

        $objActSheet->setCellValue('C25',$inspect['record_time']);
        $objActSheet->setCellValue('C26',$inspect['record_time']);
        $objActSheet->setCellValue('C27',$inspect['record_time']);
        $objActSheet->setCellValue('C28',$inspect['record_time']);
        $objActSheet->setCellValue('C29',$inspect['record_time']);
        $objActSheet->setCellValue('C30',$inspect['record_time']);
        $objActSheet->setCellValue('C31',$inspect['record_time']);

        $objActSheet->setCellValue('C33',$inspect['record_time']);
        $objActSheet->setCellValue('C34',$inspect['record_time']);
        $objActSheet->setCellValue('C35',$inspect['record_time']);
        $objActSheet->setCellValue('C36',$inspect['record_time']);
        $objActSheet->setCellValue('C37',$inspect['record_time']);
        $objActSheet->setCellValue('C38',$inspect['record_time']);
        $objActSheet->setCellValue('C39',$inspect['record_time']);

        $objActSheet->setCellValue('C41',$inspect['record_time']);
        $objActSheet->setCellValue('C42',$inspect['record_time']);
        $objActSheet->setCellValue('C43',$inspect['record_time']);

        $objActSheet->setCellValue('C14',$approve['record_time']);
        $objActSheet->setCellValue('C15',$approve['record_time']);
        $objActSheet->setCellValue('C16',$approve['record_time']);
        $objActSheet->setCellValue('C17',$approve['record_time']);
        $objActSheet->setCellValue('C18',$approve['record_time']);
        $objActSheet->setCellValue('C19',$approve['record_time']);
        $objActSheet->setCellValue('C20',$approve['record_time']);
        $objActSheet->setCellValue('C21',$approve['record_time']);
        $objActSheet->setCellValue('C22',$approve['record_time']);
        $objActSheet->setCellValue('C23',$approve['record_time']);

        $objActSheet->setCellValue('C25',$approve['record_time']);
        $objActSheet->setCellValue('C26',$approve['record_time']);
        $objActSheet->setCellValue('C27',$approve['record_time']);
        $objActSheet->setCellValue('C28',$approve['record_time']);
        $objActSheet->setCellValue('C29',$approve['record_time']);
        $objActSheet->setCellValue('C30',$approve['record_time']);
        $objActSheet->setCellValue('C31',$approve['record_time']);

        $objActSheet->setCellValue('C33',$approve['record_time']);
        $objActSheet->setCellValue('C34',$approve['record_time']);
        $objActSheet->setCellValue('C35',$approve['record_time']);
        $objActSheet->setCellValue('C36',$approve['record_time']);
        $objActSheet->setCellValue('C37',$approve['record_time']);
        $objActSheet->setCellValue('C38',$approve['record_time']);
        $objActSheet->setCellValue('C39',$approve['record_time']);

        $objActSheet->setCellValue('C41',$approve['record_time']);
        $objActSheet->setCellValue('C42',$approve['record_time']);
        $objActSheet->setCellValue('C43',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A49');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B49', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C49');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E49', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F49');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G49', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'ME04-electrical instlln (basement & common area)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPEMe05Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/ME05-acmv instlln (basement & common area).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('F6',$form_data[3]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[4]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[5]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[6]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[7]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[11]['item_value']);

        $objActSheet->setCellValue('C19',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[20]['item_value']);

        $objActSheet->setCellValue('C30',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C31',$form_data[22]['item_value']);

        $objActSheet->setCellValue('A45',$form_data[23]['item_value']);

        $objActSheet->setCellValue('C14',$inspect['record_time']);
        $objActSheet->setCellValue('C15',$inspect['record_time']);
        $objActSheet->setCellValue('C16',$inspect['record_time']);
        $objActSheet->setCellValue('C17',$inspect['record_time']);

        $objActSheet->setCellValue('C19',$inspect['record_time']);
        $objActSheet->setCellValue('C20',$inspect['record_time']);
        $objActSheet->setCellValue('C21',$inspect['record_time']);
        $objActSheet->setCellValue('C22',$inspect['record_time']);
        $objActSheet->setCellValue('C23',$inspect['record_time']);
        $objActSheet->setCellValue('C24',$inspect['record_time']);
        $objActSheet->setCellValue('C25',$inspect['record_time']);
        $objActSheet->setCellValue('C26',$inspect['record_time']);
        $objActSheet->setCellValue('C27',$inspect['record_time']);

        $objActSheet->setCellValue('C30',$inspect['record_time']);
        $objActSheet->setCellValue('C31',$inspect['record_time']);

        $objActSheet->setCellValue('C14',$approve['record_time']);
        $objActSheet->setCellValue('C15',$approve['record_time']);
        $objActSheet->setCellValue('C16',$approve['record_time']);
        $objActSheet->setCellValue('C17',$approve['record_time']);

        $objActSheet->setCellValue('C19',$approve['record_time']);
        $objActSheet->setCellValue('C20',$approve['record_time']);
        $objActSheet->setCellValue('C21',$approve['record_time']);
        $objActSheet->setCellValue('C22',$approve['record_time']);
        $objActSheet->setCellValue('C23',$approve['record_time']);
        $objActSheet->setCellValue('C24',$approve['record_time']);
        $objActSheet->setCellValue('C25',$approve['record_time']);
        $objActSheet->setCellValue('C26',$approve['record_time']);
        $objActSheet->setCellValue('C27',$approve['record_time']);

        $objActSheet->setCellValue('C30',$approve['record_time']);
        $objActSheet->setCellValue('C31',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A37');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B37', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C37');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E37', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F37');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G37', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'ME05-acmv instlln (basement & common area)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //导出QaBoredPile报告
    public static function actionEPEMe06Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $staff_list = Staff::userAllList();
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        for($o=0;$o<=2;$o++){
            $checked[$o]['signature_path'] ='';
            $checked[$o]['user_name'] ='';
            $checked[$o]['contractor_name'] = '';
            $checked[$o]['record_time'] = '';
            $checked[$o]['remark'] = '';
        }
        $checked_tag = 0;
        foreach($record_detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;

            if($v['deal_type'] == '2'){
                $submit['user_name'] = $user_name;
                $submit['signature_path'] = $signature_path;
                $submit['record_time'] = $v['record_time'];
            }

            if($v['deal_type'] == '3'){
                $inspect['user_name'] = $user_name;
                $inspect['signature_path'] = $signature_path;
                $inspect['record_time'] = $v['record_time'];
            }
            if($v['deal_type'] == '4'){
                $approve['user_name'] = $user_name;
                $approve['signature_path'] = $signature_path;
                $approve['record_time'] = $v['record_time'];
            }
        }

        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);

        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }
//        var_dump($form_data);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/ME06-fire protection instlln (basement & common area).xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'In-Situ Structural' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        foreach ($form_data as $i => $j){
            for($rowIndex_1 = 10;$rowIndex_1<=30;$rowIndex_1++){
                $addr_1 = $colIndex_1.$rowIndex_1;
                $cell = $currentSheet->getCell($addr_1)->getValue();
                if($cell == $j['item_id']){
                    $objActSheet->setCellValue($addr_1,$j['item_value']);
                }
            }
        }

        $objActSheet->setCellValue('F1',$form_data[0]['item_value']);
        $objActSheet->setCellValue('F2',$form_data[1]['item_value']);
        $objActSheet->setCellValue('A6','Location / Storey: '.$form_data[2]['item_value']);
        $objActSheet->setCellValue('F6',$form_data[3]['item_value']);
        $objActSheet->setCellValue('A8','Structural Element: '.$form_data[4]['item_value']);
        $objActSheet->setCellValue('F8',$form_data[5]['item_value']);
        $objActSheet->setCellValue('A10','Date/Time of Notification: '.$form_data[6]['item_value']);
        $objActSheet->setCellValue('F10',$form_data[7]['item_value']);

        $objActSheet->setCellValue('C14',$form_data[8]['item_value']);
        $objActSheet->setCellValue('C15',$form_data[9]['item_value']);
        $objActSheet->setCellValue('C16',$form_data[10]['item_value']);
        $objActSheet->setCellValue('C17',$form_data[11]['item_value']);
        $objActSheet->setCellValue('C18',$form_data[12]['item_value']);
        $objActSheet->setCellValue('C19',$form_data[13]['item_value']);
        $objActSheet->setCellValue('C20',$form_data[14]['item_value']);
        $objActSheet->setCellValue('C21',$form_data[15]['item_value']);
        $objActSheet->setCellValue('C22',$form_data[16]['item_value']);
        $objActSheet->setCellValue('C23',$form_data[17]['item_value']);
        $objActSheet->setCellValue('C24',$form_data[18]['item_value']);
        $objActSheet->setCellValue('C25',$form_data[19]['item_value']);
        $objActSheet->setCellValue('C26',$form_data[20]['item_value']);
        $objActSheet->setCellValue('C27',$form_data[21]['item_value']);
        $objActSheet->setCellValue('C28',$form_data[22]['item_value']);
        $objActSheet->setCellValue('C29',$form_data[23]['item_value']);
        $objActSheet->setCellValue('C30',$form_data[24]['item_value']);
        $objActSheet->setCellValue('C32',$form_data[25]['item_value']);
        $objActSheet->setCellValue('C33',$form_data[26]['item_value']);
        $objActSheet->setCellValue('C34',$form_data[27]['item_value']);

        $objActSheet->setCellValue('A36',$form_data[28]['item_value']);

        $objActSheet->setCellValue('C14',$inspect['record_time']);
        $objActSheet->setCellValue('C15',$inspect['record_time']);
        $objActSheet->setCellValue('C16',$inspect['record_time']);
        $objActSheet->setCellValue('C17',$inspect['record_time']);
        $objActSheet->setCellValue('C18',$inspect['record_time']);
        $objActSheet->setCellValue('C19',$inspect['record_time']);
        $objActSheet->setCellValue('C20',$inspect['record_time']);
        $objActSheet->setCellValue('C21',$inspect['record_time']);
        $objActSheet->setCellValue('C22',$inspect['record_time']);
        $objActSheet->setCellValue('C23',$inspect['record_time']);
        $objActSheet->setCellValue('C24',$inspect['record_time']);
        $objActSheet->setCellValue('C25',$inspect['record_time']);
        $objActSheet->setCellValue('C26',$inspect['record_time']);
        $objActSheet->setCellValue('C27',$inspect['record_time']);
        $objActSheet->setCellValue('C28',$inspect['record_time']);
        $objActSheet->setCellValue('C29',$inspect['record_time']);
        $objActSheet->setCellValue('C30',$inspect['record_time']);
        $objActSheet->setCellValue('C32',$inspect['record_time']);
        $objActSheet->setCellValue('C33',$inspect['record_time']);
        $objActSheet->setCellValue('C34',$inspect['record_time']);

        $objActSheet->setCellValue('C14',$approve['record_time']);
        $objActSheet->setCellValue('C15',$approve['record_time']);
        $objActSheet->setCellValue('C16',$approve['record_time']);
        $objActSheet->setCellValue('C17',$approve['record_time']);
        $objActSheet->setCellValue('C18',$approve['record_time']);
        $objActSheet->setCellValue('C19',$approve['record_time']);
        $objActSheet->setCellValue('C20',$approve['record_time']);
        $objActSheet->setCellValue('C21',$approve['record_time']);
        $objActSheet->setCellValue('C22',$approve['record_time']);
        $objActSheet->setCellValue('C23',$approve['record_time']);
        $objActSheet->setCellValue('C24',$approve['record_time']);
        $objActSheet->setCellValue('C25',$approve['record_time']);
        $objActSheet->setCellValue('C26',$approve['record_time']);
        $objActSheet->setCellValue('C27',$approve['record_time']);
        $objActSheet->setCellValue('C28',$approve['record_time']);
        $objActSheet->setCellValue('C29',$approve['record_time']);
        $objActSheet->setCellValue('C30',$approve['record_time']);
        $objActSheet->setCellValue('C32',$approve['record_time']);
        $objActSheet->setCellValue('C33',$approve['record_time']);
        $objActSheet->setCellValue('C34',$approve['record_time']);

        if($submit['signature_path']){
            if(file_exists($submit['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($submit['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('A39');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('B39', $submit['user_name'].' '.$submit['record_time']);

        if($inspect['signature_path']){
            if(file_exists($inspect['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($inspect['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('C39');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(30);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('E39', $inspect['user_name'].' '.$inspect['record_time']);

        if($approve['signature_path']){
            if(file_exists($approve['signature_path'])) {
                /*实例化excel图片处理类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath($approve['signature_path']);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片宽度*/
                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('F39');
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(25);//30
                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(20);//20
                $objDrawing->setWorksheet($objActSheet);
            }
        }
        $objActSheet->setCellValue('G39', $approve['user_name'].' '.$approve['record_time']);
        //导出
        $filename = 'ME06-fire protection instlln (basement & common area)-'.$title;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    //下载PDF
    public static function actionN6CCS01Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $pbu_name = TaskRecordModel::QaByModel($check_id);
        if($pbu_name == ''){
            $pbu_name = 'NA';
        }
        $item = QaForm::itemAry($form_id);
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $comment_detail = QaFormDataReal::detailList($check_id,$data_id); //步骤
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        $pic_count = 1;
        $index = 0;

        $check_data = array();
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $role_id = $staff_info->role_id;
            $role_info = Role::model()->findByPk($role_id);
            $team_id = $role_info->team_id;
            $role_name_en = $role_info->role_name_en;
            $user_name = $staff_info->user_name.'<br>'.$role_name_en;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_signature_path = $signature_path;
                    if(file_exists($prepare_signature_path)) {
                        $prepare_signature = '<img src="'.$prepare_signature_path.'" height="85" width="95"  />';
                    }else{
                        $prepare_signature = '';
                    }
                    $prepare_user_name =$user_name;
                    $prepare_contractor_name = $contractor_name;
                    $prepare_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                }
            }

            if($v['deal_type'] == '4' && $v['data_id'] == $data_id){

                $checked_signature_path =$signature_path;
                if(file_exists($checked_signature_path)) {
                    $checked_signature = '<img src="'.$checked_signature_path.'" height="85" width="95"  />';
                }else{
                    $checked_signature = '';
                }

                if($index == 0 && $v['form_data'] != NULL){
                    $checked_user_name_1 =$user_name;
                    $checked_record_time_1 = substr(Utils::DateToEn($v['record_time']),0,11);
                    $checked_signature_1 = $checked_signature;
                    $approved_data[1]['data'] = json_decode($v['form_data'],true);
                }
                if($index == 1){
                    $checked_user_name_2 =$user_name;
                    $checked_record_time_2 = substr(Utils::DateToEn($v['record_time']),0,11);
                    $checked_signature_2 = $checked_signature;
                    if($v['form_data'] != NULL){
                        $approved_data[2]['data'] = json_decode($v['form_data'],true);
                    }
                }
                if($index >1){
                    if($v['form_data'] != NULL){
                        $approved_data[3]['data'] = json_decode($v['form_data'],true);
                    }
                }
                $index++;
            }
        }
//        var_dump($approved_data);
//        exit;
        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }

        foreach($item as $item_id => $data_info){
            $group_name = $data_info['group_name'];
            $item_list['item_id'] = $item_id;
            $item_list['item_title'] = $data_info['item_title'];
            $item_data[$group_name][] = $item_list;
        }

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        if($record[0]['insp_no']){
            $insp_no = $record[0]['insp_no'];
        }else{
            $insp_no = 'NA';
        }
        $insp_no = $record[0]['insp_no'];
        $title = $record[0]['title'];
        $block = $record[0]['block']; //一级区域
        $program_id = $record[0]['project_id'];
        $main_conid = $record[0]['contractor_id'];
        $project_name = $record[0]['project_name']; //项目名称
        $contractor_name = $record[0]['contractor_name'];
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $con_logo = 'img/1661.png';

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($record[0]['apply_time'], 0, 4);//年
        $month = substr($record[0]['apply_time'], 5, 2);//月
        $day = substr($record[0]['apply_time'],8,2);//日
        $hours = substr($record[0]['apply_time'],11,2);//小时
        $minute = substr($record[0]['apply_time'],14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid.'/' . $check_id  . $data_id .'.pdf';

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 5, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $unchecked_img = 'img/wrong.png';
        $checked_img = 'img/right_1.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $ref_no = $form_data[1]['item_value'];
        $form_no = $form_data[0]['item_value'];
        $header = "<table width=\"100%\" >
                    <tr>
  	                    <td rowspan=\"4\" align=\"left\" style=\"height: 50px;\"></td>
  	                    <td align=\"right\" style=\"height: 11px;\">$contractor_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">$program_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\"> Inspection No.: $insp_no</td>
                    </tr>
                </table>";
        $storey = $form_data[2]['item_value'];
        $element = $form_data[3]['item_value'];
        $draw = $form_data[4]['item_value'];
        $notice_date = substr(Utils::DateToEn($form_data[5]['item_value']),0,11);
        $inspection_date = substr(Utils::DateToEn($form_data[6]['item_value']),0,11);
        $info_html ="<h2 align=\"center\">$data_title</h2><br><table>";
        foreach($item_data as $group_name => $res) {
            if ($group_name == 'Information') {
                foreach ($res as $i => $v) {
                    foreach($data['Information'] as $l => $list){
                        if($v['item_id'] == $list['item_id']){
                            $item_title = $v['item_title'];
                            $item_value = $list['item_value'];
                            $info_html.="<tr><td   width='35%' >$item_title </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$item_value</td></tr>";
                        }
                    }
                }
            }
        }
        $info_html.="<tr><td   width='35%' >Component Name: </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$pbu_name</td></tr>";
        $info_html.="</table>";

        $detail_html ="<br><table border=\"1\">";
        $detail_html.="<tr><td   width=\"10%\" align=\"center\">Item </td><td colspan=\"2\" width=\"60%\" >Details</td><td  width=\"10%\" align=\"center\">1st </td><td  width=\"10%\" align=\"center\">2nd</td><td  width=\"10%\" align=\"center\">3rd</td></tr>";

        $str = 65;
        $tmp = '';
        foreach($item_data as $group_name => $res){

            if($group_name != 'Information' && !strpos($group_name,'emarks')){
                $tag = chr($str);
                $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$tag</td><td colspan=\"2\" width=\"60%\" >$group_name</td><td  colspan=\"3\"  align=\"center\"> </td></tr>";
                $str++;
                $u = 0;
                foreach ($res as $i => $v) {


                    $pic_1 = '';
                    $pic_2 = '';
                    $pic_3 = '';

                    $title = $v['item_title'];
                    //第一次检查
                    if($approved_data[1]['data']){
                        foreach($approved_data[1]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_1 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_1 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_1 = '';
                                }else{
                                    $pic_1 = '';
                                }
                                $item_value_1 = $w['item_value'];
                            }
                        }
                    }

                    //第二次检查
                    if($approved_data[2]['data']){
                        foreach($approved_data[2]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_2 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_2 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_2 = '';
                                }else{
                                    $pic_2 = '';
                                }
                            }
                        }
                    }

                    //第三次检查
                    if($approved_data[3]['data']){
                        foreach($approved_data[3]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_3 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_3 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_3 = '';
                                }else{
                                    $pic_3 = '';
                                }
                            }
                        }
                    }
                    $o = 0;
                    $x = 0;
                    for($t=0;$t<strlen($title);$t++){
                        $c=substr($title,$t,1);
                        if($o == 0){
                            if (!preg_match('/[a-zA-Z]/',$c)){
                                $x++;
                            }else{
                                $o = 1;
                            }
                        }
                    }
                    $title = substr($title,$x);
                    $u++;
                    $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"30%\" >$title  </td><td  width=\"30% \" align=\"center\">$item_value_1</td><td  width=\"10%\" align=\"center\">$pic_1 </td><td  width=\"10%\" align=\"center\">$pic_2</td><td  width=\"10%\" align=\"center\">$pic_3</td></tr>";
                }
            }
            if(strpos($group_name,'emarks')){
                foreach ($res as $i => $v) {
                    $remarks = $v['item_value'].'<br>';
                }
            }
        }

        $detail_html.="</table>";

//        foreach($detail as $x => $y){
//            if($y['data_id'] == $data_id){
//                if($y['remark']){
//                    $remarks = $y['remark'].'<br>';
//                }
//            }
//        }
        $remark_html = "<h3 align=\"left\">Remarks:</h3><br><table border=\"1\">";
        $remark_html.="<tr><td style=\"height: 100px;\">$remarks</td></tr>";
        $remark_html.="</table>";

        $small_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="80px" width="80px" />';
        $img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="120px" width="120px" />';
        $big_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="160px" width="160px" />';
        $progress_list = "<table  cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" >";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">Prepared By: </td><td style=\"border-width: 1px;border-color:white white gray white;\" colspan=\"2\">Reviewed By: </td><td style=\"border-width: 1px;border-color:white white gray white\">Approved By: </td></tr>";
//        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_user_name</td><td style=\"border-width: 1px;border-color:gray white gray gray\" >$checked_user_name_1 </td><td style=\"border-width: 1px;border-color:gray gray gray white\" >$checked_user_name_2</td><td style=\"border-width: 1px;border-color:gray gray gray gray\"></td></tr>";
        $progress_list.="<tr><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray;\"><br>$prepare_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray white gray gray\"><br>$checked_signature_1</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray white\"><br>$checked_signature_2</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br></td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_record_time</td><td style=\"border-width: 1px;border-color:gray white gray gray\" >$checked_record_time_1 </td><td style=\"border-width: 1px;border-color:gray gray gray white\" >$checked_record_time_2</td><td style=\"border-width: 1px;border-color:gray gray gray gray\"></td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\" colspan=\"2\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td></tr>";
        $progress_list.="</table>";

        $pdf->writeHTML($header.$info_html, true, true, true, false, '');
        $pdf->writeHTML($detail_html, true, true, true, false, '');
        $pdf->writeHTML($remark_html, true, true, true, false, '');
        $pdf->writeHTML($progress_list, true, true, true, false, '');
        $detail_header = "<h3 align=\"center\">Comments && Photos</h3><br>";
        $pdf->writeHTML($detail_header, true, true, true, false, '');
        if($comment_detail){
            foreach($comment_detail as $i => $j){
                $comment_data[$j['item_id']][] = $j;
            }
            foreach($comment_data as $item_id => $item_data){
                $pic_before = '';
                $pic_after = '';

                $item_model = QaForm::model()->findByPk($item_id);
                $item_no = $item_model->item_title;
                foreach($item_data as $i => $j){
                    if($i%2==0){
                        $remark_before = $j['remark'];
                        $user_before = Staff::model()->findByPk($j['user_id']);
                        $user_name_before = $user_before->user_name;
                        $before_time = Utils::DateToEn(substr($j['record_time'],0,10));
                    }
                    //判断 根据图片的张数拼接  1张 big_img   2张 img  3张或者4张 small_img
                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==0){
                        $before = explode('|',$j['pic']);
                        $before_count = count($before);
                        $before_content = '';
                        if($before_count == 1){
                            $before_img_1 = '<img src="'.$before[0].'" height="160px" width="160px" />';
                            $before_content = $before_img_1;
                        }else if($before_count == 2){
                            $before_img_1 = '<img src="'.$before[0].'" height="120px" width="120px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="120px" width="120px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2;
                        }else if($before_count == 3){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;';
                        }else if($before_count >= 4){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80spx" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_img_4 = '<img src="'.$before[3].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;'.$before_img_4;
                        }
                    }
                    if($i%2==1){
                        $remark_after = $j['remark'];
                        $user_after = Staff::model()->findByPk($j['user_id']);
                        $user_name_after = $user_after->user_name;
                        $after_time = Utils::DateToEn(substr($j['record_time'],0,10));
                    }

                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==1){
                        $after = explode('|',$j['pic']);
                        $after_count = count($after);
                        $after_content = '';
                        if($after_count == 1){
                            $after_img_1 = '<img src="'.$after[0].'" height="160px" width="160px" />';
                            $after_content = $after_img_1;
                        }else if($after_count == 2){
                            $after_img_1 = '<img src="'.$after[0].'" height="120px" width="120px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="120px" width="120px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2;
                        }else if($after_count == 3){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;';
                        }else if($after_count >= 4){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80spx" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_img_4 = '<img src="'.$after[3].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;'.$after_img_4;
                        }
                    }

                    if($i%2==1){
                        $detail_list="<table width=\"100%\" border=\"1\"><tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\">$item_no</td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
                        $detail_list.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$before_content</td><td align=\"center\"  style=\"width: 50%\">$after_content </td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\">$remark_before</td><td style=\"width: 50%\">$remark_after</td></tr>";
                        $detail_list.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\" align=\"center\" >$user_name_before  $before_time</td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\" align=\"center\" >$user_name_after  $after_time</td></tr>";
                        $detail_list.="</table>";
                        $pdf->writeHTML($detail_list, true, true, true, false, '');
                    }
                }
            }
        }


//        $detail_list_1 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_1.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\"><br>$img &nbsp; $img </td><td align=\"center\"  style=\"width: 50%\">$img &nbsp; $img </td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_1.="</table>";
//
//        $detail_list_2 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_2.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$big_img </td><td align=\"center\"  style=\"width: 50%\">$big_img </td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_2.="</table>";

//        $pdf->writeHTML($detail_list_1, true, true, true, false, '');
//        $pdf->writeHTML($detail_list_2, true, true, true, false, '');
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == '1'){
                $pdf->Output($filepath, 'I');
            }
            if($params['tag'] == '2'){
                $pdf->Output($filepath, 'F');
                Utils::Download($filepath, $data_title, 'pdf');
            }
        }else{
            $pdf->Output($filepath, 'I');
        }


//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function actionN6CCS02Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $pbu_name = TaskRecordModel::QaByModel($check_id);
        if($pbu_name == ''){
            $pbu_name = 'NA';
        }
        $item = QaForm::itemAry($form_id);
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $comment_detail = QaFormDataReal::detailList($check_id,$data_id); //步骤
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        $pic_count = 1;
        $index = 0;

        $check_data = array();
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $role_id = $staff_info->role_id;
            $role_info = Role::model()->findByPk($role_id);
            $team_id = $role_info->team_id;
            $role_name_en = $role_info->role_name_en;
            $user_name = $staff_info->user_name.'<br>'.$role_name_en;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_signature_path = $signature_path;
                    if(file_exists($prepare_signature_path)) {
                        $prepare_signature = '<img src="'.$prepare_signature_path.'" height="85" width="95"  />';
                    }else{
                        $prepare_signature = '';
                    }
                    $prepare_user_name =$user_name;
                    $prepare_contractor_name = $contractor_name;
                    $prepare_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                }
            }

            if($v['deal_type'] == '3' && $v['data_id'] == $data_id){
                $checked_signature_path =$signature_path;
                if(file_exists($checked_signature_path)) {
                    $checked_signature = '<img src="'.$checked_signature_path.'" height="85" width="95"  />';
                }else{
                    $checked_signature = '';
                }
                $checked_user_name =$user_name;
                $checked_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                $checked_signature = $checked_signature;
            }

            if($v['deal_type'] == '4' && $v['data_id'] == $data_id){
                $approved_signature_path =$signature_path;
                if(file_exists($approved_signature_path)) {
                    $approved_signature = '<img src="'.$approved_signature_path.'" height="85" width="95"  />';
                }else{
                    $approved_signature = '';
                }

                if($index == 0 && $v['form_data'] != NULL){
                    $approved_user_name =$user_name;
                    $approved_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                    $approved_data[1]['data'] = json_decode($v['form_data'],true);
                }
                if($index == 1){
                    if($v['form_data'] != NULL){
                        $approved_data[2]['data'] = json_decode($v['form_data'],true);
                    }
                }
                if($index >1){
                    if($v['form_data'] != NULL){
                        $approved_data[3]['data'] = json_decode($v['form_data'],true);
                    }
                }
                $index++;
            }
        }
//        var_dump($approved_data);
//        exit;
        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }

        foreach($item as $item_id => $data_info){
            $group_name = $data_info['group_name'];
            $item_list['item_id'] = $item_id;
            $item_list['item_title'] = $data_info['item_title'];
            $item_data[$group_name][] = $item_list;
        }

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        if($record[0]['insp_no']){
            $insp_no = $record[0]['insp_no'];
        }else{
            $insp_no = 'NA';
        }
        $block = $record[0]['block']; //一级区域
        $program_id = $record[0]['project_id'];
        $main_conid = $record[0]['contractor_id'];
        $project_name = $record[0]['project_name']; //项目名称
        $contractor_name = $record[0]['contractor_name'];
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $con_logo = 'img/1661.png';

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($record[0]['apply_time'], 0, 4);//年
        $month = substr($record[0]['apply_time'], 5, 2);//月
        $day = substr($record[0]['apply_time'],8,2);//日
        $hours = substr($record[0]['apply_time'],11,2);//小时
        $minute = substr($record[0]['apply_time'],14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid.'/' . $check_id  . $data_id .'.pdf';

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 5, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $unchecked_img = 'img/wrong.png';
        $checked_img = 'img/right_1.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $ref_no = $form_data[1]['item_value'];
        $form_no = $form_data[0]['item_value'];
        $header = "<table width=\"100%\" >
                    <tr>
  	                    <td rowspan=\"4\" align=\"left\" style=\"height: 50px;\"></td>
  	                    <td align=\"right\" style=\"height: 11px;\">$contractor_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">$program_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">Inspection No.: $insp_no</td>
                    </tr>
                </table>";
        $storey = $form_data[2]['item_value'];
        $element = $form_data[3]['item_value'];
        $draw = $form_data[4]['item_value'];
        $notice_date = substr(Utils::DateToEn($form_data[5]['item_value']),0,11);
        $inspection_date = substr(Utils::DateToEn($form_data[6]['item_value']),0,11);
        $info_html ="<h2 align=\"center\">$data_title</h2><br><table>";
        foreach($item_data as $group_name => $res) {
            if ($group_name == 'Information') {
                foreach ($res as $i => $v) {
                    foreach($data['Information'] as $l => $list){
                        if($v['item_id'] == $list['item_id']){
                            $item_title = $v['item_title'];
                            $item_value = $list['item_value'];
                            $info_html.="<tr><td   width='35%' >$item_title </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$item_value</td></tr>";
                        }
                    }
                }
            }
        }
        $info_html.="<tr><td   width='35%' >Component Name: </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$pbu_name</td></tr>";
        $info_html.="</table>";

        $detail_html ="<br><table border=\"1\">";
        $detail_html.="<tr><td   width=\"10%\" align=\"center\">Item </td><td colspan=\"2\" width=\"60%\" >Details</td><td  width=\"10%\" align=\"center\">1st </td><td  width=\"10%\" align=\"center\">2nd</td><td  width=\"10%\" align=\"center\">3rd</td></tr>";

        $str = 65;
        $tmp = '';
        foreach($item_data as $group_name => $res){

            if($group_name != 'Information' && !strpos($group_name,'emarks')){
                $tag = chr($str);
                $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$tag</td><td colspan=\"2\" width=\"60%\" >$group_name</td><td  colspan=\"3\"  align=\"center\"> </td></tr>";
                $str++;
                $u = 0;
                foreach ($res as $i => $v) {


                    $pic_1 = '';
                    $pic_2 = '';
                    $pic_3 = '';

                    $title = $v['item_title'];
                    //第一次检查
                    if($approved_data[1]['data']){
                        foreach($approved_data[1]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_1 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_1 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_1 = '';
                                }else{
                                    $pic_1 = '';
                                }
                                $item_value_1 = $w['item_value'];
                            }
                        }
                    }

                    //第二次检查
                    if($approved_data[2]['data']){
                        foreach($approved_data[2]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_2 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_2 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_2 = '';
                                }else{
                                    $pic_2 = '';
                                }
                            }
                        }
                    }

                    //第三次检查
                    if($approved_data[3]['data']){
                        foreach($approved_data[3]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_3 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_3 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_3 = '';
                                }else{
                                    $pic_3 = '';
                                }
                            }
                        }
                    }
                    $o = 0;
                    $x = 0;
                    for($t=0;$t<strlen($title);$t++){
                        $c=substr($title,$t,1);
                        if($o == 0){
                            if (!preg_match('/[a-zA-Z]/',$c)){
                                $x++;
                            }else{
                                $o = 1;
                            }
                        }
                    }
                    $title = substr($title,$x);
                    $u++;
                    $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"30%\" >$title  </td><td  width=\"30% \" align=\"center\">$item_value_1</td><td  width=\"10%\" align=\"center\">$pic_1 </td><td  width=\"10%\" align=\"center\">$pic_2</td><td  width=\"10%\" align=\"center\">$pic_3</td></tr>";
                }
            }
            if(strpos($group_name,'emarks')){
                foreach ($res as $i => $v) {
                    $remarks = $v['item_value'].'<br>';
                }
            }
        }

        $detail_html.="</table>";

//        foreach($detail as $x => $y){
//            if($y['data_id'] == $data_id){
//                if($y['remark']){
//                    $remarks = $y['remark'].'<br>';
//                }
//            }
//        }
        $remark_html = "<br><br><br><h3 align=\"left\">Remarks:</h3><br><table border=\"1\">";
        $remark_html.="<tr><td style=\"height: 100px;\">$remarks</td></tr>";
        $remark_html.="</table>";

        $small_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="80px" width="80px" />';
        $img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="120px" width="120px" />';
        $big_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="160px" width="160px" />';
        $progress_list = "<table  cellpadding=\"5\" cellspacing=\"0\"  width=\"100%\" >";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">Prepared By: </td><td style=\"border-width: 1px;border-color:white white gray white\">Reviewed By: </td><td style=\"border-width: 1px;border-color:white white gray white\">Approved By: </td></tr>";
//        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_user_name</td></tr>";
        $progress_list.="<tr><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray;height:80px;\"><br>$prepare_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br>$checked_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br>$approved_signature</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_record_time</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td></tr>";
        $progress_list.="</table>";

        $pdf->writeHTML($header.$info_html, true, true, true, false, '');
        $pdf->writeHTML($detail_html, true, true, true, false, '');
        $pdf->writeHTML($remark_html, true, true, true, false, '');
        $pdf->writeHTML($progress_list, true, true, true, false, '');
        $detail_header = "<h3 align=\"center\">Comments && Photos</h3><br>";
        $pdf->writeHTML($detail_header, true, true, true, false, '');
        if($comment_detail){
            foreach($comment_detail as $i => $j){
                $comment_data[$j['item_id']][] = $j;
            }
            foreach($comment_data as $item_id => $item_data){
                $pic_before = '';
                $pic_after = '';

                $item_model = QaForm::model()->findByPk($item_id);
                $item_no = $item_model->item_title;
                foreach($item_data as $i => $j){
                    if($i%2==0){
                        $remark_before = $j['remark'];
                        $user_before = Staff::model()->findByPk($j['user_id']);
                        $user_name_before = $user_before->user_name;
                        $before_time = Utils::DateToEn(substr($j['record_time'],0,10));
                    }
                    //判断 根据图片的张数拼接  1张 big_img   2张 img  3张或者4张 small_img
                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==0){
                        $before = explode('|',$j['pic']);
                        $before_count = count($before);
                        $before_content = '';
                        if($before_count == 1){
                            $before_img_1 = '<img src="'.$before[0].'" height="160px" width="160px" />';
                            $before_content = $before_img_1;
                        }else if($before_count == 2){
                            $before_img_1 = '<img src="'.$before[0].'" height="120px" width="120px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="120px" width="120px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2;
                        }else if($before_count == 3){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;';
                        }else if($before_count >= 4){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80spx" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_img_4 = '<img src="'.$before[3].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;'.$before_img_4;
                        }
                    }
                    if($i%2==1){
                        $remark_after = $j['remark'];
                        $user_after = Staff::model()->findByPk($j['user_id']);
                        $user_name_after = $user_after->user_name;
                        $after_time = Utils::DateToEn(substr($j['record_time'],0,10));
                    }

                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==1){
                        $after = explode('|',$j['pic']);
                        $after_count = count($after);
                        $after_content = '';
                        if($after_count == 1){
                            $after_img_1 = '<img src="'.$after[0].'" height="160px" width="160px" />';
                            $after_content = $after_img_1;
                        }else if($after_count == 2){
                            $after_img_1 = '<img src="'.$after[0].'" height="120px" width="120px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="120px" width="120px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2;
                        }else if($after_count == 3){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;';
                        }else if($after_count >= 4){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80spx" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_img_4 = '<img src="'.$after[3].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;'.$after_img_4;
                        }
                    }

                    if($i%2==1){
                        $detail_list="<table width=\"100%\" border=\"1\"><tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\">$item_no</td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
                        $detail_list.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$before_content</td><td align=\"center\"  style=\"width: 50%\">$after_content </td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\">$remark_before</td><td style=\"width: 50%\">$remark_after</td></tr>";
                        $detail_list.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\" align=\"center\" >$user_name_before  $before_time</td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\" align=\"center\" >$user_name_after  $after_time</td></tr>";
                        $detail_list.="</table>";
                        $pdf->writeHTML($detail_list, true, true, true, false, '');
                    }
                }
            }
        }


//        $detail_list_1 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_1.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\"><br>$img &nbsp; $img </td><td align=\"center\"  style=\"width: 50%\">$img &nbsp; $img </td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_1.="</table>";
//
//        $detail_list_2 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_2.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$big_img </td><td align=\"center\"  style=\"width: 50%\">$big_img </td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_2.="</table>";

//        $pdf->writeHTML($detail_list_1, true, true, true, false, '');
//        $pdf->writeHTML($detail_list_2, true, true, true, false, '');
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == '1'){
                $pdf->Output($filepath, 'I');
            }
            if($params['tag'] == '2'){
                $pdf->Output($filepath, 'F');
                Utils::Download($filepath, $data_title, 'pdf');
            }
        }else{
            $pdf->Output($filepath, 'I');
        }


//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function actionN6CCS03Export($params,$data_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $pbu_name = TaskRecordModel::QaByModel($check_id);
        if($pbu_name == ''){
            $pbu_name = 'NA';
        }
        $item = QaForm::itemAry($form_id);
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $comment_detail = QaFormDataReal::detailList($check_id,$data_id); //步骤
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        $pic_count = 1;
        $index = 0;

        $check_data = array();
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $role_id = $staff_info->role_id;
            $role_info = Role::model()->findByPk($role_id);
            $team_id = $role_info->team_id;
            $role_name_en = $role_info->role_name_en;
            $user_name = $staff_info->user_name.'<br>'.$role_name_en;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_signature_path = $signature_path;
                    if(file_exists($prepare_signature_path)) {
                        $prepare_signature = '<img src="'.$prepare_signature_path.'" height="85" width="95"  />';
                    }else{
                        $prepare_signature = '';
                    }
                    $prepare_user_name =$user_name;
                    $prepare_contractor_name = $contractor_name;
                    $prepare_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                }
            }

            if($v['deal_type'] == '3' && $v['data_id'] == $data_id){
                $checked_signature_path =$signature_path;
                if(file_exists($checked_signature_path)) {
                    $checked_signature = '<img src="'.$checked_signature_path.'" height="85" width="95"  />';
                }else{
                    $checked_signature = '';
                }
                $checked_user_name =$user_name;
                $checked_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                $checked_signature = $checked_signature;
            }

            if($v['deal_type'] == '4' && $v['data_id'] == $data_id){

                $approved_signature_path =$signature_path;
                if(file_exists($approved_signature_path)) {
                    $approved_signature = '<img src="'.$approved_signature_path.'" height="85" width="95"  />';
                }else{
                    $approved_signature = '';
                }

                if($index == 0 && $v['form_data'] != NULL){
                    $approved_user_name =$user_name;
                    $approved_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                    $approved_data[1]['data'] = json_decode($v['form_data'],true);
                }
                if($index == 1){
                    if($v['form_data'] != NULL){
                        $approved_data[2]['data'] = json_decode($v['form_data'],true);
                    }
                }
                if($index >1){
                    if($v['form_data'] != NULL){
                        $approved_data[3]['data'] = json_decode($v['form_data'],true);
                    }
                }
                $index++;
            }
        }
//        var_dump($approved_data);
//        exit;
        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }

        foreach($item as $item_id => $data_info){
            $group_name = $data_info['group_name'];
            $item_list['item_id'] = $item_id;
            $item_list['item_title'] = $data_info['item_title'];
            $item_data[$group_name][] = $item_list;
        }

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        if($record[0]['insp_no']){
            $insp_no = $record[0]['insp_no'];
        }else{
            $insp_no = 'NA';
        }
        $block = $record[0]['block']; //一级区域
        $program_id = $record[0]['project_id'];
        $main_conid = $record[0]['contractor_id'];
        $project_name = $record[0]['project_name']; //项目名称
        $contractor_name = $record[0]['contractor_name'];
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $con_logo = 'img/1661.png';

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($record[0]['apply_time'], 0, 4);//年
        $month = substr($record[0]['apply_time'], 5, 2);//月
        $day = substr($record[0]['apply_time'],8,2);//日
        $hours = substr($record[0]['apply_time'],11,2);//小时
        $minute = substr($record[0]['apply_time'],14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid.'/' . $check_id  . $data_id .'.pdf';

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 5, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $unchecked_img = 'img/wrong.png';
        $checked_img = 'img/right_1.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $ref_no = $form_data[1]['item_value'];
        $form_no = $form_data[0]['item_value'];
        $header = "<table width=\"100%\" >
                    <tr>
  	                    <td rowspan=\"4\" align=\"left\" style=\"height: 50px;\"></td>
  	                    <td align=\"right\" style=\"height: 11px;\">$contractor_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">$program_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">Inspection No.: $insp_no</td>
                    </tr>
                </table>";
        $storey = $form_data[2]['item_value'];
        $element = $form_data[3]['item_value'];
        $draw = $form_data[4]['item_value'];
        $notice_date = substr(Utils::DateToEn($form_data[5]['item_value']),0,11);
        $inspection_date = substr(Utils::DateToEn($form_data[6]['item_value']),0,11);
        $info_html ="<h2 align=\"center\">$data_title</h2><br><table>";
        foreach($item_data as $group_name => $res) {
            if ($group_name == 'Information') {
                foreach ($res as $i => $v) {
                    foreach($data['Information'] as $l => $list){
                        if($v['item_id'] == $list['item_id']){
                            $item_title = $v['item_title'];
                            $item_value = $list['item_value'];
                            $info_html.="<tr><td   width='35%' >$item_title </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$item_value</td></tr>";
                        }
                    }
                }
            }
        }
        $info_html.="<tr><td   width='35%' >Component Name: </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$pbu_name</td></tr>";
        $info_html.="</table>";

        $detail_html ="<br><table border=\"1\">";
        $detail_html.="<tr><td   width=\"10%\" align=\"center\">Item </td><td colspan=\"2\" width=\"60%\" >Details</td><td  width=\"10%\" align=\"center\">1st </td><td  width=\"10%\" align=\"center\">2nd</td><td  width=\"10%\" align=\"center\">3rd</td></tr>";

        $str = 65;
        $tmp = '';
        foreach($item_data as $group_name => $res){

            if($group_name != 'Information' && !strpos($group_name,'emarks')){
                $tag = chr($str);
                $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$tag</td><td colspan=\"2\" width=\"60%\" >$group_name</td><td  colspan=\"3\"  align=\"center\"> </td></tr>";
                $str++;
                $u = 0;
                foreach ($res as $i => $v) {


                    $pic_1 = '';
                    $pic_2 = '';
                    $pic_3 = '';

                    $title = $v['item_title'];
                    //第一次检查
                    if($approved_data[1]['data']){
                        foreach($approved_data[1]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_1 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_1 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_1 = '';
                                }else{
                                    $pic_1 = '';
                                }
                                $item_value_1 = $w['item_value'];
                            }
                        }
                    }

                    //第二次检查
                    if($approved_data[2]['data']){
                        foreach($approved_data[2]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_2 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_2 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_2 = '';
                                }else{
                                    $pic_2 = '';
                                }
                            }
                        }
                    }

                    //第三次检查
                    if($approved_data[3]['data']){
                        foreach($approved_data[3]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_3 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_3 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_3 = '';
                                }else{
                                    $pic_3 = '';
                                }
                            }
                        }
                    }
                    $o = 0;
                    $x = 0;
                    for($t=0;$t<strlen($title);$t++){
                        $c=substr($title,$t,1);
                        if($o == 0){
                            if (!preg_match('/[a-zA-Z]/',$c)){
                                $x++;
                            }else{
                                $o = 1;
                            }
                        }
                    }
                    $title = substr($title,$x);
                    $u++;
                    $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"30%\" >$title  </td><td  width=\"30% \" align=\"center\">$item_value_1</td><td  width=\"10%\" align=\"center\">$pic_1 </td><td  width=\"10%\" align=\"center\">$pic_2</td><td  width=\"10%\" align=\"center\">$pic_3</td></tr>";
                }
            }
            if(strpos($group_name,'emarks')){
                foreach ($res as $i => $v) {
                    $remarks = $v['item_value'].'<br>';
                }
            }
        }

        $detail_html.="</table>";

//        foreach($detail as $x => $y){
//            if($y['data_id'] == $data_id){
//                if($y['remark']){
//                    $remarks = $y['remark'].'<br>';
//                }
//            }
//        }
        $remark_html = "<h3 align=\"left\">Remarks:</h3><br><table border=\"1\">";
        $remark_html.="<tr><td style=\"height: 60px;\">$remarks</td></tr>";
        $remark_html.="</table>";

        $small_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="80px" width="80px" />';
        $img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="120px" width="120px" />';
        $big_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="160px" width="160px" />';
        $progress_list = "<table   cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" >";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">Prepared By: </td><td style=\"border-width: 1px;border-color:white white gray white\">Reviewed By: </td><td style=\"border-width: 1px;border-color:white white gray white\">Approved By: </td></tr>";
//        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_user_name</td></tr>";
        $progress_list.="<tr><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray;height:80px;\"><br>$prepare_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br>$checked_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br>$approved_signature</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_record_time</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td></tr>";
        $progress_list.="</table>";

        $pdf->writeHTML($header.$info_html, true, true, true, false, '');
        $pdf->writeHTML($detail_html, true, true, true, false, '');
        $pdf->writeHTML($remark_html, true, true, true, false, '');
        $pdf->writeHTML($progress_list, true, true, true, false, '');
        $detail_header = "<h3 align=\"center\">Comments && Photos</h3>";
        $pdf->writeHTML($detail_header, true, true, true, false, '');
        if($comment_detail){
            foreach($comment_detail as $i => $j){
                $comment_data[$j['item_id']][] = $j;
            }
            foreach($comment_data as $item_id => $item_data){
                $pic_before = '';
                $pic_after = '';

                $item_model = QaForm::model()->findByPk($item_id);
                $item_no = $item_model->item_title;
                foreach($item_data as $i => $j){
                    if($i%2==0){
                        $remark_before = $j['remark'];
                        $user_before = Staff::model()->findByPk($j['user_id']);
                        $user_name_before = $user_before->user_name;
                        $before_time = Utils::DateToEn(substr($j['record_time'],0,10));
                    }
                    //判断 根据图片的张数拼接  1张 big_img   2张 img  3张或者4张 small_img
                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==0){
                        $before = explode('|',$j['pic']);
                        $before_count = count($before);
                        $before_content = '';
                        if($before_count == 1){
                            $before_img_1 = '<img src="'.$before[0].'" height="160px" width="160px" />';
                            $before_content = $before_img_1;
                        }else if($before_count == 2){
                            $before_img_1 = '<img src="'.$before[0].'" height="120px" width="120px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="120px" width="120px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2;
                        }else if($before_count == 3){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;';
                        }else if($before_count >= 4){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80spx" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_img_4 = '<img src="'.$before[3].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;'.$before_img_4;
                        }
                    }
                    if($i%2==1){
                        $remark_after = $j['remark'];
                        $user_after = Staff::model()->findByPk($j['user_id']);
                        $user_name_after = $user_after->user_name;
                        $after_time = Utils::DateToEn(substr($j['record_time'],0,10));
                    }

                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==1){
                        $after = explode('|',$j['pic']);
                        $after_count = count($after);
                        $after_content = '';
                        if($after_count == 1){
                            $after_img_1 = '<img src="'.$after[0].'" height="160px" width="160px" />';
                            $after_content = $after_img_1;
                        }else if($after_count == 2){
                            $after_img_1 = '<img src="'.$after[0].'" height="120px" width="120px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="120px" width="120px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2;
                        }else if($after_count == 3){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;';
                        }else if($after_count >= 4){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80spx" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_img_4 = '<img src="'.$after[3].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;'.$after_img_4;
                        }
                    }

                    if($i%2==1){
                        $detail_list="<table width=\"100%\" border=\"1\"><tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\">$item_no</td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
                        $detail_list.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$before_content</td><td align=\"center\"  style=\"width: 50%\">$after_content </td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\">$remark_before</td><td style=\"width: 50%\">$remark_after</td></tr>";
                        $detail_list.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\" align=\"center\" >$user_name_before  $before_time</td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\" align=\"center\" >$user_name_after  $after_time</td></tr>";
                        $detail_list.="</table>";
                        $pdf->writeHTML($detail_list, true, true, true, false, '');
                    }
                }
            }
        }


//        $detail_list_1 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_1.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\"><br>$img &nbsp; $img </td><td align=\"center\"  style=\"width: 50%\">$img &nbsp; $img </td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_1.="</table>";
//
//        $detail_list_2 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_2.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$big_img </td><td align=\"center\"  style=\"width: 50%\">$big_img </td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_2.="</table>";

//        $pdf->writeHTML($detail_list_1, true, true, true, false, '');
//        $pdf->writeHTML($detail_list_2, true, true, true, false, '');
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == '1'){
                $pdf->Output($filepath, 'I');
            }
            if($params['tag'] == '2'){
                $pdf->Output($filepath, 'F');
                Utils::Download($filepath, $data_title, 'pdf');
            }
        }else{
            $pdf->Output($filepath, 'I');
        }


//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }
}
