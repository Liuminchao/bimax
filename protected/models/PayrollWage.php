<?php

/**
 * 时薪管理
 * @author LiuXiaoyuan
 */
class PayrollWage extends CActiveRecord {

    //承包商类型
    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'payroll_wage';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role_name' =>  Yii::t('sys_role', 'role_name'),
            'team_name' =>  Yii::t('sys_role', 'team_name'),
            'Nation_type' =>  Yii::t('sys_role', 'Nation_type'),
            'wage' =>  Yii::t('sys_role', 'wage'),
            'overtime_wage' =>  Yii::t('sys_role', 'overtime_wage'),
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
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
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
     * 添加角色日志描述
     * @param type $model
     * @return type
     */
    public static function insertLog($model) {
        return array(
            Yii::t('sys_role', 'role_id') => $model->role_id,
            Yii::t('sys_role', 'contractor_type') => self::contractorTypeText($model->contractor_type),
            Yii::t('sys_role', 'role_name') => $model->role_name,
            Yii::t('sys_role', 'role_name_en') => $model->role_name_en,
            Yii::t('sys_role', 'team_name') => $model->team_name,
            Yii::t('sys_role', 'team_name_en') => $model->team_name_en,
            Yii::t('sys_role', 'order') => $model->sort_id,
            Yii::t('sys_role', 'status') => self::statusText($model->status),
            Yii::t('sys_role', 'record_time') => $model->record_time,
        );
    }

