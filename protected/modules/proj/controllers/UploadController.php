<?php

class UploadController extends AuthBaseController {
    public $pageSize = 8;
    
    /**
     * 查询
     */
    public function actionGrid() {
        
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        //$args = $_GET['q']; //查询条件

        $args = array();
        if($args['program_id'] == ''){
            $args['program_id'] = $_GET['program_id'];   
        }
        if($args['task_id'] == ''){
            $args['task_id'] = $_GET['task_id'];   
        }
        //$args['program_id'] = $_SESSION['program_id'];
        $program_id = $args['program_id'];
        $task_id = $args['task_id'];
        //unset($_SESSION['program_id']);
//        var_dump($_GET['program_id']);
//        exit;
        
        $t = $this->genDataGrid($task_id,$program_id);
        //$this->saveUrl();
        $args['contractor_id'] = Yii::app()->user->contractor_id;
//        var_dump($_GET['program_id']);
//        exit;
        $list = TaskAttach::queryList($page, $this->pageSize, $args);
        
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'],'program_id'=> $program_id,'task_id'=>$task_id ,'curpage' => $list['page_num']));
    }
        
    private function genDataGrid($task_id,$program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=proj/upload/grid&program_id='.$program_id.'&task_id='.$task_id;
        $t->updateDom = 'datagrid';

        return $t;
    }
    
    /**
     * 附件列表
     */
    public function actionAttachlist() {
        $program_id = $_GET['program_id'];
        $task_id = $_GET['task_id'];
//        var_dump($task_id);
//        var_dump($program_id);
//        exit;
        if($program_id <> '')
            $father_model = Program::model()->findByPk($program_id);
        $this->smallHeader = $father_model->program_name;
        $this->contentHeader = Yii::t('proj_project', 'sub contentHeader');
        $this->bigMenu = $father_model->program_name;
        $this->render('list',array('program_id'=>$program_id,'task_id'=>$task_id));
    }
    /**
     * 附件上传
     */
    public function actionUpload() {
        $args = $_GET['q'];
        $program_id = $args['program_id'];
        $task_id = $args['task_id'];
//        var_dump($task_id);
//        var_dump($program_id);
//        exit;
        $model = new TaskAttach('create');
        $this->renderPartial('upload',array('model'=> $model,'program_id'=>$program_id,'task_id'=>$task_id));
    }
     /**
     * 添加附件和内容
     */
    public function actionNew() {
        $args = array();
        $args['program_id'] = $_REQUEST['program_id'];
        $args['task_id'] = $_REQUEST['task_id'];
        $args['attach_content'] = $_REQUEST['attach_content'];
        //$args = $_REQUEST['TaskAttach'];
        $program_id = $args['program_id'];
//        var_dump($_FILES['TaskAttach']['name']['task_attach']);
//        exit;
        //判断文件是不是为空
	if ($_FILES['TaskAttach']['name']['task_attach'] == ''){
            $r['status'] = '-1';
            $r['msg'] = Yii::t('comp_staff','Error Upload_pic is null');
            $r['refresh'] = false;
            goto end;
        }
        $rs = UploadFiles::uploadPicture($_FILES['TaskAttach'], $program_id);
//        var_dump($rs);
//        exit;
        $r['status'] = '';
	$r['msg'] = '';
	foreach($rs as $key => $row){
            if($row['code'] <> 0){
                $r['status']  .= $row['code'];
                $r['msg']  .= $row['msg'];
            }else{
                $args[$key] = $row['upload_file'];
            }
        }
        if($r['status'] <> ''){
            $r['refresh'] = false;
            goto end;
        }
//        var_dump($r);
//        exit;
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
//        var_dump($args);
//        exit;
        $r = TaskAttach::insertAttach($args);
//        var_dump($r);
//        exit;
        //array(2) { ["status"]=> int(1) ["name"]=> string(6) "匿名" } 
        //array(3) { ["status"]=> string(1) "1" ["msg"]=> string(15) "添加成功！" ["refresh"]=> bool(false) } 
        end:
//        var_dump($r);
//        exit;
        print_r(json_encode($r));
    }
}
//{"status":"-1","msg":"\u6dfb\u52a0\u6210\u529f\uff01","refresh":false}
//{"status":"1","msg":"\u6dfb\u52a0\u6210\u529f\uff01","refresh":true}
//{"status":"-1","msg":"task_attach: \u4e0a\u4f20\u7684\u6587\u4ef6\u4e0d\u80fd\u5927\u4e8e200K\u3002","refresh":false}
//{"status":"-1","msg":"\u6ca1\u6709\u4e0a\u4f20\u56fe\u7247\u3002","refresh":false}
//{"status":1,"msg":"\u6dfb\u52a0\u6210\u529f\uff01","refresh":true}