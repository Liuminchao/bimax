<?php

/**
 * 日统计
 * @author LiuMinchao
 */
class ExcelExport extends CActiveRecord {

    /**
     * 导出Excel
     */
    public static function Export(){
        $contractor_list = Contractor::compAllList();
        $program_list = Program::programAllList();
        $sql = "SELECT a.program_id,a.program_name,b.contractor_id,b.contractor_name FROM bac_program a,bac_contractor b WHERE  a.program_id = a.root_proid and a.status='00' and a.contractor_id = b.contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $program_sql = "SELECT * FROM bac_program WHERE status='00' ";
        $program_command = Yii::app()->db->createCommand($program_sql);
        $program_rows = $program_command->queryAll();

        $j = array(
            "PTW_cnt" => '0',
            "TBM_cnt" => '0',
            "INSPECTION_cnt" => '0',
            "CHECKLIST_cnt" => '0',
        );
        foreach($rows as $k => $list){
            foreach($program_rows as $g => $r){
                if($list['program_id'] == $r['root_proid']){
                    $arr[$list['program_id']][$r['contractor_id']] = $j;
                }
            }
        }

        $ptw_sql = "SELECT program_id,count(apply_id) as PTW_cnt,apply_contractor_id FROM ptw_apply_basic where record_time like '2018-01-08%' group by program_id,apply_contractor_id";
        $ptw_command = Yii::app()->db->createCommand($ptw_sql);
        $ptw_rows = $ptw_command->queryAll();
//        var_dump($ptw_rows);
//        exit;

        $tbm_sql = "SELECT program_id,count(meeting_id) as TBM_cnt,add_conid FROM tbm_meeting_basic where record_time like '2018-01-08%' group by program_id,add_conid";
        $tbm_command = Yii::app()->db->createCommand($tbm_sql);
        $tbm_rows = $tbm_command->queryAll();

        $checklist_sql = "SELECT root_proid,count(check_id) as CHECKLIST_cnt,contractor_id FROM bac_routine_check where apply_time like '2018-01-08%' group by root_proid,contractor_id";
        $checklist_command = Yii::app()->db->createCommand($checklist_sql);
        $checklist_rows = $checklist_command->queryAll();

        $inspection_sql = "SELECT root_proid,count(check_id) as INSPECTION_cnt,contractor_id FROM bac_safety_check where apply_time like '2018-01-08%' group by root_proid,contractor_id";
        $inspection_command = Yii::app()->db->createCommand($inspection_sql);
        $inspection_rows = $inspection_command->queryAll();
//        var_dump($inspection_rows);
//        exit;


        foreach($arr as $program_id => &$list) {
            foreach ($ptw_rows as $a => $ptw) {
//                var_dump($program_id);
//                var_dump($list);
//                exit;
                if ($program_id == $ptw['program_id']) {
                    $PTW_cnt = $ptw['PTW_cnt'];
                    $list[$ptw['apply_contractor_id']]['PTW_cnt'] = $PTW_cnt;
//                    var_dump($program_id);
//                    var_dump($list[$ptw['apply_contractor_id']]['PTW_cnt']);
//                    var_dump($ptw['PTW_cnt']);
//                    exit;
                }
            }

            foreach ($tbm_rows as $a => $tbm) {
                if ($program_id == $tbm['program_id']) {
                    $list[$tbm['add_conid']]['TBM_cnt'] = $tbm['TBM_cnt'];
                }
            }
            foreach ($checklist_rows as $a => $checklist) {
                if ($program_id == $checklist['root_proid']) {
                    $list[$checklist['contractor_id']]['CHECKLIST_cnt'] = $checklist['CHECKLIST_cnt'];
                }
            }
            foreach ($inspection_rows as $a => $inspection) {
                if ($program_id == $inspection['root_proid']) {
                    $list[$inspection['contractor_id']]['INSPECTION_cnt'] = $inspection['INSPECTION_cnt'];
                }
            }
        }
//        var_dump($arr);
//        exit;
//        var_dump($arr);
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
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','项目编号');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1','总包项目');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C1','公司');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D1','PTW');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E1','TBM');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F1','CHECKLIST');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G1','INSPECTION');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



