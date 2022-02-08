<?php

/**
 * TransmittalDetail
 * @author LiuMinchao
 */
class TransmittalDetail extends CActiveRecord {

    //状态 1 approval 2 review 3 批准 4 批准(带有评论) 5record purposes 6 重新修改提交 7 拒绝 8关闭 9 request for information 10response
    const STATUS_DRAFT = '0';
    const STATUS_APPROVAL = '1';
    const STATUS_REVIEW = '2';
    const STATUS_APPROVE = '3';
    const STATUS_APPROVE_COMMENT = '4';
    const STATUS_RECORD_PURPOSES = '5';
    const STATUS_RESUBMIT = '6';
    const STATUS_REJECT = '7';
    const STATUS_CLOSE = '8';
    const STATUS_REQUEST_FOR_INFORMATION = '9';
    const STATUS_RESPONSE = '10';
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'transmittal_record_detail';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMIT =>  'Submit',
            self::STATUS_REPLY => 'Reply',
            self::STATUS_FORWARD =>  'Forward',
            self::STATUS_APPROVE => 'Approve',
            self::STATUS_APPROVE_COMMENT =>  'Approve(with comment)',
            self::STATUS_REJECT => 'Reject',
            self::STATUS_WITHDRAW =>  'Withdraw',
            self::STATUS_CLOSE =>  'close',
            self::STATUS_APPROVE_SUBMIT => 'APPROVED WITH COMMENTS<br>(NO RESUBMISSION REQUIRED)',
            self::STATUS_RECORD_PURPOSES=> 'FOR RECORD PURPOSES',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //动作列表
    //1 approval 2 review 3 批准 4 批准(带有评论) 5record purposes 6 重新修改提交 7 拒绝 8关闭 9 request for information 10response
    public static function typeList($key = null) {
        $rs = array(
            '1' =>  'Request For Approval',
            '2' =>  'Request For Review',
            '3' =>  'In-Principal No Objection',
            '4' =>  'Accepted with Comments',
            '5' =>  'For Record Purpose',
            '6' =>  'Rejected',
            '7' =>  'Re-test / Revise & Resubmit',
            '8' =>  'Close',
            '9' =>  'Request for information',
            '10' => 'Replied',
            '11' => 'Approved',
            '12' => 'Not Approved',
            '13' => 'Comment',

        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function shorttypeList($key = null) {
        $rs = array(
            '3' =>  'IPNO',
            '4' =>  'AwC',
            '6' =>  'Rej',
            '7' =>  'RT/Rv&Rs',
            '11' => 'Apv',
            '12' => 'N-Apv'

        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function typecolorList($key = null) {
        $rs = array(
            '1' =>  '#ddd',
            '2' =>  '#ddd',
            '3' =>  '#00CC00',
            '4' =>  '#00FF00',
            '5' =>  '#00BFFF',
            '6' =>  '#FFA500',
            '7' =>  '#CC6600',
            '8' =>  '#ddd',
            '9' =>  '#ddd',
            '10' => '#ddd',
            '11' => '#00FF00',
            '12' => '#CC6600',
            '13' => '#4a86e8',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'label-info',
            self::STATUS_SUBMIT =>  'label-default',
            self::STATUS_PENDING => 'label-info',
            self::STATUS_CLOSE =>  'label-success',
        );
        return $key === null ? $rs : $rs[$key];
    }


    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //创建
    public static function insertList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $id = date('Ymd').rand(01,99).date('His');
        $status ='1';
        $record_time = date("Y-m-d H:i:s");
        $sql = "insert into transmittal_record_detail (check_id,step,user_id,deal_type,remark,status,record_time) values (:check_id,:step,:user_id,:deal_type,:remark,:status,:record_time)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
        $command->bindParam(":user_id", $args['add_user'], PDO::PARAM_STR);
        $command->bindParam(":deal_type", $args['deal_type'], PDO::PARAM_STR);
        $command->bindParam(":remark", $args['remark'], PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['id'] = $id;
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //提交
    public static function sendList($args){
        $id = date('Ymd').rand(01,99).date('His');
        $record_time = date("Y-m-d H:i:s");
        $sql = "update rf_record_detail set deal_type = :deal_type,remark = :remark,status = :status,record_time = :record_time where check_id = :check_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $command->bindParam(":deal_type", $args['deal_type'], PDO::PARAM_STR);
        $command->bindParam(":remark", $args['message'], PDO::PARAM_STR);
        $command->bindParam(":status", $args['button_type'], PDO::PARAM_STR);
        $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
        $rs = $command->execute();
        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['id'] = $id;
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($check_id) {
        $sql = "select * from transmittal_record_detail
                 where check_id=:check_id order by step desc";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 根据步骤查询详情
     */
    public static function dealListByStep($check_id,$step) {
        $sql = "select * from transmittal_record_detail
                 where check_id=:check_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 根据类型判断第几步
     */
    public static function stepByShortType($check_id,$type) {
        $sql = "select * from rf_record_detail
                 where check_id=:check_id and status=:status";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":status", $type, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if(count($rows)>0){
            foreach ($rows as $i => $j){
                $r['short_deal_type'] = self::shorttypeList($j['deal_type']);
                $r['long_deal_type'] = self::typeList($j['deal_type']);
                $r['deal_type'] = $j['deal_type'];
                if($j['deal_type'] == '3' || $j['deal_type'] == '4'){
                    $r['color'] = 'green';
                }elseif($j['deal_type'] == '6' || $j['deal_type'] == '12'){
                    $r['color'] = 'red';
                }elseif($j['deal_type'] == '7' || $j['deal_type'] == '11'){
                    $r['color'] = 'orange';
                }
            }
        }else{
            $r['short_deal_type'] = '---';
            $r['long_deal_type'] = '---';
            $r['deal_type'] = '';
            $r['color'] = '';
        }

        return $r;
    }

}
