<?php
class StatisticController extends BaseController {

    public $defaultAction = 'list';
    public $gridId_1 = 'example1';
    public $gridId_2 = 'example1';
    public $gridId_3 = 'example1';
    public $gridId = 'example2';
    public $url = '';
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

        $data = array(
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $args['template_id']
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $data = $rs['result']['data'];
        $stage_list = array();
        if(count($data)>0){
            foreach($data as $index => $value){
                if($index == 0){
                    $stage = $value['stage'];
                    foreach($stage as $i => $j){
                        if($j['stage_id'] != ''){
                            $stage_list[$j['stage_id']] = $j['stage_name'];
                        }
                    }
                }
            }
        }


        if(count($stage_list)>0){
            $t->set_header('Block No', '', 'center','','','2');
            $t->set_header('Total No.', '', 'center','','','2');
            foreach ($stage_list as $stage_id => $stage_name){
                $t->set_header($stage_name, '', 'center');
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
            $args['program_id'] = $fields[0];
        }
        $i = 0;
        $template_list = TaskTemplate::templateByProgram($args['program_id']);
        if(count($template_list)>0){
            foreach($template_list as $template_id => $template_name){
                if($i == 0){
                    if(!array_key_exists('template_id',$args)){
                        $args['template_id'] = $template_id;
                    }
                }
                $i++;
            }
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
        $this->renderPartial('blocklist',array('program_id'=>$program_id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataDateGrid($args) {
        $t = new DataGrid($this->gridId_1);
        $t->url = 'index.php?r=qa/statistic/dategrid';
        $t->updateDom = 'datagrid_1';
//        $header_list =StatisticChecklist::headerDate($args);
        $data = array(
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $args['template_id']
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones_T2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $data = $rs['result']['stage_list'];
        $month_list = array();
        if(count($data)>0){
            foreach($data as $index => $value){
                if($index == 0){
                    foreach($value['month_cnt'] as $x => $y){
                        $month_list[] = $y['month'];
                    }
                }
            }
        }

        if(count($month_list)>0){
            $t->set_header('Milestones', '', 'center');
            foreach ($month_list as $i => $date){
                $t->set_header($date, '', 'center');
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

        if(count($fields) == 1 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
        }

        $i = 0;
        $template_list = TaskTemplate::templateByProgram($args['program_id']);
        if(count($template_list)>0){
            foreach($template_list as $template_id => $template_name){
                if($i == 0){
                    if(!array_key_exists('template_id',$args)){
                        $args['template_id'] = $template_id;
                    }
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
     * 表头
     * @return SimpleGrid
     */
    private function genDataModuletypeGrid($args) {
        $t = new DataGrid($this->gridId_2);
        $t->url = 'index.php?r=qa/statistic/moduletypegrid';
        $t->updateDom = 'datagrid_2';
        $t->set_header('Block No.', '', 'center');
        $t->set_header('Total No.', '', 'center');
        $t->set_header('Vertical', '', 'center');
        $t->set_header('Horizontal', '', 'center');
        $t->set_header('Vertical(%)', '', 'center');
        $t->set_header('Horizontal(%)', '', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionModuletypeGrid() {

        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if(count($fields) == 1 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
        }

        $t = $this->genDataModuletypeGrid($args);
        $this->saveUrl();
        $list = StatisticChecklist::queryModuletypeList($page, $this->pageSize, $args);

        $this->renderPartial('moduletype_list', array('t' => $t,'args'=>$args, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionModuletypeList() {

        $program_id = $_REQUEST['program_id'];
        $this->render('moduletypelist',array('program_id'=>$program_id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataTaskcntGrid($project_id) {
        $t = new DataGrid($this->gridId_2);
        $t->url = 'index.php?r=qa/statistic/taskcntgrid';
        $t->updateDom = 'datagrid_3';
        $block_sql = "select block
                      from pbu_info
                      where project_id = '".$project_id."' and status = '0'
                      group by block
                      order by block";
        $command = Yii::app()->db->createCommand($block_sql);
        $block_rows = $command->queryAll();
        $t->set_header('Name', '', 'center');
        if(count($block_rows)>0){
            foreach($block_rows as $i => $j){
                $t->set_header($j['block'], '', 'center');
            }
        }
        return $t;
    }

    /**
     * 查询
     */
    public function actionTaskcntGrid() {

        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        if(count($fields) == 1 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
        }

        $i = 0;
        $template_list = TaskTemplate::templateByProgram($args['program_id']);
        if(count($template_list)>0){
            foreach($template_list as $template_id => $template_name){
                if($i == 0){
                    if(!array_key_exists('template_id',$args)){
                        $args['template_id'] = $template_id;
                    }
                }
                $i++;
            }
        }

        $t = $this->genDataTaskcntGrid($fields[0]);
        $this->saveUrl();
        $list = TaskRecordModel::cntByBlock($args['program_id'],$args['template_id']);

        $this->renderPartial('taskcnt_list', array('t' => $t,'args'=>$args, 'rows' => $list));
    }

    /**
     * 列表
     */
    public function actionTaskcntList() {

        $program_id = $_REQUEST['program_id'];
        $this->render('taskcntlist',array('program_id'=>$program_id));
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
     * block统计图
     */
    public function actionBlockChart() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Block Statistic';
        $this->render('block_chart',array('program_id'=>$program_id));
    }

    /**
     * 统计图表查询数据
     */
    public function actionDataByDay() {
        $args = $_REQUEST['q'];
        $data = StatisticChecklist::DataByDay($args);
        print_r(json_encode($data));
    }

    /**
     * 统计图表查询数据
     */
    public function actionDataByDay2() {
        $args['end_date'] = $_REQUEST['end_date'];
        $args['start_date'] = $_REQUEST['start_date'];
        $args['program_id'] = $_REQUEST['program_id'];
        $module = $_REQUEST['module'];
        $data = StatisticChecklist::DataByDay2($args,$module);
        print_r(json_encode($data));
    }

    /**
     * block统计图
     */
    public function actionBlockData() {
        $args['date'] = $_REQUEST['date'];
        $args['project_id'] = $_REQUEST['program_id'];
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

    public static function actionBlockExport(){

        $args['program_id'] = $_REQUEST['program_id'];
        $args['template_id'] = $_REQUEST['template_id'];

        $data = array(
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $args['template_id']
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones_T2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $data = $rs['result']['data'];
        $stage_list = array();
        if(count($data)>0){
            foreach($data as $index => $value){
                if($index == 0){
                    $stage = $value['stage'];
                    foreach($stage as $i => $j){
                        if($j['stage_id'] != ''){
                            $stage_list[$j['stage_id']] = $j['stage_name'];
                        }
                    }
                }
            }
        }

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
        $objActSheet->setTitle ( 'Block Checklist Statistic' );

        if(count($stage_list)>0){
            $objPHPExcel->getActiveSheet()->getRowDimension('A')->setRowHeight(31.5);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objActSheet->setCellValue('A1','Block No');
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $objActSheet->setCellValue('B1','Total No.');
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $data_index = 2;
            $col_index = 1;
            foreach ($stage_list as $stage_id => $stage_name){
                if($data_index < 26){
                    $data_header = chr(65+$data_index);
                }else{
                    $x = $data_index / 26;
                    $data_header = chr(64 + $x).chr($data_index%26 + 65);
                }
                $objPHPExcel->getActiveSheet()->getStyle($data_header.$col_index)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($data_header.$col_index)->getFont()->setBold(true);
                $objActSheet->setCellValue($data_header.$col_index,$stage_name);
                $objPHPExcel->getActiveSheet()->getColumnDimension($data_header)->setWidth(30);
                $data_index++;
            }
            for($i=0;$i<3;$i++){
                if($data_index < 26){
                    $data_header = chr(65+$data_index);
                }else{
                    $x = $data_index / 26;
                    $data_header = chr(64 + $x).chr($data_index%26 + 65);
                }
                $objPHPExcel->getActiveSheet()->getStyle($data_header.$col_index)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($data_header.$col_index)->getFont()->setBold(true);
                if($i == 0){
                    $objActSheet->setCellValue($data_header.$col_index,'Percentage (Carcass)');
                }else if($i == 1){
                    $objActSheet->setCellValue($data_header.$col_index,'Percentage (Fitting out)');
                }else if($i == 2){
                    $objActSheet->setCellValue($data_header.$col_index,'Percentage (Installed)');
                }
                $objPHPExcel->getActiveSheet()->getColumnDimension($data_header)->setWidth(35);
                $data_index++;
            }
        }else{
            $objPHPExcel->getActiveSheet()->getRowDimension('A')->setRowHeight(31.5);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objActSheet->setCellValue('A1','Block No');
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

            $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $objActSheet->setCellValue('B1','Total No.');
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);

            $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
            $objActSheet->setCellValue('C1'.$index+1,'Percentage<br>(Carcass)');
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);

            $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
            $objActSheet->setCellValue('D1','Percentage<br>(Fitting out)');
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);

            $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
            $objActSheet->setCellValue('E1','Percentage<br>(Installed)');
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        }

        $i = 0;
        $block_list = array();
        if(count($data)>0){
            foreach($data as $u => $v){
                $i++;
                $stage = $v['stage'];
                $self = array();
                foreach($stage as $index => $j){
                    if($j['stage_id'] != ''){
                        $self[$j['stage_id']] = $j['cnt'];
                    }
                }
                $block_list[$i]['block'] = 'Block '.$v['block'];
                $block_list[$i]['stage'] = $self;
                $block_list[$i]['total'] = $v['total'];
            }
        }

        $col_index_1 = 2;
        $header_list = StatisticChecklist::headerCheckList($args);
        $u = 0;
        foreach ($header_list as $index => $value){
            $group[$u]['total'] = 0;
            $u++;
        }
        $total_sum = 0;
        $cnt_a = 0;
        $cnt_b = 0;
        $cnt_c = 0;
        foreach($block_list as $x => $y){
            $data_index_1 = 0;
            $data_header = chr(65+$data_index_1);
            $objActSheet->setCellValue($data_header.$col_index_1,$y['block']);
            $data_index_1++;
            $data_header = chr(65+$data_index_1);
            $objActSheet->setCellValue($data_header.$col_index_1,$y['total']);
            $total_sum+=$y['total'];
            $count = count($y['stage']);
            $j = 0;
            $u = 0;
            foreach ($y['stage'] as $stage_id => $stage_cnt){
                $data_header = chr(65+$data_index_1+1);
                $model = TaskStage::model()->findByPk($stage_id);
                $clt_type = $model->clt_type;
                $objActSheet->setCellValue($data_header.$col_index_1,$stage_cnt);
                $group[$u]['total'] += (int)$stage_cnt;
                if($clt_type == 'A'){
                    $cnt_a = (int)$stage_cnt;
                }
                if($clt_type == 'B'){
                    $cnt_b = (int)$stage_cnt;
                }
                if($clt_type == 'C'){
                    $j++;
                    if($j == 1){
                        $cnt_c = (int)$stage_cnt;
                    }
                }
                $u++;
                $data_index_1++;
            }
            if($cnt_a == 0 || $y['total'] == 0){
                $percent_a = 0;
            }else{
                $percent_a = $cnt_a/$y['total']*100;
                $percent_a = round($percent_a,2);
            }
            if($cnt_b == 0 || $y['total'] == 0){
                $percent_b = 0;
            }else{
                $percent_b = $cnt_b/$y['total']*100;
                $percent_b = round($percent_b,2);
            }
            if($cnt_c == 0 || $y['total'] == 0){
                $percent_c = 0;
            }else{
                $percent_c = $cnt_c/$y['total']*100;
                $percent_c = round($percent_c,2);
            }
            $data_index_1++;
            $data_header = chr(65+$data_index_1);
            $objActSheet->setCellValue($data_header.$col_index_1,$percent_c.'%');

            $data_index_1++;
            $data_header = chr(65+$data_index_1);
            $objActSheet->setCellValue($data_header.$col_index_1,$percent_b.'%');

            $data_index_1++;
            $data_header = chr(65+$data_index_1);
            $objActSheet->setCellValue($data_header.$col_index_1,$percent_a.'%');

            $col_index_1++;
        }

        $data_index_2 = 0;
        $data_header = chr(65+$data_index_2);
        $objActSheet->setCellValue($data_header.$col_index_1,Yii::t('comp_statistics', 'day_toatl'));

        $data_index_2++;
        $data_header = chr(65+$data_index_2);
        $objActSheet->setCellValue($data_header.$col_index_1,$total_sum);

        foreach ($group as $x => $y){
            if($y['total'] == 0 || $total_sum == 0){
                $percent_total = 0;
            }else{
                $percent_total = $y['total']/$total_sum*100;
                $percent_total = $percent_total.'%';
            }
            $data_index_2++;
            $data_header = chr(65+$data_index_2);
            $objActSheet->setCellValue($data_header.$col_index_1,$y['total']);
        }

        $col_index_1++;
        $data_index_3 = 0;
        $data_header = chr(65+$data_index_3);
        $objActSheet->setCellValue($data_header.$col_index_1,'Percentage');
        $data_index_3++;
        foreach ($group as $x => $y){
            if($y['total'] == 0 || $total_sum == 0){
                $percent_total = 0;
            }else{
                $percent_total = $y['total']/$total_sum*100;
                $percent_total = round($percent_total,2);
            }
            $data_index_3++;
            $data_header = chr(65+$data_index_3);
            $objActSheet->setCellValue($data_header.$col_index_1,$percent_total.'%');
        }

        //导出
        $filename = 'Block Checklist Statistic';
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save( 'php://output' );
    }

    public static function actionDateExport(){

        $args['program_id'] = $_REQUEST['program_id'];
        $args['template_id'] = $_REQUEST['template_id'];

        $data = array(
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $args['template_id']
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones_T2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $data = $rs['result']['stage_list'];
        $month_list = array();
        if(count($data)>0){
            foreach($data as $index => $value){
                if($index == 0){
                    foreach($value['month_cnt'] as $x => $y){
                        $month_list[] = $y['month'];
                    }
                }
            }
        }
        $stage_list = array();
        if(count($data)>0){
            foreach($data as $index => $value){
//                if($value['stage_name'] != 'Not started'){
                    $stage_list[$value['stage_id']]['stage_name'] = $value['stage_name'];
                    foreach($value['month_cnt'] as $x => $y){
                        $stage_list[$value['stage_id']][$y['month']] =0;
                    }
//                }
            }
        }

        if(count($data)>0) {
            foreach ($data as $index => $value) {
                foreach ($value['month_cnt'] as $x => $y) {
                    $stage_list[$value['stage_id']][$y['month']] += (int)$y['cnt'];
                }
            }
        }


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

        if(count($month_list)>0){
            $objPHPExcel->getActiveSheet()->getRowDimension('A')->setRowHeight(31.5);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objActSheet->setCellValue('A1','Milestones');
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $index = 1;
            $data_index = 1;
            foreach ($month_list as $i => $date){
                $data_header = chr(65+$data_index);
                $objPHPExcel->getActiveSheet()->getRowDimension($data_header)->setRowHeight(31.5);
                $objPHPExcel->getActiveSheet()->getStyle($data_header.$index)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($data_header.$index)->getFont()->setBold(true);
                $objActSheet->setCellValue($data_header.$index,$date);
                $objPHPExcel->getActiveSheet()->getColumnDimension($data_header)->setWidth(30);
                $data_index++;
            }
        }else{
            $objPHPExcel->getActiveSheet()->getRowDimension('A')->setRowHeight(31.5);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objActSheet->setCellValue('A1','Task Stage');
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

            $objPHPExcel->getActiveSheet()->getRowDimension('B')->setRowHeight(31.5);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $objActSheet->setCellValue('B1','Date');
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        }

        if (count($stage_list)>0) {
            $header_list =StatisticChecklist::headerDate($args);
            $col_index = 2;
            foreach ($stage_list as $i => $row) {
                $data_index_1 = 0;
                $data_header = chr(65+$data_index_1);
                $objActSheet->setCellValue($data_header.$col_index,$row['stage_name']);
                $data_index_1++;
                foreach($header_list as $m =>$n){
                    $data_header = chr(65+$data_index_1);
                    $objActSheet->setCellValue($data_header.$col_index,$row[$n]);
                    $data_index_1++;
                }
                $col_index++;
            }
        }

        //导出
        $filename = 'Date Checklist Statistic';
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xlsx"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' ); //在内存中准备一个excel2003文件
        $objWriter->save( 'php://output' );
    }


    public function actionShow(){
        $program_id = $_REQUEST['program_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $this->smallHeader = 'DfMA Statistics';
        $this->render('show',array('program_id'=>$root_proid));
    }

    public function actionBlockColumn(){
        $template_id = $_REQUEST['template_id'];
        $project_id = $_REQUEST['project_id'];
        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $template_id
        );

        $post_data = json_encode($data);
        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones_T2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        $block_list = array();
        $res = array();
        $color_list = array();
        $data = $rs['result']['data'];
        foreach($data as $u => $v){
            $stage = $v['stage'];
            $stage_data = array();
            $i = 0;
            $block_list[] = 'Block '.$v['block'];
            $cnt_1 = 0;
            $cnt_2 = 0;
            $total = $v['total'];
            $total_cnt = 0;
            foreach($stage as $index => $j){
                if($j['stage_id'] == ''){
                    $cnt_1 = (int)$j['cnt'];
                    $stage_name = $j['stage_name'];
                    $stage_color = $j['stage_color'];
                }else{
                    $self = array();
                    $stage_list[$i]['name'] = $j['stage_name'];
                    $stage_list[$i]['color'] = $j['stage_color'];
                    if($j['cnt'] == '0'){
                        $stage_list[$i]['data'][] = null;
                    }else{
                        $stage_list[$i]['data'][] = (int)$j['cnt'];
                        $total_cnt+= (int)$j['cnt'];
                    }
                    $i++;
                }
            }
            $cnt = $total - $total_cnt;
            $stage_list[$i]['data'][] = $cnt;
            $stage_list[$i]['name'] = $stage_name;
            $stage_list[$i]['color'] = $stage_color;
            $color_list[$i]['color'] = $stage_color;
        }
//        var_dump($stage_list);
//        sort($stage_list);
//        sort($color_list);
//
//        var_dump($stage_list);
//        exit;

        foreach ($color_list as $o => $p){
            $color_arr[] = $p['color'];
        }
        $r['x'] = array_unique($block_list);
        $r['y'] = $stage_list;
        $r['color'] = $color_arr;
        print_r(json_encode($r));
    }

    public function actionMilestones(){
        $template_id = $_REQUEST['template_id'];
        $project_id = $_REQUEST['project_id'];
        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $template_id
        );

        $post_data = json_encode($data);
        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones_T2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        $data = $rs['result']['data'];
        $model_list = array();
        $block_list = array();
        foreach($data as $index => $value){
            $stage = $value['stage'];
            $stage_list = array();
            $cnt_1 = 0;
            $cnt_2 = 0;
            $total = $value['total'];
            $total_cnt = 0;
            foreach($stage as $i => $j){
                $self = array();
                if($j['stage_id'] != ''){
                    $self['name'] = $j['stage_name'];
                    if($j['cnt'] == '0'){
                        $self['y'] = null;
                    }else{
                        $self['y'] = (int)$j['cnt'];
                        $total_cnt+=(int)$j['cnt'];
                    }
                    $self['color'] = $j['stage_color'];
                    $stage_list[] = $self;
                }else{
                    $cnt_1 = (int)$j['cnt'];
                    $name = $j['stage_name'];
                    $color = $j['stage_color'];
                }
            }
            $cnt = $total - $total_cnt;
            $self = array();
            $self['name'] = $name;
            $self['y'] = $cnt;
            $self['color'] =$color;
            $stage_list[] = $self;

            $block_list[] = 'Block '.$value['block'];
            $model_list[] = $stage_list;
        }
        $cnt = count($model_list);
        $result['block'] = $block_list;
        $result['model'] = $model_list;
        $result['cnt'] = (int)$cnt;
        print_r(json_encode($result));
    }
}
