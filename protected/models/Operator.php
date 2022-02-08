<?php

/**
 * 操作员管理
 * @author LiuMinchao
 */
class Operator extends CActiveRecord {

    public $password_c; //确认密码
    public $old_pwd; //原密码
    public $maincomp_id; //总包公司id
    public $subcomp_id; //分包公司id
  
    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    const TYPE_SYSTEM = '00'; //系统操作员  
    const TYPE_PLATFORM = '01'; //平台操作员
    const TYPE_FACEFORM = '02';//FACE客户端操作员
    const admin = '00'; //管理员
    const operator = '01'; //操作员
    const zh_CN = '00'; //中文 
    const en_US = '01'; //英文
    const INITIAL_PASSWORD = 123456;
    
    const ROLE_SYSTEM = 'smanager'; //系统管理员
    const ROLE_COMP = 'cmanager'; //承包商管理员
    /*const ROLE_MC = 'mcmanager'; //总包管理员
    const ROLE_SC = 'scmanager'; //分包管理员    */
    

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_operator';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'operator_id' => Yii::t('sys_operator', 'Operator'),
            'passwd' => Yii::t('sys_operator', 'Passwd'),
            'passwd_c' => Yii::t('sys_operator', 'Passwd_c'),
            'name' => Yii::t('sys_operator', 'Name'),
            'phone' => Yii::t('sys_operator', 'Phone'),
            'email' => Yii::t('sys_operator', 'Email'),
            'operator_type' => Yii::t('sys_operator', 'Operator Type'),
            'last_login_ip' => Yii::t('sys_operator', 'Last Login IP'),
            'last_login_time' => Yii::t('sys_operator', 'Last Login Time'),
            'unreg_time' => Yii::t('sys_operator', 'Unreg Time'),
            'reg_time' => Yii::t('sys_operator', 'Reg Time'),
            'status' => Yii::t('sys_operator', 'Status'),
            'record_time' => Yii::t('sys_operator', 'Record Time'),
            'language' => Yii::t('sys_operator', 'Language'),
            'contractor_id' => Yii::t('sys_operator', 'Contractor Id'),
            'init_passwd' => Yii::t('sys_operator', 'Init Passwd'),
            'old_pwd' => Yii::t('sys_operator', 'Old Passwd'),
            'role_id' => Yii::t('sys_operator', 'Role'),
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
            self::STATUS_NORMAL => Yii::t('sys_operator', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('sys_operator', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_DISABLE => 'label-danger', //已注销
        );
        return $key === null ? $rs : $rs[$key];
    }

