<?php

/**
 * 培训
 * @author LiuMinchao
 */
class Train extends CActiveRecord {

    const STATUS_AUDITING = '0'; //审批中
    const STATUS_FINISH = '1'; //审批完成
    const STATUS_REJECT = '2'; //审批不通过

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'train_apply_basic';
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

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_AUDITING => Yii::t('license_licensepdf', 'STATUS_AUDITING'),
            self::STATUS_FINISH => Yii::t('license_licensepdf', 'STATUS_FINISH'),
            self::STATUS_REJECT => Yii::t('license_licensepdf', 'STATUS_REJECT'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_AUDITING => 'label-info', //审批中
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
//        var_dump($args);
        //Meeting
        if ($args['training_id'] != '') {
            $condition.= ( $condition == '') ? ' training_id=:training_id' : ' AND training_id=:training_id';
            $params['training_id'] = $args['training_id'];
        }
        //培训标题
        if ($args['title'] != '') {
            $condition.= ( $condition == '') ? ' title LIKE :title' : ' AND title LIKE :title';
            $params['title'] = '%'.$args['title'].'%';
        }
        //地点
        if ($args['location'] != '') {
            $condition.= ( $condition == '') ? ' location=:location' : ' AND location=:location';
            $params['location'] = $args['location'];
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
        //Type
        if ($args['module_type'] !='') {
            $condition.= ( $condition == '') ? ' module_type=:module_type' : ' AND module_type=:module_type';
            $params['module_type'] = $args['module_type'];
        }
        //Program
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['con_id']){
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $root_proid = $pro_model->root_proid;
                $params['program_id'] = $root_proid;
//                $params['program_id'] = $args['program_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $params['program_id'] = $args['program_id'];
            }
        }else{
            $args['program_id'] = Program::getProgramId();
            if ($args['program_id'] != '') {
                $condition .= ($condition == '') ? ' program_id IN (' . $args['program_id'] . ')' : ' AND program_id IN (' . $args['program_id'] . ')';
            }else{
                $condition .= ( $condition == '') ?' program_id = :program_id' : ' AND program_id =:program_id ';
                $params['program_id'] = $args['program_id'];
            }
        }

        //Contractor
        if ($args['con_id'] != ''){
            //我提交+我审批＝我参与
            $condition.= ( $condition == '') ? ' add_conid =:add_conid ' : ' AND add_conid =:add_conid ';
            $params['add_conid'] = $args['con_id'];
        }

        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' type_id=:type_id' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }

        if($args['user_id'] != ''){
            if($args['deal_type'] != -1) {
                $sql = "SELECT b.training_id FROM bac_check_apply_detail a,train_apply_basic b WHERE a.deal_user_id = '".$args['user_id']."' and a.app_id = 'TRAIN' and a.apply_id = b.training_id and b.program_id = '".$args['program_id']."' and b.module_type='".$args['module_type']."'and b.type_id ='".$args['type_id']."' and a.deal_type = '" . $args['deal_type'] . "'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['training_id'] .= $row['training_id'] . ',';
                    }
                }
                if ($args['training_id'] != '')
                    $args['training_id'] = substr($args['training_id'], 0, strlen($args['training_id']) - 1);
                $condition .= ($condition == '') ? ' training_id IN (' . $args['training_id'] . ')' : ' AND training_id IN (' . $args['training_id'] . ')';
            }else{
                $sql = "SELECT b.training_id FROM train_apply_worker a,train_apply_basic b  where a.worker_id = '".$args['user_id']."' and a.training_id=b.training_id  and b.program_id='".$args['program_id']."'and b.module_type='".$args['module_type']."' and b.type_id ='".$args['type_id']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['training_id'] .= $row['training_id'] . ',';
                    }
                }
                if ($args['training_id'] != '')
                    $args['training_id'] = substr($args['training_id'], 0, strlen($args['training_id']) - 1);
                $condition .= ($condition == '') ? ' training_id IN (' . $args['training_id'] . ')' : ' AND training_id IN (' . $args['training_id'] . ')';
            }
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

