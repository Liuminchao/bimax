<?php

class UploadController extends AuthBaseController {
    
    public $title_rows = 6;
    //public $per_read_cnt = 5;
    
    //图片对应的存储文件路径
    private static function TypePath(){
        return array(
            'face_img'  =>  'face',
            'ppt_photo' =>  'staff',
            'home_id_photo' => 'staff',
            'bca_photo' =>  'cert',
            'csoc_photo'=>  'cert',
            'task_attach'=> 'cert',
        );
    }
    //图片对应的存储文件后缀名称
    private static function TypeSuffix(){
        return array(
            'face_img'  =>  'face',
            'ppt_photo' =>  'pass',
            'home_id_photo' => 'per',
            'bca_photo' =>  'bca',
            'csoc_photo'=>  'csoc',
            'task_attach'=> 'task',
        );
    }
    //表头数组
    private static function Exceltitle(){
        return array(
            'A' => array('field'=> 'user_name','type'=> 'string','array'=> 'staff','title_cn' => '员工名称','title_en'=>'Name'),
            'B' => array('field' => 'user_phone','type'=> 'string','array'=> 'staff','title_cn' => '手机号码','title_en'=>'Mobile No.'),
            'C' => array('field' => 'work_pass_type','type'=> 'string','array'=> 'staff','title_cn' => '准证类型','title_en'=>'ID/Pass Type'),
            'D' => array('field' => 'work_no','type'=> 'string','array'=> 'staff','title_cn' => '准证编号','title_en'=>'ID/Pass No.'),
            'E' => array('field' => 'role_id','type'=> 'string','array'=> 'staff','title_cn' => '岗位','title_en'=>'Designation'),
            'F' => array('field' => 'nation_type','type'=> 'string','array'=> 'staff','title_cn' => '国籍类型','title_en'=>'Nationality'),
            'G' => array('field' => 'primary_email','type'=> 'string','array'=> 'staff','title_cn' => '常用邮箱','title_en'=>'Email Address'),
            'H' => array('field' => 'bca_company','type'=> 'string','array'=> 'staffinfo','title_cn' => '雇主名称','title_en'=>'Employer Name'),
            'I' => array('field' => 'working_life','type'=> 'string','array'=> 'staff','title_cn' => '工作年限','title_en'=>'Years of Working Experience'),
            'J' => array('field' => 'bca_levy_rate','type'=> 'string','array'=> 'staffinfo','title_cn' =>'劳工税','title_en'=>'Levy Rate'),
            'K' => array('field' => 'service_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'加入日期','title_en'=>'Joined Date'),
            'L' => array('field' => 'category','type'=> 'string','array'=> 'staff','title_cn' =>'类别','title_en'=>'Category'),
            'M' => array('field' => 'skill','type'=> 'string','array'=> 'staffinfo','title_cn' =>'工人类型','title_en'=>'Type of Worker'),
            'N' => array('field' => 'face_img','type'=> 'photo','array'=> 'staffinfo','title_cn' => '照片','title_en'=>'Photo'),
            'O' => array('field' => 'name_cn','type'=> 'string','array'=> 'staffinfo','title_cn' => '姓名（中文）','title_en'=>'Name(Chinese)'),
            'P' => array('field' => 'first_name','type'=> 'string','array'=> 'staffinfo','title_cn' => '姓名（英文）','title_en'=>'First Name'),
            //'K' => array('field' => 'family_name','type'=> 'string','array'=> 'staffinfo','title_cn' => '姓（英文）','title_en'=>'Family Name'),
            'Q' => array('field' => 'gender','type'=> 'string','array'=> 'staffinfo','title_cn' => '性别','title_en'=>'Gender'),
            'R' => array('field' => 'birth_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '出生日期','title_en'=>'Birth Date'),
            'S' => array('field' => 'race','type'=> 'string','array'=> 'staffinfo','title_cn' =>'种族','title_en'=>'Race'),
            'T' => array('field' => 'marital','type'=> 'string','array'=> 'staffinfo','title_cn' => '婚姻状态','title_en'=>'Marital Status'),
            'U' => array('field' => 'previous_designation','type'=> 'string','array'=> 'staffinfo','title_cn' => '之前的行业经验与岗位','title_en'=>'Previous Industry Experience & Designation'),
           // 'N' => array('field' => 'nationality','type'=> 'string','array'=> 'staffinfo','title_cn' => '国籍','title_en'=>'Nationality'),
            'V' => array('field' => 'home_address','type'=> 'string','array'=> 'staffinfo','title_cn' => '国内家庭地址','title_en'=>'Home Country Address'),
            'W' => array('field' => 'home_contact','type'=> 'string','array'=> 'staffinfo','title_cn' => '国内第二联系人姓名','title_en'=>'Home Country Contact Name'),
            'X' => array('field' => 'relationship','type'=> 'string','array'=> 'staffinfo','title_cn' => '关系','title_en'=>'Relationship'),
            'Y' => array('field' => 'home_phone','type'=> 'string','array'=> 'staffinfo','title_cn' => '联系电话','title_en'=>'Home Country Contact Number'),
//            'R' => array('field' => 'home_id_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' => '身份证照片','title_en'=>'China Photo'),
//            'S' => array('field' => 'home_id','type'=> 'string','array'=> 'staffinfo','title_cn' => '国内身份信息','title_en'=>'China ID.'),
            'Z' => array('field' => 'sg_phone','type'=> 'string','array'=> 'staffinfo','title_cn' => '新加坡联系方式','title_en'=>'Singapore Contact No.'),
            'AA' => array('field' => 'sg_address','type'=> 'string','array'=> 'staffinfo','title_cn' => '新加坡住址','title_en'=>'Singapore Address'),
            'AB' => array('field' => 'sg_postal_code','type'=> 'string','array'=> 'staffinfo','title_cn' => '邮政编码','title_en'=>'Postal Code'),
//            'W' => array('field' => 'ppt_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' => '护照照片','title_en'=>'Passport Photo'),
//            'X' => array('field' => 'passport_no','type'=> 'string','array'=> 'staffinfo','title_cn' => '护照号码','title_en'=>'Passport No.'),
//            'Y' => array('field' => 'ppt_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '护照有效期','title_en'=>'Expired Date'),
//            'Z' => array('field' => 'ppt_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '签发日期','title_en'=>'Issue Date'),
//            'Z' => array('field' => 'bca_company_uen','type'=> 'string','array'=> 'staffinfo','title_cn' => '公司编号','title_en'=>'UEN'),
            //'AD' => array('field' => 'bca_pass_type','type'=> 'string','array'=> 'staffinfo','title_cn' => '准证类型 (Singaporean/PR/DP/EP/SP/WP)','title_en'=>'Pass Type (Singaporean/PR/DP/EP/SP/WP)'),
//            'AC' => array('field' => 'bca_pass_no','type'=> 'string','array'=> 'staffinfo','title_cn' => '准证号码','title_en'=>'Pass No.'),
//            'AA' => array('field' => 'bca_trade','type'=> 'string','array'=> 'staffinfo','title_cn' => '专业','title_en'=>'Trade'),
//            'AB' => array('field' => 'bca_apply_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '申请日期','title_en'=>'Application Date'),
//            'AA' => array('field' => 'bca_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '签发日期','title_en'=>'Issue Date'),
//            'AB' => array('field' => 'bca_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '截止日期','title_en'=>'Expire Date'),
//            'AC' => array('field' => 'bca_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' => '证件照片','title_en'=>'ID/Pass Photo'),
//            'AD' => array('field' => 'csoc_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'安全证号码','title_en'=>'CSOC Number'),
//            'AE' => array('field' => 'csoc_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
//            'AF' => array('field' => 'csoc_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
//            'AG' => array('field' => 'csoc_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' =>'安全证（CSOC）照片','title_en'=>'CSOC Photo'),
            'AC' => array('field' => 'ins_scy_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'入境险号码','title_en'=>'Security Bond NO.'),
            'AD' => array('field' => 'ins_scy_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
            'AE' => array('field' => 'ins_scy_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
            'AF' => array('field' => 'ins_med_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'医疗险号码','title_en'=>'Medical Insurance No.'),
            'AG' => array('field' => 'ins_med_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
            'AH' => array('field' => 'ins_med_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
            'AI' => array('field' => 'ins_adt_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'意外险号码','title_en'=>'Accident Insurance NO.'),
            'AG' => array('field' => 'ins_adt_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
            'AK' => array('field' => 'ins_adt_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
            'AL' => array('field' => 'app','type'=> 'string','array'=> 'staff','title_cn' =>'App Seeting','title_en'=>'App Seeting'),
        );
    }


    //表头数组
    private static function Excelhead(){
        return array(
            'A' => array('field'=> 'user_name','type'=> 'string','array'=> 'staff','title_cn' => '员工名称','title_en'=>'Name'),
            'B' => array('field' => 'user_phone','type'=> 'string','array'=> 'staff','title_cn' => '手机号码','title_en'=>'Mobile No.'),
            'C' => array('field' => 'work_pass_type','type'=> 'string','array'=> 'staff','title_cn' => '准证类型','title_en'=>'ID/Pass Type'),
            'D' => array('field' => 'work_no','type'=> 'string','array'=> 'staff','title_cn' => '准证编号','title_en'=>'ID/Pass No.'),
            'E' => array('field' => 'role_id','type'=> 'string','array'=> 'staff','title_cn' => '岗位','title_en'=>'Position'),
            'F' => array('field' => 'nation_type','type'=> 'string','array'=> 'staff','title_cn' => '国籍类型','title_en'=>'Nationality'),
            'G' => array('field' => 'primary_email','type'=> 'string','array'=> 'staff','title_cn' => '常用邮箱','title_en'=>'Email Address'),
            'H' => array('field' => 'working_life','type'=> 'string','array'=> 'staff','title_cn' => '工作年限','title_en'=>'Years of Working Experience'),
            'I' => array('field' => 'skill','type'=> 'string','array'=> 'staffinfo','title_cn' => '技能','title_en'=>'Skill'),
            'J' => array('field' => 'category','type'=> 'string','array'=> 'staff','title_cn' => '分类','title_en'=>'Category'),
            'K' => array('field' => 'qrcode','type'=> 'photo','array'=> 'staff','title_cn' => '二维码','title_en'=>'Qr code'),
            'L' => array('field' => 'face_img','type'=> 'photo','array'=> 'staffinfo','title_cn' => '照片','title_en'=>'Photo'),
            'M' => array('field' => 'name_cn','type'=> 'string','array'=> 'staffinfo','title_cn' => '姓名（中文）','title_en'=>'Name(Chinese)'),
            'N' => array('field' => 'first_name','type'=> 'string','array'=> 'staffinfo','title_cn' => '姓名（英文）','title_en'=>'First Name'),
            //'K' => array('field' => 'family_name','type'=> 'string','array'=> 'staffinfo','title_cn' => '姓（英文）','title_en'=>'Family Name'),
            'O' => array('field' => 'gender','type'=> 'string','array'=> 'staffinfo','title_cn' => '性别','title_en'=>'Gender'),
            'P' => array('field' => 'birth_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '出生日期','title_en'=>'Birth Date'),
            // 'N' => array('field' => 'nationality','type'=> 'string','array'=> 'staffinfo','title_cn' => '国籍','title_en'=>'Nationality'),
            'Q' => array('field' => 'home_address','type'=> 'string','array'=> 'staffinfo','title_cn' => '国内家庭地址','title_en'=>'Home Country Address'),
            'R' => array('field' => 'home_contact','type'=> 'string','array'=> 'staffinfo','title_cn' => '国内第二联系人姓名','title_en'=>'Home Country Contact Name'),
            'S' => array('field' => 'relationship','type'=> 'string','array'=> 'staffinfo','title_cn' => '关系','title_en'=>'Relationship'),
            'T' => array('field' => 'home_phone','type'=> 'string','array'=> 'staffinfo','title_cn' => '联系电话','title_en'=>'Home Country Contact Number'),
//            'R' => array('field' => 'home_id_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' => '身份证照片','title_en'=>'China Photo'),
//            'S' => array('field' => 'home_id','type'=> 'string','array'=> 'staffinfo','title_cn' => '国内身份信息','title_en'=>'China ID.'),
            'U' => array('field' => 'sg_phone','type'=> 'string','array'=> 'staffinfo','title_cn' => '新加坡联系方式','title_en'=>'Singapore Contact No.'),
            'V' => array('field' => 'sg_address','type'=> 'string','array'=> 'staffinfo','title_cn' => '新加坡住址','title_en'=>'Singapore Address'),
            'W' => array('field' => 'sg_postal_code','type'=> 'string','array'=> 'staffinfo','title_cn' => '邮政编码','title_en'=>'Postal Code'),
            'X' => array('field' => 'ppt_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' => '护照照片','title_en'=>'Passport Photo'),
            'Y' => array('field' => 'passport_no','type'=> 'string','array'=> 'staffinfo','title_cn' => '护照号码','title_en'=>'Passport No.'),
            'Z' => array('field' => 'ppt_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '护照有效期','title_en'=>'Expired Date'),
            'AA' => array('field' => 'ppt_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '签发日期','title_en'=>'Issue Date'),
            'AB' => array('field' => 'bca_company','type'=> 'string','array'=> 'staffinfo','title_cn' => '雇主名称','title_en'=>'Employer Name'),
            'AC' => array('field' => 'bca_company_uen','type'=> 'string','array'=> 'staffinfo','title_cn' => '公司编号','title_en'=>'UEN'),
            //'AD' => array('field' => 'bca_pass_type','type'=> 'string','array'=> 'staffinfo','title_cn' => '准证类型 (Singaporean/PR/DP/EP/SP/WP)','title_en'=>'Pass Type (Singaporean/PR/DP/EP/SP/WP)'),
//            'AC' => array('field' => 'bca_pass_no','type'=> 'string','array'=> 'staffinfo','title_cn' => '准证号码','title_en'=>'Pass No.'),
            'AD' => array('field' => 'bca_trade','type'=> 'string','array'=> 'staffinfo','title_cn' => '专业','title_en'=>'Trade'),
            'AE' => array('field' => 'bca_apply_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '申请日期','title_en'=>'Application Date'),
            'AF' => array('field' => 'bca_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '签发日期','title_en'=>'Issue Date'),
            'AG' => array('field' => 'bca_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' => '截止日期','title_en'=>'Expire Date'),
            'AH' => array('field' => 'bca_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' => '证件照片','title_en'=>'ID/Pass Photo'),
            'AI' => array('field' => 'bca_levy_rate','type'=> 'string','array'=> 'staffinfo','title_cn' =>'劳工税','title_en'=>'Levy Rate'),
            'AJ' => array('field' => 'csoc_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'安全证号码','title_en'=>'CSOC Number'),
            'AK' => array('field' => 'csoc_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
            'AL' => array('field' => 'csoc_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
            'AM' => array('field' => 'csoc_photo','type'=> 'photo','array'=> 'staffinfo','title_cn' =>'安全证（CSOC）照片','title_en'=>'CSOC Photo'),
            'AN' => array('field' => 'ins_scy_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'入境险号码','title_en'=>'Security Bond NO.'),
            'AO' => array('field' => 'ins_scy_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
            'AP' => array('field' => 'ins_scy_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
            'AQ' => array('field' => 'ins_med_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'医疗险号码','title_en'=>'Medical Insurance No.'),
            'AR' => array('field' => 'ins_med_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
            'AS' => array('field' => 'ins_med_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
            'AT' => array('field' => 'ins_adt_no','type'=> 'string','array'=> 'staffinfo','title_cn' =>'意外险号码','title_en'=>'Accident Insurance NO.'),
            'AU' => array('field' => 'ins_adt_issue_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'签发日期','title_en'=>'Issue Date'),
            'AV' => array('field' => 'ins_adt_expire_date','type'=> 'date','array'=> 'staffinfo','title_cn' =>'截止日期','title_en'=>'Expire Date'),
        );
    }

    
    public function actionDownload()
    {   
        $file_name = "./template/excel/Staff.xls";
        $file = fopen($file_name, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($file_name));
        header('Content-Transfer-Encoding: binary');
//        $name = "员工信息表导入模版".date('YmdHis').".xls";
        if (Yii::app()->language == 'zh_CN') {
            $name = "员工信息表导入模版".".xls";
        }else{
            $name = "BatchingTemplate".".xls";
        }

        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($file_name));
        fclose($file);
    }
    public function actionView() {
        $this->renderPartial("batch");
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
        $this->ReadPic($filename, $objPHPExcel);
            
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
        
        
        //图片类型
        $type_path = self::TypePath();
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
            $staff = array();
            $staffinfo = array();
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
                
                if($rowKey[$colIndex]['array']=='staff'){
                    $staff[$key] = $cell;

                }
                if($rowKey[$colIndex]['array']=='staffinfo'){
                    $staffinfo[$key] = $cell;
                }
            }
            //var_dump($staff);var_dump($staffinfo);
            if(!empty($staff)){
                if($staff['nation_type'] != ''){
                    $staffinfo['nationality'] = $staff['nation_type'];
                }
                if($staff['work_pass_type'] != ''){
                    $staffinfo['bca_pass_type'] = $staff['work_pass_type'];
                }

//                if($staffinfo['csoc_photo'] == ''){
//                    $r['msg'] = Yii::t('comp_staff', 'Error bca_pic is null');
//                    $r['status'] = -1;
//                    $r['refersh'] = false;
//                    $rs[$rowIndex] = $r;
//                    goto end;
//                }
//
//                if($staffinfo['bca_photo'] == ''){
//                    $r['msg'] = Yii::t('comp_staff', 'Error csoc_pic is null');
//                    $r['status'] = -1;
//                    $r['refersh'] = false;
//                    $rs[$rowIndex] = $r;
//                    goto end;
//                }
//                var_dump($staffinfo);
//                exit;
                $staffinfo['tag'] = 1;
                $rs[$rowIndex] = Staff::insertStaff($staff, $staffinfo);
            }
        }
        //var_dump($rs);
        end:
        print_r(json_encode($rs));
    }
    
    //导出员工信息表
    public function actionExport(){
        $args = $_GET['q'];
        $args['status'] = '0';
//        var_dump($args);
//        exit;
        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $staff = Staff::staffExport($args);

        $category = Staff::Category();
        if (count($staff) > 0) {
            foreach ($staff as $key => $row) {
                $rs[$row['user_id']] = $row['user_id'];
            }
        }
//        var_dump($rs);
//        exit;
        $staffinfo = StaffInfo::staffinfoExport($rs);
//        var_dump($staffinfo);
//        var_dump($staff);
//        exit;
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();
        
        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');
        
        //获取表头数组
        $rowKey = array();
        $rowKey = self::Excelhead();
//        foreach ($rowKey as $key => $value) {
//            var_dump($value["field"]);
//        }
//        exit;
        //获取最后一列表头的值
//        $end_key = key($rowKey);
        $num = array_keys($rowKey);
        $end_key = $num[count($num)-1];
//        var_dump($end_key);
//        exit;
        $gender = Staff::Gender();
        $skill = Staff::Skill();
        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.$end_key.'1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','员工信息表');
        $objStyleA1 = $objActSheet->getStyle('A1'); 
         //字体及颜色
        $objFontA1 = $objStyleA1->getFont();    
        $objFontA1->setSize(20); 
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.$end_key.'2');
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2','导出日期：'.date("d M Y"));
        $objectPHPExcel->getActiveSheet()->mergeCells('A3'.':'.'K3');
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3','基本信息 Basic Information');

        $objectPHPExcel->getActiveSheet()->mergeCells('L3'.':'.'V3');
        $objectPHPExcel->getActiveSheet()->getStyle('L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L3','个人信息 Personal Information');

        $objectPHPExcel->getActiveSheet()->mergeCells('W3'.':'.'Z3');
        $objectPHPExcel->getActiveSheet()->getStyle('W3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('W3','护照 Passport');

        $objectPHPExcel->getActiveSheet()->mergeCells('AA3'.':'.'AH3');
        $objectPHPExcel->getActiveSheet()->getStyle('AA3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('AA3','身份证信息 Identification(ID)');

        $objectPHPExcel->getActiveSheet()->mergeCells('AI3'.':'.'AL3');
        $objectPHPExcel->getActiveSheet()->getStyle('AI3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('AI3','安全证 Construction Safety Orientation Course (CSOC)');
     
        $objectPHPExcel->getActiveSheet()->mergeCells('AM3'.':'.'AV3');
        $objectPHPExcel->getActiveSheet()->getStyle('AM3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('AM3','保险 Insurance');
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        foreach ($rowKey as $key => $v) {
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($key.'4',$v['title_cn']);
            
            $objectPHPExcel->getActiveSheet()->getStyle($key.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($key.'5',$v['title_en']);
            
            $objectPHPExcel->getActiveSheet()->getStyle($key.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(20);
        }
        //写入数据
        foreach ($staff as $k => $v) {
            $user_id = $v['user_id'];
            $ppt_photo = $staffinfo[$k]['ppt_photo'];
            $passport_no = $staffinfo[$k]['passport_no'];
            $ppt_issue_date = $staffinfo[$k]['ppt_issue_date'];
            $ppt_expire_date = $staffinfo[$k]['ppt_expire_date'];
            $bca_pass_no = $staffinfo[$k]['bca_pass_no'];
            $bca_photo = $staffinfo[$k]['bca_photo'];
            $bca_issue_date = $staffinfo[$k]['bca_issue_date'];
            $bca_expire_date = $staffinfo[$k]['bca_expire_date'];
            $csoc_no = $staffinfo[$k]['csoc_no'];
            $csoc_issue_date = $staffinfo[$k]['csoc_issue_date'];
            $csoc_expire_date = $staffinfo[$k]['csoc_expire_date'];
            $csoc_photo = $staffinfo[$k]['csoc_photo'];

            $csoc_info = UserAptitude::csocInfo($user_id);
            if(!empty($csoc_info)){
                $csoc_no = $csoc_info[0]['aptitude_content'];
                $csoc_issue_date = $csoc_info[0]['permit_startdate'];
                $csoc_expire_date = $csoc_info[0]['permit_enddate'];
                if($csoc_info[0]['aptitude_type'] != 'pdf'){
                    $csoc_photo = $csoc_info[0]['aptitude_photo'];
                }
            }
            $bca_info = UserAptitude::bcaInfo($user_id);
            if(!empty($bca_info)){
                $bca_pass_no = $bca_info[0]['aptitude_content'];
                $bca_issue_date = $bca_info[0]['permit_startdate'];
                $bca_expire_date = $bca_info[0]['permit_enddate'];
                if($bca_info[0]['aptitude_type'] != 'pdf'){
                    $bca_photo = $bca_info[0]['aptitude_photo'];
                }
            }
            $ppt_info = UserAptitude::pptInfo($user_id);
            if(!empty($ppt_info)){
                $passport_no = $ppt_info[0]['aptitude_content'];
                $ppt_issue_date = $ppt_info[0]['permit_startdate'];
                $ppt_expire_date = $ppt_info[0]['permit_enddate'];
                if($ppt_info[0]['aptitude_type'] != 'pdf'){
                    $ppt_photo = $ppt_info[0]['aptitude_photo'];
                }
            }
            foreach ($rowKey as $key => $value) {
                $m = ord($key)-65;
//                static $n = 1;
                /*设置表格高度*/
                $objectPHPExcel->getActiveSheet()->getRowDimension($k+6)->setRowHeight(90);
                if($value['array']==staff){
                    if($value['type']==photo && $staff[$k][$value['field']]!=''){
                        if(substr($staff[$k][$value['field']],0,1) != '.') {
//                    var_dump($staffinfo[$k][$value['field']]);
//                    exit;
                            $pic_path = '/opt/www-nginx/web' . $staff[$k][$value['field']];
                            if(file_exists($pic_path)) {
                                /*实例化excel图片处理类*/
                                $objDrawing = new PHPExcel_Worksheet_Drawing();
                                /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);

                                $objDrawing->setPath('/opt/www-nginx/web' . $staff[$k][$value['field']]);

                                /*设置图片高度*/
                                $objDrawing->setHeight(20);
                                /*设置图片宽度*/
                                $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                                /*设置图片要插入的单元格*/
                                $objDrawing->setCoordinates($key . ($k + 6));
                                /*设置图片所在单元格的格式*/
                                $objDrawing->setOffsetX(30);//30
                                $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                                $objDrawing->getShadow()->setVisible(true);
                                $objDrawing->getShadow()->setDirection(20);//20
                                $objDrawing->setWorksheet($objActSheet);
                            }
                        }
                    }else{
                        if($value['field'] == 'category'){
                            $category_val = $staff[$k][$value['field']];
                            $objectPHPExcel->getActiveSheet()->setCellValue($key.($k+6),$category[$category_val]);
                        }else{
                            $objectPHPExcel->getActiveSheet()->setCellValue($key.($k+6),$staff[$k][$value['field']]);
                        }
                    }
                }
                if($value['array']==staffinfo){
                    if($value['type']==photo ){
                        if($value['field'] == 'bca_photo'){
                            $src = $bca_photo;
                        }else if($value['field'] == 'csoc_photo'){
                            $src = $csoc_photo;
                        }else if($value['field'] == 'ppt_photo'){
                            $src = $ppt_photo;
                        }else{
                            $src = $staffinfo[$k][$value['field']];
                        }
                        if($src !='' && substr($src,0,1) != '.') {
                            $in = strstr($src, '/opt/www-nginx/web');
                            if (empty($in)) {
                                $src = '/opt/www-nginx/web' . $src;
                            }
                        }
                        if($src !='' && substr($src,0,1) != '.' && file_exists($src)) {
//                    var_dump($staffinfo[$k][$value['field']]);
//                    exit;

                            /*实例化excel图片处理类*/
                            $objDrawing = new PHPExcel_Worksheet_Drawing();
                            /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);

                            $objDrawing->setPath($src);

                            /*设置图片高度*/
                            $objDrawing->setHeight(20);
                            /*设置图片宽度*/
                            $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                            /*设置图片要插入的单元格*/
                            $objDrawing->setCoordinates($key . ($k + 6));
                            /*设置图片所在单元格的格式*/
                            $objDrawing->setOffsetX(30);//30
                            $objDrawing->setOffsetY(5);
//                            $objDrawing->setRotation(40);//40
                            $objDrawing->getShadow()->setVisible(true);
                            $objDrawing->getShadow()->setDirection(20);//20
                            $objDrawing->setWorksheet($objActSheet);
                        }
                    }else{
                        switch($value['field']){
                            case "gender":
                                $val = $gender[$staffinfo[$k][$value['field']]];
                                break;
                            case "skill":
                                $val = $skill[$staffinfo[$k][$value['field']]];
                                break;
                            case "passport_no":
                                $val = $passport_no;
                                break;
                            case "ppt_issue_date":
                                $val = $ppt_issue_date;
                                break;
                            case "ppt_expire_date":
                                $val = $ppt_expire_date;
                                break;
                            case "bca_expire_date":
                                $val = $bca_expire_date;
                                break;
                            case "bca_issue_date":
                                $val = $bca_issue_date;
                                break;
                            case "bca_pass_no":
                                $val = $bca_pass_no;
                                break;
                            case "csoc_no":
                                $val = $csoc_no;
                                break;
                            case "csoc_issue_date":
                                $val = $csoc_issue_date;
                                break;
                            case "csoc_expire_date":
                                $val = $csoc_expire_date;
                                break;
                            default:
                                $val = $staffinfo[$k][$value['field']];
                        }
                        $objectPHPExcel->getActiveSheet()->setCellValue($key.($k+6),$val);
                    }
                }
//            $n++;
            }
        }

        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Employee information table-'.date("d M Y").'.xlsx"');
//        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
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
}
