<?php

/**
 * 风险评估
 * @author LiuMinchao
 */
class RaBasic extends CActiveRecord {

    const STATUS_AUDITING = '0'; //审批中
    const SC_AUDITING = '1'; //分包审批完成(审批中)
    const SC_REJECT = '2'; //分包审批拒绝
    const STATUS_FINISH = '3'; //总包审批完成
    const STATUS_REJECT = '4'; //总包审批不通过

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'ra_swp_basic';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //报告展示状态
    public static function pdfStatus(){

        $status = array("0" => array("0" => "Submitted (已提交)",
        ),
            "1" => array("0" => "Wait (待处理)",
                "1" => "Approved (Sub-con) (分包批准完成)",
                "2" => "Revoked (Sub-con) (分包批准拒绝)",

            ),
            "2" => array("0" => "Wait (待处理)",
                "1" => "Verified (Main Con) (总包审核完成)",
                "2" => "Revoked (Main Con) (总包审核拒绝)",
            ),

        );
        return $status;
    }
    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_AUDITING => Yii::t('comp_ra', 'STATUS_AUDITING'),
            self::SC_AUDITING => Yii::t('comp_ra', 'STATUS_SC_FINISH'),
            self::SC_REJECT => Yii::t('comp_ra', 'STATUS_SC_REJECT'),
            self::STATUS_FINISH => Yii::t('comp_ra', 'STATUS_MC_FINISH'),
            self::STATUS_REJECT => Yii::t('comp_ra', 'STATUS_MC_REJECT'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_AUDITING => 'label-info', //审批中
            self::SC_AUDITING => 'label-info', //审批中
            self::SC_REJECT => 'label-danger', //审批不通过
            self::STATUS_FINISH => 'label-success', //审批完成
            self::STATUS_REJECT => 'label-danger', //审批不通过
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();

        //Ra
        if ($args['ra_swp_id'] != '') {
            $condition.= ( $condition == '') ? ' ra_swp_id=:ra_swp_id' : ' AND ra_swp_id=:ra_swp_id';
            $params['ra_swp_id'] = $args['ra_swp_id'];
        }
        //Add User
        if ($args['add_user'] != '') {
            $condition.= ( $condition == '') ? ' add_user=:add_user' : ' AND add_user=:add_user';
            $params['add_user'] = $args['add_user'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
//        if ($args['program_id'] != '') {
//            $condition .= ' program_id IN ('.$args['program_id'].')';
//        }

        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->root_proid != $args['program_id']){
                $condition.= ( $condition == '') ? ' program_id =:program_id ' : ' AND program_id =:program_id';
                $params['program_id'] = $args['program_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' main_proid =:program_id' : ' AND main_proid =:program_id';
                $params['program_id'] = $args['program_id'];
            }
//            var_dump(123);
        }else{
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $program_list = Program::McProgramList($args);
            $key_list = array_keys($program_list);
            $program_id = $key_list[0];
            $pro_model =Program::model()->findByPk($program_id);
            //分包项目
            if($pro_model->root_proid != $args['program_id']){
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $params['program_id'] = $program_id;
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' main_proid =:program_id' : ' AND main_proid =:program_id';
                $params['program_id'] = $program_id;
            }
        }

        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' work_type=:type_id' : ' AND work_type=:type_id';
            $params['type_id'] = $args['type_id'];
        }
        //Record Time
//        if ($args['record_time'] != '') {
//            $args['record_time'] = Utils::DateToCn($args['record_time']);
//            $condition.= ( $condition == '') ? ' record_time LIKE :record_time' : ' AND record_time LIKE :record_time';
//            $params['record_time'] = '%'.$args['record_time'].'%';
//        }
        //操作开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' record_time >=:start_date' : ' AND record_time >=:start_date';
            $params['start_date'] = Utils::DateToCn($args['start_date']);
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' record_time <=:end_date' : ' AND record_time <=:end_date';
            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        }

