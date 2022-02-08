<?php

/*
 * 模块编号: M0001
 */

class MailController extends CController {
    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $pageSize = 9;
    //页头
    public $contentHeader = '';
    public $smallHeader = '';
    public $bigMenu = '';
    //接口地址
    private $url = "https://shell.cmstech.sg/dbcms/dbapi";
    private $tset_url = "http://t_cmstech.aoepos.cn/dbcms/dbapi";
    private $yunpian_url = "https://sms.yunpian.com/v2/sms/single_send.json";

    public function init() {

        //国际化
        if (isset($_REQUEST['lang']) && $_REQUEST['lang'] != "") {   //通过lang参数识别语言
//            var_dump('if');
            Yii::app()->language = $_REQUEST['lang'];
            setcookie('lang', $_REQUEST['lang']);
        } else if (isset($_COOKIE['lang']) && $_COOKIE['lang'] != "") {   //通过$_COOKIE['lang']识别语言
//            var_dump('else if');
            Yii::app()->language = $_COOKIE['lang'];
        } else {   //通过系统或浏览器识别语言
//            var_dump('else');
//            $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
//            Yii::app()->language = str_replace('-', '_', $lang[0]);
            Yii::app()->language = 'en_US';
        }
    }

    public function sendHttpPost($http_url, $data) {

        $ch = curl_init();


//        curl_setopt($ch, CURLOPT_URL, $http_url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLOPT_POST, 1); //post提交
//        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

        curl_setopt($ch, CURLOPT_URL, $http_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
//        $result = json_decode($output,true);

        if ($output === FALSE) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        // 4. 释放curl句柄
        curl_close($ch);


        return $output;
    }
    public function actionLogin(){
//        $data['uid'] = '861';
////        $data['token'] = 'lalala';
//        $http_url = $this->tset_url.'?cmd=CMSC0009';
//        foreach ($data as $key => $value) {
//            $post_data[$key] = $value;
//        }
//        $data = json_encode( $post_data );
//        $result = self::sendHttpPost($http_url,$data);
//        var_dump($result);
//        exit;
        $id = $_REQUEST['id'];
        $rcd = $_REQUEST['rcd'];
        $model = MailType::model()->findByPk($id);
        $mail_type = $model->mail_type;
        $user_id = $model->user_id;
        $root_proid = $model->root_proid;
        $contractor_id = $model->contractor_id;
        $default_rcd = md5($model->mail_type.$model->user_id.$model->rcpt_phone);
        if($rcd == $default_rcd){
            session_start();
            $_SESSION['mail_type'] = $mail_type;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['root_proid'] = $root_proid;
            $_SESSION['contractor_id'] = $contractor_id;
            $_SESSION['rcpt_phone'] = $model->rcpt_phone;
            if(isset($_SESSION['expiretime'])) {
                if($_SESSION['expiretime'] < time()) {
                    unset($_SESSION['expiretime']);
                    $this->redirect('index.php?r=mail/logout');
                }
            }else {
                $_SESSION['expiretime'] = time() + 3600*24; // 刷新时间戳
            }
            $this->layout = '//layouts/login_mail';
            $this->render('mail',array('type'=>$mail_type));
        }else{
            $this->redirect('index.php?r=mail/logout');
        }
    }
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=mail/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_staff', 'User_name'), '', 'center');
        $t->set_header(Yii::t('proj_project', 'program_name'), '', 'center');
        $t->set_header(Yii::t('comp_contractor', 'Contractor_name'), '', 'center');
        $t->set_header(Yii::t('comp_staff', 'certificate_type'), '', 'center');
//        $t->set_header(Yii::t('device', 'permit_startdate'), '', '');
//        $t->set_header(Yii::t('device', 'permit_enddate'), '', '');
        $t->set_header(Yii::t('comp_staff', 'certificate_enddate'), '', 'center');
        $t->set_header(Yii::t('device', 'status'), '', 'center');
        return $t;
    }
    /**
     * 查询
     */
    public function actionGrid() {
        //var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
//        var_dump($args);
//        exit;
        if($args['status'] == ''){
            $args['status'] = '00';
        }
        $t = $this->genDataGrid();
        $this->saveUrl();
//        $args['contractor_type'] = Staff::CONTRACTOR_TYPE_SC;
        session_start();
        $args['contractor_id'] = $_SESSION['contractor_id'];
        $args['user_id'] = $_SESSION['user_id'];
        $args['root_proid'] = $_SESSION['root_proid'];
//        var_dump($args);

        $list = MailType::queryAptitude($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    public function actionList() {
        session_start();
        if(isset($_SESSION['expiretime'])) {
            if($_SESSION['expiretime'] < time()) {
                unset($_SESSION['expiretime']);
                $this->redirect('index.php?r=mail/logout');
            }
        }else{
            $this->redirect('index.php?r=mail/logout');
        }
        $this->smallHeader = Yii::t('comp_aptitude', 'expiration_reminder');
        $this->layout = '//layouts/mail';
        $this->render('list');
    }
    /**
     * 详细
     */
    public function actionDetail() {

        $id = $_POST['id'];
        $program_list = ProgramUser::UserProgramName($id);
        $program_detail = '';
        if($program_list){
            foreach($program_list as $cnt=>$list){
                $program_detail .= $list['program_name'].',';
            }
            $program_detail = substr($program_detail,0,-1);
        }

        $msg['status'] = true;
        $roleList = Role::roleList();
        $model = Staff::model()->find('user_id=:user_id',array(':user_id'=>$id));
        if ($model) {

            $msg['detail'] .= "<table class='detailtab'>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('user_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->user_id) ? $model->user_id : "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('user_name') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->user_name) ? $model->user_name : "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('user_phone') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3 detail_phone'>" . (isset($model->user_phone) ? "$model->user_phone" : "") ."</td>";
            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('work_pass_type') . "</td>";
            $msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->work_pass_type) ? $model->work_pass_type: "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('nation_type') . "</td>";
            $msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->nation_type) ? $model->nation_type: "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" .$model->getAttributeLabel('work_no') . "</td>";
            $msg['detail'] .="<td class='tvalue-3'>" .  (isset($model->work_no) ? $model->work_no    : "") . "</td>";
            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            //	$msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('team_id') . "</td>";
            //	$msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->team_id) ? $model->team_id : "") . "</td>";
            $msg['detail'] .= "<td class='tname-1'>" . $model->getAttributeLabel('role_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3'>" . (isset($model->role_id) ? $roleList[$model->role_id] : "") . "</td>";

            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'where the project') . "</td>";
            $msg['detail'] .= "<td class='tvalue-3' colspan='4'>" . $program_detail . "</td>";

            $msg['detail'] .= "</tr>";

            if($model->loaned_status == Staff::LOANED_STATUS_YES){
                $comp_model = Contractor::model()->findByPk($model->original_conid);
                $loaned_model = Contractor::model()->findByPk($model->contractor_id);
                $msg['detail'] .= "<tr>";
                $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'original_company') . "</td>";
                $msg['detail'] .= "<td class='tvalue-3'>" . $comp_model->contractor_name . "</td>";
                $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'loane_company') . "</td>";
                $msg['detail'] .= "<td class='tvalue-3'>" . $loaned_model->contractor_name . "</td>";
                $msg['detail'] .= "<td class='tname-1'>" . Yii::t('comp_staff', 'loane_time') . "</td>";
                $msg['detail'] .= "<td class='tvalue-3'>" . $model->loaned_time . "</td>";
                $msg['detail'] .= "</tr>";
            }


            $msg['detail'] .= "</table>";
            print_r(json_encode($msg));
        }
    }
    public function actionSend(){
        $rand_num = rand(1111,9999);
        setcookie("rand_code",md5($rand_num));
        session_start();
//        $rcpt_phone = '+65'.$_SESSION['rcpt_phone'];
        $rcpt_phone = $_SESSION['rcpt_phone'];
        if(strlen($rcpt_phone)==8){
            $text = '【CMS】Your verification code is '.$rand_num.'.';
            $phone = '+65'.$rcpt_phone;
        }else{
            $text = '【CMS】您的验证码是'.$rand_num;
            $phone = $rcpt_phone;
        }
        $data = array(
            'apikey' => 'f579becad5fb0555e182e35fcbc8d98e',
            'text' => $text,
            'mobile' => $phone,
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $data = http_build_query( $post_data );
        $http_url = $this->yunpian_url;
        $result = self::sendHttpPost($http_url,$data);
//        $result['status'] = 1;
//        $result['num'] = $rand_num;
        print_r(json_encode($result));
    }
    public function actionSubmit(){

        session_start();
        if(isset($_SESSION['expiretime'])) {
            if($_SESSION['expiretime'] < time()) {
                unset($_SESSION['expiretime']);
                $this->redirect('index.php?r=mail/logout');
            }
        }else {
            $_SESSION['expiretime'] = time() + 3600*24; // 刷新时间戳
        }
        if (isset($_POST['LoginForm'])) {
            $args = $_POST['LoginForm'];
            if($_COOKIE['rand_code'] == md5($args['code'])){
//                var_dump($_SESSION);
//                exit;
                if($_SESSION['mail_type'] == '01'){
                    $this->redirect('index.php?r=mail/list');
                }
            }else{
                echo "<script>alert('".Yii::t('common','error_code')."');</script>";
            }
        }
        $this->layout = '//layouts/mail';
        $this->render('mail');
    }
    public function actionLogout(){
        $this->layout = '//layouts/login';
        $this->render('logout');
    }
    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['mail/list'] = str_replace("r=mail/grid", "r=mail/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 中英语言切换
     */
    public function actionLang() {

        if ($_REQUEST['confirm']) {
            $lang = trim($_REQUEST['_lang']);
            Yii::app()->language = $lang;
            setcookie('lang', $lang);
        }

        echo json_encode(true);
    }

    //导出人员过期证书
    public function actionExport(){
        session_start();
        $args['contractor_id'] = $_SESSION['contractor_id'];
        $args['user_id'] = $_SESSION['user_id'];
        $args['root_proid'] = $_SESSION['root_proid'];
//        var_dump($args);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $list = MailType::queryAptitude($page, $this->pageSize, $args);
        $status_list = MailType::statusText();//状态
        $current_date = date('Y-m-d');

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1'.':'.'F1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('comp_aptitude', 'expiration_reminder'));
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'F2');
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',date("d M Y"));
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('A3',Yii::t('comp_staff', 'User_name'));
        $objectPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',Yii::t('proj_project', 'program_name'));
        $objectPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('C3',Yii::t('comp_contractor', 'Contractor_name'));
        $objectPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('D3',Yii::t('comp_staff', 'certificate_type'));
        $objectPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('E3',Yii::t('comp_staff', 'certificate_enddate'));
        $objectPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objectPHPExcel->getActiveSheet()->setCellValue('F3',Yii::t('device', 'status'));
//        //设置颜色
//        $objectPHPExcel->getActiveSheet()->getStyle('AP3')->getFill()
//            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        //写入数据
//        var_dump($rs);
//        exit;
        foreach ($list['data'] as $k => $v) {
//                static $n = 1;
            /*设置表格高度*/
            $objectPHPExcel->getActiveSheet()->getRowDimension($k+4)->setRowHeight(90);

            $objectPHPExcel->getActiveSheet()->setCellValue(A . ($k + 4),$v['user_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue(B . ($k + 4),$v['program_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue(C . ($k + 4),$v['contractor_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue(D . ($k + 4),$v['certificate_name_en']);
            $objectPHPExcel->getActiveSheet()->setCellValue(E . ($k + 4),Utils::DateToEn($v['permit_enddate']));
            if($v['permit_enddate']<$current_date){
                $status = '2';
            }else{
                $status = '1';
            }
            $objectPHPExcel->getActiveSheet()->setCellValue(F . ($k + 4),$status_list[$status]);
//            $n++;
        }

        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Staff Certificate Expiration Reminder-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
}
