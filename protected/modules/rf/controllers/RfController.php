<?php
class RfController extends BaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = "";
    public $bigMenu = "";
    public $pageSize = 10;
    
    public function init() {
        parent::init();
        $this->contentHeader = 'Download Report';
        $this->bigMenu = 'RFIs & RFAs';
    }

     
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($type_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=rf/rf/grid';
        $t->updateDom = 'datagrid';
//        $t->set_header(Yii::t('license_licensepdf', 'apply_id'), '', 'center');
//        $t->set_header('Tag', '', 'center');
        $t->set_header('Ref No.', '', 'center');
        $t->set_header('Subject', '', 'center');
        $t->set_header('Discipline', '', 'center');
        if($type_id == '2'){
            $t->set_header('Trade', '', 'center');
        }
        $t->set_header('RVO', '', 'center');
//        $t->set_header('Created on', '', 'center');
        $t->set_header('Latest Date to Reply', '', 'center');
        if($type_id != '1'){
            $t->set_header('Outcome', '', 'center');
        }
        $t->set_header('Replied By', '', 'center');
        $t->set_header('Company', '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '10%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $fields = func_get_args();
//        var_dump($_GET['page']);
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        //if(count($fields) == 3 && $fields[0] != null ) {
        //    $args['program_id'] = $fields[0];
        //    $args['type_id'] = $fields[1];
        //    $args['status'] = $fields[2];
        //}
        $t = $this->genDataGrid($args['type_id']);
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;

        $list = RfList::queryList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('_list', array('t' => $t,'program_id'=>$args['program_id'],'app_id'=>$app_id,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num'],'type_id'=>$args['type_id'], 'status'=>$args['status']));
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
        $program_id = $_REQUEST['program_id'];
        $type_id = $_REQUEST['type_id'];
        $status = $_REQUEST['status'];
        
        $args = array();
        if($_GET['q']){
            $args = $_GET['q'];
            if($args['program_id']){
                $program_id = $args['program_id'];
            }
            if($args['type_id']){
                $type_id = $args['type_id'];
            }
            if($args['status']){
                $status = $args['status'];
            }
        }
        
        if($type_id == '1'){
            $this->smallHeader = 'RFIs';
        }else {
            $this->smallHeader = 'RFAs';
        }
//        if($status == '-1'){
//            $this->smallHeader = 'Draft';
//        }

        $this->render('list',array('program_id'=> $program_id,'type_id'=>$type_id,'status'=>$status,'args'=>$args));
    }

    /**
     * 类型选择
     */
    public function actionSelectType() {
        $args['program_id'] = $_REQUEST['program_id'];
        $this->renderPartial('select_type',array('program_id'=>$args['program_id']));
    }
    /**
     * 添加RFI
     */
    public function actionAddRfiChat() {
        $args['program_id'] = $_REQUEST['program_id'];
        $args['type'] = $_REQUEST['type'];
        $form_list = RfFormType::formList($args['program_id'],$args['type']);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $contractor_list = Program::ProgramAllCompany($args['program_id']);
        if($args['type'] == '1'){
            $form_id = $form_list[0]['form_id'];
            $this->render($form_id,array('current_form_id'=>$form_id,'program_id'=>$args['program_id'],'type'=>$args['type'],'contractor_list'=>$contractor_list));
        }else{
//            $form_id = $form_list[0]['form_id'];
            $form_id = 'RF00003';
            $this->render($form_id,array('current_form_id'=>$form_id,'program_id'=>$args['program_id'],'type'=>$args['type'],'contractor_list'=>$contractor_list));
        }
    }

    public function actionChangeView() {
        $form_id = $_REQUEST['form_id'];
        $args['program_id'] = $_REQUEST['program_id'];
        $args['type'] = $_REQUEST['type'];
        $contractor_list = Program::ProgramAllCompany($args['program_id']);
        $this->render($form_id,array('current_form_id'=>$form_id,'program_id'=>$args['program_id'],'type'=>$args['type'],'contractor_list'=>$contractor_list));
    }

    /**
     * 添加RFA
     */
    public function actionAddRfaChat() {
        $args['program_id'] = $_REQUEST['program_id'];
        $args['tag'] = $_REQUEST['tag'];
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $attach_list = explode('|', $args['tag']);
        $flow_id = 0;
        $contractor_list = Program::ProgramAllCompany($args['program_id']);
        $this->render('rfa_form',array('program_id'=>$args['program_id'],'flow_id'=>$flow_id,'attach_list'=>$attach_list,'contractor_list'=>$contractor_list));
    }

    /**
     * 编辑
     */
    public function actionEditChat() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $form_id = $rf_model->form_id;
        $type = $rf_model->type;
        $step = $rf_model->current_step;
        $program_id = $rf_model->project_id;
        $check_list = RfList::dealList($check_id);
        $detail_list = RfDetail::dealList($check_id);
        $rf_user_list = RfUser::userListByStep($check_id,$step);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $attach_list = RfRecordAttachment::dealListBystep($check_id,$step);
        $item_list = RfRecordItem::dealList($check_id);
        $to_user = '';
        $cc_user = '';
        foreach($rf_user_list as $i => $j){
            if($j['type'] == '1'){
                $to_user.=$j['user_id'].',';
            }
            if($j['type'] == '2'){
                $cc_user.=$j['user_id'].',';
                $cc['id'] = $j['user_id'];
                $cc['text'] = $j['user_name'];
                $cc_arr[] = $cc;
            }
        }
        $cc_json = json_encode($cc_arr);
        $to_user = substr($to_user, 0, strlen($to_user) - 1);
        $cc_user = substr($cc_user, 0, strlen($cc_user) - 1);
        if($type == '1'){
            $this->render($form_id.'_editform',array('form_id'=>$form_id,'program_id'=>$program_id,'check_id'=>$check_id,'step'=>$step,'check_list'=>$check_list,'detail_list'=>$detail_list,'rf_user_list'=>$rf_user_list,'attach_list'=>$attach_list,'item_list'=>$item_list,'to_user'=>$to_user,'cc_user'=>$cc_user,'cc_json'=>$cc_json));
        }else{
            $this->render($form_id.'_editform',array('form_id'=>$form_id,'program_id'=>$program_id,'check_id'=>$check_id,'step'=>$step,'check_list'=>$check_list,'detail_list'=>$detail_list,'rf_user_list'=>$rf_user_list,'attach_list'=>$attach_list,'item_list'=>$item_list,'to_user'=>$to_user,'cc_user'=>$cc_user,'cc_json'=>$cc_json));
        }
    }
    /**
     * 详情
     */
    public function actionInfo() {
        $check_id = $_REQUEST['check_id'];
        $platform = $_REQUEST['platform'];
//        $login_program_id = $_REQUEST['program_id'];
        $login_program_id = Yii::app()->user->getState('program_id');
        $rf_model = RfList::model()->findByPk($check_id);
        $form_id = $rf_model->form_id;
        $type = $rf_model->type;
        $step = $rf_model->current_step;
        $program_id = $rf_model->project_id;
//        $this->layout = '//layouts/main_model';
        if(!is_null($platform)){
//            $this->layout = '//layouts/main_2';
            if($type == '1'){
                $this->render($form_id.'_info',array('login_program_id'=>$login_program_id,'program_id'=>$program_id,'check_id'=>$check_id,'type'=>$type));
            }else{
                $this->render($form_id.'_info',array('login_program_id'=>$login_program_id,'program_id'=>$program_id,'check_id'=>$check_id,'type'=>$type));
            }
        }else{
            if($type == '1'){
                $this->render($form_id.'_info',array('login_program_id'=>$login_program_id,'program_id'=>$program_id,'check_id'=>$check_id,'type'=>$type));
            }else{
                $this->render($form_id.'_info',array('login_program_id'=>$login_program_id,'program_id'=>$program_id,'check_id'=>$check_id,'type'=>$type));
            }
        }

    }
    /**
     * ModelComponent
     */
    public function actionEditComponent() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('edit_component',array('check_id'=>$check_id,'step'=>$step,'program_id'=>$program_id));
    }

    /**
     * ModelView
     */
    public function actionEditView() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('edit_view',array('step'=>$step,'check_id'=>$check_id,'program_id'=>$program_id));
    }

    /**
     * ModelComponent
     */
    public function actionAddComponent() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('add_component',array('program_id'=>$program_id));
    }

    /**
     * ModelComponent
     */
    public function actionComponentWithAttachment() {
        $id = $_REQUEST['id'];
        $this->renderPartial('component_attachment',array('id'=>$id));
    }

    /**
     * ModelComponent
     */
    public function actionAddAttachmentComponent() {
        $r['id'] = $_REQUEST['id'];
        $rf_attachment = RfAttachment::model()->findByPk($r['id']);
        $r['doc_path'] = $rf_attachment->doc_path;
        $r['model_id'] = $_REQUEST['model_id'];
        $r['version'] = $_REQUEST['version'];
        $r['uuid'] = $_REQUEST['uuid'];
        $r['entityId'] = $_REQUEST['entityId'];
        $rs = RfModelAttachment::insertList($r);
        print_r(json_encode($rs));
    }

    /**
     * ModelComponent
     */
    public function actionGetAttachmentComponent() {
        $id = $_REQUEST['id'];
        $rs = RfModelAttachment::dealList($id);
        print_r(json_encode($rs));
    }

    /**
     * ModelView
     */
    public function actionAddView() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('add_view',array('program_id'=>$program_id));
    }

    /**
     * ModelComponent
     */
    public function actionShowComponent() {
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
        $attach_list = RfRecordAttachment::dealListBystep($check_id,$step);
        $url = 'https://roboxz.cmstech.sg/dmsapi/filecache/web?';
//        $url = 'http://roboxz.cmstech.sg/dmsapi/filecache/web?';
        $url.='uid='.$user_id;
        $url.='&gid='.$login_program_id;
        $url.='&files=';
        foreach($attach_list as $i => $j){
            $url.='https://shell.cmstech.sg'.urlencode($j['doc_path']).'|';
        }
        $url = substr($url, 0, strlen($url) - 1);
        $this->renderPartial('show_component',array('url'=>$url));
    }

    /**
     * ModelView
     */
    public function actionShowView() {
        $path = urlencode($_REQUEST['path']);
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
        $url = 'https://roboxz.cmstech.sg/dmsapi/filecache/web?';
//        $url = 'http://roboxz.cmstech.sg/dmsapi/filecache/web?';
        $url.='uid='.$user_id;
//        $url.='&gid='.$project_id;
        $url.='&gid='.$login_program_id;
        $url.='&files=';
        $url.='https://shell.cmstech.sg'.$path;
        $this->renderPartial('show_view',array('url'=>$url));
    }

    /**
     * 保存草稿
     */
    public function  actionSaveDraft() {
        $rf = $_REQUEST['rf'];
        $cc = $_REQUEST['cc'];
        $to = $_REQUEST['to'];
        $item = $_REQUEST['item'];
        $rf['to'] = $to;
        $rf['cc'] = $cc;
        $data_id = RfList::queryIndex();
        $rf['data_id'] = $data_id;
        $index =  str_pad((String)$data_id, 5, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        if($rf['type_id'] == '1'){
            $rf['check_id'] = 'CMS-RFI-'.$date.'-'.$index;
        }else{
            $rf['check_id'] = 'CMS-RFA-'.$date.'-'.$index;
        }
        $rf['status'] = RfList::STATUS_DRAFT;
        $r = RfList::Submit($rf,$item);
        print_r(json_encode($r));
    }

    /**
     * 发起
     */
    public function  actionSend() {
        $rf = $_REQUEST['rf'];
        $cc = $_REQUEST['cc'];
        $to = $_REQUEST['to'];
        if($_REQUEST['item']){
            $item = $_REQUEST['item'];
        }else{
            $item = array();
        }

//        var_dump($rf);
//        var_dump($cc);
//        var_dump($to);
//        var_dump($item);
//        exit;
        $rf['to'] = $to;
        $rf['cc'] = $cc;
        $exist_data = RfList::model()->count('check_id=:check_id',array('check_id' => $rf['check_id']));
        //提交
        if ($exist_data != 0) {
            $rf['status'] = RfList::STATUS_PENDING;
            $r = RfList::SubmitDraft($rf,$item);
        }else{
            //直接发起
            $rf['status'] = RfList::STATUS_PENDING;
            $r = RfList::Submit($rf,$item);
        }
        print_r(json_encode($r));
    }

    /**
     * 结束对话
     */
    public function actionEnd() {

        $args['program_id'] = $_REQUEST['program_id'];
        $args['check_id'] = $_REQUEST['check_id'];
        $operator_id = Yii::app()->user->id;
        $r = RfList::endList($args);
        $this->redirect('index.php?r=rf/rfi/list&program_id='.$args['program_id']);
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['rf/rf/list'] = str_replace("r=rf/rf/grid", "r=rf/rf/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 在线预览文件
     */
    public function actionPreviewDoc() {
        $doc_path = $_REQUEST['doc_path'];
        $this->renderPartial('preview_doc',array('file'=>$doc_path));
    }

    /**
     * 在线预览文件(批注)
     */
    public function actionPreview() {
        $check_id = $_REQUEST['check_id'];
        $attach_id = $_REQUEST['attach_id'];
        $attach = RfAttachment::model()->findByPk($attach_id);
//        $doc_path = $_REQUEST['file'];
        $doc_path = $attach->doc_path;
        $tag = $_REQUEST['tag'];
        $detail_list = RfAttachNote::dealList($check_id,$attach_id);
        $this->renderPartial('preview',array('file'=>$doc_path,'check_id'=>$check_id,'attach_id'=>$attach_id,'detail_list'=>$detail_list,'tag'=>$tag));
    }

    /**
     * 批注界面
     */
    public function actionAnnotateList() {

        $check_id = $_REQUEST['check_id'];
        $attach_id = $_REQUEST['attach_id'];
        $attach = RfAttachment::model()->findByPk($attach_id);
        $pic_path = $_REQUEST['pic_path'];
//        $file_path = $_REQUEST['file'];
        $file_path = $attach->doc_path;
        $pagenumber = $_REQUEST['pagenumber'];
        $this->renderPartial('annotate',array('pagenumber'=>$pagenumber,'pic'=>$pic_path,'file'=>$file_path,'check_id'=>$check_id,'attach_id'=>$attach_id));
    }

    /**
     * 批注界面
     */
    public function actionAnnotateInfo() {
        $note_id = $_REQUEST['note_id'];
        $file_path = $_REQUEST['file'];
        $pic = $_REQUEST['pic'];
        $tag = $_REQUEST['tag'];
        $this->renderPartial('annotate_info',array('pic'=>$pic,'note_id'=>$note_id,'file'=>$file_path,'tag'=>$tag));
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
        $rf_model = RfList::model()->findByPk($check_id);
        $check_no = $rf_model->check_no;
        $step = $_REQUEST['step'];
        $attachment = RfRecordAttachment::dealListBystep($check_id,$step);
        $filename = "/opt/www-nginx/web/filebase/tmp/".$check_no.".zip";
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
     * 转发
     */
    public function actionForward() {
        $link_check_id = $_REQUEST['check_id'];
        $link_list = explode(',', $link_check_id);
        $link_cnt = count($link_list);
        $rf_model = RfList::model()->findByPk($link_check_id);
        $program_id = $_REQUEST['program_id'];
        $type_id = $_REQUEST['type'];
        $forward_status = $_REQUEST['forward_status'];
        $form_list = RfFormType::formList($program_id,$type_id);
        if($link_cnt>1){
            $form_id = $form_list[0]['form_id'];
        }else{
            $form_id = $rf_model->form_id;
        }
        $this->smallHeader = 'RFAs';
        $this->render($form_id.'_forward',array('current_form_id'=>$form_id,'link_check_id'=> $link_check_id,'program_id'=>$program_id,'type'=>$type_id,'forward_status'=>$forward_status));
    }

    public function actionChangeForward() {
        $form_id = $_REQUEST['form_id'];
        $link_check_id = $_REQUEST['check_id'];
        $program_id = $_REQUEST['program_id'];
        $type_id = $_REQUEST['type'];
        $forward_status = $_REQUEST['forward_status'];
        $contractor_list = Program::ProgramAllCompany($program_id);
        $this->smallHeader = 'RFAs';
        $this->render($form_id.'_forward',array('current_form_id'=>$form_id,'link_check_id'=> $link_check_id,'program_id'=>$program_id,'type'=>$type_id,'forward_status'=>$forward_status));
    }

    public function actionForwardInfo() {
        $link_check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($link_check_id);
        $form_id = $rf_model->form_id;
        $this->render($form_id.'_forward_info',array('link_check_id'=> $link_check_id));
    }

    /**
     * 保存转发
     */
    public function actionSaveForward() {
        $rf = $_REQUEST['rf'];
        $cc = $_REQUEST['cc'];
        $to = $_REQUEST['to'];
        $rf['to'] = $to;
        $rf['cc'] = $cc;
        $r = RfList::forwardList($rf);
        print_r(json_encode($r));
    }

    public function actionBashConfirmForward() {
        $this->renderPartial('bash_confirm_forward');
    }

    public function actionConfirmForward() {
        $program_id = $_REQUEST['program_id'];
        $type_id = $_REQUEST['type_id'];
        $check_id = $_REQUEST['check_id'];
        $this->renderPartial('confirm_forward',array('program_id'=>$program_id,'type_id'=>$type_id,'check_id'=>$check_id));
    }

    /**
     * 回复
     */
    public function actionReply() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $apply_user_id = $rf_model->apply_user_id;
        $program_id = $rf_model->project_id;
        $type = $rf_model->type;
        $step = $rf_model->current_step;
        if($type == '2'){
            $deal_detail = RfDetail::dealListByStep($check_id,'1');
        }else{
            $deal_detail = RfDetail::dealListByStep($check_id,$step);
        }
        $rf_user_list = RfUser::userListByStep($check_id,'1');
        $to_user = '';
        $cc_user = '';
        $to_user = $deal_detail[0]['user_id'];
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }
        foreach($rf_user_list as $i => $j){
//            if($j['type'] == '1'){
//                if($step == 1){
//                    $to_user = $j['user_id'];
//                }else{
//                    $to_user = $j['user_id'];
//                }
//
//            }
            if($j['type'] == '1'){
                if($user_id != $j['user_id']){
                    $cc_user.=$j['user_id'].',';
                }
            }

            if($j['type'] == '2'){
                $cc_user.=$j['user_id'].',';
                $cc['id'] = $j['user_id'];
                $cc['text'] = $j['user_name'];
                $cc_arr[] = $cc;
            }
        }
//        $to_user = substr($to_user, 0, strlen($to_user) - 1);
        $cc_json = json_encode($cc_arr);
        $cc_user = substr($cc_user, 0, strlen($cc_user) - 1);

        $this->smallHeader = 'Reply';

        if($type == '1'){
            $this->render('rfi_returnform',array('check_id'=> $check_id,'program_id'=>$program_id,'type'=>$type,'to_user'=>$to_user,'cc_user'=>$cc_user,'cc_json'=>$cc_json));
        }else{
            $this->render('rfa_returnform',array('check_id'=> $check_id,'program_id'=>$program_id,'type'=>$type,'to_user'=>$to_user,'cc_user'=>$cc_user,'cc_json'=>$cc_json));
        }
    }
    /**
     * 评论
     */
    public function actionComment() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $apply_user_id = $rf_model->apply_user_id;
        $program_id = $rf_model->project_id;
        $type = $rf_model->type;
        $step = $rf_model->current_step;

        $this->smallHeader = 'Comment';

        if($type == '1'){
            $this->render('rfi_commentform',array('check_id'=> $check_id,'program_id'=>$program_id,'type'=>$type));
        }else{
            $this->render('rfa_commentform',array('check_id'=> $check_id,'program_id'=>$program_id,'type'=>$type));
        }
    }
    /**
     * 保存评论
     */
    public function actionSaveComment() {
        $rf = $_REQUEST['rf'];
        $r = RfList::commentList($rf);
        print_r(json_encode($r));
    }
    /**
     * 保存回复
     */
    public function actionSaveReply() {
        $rf = $_REQUEST['rf'];
        $cc = $_REQUEST['cc'];
        $to = $_REQUEST['to'];
        $rf['to'] = $to;
        $rf['cc'] = $cc;
        $r = RfList::replyList($rf);
        $rf_model = RfList::model()->findByPk($rf['check_id']);
        $status = $rf_model->status;
        if($rf['type_id'] == '2'){
            if($r['status'] == '1'){
                $r = RfList::closeList($rf);
            }
        }
        print_r(json_encode($r));
    }
    /**
     * 拒绝
     */
    public function actionReject() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '6';
        $this->smallHeader = 'RFIs & RFAs';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 保存拒绝
     */
    public function actionSaveReject() {
        $rf = $_REQUEST['rf'];
        $r = RfList::rejectList($rf);
        print_r(json_encode($r));
    }
    /**
     * 批准
     */
    public function actionApprove() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '4';
        $this->smallHeader = 'RFIs & RFAs';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * to人员列表
     */
    public function actionToList() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $type = $rf_model->type;
        $status = $rf_model->status;
        $sql = "select step from rf_record_user
                 where check_id=:check_id group by step order by step asc ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $length = count($rows);
        if($status == '-1'){
            $sql = "select * from rf_record_user
                 where check_id=:check_id and step='1' and type = '1'";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
            $rs = $command->queryAll();
            if(count($rs)>0){
                foreach($rs as $i => $j){
                    $user_model = Staff::model()->findByPk($j['user_id']);
                    $user_name = $user_model->user_name;
                    $to['id'] = $j['user_id'];
                    $to['text'] = $user_name;
                    $to_arr[] = $to;
                }
            }
        }else{
            if($length > 1){
                if($type == '2'){
                    $rf_model = RfList::model()->findByPk($check_id);
                    $apply_user_id = $rf_model->apply_user_id;
                    $user_model = Staff::model()->findByPk($apply_user_id);
                    $user_name = $user_model->user_name;
                    $to['id'] = $apply_user_id;
                    $to['text'] = $user_name;
                    $to_arr[] = $to;
                }else{
                    $step = $rows[$length-2]['step'];
                    $sql = "select * from rf_record_user
                        where check_id=:check_id and step=:step and type = '1'";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                    $command->bindParam(":step", $step, PDO::PARAM_STR);
                    $rs = $command->queryAll();
                    if(count($rs)>0){
                        foreach($rs as $i => $j){
                            $user_model = Staff::model()->findByPk($j['user_id']);
                            $user_name = $user_model->user_name;
                            $to['id'] = $j['user_id'];
                            $to['text'] = $user_name;
                            $to_arr[] = $to;
                        }
                    }
                }
            }else{
                $rf_model = RfList::model()->findByPk($check_id);
                $apply_user_id = $rf_model->apply_user_id;
                $user_model = Staff::model()->findByPk($apply_user_id);
                $user_name = $user_model->user_name;
                $to['id'] = $apply_user_id;
                $to['text'] = $user_name;
                $to_arr[] = $to;
            }
        }


        print_r(json_encode($to_arr));
    }
    /**
     * cc人员列表
     */
    public function actionReturnCcList() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $step = $rf_model->current_step;
        $rf_user_list = RfUser::userListByStep($check_id,'1');
        $cc_user = '';
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }
        foreach($rf_user_list as $i => $j){
            if($j['type'] == '2'){
                $cc_user.=$j['user_id'].',';
                $cc['id'] = $j['user_id'];
                $cc['text'] = $j['user_name'];
                $cc_arr[] = $cc;
            }
            if($j['type'] == '1'){
                if($user_id != $j['user_id']){
                    $cc['id'] = $j['user_id'];
                    $cc['text'] = $j['user_name'];
                    $cc_arr[] = $cc;
                }
            }
        }
        print_r(json_encode($cc_arr));
    }
    /**
     * cc人员列表
     */
    public function actionCcList() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $step = $rf_model->current_step;
        $rf_user_list = RfUser::userListByStep($check_id,'1');
        $cc_user = '';
        foreach($rf_user_list as $i => $j){
            if($j['type'] == '2'){
                $cc_user.=$j['user_id'].',';
                $cc['id'] = $j['user_id'];
                $cc['text'] = $j['user_name'];
                $cc_arr[] = $cc;
            }
        }
        print_r(json_encode($cc_arr));
    }
    /**
     * cc列表
     */
    public function actionStaffList() {
        $contractor_id = $_REQUEST['contractor_id'];
        $r = Staff::staffList($contractor_id);
        print_r(json_encode($r));
    }
    /**
     * 公司列表
     */
    public function actionContractorList() {

        $sql = "SELECT contractor_id,contractor_name FROM bac_contractor WHERE status=00 AND contractor_type='MC'";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs['id'] = $row['contractor_id'];
                $rs['text'] = $row['contractor_name'];
                $r[] = $rs;
            }
        }
        print_r(json_encode($r));
    }
    /**
     * 保存批准
     */
    public function actionSaveApprove() {
        $rf = $_REQUEST['rf'];
        $r = RfList::approveList($rf);
        print_r(json_encode($r));
    }
    /**
     * 批准(带评论)
     */
    public function actionApproveComment() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '5';
        $this->smallHeader = 'RFIs & RFAs';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 重新修改提交
     */
    public function actionReviseResubmit() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '11';
        $this->smallHeader = 'RFIs & RFAs';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    public function actionApproveSubmit() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '12';
        $this->smallHeader = 'RFIs & RFAs';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    public function actionRecordPurposes() {
        $check_id = $_REQUEST['check_id'];
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_list = Program::ProgramAllCompany($program_id);
        $deal_type = '13';
        $this->smallHeader = 'RFIs & RFAs';
        $this->render('_returnform',array('check_id'=> $check_id,'contractor_list'=>$contractor_list,'deal_type'=>$deal_type));
    }
    /**
     * 保存批注
     */
    public function actionSaveAttachNote() {
        $rf = $_REQUEST['AttachNot'];
        $r = RfAttachNote::saveNote($rf);
        print_r(json_encode($r));
    }
    /**
     * 保存批准(带评论)
     */
    public function actionSaveApproveComment() {
        $rf = $_REQUEST['rf'];
        $r = RfList::approveCommentList($rf);
        print_r(json_encode($r));
    }
    /**
     * 保存重新修改重新提交
     */
    public function actionSaveReviseResubmit() {
        $rf = $_REQUEST['rf'];
        $r = RfList::reviseResubmitList($rf);
        print_r(json_encode($r));
    }

    public function actionSaveApproveSubmit() {
        $rf = $_REQUEST['rf'];
        $r = RfList::approveSubmitList($rf);
        print_r(json_encode($r));
    }

    public function actionSaveRecordPurposes() {
        $rf = $_REQUEST['rf'];
        $r = RfList::recordPurposesList($rf);
        print_r(json_encode($r));
    }
    /**
     * 撤销
     */
    public function actionWithdraw() {
        $args['check_id'] = $_REQUEST['check_id'];
        $r = RfList::withdrawList($args);
        print_r(json_encode($r));
    }
    /**
     * 确认
     */
    public function actionConfirm() {
        $args['check_id'] = $_REQUEST['check_id'];
        $r = RfList::confirmList($args);
        print_r(json_encode($r));
    }
    /**
     * 关闭
     */
    public function actionClose() {
        $args['check_id'] = $_REQUEST['check_id'];
        $r = RfList::closeList($args);
        print_r(json_encode($r));
    }

    /**
     * 根据项目和承包商查询入场人员
     */
    public function actionQueryUser() {
        $contractor_id = $_POST['from'];
        $program_id = $_POST['program_id'];
        $rows = ProgramUser::UserListByMcProgram($contractor_id,$program_id);

        print_r(json_encode($rows));
    }

    /**
     * 根据项目和承包商查询入场人员
     */
    public function actionModelList() {
        $project_id = $_POST['project_id'];
        $rows = RevitModel::queryList($project_id);
        print_r(json_encode($rows));
    }

    /**
     * 详情
     */
    public static function actionDownloadPdf() {
        $id = $_REQUEST['check_id'];
        $params['id'] = $id;
        $app_id = 'RF';
        $apply = RfList::model()->findByPk($id);//许可证基本信息表
        $params['form_id'] = $apply->form_id;
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

    /**
     * 详情
     */
    public function actionRecordByModel() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Model With Record';
        $this->contentHeader = 'Model With Record';
        $this->render('model_record_list',array('program_id' => $program_id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genModelRecordGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=rf/rf/modelrecordgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('license_licensepdf', 'apply_id'), '', 'center');
        $t->set_header('Tag', '', 'center');
        $t->set_header('Ref No.', '', 'center');
        $t->set_header('Subject', '', 'center');
        $t->set_header('Created on', '', 'center');
        $t->set_header('Latest Date to Reply', '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionModelRecordGrid() {
        $fields = func_get_args();

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 4 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
        }
        $t = $this->genModelRecordGrid();
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $app_id = 'RFI';
        $list = RfModelComponent::queryList($page, $this->pageSize, $args);
        $this->renderPartial('model_rf_list', array('t' => $t,'program_id'=>$args['program_id'],'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 详情
     */
    public function actionQrByModel() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Model With Record';
        $this->contentHeader = 'Model With Record';
        $this->render('model_qr_list',array('program_id' => $program_id));
    }

    /**
     * 二维码
     */
    public function actionQrByPrint() {
        $model_id = $_REQUEST['model_id'];
        $version = $_REQUEST['version'];
        $uuid = $_REQUEST['uuid'];
        $entityId = $_REQUEST['entityId'];
        $type = $_REQUEST['type'];
        $domain = RevitModel::domainText();
        $categor = RevitModel::categoryText();
        $program_id = $_REQUEST['program_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $root_model = Program::model()->findByPk($root_proid);
        $contractor_id = $root_model->contractor_id;
        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $contractor_id . '/model/';
        //include "qrlib.php";
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);
        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


        //    $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        $filename = $PNG_TEMP_DIR . $entityId . '.png';
        $content['model_id'] = $model_id;
        $content['version'] = $version;
        $content['uuid'] = $uuid;
        $content['program_id'] = $program_id;
        $content = json_encode($content);
        $content = base64_encode($content);
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        $this->renderPartial('print_qrcode',array('category'=>$categor,'filename'=>$filename,'model_id'=>$model_id,'version'=>$version,'entityId'=>$entityId,'uuid'=>$uuid,'type'=>$type));
    }

    /**
     * 全部构件二维码
     */
    public function actionAllQrByPrint() {
        $model_id = $_REQUEST['model_id'];
        $version = $_REQUEST['version'];
        $program_id = $_REQUEST['program_id'];

        $html_content = "<>";

        $this->renderPartial('print_all_qrcode',array('program_id'=>$program_id,'model_id'=>$model_id,'version'=>$version));
    }

    /**
     * 获取模型全部信息的数量
     */
    public function actionGetModelData() {

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);

        $program_id = $_REQUEST['program_id'];

        $entity_str = $_REQUEST['entity_str'];


        $data = array(
            'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
            'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        $url = "https://bim.cmstech.sg/api/v1/token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $uuid = explode(',',$entity_str);
        foreach ($uuid as $i => $j){
            $info = explode('_',$j);
            $model_id = $info[0];
            $version = $info[1];
            $guid = $info[2];

            $arr = array(
                'x-access-token:'.$rs['data']['token']
            );

            $url = "https://bim.cmstech.sg/api/v1/entity/$guid?modelId=".$model_id."&version=".$version;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            //跳过SSL验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $r = json_decode($output,true);
            $result[] =$r['data'];
        }

        $result_str  = json_encode($result);
        $redis->set('model_list', $result_str);
        //include "qrlib.php";
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $root_model = Program::model()->findByPk($root_proid);
        $contractor_id = $root_model->contractor_id;
        $s['rowcnt'] = count($result);
        $redis->close();
        print_r(json_encode($s));
    }

    /**
     * 读取模型全部信息
     */
    public function actionReadModelData() {

        $startrow = $_REQUEST['startrow'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $program_id = $_REQUEST['program_id'];

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $model_list = $redis->get('model_list');
        $rs = json_decode($model_list,true);
        //include "qrlib.php";
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $root_model = Program::model()->findByPk($root_proid);
        $contractor_id = $root_model->contractor_id;
        $count = count($rs['data']);
        $pagedata=array_slice($rs,$startrow,$per_read_cnt);
        if(count($pagedata)>0){
            $file_path = ModelQr::downloadPdf($pagedata,$program_id);
            $filepath_cnt = $redis->lPush('file-list', $file_path);
            $redis->set('filepath_cnt', $filepath_cnt);
            $r['file_path'] = $file_path;
        }else{
            $r['file_path'] = '';
        }
        $i = 0;
        $redis->close();
        print_r(json_encode($r));
//        foreach($pagedata as $e => $data) {
//            foreach ($data as $k => $v) {
//                if ($k != 'properties') {
//                    $r[$i]['model_id'] = $data['modelId'];
//                    $r[$i]['uuid'] = $data['uuid'];
//                    $r[$i]['entityId'] = $data['entityId'];
//                    $r[$i]['type'] = $data['type'];
//                    $r[$i]['version'] = $data['version'];
//                }
//            }
//            $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $contractor_id . '/model/';
//
//            if (!file_exists($PNG_TEMP_DIR))
//                @mkdir($PNG_TEMP_DIR, 0777, true);
//
//            //processing form input
//            //remember to sanitize user input in real-life solution !!!
//            $errorCorrectionLevel = 'L';
//            if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
//                $errorCorrectionLevel = $_REQUEST['level'];
//
//            $matrixPointSize = 6;
//            if (isset($_REQUEST['size']))
//                $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
//            $filename = $PNG_TEMP_DIR . $data['entityId'] . '.png';
//            $r[$i]['filename'] = $filename;
//            $content = array();
//            $content['model_id'] = $model_id;
//            $content['version'] = $version;
//            $content['uuid'] = $data['uuid'];
//            $content['program_id'] = $program_id;
//            $content = json_encode($content);
//            QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//            $i++;
//        }

    }

    /**
     * 下载压缩包，清除redis缓存，列表
     */
    public function actionclearcache() {
        $time_str = time();
        $filename = ModelQr::createZip($time_str);
        if (file_exists($filename) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('".Yii::t('common','Document not found')."');</script>";
            return;
        }
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $redis->delete('model_list');
        $redis->delete('file-list');
        $redis->delete('filepath_cnt');
        $redis->close();

        $file = fopen($filename, "r"); // 打开文件
        header('Content-Encoding: none');
        header("Content-Type: application/force-download");
//        Header( "Content-type: application/octet-stream ");
//        header("Content-type: application/zip");
        header("Accept-Ranges: bytes");
        header("Accept-Length: " . filesize($filename));
        header('Content-Transfer-Encoding: binary');
        $name = basename($filename);
        header("Content-Disposition: attachment; filename=" . $name); //以真实文件名提供给浏览器下载
        header('Pragma: no-cache');
        header('Expires: 0');
        echo fread($file, filesize($filename));
        fclose($file);
//        echo readfile($filename);
//        $downloadHelper=new DownloadHelper($name);
//        $downloadHelper->fileDownload();
        unlink($filename);

    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genModelGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=rf/rf/modelgrid';
        $t->updateDom = 'datagrid';
        $t->set_header('Name', '', 'center');
        $t->set_header('Uuid', '', 'center');
        $t->set_header('EntityId', '', 'center');
        $t->set_header('Floor', '', 'center');
        $t->set_header('Domain', '', 'center');
        $t->set_header('Category', '', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionModelGrid() {

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $t = $this->genModelGrid();
        $this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = RevitModel::modelList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('model_list', array('t' => $t,'program_id'=>$args['program_id'],'app_id'=>$app_id,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 查询附件
     */
    public function actionAttachment() {
        $check_id = $_REQUEST['check_id'];
        $detail_list = RfAttachment::dealList($check_id);
        $rf_model = RfList::model()->findByPk($check_id);
        $program_id = $rf_model->program_id;
        $contractor_id = $rf_model->contractor_id;
//        var_dump($detail_list);
        $this->renderPartial('attachment_list', array('program_id'=>$program_id,'contractor_id'=>$contractor_id,'detail_list'=>$detail_list));
    }

    /*
     * 同步附件
     */
    public function  actionSyncAttachment(){
        $tag = $_REQUEST['tag'];
        $label = $_REQUEST['label'];
        $program_id = $_REQUEST['program_id'];
        $contractor_id = $_REQUEST['contractor_id'];
        $rows = RfAttachment::movePic($program_id,$contractor_id,$tag,$label);
        print_r(json_encode($rows));
    }

    /*
     * 搜索构件
     */
    public function  actionSearchEntity(){
        $type = $_REQUEST['type'];
        $big_type = $_REQUEST['big_type'];
        $detail = urlencode($_REQUEST['detail']);
        $model_id = $_REQUEST['model_id'];
        $data = array(
            'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
            'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        $url = "https://bim.cmstech.sg/api/v1/token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $data = array(
            'id' => $model_id,
            'vague' => 'true',
            'type' => $type,
            'value' => $detail,
            'logical' => 'and',
            'property' => 'false',
        );
        $arr = array(
            'x-access-token:'.$rs['data']['token']
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        //https://bim.cmstech.sg/api/v1/models/5e099f9f3443310011286d98/components?version=1&property=undefined
        if($big_type == '1'){
            $url = "https://bim.cmstech.sg/api/v1/models/".$model_id."/componentsBy?value=".$detail."&type=".$type."&vague=true&logical=and&property=false";
        }else{
            $url = "https://bim.cmstech.sg/api/v1/models/".$model_id."/relationsBy?value=".$detail."&type=".$type."&vague=true&logical=and&property=false";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        //跳过SSL验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        print_r(json_encode($rs['data']));
    }

    /**
     * 统计图表
     */
    public function actionTestChart() {
        $program_id = $_REQUEST['program_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $this->smallHeader = 'Statistics';
        $this->render('statistical_project',array('program_id'=>$root_proid));
    }

    /**
     * 统计图表
     */
    public function actionDashboard() {

        $program_id = $_REQUEST['program_id'];
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $this->smallHeader = 'Statistics';
        $this->render('dashboard',array('program_id'=>$root_proid));
    }

    /**
     *按状态查询（项目）
     */
    public function actionAllByProject() {
        $args['program_id'] = $_REQUEST['id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RfList::ALLCntList3($args);
        print_r(json_encode($r));
    }
    /**
     *按类型查询项目（RFA）
     */
    public function actionCntByProject() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RfList::StatusCntList($args);
        print_r(json_encode($r));
    }
    /**
     *按状态查询项目（RFI）
     */
    public function actionCntByProject2() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RfList::StatusCntList2($args);
        print_r(json_encode($r));
    }
    /**
     *按类型查询项目（RFA）
     */
    public function actionTypeByProject() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RfList::TypeCntList($args);
        print_r(json_encode($r));
    }
    /**
     *按类型查询项目（RFI）
     */
    public function actionTypeByProject2() {
        $args['program_id'] = $_REQUEST['id'];
        $args['date'] = $_REQUEST['date'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $r = RfList::TypeCntList2($args);
        print_r(json_encode($r));
    }

    public function actionDeleteRecord()
    {
        $tag = $_REQUEST['tag'];
        $tag_list = explode('|', $tag);
//        $to_user = '861';
//        $args['check_no'] = 'CCDC-C4C5-CS-RFA-011';
//        $args['subject'] = 'Subject_test';
//        $args['valid_time'] = '2020-12-27';
//        MailType::sendMail2($to_user,$args);
//        MailType::sendMail3($to_user,$args);
        foreach($tag_list as $i => $check_id){
            $sql = "insert into rf_record_del select * from rf_record where check_id=:check_id ;";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
            $command->execute();

            $sql = 'DELETE FROM rf_record WHERE check_id=:check_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_INT);
            $rs = $command->execute();

//            $sql = 'DELETE FROM rf_record_user WHERE check_id=:check_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":check_id", $check_id, PDO::PARAM_INT);
//            $rs = $command->execute();
//
//            $sql = 'DELETE FROM rf_record_item WHERE check_id=:check_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":check_id", $check_id, PDO::PARAM_INT);
//            $rs = $command->execute();
//
//            $sql = 'DELETE FROM rf_record_detail WHERE check_id=:check_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":check_id", $check_id, PDO::PARAM_INT);
//            $rs = $command->execute();
//
//            $sql = 'DELETE FROM rf_record_attach WHERE check_id=:check_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":check_id", $check_id, PDO::PARAM_INT);
//            $rs = $command->execute();
        }
        $r['msg'] = 'Delete Success';
        print_r(json_encode($r));
    }

    /**
     * 心跳保持
     */
    public function actionSendHeart() {
        $confirm = $_REQUEST['confirm'];
        $r['msg'] = $confirm;
        print_r(json_encode($r));
    }

    /**
     * 循环forward
     */
    public function actionLoopForward($check_id) {
        $rf_model = RfList::model()->findByPk($check_id);
        $link_check_id = $rf_model->link_check_id;
        if($link_check_id){
            $link_list = explode(',', $link_check_id);
            foreach($link_list as $link_index => $link_id){
                $rf_model = RfList::model()->findByPk($link_id);
                $project_id = $rf_model->project_id;
                $form_id = $rf_model->form_id;
                $this->renderPartial($form_id.'_forward_info',array('login_program_id'=>$project_id,'check_id'=>$link_id));
                self::actionLoopForward($link_id);
            }
        }
    }
}
