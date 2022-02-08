<?php

class DefectController extends AuthBaseController {

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

    //表头数组
    private static function Exceltitle(){
        return array(
            'A' => array('field'=> 'type_1','type'=> 'string','array'=> 'defect','title_cn' => 'Component Group','title_en'=>'Component Group'),
            'B' => array('field' => 'type_2','type'=> 'string','array'=> 'defect','title_cn' => 'Proposed Component 
(It should follow their respective component group)','title_en'=>'Proposed Component 
(It should follow their respective component group)'),
            'C' => array('field' => 'type_3','type'=> 'string','array'=> 'defect','title_cn' => 'Description Of Defects','title_en'=>'Description Of Defects'),
            'D' => array('field' => 'user_id','type'=> 'string','array'=> 'defect','title_cn' => 'Person in charge','title_en'=>'Person in charge'),
        );
    }

    public function actionView() {
        $project_id = $_REQUEST['id'];
        $this->renderPartial("batch",array('project_id'=>$project_id));
    }

    public function actionDownload()
    {
        $file_name = "./template/excel/DefectType.xls";
        $file = fopen($file_name, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_name));
        header('Content-Transfer-Encoding: binary');
//        $name = "员工信息表导入模版".date('YmdHis').".xls";
        if (Yii::app()->language == 'zh_CN') {
            $name = "DefectTypeTemplate.xls";
        }else{
            $name = "DefectTypeTemplate.xls";
        }

        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($file_name));
        fclose($file);
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
        $conid = Yii::app()->user->getState('company_id');
        $dir = Yii::app()->params['upload_file_path'].'/'.tmp.'/'.$conid;

        //上传Excel
        $file_rs = UploadFiles::fileUpload($file,array("xls","xlsx"), $dir);

        if($file_rs['status']==-1){
            $rs['status'] = $file_rs['status'];
            $rs['msg'] = $file_rs['desc'];
            print_r(json_encode($rs));
            exit();
        }

        $filename = $file_rs['path'];
        chmod($filename, 0777);
        //读取excel
//        var_dump($filename);
//        exit;
        $rs = $this->ReadExcel($filename);
        if(!is_object($rs)){
            print_r(json_encode($rs));
            exit();
        }

        $objPHPExcel = $rs;
        $currentSheet = $objPHPExcel->setActiveSheetIndex(0);

        //取得一共有多少行
        $rowCount = $currentSheet->getHighestRow();

        $rs = array('filename'=>$filename, 'rowcnt'=>$rowCount-$this->title_rows);
//        var_dump($rs);
//        exit;
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

    //读取文件
    public function actionReaddata(){

        $filename = $_REQUEST['filename'];
        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $project_id = $_REQUEST['id'];

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
        //行号从6开始，列号从A开始
        $row = $startrow+$this->title_rows;
        for($rowIndex=$row; $rowIndex<$row+$per_read_cnt; $rowIndex++){
            $location = array();
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

                if($rowKey[$colIndex]['array']=='defect'){
                    $defect[$key] = $cell;

                }

            }
            //var_dump($staff);var_dump($staffinfo);
            if(!empty($defect)){
                $rs[$rowIndex] = QaDefectProjectType::InsertType($defect,$project_id);
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


    /**
     * 表头
     * @return SimpleGrid
     */
    private function genTypeGrid($project_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/defect/typegrid&project_id='.$project_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Component Group', '', 'center');
        $t->set_header('Defect Category', '', 'center');
//        $t->set_header('Defect Description', '', 'center');
//        $t->set_header('In Charge(Company:User)', '', 'left');
        $t->set_header(Yii::t('comp_qa', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '10%', 'center');
        return $t;
    }

    /**
     * 类型查询
     */
    public function actionTypeGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码

        //        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        if($args['project_id'] == '')
//            $args['project_id'] = Yii::app()->session['project_id'];
        if($fields[0]!=''){
            $args['project_id'] = $fields[0];
        }
        if($fields[1]!=''){
            $args['type_1'] = $fields[1];
        }
        if($_GET['project_id']){
            $args['project_id'] = $_GET['project_id'];
        }

        $t = $this->genTypeGrid($args['project_id']);
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($args);
//        exit;
        $list = QaDefectProjectType::queryList($page, $this->pageSize, $args);

        $this->renderPartial('type_list', array('t' => $t, 'project_id'=>$args['project_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }


    /**
     * 列表
     */
    public function actionTypeList() {
        $program_id = $_REQUEST['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        if($_REQUEST['type_id']){
            $type_id = $_REQUEST['type_id'];
        }else{
            $type_id = 'Completion';
        }
        $this->smallHeader = 'Defect Selection';
        $this->render('typelist',array('project_id'=>$program_id,'args'=>$args,'type_id'=>$type_id));
    }

    /**
     * 添加
     */
    public function actionEditUser() {
        $this->smallHeader = 'Defect Selection';
        $id = $_REQUEST['id'];
        $project_id = $_REQUEST['project_id'];
        $this->render('method_statement',array('program_id'=>$project_id,'id'=>$id));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionSaveMethod() {

        $json = $_REQUEST['json_data'];
        $id = $_REQUEST['id'];
        $r = QaDefectProjectType::EditUser($id,$json);
        echo json_encode($r);
    }

    /**
     * 项目区域编辑页面展示
     */
    public function actionEditType() {
        $id = $_REQUEST['id'];
        $project_id = $_REQUEST['project_id'];
        $defect_model = QaDefectProjectType::model()->findByPk($id);
        $this->renderPartial('type_form', array('model' => $defect_model,'id'=>$id,'project_id'=>$project_id));
    }
    /*
     * 设置区域
     */
    public function actionSetType(){
        $args = $_REQUEST['defect'];
        $r = QaDefectProjectType::EditType($args);
        print_r(json_encode($r));
    }
    /*
     * 注销区域
     */
    public function actionDelType(){
        $id = $_REQUEST['id'];
        $r = QaDefectProjectType::DelType($id);
        print_r(json_encode($r));
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
