<?php
/**
 * BlockChart管理
 * @author LiuXiaoyuan
 */
class ImportController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('sys_role', 'contentHeader');
        $this->bigMenu = Yii::t('sys_role', 'bigMenu Mc');
    }

    public function actionView() {
        $model = new ProgressPlanVersion('create');
        $program_id =  $_REQUEST['program_id'];
        $this->render("batch", array('model' => $model,'program_id'=>$program_id));
    }

    /**
     * 查询
     */
    public function actionTest() {
        require_once("http://localhost:8089/JavaBridge/java/Java.inc");
//        $javaPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'java'.DIRECTORY_SEPARATOR.'Java.inc';
//        require_once($javaPath);
//        $System = java("java.lang.System");
//        echo $System->getProperties();
//        //后台执行 非阻塞 异步
//        exec('java -jar /usr/local/java/JavaBridge.jar SERVLET_LOCAL:8089');
        $file_path = "/opt/www-nginx/web/test/idd/test.mpp";
        if (!file_exists($file_path)) {
            var_dump("文件不存在");
            return;
        }
        $data = self::parseMSPJ($file_path);
        echo "success";
        $fp = fopen('/opt/www-nginx/web/test/idd/a.txt', 'a+b');
        fwrite($fp, var_export($data, true));
        fclose($fp);
    }

    public function parseMSPJ($fileName)
    {
        $file_path   = $fileName;
        require_once 'http://localhost:8089/JavaBridge/java/Java.inc';
        $data        = array();if (empty($file_path)) {return $data;}
        $mppRead     = new Java('net.sf.mpxj.mpp.MPPReader');
        $Filecontent = $mppRead->read($file_path);
        $Alltasks   = $Filecontent->getChildTasks();
        $Tasks_size  = java_values($Alltasks->size());
        $data = array();
        if($Tasks_size){
            $children = self::getChildren($Alltasks[0]);
            if($children){
                $data = $children;
            }
        }
        return $data;
    }

    public function getChildren($task,$parent_id=-1){
        $child = $task->getChildTasks();
        $Tasks_size  = java_values($child->size());
        $data = array();
        $formatter = new Java('java.text.SimpleDateFormat', "yyyy-MM-dd HH:mm:ss");
        for ($i = 0; $i < $Tasks_size; $i++) {
            $separator = '|';
//            $tmp= getResource($child[$i]);
            $items = array();
            $items['id'] = $child[$i]->getID().'';
            $items['level'] = $child[$i]->getOutlineLevel().'';
            $items['uniqueID'] = $child[$i]->getUniqueID().'';
            $items['name'] = $child[$i]->getName().'';
            $items['duration'] = $child[$i]->getDuration().'';
            $start = $child[$i]->getStart();
            $items['start'] = $formatter->format($start).'';
            $finish = $child[$i]->getFinish();
            $items['finish'] = $formatter->format($finish).'';
            $items['percentComplete'] = $child[$i]->getPercentageComplete().'';
            $items['primary_id'] = $child[$i]->getText(1).'';
            $items['parent_id'] = $parent_id;
            //前置任务
//            $prev_task = self::getProcessors($child[$i]);
//            if ($prev_task) {
//                $items['prev_task'] = $prev_task;
//            }

            $children = self::getChildren($child[$i],$items['id']);
            $data[] = $items;
            if($children){
//                var_dump($children);
//                $items['children'] = $children;
                foreach($children as $x => $y){
                    $data[] = $y;
                }
            }
//            if ($children) {
//                $items['children'] = $children;
//            }
//            $data[] = $items;
        }
//        $tasks_size  = java_values($child->size());
//        for ($i = 0; $i < $tasks_size; $i++) {
//            $items                    = array();
//            $items['id']              = $child[$i]->getID() . '';
//            $items['name']            = $child[$i]->getName() . '';
//            //前置任务
//            $prev_task = self::getProcessors($child[$i]);
//            if ($prev_task) {
//                $items['prev_task'] = $prev_task;
//            }
//            //获取下一级任务
//            $children = self::getChildren($child[$i]);
//            if ($children) {
//                $items['children'] = $children;
//            }
//            $data[] = $items;
//        }

        return $data;
    }

    public function getProcessors($task)
    {
        $items            = array();
        $rs               = '';
        $predecessorLinks = $task->getPredecessors();
        $size             = java_values($predecessorLinks->size());
        $data             = array();
        for ($i = 0; $i < $size; $i++) {
            $tmp = $predecessorLinks[$i]->getTargetTask()->getUniqueID() . '';
            if ($tmp) {
                $items[] = $tmp;
            }
        }
        if ($items) {
            $rs = implode(',', $items);
        }
        return $rs;

    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid($program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=wf/import/grid&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Id', '', '');
        $t->set_header('Version', '', '');
        $t->set_header('Version Name', '', '');
        $t->set_header('Record Time', '', '');
        $t->set_header(Yii::t('sys_role', 'status'), '', '');
        $t->set_header(Yii::t('common', 'action'), '15%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $fields = func_get_args();
        if($_REQUEST['program_id']){
            $fields[0] = $_REQUEST['program_id'];
        }

        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $t = $this->genDataGrid($fields[0]);
        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = ProgressPlanVersion::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('program_id' => $fields[0], 't' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Progress Plan List';
        $this->render('list',array('program_id'=> $program_id));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDetailDataGrid($form_id,$program_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=wf/import/detailgrid&form_id='.$form_id.'&program_id='.$program_id;
        $t->updateDom = 'datagrid';
        $t->set_header('Id', '', '');
        $t->set_header('Plan Name', '', '');
        $t->set_header('Plan Start', '', '');
        $t->set_header('Plan Finish', '', '');
        $t->set_header('Duration', '', '');
        $t->set_header('Level', '', '');
        $t->set_header(Yii::t('sys_role', 'status'), '', '');
        $t->set_header(Yii::t('sys_role', 'status'), '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionDetailGrid() {
        $fields = func_get_args();
        if($_REQUEST['version_id']){
            $fields[0] = $_REQUEST['version_id'];
        }
        if($_REQUEST['program_id']){
            $fields[1] = $_REQUEST['program_id'];
        }
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $args['version_id'] = $fields[0];
        $t = $this->genDetailDataGrid($fields[0],$fields[1]);
        $this->saveUrl();
        //      $args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = ProgressPlan::queryList($page, $this->pageSize, $args);
        $this->renderPartial('detail_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionDetailList() {
        $form_id = $_REQUEST['form_id'];
        $program_id = $_REQUEST['program_id'];
        $this->smallHeader = 'Progress Plan';
        $this->render('detaillist',array('form_id'=> $form_id,'program_id'=>$program_id));
    }

    /**
     * 停用
     */
    public function actionStop() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {

            $r = QaChecklist::stopForm($id);
        }
        echo json_encode($r);
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['mcrole/list'] = str_replace("r=comp/role/grid", "r=comp/role/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }


    public function actionSave() {
        $checklist = $_REQUEST['ProgressPlanVersion'];
        $checklist['status'] = '0';
        $r = ProgressPlanVersion::saveForm($checklist);
        print_r(json_encode($r));
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
        $file_rs = UploadFiles::fileUpload($file,array("mpp"), $dir);
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

        require_once("http://localhost:8089/JavaBridge/java/Java.inc");
        if (!file_exists($filename)) {
            var_dump("文件不存在");
            return;
        }
        $data = self::parseMSPJ($filename);
        $rowCount = count($data);

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $mpp_str = json_encode($data);
        $redis->set('mpp_str', $mpp_str);
        $redis->close();

        $rs = array('filename'=>$filename, 'rowcnt'=>$rowCount);
        print_r(json_encode($rs));

    }

    //读取文件
    public function actionReaddata(){

        $filename = $_REQUEST['filename'];
        $startrow = $_REQUEST['startrow'];
        $version_id = $_REQUEST['id'];
        $per_read_cnt = $_REQUEST['per_read_cnt'];
        $project_id = $_REQUEST['project_id'];

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);

        $mpp_str = $redis->get('mpp_str');
        $mpp_data = json_decode($mpp_str,true);
        $cnt = count($mpp_data);
        $mpp_slice_data = array_slice($mpp_data,$startrow,$per_read_cnt);
        $rs = array();
        $rowIndex=$startrow;
        foreach($mpp_slice_data as $i => $j){
            if($rowIndex > $cnt){
                $sql = "DELETE FROM progress_plan WHERE project_id=:project_id and tag ='0' ";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":project_id", $project_id, PDO::PARAM_INT);
                $command->execute();

                $sql = "UPDATE progress_plan SET tag = '0' WHERE project_id=:project_id and tag ='1' ";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":project_id", $project_id, PDO::PARAM_INT);
                $command->execute();
            }else{
                $rs[$rowIndex] = ProgressPlan::insertForm($j,$version_id,$project_id);
                $rowIndex++;
            }
        }

        end:
        print_r(json_encode($rs));
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
