<?php
/**
 * 质量检查
 * @author LiuMinChao
 */
class Qadefect2Controller extends AuthBaseController
{

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    const STATUS_NORMAL = 0; //正常

    public function init()
    {
        parent::init();
        $this->contentHeader = Yii::t('comp_routine', 'contentHeader');
        $this->bigMenu = Yii::t('comp_routine', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($args)
    {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=qa/qadefect2/grid';
        $t->updateDom = 'datagrid';
        $t->set_header('S/N', '', 'center');
        $t->set_header('Category', '', 'center');
        $t->set_header('Description', '', 'center');
        $t->set_header('Block', '', 'center');
        $t->set_header('Level', '', 'center');
        $t->set_header('Initiator', '', '');
        $t->set_header('Person to rectify', '', '');
        $t->set_header('Issue date', '', '');
        $t->set_header(Yii::t('comp_qa', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '10%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid()
    {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        var_dump($args);
//        exit;

        $t = $this->genDataGrid($args);
        $this->saveUrl();
//        $args['status'] = ApplyBasic::STATUS_FINISH;
        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = QaDefect2::queryList($page, $this->pageSize, $args);
//        var_dump($list['rows']);
        $this->renderPartial('_list', array('t' => $t,'phase' => $args['phase'], 'program_id' => $args['program_id'], 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }


    /**
     * 列表
     */
    public function actionCompletionList()
    {
        $program_id = $_REQUEST['program_id'];
        $phase = 'DEFECT-C';
        $args = array();
        if ($_GET['q']) {
            $args = $_GET['q'];
        }
        $this->smallHeader = 'Completion';
        $this->render('list', array('program_id' => $program_id,'phase'=>$phase,'args' => $args));
    }
    /**
     * 列表
     */
    public function actionHandoverList()
    {
        $program_id = $_REQUEST['program_id'];
        $phase = 'DEFECT-H';
        $args = array();
        if ($_GET['q']) {
            $args = $_GET['q'];
        }
        $this->smallHeader = 'Handover';
        $this->render('list', array('program_id' => $program_id,'phase'=>$phase,'args' => $args));
    }
    /**
     * 列表
     */
    public function actionDlpList()
    {
        $program_id = $_REQUEST['program_id'];
        $phase = 'DEFECT-D';
        $args = array();
        if ($_GET['q']) {
            $args = $_GET['q'];
        }
        $this->smallHeader = 'DLP';
        $this->render('list', array('program_id' => $program_id,'phase'=>$phase,'args' => $args));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['qa/qadefect/list'] = str_replace("r=qa/qadefect/grid", "r=qa/qadefect/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

    /**
     * 下载附件列表
     */
    public function actionDownloadAttachment() {
        $check_id = $_REQUEST['check_id'];
        $defect_model = QaDefect2::model()->findByPk($check_id);
        exec('php /opt/www-nginx/web/test/bimax/protected/yiic merge createimage2 --param1='.$check_id.' >/dev/null  &');
//        $new_path = Drawing::createDrawing($drawing_path,$position_list);
        $drawing_id = $defect_model->drawing_id;
        $drawing_path = $defect_model->drawing_pic_path;
        $documen_list = QaDocument::detailList($check_id); //记录
        $this->renderPartial('download_attachment', array('check_id'=>$check_id,'drawing_id'=>$drawing_id,'drawing_path'=>$drawing_path));
    }

    /**
     * 查询
     */
    public function actionWorkflow() {
        $check_id = $_REQUEST['check_id'];
        $this->renderPartial('workflow', array('check_id' => $check_id));
    }

    /**
     * 在线预览文件
     */
    public function actionPreviewDoc() {
        $check_id = $_REQUEST['check_id'];
        $defect_model = QaDefect2::model()->findByPk($check_id);
        $drawing_path = $defect_model->drawing_pic_path;
        $file = explode('.',$drawing_path);
        $file_type = $file[1];
        $file_name = $file[0];
        $path = $file_name.'_position.'.$file_type;
//        $path = encodeURI(encodeURIComponent($path));
//        var_dump($path);
//        exit;
        $this->renderPartial('preview_doc',array('file'=>$path));
    }

    /**
     * 下载文档
     */
    public function actionDownload() {
        $check_id = $_REQUEST['check_id'];
        $defect_model = QaDefect::model()->findByPk($check_id);
        $drawing_id = $defect_model->drawing_id;
        $drawing_path = $defect_model->drawing_pic_path;
        $file = explode('.',$drawing_path);
        $file_type = $file[1];
        $file_name = $file[0];
        $path = $file_name.'_position.'.$file_type;
        $drawing_model = ProgramDrawing::model()->findByPk($drawing_id);
        $drawing_name = $drawing_model->drawing_name;
        if(file_exists($path)) {
            Utils::Download($path, $drawing_name, $file_type);
            return;
        }
    }
}