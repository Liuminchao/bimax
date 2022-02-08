<?php

/**
 * 操作员日志
 * @author LiuXiaoyuan
 */
class OperatorLog extends CActiveRecord {

    //操作结果
    const OPT_RESULT_SUCCESS = '0';
    const OPT_RESULT_FAIL = '1';
    //日志模块
    const MODULE_ID_OPERATOR = 'OPERATOR'; //操作员管理
    const MODULE_ID_ROLE = 'ROLE'; //角色管理
    const MODULE_ID_PROJ = 'PROJ';//项目管理
    const MODULE_ID_MAINCOMP ='COMP';//总包管理
    const MODULE_ID_USER ='USER';//用户管理
    const MODULE_ID_FLOW = 'FLOW';//工作流配置
    const MODULE_ID_LICENSE = 'LICEN';//许可证配置
    const MODULE_ID_ATTEND = 'ATTEND';//考勤 
    
    public function tableName() {
        $table = "bac_operator_log_" . date("Ym");
        $sql = 'create table  if not exists `' . $table . '` like bac_operator_log_';
        Yii::app()->db->createCommand($sql)->execute();

        return $table;
    }
    

    //操作结果描述
    public static function statusDesc($status = '_ARRAY') {
        $rs = array(
            self::OPT_RESULT_SUCCESS => Yii::t('common','success'),
            self::OPT_RESULT_FAIL =>  Yii::t('common','fail'),
        );

        return $status == '_ARRAY' ? $rs : $rs[$status];
    }

    //操作结果css
    public static function statusCss($key = null) {
        $rs = array(
            self::OPT_RESULT_SUCCESS => 'label-success', //成功
            self::OPT_RESULT_FAIL => 'label-danger', //失败
        );
        return $key === null ? $rs : $rs[$key];
    }

    //操作模块描述
    public static function moduleDesc($module_id = "_ARRAY") {
        $rs = array(
            self::MODULE_ID_OPERATOR => Yii::t('sys_optlog','MODULE_ID_OPERATOR'),
            self::MODULE_ID_ROLE => Yii::t('sys_optlog','MODULE_ID_ROLE'),
            self::MODULE_ID_PROJ => Yii::t('sys_optlog','MODULE_ID_PROJ'),
            self::MODULE_ID_FLOW => Yii::t('sys_optlog','MODULE_ID_FLOW'),
            self::MODULE_ID_OPERATOR => Yii::t('sys_optlog','MODULE_ID_OPERATOR'),
            self::MODULE_ID_ROLE => Yii::t('sys_optlog','MODULE_ID_ROLE'),
            self::MODULE_ID_PROJ => Yii::t('sys_optlog','MODULE_ID_PROJ'),
            self::MODULE_ID_MAINCOMP => Yii::t('sys_optlog','MODULE_ID_MAINCOMP'),
            self::MODULE_ID_USER => Yii::t('sys_optlog','MODULE_ID_USER'),
            self::MODULE_ID_FLOW => Yii::t('sys_optlog','MODULE_ID_FLOW'),
            self::MODULE_ID_LICENSE => Yii::t('sys_optlog','MODULE_ID_LICENSE'),
            self::MODULE_ID_ATTEND => Yii::t('sys_optlog','MODULE_ID_ATTEND'),
        );
        return $module_id == '_ARRAY' ? $rs : $rs[$module_id];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('operator_id, opt_field, record_time', 'required'),
            array('operator_id, opt_host_ip', 'length', 'max' => 64),
            array('operator_name', 'length', 'max' => 256),
            array('operator_type, opt_result', 'length', 'max' => 16),
            array('opt_field, result_desc', 'length', 'max' => 128),
            array('opt_desc', 'length', 'max' => 4000),
            array('module_id', 'length', 'max' => 32),
            array('status', 'length', 'max' => 2),
            array('opt_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('log_sn, operator_id, operator_name, operator_type, opt_field, opt_desc, opt_result, result_desc, opt_host_ip, opt_time, module_id, status, record_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OperatorLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //IP地址
    public static function getIP() {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = "无法获取！";
        }
        return $cip;
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
        //Opt Field
        if ($args['opt_field'] != '') {
            $condition.= ( $condition == '') ? ' opt_field LIKE :opt_field' : ' AND opt_field LIKE :opt_field';
            $params['opt_field'] = '%' . $args['opt_field'] . '%';
        }
        //Module
        if ($args['module_id'] != '') {
            $condition.= ( $condition == '') ? ' module_id=:module_id' : ' AND module_id=:module_id';
            $params['module_id'] = $args['module_id'];
        }
        //操作开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' record_time >=:start_date' : ' AND record_time >=:start_date';
            $params['start_date'] = $args['start_date'];
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' record_time <=:end_date' : ' AND record_time <=:end_date';
            $params['end_date'] = $args['end_date'] . " 23:59:59";
        }

        $total_num = OperatorLog::model()->count($condition, $params); //总记录数

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
        $rows = OperatorLog::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 添加日志
     * @param array $logitem
     * @param string $status
     */
    public static function savelog($module_id, $opt_field, $logitem = array(), $status = self::OPT_RESULT_SUCCESS) {
        $log = New OperatorLog();

        $log->operator_id = Yii::app()->user->id;
        $log->operator_name = Yii::app()->user->getState('name');
        $log->operator_type = Yii::app()->user->getState('operator_type');
        $log->opt_field = $opt_field;
        $log->opt_result = $status;
        $log->result_desc = self::statusDesc($status);
        $log->opt_host_ip = self::getIP();
        $log->opt_time = date("Y-m-d H:i:s");
        $log->record_time = date("Y-m-d H:i:s");
        $log->module_id = $module_id;
        if (count($logitem) > 0) {
            //var_dump($logitem);
            $detail = json_encode($logitem);
            $log->opt_desc = $detail;
        }

        $log->save();
    }

}
