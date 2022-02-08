<?php

class ImportController extends AuthBaseController {

    public $title_rows = 1;
    //public $per_read_cnt = 5;
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
    }

    //表头数组
    private static function Exceltitle(){
        return array(
            'A' => array('field' => 'pbu_id','type'=> 'string','array'=> 'task','title_cn' => 'Pbu Name','title_en'=>'Pbu Name'),
            'B' => array('field'=> 'phase','type'=> 'string','array'=> 'task','title_cn' => 'Phase','title_en'=>'Phase'),
            'C' => array('field' => 'stage_id','type'=> 'string','array'=> 'task','title_cn' => 'Stage Name','title_en'=>'Stage Name'),
            'D' => array('field' => 'task_id','type'=> 'string','array'=> 'task','title_cn' => 'Task Name','title_en'=>'Task Name'),
            'E' => array('field' => 'start_date','type'=> 'date','array'=> 'task','title_cn' => 'Start Date','title_en'=>'Start Date'),
            'F' => array('field' => 'end_date','type'=> 'date','array'=> 'task','title_cn' =>'End Date','title_en'=>'End Date'),
            'G' => array('field' => 'attach','type'=> 'string','array'=> 'task','title_cn' =>'Attachment','title_en'=>'Attachment'),
            'H' => array('field' => 'attach_name','type'=> 'string','array'=> 'task','title_cn' =>'Attachment Name','title_en'=>'Attachment Name'),
            'I' => array('field' => 'attach_id','type'=> 'string','array'=> 'task','title_cn' =>'Attachment Id','title_en'=>'Attachment Id'),
            'J' => array('field' => 'record','type'=> 'string','array'=> 'task','title_cn' =>'Record','title_en'=>'Record'),
            'K' => array('field' => 'na_flag','type'=> 'string','array'=> 'task','title_cn' =>'Na Flag','title_en'=>'Na Flag'),
        );
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/import/grid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Form Id', '', '');
        $t->set_header('Form Name', '', '');
        $t->set_header('Type Name', '', '');
        $t->set_header(Yii::t('sys_role', 'status'), '', '');
        $t->set_header(Yii::t('common', 'action'), '15%', '');
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
        $t = $this->genDataGrid($fields[0]);
        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = QaChecklist::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('program_id' => $fields[0], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'QaChecklist';
        $this->render('list',array('program_id'=> $program_id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDetailDataGrid($form_id,$program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/import/detailgrid&form_id='.$form_id.'&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Item Id', '', '');
        $t->set_header('Order Id', '', '');
        $t->set_header('Item Title En', '', '');
        $t->set_header('Group Name', '', '');
        $t->set_header('Item Type', '', '');
        $t->set_header('Item Data', '', '');
        $t->set_header(Yii::t('sys_role', 'status'), '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionDetailGrid() {
        $fields = func_get_args();
        if($_REQUEST['form_id']){
            $fields[0] = $_REQUEST['form_id'];
        }
        if($_REQUEST['program_id']){
            $fields[1] = $_REQUEST['program_id'];
        }
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $args['form_id'] = $fields[0];
        $t = $this->genDetailDataGrid($fields[0],$fields[1]);
        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = QaForm::queryList($page, $this->pageSize, $args);
        $this->renderPartial('detail_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionDetailList() {
        $form_id = $_REQUEST['form_id'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'QaChecklist';
        $this->render('detaillist',array('form_id'=> $form_id,'program_id'=>$program_id));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['mcrole/list'] = str_replace("r=comp/role/grid", "r=comp/role/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    public function actionView() {
        $program_id =  $_REQUEST['program_id'];
        $this->render("batch", array('program_id'=>$program_id));
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
        $project_id = $_REQUEST['project_id'];

        $rs = $this->ReadExcel($filename);
        if(!is_object($rs)){
            print_r(json_encode($rs));
            exit();
        }

        $objPHPExcel = $rs;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //取得一共有多少行
        $rowCount = $currentSheet ->getHighestRow();
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
        $row = $startrow+$this->title_rows;
        for($rowIndex=$row; $rowIndex<$row+$per_read_cnt; $rowIndex++){
            $task = array();
            for($colIndex='A';$colIndex != $highestColumn;$colIndex++){
                //获得字段值
                $addr = $colIndex.$rowIndex;
                $cell = $currentSheet->getCell($addr)->getValue();
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

                if($rowKey[$colIndex]['array']=='task'){
                    $task[$key] = $cell;

                }
            }
            if(!empty($task)){
                if(!array_key_exists('is_required',$task)){
                    $task['is_required'] = '0';
                }
                $task['project_id'] = $project_id;
//                if($task['record'] == '1'){
                    $rs[$rowIndex] = TaskRecord::uploadRecord($task);
//                }
//                $rs[$rowIndex] = Yii::t('common','success_insert');
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
    //验证日期格式
    function is_Date($str){
        $format='Y-m-d';
        $unixTime_1=strtotime($str);
        if(!is_numeric($unixTime_1)) return false; //如果不是数字格式，则直接返回
        $checkDate=date($format,$unixTime_1);
        $unixTime_2=strtotime($checkDate);
        if($unixTime_1==$unixTime_2){
            return true;
        }else{
            return false;
        }
    }

    //读取文件
        public function actionPbuinfo(){
        $type =  $_REQUEST['type'];
        $model_id = $_REQUEST['model_id'];
        $version = $_REQUEST['version'];
        $uuid = $_REQUEST['uuid'];
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial("pbu_info", array('type'=>$type,'model_id'=>$model_id,'version'=>$version,'guid'=>$uuid,'program_id'=>$program_id));
    }

    /**
     * 查询模板
     */
    public function actionQueryTemplate() {
        $program_id = $_POST['program_id'];

        $rows = TaskTemplate::templateByProgram($program_id);

        print_r(json_encode($rows));
    }

    /**
     * 查询导出模板
     */
    public function actionQueryExportTemplate() {
        $program_id = $_POST['program_id'];

        $rows = PbuExport::templateByProgram($program_id);

        print_r(json_encode($rows));
    }

    /**
     * 查询模板详情
     */
    public function actionQueryExportDetail() {
        $export_id = $_POST['export_id'];

        $rows = PbuExportDetail::detailList($export_id);

        print_r(json_encode($rows));
    }

    /**
     * 查询阶段
     */
    public function actionQueryStage() {
        $template_id = $_POST['template_id'];

        $rows = TaskStage::queryStage($template_id);

        print_r(json_encode($rows));
    }

    /**
     * 保存导出模板
     */
    public function actionSaveExport() {

        $export_args = $_REQUEST['Export'];
        $pro_args = $_REQUEST['Program'];
        $rows = PbuExport::SaveExport($export_args,$pro_args);
        print_r(json_encode($rows));
    }

    /**
     * 删除导出模板
     */
    public function actionDelExport() {

        $export_id = $_REQUEST['template_id'];
        $rows = PbuExport::DelExport($export_id);
        print_r(json_encode($rows));
    }
}