//        if ($args['contractor_id'] != ''){
//            $condition.= ( $condition == '') ? ' (add_conid =:contractor_id or main_conid = :contractor_id)' : ' AND (add_conid =:contractor_id or main_conid = :contractor_id)';
//            $params['contractor_id'] = $args['contractor_id'];
//        }

        $total_num = Train::model()->count($condition, $params); //总记录数

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
        $rows = Train::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    public static function updatePath($training_id,$save_path) {
        $save_path = substr($save_path,18);
//        var_dump($save_path);
//        exit;
        $model = Train::model()->findByPk($training_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }
    //人员按权限和成员进行统计
    public static function findBySummary($user_id,$program_id,$module_type){
        $sql = "SELECT a.type_id, b.deal_type, count(distinct b.apply_id) as cnt
                  FROM train_apply_basic a inner join bac_check_apply_detail b
                  on  a.training_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'TRAIN' and b.deal_user_id = '".$user_id."' and a.module_type='".$module_type."'
                  group by a.type_id
                UNION
                SELECT c.type_id, 'MEMBER' as deal_type, count(distinct c.training_id) as cnt
                  FROM train_apply_basic c inner join train_apply_worker d
                  on c.training_id=d.training_id where c.program_id = '".$program_id."' and d.worker_id = '".$user_id."' and c.module_type='".$module_type."'
                  group by c.type_id";
        $ptw_type = ApplyBasic::typeList();//许可证类型表(双语)
        $status_css = CheckApplyDetail::statusText();//PTW执行类型
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['type_id'] = $row['type_id'];
                $rs[$key]['type_name'] = $ptw_type[$row['type_id']];
                if($row['deal_type'] == 'MEMBER'){
                    $rs[$key]['deal_type'] = $row['deal_type'];
                }else{
                    $rs[$key]['deal_type'] = $status_css[$row['deal_type']];
                }
                $rs[$key]['cnt'] = $row['cnt'];
            }
        }
        return $rs;
    }
    //统计总次数
    public static function cntBySummary($user_id,$program_id,$module_type){
        $sql = "select count(DISTINCT aa.apply_id) as cnt
                  from (  SELECT a.type_id, b.deal_type, b.apply_id
                              FROM train_apply_basic a inner join bac_check_apply_detail b
                              on  a.training_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'TRAIN'  and b.deal_user_id = '".$user_id."' and a.module_type='".$module_type."'
                           UNION
                           SELECT c.type_id, 'MEMBER' as deal_type, c.training_id
                              FROM train_apply_basic c inner join train_apply_worker d
                              on c.training_id=d.training_id where c.program_id = '".$program_id."' and d.worker_id = '".$user_id."' and c.module_type='".$module_type."'
                  )aa";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按权限统计使用次数(培训)
    public static function trainBySet($user_id,$program_id){
        $sql = "SELECT count(DISTINCT a.apply_id) as cnt,a.deal_type,b.type_id,b.type_name,b.type_name_en FROM bac_check_apply_detail a,train_apply_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'TRAIN' and a.apply_id = b.training_id and b.program_id = '".$program_id."' and b.module_type='1' group by b.type_id ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按成员统计使用次数(培训)
    public static function trainByMember($user_id,$program_id){
        $sql = "SELECT count(a.training_id) as cnt,b.type_id,b.type_name,b.type_name_en FROM train_apply_worker a,train_apply_basic b  where a.worker_id = '".$user_id."' and a.training_id=b.training_id  and b.program_id='".$program_id."'and b.module_type='1' group by b.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计使用总次数(培训)
    public static function trainByAll($user_id,$program_id){
        $sql = "select count(DISTINCT aa.training_id) as cnt from (SELECT b.training_id FROM bac_check_apply_detail a,train_apply_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'TRAIN' and a.apply_id = b.training_id and b.program_id = '".$program_id."'and b.module_type='1' UNION ALL SELECT c.training_id FROM train_apply_worker c,train_apply_basic d where c.worker_id = '".$user_id."' and c.training_id=d.training_id and d.program_id='".$program_id."'and d.module_type='1')aa ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    //人员按权限统计使用次数(培训)
    public static function meetingBySet($user_id,$program_id){
        $sql = "SELECT count(DISTINCT a.apply_id) as cnt,a.deal_type,b.type_id,b.type_name,b.type_name_en FROM bac_check_apply_detail a,train_apply_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'TRAIN' and a.apply_id = b.training_id and b.program_id = '".$program_id."' and b.module_type='2' group by b.type_id ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按成员统计使用次数(培训)
    public static function meetingByMember($user_id,$program_id){
        $sql = "SELECT count(a.training_id) as cnt,b.type_id,b.type_name,b.type_name_en FROM train_apply_worker a,train_apply_basic b  where a.worker_id = '".$user_id."' and a.training_id=b.training_id  and b.program_id='".$program_id."'and b.module_type='2' group by b.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计使用总次数(培训)
    public static function meetingByAll($user_id,$program_id){
        $sql = "select count(DISTINCT aa.training_id) as cnt from (SELECT b.training_id FROM bac_check_apply_detail a,train_apply_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'TRAIN' and a.apply_id = b.training_id and b.program_id = '".$program_id."'and b.module_type='2' UNION ALL SELECT c.training_id FROM train_apply_worker c,train_apply_basic d where c.worker_id = '".$user_id."' and c.training_id=d.training_id and d.program_id='".$program_id."'and d.module_type='2')aa ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    //下载PDF
    public static function downloadPDF($params,$app_id){
        $type = $params['type'];
        if($type == 'A'){
            $filepath = self::downloaddefaultPDF($params,$app_id);
        }else if($type == 'B'){
            $filepath = self::downloadShsdPDF($params,$app_id);
        }
        return $filepath;
    }

    //下载PDF
    public static function downloaddefaultPDF($params,$app_id){
        $id = $params['id'];
        $meeting = Train::model()->findByPk($id);
        $document_list = TrainDocument::queryDocument($id);//文档列表
        $worker_temp = TrainWorkerTemp::queryWorker($id);//临时人员列表
        $module_type = $meeting->module_type;//类型
        $company_list = Contractor::compAllList();//承包商公司列表
//        $program_list =  Program::programAllList();//获取承包商所有项目
        $program_id = $meeting->program_id;
        $lang = "_en";

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($meeting->record_time,0,4);//年
        $month = substr($meeting->record_time,5,2);//月
        $day = substr($meeting->record_time,8,2);//日
        $hours = substr($meeting->record_time,11,2);//小时
        $minute = substr($meeting->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        if($meeting->save_path){
            $file_path = $meeting->save_path;
            $filepath = '/opt/www-nginx/web'.$file_path;
        }else{
            if($module_type == 1) {
                $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/train/'.$meeting->add_conid.'/TRAIN' . $id . $time .'.pdf';
                $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/train/'.$meeting->add_conid;
//                $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/train' . '/TRAIN' . $id . '.pdf';
//                $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/train';
            }else{
                $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/meeting/'.$meeting->add_conid.'/MEETING' . $id . $time .'.pdf';
                $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/meeting/'.$meeting->add_conid;
//                $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/meeting' . '/MEETING' . $id . '.pdf';
//                $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/meeting';
            }
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
            Train::updatepath($id,$filepath);
        }

        $title = $meeting->title;

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
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
        if($module_type == 1) {
            $_SESSION['title'] = 'Training Records No. (培训记录编号): ' . $meeting->training_id;
        }else{
            $_SESSION['title'] = 'Meeting Records No. (会议记录编号): ' . $meeting->training_id;
        }

        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                $main_conid = $pro_params['transfer_con'];
            } else {
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $main_conid = $pro_model->contractor_id;//总包编号
        }
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $pdf->Header($logo_pic);
//        if($module_type == 1) {
//            $pdf->SetHeaderData($logo, $logo_size, 'Training Records No. (培训记录编号): ' . $meeting->training_id, $main_conid_name, array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData($logo, $logo_size, 'Meeting Records No. (会议记录编号): ' . $meeting->training_id, $main_conid_name, array(0, 64, 255), array(0, 64, 128));
//        }
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);

        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

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
//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文OR中文
//        }

        $pdf->AddPage();

        $members = TrainWorker::getMembersName($meeting->training_id);

//        $start_date = date('Y-m-d',strtotime($meeting->from_date));
//        $end_date = date('Y-m-d',strtotime($meeting->end_date));
        $from_date = Utils::DateToEn(substr($meeting->start_time,0,10));//起始日期
        $end_date = Utils::DateToEn(substr($meeting->end_time,0,10));//截止日期
        $from_time = substr($meeting->start_time,11,19);//起始时间
        $end_time = substr($meeting->end_time,11,19);//起始时间

        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300%;\" align=\"center\">{$main_conid_name}</h1><h2 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$pro_model->program_name}</h2><br/>";

        $apply_user =  Staff::model()->findAllByPk($meeting->add_user);//申请人
        $apply_content = $apply_user[0]['signature_path'];
        $apply_sign_img = '';
        if ($apply_content != '' && $apply_content != 'nil' && $apply_content != '-1') {
            if(file_exists($apply_content)){
                $apply_sign_img= '<img src="'.$apply_content.'" height="30" width="60" />';
            }
        }
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        $contractor_id = $apply_user[0]['contractor_id'];//发起人公司
//        $user_list = Staff::allInfo();//员工信息（包括已被删除的）
        $status_css = CheckApplyDetail::statusTxt();//执行类型
        //发起人详情
        $record_time = Utils::DateToEn($meeting->record_time);
        $apply_info_html = "<br/><br/><h2 align=\"center\">Conducting Personnel Details (举办人员详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
        if($apply_user[0]['work_pass_type'] = 'IC' || $apply_user[0]['work_pass_type'] = 'PR'){
            if(substr($apply_user[0]['work_no'],0,1) == 'S' && strlen($apply_user[0]['work_no']) == 9){
                $work_no = 'SXXXX'.substr($apply_user[0]['work_no'],5,8);
            }else{
                $work_no = $apply_user[0]['work_no'];
            }
        }else{
            $work_no = $apply_user[0]['work_no'];
        }
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$apply_user[0]['user_name']}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$apply_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$work_no}</td><td  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$contractor_id]}</td></tr>";
        $apply_info_html .="<tr><td colspan='2' height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Created Date (创建日期)</td><td colspan='2' height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td colspan='2' height=\"50px\" width=\"50%\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$record_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;$apply_sign_img</td></tr>";
        $apply_info_html .="</table>";

        $apply_y = $pdf->GetY();
        $apply_y = $apply_y +78;
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        if($apply_user->signature_path){
            $pdf->Image($apply_user->signature_path, 150, $apply_y, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }

        //培训详情&&会议详情
        $type_name = $meeting->type_name;
        $type_name=str_replace('"', '', $type_name);
        if($module_type == 1) {
            $work_content_html = "<br/><h2 align=\"center\">Training Details (培训详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Training Type (培训类型)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Training Venue (培训地点)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->type_name_en}<br>({$type_name})</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->location}</td></tr>";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$from_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$end_time}</td></tr>";
        }else{
            $work_content_html = "<br/><br/><h2 align=\"center\">Meeting Details (会议详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Meeting Type (会议类型)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Meeting Venue (会议地点)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->type_name_en}<br>({$type_name})</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->location}</td></tr>";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$from_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$end_time}</td></tr>";
        }
        $work_content_html .="</table>";
        //培训日期
        if($module_type == 1) {
            $work_date_html = "<br><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
            $work_date_html .="<tr><td height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->title}</td></tr>";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Content (内容)</td></tr>";
            $work_date_html .="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray; \">{$meeting->content}</td></tr>";
        }else{
            $work_date_html = "<br><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
            $work_date_html .="<tr><td height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->title}</td></tr>";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Content (内容)</td></tr>";
            $work_date_html .="<tr><td  style=\"border-width: 1px;border-color:gray gray gray gray; \">{$meeting->content}</td></tr>";
        }
        $work_date_html .="</table>";

        $html = $title_html . $apply_info_html . $work_content_html . $work_date_html;

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->AddPage();
        $progress_list = CheckApplyDetail::progressList( $app_id,$meeting->training_id);//审批步骤详情

        if($module_type == 1) {
            $pic_title_html = '<h2 align="center">Training Photo(s) (培训照片)</h2>';
        }else{
            $pic_title_html = '<h2 align="center">Meeting Photo(s) (会议照片)</h2>';
        }
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        $y2 = $pdf->GetY();

        //判断每一页图片边框的高度
        $total_height = array();
        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    // var_dump($pic);
                    // exit;
                    if($y2 > 266){
                        $y2 = 30;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $title_height =48+3;
                    $cnt = 0;
                    foreach ($pic as $key => $content) {
                        $content = $pic[0];
                        if($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)) {
                                $ratio_width = 55;
                                //超过固定宽度换行
                                if($toatl_width > 190){
                                    if($info_y < 220){
                                        $toatl_width = 0;
                                        $info_x = 15+3;
                                        $info_y+=45+3;
                                        $title_height+=45+3;
//                                        var_dump($info_y);
                                    }
//                                    var_dump(111111);
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220){
//                                    var_dump(2222222);
                                    $total_height[$cnt] = $title_height-43;
//                                    var_dump(1111);
                                    $info_y = 10;
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                    $title_height = 45+10;
                                    $cnt++;
                                }else{
//                                    var_dump(333333);
                                    $total_height[$cnt] = $title_height;
                                }
                                //一行中按间距排列图片
                                $info_x += $ratio_width+3;
                                if($toatl_width == 0){
                                    $toatl_width = $ratio_width;
                                }
                                $toatl_width+=$ratio_width+3;
                            }
                        }
                    }
//                    var_dump($y2);
                }
            }
        }
