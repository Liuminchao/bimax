<?php

class LocationController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'projlist';
    public $contentHeader = "";
    public $bigMenu = "";
    public $ptype;
    public $title_rows = 1;
    //public $per_read_cnt = 5;

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

    //表头数组
    private static function Exceltitle(){
        return array(
            'A' => array('field'=> 'block','type'=> 'string','array'=> 'location','title_cn' => '座,区域','title_en'=>'Block'),
            'B' => array('field' => 'level','type'=> 'string','array'=> 'location','title_cn' => '座,类型','title_en'=>'Level'),
            'C' => array('field' => 'unit','type'=> 'string','array'=> 'location','title_cn' => '楼层','title_en'=>'Unit'),
            'D' => array('field' => 'doc_name','type'=> 'string','array'=> 'location','title_cn' => '文件名称','title_en'=>'Doc Name'),
            'E' => array('field' => 'doc_id','type'=> 'string','array'=> 'location','title_cn' => '文件连接','title_en'=>'Doc Url'),
        );
    }

    public function actionView() {
        $project_id = $_REQUEST['id'];
        $this->renderPartial("batch",array('project_id'=>$project_id));
    }

    public function actionDownload()
    {
        $file_name = "./template/excel/Project Area.xls";
        $file = fopen($file_name, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_name));
        header('Content-Transfer-Encoding: binary');
