<?php

/**
 * 许可证申请表
 * @author LiuMinchao
 */
class ApplyBasic extends CActiveRecord {

    //状态：00-未审批，01－审批中，02审批完成。
    const STATUS_APPLY_AUDITING = '0'; //申请审批中
    const STATUS_APPLY_FINISH = '1'; //申请审批完成
    const STATUS_REJECT = '2'; //拒绝或者驳回
    const STATUS_CLOSE_AUDITING = '3'; //申请关闭中
    const STATUS_CLOSE_FINISH = '4'; //关闭审批完成
    const RESULT_YES = 0;
    const RESULT_NO = 1;
    const RESULT_NA = 2;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ptw_apply_basic';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_APPLY_AUDITING => Yii::t('license_licensepdf', 'STATUS_AUDITING'),
            self::STATUS_APPLY_FINISH => Yii::t('license_licensepdf', 'STATUS_FINISH'),
            self::STATUS_CLOSE_AUDITING => Yii::t('license_licensepdf', 'STATUS_CLOSE_AUDITING'),
            self::STATUS_CLOSE_FINISH => Yii::t('license_licensepdf', 'STATUS_CLOSE_FINISH'),
            self::STATUS_REJECT       => Yii::t('license_licensepdf', 'STATUS_REVOKED'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //拒绝&&驳回状态
    public static function statusNo($key = null){
        $rs = array(
            '1' => Yii::t('license_licensepdf', 'STATUS_REJECT'),
            '2' => Yii::t('license_licensepdf', 'STATUS_REJECT'),
            '4' => Yii::t('license_licensepdf', 'STATUS_CLOSE_REJECT'),
            '5' => Yii::t('license_licensepdf', 'STATUS_CLOSE_REJECT'),
            '6' => Yii::t('license_licensepdf', 'STATUS_REVOKED'),
            '8' => Yii::t('license_licensepdf', 'STATUS_REJECT'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态列表
    public static function statusList($key = null){
        $rs = array(
            '0' => Yii::t('license_licensepdf', 'submitted'),
            '1' => Yii::t('license_licensepdf', 'assessed'),
            '2' => Yii::t('license_licensepdf', 'approved'),
            '1R' => Yii::t('license_licensepdf', 'rejected'),
            '3' => Yii::t('license_licensepdf', 'closed'),
            '4' => Yii::t('license_licensepdf', 'closure approved'),
            '5' => Yii::t('license_licensepdf', 'closure accepted'),
            '2R' => Yii::t('license_licensepdf', 'closure rejected'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_APPLY_AUDITING => 'label-info', //审批中
            self::STATUS_CLOSE_AUDITING => 'label-info', //关闭中
            self::STATUS_APPLY_FINISH => 'label-success', //审批完成
            self::STATUS_CLOSE_FINISH => 'label-success', //关闭完成
            self::STATUS_REJECT => 'label-danger', //不通过或者驳回

        );
        return $key === null ? $rs : $rs[$key];
    }

    public static  function AllRoleList(){
        if (Yii::app()->language == 'zh_CN') {
            $role = array("0" => array("0" => "等待审批",
                "1" => "审批中",
                "2" => "审批中",
                "3" => "成员"
            ),
                "ptw_role" => array("0" => "否",
                    "1" => "申请者",
                    "4" => "评审者2",
                    "2" => "评审者",
                    "3" => "批准者"
                ),
                "wsh_mbr_flag" => array("0" => "否",
                    "1" => "是"
                ),
                "meeting_flag" => array("0" => "否",
                    "1" => "发起者",
                    "2" => "批准者"
                ),
                "training_flag" => array("0" => "否",
                    "1" => "发起者",
                    "2" => "批准者"
                ),
            );
        }else{
            $role = array("ra_role" => array("0" => "No",
                "1" => "Approver",
                "2" => "Leader",
                "3" => "Member"
            ),
                "ptw_role" => array("0" => "No",
                    "1" => "Applicant",
                    "4" => "Assessor2",
                    "2" => "Assessor",
                    "3" => "Approver",

                ),
                "wsh_mbr_flag" => array("0" => "No",
                    "1" => "Yes"
                ),
                "meeting_flag" => array("0" => "No",
                    "1" => "Conducting",
                    "2" => "Approver"
                ),
                "training_flag" => array("0" => "No",
                    "1" => "Conducting",
                    "2" => "Approver"
                ),
            );
        }
        return $role;
    }


    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => 'YES',
            self::RESULT_NO => 'NO',
            self::RESULT_NA => 'N/A',
        );
        return $key == null ? $rs : $rs[$key];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'apply_id' => 'Apply',
            'approve_id' => 'Approve',
            'program_id' => 'Program',
            'program_name' => 'Program Name',
            'apply_date' => 'Apply Date',
            'contractor_id' => 'Contractor',
            'contractor_name' => 'Contractor Name',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'condition_set' => 'Condition Set',
            'status' => 'Status',
            'record_time' => 'Record Time',
            'work_content' => 'Work Content',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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
        if ($args['apply_id'] != '') {
            $condition.= ( $condition == '') ? ' apply_id=:apply_id' : ' AND apply_id=:apply_id';
            $params['apply_id'] = $args['apply_id'];
        }
        //Program Name Mc_ScProgramList
        if ($args['program_name'] != '') {
            $condition.= ( $condition == '') ? ' program_name=:program_name' : ' AND program_name=:program_name';
            $params['program_name'] = $args['program_name'];
        }


        if($args['user_id'] != ''){
            if($args['deal_type'] != -1) {
                $sql = "SELECT b.apply_id FROM bac_check_apply_detail a,ptw_apply_basic b WHERE a.deal_user_id = '" . $args['user_id'] . "' and a.app_id = 'PTW' and a.apply_id = b.apply_id and b.program_id = '" . $args['program_id'] . "' and b.type_id = '" . $args['type_id'] . "' and a.deal_type = '" . $args['deal_type'] . "' ";
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
                $sql = "SELECT b.apply_id FROM ptw_apply_worker a,ptw_apply_basic b  where a.user_id = '".$args['user_id']."' and a.apply_id=b.apply_id and b.program_id = '".$args['program_id']."' and b.type_id = '" . $args['type_id'] . "' ";
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


        $contractor_list = Contractor::Mc_scCompList($args);
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $condition.= ( $condition == '') ? ' apply_contractor_id =:contractor_id ' : ' AND apply_contractor_id =:contractor_id ';
                $root_proid = $pro_model->root_proid;
                $params['program_id'] = $root_proid;
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
                $condition.= ( $condition == '') ? ' apply_contractor_id =:contractor_id ' : ' AND apply_contractor_id =:contractor_id ';
                $params['program_id'] = $program_id;
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
                $params['program_id'] = $program_id;
            }
        }

        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' type_id=:type_id' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }
        //Record Time
        //if ($args['record_time'] != '') {
        //$args['record_time'] = Utils::DateToCn($args['record_time']);
        //$condition.= ( $condition == '') ? ' record_time LIKE :record_time' : ' AND record_time LIKE :record_time';
        //$params['record_time'] = '%'.$args['record_time'].'%';
        //}

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
            $condition.= ( $condition == '') ? ' apply_contractor_id =:contractor_id ' : ' AND apply_contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['con_id'];
        }
        //Program Id Mc_ScProgramList
        //$args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        //$program_list = Program::getMProgramId();
        //$condition.= ( $condition == '') ? ' program_id IN (' . $program_list . ')' : ' AND program_id IN (' . $program_list . ')';
        //$condition .= ' OR apply_contractor_id = '.$args['contractor_id'].' ';
        $total_num = ApplyBasic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
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
        $rows = ApplyBasic::model()->findAll($criteria);
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
    public static function newqueryList($page, $pageSize, $args = array()) {

        $condition = '';
        $sql = '';
        $apply_contractor_id = '';

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);

        //分包项目
        if($pro_model->main_conid != $contractor_id){
            $root_proid = $pro_model->root_proid;
            $args['con_id'] = $contractor_id;
        }else{
            $root_proid = $args['program_id'];
        }

        if ($args['con_id'] != '') {
            $apply_contractor_id = $args['con_id'];
            $condition .= "and apply_contractor_id = '$apply_contractor_id'";
        }

        if ($args['type_id'] != '') {
            $type_id = $args['type_id'];
            $condition .= "and type_id = '$type_id'";
        }

        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= " and record_time >='$start_date'";
        }

        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= " and record_time <='$end_date 23:59:59'";
        }

