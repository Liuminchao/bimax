<?php
class ModelController extends BaseController {

    public $title_rows = 4;
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";
    public $pageSize = 20;

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('license_condition', 'contentHeader');
        $this->bigMenu = Yii::t('license_condition', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($args) {

        $program_id = $args['project_id'];
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/model/grid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Block', '', '');
        $t->set_header('Level', '', '');
        $t->set_header('Part/Zone', '', '');
        $t->set_header('Unit No', '', '');
        $t->set_header('Unit Type', '', '');
        if($args['pbu_tag'] == '1'){
            $t->set_header('PBU Type', '', '');
        }else if($args['pbu_tag'] == '2'){
            $t->set_header('PPVC Type', '', '');
        }else if($args['pbu_tag'] == '3'){
            $t->set_header('Precast Type', '', '');
        }
        $t->set_header('QR Code ID', '', '');
        $t->set_header('Module Type', '', '');
        $t->set_header('Template', '', '');
        $t->set_header('Stage', '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $fields = func_get_args();
        if($_REQUEST['program_id']){
            $fields[0] = $_REQUEST['program_id'];
        }

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
        $t = $this->genDataGrid($args);
        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = RevitComponent::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('program_id' => $args['project_id'],'template_id' => $args['template_id'], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
        }
        if($_REQUEST['pbu_tag']){
            $pbu_tag = $_REQUEST['pbu_tag'];
        }else{
            $pbu_tag = '2';
        }
        $this->smallHeader = 'Components Status';
//        $this->layout = '//layouts/main_3';
        $this->render('list',array('program_id'=> $program_id,'args'=>$args,'pbu_tag'=>$pbu_tag));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genPbuGrid($args) {
        $program_id = $args['project_id'];
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=task/model/pbugrid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
//        $detail_list = ModelQr::QueryQr($program_id);
//            if(count($detail_list)){
////                $t->set_header('Guid', '', '');
//                foreach ($detail_list as $key => $value) {
//                    $t->set_header($value['name'], '', '');
//                }
//            }
        $t->set_header('Block', '', '');
        $t->set_header('Level', '', '');
        $t->set_header('Part/Zone', '', '');
        $t->set_header('Unit No', '', '');
        if($args['pbu_tag'] == '1'){
            $t->set_header('Unit Type', '', '');
        }else if($args['pbu_tag'] == '2'){
            $t->set_header('Unit Type', '', '');
        }else if($args['pbu_tag'] == '3'){
            $t->set_header('Component Type', '', '');
        }
        if($args['pbu_tag'] == '1'){
            $t->set_header('PBU Type', '', '');
        }else if($args['pbu_tag'] == '2'){
            $t->set_header('PPVC Type', '', '');
        }else if($args['pbu_tag'] == '3'){
            $t->set_header('Precast Type', '', '');
        }
        $t->set_header('QR Code ID', '', '');
        $t->set_header('Module Type', '', '');
        $t->set_header('Version', '', '');
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
        $t = $this->genPbuGrid($args);
        $this->savePbuUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = RevitComponent::queryPbuList($page, $this->pageSize, $args);
        $this->renderPartial('pbu_list', array('program_id' => $args['project_id'],'template_id' => $args['template_id'],'pbu_tag' =>$args['pbu_tag'], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionPbuList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['project_id']){
                $program_id = $args['project_id'];
            }
        }
        if($_REQUEST['pbu_tag']){
            $pbu_tag = $_REQUEST['pbu_tag'];
        }else{
            $pbu_tag = '2';
        }
        $this->smallHeader = 'Components';
//        $this->layout = '//layouts/main_3';
        $this->render('pbulist',array('program_id'=> $program_id,'args'=>$args,'pbu_tag'=>$pbu_tag));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['task/model/list'] = str_replace("r=task/model/grid", "r=task/model/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 保存查询链接
     */
    private function savePbuUrl() {

        $a = Yii::app()->session['list_url'];
        $a['model/pbulist'] = str_replace("r=task/model/pbugrid", "r=task/model/pbulist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 用于页面二维码显示
     */
    public function actionQrByPrint() {

        $model_id = $_REQUEST['model_id'];
        $version = $_REQUEST['version'];
        $uuid = $_REQUEST['uuid'];
        $entityId = $_REQUEST['entityId'];
        $model_level = $_REQUEST['level'];
        $domain = RevitModel::domainText();
        $categor = RevitModel::categoryText();
        $program_id = $_REQUEST['program_id'];
        $detail_list = ModelQr::QueryQr($program_id);
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $program_id = $pro_model->root_proid;
        $root_model = Program::model()->findByPk($root_proid);
        $contractor_id = $root_model->contractor_id;
        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $contractor_id . '/model/';
        //include "qrlib.php";
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);
        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


        //    $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        $pbu_info = RevitComponent::pbuInfo($program_id,$uuid);
        $level = '';
        $block = '';
        $part = '';
        $unit_type = '';
        $element_name = '';
        $element_type = '';
        $pbu_name = '';
        if(count($pbu_info)>0){
            $level = $pbu_info[0]['level'];
            if($pbu_info[0]['unit_nos']){
                $level.='-'.$pbu_info[0]['unit_nos'];
            }
            $part = $pbu_info[0]['part'];
            $unit_type = $pbu_info[0]['unit_type'];
            $unit_name = $pbu_info[0]['unit_name'];
            $unit_nos = $pbu_info[0]['unit_nos'];
            $element_type = $pbu_info[0]['pbu_type'];
            $element_name = $pbu_info[0]['pbu_name'];
            $block = $pbu_info[0]['block'];
            $pbu_name = $pbu_info[0]['pbu_name'];
            $serial_number = $pbu_info[0]['serial_number'];
        }
        $filename = $PNG_TEMP_DIR . $entityId . '.png';
        $content['model_id'] = $model_id;
        $content['version'] = $version;
        $content['uuid'] = $uuid;
        $content['level'] = $level;
        $content['model_level'] = $model_level;
        $content['block'] = $block;
        $content['program_id'] = $program_id;
        $content = json_encode($content);
        $content = base64_encode($content);
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

        if(strlen($block) > 30){
            $block = substr($block,0,30).'..';
        }

        if(strlen($element_type) > 30){
            $element_type = substr($element_type,0,30).'..';
        }

        if(strlen($element_name) > 30){
            $element_name = substr($element_name,0,30).'..';
        }

        if(strlen($part) > 30){
            $element_part = substr($part,0,30).'..';
        }

        $rs['uuid'] = $uuid;
        $rs['block'] = $block;
        $rs['level'] = $level;
        $rs['part'] = $part;
        $rs['serial_number'] = $serial_number;
        $rs['unit_nos'] = $unit_nos;
        $rs['unit_name'] = $unit_name;
        $rs['unit_type'] = $unit_type;
        $rs['element_name'] = $element_name;
        $rs['element_type'] = $element_type;
        $rs['filename'] = $filename;
        $rs['pbu_name'] = $pbu_name;
        $rs['content'] = $detail_list;
        $level_unit = $level;
//        if($unit_nos){
//            $level_unit.='-'.$unit_nos;
//        }
        if(strlen($level_unit) > 30){
            $level_unit = substr($level_unit,0,30).'..';
        }
        $rs['level_unit'] = $level_unit;
        print_r(json_encode($rs));
    }

    /**
     * 用于页面二维码显示
     */
    public function actionPbuInfo() {
        $model_id = $_REQUEST['model_id'];
        $version = $_REQUEST['version'];
        $uuid = $_REQUEST['uuid'];
        $program_id = $_REQUEST['program_id'];
        $pbu_info = RevitComponent::pbuInfo($program_id,$uuid);
        $pbu_info_plan = PbuPlan::pbuInfo($program_id,$uuid);
        if(count($pbu_info)>0){
            $pbu_info[0]['plan'] = $pbu_info_plan;
//            if($pbu_info[0]['start_a']){
//                $pbu_info[0]['start_a'] = Utils::DateMonthYear($pbu_info[0]['start_a']);
//            }
//            if($pbu_info[0]['finish_a']){
//                $pbu_info[0]['finish_a'] = Utils::DateMonthYear($pbu_info[0]['finish_a']);
//            }
//            if($pbu_info[0]['start_b']){
//                $pbu_info[0]['start_b'] = Utils::DateMonthYear($pbu_info[0]['start_b']);
//            }
//            if($pbu_info[0]['finish_b']){
//                $pbu_info[0]['finish_b'] = Utils::DateMonthYear($pbu_info[0]['finish_b']);
//            }
//            if($pbu_info[0]['start_c']){
//                $pbu_info[0]['start_c'] = Utils::DateMonthYear($pbu_info[0]['start_c']);
//            }
//            if($pbu_info[0]['finish_c']){
//                $pbu_info[0]['finish_c'] = Utils::DateMonthYear($pbu_info[0]['finish_c']);
//            }
//            if($pbu_info[0]['start_d']){
//                $pbu_info[0]['start_d'] = Utils::DateMonthYear($pbu_info[0]['start_d']);
//            }
//            if($pbu_info[0]['finish_d']){
//                $pbu_info[0]['finish_d'] = Utils::DateMonthYear($pbu_info[0]['finish_d']);
//            }
//            if($pbu_info[0]['start_e']){
//                $pbu_info[0]['start_e'] = Utils::DateMonthYear($pbu_info[0]['start_e']);
//            }
//            if($pbu_info[0]['finish_e']){
//                $pbu_info[0]['finish_e'] = Utils::DateMonthYear($pbu_info[0]['finish_e']);
//            }
//            if($pbu_info[0]['start_f']){
//                $pbu_info[0]['start_f'] = Utils::DateMonthYear($pbu_info[0]['start_f']);
//            }
//            if($pbu_info[0]['finish_f']){
//                $pbu_info[0]['finish_f'] = Utils::DateMonthYear($pbu_info[0]['finish_f']);
//            }
//            if($pbu_info[0]['start_g']){
//                $pbu_info[0]['start_g'] = Utils::DateMonthYear($pbu_info[0]['start_g']);
//            }
//            if($pbu_info[0]['finish_g']){
//                $pbu_info[0]['finish_g'] = Utils::DateMonthYear($pbu_info[0]['finish_g']);
//            }
        }
        print_r(json_encode($pbu_info));
    }

    /**
     * QAQC
     */
    public function actionQaQc() {
        $args = $_REQUEST;
        $rs = TaskRecordModel::queryQaList($args);
        print_r(json_encode($rs));
    }

    /**
     * 查询任务
     */
    public function actionQueryCntProgressModel() {
        $template_id = $_REQUEST['template_id'];
        $program_id = $_REQUEST['program_id'];
        $model_id = $_REQUEST['model_id'];
        $rows = TaskRecordModel::cntByModel($program_id,$model_id,$template_id);
        print_r(json_encode($rows));
    }

    //表头数组
    private static function Exceltitle(){
        return array(
            'A' => array('field'=> 'model_id','type'=> 'string','array'=> 'pbu','title_cn' => 'ModelId','title_en'=>'ModelId'),
            'B' => array('field'=> 'guid','type'=> 'string','array'=> 'pbu','title_cn' => 'Guid','title_en'=>'Guid'),
            'C' => array('field' => 'block','type'=> 'string','array'=> 'pbu','title_cn' => 'Block','title_en'=>'Block'),
            'D' => array('field' => 'level','type'=> 'string','array'=> 'pbu','title_cn' => 'Level','title_en'=>'Level'),
            'E' => array('field' => 'unit_nos','type'=> 'string','array'=> 'pbu','title_cn' => 'Unit nos.','title_en'=>'Unit nos.'),
            'F' => array('field' => 'part','type'=> 'string','array'=> 'pbu','title_cn' => 'Part','title_en'=>'Part'),
            'G' => array('field' => 'unit_type','type'=> 'string','array'=> 'pbu','title_cn' => 'Unit Type','title_en'=>'Unit Type'),
            'H' => array('field' => 'serial_number','type'=> 'string','array'=> 'pbu','title_cn' => 'Serial Number','title_en'=>'Serial Number'),
            'I' => array('field' => 'pbu_type','type'=> 'string','array'=> 'pbu','title_cn' => 'Element Type','title_en'=>'PBU Type'),
            'J' => array('field' => 'pbu_name','type'=> 'string','array'=> 'pbu','title_cn' => 'Element Name','title_en'=>'PBU Name'),
            'K' => array('field' => 'module_type','type'=> 'string','array'=> 'pbu','title_cn' => 'Module Type','title_en'=>'Module Type'),
            'L' => array('field' => 'precast_plant','type'=> 'string','array'=> 'pbu','title_cn' => 'Precast Plant','title_en'=>'Precast Plant'),
//            'M' => array('field'=> 'start_a','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-A','title_en'=>'Start-A'),
//            'N' => array('field' => 'finish_a','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-A','title_en'=>'Finish-A'),
//            'O' => array('field' => 'start_b','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-B','title_en'=>'Start-B'),
//            'P' => array('field' => 'finish_b','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-B','title_en'=>'Finish-B'),
//            'Q' => array('field' => 'start_c','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-C','title_en'=>'Start-C'),
//            'R' => array('field' => 'finish_c','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-C','title_en'=>'Finish-C'),
//            'S' => array('field' => 'start_d','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-D','title_en'=>'Start-D'),
//            'T' => array('field' => 'finish_d','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-D','title_en'=>'Finish-D'),
//            'U' => array('field' => 'start_e','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-E','title_en'=>'Start-E'),
//            'V' => array('field' => 'finish_e','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-E','title_en'=>'Finish-E'),
//            'W' => array('field' => 'start_f','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-F','title_en'=>'Start-F'),
//            'X' => array('field' => 'finish_f','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-F','title_en'=>'Finish-F'),
//            'Y' => array('field' => 'start_g','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-G','title_en'=>'Start-G'),
//            'Z' => array('field' => 'finish_g','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-G','title_en'=>'Finish-G'),
        );
    }

    public function actionDownload()
    {
//        $file_name = "./template/excel/component_template.xlsx";
//        $file = fopen($file_name, "r"); // 打开文件
//        header('Content-Encoding: none');
//        header("Content-type: application/octet-stream");
//        header("Accept-Ranges: bytes");
//        header("Accept-Length: " . filesize($file_name));
//        header('Content-Transfer-Encoding: binary');
////        $name = "员工信息表导入模版".date('YmdHis').".xls";
//        if (Yii::app()->language == 'zh_CN') {
//            $name = "Component导入模版".".xls";
//        }else{
//            $name = "Component".".xls";
//        }
//
//        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
//        header('Pragma: no-cache');
//        header('Expires: 0');
//        echo fread($file, filesize($file_name));
//        fclose($file);

        $pbu_tag = $_REQUEST['pbu_tag'];
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A1')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('1')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('2')->setWidth(40);
        $objStyleA1 = $objActSheet->getStyle('A1');
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//        $objectPHPExcel->getActiveSheet()->getStyle('A1'.':'.'I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(11);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'A4');
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A2','ModelId');
        $objectPHPExcel->getActiveSheet()->getStyle('A2'.':'.'A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
        $objectPHPExcel->getActiveSheet()->mergeCells('B2'.':'.'B4');
        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B2','Guid');
        $objectPHPExcel->getActiveSheet()->getStyle('B2'.':'.'B4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('C2'.':'.'C4');
        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','Block');
        $objectPHPExcel->getActiveSheet()->getStyle('C2'.':'.'C4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('D2'.':'.'D4');
        $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2','Level');
        $objectPHPExcel->getActiveSheet()->getStyle('D2'.':'.'D4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('E2'.':'.'E4');
        $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2','Unit nos.');
        $objectPHPExcel->getActiveSheet()->getStyle('E2'.':'.'E4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('F2'.':'.'F4');
        $objectPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2','Part');
        $objectPHPExcel->getActiveSheet()->getStyle('F2'.':'.'F4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('G2'.':'.'G4');
        $objectPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2','Unit Type');
        $objectPHPExcel->getActiveSheet()->getStyle('G2'.':'.'G4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('H2'.':'.'H4');
        $objectPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2','Serial Number');
        $objectPHPExcel->getActiveSheet()->getStyle('H2'.':'.'H4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('I2'.':'.'I4');
        $objectPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2','Element Type');
        $objectPHPExcel->getActiveSheet()->getStyle('I2'.':'.'I4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('J2'.':'.'J4');
        $objectPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2','QR Code ID');
        $objectPHPExcel->getActiveSheet()->getStyle('J2'.':'.'J4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('K2'.':'.'K4');
        $objectPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        if($pbu_tag == '3'){
            $module_type = 'Module Type(Vertical/Horizontal)';
            $objectPHPExcel->getActiveSheet()->setCellValue('K2',$module_type);
        }else{
            $objectPHPExcel->getActiveSheet()->setCellValue('K2','Module Type');
        }
        $objectPHPExcel->getActiveSheet()->getStyle('K2'.':'.'K4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->mergeCells('L2'.':'.'L4');
        $objectPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L2','Precast Plant');
        $objectPHPExcel->getActiveSheet()->getStyle('L2'.':'.'L4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//        $stage_list = TaskTemplate::detailList($template_id);
//        $total = 1;
//        foreach($stage_list as $i => $j){
//            if($total < 15){
//                $header = chr(76+$total);
//            }else{
//                $y = $total / 15;
//                $header = chr(64 + $y).chr($total%15 + 65);
//            }
//
//            $objectPHPExcel->getActiveSheet()->getColumnDimension($header)->setWidth(50);
//            $objectPHPExcel->getActiveSheet()->mergeCells($header.'2'.':'.$header.'4');
//            $objectPHPExcel->getActiveSheet()->getStyle($header.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objectPHPExcel->getActiveSheet()->setCellValue($header.'2',$j['stage_id'].'|'.$j['stage_name'].' Start Date');
//            $objectPHPExcel->getActiveSheet()->getStyle($header.'2'.':'.$header.'4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $total++;
//
//            if($total < 15){
//                $header = chr(76+$total);
//            }else{
//                $y = $total / 15;
//                $header = chr(64 + $y).chr($total%15 + 65);
//            }
//
//            $objectPHPExcel->getActiveSheet()->getColumnDimension($header)->setWidth(50);
//            $objectPHPExcel->getActiveSheet()->mergeCells($header.'2'.':'.$header.'4');
//            $objectPHPExcel->getActiveSheet()->getStyle($header.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            $objectPHPExcel->getActiveSheet()->setCellValue($header.'2',$j['stage_id'].'|'.$j['stage_name'].' End Date');
//            $objectPHPExcel->getActiveSheet()->getStyle($header.'2'.':'.$header.'4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $total++;
//        }
        //下载输出
        ob_end_clean();
        //导出
        $rand = mt_rand(10,100);
        $filename = 'Model_component_template'.'_'.$rand;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xls"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objectPHPExcel, 'Excel5' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }


    public function actionView() {
        $model = new QaChecklist('create');
        $program_id =  $_REQUEST['program_id'];
        $pbu_tag =  $_REQUEST['pbu_tag'];
        $this->layout = '//layouts/main_new';
        $this->smallHeader = 'Upload Component';
        $this->render("batch", array('model' => $model,'program_id'=>$program_id,'pbu_tag'=>$pbu_tag));
    }

    public function actionDeleteView() {
        $model = new QaChecklist('create');
        $program_id =  $_REQUEST['program_id'];
        $this->layout = '//layouts/main_new';
        $this->smallHeader = 'Delete Component';
        $this->render("delete_batch", array('model' => $model,'program_id'=>$program_id));
    }

    //文件上传
    public function actionUpload(){
        $file = $_FILES['file'];
        $rs = array();

        if(!$file){
            $rs['status'] = '-1';
            $rs['msg'] = 'file does not exist.';
            print_r(json_encode($rs));
            exit();
        }

        $conid = Yii::app()->user->getState('contractor_id');
        $dir = Yii::app()->params['upload_file_path'].'/'.tmp.'/'.$conid;

        //上传Excel
        $file_rs = UploadFiles::fileUpload($file,array("xls","xlsx"), $dir);
        if($file_rs['status']==-1){
            $rs['status'] = $file_rs['status'];
            $rs['msg'] = $file_rs['desc'];
            print_r(json_encode($rs));
            exit();
        }

        $fname = $file ['name'];
        $ftype = substr ( strrchr ( $fname, '.' ), 1 );
        $filename = $file_rs['path'];
        chmod($filename, 0777);
        //读取excel
        $rs = $this->ReadExcel($filename);
        if(!is_object($rs)){
            print_r(json_encode($rs));
            exit();
        }

        $objPHPExcel = $rs;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //取得一共有多少行
        $rowCount = $currentSheet->getHighestRow();
        //处理excel的图片
//        $this->ReadPic($filename, $objPHPExcel);

        $rs = array('filename'=>$filename, 'rowcnt'=>$rowCount-$this->title_rows);
        print_r(json_encode($rs));

    }

    //文件上传(之前上传大文件excel 报null  改成接口上传)
    public function actionNewUpload(){

        $filename = $_REQUEST['file_path'];
        chmod($filename, 0777);
        //读取excel
        $rs = $this->ReadExcel($filename);
        if(!is_object($rs)){
            print_r(json_encode($rs));
            exit();
        }

        $objPHPExcel = $rs;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //取得一共有多少行
        $rowCount = $currentSheet->getHighestRow();

        //处理excel的图片
//        $this->ReadPic($filename, $objPHPExcel);

        $rs = array('filename'=>$filename, 'rowcnt'=>$rowCount-$this->title_rows);

        print_r(json_encode($rs));
    }

    /*
     *读取excel
     */
    function ReadExcel($filename){
        //设置脚本允许内存
        ini_set('memory_limit', '2048M');
        if(!file_exists($filename)){
            return array('status'=>'-2','msg'=>'未找到指定文件');
        }

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'IOFactory.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));

        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        if(!$objReader->canRead($filename)){
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            if(!$objReader->canRead($filename)){
                return array('status'=>'-3','msg'=>'文件不能读取');
            }
        }
        $objPHPExcel = $objReader->load($filename);

        return $objPHPExcel;
    }

    /*
     * 处理图片
     */
    function ReadPic($filename, $objPHPExcel){

        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //获取哪个企业
        $conid = Yii::app()->user->getState('contractor_id');

        //图片类型
        $type_path = self::TypePath();
        //后缀类型
        $suffix_path = self::TypeSuffix();
        //获取表头数组
        $rowKey = self::Exceltitle();

        //先处理图片
        $AllImages= $currentSheet->getDrawingCollection();
        foreach($AllImages as $drawing){
            if($drawing instanceof PHPExcel_Worksheet_MemoryDrawing){
                $image = $drawing->getImageResource();
                //$filename=$drawing->getIndexedFilename();
                $XY = $drawing->getCoordinates();
                //把图片存起来
                preg_match_all('/[0-9-]/',$XY,$d);
                //获取图片所在列数
                preg_match_all('/[A-Z-]/',$XY,$s);

                $column = implode('',$s[0]);
                $row = implode('',$d[0]);
                ob_start();
                call_user_func(
                    $drawing->getRenderingFunction(),
                    $drawing->getImageResource()
                );
                $imageContents = ob_get_contents();

                $pic_key = $rowKey[$column]['field'];
                $type = $type_path[$pic_key];
                $suffix_type = $suffix_path[$pic_key];
//                var_dump($type);
//                exit;
                $dir = Yii::app()->params['upload_data_path'].'/'.$type.'/'.$conid.'/';
                self::createPath($dir);
                $data = date('YmdHis').rand(10, 99).'_'.$suffix_type;
                $path = $dir.$data.'.png';
                file_put_contents($path,$imageContents); //把文件保存到本地
                ob_end_clean();

                //把图片的单元格的值设置为图片名称
                $currentSheet->getCell($XY)->setValue($path);

            }
        }

        PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5')->save($filename);

    }


    //读取文件
    public function actionReaddata(){
        $pbu_tag = $_REQUEST['pbu_tag'];
        $blockchart = $_REQUEST['blockchart'];
        $filename = $_REQUEST['filename'];
        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = $operator_id;
        }
//        $model_id = $_REQUEST['model_id'];
//        $version = $_REQUEST['version'];
        $model_id = 0;
        $version = '';

        $rs = $this->ReadExcel($filename);
        if(!is_object($rs)){
            print_r(json_encode($rs));
            exit();
        }

        $objPHPExcel = $rs;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //取得一共有多少行
        $rowCount = $currentSheet ->getHighestRow();
        $datarowCnt = $rowCount;
        //取得一共有多少列
        $highestColumn = $currentSheet->getHighestColumn();

        //获取表头数组
        $rowKey = array();
        $rowKey = self::Exceltitle();

        //获取最大列后的空白一列
        $highestColumn++;
//        var_dump($highestColumn);
//        exit;

        $rs = array();
        //行号从3开始，列号从A开始
        $template_id = $currentSheet->getCell('B1')->getValue();
//        $sql = "SELECT * FROM task_stage WHERE template_id=:template_id and status='0' ";
//        $command = Yii::app()->db->createCommand($sql);
//        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
//        $stage_list = $command->queryAll();

        $row = (int)$startrow;
        for($rowIndex=$row; $rowIndex<$row+$per_read_cnt; $rowIndex++){
            if($rowIndex<=$datarowCnt){
                $pbu = array();
                $stage_index = 0;
                $pbu['template_id']= $template_id;
                for($colIndex='A';$colIndex != $highestColumn;$colIndex++){
                    //获得字段值
                    $addr = $colIndex.$rowIndex;
                    $cell = $currentSheet->getCell($addr)->getValue();
                    //var_dump($cell);
                    //获取字段
                    $key = $rowKey[$colIndex]['field'];

                    if($cell instanceof PHPExcel_RichText)     //富文本转换字符串
                        $cell = $cell->__toString();

                    if($colIndex > 'L'){
                        $stage_index++;
                        $title_addr = $colIndex.'2';
                        $title_cell = $currentSheet->getCell($title_addr)->getValue();
                        $title_ar = explode('|',$title_cell);
                        if($cell != ''){
                            $cell = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell));
                        }else{
                            $cell = '';
                        }
                        if($stage_index%2 == 0){
                            $pbu_plan[$title_ar[0]]['end_date'] = $cell;
                        }else{
                            $pbu_plan[$title_ar[0]]['start_date'] = $cell;
                        }
                    }else{
                        if($rowKey[$colIndex]['array']=='pbu'){
                            if($cell == ''){
                                $pbu[$key] = '';
                            }else{
                                $pbu[$key] = $cell;
                            }
                        }
                    }

                }

                $project_id = $_REQUEST['id'];
                $pro_model =Program::model()->findByPk($project_id);
                $root_proid = $pro_model->root_proid;
                $pbu['project_id'] = $root_proid;
                $pbu['pbu_tag'] = $pbu_tag;
                $pro_model =Program::model()->findByPk($project_id);
                $root_proid = $pro_model->root_proid;
                $pbu['project_id'] = $root_proid;
                if($pbu['model_id'] != '0' && $pbu['model_id'] != ''){
                    $model_arr = explode('_',$pbu['model_id']);
                    $pbu['model_id'] = $model_arr[0];
                    $pbu['version'] = $model_arr[1];
                }else{
                    $pbu['model_id'] = '0';
                    $pbu['version'] = '';
                }
                $pbu['user_id'] = $user_id;
                $pbu['blockchart'] = $blockchart;
                $rs[$rowIndex] = RevitComponent::insertForm($pbu,$pbu_plan);
            }
        }
        //var_dump($rs);
        end:
        print_r(json_encode($rs));
    }

    //读取文件
    public function actionDeletedata(){

        $blockchart = $_REQUEST['blockchart'];
        $filename = $_REQUEST['filename'];
        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = $operator_id;
        }
//        $model_id = $_REQUEST['model_id'];
//        $version = $_REQUEST['version'];
        $model_id = 0;
        $version = '';

        $rs = $this->ReadExcel($filename);
        if(!is_object($rs)){
            print_r(json_encode($rs));
            exit();
        }

        $objPHPExcel = $rs;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //取得一共有多少行
        $rowCount = $currentSheet ->getHighestRow();
        $datarowCnt = $rowCount;
        //取得一共有多少列
        $highestColumn = $currentSheet->getHighestColumn();

        //获取表头数组
        $rowKey = array();
        $rowKey = self::Exceltitle();

        //获取最大列后的空白一列
        $highestColumn++;
//        var_dump($highestColumn);
//        exit;

        $rs = array();
        //行号从3开始，列号从A开始
        $template_id = $currentSheet->getCell('B1')->getValue();
//        $sql = "SELECT * FROM task_stage WHERE template_id=:template_id and status='0' ";
//        $command = Yii::app()->db->createCommand($sql);
//        $command->bindParam(":template_id", $template_id, PDO::PARAM_STR);
//        $stage_list = $command->queryAll();

        $row = (int)$startrow;
        for($rowIndex=$row; $rowIndex<$row+$per_read_cnt; $rowIndex++){
            if($rowIndex<=$datarowCnt){
                $pbu = array();
                $stage_index = 0;
                $pbu['template_id']= $template_id;
                for($colIndex='A';$colIndex != $highestColumn;$colIndex++){
                    //获得字段值
                    $addr = $colIndex.$rowIndex;
                    $cell = $currentSheet->getCell($addr)->getValue();
                    //var_dump($cell);
                    //获取字段
                    $key = $rowKey[$colIndex]['field'];

                    if($cell instanceof PHPExcel_RichText)     //富文本转换字符串
                        $cell = $cell->__toString();

                    if($rowKey[$colIndex]['array']=='pbu'){
                        if($cell == ''){
                            $pbu[$key] = '';
                        }else{
                            $pbu[$key] = $cell;
                        }
                    }

                }
                if($pbu['guid']){
                    $project_id = $_REQUEST['id'];
                    $pro_model =Program::model()->findByPk($project_id);
                    $root_proid = $pro_model->root_proid;
                    $pbu['project_id'] = $root_proid;
                    $pro_model =Program::model()->findByPk($project_id);
                    $root_proid = $pro_model->root_proid;
                    $pbu['project_id'] = $root_proid;
                    if($pbu['model_id'] != '0' && $pbu['model_id'] != ''){
                        $model_arr = explode('_',$pbu['model_id']);
                        $pbu['model_id'] = $model_arr[0];
                        $pbu['version'] = $model_arr[1];
                    }else{
                        $pbu['model_id'] = '0';
                        $pbu['version'] = '';
                    }
                    $pbu['user_id'] = $user_id;
                    $pbu['blockchart'] = $blockchart;
                    $rs[$rowIndex] = RevitComponent::deleteProjectPbu($pbu);
                }
            }
        }
        //var_dump($rs);
        end:
        print_r(json_encode($rs));
    }

    //读取文件
    public function actionReadPbudata(){

        $filename = $_REQUEST['filename'];
        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $entity_str = $_REQUEST['id_version'];
        $entity = explode('_',$entity_str);
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = $operator_id;
        }
//        $model_id = $_REQUEST['model_id'];
//        $version = $_REQUEST['version'];
        $model_id = $entity[0];
        $version = $entity[1];

        $rs = $this->ReadExcel($filename);
        if(!is_object($rs)){
            print_r(json_encode($rs));
            exit();
        }

        $objPHPExcel = $rs;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //取得一共有多少行
        $rowCount = $currentSheet ->getHighestRow();
        $datarowCnt = $rowCount;
        //取得一共有多少列
        $highestColumn = $currentSheet->getHighestColumn();

        //获取表头数组
        $rowKey = array();
        $rowKey = self::Exceltitle();

        //获取最大列后的空白一列
        $highestColumn++;
//        var_dump($highestColumn);
//        exit;

        $rs = array();
        //行号从3开始，列号从A开始
        $row = $startrow;
        for($rowIndex=$row; $rowIndex<$row+$per_read_cnt; $rowIndex++){
            $pbu = array();
            if($rowIndex<=$datarowCnt){
                for($colIndex='A';$colIndex != $highestColumn;$colIndex++){
                    //获得字段值
                    $addr = $colIndex.$rowIndex;
                    $cell = $currentSheet->getCell($addr)->getValue();
                    //var_dump($cell);
                    //获取字段
                    $key = $rowKey[$colIndex]['field'];

                    if($cell instanceof PHPExcel_RichText)     //富文本转换字符串
                        $cell = $cell->__toString();

                    if($cell == ''){
                        continue;
                    }
                    if($rowKey[$colIndex]['type']=='date'){
                        $cell = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell));
                    }

                    if($rowKey[$colIndex]['array']=='pbu'){
                        $pbu[$key] = $cell;
                    }
                    $pbu['project_id'] = $_REQUEST['id'];
                    $pbu['user_id'] = $user_id;
                    $pbu['model_id'] = $model_id;
                    $pbu['version'] = $version;
                }
                //var_dump($staff);var_dump($staffinfo);
                if(!empty($pbu)){
                    $rs[$rowIndex] = RevitComponent::insertForm($pbu);
                }
            }
        }
        //var_dump($rs);
        end:
        print_r(json_encode($rs));
    }

    //创建目录
    private static function createPath($path){
        if($path == ''){
            return false;
        }
        if(!file_exists($path))
        {
            umask(0000);
            @mkdir($path, 0777, true);
        }
        return true;
    }

    /**
     * 保存构件
     */
    public function actionSaveEntity() {

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);

        $entity_str = $_REQUEST['entity_str'];
        $redis->set('entity_str', $entity_str);
        $redis->close();
        $s['msg'] = 'Save Success';
        $s['status'] = '1';
        print_r(json_encode($s));
    }
    /**
     * 设置构件界面
     */
    public function actionSetEntity() {

        $program_id = $_REQUEST['program_id'];

        $this->renderPartial('setentity',array('program_id'=>$program_id));
    }
    /**
     * 保存构件
     */
    public function actionAddEntity() {

        $program_id = $_REQUEST['program_id'];
        $json_data = $_REQUEST['json_data'];
        $mode = $_REQUEST['mode'];
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);

        $entity_str = $redis->get('entity_str');
        $entity_list = explode(',',$entity_str);
//        $result_str  = json_encode($result);
//        $redis->set('model_list', $result_str);
        $json_data = implode($json_data,'@');
        $redis->set('josn_data',$json_data);

        $s['rowcnt'] = count($entity_list);
        $redis->close();
        print_r(json_encode($s));
    }

    /**
     * 读取模型全部信息
     */
    public function actionReadModelData()
    {

        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $program_id = $_REQUEST['program_id'];

        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $res['user_id'] = $user_model->user_id;
        }else{
            $res['user_id'] = $operator_id;
        }

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $entity_str = $redis->get('entity_str');
        $json_data = $redis->get('josn_data');
        $entity_list = explode(',',$entity_str);
        $uuid = array_slice($entity_list, $startrow, $per_read_cnt);
        $data = array(
            'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
            'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        $url = "https://bim.cmstech.sg/api/v1/token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        foreach ($uuid as $i => $j){
            $info = explode('_',$j);
            $model_id = $info[0];
            $version = $info[1];
            $guid = $info[2];
            $arr = array(
                'x-access-token:'.$rs['data']['token']
            );
            //        $data = json_encode($post_data);
            //https://bim.cmstech.sg/api/v1/models/5e099f9f3443310011286d98/components?version=1&property=undefined
//        $url = "https://bim.cmstech.sg/api/v1/models/".$model_id."/components?version=".$version."&property=false";
//            $url = "https://bim.cmstech.sg/api/v1/models/components";
            $url = "https://bim.cmstech.sg/api/v1/entity/$guid?modelId=".$model_id."&version=".$version;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            //跳过SSL验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $r = json_decode($output,true);
            $pagedata[] =$r['data'];
        }

        $pro_model = Program::model()->findByPk($program_id);
        $res['project_id'] = $pro_model->root_proid;

        if (count($pagedata) > 0) {
            //参数1:构件信息 参数2：页面添加的附属属性  参数3：其余参数 项目，模型，版本
            $r = RevitComponent::batchSetInfo($pagedata,$json_data,$res);
        } else {
            $r['file_path'] = '';
        }
        $i = 0;
        $redis->close();
        print_r(json_encode($r));
    }

    /**
     * 清除redis缓存，列表
     */
    public function actionclearcache() {

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $redis->del('model_list');
        $redis->del('josn_data');
        $redis->close();
        $r['message'] = 'Set Success';
        print_r(json_encode($r));
    }

    /**
     * 获取模型全部信息的数量
     */
    public function actionGetModelData() {

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);

        $entity_str = $_REQUEST['id_version'];
        $entity = explode('_',$entity_str);


        $data = array(
            'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
            'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        $url = "https://bim.cmstech.sg/api/v1/token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $data = array(
            'modelId' => $entity[0],
            'version' => $entity[1]
//            'uuids' => $entity_str
        );

        $arr = array(
            'x-access-token:'.$rs['data']['token']
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }

        //        $data = json_encode($post_data);
        //https://bim.cmstech.sg/api/v1/models/5e099f9f3443310011286d98/components?version=1&property=undefined
        $url = "https://bim.cmstech.sg/api/v1/models/".$entity[0]."/components?version=".$entity[1]."&property=false";
//            $url = "https://bim.cmstech.sg/api/v1/models/components";
//            $url = "https://bim.cmstech.sg/api/v1/entity/$j";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        //跳过SSL验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $r = json_decode($output,true);
        $result[] =$r['data'];

        $result_str  = json_encode($r['data']);
        $redis->set('model_list', $result_str);
        $s['rowcnt'] = count($result);
        $redis->close();
        print_r(json_encode($s));
    }

    //导出带模型的构件excel
    public static function actionExport(){
        $entity_str = $_REQUEST['id_version'];
        $program_id = $_REQUEST['program_id'];
        $entity = explode('_',$entity_str);
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $pbu_info = RevitComponent::detailList($program_id,$entity[0],$entity[1]);

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $model_list = $redis->get('model_list');
        $data = json_decode($model_list, true);

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = $objReader->load("./template/excel/model_component_template.xlsx");
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        $index = 7 ;
        $objActSheet->setCellValue('A2',$pro_name);
        $objActSheet->setCellValue('A3',$entity[0]);

        foreach($data as $i => $params) {
            $model_id = $params['modelId'];
            $uuid = $params['uuid'];
            $entityId = $params['entityId'];
            $type = $params['type'];
            $version = $params['version'];
            $name = $params['name'];
            $level = $params['floor'];
            $block = '';
            $unit_type = '';
//            foreach ($params['properties'] as $m => $n) {
//                $tag = 0;
//                if ($n['group'] == 'Constraints') {
//                    if ($n['key'] == 'Reference Level') {
//                        $level = $n['value'];
//                    } else if ($n['key'] == 'Level') {
//                        $level = $n['value'];
//                    } else if ($n['key'] == 'Base Constraint') {
//                        $level = $n['value'];
//                    }
//                }
//            }
            $level = '88';
            foreach($pbu_info as $j => $info){
                if($uuid == $info['pbu_id']){
                    $tag = 1;
                    $objActSheet->setCellValue('A'.$index,$info['pbu_id']);
                    $objActSheet->setCellValue('B'.$index,$info['block']);
                    $objActSheet->setCellValue('C'.$index,$info['level']);
                    $objActSheet->setCellValue('D'.$index,$info['unit_nos']);
                    $objActSheet->setCellValue('E'.$index,$info['part']);
                    $objActSheet->setCellValue('F'.$index,$info['unit_type']);
                    $objActSheet->setCellValue('G'.$index,$info['serial_number']);
                    $objActSheet->setCellValue('H'.$index,$info['pbu_type']);
                    $objActSheet->setCellValue('I'.$index,$info['pbu_name']);
                    $objActSheet->setCellValue('J'.$index,$info['model_type']);
                    $objActSheet->setCellValue('K'.$index,$info['precast_plant']);
                }
            }
            if($tag == 0){
                $objActSheet->setCellValue('A'.$index,$uuid);
                $objActSheet->setCellValue('B'.$index,'');
                $objActSheet->setCellValue('C'.$index,$level);
                $objActSheet->setCellValue('D'.$index,'');
                $objActSheet->setCellValue('E'.$index,'');
                $objActSheet->setCellValue('F'.$index,'');
                $objActSheet->setCellValue('G'.$index,'');
                $objActSheet->setCellValue('H'.$index,$entityId);
                $objActSheet->setCellValue('I'.$index,$name);
                $objActSheet->setCellValue('J'.$index,'');
                $objActSheet->setCellValue('K'.$index,'');
            }
            $index++;
        }
        $redis->delete('model_list');
        $redis->close();
        //导出
        $filename = 'Model_component_template-'.$entity[0];
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save ( 'php://output' );
    }

    /**
     * 获取模型全部信息的数量
     */
    public function actionGetPbuinfo() {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
//        $redis->delete('model_list');
//        $redis->delete('pbu_list');
//        $redis->delete('pbu_cnt');

        $program_id = $_REQUEST['program_id'];
        $entity_str = $_REQUEST['entity_str'];
        $info_list = '';
        $redis->set('model_list', $entity_str);
        $redis->set('info_list', $info_list);
        $uuid = explode(',',$entity_str);
        $s['rowcnt'] = count($uuid);
        $redis->close();
        print_r(json_encode($s));

    }

    /**
     * 获取模型全部信息的数量
     */
    public function actionSavePbuinfo() {

        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $program_id = $_REQUEST['program_id'];

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $model_list = $redis->get('model_list');
        $info_list = $redis->get('info_list');
        $info = json_decode($info_list,'true');
        //转成数组获取个数
        $count = count($info);
        //重新新增索引
        $index = $count;

        $pbu_list = explode(',',$model_list);

        $pagedata=array_slice($pbu_list,$startrow,$per_read_cnt);

        $data = array(
            'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
            'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        $url = "https://bim.cmstech.sg/api/v1/token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        foreach ($pagedata as $i => $j){
            $pbu_info = explode('_',$j);
            $model_id = $pbu_info[0];
            $version = $pbu_info[1];
            $guid = $pbu_info[2];
            $arr = array(
                'x-access-token:'.$rs['data']['token']
            );
            $url = "https://bim.cmstech.sg/api/v1/entity/$guid?modelId=".$model_id."&version=".$version;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            //跳过SSL验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $r = json_decode($output,true);
            $rs['info']['model'] = $r['data']['model'];
            $rs['info']['model_id'] = $r['data']['modelId'];
            $rs['info']['version'] = $r['data']['version'];
            $rs['info']['name'] = $r['data']['name'];
            $rs['info']['level'] = $r['data']['floor'];
            $rs['info']['uuid'] = $r['data']['uuid'];
            $rs['info']['block'] = '';
            $rs['info']['unit_nos'] = '';
            $rs['info']['unit_type'] = '';
            $rs['info']['element_type'] = '';
            $rs['info']['serial_no'] = '';
            $rs['info']['element_name'] = '';
            $rs['info']['module_type'] = '';
            $rs['info']['precast_plant'] = '';
            foreach ($r['data']['properties'] as $m => $n) {
                if ($n['group'] == 'Constraints') {
                    if ($n['key'] == 'Reference Level') {
                        $rs['info']['level'] = $n['value'];
                    } else if ($n['key'] == 'Level') {
                        $rs['info']['level'] = $n['value'];
                    } else if ($n['key'] == 'Base Constraint') {
                        $rs['info']['level'] = $n['value'];
                    }
                }
                if ($n['group'] == 'IFC Parameters') {
                    if ($n['key'] == '1:BLOCK') {
                        $rs['info']['block'] = $n['value'];
                    }
                    if ($n['key'] == '2:LEVEL/ UNIT') {
                        $level_unit = explode('/',$n['value']);
                        $rs['info']['level'] = $level_unit[0];
                        $rs['info']['unit_nos'] = $level_unit[1];
                    }
                    if ($n['key'] == '3:UNIT TYPE') {
                        $rs['info']['unit_type'] = $n['value'];
                    }
                    if ($n['key'] == '4:PPVC MARK') {
                        $rs['info']['element_type'] = $n['value'];
                    }
                    if ($n['key'] == '5:SERIAL NO.') {
                        $rs['info']['serial_no'] = $n['value'];
                    }
                    if ($n['key'] == '6:ELEMENT NAME.') {
                        $rs['info']['element_name'] = $n['value'];
                    }
                    if ($n['key'] == '7:WEIGHT') {
                        $rs['info']['module_type'] = $n['value'];
                    }
                    if ($n['key'] == '8:CONCRETE GRADE') {
                        $rs['info']['precast_plant'] = $n['value'];
                    }
                }
            }
            if($info_list == ''){
                $info[] = $rs['info'];
            }else{
                $info[$index] = $rs['info'];
                $index++;
            }
        }
        $info_str = json_encode($info);
        $redis->set('info_list', $info_str);
        $redis->close();
        print_r(json_encode($r));
    }

    //导出带模型的构件excel
    public static function actionExportPbu(){
        $program_id = $_REQUEST['program_id'];
        $rowcnt = $_REQUEST['rowcnt'];
        $export_id = $_REQUEST['export_id'];
//        $template_id = $_REQUEST['template_id'];
        if($rowcnt < 200){
            $pro_model = Program::model()->findByPk($program_id);
            $pro_name = $pro_model->program_name;
            //查询pbu_info表中已经有的构件
//        $pbu_info = RevitComponent::detailList($program_id,$model_id,$version);

            $data = array(
                'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
                'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
            );
            foreach ($data as $key => $value) {
                $post_data[$key] = $value;
            }
            //        $data = json_encode($post_data);
            $url = "https://bim.cmstech.sg/api/v1/token";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $rs = json_decode($output,true);

            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(7);
//        var_dump($redis->ping());
            $info_list = $redis->get('info_list');
//        $info_list = '"[{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type 2_R-WT : WP_Type_R-WT_4600x2820","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a453"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_1550x1760","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a62e"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x2395","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a680"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x3010","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a68a"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_PC-KGW_with Opening : K1F-3R-GW1-H","level":"CLUSTER A_14TH STOREY","uuid":"3bacf0a4-5050-4a27-80ce-a432715fa8e7-0012ae4b"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Roof_Canopy L shaped : WP_Roof_Canopy_3010&2545","level":"CLUSTER A_15TH STOREY","uuid":"a26374ad-ef75-47b3-ba25-f2a22bbf636b-0015acfd"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x2895","level":"CLUSTER A_15TH STOREY","uuid":"b9abea52-c0f2-4b3c-9048-20b3f1a8ba94-0013c61f"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x2895","level":"CLUSTER A_15TH STOREY","uuid":"b9abea52-c0f2-4b3c-9048-20b3f1a8ba94-0013c620"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x3875","level":"CLUSTER A_15TH STOREY","uuid":"bc9b118b-dff2-4fe9-8c7d-878a90288afd-0013c52a"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x4865","level":"CLUSTER A_15TH STOREY","uuid":"bc9b118b-dff2-4fe9-8c7d-878a90288afd-0013c5c4"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-WT_4600x3450","level":"CLUSTER A_15TH STOREY","uuid":"dd548d59-d00f-41a6-954c-e71488fc24cd-0013a770"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StCol-C_Rectangular_641B : LR Col (NO SCHEDULE)_250x1960","level":"LOWER ROOF LEVEL","uuid":"2791df73-2058-4a1a-94e4-12466d5a9ab2-000fa674"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WHB34_250x800","level":"LOWER ROOF LEVEL","uuid":"392a47b4-be5a-4c48-8bf3-d4840ce47dca-000c9760"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WHB34a_250x800","level":"LOWER ROOF LEVEL","uuid":"392a47b4-be5a-4c48-8bf3-d4840ce47dca-000c97da"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : RPT3_250x500","level":"LOWER ROOF LEVEL","uuid":"4437760a-1194-43c5-8f5c-39b2fe0dd7bd-00107c20"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WVB6_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c92e5"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WVB8_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c9329"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WHB38_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c9420"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WVB8a_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c9631"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 3 : Roof_Stump_Type3","level":"LOWER ROOF LEVEL","uuid":"5f5e50b7-6c90-466e-bfa3-fab00d9c4dc2-000ce557"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : RPT5_250x500","level":"LOWER ROOF LEVEL","uuid":"76f92e70-4a27-48ab-b217-ff3470670ad8-000c307a"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : RPT6_250x500","level":"LOWER ROOF LEVEL","uuid":"76f92e70-4a27-48ab-b217-ff3470670ad8-000c3237"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"9aae9287-3a12-409d-aaf6-eda8f57cb781-000ce767"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"9aae9287-3a12-409d-aaf6-eda8f57cb781-000ce779"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"bc2a9b05-1727-416b-9822-552e588b9112-000ce486"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"bc2a9b05-1727-416b-9822-552e588b9112-000ce496"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"bc2a9b05-1727-416b-9822-552e588b9112-000ce4c6"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WATER TANK_PRECAST 1 RING : WP_PC_Water Tank","level":"LOWER ROOF LEVEL","uuid":"ca94b050-0c42-4753-9382-7de2cdae31ba-000cd1da"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"Floor : CIS_250mm THK","level":"LOWER ROOF LEVEL","uuid":"df5e7891-d62a-47d6-8d2b-2d59683c86b8-001066fc"},{"model":"TMN6C17-18_WP_C_CS_A-641B_PBU","model_id":"5f168d0863f9e300118f67bc","version":1,"name":"HL_CS_PBU_3R","level":"CLUSTER A_14TH STOREY","uuid":"262b815b-6e94-43e5-ad9e-dd7dea468e03-00142e6e"}]"';

            spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
            $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
            require_once($phpExcelPath);
            spl_autoload_register(array('YiiBase', 'autoload'));
            $objectPHPExcel = new PHPExcel();

            $objectPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objectPHPExcel->getActiveSheet();
            $objActSheet->setTitle('Sheet1');

            //报表头的输出
            $objectPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);
            $objectPHPExcel->getActiveSheet()->setCellValue('A1','Model Template');
            $objectPHPExcel->getActiveSheet()->getStyle( 'A1')->getFont()->setBold(true);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('1')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('2')->setWidth(40);
            $objStyleA1 = $objActSheet->getStyle('A1');
            $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//        $objectPHPExcel->getActiveSheet()->getStyle('A1'.':'.'I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
            //字体及颜色
            $objFontA1 = $objStyleA1->getFont();
            $objFontA1->setSize(11);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
            $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'A4');
            $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('A2','ModelId');
            $objectPHPExcel->getActiveSheet()->getStyle('A2'.':'.'A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
            $objectPHPExcel->getActiveSheet()->mergeCells('B2'.':'.'B4');
            $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('B2','Guid');
            $objectPHPExcel->getActiveSheet()->getStyle('B2'.':'.'B4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('C2'.':'.'C4');
            $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('C2','Block');
            $objectPHPExcel->getActiveSheet()->getStyle('C2'.':'.'C4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('D2'.':'.'D4');
            $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('D2','Level');
            $objectPHPExcel->getActiveSheet()->getStyle('D2'.':'.'D4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('E2'.':'.'E4');
            $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('E2','Unit nos.');
            $objectPHPExcel->getActiveSheet()->getStyle('E2'.':'.'E4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('F2'.':'.'F4');
            $objectPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('F2','Part');
            $objectPHPExcel->getActiveSheet()->getStyle('F2'.':'.'F4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('G2'.':'.'G4');
            $objectPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('G2','Unit Type');
            $objectPHPExcel->getActiveSheet()->getStyle('G2'.':'.'G4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('H2'.':'.'H4');
            $objectPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('H2','Serial Number');
            $objectPHPExcel->getActiveSheet()->getStyle('H2'.':'.'H4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('I2'.':'.'I4');
            $objectPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('I2','Element Type');
            $objectPHPExcel->getActiveSheet()->getStyle('I2'.':'.'I4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('J2'.':'.'J4');
            $objectPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('J2','Element Name');
            $objectPHPExcel->getActiveSheet()->getStyle('J2'.':'.'J4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('K2'.':'.'K4');
            $objectPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('K2','Module Type');
            $objectPHPExcel->getActiveSheet()->getStyle('K2'.':'.'K4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(40);
            $objectPHPExcel->getActiveSheet()->mergeCells('L2'.':'.'L4');
            $objectPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->setCellValue('L2','Precast Plant');
            $objectPHPExcel->getActiveSheet()->getStyle('L2'.':'.'L4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $export_detail = PbuExportDetail::detailList($export_id);
//            $stage_list = TaskTemplate::detailList($template_id);
//            $total = 1;
//            foreach($stage_list as $i => $j){
//                if($total < 15){
//                    $header = chr(76+$total);
//                }else{
//                    $y = $total / 15;
//                    $header = chr(64 + $y).chr($total%15 + 65);
//                }
//
//                $objectPHPExcel->getActiveSheet()->getColumnDimension($header)->setWidth(50);
//                $objectPHPExcel->getActiveSheet()->mergeCells($header.'2'.':'.$header.'4');
//                $objectPHPExcel->getActiveSheet()->getStyle($header.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                $objectPHPExcel->getActiveSheet()->setCellValue($header.'2',$j['stage_id'].'|'.$j['stage_name'].' Start Date');
//                $objectPHPExcel->getActiveSheet()->getStyle($header.'2'.':'.$header.'4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//                $total++;
//
//                if($total < 15){
//                    $header = chr(76+$total);
//                }else{
//                    $y = $total / 15;
//                    $header = chr(64 + $y).chr($total%15 + 65);
//                }
//
//                $objectPHPExcel->getActiveSheet()->getColumnDimension($header)->setWidth(50);
//                $objectPHPExcel->getActiveSheet()->mergeCells($header.'2'.':'.$header.'4');
//                $objectPHPExcel->getActiveSheet()->getStyle($header.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//                $objectPHPExcel->getActiveSheet()->setCellValue($header.'2',$j['stage_id'].'|'.$j['stage_name'].' End Date');
//                $objectPHPExcel->getActiveSheet()->getStyle($header.'2'.':'.$header.'4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//                $total++;
//            }
//
            $index = 5;
//        var_dump($info_list);
//        var_dump($redis->ttl('info_list'));
//        exit;
            $info = json_decode($info_list,'true');

            $detail_list = ModelQr::QueryQr($program_id);

            $local = array(
                'A' => 'model_id',
                'B' => 'pbu_id',
                'C' => 'block',
                'D' => 'level',
                'E' => 'unit_nos',
                'F' => 'part',
                'G' => 'unit_type',
                'H' => 'serial_number',
                'I' => 'pbu_type',
                'J' => 'pbu_name',
                'K' => 'module_type',
                'L' => 'precast_plant'
            );

            $local2 = array(
                'model_id' => 'model_id',
                'pbu_id' => 'pbu_id',
                'block' => 'block',
                'level' => 'level',
                'unit_nos' => 'unit_nos',
                'part' => 'part',
                'unit_type' => 'unit_type',
                'serial_number' => 'serial_number',
                'pbu_type' => 'pbu_type',
                'pbu_name' => 'pbu_name',
                'module_type' => 'module_type',
                'precast_plant' => 'precast_plant'
            );

            foreach($info as $i => $params){
                $model_name = $params['model'];
                $model_id = $params['model_id'];
                $uuid = $params['uuid'];
                $version = $params['version'];
                $arr = array(
                    'x-access-token:'.$rs['data']['token']
                );
                $url = "https://bim.cmstech.sg/api/v1/entity/$uuid?modelId=".$model_id."&version=".$version;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                //跳过SSL验证
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
                // 3. 执行并获取HTML文档内容
                $output = curl_exec($ch);
                $r = json_decode($output,true);

                $params_info = $r['data'];

                $model = RevitComponent::model()->find('project_id=:project_id and model_id=:model_id and version=:version and pbu_id=:pbu_id',array(':project_id'=>$program_id,':model_id'=>$model_id,':pbu_id'=>$uuid,':version'=>$version));
                foreach($export_detail as $w => $e){
                    if($model){
                        if ($e['col'] == 'A') {
                            $objActSheet->setCellValue('A' . $index, $model->model_id . '_' . $model->version);
                        }else if($e['col'] == 'B'){
                            $objActSheet->setCellValue('B' . $index, $model->pbu_id);
                        }else{
//                            if(array_key_exists($e['value'],$local2)){
//                                $objActSheet->setCellValue($e['col'] . $index, $model->$e['value']);
//                            }else{
//                                $objActSheet->setCellValue($e['col'] . $index, $model->$local[$e['col']]);
//                            }
                            $fixed_val = '';
                            if($e['value']){
                                if(array_key_exists($e['value'],$local2)){
                                    $fixed_val = $model->$e['value'];
                                }
                                if(array_key_exists($e['value'],$params_info)){
                                    $fixed_val = $params_info[$e['value']];
                                }else{
                                    foreach ($params_info['properties'] as $m => $n){
                                        if($n['key'] == $e['value']){
                                            $fixed_val = $n['value'];
                                        }
                                    }
                                }
                            }
                            $objActSheet->setCellValue($e['col'].$index,$fixed_val);
                        }

                    }else{
                        $fixed_val = '';
                        if($e['col'] == 'A'){
                            $fixed_val = $params_info['modelId'] .'_'. $params_info['version'];
                        }
                        if($e['col'] == 'B'){
                            $fixed_val = $params_info['uuid'];
                        }
                        if($e['value']){
                            if(array_key_exists($e['value'],$params_info)){
                                $fixed_val = $params_info[$e['value']];
                            }else{
                                foreach ($params_info['properties'] as $m => $n){
                                    if($n['key'] == $e['value']){
                                        $fixed_val = $n['value'];
                                    }
                                }
                            }
                        }
                        $objActSheet->setCellValue($e['col'].$index,$fixed_val);
                    }
                }

                $index++;
            }
//        $objActSheet->setCellValue('A3',$model_id);
//        $objActSheet->setCellValue('B3',$model_name);
//        $objActSheet->setCellValue('C3',$version);
            $redis->del('model_list');
            $redis->del('info_list');
            $redis->close();
            //下载输出
            ob_end_clean();
            //导出
            $rand = mt_rand(10,100);
            $filename = 'Model_component_template-'.$model_id.'_'.$rand;
            header ( 'Content-Type: application/vnd.ms-excel' );
            header ( 'Content-Disposition: attachment;filename="' . $filename . '.xls"' ); //"'.$filename.'.xls"
            header ( 'Cache-Control: max-age=0' );
            $objWriter = PHPExcel_IOFactory::createWriter ( $objectPHPExcel, 'Excel5' ); //在内存中准备一个excel2003文件
            $filepath = '/opt/www-nginx/web/filebase/tmp/'.$filename.'.xls';
            $objWriter->save ( 'php://output' );
            $r['status'] = '1';
            print_r(json_encode($r));
        }else{
            $task['task_name'] = 'test export';
            $task['program_id'] = $_REQUEST['program_id'];
            $result = TaskModel::insertBasic($task);
            $id = $result['id'];
            //后台执行 非阻塞 异步
            exec('php /opt/www-nginx/web/test/idd/protected/yiic model exportpbu --param1='.$id.' --param2='.$program_id.' --param3='.$export_id.' --param4='.$template_id.' >/dev/null  &');
            $r['status'] = '1';
            print_r(json_encode($r));
        }
    }
    //导出任务列表
    public static function actionExportTask(){
        $program_id = $_REQUEST['program_id'];
        $r = TaskModel::detailList($program_id);
        print_r(json_encode($r));
    }
    //导出任务列表
    public static function actionExportDownload(){
        $id = $_REQUEST['id'];
        $export_model = TaskModel::model()->findByPk($id);
        $file_name = $export_model->path;
        $file = fopen($file_name, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_name));
        header('Content-Transfer-Encoding: binary');
//        $name = "员工信息表导入模版".date('YmdHis').".xls";
        if (Yii::app()->language == 'zh_CN') {
            $name = "模型导入模版".".xls";
        }else{
            $name = "ModelTemplate".".xls";
        }

        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($file_name));
        fclose($file);
    }
    /**
     * 列表
     */
    public function actionDemo() {
        //$this->layout = '//layouts/main_model';
        $this->layout = '//layouts/main_new';
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Models';
        $this->render('demo',array('program_id'=> $program_id));
    }

    /**
     * 读取模型全部信息
     */
    public function actionCreateQrPdf() {

        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $tag = $_REQUEST['tag'];

        $program_id = $_REQUEST['program_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $rs = explode('|',$tag);
        $pagedata=array_slice($rs,$startrow,$per_read_cnt);

        if(count($pagedata)>0){

            $data = array(
                'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
                'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
            );
            foreach ($data as $key => $value) {
                $post_data[$key] = $value;
            }
            //        $data = json_encode($post_data);
            $url = "https://bim.cmstech.sg/api/v1/token";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $rs = json_decode($output,true);
            foreach ($pagedata as $i => $j){

                $pbu_model = RevitComponent::model()->findByPk($j);
                $model_id = $pbu_model->model_id;
                if($model_id != '0' && $model_id != ''){
                    $tag = 0;
                    $version = $pbu_model->version;
                    $guid = $pbu_model->pbu_id;

                    $arr = array(
                        'x-access-token:'.$rs['data']['token']
                    );

                    $url = "https://bim.cmstech.sg/api/v1/entity/$guid?modelId=".$model_id;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    //跳过SSL验证
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
                    // 3. 执行并获取HTML文档内容
                    $output = curl_exec($ch);
                    $r = json_decode($output,true);
                    $result[] =$r['data'];
                }else{
                    $tag = 1;
                    $result[] =$j;
                }
            }
            //带模型的
            if($tag == 0){
                $file_path = ModelQr::downloadPdf($result,$program_id);
            }
            //不带模型的
            if($tag == 1){
                $file_path = ModelQr::downloadNomodelPdf($result,$program_id,$pbu_tag);
            }

            $filepath_cnt = $redis->lPush('pbuinfo-list', $file_path);
            $redis->set('pbuinfo_cnt', $filepath_cnt);
            $r['file_path'] = $file_path;
        }else{
            $r['file_path'] = '';
        }
        $i = 0;
        $redis->close();
        print_r(json_encode($r));
    }

    /**
     * 下载压缩包，清除redis缓存，列表
     */
    public function actionDownloadQrZip() {
        $filename = ModelQr::createPbuZip();
        if (file_exists($filename) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $redis->delete('pbuinfo-list');
        $redis->delete('pbuinfo_cnt');
        $redis->close();

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
        unlink($filename);
    }

    /**
     * 查询Block
     */
    public function actionQueryBlock() {
        $modellist = $_REQUEST['modellist'];
        $program_id = $_REQUEST['program_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $rows = RevitComponent::blockByModel($modellist,$program_id,$pbu_tag);
        print_r(json_encode($rows));
    }

    /**
     * 查询BlockList
     */
    public function actionQueryBlockList() {
        $modellist = $_REQUEST['modellist'];
        $program_id = $_REQUEST['program_id'];
        $rows = RevitComponent::blockByModelList($modellist,$program_id);
        print_r(json_encode($rows));
    }

    /**
     * 查询Level
     */
    public function actionQueryLevel() {
        $modellist = $_REQUEST['modellist'];
        $program_id = $_REQUEST['program_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $rows = RevitComponent::levelByModel($modellist,$program_id,$pbu_tag);
        print_r(json_encode($rows));
    }

    /**
     * 查询Level
     */
    public function actionQueryLevelList() {
        $block = $_REQUEST['block'];
        $program_id = $_REQUEST['program_id'];
        $rows = RevitComponent::levelByModelList($block,$program_id);
        print_r(json_encode($rows));
    }

    /**
     * 查询Part
     */
    public function actionQueryPart() {
        $modellist = $_REQUEST['modellist'];
        $program_id = $_REQUEST['program_id'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $rows = RevitComponent::partByModel($modellist,$program_id,$pbu_tag);
        print_r(json_encode($rows));
    }

    /**
     * 查询Part
     */
    public function actionQueryPartList() {
        $block = $_REQUEST['block'];
        $level = $_REQUEST['level'];
        $program_id = $_REQUEST['program_id'];
        $rows = RevitComponent::partByModelList($block,$level,$program_id);
        print_r(json_encode($rows));
    }

    /**
     * 查询Unit
     */
    public function actionQueryUnitList() {
        $block = $_REQUEST['block'];
        $level = $_REQUEST['level'];
        $part = $_REQUEST['part'];
        $program_id = $_REQUEST['program_id'];
        $rows = RevitComponent::unitByModelList($block,$level,$part,$program_id);
        print_r(json_encode($rows));
    }

    /**
     * 查询Name
     */
    public function actionQueryNameList() {
        $block = $_REQUEST['block'];
        $level = $_REQUEST['level'];
        $part = $_REQUEST['part'];
        $unit = $_REQUEST['unit'];
        $program_id = $_REQUEST['program_id'];
        $rows = RevitComponent::nameByModelList($block,$level,$part,$unit,$program_id);
        print_r(json_encode($rows));
    }

    /**
     * 查询Guid
     */
    public function actionQueryGuid() {
        $block = $_REQUEST['block'];
        $level = $_REQUEST['level'];
        $part = $_REQUEST['part'];
        $name = $_REQUEST['name'];
        $program_id = $_REQUEST['program_id'];
        $rows = RevitComponent::guidByModelList($block,$level,$part,$name,$program_id);
        print_r(json_encode($rows));
    }

    /**
     * 添加
     */
    public function actionNewQr() {
        $this->smallHeader = 'QR Code Template';
        $program_id = $_REQUEST['program_id'];
//        $this->layout = '//layouts/main_3';
        $detail_list = ModelQr::QueryQr($program_id);
        $this->render('method_statement',array('program_id'=>$program_id,'detail_list'=>$detail_list));
    }

    /**
     * 保存模型二维码内容
     */
    public function actionSaveQr() {

        $json = $_REQUEST['json_data'];
        $program_id = $_REQUEST['program_id'];
        $r = ModelQr::SaveQr($json,$program_id);
        echo json_encode($r);
    }

    /**
     * 删除文档
     */
    public function actionDeletePbu() {
        $tag = trim($_REQUEST['tag']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = RevitComponent::deletePbu($tag);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 构件交换任务记录
     */
    public function  actionTransfer(){
        $args['guid_1'] = $_REQUEST['guid_1'];
        $args['guid_2'] = $_REQUEST['guid_2'];
        $args['entityid_1'] = $_REQUEST['entityid_1'];
        $args['entityid_2'] = $_REQUEST['entityid_2'];
        $args['program_id'] = $_REQUEST['program_id'];
        $r = RevitComponent::transferPbu($args);
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 批量添加构件界面
     */
    public function actionCreate() {
        $program_id = $_REQUEST['id'];
        $model = new RevitComponent('modify');
        $this->renderPartial('create',array('program_id'=>$program_id,'model'=>$model));
    }

    /**
     * 保存构件
     */
    public function actionSaveCreate() {
        $args = $_POST['Create'];
        $r = RevitComponent::CreateNoModel($args);
        print_r(json_encode($r));
    }

    //导出带模型的构件excel
    public static function actionBachExport(){
        $args = $_GET['q']; //查询条件
        $rows = RevitComponent::queryAllList($args);
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        if($args['pbu_tag'] == '3'){
            $objPHPExcel = $objReader->load("./template/excel/component_bach_precast.xlsx");
        }else{
            $objPHPExcel = $objReader->load("./template/excel/component_bach.xlsx");
        }
        //获取当前活动的表
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->setTitle( 'Components' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        $index = 5;
        if(count($rows)>0){
            foreach ($rows as $i => $j){
                $block = $j['block'];
                $objActSheet->setCellValue('A'.$index,$j['model_id']);
                $objActSheet->setCellValue('B'.$index,$j['pbu_id']);
                $objActSheet->setCellValue('C'.$index,$j['block']);
                $objActSheet->setCellValue('D'.$index,$j['level']);
                $objActSheet->setCellValue('E'.$index,$j['unit_nos']);
                $objActSheet->setCellValue('F'.$index,$j['part']);
                $objActSheet->setCellValue('G'.$index,$j['unit_type']);
                $objActSheet->setCellValue('H'.$index,$j['serial_number']);
                $objActSheet->setCellValue('I'.$index,$j['pbu_type']);
                $objActSheet->setCellValue('J'.$index,$j['pbu_name']);
                $objActSheet->setCellValue('K'.$index,$j['module_type']);
                $objActSheet->setCellValue('L'.$index,$j['precast_plant']);
                $index++;
            }
        }


        if($args['pbu_tag'] == '1'){
            $filename = 'Components - PBU';
        }else if($args['pbu_tag'] == '2'){
            $filename = 'Components - PPVC';
        }else if($args['pbu_tag'] == '3'){
            $filename = 'Components - Precast';
        }
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' );
        $objWriter= PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        $objWriter->save('php://output');
    }

    //导出带模板的模型的构件excel
    public static function actionBachTemplateExport(){
        $args = $_GET['q']; //查询条件
        $rows = RevitComponent::queryStatusList($args);
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        if($args['pbu_tag'] == '3'){
            $objPHPExcel = $objReader->load("./template/excel/component_status_bach_precast.xlsx");
        }else{
            $objPHPExcel = $objReader->load("./template/excel/component_status_bach.xlsx");
        }
        //获取当前活动的表
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->setTitle( 'Components Status' );
        $colIndex_1 = 'H';
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
        $index = 5;
        if(count($rows)>0){
            foreach ($rows as $i => $j){
                $block = $j['block'];
                $objActSheet->setCellValue('A'.$index,$j['model_id']);
                $objActSheet->setCellValue('B'.$index,$j['pbu_id']);
                $objActSheet->setCellValue('C'.$index,$j['block']);
                $objActSheet->setCellValue('D'.$index,$j['level']);
                $objActSheet->setCellValue('E'.$index,$j['unit_nos']);
                $objActSheet->setCellValue('F'.$index,$j['part']);
                $objActSheet->setCellValue('G'.$index,$j['unit_type']);
                $objActSheet->setCellValue('H'.$index,$j['serial_number']);
                $objActSheet->setCellValue('I'.$index,$j['pbu_type']);
                $objActSheet->setCellValue('J'.$index,$j['pbu_name']);
                $objActSheet->setCellValue('K'.$index,$j['module_type']);
                $objActSheet->setCellValue('L'.$index,$j['precast_plant']);
                if($j['template_id']){
                    $model = TaskTemplate::model()->findByPk($j['template_id']);
                    $template_name=$model->template_name;
                }else{
                    $template_name='';
                }
                $objActSheet->setCellValue('M'.$index,$template_name);
                if($j['stage_id']){
                    $model = TaskStage::model()->findByPk($j['stage_id']);
                    $stage_name=$model->stage_name;
                }else{
                    $stage_name='Not Start';
                }
                $objActSheet->setCellValue('N'.$index,$stage_name);
                $index++;
            }
        }

        if($args['pbu_tag'] == '1'){
            $filename = 'Components Status - PBU';
        }else if($args['pbu_tag'] == '2'){
            $filename = 'Components Status - PPVC';
        }else if($args['pbu_tag'] == '3'){
            $filename = 'Components Status - Precast';
        }

        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' );
        $objWriter= PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        $objWriter->save('php://output');
    }

    /**
     * 替换界面
     */
    public function actionReplace() {
        $tag = $_REQUEST['tag'];
        $pbu_tag = $_REQUEST['pbu_tag'];
        $this->renderPartial('replace',array('tag'=>$tag,'pbu_tag'=>$pbu_tag));
    }

    /**
     * 获取构件原属性值
     */
    public function actionGetOriginal() {
        $id = $_POST['id'];
        $type = $_POST['type'];
        $r = RevitComponent::GetOriginal($id,$type);
        print_r(json_encode($r));
    }

    /**
     * 设置构件属性更新值
     */
    public function actionSetOriginal() {
        $id = $_POST['id'];
        $type = $_POST['type'];
        $replace = $_POST['replace'];
        $r = RevitComponent::SetOriginal($id,$type,$replace);
        print_r(json_encode($r));
    }
}
