<?php

/**
 * 工资汇总
 * @author LiuMinchao
 */
class PayrollSalarysummary extends CActiveRecord {

    const STATUS_NORMAL = 1; //已入库
    const STATUS_DISABLE = 0; //未入库

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'payroll_salary_summary';
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
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'wage_date' => Yii::t('pay_payroll', 'wage_date'),
            'wage'=> Yii::t('pay_payroll', 'wage'),
            'work_hours'=> Yii::t('pay_payroll', 'work_hours'),
            'basic_wage' => Yii::t('pay_payroll', 'basic_wage'),
            'overtime_hours' => Yii::t('pay_payroll', 'overtime_hours'),
            'overtime_wage' => Yii::t('pay_payroll', 'overtime_wage'),
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

        //type        
        $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
        $params['status'] = $args['status'];
        
//        var_dump($condition);       
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }
        
        if ($args['month'] != '') {
            $condition.= ( $condition == '') ? ' wage_month =:wage_month ' : ' AND wage_month =:wage_month ';
            $params['wage_month'] = $args['month'];
        }
        
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' user_name =:user_name ' : ' AND user_name =:user_name ';
            $params['user_name'] = $args['user_name'];
        }
        $total_num = PayrollSalarysummary::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
            $order = 'summary_id DESC';
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

        $rows = PayrollSalarysummary::model()->findAll($criteria);
        
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    public static function infoQuery($contractor_id,$start,$end) {
        $sql = "SELECT summary_id,user_name,user_id FROM payroll_salary_summary WHERE contractor_id =:contractor_id AND start_date =:start_date AND end_date =:end_date";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":start_date", $start, PDO::PARAM_INT);
        $command->bindParam(":end_date", $end, PDO::PARAM_INT);
        $list = $command->queryAll();
        if (count($list) > 0) {
            foreach ($list as $key => $row) {
                $info[$row['user_id']]['user_name'] = $row['user_name'];
                $info[$row['user_id']]['summary_id'] = $row['summary_id'];
            }
        }
        return $info;
    }
    public static function insertSummary($user_list,$start,$end){
        $contractor_id = Yii::app()->user->contractor_id;
        $wage_month = substr($start,0,6);       
        $default = 0;
//        var_dump($user_list);
//        exit;
        foreach($user_list as $id => $name){
            $sql = "SELECT * FROM payroll_salary_summary WHERE start_date = '".$start."' AND end_date = '".$end."' AND contractor_id = '".$contractor_id."' AND user_id ='".$id."'";
            $command = Yii::app()->db->createCommand($sql);
            $datelist = $command->queryAll();
            if(empty($datelist)){
                $sql = "INSERT INTO payroll_salary_summary (user_id,user_name,contractor_id,wage,wage_overtime,work_hours,overtime_hours,basic_wage,overtime_wage,allowance,total_wage,start_date,end_date,wage_month,status) VALUES(:user_id,:user_name,:contractor_id,:wage,:wage_overtime,:work_hours,:overtime_hours,:basic_wage,:overtime_wage,:allowance,:total_wage,:start_date,:end_date,:wage_month,:status)";
                    $command = Yii::app()->db->createCommand($sql);
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
                    $command->bindParam(":total_wage", $default, PDO::PARAM_INT);
                    $command->bindParam(":start_date", $start, PDO::PARAM_INT);
                    $command->bindParam(":end_date", $end, PDO::PARAM_INT);
                    $command->bindParam(":wage_month", $wage_month, PDO::PARAM_INT);
                    $command->bindParam(":status", $default, PDO::PARAM_INT);
                $rs = $command->execute();
            }
        }
        if($rs2==0){
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
    public static function exportQuery($contractor_id,$start,$end,$status) {
        $month = substr($start,0,6);
        $table = 'payroll_salary_'.$month;
        $sql = "SELECT * FROM payroll_salary_summary WHERE contractor_id =".$contractor_id.". AND start_date =".$start." AND end_date =".$end." AND status =".$status." ";
        $command = Yii::app()->db->createCommand($sql);
        $summary_list = $command->queryAll();
        $sql = "SELECT * FROM ".$table." WHERE contractor_id =".$contractor_id.". AND wage_date BETWEEN '".$start."' and '".$end."' AND status =".$status." ";
        $command = Yii::app()->db->createCommand($sql);
        $list = $command->queryAll();
//        var_dump($list);
//        exit;
//        var_dump($list);
            foreach ($summary_list as $key => $row) {
                foreach($list as $k => $v){
                    if($row['user_id'] == $v['user_id']){
                        $info[$row['user_id']]['user_name'] = $row['user_name'];
                        $info[$row['user_id']]['summary_id'] = $row['summary_id'];
                        $info[$row['user_id']]['wage'] = $row['wage'];
                        $info[$row['user_id']]['work_hours'] = $row['work_hours'];
                        $info[$row['user_id']]['basic_wage'] = $row['basic_wage'];
                        $info[$row['user_id']]['wage_overtime'] = $row['wage_overtime'];
                        $info[$row['user_id']]['overtime_hours'] = $row['overtime_hours'];
                        $info[$row['user_id']]['overtime_wage'] = $row['overtime_wage'];
                        $info[$row['user_id']]['allowance'] = $row['allowance'];
                        if($v['allowance_content'] != '暂无补贴'){
                            $info[$row['user_id']]['allowance_content'].= $v['wage_date'].$v['allowance_content']." ";
                        }
                        $info[$row['user_id']]['total_wage'] = $row['total_wage'];
                    }
                }
            }
        
        return $info;
    }
}