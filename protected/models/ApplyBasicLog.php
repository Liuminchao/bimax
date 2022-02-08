<?php

/**
 * 许可证申请基本信息表
 * @author LiuXiaoyuan
 */
class ApplyBasicLog extends CActiveRecord {

    //状态：00-未审批，01－审批中，02审批完成。
    const STATUS_WAIT = '00'; //未审批
    const STATUS_AUDITING = '01'; //审批中
    const STATUS_FINISH = '02'; //审批完成
    const RESULT_YES = 1;
    const RESULT_NO = 2;
    const RESULT_NA = 3;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ptw_apply_basic_log';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_WAIT => Yii::t('license_licensepdf', 'STATUS_WAIT'),
            self::STATUS_AUDITING => Yii::t('license_licensepdf', 'STATUS_AUDITING'),
            self::STATUS_FINISH => Yii::t('license_licensepdf', 'STATUS_FINISH'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_AUDITING => 'label-warning', //审批中
            self::STATUS_FINISH => 'label-success', //审批完成
            self::STATUS_WAIT => 'label-danger', //未审批
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => '√',
            self::RESULT_NO => '×',
            self::RESULT_NA => '*NA',
        );
        return $key == null ? $rs : $rs[$key];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'apply_id' => 'Apply',
            'approve_id' => 'Approve',
            'program_id' => 'Program',
            'program_name' => 'Program Name',
            'apply_date' => 'Apply Date',
            'contractor_id' => 'Contractor',
            'contractor_name' => 'Contractor Name',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'condition_set' => 'Condition Set',
            'status' => 'Status',
            'record_time' => 'Record Time',
            'work_content' => 'Work Content',
        );
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

        //Apply
        if ($args['apply_id'] != '') {
            $condition.= ( $condition == '') ? ' apply_id=:apply_id' : ' AND apply_id=:apply_id';
            $params['apply_id'] = $args['apply_id'];
        }
        //Approve
        if ($args['approve_id'] != '') {
            $condition.= ( $condition == '') ? ' approve_id=:approve_id' : ' AND approve_id=:approve_id';
            $params['approve_id'] = $args['approve_id'];
        }
        //Program Name
        if ($args['program_name'] != '') {
            $condition.= ( $condition == '') ? ' program_name=:program_name' : ' AND program_name=:program_name';
            $params['program_name'] = $args['program_name'];
        }
        //Apply Date
        if ($args['apply_date'] != '') {
            $condition.= ( $condition == '') ? ' apply_date=:apply_date' : ' AND apply_date=:apply_date';
            $params['apply_date'] = $args['apply_date'];
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Contractor Name
        if ($args['contractor_name'] != '') {
            $condition.= ( $condition == '') ? ' contractor_name=:contractor_name' : ' AND contractor_name=:contractor_name';
            $params['contractor_name'] = $args['contractor_name'];
        }
        //From Time
        if ($args['from_time'] != '') {
            $condition.= ( $condition == '') ? ' from_time=:from_time' : ' AND from_time=:from_time';
            $params['from_time'] = $args['from_time'];
        }
        //To Time
        if ($args['to_time'] != '') {
            $condition.= ( $condition == '') ? ' to_time=:to_time' : ' AND to_time=:to_time';
            $params['to_time'] = $args['to_time'];
        }
        //Start Time
        if ($args['start_time'] != '') {
            $condition.= ( $condition == '') ? ' start_time=:start_time' : ' AND start_time=:start_time';
            $params['start_time'] = $args['start_time'];
        }
        //End Time
        if ($args['end_time'] != '') {
            $condition.= ( $condition == '') ? ' end_time=:end_time' : ' AND end_time=:end_time';
            $params['end_time'] = $args['end_time'];
        }
        //Condition Set
        if ($args['condition_set'] != '') {
            $condition.= ( $condition == '') ? ' condition_set=:condition_set' : ' AND condition_set=:condition_set';
            $params['condition_set'] = $args['condition_set'];
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
        //Work Content
        if ($args['work_content'] != '') {
            $condition.= ( $condition == '') ? ' work_content=:work_content' : ' AND work_content=:work_content';
            $params['work_content'] = $args['work_content'];
        }
        //Program
        if ($args['program_id'] != '') {
            $condition .= ' AND program_id IN ('.$args['program_id'].')';
            //$params['program_id'] = '('.$args['program_id'].')';
        }

        $total_num = ApplyBasicLog::model()->count($condition, $params); //总记录数

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
        $rows = ApplyBasicLog::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertApplyBasicLog($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['apply_id'] == '') {
            $r['msg'] = '编号不能为空。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = ApplyBasicLog::model()->count('apply_id=:apply_id', array('apply_id' => $args['apply_id']));
        if ($exist_data != 0) {
            $r['msg'] = '该记录已存在。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new ApplyBasicLog('create');
            $model->apply_id = $args['apply_id'];
            $model->approve_id = $args['approve_id'];
            $model->program_id = $args['program_id'];
            $model->program_name = $args['program_name'];
            $model->apply_date = $args['apply_date'];
            $model->contractor_id = $args['contractor_id'];
            $model->contractor_name = $args['contractor_name'];
            $model->from_time = $args['from_time'];
            $model->to_time = $args['to_time'];
            $model->start_time = $args['start_time'];
            $model->end_time = $args['end_time'];
            $model->condition_set = $args['condition_set'];
            $model->status = $args['status'];
            $model->record_time = $args['record_time'];
            $model->work_content = $args['work_content'];
            $result = $model->save();

            if ($result) {
                $r['msg'] = '添加成功！';
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = '添加失败！';
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //修改数据
    public static function updateApplyBasicLog($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['apply_id'] == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ApplyBasicLog::model()->findByPk($args['apply_id']);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {

            $model->apply_id = $args['apply_id'];
            $model->approve_id = $args['approve_id'];
            $model->program_id = $args['program_id'];
            $model->program_name = $args['program_name'];
            $model->apply_date = $args['apply_date'];
            $model->contractor_id = $args['contractor_id'];
            $model->contractor_name = $args['contractor_name'];
            $model->from_time = $args['from_time'];
            $model->to_time = $args['to_time'];
            $model->start_time = $args['start_time'];
            $model->end_time = $args['end_time'];
            $model->condition_set = $args['condition_set'];
            $model->status = $args['status'];
            $model->record_time = $args['record_time'];
            $model->work_content = $args['work_content'];
            $result = $model->save();

            if ($result) {
                $r['msg'] = '修改成功！';
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = '修改失败！';
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //删除数据
    public static function deleteApplyBasicLog($id) {

        if ($id == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ApplyBasicLog::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        $sql = 'DELETE FROM ptw_apply_basic_log WHERE apply_id=:apply_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $id, PDO::PARAM_INT);

        $rs = $command->execute();

        if ($rs == 0) {
            $r['msg'] = '您要删除的记录不存在！';
            $r['status'] = -1;
            $r['refresh'] = false;
        } else {
            $r['msg'] = '删除成功！';
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

}
