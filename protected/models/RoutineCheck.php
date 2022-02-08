<?php

/**
 * 例行检查
 * @author LiuMinchao
 */
class RoutineCheck extends CActiveRecord {

    //状态：0-进行中，1－已接收，2-已拒绝。
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_RECEIVE = '1'; //已接收
    const STATUS_REJECT = '2'; //已拒绝
    const RESULT_YES = 0;
    const RESULT_NO = 2;
    const RESULT_NA = 1;

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_routine_check';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_ONGOING => Yii::t('comp_routine', 'STATUS_ONGOING'),
            self::STATUS_RECEIVE => Yii::t('comp_routine', 'STATUS_RECEIVE'),
            self::STATUS_REJECT => Yii::t('comp_routine', 'STATUS_REJECT'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_ONGOING => 'label-info', //进行中
            self::STATUS_RECEIVE => 'label-success', //已接收
            self::STATUS_REJECT => 'label-danger', //已拒绝
        );
        return $key === null ? $rs : $rs[$key];
    }
    //检查条件
    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => 'YES',
            self::RESULT_NO => 'NO',
            self::RESULT_NA => 'N/A',
        );
        return $key == null ? $rs : $rs[$key];
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {
        //var_dump($args);
//        $condition = 'root_proid != "" AND root_proname !="" ';
        $condition = 'root_proid != "" ';
        $params = array();

        //申请人
        if($args['apply_name'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['apply_name']));
            if($model) {
                $initiator = $model->user_id;
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = $initiator;
            }else{
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = '';
            }
        }
        //Apply
        if ($args['check_id'] != '') {
            $condition.= ( $condition == '') ? ' check_id=:check_id ' : ' AND check_id=:check_id';
            $params['check_id'] = $args['check_id'];
        }
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $root_proid = $pro_model->root_proid;
                $params['program_id'] = $root_proid;
//                $params['program_id'] = $args['program_id'];
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
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
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $params['program_id'] = $program_id;
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $params['program_id'] = $program_id;
            }
        }
        //Type_id
        if($args['type_id'] != ''){
            $condition.= ( $condition == '') ? ' type_id =:type_id' : ' AND type_id =:type_id';
            $params['type_id'] = $args['type_id'];
        }
        //Contractor
        if ($args['con_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['con_id'];
        }
        //Apply Time
//        if ($args['record_time'] != '') {
//            $args['record_time'] = Utils::DateToCn($args['record_time']);
//            $condition.= ( $condition == '') ? ' apply_time LIKE :record_time' : ' AND apply_time LIKE :record_time';
//            $params['record_time'] = '%'.$args['record_time'].'%';
//        }
        //操作开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' apply_time >=:start_date' : ' AND apply_time >=:start_date';
            $params['start_date'] = Utils::DateToCn($args['start_date']);
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' apply_time <=:end_date' : ' AND apply_time <=:end_date';
            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        }
//        //关闭时间(起始)
//        if ($args['start_date'] != ''){
//            $condition.= ( $condition == '') ? ' apply_time >=:start_date ' : ' AND apply_time >=:start_date';
//            $params['start_date'] = $args['start_date'];
//        }
//        //关闭时间(截止)
//        if ($args['end_date'] != ''){
//            $condition.= ( $condition == '') ? ' apply_time <=:end_date ' : ' AND apply_time <=:end_date';
//            $params['end_date'] = $args['end_date'];
//        }


        $total_num = RoutineCheck::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'apply_time desc';
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
        //var_dump($criteria);
        $rows = RoutineCheck::model()->findAll($criteria);
