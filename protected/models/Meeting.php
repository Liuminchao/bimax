<?php

/**
 * 工具箱会议
 * @author LiuMinchao
 */
class Meeting extends CActiveRecord {

    const STATUS_REVISE = '-1'; //修改中
    const STATUS_AUDITING = '0'; //审批中
    const STATUS_FINISH = '1'; //审批完成
    const STATUS_REJECT = '2'; //审批不通过

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'tbm_meeting_basic';
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
            self::STATUS_REVISE => Yii::t('license_licensepdf', 'STATUS_REVISE'),
            self::STATUS_AUDITING => Yii::t('comp_ra', 'STATUS_AUDITING'),
            self::STATUS_FINISH => Yii::t('license_licensepdf', 'STATUS_FINISH'),
            self::STATUS_REJECT => Yii::t('license_licensepdf', 'STATUS_REJECT'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_REVISE => 'label-primary', //审批中
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

        //Meeting
        if ($args['meeting_id'] != '') {
            $condition.= ( $condition == '') ? ' meeting_id=:meeting_id' : ' AND meeting_id=:meeting_id';
            $params['meeting_id'] = $args['meeting_id'];
        }
        //会议标题
        if ($args['title'] != '') {
            $condition.= ( $condition == '') ? ' title LIKE :title' : ' AND title LIKE :title';
            $params['title'] = '%'.$args['title'].'%';
        }

        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        $contractor_list = Contractor::Mc_scCompList($args);
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $condition.= ( $condition == '') ? ' add_conid =:contractor_id ' : ' AND add_conid =:contractor_id ';
                $root_proid = $pro_model->root_proid;
                $params['program_id'] = $root_proid;
//                $params['program_id'] = $args['program_id'];
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
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
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $condition.= ( $condition == '') ? ' add_conid =:contractor_id ' : ' AND add_conid =:contractor_id ';
                $params['program_id'] = $program_id;
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $params['program_id'] = $program_id;
            }
        }

        if($args['user_id'] != ''){
            if($args['deal_type'] != -1) {
                $sql = "SELECT b.meeting_id FROM bac_check_apply_detail a,tbm_meeting_basic b WHERE a.deal_user_id = '".$args['user_id']."' and a.app_id = 'TBM' and a.apply_id = b.meeting_id and b.program_id = '".$args['program_id']."' and a.deal_type = '" . $args['deal_type'] . "'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['meeting_id'] .= $row['meeting_id'] . ',';
                    }
                }
                if ($args['meeting_id'] != ''){
                    $args['meeting_id'] = substr($args['meeting_id'], 0, strlen($args['meeting_id']) - 1);
                }else{
                    $args['meeting_id'] = 0;
                }
                $condition .= ($condition == '') ? ' meeting_id IN (' . $args['meeting_id'] . ')' : ' AND meeting_id IN (' . $args['meeting_id'] . ')';
            }else{
                $sql = "SELECT b.meeting_id FROM tbm_meeting_worker a,tbm_meeting_basic b  where a.worker_id = '".$args['user_id']."' and a.meeting_id=b.meeting_id  and b.program_id='".$args['program_id']."' ";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['meeting_id'] .= $row['meeting_id'] . ',';
                    }
                }
                if ($args['meeting_id'] != ''){
                    $args['meeting_id'] = substr($args['meeting_id'], 0, strlen($args['meeting_id']) - 1);
                }else{
                    $args['meeting_id'] = 0;
                }
                $condition .= ($condition == '') ? ' meeting_id IN (' . $args['meeting_id'] . ')' : ' AND meeting_id IN (' . $args['meeting_id'] . ')';
            }
        }

//        //Record Time
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

        //Contractor
        if ($args['con_id'] != ''){
            //我提交+我审批＝我参与
            $condition.= ( $condition == '') ? ' add_conid =:contractor_id ' : ' AND add_conid =:contractor_id ';
            $params['contractor_id'] = $args['con_id'];
        }