        if ($args['con_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['con_id'];
        }
//        var_dump($condition);
//        var_dump($params);
        $total_num = RaBasic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = RaBasic::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function exportList($args = array()) {

        $condition = '';
        $params = array();

        //Ra
        if ($args['ra_swp_id'] != '') {
            $condition.= ( $condition == '') ? ' ra_swp_id=:ra_swp_id' : ' AND ra_swp_id=:ra_swp_id';
            $params['ra_swp_id'] = $args['ra_swp_id'];
        }
        //Add User
        if ($args['add_user'] != '') {
            $condition.= ( $condition == '') ? ' add_user=:add_user' : ' AND add_user=:add_user';
            $params['add_user'] = $args['add_user'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
//        if ($args['program_id'] != '') {
//            $condition .= ' program_id IN ('.$args['program_id'].')';
//        }

        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' main_proid =:program_id' : ' AND main_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $params['program_id'] = $args['program_id'];
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' main_proid =:program_id' : ' AND main_proid =:program_id';
                $params['program_id'] = $args['program_id'];
            }
        }else{
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $program_list = Program::McProgramList($args);
            $key_list = array_keys($program_list);
            $program_id = $key_list[0];
            $pro_model =Program::model()->findByPk($program_id);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' main_proid =:program_id' : ' AND main_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $params['program_id'] = $program_id;
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' main_proid =:program_id' : ' AND main_proid =:program_id';
                $params['program_id'] = $program_id;
            }
        }

        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' work_type=:type_id' : ' AND work_type=:type_id';
            $params['type_id'] = $args['type_id'];
        }
        //Record Time
//        if ($args['record_time'] != '') {
//            $args['record_time'] = Utils::DateToCn($args['record_time']);
//            $condition.= ( $condition == '') ? ' record_time LIKE :record_time' : ' AND record_time LIKE :record_time';
//            $params['record_time'] = '%'.$args['record_time'].'%';
//        }
        //操作开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' record_time >=:start_date' : ' AND record_time >=:start_date';
            $params['start_date'] = Utils::DateToCn($args['start_date']);
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' record_time <=:end_date' : ' AND record_time <=:end_date';
            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        }

        if ($args['con_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['con_id'];
        }
//        var_dump($condition);
//        var_dump($params);
        $total_num = RaBasic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $rows = RaBasic::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['total_num'] = $total_num;
        $rs['rows'] = $rows;

        return $rs;
    }

    public static function updatePath($apply_id,$save_path) {
        $save_path = substr($save_path,18);
//        var_dump($save_path);
//        exit;
        $model = RaBasic::model()->findByPk($apply_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }
    /**
     * 添加记录
     */
    public  static function insertBasic($ra,$ra_user,$ra_leader,$ra_approver) {
//        var_dump($ra['type_add']);
//        exit;
        $ra_swp_id = date('YmdHis').rand(000001,999999);
        $program_list = Program::model()->findByPk($ra['programid']);
//        var_dump(Yii::app()->user->getState('name'));
//        exit;
        $model = new RaBasic('create');
        if(count($ra_user) <= 0 ){
            $r['status'] = -1;
            $r['msg'] = Yii::t('comp_ra','ra_error');
            $r['refresh'] = false;
            return $r;
        }
//        if($ra['worker_type'] == ''|| $ra['type_add'] == ''){
//            $r['status'] = -1;
//            $r['msg'] = Yii::t('comp_ra','worker_type_error');
//            $r['refresh'] = false;
//            return $r;
//        }
        if($ra['type_add'] != ''){
            $type_model = new WorkerType('create');
            $type_model->type_name = $ra['type_add'];
            $type_model->type_name_en = $ra['type_add'];
            $type_model->tag = '1';
            $type_model->save();
            $ra['worker_type'] = $type_model->type_id;
        }
        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->ra_swp_id = $ra_swp_id;
            $model->program_id = $ra['programid'];
            $model->program_name = $program_list['program_name'];
            $model->main_proid = $program_list['root_proid'];
            $model->work_type = $ra['worker_type'];
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
            $model->add_user_id = Yii::app()->user->id;
            $model->status = self::STATUS_AUDITING;
            $model->record_time = date('Y-m-d H:i:s', time());
            $model->valid_time = $ra['valid_time'];
            $model->ra_path = $ra['ra_path'];
            $model->swp_path = $ra['swp_path'];
            $model->title = $ra['title'];
//            $model->tag = $ra['tag'];
            $result = $model->save();
            $trans->commit();
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        if($result){
            $r1 = RaWorker::insertMembers($ra_swp_id,$ra_user,$ra_leader,$ra_approver);
            if($r1['status'] == 1) {
                $r2 = CheckApply::insertRaApply($ra_swp_id);
//                var_dump($r2);
//                exit;
            }
            if($r2['status'] == 1){
                $r = CheckApplyDetail::insertRaApplyDetail($ra_swp_id);
//                var_dump($r);
//                exit;
            }

        }
        return $r;
    }
    /**
     * 修改记录
     */
    public  static function updateBasic($ra,$ra_user) {
        $model = RaBasic::model()->findByPk($ra['ra_swp_id']);
//        $version = $model['version'];
        if(count($ra_user) <= 0 ){
            $r['status'] = -1;
            $r['msg'] = Yii::t('comp_ra','ra_error');
            $r['refresh'] = false;
            return $r;
        }
//        if($ra['worker_type'] == ''|| $ra['type_add'] == ''){
//            $r['status'] = -1;
//            $r['msg'] = Yii::t('comp_ra','worker_type_error');
//            $r['refresh'] = false;
//            return $r;
//        }
        if($ra['type_add'] != ''){
            $type_model = new WorkerType('create');
            $type_model->type_name = $ra['type_add'];
            $type_model->type_name_en = $ra['type_add'];
            $type_model->tag = '1';
            $type_model->save();
            $ra['worker_type'] = $type_model->type_id;
        }
        $program_list = Program::model()->findByPk($ra['programid']);
//        var_dump(Yii::app()->user->getState('name'));
//        exit;
        $trans = $model->dbConnection->beginTransaction();
        try {
//            $model->program_id = $ra['programid'];
//            $model->program_name = $program_list['program_name'];
//            $model->main_proid = $program_list['root_proid'];
            $model->work_type = $ra['worker_type'];
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
            $model->add_user_id = Yii::app()->user->id;
            $model->status = self::STATUS_AUDITING;
            $model->record_time = date('Y-m-d H:i:s', time());
            $model->ra_path = $ra['ra_path'];
            $model->swp_path = $ra['swp_path'];
            $model->valid_time = $ra['valid_time'];
            $model->title = $ra['title'];
//            $model->tag = $ra['tag'];
            $result = $model->save();
            $trans->commit();
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        if($result){
            $r1 = RaWorker::updateMembers($ra['ra_swp_id'],$ra_user);
            if($r1['status'] == 1) {
                $r2 = CheckApplyDetail::updateRaApplyDetail($ra['ra_swp_id']);
//                var_dump($r2);
//                exit;
            }
            if($r2['status'] == 1){
                $r = CheckApply::updateRaApply($ra['ra_swp_id']);
//                var_dump($r);
//                exit;
            }

        }
        return $r;
    }

    public static function deleteFile($src){
        $src = '/opt/www-nginx/web'.$src;
        if (!unlink($src))
        {
            $r['msg'] = "Error deleting $src";
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        else
        {
            $r['msg'] = "Deleted $src";
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }
    public static function moveFile($file_src,$tag){
//        $name = substr($file_src,35);
        $record_time = date('Y-m-d H:i:s', time());
        $year = substr($record_time, 0, 4);//年
        $month = substr($record_time, 5, 2);//月
        $name = substr($file_src,38);
        $conid = Yii::app()->user->getState('contractor_id');
        $time = substr(time(),-4);
        $rand = rand(111,999);
        if($tag == 'ra'){
            $upload_path = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ra/'.$conid;
            $upload_file = $upload_path.'/'.$time.$rand.'_'.$name;
        }else{
            $upload_path = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/swp/'.$conid;
            $upload_file = $upload_path.'/'.$time.$rand.'_'.$name;
        }
//            var_dump($name);exit;
        //创建目录
        if($upload_path == ''){
            return false;
        }
        if(!file_exists($upload_path))
        {
            umask(0000);
            @mkdir($upload_path, 0777, true);
        }
        //移动文件到指定目录下
        if (rename($file_src,$upload_file)) {
            $r['src'] = substr($upload_file,18);
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = "Error moving";
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    /** 工种列表
     * @return type
     */
    public static function getType() {

        $sql = "select * from bac_worker_type where tag =2";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']]['type_name'] = $row['type_name'];
                    $rs[$row['type_id']]['type_name_en'] = $row['type_name_en'];
                }
            }

        return $rs;
    }
    /*
     * 下载PDF
     */
    public static function downloadPdf($params,$app_id) {
        $ra_swp_id = $params['ra_swp_id'];
        $ra_list = RaBasic::model()->findByPk($ra_swp_id);//详细信息
//        var_dump($ra_list);
//        exit;
        $company_list = Contractor::compAllList();//承包商公司列表
        $roleList = Role::rolebrList();//岗位列表
//        var_dump(11111);
//        exit;

        $program_id = $ra_list['main_proid'];
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                $contractor_id = $pro_params['transfer_con'];
            } else {
                $contractor_id = $ra_list['contractor_id'];
            }
        }else{
            $contractor_id = $ra_list['contractor_id'];
        }
        $user_list = ProgramUser::UserListByRa($ra_list['program_id'], $contractor_id);//RA成员信息
        $lang = "_en";

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($ra_list->record_time, 0, 4);//年
        $month = substr($ra_list->record_time, 5, 2);//月
        $day = substr($ra_list->record_time,8,2);//日
        $hours = substr($ra_list->record_time,11,2);//小时
        $minute = substr($ra_list->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        if ($ra_list['save_path']) {
            $filepath = '/opt/www-nginx/web' . $ra_list['save_path'];
        } else {
            $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ra/'.$contractor_id.'/RA_SWP' . $ra_swp_id . $time .'.pdf';
//            $filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ra' . '/ra_swp' . $ra_swp_id . '.pdf';
            RaBasic::updatepath($ra_swp_id, $filepath);
        }
//        $full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ra';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ra/'.$contractor_id;
        if($full_dir == ''){
            return false;
        }
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $title = 'RA_SWP';

//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $main_model->contractor_name;

        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $_SESSION['title'] = 'RA/SWP No.(风险评估编号): ' . $ra_swp_id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 23, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 8, '', true); //英文

        $pdf->AddPage();

        $contractor_name = $company_list[$contractor_id];//承包商名称
        $worker_type = RaBasic::getType();//工种列表
        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300% \" align=\"center\">{$contractor_name}</h1><br/><h2 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$ra_list['program_name']}</h2>
            <h2 style=\"font-size: 200%\" align=\"center\">Title (标题) : {$ra_list['title']}</h2><br/>";
        //工作内容
        $record_time = substr(Utils::DateToEn($ra_list['record_time']),0,11);
        $work_content_html = "<br/><br/><h2 align=\"center\">Nature of RA/SWP (风险评估/工作安全程序详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Submitted By (提交者)</td><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Type of RA (风险评估类型)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$company_list[$contractor_id]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$worker_type[$ra_list['work_type']]['type_name']}<br>{$worker_type[$ra_list['work_type']]['type_name_en']}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Submission Date (提交日期)</td><td  width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Next Review Date (下次审核日期)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$record_time}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$ra_list['valid_time']}</td></tr></table>";

        $worker_html = '<br/><br/><h2 align="center">RA/SWP Personnel (风险评估/工作安全程序人员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Team (组)</td><td  width="65%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Personnel (人员)</td></tr>';
//        var_dump($user_list);
//        exit;
//        foreach ($user_list as $cnt => $r) {
//            if ($cnt == 3) {
//                $member_cnt = count($r);
//                foreach ($r as $i => $user) {
//                    var_dump($member_cnt);
//                    exit;
//                }
//            }
//        }

        if (!empty($user_list)) {
            foreach ($user_list as $cnt => $r) {
                if ($cnt == 2) {
                    $leader_cnt = count($r);
                    foreach ($r as $i => $leader) {
                        if($i == 0){
                            $ra_leader = $leader['user_name'] . '(' . $roleList[$leader['role_id']] . ')';
                            $worker_html .= '<tr><td style="border-width: 1px;border-color:gray gray gray gray" rowspan="'.$leader_cnt.'">RA Team Leader(s)(风险评估组领导人)</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $ra_leader . '</td></tr>';
                        }else{
                            $ra_leader = $leader['user_name'] . '(' . $roleList[$leader['role_id']] . ')';
                            $worker_html .= '<tr><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $ra_leader . '</td></tr>';
                        }
                    }
                }
            }
            foreach ($user_list as $cnt => $r) {
                if ($cnt == 3) {
                    $member_cnt = count($r);
                    foreach ($r as $i => $user) {
                        if($i == 0){
                            $ra_user = $user['user_name'] . '(' . $roleList[$user['role_id']] . ')';
                            $worker_html .= '<tr><td style="border-width: 1px;border-color:gray gray gray gray" rowspan="'.$member_cnt.'">RA Team Member(s) (风险评估组成员)</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $ra_user . '</td></tr>';
                        }else{
                            $ra_user = $user['user_name'] . '(' . $roleList[$user['role_id']] . ')';
                            $worker_html .= '<tr><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $ra_user . '</td></tr>';
                        }
                    }
                }
            }
            foreach ($user_list as $cnt => $r) {
                if ($cnt == 1) {
                    $approver_cnt = count($r);
                    foreach ($r as $i => $approver) {
                        if($i == 0){
                            $ra_approver = $approver['user_name'] . '(' . $roleList[$approver['role_id']] . ')';
                            $worker_html .= '<tr><td style="border-width: 1px;border-color:gray gray gray gray" rowspan="'.$approver_cnt.'">RA Team Approver(s) (风险评估组批准人)</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $ra_approver . '</td></tr>';
                        }else{
                            $ra_approver = $approver['user_name'] . '(' . $roleList[$approver['role_id']] . ')';
                            $worker_html .= '<tr><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $ra_approver . '</td></tr>';
                        }
                    }
                }
            }
        }
        $worker_html .= '</table>';
        $progress_list = CheckApplyDetail::progressList($app_id, $ra_swp_id);//TBM审批结果(快照)
        $user_list = Staff::userInfo();//员工信息
        $audit_html = '<br/><br/><h2 align="center">Review Process (审核步骤)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(编号)</td><td  width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person In Charge<br>(执行人)</td><td  width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">Electronic Signature<br>(电子签名)</td></tr>';

//        var_dump($progress_list);
//        exit;
        $progress_result = CheckApplyDetail::resultTxt();
        $status = self::pdfStatus();
        $info_xx = 156;//X方向距离
        $info_yy = 156;//Y方向距离
        $j = 1;
        $operator_list = Operator::companyByOperator();
        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {
                if($row['user_name'] ==''){
                    $row['user_name'] = $operator_list[$row['deal_user_id']];
                }
                $audit_html .= '<tr><td height="75px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $j . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status[$row['deal_type']][$row['status']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.Utils::DateToEn($row['deal_time']).'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"></td>';
//                $pic = 'img/avatar04.png';
//                $content = $user_list[$row['deal_user_id']]['signature_path'];
                $content_list = $user_list[$row['deal_user_id']];
                $content = $content_list[0]['signature_path'];
//                    $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
                if($content != '') {
                    $pdf->Image($content, $info_xx, $info_yy, 32, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                }
                $info_yy += 22;

                $audit_html .= '</tr>';
                $j++;
            }
        $audit_html .= '</table>';

        $ra_file = $ra_list['ra_path'];
        $swp_file = $ra_list['swp_path'];
        //文档标签
        $document_html = '<br/><br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N (序号)</td><td  width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name (文档名称)</td></tr>';
        $i = 1;
        if($ra_file){
            $ra_file_list = explode('|',$ra_file);
            foreach($ra_file_list as $cnt => $name){
                $document_html .='<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . substr($name,22) . '</td></tr>';
                $i++;
            }
        }
        if($swp_file){
            $swp_file_list = explode('|',$swp_file);
            foreach($swp_file_list as $cnt => $name){
                $document_html .='<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . substr($name,23) . '</td></tr>';
                $i++;
            }
        }
        $document_html .= '</table>';

//        $html = $apply_html . $worker_html . $audit_html;
        $html = $title_html . $work_content_html . $audit_html  .$worker_html .$document_html;

        $pdf->writeHTML($html, true, false, true, false, '');

        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }


    //删除申请记录
    public static function deleteApply($ra_swp_id){
        $sql = "DELETE FROM ra_swp_basic WHERE ra_swp_id =:ra_swp_id ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":ra_swp_id", $ra_swp_id, PDO::PARAM_STR);

        $rs = $command->execute();

        if($rs ==2 || $rs == 1){
            $r['msg'] = Yii::t('common', 'success_delete');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //按项目查询安全检查次数（按类别分组）
    public static function AllCntList($args){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $type_list = WorkerType::getAllType();
//        var_dump($args['date']);
//        var_dump($month);
        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(ra_swp_id) as cnt,main_proid,work_type from ra_swp_basic  where  main_proid = '".$root_proid."' and record_time like '".$month."%' and contractor_id = '".$args['contractor_id']."'  GROUP BY work_type";
        }else{
            //总包项目
            $sql = "select count(ra_swp_id) as cnt,main_proid,work_type from ra_swp_basic  where  record_time like '".$month."%' and main_proid ='".$args['program_id']."'  GROUP BY work_type";
        }
        if(!$args['contractor_id']){
            //总包项目
            $sql = "select count(ra_swp_id) as cnt,main_proid,work_type from ra_swp_basic  where  record_time like '".$month."%' and main_proid ='".$args['program_id']."'  GROUP BY work_type";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $type_list[$list['work_type']];

            }
        }
        return $r;
//        var_dump($r);
//        exit;
    }

    //按项目查询安全检查次数（按公司分组）
    public static function CompanyCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $contractor_list = Contractor::compAllList();
//        var_dump($args['date']);
//        var_dump($month);

        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(ra_swp_id) as cnt,contractor_id from ra_swp_basic  where  record_time like '".$month."%' and main_proid ='".$root_proid."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
        }else{
            //总包项目
            $sql = "select count(ra_swp_id) as cnt,contractor_id from ra_swp_basic  where  record_time like '".$month."%' and main_proid ='".$args['program_id']."'   GROUP BY contractor_id";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;

        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['contractor_id']];

            }
        }
        return $r;
//        var_dump($r);
//        exit;
    }
}
