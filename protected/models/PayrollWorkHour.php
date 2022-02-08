<?php

class PayrollWorkHour extends CActiveRecord {
    const STATUS_CONFIRM = 1; //已确认
    const STATUS_UNCONFIRMED = -1; //未确认
    const STATUS_DISSENT = 0; //异议
    public function tableName() {
        return 'payroll_work_hour';
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'working_date' => Yii::t('pay_payroll', 'working_date'),
            'program' => Yii::t('pay_payroll', 'program'),
            'user_name' => Yii::t('pay_payroll', 'user_name'),
            'work_hours' => Yii::t('pay_payroll', 'work_hours'),
            'overtime_hours' => Yii::t('pay_payroll', 'overtime_hours'),
            'record_time' => Yii::t('pay_payroll','record_time'),
            'status' => Yii::t('pay_payroll','status'),
            
        );
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Operator the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_CONFIRM => Yii::t('pay_payroll', 'STATUS_CONFIRM'),
            self::STATUS_UNCONFIRMED => Yii::t('pay_payroll', 'STATUS_UNCONFIRMED'),
            self::STATUS_DISSENT => Yii::t('pay_payroll', 'STATUS_DISSENT'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_CONFIRM => 'label-success', //已确认
            self::STATUS_UNCONFIRMED => ' label-warning', //未确认
            self::STATUS_DISSENT => ' label-danger', //未确认
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
    public static function queryList($page, $pageSize, $args = array(),$programlist) {
        //var_dump($args);
        $condition = '';
        $params = array();
//        var_dump($args);
//        exit;
        //user_phone
        if ($args['program_id'] != '') {
//            $condition.= ( $condition == '') ? ' program_id IN (:program_id)' : ' AND program_id IN (:program_id)';
//            $params['program_id'] = $programlist;
            $condition.= "program_id IN ('$programlist') ";
        }
//        var_dump($params['program_id']);
        //User_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        
        //User_name
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' user_name LIKE :user_name' : ' AND user_name LIKE :user_name';
            $params['user_name'] = '%' . $args['user_name'] . '%';
        }
        
        
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }
        
        if($args['start_date'] == '')
            $args['start_date'] = date('d M Y',strtotime('+1 day'));
        