        $condition_status = '1=1';
        if($args['status'] != ''){
            $status = $args['status'];
            if ($args['status'] == '1R'){
                $condition_status.= " and (enddealtype in ('1','8','2') and enddealstatus = '2' or enddealtype ='6')";
            }else if ($args['status'] == '2R'){
                $condition_status.= " and enddealtype in ('4','5') and enddealstatus = '2'";
            }else if ($args['status'] == '1'){
                $condition_status.= " and enddealtype in ('1','8') and enddealstatus = '1'";
            }else{
                $condition_status.= " and enddealtype = $status and enddealstatus = '1'";
            }
        }
        $start=$page*$pageSize;
        $sql_1 = "SELECT
            p.*, if(p.enddealtype='6','1','0') as isreject,
            p.enddealstep as current_step, p.enddealstatus as deal_status
        FROM(
            SELECT
                a.apply_id, a.title, a.record_time, a.program_id, a.program_name,
                a.apply_contractor_id, a.apply_contractor_name as contractor_name,a.type_id,
                a.apply_user_id, a.status, IFnull(a.add_conid, 'A') as ptw_mode,
                SUBSTRING_INDEX(a.add_operator, '|', 1) as enddealstep,
                SUBSTRING_INDEX(SUBSTRING_INDEX(a.add_operator, '|', 2), '|', -1) as enddealtype,
                SUBSTRING_INDEX(a.add_operator, '|', -1) as enddealstatus
            FROM
                ptw_apply_basic a
            WHERE
                a.program_id = '".$root_proid."'  $condition
            ) p
        WHERE $condition_status
        ORDER BY p.record_time desc limit $start, $pageSize";

        $sql_2 = "SELECT
            count(*)
        FROM(
            SELECT
                a.apply_id, a.title, a.record_time, a.program_id, a.program_name,
                a.apply_contractor_id, a.apply_contractor_name as contractor_name,a.type_id,
                a.apply_user_id, a.status, IFnull(a.add_conid, 'A') as ptw_mode,
                SUBSTRING_INDEX(a.add_operator, '|', 1) as enddealstep,
                SUBSTRING_INDEX(SUBSTRING_INDEX(a.add_operator, '|', 2), '|', -1) as enddealtype,
                SUBSTRING_INDEX(a.add_operator, '|', -1) as enddealstatus
            FROM
                ptw_apply_basic a
            WHERE
                a.program_id = '".$root_proid."'  $condition
            ) p
        WHERE $condition_status
        ORDER BY p.record_time desc";

        $command_1 = Yii::app()->db->createCommand($sql_1);
        $retdata_1 = $command_1->queryAll();

        $command_2 = Yii::app()->db->createCommand($sql_2);
        $retdata_2 = $command_2->queryAll();

//        var_dump($sql_1);
//        exit;
//        $start=$page*$pageSize; #计算每次分页的开始位置
//        $count = count($retdata);
//        $pagedata=array();
//        if($count>0){
//            $pagedata=array_slice($retdata,$start,$pageSize);
//        }else{
//            $pagedata = array();
//        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $retdata_2[0]['count(*)'];
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $retdata_1;

