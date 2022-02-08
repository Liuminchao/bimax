<?php

/**
 * This is the model class for table "ptw_workflow_detail".
 *
 * The followings are the available columns in table 'ptw_workflow_detail':
 * @property integer $id
 * @property string $flow_id
 * @property string $type
 * @property string $object_id
 * @property string $object_name
 * @property string $step
 * @author LiuXiaoyuan
 */
class WorkflowDetail extends CActiveRecord {

    const TYPE_ROLE = 0;
    const TYPE_PEOPLE = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ptw_workflow_detail';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'flow_id' => 'Flow',
            'type' => Yii::t('sys_workflow', 'step_type'),
            'object_id' => 'Object',
            'object_name' => 'Object Name',
            'step' => 'Step',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return WorkflowDetail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //节点类型
    public static function typeText($key = null) {
        $rs = array(
            self::TYPE_ROLE => Yii::t('sys_workflow', 'role'),
            self::TYPE_PEOPLE => Yii::t('sys_workflow', 'people'),
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

        //ID
        if ($args['id'] != '') {
            $condition.= ( $condition == '') ? ' id=:id' : ' AND id=:id';
            $params['id'] = $args['id'];
        }
        //Flow
        if ($args['flow_id'] != '') {
            $condition.= ( $condition == '') ? ' flow_id=:flow_id' : ' AND flow_id=:flow_id';
            $params['flow_id'] = $args['flow_id'];
        }
        //Type
        if ($args['type'] != '') {
            $condition.= ( $condition == '') ? ' type=:type' : ' AND type=:type';
            $params['type'] = $args['type'];
        }
        //Object
        if ($args['object_id'] != '') {
            $condition.= ( $condition == '') ? ' object_id=:object_id' : ' AND object_id=:object_id';
            $params['object_id'] = $args['object_id'];
        }
        //Object Name
        if ($args['object_name'] != '') {
            $condition.= ( $condition == '') ? ' object_name=:object_name' : ' AND object_name=:object_name';
            $params['object_name'] = $args['object_name'];
        }
        //Step
        if ($args['step'] != '') {
            $condition.= ( $condition == '') ? ' step=:step' : ' AND step=:step';
            $params['step'] = $args['step'];
        }


        $total_num = WorkflowDetail::model()->count($condition, $params); //总记录数

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
        $rows = WorkflowDetail::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function batchInsertWorkflowDetail($args) {

        if ($args['flow_id'] == '') {
            $r['msg'] = Yii::t('sys_workflow', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if (empty($args['object_id']) || empty($args['object_name']) || empty($args['type'])) {
            $r['msg'] = Yii::t('sys_workflow', 'error_step_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = WorkflowDetail::model()->count('flow_id=:flow_id', array('flow_id' => $args['flow_id']));
        if ($exist_data != 0) {
            $sql = 'DELETE FROM ptw_workflow_detail WHERE flow_id=:flow_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":flow_id", $args['flow_id'], PDO::PARAM_INT);
            $rs = $command->execute();
        }

        $trans = Yii::app()->db->beginTransaction();

        try {

            $sub_sql = 'INSERT INTO ptw_workflow_detail(flow_id,type,object_id,object_name,step) VALUES(:flow_id,:type,:object_id,:object_name,:step);';
            
            $cnt = count($args['object_id']);
            $step = 1;
            for ($i =0; $i < $cnt; $i++) {
                
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":flow_id", $args['flow_id'], PDO::PARAM_INT);
                $command->bindParam(":type",$args['type'][$i], PDO::PARAM_INT);
                $command->bindParam(":object_id",$args['object_id'][$i], PDO::PARAM_INT);
                $command->bindParam(":object_name",$args['object_name'][$i], PDO::PARAM_STR);
                $command->bindParam(":step",$step, PDO::PARAM_INT);
                $rs = $command->execute();
                
                $step += 1;
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

    /**
     * 根据工作流id返回审批步骤
     * @param type $flow_id
     * @return type
     */
    public static function stepList($flow_id) {
        $sql = "SELECT id,object_id,object_name,type,step FROM ptw_workflow_detail WHERE flow_id=:flow_id ORDER BY step ASC";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":flow_id", $flow_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['id']]['obj_name'] = $row['object_name'];
                $rs[$row['id']]['obj_id'] = $row['object_id'];
                $rs[$row['id']]['type'] = $row['type'];
                $rs[$row['id']]['step'] = $row['step'];
            }
        }
        return $rs;
    }

}