//        var_dump($total_height);
//        exit;
        $table_count = count($total_height);
        $table_height = 3.5*$total_height[0];
        $pdf->Ln(2);

        if($table_count>1){
            if($module_type == 1) {
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
            }else{
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
            }
            $pic_html .= '</table>';
        }else{
            if($module_type == 1) {
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            }else{
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            }
            $pic_html .= '</table>';
        }
        $y2= $pdf->GetY();
        $pdf->writeHTML($pic_html, true, false, true, false, '');

        if (!empty($progress_list)){
//            var_dump($progress_list);
//            exit;
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    if($y2 > 266){
                        $y2 = 23;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $j = 1;
                    foreach ($pic as $key => $content) {
//                        $content = $pic[0];
                        if($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)) {
                                $ratio_width = 55;
                                //超过固定宽度换行
                                if($toatl_width > 190){
                                    $toatl_width = 0;
                                    $info_x = 15+3;
                                    $info_y+=45+3;
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220 ){
                                    $j++;
                                    $pdf->AddPage();
                                    $pdf->setPrintHeader(false);
                                    $info_y = $pdf->GetY();
//                                    var_dump($info_y);
                                    $table_height = 3.5*$total_height[$j-1];
                                    if($table_count == $j){
                                        $pic_html = '<br/><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
                                        $pic_html .= '</table>';
                                    }else{
                                        $pic_html = '<br/><table style="border-width: 1px;border-color:lightslategray lightslategray white lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
                                        $pic_html .= '</table>';
                                    }
                                    $pdf->writeHTML($pic_html, true, false, true, false, '');
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                }
                                $pdf->Image($content, $info_x, $info_y, '55', '45', 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                //一行中按间距排列图片
                                $info_x += $ratio_width+3;
                                if($toatl_width == 0){
                                    $toatl_width = $ratio_width;
                                }
                                $toatl_width+=$ratio_width+3;
                            }
                        }
                    }
//                    var_dump($y2);
                }
            }
        }

        $pdf->AddPage();

        $progress_result = CheckApplyDetail::resultTxt();
        $j = 1;
        $y = 1;
        $info_xx = 166;
        $info_yy = $pdf->GetY();
        if (!empty($progress_list))
            $num = count($progress_list);
