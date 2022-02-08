<?php

/**
 * 意外
 * @author LiuMinchao
 */
class AccidentBasic extends CActiveRecord {

    //状态:0 申请  1 申请审批   2 申请批准  3关闭   4 关闭审批   5  关闭批准   6 强制驳回
    const STATUS_DRAFT = '-1'; //申请
    const STATUS_SUBMITTED = '0'; //申请审批
    const STATUS_REVIEWED = '1'; //申请批准
    const STATUS_REJECTED = '2'; //关闭
    const STATUS_ENDORSED = '3'; //关闭审批
    const STATUS_DELETED = '9'; //删除草稿

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'accident_basic';
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
    public static function statusTxt($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVIEWED => 'Reviewed',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_ENDORSED => 'Endorsed',
            self::STATUS_DELETED => 'Deleted',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'label-info',
            self::STATUS_SUBMITTED => 'label-info',
            self::STATUS_REVIEWED => 'label-success',
            self::STATUS_REJECTED => 'label-danger',
            self::STATUS_ENDORSED => 'label-info',
            self::STATUS_DELETED => 'label-danger',
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
//        exit;
        //Accident
        if ($args['apply_id'] != '') {
            $condition.= ( $condition == '') ? ' apply_id=:apply_id' : ' AND apply_id=:apply_id';
            $params['apply_id'] = $args['apply_id'];
        }
        //意外标题
        if ($args['title'] != '') {
            $condition.= ( $condition == '') ? ' title LIKE :title' : ' AND title LIKE :title';
            $params['title'] = '%'.$args['title'].'%';
        }
        if($args['user_id'] != ''){
            if($args['deal_type'] != -1) {
                $sql = "SELECT b.apply_id FROM bac_check_apply_detail a,accident_basic b WHERE a.deal_user_id = '".$args['user_id']."' and a.app_id = 'ACCI' and a.apply_id = b.apply_id and b.root_proid = '".$args['program_id']."' and b.type_id ='".$args['type_id']."' and a.deal_type = '" . $args['deal_type'] . "'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['apply_id'] .= $row['apply_id'] . ',';
                    }
                }
                if ($args['apply_id'] != '')
                    $args['apply_id'] = substr($args['apply_id'], 0, strlen($args['apply_id']) - 1);
                $condition .= ($condition == '') ? ' apply_id IN (' . $args['apply_id'] . ')' : ' AND apply_id IN (' . $args['apply_id'] . ')';
            }else{
                $sql = "SELECT b.apply_id FROM accident_staff_info a,accident_basic b  where a.user_id = '".$args['user_id']."' and a.apply_id=b.apply_id  and b.root_proid='".$args['program_id']."'and b.type_id ='".$args['type_id']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['apply_id'] .= $row['apply_id'] . ',';
                    }
                }
                if ($args['apply_id'] != '')
                    $args['apply_id'] = substr($args['apply_id'], 0, strlen($args['apply_id']) - 1);
                $condition .= ($condition == '') ? ' apply_id IN (' . $args['apply_id'] . ')' : ' AND apply_id IN (' . $args['apply_id'] . ')';
            }
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
        //Program
        if ($args['program_id'] != '') {
            $pro_model = Program::model()->findByPk($args['program_id']);
            $root_proid = $pro_model->root_proid;
            if($args['program_id'] != $root_proid){
                $condition .= ( $condition == '') ?' root_proid = :root_proid' : 'AND root_proid =:root_proid ';
                $params['root_proid'] = $root_proid;
                //contractor_id
                if ($args['contractor_id'] != '') {
                    $condition.= ( $condition == '') ? ' involve_company=:involve_company' : ' AND involve_company=:involve_company';
                    $params['involve_company'] = $args['contractor_id'];
                }
            }else{
                $condition .= ( $condition == '') ?' root_proid = :root_proid' : 'AND root_proid =:root_proid ';
                $params['root_proid'] = $args['program_id'];
            }

        }
        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' type_id=:type_id' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }

        //type_id
        if ($args['acci_type'] != '') {
            $condition.= ( $condition == '') ? ' acci_type=:acci_type' : ' AND acci_type=:acci_type';
            $params['acci_type'] = $args['acci_type'];
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

        $total_num = AccidentBasic::model()->count($condition, $params); //总记录数

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
        $rows = AccidentBasic::model()->findAll($criteria);

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
        $sql = "update accident_basic set save_path = '".$save_path."' where apply_id = '".$apply_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();

    }
    //人员按权限和成员进行统计
    public static function findBySummary($user_id,$program_id){
        $sql = "SELECT a.type_id, b.deal_type, count(distinct b.apply_id) as cnt
                  FROM accident_basic a inner join bac_check_apply_detail b
                  on  a.apply_id = b.apply_id and a.root_proid = '".$program_id."' and b.app_id = 'ACCI' and b.deal_user_id = '".$user_id."'
                  group by a.type_id
                UNION
                SELECT c.type_id, 'MEMBER' as deal_type, count(distinct c.apply_id) as cnt
                  FROM accident_basic c inner join accident_staff_info d
                  on c.apply_id=d.apply_id where c.root_proid = '".$program_id."' and d.user_id = '".$user_id."'
                  group by c.type_id";
        $ptw_type = ApplyBasic::typeList();//许可证类型表(双语)
        $status_css = CheckApplyDetail::statusText();//PTW执行类型
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['type_id'] = $row['type_id'];
//                $rs[$key]['type_name'] = $ptw_type[$row['type_id']];
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
                  from (  SELECT a.type_id, b.deal_type, b.apply_id
                              FROM accident_basic a inner join bac_check_apply_detail b
                              on  a.apply_id = b.apply_id and a.root_proid = '".$program_id."' and b.app_id = 'ACCI'  and b.deal_user_id = '".$user_id."'
                           UNION
                           SELECT c.type_id, 'MEMBER' as deal_type, c.apply_id
                              FROM accident_basic c inner join accident_staff_info d
                              on c.apply_id=d.apply_id where c.root_proid = '".$program_id."' and d.user_id = '".$user_id."'
                  )aa";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按权限统计使用次数(培训)
    public static function accidentBySet($user_id,$program_id){
        $sql = "SELECT count(DISTINCT a.apply_id) as cnt,a.deal_type,b.type_id,b.type_name,b.type_name_en FROM bac_check_apply_detail a,accident_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'ACCI' and a.apply_id = b.apply_id and b.root_proid = '".$program_id."' group by b.type_id ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按成员统计使用次数(培训)
    public static function accidentByMember($user_id,$program_id){
        $sql = "SELECT count(a.apply_id) as cnt,b.type_id,b.type_name,b.type_name_en FROM accident_staff_info a,accident_basic b  where a.user_id = '".$user_id."' and a.apply_id=b.apply_id  and b.root_proid='".$program_id."'group by b.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计使用总次数(培训)
    public static function accidentByAll($user_id,$program_id){
        $sql = "select count(DISTINCT aa.apply_id) as cnt from (SELECT b.apply_id FROM bac_check_apply_detail a,accident_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'ACCI' and a.apply_id = b.apply_id and b.root_proid = '".$program_id."' UNION ALL SELECT c.apply_id FROM accident_staff_info c,accident_basic d where c.user_id = '".$user_id."' and c.apply_id=d.apply_id and d.root_proid='".$program_id."')aa ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    //根据月份统计承包商下各项目意外次数
    public static function summaryByMonth() {
        $contractor_id = Yii::app()->user->contractor_id;
        $args['contractor_id'] = $contractor_id;
        $program_list = Program::McProgramList($args);

        $i = 0;
        $startdate=date('Y-m-01', strtotime(date("Y-m-d")));

        $enddate = date('Y-m-d',strtotime("$startdate +1 month -1 day"));
//        foreach($program_list as $program_id => $program_name) {
            $sql = "select count(apply_id) as cnt,root_proname from accident_basic where apply_contractor_id = '" . $contractor_id . "' and  record_time >= '".$startdate ."' and record_time <= '".$enddate ."' group by  root_proid";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();

            if ($rows) {
                $j = 0;
                $data = array();
                foreach ($rows as $cnt => $list) {
//                    $data[$j][0] = (int)$list['date'];
//                    $data[$j][1] = (int)$list['cnt'];
                    $r[$j]['data'] = $list['cnt'];
                    $r[$j]['label'] = $list['root_proname'];
                    $j++;
                }
            }
//        }
        return $r;
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
        $accident_list = AccidentBasic::model()->find('apply_id=:apply_id',array(':apply_id'=>$id));
        $accident_staff = AccidentStaff::getStaffList($id);//意外人员
        $accident_device = AccidentDevice::getDeviceList($id);//意外设备
        $accident_confession = AccidentConfession::getConfessionList($id);//意外口供
        $accident_sick = AccidentSickLeave::getSickList($id);//请假单
        $company_list = Contractor::compAllList();//承包商公司列表
        $type_list = AccidentType::typeList();//意外类型列表
//        $program_list =  Program::programAllList();//获取承包商所有项目
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($accident_list->record_time,0,4);//年
        $month = substr($accident_list->record_time,5,2);//月
        $day = substr($accident_list->record_time,8,2);//日
        $hours = substr($accident_list->record_time,11,2);//小时
        $minute = substr($accident_list->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

        $program_id = $accident_list->root_proid;
        $root_company = Program::ProgramCompany();//根据项目ID获取企业名称
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                $contractor_id = $pro_params['transfer_con'];
            } else {
                $contractor_id = $accident_list->apply_contractor_id;
            }
        }else{
            $contractor_id = $accident_list->apply_contractor_id;
        }
        $record_time = Utils::DateToEn($accident_list->record_time);
        $title = $accident_list->title;
        $contractor_name = $company_list[$contractor_id];//承包商名称
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/incident/'.$contractor_id.'/ACCIDENT' . $id . $time .'.pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/incident/'.$contractor_id;
//        $filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/incident'.'/ACCIDENT' . $id . '.pdf';
//        $full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/incident';
            if(!file_exists($full_dir))
            {
                umask(0000);
                @mkdir($full_dir, 0777, true);
            }
            AccidentBasic::updatepath($id,$filepath);

        $title = $accident_list->title;
        $type_id = $accident_list->type_id;

//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
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
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $main_conid = $pro_model->contractor_id;//总包编号
        $main_model = Contractor::model()->findByPk($main_conid);
        $contractor_name = $main_model->contractor_name;

        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $_SESSION['title'] = 'Incident Records No. (意外记录编号):' . $accident_list->apply_id;

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

        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300% \" align=\"center\">{$root_company[$program_id]}</h1><br/><h2 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$accident_list->root_proname}</h2><h2 style=\"font-size: 200%\" align=\"center\">Title (标题) : {$title}</h2><br/><br/>";
        $apply_user =  Staff::model()->findAllByPk($accident_list->apply_user_id);//申请人
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        $contractor_id = $apply_user[0]['contractor_id'];//发起人公司

        $user_list = Staff::allInfo();//员工信息（包括已被删除的）
        $status_css = self::statusTxt();//执行类型
        //发起人详情
        $apply_info_html = "<br/><br/><h2 align=\"center\">Reporting Personnel Details (汇报人员详)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td  width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td></tr>";
        if($apply_user[0]['work_pass_type'] = 'IC' || $apply_user[0]['work_pass_type'] = 'PR'){
            if(substr($apply_user[0]['work_no'],0,1) == 'S' && strlen($apply_user[0]['work_no']) == 9){
                $work_no = 'SXXXX'.substr($apply_user[0]['work_no'],5,8);
            }else{
                $work_no = $apply_user[0]['work_no'];
            }
        }else{
            $work_no = $apply_user[0]['work_no'];
        }
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$apply_user[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$apply_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$work_no}</td><td  style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$contractor_id]}</td></tr>";
        $apply_info_html .="<tr><td colspan='2' height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Created Date (创建日期)</td><td colspan='2' height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td colspan='2' height=\"50px\" width=\"50%\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$record_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";

        //意外详情
        $acci_time = Utils::DateToEn($accident_list->acci_time);
        $work_content_html = "<br/><br/><h2 align=\"center\">Incident Details (意外详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $work_content_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Type (类型)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Event Location (事故地点) </td><td  width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Event Time (事故发生时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$type_list[$type_id]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$accident_list->acci_location}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$acci_time}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" colspan='3' width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\">Event Details (事故过程)</td></tr>";
        $work_content_html .="<tr><td height=\"120px\" colspan='3' style=\"border-width: 1px;border-color:gray gray gray gray\">{$accident_list->acci_process}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" colspan='3' width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\">Injury Details (受伤细节)</td></tr>";
        $work_content_html .="<tr><td height=\"70px\" colspan='3' style=\"border-width: 1px;border-color:gray gray gray gray\">{$accident_list->acci_details}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" colspan='3' width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Event Summary (事故总结)</td></tr>";
        $work_content_html .="<tr><td height=\"120px\" colspan='3' style=\"border-width: 1px;border-color:gray gray gray gray\">{$accident_list->description}</td></tr>";
        $work_content_html .="</table>";

        $html_1 = $title_html . $apply_info_html . $work_content_html;
        $pdf->writeHTML($html_1, true, false, true, false, '');

        $pdf->AddPage();
        //意外照片
        $pic_html = '<h2 align="center">Incident Photos (意外照片)</h2><table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
        $pic = $accident_list->acci_pic;
        if($pic != '') {
            $pic = explode('|', $pic);
//            var_dump($pic);
//            exit;
            $info_x = 18;
            $info_y = $pdf->GetY();
            foreach ($pic as $key => $content) {
//                $pic = 'C:\Users\minchao\Desktop\5.png';
//                $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                if ($content != '' && $content != 'nil' && $content != '-1'){
                    if(file_exists($content)){
                        $pdf->Image($content, $info_x, $info_y+13, 55, 45, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        $info_x += 60;
                    }
                }
            }
        }
        $pic_html .= '</table>';


        //发生意外人员
        $worker_html = '<br/><br/><h2 align="center">Personnel Involved (涉及人员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Company<br>(公司)</td><td width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td></tr>';

        if (!empty($accident_staff)){
            $i = 1;
            foreach ($accident_staff as $cnt => $r) {
                $apply_user =  Staff::model()->findAllByPk($r['user_id']);
                if($apply_user[0]['work_pass_type'] = 'IC' || $apply_user[0]['work_pass_type'] = 'PR'){
                    if(substr($apply_user[0]['work_no'],0,1) == 'S' && strlen($apply_user[0]['work_no']) == 9){
                        $work_no = 'SXXXX'.substr($apply_user[0]['work_no'],5,8);
                    }else{
                        $work_no = $apply_user[0]['work_no'];
                    }
                }else{
                    $work_no = $apply_user[0]['work_no'];
                }
                $worker_html .= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$company_list[$apply_user[0]['contractor_id']].'</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['user_name'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$roleList[$apply_user[0]['role_id']].'</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        //发生意外设备
        $primary_list = Device::primaryAllList();
        $devices_html = '<br/><br/><h2 align="center">Equipment Involved (涉及设备)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Registration No.<br>(设备编码)</td><td width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Name<br>(设备名称)</td><td width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Type<br>(设备类型)</td></tr>';
        if (!empty($accident_device)){
            $j =1;
            foreach ($accident_device as $cnt => $list) {
//                var_dump($device_list);
//                exit;
                $devices_html .= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $j . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $primary_list[$list['device_id']] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['device_name'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['device_type'] .'<br>'.$list['device_type_ch']. '</td></tr>';
                $j++;
            }
        }else{
            $devices_html .= '<tr><td colspan="4" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $devices_html .= '</table>';

        //意外口供
        $confession_html = '<br/><br/><h2 align="center">Witness Statement (目击人口供)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td  width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td><td width="50%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Statement<br>(口供)</td></tr>';

        if (!empty($accident_confession)){
            $i = 1;
            foreach ($accident_confession as $cnt => $r) {
                $confession_html .= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" height="140px">&nbsp;' . $r['user_name'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$r['role_name'].'</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['confession'] . '</td></tr>';
                $i++;
            }
        }
        $confession_html .= '</table>';

        $html_2 = $pic_html . $worker_html . $devices_html .$confession_html;
        $pdf->writeHTML($html_2, true, false, true, false, '');

        $pdf->AddPage();
        //请假单
        $info_yy = $pdf->GetY();
        $sick_html = '<br/><br/><h2 align="center">Sick Leave Record (病假记录)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td  width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Start Date<br>(开始日期)</td><td  width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> End Date<br>(结束日期)</td><td  width="70%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Medical Certificate<br>(医疗证书)</td></tr>';
        if($accident_sick) {
            foreach ($accident_sick as $key => $row) {
                $info_xx = 72;
                //$p = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
                if ($row['pic'] != '') {
                    $sick_pic = explode('|', $row['pic']);
                    foreach ($sick_pic as $key => $content) {
//                        $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                        if ($content != '' && $content != 'nil' && $content != '-1') {
                            if(file_exists($content)) {
                                $pdf->Image($content, $info_xx, $info_yy+34, 35, 30, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                                $info_xx = $info_xx + 40;
                            }
                        }
                    }
                }
                $sick_html .= '<tr><td height="115px" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['start_time']) . '</td><td align="center"style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['end_time']) . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray"></td></tr>';
                $j++;
                $info_yy += 32;

            }
        }
        $sick_html .= '</table>';

        $html_3 = $sick_html;

        $pdf->writeHTML($html_3, true, false, true, false, '');

        $progress_list = AccidentBasicDetail::progressList( $app_id,$id);//审批步骤详情
        $progress_result = AccidentBasicDetail::resultTxt();
        $j = 1;
        $y = 1;
        $info_xx = 170;
        $info_yy = $pdf->GetY();
        if (!empty($progress_list))
            $num = count($progress_list);
//        if($num < 5) {
        //审批流程
        //                $pic = 'C:\Users\minchao\Desktop\5.png';
        $audit_html_1 = '<br/><br/><h2 align="center">Workflow (流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
        $count = count($progress_list);

        foreach ($progress_list as $key => $row) {
            if($row['deal_type'] != '7'){
                $content_list = $user_list[$row['deal_user_id']];
                $content = $content_list[0]['signature_path'];
//                    $content = 'http://k.sinaimg.cn/n/sports/transform/20170715/Da8Z-fyiavtv7477193.jpg/w140h95z1l0t0q10009b.jpg';
                if ($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)) {
//                    var_dump($content);
//                    var_dump($key);
                        $pdf->Image($content, $info_xx, $info_yy+35, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                $audit_html_1.= '<tr><td height="55px" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $status_css[$row['deal_type']] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . Utils::DateToEn($row['deal_time']) . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['remark'] . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray"></td></tr>';
                $j++;
                $info_yy += 15;
            }
        }
//        exit;
        $audit_html_1 .= '</table>';

        $html_1 = $audit_html_1;
        $pdf->writeHTML($html_1, true, false, true, false, '');

        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function downloadShsdPDF($params,$app_id){

        $id = $params['id'];
        $accident_list = AccidentBasic::model()->find('apply_id=:apply_id',array(':apply_id'=>$id));
        $accident_staff = AccidentStaff::getStaffList($id);//意外人员
        if(count($accident_staff) == 1 ){
            $accident_staff[1]['user_id'] = '';
            $accident_staff[1]['user_name'] = '';
            $accident_staff[1]['role_name'] = '';
            $accident_staff[1]['company_name'] = '';
            $accident_staff[1]['staff_data'] = '';
            $accident_staff[2]['user_id'] = '';
            $accident_staff[2]['user_name'] = '';
            $accident_staff[2]['role_name'] = '';
            $accident_staff[2]['company_name'] = '';
            $accident_staff[2]['staff_data'] = '';
        }else if(count($accident_staff) == 2){
            $accident_staff[2]['user_id'] = '';
            $accident_staff[2]['user_name'] = '';
            $accident_staff[2]['role_name'] = '';
            $accident_staff[2]['company_name'] = '';
            $accident_staff[2]['staff_data'] = '';
        }
        $accident_detail = AccidentBasicDetail::detailList($id);
        $accident_device = AccidentDevice::getDeviceList($id);//意外设备
        $accident_confession = AccidentConfession::getConfessionList($id);//意外口供
        $accident_sick = AccidentSickLeave::getSickList($id);//请假单
        $detail_list = UniForm::detailList($accident_list->form_id);
        $data_detail_list = UniFormDataAcci::detailList($id);
        $company_list = Contractor::compAllList();//承包商公司列表
        $type_list = AccidentType::typeAllList();//意外类型列表
        $gender_list = Staff::Gender();
        $role_list = Role::roleList();

//        $program_list =  Program::programAllList();//获取承包商所有项目
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($accident_list->record_time,0,4);//年
        $month = substr($accident_list->record_time,5,2);//月
        $day = substr($accident_list->record_time,8,2);//日
        $hours = substr($accident_list->record_time,11,2);//小时
        $minute = substr($accident_list->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        $type_name_en = $accident_list->type_name_en;
        $program_id = $accident_list->root_proid;
        $apply_user_id = $accident_list->apply_user_id;
        $pro_model = Program::model()->findByPk($program_id);
        $apply_user_model = Staff::model()->findByPk($apply_user_id);
        $pro_params = $pro_model->params;//项目参数
        $contractor_id = $accident_list->apply_contractor_id;
        $occurrence_date = Utils::DateToEn(substr($accident_list->acci_time,0,10));
        $occurrence_time = substr($accident_list->acci_time,11,19);
        $reported_date = Utils::DateToEn(substr($accident_list->record_time,0,10));
        $reported_time = substr($accident_list->record_time,11,19);

        $record_time = Utils::DateToEn($accident_list->record_time);
        $title = $accident_list->title;
        $contractor_name = $company_list[$contractor_id];//承包商名称
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/incident/'.$contractor_id.'/ACCIDENT' . $id . $time .'.pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/incident/'.$contractor_id;
//        $filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/incident'.'/ACCIDENT' . $id . '.pdf';
//        $full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/incident';
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        AccidentBasic::updatepath($id,$filepath);

        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        $tcpdf_include_path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'examples' .DIRECTORY_SEPARATOR .'tcpdf_include.php';
        $eng_path = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'examples' .DIRECTORY_SEPARATOR . 'lang' .DIRECTORY_SEPARATOR .'eng.php';
        require_once($tcpdf_include_path);
        require_once($tcpdfPath);
        require_once($eng_path);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // set default monospaced font
        $pdf = new AccShsdPdf('P', 'mm', 'A4', true, 'UTF-8', false);
//        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $main_conid = $pro_model->contractor_id;//总包编号
        $main_model = Contractor::model()->findByPk($main_conid);
        $contractor_name = $main_model->contractor_name;

        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;
        $_SESSION['title'] = 'Incident Records No. (意外记录编号):' . $accident_list->apply_id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

        if($accident_list->acci_type =='ACC'){
            $_SESSION['acci_type'] = 'STEC-EHS-016';
        }else if($accident_list->acci_type =='INC'){
            $_SESSION['acci_type'] = 'STEC-EHS-017';
        }else{
            $_SESSION['acci_type'] = 'STEC-EHS-017A';
        }
        $pdf->Header();
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

        $l = Array();

// PAGE META DESCRIPTORS --------------------------------------

        $l['a_meta_charset'] = 'UTF-8';
        $l['a_meta_dir'] = 'ltr';
        $l['a_meta_language'] = 'da';

// TRANSLATIONS --------------------------------------
        $l['w_page'] = 'side';
        $pdf->setLanguageArray($l);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_conid = $pro_model->contractor_id;//总包编号

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

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('times', '', 10); //英文OR中文

        $pdf->SetLineWidth(0.1);

        $title = $accident_list->title;
        $type_id = $accident_list->type_id;
        $type_info = $type_list[$type_id];
        $checked_img = 'img/checkbox_checked.png';
        $unchecked_img = 'img/checkbox_unchecked.png';
        $logo_img = 'img/shsd.png';
        $logo_img_html= '<img src="'.$logo_img.'" height="40" width="224" /> ';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $background_path = 'img/background.jpg';
        $checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';
        if($accident_list->acci_type == 'INC'){
            $acci_type_list = AccidentType::ListByAccitype('INC');
            $title_left = "<b>INCIDENT OCCURRENCE REPORT FORM</b>";
            $title_right = "<b>REF NO :</b>STEC/NM/  $id<br><b> Page 1 of 5</b>";
            $title_right_2 = "<b> Page 2 of 5</b>";
            $title_right_3 = "<b> Page 3 of 5</b>";
            $title_right_4 = "<b> Page 4 of 5</b>";
            $title_right_5 = "<b> Page 5 of 5</b>";
            $part_a_title = "<b>PART A (Type of Incident)</b>";
            $part_b_title = "<b>PART B (Details of Incident)</b>";
            $part_c_title = "<b>PART C (Persons involved in the Incident)</b>";
            $part_d_title = "<b>PART D (Details of Damage to Property)</b>";
            $part_d_title_1 = "<b>Estimated (if actual mandays lost is not available)</b>";
            $part_d_title_2 = "<b>Actual</b>";
            $part_e_title = "<b>PART E (Details of Damage to Utilities)</b>";
            $part_f_title = "<b>PART F (Description of Incident)</b>";
            $part_g_title = "<b>PART G (Causes of Incident)</b>";
            $part_g_title_1 = "<b>(1)Direct Causes</b>";
            $part_g_title_2 = "<b>(i) Unsafe Conditions</b>";
            $part_g_title_3 = "<b>(ii) Unsafe Practice</b>";
            $part_g_title_4 = "<b>(2)Root Causes</b>";
            $part_g_title_5 = "<b>(i) Work Factors</b>";
            $part_g_title_6 = "<b>(ii) Human Factors</b>";
            $part_g_title_7 = "<b>(3)Weakness of Safety Management System</b>";
            $part_h_title = "<b>PART H (Corrective Action Taken)</b>";
            $part_i_title = "<b>PART I (Follow up investigation)</b>";
            $part_j_title = "<b>PART J (Details of Investigating Person)</b>";
            $part_k_title = "<b>PART K (Reviewed By Contractor’s PM)</b>";
            $part_l_title = "<b>PART L (Endorsed By LTA SPM or Senior Project Staff)</b>";
            $part = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray " align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:white gray gray gray"  align="left" > ' . $title_right .'</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;Name of Company:  '.$company_list[$contractor_id].'&nbsp;</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_a_title.'&nbsp;</td></tr>';
            $u = 0;
            foreach($acci_type_list as $x => $y){
                $u++;
                if($type_id == $y['type_id']){
                    $check_tag = $checked_img_html;
                }else{
                    $check_tag = $unchecked_img_html;
                }
                if($u == 1){
                    $part .= '<tr><td width="25%"  align="left" >&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td>';
                }else if($u ==2){
                    $part .= '<td width="25%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td>';
                }else if($u ==3){
                    if($y['type_id'] =='INC999'){
                        $part .= '<td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'   '.$type_name_en.'</td></tr>';
                    }else{
                        $part .= '<td width="25%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td>';
                    }

                }else if($u ==4){
                    $part .= '<td width="25%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td></tr>';
                    $u = 0;
                }
            }
//            if($u == 3){
//                if($y['type_id'] =='INC999'){
//                    $part .= '<td width="25%"  align="left" >'.$type_name_en.'</td></tr>';
//                }else{
//                    $part .= '<td width="25%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
//                }
//            }
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_b_title.'&nbsp;</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'PROJECT: '.$pro_model->program_name.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'CONTRACT: '.$apply_user_model->user_phone.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" colspan="2">&nbsp;&nbsp;'.'EXACT LOCATION: '.$accident_list->acci_location.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'OCCURRENCE DATE: '.$occurrence_date.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'OCCURRENCE TIME: '.$occurrence_time.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'REPORTED DATE: '.$reported_date.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'REPORTED TIME: '.$reported_time.'</td></tr>';
            $part .= '<tr><td width="100%"  align="left" >&nbsp;&nbsp;'.'COMPANY RESPONSIBLE FOR INCIDENT:  '.$company_list[$contractor_id].'</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_c_title.'&nbsp;</td></tr>';
            $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;A</td><td width="25%"  align="center" >&nbsp;&nbsp;B</td><td width="25%"  align="center" >&nbsp;&nbsp;C</td></tr>';
            if(!empty($accident_staff)){
                $part .= '<tr><td width="25%"  align="left" >&nbsp;&nbsp;NAME</td><td width="25%"  align="center" >&nbsp;&nbsp;'.str_replace('  √','',$accident_staff[0]['user_name']).'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[1]['user_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[2]['user_name'].'</td></tr>';
                $part .= '<tr><td width="25%"  align="left" >&nbsp;&nbsp;DESIGNATION :</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[0]['role_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[1]['role_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[2]['role_name'].'</td></tr>';
                $part .= '<tr><td width="25%"  align="left" >&nbsp;&nbsp;COMPANY :</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[0]['company_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[1]['company_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[2]['company_name'].'</td></tr>';
                $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;<b>Status (you may tick more than one) :</b>&nbsp;</td></tr>';
                $part .= '<tr><td  width="5%"></td><td width="10%">Witness</td><td  width="10%">Incident Reporter</td><td  width="10%">LTA Personnel</td><td width="10%"> Main Contractor\'s Personnel</td><td  width="10%">Subcontractor\'s Personnel</td><td width="10%" >Visitor</td><td width="10%" >Public</td><td width="10%" >Self Employed</td><td width="15%" >Others :</td></tr>';
                $accident_staff_1 = json_decode($accident_staff[0]['staff_data'],true);
                if(!empty($accident_staff_1)){
                    $part .= '<tr><td  width="5%">A</td>';
                    foreach($accident_staff_1 as $x => $y){
                        if($y["item_check"] == '1'){
                            $check_tag = $checked_img_html;
                        }else{
                            $check_tag = $unchecked_img_html;
                        }
                        if($y['item_name'] == 'Others :'){
                            $item_remark = $y["item_remarks"];
                            $part .= '<td width="15%">'.$check_tag.$item_remark.'</td>';
                        }else{
                            $part .= '<td width="10%">'.$check_tag.'</td>';
                        }
                    }
                    $part .='</tr>';
                }else{
                    $part .= '<tr><td  width="5%">A</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="15%" >'.$unchecked_img_html.'</td></tr>';
                }
                $accident_staff_2 = json_decode($accident_staff[1]['staff_data'],true);
                if(!empty($accident_staff_2)){
                    $part .= '<tr><td  width="5%">B</td>';
                    foreach($accident_staff_2 as $x => $y){
                        if($y["item_check"] == '1'){
                            $check_tag = $checked_img_html;
                        }else{
                            $check_tag = $unchecked_img_html;
                        }
                        if($y['item_name'] == 'Others :'){
                            $item_remark = $y["item_remarks"];
                            $part .= '<td width="15%">'.$check_tag.$item_remark.'</td>';
                        }else{
                            $part .= '<td width="10%">'.$check_tag.'</td>';
                        }
                    }
                    $part .='</tr>';
                }else{
                    $part .= '<tr><td  width="5%">B</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="15%" >'.$unchecked_img_html.'</td></tr>';
                }

                $accident_staff_3 = json_decode($accident_staff[2]['staff_data'],true);
                if(!empty($accident_staff_3)){
                    $part .= '<tr><td  width="5%">C</td>';
                    foreach($accident_staff_3 as $x => $y){
                        if($y["item_check"] == '1'){
                            $check_tag = $checked_img_html;
                        }else{
                            $check_tag = $unchecked_img_html;
                        }
                        if($y['item_name'] == 'Others :'){
                            $item_remark = $y["item_remarks"];
                            $part .= '<td width="15%">'.$check_tag.$item_remark.'</td>';
                        }else{
                            $part .= '<td width="10%">'.$check_tag.'</td>';
                        }
                    }
                    $part .='</tr>';
                }else{
                    $part .= '<tr><td  width="5%">C</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="15%" >'.$unchecked_img_html.'</td></tr>';
                }
            }else{
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;NAME</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td></tr>';
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;DESIGNATION :</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td></tr>';
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;COMPANY :</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td></tr>';
                $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;<b>Status (you may tick more than one) :</b>&nbsp;</td></tr>';
                $part .= '<tr><td  width="10%"></td><td width="10%">Witness</td><td  width="10%">Incident Reporter</td><td  width="10%">LTA Personnel</td><td width="10%"> Main Contractor\'s Personnel</td><td  width="10%">Subcontractor\'s Personnel</td><td width="10%" >Visitor</td><td width="10%" >Public</td><td width="10%" >Self Employed</td><td width="10%" >Others :</td></tr>';
                $part .= '<tr><td  width="10%">A</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td></tr>';
                $part .= '<tr><td  width="10%">B</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td></tr>';
                $part .= '<tr><td  width="10%">C</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td></tr>';
            }
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_d_title.'&nbsp;</td></tr>';
            //Part D
            $d_list = json_decode($detail_list['INC-001']['item_data'],true);
            if(array_key_exists('INC-001',$data_detail_list)){
                $data_list = $data_detail_list['INC-001'];
                $value_list = explode(',',$data_list['item_value']);
            }else{
                $value_list[0] = '';
            }
            $d_i = 0;
            foreach($d_list as $item => $value){
                $d_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($d_i == 1){
                    $part .= '<tr><td width="30%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($d_i == 2){
                    $part .= '<td width="20%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($d_i == 3){
                    $part .= '<td width="20%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($d_i == 4){
                    $part .= '<td width="30%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td></tr>';
                    $d_i = 0;
                }
            }

            if(array_key_exists('INC-002',$data_detail_list)){
                $inc_002 = $data_detail_list['INC-002']['item_value'];
            }else{
                $inc_002 = '';
            }
            if($d_i == '2'){
                $part .= '<td width="20%"  align="left" >&nbsp;&nbsp;'.$unchecked_img_html.'Others:</td><td width="30%"  align="left" >&nbsp;&nbsp;'.$inc_002.'</td></tr>';
            }
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_e_title.'&nbsp;</td></tr>';
            $e_list = json_decode($detail_list['INC-003']['item_data'],true);
            if(array_key_exists('INC-001',$data_detail_list)){
                $data_list = $data_detail_list['INC-003'];
                $value_list = explode(',',$data_list['item_value']);
            }else{
                $value_list[0] = '';
            }
            $e_i = 0;
            foreach($e_list as $item => $value){
                $e_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($e_i == 1){
                    $part .= '<tr><td width="16%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($e_i == 2){
                    $part .= '<td width="16%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($e_i == 3){
                    $part .= '<td width="16%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($e_i == 4){
                    $part .= '<td width="16%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                } else if($e_i == 5){
                    $part .= '<td width="16%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($e_i == 6){
                    $part .= '<td width="20%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td></tr>';
                    $e_i = 0;
                }
            }
            if($e_i == '5'){
                if(array_key_exists('INC-004',$data_detail_list)){
                    $inc_004 = $data_detail_list['INC-004']['item_value'];
                }else{
                    $inc_004 = '';
                }
                $part .= '<td width="20%"  align="left" >&nbsp;&nbsp;'.$unchecked_img_html.'Others:'.$inc_004.'</td></tr>';
            }
            $part .= '</table>';

            $part_2 = "<table style=\"border-width: 1px;border-color:gray gray black gray\">";
//            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_2 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_2 .'</td></tr>';
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_f_title.'&nbsp;</td></tr>';
            $f_title_1 = $detail_list['INC-005']['item_title_en'];
            $f_title_2 = $detail_list['INC-006']['item_title_en'];
            $f_title_3 = $detail_list['INC-007']['item_title_en'];
            $f_title_4 = $detail_list['INC-008']['item_title_en'];
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_1.'</td></tr>';
            $part_2 .= '<tr><td height="100px;" width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['INC-005']['item_value'].'</td></tr>';
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_2.'</td></tr>';
            $part_2 .= '<tr><td height="100px;" width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['INC-006']['item_value'].'</td></tr>';
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_3.'</td></tr>';
            $part_2 .= '<tr><td height="100px;" width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['INC-007']['item_value'].'</td></tr>';
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_4.'</td></tr>';
            if($data_detail_list['INC-008']['item_value']){
                $pic_list = explode('|',$data_detail_list['INC-008']['item_value']);
                $f_i = 0;
                $pic_count = count($pic_list);
                $count = 0;
                foreach($pic_list as $i => $pic){
                    if(file_exists($pic)){
                        $img_html= '<img src="https://shell.cmstech.sg'.$pic.'" height="100" width="120" /> ';
                    }else{
                        $img_html = '';
                    }
                    $count++;
                    $f_i++;
                    $d_value = $pic_count-$count;
                    if($d_value > 3){
                        if($f_i == 1){
                            $part_2 .= '<tr><td  width="34%" style="border-width: 1px;border-color:white white white gray" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 2){
                            $part_2 .= '<td  width="33%" style="border-width: 1px;border-color:white white white white" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 3){
                            $part_2 .= '<td  width="33%" style="border-width: 1px;border-color:white gray white white" align="left" >&nbsp;&nbsp;'.$img_html.'</td></tr>';
                            $f_i = 0;
                        }
                    }else{
                        if($f_i == 1){
                            $part_2 .= '<tr><td  width="34%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 2){
                            $part_2 .= '<td  width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 3){
                            $part_2 .= '<td  width="33%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;'.$img_html.'</td></tr>';
                            $f_i = 0;
                        }
                    }

                }
                if($f_i != 3 && $f_i != 0){
                    $part_2 .= '</tr>';
                }

            }else{
                $part_2 .= '<tr><td  height="350px" width="100%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;</td></tr>';
            }

            $part_2 .= '</table>';
//            var_dump($part_2);
//            exit;

            //PartG
            $g_list_1 = json_decode($detail_list['INC-009']['item_data'],true);
            $part_3 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_3 .'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title_1.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_2.'&nbsp;</td></tr>';
            $g_i = 0;
            $data_list = $data_detail_list['INC-009'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_1 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_3 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['INC-010']['item_value'].'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_3.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_2 = json_decode($detail_list['INC-011']['item_data'],true);
            $data_list = $data_detail_list['INC-011'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_2 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_3 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['INC-012']['item_value'].'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title_4.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_5.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_3 = json_decode($detail_list['INC-013']['item_data'],true);
            $data_list = $data_detail_list['INC-013'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_3 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_3 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['INC-014']['item_value'].'</td></tr>';
            $part_3 .= '</table>';
            $part_4 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_4 .'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_6.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_4 = json_decode($detail_list['INC-015']['item_data'],true);
            $data_list = $data_detail_list['INC-015'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_4 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['INC-016']['item_value'].'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title_7.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_5 = json_decode($detail_list['INC-017']['item_data'],true);
            $data_list = $data_detail_list['INC-017'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_5 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['INC-018']['item_value'].'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_h_title.'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['INC-019']['item_value'].'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_i_title.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_6 = json_decode($detail_list['INC-020']['item_data'],true);
            $data_list = $data_detail_list['INC-020'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_6 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="22%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="62%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'&nbsp;&nbsp;(completion date :)'.$data_detail_list['INC-021']['item_value'].'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '</table>';

            $apply_user_model = Staff::model()->findByPk($apply_user_id);
            $apply_sign_html= '<img src="'.$apply_user_model->signature_path.'" height="30" width="30"  /> ';
            $part_5 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_5 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_5 .'</td></tr>';
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_j_title.'&nbsp;</td></tr>';
            if(array_key_exists('submitted',$accident_detail)) {
                $submitted_user_model = Staff::model()->findByPk($accident_detail['submitted']);
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: ' . $submitted_user_model->user_name . '</td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: ' . $role_list[$submitted_user_model->role_id] . '</td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: ' . $company_list[$submitted_user_model->contractor_id] . '</td></tr>';
                $submitted_sign_html= '<img src="'.$submitted_user_model->signature_path.'" height="30" width="30"  /> ';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : ' . $submitted_user_model->user_phone . $checker_pic_html . '</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : ' . Utils::DateToEn($accident_detail['submitted_time']) . $checker_pic_html . '</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : ' . $submitted_sign_html . '</td></tr>';
            }else{
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
            }
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;' . $part_k_title . '&nbsp;</td></tr>';

            if(array_key_exists('reviewd',$accident_detail)){
                $reviewd_user_model = Staff::model()->findByPk($accident_detail['reviewd']);
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: '.$reviewd_user_model->user_name.' </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: '.$role_list[$reviewd_user_model->role_id].'</td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: '.$company_list[$reviewd_user_model->contractor_id].' </td></tr>';
                $reviewd_sign_html= '<img src="'.$reviewd_user_model->signature_path.'" height="30" width="30"  /> ';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : '.$reviewd_user_model->user_phone.$checker_pic_html.'</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : '.Utils::DateToEn($accident_detail['reviewd_time']).$checker_pic_html.'</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : '.$reviewd_sign_html.' </td></tr>';
            }else{
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
            }

            if($accident_list->company_type == '0' || $accident_list->company_type == '01'){
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_l_title.'&nbsp;</td></tr>';

                if(array_key_exists('endorsed',$accident_detail)){
                    $endorsed_user_model = Staff::model()->findByPk($accident_detail['endorsed']);
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: '.$endorsed_user_model->user_name.'  </td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION:  '.$role_list[$endorsed_user_model->role_id].'</td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY:  LTA </td></tr>';
                    $endorsed_sign_html= '<img src="'.$endorsed_user_model->signature_path.'" height="30" width="30"  /> ';
                    $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL :  '.$endorsed_user_model->user_phone.$checker_pic_html.'</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE :  '.Utils::DateToEn($accident_detail['endorsed_time']).$checker_pic_html.'</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN :  '.$endorsed_sign_html.' </td></tr>';
                }else{
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                    $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
                }
            }


            $part_5 .= '</table>';

            $pdf->writeHTML($part, true, false, true, false, '');

            $pdf->AddPage();

            $pdf->writeHTML($part_2, true, false, true, false, '');
//
            $pdf->AddPage();

            $pdf->writeHTML($part_3, true, false, true, false, '');
//
            $pdf->AddPage();

            $pdf->writeHTML($part_4, true, false, true, false, '');

            $pdf->AddPage();

            $pdf->writeHTML($part_5, true, false, true, false, '');
        }else if($accident_list->acci_type == 'ACC'){
            $acci_type_list = AccidentType::ListByAccitype('ACC');
            $title_left = "<b>ACCIDENT OCCURRENCE REPORT FORM</b>";
            $title_right = "<b>REF NO :</b>STEC/NM/  $id<br><b> Page 1 of 6</b>";
            $title_right_2 = "<b> Page 2 of 6</b>";
            $title_right_3 = "<b> Page 3 of 6</b>";
            $title_right_4 = "<b> Page 4 of 6</b>";
            $title_right_5 = "<b> Page 5 of 6</b>";
            $title_right_6 = "<b> Page 6 of 6</b>";
            $part_a_title = "<b>PART A (Type of Accident)</b>";
            $part_b_title = "<b>PART B (Details of Accident)</b>";
            $part_c_title = "<b>PART C (Details of Injured Person)</b>";
            $part_d_title = "<b>PART D (Lost time)</b>";
            $part_d_title_1 = "<b>Estimated (if actual mandays lost is not available)</b>";
            $part_d_title_2 = "<b>Actual</b>";
            $part_e_title = "<b>PART E (Details of Injury)</b><br><br>&nbsp;&nbsp;<b>Use the following codes :</b><br>";
            $part_f_title = "<b>PART F (Description of Accident)</b>";
            $part_g_title = "<b>PART G (Causes of Accident)</b>";
            $part_g_title_1 = "<b>(1)Direct Causes</b>";
            $part_g_title_2 = "<b>(i) Unsafe Conditions</b>";
            $part_g_title_3 = "<b>(ii) Unsafe Practice</b>";
            $part_g_title_4 = "<b>(2)Root Causes</b>";
            $part_g_title_5 = "<b>(i) Work Factors</b>";
            $part_g_title_6 = "<b>(ii) Human Factors</b>";
            $part_g_title_7 = "<b>(3)Weakness of Safety Management System</b>";
            $part_h_title = "<b>PART H (Corrective Action Taken)</b>";
            $part_j_title = "<b>PART J (Details of Investigating Person)</b>";
            $part_k_title = "<b>PART K (Reviewed By Contractor’s PM)</b>";
            $part_l_title = "<b>PART L (Endorsed By LTA SPM or Senior Project Staff)</b>";
            $part = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right .'</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;Name of Company:  '.$company_list[$contractor_id].'&nbsp;</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_a_title.'&nbsp;</td></tr>';
            $u = 0;
            foreach($acci_type_list as $x => $y){
                $u++;
                if($type_id == $y['type_id']){
                    $check_tag = $checked_img_html;
                }else{
                    $check_tag = $unchecked_img_html;
                }

                if($u == 1){
                    $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td>';
                }else if($u ==2){
                    $part .= '<td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td></tr>';
                    $u = 0;
                }
            }
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_b_title.'&nbsp;</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'PROJECT: '.$pro_model->program_name.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'CONTRACT: '.$apply_user_model->user_phone.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" colspan="2">&nbsp;&nbsp;'.'EXACT LOCATION: '.$accident_list->acci_location.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'OCCURRENCE DATE: '.$occurrence_date.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'OCCURRENCE TIME: '.$occurrence_time.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'REPORTED DATE: '.$reported_date.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'REPORTED TIME: '.$reported_time.'</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_c_title.'&nbsp;</td></tr>';
            if(!empty($accident_staff)){
                foreach($accident_staff as $i => $j){
                    if($j['user_id'] != ''){
                        $staff_model = Staff::model()->findByPk($j['user_id']);
                        $staff_info_model = StaffInfo::model()->findByPk($j['user_id']);
                        $staff_contractor_id = $staff_model->contractor_id;
                        $staff_contractor_model = Contractor::model()->findByPk($staff_contractor_id);
                        $staff_contractor_name = $staff_contractor_model->contractor_name;
                    }else{
                        $staff_contractor_name = '';
                        $staff_model = new Staff();
                        $staff_info_model = new StaffInfo();
                    }

                    $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'NAME: '.str_replace('  √','',$j['user_name']).'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'EMPLOYER: '.$staff_contractor_name.'</td></tr>';
                    if($staff_model->work_pass_type = 'IC' || $staff_model->work_pass_type = 'PR'){
                        if(substr($staff_model->work_no,0,1) == 'S' && strlen($staff_model->work_no) == 9){
                            $work_no = 'SXXXX'.substr($staff_model->work_no,5,8);
                        }else{
                            $work_no = $staff_model->work_no;
                        }
                    }else{
                        $work_no = $staff_model->work_no;
                    }
                    if($staff_info_model->gender){
                        $staff_gender = $gender_list[$staff_info_model->gender];
                    }else{
                        $staff_gender = '';
                    }

                    if($staff_model->role_id){
                        $staff_role = $role_list[$staff_model->role_id];
                    }else{
                        $staff_role = '';
                    }
                    $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'DATE OF BIRTH: '.Utils::DateToEn($staff_info_model->birth_date).'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'NRIC/WORK PERMIT NO: '.$work_no.'</td></tr>';
                    $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'GENDER: '.$staff_gender.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'DESIGNATION: '.$staff_role.'</td></tr>';
                    $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'CITIZENSHIP: '.$staff_model->nation_type.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'DATE JOINED SERVICE: '.Utils::DateToEn($staff_info_model->service_date).'</td></tr>';
                    $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'RACE: '.$staff_info_model->race.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'PREVIOUS INDUSTRY EXPERIENCE & <br>&nbsp;&nbsp;&nbsp;&nbsp;DESIGNATION: '.$staff_info_model->previous_designation.'</td></tr>';
                    $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;'.'MARITAL STATUS: '.$staff_info_model->marital.'</td></tr>';

                    $staff_data = json_decode($j['staff_data'],true);

                    $c_list = json_decode($detail_list['ACC-021']['item_data'],true);
//                    $data_list = $data_detail_list['ACC-021'];
//                    $value_list = explode(',',$data_list['item_value']);
                    $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;'.'EMPLOYEE SENT TO: ';
//                    var_dump($c_list);
//                    exit;
                    foreach($c_list as $item => $value) {
                        $check_tag = $unchecked_img_html;
                        $remark = '';
                        if(!empty($staff_data)){
                            foreach($staff_data as $o =>$p ){
                                if($p['item_check'] == '1'){
                                    if($value == $p['item_name']){
                                        $check_tag = $checked_img_html;
                                    }
                                }
                                if($p['item_remarks']){
                                    if($value == $p['item_name']){
                                        $remark = $p['item_remarks'];
                                    }
                                }
                            }
                        }
//                        foreach ($value_list as $x => $y) {
//                            if ($value == $y) {
//                                $check_tag = $checked_img_html;
//                            }
//                        }
                        if($value == 'Hospital :'){
                            $part .= '<tr><td width="23%"  align="left" ></td><td width="77%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'  '. $remark.'&nbsp;&nbsp;(Hospital name)</td></tr>';
                        }else if($value == 'Polyclinic :'){
                            $part .= '<tr><td width="23%"  align="left" ></td><td width="77%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'  '.$remark.'&nbsp;&nbsp;(Polyclinic name) </td></tr>';
                        }else if($value == 'Private Doctor'){
                            $part .='&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$value.'</td></tr>';
                        }else{
                            $part .='&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$value;
                        }
                    }
                }
            }else{
                $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'NAME:</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'EMPLOYER:</td></tr>';
                $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'DATE OF BIRTH:</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'NRIC/WORK PERMIT NO:</td></tr>';
                $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'GENDER:</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'DESIGNATION:</td></tr>';
                $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'CITIZENSHIP:</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'DATE JOINED SERVICE:</td></tr>';
                $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'RACE: </td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'PREVIOUS INDUSTRY EXPERIENCE & DESIGNATION:</td></tr>';
                $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;'.'MARITAL STATUS:</td></tr>';

                $c_list = json_decode($detail_list['ACC-021']['item_data'],true);
                $data_list = $data_detail_list['ACC-021'];
                $value_list = explode(',',$data_list['item_value']);
                $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;'.'EMPLOYEE SENT TO: ';
                foreach($c_list as $item => $value) {
                    $check_tag = $unchecked_img_html;
                    foreach ($value_list as $x => $y) {
                        if ($value == $y) {
                            $check_tag = $checked_img_html;
                        }
                    }
                    if($value == 'Hospital :'){
                        $part .= '<tr><td width="23%"  align="left" ></td><td width="77%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'&nbsp;&nbsp;(Hospital name) '.$data_detail_list['ACC-022']['item_value'].'</td></tr>';
                    }else if($value == 'Polyclinic :'){
                        $part .= '<tr><td width="23%"  align="left" ></td><td width="77%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'&nbsp;&nbsp;(Polyclinic name) '.$data_detail_list['ACC-023']['item_value'].'</td></tr>';
                    }else if($value == 'Private Doctor'){
                        $part .='&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$value.'</td></tr>';
                    }else{
                        $part .='&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$value;
                    }
                }
            }



            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_d_title.'&nbsp;</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_d_title_1.'&nbsp;</td></tr>';
            //Part D
            $d_list = json_decode($detail_list['ACC-001']['item_data'],true);
            $data_list = $data_detail_list['ACC-001'];
            $value_list = explode(',',$data_list['item_value']);
            $d_i = 0;
            foreach($d_list as $item => $value){
                $d_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($d_i == 1){
                    $part .= '<tr><td width="30%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($d_i == 2){
                    $part .= '<td width="30%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td>';
                }else if($d_i == 3){
                    $part .= '<td width="40%"  align="left" >&nbsp;&nbsp;'.$check_tag.$value.'</td></tr>';
                    $d_i = 0;
                }
            }
            $d_title_2 = $detail_list['ACC-002']['item_title_en'];
            $d_title_3 = $detail_list['ACC-003']['item_title_en'];
            $d_title_4 = $detail_list['ACC-004']['item_title_en'];
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_d_title_2.'&nbsp;</td></tr>';
            $part .= '<tr><td width="50%">'.$d_title_2.'</td><td align="center" width="30%" style="border-width: 1px;border-color:black gray gray gray">'.$data_detail_list['ACC-002']['item_value'].'</td></tr>';
            $part .= '<tr><td width="50%">'.$d_title_3.'</td><td align="center" width="30%" style="border-width: 1px;border-color:gray gray gray gray">'.$data_detail_list['ACC-003']['item_value'].'</td></tr>';
            $part .= '<tr><td width="50%">'.$d_title_4.'</td><td align="center" width="30%" style="border-width: 1px;border-color:gray gray gray gray">'.$data_detail_list['ACC-004']['item_value'].'</td></tr>';
            $part .= '</table>';
            //ACC-005-01 Part E
            $e_list_1 = json_decode($detail_list['ACC-005-01']['item_data'],true);
            $e_count_1 = count($e_list_1);
            $e_list_2 = json_decode($detail_list['ACC-005-02']['item_data'],true);
            $part_2 = '<table style=\"border-width: 1px;border-color:gray gray gray gray\">';
//            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_2 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_2 .'</td></tr>';
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_e_title.'&nbsp;</td></tr>';
            $part_2 .= '<tr><td width="50%" style="border-width: 1px;border-color:white white gray gray" align="left" colspan="2">&nbsp;&nbsp;<b>Nature of Injury</b>&nbsp;</td><td width="50%" style="border-width: 1px;border-color:white gray gray white" align="left" colspan="2">&nbsp;&nbsp;<b>Injured Bodypart</b>&nbsp;</td></tr>';
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="left" colspan="2">&nbsp;&nbsp;</td></tr>';

            for($e_i=0;$e_i<=$e_count_1-1;$e_i++){
                if(array_key_exists($e_i,$e_list_1)){
                    $list_1 = explode(':',$e_list_1[$e_i]);
                }else{
                    $list_1[1] = '';
                }
                if(array_key_exists($e_i,$e_list_2)){
                    $list_2 = explode(':',$e_list_2[$e_i]);
                }else{
                    $list_2[1] = '';
                }
                $part_2 .= '<tr><td width="15%" style="border-width: 1px;border-color:gray gray gray gray" align="left" >&nbsp;&nbsp;'.$list_1[0].'</td><td width="35%" style="border-width: 1px;border-color:gray gray gray gray" align="left" >&nbsp;&nbsp;'.$list_1[1].'</td>';
                $part_2 .= '<td width="15%" style="border-width: 1px;border-color:gray gray gray gray" align="left" >&nbsp;&nbsp;<b>'.$list_2[0].'</b></td><td width="35%" style="border-width: 1px;border-color:gray gray gray gray" align="left" >&nbsp;&nbsp;'.$list_2[1].'</td></tr>';
            }

            $data_5 = $data_detail_list['ACC-005']['item_value'];
            $data_5 = json_decode($data_5,true);

            $part_2 .= '<tr><td width="100%"></td></tr>';
            $part_2 .= '<tr><td width="100%"></td></tr>';
            $part_2 .= '<tr><td width="100%"></td></tr>';

            $part_2 .= '<tr><td width="20%" style="border-width: 1px;border-color:gray gray gray gray" align="center" ><b>Nature of Injury</b></td><td width="20%" style="border-width: 1px;border-color:gray gray gray gray" align="center" ><b>Injured Bodypart</b></td><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="left" ><b>Exact description (state Left/Right body part)</b></td></tr>';
            if(!empty($data_5)){
                foreach($data_5 as $i => $j){
                    foreach($j['item'] as $x => $y){
                        if($x==0){
                            $part_2 .= '<tr><td width="20%" style="border-width: 1px;border-color:gray gray gray gray" align="center" >'.$y['item_value'].'</td>';
                        }else if($x == 1){
                            $part_2 .= '<td width="20%" style="border-width: 1px;border-color:gray gray gray gray" align="center" >'.$y['item_value'].'</td>';
                        }else if($x ==2){
                            $part_2 .= '<td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="left" >'.$y['item_value'].'</td></tr>';
                        }
                    }
                }
            }

            $part_2 .= '</table>';

            $part_3 = "<table style=\"border-width: 1px;border-color:gray gray black gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_3 .'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_f_title.'&nbsp;</td></tr>';
            $f_title_1 = $detail_list['ACC-006']['item_title_en'];
            $f_title_2 = $detail_list['ACC-007']['item_title_en'];
            $f_title_3 = $detail_list['ACC-008']['item_title_en'];
            $f_title_4 = $detail_list['ACC-009']['item_title_en'];
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_1.'</td></tr>';
            $part_3 .= '<tr><td height="100px;" width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['ACC-006']['item_value'].'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_2.'</td></tr>';
            $part_3 .= '<tr><td height="100px;" width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['ACC-007']['item_value'].'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_3.'</td></tr>';
            $part_3 .= '<tr><td height="100px;" width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['ACC-008']['item_value'].'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f_title_4.'</td></tr>';
            if($data_detail_list['ACC-009']['item_value']){
                $pic_list = explode('|',$data_detail_list['ACC-009']['item_value']);
                $f_i = 0;
                $pic_count = count($pic_list);
                $count = 0;
                foreach($pic_list as $i => $pic){
                    if(file_exists($pic)){
                        $img_html= '<img src="https://shell.cmstech.sg'.$pic.'" height="100" width="120" /> ';
                    }else{
                        $img_html = '';
                    }
                    $count++;
                    $f_i++;
                    $d_value = $pic_count - $count;
                    if($d_value >= 3){
                        if($f_i == 1){
                            $part_3 .= '<tr><td  width="34%" style="border-width: 1px;border-color:white white white gray" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 2){
                            $part_3 .= '<td  width="33%" style="border-width: 1px;border-color:white white white white" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 3){
                            $part_3 .= '<td  width="33%" style="border-width: 1px;border-color:white gray white white" align="left" >&nbsp;&nbsp;'.$img_html.'</td></tr>';
                            $f_i = 0;
                        }
                    }else{
                        if($f_i == 1){
                            $part_3 .= '<tr><td  width="34%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 2){
                            $part_3 .= '<td  width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;'.$img_html.'</td>';
                        }else if($f_i == 3){
                            $part_3 .= '<td  width="33%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;'.$img_html.'</td></tr>';
                            $f_i = 0;
                        }
                    }
                }
                if($f_i != 3 && $f_i != 0){
                    $part_3 .= '</tr>';
                }
            }else{
                $part_3 .= '<tr><td  height="370px" width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;</td></tr>';
            }

            $part_3 .= '</table>';

            //PartG
            $g_list_1 = json_decode($detail_list['ACC-010']['item_data'],true);
            $data_list = $data_detail_list['ACC-010'];
            $value_list = explode(',',$data_list['item_value']);
            $part_4 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_4 .'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title.'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title_1.'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_2.'&nbsp;</td></tr>';
            $g_i = 0;
            foreach ($g_list_1 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }

                if($g_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$data_detail_list['ACC-010']['item_value'].'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_3.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_2 = json_decode($detail_list['ACC-012']['item_data'],true);
            $data_list = $data_detail_list['ACC-012'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_2 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>&nbsp;&nbsp;&nbsp;'.$data_detail_list['ACC-013']['item_value'].'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title_4.'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_5.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_3 = json_decode($detail_list['ACC-014']['item_data'],true);
            $data_list = $data_detail_list['ACC-014'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_3 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ACC-015']['item_value'].'</td></tr>';
            $part_4 .= '</table>';

            $part_5 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_5 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_5 .'</td></tr>';
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_g_title_6.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_4 = json_decode($detail_list['ACC-016']['item_data'],true);
            $data_list = $data_detail_list['ACC-016'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_4 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_5 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_5 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_5 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ACC-017']['item_value'].'</td></tr>';
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title_7.'&nbsp;</td></tr>';
            $g_i = 0;
            $g_list_5 = json_decode($detail_list['ACC-018']['item_data'],true);
            $data_list = $data_detail_list['ACC-018'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($g_list_5 as $item => $value){
                $g_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($g_i == 1){
                    $part_5 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($g_i == 2){
                    $part_5 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $g_i = 0;
                }
            }
            if($g_i == 1){
                $part_5 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ACC-019']['item_value'].'</td></tr>';
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_h_title.'&nbsp;</td></tr>';
            $part_5 .= '<tr><td width="100%" height="380" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['ACC-020']['item_value'].'&nbsp;</td></tr>';
            $part_5 .= '</table>';

            $apply_user_model = Staff::model()->findByPk($apply_user_id);
            $part_6 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_6 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_6 .'</td></tr>';
            $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_j_title.'&nbsp;</td></tr>';
            if(array_key_exists('submitted',$accident_detail)) {
                $submitted_user_model = Staff::model()->findByPk($accident_detail['submitted']);
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: ' . $submitted_user_model->user_name . '</td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: ' . $role_list[$submitted_user_model->role_id] . '</td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: ' . $company_list[$submitted_user_model->contractor_id] . '</td></tr>';
                if($submitted_user_model->signature_path){
                    $submitted_sign_html= '<img src="'.$submitted_user_model->signature_path.'" height="30" width="30"  />';
                }else{
                    $submitted_sign_html= $checker_pic_html;
                }
                $part_6 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : ' . $submitted_user_model->user_phone . $checker_pic_html . '</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : ' . Utils::DateToEn($accident_detail['submitted_time']) . $checker_pic_html . '</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : ' . $submitted_sign_html . '</td></tr>';
            }else{
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                $part_6 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
            }
            $background_path = 'img/background.jpg';
            $checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';
            $apply_sign_html= '<img src="'.$apply_user_model->signature_path.'" height="30" width="30"  /> ';
            $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_k_title.'&nbsp;</td></tr>';
            if(array_key_exists('reviewd',$accident_detail)){
                $reviewd_user_model = Staff::model()->findByPk($accident_detail['reviewd']);
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: '.$reviewd_user_model->user_name.'  </td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION:  '.$role_list[$reviewd_user_model->role_id].'</td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY:  '.$company_list[$reviewd_user_model->contractor_id].' </td></tr>';
                if($reviewd_user_model->signature_path){
                    $reviewd_sign_html= '<img src="'.$reviewd_user_model->signature_path.'" height="30" width="30"  />';
                }else{
                    $reviewd_sign_html= $checker_pic_html;
                }
                $part_6 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL :  '.$reviewd_user_model->user_phone.$checker_pic_html.'</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE :  '.Utils::DateToEn($accident_detail['reviewd_time']).$checker_pic_html.'</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN :  '.$reviewd_sign_html.' </td></tr>';
            }else{
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                $part_6 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
            }

            if($accident_list->company_type == '0' || $accident_list->company_type == '01'){
                $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_l_title.'&nbsp;</td></tr>';
                if(array_key_exists('endorsed',$accident_detail)){
                    $endorsed_user_model = Staff::model()->findByPk($accident_detail['endorsed']);
                    $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: '.$endorsed_user_model->user_name.'  </td></tr>';
                    $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION:  '.$role_list[$endorsed_user_model->role_id].'</td></tr>';
                    $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY:  LTA </td></tr>';
                    if($endorsed_user_model->signature_path){
                        $endorsed_sign_html= '<img src="'.$endorsed_user_model->signature_path.'" height="30" width="30"  />';
                    }else{
                        $endorsed_sign_html= $checker_pic_html;
                    }
                    $part_6 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL :  '.$endorsed_user_model->user_phone.$checker_pic_html.'</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE :  '.Utils::DateToEn($accident_detail['endorsed_time']).$checker_pic_html.'</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN :  '.$endorsed_sign_html.' </td></tr>';
                }else{
                    $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                    $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                    $part_6 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                    $part_6 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
                }
            }


            $part_6 .= '</table>';

            $pdf->writeHTML($part, true, false, true, false, '');

            $pdf->AddPage();

            $pdf->writeHTML($part_2, true, false, true, false, '');
//
            $pdf->AddPage();

            $pdf->writeHTML($part_3, true, false, true, false, '');
//
            $pdf->AddPage();

            $pdf->writeHTML($part_4, true, false, true, false, '');

            $pdf->AddPage();

            $pdf->writeHTML($part_5, true, false, true, false, '');

            $pdf->AddPage();

            $pdf->writeHTML($part_6, true, false, true, false, '');
        }else{
            $acci_type_list = AccidentType::ListByAccitype('ENV');
            $title_left = "<b>ENVIRONMENTAL INCIDENT OCCURRENCE REPORT FORM</b>";
            $title_right = "<b>OUR REF NO :  $id</b><br> Page 1 of 5";
            $title_right_2 = "<b> Page 2 of 5</b>";
            $title_right_3 = "<b> Page 3 of 5</b>";
            $title_right_4 = "<b> Page 4 of 5</b>";
            $title_right_5 = "<b> Page 5 of 5</b>";
            $part_a_title = "<b>PART A (Type of Incident)</b>";
            $part_b_title = "<b>PART B (Details of Incident)</b>";
            $part_c_title = "<b>PART C (Persons involved in the Incident)</b>";
            $part_d_title = "<b>PART D (Description of Incident)</b>";
            $part_e_title = "<b>PART E (Causes of Accident)</b>";
            $part_e_title_1 = "<b>(1)Direct Causes</b>";
            $part_e_title_2 = "<b>(i) Non-Complying Environmental Management Conditions</b>";
            $part_e_title_3 = "<b>(ii) Non-Complying Environmental Management Practice</b>";
            $part_e_title_4 = "<b>(2)Root Causes</b>";
            $part_e_title_5 = "<b>(i) Work Factors</b>";
            $part_e_title_6 = "<b>(ii) Human Factors</b>";
            $part_e_title_7 = "<b>(3)Weakness of Safety Management System</b>";
            $part_f_title = "<b>PART F (Corrective Action Taken)</b>";
            $part_f_title_1 = "RECOMMENDATION";
            $part_f_title_2 = "ACTION TAKEN";
            $part_g_title = "<b>PART G (Details of Investigating Person)</b>";
            $part_h_title = "<b>PART H (Reviewed By Contractor’s PM)</b>";
            $part_i_title = "<b>PART I (Endorsed By LTA SPM or Senior Project Staff)</b>";
            $part = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray " align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:white gray gray gray"  align="left" > ' . $title_right .'</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;<b>Incident Title</b>  '.$accident_list->title.'&nbsp;</td></tr>';
            $part .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray " align="center">&nbsp;&nbsp;Name of Main Contractor:  '.$company_list[$contractor_id].'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:white gray gray gray"  align="center" > &nbsp;Ref No: ' . $accident_list->apply_id .'</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_a_title.'&nbsp;</td></tr>';
            $u = 0;
            foreach($acci_type_list as $x => $y){
                $u++;
                if($type_id == $y['type_id']){
                    $check_tag = $checked_img_html;
                }else{
                    $check_tag = $unchecked_img_html;
                }
                if($u == 1){
                    $part .= '<tr><td width="33%"  align="left" >&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td>';
                }else if($u ==2){
                    if($y['type_id'] == 'ENV999'){
                        $part .= '<td width="67%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'  '.$type_name_en.'</td></tr>';
                    }else{
                        $part .= '<td width="33%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td>';
                    }

                }else if($u ==3){
                    $part .= '<td width="34%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.$y['type_name_en'].'</td></tr>';
                    $u = 0;
                }
            }
//            if($u == 2){
//                if($y['type_id'] == 'ENV999'){
//                    $part .= '<td width="34%"  align="left" >'.$type_name_en.'</td></tr>';
//                }else{
//                    $part .= '<td width="34%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>';
//                }
//            }
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_b_title.'&nbsp;</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'PROJECT: '.$pro_model->program_name.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'CONTRACT: '.$apply_user_model->user_phone.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" colspan="2">&nbsp;&nbsp;'.'EXACT LOCATION: '.$accident_list->acci_location.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'OCCURRENCE DATE: '.$occurrence_date.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'OCCURRENCE TIME: '.$occurrence_time.'</td></tr>';
            $part .= '<tr><td width="50%"  align="left" >&nbsp;&nbsp;'.'REPORTED DATE: '.$reported_date.'</td><td width="50%"  align="left" >&nbsp;&nbsp;&nbsp;&nbsp;'.'REPORTED TIME: '.$reported_time.'</td></tr>';
            $part .= '<tr><td width="100%"  align="left" >&nbsp;&nbsp;'.'COMPANY RESPONSIBLE FOR INCIDENT:  '.$company_list[$contractor_id].'</td></tr>';
            $part .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray white " align="left" colspan="2">&nbsp;&nbsp;'.$part_c_title.'&nbsp;</td></tr>';
            $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;A</td><td width="25%"  align="center" >&nbsp;&nbsp;B</td><td width="25%"  align="center" >&nbsp;&nbsp;C</td></tr>';
            if(!empty($accident_staff)){
//                var_dump($accident_staff);
//                exit;
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;NAME</td><td width="25%"  align="center" >&nbsp;&nbsp;'.str_replace('  √','',$accident_staff[0]['user_name']).'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[1]['user_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[2]['user_name'].'</td></tr>';
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;DESIGNATION :</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[0]['role_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[1]['role_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[2]['role_name'].'</td></tr>';
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;COMPANY :</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[0]['company_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[1]['company_name'].'</td><td width="25%"  align="center" >&nbsp;&nbsp;'.$accident_staff[2]['company_name'].'</td></tr>';
                $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;<b>Status (you may tick more than one) :</b>&nbsp;</td></tr>';
                $part .= '<tr><td  width="5%"></td><td width="10%">Witness</td><td  width="10%">Incident Reporter</td><td  width="10%">LTA Personnel</td><td width="10%"> Main Contractor\'s Personnel</td><td  width="10%">Subcontractor\'s Personnel</td><td width="10%" >Visitor</td><td width="10%" >Public</td><td width="10%" >Self Employed</td><td width="15%" >Others :</td></tr>';
                $accident_staff_1 = json_decode($accident_staff[0]['staff_data'],true);
                if(!empty($accident_staff_1)){
                    $part .= '<tr><td  width="5%">A</td>';
                    foreach($accident_staff_1 as $x => $y){
                        if($y["item_check"] == '1'){
                            $check_tag = $checked_img_html;
                        }else{
                            $check_tag = $unchecked_img_html;
                        }
                        if($y['item_name'] == 'Others :'){
                            $item_remark = $y["item_remarks"];
                            $part .= '<td width="15%">'.$check_tag.$item_remark.'</td>';
                        }else{
                            $part .= '<td width="10%">'.$check_tag.'</td>';
                        }
                    }
                    $part .='</tr>';
                }else{
                    $part .= '<tr><td  width="5%">A</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="15%" >'.$unchecked_img_html.'</td></tr>';
                }
                $accident_staff_2 = json_decode($accident_staff[1]['staff_data'],true);
                if(!empty($accident_staff_2)){
                    $part .= '<tr><td  width="5%">B</td>';
                    foreach($accident_staff_2 as $x => $y){
                        if($y["item_check"] == '1'){
                            $check_tag = $checked_img_html;
                        }else{
                            $check_tag = $unchecked_img_html;
                        }
                        if($y['item_name'] == 'Others :'){
                            $item_remark = $y["item_remarks"];
                            $part .= '<td width="15%">'.$check_tag.$item_remark.'</td>';
                        }else{
                            $part .= '<td width="10%">'.$check_tag.'</td>';
                        }
                    }
                    $part .='</tr>';
                }else{
                    $part .= '<tr><td  width="5%">B</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="15%" >'.$unchecked_img_html.'</td></tr>';
                }

                $accident_staff_3 = json_decode($accident_staff[2]['staff_data'],true);
                if(!empty($accident_staff_3)){
                    $part .= '<tr><td  width="5%">C</td>';
                    foreach($accident_staff_3 as $x => $y){
                        if($y["item_check"] == '1'){
                            $check_tag = $checked_img_html;
                        }else{
                            $check_tag = $unchecked_img_html;
                        }
                        if($y['item_name'] == 'Others :'){
                            $item_remark = $y["item_remarks"];
                            $part .= '<td width="15%">'.$check_tag.$item_remark.'</td>';
                        }else{
                            $part .= '<td width="10%">'.$check_tag.'</td>';
                        }
                    }
                    $part .='</tr>';
                }else{
                    $part .= '<tr><td  width="5%">C</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="15%" >'.$unchecked_img_html.'</td></tr>';
                }
            }else{
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;NAME</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td></tr>';
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;DESIGNATION :</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td></tr>';
                $part .= '<tr><td width="25%"  align="center" >&nbsp;&nbsp;COMPANY :</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td><td width="25%"  align="center" >&nbsp;&nbsp;</td></tr>';
                $part .= '<tr><td width="100%"  align="left" colspan="2">&nbsp;&nbsp;<b>Status (you may tick more than one) :</b>&nbsp;</td></tr>';
                $part .= '<tr><td  width="10%"></td><td width="10%">Witness</td><td  width="10%">Incident Reporter</td><td  width="10%">LTA Personnel</td><td width="10%"> Main Contractor\'s Personnel</td><td  width="10%">Subcontractor\'s Personnel</td><td width="10%" >Visitor</td><td width="10%" >Public</td><td width="10%" >Self Employed</td><td width="10%" >Others :</td></tr>';
                $part .= '<tr><td  width="10%">A</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td></tr>';
                $part .= '<tr><td  width="10%">B</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td></tr>';
                $part .= '<tr><td  width="10%">C</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%">'.$unchecked_img_html.'</td><td  width="10%">'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td><td width="10%" >'.$unchecked_img_html.'</td></tr>';
            }
            $part .= '</table>';
            $part_2 = "<table style=\"border-width: 1px;border-color:gray gray black gray\">";
//            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_2 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_2 .'</td></tr>';
            $part_2 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_d_title.'&nbsp;</td></tr>';
            $part_2 .= '<tr><td height="600px;" width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$data_detail_list['ENV-001']['item_value'].'</td></tr>';

            $part_2 .= '</table>';
//            var_dump($part);
//            exit;

            //PartE
            $e_list_1 = json_decode($detail_list['ENV-002']['item_data'],true);
            $part_3 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_3 .'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_e_title.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_e_title_1.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_e_title_2.'&nbsp;</td></tr>';
            $e_i = 0;
            $data_list = $data_detail_list['ENV-002'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($e_list_1 as $item => $value){
                $e_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($e_i == 1){
                    $part_3 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($e_i == 2){
                    $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $e_i = 0;
                }
            }
            if($e_i == 1){
                $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ENV-003']['item_value'].'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_e_title_3.'&nbsp;</td></tr>';
            $e_i = 0;
            $e_list_2 = json_decode($detail_list['ENV-004']['item_data'],true);
            $data_list = $data_detail_list['ENV-004'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($e_list_2 as $item => $value){
                $e_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($e_i == 1){
                    $part_3 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($e_i == 2){
                    $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $e_i = 0;
                }
            }
            if($e_i == 1){
                $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ENV-005']['item_value'].'</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_e_title_4.'&nbsp;</td></tr>';
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_e_title_5.'&nbsp;</td></tr>';
            $e_i = 0;
            $e_list_3 = json_decode($detail_list['ENV-006']['item_data'],true);
            $data_list = $data_detail_list['ENV-006'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($e_list_3 as $item => $value){
                $e_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($e_i == 1){
                    $part_3 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($e_i == 2){
                    $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $e_i = 0;
                }
            }
            if($e_i == 1){
                $part_3 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ENV-007']['item_value'].'</td></tr>';
            $part_3 .= '</table>';
            $part_4 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_4 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_4 .'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;'.$part_e_title_6.'&nbsp;</td></tr>';
            $e_i = 0;
            $e_list_4 = json_decode($detail_list['ENV-008']['item_data'],true);
            $data_list = $data_detail_list['ENV-008'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($e_list_4 as $item => $value){
                $e_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($e_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($e_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $e_i = 0;
                }
            }
            if($e_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ENV-009']['item_value'].'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_e_title_7.'&nbsp;</td></tr>';
            $e_i = 0;
            $e_list_5 = json_decode($detail_list['ENV-010']['item_data'],true);
            $data_list = $data_detail_list['ENV-010'];
            $value_list = explode(',',$data_list['item_value']);
            foreach ($e_list_5 as $item => $value){
                $e_i++;
                $check_tag = $unchecked_img_html;
                foreach($value_list as $x => $y){
                    if($value == $y){
                        $check_tag = $checked_img_html;
                    }
                }
                if($e_i == 1){
                    $part_4 .= '<tr><td width="8%" style="border-width: 1px;border-color:white white gray gray" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white white gray white" align="left" >'.$value.'</td>';
                }else if($e_i == 2){
                    $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;'.$check_tag.'</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" >'.$value.'</td></tr>';
                    $e_i = 0;
                }
            }
            if($e_i == 1){
                $part_4 .= '<td width="8%" style="border-width: 1px;border-color:white white gray white" align="right" >&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="42%" style="border-width: 1px;border-color:white gray gray white" align="left" ></td></tr>';
            }
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remarks :&nbsp;<br>'.$data_detail_list['ENV-011']['item_value'].'</td></tr>';
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_f_title.'&nbsp;</td></tr>';

            $e_list_6 = json_decode($data_detail_list['ENV-012']['item_value'],true);

            $e_list_7 = json_decode($detail_list['ENV-012-02']['item_data'],true);
            $table = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
            $table .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\">$part_f_title_1</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray;\">$part_f_title_2</td></tr>";
            $f_list = array();
            $e_index = 0;
            if(!empty($e_list_6)){
                foreach ($e_list_6 as $x => $value){
                    foreach($value as $y => $list){
                        foreach($list as $i => $j){
                            if($j['item_id'] == 'ENV-012-01'){
                                $f_list[$e_index]['RECOMMENDATION'] = $j['item_value'];
                            }
                            if($j['item_id'] == 'ENV-012-02'){
                                $f_list[$e_index]['ACTIONTAKEN'] = $j['item_value'];
                            }
                        }
                        $e_index++;
                    }
                }
            }
            if(!empty($f_list)){
                foreach($f_list as $q => $w){
                    $RECOMMENDATION = $w['RECOMMENDATION'];
                    $ACTIONTAKEN = $w['ACTIONTAKEN'];
                    $table .="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\">$RECOMMENDATION</td><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\">$ACTIONTAKEN</td></tr>";
                }
            }else{
                $table .="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td></tr>";
                $table .="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td></tr>";
                $table .="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td></tr>";
                $table .="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td></tr>";
                $table .="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\"></td></tr>";
            }

            $table .="</table>";
            $part_4 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray gray gray" align="left" colspan="2">&nbsp;&nbsp;'.$table.'&nbsp;</td></tr>';
            $part_4 .= '</table>';
//            var_dump($part_4);
//            exit;
            $apply_user_model = Staff::model()->findByPk($apply_user_id);
            $apply_sign_html= '<img src="'.$apply_user_model->signature_path.'" height="30" width="30"  /> ';
            $part_5 = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//            $part_3 .= '<tr><td width="100%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$logo_img_html.'&nbsp;</td></tr>';
            $part_5 .= '<tr><td width="60%" style="border-width: 1px;border-color:gray gray gray gray" align="center">&nbsp;&nbsp;'.$title_left.'&nbsp;</td><td width="40%" style="border-width: 1px;border-color:gray gray gray gray "  align="left" > ' . $title_right_5 .'</td></tr>';
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_g_title.'&nbsp;</td></tr>';
            if(array_key_exists('submitted',$accident_detail)) {
                $submitted_user_model = Staff::model()->findByPk($accident_detail['submitted']);
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: ' . $submitted_user_model->user_name . '</td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: ' . $role_list[$submitted_user_model->role_id] . '</td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: ' . $company_list[$submitted_user_model->contractor_id] . '</td></tr>';
                $submitted_sign_html= '<img src="'.$submitted_user_model->signature_path.'" height="30" width="30"  /> ';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : ' . $submitted_user_model->user_phone . $checker_pic_html . '</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : ' . Utils::DateToEn($accident_detail['submitted_time']) . $checker_pic_html . '</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : ' . $submitted_sign_html . '</td></tr>';
            }else{
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
            }
            $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;' . $part_h_title . '&nbsp;</td></tr>';

            if(array_key_exists('reviewd',$accident_detail)){
                $reviewd_user_model = Staff::model()->findByPk($accident_detail['reviewd']);
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: '.$reviewd_user_model->user_name.' </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: '.$role_list[$reviewd_user_model->role_id].'</td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: '.$company_list[$reviewd_user_model->contractor_id].' </td></tr>';
                $reviewd_sign_html= '<img src="'.$reviewd_user_model->signature_path.'" height="30" width="30"  /> ';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : '.$reviewd_user_model->user_phone.$checker_pic_html.'</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : '.Utils::DateToEn($accident_detail['reviewd_time']).$checker_pic_html.'</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : '.$reviewd_sign_html.' </td></tr>';
            }else{
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
            }

            if($accident_list->company_type == '0' || $accident_list->company_type == '01'){
                $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" colspan="2">&nbsp;&nbsp;'.$part_i_title.'&nbsp;</td></tr>';

                if(array_key_exists('endorsed',$accident_detail)){
                    $endorsed_user_model = Staff::model()->findByPk($accident_detail['endorsed']);
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: '.$endorsed_user_model->user_name.'  </td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION:  '.$role_list[$endorsed_user_model->role_id].'</td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY:  LTA </td></tr>';
                    $endorsed_sign_html= '<img src="'.$endorsed_user_model->signature_path.'" height="30" width="30"  /> ';
                    $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL :  '.$endorsed_user_model->user_phone.$checker_pic_html.'</td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE :  '.Utils::DateToEn($accident_detail['endorsed_time']).$checker_pic_html.'</td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN :  '.$endorsed_sign_html.' </td></tr>';
                }else{
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;NAME: </td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;DESIGNATION: </td></tr>';
                    $part_5 .= '<tr><td width="100%" style="border-width: 1px;border-color:white gray white gray" align="left" >&nbsp;&nbsp;COMPANY: </td></tr>';
                    $part_5 .= '<tr><td width="33%" style="border-width: 1px;border-color:white white gray gray" align="left" >&nbsp;&nbsp;TEL : </td><td width="33%" style="border-width: 1px;border-color:white white gray white" align="left" >&nbsp;&nbsp;DATE : </td><td width="34%" style="border-width: 1px;border-color:white gray gray white" align="left" >&nbsp;&nbsp;SIGN : </td></tr>';
                }
            }

            $part_5 .= '</table>';

            $pdf->writeHTML($part, true, false, true, false, '');

            $pdf->AddPage();

            $pdf->writeHTML($part_2, true, false, true, false, '');
//
            $pdf->AddPage();

            $pdf->writeHTML($part_3, true, false, true, false, '');
//
            $pdf->AddPage();

            $pdf->writeHTML($part_4, true, false, true, false, '');

            $pdf->AddPage();

            $pdf->writeHTML($part_5, true, false, true, false, '');
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
            $sql = "select count(apply_id) as cnt,apply_contractor_id from accident_basic  where  record_time like '".$month."%' and root_proid ='".$root_proid."' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY apply_contractor_id";
        }else{
            //总包项目
            $sql = "select count(apply_id) as cnt,apply_contractor_id from accident_basic  where  record_time like '".$month."%' and root_proid ='".$args['program_id']."'   GROUP BY apply_contractor_id";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;

        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['apply_contractor_id']];

            }
        }
        return $r;
//        var_dump($r);
//        exit;
    }
}
