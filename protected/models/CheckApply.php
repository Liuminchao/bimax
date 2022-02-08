<?php

/**
 * 
 *
 * @author liuminchao
 */
class CheckApply extends CActiveRecord {

    const STATUS_WAIT = '00'; //未审批
    const STATUS_AUDITING = '01'; //审批中
    const STATUS_FINISH = '02'; //审批完成
    
    public function tableName() {
        return 'bac_check_apply';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    /**
     * 添加RA申请
     */
    public static function insertRaApply($ra_swp_id){
        $model = new CheckApply('create');
        $app_id = 'RA';
        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->apply_id = $ra_swp_id;
            $model->app_id = $app_id;
            $model->result = '0';
            $model->current_step = '1';
            $model->apply_user_id = Yii::app()->user->id;
            $model->start_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            $trans->commit();
            if ($result) {
                $r['msg'] = Yii::t('comp_ra', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('comp_ra', 'error_insert');
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
     * 修改RA申请
     */
    public static function updateRaApply($ra_swp_id){
        $model = CheckApply::model()->findByPk($ra_swp_id);
        $step = $model['current_step'];
        $app_id = 'RA';
        $trans = $model->dbConnection->beginTransaction();
        try {
            $model->apply_id = $ra_swp_id;
            $model->app_id = $app_id;
            $model->result = '0';
            $model->current_step = $step + 1;
            $model->apply_user_id = Yii::app()->user->id;
            $model->start_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            $trans->commit();
            if ($result) {
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
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
}