        return $rs;
    }

    public static function typeList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT type_id,type_name FROM ptw_type_list WHERE status=00 ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT type_id,type_name_en FROM ptw_type_list WHERE status=00  ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }
        return $rs;
    }

    public static function typelanguageList() {

        $sql = "SELECT type_id,type_name,type_name_en FROM ptw_type_list WHERE status=00 ";
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

    public static function updatePath($apply_id,$save_path) {
        $save_path = substr($save_path,18);
        $model = ApplyBasic::model()->findByPk($apply_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }
    //人员按权限和成员进行统计
    public static function findBySummary($user_id,$program_id){
        $sql = "SELECT a.type_id, b.deal_type, count(distinct b.apply_id) as cnt
                  FROM ptw_apply_basic a inner join bac_check_apply_detail b
                  on  a.apply_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'PTW' and b.deal_user_id = '".$user_id."'
                  group by a.type_id
                UNION
                SELECT c.type_id, 'MEMBER' as deal_type, count(distinct c.apply_id) as cnt
                  FROM ptw_apply_basic c inner join ptw_apply_worker d
                  on c.apply_id=d.apply_id where c.program_id = '".$program_id."' and d.user_id = '".$user_id."'
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
                    $rs[$key]['deal_type'] = $row['deal_type'];
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
                              FROM ptw_apply_basic a inner join bac_check_apply_detail b
                              on  a.apply_id = b.apply_id and a.program_id = '".$program_id."' and b.app_id = 'PTW'  and b.deal_user_id = '".$user_id."'
                           UNION
                           SELECT c.type_id, 'MEMBER' as deal_type, c.apply_id
                              FROM ptw_apply_basic c inner join ptw_apply_worker d
                              on c.apply_id=d.apply_id where c.program_id = '".$program_id."' and d.user_id = '".$user_id."'
                  )aa";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按权限统计使用次数
    public static function findBySet($user_id,$program_id){
        $sql = "SELECT count(DISTINCT a.apply_id) as cnt,a.deal_type,b.type_id FROM bac_check_apply_detail a,ptw_apply_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'PTW' and a.apply_id = b.apply_id and b.program_id = '".$program_id."' group by b.type_id ";
        //$sql = "SELECT count(DISTINCT a.apply_id) as cnt,a.deal_type,b.type_id FROM bac_check_apply_detail a INNER JOIN ptw_apply_basic b on a.apply_id = b.apply_id  WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'PTW' and  b.program_id = '".$program_id."' group by b.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //人员按成员统计使用次数
    public static function findByMember($user_id,$program_id){
        $sql = "SELECT b.type_id, count(a.apply_id) as cnt FROM ptw_apply_worker a,ptw_apply_basic b  where a.user_id = '".$user_id."' and a.apply_id=b.apply_id and b.program_id = '".$program_id."' group by b.type_id ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计使用总次数
    public static function findByAll($user_id,$program_id){
        $sql = "select count(DISTINCT aa.apply_id) as cnt from (SELECT a.apply_id FROM bac_check_apply_detail a,ptw_apply_basic b WHERE a.deal_user_id = '".$user_id."' and a.app_id = 'PTW' and a.apply_id = b.apply_id and b.program_id = '".$program_id."' UNION ALL SELECT c.apply_id FROM ptw_apply_worker c,ptw_apply_basic d where c.user_id = '".$user_id."' and c.apply_id=d.apply_id and d.program_id = '".$program_id."')aa ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //设备统计使用次数
    public static function deviceByType($device_id,$program_id){
        $sql = "SELECT b.type_id, count(a.apply_id) as cnt
                FROM ptw_apply_device a inner join ptw_apply_basic b
                on a.device_id = '".$device_id."' and a.apply_id=b.apply_id and b.program_id = '".$program_id."'
                group by b.type_id ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $ptw_type = ApplyBasic::typeList();//许可证类型表(双语)
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$key]['type_id'] = $row['type_id'];
                $rs[$key]['type_name'] = $ptw_type[$row['type_id']];
                $rs[$key]['cnt'] = $row['cnt'];
            }
        }
        return $rs;
    }
    //统计使用总次数
    public static function deviceByAll($device_id,$program_id){
        $sql = "select count(DISTINCT a.apply_id) as cnt
                FROM ptw_apply_device a inner join ptw_apply_basic b
                on a.device_id = '".$device_id."' and a.apply_id=b.apply_id and b.program_id = '".$program_id."' ";
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
            $filepath = self::downloadZjnyPDF($params,$app_id);//中建南阳
        }
        return $filepath;
    }
    //下载默认PDF
    public static function downloaddefaultPDF($params,$app_id){

        $id = $params['id'];
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $device_list = ApplyDevice::getDeviceList($id);//许可证申请设备表
        $worker_list = ApplyWorker::getWorkerList($apply->apply_id);//许可证申请人员表
        $company_list = Contractor::compAllList();//承包商公司列表
        $document_list = PtwDocument::queryDocument($id);//文档列表
        //$program_list =  Program::programAllList();//获取承包商所有项目
        //$ptw_type = ApplyBasic::typeList();//许可证类型表
        $ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
        $type_id = $apply->type_id;//许可证类型编号
        $program_id = $apply->program_id;
        //$programdetail_list = Program::getProgramDetail($program_id);
        //根据项目id得到总包商和根节点项目
        $region_list = PtwApplyBlock::regionList($id);//PTW项目区域
        $status_css = CheckApplyDetail::statusTxt();//PTW执行类型(成功)
        $reject_css = CheckApplyDetail::rejectTxt();//PTW执行类型(拒绝)

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $hours = substr($apply->record_time,11,2);//小时
        $minute = substr($apply->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';
        ApplyBasic::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = $apply->program_name;

        //if (file_exists($filepath)) {
        //$show_name = $title;
        //$extend = 'pdf';
        //Utils::Download($filepath, $show_name, $extend);
        //return;
        //}
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0'){
            $pro_params = json_decode($pro_params,true);
            //判断是否是迁移的
            if(array_key_exists('transfer_con',$pro_params)){
                $main_conid = $pro_params['transfer_con'];
            }else{
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $main_conid = $pro_model->contractor_id;//总包编号
        }
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Permit-to-Work (PTW) No. (许可证申请编号):  ' . $apply->apply_id;

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
        $operator_id = $apply->apply_user_id;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_first_time = Utils::DateToEn(substr($apply->record_time,0,10));
        $apply_second_time = substr($apply->record_time,11,18);
        //$path = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300%\" align=\"center\">{$main_conid_name}</h1><br/><h2  style=\"font-size: 200%\" align=\"center\">Project (项目) : {$apply->program_name}</h2>
            <h2 style=\"font-size: 200%\" align=\"center\">PTW Type (许可证类型): {$ptw_type[$type_id]['type_name_en']} ({$ptw_type[$type_id]['type_name']})</h2><br/>";
        $html =$title_html;
        $pdf->writeHTML($html, true, false, true, false, '');
        $apply_y = $pdf->GetY();
        //申请人资料
        $apply_info_html = "<h2 align=\"center\"> Applicant Details (申请人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name(姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation(职位)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($add_operator->work_pass_type = 'IC' || $add_operator->work_pass_type = 'PR'){
            if(substr($add_operator->work_no,0,1) == 'S' && strlen($add_operator->work_no) == 9){
                $work_no = 'SXXXX'.substr($add_operator->work_no,5,8);
            }else{
                $work_no = $add_operator->work_no;
            }
        }else{
            $work_no = $add_operator->work_no;
        }
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$add_operator->user_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$add_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Application (申请时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$company_list[$apply->apply_contractor_id]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_first_time}  {$apply_second_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        //判断电子签名是否存在 $add_operator->signature_path
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        $content = $add_operator->signature_path;
        if($content){
            $pdf->Image($content, 150, $apply_y+40, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
        //工作内容
        $work_content_html = "<h2 align=\"center\">Nature of Work (工作详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br/>{$apply->title}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Description (描述)</td></tr>";
        $work_content_html .="<tr><td height=\"215px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->work_content}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" width=\"50%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->start_time}</td><td height=\"50px\" width=\"50%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->end_time}</td></tr></table>";


        $progress_list = CheckApplyDetail::progressList( $app_id,$apply->apply_id);//审批步骤详情

        $html_1 = $apply_info_html . $work_content_html;

        $pdf->writeHTML($html_1, true, false, true, false, '');

        //现场照片
        $pdf->AddPage();
        $pic_title_html = '<h2 align="center">Site Photo(s) (现场照片)</h2>';
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        $y2= $pdf->GetY();
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
                                    }
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220){
                                    $total_height[$cnt] = $title_height-43;
                                    $info_y = 10;
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                    $title_height = 45+10;
                                    $cnt++;
                                }else{
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
                }
            }
        }
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
//                    var_dump($pic);
//                    exit;
                    //for($o=0;$o<=8;$o++){
                    //    $pic[$o] =  "/opt/www-nginx/web/filebase/record/2019/02/tbm/pic/tbm_1550054744258_1.jpg";
                    //}
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
                }
            }
        }

        //安全条件
        $condition_html = '<h2 align="center">Safety Conditions (安全条件)</h2><table style="border-width: 1px;border-color:gray gray gray gray"><tr><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td height="20px" width="75%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Conditions (安全条件)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">YES / NO / N/A</td></tr>';
        if($apply->condition_set != '{}' && $apply->condition_set != '[]') {
            $condition_set = json_decode($apply->condition_set, true);
            $resultText = ApplyBasic::resultText();
            $condition_list = PtwCondition::conditionList();

            if (array_key_exists('name', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $condition_name = $row['name'] . '<br>' . $row['name_en'];
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td>&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
            if (array_key_exists('id', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $name = $condition_list[$row['id']]['condition_name'];
                    $name_en = $condition_list[$row['id']]['condition_name_en'];
                    $condition_name = $name . '<br>' . $name_en;
                    //$condition_name = $row['name'].'<br>'.$row['name_en'];
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td  style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
        }
        $condition_html .= '</table>';
        $remark_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Remark (备注)</td></tr>";
        $remark_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->devices}</td></tr></table>";

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list)){
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        }else{
            $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }

        $region_html .= '</table>';

        $html_2 =$condition_html . $remark_html . $region_html;
        $pdf->writeHTML($html_2, true, false, true, false, '');

        $pdf->AddPage();
        $approval_y = $pdf->GetY();
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
        //$audit_close_html = '<h5 align="center">' . Yii::t('license_licensepdf', 'progress_close_list') . '</h5><table border="1">
        //<tr><td width="10%">&nbsp;' . Yii::t('license_licensepdf', 'seq') . '</td><td width="30%">&nbsp;' . Yii::t('license_licensepdf', 'audit_person') . '</td><td width="30%"> '. Yii::t('license_licensepdf','audit_result').'</td><td width="30%"> ' . Yii::t('tbm_meeting', 'audit_date') . '</td></tr>';

        //$progress_close_list = WorkflowProgressDetail::progressList('PTW_CLOSE', $apply->apply_id);

        $progress_result = CheckApplyDetail::resultTxt();
        $j = 1;
        $y = 1;
        $info_xx = 170;
        if($approval_y > 260){
            $info_yy = 46;
        }else{
            $info_yy = $approval_y+24;
        }

        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {
                $content_list = Staff::model()->findAllByPk($row['deal_user_id']);
                $content = $content_list[0]['signature_path'];
                if($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)){
                        $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                if($row['status'] == 2){
                    $status = $reject_css[$row['deal_type']];
                }else{
                    $status = $status_css[$row['deal_type']];
                }
                $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$status.'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.Utils::DateToEn($row['deal_time']).'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$row['remark'].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"></td></tr>';
                $j++;
                $info_yy += 15;
                if($info_yy > 271){
                    $info_yy = 46;
                }
            }
        $audit_html .= '</table>';
        $pdf->writeHTML($audit_html, true, false, true, false, '');

//        $pic_html = '<h4 align="center">Site Photo(s) (现场照片)</h4><table border="1">
//                <tr><td width ="100%" height="107px"></td></tr>';
//        $pic = $progress_list[0]['pic'];
//        if($pic != '') {
//            $pic = explode('|', $pic);
////            var_dump($pic);
////            exit;
//            $info_x = 40;
//            $info_y = 148;
//            foreach ($pic as $key => $content) {
////                $pic = 'C:\Users\minchao\Desktop\5.png';
//                if($content != '' && $content != 'nil' && $content != '-1') {
//                    if(file_exists($content)){
//                        $pdf->Image($content, $info_x, $info_yy + 13, 30, 23, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                        $info_x += 50;
//                    }
//                }
//            }
//        }
//        $pic_html .= '</table>';
        //现场人员
        $worker_html = '<br/><br/><h2 align="center">Member(s) (施工人员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="35%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Employee ID<br>(员工编号)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td></tr>';

        if (!empty($worker_list)){
            $i = 1;
            foreach ($worker_list as $user_id => $r) {
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
                $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $user_id . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$roleList[$r['role_id']].  '</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        //现场设备
        $primary_list = Device::primaryAllList();
        $device_type = DeviceType::deviceList();
        $devices_html = '<br/><br/><h2 align="center">Equipment (设备)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Registration No.<br>(设备编码)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Name<br>(设备名称)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Type<br>(设备类型)</td></tr>';
        if (!empty($device_list)){
            $j =1;
            foreach ($device_list as $id => $list) {
                $devicetype_model = DeviceType::model()->findByPk($list['type_no']);//设备类型信息
                $device_type_ch = $devicetype_model->device_type_ch;
                $device_type_en = $devicetype_model->device_type_en;
                $devices_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $j . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $primary_list[$id] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['device_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$device_type_en.'<br>'.$device_type_ch . '</td></tr>';
                $j++;
            }
        }else{
            $devices_html .= '<tr><td align="center" colspan="4" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $devices_html .= '</table>';

        //文档标签
        $document_html = '<br/><br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">S/N (序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">Document Name (文档名称)</td></tr>';
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

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list))
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        $region_html .= '</table>';

        $html_3 = $worker_html . $devices_html . $document_html;

        $pdf->writeHTML($html_3, true, false, true, false, '');


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
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $device_list = ApplyDevice::getDeviceList($id);//许可证申请设备表
        $device_no = '';
        foreach($device_list as $device_id =>$device_info){
            $device_model = Device::model()->findByPk($device_id);
            $device_no = $device_model->device_id;
        }
        $worker_list = ApplyWorker::getWorkerList($apply->apply_id);//许可证申请人员表
        $company_list = Contractor::compAllList();//承包商公司列表
        $document_list = PtwDocument::queryDocument($id);//文档列表
        $staff_list = Staff::userAllList();
        //$program_list =  Program::programAllList();//获取承包商所有项目
        //$ptw_type = ApplyBasic::typeList();//许可证类型表
        $ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
        $role_list = Role::roleList();
        $type_id = $apply->type_id;//许可证类型编号
        $check_list = $apply->check_list;
        $program_id = $apply->program_id;
        //$programdetail_list = Program::getProgramDetail($program_id);
        //根据项目id得到总包商和根节点项目
        $region_list = PtwApplyBlock::regionList($id);//PTW项目区域
        $status_css = CheckApplyDetail::statusTxt();//PTW执行类型(成功)
        $reject_css = CheckApplyDetail::rejectTxt();//PTW执行类型(拒绝)

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $hours = substr($apply->record_time,11,2);//小时
        $minute = substr($apply->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';
        ApplyBasic::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = $apply->program_name;

        //if (file_exists($filepath)) {
        //$show_name = $title;
        //$extend = 'pdf';
        //Utils::Download($filepath, $show_name, $extend);
        //return;
        //}
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);

        //Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportShsdPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        $program_name = $pro_model->program_name;//项目名称
        $operator_id = $apply->apply_user_id;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_operator_conid = $add_operator->contractor_id;//总包编号
        $add_operator_model = Contractor::model()->findByPk($add_operator_conid);
        $apply_conid_name = $add_operator_model->contractor_name;
        $main_conid = $pro_model->contractor_id;//总包编号
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Permit-to-Work (PTW) No. (许可证申请编号):  ' . $apply->apply_id;

        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        $pdf->SetHeaderData('', 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

//        $pdf->Header($logo_pic);
//        var_dump(11111);
//        exit;
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        //设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(9);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('times', '', 12, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $progress_list = CheckApplyDetail::progressList( $app_id,$apply->apply_id);//审批步骤详情
        $progress_cnt = count($progress_list);
        $background_path = 'img/background.jpg';
        $checker_user_name = '';
        $checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';
        $checker_deal_time = '';
        $sc_approve_tag =0; //分包审批标识
        $sc_apply_tag = 0;//分包申请标识
        foreach($progress_list as $i => $j){
            //分包是否参与申请
            if($j['deal_type'] == '0'){
                $apply_model = Staff::model()->findByPk($j['deal_user_id']);
                $apply_contractor_id = $apply_model->contractor_id;
                if($main_conid != $apply_contractor_id){
                    $sc_apply_tag =1;
                }
            }
            //确认人
            if($j['deal_type'] == '10' && $j['step'] == '2'){
                $checker_step = $j['step'];
                $checker_user_name = $staff_list[$j['deal_user_id']];
                $checker_deal_time = Utils::DateToEn($j['deal_time']);
                $checker_model = Staff::model()->findByPk($j['deal_user_id']);
                $checker_role_id = $checker_model->role_id;
                $checker_role = $role_list[$checker_role_id];
                $checker_signature_path = $checker_model->signature_path;
                if(file_exists($checker_signature_path)) {
                    $checker_pic_html= '<img src="'.$checker_signature_path.'" height="30" width="30" />';
                }else{
                    $checker_pic_html = '<img src="'.$background_path.'" height="30" width="30"  /> ';
                }
            }
            //分包是否参与审批
            if($j['deal_type'] == '1'){
                $approve_model = Staff::model()->findByPk($j['deal_user_id']);
                $approve_contractor_id = $approve_model->contractor_id;
                if($main_conid != $approve_contractor_id){
                    $sc_approve_tag =1;
                }
            }
        }
//        var_dump($sc_apply_tag);
//        var_dump($sc_approve_tag);
//        exit;
        $ptwShsdList = ReportTemplate::ptwShsdList();

        $check_list = json_decode($check_list,true);
        if(count($check_list)>0){
            $check_id = $check_list[0]['check_id'];
            $check_list = RoutineCheck::detailList($check_id);//例行检查单
            $condition_set = json_decode($check_list[0]['condition_list'], true);
        }else{
            $condition_set = 'NULL';
        }

        $resultText = ReportShsdPdf::resultText();
        $condition_list = RoutineCondition::conditionList();
//        $data = $ptwShsdList['SHSD001'];
        $data = $ptwShsdList[$type_id];
        $_SESSION['type_id'] = $type_id;
        $bck_html= '<img src="'.$background_path.'" height="30" width="30"  />';

//        if($sc_apply_tag == '1'){
//            $e = -2;
//        }else{
//            $e = -3;
//        }
//
//        if($sc_approve_tag == '1'){
//            $e = -2;
//        }else{
//            $e = -3;
//        }

//        var_dump($e);
//        exit;
        $e = -2;
        $t = 0;
        if (!empty($region_list)){
            $secondary_str = "";
            $secondary_str_1 = "";
            $secondary_str_2 ="";
            $count = count($region_list);
            foreach($region_list as $region => $secondary){
                $t++;
                if($count == 1){
                    $secondary_str .= $region.' ';
                }else{
                    if($type_id == 'SHSD011' || $type_id == 'SHSD008'){
                        if($t == 2){
                            $secondary_str_1 .= 'From '.$region.' To ';
                        }
                        if($t == 1){
                            $secondary_str_2 .= $region.' ';
                        }
                    }else{
                        $secondary_str .= $region.'<br>';
                    }
                }
            }
            if($secondary_str_1){
                $secondary_str = $secondary_str_1.$secondary_str_2;
            }
        }else{
            $secondary_str = '';
        }
        foreach($data as $i => $list){
//            var_dump($i);
            //stage
            if($i == 'title'){

                $pdf->writeHTMLCell(0, 0, '', '', $program_name, 0, 1, 0, true, 'C', true);
//                $pdf->Cell(0, 0, $program_name, 0, 0, 'C', 0, '', 0);
//                $pdf->Cell(0, 0, 'PTW No.:'.$id, 0, 1, 'R', 0, '', 0);
                // Set some content to print
                if($type_id == 'SHSD005' || $type_id == 'SHSD006'){
                    $stage_title = $data['stage']['title'];
                    $html_left = "<b>$stage_title  $device_no)</b>";
                }else{
                    $html_left = "<b>{$data['stage']['title']}</b>";
                }
                $html_right = "<b>PTW No.:</b>{$id}";
                // Print text using writeHTMLCell()
                $pdf->writeHTMLCell(0, 0, '15', '', $html_left, 0, 0, 0, true, 'L', true);

                $pdf->writeHTMLCell(0, 0, '95', '', $html_right, 0, 1, 0, true, 'R', true);
//                $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
                $pdf->Ln(4);
            }
            $pdf->SetFont('times', '', 10, '', true); //英文
            if($i == 'stage'){
                $start_date = Utils::DateToEn($apply->start_date);
                $end_date = Utils::DateToEn($apply->start_date);
                $stage_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
                foreach($list as $stage_name => $stage_list){
                    if($stage_name != 'title' && $stage_name != 'note'){

                        if($stage_name == 'dept'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply_conid_name}</td></tr>";
                        }else if($stage_name == 'location'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$secondary_str}</td></tr>";
                        }else if($stage_name == 'description'){
                            $pdf->SetFont('droidsansfallback', '', 10, '', true);
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->work_content}</td></tr>";
                        }else if($stage_name == 'permit_date'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">Form: $start_date  $apply->start_time To: $end_date  $apply->end_time</td></tr>";
                        }else if($stage_name == 'location_1'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Location of Work']}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Tunnel: From Ring No.']}{$secondary_str}</td></tr>";
                        }else if($stage_name == 'machine_no'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$device_no}</td></tr>";
                        }else if($stage_name == 'machinery_movement'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Machinery Movement']}</td><td width=\"70%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Machinery Shifting From :']}&nbsp;&nbsp;{$secondary_str}</td></tr>";
                        }else if($stage_name == 'air_pressure'){
                            $stage_html .="<tr><td width=\"30%\" height=\"40px\"  nowrap=\"nowrap\"  align=\"left\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$stage_list['Compressed Air Pressure']}</td>";
                            if(is_array($condition_set)){
                                foreach ($condition_set as $key => $row) {
//                                $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文
                                    $name_en = $condition_list[$row['condition_id']]['condition_name_en'];
                                    $condition_name = $name_en;
                                    $stage_html .= '<td width="35%" style="text-align: left;border-width: 1px;border-color:gray gray gray gray" >&nbsp;' . $condition_name . '&nbsp;' . $row['remarks']  . '</td>';
                                }
                            }
                            $stage_html .= '</tr>';
                        }
                    }
                }
                $stage_html .="</table>";

                $pdf->writeHTML($stage_html, true, false, true, false, '');
                $pdf->SetFont('times', '', 10, '', true); //英文
                $stage_note = '<b>NOTE: </b>'.$list['note'];
                $pdf->MultiCell(0, 0, $stage_note, 0, 'L', 0, 1, '', '', true, 0, true, true, 20, 'M', true);
                $stage1_title = "<b>{$data['stage1']['title']}</b>";
                $pdf->writeHTMLCell(0, 0, '15', '', $stage1_title, 0, 1, 0, true, 'L', true);

                $stage1_note_1 = '<b>Note:</b> ';
                $stage1_note_2 = 'Condition of issue must be confirmed and ticked (√), if not applicable write ';
                $stage1_note_3 = '<b>‘NA’</b>';
                $pdf->writeHTMLCell(0, 0, '15', '', $stage1_note_1, 0, 0, 0, true, 'L', true);
                $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
                $pdf->writeHTMLCell(0, 0, '24', '', $stage1_note_2, 0, 0, 0, true, 'L', true);
                $pdf->SetFont('times', '', 10, '', true); //英文
                $pdf->writeHTMLCell(0, 0, '144', '', $stage1_note_3, 0, 1, 0, true, 'L', true);
            }

            $pdf->SetFont('droidsansfallback', '', 9, '', true); //英文

            if($i == 'stage1'){

                $condition_html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                if($apply->condition_set != '{}' && $apply->condition_set != '[]') {
                    $condition_set = json_decode($apply->condition_set, true);
                    $resultText = ReportShsdPdf::resultText();
                    $condition_list = PtwCondition::conditionList();
                    if (array_key_exists('name', $condition_set[0])) {
                        foreach ($condition_set as $key => $row) {
                            $condition_name = '';
                            $condition_name = $row['name_en'];
                            $condition_html .= '<tr><td >&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                        }
                    }
                    if (array_key_exists('id', $condition_set[0])) {
                        $condition_count = round(count($condition_set)/2)-1;
                        $total_condition_count = count($condition_set);
//                        var_dump($condition_set[0]);
//                        exit;
                        foreach ($condition_set as $key => $row) {

                            if($key<=$condition_count){
                                $name_en_1 = $condition_list[$condition_set[$key]['id']]['condition_name_en'];
                                $key_2 = $key+$condition_count+1;
                                if($key_2 >= $total_condition_count){
                                    $name_en_2 = '';
                                    $resultText_2 = '';
                                }else{
                                    $name_en_2 = $condition_list[$condition_set[$key+$condition_count+1]['id']]['condition_name_en'];
                                    $resultText_2 = $resultText[$condition_set[$key+$condition_count+1]['check']];
                                }
                                $condition_html .= '<tr><td width="40%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name_en_1 . '</td><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$condition_set[$key]['check']] . '</td>';
                                $condition_html .= '<td width="40%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $name_en_2 . '</td><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText_2 . '</td></tr>';
//                            }else{
                            }
                        }
                    }
                }
//                if($key%2!=1){
//                    $condition_html .= '<td width="40%" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;1111' .  '</td><td width="10%" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;222' . '</td></tr>';
//                }
                $condition_html .= '<tr><td height="80px" style="border-width: 1px;border-color:gray gray gray gray" colspan="4">&nbsp;State Other Safety Requirements:<br>' . $apply->devices . '</td></tr>';
                $condition_html .= '<tr><td style="border-width: 1px;border-color:white gray white " colspan="4">' . $list['note'] . '</td></tr>';
                $stage1_deal_user = $staff_list[$progress_list[0]['deal_user_id']];
                $stage_deal_model = Staff::model()->findByPk($progress_list[$e]['deal_user_id']);
                $signature_path = $stage_deal_model->signature_path;
//                $signature_path = '/filebase/record/2018/02/sign/pic/sign1518052755787_1.jpg';
                $pic_html= '<img src="'.$signature_path.'" height="30" width="30" />';
                if($checker_user_name != ''){
                    if($checker_step == '2'){
                        $e = $e+1;
                    }
                }
                $condition_html .= '<tr><td style="border-width: 1px;border-color:white white white " colspan="2">&nbsp;' . $list['APPLICANT NAME:'] .$stage1_deal_user. '</td><td style="border-width: 1px;border-color:white gray white " colspan="2" align="center">&nbsp;&nbsp;&nbsp;' . $list['STEC IN-CHARGE:'] .$checker_user_name.'</td></tr>';
                $stage1_deal_date = Utils::DateToEn($progress_list[0]['deal_time']);
                $condition_html .= '<tr><td  style=" border-width: 1px;border-color:white white gray " colspan="2">&nbsp;' . $list['DATE / TIME:'] .'&nbsp;'.$stage1_deal_date .'&nbsp;&nbsp;&nbsp;'. $list['SIGNATURE:'] .$pic_html.'</td><td  style="border-width: 1px;border-color:white gray gray " colspan="2" align="center" valign="bottom"> &nbsp;' . $list['SIGNATURE:'] .$checker_pic_html.'</td></tr>';
                $condition_html .= '</table>';
//                var_dump($condition_html);
//                exit;
                $pdf->writeHTML($condition_html, true, false, true, false, '');
            }

            $pdf->SetFont('times', '', 10, '', true); //英文

            if($i != 'title' && $i != 'stage' && $i != 'stage1' ){
                //$stage_deal_type = $progress_list[$e]['deal_type'];
                if($i == 'stage3' && $type_id == 'SHSD002' && $sc_apply_tag == '0') {
                    $e--;
                }
                if($i == 'stage3' && $type_id == 'SHSD003' && $sc_apply_tag == '0') {
                    $e--;
                }
                if($i == 'stage2' && $type_id != 'SHSD007'&& $type_id != 'SHSD002' && $type_id != 'SHSD003' && $sc_apply_tag == '0') {
                    $e--;
                }
                //$stage_deal_type
//                if($i != 'stage2' && $type_id != 'SHSD002' && $type_id != 'SHSD003' && $stage_deal_type == '10') {
//                    $e++;
//                }

                $html = '';
                if($e >= $progress_cnt){
                    $stage_deal_user = 'NULL';
                    $stage_deal_model = 'NULL';
                    $signature_path = '';
                    $role_id = '';
                    $user_phone = '';
                    $role_name = '';
                    $deal_date = '';
                }else{
                    $stage_deal_user = $staff_list[$progress_list[$e]['deal_user_id']];
                    $stage_deal_model = Staff::model()->findByPk($progress_list[$e]['deal_user_id']);
                    $signature_path = $stage_deal_model->signature_path;
                    $role_id = $stage_deal_model->role_id;
                    $user_phone = $stage_deal_model->user_phone;
                    $role_name = $role_list[$role_id];
                    $deal_date = Utils::DateToEn($progress_list[$e]['deal_time']);
                }

//                $signature_path = '/filebase/record/2018/02/sign/pic/sign1518052755787_1.jpg';
                $pic_html= '<img src="'.$signature_path.'" height="30" width="30"  /> ';

                if(array_key_exists('checklist',$list)){
                    $pdf->Cell(0, 0, 'The type of services are summarized below:', 0, 1, 'L', 0, '', 0);

                    $html = '<table style="border-width: 1px;border-color:gray gray gray gray">';
                    $html.='<tr><td  width="20%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Type of Services</td><td  width="15%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Present</td><td  width="15%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Not Present</td><td  width="50%" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Measures</td></tr>';

                    $stage_checklist = '';
                    foreach($progress_list as $i => $j){
                        if($j['check_list'] != '' && $j['check_list'] != '{}'){
                            $stage_checklist = $j['check_list'];
                            $stage_checklist = json_decode($stage_checklist,true);
                            $stage_check_id = $stage_checklist[0]['check_id'];
                        }
                    }
//                    var_dump($check_list);
//                    exit;
                    if(!empty($stage_checklist)){
                        $check_list = RoutineCheck::detailList($stage_check_id);//例行检查单
                        $condition_set = json_decode($check_list[0]['condition_list'], true);
                        $resultText = RoutineCheck::resultText();
                        $condition_list = RoutineCondition::conditionList();
//                        var_dump($condition_set);
//                        exit;
                        foreach ($condition_set as $key => $row) {
                            $name_en = $condition_list[$row['condition_id']]['condition_name_en'];
                            $condition_name = $name_en;
                            $html.= '<tr><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;' . $condition_name . '</td>';
                            if($row['flatStatus'] == '0'){
                                $html.='<td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['flatStatus']]  . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;'. '</td>';
                            }else{
                                $html.='<td style="text-align: center;border-width: 1px;border-color:gray gray gray gray">&nbsp;'   . '</td><td style="text-align: center;border-width: 1px;border-color:gray gray gray gray" >&nbsp;'. $resultText[$row['flatStatus']]. '</td>';
                            }
                            $html.='<td style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$row['remarks']. '</td></tr>';
                        }
                    }else{
                        $html.='<tr><td style="border-width: 1px;border-color:gray gray gray gray" colspan="4">&nbsp;Nil</td></tr>';
                    }

                    $html .= '</table><br>';
                }
//                var_dump($html);
//                exit;
                $j = 0;
                $pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                $pdf->SetFillColor(255,255,128);

                $title = $list['title'];
                $html_title = "<b>$title</b>";
                $pdf->writeHTMLCell(0, 0, '15', '', $html_title, 0, 1, 0, true, 'L', true);
//                $pdf->Cell(0, 0, $list['title'], 0, 1, 'L', 0, '', 0);

                if($html){
                    $html.= '<table >';
                }else{
                    $html = '<table >';
                }

                if(array_key_exists('The above work was completed on (date)',$list)){
//                    $deal_date = '2019-06-13 10:33:16';
                    if($deal_date){
                        $date = substr($deal_date,0,11);
                        $time = substr($deal_date,11,9);
                        $html .= '<tr><td colspan="2"><br>&nbsp;The above work was completed on (date) '. $date .' at (Time) '.$time.' hrs' . '<br></td></tr>';
                    }else{
                        $html .= '<tr><td colspan="2"><br>&nbsp;The above work was completed on (date)   ' .' at (Time)   '.'   hrs' . '<br></td></tr>';
                    }
                }
                if(array_key_exists('I, (NAME)',$list)){
                    $html .= '<tr><td colspan="2"><br>&nbsp;I, (NAME) '.$stage_deal_user.'  acknowledge the issuance of the permit<br></td></tr>';
                }
                foreach($list as $name => $value){
                    if($name == 'note' ){
                        $html .= '<tr ><td colspan="2">' . $list['note'] . '<br></td></tr>';
                    }else if($name != 'title' && $name != 'The above work was completed on (date)' && $name !='checklist' && $name !='I, (NAME)'){
                        if($name == 'NAME:'){
                            $val = $stage_deal_user;
                        }else if($name == 'DESIGNATION :'){
                            $val = $role_name;
                        }else if($name == 'Bck_DATE / TIME:'){
                            $val = $deal_date.$bck_html;
                        }else if($name == 'DATE / TIME:'){
                            $val = $deal_date.$bck_html;
                        }else if($name == 'SIGN :'){
                            $val = $pic_html;
                        }else if($name == 'SIGNATURE:'){
                            $val = $pic_html;
                        }else if($name == 'Checker_NAME:'){
                            $val = $stage_deal_user.$bck_html;
                        }else if($name == 'Checker_DESIGNATION:'){
                            $val = $role_name.$bck_html;
                        }else if($name == 'Checker_DATE / TIME:'){
                            $val = $deal_date;
                        }else if($name == 'Checker_SIGNATURE:'){
                            $val = $pic_html;
                        }else if($name == 'HANDPHONE / CONTACT NO. :'){
                            $val = $user_phone;
                        }else if($name == 'DATE / TIME:_last'){
                            $val = $deal_date.$bck_html;
                        }else if($name == 'SIGNATURE:_last'){
                            $val = $pic_html;
                        }else if($name == 'NAME:_last'){
                            $val = $stage_deal_user.$bck_html;
                        }else{
                            $val = '';
                        }

                        if($i == 'stage2' && $type_id != 'SHSD002' && $type_id != 'SHSD003' && $type_id != 'SHSD007'){
                            if($j%2==0){
                                if($sc_apply_tag == '0' || $sc_approve_tag == '0'){
                                    $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;</td>';
                                }else{
                                    $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;' . $val .'</td>';
                                }
                            }else{
                                if($sc_apply_tag == '0' || $sc_approve_tag == '0'){
                                    $html .= '<td>&nbsp;' . $value . '&nbsp;<br></td></tr>';
                                }else{
                                    $html .= '<td>&nbsp;' . $value . '&nbsp;' . $val .'<br></td></tr>';
                                }
                            }
                        }else if($i == 'stage3' && $type_id == 'SHSD002' && $sc_apply_tag == '0'){
                            if($j%2==0){
                                $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;</td>';
                            }else{
                                $html .= '<td>&nbsp;' . $value . '&nbsp;<br></td></tr>';
                            }
                        }else if($i == 'stage3' && $type_id == 'SHSD003' && $sc_apply_tag == '0'){
                            if($j%2==0){
                                $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;</td>';
                            }else{
                                $html .= '<td>&nbsp;' . $value . '&nbsp;<br></td></tr>';
                            }
                        }else{
                            if($j%2==0){
                                $html .= '<tr><td height="30px">&nbsp;' . $value . '&nbsp;' . $val .'</td>';
                            }else{
                                $html .= '<td>&nbsp;' . $value . '&nbsp;' . $val .'<br></td></tr>';
                            }
                        }

                        $j++;
                    }
                }
                if($j%2==1){
                    $html .= '<td>&nbsp;</td></tr>';
                }

                $html .= '</table>';
//                var_dump($html);
//                exit;
                $pdf->MultiCell('', '', $html, 1, 'J',false, 1, '', '',  true, 0,true, true, $maxh=0, 'T', false);
                $pdf->Ln(4);
            }
            $e++;
        }
        //输出PDF
        if(array_key_exists('ftp',$params)){
            $pdf->Output($filepath, 'F');  //保存到指定目录
        }else{
            $pdf->Output($filepath, 'F');  //保存到指定目录
            $pdf->Output($filepath, 'I');
        }

        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载默认PDF
    public static function downloadZjnyPDF($params,$app_id){

        $id = $params['id'];
        $apply = ApplyBasic::model()->findByPk($id);//许可证基本信息表
        $device_list = ApplyDevice::getDeviceList($id);//许可证申请设备表
        $worker_list = ApplyWorker::getWorkerList($apply->apply_id);//许可证申请人员表
        $company_list = Contractor::compAllList();//承包商公司列表
        $document_list = PtwDocument::queryDocument($id);//文档列表
        //$program_list =  Program::programAllList();//获取承包商所有项目
        //$ptw_type = ApplyBasic::typeList();//许可证类型表
        $ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
        $type_id = $apply->type_id;//许可证类型编号
        $program_id = $apply->program_id;
        //$programdetail_list = Program::getProgramDetail($program_id);
        //根据项目id得到总包商和根节点项目
        $region_list = PtwApplyBlock::regionList($id);//PTW项目区域
        $status_css = CheckApplyDetail::statusTxt();//PTW执行类型(成功)
        $reject_css = CheckApplyDetail::rejectTxt();//PTW执行类型(拒绝)

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($apply->record_time,0,4);//年
        $month = substr($apply->record_time,5,2);//月
        $day = substr($apply->record_time,8,2);//日
        $hours = substr($apply->record_time,11,2);//小时
        $minute = substr($apply->record_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id.'/PTW' . $id . $time .'.pdf';
        ApplyBasic::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/ptw/'.$apply->apply_contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
        $title = $apply->program_name;

        //if (file_exists($filepath)) {
        //$show_name = $title;
        //$extend = 'pdf';
        //Utils::Download($filepath, $show_name, $extend);
        //return;
        //}
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0'){
            $pro_params = json_decode($pro_params,true);
            //判断是否是迁移的
            if(array_key_exists('transfer_con',$pro_params)){
                $main_conid = $pro_params['transfer_con'];
            }else{
                $main_conid = $pro_model->contractor_id;//总包编号
            }
        }else{
            $main_conid = $pro_model->contractor_id;//总包编号
        }
        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'Permit-to-Work (PTW) No. (许可证申请编号):  ' . $apply->apply_id;

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
        $operator_id = $apply->apply_user_id;//申请人ID
        $add_operator = Staff::model()->findByPk($operator_id);//申请人信息
        $add_role = $add_operator->role_id;
        $roleList = Role::roleallList();//岗位列表
        $apply_first_time = Utils::DateToEn(substr($apply->record_time,0,10));
        $apply_second_time = substr($apply->record_time,11,18);
        //$path = '/opt/www-nginx/appupload/4/0000002314_TBMMEETINGPHOTO.jpg';
        //标题(许可证类型+项目)
        $title_html = "<h1 style=\"font-size: 300%\" align=\"center\">{$main_conid_name}</h1><br/><h2  style=\"font-size: 200%\" align=\"center\">Project (项目) : {$apply->program_name}</h2>
            <h2 style=\"font-size: 200%\" align=\"center\">PTW Type (许可证类型): {$ptw_type[$type_id]['type_name_en']} ({$ptw_type[$type_id]['type_name']})</h2><br/>";
        $html =$title_html;
        $pdf->writeHTML($html, true, false, true, false, '');
        $apply_y = $pdf->GetY();
        //申请人资料
        $apply_info_html = "<h2 align=\"center\"> Applicant Details (申请人详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Name(姓名)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Designation(职位)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">ID Number (身份证号码)</td></tr>";
        if($add_operator->work_pass_type = 'IC' || $add_operator->work_pass_type = 'PR'){
            if(substr($add_operator->work_no,0,1) == 'S' && strlen($add_operator->work_no) == 9){
                $work_no = 'SXXXX'.substr($add_operator->work_no,5,8);
            }else{
                $work_no = $add_operator->work_no;
            }
        }else{
            $work_no = $add_operator->work_no;
        }
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$add_operator->user_name}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$roleList[$add_role]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$work_no}</td></tr>";
        $apply_info_html .="<tr><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Company (公司)</td><td height=\"20px\" width=\"33%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date of Application (申请时间)</td><td height=\"20px\" width=\"34%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Electronic Signature (电子签名)</td></tr>";
        $apply_info_html .="<tr><td height=\"50px\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$company_list[$apply->apply_contractor_id]}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>&nbsp;{$apply_first_time}  {$apply_second_time}</td><td align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">&nbsp;</td></tr>";
        $apply_info_html .="</table>";
        //判断电子签名是否存在 $add_operator->signature_path