//        $pdf->AddPage();
        if($num < 10) {
            //审批流程
            //                $pic = 'C:\Users\minchao\Desktop\5.png';
            $audit_html = '<h2 align="center">Workflow (流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
            foreach ($progress_list as $key => $row) {
                $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                $content = $content_list[0]['signature_path'];
                $sign_img = '';
//                $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
                //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                if ($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)){
                        $sign_img= '<img src="'.$content.'" height="30" width="60" />';
//                        $pdf->Image($content, $info_xx, $info_yy+24, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['remark'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">'.$sign_img.'</td></tr>';
                $j++;
//                $info_yy += 15.5;
            }
            $audit_html .= '</table>';

            //文档标签
            $document_html = '<h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N (序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name (文档名称)</td></tr>';
            if(!empty($document_list)){
                $i =1;
                foreach($document_list as $cnt => $name){
                    $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                    $i++;
                }
            }else{
                $document_html .='<tr><td colspan="2" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
            }
            $document_html .= '</table>';

            //参与人员
            $worker_html = '<h2 align="center">Participant(s) (参与成员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Company<br>(公司)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职务)</td></tr>';

            if (!empty($members)){
                $i = 1;
                foreach ($members as $user_id => $r) {
                    $user_model =Staff::model()->findByPk($user_id);
                    if($user_model->work_pass_type = 'IC' || $user_model->work_pass_type = 'PR'){
                        if(substr($user_model->work_no,0,1) == 'S' && strlen($user_model->work_no) == 9){
                            $work_no = 'SXXXX'.substr($user_model->work_no,5,8);
                        }else{
                            $work_no = $r['wp_no'];
                        }
                    }else{
                        $work_no = $r['wp_no'];
                    }
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$company_list[$r['contractor_id']].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['worker_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$roleList[$r['role_id']].'</td></tr>';
                    $i++;
                }
            }
            if(!empty($worker_temp)){
                foreach ($worker_temp as $k => $u) {
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Temp</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['contractor_name'].'</td><td align="center">&nbsp;' . $u['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['role_name'].'</td></tr>';
                    $i++;
                }
            }
            $worker_html .= '</table>';

            $html_1 = $audit_html . $worker_html .$document_html;

            $pdf->writeHTML($html_1, true, false, true, false, '');
        }else{
            $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
            foreach ($progress_list as $key => $row) {
                if($row['step'] < 10) {
                    $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                    $content = $content_list[0]['signature_path'];
                    //$content = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                    if ($content != '' && $content != 'nil' && $content != '-1') {
                        $sign_img= '<img src="'.$content.'" height="30" width="60" />';
//                        $pdf->Image($content, $info_xx, $info_yy, 24, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['remark'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">'.$sign_img.'</td></tr>';
                    $j++;
                    $info_yy += 15.5;
                }
            }
            $audit_html .= '</table>';
            $html_1 = $audit_html;
            $pdf->writeHTML($html_1, true, false, true, false, '');
            $pdf->AddPage();
            $audit_html2 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            foreach ($progress_list as $key => $row) {
                if($row['step'] >= 10) {
                    $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                    $content = $content_list[0]['signature_path'];
                    //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                    if ($content != '' && $content != 'nil' && $content != '-1') {
                        $sign_img= '<img src="'.$content.'" height="30" width="60" />';
//                        $pdf->Image($content, $info_xx, 30, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    $audit_html2 .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['remark'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">'.$sign_img.'</td></tr>';
                    $j++;
                    $info_yy += 15.5;
                }
            }
            $audit_html2 .= "</table>";
            //文档标签
            $document_html = '<h2 align="center">Document(s) (标签)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name(文档名称)</td></tr>';
            if(!empty($document_list)){
                $i =1;
                foreach($document_list as $cnt => $name){
                    $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                    $i++;
                }
            }
            $document_html .= '</table>';
            //参与人员
            $worker_html = '<h2 align="center">Participants(s) (参与成员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Company<br>(公司)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职务)</td></tr>';

            if (!empty($members)){
                $i = 1;
                foreach ($members as $user_id => $r) {
                    $user_model =Staff::model()->findByPk($user_id);
                    if($user_model->work_pass_type = 'IC' || $user_model->work_pass_type = 'PR'){
                        if(substr($user_model->work_no,0,1) == 'S' && strlen($user_model->work_no) == 9){
                            $work_no = 'SXXXX'.substr($user_model->work_no,5,8);
                        }else{
                            $work_no = $r['wp_no'];
                        }
                    }else{
                        $work_no = $r['wp_no'];
                    }
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$company_list[$r['contractor_id']].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['worker_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$roleList[$r['role_id']].'</td></tr>';
                    $i++;
                }
            }
            if(!empty($worker_temp)){
                foreach ($worker_temp as $k => $u) {
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Temp</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['contractor_name'].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $u['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['role_name'].'</td></tr>';
                    $i++;
                }
            }
            $worker_html .= '</table>';

            $html2 = $audit_html2 . $worker_html .$document_html;
            $pdf->writeHTML($html2, true, false, true, false, '');
        }


        //输出PDF
//        $pdf->Output($filepath, 'I');
        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
    }


    //下载PDF
    public static function downloadShsdPDF($params,$app_id){
        $id = $params['id'];
        $meeting = Train::model()->findByPk($id);
        $document_list = TrainDocument::queryDocument($id);//文档列表
        $worker_temp = TrainWorkerTemp::queryWorker($id);//临时人员列表
        $module_type = $meeting->module_type;//类型
        $company_list = Contractor::compAllList();//承包商公司列表
//        $program_list =  Program::programAllList();//获取承包商所有项目
        $program_id = $meeting->program_id;
        $lang = "_en";

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($meeting->record_time,0,4);//年
        $month = substr($meeting->record_time,5,2);//月
        $day = substr($meeting->record_time,8,2);//日
        $hours = substr($meeting->record_time,11,2);//小时
        $minute = substr($meeting->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        if($meeting->save_path){
            $file_path = $meeting->save_path;
            $filepath = '/opt/www-nginx/web'.$file_path;
        }else{
            if($module_type == 1) {
                $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/train/'.$meeting->add_conid.'/TRAIN' . $id . $time .'.pdf';
                $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/train/'.$meeting->add_conid;
//                $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/train' . '/TRAIN' . $id . '.pdf';
//                $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/train';
            }else{
                $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/meeting/'.$meeting->add_conid.'/MEETING' . $id . $time .'.pdf';
                $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/meeting/'.$meeting->add_conid;
//                $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/meeting' . '/MEETING' . $id . '.pdf';
//                $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/meeting';
            }
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
        }

        $title = $meeting->title;

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new TrainShsdPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        if($module_type == 1) {
            $_SESSION['title'] = 'Training Records No. (培训记录编号): ' . $meeting->training_id;
        }else{
            $_SESSION['title'] = 'Meeting Records No. (会议记录编号): ' . $meeting->training_id;
        }

        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                $main_conid = $pro_params['transfer_con'];
            } else {
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $main_conid = $pro_model->contractor_id;//总包编号
        }
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $pdf->Header();
//        if($module_type == 1) {
//            $pdf->SetHeaderData($logo, $logo_size, 'Training Records No. (培训记录编号): ' . $meeting->training_id, $main_conid_name, array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData($logo, $logo_size, 'Meeting Records No. (会议记录编号): ' . $meeting->training_id, $main_conid_name, array(0, 64, 255), array(0, 64, 128));
//        }
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);

        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

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
//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文OR中文
//        }

        $pdf->AddPage();

        $members = TrainWorker::getMembersName($meeting->training_id);

//        $start_date = date('Y-m-d',strtotime($meeting->from_date));
//        $end_date = date('Y-m-d',strtotime($meeting->end_date));
        $from_date = Utils::DateToEn(substr($meeting->start_time,0,10));//起始日期
        $end_date = Utils::DateToEn(substr($meeting->end_time,0,10));//截止日期
        $from_time = substr($meeting->start_time,11,19);//起始时间
        $end_time = substr($meeting->end_time,11,19);//起始时间

        //标题(许可证类型+项目)
        $title_html = "<h2 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$pro_model->program_name}</h2><br/>";

        $apply_user =  Staff::model()->findAllByPk($meeting->add_user);//申请人
        $apply_content = $apply_user[0]['signature_path'];
        $apply_sign_img = '';
        if ($apply_content != '' && $apply_content != 'nil' && $apply_content != '-1') {
            if(file_exists($apply_content)){
                $apply_sign_img= '<img src="'.$apply_content.'" height="30" width="60" />';
            }
        }
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        $contractor_id = $apply_user[0]['contractor_id'];//发起人公司
//        $user_list = Staff::allInfo();//员工信息（包括已被删除的）
        $status_css = CheckApplyDetail::statusTxt();//执行类型
        //发起人详情
        $record_time = Utils::DateToEn($meeting->record_time);
        $apply_info_html = "<br/><br/><h2 align=\"center\">Conducting Personnel Details (举办人员详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
        if($apply_user[0]['work_pass_type'] = 'IC' || $apply_user[0]['work_pass_type'] = 'PR'){
            if(substr($apply_user[0]['work_no'],0,1) == 'S' && strlen($apply_user[0]['work_no']) == 9){
                $work_no = 'SXXXX'.substr($apply_user[0]['work_no'],5,8);
            }else{
                $work_no = $apply_user[0]['work_no'];
            }
        }else{
            $work_no = $apply_user[0]['work_no'];
        }
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$apply_user[0]['user_name']}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$apply_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$work_no}</td><td  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$contractor_id]}</td></tr>";
        $apply_info_html .="<tr><td colspan='2' height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Created Date (创建日期)</td><td colspan='2' height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td colspan='2' height=\"50px\" width=\"50%\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$record_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;$apply_sign_img</td></tr>";
        $apply_info_html .="</table>";

        $apply_y = $pdf->GetY();
        $apply_y = $apply_y +78;
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
//        if($apply_user[0]['signature_path']){
//            $pdf->Image($apply_user[0]['signature_path'], 150, $apply_y, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//        }

        //培训详情&&会议详情
        $type_name = $meeting->type_name;
        $type_name=str_replace('"', '', $type_name);
        if($module_type == 1) {
            $work_content_html = "<br/><h2 align=\"center\">Training Details (培训详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Training Type (培训类型)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Training Venue (培训地点)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->type_name_en}<br>({$type_name})</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->location}</td></tr>";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$from_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$end_time}</td></tr>";
        }else{
            $work_content_html = "<br/><br/><h2 align=\"center\">Meeting Details (会议详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Meeting Type (会议类型)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Meeting Venue (会议地点)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->type_name_en}<br>({$type_name})</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->location}</td></tr>";
            $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td></tr>";
            $work_content_html .="<tr><td align=\"center\" height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$from_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$from_date} {$end_time}</td></tr>";
        }
        $work_content_html .="</table>";
        //培训日期
        if($module_type == 1) {
            $work_date_html = "<br><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
            $work_date_html .="<tr><td height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->title}</td></tr>";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Content (内容)</td></tr>";
            $work_date_html .="<tr><td  style=\"border-width: 1px;border-color:gray gray gray gray; line-height:200%;\">{$meeting->content}</td></tr>";
        }else{
            $work_date_html = "<br><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
            $work_date_html .="<tr><td height=\"50px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->title}</td></tr>";
            $work_date_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Content (内容)</td></tr>";
            $work_date_html .="<tr><td  style=\"border-width: 1px;border-color:gray gray gray gray; line-height:200%;\">{$meeting->content}</td></tr>";
        }
        $work_date_html .="</table>";

        $html = $title_html . $apply_info_html . $work_content_html . $work_date_html;

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->AddPage();
        $progress_list = CheckApplyDetail::progressList( $app_id,$meeting->training_id);//审批步骤详情

        if($module_type == 1) {
            $pic_title_html = '<h2 align="center">Training Photo(s) (培训照片)</h2>';
        }else{
            $pic_title_html = '<h2 align="center">Meeting Photo(s) (会议照片)</h2>';
        }
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        $y2 = $pdf->GetY();

        //判断每一页图片边框的高度
        $total_height = array();
        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    // var_dump($pic);
                    // exit;
                    if($y2 > 266){
                        $y2 = 30;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $title_height =48+3;
                    $cnt = 0;
                    foreach ($pic as $key => $content) {
                        $content = $pic[0];
                        if($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)) {
                                $ratio_width = 55;
                                //超过固定宽度换行
                                if($toatl_width > 190){
                                    if($info_y < 220){
                                        $toatl_width = 0;
                                        $info_x = 15+3;
                                        $info_y+=45+3;
                                        $title_height+=45+3;
//                                        var_dump($info_y);
                                    }
//                                    var_dump(111111);
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220){
//                                    var_dump(2222222);
                                    $total_height[$cnt] = $title_height-43;
//                                    var_dump(1111);
                                    $info_y = 10;
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                    $title_height = 45+10;
                                    $cnt++;
                                }else{
//                                    var_dump(333333);
                                    $total_height[$cnt] = $title_height;
                                }
                                //一行中按间距排列图片
                                $info_x += $ratio_width+3;
                                if($toatl_width == 0){
                                    $toatl_width = $ratio_width;
                                }
                                $toatl_width+=$ratio_width+3;
                            }
                        }
                    }
