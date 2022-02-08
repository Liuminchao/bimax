<?php

/**
 * 工资
 * @author LiuMinchao
 */
class PayrollSalary extends CActiveRecord {

    const STATUS_NORMAL = 1; //已审核
    const STATUS_DISABLE = 0; //未审核

    /**
     * @return string the associated database table name
     */
    public static $table_name = "payroll_salary_";
    public function __construct($month = '') {
//        var_dump('__construct');
        if ($month === '') {
            self::$table_name.= date("Ym");
            parent::__construct ();
        }else{
            self::$table_name.= $month;
            parent::__construct ();
        }
    }
    public  function tableName() {
//        var_dump('tableName');
//        $table = "payroll_salary_" . date("Ym");
//        $table = "payroll_salary_" . date;
        return self::$table_name;
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($month = '', $className = __CLASS__) {
//        var_dump('model');
        if($month === ''){
            self::$table_name.= date("Ym");
        }else{
            self::$table_name.= $month;
        } 	
        return parent::model($className);
    }
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_name' => Yii::t('pay_payroll','user_name'),
            'wage_date' => Yii::t('pay_payroll', 'wage_date'),
            'wage'=> Yii::t('pay_payroll', 'wage'),
            'work_hours'=> Yii::t('pay_payroll', 'work_hours'),
            'basic_wage' => Yii::t('pay_payroll', 'basic_wage'),
            'wage_overtime' => Yii::t('pay_payroll', 'wage_overtime'),
            'overtime_hours' => Yii::t('pay_payroll', 'overtime_hours'),
            'overtime_wage' => Yii::t('pay_payroll', 'overtime_wage'),
            'allowance'=>Yii::t('pay_payroll','allowance'),
            'allowance_content'=>Yii::t('pay_payroll','allowance_content'),
            'deduction_wage'=>Yii::t('pay_payroll','deduction_wage'),
            'total_wage'=>Yii::t('pay_payroll','total_wage'),
            'record_time' =>Yii::t('pay_payroll','record_time'),
        );
    }
     //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('pay_payroll', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('pay_payroll', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //已启用
            self::STATUS_DISABLE => ' label-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    public static function createSalaryTable($month){
        if($month == '')
            $month = date('Ym');
        $table_prefix = 'payroll_salary';
		$table_name = $table_prefix.'_'.$month;

        return $table_name;
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
//        var_dump($args);
//        exit;

//        var_dump($params['program_id']);
        //User_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        
        
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }
        

        //summary_id
        if ($args['summary_id'] != '') {
            $condition.= ( $condition == '') ? ' summary_id =:summary_id ' : ' AND summary_id =:summary_id ';
            $params['summary_id'] = $args['summary_id'];
        }
        
        //开始时间
        if ($args['start_date'] != '') {
            $date1 = str_replace("-", "", $args["start_date"]);
            $table = "payroll_salary_".substr($date1, 0,6);   
        }else{
            $table = "payroll_salary_".date("Ym");
        }
        //var_dump($condition);
//        $total_num = PayrollSalary::model()->count($condition, $params); //总记录数
        
        $total_nums = Yii::app()->db->createCommand()
                ->select("count(1) cnt")
                ->from("$table T")
                ->where($condition, $params)
                ->queryRow();

        $total_num = $total_nums["cnt"];
        
        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
            $order = 'wage_date DESC';
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

