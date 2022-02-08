<?php

/**
 * 
 *
 * @author liuxiaoyuan
 */
class WorkflowProgressDetail extends CActiveRecord {

    const STATUS_WAIT = '00'; //未审批
    const STATUS_AUDITING = '01'; //审批中
    const STATUS_FINISH = '02'; //审批完成

    const RESULT_YES = 0;//通过
    const RESULT_NO = 1;//未通过　
    const RESULT_WAIT = -1;//待处理
    
    public function tableName() {
        return 'workflow_progress_detail';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => Yii::t('tbm_meeting', 'agree'),
            self::RESULT_NO => Yii::t('tbm_meeting', 'disagree'),
            self::RESULT_WAIT => Yii::t('tbm_meeting', 'wait'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    

    /**
     * 审批结果
     * @return type
     */
    public static function progressList($app_id, $apply_id) {

        $list = array();

        $sql = "select a.seq_id, a.approve_uid, a. approve_status, a.approve_date, a.approve_step,
                       b.user_name
                  from workflow_progress_detail a
                  left join bac_staff b
                    on a.approve_uid = b.user_id
                 where a.app_id=:app_id and a.apply_id=:apply_id
                 order by a.approve_step asc";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();

        return $rows;
    }
    
}