	$start = str_replace('-', '', Utils::DateToCn(($args['start_date'])));
//        var_dump($start);
        if($args['end_date'] == ''){
            $end = $start;
	}else{
            $end = str_replace('-', '', Utils::DateToCn(($args['end_date'])));
	}
//        var_dump($end);
        $condition.= " AND working_date between '".$start."' and '".$end."'";
//        var_dump($condition);
//        exit;
        $total_num = PayrollWorkHour::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
            $order = 'working_date DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = $order;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = PayrollWorkHour::model()->findAll($criteria);
        
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    /**
     * 报表统计查询
     */
    public static function report($args,$programlist){
        $condition = '';
        if($args['start_date'] == '')
            $args['start_date'] = date('d M Y',strtotime('-1 day'));
        
	$start = str_replace('-', '', Utils::DateToCn(($args['start_date'])));
//        var_dump($start);
        if($args['end_date'] == ''){
            $end = $start;
	}else{
            $end = str_replace('-', '', Utils::DateToCn(($args['end_date'])));
	}
//        var_dump($end);
        $condition.= " contractor_id =  '".$args['contractor_id']."'";
        if ($args['program_id'] != '') {
            $condition.= "AND program_id IN ('$programlist')";
        }
        if ($args['status'] != '') {
            $condition.= "AND status = '".$args['status']."'";
        }
        if ($args['user_name'] != '') {
            $condition.=  "AND user_name LIKE '%".$args['user_name']."%'";
        }
        $condition.= " AND working_date between '".$start."' and '".$end."'";
        $sql = "SELECT *
                  from payroll_work_hour
                 where ".$condition."
                 order by working_date";
//        var_dump($sql);
//        exit;
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $key => $row){
//            $rs[$row['user_name']][$row['working_date']] = $row['work_hours'];
            $rs[$row['user_name']][] = $row;
        }
//	var_dump($rs);
        return $rs;
    }
    /**
     * 根据条件查出已有工时数据的员工
     */
    public static function listuser_id($args,$programlist) {
        
//        var_dump($program_list);
        $condition = '';
        if($args['start_date'] == '')
            $args['start_date'] = date('d M Y',strtotime('-1 day'));
        
	$start = str_replace('-', '', Utils::DateToCn(($args['start_date'])));

        $condition = "working_date = '".$start."'";
        if ($args['program_id'] != '') {
            $condition.= "AND program_id IN  ('".$programlist."')";
        }
        if ($args['status'] != '') {
            $condition.= "AND status = '".$args['status']."'";
        }
        if ($args['contractor_id'] != '') {
            $condition.= "AND contractor_id =  '".$args['contractor_id']."'";
        }
        
        $sql = "SELECT *
                  from payroll_work_hour
                 where ".$condition."
                 order by working_date";
//        var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        if (count($rows) > 0) {
//            foreach ($rows as $key => $row) {
//                $rs[$row['user_id']] = $row['user_name'];
//            }
//        }
        return $rows;
    }
     /**
     * 工时修改
     */
    public static function setdate($args){
//        $model = self::model()->find('working_date=:working_date AND user_name=:user_name', array(':working_date' => $args['working_date'],':user_name' => $args['user_name']));
//
//        $model->working_date = $args['working_date'];
//        $rs = $model->save();
        $sql = "SELECT * FROM payroll_work_hour WHERE user_id = '".$args['user_id']."' AND working_date ='".$args['working_date']."' AND program_id = '".$args['program_id']."'";
//        var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
//        var_dump($data);
        if(!empty($data)){
            if($data[0]['status'] == 1){
                $r['msg'] = Yii::t('pay_payroll', 'error_workhour_update');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            if($args['name'] == 'overtime_hours'){
                $sql = "UPDATE payroll_work_hour SET overtime_hours = '".$args['value']."',status ='".$args['status']."'  WHERE user_id = '".$args['user_id']."' AND working_date ='".$args['working_date']."' AND program_id ='".$args['program_id']."'";
            }
            if($args['name'] == 'work_hours'){
                $sql = "UPDATE payroll_work_hour SET work_hours = '".$args['value']."',status ='".$args['status']."'  WHERE user_id = '".$args['user_id']."' AND working_date ='".$args['working_date']."'AND program_id ='".$args['program_id']."'";
            }
        }else{
            $record_time = date('Y-m-d H:i:s');
            if($args['name'] == 'overtime_hours')
                $sql = "INSERT INTO payroll_work_hour(program_id,user_id,user_name,role_id,contractor_id,overtime_hours,working_date,record_time,operator,operator_id,status)VALUES('".$args['program_id']."','".$args['user_id']."','".$args['user_name']."','".$args['role_id']."','".$args['contractor_id']."','".$args['value']."','".$args['working_date']."','".$record_time."','".$args['operator']."','".$args['operator_id']."','".$args['status']."')";
        
            if($args['name'] == 'work_hours')
                $sql = "INSERT INTO payroll_work_hour(program_id,user_id,user_name,role_id,contractor_id,work_hours,working_date,record_time,operator,operator_id,status)VALUES('".$args['program_id']."','".$args['user_id']."','".$args['user_name']."','".$args['role_id']."','".$args['contractor_id']."','".$args['value']."','".$args['working_date']."','".$record_time."','".$args['operator']."','".$args['operator_id']."','".$args['status']."')";
        }
        
//        exit;
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->execute();
//        var_dump($rows);
        if($rows ==1 || $rows == 2){
            $r['msg'] = Yii::t('common', 'success_set');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('common', 'error_set');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    /**
     * 查找项目组下的成员
     */
    public static function myUserList($page, $pageSize,$args = array()) {

        $sql = "SELECT a.user_id,a.program_id,b.user_name FROM bac_program_user_q  a,bac_staff b  WHERE  a.user_id =b.user_id and  a.contractor_id='".$args['contractor_id']."' and a.root_proid='".$args['program_id']."' ";
//        $sql = "SELECT a.user_id FROM bac_program_user a WHERE a.program_id=:program_id and a.contractor_id=:contractor_id";
//        $command = Yii::app()->db->createCommand($sql);
        $command =Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
//        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
//        $command->bindParam(":contractor_id", $args['contractor_id'], PDO::PARAM_INT);
//        $rows = $command->queryAll();
//        var_dump($sql);
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $currentPage = (int)$page;
//            var_dump($pages->currentPage);
//            exit;
        $command->bindValue(':offset', $currentPage*$pages->pageSize);//$pages->getOffset();  
        $command->bindValue(':limit', $pages->pageSize);//$pages->getLimit(); 
        $rows = $command->queryAll();
//        var_dump($rows);
        $total_num = yii::app()->db->createCommand($sql)->query()->rowCount;
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $r[$key]['user_id'] = $row['user_id'];
                $r[$key]['program_id'] = $row['program_id'];
                $r[$key]['user_name'] = $row['user_name'];
            }
        }
//        var_dump($total_num);
//        exit;
        $criteria=new CDbCriteria();
        
//        $pages->applyLimit($criteria);

//        $rs['status'] = 0;
//        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['r'] = $r;

        return $rs;
    }
    /**
     * 查出总包下的承包商所属的分包项目
     */
    public static function programList($args){
        $sql = "select program_id from bac_program where root_proid ='".$args['program_id']."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach ($rows as $key => $row) {
            $rs[$key] = $row['program_id'];
        }
        $program_list = implode("','",$rs);
        return $program_list;
    }
}

