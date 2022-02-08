<?php

/**
 * 项目报表
 * @author weijuan
 */
class ReportController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    public $layout = '//layouts/main_1';

    public function init() {
        parent::init();
        //echo Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        //$this->contentHeader = Yii::t('proj_report', 'contentHeader');
        $this->bigMenu = Yii::t('proj_report', 'bigMenu');
    }

    /**
     * 考勤统计表头
     * @return SimpleGrid
     */
    private function genAttendDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/report/attendgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('proj_report', 'report_num'), '', '');
        $t->set_header(Yii::t('proj_report', 'report_program'), '', '');
        $t->set_header(Yii::t('proj_report', 'report_role'), '', '');
        $t->set_header(Yii::t('proj_report', 'attend_hour'), '', '');
        return $t;
    }

    /**
     * 考勤统计查询
     */
    public function actionAttendGrid() {

        $args = $_GET['q']; //查询条件
        //var_dump($args);
        
        $t = $this->genAttendDataGrid();
        //$this->saveAttendUrl();
       
        $list = ProjectAttend::report($args);
        //var_dump($list);
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
        $program_list = Program::programList($args);
        $role_list = Role::roleList();
        
        $this->renderPartial('attend_list', array('t' => $t, 'rows' => $list, 'program_list'=>$program_list, 'role_list'=>$role_list));
    }

    /**
     * 考勤统计列表
     */
    public function actionAttendList() {

        $this->smallHeader = Yii::t('proj_report', 'smallHeader Attend');
        
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
 
        $program_list = Program::programList($args);
        $role_list = Role::roleList();
        $this->render('attendlist', array('program_list'=>$program_list, 'role_list'=>$role_list));
    }
    /**
     * 导出Excel
     */
    public function actionExport(){
        $args = $_GET['q'];
//        var_dump($args);
//        exit();
        $list = ProjectAttend::report($args);
//        var_dump($list);
//        exit();
        $args = array(
            'contractor_id' => Yii::app()->user->contractor_id,
            'project_type'  =>  'MC',
        );
        $program_list = Program::programList($args);
        $role_list = Role::roleList();
        //为指定表单制定表格
       //echo Yii::app()->basePath;
         spl_autoload_unregister(array('YiiBase', 'autoload')); 
         $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
         require_once($phpExcelPath);
         spl_autoload_register(array('YiiBase', 'autoload')); 
         $objectPHPExcel = new PHPExcel();
         $objectPHPExcel->setActiveSheetIndex(0);
         $objActSheet = $objectPHPExcel->getActiveSheet();
         $objActSheet->setTitle('Sheet1');
//        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭yii的自动加载功能

        //报表头的输出
         $objectPHPExcel->getActiveSheet()->mergeCells('A1:D1');
         $objectPHPExcel->getActiveSheet()->setCellValue('A1','总包项目工时统计表');
         $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
         $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','日期：'.date("Y年m月j日"));
        //表格头的输出
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','序号');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6.5);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','总包项目');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','角色');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','出勤时间（小时）');
         $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(17);
        //设置居中
         $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
              ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置边框
         $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
              ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
     
         
        //设置颜色
         $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
     if($list){
        foreach($list as $i=>$row){
              if($row['hours']>0){
                static $n = 1;
                static $t = 0;
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+3),$n);
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+3),$program_list[$row['node_id']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+3),$role_list[$row['node_name']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+3),$row['hours']);
                //设置边框
                $currentRowNum = $n+4;
                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $n++; 
                $t+=$row['hours'];
              }  
            }
     }else{
         static $n = 1;
     }   
         //合并列
         $objectPHPExcel->getActiveSheet()->mergeCells("A".($n+3).":C".($n+3));
         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".($n+3),'出勤时间汇总');
         $objectPHPExcel->getActiveSheet()->setCellValue("D".($n+3),$t);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'工时统计表-'.date("Y年m月j日").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    /**
     * 导出Excel
     */
    public function actionExportEpss(){
        $program_id = $_REQUEST['program_id'];
        $type_id = $_REQUEST['type_id'];
        $year = substr(Utils::MonthToCn($_REQUEST['month']),0,4);
        $month = substr(Utils::MonthToCn($_REQUEST['month']),5,2);
        $date = $year.$month;
        $rows = ProjectAttend::queryProgramAttend($date,$program_id,$type_id);
        $pro_model = Program::model()->findByPk($program_id);
        $contractor_id = $pro_model->contractor_id;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $type_list = EpssType::typeListByType($type_id);
//        var_dump($rows);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','Submission Of Monthly Manpower Usage');
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
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A3')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3','Builder UEN No.');
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A3')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B3')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('B3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',$con_model->company_sn);
        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A4')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A4','Project BP No. ');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A4')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B4')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('B4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B4',$pro_model->program_bp_no);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B4')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A5')->getFont()->setSize(11);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A5','Project Name');
        $objectPHPExcel->getActiveSheet()->getStyle('A5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A5')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B5')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('B5',$pro_model->program_name);
        $objectPHPExcel->getActiveSheet()->getStyle('B5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A6')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A6','Builder');
        $objectPHPExcel->getActiveSheet()->getStyle('A6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A6')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B6')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('B6',$con_model->contractor_name);
        $objectPHPExcel->getActiveSheet()->getStyle('B6')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A7')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->setCellValue('A7','Month that Data is submitted for');
        $objectPHPExcel->getActiveSheet()->getStyle('A7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A7')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->setCellValue('B7',$month);
        $objectPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B7')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('B7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A8','Year that Data is submitted for');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A8')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A8')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->setCellValue('B8',$year);
        $objectPHPExcel->getActiveSheet()->getStyle('B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B8')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle('A10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A10','Trades');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A10')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle( 'A10')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getStyle('A10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B10','ManPowerUsed(mandays)');
        $objectPHPExcel->getActiveSheet()->getStyle('B10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B10')->getFont()->setSize(11);
        $objectPHPExcel->getActiveSheet()->getStyle( 'B10')->getFont()->setBold(true);

        $x = 11;
        $y = 0;
        $teamname_tmp = '';
        $subtotal_tmp = '';
        foreach($type_list as $team_name => $team_list){
            if($team_name != 'N/A'){
                $y++;
                $subtotal = 0;
                $usrtotal = 0;
                foreach ($team_list as $k => $v) {
//                $group_name = $item[$v['item_id']]['group_name'];
                    if ($teamname_tmp != $team_name) {
                        $teamname_tmp = $team_name;
                        if ($y > 1) {
                            $objectPHPExcel->getActiveSheet()->getStyle(A .$x)->getFont()->setSize(11);
                            $objectPHPExcel->getActiveSheet()->setCellValue(A .$x, $teamname_tmp);
                            $objectPHPExcel->getActiveSheet()->getStyle(A .$x)->getFont()->setBold(true);
                            $objectPHPExcel->getActiveSheet()->getStyle(A .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $objectPHPExcel->getActiveSheet()->getStyle(B .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $x++;
                        }
                    }
                    $objectPHPExcel->getActiveSheet()->setCellValue(A .$x,$v);
                    $objectPHPExcel->getActiveSheet()->getStyle(A .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    if($rows[$k]['act_hour'] != '0'){
                        if(!empty($rows[$k])){
                            $subtotal+=$rows[$k]['act_hour'];
                            if(array_key_exists('user_list',$rows[$k])){
                                $user_list = $rows[$k]['user_list'];
                                $user_list = array_unique($user_list);
                                $user_cnt = count($user_list);
                                $usrtotal+=$user_cnt;
                                $objectPHPExcel->getActiveSheet()->setCellValue(B .$x,$user_cnt.'('.$rows[$k]['act_hour'].')');
                            }else{
                                $objectPHPExcel->getActiveSheet()->setCellValue(B .$x,$rows[$k]['act_hour']);
                            }
                        }
                    }
                    $objectPHPExcel->getActiveSheet()->getStyle(B .$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objectPHPExcel->getActiveSheet()->getStyle(B .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $x++;
                }
                $subtotal_teamname = 'SubTotal('.$team_name.')';
                $subtotal_tmp = $subtotal_teamname;

                $objectPHPExcel->getActiveSheet()->getStyle(A .$x)->getFont()->setSize(11);
                $objectPHPExcel->getActiveSheet()->setCellValue(A .$x, $subtotal_tmp);
                $objectPHPExcel->getActiveSheet()->getStyle(A .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle(A .$x)->getFont()->setBold(true);
                $objectPHPExcel->getActiveSheet()->getStyle(B .$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if($subtotal != 0){
                     $objectPHPExcel->getActiveSheet()->setCellValue(B .$x, $usrtotal.'('.$subtotal.')');
                }
                $objectPHPExcel->getActiveSheet()->getStyle(B .$x)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $x++;
            }
        }
//        exit;
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        if($type_id == '1'){
            header('Content-Disposition:attachment;filename="'.'Building Project Manpower (铁路或地轨项目人力统计)Template'.date("d M Y").'.xls"');
        }else if($type_id == '2'){
            header('Content-Disposition:attachment;filename="'.'Rail Project Manpower (铁路或地轨项目人力统计)Template'.date("d M Y").'.xls"');
        }else{
            header('Content-Disposition:attachment;filename="'.'Road Project Manpower (陆路项目人力统计)Template'.date("d M Y").'.xls"');
        }
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    /**
     * 保存考勤统计查询链接
     */
    private function saveAttendUrl() {

        $a = Yii::app()->session['list_url'];
        $a['licensepdf/list'] = str_replace("r=proj/attend/attendgrid", "r=proj/attend/attendlist", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
