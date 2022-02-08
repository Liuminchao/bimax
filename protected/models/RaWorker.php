<?php

/**
 * 参会人员
 * @author Liuminchao
 */
class RaWorker extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ra_swp_worker';
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

        //Meeting
        if ($args['ra_swp_id'] != '') {
            $condition.= ( $condition == '') ? ' meeting_id=:meeting_id' : ' AND meeting_id=:meeting_id';
            $params['meeting_id'] = $args['meeting_id'];
        }
        //Worker
        if ($args['worker_id'] != '') {
            $condition.= ( $condition == '') ? ' worker_id=:worker_id' : ' AND worker_id=:worker_id';
            $params['worker_id'] = $args['worker_id'];
        }
        //Worker Name
        if ($args['worker_name'] != '') {
            $condition.= ( $condition == '') ? ' worker_name=:worker_name' : ' AND worker_name=:worker_name';
            $params['worker_name'] = $args['worker_name'];
        }
        //Creater
        if ($args['creater_id'] != '') {
            $condition.= ( $condition == '') ? ' creater_id=:creater_id' : ' AND creater_id=:creater_id';
            $params['creater_id'] = $args['creater_id'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Record Time
        if ($args['record_time'] != '') {
            $condition.= ( $condition == '') ? ' record_time=:record_time' : ' AND record_time=:record_time';
            $params['record_time'] = $args['record_time'];
        }


        $total_num = RaWorker::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'ra_swp_id';
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
        $rows = MeetingWorker::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /** 根据项目得到RA成员的名字
     * @return type
     */
    public static function getMembersName($ra_swp_id) {

        $member = '';
        $sql = "select worker_name,worker_id from ra_swp_worker where ra_swp_id=:ra_swp_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":ra_swp_id", $ra_swp_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['worker_id']]['worker_name'] = $row['worker_name'];
            }
        }
        return $rs;
    }
    /**
     * 添加RA成员
     */
    public static function insertMembers($ra_swp_id,$ra_user,$ra_leader,$ra_approver){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $status = '00';
        if(!empty($ra_user)){
            foreach($ra_user as $cnt => $user_id){
                $record_time = date('Y-m-d H:i:s');
                $sql = "INSERT INTO  ra_swp_worker (ra_swp_id,worker_id,worker_name,contractor_id,status,record_time)VALUES(:ra_swp_id,:worker_id,:worker_name,:contractor_id,:status,:record_time)";
                $command = Yii::app()->db->createCommand($sql);
                $staff_list = Staff::model()->findByPk($user_id);
                $command->bindParam(":ra_swp_id", $ra_swp_id, PDO::PARAM_STR);
                $command->bindParam(":worker_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":worker_name", $staff_list['user_name'], PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }

        if(!empty($ra_leader)){
            foreach($ra_leader as $cnt => $user_id){
                $record_time = date('Y-m-d H:i:s');
                $sql = "INSERT INTO  ra_swp_worker (ra_swp_id,worker_id,worker_name,contractor_id,status,record_time)VALUES(:ra_swp_id,:worker_id,:worker_name,:contractor_id,:status,:record_time)";
                $command = Yii::app()->db->createCommand($sql);
                $staff_list = Staff::model()->findByPk($user_id);
                $command->bindParam(":ra_swp_id", $ra_swp_id, PDO::PARAM_STR);
                $command->bindParam(":worker_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":worker_name", $staff_list['user_name'], PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }

        if(!empty($ra_approver)){
            foreach($ra_approver as $cnt => $user_id){
                $record_time = date('Y-m-d H:i:s');
                $sql = "INSERT INTO  ra_swp_worker (ra_swp_id,worker_id,worker_name,contractor_id,status,record_time)VALUES(:ra_swp_id,:worker_id,:worker_name,:contractor_id,:status,:record_time)";
                $command = Yii::app()->db->createCommand($sql);
                $staff_list = Staff::model()->findByPk($user_id);
                $command->bindParam(":ra_swp_id", $ra_swp_id, PDO::PARAM_STR);
                $command->bindParam(":worker_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":worker_name", $staff_list['user_name'], PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }

        if($rs >= 0){
            $r['msg'] = Yii::t('common', 'success_apply');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common', 'error_apply');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    /**
     * 修改RA成员
     */
    public static function updateMembers($ra_swp_id,$ra_user){
        $result = RaWorker::model()->deleteAll('ra_swp_id=:ra_swp_id',array(':ra_swp_id'=>$ra_swp_id));
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $status = '00';
        if($result > 0) {
            foreach ($ra_user as $cnt => $user_id) {
                $sql = "INSERT INTO  ra_swp_worker (ra_swp_id,worker_id,worker_name,contractor_id,status)VALUES(:ra_swp_id,:worker_id,:worker_name,:contractor_id,:status)";
                $command = Yii::app()->db->createCommand($sql);
                $staff_list = Staff::model()->findByPk($user_id);
                $command->bindParam(":ra_swp_id", $ra_swp_id, PDO::PARAM_STR);
                $command->bindParam(":worker_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":worker_name", $staff_list['user_name'], PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $rs = $command->execute();
            }
        }
        if($rs >= 0){
            $r['msg'] = Yii::t('common', 'success_apply');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common', 'error_apply');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
}
