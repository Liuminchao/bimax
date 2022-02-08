<?php

/**
 * 会议计划列表
 * @author LiuMinChao
 */
class MeetingPlan extends CActiveRecord {

    const STATUS_NORMAL = '0'; //已启用
    const STATUS_DISABLE = '1'; //未启用

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbm_meeting_plan';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MeetingWorker the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_DISABLE => ' label-danger', //已注销
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('comp_staff', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('comp_staff', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
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

        //Program id
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Contractor id
        if ($args['contractor_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Plan date
        if ($args['plan_date'] != '') {
            $plan_date = Utils::MonthToCn($args['plan_date']);
            $condition.= ( $condition == '') ? ' plan_date like :plan_date' : ' AND plan_date like :plan_date';
            $params['plan_date'] = '%'.$plan_date.'%';
        }
        //Plan detail
//        if ($args['plan_detail'] != '') {
//            $condition.= ( $condition == '') ? ' plan_detail like :plan_detail' : ' AND plan_detail like :plan_detail';
//            $params['plan_detail'] = '%'.$args['plan_detail'].'%';
//        }

        $total_num = MeetingPlan::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'plan_id';
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
        $rows = MeetingPlan::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //导出全部数据(不分页)
    public static function exportList($args = array()) {

        $condition = '';
        $params = array();

        //Program id
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            $condition.= ( $condition == '') ? ' program_id =:program_id' : ' AND program_id =:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Contractor id
        if ($args['contractor_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Plan date
        if ($args['plan_date'] != '') {
            $plan_date = Utils::MonthToCn($args['plan_date']);
            $condition.= ( $condition == '') ? ' plan_date like :plan_date' : ' AND plan_date like :plan_date';
            $params['plan_date'] = '%'.$plan_date.'%';
        }
        //Plan detail
//        if ($args['plan_detail'] != '') {
//            $condition.= ( $condition == '') ? ' plan_detail like :plan_detail' : ' AND plan_detail like :plan_detail';
//            $params['plan_detail'] = '%'.$args['plan_detail'].'%';
//        }

        $total_num = MeetingPlan::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'plan_id';
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
        $pages->applyLimit($criteria);
        $rows = MeetingPlan::model()->findAll($criteria);
        if(!empty($rows)){
            foreach($rows as $k => $v){
                $rs[$v['plan_date']] = $v['plan_detail'];
            }
        }
        return $rs;
    }

    //插入数据
    public static function InsertList($args){
        $status = self::STATUS_NORMAL;
        $record_time = date('Y-m-d H:i:s',time());//获取精确时间
        $args['plan_date'] = Utils::DateToCn($args['plan_date']);
        $exist_data = MeetingPlan::model()->count('program_id=:program_id and contractor_id=:contractor_id and plan_date=:plan_date', array('program_id' => $args['program_id'],'contractor_id' => $args['contractor_id'],'plan_date' => $args['plan_date']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('tbm_meeting', 'Error Plan is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['plan_date']==''){
            $r['msg']=Yii::t('tbm_meeting', 'Error plan date is null');
            $r['status']= -1;
            $r['refersh'] = false;
            return $r;
        }
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sub_sql = 'INSERT INTO tbm_meeting_plan(program_id,contractor_id,plan_detail,plan_date,record_time,status) VALUES(:program_id,:contractor_id,:plan_detail,:plan_date,:record_time,:status)';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
            $command->bindParam(":contractor_id", $args['contractor_id'], PDO::PARAM_INT);
            $command->bindParam(":plan_detail", $args['plan_detail'], PDO::PARAM_STR);
            $command->bindParam(":plan_date", $args['plan_date'], PDO::PARAM_STR);
            $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $rs = $command->execute();

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }

    //修改数据
    public static function UpdateList($plan_id,$args){
        $status = self::STATUS_NORMAL;
        $model = MeetingPlan::model()->findByPk($plan_id);
        $plan_date = $model->plan_date;
        if($plan_date != $args['plan_date']){
            $exist_data = MeetingPlan::model()->count('program_id=:program_id and contractor_id=:contractor_id and plan_date=:plan_date', array('program_id' => $args['program_id'],'contractor_id' => $args['contractor_id'],'plan_date' => $args['plan_date']));
            if ($exist_data != 0) {
                $r['msg'] = Yii::t('tbm_meeting', 'Error Plan is exist');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }
        $trans = Yii::app()->db->beginTransaction();
        try {
            $model->program_id = $args['program_id'];
            $model->contractor_id = $args['contractor_id'];
            $model->plan_date = Utils::DateToCn($args['plan_date']);
            $model->plan_detail = $args['plan_detail'];
            $model->status = $status;
            $result = $model->save();

            if ($result) {

                $trans->commit();

                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }

    //删除数据
    public static function DeleteList($plan_id) {
        $sql = "delete from tbm_meeting_plan where plan_id = '".$plan_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();
        if ($re == 1) {
            $r['msg'] = Yii::t('common', 'success_delete');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
}
