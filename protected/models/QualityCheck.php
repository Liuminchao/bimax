<?php

/**
 * QA/QC安全检查
 * @author LiuMinchao
 */
class QualityCheck extends CActiveRecord {

    //状态：0-进行中，1－已关闭，2-超时强制关闭。
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_CLOSE = '1'; //已关闭
    const STATUS_TIMEOUT_CLOSE = '2'; //超时强制关闭


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_qaqc_basic';
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
            self::STATUS_ONGOING => Yii::t('comp_qaqc', 'STATUS_ONGOING'),
            self::STATUS_CLOSE => Yii::t('comp_qaqc', 'STATUS_CLOSE'),
            self::STATUS_TIMEOUT_CLOSE => Yii::t('comp_qaqc', 'STATUS_TIMEOUT_CLOSE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_ONGOING => 'label-info', //进行中
            self::STATUS_CLOSE => ' label-success', //已关闭
            self::STATUS_TIMEOUT_CLOSE => ' label-warning', //超时强制关闭
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
        //var_dump($args);
        $condition = '';
        $params = array();

        //Apply
        if ($args['check_id'] != '') {
            $condition.= ( $condition == '') ? ' check_id=:check_id ' : ' AND check_id=:check_id';
            $params['check_id'] = $args['check_id'];
        }
        //Assess_id
        if ($args['assess_id'] != '') {
            $condition.= ( $condition == '') ? ' assess_id=:assess_id ' : ' AND assess_id=:assess_id';
            $params['assess_id'] = $args['assess_id'];
        }
        //Item_id
        if ($args['Item_id'] != '') {
            $condition.= ( $condition == '') ? ' item_id=:item_id ' : ' AND item_id=:item_id';
            $params['item_id'] = $args['item_id'];
        }
        //Program Name
        if ($args['root_proname'] != '') {
            $condition.= ( $condition == '') ? ' root_proname=:root_proname ' : ' AND root_proname=:root_proname';
            $params['root_proname'] = $args['root_proname'];
        }

        //Contractor
        if ($args['con_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['con_id'];
        }
        //发起人
        if($args['initiator'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['initiator']));
            if($model) {
                $initiator = $model->user_id;
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = $initiator;
            }else{
                $condition.= ( $condition == '') ? ' apply_user_id =:apply_user_id ' : ' AND apply_user_id =:apply_user_id';
                $params['apply_user_id'] = '';
            }
        }
        //负责人
        if($args['person_in_charge'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_in_charge']));
            if($model) {
                $person_in_charge_id = $model->user_id;
                $condition.= ( $condition == '') ? ' person_in_charge_id =:person_in_charge_id ' : ' AND person_in_charge_id =:person_in_charge_id';
                $params['person_in_charge_id'] = $person_in_charge_id;
            }else{
                $condition.= ( $condition == '') ? ' person_in_charge_id =:person_in_charge_id ' : ' AND person_in_charge_id =:person_in_charge_id';
                $params['person_in_charge_id'] = '';
            }
        }
        //责任人
//        if($args['person_responsible'] !=''){
//            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['person_responsible']));
//            if($model) {
//                $person_responsible = $model->user_id;
//                $sql = 'SELECT check_id FROM bac_violation_record where user_id = '.$person_responsible.'';
//                $command = Yii::app()->db->createCommand($sql);
//                $rows = $command->queryAll();
//                $i = '';
//                foreach($rows as $n => $r){
//                    $i.=$r['check_id'].',';
//                }
//
//                if ($i != '')
//                    $check_id = substr($i, 0, strlen($i) - 1);
//                $condition.= ( $condition == '') ? ' check_id IN ('.$check_id.') ' : ' AND check_id IN ('.$check_id.')';
//            }else{
//                $condition.= ( $condition == '') ? ' check_id = :check_id ' : ' AND check_id = :check_id';
//                $params['check_id'] = '';
//            }
//        }
        $contractor_list = Contractor::Mc_scCompList($args);
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' root_proid =:program_id' : ' AND root_proid =:program_id';
                $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
                $params['program_id'] = $args['program_id'];
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

        if($args['user_id'] != ''){
            if($args['deal_type'] == 1) {
                $sql = "SELECT check_id FROM bac_safety_check  WHERE apply_user_id = '".$args['user_id']."' and root_proid = '".$args['program_id']."' and safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
            }else if($args['deal_type'] == 2){
                $sql = "SELECT check_id FROM bac_safety_check  WHERE person_in_charge_id = '".$args['user_id']."' and root_proid = '".$args['program_id']."' and safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
            }else{
                $sql = "SELECT b.check_id FROM bac_violation_record a,bac_safety_check b  where a.user_id = '".$args['user_id']."' and a.check_id=b.check_id and b.root_proid = '".$args['program_id']."' and b.safety_level = '".$args['safety_level']."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if (count($rows) > 0) {
                    foreach ($rows as $key => $row) {
                        $args['check_id'] .= $row['check_id'] . ',';
                    }
                }
                if ($args['check_id'] != '')
                    $args['check_id'] = substr($args['check_id'], 0, strlen($args['check_id']) - 1);
                $condition .= ($condition == '') ? ' check_id IN (' . $args['check_id'] . ')' : ' AND check_id IN (' . $args['check_id'] . ')';
            }
        }

        //Record Time
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
//        var_dump($condition);
        $total_num = QualityCheck::model()->count($condition, $params); //总记录数

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
//        if($args['initiator']){
//            $criteria->join = 'LEFT JOIN bac_staff b ON b.user_name='.$args['initiator'].'and t.apply_user_id = b.user_id';
//        }elseif($args['person_in_charge']){
//            $criteria->join = 'LEFT JOIN bac_staff b ON b.user_name='.$args['person_in_charge'].'and t.person_in_charge_id = b.user_id';
//        }elseif($args['person_responsible']){
//
//        }
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        //var_dump($criteria);
        $rows = QualityCheck::model()->findAll($criteria);
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

        $total_num = QaqcCheck::model()->count($condition, $params); //总记录数

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
        $rows = QaqcCheck::model()->findAll($criteria);
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

        $sql = "SELECT * FROM bac_qaqc_basic WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }

    //根据时间日期查询安全单
    public static function quertListByTime($args){
        $contractor_id = Yii::app()->user->contractor_id;
        $sql = "SELECT * FROM bac_qaqc_basic WHERE  contractor_id = '".$contractor_id."' AND apply_time >= '".$args['start_date']."' AND apply_time <= '".$args['end_date']."' AND status != 0";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //修改路径
    public static function updatePath($check_id,$save_path) {
        $save_path = substr($save_path,18);
        $sql = "update bac_qaqc_basic set save_path = '".$save_path."' where check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }

    //根据月份统计承包商下各项目违规次数
    public static function summaryByMonth() {
        $contractor_id = Yii::app()->user->contractor_id;
        $args['contractor_id'] = $contractor_id;
        $program_list = Program::McProgramList($args);
        $i = 0;
        foreach($program_list as $program_id => $program_name) {
            $sql = "select DATE_FORMAT(apply_time,'%c') as date,count(check_id) as cnt,root_proname from bac_qaqc_basic where contractor_id = '" . $contractor_id . "' and root_proid = '" . $program_id . "'  group by DATE_FORMAT(apply_time,'%c') ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
//            var_dump($rows);
            if ($rows) {
                $i++;
                $j = 0;
                $data = array();
                foreach ($rows as $cnt => $list) {
                    $data[$j][0] = (int)$list['date'];
                    $data[$j][1] = (int)$list['cnt'];
                    $r[$i]['data'] = $data;
                    $r[$i]['label'] = $list['root_proname'];
                    $j++;
                }
            }
        }
        return $r;
    }

    //人员按申请权限统计使用次数
    public static function findByApply($user_id,$program_id){
        $sql = "SELECT count(DISTINCT check_id) as apply_cnt,safety_level FROM bac_qaqc_basic  WHERE apply_user_id = '".$user_id."' and root_proid = '".$program_id."' group by safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按负责权限统计使用次数
    public static function findByCharge($user_id,$program_id){
        $sql = "SELECT count(DISTINCT check_id) as charge_cnt,safety_level FROM bac_qaqc_basic  WHERE person_in_charge_id = '".$user_id."' and root_proid = '".$program_id."' group by safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按成员统计使用次数
    public static function findByMember($user_id,$program_id){
        $sql = "SELECT b.safety_level, count(a.check_id) as cnt FROM bac_violation_record a,bac_qaqc_basic b  where a.user_id = '".$user_id."' and a.check_id=b.check_id and b.root_proid = '".$program_id."' group by b.safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计使用总次数
    public static function findByAll($user_id,$program_id){
        $sql = "select count(DISTINCT aa.check_id) as cnt from (SELECT check_id FROM bac_qaqc_basic WHERE root_proid = '".$program_id."' and (apply_user_id = '".$user_id."' or person_in_charge_id = '".$user_id."' ) UNION ALL SELECT c.check_id FROM bac_violation_record c,bac_qaqc_basic d where c.user_id = '".$user_id."' and c.check_id=d.check_id and d.root_proid = '".$program_id."')aa ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //设备按成员统计使用次数
    public static function deviceByMember($device_id,$program_id){
        $sql = "SELECT b.safety_level, count(a.check_id) as cnt FROM bac_violation_device a,bac_qaqc_basic b  where a.device_id = '".$device_id."' and a.check_id=b.check_id and b.root_proid = '".$program_id."' group by b.safety_level ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //设备统计使用总次数
    public static function deviceByAll($device_id,$program_id){
        $sql = "select count(DISTINCT a.check_id) as cnt FROM bac_violation_device a,bac_qaqc_basic b  where a.device_id = '".$device_id."' and a.check_id=b.check_id and b.root_proid = '".$program_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //下载PDF
    public static function downloadPdf($params,$app_id){
        $check_id = $params['check_id'];
//        $a = SafetyCheckDetail::detailAllList();
        $check_list = QualityCheck::detailList($check_id);//安全检查单
        $detail_list = QualityCheckDetail::detailList($check_id);//安全检查单详情
        $company_list = Contractor::compAllList();//承包商公司列表
        $program_list =  Program::programAllList();//获取承包商所有项目
        $staff_list = Staff::userAllList();//所有人员列表

        $title = $check_list[0]['title'];//标题
        $contractor_id = $check_list[0]['contractor_id'];
        $contractor_name = $company_list[$contractor_id];//承包商名称
        $root_proname = $check_list[0]['root_proname'];//总包项目名称
        $block = $check_list[0]['block'];//一级区域
        $secondary_region = $check_list[0]['secondary_region'];//二级区域
        $stipulation_time = $check_list[0]['stipulation_time'];//规定时间
        $person_in_charge_id = $check_list[0]['person_in_charge_id'];//负责人ID
        $person_in_charge = Staff::model()->findAllByPk($person_in_charge_id);//负责人
        $apply_user_id = $check_list[0]['apply_user_id'];//申请人ID
        $apply_user =  Staff::model()->findAllByPk($apply_user_id);//申请人
        $apply_time = $check_list[0]['apply_time'];//申请时间
        $close_time = $check_list[0]['close_time'];//关闭时间
        $deadline_date = $check_list[0]['close_time'];//截止时间
        $assess_name = $check_list[0]['assess_name']; //评定类型(中文)
        $assess_name_en = $check_list[0]['assess_name_en'];//评定类型(英文)
        $item_name = $check_list[0]['item_name']; //事件选项(中文)
        $item_name_en = $check_list[0]['item_name_en'];//事件选项(英文)
//        var_dump($violations_user);
//        exit;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply_time,0,4);//年
        $month = substr($apply_time,5,2);//月

        $filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/qaqc'.'/QAQC' . $check_id . '.pdf';
        $full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/qaqc';
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        SafetyCheck::updatepath($check_id,$filepath);

        if (file_exists($filepath)) {
            $show_name = $title;
            $extend = 'pdf';
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
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
        $logo_pic = $main_model->remark;
        if($logo_pic){
            $logo = '/opt/www-nginx/web'.$logo_pic;
            $pdf->SetHeaderData($logo, 20, 'QA/QC Inspection Records No. (QA/QC检查记录编号:)' . $check_id, $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }else{
            $pdf->SetHeaderData('', 0, 'QA/QC Inspection Records No. (QA/QC检查记录编号:)' . $check_id, $contractor_name, array(0, 64, 255), array(0, 64, 128));
        }
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 27, 15);
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
//            $pdf->SetFont('stsongstdlight', '', 14, '', true); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
//        }

        $pdf->AddPage();
        $user_list = Staff::userInfo();//员工信息
        $roleList = Role::roleallList();//岗位列表
        $apply_role = $apply_user[0]['role_id'];//发起人角色
        //标题(许可证类型+项目)
        $title_html = "<h2 align=\"center\">Project (项目) : {$root_proname}</h2>
            <h2 align=\"center\">QA/QC Inspection Title (QA/QC检查标题): {$title}</h2><br/>";
        //发起人详情
        $apply_info_html = "<h4 align=\"center\">Initiator Details (发起人详情)</h4><table border=\"1\">";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name (姓名)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation (职位)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID Number (身份证号码)</td></tr>";
        if($apply_user[0]['work_pass_type'] = 'IC' || $apply_user[0]['work_pass_type'] = 'PR'){
            if(substr($apply_user[0]['work_no'],0,1) == 'S' && strlen($apply_user[0]['work_no']) == 9){
                $work_no = 'SXXXX'.substr($apply_user[0]['work_no'],5,8);
            }else{
                $work_no = $apply_user[0]['work_no'];
            }
        }else{
            $work_no = $apply_user[0]['work_no'];
        }
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$apply_user[0]['user_name']}</td><td align=\"center\">&nbsp;{$roleList[$apply_role]}</td><td align=\"center\">&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Company (公司)</td><td width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Date of Initiation (发起时间)</td><td width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$company_list[$contractor_id]}</td><td align=\"center\">&nbsp;{$apply_time}</td><td align=\"center\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        //判断电子签名是否存在 $add_operator->signature_path
        $content_list = $user_list[$apply_user_id];
        $content = $content_list[0]['signature_path'];
//        if($content){
//            $pdf->Image($content, 150, 76, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//        }
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色
        //负责人详情
        $charge_info_html = "<h4 align=\"center\">Person In Charge Details (负责人详情)</h4><table border=\"1\">";
        $charge_info_html .="<tr><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Name (姓名)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Designation (职位)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">ID Number (身份证号码)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Company (公司)</td></tr>";
        if($person_in_charge[0]['work_pass_type'] = 'IC' || $person_in_charge[0]['work_pass_type'] = 'PR'){
            if(substr($person_in_charge[0]['work_no'],0,1) == 'S' && strlen($person_in_charge[0]['work_no']) == 9){
                $work_no = 'SXXXX'.substr($person_in_charge[0]['work_no'],5,8);
            }else{
                $work_no = $person_in_charge[0]['work_no'];
            }
        }else{
            $work_no = $person_in_charge[0]['work_no'];
        }
        $charge_info_html .="<tr><td height=\"50px\" align=\"center\">&nbsp;{$person_in_charge[0]['user_name']}</td><td align=\"center\">&nbsp;{$roleList[$charge_role]}</td><td align=\"center\">&nbsp;{$work_no}</td><td align=\"center\">&nbsp;{$company_list[$person_in_charge[0]['contractor_id']]}</td></tr>";
        $charge_info_html .="</table>";
        $charge_role = $person_in_charge[0]['role_id'];//发起人角色

        //安全检查详情
        $work_content_html = "<h4 align=\"center\">QA/QC Inspection Details (质量检查类型)</h4><table border=\"1\" >";
        $work_content_html .="<tr><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Work Location<br>(工作地点)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Type of Assessment<br>(评定类型)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Type of Item<br>(事项类型)</td><td width=\"25%\" nowrap=\"nowrap\" bgcolor=\"#d9d9d9\" align=\"center\">Expected Completion Time<br>(预计完成时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" align=\"center\">{$block}</td><td align=\"center\">".$assess_name_en."</td><td align=\"center\">".$item_name_en."</td><td align=\"center\">".$check_list[0]['stipulation_time']. "</td></tr></table>";

//        $check_html = '
//            <h4 align="center">WSH Inspection(安全检查)</h4><table border="1"><tr><td width="35%" nowrap="nowrap">&nbsp;Title(标题)</td><td width="65%" nowrap="nowrap">&nbsp;'.$title.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Applicant Company(申请公司)</td><td width="65%" nowrap="nowrap">&nbsp;'.$company_list[$contractor_id].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;The contractor project(总包项目)</td><td width="65%" nowrap="nowrap">&nbsp;'.$root_proname.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Block(一级区域)</td><td width="65%" nowrap="nowrap">&nbsp;'.$block.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Secondary Region(二级区域)</td><td width="65%" nowrap="nowrap">&nbsp;'.$secondary_region.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Safety Level(安全等级)</td><td width="65%" nowrap="nowrap">&nbsp;'.$check_list[0]['safety_level'].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Safety Level Description(安全等级描述)</td><td width="65%" nowrap="nowrap">&nbsp;'.$description.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Applicant(申请人)</td><td width="65%" nowrap="nowrap">&nbsp;'.$apply_user[0]['user_name'].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Head(负责人)</td><td width="65%" nowrap="nowrap">&nbsp;'.$person_in_charge[0]['user_name'].'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Time of application(申请时间)</td><td width="65%" nowrap="nowrap">&nbsp;'.$apply_time.'</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Closing time(关闭时间)</td><td width="65%" nowrap="nowrap">&nbsp;'.$close_time.'</td></tr>
//                <tr><td width="35%" height="43px" nowrap="nowrap">&nbsp;Applicant Electronic Signature(申请人电子签名)</td><td width="65%" nowrap="nowrap">&nbsp;</td></tr>
//                <tr><td width="35%" nowrap="nowrap">&nbsp;Violations personnel(违规人员)</td><td width="65%" nowrap="nowrap">&nbsp;'.$violations_user.'</td></tr></table>';
////        $content = $user_list[$apply_user_id]['signature_path'];
//
////        $pic = 'C:\Users\minchao\Desktop\5.png';
//        if($content != '') {
//            $pdf->Image($content, 100, 77, 32, 12, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//        }


        $info_x = 44;//X方向距离
        $info_y_1 = 227;//第一页Y方向距离
        $info_y_2 = 44;//第二页Y方向距离
        $info_y_3 = 30;//第三页Y方向距离
        $cnt_1 = 0;
        $cnt_2 = 0;
//        $pic = 'C:\Users\minchao\Desktop\5.png';
//        var_dump($detail_list);
//        exit;
//        if (!empty($detail_list)){
//            foreach ($detail_list as $cnt => $list) {
////                var_dump($device_list);
////                exit;
//                if($list['step'] <= 2){
//                    if($list['pic']) {
//                        $pdf->Image($list['pic'], $info_x, $info_y_1, 26, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                    }
//                    $check_detail_html_1 .= '<tr ><td height="80px">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td></td><td >&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
//                    $info_y_1 +=22;
//
//                }
//            }
//        }
//        $check_detail_html_1 .= '</table>';
        $html_1 = $title_html . $apply_info_html . $charge_info_html . $work_content_html ;
        $pdf->writeHTML($html_1, true, false, true, false, '');
        $num = count($detail_list);

        if (!empty($detail_list)) {
            $pdf->AddPage();
        }
        $check_detail_html_2 = '<h4 align="center">QA/QC Inspection Process (质量检查流程)</h4><table border="1">
            <tr><td  width="10%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Step<br>(步骤)</td><td  width="25%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Photo(s)<br>(照片)</td><td width="30%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Content<br>(内容)</td><td width="15%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Correspondents<br>(对应人)</td><td width="20%" nowrap="nowrap" bgcolor="#d9d9d9" align="center">&nbsp;Date & Time<br>(日期&时间)</td></tr>';
        if (!empty($detail_list)){
            foreach ($detail_list as $cnt => $list) {
//                var_dump($device_list);
//                exit;
                if($list['step'] < 13){
                    if($list['pic'] && $list['pic'] != -1) {
                        $pdf->Image($list['pic'], $info_x, $info_y_2, 26, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    if($list['step']%2==0) {
                        $check_detail_html_2 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$person_in_charge[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }else{
                        $check_detail_html_2 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$apply_user[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }
                    $info_y_2 +=22;

                }
            }
        }
        $check_detail_html_2 .= '</table>';
        $html_2 = $check_detail_html_2;
        if($num < 13) {
            $pdf->writeHTML($html_2, true, false, true, false, '');
        }

        if($num >= 13) {
            $pdf->AddPage();
        }
        $check_detail_html_3 = '<table border="1">';
        if (!empty($detail_list)){
            foreach ($detail_list as $cnt => $list) {
//                var_dump($device_list);
//                exit;
                if($list['step'] >= 13){
                    if($list['pic'] && $list['pic'] != -1) {
                        $pdf->Image($list['pic'], $info_x, $info_y_2, 26, 18, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                    if($list['step']%2==0) {
                        $check_detail_html_3 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$person_in_charge[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }else{
                        $check_detail_html_3 .= '<tr ><td height="80px" align="center">&nbsp;' . $list['step'] . '</td><td >&nbsp;</td><td >&nbsp;' . $list['description'] . '</td><td align="center">&nbsp;' .$apply_user[0]['user_name'].'</td><td align="center">&nbsp;' . Utils::DateToEn($list['record_time']) . '</td></tr>';
                    }
                    $info_y_3 +=22;

                }
            }
        }
        $check_detail_html_3 .= '</table>';
        $html_3 = $check_detail_html_3;
        if($num >= 13) {
            $pdf->writeHTML($html_3, true, false, true, false, '');
        }

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
        $type_list = QualityAssessType::allText();
//        var_dump($args['date']);
//        var_dump($month);
        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $sql = "select count(check_id) as cnt,root_proid,assess_id from bac_qaqc_basic  where  main_proid = '".$args['program_id']."' and apply_time like '".$month."%' and contractor_id = '".$args['contractor_id']."'  GROUP BY assess_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,root_proid,assess_id from bac_qaqc_basic  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."'  GROUP BY assess_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $type_list[$list['assess_id']];

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
            $sql = "select count(check_id) as cnt,contractor_id from bac_qaqc_basic  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,contractor_id from bac_qaqc_basic  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."'   GROUP BY contractor_id";
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