//                    var_dump($y2);
                }
            }
        }
//        var_dump($total_height);
//        exit;
        $table_count = count($total_height);
        if(empty($total_height)){
            $table_height = 0;
        }else{
            $table_height = 3.5*$total_height[0];
        }

        $pdf->Ln(2);

        if($table_count>1){
            if($module_type == 1) {
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
            }else{
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
            }
            $pic_html .= '</table>';
        }else{
            if($module_type == 1) {
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            }else{
                $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            }
            $pic_html .= '</table>';
        }
        $y2= $pdf->GetY();
        $pdf->writeHTML($pic_html, true, false, true, false, '');

        if (!empty($progress_list)){
//            var_dump($progress_list);
//            exit;
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    if($y2 > 266){
                        $y2 = 23;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $j = 1;
                    foreach ($pic as $key => $content) {
//                        $content = $pic[0];
                        if($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)) {
                                $ratio_width = 55;
                                //超过固定宽度换行
                                if($toatl_width > 190){
                                    $toatl_width = 0;
                                    $info_x = 15+3;
                                    $info_y+=45+3;
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220 ){
                                    $j++;
                                    $pdf->AddPage();
                                    $pdf->setPrintHeader(false);
                                    $info_y = $pdf->GetY();
//                                    var_dump($info_y);
                                    $table_height = 3.5*$total_height[$j-1];
                                    if($table_count == $j){
                                        $pic_html = '<br/><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
                                        $pic_html .= '</table>';
                                    }else{
                                        $pic_html = '<br/><table style="border-width: 1px;border-color:lightslategray lightslategray white lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
                                        $pic_html .= '</table>';
                                    }
                                    $pdf->writeHTML($pic_html, true, false, true, false, '');
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                }
                                $pdf->Image($content, $info_x, $info_y, '55', '45', 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                //一行中按间距排列图片
                                $info_x += $ratio_width+3;
                                if($toatl_width == 0){
                                    $toatl_width = $ratio_width;
                                }
                                $toatl_width+=$ratio_width+3;
                            }
                        }
                    }
//                    var_dump($y2);
                }
            }
        }

        $pdf->AddPage();

        $progress_result = CheckApplyDetail::resultTxt();
        $j = 1;
        $y = 1;
        $info_xx = 166;
        $info_yy = $pdf->GetY();
        if (!empty($progress_list))
            $num = count($progress_list);
