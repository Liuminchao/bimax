<?php

/**
 *
 *
 * @author liuminchao
 */
class CheckApplyDetail extends CActiveRecord {
   //状态:0 申请  1 申请审批   2 申请批准  3关闭   4 关闭审批   5  关闭批准   6 强制驳回
    const STATUS_APPLY = '0'; //申请
    const STATUS_APPLY_ACCESS = '1'; //申请审批
    const STATUS_APPLY_APPROVE = '2'; //申请批准
    const STATUS_CLOSE = '3'; //关闭
    const STATUS_CLOSE_ACCESS = '4'; //关闭审批
    const STATUS_CLOSE_APPROVE = '5'; //关闭批准
    const STATUS_REVOKED = '6';//驳回
    const STATUS_ALTER = '7';//修改
    const STATUS_FIRST_APPLY_ACCESS = '8';//申请预审
    const STATUS_FIRST_CLOSE_ACCESS = '9';//关闭预审
    const STATUS_ACKNOWLEDG = '10';//确认

    const RESULT_WAIT = 0;//待处理
    const RESULT_YES = 1;//成功　
    const RESULT_NO = 2;//拒绝

    public function tableName() {
        return 'bac_check_apply_detail';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_APPLY => Yii::t('check_apply', 'STATUS_APPLY'),
            self::STATUS_APPLY_ACCESS => Yii::t('check_apply', 'STATUS_APPLY_ACCESS'),
            self::STATUS_FIRST_APPLY_ACCESS => Yii::t('check_apply', 'STATUS_APPLY_ACCESS'),
            self::STATUS_APPLY_APPROVE => Yii::t('check_apply', 'STATUS_APPLY_APPROVE'),
            self::STATUS_CLOSE => Yii::t('check_apply', 'STATUS_CLOSE'),
            self::STATUS_CLOSE_ACCESS => Yii::t('check_apply', 'STATUS_CLOSE_ACCESS'),
            self::STATUS_FIRST_CLOSE_ACCESS => Yii::t('check_apply', 'STATUS_CLOSE_ACCESS'),
            self::STATUS_CLOSE_APPROVE => Yii::t('check_apply', 'STATUS_CLOSE_APPROVE'),
            self::STATUS_REVOKED => Yii::t('check_apply', 'STATUS_REVOKED'),
            self::STATUS_ALTER => Yii::t('check_apply','STATUS_ALTER'),
            self::STATUS_ACKNOWLEDG => Yii::t('check_apply','STATUS_ACKNOWLEDG'),

        );
        return $key === null ? $rs : $rs[$key];
    }

    //等待状态
    public static function pendingText($key = null) {
        $rs = array(
            '2' => Yii::t('check_apply', 'Pending_APPLY_ACCESS'),
            '3' => Yii::t('check_apply', 'Pending_APPLY_APPROVE'),
            '4' => Yii::t('check_apply', 'Pending_CLOSE'),
            '5' => Yii::t('check_apply', 'Pending_CLOSE_ACCESS'),
            '6' => Yii::t('check_apply', 'Pending_CLOSE_APPROVE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => Yii::t('tbm_meeting', 'agree'),
            self::RESULT_NO => Yii::t('tbm_meeting', 'disagree'),
            self::RESULT_WAIT => Yii::t('tbm_meeting', 'wait'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function dealtypeTxt($key = null) {
        $rs = array(
            self::STATUS_APPLY => 'Submitted',
            self::STATUS_APPLY_ACCESS => 'Assessed',
            self::STATUS_APPLY_APPROVE => 'Approved',
            self::STATUS_CLOSE => 'Closed',self::STATUS_CLOSE_APPROVE => 'Closure Accepted',
            self::STATUS_CLOSE_ACCESS => 'Closure Assessed',
            self::STATUS_CLOSE_APPROVE=> 'Closure Approved',
            self::STATUS_REVOKED => 'Revoked',
            self::STATUS_ALTER =>  'Revised',
            self::STATUS_FIRST_APPLY_ACCESS => 'Assessed',
            self::STATUS_FIRST_CLOSE_ACCESS => 'Approved',
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function statusTxt($key = null) {
        $rs = array(
            self::STATUS_APPLY => 'Submitted<br>(已申请)',
            self::STATUS_APPLY_ACCESS => 'Assessed<br>(已审批)',
            self::STATUS_FIRST_APPLY_ACCESS => 'Assessed<br>(已审批)',
            self::STATUS_APPLY_APPROVE => 'Approved<br>(已批准)',
            self::STATUS_CLOSE => 'Closed<br>(已关闭申请)',
            self::STATUS_CLOSE_ACCESS => 'Closure Approved<br>(已关闭审批)',
            self::STATUS_FIRST_CLOSE_ACCESS => 'Assessed<br>(已关闭审批)',
            self::STATUS_CLOSE_APPROVE => 'Closure Approved<br>(已关闭批准)',
            self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
            self::STATUS_ALTER =>  'Revised<br>(已修改)',
        );
        return $key === null ? $rs : $rs[$key];
    }
    public static function rejectTxt($key = null) {
        $rs = array(
            self::STATUS_APPLY_ACCESS => 'Rejected<br>(审批不通过)',
            self::STATUS_FIRST_APPLY_ACCESS => 'Rejected<br>(审批不通过)',
            self::STATUS_APPLY_APPROVE => 'Rejected<br>(批准不通过)',
            self::STATUS_CLOSE => 'PTW Closed<br>(已关闭申请)',
            self::STATUS_CLOSE_ACCESS => 'Rejected<br>(关闭审批不通过)',
            self::STATUS_FIRST_CLOSE_ACCESS => 'Rejected<br>(关闭审批不通过)',
            self::STATUS_CLOSE_APPROVE => 'Rejected<br>(关闭批准不通过)',
            self::STATUS_REVOKED => 'Revoked<br>(强制驳回)',
        );
        return $key === null ? $rs : $rs[$key];
    }
    public static function resultTxt($key = null) {
        $rs = array(
            self::RESULT_YES => 'Agree(同意)',
            self::RESULT_NO => 'Disagree(拒绝)',
            self::RESULT_WAIT => 'Wait(待处理)',
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 审批步骤(类型)
     */
    public static function dealtypeList($app_id, $apply_id,$step) {
        $sql = "select deal_type,status from bac_check_apply_detail
                 where app_id=:app_id and apply_id=:apply_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
    /**
     * 审批结果(快照)
     * @return type
     */
    public static function progressList($app_id, $apply_id) {

        $list = array();

        $sql = "select a.deal_user_id,a.deal_type, a.status, a.deal_time, a.step,a.remark,a.pic,a.address,a.check_list,
                       b.user_name
                  from bac_check_apply_detail a
                  left join bac_staff b
                    on a.deal_user_id = b.user_id
                 where a.app_id=:app_id and a.apply_id=:apply_id
                 order by a.step asc";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
    /**
     * 添加RA申请步骤
     */
    public static function insertRaApplyDetail($ra_swp_id){
//        $apply_model = CheckApply::model()->findByPk($ra_swp_id);
//        $step = $apply_model->current_step;
        $model = new CheckApplyDetail('create');
        $app_id = 'RA';
        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->apply_id = $ra_swp_id;
            $model->app_id = $app_id;
            $model->deal_type = '0';
            $model->step = '1';
            $model->deal_user_id = Yii::app()->user->id;
            $model->status = '0';
            $model->apply_time = date('Y-m-d H:i:s', time());
            $model->deal_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            $trans->commit();
            if ($result) {
                $r['msg'] = Yii::t('common', 'success_submit');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_submit');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    /**
     * 修改RA申请步骤
     */
    public static function updateRaApplyDetail($ra_swp_id){
        $apply_model = CheckApply::model()->findByPk($ra_swp_id);
        $step = $apply_model['current_step'];
        $model = new CheckApplyDetail('create');
        $app_id = 'RA';
        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->apply_id = $ra_swp_id;
            $model->app_id = $app_id;
            $model->deal_type = '0';
            $model->step = $step + 1;
            $model->deal_user_id = Yii::app()->user->id;
            $model->status = '0';
            $model->apply_time = date('Y-m-d H:i:s', time());
            $model->deal_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            $trans->commit();
            if ($result) {
                $r['msg'] = Yii::t('common', 'success_apply');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_apply');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 审批步骤(类型)
     */
    public static function dealList($apply_id) {
        $sql = "select * from bac_check_apply_detail
                 where apply_id=:apply_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }
    /**
     * 查询第3个步骤批准人
     */
    public static function queryApprovedPerson($args) {
       // $sql = "select b.deal_user_id
       //            from bac_check_apply_detail a
       //            left join ptw_apply_basic b
       //              on a.apply_id = b.apply_id
       //           where a.program_id=".$args['program_id']." and a.type_id=".$args['type_id']."
       //           order by a.step asc";
        $condition = '';
        $params = array();
        if ($args['program_id'] != '') {
            //总包项目
            $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
            $params['program_id'] = $args['program_id'];
        }

        //type_id
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' type_id=:type_id' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }

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

        // if ($_REQUEST['q_order'] == '') {

        //     $order = 'record_time desc';
        // } else {
        //     if (substr($_REQUEST['q_order'], -1) == '~')
        //         $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
        //     else
        //         $order = $_REQUEST['q_order'] . ' ASC';
        // }
        $criteria = new CDbCriteria();
        $criteria->select = 't.deal_user_id';//代表了要查询的字段，默认select='*';
        $criteria->join = 'ptw_apply_basic as b'; //连接表
        // $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $rows = CheckApplyDetail::model()->findAll($criteria);
        return $rows;
    }
}
