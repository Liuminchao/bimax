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
        $detail_list = ModelQr::QueryQr($program_id);
        if($args['modellist'] != '0'){
            if(count($detail_list)){
                $t->set_header('Model Version', '', '');
                foreach ($detail_list as $key => $value) {
                    $t->set_header($value['name'], '', '');
                }
                $t->set_header('Record Time', '', '');
            }else{
                $t->set_header('Model Version', '', '');
                $t->set_header('Block', '', '');
                $t->set_header('Part', '', '');
                $t->set_header('Level/Unit', '', '');
                $t->set_header('Unit Type', '', '');
                $t->set_header('Element Name', '', '');
                $t->set_header('Element Type', '', '');
                $t->set_header('Record Time', '', '');
            }
        }else{
            $t->set_header('Model Version', '', '');
            $t->set_header('Block', '', '');
            $t->set_header('Part', '', '');
            $t->set_header('Level/Unit', '', '');
            $t->set_header('Unit Type', '', '');
            $t->set_header('Element Name', '', '');
            $t->set_header('Element Type', '', '');
            $t->set_header('Record Time', '', '');
        }
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
        $this->smallHeader = 'QA Checklist';
        $this->layout = '//layouts/main_3';
        $this->render('list',array('program_id'=> $program_id));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['mcrole/list'] = str_replace("r=comp/role/grid", "r=comp/role/list", $_SERVER["QUERY_STRING"]);
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
        if($unit_nos){
            $level_unit.='-'.$unit_nos;
        }
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
        $pbu_info = RevitComponent::pbuInfo($program_id,$uuid,$version);
        if(count($pbu_info)>0){
            if($pbu_info[0]['start_a']){
                $pbu_info[0]['start_a'] = Utils::DateMonthYear($pbu_info[0]['start_a']);
            }
            if($pbu_info[0]['finish_a']){
                $pbu_info[0]['finish_a'] = Utils::DateMonthYear($pbu_info[0]['finish_a']);
            }
            if($pbu_info[0]['start_b']){
                $pbu_info[0]['start_b'] = Utils::DateMonthYear($pbu_info[0]['start_b']);
            }
            if($pbu_info[0]['finish_b']){
                $pbu_info[0]['finish_b'] = Utils::DateMonthYear($pbu_info[0]['finish_b']);
            }
            if($pbu_info[0]['start_c']){
                $pbu_info[0]['start_c'] = Utils::DateMonthYear($pbu_info[0]['start_c']);
            }
            if($pbu_info[0]['finish_c']){
                $pbu_info[0]['finish_c'] = Utils::DateMonthYear($pbu_info[0]['finish_c']);
            }
            if($pbu_info[0]['start_d']){
                $pbu_info[0]['start_d'] = Utils::DateMonthYear($pbu_info[0]['start_d']);
            }
            if($pbu_info[0]['finish_d']){
                $pbu_info[0]['finish_d'] = Utils::DateMonthYear($pbu_info[0]['finish_d']);
            }
            if($pbu_info[0]['start_e']){
                $pbu_info[0]['start_e'] = Utils::DateMonthYear($pbu_info[0]['start_e']);
            }
            if($pbu_info[0]['finish_e']){
                $pbu_info[0]['finish_e'] = Utils::DateMonthYear($pbu_info[0]['finish_e']);
            }
            if($pbu_info[0]['start_f']){
                $pbu_info[0]['start_f'] = Utils::DateMonthYear($pbu_info[0]['start_f']);
            }
            if($pbu_info[0]['finish_f']){
                $pbu_info[0]['finish_f'] = Utils::DateMonthYear($pbu_info[0]['finish_f']);
            }
            if($pbu_info[0]['start_g']){
                $pbu_info[0]['start_g'] = Utils::DateMonthYear($pbu_info[0]['start_g']);
            }
            if($pbu_info[0]['finish_g']){
                $pbu_info[0]['finish_g'] = Utils::DateMonthYear($pbu_info[0]['finish_g']);
            }
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
            'M' => array('field'=> 'start_a','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-A','title_en'=>'Start-A'),
            'N' => array('field' => 'finish_a','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-A','title_en'=>'Finish-A'),
            'O' => array('field' => 'start_b','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-B','title_en'=>'Start-B'),
            'P' => array('field' => 'finish_b','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-B','title_en'=>'Finish-B'),
            'Q' => array('field' => 'start_c','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-C','title_en'=>'Start-C'),
            'R' => array('field' => 'finish_c','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-C','title_en'=>'Finish-C'),
            'S' => array('field' => 'start_d','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-D','title_en'=>'Start-D'),
            'T' => array('field' => 'finish_d','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-D','title_en'=>'Finish-D'),
            'U' => array('field' => 'start_e','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-E','title_en'=>'Start-E'),
            'V' => array('field' => 'finish_e','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-E','title_en'=>'Finish-E'),
            'W' => array('field' => 'start_f','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-F','title_en'=>'Start-F'),
            'X' => array('field' => 'finish_f','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-F','title_en'=>'Finish-F'),
            'Y' => array('field' => 'start_g','type'=> 'date','array'=> 'pbu','title_cn' => 'Start-G','title_en'=>'Start-G'),
            'Z' => array('field' => 'finish_g','type'=> 'date','array'=> 'pbu','title_cn' => 'Finish-G','title_en'=>'Finish-G'),
        );
    }

    public function actionDownload()
    {
        $file_name = "./template/excel/component_template.xlsx";
        $file = fopen($file_name, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_name));
        header('Content-Transfer-Encoding: binary');