//        $pdf->AddPage();
        if($num < 10) {
            //审批流程
            //                $pic = 'C:\Users\minchao\Desktop\5.png';
            $audit_html = '<h2 align="center">Workflow (流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
            foreach ($progress_list as $key => $row) {

                $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                $content = $content_list[0]['signature_path'];
                $sign_img = '';
//                $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
                //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                if ($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)){
                        $sign_img= '<img src="'.$content.'" height="30" width="60" />';
//                        $pdf->Image($content, $info_xx, $info_yy+24, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['remark'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">'.$sign_img.'</td></tr>';
                $j++;
//                $info_yy += 15.5;
            }
            $audit_html .= '</table>';

            //文档标签
            $document_html = '<h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N (序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name (文档名称)</td></tr>';
            if(!empty($document_list)){
                $i =1;
                foreach($document_list as $cnt => $name){
                    $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                    $i++;
                }
            }else{
                $document_html .='<tr><td colspan="2" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
            }
            $document_html .= '</table>';

            //参与人员
            $worker_html = '<h2 align="center">Participant(s) (参与成员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Company<br>(公司)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职务)</td></tr>';

            if (!empty($members)){
                $i = 1;
                foreach ($members as $user_id => $r) {
                    $user_model =Staff::model()->findByPk($user_id);
                    if($user_model->work_pass_type = 'IC' || $user_model->work_pass_type = 'PR'){
                        if(substr($user_model->work_no,0,1) == 'S' && strlen($user_model->work_no) == 9){
                            $work_no = 'SXXXX'.substr($user_model->work_no,5,8);
                        }else{
                            $work_no = $r['work_no'];
                        }
                    }else{
                        $work_no = $r['work_no'];
                    }
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$company_list[$r['contractor_id']].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['worker_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$roleList[$r['role_id']].'</td></tr>';
                    $i++;
                }
            }
            if(!empty($worker_temp)){
                foreach ($worker_temp as $k => $u) {
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Temp</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['contractor_name'].'</td><td align="center">&nbsp;' . $u['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['role_name'].'</td></tr>';
                    $i++;
                }
            }
            $worker_html .= '</table>';

            $html_1 = $audit_html . $worker_html .$document_html;

            $pdf->writeHTML($html_1, true, false, true, false, '');
        }else{
            $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
            foreach ($progress_list as $key => $row) {
                if($row['step'] < 10) {
                    $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                    $content = $content_list[0]['signature_path'];
                    //$content = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                    if ($content != '' && $content != 'nil' && $content != '-1') {
                        $sign_img= '<img src="'.$content.'" height="30" width="60" />';
//                        $pdf->Image($content, $info_xx, $info_yy, 24, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['remark'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">'.$sign_img.'</td></tr>';
                    $j++;
                    $info_yy += 15.5;
                }
            }
            $audit_html .= '</table>';
            $html_1 = $audit_html;
            $pdf->writeHTML($html_1, true, false, true, false, '');
            $pdf->AddPage();
            $audit_html2 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            foreach ($progress_list as $key => $row) {
                if($row['step'] >= 10) {
                    $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                    $content = $content_list[0]['signature_path'];
                    //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                    if ($content != '' && $content != 'nil' && $content != '-1') {
                        $sign_img= '<img src="'.$content.'" height="30" width="60" />';
//                        $pdf->Image($content, $info_xx, 30, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    $audit_html2 .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status_css[$row['deal_type']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['remark'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">'.$sign_img.'</td></tr>';
                    $j++;
                    $info_yy += 15.5;
                }
            }
            $audit_html2 .= "</table>";
            //文档标签
            $document_html = '<h2 align="center">Document(s) (标签)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name(文档名称)</td></tr>';
            if(!empty($document_list)){
                $i =1;
                foreach($document_list as $cnt => $name){
                    $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                    $i++;
                }
            }
            $document_html .= '</table>';
            //参与人员
            $worker_html = '<h2 align="center">Participants(s) (参与成员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Company<br>(公司)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职务)</td></tr>';

            if (!empty($members)){
                $i = 1;
                foreach ($members as $user_id => $r) {
                    $user_model =Staff::model()->findByPk($user_id);
                    if($user_model->work_pass_type = 'IC' || $user_model->work_pass_type = 'PR'){
                        if(substr($user_model->work_no,0,1) == 'S' && strlen($user_model->work_no) == 9){
                            $work_no = 'SXXXX'.substr($user_model->work_no,5,8);
                        }else{
                            $work_no = $r['wp_no'];
                        }
                    }else{
                        $work_no = $r['wp_no'];
                    }
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$company_list[$r['contractor_id']].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['worker_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$roleList[$r['role_id']].'</td></tr>';
                    $i++;
                }
            }
            if(!empty($worker_temp)){
                foreach ($worker_temp as $k => $u) {
                    $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Temp</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['contractor_name'].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $u['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$u['role_name'].'</td></tr>';
                    $i++;
                }
            }
            $worker_html .= '</table>';

            $html2 = $audit_html2 . $worker_html .$document_html;
            $pdf->writeHTML($html2, true, false, true, false, '');
        }


        //输出PDF
        if(array_key_exists('ftp',$params)){
            $pdf->Output($filepath, 'F');  //保存到指定目录
        }else{
            $pdf->Output($filepath, 'F');  //保存到指定目录
            $pdf->Output($filepath, 'I');
        }
        return $filepath;
    }

    //按项目查询安全检查次数（按类别分组）
    public static function AllCntList($args){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $type_list = TrainType::typeText();
//        var_dump($args['date']);
//        var_dump($month);
        //分包项目
        if($args['contractor_id'] != '' && $pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(training_id) as cnt,program_id,type_id from train_apply_basic  where  program_id = '".$root_proid."' and record_time like '".$month."%' and add_conid = '".$args['contractor_id']."' and module_type='".$args['module_type']."'  GROUP BY type_id";
        }else{
            //总包项目
            $sql = "select count(training_id) as cnt,program_id,type_id from train_apply_basic  where  record_time like '%".$month."%' and program_id ='".$args['program_id']."' and module_type='".$args['module_type']."' GROUP BY type_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $type_list[$list['type_id']];

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
            $sql = "select count(training_id) as cnt,add_conid from train_apply_basic  where  record_time like '%".$month."%' and program_id ='".$root_proid."' and add_conid = '".$args['contractor_id']."' and module_type='".$args['module_type']."'  GROUP BY add_conid";
        }else{
            //总包项目
            $sql = "select count(training_id) as cnt,add_conid from train_apply_basic  where  record_time like '%".$month."%' and program_id ='".$args['program_id']."' and module_type='".$args['module_type']."'  GROUP BY add_conid";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;

        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['add_conid']];

            }
        }
        return $r;
//        var_dump($r);
//        exit;
    }
}
