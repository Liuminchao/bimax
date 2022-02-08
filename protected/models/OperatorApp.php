<?php

/**
 * 操作员管理
 * @author LiuMinchao
 */
class OperatorApp extends CActiveRecord {

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

    const ROLE_SYSTEM = 'smanager'; //系统管理员
    const ROLE_COMP = 'cmanager'; //承包商管理员
    /*const ROLE_MC = 'mcmanager'; //总包管理员
    const ROLE_SC = 'scmanager'; //分包管理员    */


    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_operator_app';
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

    public static  function updateApp($args){

    }

    public static function appMenuList($operator_id){

        $sql = "SELECT a.app_id,b.app_name,b.app_name_en FROM bac_operator_app a,sys_app b WHERE a.status ='00' and a.operator_id = '".$operator_id."' and a.app_id = b.app_id and b.app_status='00' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        if(count($rows) > 0){
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['app_id']] = $row['app_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['app_id']] = $row['app_name_en'];
                }
            }
        }

        return $rs;
    }

}