//        $name = "员工信息表导入模版".date('YmdHis').".xls";
        if (Yii::app()->language == 'zh_CN') {
            $name = "Component导入模版".".xls";
        }else{
            $name = "Component".".xls";
        }

        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($file_name));
        fclose($file);
    }


    public function actionView() {
        $model = new QaChecklist('create');
        $program_id =  $_REQUEST['program_id'];
        $this->layout = '//layouts/main_3';
        $this->render("batch", array('model' => $model,'program_id'=>$program_id));
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
        $row = (int)$startrow;
        for($rowIndex=$row; $rowIndex<$row+$per_read_cnt; $rowIndex++){
            if($rowIndex<=$datarowCnt){
                $pbu = array();
                for($colIndex='A';$colIndex != $highestColumn;$colIndex++){
                    //获得字段值
                    $addr = $colIndex.$rowIndex;
                    $cell = $currentSheet->getCell($addr)->getValue();
                    //var_dump($cell);
                    //获取字段
                    $key = $rowKey[$colIndex]['field'];

                    if($cell instanceof PHPExcel_RichText)     //富文本转换字符串
                        $cell = $cell->__toString();

                    if($rowKey[$colIndex]['type']=='date'){
                        if($cell != ''){
                            $cell = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell));
                        }
                    }

//                    if($cell == ''){
//                        continue;
//                    }

                    if($rowKey[$colIndex]['array']=='pbu'){
                        if($cell == ''){
                            $pbu[$key] = '';
                        }else{
                            $pbu[$key] = $cell;
                        }
                    }
                }

                if($pbu['guid']){
                    $pbu['project_id'] = $_REQUEST['id'];
                    if($pbu['model_id'] != '0' && $pbu['model_id'] != ''){
                        $model_arr = explode('_',$pbu['model_id']);
                        $pbu['model_id'] = $model_arr[0];
                        $pbu['version'] = $model_arr[1];
                    }else{
                        $pbu['model_id'] = '0';
                        $pbu['version'] = '';
                    }
                    $pbu['user_id'] = $user_id;
                    if($rowIndex == 5){
                        $exist_data = RevitComponent::model()->count('project_id=:project_id  and model_id=:model_id and version=:version and status=:status', array('project_id'=>$pbu['project_id'],'model_id'=>$pbu['model_id'],'version'=>$pbu['version'],'status'=>'0'));
                        if ($pbu['model_id'] !=0 && $exist_data != 0) {
                            $status = '0';
                            $sub_sql = "delete from pbu_info  where project_id = :project_id and model_id = :model_id ";
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":project_id", $pbu['project_id'], PDO::PARAM_STR);
                            $command->bindParam(":model_id", $pbu['model_id'], PDO::PARAM_STR);
//                            $command->bindParam(":version", $pbu['version'], PDO::PARAM_STR);
                            $command->execute();
                        }
                    }
//                    if($pbu['version']){
//
//                    }
                    $rs[$rowIndex] = RevitComponent::insertForm($pbu);
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
            $expireTime = mktime('23',59,59,date('m'),date('d'),date('Y'));
            $redis->expireAt('model_list',$expireTime);
            $redis->expireAt('info_list',$expireTime);
            $info_list = $redis->get('info_list');
            spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
            $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
            require_once($phpExcelPath);
            spl_autoload_register(array('YiiBase', 'autoload'));
            $objectPHPExcel = new PHPExcel();
            //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
            //创建一个读Excel模版的对象
            $objReader = PHPExcel_IOFactory::createReader ('Excel2007');
            $objPHPExcel = $objReader->load("./template/excel/model_component_template.xlsx");
            //获取当前活动的表
            $objActSheet = $objPHPExcel->getActiveSheet();
            $currentSheet = $objPHPExcel->setActiveSheetIndex(0);
            $index = 5;

            $info = json_decode($info_list,'true');

            $detail_list = ModelQr::QueryQr($program_id);

            $detail['Block'] = 'C';
            $detail['Level'] = 'D';
            $detail['Unit nos.'] = 'E';
            $detail['Part'] = 'F';
            $detail['Unit Type'] = 'G';
            $detail['Serial Number'] = 'H';
            $detail['Element Type'] = 'I';
            $detail['Element Name'] = 'J';
            $detail['Module Type'] = 'K';
            $detail['Precast Plant'] = 'L';
            $local = array(
                'Uuid' =>	'pbu_id',
                'Block' =>	'block',
                'Level' =>	'level',
                'Part' =>	'part',
                'Serial Number' =>	'serial_number',
                'Unit Nos' =>	'unit_nos',
                'Unit Type' =>	'unit_type',
                'Module Type' => 'module_type',
                'Pbu Type' =>	'pbu_type',
                'Pbu Name' =>	'pbu_name',
                'Element Type' =>	'pbu_type',
                'Element Name' =>	'pbu_name',
                'Level/Unit' => 'Level/Unit',
            );

            foreach($info as $i => $params){
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

                $tag = 0;
                $model_name = $params['model'];
                $name = $params['name'];
                $level = $params['level'];
                if($params['block']){
                    $block = $params['block'];
                }else{
                    $block = '';
                }
                if($params['unit_nos']){
                    $unit_nos = $params['unit_nos'];
                }else{
                    $unit_nos = '';
                }
                if($params['unit_type']){
                    $unit_type = $params['unit_type'];
                }else{
                    $unit_type = '';
                }
                if($params['element_type']){
                    $name = $params['element_type'];
                }
                if($params['serial_no']){
                    $serial_no = $params['serial_no'];
                }else{
                    $serial_no = '';
                }
                if($params['element_name']){
                    $pbu_name = $params['element_name'];
                }else{
                    $pbu_name = '';
                }

                if($params['module_type']){
                    $module_type = $params['module_type'];
                }else{
                    $module_type = '';
                }
                if($params['precast_plant']){
                    $precast_plant = $params['precast_plant'];
                }else{
                    $precast_plant = '';
                }

                $model = RevitComponent::model()->find('project_id=:project_id and model_id=:model_id and version=:version and pbu_id=:pbu_id',array(':project_id'=>$program_id,':model_id'=>$model_id,':pbu_id'=>$uuid,':version'=>$version));
                if($model){
                    $tag = 1;
                    $objActSheet->setCellValue('A'.$index,$model->model_id.'_'.$model->version);
                    $objActSheet->setCellValue('B'.$index,$model->pbu_id);
                    $objActSheet->setCellValue('C'.$index,$model->block);
                    $objActSheet->setCellValue('D'.$index,$model->level);
                    $objActSheet->setCellValue('E'.$index,$model->unit_nos);
                    $objActSheet->setCellValue('F'.$index,$model->part);
                    $objActSheet->setCellValue('G'.$index,$model->unit_type);
                    $objActSheet->setCellValue('H'.$index,$model->serial_number);
                    if($model->pbu_type){
                        $objActSheet->setCellValue('I'.$index,$model->pbu_type);
                    }else{
                        $objActSheet->setCellValue('I'.$index,$name);
                    }
                    $objActSheet->setCellValue('J'.$index,$model->pbu_name);
                    $objActSheet->setCellValue('K'.$index,$model->module_type);
                    $objActSheet->setCellValue('L'.$index,$model->precast_plant);
                }
                if($tag == 0){
                    $objActSheet->setCellValue('A'.$index,$model_id.'_'.$version);
                    $objActSheet->setCellValue('B'.$index,$uuid);
                    $objActSheet->setCellValue('C'.$index,$block);
                    $objActSheet->setCellValue('D'.$index,$level);
                    $objActSheet->setCellValue('E'.$index,$unit_nos);
                    $objActSheet->setCellValue('F'.$index,'');
                    $objActSheet->setCellValue('G'.$index,$unit_type);
                    $objActSheet->setCellValue('H'.$index,$serial_no);
                    $objActSheet->setCellValue('I'.$index,$name);
                    $objActSheet->setCellValue('J'.$index,$pbu_name);
                    $objActSheet->setCellValue('K'.$index,$module_type);
                    $objActSheet->setCellValue('L'.$index,$precast_plant);
                }
                foreach ($detail_list as $t => $j){
                    if($detail[$j['name']]){
                        $fixed_val = '';
                        $letter = $detail[$j['name']];
                        if($j['status'] == '1' && $local[$j['fixed']] == ''){
                            if($params_info[$j['fixed']]){
                                $fixed_val = $params_info[$j['fixed']];
                            }else{
                                foreach ($params_info['properties'] as $m => $n){
                                    if($n['key'] == $j['fixed']){
                                        $fixed_val = $n['value'];
                                    }
                                }
                            }
                            $objActSheet->setCellValue($letter.$index,$fixed_val);
                        }else if($j['status'] == '0'){
                            $fixed_val = $j['fixed'];
                            $objActSheet->setCellValue($letter.$index,$fixed_val);
                        }

                    }
                }
                $index++;
            }

//        $objActSheet->setCellValue('A3',$model_id);
//        $objActSheet->setCellValue('B3',$model_name);
//        $objActSheet->setCellValue('C3',$version);
            $redis->delete('model_list');
            $redis->delete('info_list');
            $redis->close();
            //导出
            $filename = 'Model_component_template-'.$model_id;
            header ( 'Content-Type: application/vnd.ms-excel' );
            header ( 'Content-Disposition: attachment;filename="' . $filename . '.xls"' ); //"'.$filename.'.xls"
            header ( 'Cache-Control: max-age=0' );
            $objWriter= PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5'); //在内存中准备一个excel2003文件
            $objWriter->save ( 'php://output' );
            $r['status'] = '1';
            print_r(json_encode($r));
        }else{
            $task['task_name'] = 'test export';
            $task['program_id'] = $_REQUEST['program_id'];
            $result = TaskModel::insertBasic($task);
            $id = $result['id'];
            //后台执行 非阻塞 异步
            exec('php /opt/www-nginx/web/test/idd/protected/yiic model exportpbu --param1='.$id.' --param2='.$program_id.' >/dev/null  &');
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

        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Model';
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
                    $result[] =$r['data'];
                }else{
                    $tag = 1;
                    $result[] =$j;
                }
            }
            if($tag == 0){
                $file_path = ModelQr::downloadPdf($result,$program_id);
            }

            if($tag == 1){
                $file_path = ModelQr::downloadNomodelPdf($result,$program_id);
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
        $rows = RevitComponent::blockByModel($modellist,$program_id);
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
        $rows = RevitComponent::levelByModel($modellist,$program_id);
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
        $rows = RevitComponent::partByModel($modellist,$program_id);
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
        $this->smallHeader = 'ModelQr Template';
        $program_id = $_REQUEST['program_id'];
        $this->layout = '//layouts/main_3';
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
}
