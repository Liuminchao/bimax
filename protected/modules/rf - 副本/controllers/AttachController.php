<?php

/**
 * RFA 图纸
 * @author LiuMinchao
 */
class AttachController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常
    public $pageSize = 40;

    public function init() {
        parent::init();
        $this->contentHeader = 'RFAs Attachment';
        $this->bigMenu = 'RFA/RFI';
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=document/platform/grid';
        $t->updateDom = 'datagrid';
//        $t->set_header('文档编号', '', '');
        $t->set_header(Yii::t('comp_document', 'document_name'), '', 'center');
//        $t->set_header(Yii::t('comp_document', 'commonly_used'), '', 'center');
//        $t->set_header(Yii::t('comp_document', 'label'), '', 'center');
        $t->set_header('Subject', '', 'center');
        $t->set_header('Archi', '', 'center');
        $t->set_header('M&E', '', 'center');
        $t->set_header('C&S', '', 'center');
        $t->set_header(Yii::t('license_licensepdf', 'status'), '', 'center');
        $t->set_header(Yii::t('comp_document', 'upload_time'),'','center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 1 && $fields[0] != null ) {
            $args['program_id'] = $fields[0];
        }
        $t = $this->genDataGrid();
//        $this->saveUrl();

        $list = RfAttachment::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'program_id' =>$args['program_id'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('comp_document', 'smallHeader List Platform');
        $this->render('list',array('program_id'=>$program_id));
    }

    /**
     * 展示
     */
    public function actionShow() {
        $this->render('show');
    }
    /**
     * 上传
     */
    public function actionUpload() {
        $program_id = $_REQUEST['program_id'];
        $this->renderPartial('upload',array('program_id'=>$program_id));
    }

    /**
     * 将上传的图片移动到正式路径下
     */
    public function actionMove() {
        $args = $_REQUEST['rf'];
        $r = RfAttachment::movePic($args);
        print_r(json_encode($r));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['document/list'] = str_replace("r=document/platform/grid", "r=document/platform/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $doc_path = $_REQUEST['doc_path'];
        $doc_id = $_REQUEST['doc_id'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('preview',array('doc_path'=>$doc_path,'doc_id'=>$doc_id));
    }
    /**
     * 删除文档
     */
    public function actionDelete() {
        $doc_id = trim($_REQUEST['doc_id']);
        $doc_path = trim($_REQUEST['doc_path']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Document::deleteFile($doc_id,$doc_path);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $doc_path = $_REQUEST['doc_path'];
        $rows = Document::queryFile($doc_path);
        if(count($rows)>0){
            $show_name = $rows[0]['doc_name'];
            $filepath = '/opt/www-nginx/web'.$rows[0]['doc_path'];
            $extend = $rows[0]['doc_type'];
//            var_dump($show_name);
//            var_dump($filepath);
//            var_dump($extend);
//            exit;
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }

    /**
     * 同步文档
     */
    public function actionPublish() {
//        $attach_id = $_REQUEST['attach_id'];
//        $this->renderPartial('publish',array('attach_id'=>$attach_id));
        $id = $_REQUEST['id'];
        $attach_model = RfAttachment::model()->findByPk($id);
        $check_id = $attach_model->check_id;
        $check_model = RfList::model()->findByPk($check_id);
        $project_id = $check_model->project_id;
        $file_path = $attach_model->doc_path;
        $file_name = $attach_model->doc_name;
        $file['path'] = 'https://shell.cmstech.sg'.$file_path;
        $file['program_id'] = $project_id;
        $file['name'] = $file_name;
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $file['user_id'] = $user_model->user_id;
        }
        $result = Dms::publish($file);
        if($result['code'] == '100'){
            $file_id = $result['fileid'];
            $attach_model->dms_id = $file_id;
            $attach_model->status =  '1';
            $attach_model->save();
        }
        $result['msg'] = Yii::t('common', 'success_insert');
        $result['status'] = 1;
        $result['refresh'] = true;
        print_r(json_encode($result));
    }

    /**
     * 同步文档
     */
    public function actionAllPublish() {
        $step = $_REQUEST['step'];
        $check_id = $_REQUEST['check_id'];
        $attach_list = RfRecordAttachment::dealListBystep($check_id,$step);
        foreach($attach_list as $i => $j){
            $id = $j['id'];
            $attach_model = RfAttachment::model()->findByPk($id);
            $check_id = $attach_model->check_id;
            $check_model = RfList::model()->findByPk($check_id);
            $project_id = $check_model->project_id;
            $file_path = $attach_model->doc_path;
            $file_name = $attach_model->doc_name;
            $file['path'] = 'https://shell.cmstech.sg'.$file_path;
            $file['program_id'] = $project_id;
            $file['name'] = $file_name;
            $operator_id = Yii::app()->user->id;
            $user = Staff::userByPhone($operator_id);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $file['user_id'] = $user_model->user_id;
            }
            $result = Dms::publish($file);
            if($result['code'] == '100'){
                $file_id = $result['fileid'];
                $attach_model->dms_id = $file_id;
                $attach_model->status =  '1';
                $attach_model->save();
            }
        }
        $result['msg'] = Yii::t('common', 'success_insert');
        $result['status'] = 1;
        $result['refresh'] = true;
        print_r(json_encode($result));
    }

    /*
     * 同步附件
     */
    public function  actionSyncAttachment(){
        $attach_id = $_REQUEST['attach_id'];
        $label = $_REQUEST['label'];
        $program_id = $_REQUEST['program_id'];
        $rows = RfAttachment::publishPic($program_id,$attach_id,$label);
        print_r(json_encode($rows));
    }

    /*
     * 同步记录附件中的状态
     */
    public function  actionSyncRecordAttachment(){
        $attach_id = $_REQUEST['attach_id'];
        $check_id = $_REQUEST['check_id'];
        $status = $_REQUEST['status'];
        $rows = RfRecordAttachment::syncDoc($check_id,$attach_id,$status);
        print_r(json_encode($rows));
    }

    /**
     * ModelComponent
     */
    public function actionComponentWithAttachment() {
        $id = $_REQUEST['id'];
        $this->renderPartial('component_attachment',array('id'=>$id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genRecordGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=rf/attach/grid';
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
    public function actionRecordGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        if(count($fields) == 1 && $fields[0] != null ) {
            $args['attach_id'] = $fields[0];
        }
        $t = $this->genRecordGrid();
//        $this->saveUrl();

        $list = RfRecordAttachment::queryList($page, $this->pageSize, $args);
        $this->renderPartial('record_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionRecordList() {
        $attach_id = $_REQUEST['attach_id'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = Yii::t('comp_document', 'smallHeader List Platform');
        $this->render('recordlist',array('attach_id'=>$attach_id,'program_id'=>$program_id));
    }
}