//        var_dump($rows);

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
    public static function queryContractorList($page, $pageSize, $args = array()) {
        //var_dump($args);
        $condition = 'root_proid = "" AND root_proname ="" ';
        $params = array();

        //Apply
        if ($args['check_id'] != '') {
            $condition.= ( $condition == '') ? ' check_id=:check_id ' : ' AND check_id=:check_id';
            $params['check_id'] = $args['check_id'];
        }

        //Contractor
        if ($args['contractor_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        $total_num = RoutineCheck::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'apply_time desc';
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
        //var_dump($criteria);
        $rows = RoutineCheck::model()->findAll($criteria);
//        var_dump($rows);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 个人违规查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByUser($page, $pageSize, $args = array()) {
        //var_dump($args);
        $condition = '';
        $params = array();

        //Apply
        if ($args['check_id'] != '') {
            $condition .= 'check_id IN ('.$args['check_id'].')';
            //$params['program_id'] = '('.$args['program_id'].')';
        }

        //Contractor
        if ($args['contractor_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        $total_num = SafetyCheck::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'apply_time desc';
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
        //var_dump($criteria);
        $rows = SafetyCheck::model()->findAll($criteria);
//        var_dump($rows);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //查询安全单
    public static function detailList($check_id){

        $sql = "SELECT * FROM bac_routine_check WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }

    //根据时间日期查询安全单
    public static function quertListByTime($args){
        $contractor_id = Yii::app()->user->contractor_id;
        $sql = "SELECT * FROM bac_safety_check WHERE  contractor_id = '".$contractor_id."' AND apply_time >= '".$args['start_date']."' AND apply_time <= '".$args['end_date']."' AND status != 0";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //修改路径
    public static function updatePath($check_id,$save_path) {
        $save_path = substr($save_path,18);
        $sql = "update bac_routine_check set save_path = '".$save_path."' where check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }
    //下载PDF
    public static function downloadPdf($params,$app_id){
        $check_id = $params['check_id'];
//        $a = SafetyCheckDetail::detailAllList();
        $check_list = RoutineCheck::detailList($check_id);//例行检查单
        $detail_list = RoutineCheckDetail::detailList($check_id);//例行检查单详情
        $check_type = RoutineCheckType::checkTypeByReport();//检查类型列表
        $check_kind = RoutineCheckType::checkKind();//检查种类列表
        $check_module = RoutineCheckType::checkModule();//检查单类别
        $record_list = ViolationRecord::recordList($check_id);//违规记录
        $company_list = Contractor::compAllList();//承包商公司列表
        $deal_list = SafetyCheckDetail::dealList();//处理类型列表
//        $staff_list = Staff::userAllList();//所有人员列表
        $region_list = RoutineCheckBlock::regionList($check_id);//PTW项目区域

        $title = $check_list[0]['title'];//标题
        $root_proid = $check_list[0]['root_proid'];//总包项目ID
        $pro_model = Program::model()->findByPk($root_proid);
        $pro_params = $pro_model->params;//项目参数
        $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
        $apply_user =  Staff::model()->findAllByPk($apply_user_id);//申请人
        $apply_contractor_id = $apply_user[0]['contractor_id'];//申请人公司
        $pro_contractor_id = $pro_model->contractor_id;//总包公司
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                if($apply_contractor_id == $pro_contractor_id){
                    $contractor_id = $pro_params['transfer_con'];
                    $apply_contractor_id = $pro_params['transfer_con'];;
                }else{
                    $contractor_id = $check_list[0]['contractor_id'];
                }
            } else {
                $contractor_id = $check_list[0]['contractor_id'];
            }
        }else{
            $contractor_id = $check_list[0]['contractor_id'];
        }

        $contractor_name = $company_list[$contractor_id];//承包商名称
        if(count($detail_list) > 1){
            $person_in_charge_id = $detail_list[1]['deal_user_id'];//负责人ID
            $person_in_charge = Staff::model()->findAllByPk($person_in_charge_id);//负责人
        }else{
            $person_in_charge[0]['signature_path'] = '';
            $person_in_charge[0]['user_name'] = '';
        }
        $apply_time = $check_list[0]['apply_time'];//申请时间
        $violations_user = '';
        foreach($record_list as $n => $m){
            $staff_model = Staff::model()->findAllByPk($m['user_id']);
            $violations_user .= '  '.$staff_model->user_name;
        }
//        var_dump($violations_user);
//        exit;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply_time,0,4);//年
        $month = substr($apply_time,5,2);//月
        $day = substr($apply_time,8,2);//日
        $hours = substr($apply_time,11,2);//小时
        $minute = substr($apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        $program_id = $check_list[0]['root_proid'];
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/routine/'.$contractor_id.'/Routine' . $check_id . $time .'.pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/routine/'.$contractor_id;

//            $filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/routine'.'/Routine' . $check_id . '.pdf';
//            $full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/routine';
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        RoutineCheck::updatepath($check_id,$filepath);


//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Checklist No. (检查单编号): ' . $check_id; // 把username存在$_SESSION['user'] 里面
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
        $pdf->SetLineWidth(0.1);
//        $user_list = Staff::allInfo();//员工信息（包括已被删除的）

        $operator_id = $apply_user[0]['user_id'];//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_first_time = Utils::DateToEn(substr($apply_time,0,10));
        $apply_second_time = substr($apply_time,11,18);
        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size:300%;\" align=\"center\">{$contractor_name}</h1><h3 style=\"font-size: 200%\" align=\"center\">Project (项目) : {$check_list[0]['root_proname']}</h3><br/>";
        $pdf->writeHTML($title_html, true, false, true, false, '');
        $title_y = $pdf->GetY();
        //申请人资料
        $apply_info_html = "<br/><br/><h2 align=\"center\"> Applicant Details (申请人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name (姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation (职位)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($add_operator->work_pass_type = 'IC' || $add_operator->work_pass_type = 'PR'){
            if(substr($add_operator->work_no,0,1) == 'S' && strlen($add_operator->work_no) == 9){
                $work_no = 'SXXXX'.substr($add_operator->work_no,5,8);
            }else{
                $work_no = $add_operator->work_no;
            }
        }else{
            $work_no = $add_operator->work_no;
        }
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$add_operator->user_name}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$roleList[$add_role]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Application (申请时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$company_list[$apply_contractor_id]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;{$apply_first_time}  {$apply_second_time}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";

        $module = $check_list[0]['device_id'];//检查单类别

        $primary_list = Device::primaryAllList();
        $primary_id = $check_list[0]['device_id'];
        //工作内容
        $receive_time = Utils::DateToEn($check_list[0]['receive_time']);

        $work_content_html = "<br/><br/><h2 align=\"center\">Checklist Details (检查单详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
//        $work_content_html .="<tr><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Title (标题)</td><td width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Device name (设备名称)</td></tr>";
//        $work_content_html .="<tr><td height=\"120px\" align=\"center\">{$title}</td><td align=\"center\">{$check_list[0]['device_name']}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Checklist Type (检查单类型)</td><td width=\"50%\"  nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Work Location (工作地点)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$check_type[$check_list[0]['type_id']]}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$check_list[0]['address']}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Received By (接收人)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td><td width=\"33%\"  nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Received Date (接收日期)</td></tr>";
        $incharge_sign_html = '<img src="' . $person_in_charge[0]['signature_path'] . '" height="30" width="30"  />';
        $work_content_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$person_in_charge[0]['user_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$incharge_sign_html}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$receive_time}</td></tr></table>";


        if($module && $module != 'nil'){

            $device_model = Device::model()->findByPk($check_list[0]['device_id']);
//            var_dump($check_id);
//            var_dump($check_list[0]['device_id']);
//            var_dump($device_model);
//            exit;
            $type_no = $device_model->type_no;
            $device_id = $device_model->device_id;
            $devicetype_model = DeviceType::model()->findByPk($type_no);//设备类型信息
            $device_type_ch = $devicetype_model->device_type_ch;
            $device_type_en = $devicetype_model->device_type_en;
            $equipment_html = "<br/><br/><h2 align=\"center\">Equipment (设备)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
            $equipment_html .="<tr><td height=\"20px\" width=\"10%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">S/N<br>(序号)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Registration No.<br>(设备编码)</td><td height=\"20px\" width=\"30%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Name (设备名称)</td><td width=\"30%\"  nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Equipment Type (设备类型)</td></tr>";
            $equipment_html .="<tr><td height=\"50px\" style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">1</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$device_id}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$check_list[0]['device_name']}</td><td style=\"text-align: center;border-width: 1px;border-color:gray gray gray gray\">{$device_type_en}<br>{$device_type_ch}</td></tr></table>";
        }



//        $content = $user_list[$person_in_charge_id]['signature_path'];
//        $pic = 'C:\Users\minchao\Desktop\5.png';
        $content_list = Staff::model()->findAllByPk($operator_id);
        $content = $content_list[0]['signature_path'];
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        if($content != '' && $content != 'nil' && $content != '-1') {
            if(file_exists($content)) {
                $pdf->Image($content, 140, $title_y +50, 32, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            }
        }

        $region_html = '<br/><br/><h2 align="center">Project Area (项目区域)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width=\'100%\' bgcolor="#E5E5E5" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;Work Location（工作地点）</td></tr>';
        if (!empty($region_list))
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td height="20px" style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        $region_html .= '</table>';
        if($module && $module != 'nil'){
//            var_dump(1);
            $html_1 = $apply_info_html . $work_content_html . $equipment_html .$region_html;
        }else{
//            var_dump(2);
            $html_1 = $apply_info_html . $work_content_html . $region_html;
        }
        $pdf->writeHTML($html_1, true, false, true, false, '');
        $pdf->AddPage();
        $pic = $check_list[0]['pic'];
        if ($pic != '-1' && $pic != '') {
            $info_y_2 = $pdf->GetY();
            //现场照片
            $info_x = 44;//X方向距离
            $pic_html = '<h2 align="center">Photo(s) (照片)</h2>';
            $pic_html.= '<table style="border-width: 1px;border-color:lightslategray lightslategray lightslategray lightslategray">
                <tr><td width ="100%" height="840px"></td></tr>';
            $pic_html .= '</table>';
            $pic = explode('|', $check_list[0]['pic']);
//            for($o=0;$o<=8;$o++){
//                $pic[$o] =  "/opt/www-nginx/web/filebase/record/2019/02/tbm/pic/tbm_1550054744258_1.jpg";
//            }
            $info_x_2 = 18;
            $toatl_width  =0;
            $ratio_width = 55;
            foreach ($pic as $key => $content) {

                if ($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)) {
                        if($toatl_width > 190){
                            $toatl_width = 0;
                            $info_x_2 = 18;
                            $info_y_2+=45+3;
                        }
                        $pdf->Image($content, $info_x_2, $info_y_2+12, 55, 45, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                        $info_x_2 += $ratio_width+3;
                        if($toatl_width == 0){
                            $toatl_width = $ratio_width;
                        }
                        $info_x += $ratio_width+3;
                        $toatl_width+=$ratio_width+3;
                    }
                }
            }
            $pdf->writeHTML($pic_html, true, false, true, false, '');
        }

        $condition_html = '<h2 align="center">Safety Conditions (安全条件)</h2><table style="border-width: 1px;border-color:gray gray gray gray"><tr><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td height="20px" width="75%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Conditions (安全条件)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">YES / NO / N/A</td></tr>';
        $condition_set = json_decode($check_list[0]['condition_list'], true);
//        var_dump($check_list[0]['condition_list']);
//        var_dump($condition_set);
//        exit;
        $resultText = RoutineCheck::resultText();
        $condition_list = RoutineCondition::conditionList();
        if(array_key_exists('condition_name',$condition_set[0])){
            foreach ($condition_set as $key => $row) {
                $condition_name = $row['condition_name'].'<br>'.$row['condition_name_en'];
                $condition_html .= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;' . ($key + 1) . '</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;' . $resultText[$row['flatStatus']] . '</td></tr>';
            }
        }else{
            if(array_key_exists('remarks',$condition_set[0])){
                foreach ($condition_set as $key => $row) {
                    if(array_key_exists($row['condition_id'],$condition_list)){
                        $name = $condition_list[$row['condition_id']]['condition_name'];
                        $name_en = $condition_list[$row['condition_id']]['condition_name_en'];
                    }else{
                        $name = '';
                        $name_en = '';
                    }
                    $condition_name = $name.'<br>'.$name_en;
//            $condition_name = $row['condition_name'].'<br>'.$row['condition_name_en'];
                    $condition_html .= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" rowspan="2">&nbsp;' . ($key + 1) . '</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" rowspan="2">&nbsp;' . $resultText[$row['flatStatus']] . '</td></tr>';
                    $condition_html .= '<tr><td height="20px" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Comment (诠释):' . $row['remarks'] . '</td></tr>';
                }
            }else{
                foreach ($condition_set as $key => $row) {
                    $name = $condition_list[$row['condition_id']]['condition_name'];
                    $name_en = $condition_list[$row['condition_id']]['condition_name_en'];
                    $condition_name = $name.'<br>'.$name_en;
//            $condition_name = $row['condition_name'].'<br>'.$row['condition_name_en'];
                    $condition_html .= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;' . $resultText[$row['flatStatus']] . '</td></tr>';
                    $condition_html .= '<tr><td height="20px" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Comment (诠释):</td></tr>';
                }
            }
        }

        $condition_html .= '</table>';

        $remark_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Remark (备注)</td></tr>";
        $remark_html .="<tr><td height=\"20px\" width='100%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$check_list[0]['remark']}</td></tr></table>";

        $html_2 = $condition_html . $remark_html;
        $pdf->writeHTML($html_2, true, false, true, false, '');

        //输出PDF
//        $pdf->Output($filepath, 'I');

//        $pdf->Output($filepath, 'D');
        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //按项目查询安全检查次数（按类别分组）
    public static function AllCntList($args){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $type_list = RoutineCheckType::typeByContractor($args['program_id']);
//        var_dump($args['date']);
//        var_dump($month);
        //分包项目
        if($args['contractor_id'] != '' && $pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,root_proid,type_id from bac_routine_check  where  root_proid = '".$root_proid."' and apply_time like '".$month."%' and contractor_id = '".$args['contractor_id']."'  GROUP BY type_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,root_proid,type_id from bac_routine_check  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."'  GROUP BY type_id";
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
            $sql = "select count(check_id) as cnt,contractor_id from bac_routine_check  where  apply_time like '".$month."%' and root_proid ='".$root_proid."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,contractor_id from bac_routine_check  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."'   GROUP BY contractor_id";
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
