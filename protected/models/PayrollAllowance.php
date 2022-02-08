<?php

/**
 * 时薪管理
 * @author LiuXiaoyuan
 */
class PayrollAllowance extends CActiveRecord {

    //承包商类型
    const STATUS_NORMAL = 1; //入库
    const STATUS_DISABLE = 0; //未入库

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'payroll_allowance';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'allowance_date' =>  Yii::t('pay_payroll', 'date'),
            'allowance_type' =>  Yii::t('pay_payroll', 'date'),
            'user_phone' => Yii::t('comp_staff','User_phone'),
            'user_name' => Yii::t('comp_staff','User_name'),
            'allowance' =>  Yii::t('pay_payroll', 'allowance'),
            'allowance_content' =>  Yii::t('pay_payroll', 'allowance_content'),
            'record_time' => Yii::t('sys_role', 'record_time'),
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

    //承包商类型
    public static function contractorTypeText($key = null) {
        $rs = array(
            self::CONTRACTOR_TYPE_MC => Yii::t('sys_role', 'CONTRACTOR_TYPE_MC'),
            self::CONTRACTOR_TYPE_SC => Yii::t('sys_role', 'CONTRACTOR_TYPE_SC'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    /**
     * 删除角色日志描述
     * @param type $model
     * @return type
     */
    public static function deleteLog($model) {
        return array(
            Yii::t('sys_role', 'role_id') => $model->role_id,
            Yii::t('sys_role', 'contractor_type') => self::contractorTypeText($model->contractor_type),
            Yii::t('sys_role', 'role_name') => $model->role_name,
            Yii::t('sys_role', 'role_name_en') => $model->role_name_en,
            Yii::t('sys_role', 'team_name') => $model->team_name,
            Yii::t('sys_role', 'team_name_en') => $model->team_name_en,
        );
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
        
//	$start = Utils::DateToCn(($args['start_date']));
        $start = str_replace('-', '', Utils::DateToCn(($args['start_date'])));
//        var_dump($start);
        if ($start != '') {
            $condition.= ( $condition == '') ? ' allowance_date =:allowance_date ' : ' AND allowance_date =:allowance_date ';
            $params['allowance_date'] = $start;
        }
//        $condition.= " AND allowance_date = '".$start."'";

        $total_num = PayrollAllowance::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'user_id ASC';
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
        $rows = PayrollAllowance::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //添加员工补贴
    public static function insertAllowance($args) {
        
        $contractor_id = Yii::app()->user->getState('contractor_id');

        $sql = "select user_name,user_id from bac_staff where user_phone='".$args['user_phone']."' and contractor_id='".$contractor_id."'";
//            var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->queryAll();
//            var_dump($result);
        $args['user_id'] = $result[0]['user_id'];
        $args['user_name'] = $result[0]['user_name'];
        
        
        if( $args['allowance_date']==''){
            $r['msg'] = Yii::t('pay_payroll', 'error_allowance_date_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $allowance_date = str_replace('-', '', Utils::DateToCn($args['allowance_date']));
        $month = substr($allowance_date,0,6);
        $table = 'payroll_salary_'.$month;
        
        $sql = "SELECT * FROM ".$table." WHERE contractor_id=:contractor_id AND user_id=:user_id AND wage_date=:wage_date";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":wage_date", $allowance_date, PDO::PARAM_INT);
        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_INT);
        $rows = $command->queryAll();
        if ($rows[0]['status'] == 1) {
            $r['msg'] = Yii::t('pay_payroll', 'error_insert_allowance');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if( $args['allowance']==''){
            $r['msg'] = Yii::t('pay_payroll', 'error_allowance_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {          
//            $args['allowance_date'] = Utils::DateToCn($args['allowance_date']);
//            var_dump($args['allowance_date']);
            $model = new PayrollAllowance('create');
            
            $model->contractor_id = $contractor_id;
            $model->user_id = $args['user_id'];
            $model->user_name = $args['user_name'];
            $model->user_phone = $args['user_phone'];
            $model->allowance_date = $allowance_date;
            $model->allowance = $args['allowance'];
            $model->allowance_content = $args['allowance_content'];
            $model->status = $args['status'];
            $result = $model->save();
            
            if ($result) {
                //var_dump($rs);
//                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('task', 'Add Task'), self::insertLog($model));

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //批量添加员工补贴
    public static function insertbatchAllowance($args) {
        if( $args['allowance_date']==''){
            $r['msg'] = Yii::t('pay_payroll', 'error_allowance_date_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if( $args['allowance']==''){
            $r['msg'] = Yii::t('pay_payroll', 'error_allowance_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $allowance_date = str_replace('-', '', Utils::DateToCn($args['allowance_date']));
        $month = substr($allowance_date,0,6);
        $table = 'payroll_salary_'.$month;
        
        $sql = "SELECT user_id,status FROM ".$table." WHERE contractor_id=:contractor_id AND wage_date=:wage_date";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":wage_date", $allowance_date, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $salary[$row['user_id']]['status'] = $row['status'];
            }
        }
        
        $sql = "SELECT user_id,user_name,user_phone FROM bac_staff WHERE contractor_id=:contractor_id AND status=00";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $user_list[$row['user_id']]['user_name'] = $row['user_name'];
                $user_list[$row['user_id']]['user_phone'] = $row['user_phone'];
            }
        }
        foreach($user_list as $id => $r){
            $sql = "SELECT status FROM ".$table." WHERE contractor_id=:contractor_id AND user_id=:user_id AND wage_date=:wage_date";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
            $command->bindParam(":user_id", $id, PDO::PARAM_INT);
            $command->bindParam(":wage_date", $allowance_date, PDO::PARAM_INT);
            $rows = $command->queryAll();
                if($rows[0]['status']!=1){
                    $sql = "INSERT INTO payroll_allowance (user_id,user_name,user_phone,contractor_id,allowance_date,allowance,allowance_content,status) VALUES(:user_id,:user_name,:user_phone,:contractor_id,:allowance_date,:allowance,:allowance_content,:status)";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":user_id", $id, PDO::PARAM_INT);
                        $command->bindParam(":user_name", $r['user_name'], PDO::PARAM_INT);
                        $command->bindParam(":user_phone", $r['user_phone'], PDO::PARAM_INT);
                        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                        $command->bindParam(":allowance_date", $allowance_date, PDO::PARAM_INT);
                        $command->bindParam(":allowance", $args['allowance'], PDO::PARAM_INT);
                        $command->bindParam(":allowance_content", $args['allowance_content'], PDO::PARAM_INT);
                        $command->bindParam(":status", $args['status'], PDO::PARAM_INT);
                        $rs = $command->execute();
                }    
        }
//        var_dump($rs);
//        exit;
        if ($rs==null) {
            $r['msg'] = Yii::t('pay_payroll', 'error_insert_allowance');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else if($rs>=0){
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
            } else{
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
            }
        return $r;
    }
    //修改员工补贴
    public static function updateAllowance($args) {
        $allowance_date = str_replace('-', '', Utils::DateToCn($args['allowance_date']));
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $model = PayrollSalary::model()->find('contractor_id=:id AND wage_date=:date', array(':id' => $contractor_id,':date'=>$allowance_date));
        if ($model != null) {
            $r['msg'] = Yii::t('pay_payroll', 'error_update_allowance');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = PayrollAllowance::model()->findByPk($args['allowance_id']);       
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
            $model->user_id = $args['user_id'];
            $model->user_name = $args['user_name'];
            $model->user_phone = $args['user_phone'];
            $model->allowance_date = $allowance_date;
            $model->allowance = $args['allowance'];
            $model->allowance_content = $args['allowance_content'];
            $result = $model->save();

            if ($result) {
//                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('task', 'Edit Task'),self::updateLog($model));
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
        //删除
    public static function deleteAllowance($allowance_id) {

        $model = PayrollAllowance::model()->findByPk($allowance_id);       
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $allowance_date = $model->allowance_date;
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $model = PayrollSalary::model()->find('contractor_id=:id AND wage_date=:date', array(':id' => $contractor_id,':date'=>$allowance_date));
        if ($model != null) {
            $r['msg'] = Yii::t('pay_payroll', 'error_delete_allowance');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            $sql = "delete from payroll_allowance where allowance_id = '".$allowance_id."'";
//            var_dump($sql);
//            exit;
            $command = Yii::app()->db->createCommand($sql);
            $re = $command->execute();
            if($re){
                $r['msg'] = Yii::t('common', 'success_delete');
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
                $r['msg'] = Yii::t('common', 'error_delete');
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
}