    /**
     * 修改角色日志描述
     * @param type $model
     * @return type
     */
    public static function updateLog($model) {
        return array(
            Yii::t('sys_role', 'role_id') => $model->role_id,
            Yii::t('sys_role', 'contractor_type') => self::contractorTypeText($model->contractor_type),
            Yii::t('sys_role', 'role_name') => $model->role_name,
            Yii::t('sys_role', 'role_name_en') => $model->role_name_en,
            Yii::t('sys_role', 'team_name') => $model->team_name,
            Yii::t('sys_role', 'team_name_en') => $model->team_name_en,
            Yii::t('sys_role', 'order') => $model->sort_id,
            Yii::t('sys_role', 'status') => self::statusText($model->status),
        );
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

        //Role
        if ($args['role_id'] != '') {
            $condition.= ( $condition == '') ? ' role_id=:role_id' : ' AND role_id=:role_id';
            $params['role_id'] = $args['role_id'];
        }
        //Contractor Type
       if ($args['contractor_type'] != '') {
      //      $condition.= ( $condition == '') ? ' contractor_type=:contractor_type' : ' AND contractor_type=:contractor_type';
            $params['contractor_type'] = $args['contractor_type'];
        }
        if (Yii::app()->language == 'zh_CN') {
            //Role Name
            if ($args['role_name'] != '') {
                $condition.= ( $condition == '') ? ' role_name LIKE :role_name' : ' AND role_name LIKE :role_name';
                $params['role_name'] = '%' . $args['role_name'] . '%';
            }
        } else if (Yii::app()->language == 'en_US') {
            //Role Name En
            if ($args['role_name'] != '') {
                $condition.= ( $condition == '') ? ' role_name_en LIKE :role_name_en' : ' AND role_name_en LIKE :role_name_en';
                $params['role_name_en'] = '%' . $args['role_name'] . '%';
            }
        }
        //Teamid
        if ($args['teamid'] != '') {
            $condition.= ( $condition == '') ? ' team_name_en=:teamid' : ' AND team_name_en=:teamid';
            $params['teamid'] = $args['teamid'];
        }
        
        //Team Name En
        if ($args['team_name_en'] != '') {
            $condition.= ( $condition == '') ? ' team_name_en=:team_name_en' : ' AND team_name_en=:team_name_en';
            $params['team_name_en'] = $args['team_name_en'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = Role::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'sort_id ASC';
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
        $rows = Role::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    //查询角色分组
    public static function roleTeamList(){
        if (Yii::app()->language == 'zh_CN')
            $field = "team_name";
        else
            $field = "team_name_en";
            
        $sql = "SELECT team_name_en as teamid, ".$field." as teamname FROM bac_role WHERE status=00 group by ".$field." order by sort_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['teamid']] = $row['teamname'];
            }
        }
        return $rs;
        
    }
    
    public static function roleListByTeam($team=''){
        if (Yii::app()->language == 'zh_CN')
            $field = "role_name";
        else
            $field = "role_name_en";
            
        $sql = "SELECT role_id, ".$field." as role_name FROM bac_role WHERE status=00";
        if($team <> '')
            $sql .= " and team_name_en='".$team."'";
        $sql .= "  order by sort_id";//var_dump($sql);
        
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']] = $row['role_name'];
            }
        }
        return $rs;
    }
    
    //角色列表
    public static function roleList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT role_id,role_name FROM bac_role WHERE status=00 order by sort_id";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT role_id,role_name_en FROM bac_role WHERE status=00  order by team_name";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name_en'];
                }
            }
        }



        return $rs;
    }

    //时薪列表
    public static function wageList($ation_type) {
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $sql = "SELECT role_id,wage,overtime_wage FROM payroll_wage WHERE status='0' AND contractor_id='".$contractor_id."' AND nation_type='".$ation_type."' ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":contractor_type", $type, PDO::PARAM_STR);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']]['wage'] = $row['wage'];
                    $rs[$row['role_id']]['overtime_wage'] = $row['overtime_wage'];
                }
            }
        return $rs;
    }
    
     /**
     * 时薪设置
     */
    public static function setwage($args){
//        $model = self::model()->find('working_date=:working_date AND user_name=:user_name', array(':working_date' => $args['working_date'],':user_name' => $args['user_name']));
//
//        $model->working_date = $args['working_date'];
//        $rs = $model->save();
        $sql = "SELECT * FROM payroll_wage WHERE role_id = '".$args['role_id']."' AND contractor_id ='".$args['contractor_id']."' AND nation_type = '".$args['ation_type']."'";
//        var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
//        var_dump($data);
        if(!empty($data)){
//            if($data[0]['program_id'] != $args['program_id']){
//                $r['msg'] = '该员工在其他项目组已填写工时';
//                $r['status'] = -1;
//                $r['refresh'] = false;
//                return $r;
//            }
            if($args['name'] == 'overtime_wage'){
                $sql = "UPDATE payroll_wage SET overtime_wage = '".$args['value']."',status ='".$args['status']."'  WHERE role_id = '".$args['role_id']."' AND contractor_id ='".$args['contractor_id']."' AND nation_type = '".$args['ation_type']."'";
            }
            if($args['name'] == 'wage'){
                $sql = "UPDATE payroll_wage SET wage = '".$args['value']."',status ='".$args['status']."'  WHERE role_id = '".$args['role_id']."' AND contractor_id ='".$args['contractor_id']."' AND nation_type = '".$args['ation_type']."'";
            }
        }else{
            $record_time = date('Y-m-d H:i:s');
            if($args['name'] == 'overtime_wage')
                $sql = "INSERT INTO payroll_wage(role_id,contractor_id,nation_type,overtime_wage,record_time,status)VALUES('".$args['role_id']."','".$args['contractor_id']."','".$args['ation_type']."','".$args['value']."','".$record_time."','".$args['status']."')";
        
            if($args['name'] == 'wage')
                $sql = "INSERT INTO payroll_wage(role_id,contractor_id,nation_type,wage,record_time,status)VALUES('".$args['role_id']."','".$args['contractor_id']."','".$args['ation_type']."','".$args['value']."','".$record_time."','".$args['status']."')";
        }
        
//        exit;
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->execute();
//        var_dump($rows);
        if($rows ==1 || $rows == 2){
            $r['msg'] = '设置成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '设置失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
}
