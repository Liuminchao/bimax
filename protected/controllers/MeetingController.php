<?php

/**
 * 我的会议
 * @author yangtl
 */
class MeetingController extends BaseController {

    public $gridId = 'list';
    public $signgridId = 'sign';
    
    public $pageSize = '10';
    
    public $smallHeader = "我的会议";
    public $contentHeader = "会议管理";
    public $bigMenu = "会议管理";
    
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genGrid($mode) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=meeting/grid&mode='.$mode;
        $t->updateDom = 'datagrid';
        $t->set_header('序号', '', '','');
        $t->set_header('会议主题', '', '','');
        $t->set_header('会议时间', '', '','');
        $t->set_header('会议地点', '', '','');
        $t->set_header('我的签到', '', '','');
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
        $mode = $_REQUEST["mode"];
        if($mode=='found')
        	$list = Meeting::foundList($page,$this->pageSize,$args);
        else if($mode=='common')
        	$list = UserMeeting::commonList($page,$this->pageSize,$args);
        	
        $t = $this->genGrid($mode);
        $this->saveUrl();
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
    	$mode = $_REQUEST["mode"];
        $this->render('list',array("mode"=>$mode));
    }
    
    /**
     * 表头
     * @return SimpleGrid
     */
    private function genSigngrid($meeting_id) {
    	$t = new DataGrid($this->signgridId);
    	$t->url = 'index.php?r=meeting/signgrid&meeting_id='.$meeting_id;
    	$t->updateDom = 'datagrid';
    	$t->set_header('序号', '', '','');
    	$t->set_header('会议成员', '', '','');
    	$t->set_header('签到情况', '', '','');
    	$t->set_header('签到时间', '', '','');
    	return $t;
    }
    
    
    /**
     * 查询
     */
    public function actionSigngrid() {
    	$page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
    	$_GET['page'] = $_GET['page'] + 1;
    	$args = $_GET['q']; //查询条件
    	$meeting_id = $_GET["meeting_id"];
    	$args["meeting_id"] = $meeting_id;
    	$list = UserMeeting::signList($page,$this->pageSize,$args);
    	$t = $this->genSigngrid($meeting_id);
    	$this->renderPartial('_signlist', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    
    /**
     * 列表
     */
    public function actionSignlist() {
    	$id = $_GET["meeting_id"];
    	$name = $_GET["name"];
    	$this->render('signlist',array("meeting_id"=>$id,"name"=>$name));
    }
    
     /**
      * 保存查询链接
      */
     private function saveUrl() {
     	$a = Yii::app()->session['list_url'];
     	$a['meeting/list'] = "index.php?".str_replace("r=meeting/grid", "r=meeting/list", $_SERVER["QUERY_STRING"]);
     	Yii::app()->session['list_url'] = $a;
     }
     
    

    
   
    
}