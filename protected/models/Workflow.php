<?php

/**
 * 工作流
 * @author LiuXiaoyuan
 */
class Workflow extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_STOP = 1; //停用
    const PTW_APP = 'PTW'; //许可证
    const TBM_APP = 'TBM'; //工具箱会议

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'ptw_workflow';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('sys_workflow', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_workflow', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => 'label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }

    //应用对象
    public static function UseObjectText() {

        $sql = "SELECT contractor_id,contractor_name FROM bac_contractor ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $rs[''] = Yii::t('sys_workflow', 'allpeople');
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'flow_id' => Yii::t('sys_workflow', 'Flow Id'),
            'flow_name' => Yii::t('sys_workflow', 'Flow Name'),
            'contractor_id' => Yii::t('sys_workflow', 'Use Object'),
            'status' => Yii::t('sys_workflow', 'Status'),
            'operator_id' => Yii::t('sys_workflow', 'Operator'),
            'record_time' => Yii::t('sys_workflow', 'Record Time'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Workflow the static model class
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

        //Flow
        if ($args['flow_id'] != '') {
            $condition.= ( $condition == '') ? ' flow_id=:flow_id' : ' AND flow_id=:flow_id';
            $params['flow_id'] = $args['flow_id'];
        }
        //工作流类型
        if ($args['flow_type'] != '') {
            $condition.=( $condition == '') ? ' app_id LIKE :flow_type' : ' AND app_id LIKE :flow_type';
            $params['flow_type'] = "%".$args['flow_type']."%";
        }
        //工作流名称
        if ($args['flow_name'] != '') {
            $condition.= ( $condition == '') ? ' flow_name LIKE :flow_name' : ' AND flow_name LIKE :flow_name';
            $params['flow_name'] = '%' . $args['flow_name'] . '%';
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Operator
        if ($args['operator_id'] != '') {
            $condition.= ( $condition == '') ? ' operator_id=:operator_id' : ' AND operator_id=:operator_id';
            $params['operator_id'] = $args['operator_id'];
        }
        
        $total_num = Workflow::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'flow_id';
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
        $rows = Workflow::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 添加日志
     * @param type $model
     */
    public static function insertLog($model) {
        $useObject = self::UseObjectText();
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('flow_id') => $model->flow_id,
            $model->getAttributeLabel('flow_name') => $model->flow_name,
            $model->getAttributeLabel('contractor_id') => $useObject[$model->contractor_id],
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('operator_id') => Yii::app()->user->getState('name'),
                //$model->getAttributeLabel('record_time') => $model->record_time,
        );
    }

    //插入数据
    public static function insertWorkflow($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['flow_name'] == '') {
            $r['msg'] = Yii::t('sys_workflow', 'error_name_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = Workflow::model()->count('app_id=:app_id', array('app_id' => $args['flow_type']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('sys_workflow', 'error_name_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        $exist_data = Workflow::model()->count('flow_name=:flow_name', array('flow_name' => $args['flow_name']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('sys_workflow', 'error_name_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new Workflow('create');
            $model->flow_name = $args['flow_name'];
            $model->app_id = $args['flow_type'];
            $model->contractor_id = $args['contractor_id'];
            $model->status = self::STATUS_STOP;
            $model->operator_id = Yii::app()->user->id;
            $model->record_time = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_FLOW, Yii::t('sys_workflow', 'Add Flow'), self::insertLog($model));

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
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

    //修改日志
    public static function updateLog($model) {
        $useObject = self::UseObjectText();
        return array(
            $model->getAttributeLabel('flow_id') => $model->flow_id,
            $model->getAttributeLabel('flow_name') => $model->flow_name,
            $model->getAttributeLabel('contractor_id') => $useObject[$model->contractor_id],
        );
    }

    //修改数据
    public static function updateWorkflow($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['flow_id'] == '') {
            $r['msg'] = Yii::t('sys_workflow', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Workflow::model()->findByPk($args['flow_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {

            $model->flow_name = $args['flow_name'];
            $model->contractor_id = $args['contractor_id'];
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_FLOW, Yii::t('sys_workflow', 'Edit Flow'), self::updateLog($model));

                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'success_error');
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

    //启用日志
    public static function startLog($model) {
        $useObject = self::UseObjectText();
        return array(
            $model->getAttributeLabel('flow_id') => $model->flow_id,
            $model->getAttributeLabel('flow_name') => $model->flow_name,
            $model->getAttributeLabel('contractor_id') => $useObject[$model->contractor_id],
        );
    }

    //启用工作流
    public static function startWorkflow($id) {


        if ($id == '') {
            $r['msg'] = Yii::t('sys_workflow', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Workflow::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = self::STATUS_NORMAL;
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_FLOW, Yii::t('sys_workflow', 'Start Flow'), self::startLog($model));

                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'success_start');
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

    //停用日志
    public static function stopLog($model) {
        $useObject = self::UseObjectText();
        return array(
            $model->getAttributeLabel('flow_id') => $model->flow_id,
            $model->getAttributeLabel('flow_name') => $model->flow_name,
            $model->getAttributeLabel('contractor_id') => $useObject[$model->contractor_id],
        );
    }

    //停用工作流
    public static function stopWorkflow($id) {


        if ($id == '') {
            $r['msg'] = Yii::t('sys_workflow', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Workflow::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = self::STATUS_STOP;
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_FLOW, Yii::t('sys_workflow', 'Stop Flow'), self::stopLog($model));

                $r['msg'] = Yii::t('common', 'success_stop');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'success_stop');
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
    /**
     * 返回承包商工作流
     * @param type $status
     * @return type
     */
    public static function contractorflowList($status,$contractor_id,$app_id) {
        $sql = "SELECT flow_id,flow_name FROM ptw_workflow WHERE contractor_id=:contractor_id AND status=:status AND app_id=:app_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":status", $status, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":app_id", $app_id, PDO::PARAM_INT);
        $rs = $command->queryAll();
//        if (count($rows) > 0) {
//            foreach ($rows as $key => $row) {
//                $rs[$row['flow_id']]['flow_id'] = $row['flow_id'];
//                $rs[$row['flow_id']]['flow_name'] = $row['flow_name'];
//            }
//        }
        return $rs;
    }
    /**
     * 返回指定状态的工作流
     * @param type $status
     * @return type
     */
    public static function flowList($status) {
        $sql = "SELECT flow_id,flow_name,operator_id,record_time,status,app_id FROM ptw_workflow WHERE contractor_id='' AND status=:status";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":status", $status, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['flow_id']]['flow_id'] = $row['flow_id'];
                $rs[$row['flow_id']]['flow_name'] = $row['flow_name'];
                $rs[$row['flow_id']]['operator_id'] = $row['operator_id'];
                $rs[$row['flow_id']]['record_time'] = $row['record_time'];
                $rs[$row['flow_id']]['status'] = $row['status'];
                $rs[$row['flow_id']]['app_id'] = $row['app_id'];
            }
        }

        return $rs;
    }

    /**
     * 获得工作流的步骤
     * @param type $flow_id
     * @return type
     */
    public static function flowDetailList($flow_id) {
        $sql = "SELECT type,object_id,object_name,step FROM ptw_workflow_detail WHERE flow_id=:flow_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":flow_id", $flow_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['step']]['type'] = $row['type'];
                $rs[$row['step']]['object_id'] = $row['object_id'];
                $rs[$row['step']]['object_name'] = $row['object_name'];
                $rs[$row['step']]['step'] = $row['step'];
            }
        }

        return $rs;
    }

    /**
     * 复制工作流
     * @param type $args
     * @return boolean
     */
    public static function copyWorkflow($contractor_id, $operator_id) {

        if ($contractor_id == '') {
            $r['msg'] = Yii::t('com_contractor', 'Error contractor_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        if ($operator_id == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $flow_list = self::flowList(self::STATUS_NORMAL);
//        var_dump($flow_list);
//        exit;
        if (!empty($flow_list)) {
            foreach ($flow_list as $flow_id => $row) {

                $record_time = date('Y-m-d H:i:s');
                $sql = 'INSERT INTO ptw_workflow(flow_name,contractor_id,status,operator_id,record_time,app_id) VALUES(:flow_name,:contractor_id,:status,:operator_id,:record_time,:app_id)';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":flow_name", $row['flow_name'], PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                $command->bindParam(":status", $row['status'], PDO::PARAM_STR);
                $command->bindParam(":operator_id", $operator_id, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $command->bindParam(":app_id", $row['app_id'], PDO::PARAM_STR);
                $rs1 = $command->execute();

                $new_flow_id = Yii::app()->db->lastInsertID;
                //步骤
                $detail_list = self::flowDetailList($flow_id);
                if (!empty($detail_list)) {
                    foreach ($detail_list as $step => $row) {
                        $sql = 'INSERT INTO ptw_workflow_detail(flow_id,type,object_id,object_name,step) VALUES(:flow_id,:type,:object_id,:object_name,:step)';
//                        var_dump($sql);
//                        exit;
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":flow_id", $new_flow_id, PDO::PARAM_STR);
                        $command->bindParam(":type", $row['type'], PDO::PARAM_STR);
                        $command->bindParam(":object_id", $row['object_id'], PDO::PARAM_STR);
                        $command->bindParam(":object_name", $row['object_name'], PDO::PARAM_STR);
                        $command->bindParam(":step", $row['step'], PDO::PARAM_STR);
                        $rs2 = $command->execute();
                    }
                }
            }
        }
    }
    /**
     * 
     * 建立默认申请审核工作流
     */
    public static function defaultWorkflow($contractor_id, $operator_id) {

        if ($contractor_id == '') {
            $r['msg'] = Yii::t('com_contractor', 'Error contractor_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        if ($operator_id == '') {
            $r['msg'] = Yii::t('sys_operator', 'Error operator_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $flow_list = self::flowList(self::STATUS_NORMAL);
        if (!empty($flow_list)) {
            foreach ($flow_list as $flowid => $row) {
                if($row['app_id']=='PTW_CLOSE'){
                    $flow_id = $flowid; 
                    $record_time = date('Y-m-d H:i:s');
                    $sql = 'INSERT INTO ptw_workflow(flow_name,contractor_id,status,operator_id,record_time,app_id) VALUES(:flow_name,:contractor_id,:status,:operator_id,:record_time,:app_id)';
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":flow_name", $row['flow_name'], PDO::PARAM_STR);
                    $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                    $command->bindParam(":status", $row['status'], PDO::PARAM_STR);
                    $command->bindParam(":operator_id", $operator_id, PDO::PARAM_STR);
                    $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                    $command->bindParam(":app_id", $row['app_id'], PDO::PARAM_STR);
                    $rs1 = $command->execute();
                }
                $new_flow_id = Yii::app()->db->lastInsertID;
                //步骤
                $detail_list = self::flowDetailList($flow_id);
                if (!empty($detail_list)) {
                    foreach ($detail_list as $step => $row) {
                        $sql = 'INSERT INTO ptw_workflow_detail(flow_id,type,object_id,object_name,step) VALUES(:flow_id,:type,:object_id,:object_name,:step)';
//                        var_dump($sql);
//                        exit;
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":flow_id", $new_flow_id, PDO::PARAM_STR);
                        $command->bindParam(":type", $row['type'], PDO::PARAM_STR);
                        $command->bindParam(":object_id", $row['object_id'], PDO::PARAM_STR);
                        $command->bindParam(":object_name", $row['object_name'], PDO::PARAM_STR);
                        $command->bindParam(":step", $row['step'], PDO::PARAM_STR);
                        $rs2 = $command->execute();
                    }
                }
            }
        }
    }
}