//        //设置居中
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
//            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        //设置边框
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
//            ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//
//
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        if($arr){
            $n = 2;
            foreach($arr as $i=>$row){
                $count = count($row);
//                var_dump($n+$count);
//                $objectPHPExcel->getActiveSheet()->mergeCells('A'.($n).':'.'A'.($n+$count));
//                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n), $program_list[$i]);
                foreach($row as $tag=>$h) {
                    $objectPHPExcel->getActiveSheet()->setCellValue('A'.  ($n), $i);
                    $objectPHPExcel->getActiveSheet()->setCellValue('B'.  ($n), $program_list[$i]);
                    $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n), $tag);
                    $objectPHPExcel->getActiveSheet()->setCellValue('D' . ($n), $h['PTW_cnt']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('E' . ($n), $h['TBM_cnt']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('F' . ($n), $h['CHECKLIST_cnt']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('G' . ($n), $h['INSPECTION_cnt']);
                    //设置边框
//                $currentRowNum = $n+4;
//                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
//                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $n++;
                }
            }
        }else{
            static $n = 1;
        }
        //合并列
        $objectPHPExcel->getActiveSheet()->setCellValue("B".$n,$n-1);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.Yii::t('comp_statistics', 'daily_statistics').'-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
    // 获取文件夹大小
    public static function getDirSize($dir)
    {
        $handle = opendir($dir);
        $sizeResult = 0;
        while (false!==($FolderOrFile = readdir($handle)))
        {
            if($FolderOrFile != "." && $FolderOrFile != "..")
            {
                if(is_dir("$dir/$FolderOrFile"))
                {
                    $sizeResult += self::getDirSize("$dir/$FolderOrFile");
                }
                else
                {
                    $sizeResult += filesize("$dir/$FolderOrFile");
                }
            }
        }
        closedir($handle);
        return $sizeResult;
    }
    /**
     * 文档，图片，流程图片，证书
     */
    public static function FileStatistical() {
//        $contractor_id = Yii::app()->user->getState('contractor_id');
        $file = array(
            'document' => array(
                '0' => '/opt/www-nginx/web/filebase/company/',//公司级文档
                '1' => '/opt/www-nginx/web/filebase/program/',//项目级文档
            ),
            'photo' => array(
                '0' => '/opt/www-nginx/web/filebase/data/staff/',//人员标签页图片，证书
                '1' => '/opt/www-nginx/web/filebase/data/face/',//人员头像
                '2' => '/opt/www-nginx/web/filebase/data/device/',//设备照片，证书
                '3' => '/opt/www-nginx/web/filebase/data/qrcode/',//人员，设备二维码
            ),
            'appupload' => array(
                '0' => '/opt/www-nginx/appupload',
            )
        );
        $contractor_list = Contractor::compList();//承包商列表
        foreach($contractor_list as $contractor_id => $contractor_name){
            //PTW
            $ptw_sql = "select b.pic from ptw_apply_basic a,bac_check_apply_detail b where a.apply_id = b.apply_id and a.apply_contractor_id = '".$contractor_id."' ";
            $command = Yii::app()->db->createCommand($ptw_sql);
            $ptw_rows = $command->queryAll();
            $pic_sum = 0;
            foreach($ptw_rows as $i => $j){
                if($j['pic'] != '-1' && $j['pic'] != ''){
                    if (file_exists($j['pic'])) {
                        $pic_sum += filesize($j['pic']);
                    }
                }
            }
            $data[$contractor_id]['PTW_pic'] = $pic_sum/1024/1024;
            //TBM
            $tbm_sql = "select b.pic from tbm_meeting_basic a,bac_check_apply_detail b where a.meeting_id = b.apply_id and a.add_conid = '".$contractor_id."' ";
            $command = Yii::app()->db->createCommand($tbm_sql);
            $tbm_rows = $command->queryAll();
            $tbm_sum = 0;
            foreach($tbm_rows as $i => $j){
                if($j['pic'] != '-1' && $j['pic'] != ''){
                    if (file_exists($j['pic'])) {
                        $tbm_sum += filesize($j['pic']);
                    }
                }
            }
            $data[$contractor_id]['TBM_pic'] = $tbm_sum/1024/1024;
            //培训
            $train_sql = "select b.pic from train_apply_basic a,bac_check_apply_detail b where a.training_id = b.apply_id and a.add_conid = '".$contractor_id."' and a.module_type = '1' ";
            $command = Yii::app()->db->createCommand($train_sql);
            $train_rows = $command->queryAll();
            $train_sum = 0;
            foreach($train_rows as $i => $j){
                if($j['pic'] != '-1' && $j['pic'] != ''){
                    if (file_exists($j['pic'])) {
                        $train_sum += filesize($j['pic']);
                    }
                }
            }
            $data[$contractor_id]['TRAIN_pic'] = $train_sum/1024/1024;
            //会议
            $meeting_sql = "select b.pic from train_apply_basic a,bac_check_apply_detail b where a.training_id = b.apply_id and a.add_conid = '".$contractor_id."' and a.module_type = '2' ";
            $command = Yii::app()->db->createCommand($meeting_sql);
            $meeting_rows = $command->queryAll();
            $meeting_sum = 0;
            foreach($meeting_rows as $i => $j){
                if($j['pic'] != '-1' && $j['pic'] != ''){
                    if (file_exists($j['pic'])) {
                        $meeting_sum += filesize($j['pic']);
                    }
                }
            }
            $data[$contractor_id]['Meeting_pic'] = $meeting_sum/1024/1024;
            //安全检查
            $inspection_sql = "select b.pic from bac_safety_check a,bac_safety_check_detail b where a.check_id = b.check_id and a.contractor_id = '".$contractor_id."' ";
            $command = Yii::app()->db->createCommand($inspection_sql);
            $inspection_rows = $command->queryAll();
            $inspection_sum = 0;
            foreach($inspection_rows as $i => $j){
                if($j['pic'] != '-1' && $j['pic'] != ''){
                    if (file_exists($j['pic'])) {
                        $inspection_sum += filesize($j['pic']);
                    }
                }
            }
            $data[$contractor_id]['Inspection_pic'] = $inspection_sum/1024/1024;
            //意外
            $acci_sql = "select a.acci_pic,b.pic from accident_basic a,accident_sick_leave b where a.apply_id = b.apply_id and a.apply_contractor_id = '".$contractor_id."' ";
            $command = Yii::app()->db->createCommand($acci_sql);
            $acci_rows = $command->queryAll();
            $acci_sum = 0;
            foreach($acci_rows as $i => $j){
                if($j['acci_pic'] != '-1' && $j['acci_pic'] != ''){
                    if (file_exists($j['acci_pic'])) {
                        $acci_sum += filesize($j['acci_pic']);
                    }
                }
                if($j['pic'] != '-1' && $j['pic'] != ''){
                    if (file_exists($j['pic'])) {
                        $acci_sum += filesize($j['pic']);
                    }
                }
            }
            $data[$contractor_id]['Acci_pic'] = $acci_sum/1024/1024;
            //QAQC
            $qaqc_sql = "select b.pic from bac_qaqc_basic a,bac_qaqc_detail b where a.check_id = b.check_id and a.contractor_id = '".$contractor_id."' ";
            $command = Yii::app()->db->createCommand($qaqc_sql);
            $qaqc_rows = $command->queryAll();
            $qaqc_sum = 0;
            foreach($qaqc_rows as $i => $j){
                if($j['pic'] != '-1' && $j['pic'] != ''){
                    if (file_exists($j['pic'])) {
                        $qaqc_sum += filesize($j['pic']);
                    }
                }
            }
            $data[$contractor_id]['Qaqc_pic'] = $qaqc_sum/1024/1024;
            //人员各种证书
            $staff_cert_path = $file['photo'][0].$contractor_id.'/';
            if(file_exists($staff_cert_path)){
                $staff_cert_str = exec('du -sh '.$staff_cert_path);
                $staff_cert_ar = explode('/',$staff_cert_str);
                $staff_cert_size = $staff_cert_ar[0];
                $data[$contractor_id]['Staff_cert'] = $staff_cert_size;
            }
            //人员头像
            $staff_face_path = $file['photo'][1].$contractor_id.'/';
            if(file_exists($staff_face_path)){
                $staff_face_str = exec('du -sh '.$staff_face_path);
                $staff_face_ar = explode('/',$staff_face_str);
                $staff_face_size = $staff_face_ar[0];
                $data[$contractor_id]['Staff_face'] = $staff_face_size;
            }
            //设备照片+设备证书
            $device_path = $file['photo'][2].$contractor_id.'/';
            if(file_exists($device_path)){
                $device_str = exec('du -sh '.$device_path);
                $device_ar = explode('/',$device_str);
                $device_size = $device_ar[0];
                $data[$contractor_id]['device'] = $device_size;
            }
            //二维码
            $qrcode_path = $file['photo'][3].$contractor_id.'/';
            if(file_exists($qrcode_path)){
                $qrcode_str = exec('du -sh '.$qrcode_path);
                $qrcode_ar = explode('/',$qrcode_str);
                $qrcode_size = $qrcode_ar[0];
                $data[$contractor_id]['qrcode'] = $qrcode_size;
            }
            //公司级文档
            $company_document = $file['document'][0].$contractor_id.'/';
            if(file_exists($company_document)){
                $company_str = exec('du -sh '.$company_document);
                $company_ar = explode('/',$company_str);
                $company_size = $company_ar[0];
                $data[$contractor_id]['document_company'] = $company_size;
            }
            //项目级文档
            $program_document_sql = "select doc_path from bac_document where  type='4' and contractor_id = '".$contractor_id."' ";
            $command = Yii::app()->db->createCommand($program_document_sql);
            $program_document_rows = $command->queryAll();
            $program_document_sum = 0;
            foreach($program_document_rows as $i => $j){
                if($j['doc_path'] != '-1' && $j['doc_path'] != ''){
                    $doc_path = '/opt/www-nginx/web'.$j['doc_path'];
                    if (file_exists($doc_path)) {
                        $program_document_sum += filesize($doc_path);
                    }
                }
            }
            $data[$contractor_id]['document_program'] = $program_document_sum;
        }

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
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','公司');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1','PTW');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C1','TBM');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D1','TRAIN');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('D1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E1','MEETING');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('E1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F1','INSPECTION');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('F1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G1','ACCI');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H1','QAQC');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I1','人员证书');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('I1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J1','人员头像');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('J1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K1','设备');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('K1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L1','二维码');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('L1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('M1','公司级文档');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('N1','项目级文档');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N1')->getFont()->setSize(10);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('N1')
            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