//        $content = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
        $content = $add_operator->signature_path;
        if($content){
            $pdf->Image($content, 150, $apply_y+40, 20, 9, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
        //工作内容
        $work_content_html = "<h2 align=\"center\">Nature of Work (工作详情)</h2><table style=\"border-width: 1px;border-color:gray gray gray gray\" >";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Title (标题)</td></tr>";
        $work_content_html .="<tr><td height=\"40px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br/>{$apply->title}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Description (描述)</td></tr>";
        $work_content_html .="<tr><td height=\"215px\" width=\"100%\" nowrap=\"nowrap\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->work_content}</td></tr>";
        $work_content_html .="<tr><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Start Time (开始时间)</td><td height=\"20px\" width=\"50%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">End Time (结束时间)</td></tr>";
        $work_content_html .="<tr><td height=\"50px\" width=\"50%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->start_time}</td><td height=\"50px\" width=\"50%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\"><br/><br/>{$apply->end_time}</td></tr></table>";


        $progress_list = CheckApplyDetail::progressList( $app_id,$apply->apply_id);//审批步骤详情
        $user_list = Staff::allInfo();//员工信息（包括已被删除的）

        $html_1 = $apply_info_html . $work_content_html;

        $pdf->writeHTML($html_1, true, false, true, false, '');

        //现场照片
        $pdf->AddPage();
        $pic_title_html = '<h2 align="center">Site Photo(s) (现场照片)</h2>';
        $pdf->writeHTML($pic_title_html, true, false, true, false, '');
        $y2= $pdf->GetY();
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
                                    }
                                }
                                //超过纵坐标换新页
                                if($info_y >= 220){
                                    $total_height[$cnt] = $title_height-43;
                                    $info_y = 10;
                                    $info_x = 15+3;
                                    $toatl_width = 0;
                                    $title_height = 45+10;
                                    $cnt++;
                                }else{
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
                }
            }
        }
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
//                    var_dump($pic);
//                    exit;
                    //for($o=0;$o<=8;$o++){
                    //    $pic[$o] =  "/opt/www-nginx/web/filebase/record/2019/02/tbm/pic/tbm_1550054744258_1.jpg";
                    //}
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
                }
            }
        }

        //安全条件
        $condition_html = '<h2 align="center">Safety Conditions (安全条件)</h2><table style="border-width: 1px;border-color:gray gray gray gray"><tr><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N(序号)</td><td height="20px" width="75%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Safety Conditions (安全条件)</td><td height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">YES / NO / N/A</td></tr>';
        if($apply->condition_set != '{}' && $apply->condition_set != '[]') {
            $condition_set = json_decode($apply->condition_set, true);
            $resultText = ApplyBasic::resultText();
            $condition_list = PtwCondition::conditionList();

            if (array_key_exists('name', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $condition_name = $row['name'] . '<br>' . $row['name_en'];
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td>&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
            if (array_key_exists('id', $condition_set[0])) {
                foreach ($condition_set as $key => $row) {
                    $condition_name = '';
                    $name = $condition_list[$row['id']]['condition_name'];
                    $name_en = $condition_list[$row['id']]['condition_name_en'];
                    $condition_name = $name . '<br>' . $name_en;
                    //$condition_name = $row['name'].'<br>'.$row['name_en'];
                    $condition_html .= '<tr><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . ($key + 1) . '</td><td  style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $condition_name . '</td><td  align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $resultText[$row['check']] . '</td></tr>';
                }
            }
        }
        $condition_html .= '</table>';
        $remark_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\" bgcolor=\"#E5E5E5\" align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">Remark (备注)</td></tr>";
        $remark_html .="<tr><td height=\"20px\" width=\"100%\" nowrap=\"nowrap\"  align=\"center\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$apply->devices}</td></tr></table>";

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list)){
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        }else{
            $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }

        $region_html .= '</table>';

        $html_2 =$condition_html . $remark_html . $region_html;
        $pdf->writeHTML($html_2, true, false, true, false, '');

        $pdf->AddPage();
        $approval_y = $pdf->GetY();
        $audit_html = '<h2 align="center">Approval Process (审批流程)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Person in Charge<br>(执行人)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Status<br>(状态)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray"> Date & Time<br>(日期&时间)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Remark<br>(备注)</td><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Electronic Signature<br>(电子签名)</td></tr>';
        //$audit_close_html = '<h5 align="center">' . Yii::t('license_licensepdf', 'progress_close_list') . '</h5><table border="1">
        //<tr><td width="10%">&nbsp;' . Yii::t('license_licensepdf', 'seq') . '</td><td width="30%">&nbsp;' . Yii::t('license_licensepdf', 'audit_person') . '</td><td width="30%"> '. Yii::t('license_licensepdf','audit_result').'</td><td width="30%"> ' . Yii::t('tbm_meeting', 'audit_date') . '</td></tr>';

        //$progress_close_list = WorkflowProgressDetail::progressList('PTW_CLOSE', $apply->apply_id);

        $progress_result = CheckApplyDetail::resultTxt();
        $j = 1;
        $y = 1;
        $info_xx = 170;
        if($approval_y > 260){
            $info_yy = 46;
        }else{
            $info_yy = $approval_y+24;
        }

        if (!empty($progress_list))
            foreach ($progress_list as $key => $row) {
                $content_list = $user_list[$row['deal_user_id']];
                $content = $content_list[0]['signature_path'];
                if($content != '' && $content != 'nil' && $content != '-1') {
                    if(file_exists($content)){
                        $pdf->Image($content, $info_xx, $info_yy, 21, 10, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                    }
                }
                if($row['status'] == 2){
                    $status = $reject_css[$row['deal_type']];
                }else{
                    $status = $status_css[$row['deal_type']];
                }
                $audit_html .= '<tr><td height="55px" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $row['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$status.'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.Utils::DateToEn($row['deal_time']).'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;'.$row['remark'].'</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray"></td></tr>';
                $j++;
                $info_yy += 15;
                if($info_yy > 271){
                    $info_yy = 46;
                }
            }
        $audit_html .= '</table>';
        $pdf->writeHTML($audit_html, true, false, true, false, '');

//        $pic_html = '<h4 align="center">Site Photo(s) (现场照片)</h4><table border="1">
//                <tr><td width ="100%" height="107px"></td></tr>';
//        $pic = $progress_list[0]['pic'];
//        if($pic != '') {
//            $pic = explode('|', $pic);
////            var_dump($pic);
////            exit;
//            $info_x = 40;
//            $info_y = 148;
//            foreach ($pic as $key => $content) {
////                $pic = 'C:\Users\minchao\Desktop\5.png';
//                if($content != '' && $content != 'nil' && $content != '-1') {
//                    if(file_exists($content)){
//                        $pdf->Image($content, $info_x, $info_yy + 13, 30, 23, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
//                        $info_x += 50;
//                    }
//                }
//            }
//        }
//        $pic_html .= '</table>';
        //现场人员
        $worker_html = '<br/><br/><h2 align="center">Member(s) (施工人员)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="5%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td  height="20px" width="15%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;ID Number<br>(身份证号码)</td><td  height="20px" width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Name<br>(姓名)</td><td height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Employee ID<br>(员工编号)</td><td height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Designation<br>(职位)</td><td height="20px" width="25%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Signature<br>(电子签名)</td></tr>';

        if (!empty($worker_list)){
            $i = 1;
            foreach ($worker_list as $user_id => $r) {
                $worker_model = Staff::model()->findAllByPk($user_id);//负责人
                if($worker_model[0]['work_pass_type'] = 'IC' || $worker_model[0]['work_pass_type'] = 'PR'){
                    if(substr($worker_model[0]['work_no'],0,1) == 'S' && strlen($worker_model[0]['work_no']) == 9){
                        $work_no = 'SXXXX'.substr($worker_model[0]['work_no'],5,8);
                    }else{
                        $work_no = $r['wp_no'];
                    }
                }else{
                    $work_no = $r['wp_no'];
                }
                $worker_sign_html = '<img src="' . $worker_model[0]['signature_path'] . '" height="30" width="30"  />';
                $worker_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $i . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $work_no . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $r['user_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $user_id . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$roleList[$r['role_id']].  '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$worker_sign_html.  '</td></tr>';
                $i++;
            }
        }
        $worker_html .= '</table>';

        //现场设备
        $primary_list = Device::primaryAllList();
        $device_type = DeviceType::deviceList();
        $devices_html = '<br/><br/><h2 align="center">Equipment (设备)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="10%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;S/N<br>(序号)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Registration No.<br>(设备编码)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Name<br>(设备名称)</td><td height="20px" width="30%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Equipment Type<br>(设备类型)</td></tr>';
        if (!empty($device_list)){
            $j =1;
            foreach ($device_list as $id => $list) {
                $devicetype_model = DeviceType::model()->findByPk($list['type_no']);//设备类型信息
                $device_type_ch = $devicetype_model->device_type_ch;
                $device_type_en = $devicetype_model->device_type_en;
                $devices_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $j . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $primary_list[$id] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $list['device_name'] . '</td><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' .$device_type_en.'<br>'.$device_type_ch . '</td></tr>';
                $j++;
            }
        }else{
            $devices_html .= '<tr><td align="center" colspan="4" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Nil</td></tr>';
        }
        $devices_html .= '</table>';

        //文档标签
        $document_html = '<br/><br/><h2 align="center">Attachment(s) (附件)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="20%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">S/N (序号)</td><td  height="20px" width="80%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">Document Name (文档名称)</td></tr>';
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

        $region_html = '<h2 align="center">Work Location (工作地点)</h2><table style="border-width: 1px;border-color:gray gray gray gray">
                <tr><td  height="20px" width="100%" nowrap="nowrap" bgcolor="#E5E5E5" align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;Project Area (项目区域)</td></tr>';
        if (!empty($region_list))
            foreach($region_list as $region => $secondary){
                $secondary_str = '';
                if(!empty($secondary))
                    foreach($secondary as $num => $secondary_region){
                        $secondary_str .= '['.$num.']:'.$secondary_region.'  ';
                    }

                $region_html .= '<tr><td align="center" style="border-width: 1px;border-color:gray gray gray gray">&nbsp;' . $region . '</td></tr>';
            }
        $region_html .= '</table>';

        $html_3 = $worker_html . $devices_html . $document_html;

        $pdf->writeHTML($html_3, true, false, true, false, '');


        //输出PDF
//        $pdf->Output($filepath, 'I');

        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //按项目查询安全检查次数（按类别分组）2019-03-19修改
    public static function AllCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        //分包项目
        if($args['contractor_id'] != '' && $pro_model->main_conid != $args['contractor_id'] ){
            $root_proid = $pro_model->root_proid;
            // $sql = "select count(apply_id) as cnt,program_id,type_id from ptw_apply_basic where program_id = '".$args['program_id']."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY type_id";
            $sql = "select count(t.apply_id) as cnt,t.program_id,t.type_id,li.type_name_en from ptw_apply_basic as t LEFT JOIN ptw_type_list as li ON li.type_id=t.type_id where t.program_id = '".$root_proid."' and t.record_time like '".$month."%' and t.apply_contractor_id = '".$args['contractor_id']."'  GROUP BY t.type_id";

        }else{
            //总包项目
            // $sql = "select count(apply_id) as cnt,program_id,type_id from ptw_apply_basic where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY type_id";
            $sql = "select count(t.apply_id) as cnt,t.program_id,t.type_id,li.type_name_en from ptw_apply_basic as t LEFT JOIN ptw_type_list as li ON li.type_id=t.type_id where t.record_time like '".$month."%' and t.program_id ='".$args['program_id']."'  GROUP BY t.type_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $list['type_name_en'];

            }
        }
        return $r;
    }
    //按项目查询安全检查次数（按公司分组）
    public static function CompanyCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $contractor_list = Contractor::compAllList();

        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(apply_id) as cnt,apply_contractor_id from ptw_apply_basic where program_id = '".$root_proid."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY apply_contractor_id";
        }else{
            //总包项目
            $sql = "select count(apply_id) as cnt,apply_contractor_id from ptw_apply_basic  where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY apply_contractor_id";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['apply_contractor_id']];
            }
        }
        return $r;
    }

    //按项目查询（按stutas把ptw_apply_basic表里的数据分组）
    public static function TestCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
        $month = Utils::MonthToCn($args['date']);

        if($pro_model->main_conid != $args['contractor_id']){
            //SUBSTRING_INDEX(a.add_operator, '|', 1)
            $root_proid = $pro_model->root_proid;
            $sql = "select count(a.apply_id) as cnt,a.program_id,b.deal_type from ptw_apply_basic a,bac_check_apply_detail b where a.program_id = '".$root_proid."' and a.record_time like '".$month."%' and a.apply_contractor_id = '".$args['contractor_id']."' and a.apply_id = b.apply_id and b.step = SUBSTRING_INDEX(a.add_operator, '|', 1)  GROUP BY b.deal_type";
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where program_id = '".$args['program_id']."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY status";
        }else{
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY status";
            $sql = "select count(a.apply_id) as cnt,a.program_id,b.deal_type from ptw_apply_basic a,bac_check_apply_detail b where a.record_time like '".$month."%' and a.program_id ='".$args['program_id']."' and a.apply_id = b.apply_id and b.step = SUBSTRING_INDEX(a.add_operator, '|', 1) GROUP BY b.deal_type";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['status'] =CheckApplyDetail::statusText($list['deal_type']);
            }
        }
        return $r;
    }

    //按项目查询（按stutas把ptw_apply_basic表里的数据分组）
    public static function StatusExcelList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
