<?php

/**
 * 协议管理
 * @author yangtl
 */
class AgreeController extends BaseController {

    public $gridId = 'list';
    
    public $pageSize = '10';
    
    public $smallHeader = "协议列表";
    public $contentHeader = "协议管理";
    public $bigMenu = "协议管理";
    
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genGrid($status) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=agree/grid&status='.$status;
        $t->updateDom = 'datagrid';
        $t->set_header('序号', '', '','');
        $t->set_header('协议名称', '', '','');
        $t->set_header('上传用户', '', '','');
        $t->set_header('上传时间', '', '','');
        $t->set_header('操作', '', '');
        return $t; 
    }
    

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $status = $_REQUEST["status"];
        
        $args["audit_user"] = Yii::app()->user->getState("user_id");
        $args["status"] = $status;
        
        $list = Agreement::queryList($page,$this->pageSize,$args);
        //var_dump($args["STATUS"]);
        $t = $this->genGrid($status);
        $this->saveUrl();
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
    	$status = $_REQUEST["status"];
        $this->render('list',array("status"=>$status));
    }
    
    
    
    //审核
    public function actionAudit(){
    	$id = $_REQUEST["id"];
    	$model = Agreement::model()->findByPk($id);
    	$this->renderPartial("audit",array("model"=>$model));
    }
    
    //审核保存
    public function actionAuditsave(){
    	$audit = $_POST["audit"];
    	$id = $_GET["id"];
    	$rs = Agreement::audit($id, $audit);
    	Utils::ajaxFormMsg($rs,$this->gridId);
    }
    
    //查看
    public function actionView(){
    	$id = $_GET["id"];
    	$model = Agreement::model()->findByPk($id);
    	$this->render("view",array("model"=>$model));
    }
   
     /**
      * 保存查询链接
      */
     private function saveUrl() {
     	$a = Yii::app()->session['list_url'];
     	$a['agree/list'] = "index.php?".str_replace("r=agree/grid", "r=agree/list", $_SERVER["QUERY_STRING"]);
     	Yii::app()->session['list_url'] = $a;
     }
     
    

    
   
    
}