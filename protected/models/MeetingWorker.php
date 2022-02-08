<?php

/**
 * 参会人员
 * @author LiuXiaoyuan
 */
class MeetingWorker extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbm_meeting_worker';
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
        if ($args['meeting_id'] != '') {
            $condition.= ( $condition == '') ? ' meeting_id=:meeting_id' : ' AND meeting_id=:meeting_id';
            $params['meeting_id'] = $args['meeting_id'];
        }
        //Worker
        if ($args['worker_id'] != '') {
            $condition.= ( $condition == '') ? ' worker_id=:worker_id' : ' AND worker_id=:worker_id';
            $params['worker_id'] = $args['worker_id'];
        }
        //Wp No
        if ($args['wp_no'] != '') {
            $condition.= ( $condition == '') ? ' wp_no=:wp_no' : ' AND wp_no=:wp_no';
            $params['wp_no'] = $args['wp_no'];
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


        $total_num = MeetingWorker::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'meeting_id';
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

    /** 得到成员的名字
     * @return type
     */
    public static function getMembersName($mid) {

        $member = '';
        $sql = "select a.worker_name,a.worker_id,a.wp_no,b.role_id from tbm_meeting_worker a,bac_staff b where a.meeting_id=:mid and a.worker_id=b.user_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":mid", $mid, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['worker_id']]['worker_name'] = $row['worker_name'];
                $rs[$row['worker_id']]['wp_no'] = $row['wp_no'];
                $rs[$row['worker_id']]['role_id'] = $row['role_id'];
            }
        }else{
            $rs =array();
        }
        return $rs;
    }
    
}