//        var_dump($condition);
//        var_dump($params);
//        exit;
        $total_num = Meeting::model()->count($condition, $params); //总记录数

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
        $rows = Meeting::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;
        return $rs;
    }
    public static function updatePath($apply_id,$save_path) {
        $save_path = substr($save_path,18);
//        var_dump($save_path);
//        exit;
        $model = Meeting::model()->findByPk($apply_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }
    //人员按权限和成员进行统计
    public static function findBySummary($user_id,$program_id){
        $sql = "SELECT b.deal_type, count(distinct b.apply_id) as cnt
                  FROM tbm_meeting_basic a inner join bac_check_apply_detail b
                  on  a.meeting_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'TBM' and b.deal_user_id = '".$user_id."'
                UNION
                SELECT 'MEMBER' as deal_type, count(distinct c.meeting_id) as cnt
                  FROM tbm_meeting_basic c inner join tbm_meeting_worker d
                  on c.meeting_id=d.meeting_id where c.program_id = '".$program_id."' and d.worker_id = '".$user_id."'";
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
    public static function cntBySummary($user_id,$program_id){
        $sql = "select count(DISTINCT aa.apply_id) as cnt
                  from (  SELECT b.deal_type, b.apply_id
                              FROM tbm_meeting_basic a inner join bac_check_apply_detail b
                              on  a.meeting_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'TBM'  and b.deal_user_id = '".$user_id."'
                           UNION
                           SELECT 'MEMBER' as deal_type, c.meeting_id
                              FROM tbm_meeting_basic c inner join tbm_meeting_worker d
                              on c.meeting_id=d.meeting_id where c.program_id = '".$program_id."' and d.worker_id = '".$user_id."'
                  )aa";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按权限统计使用次数
    public static function findBySet($user_id,$program_id){
        $sql = "SELECT count(DISTINCT a.apply_id) as cnt,a.deal_type FROM bac_check_apply_detail a,tbm_meeting_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'TBM' and a.apply_id = b.meeting_id and b.program_id = '".$program_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按成员统计使用次数
    public static function findByMember($user_id,$program_id){
        $sql = "SELECT count(a.meeting_id) as cnt FROM tbm_meeting_worker a,tbm_meeting_basic b  where a.worker_id = '".$user_id."' and a.meeting_id=b.meeting_id  and b.program_id='".$program_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计使用总次数
    public static function findByAll($user_id,$program_id){
        $sql = "select count(DISTINCT aa.meeting_id) as cnt from (SELECT b.meeting_id FROM bac_check_apply_detail a,tbm_meeting_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'PTW' and a.apply_id = b.meeting_id and b.program_id = '".$program_id."' UNION ALL SELECT c.meeting_id FROM tbm_meeting_worker c,tbm_meeting_basic d where c.worker_id = '".$user_id."' and c.meeting_id=d.meeting_id and d.program_id='".$program_id."')aa ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //下载PDF
    public static function downloadPDF($params,$app_id){
        $type = $params['type'];
        if($type == 'A'){
            $filepath = self::downloaddefaultPDF($params,$app_id);//通用
        }else if($type == 'B'){
            $filepath = self::downloadShsdPDF($params,$app_id);//上海隧道
        }else if($type == 'C'){
            $filepath = self::downloadZjnyPDF($params,$app_id);
        }
        return $filepath;
    }
    //下载PDF
    public static function downloaddefaultPDF($params,$app_id){

        $id = $params['id'];
        $meeting = Meeting::model()->findByPk($id);
        $company_list = Contractor::compAllList();//承包商公司列表
//        $program_list = Program::programAllList();//获取承包商所有项目
        $document_list = TbmDocument::queryDocument($id);//文档列表
        $lang = "_en";
        $program_id = $meeting->program_id;//项目编号

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($meeting->record_time, 0, 4);//年
        $month = substr($meeting->record_time, 5, 2);//月
        $day = substr($meeting->record_time,8,2);//日
        $hours = substr($meeting->record_time,11,2);//小时
        $minute = substr($meeting->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/tbm/'.$meeting->add_conid.'/TBM' . $id . $time .'.pdf';
        Meeting::updatepath($id, $filepath);

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/tbm/'.$meeting->add_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $title = $meeting->title;
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
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
        $add_conid = $meeting->add_conid;
        if($add_conid == '454'){
            $contractor_name = $company_list[$main_conid];
        }else{
            $contractor_name = $company_list[$meeting->add_conid];
        }
//        $contractor_name = $company_list[$meeting->add_conid];
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Toolbox Meeting (TBM) No.(工具箱会议编号):' . $meeting->meeting_id; // 把username存在$_SESSION['user'] 里面
//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 23, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 8, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
//        $pdf->setPrintHeader(false);
        $members = MeetingWorker::getMembersName($meeting->meeting_id);

        $meeting_date = date('Y-m-d', strtotime($meeting->meeting_date));

        $operator_id = $meeting->add_user;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_date = str_replace('-', ' ', $meeting->meeting_date);//申请日期
        $pdf->Ln(2);
        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300%;\" align=\"center\">{$main_conid_name}</h1><h3 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$program_name}</h3>";
        $pdf->writeHTML($title_html, true, false, true, false, '');
        $title_y = $pdf->GetY();
        //申请人资料
        $meeting_date = Utils::DateToEn($meeting->record_time);
//        $meeting_date = substr($meeting_date,0,11);
        $apply_info_html = "<br/><br/><h2 align=\"center\">Applicant Details (申请人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td  height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($add_operator->work_pass_type = 'IC' || $add_operator->work_pass_type = 'PR'){
            if(substr($add_operator->work_no,0,1) == 'S' && strlen($add_operator->work_no) == 9){
                $work_no = 'SXXXX'.substr($add_operator->work_no,5,8);
            }else{
                $work_no = $add_operator->work_no;
            }
        }else{
            $work_no = $add_operator->work_no;
        }

        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$add_operator->user_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$add_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Application (申请时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$contractor_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$meeting_date}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";

        //判断电子签名是否存在 $add_operator->signature_path
        $apply_y = $pdf->GetY();
        $apply_y = $title_y +50;
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        if($add_operator->signature_path){
            $pdf->Image($add_operator->signature_path, 150, $apply_y, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }

        //工作内容
        $work_content_html = "<br/><h2 align=\"center\">TBM Content (工具箱会议内容)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\"><br/>Title (标题)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;&nbsp;{$meeting->title}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/>Description (描述)</td></tr>";
        $work_content_html .="<tr><td height=\"280px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->content}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\"><br/>Start Time (开始时间)</td><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\"><br/>End Time (结束时间)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;&nbsp;{$meeting->from_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;&nbsp;{$meeting->to_time}</td></tr></table>";

        $progress_list = CheckApplyDetail::progressList($app_id, $meeting->meeting_id);//TBM审批结果(快照)
        $status_css = CheckApplyDetail::statusTxt();//执行类型

        $html_1 = $apply_info_html . $work_content_html ;

        $pdf->writeHTML($html_1, true, false, true, false, '');


//        var_dump($y1);
//        exit;

        $pdf->AddPage();
        $pic_title_html = '<h2 align="center">TBM Photo(s) (工具箱会议照片)</h2>';
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        $y2= $pdf->GetY();
//        var_dump($page);
//        exit;
//        var_dump($y2);
//        exit;
        //判断每一页图片边框的高度
        $total_height = array();
        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
                    if($y2 > 266){
                        $y2 = 30;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $title_height =48+3;
                    $cnt = 0;
                    foreach ($pic as $key => $content) {
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
//                                    var_dump($info_y);
//                                    var_dump(2222222);
                                    $total_height[$cnt] = 261-$info_y;
                                    $cnt =$cnt+1;
                                    $total_height[$cnt] = $title_height;
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
            $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray white lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
            $pic_html .= '</table>';
        }else{
            $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            $pic_html .= '</table>';
        }
        $y2= $pdf->GetY();
        $pdf->writeHTML($pic_html, true, false, true, false, '');

        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
//                    for($o=0;$o<=8;$o++){
//                        $pic[$o] =  "/opt/www-nginx/web/filebase/record/2019/02/tbm/pic/tbm_1550054744258_1.jpg";
//                    }
//                     var_dump($pic);
//                     exit;
                    if($y2 > 266){
                        $y2 = 23;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $j = 1;
                    foreach ($pic as $key => $content) {
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
//                                    var_dump(1111);
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
                                $type = getimagesize($content);
                                if($type['mime'] == 'image/jpeg'){
                                    $pdf->Image($content, $info_x, $info_y, 55, 45, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                }else{
                                    $pdf->Image($content, $info_x, $info_y, 55, 45, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                }
//                                $pdf->Image($content, $info_x, $info_y, '55', '45', 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
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
        //审批流程
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';

//        var_dump($progress_list);
//        exit;
        $progress_result = CheckApplyDetail::resultTxt();
        $user_list = Staff::allInfo();//员工信息（包括已被删除的）
        $info_xx = 164;//X方向距离
        $y1= $pdf->GetY();
        $info_yy = 22+$y1;//Y方向距离
        $j = 1;
        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {

                $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;'.$status_css[$row['deal_type']].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;'.Utils::DateToEn($row['deal_time']).'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;'.$row['remark'].'</td><td style="border-width: 1px;border-color:gray gray gray gray"></td>';
//                $pic = 'C:\Users\minchao\Desktop\5.png';
                $content_list = $user_list[$row['deal_user_id']];
                $content = $content_list[0]['signature_path'];
//                $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
//                $path = '/opt/www-nginx/appupload/5/0000001595_TBMMEETINGPHOTO.jpg';
                if($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)) {
                        $pdf->Image($content, $info_xx, $info_yy, 25, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                $audit_html .= '</tr>';
                $j++;
                $info_yy += 16;
            }
        $audit_html .= '</table>';
        $pdf->writeHTML($audit_html, true, false, true, false, '');

        $worker_html = '<br/><h2 align="center">Member(s) (参会成员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  width="10%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  width="35%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td width="15%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Employee ID<br>(员工编号)</td><td width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td></tr>';

        if (!empty($members)) {
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
                $worker_html .= '<tr><td height="25px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['worker_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $user_id . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $roleList[$r['role_id']] . '</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        $html_2 = $worker_html  ;

        $pdf->writeHTML($html_2, true, false, true, false, '');

        //文档标签
        $document_html = '<br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N (序号)</td><td  width="80%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name (文档名称)</td></tr>';
        if(!empty($document_list)){
            $i =1;
            foreach($document_list as $cnt => $name){
                $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                $i++;
            }
        }else{
            $document_html .='<tr><td align="center" colspan="2" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $document_html .= '</table>';

        $pdf->writeHTML($document_html, true, false, true, false, '');


        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function downloadShsdPDF($params,$app_id){
        $id = $params['id'];
        $meeting = Meeting::model()->findByPk($id);
        $program_id = $meeting->program_id;//项目编号
        $main_proname = $meeting->main_proname;
        $document_list = TbmDocument::queryDocument($id);//文档列表
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($meeting->record_time, 0, 4);//年
        $month = substr($meeting->record_time, 5, 2);//月
        $day = substr($meeting->record_time,8,2);//日
        $hours = substr($meeting->record_time,11,2);//小时
        $minute = substr($meeting->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        $staff_list = Staff::userAllList();
        $applydate = Utils::DateToEn(substr($meeting->record_time,0,11));
        $applytime = substr($meeting->record_time,11,8);

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/tbm/'.$meeting->add_conid.'/TBM' . $id . $time .'.pdf';
        Meeting::updatepath($id, $filepath);

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/tbm/'.$meeting->add_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $title = $meeting->title;
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new ReportShsdPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
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
        $add_conid = $meeting->add_conid;

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;


        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('times', '', 11, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
//        $pdf->setPrintHeader(false);
        $members = MeetingWorker::getMembersName($meeting->meeting_id);

        $_SESSION['type_id'] = 'SHSD003';

        $progress_list = CheckApplyDetail::progressList($app_id, $meeting->meeting_id);//TBM审批结果(快照)

        $operator_id = $meeting->add_user;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_date = str_replace('-', ' ', $meeting->meeting_date);//申请日期
        $pdf->Ln(2);
        //标题(许可证类型+项目)
        $program_html = "<p align=\"center\">$program_name</p>";
        $title_html = "<b  align=\"center\">Tool Box Meeting</b>";
        $pdf->writeHTML($program_html, true, false, true, false, '');
        $pdf->writeHTML($title_html, true, false, true, false, '');

        $pdf->Ln(4);

        $html = "<table >";

        $html .= "<tr><td><br>&nbsp;Venue: &nbsp;{$meeting->location}</td><td>&nbsp;Date: &nbsp;{$applydate}&nbsp;<br></td></tr>";
        $html .= "<tr><td>&nbsp;Conducted By: &nbsp;{$add_operator->user_name}&nbsp;</td><td>&nbsp;Time: {$applytime}&nbsp;<br></td></tr>";
        $html .= "</table>";

        $pdf->writeHTML($html, true, false, true, false, '');
//        $pdf->MultiCell('', '', $html, 0, 'J',false, 1, '', '',  true, 0,true, true, $maxh=0, 'T', false);

        $pdf->Cell(0, 0, 'Points / Topics Discussed', 0, 1, 'L', 0, '', 0);

        $html = '<table style="border-width: 1px;border-color:gray gray gray gray">';

        $document_name = '';
        if(!empty($document_list)){
            foreach($document_list as $cnt => $name){
                $document_name .= $name.' ';
            }
        }

        $html .= '<tr><td  align="left" style="border-width: 1px;border-color:gray gray gray gray;">&nbsp;Ref. Doc. (if any):&nbsp;&nbsp;&nbsp;&nbsp;'.$document_name .'</td></tr>';

        $html .='<tr><td align="left" style="border-width: 1px;border-color:white gray gray gray;">&nbsp;' . $meeting->content . '</td></tr>';
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $attendance_title = "<b>Attendance</b>";
        $pdf->writeHTMLCell(0, 0, '15', '', $attendance_title, 0, 1, 0, true, 'L', true);
//        $pdf->Cell(0, 0, 'Attendance', 0, 1, 'L', 0, '', 0);

        $worker_html = '<table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  width="7%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;<b>S/No.</b></td><td  width="28%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;<b>Name</b></td><td width="15%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;<b>WP / FIN / IC No.</b></td><td width="30%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;<b>Company</b></td><td  width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;<b>Signature</b></td></tr>';

        if (!empty($members)) {
            $i = 1;
            foreach ($members as $user_id => $r) {
                $staff_model = Staff::model()->findByPk($user_id);
                $per_signature_path = $staff_model->signature_path;
                $contractor_id = $staff_model->contractor_id;
                $contractor_model = Contractor::model()->findByPk($contractor_id);
                $contractor_name = $contractor_model->contractor_name;
                if(file_exists($per_signature_path)) {
                    $pic_html= '<img src="'.$per_signature_path.'" height="60" width="80" /> ';
                }else{
                    $pic_html = '';
                }
                $worker_html .= '<tr><td height="25px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['worker_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['wp_no'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $contractor_name . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $pic_html . '</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        $html_2 = $worker_html  ;

        $pdf->writeHTML($worker_html, true, false, true, false, '');

        $accident_declare = "<b>Report of Accident/Incident/Near Miss/Injury/Sickness</b>";
        $pdf->writeHTMLCell(0, 0, '15', '', $accident_declare, 0, 1, 0, true, 'L', true);
//        $pdf->Cell(0, 0, 'Report of Accident/Incident/Near Miss/Injury/Sickness', 0, 1, 'L', 0, '', 0);

        $main_proname_arr = json_decode($main_proname,true);

        if(is_array($main_proname_arr)){
            $proname_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
            $proname_html.='<tr>';

            $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Accident</td>';
            $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Incident</td>';
            $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Near Miss</td>';
            $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Personal Injury</td>';
            $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;First-Aid Case</td>';
            $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Sickness</td>';
            $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Infection</td>';

//            foreach($main_proname_arr as $name=>$value){
//                $proname_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$name.'</td>';
//            }
            $proname_html.='</tr><tr>';
            for($x=0;$x<=6;$x++){
                $accident_list[$x] = 0;
            }
            foreach($main_proname_arr as $name=>$value){
                if($name =='accident'){
                    $accident_list[0] = $value;
                }else if($name =='incident'){
                    $accident_list[1] = $value;
                }else if($name =='near_miss'){
                    $accident_list[2] = $value;
                }else if($name == 'personal_injury'){
                    $accident_list[3] = $value;
                }else if($name =='first_aid_case'){
                    $accident_list[4] = $value;
                }else if($name =='sickness'){
                    $accident_list[5] = $value;
                }else if($name == 'infection'){
                    $accident_list[6] = $value;
                }

            }

            foreach($accident_list as $k=>$value){
                $proname_html.='<td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$value.'</td>';
            }

            $proname_html.='</tr></table>';
        }else{
            $proname_html = '';
        }


        $pdf->writeHTML($proname_html, true, false, true, false, '');

        $nation_declare_1 = "<b>Is everyone fit for work after personal health & mental conditions checks are carried out? 	(Yes / <del>No</del>)</b>";
        $pdf->writeHTMLCell(0, 0, '15', '', $nation_declare_1, 0, 1, 0, true, 'L', true);

        $nation_declare_2 = "(<b>Note: </b>If anyone report for medical attention, the temperature & blood pressure check shall be conducted before referring to clinic.)";
        $pdf->writeHTMLCell(0, 0, '15', '', $nation_declare_2, 0, 1, 0, true, 'L', true);

        $nation_declare_3 = "<b>No. of Workers attending TBM</b>";
        $pdf->writeHTMLCell(0, 0, '15', '', $nation_declare_3, 0, 1, 0, true, 'L', true);
//        $pdf->Cell(0, 0, 'Is everyone fit for work after personal health & mental conditions checks are carried out? 	(Yes / No)', 0, 1, 'L', 0, '', 0);

//        $pdf->Cell(0, 0, '(Note: If anyone report for medical attention, the temperature & blood pressure check shall be conducted before referring to clinic.)', 0, 1, 'L', 0, '', 0);

        $sql = "SELECT
           a.worker_id, '' as work_no, a.worker_id as user_id, b.nation_type,
           concat((case b.role_id
                when 'MCLS' then CONCAT('* ',b.user_name) 
                when 'MCWS' then CONCAT('* ',b.user_name)
                when 'MES' then CONCAT('* ',b.user_name)
                when 'WT05' then CONCAT('* ',b.user_name)
                when 'WT14' then CONCAT('* ',b.user_name)
                when 'WT15' then CONCAT('* ',b.user_name)
                when 'WT26' then CONCAT('* ',b.user_name)
                when 'WT30' then CONCAT('* ',b.user_name)
                when 'WT34' then CONCAT('* ',b.user_name)
                when 'WT38' then CONCAT('* ',b.user_name)
            else b.user_name end), ' (', b.nation_type, ')') as user_name
        FROM
           tbm_meeting_worker a
        join bac_staff b on a.worker_id = b.user_id 
        WHERE
           a.meeting_id = '".$id."'";

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        $res_nation = array();
        foreach($rows as $i =>$j){
            if(array_key_exists($j['nation_type'],$res_nation)){
                $res_nation[$j['nation_type']]+=1;
            }else{
                $res_nation[$j['nation_type']] = 1;
            }
        }

        $nation_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
        $nation_html.='<tr>';

        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Bangladeshi</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Chinese</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Indian</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Malaysian</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Indonesian</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Myanmar</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Thailand</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Others</td>';
        $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Total</td>';
//        foreach($res_nation as $name=>$value){
//            $nation_html.='<td   align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$name.'</td>';
//        }
        $nation_list =array();
        for($x=0;$x<=8;$x++){
            $nation_list[$x] = 0;
        }

        foreach($res_nation as $name=>$value){
            if($name =='Bangladesh'){
                $nation_list[0] = $value;
            }else if($name =='PRC'){
                $nation_list[1] = $value;
            }else if($name =='Indian'){
                $nation_list[2] = $value;
            }else if($name == 'Malaysia'){
                $nation_list[3] = $value;
            }else if($name =='Indonesian'){
                $nation_list[4] = $value;
            }else if($name =='Myanmar'){
                $nation_list[5] = $value;
            }else if($name == 'Thai'){
                $nation_list[6] = $value;
            }else{
                $nation_list[7] = $value;
            }
        }

        $total = 0;
        foreach($nation_list as $k=>$value){
            $total+=$value;
        }
        $nation_list[8] = $total;
//        var_dump($nation_list);
//        exit;
        $nation_html.='</tr><tr>';
        foreach($nation_list as $k=>$value){
            $nation_html.='<td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$value.'</td>';
        }

        $nation_html.='</tr></table>';

        $pdf->writeHTML($nation_html, true, false, true, false, '');

        foreach($progress_list as $i => $j){
            if($j['deal_type'] == '1' && $j['status'] == '1'){
                $deal_user_name = $staff_list[$j['deal_user_id']];
                $deal_model = Staff::model()->findByPk($j['deal_user_id']);
                $signature_path = $deal_model->signature_path;
            }else{
                $signature_path = '';
            }
        }
//        $signature_path = '/filebase/record/2018/02/sign/pic/sign1518052755787_1.jpg';
        if(file_exists($signature_path)) {
            $pic_html= '<img src="'.$signature_path.'" height="30" width="30" /> ';
        }else{
            $pic_html = '';
        }


        $signature_declare = 'Witnessed by:  '.$deal_user_name.$pic_html.'<br>(Name / Signature)';

//        $pdf->Cell(0, 0, 'Witnessed by: ______________________
//		     (Name / Signature)', 0, 1, 'L', 0, '', 0);

        $pdf->writeHTMLCell(0, 0, '15', '', $signature_declare, 0, 1, 0, true, 'L', true);

        //输出PDF
        if(array_key_exists('ftp',$params)){
            $pdf->Output($filepath, 'F');  //保存到指定目录
        }else{
            $pdf->Output($filepath, 'F');  //保存到指定目录
            $pdf->Output($filepath, 'I');
        }

        return $filepath;
    }

    //下载PDF
    public static function downloadZjnyPDF($params,$app_id){

        $id = $params['id'];
        $meeting = Meeting::model()->findByPk($id);
        $company_list = Contractor::compAllList();//承包商公司列表
//        $program_list = Program::programAllList();//获取承包商所有项目
        $document_list = TbmDocument::queryDocument($id);//文档列表
        $lang = "_en";
        $program_id = $meeting->program_id;//项目编号

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($meeting->record_time, 0, 4);//年
        $month = substr($meeting->record_time, 5, 2);//月
        $day = substr($meeting->record_time,8,2);//日
        $hours = substr($meeting->record_time,11,2);//小时
        $minute = substr($meeting->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/tbm/'.$meeting->add_conid.'/TBM' . $id . $time .'.pdf';
        Meeting::updatepath($id, $filepath);

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/tbm/'.$meeting->add_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $title = $meeting->title;
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
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
        $add_conid = $meeting->add_conid;
        if($add_conid == '454'){
            $contractor_name = $company_list[$main_conid];
        }else{
            $contractor_name = $company_list[$meeting->add_conid];
        }
//        $contractor_name = $company_list[$meeting->add_conid];
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Toolbox Meeting (TBM) No.(工具箱会议编号):' . $meeting->meeting_id; // 把username存在$_SESSION['user'] 里面
//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 23, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 8, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
//        $pdf->setPrintHeader(false);
        $members = MeetingWorker::getMembersName($meeting->meeting_id);

        $meeting_date = date('Y-m-d', strtotime($meeting->meeting_date));

        $operator_id = $meeting->add_user;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_date = str_replace('-', ' ', $meeting->meeting_date);//申请日期
        $pdf->Ln(2);
        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300%;\" align=\"center\">{$main_conid_name}</h1><h3 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$program_name}</h3>";
        $pdf->writeHTML($title_html, true, false, true, false, '');
        $title_y = $pdf->GetY();
        //申请人资料
        $meeting_date = Utils::DateToEn($meeting->record_time);
//        $meeting_date = substr($meeting_date,0,11);
        $apply_info_html = "<br/><br/><h2 align=\"center\">Applicant Details (申请人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td  height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($add_operator->work_pass_type = 'IC' || $add_operator->work_pass_type = 'PR'){
            if(substr($add_operator->work_no,0,1) == 'S' && strlen($add_operator->work_no) == 9){
                $work_no = 'SXXXX'.substr($add_operator->work_no,5,8);
            }else{
                $work_no = $add_operator->work_no;
            }
        }else{
            $work_no = $add_operator->work_no;
        }
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$add_operator->user_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$add_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Application (申请时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$contractor_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$meeting_date}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";

        //判断电子签名是否存在 $add_operator->signature_path
        $apply_y = $pdf->GetY();
        $apply_y = $title_y +50;
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        if($add_operator->signature_path){
            $pdf->Image($add_operator->signature_path, 150, $apply_y, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }

        //工作内容
        $work_content_html = "<br/><h2 align=\"center\">TBM Content (工具箱会议内容)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\"><br/>Title (标题)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;&nbsp;{$meeting->title}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/>Description (描述)</td></tr>";
        $work_content_html .="<tr><td height=\"280px\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$meeting->content}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\"><br/>Start Time (开始时间)</td><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\"><br/>End Time (结束时间)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;&nbsp;{$meeting->from_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;&nbsp;{$meeting->to_time}</td></tr></table>";

        $progress_list = CheckApplyDetail::progressList($app_id, $meeting->meeting_id);//TBM审批结果(快照)

        $status_css = CheckApplyDetail::statusTxt();//执行类型

        $html_1 = $apply_info_html . $work_content_html ;

        $pdf->writeHTML($html_1, true, false, true, false, '');


//        var_dump($y1);
//        exit;

        $pdf->AddPage();
        $pic_title_html = '<h2 align="center">TBM Photo(s) (工具箱会议照片)</h2>';
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        $y2= $pdf->GetY();
//        var_dump($page);
//        exit;
//        var_dump($y2);
//        exit;
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
//                                    var_dump($info_y);
//                                    var_dump(2222222);
                                    $total_height[$cnt] = 261-$info_y;
                                    $cnt =$cnt+1;
                                    $total_height[$cnt] = $title_height;
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
            $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray white lightslategray">
                <tr><td width ="100%" height="'.$table_height.'"></td></tr>';
            $pic_html .= '</table>';
        }else{
            $pic_html = '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            $pic_html .= '</table>';
        }
        $y2= $pdf->GetY();
        $pdf->writeHTML($pic_html, true, false, true, false, '');

        if (!empty($progress_list)){
            foreach ($progress_list as $key => $row) {
                if($row['pic'] != '') {
                    $pic = explode('|', $row['pic']);
//                    for($o=0;$o<=8;$o++){
//                        $pic[$o] =  "/opt/www-nginx/web/filebase/record/2019/02/tbm/pic/tbm_1550054744258_1.jpg";
//                    }
//                     var_dump($pic);
//                     exit;
                    if($y2 > 266){
                        $y2 = 23;
                    }
                    $info_x = 15+3;
                    $info_y = $y2;
                    $toatl_width  =0;
                    $j = 1;
                    foreach ($pic as $key => $content) {
                        $content = $pic[0];
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
//                                    var_dump(1111);
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
                                $type = getimagesize($content);
                                if($type['mime'] == 'image/jpeg'){
                                    $pdf->Image($content, $info_x, $info_y, 55, 45, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                }else{
                                    $pdf->Image($content, $info_x, $info_y, 55, 45, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
                                }
//                                $pdf->Image($content, $info_x, $info_y, '55', '45', 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
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
        //审批流程
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  nowrap="nowrap" width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';

//        var_dump($progress_list);
//        exit;
        $progress_result = CheckApplyDetail::resultTxt();
        $user_list = Staff::allInfo();//员工信息（包括已被删除的）
        $info_xx = 164;//X方向距离
        $y1= $pdf->GetY();
        $info_yy = 22+$y1;//Y方向距离
        $j = 1;
        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {

                $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;'.$status_css[$row['deal_type']].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;'.Utils::DateToEn($row['deal_time']).'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"><br/><br/>&nbsp;'.$row['remark'].'</td><td style="border-width: 1px;border-color:gray gray gray gray"></td>';
//                $pic = 'C:\Users\minchao\Desktop\5.png';
                $content_list = $user_list[$row['deal_user_id']];
                $content = $content_list[0]['signature_path'];
//                $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
//                $path = '/opt/www-nginx/appupload/5/0000001595_TBMMEETINGPHOTO.jpg';
                if($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)) {
                        $pdf->Image($content, $info_xx, $info_yy, 25, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                $audit_html .= '</tr>';
                $j++;
                $info_yy += 16;
            }
        $audit_html .= '</table>';
        $pdf->writeHTML($audit_html, true, false, true, false, '');

        $worker_html = '<br/><h2 align="center">Member(s) (参会成员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  width="5%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  width="12%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  width="18%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td width="10%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Employee ID<br>(员工编号)</td><td width="15%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td><td width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Signature (Start)<br>签名(开工)</td><td width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Signature (End)<br>签名(完工)</td></tr>';

        if (!empty($members)) {
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
                $worker_html .= '<tr><td height="40px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['worker_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $user_id . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $roleList[$r['role_id']] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        $html_2 = $worker_html  ;

        $pdf->writeHTML($html_2, true, false, true, false, '');

        //文档标签
        $document_html = '<br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N (序号)</td><td  width="80%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Document Name (文档名称)</td></tr>';
        if(!empty($document_list)){
            $i =1;
            foreach($document_list as $cnt => $name){
                $document_html .='<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name . '</td></tr>';
                $i++;
            }
        }else{
            $document_html .='<tr><td align="center" colspan="2" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $document_html .= '</table>';

        $pdf->writeHTML($document_html, true, false, true, false, '');


        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
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
        if($args['contractor_id'] != '' && $pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(meeting_id) as cnt,add_conid from tbm_meeting_basic where record_time like '%".$month."%' and program_id ='".$root_proid."' and add_conid = '".$args['contractor_id']."'  GROUP BY add_conid";
        }else{
            //总包项目
            $sql = "select count(meeting_id) as cnt,add_conid from tbm_meeting_basic where record_time like '%".$month."%' and program_id ='".$args['program_id']."'  GROUP BY add_conid";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

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