    //操作员类型
    public static function typeText($key = null) {
        $rs = array(
            self::TYPE_PLATFORM => Yii::t('sys_operator', 'TYPE_PLATFORM'),
            self::TYPE_SYSTEM => Yii::t('sys_operator', 'TYPE_SYSTEM'),
//            self::TYPE_FACEFORM => Yii::t('sys_operator','TYPE_FACEFORM'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //操作员角色
    public static function roleText($key = null) {
        if (Yii::app()->language == 'zh_CN'){
            $rs = array(
                self::admin => '管理员',
                self::operator => '操作员',
            );
        }else{
            $rs = array(
                self::admin => 'Super Admin',
                self::operator => 'Web User',
            );
        }

        return $key === null ? $rs : $rs[$key];
    }

    //语言
    public static function langText($key = null) {
        $rs = array(
            self::zh_CN => Yii::t('sys_operator', 'zh_CN'),
            self::en_US => Yii::t('sys_operator', 'en_US'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //语言对应编码
    public static function langCode($key = null) {
        $rs = array(
            'zh_CN' => self::zh_CN,
            'en_US' => self::en_US,
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 添加操作员日志详细
     * @param type $model
     * @return array
     */
    public static function insertLog($model) {
        $log = array(
            $model->getAttributeLabel('operator_id') => $model->operator_id,
            $model->getAttributeLabel('name') => $model->name,
            $model->getAttributeLabel('phone') => $model->phone,
            $model->getAttributeLabel('email') => $model->email,
            $model->getAttributeLabel('operator_type') => self::typeText($model->operator_type),
            $model->getAttributeLabel('role_id') => self::roleText($model->role_id),
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            //$model->getAttributeLabel('last_login_ip') => $model->last_login_ip,
            //$model->getAttributeLabel('last_login_time') => $model->last_login_time,
            //$model->getAttributeLabel('unreg_time') => $model->unreg_time,
            //$model->getAttributeLabel('reg_time') => $model->reg_time,
            $model->getAttributeLabel('status') => self::statusText($model->status),
                //$model->getAttributeLabel('record_time') => $model->record_time,
        );
        return $log;
    }

    /**
     * 修改操作员日志详细
     * @param type $model
     * @return array
     */
    public static function updateLog($model) {
        $log = array(
            $model->getAttributeLabel('operator_id') => $model->operator_id,
            $model->getAttributeLabel('operator_type') => self::typeText($model->operator_type),
            $model->getAttributeLabel('role_id') => self::roleText($model->role_id),
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('name') => $model->name,
            $model->getAttributeLabel('phone') => $model->phone,
            $model->getAttributeLabel('email') => $model->email,
        );
        return $log;
    }

    /**
     * 修改个人基本信息
     * @param type $model
     * @return type
     */
    public static function updateInfoLog($model) {
        $log = array(
            $model->getAttributeLabel('operator_id') => $model->operator_id,
            $model->getAttributeLabel('name') => $model->name,
            $model->getAttributeLabel('phone') => $model->phone,
            $model->getAttributeLabel('email') => $model->email,
        );
        return $log;
    }

     /**
     * 修改登录密码
     * @param type $model
     * @return type
     */
    public static function updatePwdLog($model) {
        $log = array(
            $model->getAttributeLabel('operator_id') => $model->operator_id,
            $model->getAttributeLabel('name') => $model->name,
        );
        return $log;
    }
    
    /**
     * 注销操作员日志详细
     * @param type $model
     * @return array
     */
    public static function logoutLog($model) {
        $log = array(
            //$model->getAttributeLabel('operator_type') => self::typeText($model->operator_type),
            $model->getAttributeLabel('operator_id') => $model->operator_id,
            $model->getAttributeLabel('name') => $model->name,
            $model->getAttributeLabel('phone') => $model->phone,
            $model->getAttributeLabel('email') => $model->email,
        );
        return $log;
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

        //Operator
        if ($args['operator_id'] != '') {
            $condition.= ( $condition == '') ? ' operator_id=:operator_id' : ' AND operator_id=:operator_id';
            $params['operator_id'] = $args['operator_id'];
        }
        //Name
        if ($args['name'] != '') {
            $condition.= ( $condition == '') ? ' name LIKE :name' : ' AND name LIKE :name';
            $params['name'] = '%' . $args['name'] . '%';
        }
        //Phone
        if ($args['phone'] != '') {
            $condition.= ( $condition == '') ? ' phone=:phone' : ' AND phone=:phone';
            $params['phone'] = $args['phone'];
        }
        //Operator Type
        if ($args['operator_type'] != '') {
            $condition.= ( $condition == '') ? ' operator_type=:operator_type' : ' AND operator_type=:operator_type';
            $params['operator_type'] = $args['operator_type'];
        }
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = Operator::model()->count($condition, $params); //总记录数

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
        $rows = Operator::model()->findAll($criteria);
//        var_dump($rows);
//        exit;
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertOperator($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['operator_type'] == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_type is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['role_id'] == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error role_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        /*if ($args['role_id'] == self::ROLE_MC) {
            $args['contractor_id'] = $args['maincomp_id'];
        } else if ($args['role_id'] == self::ROLE_SC) {
            $args['contractor_id'] = $args['subcomp_id'];
        }*/

        if ($args['operator_id'] == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['passwd'] == '') {
            $args['passwd'] = self::INITIAL_PASSWORD;
        }

        $exist_data = Operator::model()->count('operator_id=:operator_id', array('operator_id' => $args['operator_id']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new Operator('create');
            $model->operator_id = $args['operator_id'];
            $model->operator_type = $args['operator_type'];
            $model->operator_role = $args['operator_role'];
            $model->passwd = md5($args['passwd']);
            $model->name = $args['name'];
            $model->phone = $args['phone'];
            $model->email = $args['email'];
            $model->role_id = $args['role_id'];
            $model->contractor_id = $args['contractor_id'];
            $model->reg_time = date('Y-m-d H:i:s');
            $model->status = self::STATUS_NORMAL;
            $model->record_time = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result) {
                //建立项目权限
                $re['contractor_id'] = $args['contractor_id'];
                $project_list = Program::programAllList($re);
                if(count($project_list) > 0){
                    foreach($project_list as $project_id => $project_name){
                        //企业普通操作员
                        if($args['operator_role'] == '01'){
                            $rs['operator_id'] = $args['operator_id'];
                            $rs['value'] = '2';
                            $rs['program_id'] = $project_id;
                            OperatorProject::SetAuthority($rs);
                        }
                    }
                }

                OperatorLog::savelog(OperatorLog::MODULE_ID_OPERATOR, Yii::t('sys_operator', 'Add Operator'), self::insertLog($model));

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
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

    //修改数据
    public static function updateOperator($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['operator_id'] == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Operator::model()->findByPk($args['operator_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        /*if ($args['role_id'] == self::ROLE_MC) {
            $args['contractor_id'] = $args['maincomp_id'];
        } else if ($args['role_id'] == self::ROLE_SC) {
            $args['contractor_id'] = $args['subcomp_id'];
        }*/

        try {
            $model->name = $args['name'];
            $model->phone = $args['phone'];
            $model->email = $args['email'];
            $result = $model->save();

            if ($result) {

                OperatorLog::savelog(OperatorLog::MODULE_ID_OPERATOR, Yii::t('sys_operator', 'Edit Operator'), self::updateLog($model));

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
    //修改Face登录帐号和平台登录帐号
    /*public static function updateFaceOperator($args,$operator_id) {
//        var_dump($args['company_sn']);
//        exit;
        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        if ($operator_id == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Contractor::model()->findByPk($args['contractor_id']);
        $re = Operator::model()->findByPk($operator_id);
        $company_sn = $model->company_sn;
        $rs = Operator::model()->findByPk($company_sn);
//        var_dump($r->phone);
//        exit;
        //承包商没有企业注册编码 有企业注册编码但没有FACE帐号的
        if($rs == null){
             //将平台帐号的类型改为02
            if($re->operator_type == 01){
                $res['operator_type'] = Operator::TYPE_FACEFORM;
                $re->operator_type = $res['operator_type'];
                $result = $re->save();
            }
            
            //插入一条face数据
            if($args['company_sn']<>$company_sn){
                $face['operator_id'] = $args['company_sn'];
            }else{
                $face['operator_id'] = $company_sn;
            }
            $face['operator_type'] = Operator::TYPE_PLATFORM;
            $face['name'] = $re->name;
            $face['phone'] = $re->phone;          
            $face['role_id'] = Operator::ROLE_COMP;
            $face['contractor_id'] = $args['contractor_id'];        
            Operator::insertOperator($face);
            
        }
//        var_dump($args['company_sn']);
//        exit;
        //已经有企业注册编码需要修改
        if($rs!=null && $args['company_sn']<>$company_sn){
            $exist_data = Operator::model()->count('operator_id=:operator_id', array('operator_id' => $args['company_sn']));
            if ($exist_data != 0) {
                $r['msg'] = Yii::t('comp_contractor', 'Error company_sn is exist');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            $rs->operator_id = $args['company_sn'];
            $result = $rs->save();
        }

    }*/

    //修改基本信息
    public static function updateOperatorInfo($args) {


        if ($args['operator_id'] == '') {
            $args['operator_id'] = Yii::app()->user->id;
        }

        $model = Operator::model()->findByPk($args['operator_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model->name = $args['name'];
            $model->phone = $args['phone'];
            $model->email = $args['email'];
            $result = $model->save();

            if ($result) {
                Yii::app()->user->setState('name', $args['name']);
                OperatorLog::savelog(OperatorLog::MODULE_ID_OPERATOR, Yii::t('sys_operator', 'Edit OperatorInfo'), self::updateInfoLog($model));

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

    //修改个人登录密码
    public static function updateOperatorPwd($args) {
        
        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['operator_id'] == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Operator::model()->findByPk($args['operator_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        if (md5($args['old_pwd']) != $model->passwd) {
            $r['msg'] = Yii::t('sys_operator', 'Error old password is error');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {

            $model->passwd = md5($args['passwd']);
            $result = $model->save();

            if ($result) {

                OperatorLog::savelog(OperatorLog::MODULE_ID_OPERATOR, Yii::t('sys_operator', 'Edit Operator Pwd'), self::updatePwdLog($model));

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

    /**
     * 更新操作员登录ip和时间
     * @param type $id
     * @return boolean
     */
    public static function updateOperatorLogin($id) {

        $model = Operator::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('sys_operator', 'Error record is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model->last_language = self::langCode(Yii::app()->language);
            $model->last_login_ip = OperatorLog::getIP();
            $model->last_login_time = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result) {

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

    //注销操作员
    public static function logoutOperator($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Operator::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
//            $model->status = self::STATUS_DISABLE;
            $result = Operator::model()->deleteByPk($id);

            if ($result) {
                $sql = "DELETE FROM bac_operator_program WHERE operator_id =:operator_id ";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":operator_id", $id, PDO::PARAM_STR);

                $rs = $command->execute();

                OperatorLog::savelog(OperatorLog::MODULE_ID_OPERATOR, Yii::t('sys_operator', 'Logout Operator'), self::logoutLog($model));
                $r['msg'] = Yii::t('common', 'success_logout');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error _logout');
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

    /**
     * 重置登录密码
     * @param type $id
     * @return boolean
     */
    public static function resetPwd($id) {
        if ($id == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Operator::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->passwd = md5(self::INITIAL_PASSWORD);
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_OPERATOR, Yii::t('sys_operator', 'Reset Operator Pwd'), self::updatePwdLog($model));
                $r['msg'] = Yii::t('sys_operator', 'Success Resetpwd');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('sys_operator', 'Error Resetpwd');
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

     /**
     * 重置登录密码
     * @param type $id
     * @return boolean
     */
    public static function resetContractorPwd($args) {
        
        if ($args['operator_id'] == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Operator::model()->findBySql("select * from bac_operator where contractor_id=:contractor_id and operator_type=:operator_type",array(':contractor_id'=>$args[operator_id],operator_type=>$args['operator_type']));
//        $model = Operator::model()->find("contractor_id=:contractor_id",array("contractor_id"=>$args[operator_id]));
        
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            
            $model->passwd = md5(self::INITIAL_PASSWORD);
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_OPERATOR, Yii::t('sys_operator', 'Reset Operator Pwd'), self::updatePwdLog($model));
                $r['msg'] = Yii::t('sys_operator', 'Success Resetpwd');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('sys_operator', 'Error Resetpwd');
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

    /**
     * 根据web登录账号获取企业名称
     * @return type
     */
    public static function companyByOperator() {
        $sql = "SELECT a.contractor_id,a.contractor_name,b.operator_id FROM bac_contractor a,bac_operator b WHERE a.status=0 AND a.contractor_id = b.contractor_id";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['operator_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }

    //查询公司中的操作员
    public static function myOperatorListBySuccess($contractor_id) {
        $c_model = Contractor::model()->findByPk($contractor_id);

        $sql = "SELECT user_id,user_name FROM bac_staff  WHERE user_phone in (SELECT operator_id FROM bac_operator WHERE contractor_id = :contractor_id and operator_role = '01')";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        return $rs;
    }

    public static function setOperator($user_id) {
        $u_model = Staff::model()->findByPk($user_id);
        $user_phone = $u_model->user_phone;
        $exist_data = Operator::model()->findAll(
            array(
                'select'=>array('*'),
                'condition' => 'operator_id=:operator_id',
                'params' => array(':operator_id'=>$user_phone),
            )
        );
        if(!$exist_data){
            $contractor_id = $u_model->contractor_id;
            $args['contractor_id'] = $contractor_id;
            $c_model = Contractor::model()->findByPk($contractor_id);
            $rs['operator_role'] = Operator::operator;
            $rs['operator_id'] = $u_model->user_phone;
            $rs['operator_type'] = self::TYPE_PLATFORM;
            $rs['contractor_id'] = $contractor_id;
            $rs['role_id'] = 'cmanager';
            $rs['name'] = $u_model->user_name;
            $rs['phone'] = $u_model->user_phone;
            $rs['email'] = $u_model->primary_email;
            $rs['passwd'] = '123456';
            $user_phone = $u_model->user_phone;
            $r = Operator::insertOperator($rs);
        }

        if($r['status'] == 1){
            $exist_data = OperatorMenu::model()->findAll(
                array(
                    'select'=>array('*'),
                    'condition' => 'operator_id=:operator_id',
                    'params' => array(':operator_id'=>$user_phone),
                )
            );
            if(!$exist_data){
                $status = '00';
                $c_model = Contractor::model()->findByPk($contractor_id);
                $menu_list = Menu::appMenuList();
                foreach($menu_list as $menu_id => $name){
                    $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":operator_id", $user_phone, PDO::PARAM_STR);
                    $command->bindParam(":menu_id", $menu_id, PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $command->execute();
                }
                $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                $id = '105';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":operator_id", $user_phone, PDO::PARAM_STR);
                $command->bindParam(":menu_id", $id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->execute();
            }

            $re['contractor_id'] = $contractor_id;
            $project_list = Program::programList($re);
//                var_dump($project_list);
            foreach($project_list as $k => $v){
                $s['operator_id'] = $user_phone;
                $s['value'] = '2';
                $s['program_id'] = $k;
                $r = OperatorProject::SetAuthority($s);
            }
        }
        return $r;
    }

    //设置操作员(批量)
    public static function setBatchOperator($args) {
//        var_dump($args);
//        exit;
        foreach($args['sc_list'] as $k => $user_id){
            $u_model = Staff::model()->findByPk($user_id);
            $contractor_id = $u_model->contractor_id;
            $args['contractor_id'] = $contractor_id;
            $c_model = Contractor::model()->findByPk($contractor_id);
            $rs['operator_role'] = Operator::operator;
            $rs['operator_id'] = $u_model->user_phone;
            $rs['operator_type'] = self::TYPE_PLATFORM;
            $rs['contractor_id'] = $contractor_id;
            $rs['role_id'] = 'cmanager';
            $rs['name'] = $u_model->user_name;
            $rs['phone'] = $u_model->user_phone;
            $rs['email'] = $u_model->primary_email;
            $rs['passwd'] = '123456';
            $user_phone = $u_model->user_phone;
            $r = Operator::insertOperator($rs);

            if($r['status'] == 1){
                $exist_data = OperatorMenu::model()->findAll(
                    array(
                        'select'=>array('*'),
                        'condition' => 'operator_id=:operator_id',
                        'params' => array(':operator_id'=>$user_phone),
                    )
                );
                if(!$exist_data){
                    $status = '00';
                    $c_model = Contractor::model()->findByPk($contractor_id);
                    $menu_list = Menu::appMenuList();
                    foreach($menu_list as $menu_id => $name){
                        $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":operator_id", $user_phone, PDO::PARAM_STR);
                        $command->bindParam(":menu_id", $menu_id, PDO::PARAM_STR);
                        $command->bindParam(":status", $status, PDO::PARAM_STR);
                        $command->execute();
                    }
                    $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                    $id = '105';
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":operator_id", $user_phone, PDO::PARAM_STR);
                    $command->bindParam(":menu_id", $id, PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $command->execute();
                }

                $re['contractor_id'] = $contractor_id;
                $project_list = Program::programList($re);
//                var_dump($project_list);
                foreach($project_list as $k => $v){
                    $s['operator_id'] = $user_phone;
                    $s['value'] = '2';
                    $s['program_id'] = $k;
                    $r = OperatorProject::SetAuthority($s);
                }
            }
        }
        return $r;
    }

    /**
     * 获取企业下操作员列表
     */
    public static function OperatorList($contractor_id) {

        $list = array();
        $sql = "select *
                  from bac_operator
                 where status=0 and contractor_id in ('".$contractor_id."') and operator_type = '01'";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    /**
     * 获取所有操作员列表
     */
    public static function OperatorAllList() {

        $list = array();
        $sql = "select *
                  from bac_operator
                 where status=0 and operator_type = '01' ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
}
