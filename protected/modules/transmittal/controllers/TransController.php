<?php
class TransController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";
    
    public function init() {
        parent::init();
        $this->contentHeader = 'Group';
        $this->bigMenu = 'Group List';
    }

    /**
     * 添加
     */
    public function actionNew() {
        $this->smallHeader = 'Transmittal';
        $project_id = $_REQUEST['project_id'];
        $project_model = Program::model()->findByPk($project_id);
        $project_name = $project_model->program_name;
        $this->render('method_statement',array('program_id'=>$project_id,'program_name'=>$project_name));
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionSaveMethod() {

        $args = $_REQUEST['Trans'];
        $cc = $_REQUEST['cc'];
        $to = $_REQUEST['to'];
        $args['to'] = $to;
        $args['cc'] = $cc;
        $r = TransmittalRecord::submit($args);
        echo json_encode($r);
    }


    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=transmittal/trans/grid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Reference No.', '', 'center');
        $t->set_header('Subject', '', 'center');
        $t->set_header('Issued On', '', 'center');
        $t->set_header('RVO', '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;

        $args = $_GET['q']; //查询条件

        $t = $this->genDataGrid($args['program_id']);
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = TransmittalRecord::queryList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('_list', array('t' => $t,'program_id'=>$args['program_id'],'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num'], 'args'=>$args));
    }

    /**
     * 列表
     */
    public function actionList() {
//        $user_id = '48781';
//        $program_id = '1261';
//        $file_list = Dms::Filepath($program_id,$user_id);
//        var_dump($file_list);
//        exit;
        $program_id = $_REQUEST['project_id'];
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            $program_id = $args['program_id'];
        }
        
        $this->smallHeader = 'Transmittal';

        $this->render('list',array('program_id'=> $program_id, 'args'=>$args));
    }


    public function actionChangeView() {
        $form_id = $_REQUEST['form_id'];
        $args['program_id'] = $_REQUEST['program_id'];
        $args['type'] = $_REQUEST['type'];
        $contractor_list = Program::ProgramAllCompany($args['program_id']);
        $this->render($form_id,array('current_form_id'=>$form_id,'program_id'=>$args['program_id'],'type'=>$args['type'],'contractor_list'=>$contractor_list));
    }

    /**
     * 详情
     */
    public function actionInfo() {
        $check_id = $_REQUEST['check_id'];
        $login_program_id = Yii::app()->user->getState('program_id');
        $trans_model = TransmittalRecord::model()->findByPk($check_id);
        $program_id = $trans_model->project_id;
        $attach_list = TransmittalRecordAttach::dealListBystep($check_id,'1');
        $user_list = TransmittalUser::userListByStep($check_id,1);
        $purpose_list = TransmittalRecord::purposeList();
        $detail_list = TransmittalDetail::dealListByStep($check_id,1);
        $this->render('_info',array('login_program_id'=>$login_program_id,'trans_model'=>$trans_model,'program_id'=>$program_id,'check_id'=>$check_id,'attach_list'=>$attach_list,'user_list'=>$user_list,'purpose_list'=>$purpose_list,'detail_list'=>$detail_list));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['transmittal/trans/list'] = str_replace("r=transmittal/trans/grid", "r=transmittal/trans/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $doc_path = $_REQUEST['doc_path'];
        $doc_name = $_REQUEST['doc_name'];
        $doc_arr = explode('.',$doc_name);
        $filepath = '/opt/www-nginx/web'.$doc_path;
        if(file_exists($filepath)) {
            $extend = substr($filepath,-3,4);
            Utils::Download($filepath, $doc_arr[0], $extend);
            return;
        }
    }

    /**
     * 下载压缩包
     */
    public function actionZip() {
        $check_id = $_REQUEST['check_id'];
        $trans_model = TransmittalRecord::model()->findByPk($check_id);
        $attachment = TransmittalRecordAttach::dealListBystep($check_id,'1');
        $filename = "/opt/www-nginx/web/filebase/tmp/".$check_id.".zip";
        $file_cnt = count($attachment);
        $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            //如果是Linux系统，需要保证服务器开放了文件写权限
            exit("文件打开失败!");
        }
        $x = 0;
        foreach($attachment as $i => $j){
            $doc_path = '/opt/www-nginx/web'.$j['doc_path'];
            if (file_exists($doc_path)) {
                $zip->addFile($doc_path, basename($doc_path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                $x++;
            }
        }
        $zip->close();

        if (file_exists($filename) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }

        $file = fopen($filename, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/zip");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        $name = basename($filename);
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filename));
        fclose($file);
        unlink($filename);
        $r['filename'] = $filename;
        print_r(json_encode($r));
    }

    /**
     * ModelComponent
     */
    public function actionSaveAll() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $login_program_id = $_REQUEST['login_program_id'];
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = Yii::app()->user->id;
        }
        $rf_model = RfList::model()->findByPk($check_id);
        $project_id = $rf_model->project_id;
        $attach_list = TransmittalRecordAttach::dealListBystep($check_id,'1');
        $url = 'https://roboxz.cmstech.sg/dmsapi/filecache/web?';
//        $url = 'http://roboxz.cmstech.sg/dmsapi/filecache/web?';
        $url.='uid='.$user_id;
        $url.='&gid='.$login_program_id;
        $url.='&files=';
        foreach($attach_list as $i => $j){
            $url.='https://shell.cmstech.sg'.$j['doc_path'].'|';
        }
        $url = substr($url, 0, strlen($url) - 1);
        $this->renderPartial('save_one',array('url'=>$url));
    }

    /**
     * ModelView
     */
    public function actionSaveOne() {
        $path = $_REQUEST['path'];
        $check_id = $_REQUEST['check_id'];
        $login_program_id = $_REQUEST['login_program_id'];
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = Yii::app()->user->id;
        }
        $rf_model = TransmittalRecord::model()->findByPk($check_id);
        $project_id = $rf_model->project_id;
        $url = 'https://roboxz.cmstech.sg/dmsapi/filecache/web?';
//        $url = 'http://roboxz.cmstech.sg/dmsapi/filecache/web?';
        $url.='uid='.$user_id;
//        $url.='&gid='.$project_id;
        $url.='&gid='.$login_program_id;
        $url.='&files=';
        $url.='https://shell.cmstech.sg'.$path;
        $this->renderPartial('save_all',array('url'=>$url));
    }

    /**
     * 保存回复
     */
    public function actionReceive() {
        $check_id = $_REQUEST['check_id'];
        $r = TransmittalRecord::receiveList($check_id);
        print_r(json_encode($r));
    }

    /**
     * 详情
     */
    public static function actionDownloadPdf() {
        $id = $_REQUEST['check_id'];
        $app_id = 'TRANS';
        $params['id'] = $id;
        $apply = TransmittalRecord::model()->findByPk($id);//许可证基本信息表
        //报告定制化
        $program_id = $apply->project_id;
        $pro_model = Program::model()->findByPk($program_id);
        $title = $pro_model->program_name;
        $filename = DownloadPdf::transferDownload($params,$app_id);
//        Utils::Download($filepath, $title, 'pdf');
        $file = fopen($filename, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-type: application/zip");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        $name = basename($filename);
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filename));
        fclose($file);
        unlink($filename);
    }
}