//        //设置居中
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')
//            ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        //设置边框
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3' )
//            ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//
//
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
        if($data){
            $n = 2;
            foreach($data as $id=>$list){
                $count = count($data);
//                var_dump($n+$count);
//                $objectPHPExcel->getActiveSheet()->mergeCells('A'.($n).':'.'A'.($n+$count));
//                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n), $program_list[$i]);

                    $objectPHPExcel->getActiveSheet()->setCellValue('A'.  ($n), $contractor_list[$id]);
                    $objectPHPExcel->getActiveSheet()->setCellValue('B'.  ($n), $list['PTW_pic']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n), $list['TBM_pic']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('D' . ($n), $list['TRAIN_pic']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('E' . ($n), $list['Meeting_pic']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('F' . ($n), $list['Inspection_pic']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('G' . ($n), $list['Acci_pic']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('H' . ($n), $list['Qaqc_pic']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('I' . ($n), $list['Staff_cert']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('J' . ($n), $list['Staff_face']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('K' . ($n), $list['device']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('L' . ($n), $list['qrcode']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('M' . ($n), $list['document_company']);
                    $objectPHPExcel->getActiveSheet()->setCellValue('N' . ($n), $list['document_program']);
                    //设置边框
//                $currentRowNum = $n+4;
//                $objectPHPExcel->getActiveSheet()->getStyle('A'.($n+4).':D'.$currentRowNum )
//                    ->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $n++;

            }
        }else{
            static $n = 1;
        }
        //合并列
        $objectPHPExcel->getActiveSheet()->setCellValue("A".$n,$n-1);
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.Yii::t('comp_statistics', 'daily_statistics').'-'.date("Ymj").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    public static function sumList()
    {
        $ptw_dir = '/opt/www-nginx/web/filebase/record/2018/01/ptw/pic';
        $ptw_files = array();
        if (@$handle = opendir($ptw_dir)) { //注意这里要加一个@，不然会有warning错误提示：）
            while (($file = readdir($handle)) !== false) {
                if ($file != ".." && $file != ".") { //排除根目录；
                    if (is_dir($ptw_dir . "/" . $file)) { //如果是子文件夹，就进行递归
                        $ptw_files[$file] = my_dir($ptw_dir . "/" . $file);
                    } else { //不然就将文件的名字存入数组；
                        $ptw_files[] = '/opt/www-nginx/web/filebase/record/2018/01/ptw/pic/' . $file;
                    }

                }
            }
            closedir($handle);
        }
            $ptw_sql = "select b.pic from ptw_apply_basic a,bac_check_apply_detail b where a.apply_id = b.apply_id and a.record_time like '2018-01%' ";
            $command = Yii::app()->db->createCommand($ptw_sql);
            $ptw_rows = $command->queryAll();
            foreach($ptw_rows as $cnt => $r){
                if($r['pic'] != '-1' && $r['pic'] != '') {
                    $pic = explode('|', $r['pic']);
                    foreach ($pic as $key => $content) {
                        $ptw_pic[] = $content;
                    }
                }
            }
//        $result=array_diff($ptw_files,$ptw_pic);
//        var_dump($result);
        var_dump($ptw_pic);
        exit;
            $ptw_sum = 0;
            foreach ($ptw_files as $cnt => $data) {
                if (!in_array($data, $ptw_pic)) {
                    var_dump($data);
                    exit;
//                    $ptw_sum += filesize($data)/1024/1024;
//                    $re['PTW'][] = $data;
                }

            }

            $tbm_dir = '/opt/www-nginx/web/filebase/record/2018/01/tbm/pic';
            $tbm_files = array();
            if (@$handle = opendir($tbm_dir)) { //注意这里要加一个@，不然会有warning错误提示：）
                while (($file = readdir($handle)) !== false) {
                    if ($file != ".." && $file != ".") { //排除根目录；
                        if (is_dir($tbm_dir . "/" . $file)) { //如果是子文件夹，就进行递归
                            $tbm_files[$file] = my_dir($tbm_dir . "/" . $file);
                        } else { //不然就将文件的名字存入数组；
                            $tbm_files[] = '/opt/www-nginx/web/filebase/record/2018/01/tbm/pic/' . $file;
                        }

                    }
                }
                closedir($handle);
            }
                $tbm_sql = "select b.pic from tbm_meeting_basic a,bac_check_apply_detail b where a.meeting_id = b.apply_id and a.record_time like '2018-01%' ";
                $command = Yii::app()->db->createCommand($tbm_sql);
                $tbm_rows = $command->queryAll();
                foreach($tbm_rows as $cnt => $r){
                    if($r['pic'] != '-1' && $r['pic'] != '') {
                        $pic = explode('|', $r['pic']);
                        foreach ($pic as $key => $content) {
                            $tbm_pic[] = $content;
                        }
                    }
                }
        $result=array_diff($tbm_files,$tbm_pic);

                $tbm_sum = 0;
                foreach ($tbm_files as $cnt => $data) {
                    if (!in_array($data, $tbm_pic)) {
                        $tbm_sum += filesize($data)/1024/1024;
                        $re['TBM'][] = $data;
                    }

                }

                $train_dir = '/opt/www-nginx/web/filebase/record/2018/01/train/pic';
                $train_files = array();
                if (@$handle = opendir($train_dir)) { //注意这里要加一个@，不然会有warning错误提示：）
                    while (($file = readdir($handle)) !== false) {
                        if ($file != ".." && $file != ".") { //排除根目录；
                            if (is_dir($train_dir . "/" . $file)) { //如果是子文件夹，就进行递归
                                $train_files[$file] = my_dir($train_dir . "/" . $file);
                            } else { //不然就将文件的名字存入数组；
                                $train_files[] = '/opt/www-nginx/web/filebase/record/2018/01/train/pic/' . $file;
                            }

                        }
                    }
                    closedir($handle);
                }
                    $train_sql = "select b.pic from train_apply_basic a,bac_check_apply_detail b where a.training_id = b.apply_id and a.record_time like '2018-01%'  ";
                    $command = Yii::app()->db->createCommand($train_sql);
                    $train_rows = $command->queryAll();
                    foreach($train_rows as $cnt => $r){
                        if($r['pic'] != '-1' && $r['pic'] != '') {
                            $train_pic[] = $r['pic'];
                        }
                    }
                    $train_sum = 0;
                    foreach ($train_files as $cnt => $data) {
                        if (!in_array($data, $train_pic)) {
                            $train_sum += filesize($data)/1024/1024;
                            $re['TRAIN/MEETING'][] = $data;
                        }

                    }



        $inspection_dir = '/opt/www-nginx/web/filebase/record/2018/01/wsh/pic';
        $inspection_files = array();
        if (@$handle = opendir($inspection_dir)) { //注意这里要加一个@，不然会有warning错误提示：）
            while (($file = readdir($handle)) !== false) {
                if ($file != ".." && $file != ".") { //排除根目录；
                    if (is_dir($inspection_dir . "/" . $file)) { //如果是子文件夹，就进行递归
                        $inspection_files[$file] = my_dir($inspection_dir . "/" . $file);
                    } else { //不然就将文件的名字存入数组；
                        $inspection_files[] = '/opt/www-nginx/web/filebase/record/2018/01/wsh/pic/' . $file;
                    }

                }
            }
            closedir($handle);
        }
        $inspection_sql = "select b.pic from bac_safety_check a,bac_safety_check_detail b where a.check_id = b.check_id and a.apply_time like '2018-01%' ";
        $command = Yii::app()->db->createCommand($inspection_sql);
        $inspection_rows = $command->queryAll();
        foreach($inspection_rows as $cnt => $r){
            if($r['pic'] != '-1' && $r['pic'] != '') {
                $inspection_pic[] = $r['pic'];
            }
        }
        $inspection_sum = 0;
        foreach ($inspection_files as $cnt => $data) {
            if (!in_array($data, $inspection_pic)) {
                $inspection_sum += filesize($data)/1024/1024;
                $re['INSPECTION'][] = $data;
            }

        }
            var_dump($re);
            exit;
            //PTW
//        $ptw_sql = "select b.pic from ptw_apply_basic a,bac_check_apply_detail b where a.apply_id = b.apply_id and a.record_time like '2018-01-21%' ";
//        $command = Yii::app()->db->createCommand($ptw_sql);
//        $ptw_rows = $command->queryAll();
//        $ptw_sum = 0;
//        foreach($ptw_rows as $i => $j){
//            if($j['pic'] != '-1' && $j['pic'] != ''){
//                if (file_exists($j['pic'])) {
//                    $ptw_sum += filesize($j['pic']);
//                }
//            }
//        }
//
//        //TBM
//        $tbm_sql = "select b.pic from tbm_meeting_basic a,bac_check_apply_detail b where a.meeting_id = b.apply_id and a.record_time like '2018-01-21%' ";
//        $command = Yii::app()->db->createCommand($tbm_sql);
//        $tbm_rows = $command->queryAll();
//        $tbm_sum = 0;
//        foreach($tbm_rows as $i => $j){
//            if($j['pic'] != '-1' && $j['pic'] != ''){
//                if (file_exists($j['pic'])) {
//                    $tbm_sum += filesize($j['pic']);
//                }
//            }
//        }
//
//        //培训
//        $train_sql = "select b.pic from train_apply_basic a,bac_check_apply_detail b where a.training_id = b.apply_id and a.record_time like '2018-01-21%' and a.module_type = '1' ";
//        $command = Yii::app()->db->createCommand($train_sql);
//        $train_rows = $command->queryAll();
//        $train_sum = 0;
//        foreach($train_rows as $i => $j){
//            if($j['pic'] != '-1' && $j['pic'] != ''){
//                if (file_exists($j['pic'])) {
//                    $train_sum += filesize($j['pic']);
//                }
//            }
//        }
//
//        //会议
//        $meeting_sql = "select b.pic from train_apply_basic a,bac_check_apply_detail b where a.training_id = b.apply_id and a.record_time like '2018-01-21%' and a.module_type = '2' ";
//        $command = Yii::app()->db->createCommand($meeting_sql);
//        $meeting_rows = $command->queryAll();
//        $meeting_sum = 0;
//        foreach($meeting_rows as $i => $j){
//            if($j['pic'] != '-1' && $j['pic'] != ''){
//                if (file_exists($j['pic'])) {
//                    $meeting_sum += filesize($j['pic']);
//                }
//            }
//        }
//
//        //安全检查
//        $inspection_sql = "select b.pic from bac_safety_check a,bac_safety_check_detail b where a.check_id = b.check_id and a.apply_time like '2018-01-21%' ";
//        $command = Yii::app()->db->createCommand($inspection_sql);
//        $inspection_rows = $command->queryAll();
//        $inspection_sum = 0;
//        foreach($inspection_rows as $i => $j){
//            if($j['pic'] != '-1' && $j['pic'] != ''){
//                if (file_exists($j['pic'])) {
//                    $inspection_sum += filesize($j['pic']);
//                }
//            }
//        }
//
//        //意外
//        $acci_sql = "select a.acci_pic,b.pic from accident_basic a,accident_sick_leave b where a.apply_id = b.apply_id and a.record_time like '2018-01-21%' ";
//        $command = Yii::app()->db->createCommand($acci_sql);
//        $acci_rows = $command->queryAll();
//        $acci_sum = 0;
//        foreach($acci_rows as $i => $j){
//            if($j['acci_pic'] != '-1' && $j['acci_pic'] != ''){
//                if (file_exists($j['acci_pic'])) {
//                    $acci_sum += filesize($j['acci_pic']);
//                }
//            }
//            if($j['pic'] != '-1' && $j['pic'] != ''){
//                if (file_exists($j['pic'])) {
//                    $acci_sum += filesize($j['pic']);
//                }
//            }
//        }
//
//        //QAQC
//        $qaqc_sql = "select b.pic from bac_qaqc_basic a,bac_qaqc_detail b where a.check_id = b.check_id and a.apply_time like '2018-01-21%' ";
//        $command = Yii::app()->db->createCommand($qaqc_sql);
//        $qaqc_rows = $command->queryAll();
//        $qaqc_sum = 0;
//        foreach($qaqc_rows as $i => $j){
//            if($j['pic'] != '-1' && $j['pic'] != ''){
//                if (file_exists($j['pic'])) {
//                    $qaqc_sum += filesize($j['pic']);
//                }
//            }
//        }
//        $sum = $ptw_sum+$tbm_sum+$train_sum+$meeting_sum+$inspection_sum+$acci_sum+$qaqc_sum;
//        var_dump($sum);
//            exit;

    }
    public static function fileSum($file) {
        $sum = 0;
        $pic = explode('|', $file);
        foreach($pic as $i => $data) {
            if (file_exists($data)) {
                $sum += filesize($data);
            }
        }
        return $sum;
    }
    /*
     * 2017年流程图片 ，文档，人员，设备属性图片合计大小（按公司）
     */
    public static function queryList() {

        $file = array(
            'document' => array(
                '0' => '/opt/www-nginx/web/filebase/company/',//公司级文档
                '1' => '/opt/www-nginx/web/filebase/program/',//项目级文档
            ),
            'photo' => array(
                '0' => '/opt/www-nginx/web/filebase/data/staff/',//人员标签页图片，证书
                '1' => '/opt/www-nginx/web/filebase/data/face/',//人员头像
                '2' => '/opt/www-nginx/web/filebase/data/device/',//设备照片，证书
                '3' => '/opt/www-nginx/web/filebase/data/qrcode/',//人员，设备二维码
            ),
            'appupload' => array(
                '0' => '/opt/www-nginx/appupload',
            )
        );
        $contractor_list = Contractor::compList();//承包商列表
        $num = 0;
        //main_cnt
        $main_sql = "select count(program_id) as cnt,contractor_id from bac_program where program_id = root_proid and status = 00 group by contractor_id";
        $command = Yii::app()->db->createCommand($main_sql);
        $main_rows = $command->queryAll();
        //FILE
        $file_sql = "select sum(a.file_size) as file_size,b.contractor_id from stats_date_app a,bac_program b where a.date >='2017-04' and a.date<='2017-11' and a.root_proid = b.program_id group by b.contractor_id";
        $command = Yii::app()->db->createCommand($file_sql);
        $file_rows = $command->queryAll();
        //12月份统计
        $twfile_sql = "select a.file_size,b.contractor_id from stats_date_app a,bac_program b where a.date like '2017-12' and a.root_proid = b.program_id ";
        $command = Yii::app()->db->createCommand($twfile_sql);
        $twfile_rows = $command->queryAll();
        //PTW
//        $ptw_sql = "select c.contractor_id as con_id, a.pic,b.save_path
//                  FROM bac_check_apply_detail a
//                  JOIN ptw_apply_basic b ON a.apply_id = b.apply_id
//                  JOIN bac_staff c ON a.deal_user_id = c.user_id
//                  where a.apply_time < '2018-01-01' and a.pic not in ('', 'nil', '-1', '-2', '-3')";
//        $command = Yii::app()->db->createCommand($ptw_sql);
//        $ptw_rows = $command->queryAll();
//
//        //TRAIN/MEETING
//        $train_sql = "select c.contractor_id as con_id, a.pic,b.pic as train_pic
//                    FROM bac_check_apply_detail a
//                    JOIN train_apply_basic b ON a.apply_id = b.training_id
//                    JOIN bac_staff c ON a.deal_user_id = c.user_id
//                    where a.apply_time < '2018-01-01' and a.pic not in ('', 'nil', '-1', '-2', '-3') ";
//        $command = Yii::app()->db->createCommand($train_sql);
//        $train_rows = $command->queryAll();
//
//        //TBM
//        $tbm_sql = "select c.contractor_id as con_id, a.pic
//                  FROM bac_check_apply_detail a
//                  JOIN tbm_meeting_basic b ON a.apply_id = b.meeting_id
//                  JOIN bac_staff c ON a.deal_user_id = c.user_id
//                  where a.apply_time < '2018-01-01' and a.pic not in ('', 'nil', '-1', '-2', '-3')  ";
//        $command = Yii::app()->db->createCommand($tbm_sql);
//        $tbm_rows = $command->queryAll();
//        //RA
//        $ra_sql = "select contractor_id as con_id, CONCAT(ra_path,'|',swp_path) as pic
//                  FROM ra_swp_basic
//                  where record_time like '%s'";
//        $command = Yii::app()->db->createCommand($ra_sql);
//        $ra_rows = $command->queryAll();
//        //INSPECTION
//        $inspection_sql = "select b.root_proid, b.contractor_id as con_id, a.pic
//                          FROM bac_safety_check_detail a
//                          JOIN bac_safety_check b ON a.check_id = b.check_id
//                          where a.record_time < '2018-01-01' and a.deal_type='1' and a.pic not in ('', 'nil', '-1', '-2', '-3')
//                          UNION
//                            select b.root_proid, c.contractor_id as con_id, a.pic
//                          FROM bac_safety_check_detail a
//                          JOIN bac_safety_check b ON a.check_id = b.check_id
//                          JOIN bac_staff c ON b.person_in_charge_id = c.user_id
//                          where a.record_time < '2018-01-01' and a.deal_type='2' and a.pic not in ('', 'nil', '-1', '-2', '-3') ";
//        $command = Yii::app()->db->createCommand($inspection_sql);
//        $inspection_rows = $command->queryAll();
//
//        //ACCIDENT
//        $acci_sql = "select c.contractor_id as con_id, a.pic,b.acci_pic
//                    FROM bac_check_apply_detail a
//                    JOIN accident_basic b ON a.apply_id = b.apply_id
//                    JOIN bac_staff c ON a.deal_user_id = c.user_id
//                    where a.apply_time < '2018-01-01' and a.pic not in ('', 'nil', '-1', '-2', '-3') ";
//        $command = Yii::app()->db->createCommand($acci_sql);
//        $acci_rows = $command->queryAll();

            foreach ($contractor_list as $contractor_id => $contractor_name) {
                $data[$num]['contractor_id'] = $contractor_id;
                $data[$num]['flow_pic'] = 0;
                //FILE总计
                foreach($file_rows as $r =>$t){
                    if($contractor_id == $t['contractor_id'] ){
                        $data[$num]['flow_pic'] += $t['file_size'] / 1024;
                    }
                }
                //2017年12月份数据
                foreach($twfile_rows as $x => $y){
                    if($contractor_id == $y['contractor_id']){
                        $data[$num]['flow_pic'] += $y['file_size'] / 1024;
                    }
                }
                foreach($main_rows as $m => $n){
                    if($contractor_id == $n['contractor_id']){
                        $pro_cnt = $n['cnt'];
                    }
                }
//                //PTW
//                $pic_sum = 0;
//                foreach ($ptw_rows as $i => $j) {
//                    $pic = explode('|', $j['pic']);
//                    if ($j['con_id'] == $contractor_id) {
//                        $pic_sum += self::fileSum($j['pic']);
//
//                    }
//                }
//                $data[$num]['flow_pic'] += $pic_sum / 1024 / 1024;
//                //TBM
//                $tbm_sum = 0;
//                foreach ($tbm_rows as $i => $j) {
//                    if ($j['con_id'] == $contractor_id) {
//                        $tbm_sum += self::fileSum($j['pic']);
//                    }
//                }
//                $data[$num]['flow_pic'] += $tbm_sum / 1024 / 1024;
//                //培训
//                $train_sum = 0;
//                foreach ($train_rows as $i => $j) {
//                    if ($j['con_id'] == $contractor_id) {
//                        $train_sum += self::fileSum($j['pic']);
//                        $train_sum += self::fileSum($j['train_pic']);
//                    }
//                }
//                $data[$num]['flow_pic'] += $train_sum / 1024 / 1024;
//                //安全检查
//                $inspection_sum = 0;
//                foreach ($inspection_rows as $i => $j) {
//                    if ($j['con_id'] == $contractor_id) {
//                        $inspection_sum += self::fileSum($j['pic']);
//                    }
//                }
//                $data[$num]['flow_pic'] += $inspection_sum / 1024 / 1024;
//                //RA
//                $ra_sum = 0;
//                foreach ($ra_rows as $i => $j) {
//                    if ($j['con_id'] == $contractor_id) {
//                        $inspection_sum += self::fileSum($j['pic']);
//                    }
//                }
//                $data[$num]['flow_pic'] += $inspection_sum / 1024 / 1024;
//                //意外
//                $acci_sum = 0;
//                foreach ($acci_rows as $i => $j) {
//                    if ($j['con_id'] == $contractor_id) {
//                        $acci_sum += self::fileSum($j['acci_pic']);
//                        $acci_sum += self::fileSum($j['pic']);
//                    }
//                }
//                $data[$num]['flow_pic'] += $acci_sum / 1024 / 1024;
                //QAQC
//                $qaqc_sql = "select b.pic from bac_qaqc_basic a,bac_qaqc_detail b where a.check_id = b.check_id and a.contractor_id = '" . $contractor_id . "' and a.apply_time <='2018-01-09' ";
//                $command = Yii::app()->db->createCommand($qaqc_sql);
//                $qaqc_rows = $command->queryAll();
//                $qaqc_sum = 0;
//                foreach ($qaqc_rows as $i => $j) {
//                    if ($j['pic'] != '-1' && $j['pic'] != '') {
//                        if (file_exists($j['pic'])) {
//                            $qaqc_sum += filesize($j['pic']);
//                        }
//                    }
//                }
//                $data[$num]['flow_pic'] += $qaqc_sum / 1024 / 1024;
                $data[$num]['flow_pic'] = round($data[$num]['flow_pic'], 2);
                $data[$num]['attribute'] = 0;
                //人员各种证书
                $staff_cert_path = $file['photo'][0] . $contractor_id . '/';
                if (file_exists($staff_cert_path)) {
                    $staff_cert_str = exec('du -s ' . $staff_cert_path);
                    $staff_cert_ar = explode('/', $staff_cert_str);
                    $staff_cert_size = $staff_cert_ar[0];
                    $data[$num]['attribute'] += $staff_cert_size / 1024;
                }
                //人员头像
                $staff_face_path = $file['photo'][1] . $contractor_id . '/';
                if (file_exists($staff_face_path)) {
                    $staff_face_str = exec('du -s ' . $staff_face_path);
                    $staff_face_ar = explode('/', $staff_face_str);
                    $staff_face_size = $staff_face_ar[0];
                    $data[$num]['attribute'] += $staff_face_size / 1024;
                }
                //设备照片+设备证书
                $device_path = $file['photo'][2] . $contractor_id . '/';
                if (file_exists($device_path)) {
                    $device_str = exec('du -s ' . $device_path);
                    $device_ar = explode('/', $device_str);
                    $device_size = $device_ar[0];
                    $data[$num]['attribute'] += $device_size / 1024;
                }
                //二维码
                $qrcode_path = $file['photo'][3] . $contractor_id . '/';
                if (file_exists($qrcode_path)) {
                    $qrcode_str = exec('du -s ' . $qrcode_path);
                    $qrcode_ar = explode('/', $qrcode_str);
                    $qrcode_size = $qrcode_ar[0];
                    $data[$num]['attribute'] += $qrcode_size / 1024;
                }
                $data[$num]['attribute'] = round($data[$num]['attribute'], 2);
                //公司级文档
                $company_document = $file['document'][0] . $contractor_id . '/';
                if (file_exists($company_document)) {
                    $company_str = exec('du -s ' . $company_document);
                    $company_ar = explode('/', $company_str);
                    $company_size = $company_ar[0];
                    $data[$num]['document'] += $company_size / 1024;
                }
                //项目级文档
                $program_document_sql = "select sum(doc_size) as doc_size from bac_document where  type='4' and contractor_id = '" . $contractor_id . "' ";
                $command = Yii::app()->db->createCommand($program_document_sql);
                $program_document_rows = $command->queryAll();
                $program_document_sum = 0;

                $data[$num]['document'] += $program_document_rows[0]['doc_size']/1024;
                $data[$num]['document'] = round($data[$num]['document'], 2);
                $data[$num]['total_size'] = $data[$num]['flow_pic'] + $data[$num]['attribute'] + $data[$num]['document'];
                $data[$num]['max_size'] = $pro_cnt * 2 *1024+2*1024;
                $num++;
            }
//        var_dump($data);
//        exit;

//            Yii::app()->fcache->set('filecache', $data,30);
//            $data = Yii::app()->fcache->get('filecache');
//        }
        foreach($data as $id => $list){
            $total_size = $list['flow_pic']+$list['attribute']+$list['document'];
            $record_time = date('Y-m-d H:i:s');
            $sql = 'INSERT INTO stats_contractor_store(contractor_id,flow_pic_size,attribute_size,document_size,total_size,max_size,statistics_date) VALUES(:contractor_id,:flow_pic_size,:attribute_size,:document_size,:total_size,:max_size,:statistics_date)';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":contractor_id", $list['contractor_id'], PDO::PARAM_STR);
            $command->bindParam(":flow_pic_size", $list['flow_pic'], PDO::PARAM_STR);
            $command->bindParam(":attribute_size", $list['attribute'], PDO::PARAM_STR);
            $command->bindParam(":document_size", $list['document'], PDO::PARAM_STR);
            $command->bindParam(":total_size", $total_size, PDO::PARAM_STR);
            $command->bindParam(":max_size", $list['max_size'], PDO::PARAM_STR);
            $command->bindParam(":statistics_date", $record_time, PDO::PARAM_STR);
            $rs1 = $command->execute();
//            $total_size = $list['flow_pic']+$list['attribute']+$list['document'];
//            $sql = "update stats_contractor_store set total_size = '".$total_size."' where contractor_id = '".$list['contractor_id']."'";
//            $command = Yii::app()->db->createCommand($sql);
//            $rs1 = $command->execute();
        }
        var_dump(11111111);
        exit;
        $start=$page*$pageSize; #计算每次分页的开始位置
//        var_dump($page);
//        var_dump($pageSize);
        $count = count($data);
        $pagedata=array();
        $pagedata=array_slice($data,$start,$pageSize);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;

        return $rs;
    }
    /*
     * 把bac_staff_info表中的记录 移到 bac_aptitude 中
     */
    public static function updateInfo() {
        $sql = "select * from bac_aptitude where record_time like '2018-01-31%' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $num => $r){
            $name = substr($r['aptitude_photo'],25);
            $src = '/opt/www-nginx/web'.$r['aptitude_photo'];
            $file_name = explode('.',$name);
            $aptitude_name = $file_name[0];
            $aptitude_type = $file_name[1];


            if(file_exists($src)){
                $size = filesize($src)/1024;
                $aptitude_size = sprintf('%.2f',$size);
                $model = UserAptitude::model()->findByPk($r['aptitude_id']);
                try {
                    $model->aptitude_name = $aptitude_name;
                    $model->aptitude_type = $aptitude_type;
                    $model->aptitude_size = $aptitude_size;
                    $result = $model->save();
                    if ($result) {
                        var_dump('YES');
                        $r['status'] = 1;
                        $r['msg'] = Yii::t('common', 'success_update');
                        $r['refresh'] = true;
                    } else {
                        $r['status'] = (string)-1;
                        $r['msg'] = Yii::t('common', 'error_update');
                        $r['refresh'] = false;
                    }
                } catch (Exception $e) {
                    //$trans->rollBack();
                    $r['status'] = -1;
                    $r['msg'] = $e->getmessage();
                    $r['refresh'] = false;
                }
            }
        }
    }
}

