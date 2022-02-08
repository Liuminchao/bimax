<?php

class ExportController extends AuthBaseController {
     
     /**
     * 添加
     */
    public function actionTexport() {
        $args = $_GET['q'];
        $program_id = $args['program_id'];
        $task_id = $args['task_id'];
        $model = new Task('create');
        $this->renderPartial('export', array('program_id' => $program_id,'task_id'=> $task_id,'model' => $model));
    }
    
    /**
     * 导出Excel
     */
    public function actionExport(){
        $args = $_REQUEST['Task'];
        $args['task_id'] = $_REQUEST['task_id'];
        $args['program_id'] = $_REQUEST['program_id'];
//        var_dump($args);
//        exit;
        $year_month = (date('Ym',  strtotime($args['plan_start_time'])));
        $year = substr($year_month,0,4);
        $month = substr($year_month,4,2);

        if($args['plan_start_time']){
            //获取指定月份最后一天
        $end_date = date('Ymd', strtotime(date('Ym01', strtotime($args['plan_start_time'])) . ' +1 month -1 day'));
        $start_date = date('Ymd', strtotime(date('Ym01', strtotime($args['plan_start_time'])) . ' +2 day'));
        //$startdate = strtotime($start_date);
        //获取一个月的天数
        $enddate = strtotime($end_date);
        $end_num = substr($end_date,6,2);

        //指定的月份遍历存放在数组中
        for($i=0;$i<$end_num;$i++){
            $stratdate = date('Ymd', strtotime(date('Ym01', strtotime($args['plan_start_time'])) .'+'."$i" .' day'));
            $arr[$i] = $stratdate;   
        }

        }
//        var_dump($year);
//        exit;
        $list = Task::taskList($args,$start_date,$end_date);
        $model = Program::model()->findByPk($list[0]['program_id']);
        $program_name = $model->program_name;
        $user_list = TaskUser::taskuserList($args);
//        var_dump($user_list);
//        exit;
        //为指定表单制定表格
       //echo Yii::app()->basePath;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload')); 
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
 
        //表格头的输出
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A4','任务编号');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B4','任务名称');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(23);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C4','成员');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D4','计划量');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E4','计划工时');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F4','计划开始时间');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G4','计划结束时间');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
        for($i=7,$j=1; $i<38; $i++,$j++){
            if($i<26){
                $a = chr(65+$i);
                $d = $a.'4';
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(3);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($d,$j);
            }else{
                $a = chr(64 + ($i / 26)).chr(65 + ($i % 26));
                $d = $a.'4';
                $objectPHPExcel->getActiveSheet()->getColumnDimension($a)->setWidth(3);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($d,$j);
            }
        }
         
         //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$a.'1');
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',$year.'年'.$month.'月'.'项目分解计划表');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.$a.'2');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','导出日期：'.date("Y年m月j日"));
//                var_dump($list[0]['program_id']);
//        exit;

        $objectPHPExcel->getActiveSheet()->mergeCells('A3'.':'.$a.'3');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','项目名:'.$program_name);
//         var_dump($a);
//         exit;
        //设置居中
        $objectPHPExcel->getActiveSheet()->getStyle('A4:'.$d)
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置边框
        $objectPHPExcel->getActiveSheet()->getStyle('A4:'.$d)
            ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
     
         
        //设置颜色
        $objectPHPExcel->getActiveSheet()->getStyle('A4:'.$d)->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        
        if($list){     
            foreach($list as $i=>$row){
                
                static $n = 1;
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n+4),$row['task_id']);
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+4),$row['task_name']);
                if($user_list){
//                    var_dump($user_list);
//                    exit;
                    foreach($user_list as $key=>$r){
                        if($row['task_id']==$key){
                            $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4),implode(',',$r));
                        }
                    }
                }
                //一级任务不显示日期
                if($row['father_taskid']){
                    $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+4),$row['plan_amount'].$row['amount_unit']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n+4),$row['plan_work_hour']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4),substr($row['plan_start_time'],0,10));
                    $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n+4),substr($row['plan_end_time'],0,10));
                
                    $start_time = trim(date('Ymd',strtotime($row['plan_start_time'])));
                    $starttime = strtotime($start_time);
                    $end_time = trim(date('Ymd',strtotime($row['plan_end_time'])));
                    $endtime = strtotime($end_time);
                    //$start= substr($start_time,6,8);
                    //$end = substr($end_time,6,8);
                    $j=0;
                    $array = array();
                    foreach ($arr as $key=>$date){
                        $datetime = strtotime($date);
                            
                        if($starttime<=$datetime && $datetime<=$endtime){
                            $start= substr($date,6,8);
                                
                            $j++;
                            $array[$j] = $start;
                            if($start<19){
                                $a = chr(71+$start);
                            }else{
                                $a = chr(64 + ($start/ 19)).chr(64 + ($start % 19));
                            }
                        }
                    }
//                        var_dump($array[1]);
//                        var_dump($a);
//                        exit;
                    $c = $array[1];
                    if($c<19){
                        $min = chr(71+$c);
                    }else{
                        $min = chr(64 + ($c/ 19)).chr(64 + ($c % 19));
                    }
//                        var_dump($min);
//                        var_dump($c);
//                        exit;
                    if($array){
                        $objectPHPExcel->getActiveSheet()->getStyle($min.($n+4).':'.$a.($n+4))->getFill()
                                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF808080');
                    }
                      
                }
                     
                
                
//                //设置边框
//                $currentRowNum = $n+4;
//                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':'.$a.$currentRowNum )
//                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                
                $n++;
            
            }    
        }else{
            static $n = 1;
        }
//        var_dump($a);
//        var_dump($array);
//        exit;
         //合并列
//         $objectPHPExcel->getActiveSheet()->mergeCells("A".($n+3).":C".($n+3));
//         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue("A".($n+3),'出勤时间汇总');
//         $objectPHPExcel->getActiveSheet()->setCellValue("D".($n+3),$t);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'项目分解表-'.date("Y年m月j日").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
}
