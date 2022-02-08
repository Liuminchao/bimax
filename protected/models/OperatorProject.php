<?php

/**
 * 操作员管理
 * @author LiuMinchao
 */
class OperatorProject extends CActiveRecord {

    public $password_c; //确认密码
    public $old_pwd; //原密码
    public $maincomp_id; //总包公司id
    public $subcomp_id; //分包公司id

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    const TYPE_SYSTEM = '00'; //系统操作员
    const TYPE_PLATFORM = '01'; //平台操作员
    const TYPE_FACEFORM = '02';//FACE客户端操作员
    const zh_CN = '00'; //中文
    const en_US = '01'; //英文
    const INITIAL_PASSWORD = 123456;



    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_operator_program';
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

        $rs = array(
            self::ROLE_SYSTEM => Yii::t('sys_operator', 'ROLE_SYSTEM'),

            /*self::ROLE_MC => Yii::t('sys_operator', 'ROLE_MC'),
            self::ROLE_SC => Yii::t('sys_operator', 'ROLE_SC'), */

            self::ROLE_COMP => Yii::t('sys_operator', 'TYPE_PLATFORM'),
        );
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
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();

        //operator_id
        if ($args['operator_id'] != '') {
            $condition.= ( $condition == '') ? ' operator_id=:operator_id' : ' AND operator_id=:operator_id';
            $params['operator_id'] = $args['operator_id'];
        }

        //status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = OperatorProject::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'program_id desc';
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

        $rows = OperatorProject::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //设置权限
    public static function SetAuthority($args) {
//        var_dump($args);
//        exit;
        $exist_data = OperatorProject::model()->findAll(
            array(
                'select'=>array('*'),
                'condition' => 'program_id=:program_id and  operator_id=:operator_id',
                'params' => array(':program_id'=>$args['program_id'],':operator_id'=>$args['operator_id']),
            )
        );
        $status = '0';
        $record_time = date('Y-m-d H:i:s');
        if($exist_data){
//            var_dump(1111);
            $sql = "UPDATE bac_operator_program SET flag = '".$args['value']."',status=0 WHERE operator_id = '".$args['operator_id']."' and program_id ='".$args['program_id']."'  ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->execute();
        }else{
//            var_dump(222222);
            $sql = 'INSERT INTO bac_operator_program(program_id,operator_id,flag,status,record_time) VALUES(:program_id,:operator_id,:flag,:status,:record_time)';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
            $command->bindParam(":operator_id", $args['operator_id'], PDO::PARAM_INT);
            $command->bindParam(":flag", $args['value'], PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
            $rows = $command->execute();
        }

        if($rows=='0' || $rows=='1'){
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

    //查看操作员项目权限
    public static function authorityList($operator_id){

        $sql = "select program_id,flag
                  from bac_operator_program 
                 where  operator_id='".$operator_id."' and status = '0' 
                 ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $k => $v){
            $r[$v['program_id']] = $v['flag'];
        }
        return $r;
    }

    public static function flagList(){
        if (Yii::app()->language == 'zh_CN') {
            $flag = array(
                '0' => '查看',
                '1' => '编辑',
                '2' => '屏蔽',
            );
        }else{
            $flag = array(
                '0' => 'View',
                '1' => 'Edit',
                '2' => 'Hide',
            );
        }
        return $flag;
    }

}