//        $args['start_date'] = Utils::DateToCn($args['start_date']);
//        $args['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        $args['month'] = Utils::MonthToCn($args['month']);

        if($pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(apply_id) as cnt,program_id,SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1) as dealtype from ptw_apply_basic where program_id = '".$root_proid."' and record_time like '%".$args['month']."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1)";
        }else{
            $sql = "select count(apply_id) as cnt,program_id,SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1) as dealtype from ptw_apply_basic where record_time like '%".$args['month']."%'  and program_id ='".$args['program_id']."'  GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(add_operator, '|', 2), '|', -1)";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $status_css = CheckApplyDetail::dealtypeTxt();//PTW执行类型(成功)
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['status'] =$status_css[$list['dealtype']];
            }
        }

        return $r;
    }

    /**
     * 导出表格查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryExcelList($args = array()) {

        $condition = '';
        $params = array();

        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' t.program_id ='.$args["program_id"] : ' AND t.program_id ='.$args["program_id"];
                $condition.= ( $condition == '') ? ' apply_contractor_id ='.$args["contractor_id"] : ' AND apply_contractor_id ='.$args["contractor_id"];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' t.program_id ='.$args["program_id"] : ' AND t.program_id ='.$args["program_id"];
            }
        }

        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' t.type_id='.$args['type_id'] : ' AND t.type_id='.$args['type_id'];
        }
        //操作开始时间
        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition.= ( $condition == '') ? ' t.record_time >='."'$start_date'" : ' AND t.record_time >='."'$start_date'";
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']) . " 23:59:59" ;
            $condition.= ( $condition == '') ? ' t.record_time <='."'$end_date'" : ' AND t.record_time <='."'$end_date'";
        }

        //Contractor
        if ($args['con_id'] != ''){
            //我提交+我审批＝我参与
            $condition.= ( $condition == '') ? ' t.apply_contractor_id = '.$args['con_id'] : ' AND t.apply_contractor_id = '.$args['con_id'];
        }
        if ($_REQUEST['q_order'] == '') {

            $order = 't.record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = 't'.substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = 't'.$_REQUEST['q_order'] . ' ASC';
        }
        $sql_1 = "select t.*,de.deal_user_id from ptw_apply_basic as t LEFT JOIN bac_check_apply_detail as de ON de.apply_id=t.apply_id  where  ".$condition." order by t.record_time desc";
        $command = Yii::app()->db->createCommand($sql_1);
        $rows = $command->queryAll();

//        var_dump($sql_1);
//        exit;
//        $sql_2 = "select t.apply_id,bl.* from ptw_apply_basic as t ,ptw_apply_block AS bl  where bl.apply_id = t.apply_id and".$condition ;
//        var_dump($sql_2);
//        exit;
//        $command_2 = Yii::app()->db->createCommand($sql_2);
//        $s = $command_2->queryAll();

        foreach($rows as $x => $y){
            $r[$y['apply_id']] = $y;
        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['rows'] = $r;
//        $rs['s'] = $s;



        return $rs;
    }

}