//        $name = "员工信息表导入模版".date('YmdHis').".xls";
        if (Yii::app()->language == 'zh_CN') {
            $name = "项目位置导入模版.xls";
        }else{
            $name = "BatchingTemplate.xls";
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

                if($rowKey[$colIndex]['array']=='location'){
                    $location[$key] = $cell;

                }

            }
            //var_dump($staff);var_dump($staffinfo);
            if(!empty($location)){
                $rs[$rowIndex] = ProgramLocation::InsertLocation($location,$project_id);
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
     * 项目区域表头
     * @return SimpleGrid
     */
    private function genLocationGrid($project_id) {
        $company_id = Yii::app()->user->getState('company_id');
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/location/locationgrid&project_id='.$project_id;
//        $t->url = 'index.php?r=proj/project/grid&ptype='.Yii::app()->session['project_type'];
        $ptype = Yii::app()->session['project_type'];
        $t->updateDom = 'datagrid';
//        $t->set_header(Yii::t('proj_project', 'type'), '', 'center');
//        $t->set_header(Yii::t('proj_project', 'value'), '', 'center');
        $t->set_header('Block', '', 'center');
//        $t->set_header(Yii::t('proj_project', 'block_type'), '', 'center');
        $t->set_header('Level', '', 'center');
        $t->set_header('Unit No.','','center');
        $t->set_header('Floor Type','','center');
        $t->set_header('Drawings','','center');
        $t->set_header(Yii::t('common', 'action'), '20%', 'center');
        return $t;
    }

    /**
     * 项目区域查询
     */
    public function actionLocationGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($page);

//        if($args['project_id'] == '')
//            $args['project_id'] = Yii::app()->session['project_id'];
        if($fields[0]!=''){
            $args['project_id'] = $fields[0];
        }
        if($_GET['project_id']){
            $args['project_id'] = $_GET['project_id'];
        }

        $t = $this->genLocationGrid($args['project_id']);
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($args);
//        exit;
        $list = ProgramLocation::queryList($page, $this->pageSize, $args);

        $this->renderPartial('location_list', array('t' => $t, 'project_id'=>$args['project_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 项目区域列表
     */
    public function actionLocationList() {
        $project_id = $_GET['program_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
        }
        if($_REQUEST['block']){
            $block = $_REQUEST['block'];
        }else{
            $block_list = ProgramRegion::locationAllBlock($project_id);
            if(count($block_list)>0){
                $block = $block_list[0];
            }else{
                $block = '';
            }
        }
//        var_dump($ptype);
//        exit;
        $this->smallHeader = 'Defect Location';
        $this->render('locationlist',array('project_id'=>$project_id, 'args'=>$args,'block'=>$block));
    }
    /**
     * 项目区域编辑页面展示
     */
    public function actionEditLocation() {
        $id = $_REQUEST['id'];
        $project_id = $_REQUEST['project_id'];
        $location_model = ProgramLocation::model()->findByPk($id);
        $this->renderPartial('location_form', array('model' => $location_model,'id'=>$id,'project_id'=>$project_id));
    }
    /*
     * 设置区域
     */
    public function actionSetLocation(){
        $args = $_REQUEST['location'];
        $r = ProgramLocation::EditLocation($args);
        print_r(json_encode($r));
    }
    /*
     * 注销区域
     */
    public function actionDelLocation(){
        $id = $_REQUEST['id'];
        $r = ProgramLocation::DelLocation($id);
        print_r(json_encode($r));
    }

    /*
     * 设置区域文档
     */
    public function actionSetLocationDoc(){
        $id = $_REQUEST['id'];
        $doc_id = $_REQUEST['doc_id'];
        $r = ProgramLocation::EditDoc($id,$doc_id);
        print_r(json_encode($r));
    }

    /*
     * 注销区域
     */
    public function actionSetDoc(){
        $id = $_REQUEST['id'];
        $doc_id = $_REQUEST['doc_id'];
        $doc_name = $_REQUEST['doc_name'];
        $r = ProgramLocation::EditDoc($id,$doc_id,$doc_name);
        print_r(json_encode($r));
    }

    /**
     * 同步数据
     */
    public function actionSyncData(){
        $id = $_REQUEST['id'];
        $r = ProgramLocation::SyncData($id);
        print_r(json_encode($r));
    }

    public function actionUploadFile() {
        $project_id = $_REQUEST['project_id'];
        $id = $_REQUEST['id'];
        $location_model = ProgramLocation::model()->findByPk($id);
        $doc_id = $location_model->doc_id;
        $this->renderPartial('block_upload', array('project_id' => $project_id,'id'=>$id,'doc_id'=>$doc_id));
    }

    public function actionSaveDrawing(){
        $drawing_id = $_REQUEST['drawing_id'];
        $id = $_REQUEST['id'];
        $r = ProgramLocation::SaveDrawing($id,$drawing_id);
        print_r(json_encode($r));
    }

    public function actionChangePart() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $this->renderPartial('change_part', array('block' => $block,'project_id'=>$project_id));
    }

    public function actionAssign() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $tag = $_REQUEST['tag'];
        $this->renderPartial('assign', array('tag'=>$tag,'block' => $block,'project_id'=>$project_id));
    }

    public function actionSavePart() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $level = $_REQUEST['level'];
        $r = ProgramLocation::SetType($project_id,$block,$level);
        print_r(json_encode($r));
    }

    public function actionChangeUnit() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $this->renderPartial('change_unit', array('block' => $block,'project_id'=>$project_id));
    }

    public function actionAddLocation() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $this->renderPartial('add_form', array('block' => $block,'project_id'=>$project_id));
    }

    public function actionSaveLocation() {
        $location = $_REQUEST['location'];
        $r = ProgramRegion::saveLocation($location);
        print_r(json_encode($r));
    }

    public function actionSaveUnit() {
        $project_id = $_REQUEST['project'];
        $block = $_REQUEST['block'];
        $unit_list = $_REQUEST['Unit'];
        $unit_old_list = $_REQUEST['Unit_old'];
        $r = ProgramRegion::SetUnit($project_id,$block,$unit_list,$unit_old_list);
        print_r(json_encode($r));
    }

    public function actionSaveLevelDraw() {
        $level = $_REQUEST['Level'];
        $project_id = $_REQUEST['project_id'];
        $block = $_REQUEST['block'];
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $level_list = json_encode($level);
        $redis->set('level_list',$level_list);
        $redis->close();
//        $r = ProgramRegion::saveLevelDraw($project_id,$block,$level);
        //后台执行 非阻塞 异步
        $block = urlencode($block);
        exec('php /opt/www-nginx/web/test/bimax/protected/yiic location saveleveldraw --param1='.$project_id.' --param2='.$block.' >/dev/null  &');
        $r['status'] = '1';
        $r['msg'] = Yii::t('common','success_update');
        echo json_encode($r);
    }

    public function actionSaveUnitDraw() {
        $unit = $_REQUEST['Unit'];
        $project_id = $_REQUEST['project_id'];
        $block = $_REQUEST['block'];
        $r = ProgramRegion::saveUnitDraw($project_id,$block,$unit);
        echo json_encode($r);
    }

    public function actionUnitList() {
        $project_id = $_REQUEST['project_id'];
        $block = $_REQUEST['block'];
        $r = ProgramRegion::locationUnit($project_id,$block);
        echo json_encode($r);
    }

    public function actionLevelList() {
        $project_id = $_REQUEST['project_id'];
        $block = $_REQUEST['block'];
        $r = ProgramRegion::locationLevel($project_id,$block);
        echo json_encode($r);
    }

    public function actionUploadList() {
        $block = $_REQUEST['block'];
        $project_id = $_REQUEST['project_id'];
        $tag = $_REQUEST['tag'];
        $this->smallHeader = 'Block '.$block;
        $level_draw_list = ProgramRegion::levelDrawList($project_id,$block);
        $unit_draw_list = ProgramRegion::unitDrawList($project_id,$block);
        $this->render('upload_list', array('block' => $block,'project_id'=>$project_id,'tag'=>$tag,'level_draw_list'=>$level_draw_list,'unit_draw_list'=>$unit_draw_list));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['proj/location/locationlist'] = str_replace("r=proj/location/locationgrid", "r=proj/location/locationlist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
}