//        $rows = PayrollSalary::model()->findAll($criteria);
        $rows = Yii::app()->db->createCommand()
            ->select("*")
            ->from("$table T")
            ->where($condition, $params)
            ->order($order)
            ->limit($pageSize)
            ->offset($page * $pageSize)
            ->queryAll();
        
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //计算工资
    public static function calculateStaff($args){
//        ini_set('max_execution_time', '100');
        set_time_limit(0);
        if ($args['start_date'] == '') {
            $r['msg'] = Yii::t('pay_payroll', 'Error start_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['end_date'] == '') {
            $r['msg'] = Yii::t('pay_payroll', 'Error end_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $contractor_id = Yii::app()->user->contractor_id;
        //根据员工号码得到员工信息
        if($args['user_phone']){
            $user_phone = $args['user_phone'];
            $user_list = Staff::phoneList($user_phone);
            if ($user_list == NULL) {
                $r['msg'] = Yii::t('pay_payroll', 'Error user_phone is null');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }else{
            $user_list = Staff::staffList($contractor_id);
        }
        $start = str_replace('-', '', Utils::DateToCn($args['start_date']));
        $end = str_replace('-', '', Utils::DateToCn($args['end_date']));
        $default = 0;
        $default_content = Yii::t('pay_payroll', 'No_allowance');
        $wage_month = substr($start,0,6);
        $table = self::createSalaryTable($wage_month);
        $now_month = date("Ym");
        $now_day = date("d");
        $wage_day = substr($end,6,2);
        if($wage_month == $now_month){
            if($wage_day >= $now_day){
                $r['msg'] = Yii::t('pay_payroll', 'Error now_day is last');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }
        $status = self::STATUS_DISABLE;
        
        
////        $user_list = Staff::staffList($contractor_id);
//        //查找汇总表中在该日期段已有的记录
//        $sql = "SELECT user_name,user_id FROM payroll_salary_summary WHERE contractor_id =:contractor_id AND start_date =:start_date AND end_date =:end_date AND status = :status";
//        $command = Yii::app()->db->createCommand($sql);
//        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
//        $command->bindParam(":start_date", $start, PDO::PARAM_INT);
//        $command->bindParam(":end_date", $end, PDO::PARAM_INT);
//        $command->bindParam(":status", $status, PDO::PARAM_INT);
//        $list = $command->queryAll();
//        if (count($list) > 0) {
//            foreach ($list as $key => $row) {
//                $idlist[$row['user_id']] = $row['user_name'];
//            }
//            //比较数组得到差集
//            $user_list = array_diff_key($user_list,$idlist);
//            if(empty($user_list)){
//                goto end;
//            }
//        }
        
//        //查找详细表中在该日期段已有的记录
//        $sql = "SELECT user_id,wage_date FROM payroll_salary WHERE wage_date between '".$start."' and '".$end."' AND contractor_id = '".$contractor_id."'";
//        $command = Yii::app()->db->createCommand($sql);
//        $datelist = $command->queryAll();
//        var_dump($datelist);
//        exit;
        //添加员工工资汇总信息
        $result = PayrollSalarysummary::insertSummary($user_list, $start, $end);
        $info = PayrollSalarysummary::infoQuery($contractor_id, $start, $end);
        
        //添加员工工资详细信息
        foreach($user_list as $id => $name){
            for($i = strtotime($start); $i <= strtotime($end); $i += 86400) {
                $wage_date = date('Ymd',$i);
                //查找详细表中在该日期段已有的记录
                $sql = "SELECT * FROM ".$table." WHERE wage_date = '".$wage_date."' AND user_id = '".$id."'";
                $command = Yii::app()->db->createCommand($sql);
                $datelist = $command->queryAll();
//                var_dump($datelist);
                if(empty($datelist)){
                    $sql = "INSERT INTO ".$table." (summary_id,user_id,user_name,contractor_id,wage,wage_overtime,work_hours,overtime_hours,basic_wage,overtime_wage,allowance,allowance_content,total_wage,wage_date,status) VALUES(:summary_id,:user_id,:user_name,:contractor_id,:wage,:wage_overtime,:work_hours,:overtime_hours,:basic_wage,:overtime_wage,:allowance,:allowance_content,:total_wage,:wage_date,:status)";
                        $command = Yii::app()->db->createCommand($sql);
//                        $wage_date = date('Ymd',$i);
                        $command->bindParam(":summary_id", $info[$id]['summary_id'], PDO::PARAM_INT);
                        $command->bindParam(":user_id", $id, PDO::PARAM_INT);
                        $command->bindParam(":user_name", $name, PDO::PARAM_INT);
                        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                        $command->bindParam(":wage", $default, PDO::PARAM_INT);
                        $command->bindParam(":wage_overtime", $default, PDO::PARAM_INT);
                        $command->bindParam(":work_hours", $default, PDO::PARAM_INT);
                        $command->bindParam(":overtime_hours", $default, PDO::PARAM_INT);
                        $command->bindParam(":basic_wage", $default, PDO::PARAM_INT);
                        $command->bindParam(":overtime_wage", $default, PDO::PARAM_INT);
                        $command->bindParam(":allowance", $default, PDO::PARAM_INT);
                        $command->bindParam(":allowance_content", $default_content, PDO::PARAM_INT);
                        $command->bindParam(":total_wage", $default, PDO::PARAM_INT);
                        $command->bindParam(":wage_date", $wage_date, PDO::PARAM_INT);
                        $command->bindParam(":status", $status, PDO::PARAM_INT);
                        $rs = $command->execute();
                }
            }
        }
        end:
        $user_list = Staff::staffList($contractor_id);
        $num = 0;
        //默认员工工时数组
        foreach($user_list as $id => $name){
            for($i = strtotime($start); $i <= strtotime($end); $i += 86400) {
                $date=date('Ymd',$i);
                $num++;
                $default_hour[$num]['user_id'] = $id;
                $default_hour[$num]['user_name'] = $name;
                $default_hour[$num]['working_date'] = $date;
                $default_hour[$num]['work_hours'] = 0;
                $default_hour[$num]['overtime_hours'] = 0;
            }
        }
//        var_dump($default_hour);
//        exit;
       // 1.先得到每个员工的时薪，加班时薪
        
        $sql = "SELECT a.user_id,a.user_name,b.wage,b.overtime_wage FROM bac_staff a LEFT JOIN payroll_wage b ON a.contractor_id = b.contractor_id AND a.role_id = b.role_id AND a.nation_type = b.nation_type WHERE a.contractor_id = '".$contractor_id."' ORDER BY user_id asc";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $wage[$row['user_id']]['user_name'] = $row['user_name'];
                if($row['wage']){
                    $wage[$row['user_id']]['wage'] = $row['wage'];
                }else{
                    $wage[$row['user_id']]['wage'] = 0;
                }
                if($row['overtime_wage']){
                    $wage[$row['user_id']]['overtime_wage'] = $row['overtime_wage'];
                }else{
                    $wage[$row['user_id']]['overtime_wage'] = 0;
                }
                
            }
        }
//        var_dump($wage);
//        exit;
        // 2.在得到每个员工的工时，加班工时（按日期）的数组
        $sql ="SELECT user_id,user_name,working_date,SUM(work_hours) as work_hours,SUM(overtime_hours) as overtime_hours  FROM payroll_work_hour WHERE contractor_id = '".$contractor_id."' AND working_date BETWEEN '".$start."' AND '".$end."' AND status=1 GROUP BY user_name,working_date ORDER BY working_date desc";
        $command = Yii::app()->db->createCommand($sql);
        $work_hour = $command->queryAll();
//        var_dump(count($work_hour));

        if (count($work_hour) > 0) {
            foreach ($default_hour as $k => $r) {
                foreach($work_hour as $key => $row){
                    if($row['working_date'] == $r['working_date'] && $row['user_id'] == $r['user_id']){
//                        var_dump($key);
                        $default_hour[$k]['work_hours'] = $row['work_hours'];
                        $default_hour[$k]['overtime_hours'] = $row['overtime_hours'];
                    }
                }
            }      
        }
//        var_dump($default_hour);
//        exit;
       // 3.然后得到每个员工的补贴以及扣款金额（按日期）
        $y=0;
        $sql ="select user_id,user_name,allowance_date,allowance,allowance_content from payroll_allowance  where contractor_id='".$contractor_id."' AND allowance_date BETWEEN '".$start."' AND '".$end."'ORDER BY allowance_date desc";
        $command = Yii::app()->db->createCommand($sql);
        $t = $command->queryAll();
        if (count($t) > 0) {
            for($i = strtotime($start); $i <= strtotime($end); $i += 86400) {
                $date=date('Ymd',$i);
                foreach ($t as $key => $te) {
                    foreach($user_list as $id => $name){
                        if($date == $te['allowance_date'] && $te['user_id'] == $id){
                            $y++;
                            $allowance[$y]['allowance_date'] = $te['allowance_date'];
                            $allowance[$y]['allowance'] += $te['allowance'];
                            $allowance[$y]['user_id'] += $te['user_id'];
                            $allowance[$y]['allowance_content'].= $te['allowance_content'].':'.$te['allowance'];
                        }
                    }
                }
            }
        }
//        var_dump($allowance);
//        exit;
        //4.计算详细工资
        $j = 0;
        foreach($wage as $key => $value){
            foreach($default_hour as $k => $v){
                if($key==$v['user_id']){
                    $j++;
                    $basic_wage = $value['wage']*$v['work_hours'];
                    $overtime_wage = $value['overtime_wage']*$v['overtime_hours'];
                    $total_wage = $basic_wage + $overtime_wage;
                    $salary[$j]['user_id']= $key;
                    $salary[$j]['user_name']= $value['user_name'];
                    $salary[$j]['wage']= $value['wage'];
                    $salary[$j]['wage_overtime']= $value['overtime_wage'];
                    $salary[$j]['work_hours']= $v['work_hours'];
                    $salary[$j]['overtime_hours']= $v['overtime_hours'];
                    $salary[$j]['basic_wage']=$basic_wage;
                    $salary[$j]['overtime_wage']=$overtime_wage;
                    $salary[$j]['allowance']=0;
                    $salary[$j]['allowance_content']="";
                    $salary[$j]['total_wage']=$total_wage;
                    $salary[$j]['wage_date']=$v['working_date'];
                }
            }
        }
        //在工资数组中添加补贴
        if (count($allowance) > 0){
            foreach($allowance as $y => $f){
                foreach($salary as $k => $r){
                    if($f['user_id'] == $r['user_id'] && $f['allowance_date'] == $r['wage_date']){
                        $allowance_content = $f['allowance_content'];
                        $salary[$k]['allowance']+=$f['allowance'];
                        $salary[$k]['allowance_content'].=$allowance_content;
                        $salary[$k]['total_wage']+=$f['allowance'];
                    }
//                    if($r['allowance_content']==''){
//                        $salary[$k]['allowance_content']=$default_content;
//                    }
                }
            }
        }
//        var_dump($salary);
//        exit;
        //5.计算汇总工资
        foreach($user_list as $k => $v){
            foreach($salary as $t => $h){
                if($k == $h['user_id']){
                    $salary_summary[$k]['user_id'] = $k;
                    $salary_summary[$k]['user_name']= $v;
                    $salary_summary[$k]['wage'] = $h['wage'];
                    $salary_summary[$k]['wage_overtime'] = $h['wage_overtime'];
                    $salary_summary[$k]['work_hours']+=$h['work_hours'];
                    $salary_summary[$k]['overtime_hours']+=$h['overtime_hours'];
                    $salary_summary[$k]['basic_wage']+=$h['basic_wage'];
                    $salary_summary[$k]['overtime_wage']+=$h['overtime_wage'];
                    $salary_summary[$k]['allowance']+=$h['allowance'];
                    $salary_summary[$k]['total_wage']+=$h['total_wage'];
                    $salary_summary[$k]['start_date']=$start;
                    $salary_summary[$k]['end_date']=$end;
                    $salary_summary[$k]['wage_month']=$wage_month;
                }
            }
        }
//        var_dump($salary_summary);
//        exit;
//        var_dump($salary);
//        exit;
        //填充详细记录
        foreach($salary as $key => $h){
            $sql = "UPDATE ".$table." set wage=:wage,wage_overtime=:wage_overtime,work_hours=:work_hours,overtime_hours=:overtime_hours,basic_wage=:basic_wage,overtime_wage=:overtime_wage,allowance=:allowance,allowance_content=:allowance_content,total_wage=:total_wage WHERE  user_id = :user_id AND wage_date = :wage_date AND status = :status";
                    if($h['allowance_content']==""){
                        $h['allowance_content']=$default_content;
                    }
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":user_id", $h['user_id'], PDO::PARAM_INT);
//                    $command->bindParam(":user_name", $h['user_name'], PDO::PARAM_INT);
//                    $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                    $command->bindParam(":wage", $h['wage'], PDO::PARAM_INT);
                    $command->bindParam(":wage_overtime", $h['wage_overtime'], PDO::PARAM_INT);
                    $command->bindParam(":work_hours", $h['work_hours'], PDO::PARAM_INT);
                    $command->bindParam(":overtime_hours", $h['overtime_hours'], PDO::PARAM_INT);
                    $command->bindParam(":basic_wage", $h['basic_wage'], PDO::PARAM_INT);
                    $command->bindParam(":overtime_wage", $h['overtime_wage'], PDO::PARAM_INT);
                    $command->bindParam(":allowance", $h['allowance'], PDO::PARAM_INT);
                    $command->bindParam(":allowance_content", $h['allowance_content'], PDO::PARAM_INT);
                    $command->bindParam(":total_wage", $h['total_wage'], PDO::PARAM_INT);
                    $command->bindParam(":wage_date", $h['wage_date'], PDO::PARAM_INT);
                    $command->bindParam(":status", $status, PDO::PARAM_INT);
            $rs2 = $command->execute();
        }
        //填充汇总记录
        foreach($salary_summary as $key => $h){
            $sql = "UPDATE payroll_salary_summary set wage=:wage,wage_overtime=:wage_overtime,work_hours=:work_hours,overtime_hours=:overtime_hours,basic_wage=:basic_wage,overtime_wage=:overtime_wage,allowance=:allowance,total_wage=:total_wage WHERE  user_id = :user_id AND start_date = :start_date AND end_date = :end_date AND status = :status";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":user_id", $h['user_id'], PDO::PARAM_INT);
//                    $command->bindParam(":user_name", $h['user_name'], PDO::PARAM_INT);
//                    $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                    $command->bindParam(":wage", $h['wage'], PDO::PARAM_INT);
                    $command->bindParam(":wage_overtime", $h['wage_overtime'], PDO::PARAM_INT);
                    $command->bindParam(":work_hours", $h['work_hours'], PDO::PARAM_INT);
                    $command->bindParam(":overtime_hours", $h['overtime_hours'], PDO::PARAM_INT);
                    $command->bindParam(":basic_wage", $h['basic_wage'], PDO::PARAM_INT);
                    $command->bindParam(":overtime_wage", $h['overtime_wage'], PDO::PARAM_INT);
                    $command->bindParam(":allowance", $h['allowance'], PDO::PARAM_INT);
                    $command->bindParam(":total_wage", $h['total_wage'], PDO::PARAM_INT);
                    $command->bindParam(":start_date", $h['start_date'], PDO::PARAM_INT);
                    $command->bindParam(":end_date", $h['end_date'], PDO::PARAM_INT);
                    $command->bindParam(":status", $status, PDO::PARAM_INT);
            $rs2 = $command->execute();
        }
//        var_dump($rs2);
//        exit;
//        if($rs2==0){
//            $r['msg'] = Yii::t('pay_payroll', 'maintain_calculate');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//        }else 
        if($rs2 >= 0){
            $r['msg'] = Yii::t('pay_payroll', 'success_calculate');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('pay_payroll', 'error_calculate');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    //编辑详细工资
    public static function updateDetail($args) {
        
        $model = PayrollSalary::model()->find('user_id=:id AND wage_date=:date', array(':id' => $args['user_id'],':date'=>$args['wage_date']));
        
        if ($model == null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $wage_month = substr($args['wage_date'],0,6);
        $table = self::createSalaryTable($wage_month);
        try {
            $count = PayrollSalary::model()->updateAll(array('basic_wage'=>$args['basic_wage'],'overtime_wage'=>$args['overtime_wage'],'total_wage'=>$args['total_wage']), 'user_id=:user_id and wage_date=:wage_date', array(':user_id'=>$args['user_id'], ':wage_date'=>$args['wage_date']));
            
            $sql = "SELECT * FROM ".$table." WHERE summary_id = '".$args['summary_id']."'";
            $command = Yii::app()->db->createCommand($sql);
            $t = $command->queryAll();
            if (count($t) > 0) {
                foreach($t as $k => $v){
                    $summary[$v['summary_id']]['basic_wage'] += $v['basic_wage'];
                    $summary[$v['summary_id']]['overtime_wage'] += $v['overtime_wage'];
                    $summary[$v['summary_id']]['deduction_wage'] += $v['deduction_wage'];
                    $summary[$v['summary_id']]['total_wage'] += $v['total_wage'];
                }
            }
            $result = PayrollSalarysummary::model()->updateAll(array('basic_wage'=>$summary[$args['summary_id']]['basic_wage'],'overtime_wage'=>$summary[$args['summary_id']]['overtime_wage'],'total_wage'=>$summary[$args['summary_id']]['total_wage']), 'summary_id=:summary_id ', array(':summary_id'=>$args['summary_id']));

            if ($result>=0) {
                
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //工资审核
    public static function storage($args){
        set_time_limit(0);
        if ($args['start_date'] == '') {
            $r['msg'] = Yii::t('pay_payroll', 'Error start_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['end_date'] == '') {
            $r['msg'] = Yii::t('pay_payroll', 'Error end_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $start = str_replace('-', '', Utils::DateToCn($args['start_date']));
        $end = str_replace('-', '', Utils::DateToCn($args['end_date']));
        $wage_month = substr($start,0,6);
        $table = self::createSalaryTable($wage_month);
        $status = self::STATUS_NORMAL;
        $contractor_id = Yii::app()->user->contractor_id;
        //根据员工号码得到员工信息
        if($args['user_phone']){
            $user_phone = $args['user_phone'];
            $user_list = Staff::phoneList($user_phone);
            if ($user_list == NULL) {
                $r['msg'] = Yii::t('pay_payroll', 'Error user_phone is null');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }else{
            $user_list = Staff::staffList($contractor_id);
        }
        foreach($user_list as $id => $name){
            $sql = "SELECT * FROM payroll_salary_summary  WHERE user_id='".$id."' AND start_date ='".$start."' AND end_date = '".$end."'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
        }
//        var_dump($rows);
//        exit;
        if (empty($rows)) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        var_dump($rows);
//        exit;
        if ($rows[0]['status']==1){
            $r['msg'] = Yii::t('pay_payroll', 'salary_audit_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        foreach($user_list as $id => $name){
            for($i = strtotime($start); $i <= strtotime($end); $i += 86400) {
                $allowance_date = date('Ymd',$i);
                $sql = "UPDATE payroll_allowance SET status ='".$status."' WHERE user_id='".$id."' AND allowance_date = '".$allowance_date."'";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->execute();
            }
        }
        foreach($user_list as $id => $name){
            $sql = "UPDATE payroll_salary_summary SET status ='".$status."' WHERE user_id='".$id."' AND start_date ='".$start."' AND end_date ='".$end."'";
            $command = Yii::app()->db->createCommand($sql);
            $rs = $command->execute();
        }
//                    var_dump($rs);
//            exit;
        if($rs!=0){
            foreach($user_list as $id => $name){
                for($i = strtotime($start); $i <= strtotime($end); $i += 86400) {
                    $wage_date = date('Ymd',$i);
                    $sql = "UPDATE ".$table." SET status ='".$status."' WHERE user_id='".$id."' AND wage_date = '".$wage_date."'";
                    $command = Yii::app()->db->createCommand($sql);
                    $rows = $command->execute();
                }
            }
        }
        if($rows!=0){
            $r['msg'] = Yii::t('pay_payroll', 'success_storage');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('pay_payroll', 'error_storage');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
//    //查询工资明细表中已有的记录
//    public static function querySalary($wage_date){
//        $status = self::STATUS_DISABLE;
//        $sql = "SELECT * FROM payroll_salary WHERE wage_date = '".$wage_date."' AND status = '".$status."'";
//        $command = Yii::app()->db->createCommand($sql);
//        $detaillist = $command->queryAll();
//    }
}
