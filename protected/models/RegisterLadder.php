<?php

/**
 * ScaffoldAudit
 * @author LiuMinChao
 */
class RegisterLadder extends CActiveRecord {

    const STATUS_NORMAL = '0'; //已启用
    const STATUS_DISABLE = '1'; //未启用

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'bac_register_ladder';
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
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Status
        $status = self::STATUS_NORMAL;
        if ($status != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $status;
        }

        $total_num = RegisterLadder::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'id';
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
        $rows = RegisterLadder::model()->findAll($criteria);

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
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Status
        $status = self::STATUS_NORMAL;
        if ($status != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $status;
        }

        $total_num = RegisterLadder::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'id';
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
        $rows = RegisterLadder::model()->findAll($criteria);

        return $rows;
    }

    //插入数据
    public static function InsertList($data,$program_id){
        $status = self::STATUS_NORMAL;
        $record_time = date('Y-m-d H:i:s',time());//获取精确时间
        $contractor_id = $contractor_id = Yii::app()->user->getState('contractor_id');
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach ($data as $k => $v){
                $sub_sql = 'INSERT INTO bac_register_ladder(program_id,contractor_id,ladder_no,ladder_type,height,using_by,location,inspected_by,remarks,record_time,status) VALUES(:program_id,:contractor_id,:ladder_no,:ladder_type,:height,:using_by,:location,:inspected_by,:remarks,:record_time,:status)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                $command->bindParam(":ladder_no", $v->ladder_no, PDO::PARAM_STR);
                $command->bindParam(":ladder_type", $v->ladder_type, PDO::PARAM_STR);
                $command->bindParam(":height", $v->height, PDO::PARAM_STR);
                $command->bindParam(":using_by", $v->using_by, PDO::PARAM_STR);
                $command->bindParam(":location", $v->location, PDO::PARAM_STR);
                $command->bindParam(":inspected_by", $v->inspected_by, PDO::PARAM_STR);
                $command->bindParam(":remarks", $v->remarks, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $rs = $command->execute();
            }


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
    public static function UpdateList($id,$args){
        $status = self::STATUS_NORMAL;
        $model = RegisterLadder::model()->findByPk($id);
        $trans = Yii::app()->db->beginTransaction();
        try {
            $model->program_id = $args['program_id'];
            $model->ladder_no = $args['ladder_no'];
            $model->ladder_type = $args['ladder_type'];
            $model->height = $args['height'];
            $model->using_by = $args['using_by'];
            $model->location = $args['location'];
            $model->inspected_by = $args['inspected_by'];
            $model->remarks = $args['remarks'];
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
    public static function DeleteList($id) {
//        $sql = "delete from bac_scaffold_audit where id = '".$id."' ";
//        $command = Yii::app()->db->createCommand($sql);
//        $re = $command->execute();
        $status = self::STATUS_DISABLE;
        $model = RegisterLadder::model()->findByPk($id);
        $model->status = $status;
        $result = $model->save();
        if ($result) {
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
