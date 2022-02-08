<?php

/**
 * 平台文档管理
 * @author LiuMinchao
 */
class PlatformController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('comp_document', 'contentHeader_platform');
        $this->bigMenu = Yii::t('comp_document', 'bigMenu_platform');
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
        $t->set_header(Yii::t('comp_document', 'commonly_used'), '', 'center');
        $t->set_header(Yii::t('comp_document', 'label'), '', 'center');
        $t->set_header(Yii::t('comp_document', 'upload_time'),'','center');
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
        $args['type'] = 1;
        $t = $this->genDataGrid();
//        $this->saveUrl();
        $list = Document::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'],'rs' =>$rs, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $this->smallHeader = Yii::t('comp_document', 'smallHeader List Platform');
        $this->render('list');
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
        $this->renderPartial('upload');
    }
    /**
     * 来源
     */
    public function actionSource() {
        $sql = "SELECT label_id,label_name,label_name_en FROM bac_document_label where type=1";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        $i = 1;
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$i]['id'] = $row['label_id'];
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$i]['name'] = $row['label_name'];
                }else{
                    $rs[$i]['name'] = $row['label_name_en'];
                }
                $i++;
            }
        }
        print_r( json_encode($rs,true));
//        $rs[0]['id'] = 'null';
//        $rs[0]['name'] = '无';
    }
    /**
     * 将上传的图片移动到正式路径下
     */
    public function actionMove() {
        $file_src = $_REQUEST['file_src'];
        $args['type'] = 1;
        $r = Document::movePic($file_src,$args);
        print_r(json_encode($r));
    }
    /**
     * 设置标签
     */
    public function actionSettags() {
        $doc_id = $_REQUEST['doc_id'];
        $label_id = $_REQUEST['value'];
        $rs = Document::SetTag($doc_id,$label_id);
        echo json_encode($rs);
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
     * 设置常用
     */
    public function actionSetused() {
        $doc_id = trim($_REQUEST['doc_id']);
        $doc_use = trim($_REQUEST['doc_use']);
        $r = array();
        $r = Document::setUsed($doc_id,$doc_use);
        //var_dump($r);
        echo json_encode($r);
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
}
