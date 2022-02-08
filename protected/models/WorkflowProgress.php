<?php

/**
 * 
 *
 * @author liuxiaoyuan
 */
class WorkflowProgress extends CActiveRecord {

    const STATUS_WAIT = '00'; //未审批
    const STATUS_AUDITING = '01'; //审批中
    const STATUS_FINISH = '02'; //审批完成
    
    public function tableName() {
        return 'workflow_progress';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
