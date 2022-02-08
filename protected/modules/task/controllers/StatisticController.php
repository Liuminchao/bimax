<?php
class StatisticController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";

    public function init() {
        parent::init();
        $this->contentHeader = 'Task';
        $this->bigMenu = 'Task Template';
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataBlockGrid($args) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/statistic/blockgrid';
        $t->updateDom = 'datagrid';
        $header_list =StatisticChecklist::headerCheckList($args);

        if(count($header_list)>0){
            $t->set_header('Block No', '', 'center','','','2');
            $t->set_header('Total No.', '', 'center','','','2');
            foreach ($header_list as $i => $group){
//            if($i == 'C'){
//                $group_name = 'Carcass';
//            }else if($i == 'B'){
//                $group_name = 'Fitting-out';
//            }else if($i == 'A'){
//                $group_name = 'Site';
//            }
//            $cnt = count($group);
//            $t->set_compound_header($group_name, '', 'center','',$cnt,'');
                foreach($group as $m => $n){
                    $t->set_header($n['stage_name'], '', 'center');
                }
            }
            $t->set_header('Percentage<br>(Carcass)', '', 'center','','','2');
            $t->set_header('Percentage<br>(Fitting out)', '', 'center','','','2');
            $t->set_header('Percentage<br>(Installed)', '', 'center','','','2');
        }else{
            $t->set_header('Block No', '', 'center','','','2');
            $t->set_header('Total No.', '', 'center','','','2');
            $t->set_header('Percentage<br>(Carcass)', '', 'center','','','2');
            $t->set_header('Percentage<br>(Fitting out)', '', 'center','','','2');
            $t->set_header('Percentage<br>(Installed)', '', 'center','','','2');
        }
        return $t;
    }

    /**
     * 查询
     */
    public function actionBlockGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 1 && $fields[0] != null ) {
            $args['project_id'] = $fields[0];
        }
        $t = $this->genDataBlockGrid($args);
        $this->saveUrl();
        $list = StatisticChecklist::queryBlockList2($page, $this->pageSize, $args);

        $this->renderPartial('block_list', array('t' => $t,'args'=>$args, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionBlockList() {

        $program_id = $_REQUEST['program_id'];
        $this->render('blocklist',array('program_id'=>$program_id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataDateGrid($args) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/statistic/dategrid';
        $t->updateDom = 'datagrid';
        $header_list =StatisticChecklist::headerDate($args);
        if(count($header_list)>0){
            $t->set_header('', '', 'center');
            foreach ($header_list as $i => $group){
                $t->set_header($group['date'], '', 'center');
            }
        }else{
            $t->set_header('Task Stage', '', 'center');
            $t->set_header('Date', '', 'center');
        }

        return $t;
    }

    /**
     * 查询
     */
    public function actionDateGrid() {

        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if($args == NULL){
            if(count($fields) == 1 && $fields[0] != null ) {
                $args['program_id'] = $fields[0];
            }
            $i = 0;
            $template_list = TaskTemplate::templateByProgram($args['program_id']);
            foreach($template_list as $template_id => $template_name){
                if($i == 0){
                    $args['template_id'] = $template_id;
                }
                $i++;
            }
        }

        $t = $this->genDataDateGrid($args);
        $this->saveUrl();
        $list = StatisticChecklist::queryDateList2($page, $this->pageSize, $args);
        $this->renderPartial('date_list', array('t' => $t,'args'=>$args, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionDateList() {

        $program_id = $_REQUEST['program_id'];
        $this->render('datelist',array('program_id'=>$program_id));
    }

    /**
     * 业务统计图表
     */
    public function actionChart() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('dboard', 'Business Statistics Graph');
        $this->render('chart',array('program_id'=>$program_id));
    }

    /**
     * 统计图表查询数据
     */
    public function actionDataByMix() {
        $date = $_REQUEST['date'];
        $program_id = $_REQUEST['program_id'];
        $sql = "SELECT template_id,template_name FROM task_template WHERE status=0 and project_id='".$program_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $template_id = $row['template_id'];
            }
        }else{
            $template_id = '';
        }

        $data = array(
            'user' => '18615146587',
            'token' => 'lalala',
            'project_id' => $program_id,
            'template_id' => $template_id,
            'block' => 'ALL',
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $data = json_encode($post_data);

        $module = 'DBProjFaStage';
        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=".$module."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
//        $data = RevitComponent::DataByMix($args);
        print_r($output);
    }

    /**
     * 统计图表查询数据
     */
    public function actionDataByPie() {
        $args['clt_type'] = $_REQUEST['type'];
        $args['date'] = $_REQUEST['date'];
        $args['program_id'] = $_REQUEST['program_id'];
        $data = RevitComponent::DataByPie($args);
        print_r(json_encode($data));
    }

    /**
     * 统计图表查询数据
     */
    public function actionBlockData() {
        $args['date'] = $_REQUEST['date'];
        $args['program_id'] = Yii::app()->session['program_id'];
        $data = RevitComponent::BlockData($args);
        print_r(json_encode($data));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['project/list'] = str_replace("r=proj/project/grid", "r=proj/project/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    public static function actionPbuExport(){
        $args['project_id'] = $_REQUEST['program_id'];
        $args['model_id'] = $_REQUEST['model_id'];
        $args['version'] = $_REQUEST['version'];
        $args['clt_type'] = 'B';
        $data_list = RevitComponent::statusexcel($args);
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ('Excel2007');
        //获取当前活动的表
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Carcass');


        $row_total = count($data_list)+8;
        $pub_row = count($data_list)+9;
        $unit_row = count($data_list)+10;
        foreach ($data_list as $i => $j) {
            $p = 1;
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.$row_total, $j['level']);
            foreach($j['data'] as $m => $n){
                $pbu_cnt = count($n['data']);
                if ($p < 26) {
                    $a = chr(65 + $p);
                    $d = $a . $row_total;
                } else {
                    $a = chr(64 + ($p / 26)) . chr(65 + ($p % 26));
                    $d = $a . $row_total;
                }
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(20);
//                $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$end_key.'1');
                $objectPHPExcel->getActiveSheet()->setCellValue($a.$unit_row, $n['unit']);
                if($n['data'] != ''){
                    foreach($n['data'] as $x => $y){
                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$pub_row, $y['pbu_type']);
                        $pbu_data = $y['data'][0];
                        //#00FF7F
                        if($pbu_data['tag']=='1'){
                            $objectPHPExcel->getActiveSheet()->getStyle($a.$row_total)->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($pbu_data['color']);
                        }
//                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$row_total, $pbu_data['pbu_id']);
                    }
                }
                $p++;
            }
            $row_total--;
        }

        //创建一个新的工作空间(sheet)
        $objectPHPExcel->createSheet();
        $objectPHPExcel->setactivesheetindex(1);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Fitting out');
        $args['clt_type'] = 'D';
        $data_list = RevitComponent::statusexcel($args);

        $row_total = count($data_list)+8;
        $pub_row = count($data_list)+9;
        $unit_row = count($data_list)+10;
        foreach ($data_list as $i => $j) {
            $p = 1;
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.$row_total, $j['level']);
            foreach($j['data'] as $m => $n){
                $pbu_cnt = count($n['data']);
                if ($p < 26) {
                    $a = chr(65 + $p);
                    $d = $a . $row_total;
                } else {
                    $a = chr(64 + ($p / 26)) . chr(65 + ($p % 26));
                    $d = $a . $row_total;
                }
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(20);
//                $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$end_key.'1');
                $objectPHPExcel->getActiveSheet()->setCellValue($a.$unit_row, $n['unit']);
                if($n['data'] != ''){
                    foreach($n['data'] as $x => $y){
                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$pub_row, $y['pbu_type']);
                        $pbu_data = $y['data'][0];
                        //#00FF7F
                        if($pbu_data['tag']=='1'){
                            $objectPHPExcel->getActiveSheet()->getStyle($a.$row_total)->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($pbu_data['color']);
                        }
//                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$row_total, $pbu_data['pbu_id']);
                    }
                }
                $p++;
            }
            $row_total--;
        }

        //创建一个新的工作空间(sheet)
        $objectPHPExcel->createSheet();
        $objectPHPExcel->setactivesheetindex(2);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Site');
        $args['clt_type'] = 'E';
        $data_list = RevitComponent::statusexcel($args);

        $row_total = count($data_list)+8;
        $pub_row = count($data_list)+9;
        $unit_row = count($data_list)+10;
        foreach ($data_list as $i => $j) {
            $p = 1;
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.$row_total, $j['level']);
            foreach($j['data'] as $m => $n){
                $pbu_cnt = count($n['data']);
                if ($p < 26) {
                    $a = chr(65 + $p);
                    $d = $a . $row_total;
                } else {
                    $a = chr(64 + ($p / 26)) . chr(65 + ($p % 26));
                    $d = $a . $row_total;
                }
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(20);
//                $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$end_key.'1');
                $objectPHPExcel->getActiveSheet()->setCellValue($a.$unit_row, $n['unit']);
                if($n['data'] != ''){
                    foreach($n['data'] as $x => $y){
                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$pub_row, $y['pbu_type']);
                        $pbu_data = $y['data'][0];
                        //#00FF7F
                        if($pbu_data['tag']=='1'){
                            $objectPHPExcel->getActiveSheet()->getStyle($a.$row_total)->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($pbu_data['color']);
                        }
//                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$row_total, $pbu_data['pbu_id']);
                    }
                }
                $p++;
            }
            $row_total--;
        }

        //创建一个新的工作空间(sheet)
        $objectPHPExcel->createSheet();
        $objectPHPExcel->setactivesheetindex(3);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Installed');
        $args['clt_type'] = 'F';
        $data_list = RevitComponent::statusexcel($args);

        $row_total = count($data_list)+8;
        $pub_row = count($data_list)+9;
        $unit_row = count($data_list)+10;
        foreach ($data_list as $i => $j) {
            $p = 1;
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.$row_total, $j['level']);
            foreach($j['data'] as $m => $n){
                $pbu_cnt = count($n['data']);
                if ($p < 26) {
                    $a = chr(65 + $p);
                    $d = $a . $row_total;
                } else {
                    $a = chr(64 + ($p / 26)) . chr(65 + ($p % 26));
                    $d = $a . $row_total;
                }
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(20);
//                $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$end_key.'1');
                $objectPHPExcel->getActiveSheet()->setCellValue($a.$unit_row, $n['unit']);
                if($n['data'] != ''){
                    foreach($n['data'] as $x => $y){
                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$pub_row, $y['pbu_type']);
                        $pbu_data = $y['data'][0];
                        //#00FF7F
                        if($pbu_data['tag']=='1'){
                            $objectPHPExcel->getActiveSheet()->getStyle($a.$row_total)->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($pbu_data['color']);
                        }
//                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$row_total, $pbu_data['pbu_id']);
                    }
                }
                $p++;
            }
            $row_total--;
        }

        //创建一个新的工作空间(sheet)
        $objectPHPExcel->createSheet();
        $objectPHPExcel->setactivesheetindex(4);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('All');
            $args['clt_type'] = '';
        $data_list = RevitComponent::statusexcel($args);

        $row_total = count($data_list)+8;
        $pub_row = count($data_list)+9;
        $unit_row = count($data_list)+10;
        foreach ($data_list as $i => $j) {
            $p = 1;
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.$row_total, $j['level']);
            foreach($j['data'] as $m => $n){
                $pbu_cnt = count($n['data']);
                if ($p < 26) {
                    $a = chr(65 + $p);
                    $d = $a . $row_total;
                } else {
                    $a = chr(64 + ($p / 26)) . chr(65 + ($p % 26));
                    $d = $a . $row_total;
                }
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(20);
//                $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$end_key.'1');
                $objectPHPExcel->getActiveSheet()->setCellValue($a.$unit_row, $n['unit']);
                if($n['data'] != ''){
                    foreach($n['data'] as $x => $y){
                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$pub_row, $y['pbu_type']);
                        $pbu_data = $y['data'][0];
                        //#00FF7F
                        if($pbu_data['tag']=='1'){
                            $objectPHPExcel->getActiveSheet()->getStyle($a.$row_total)->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($pbu_data['color']);
                        }
//                        $objectPHPExcel->getActiveSheet()->setCellValue($a.$row_total, $pbu_data['pbu_id']);
                    }
                }
                $p++;
            }
            $row_total--;
        }

        //导出
        $filename = 'Block Checklist Statistic';
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objectPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save( 'php://output' );
    }

    public static function actionDateExport(){
        $args = $_REQUEST['q'];
        $header_list =StatisticChecklist::headerDate($args);
        $data_list = StatisticChecklist::queryAllDateList($args);
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        //PHPExcel支持读模版 所以我还是比较喜欢先做好一个Excel的模版  比较好，不然要写很多代码  模版我放在根目录了
        //创建一个读Excel模版的对象
        $objReader = PHPExcel_IOFactory::createReader ( 'Excel2007' );
        $objPHPExcel = new PHPExcel();
        //获取当前活动的表
        $objActSheet = $objPHPExcel->getActiveSheet ();
        $objActSheet->setTitle ( 'Date Checklist Statistic' );

        $header_index = 3;
        $col_index_1 = 2;
        $col_index_2 = 3;
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getRowDimension($col_index_1)->setRowHeight(31.5);
        foreach ($header_list as $i => $group){
            if($header_index < 26){
                $header = chr(65+$header_index);
            }else{
                $x = $header_index / 26;
                $header = chr(64 + $x).chr($header_index%26 + 65);
            }
            $objActSheet->setCellValue($header.$col_index_1,$group['date']);
            $objPHPExcel->getActiveSheet()->getColumnDimension($header)->setWidth(15);
            $header_index++;
        }
        foreach ($data_list as $m => $n){
            $objActSheet->setCellValue('C'.$col_index_2,$n['stage_name']);
            $data_index = 3;
            foreach($header_list as $x =>$y){
                if($data_index < 26){
                    $data_header = chr(65+$data_index);
                }else{
                    $x = $data_index / 26;
                    $data_header = chr(64 + $x).chr($data_index%26 + 65);
                }
                $objActSheet->setCellValue($data_header.$col_index_2,$n[$y['date']]);
                $data_index++;
            }
            $col_index_2++;
        }
        //导出
        $filename = 'Date Checklist Statistic';
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save( 'php://output' );
    }

    public function actionBlockChart(){
        $program_id = $_REQUEST['program_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        if($root_proid == '1627' || $root_proid == '2310'){
//            $url = "https://shell.cmstech.sg/hvblchart/index.html#/?proj_id=".$program_id;
              $url = "https://shell.cmstech.sg/blchart/index.html#/hv?proj_id=".$root_proid;
        }else{
            $url = "https://shell.cmstech.sg/blchart/index.html#/?proj_id=".$program_id;
        }
        $this->smallHeader = 'Block Chart';
        $this->renderPartial('blockchart',array('program_id'=>$program_id,'url'=>$url));
    }

    public function actionBlockPie(){
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Block Pie';
        $this->renderPartial('blockpie',array('program_id'=>$program_id));
    }


}
