<?php

/**
 * 例行检查类型
 * @author LiuMinchao
 */
class TaskTemplate extends CActiveRecord {


    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '9'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'task_template';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('common', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('common', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'bg-success', //正常
            self::STATUS_STOP => ' bg-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }

    //类型
    public static function typeList($key = null) {
        $rs = array(
            'A' => 'Site',
            'B' => 'Fitting Out',
            'C' => 'Carcass',
            'D' => 'Site Inspection',
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

        $pro_model =Program::model()->findByPk($args['program_id']);

        $root_proid = $pro_model->root_proid;

        //Program
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $root_proid;
        }
        //Type Name
        if ($args['template_name'] != '') {
            $condition.= ( $condition == '') ? ' template_name LIKE :template_name' : ' AND template_name LIKE :template_name';
            $params['template_name'] = '%' . $args['template_name'] . '%';
        }
        //Status
        $args['status'] = '0';
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Record Time
        if ($args['record_time'] != '') {
            $condition.= ( $condition == '') ? ' record_time=:record_time' : ' AND record_time=:record_time';
            $params['record_time'] = $args['record_time'];
        }

        $condition.= ( $condition == '') ? ' project_id=0' : ' OR project_id=0';

        $condition.= ( $condition == '') ? ' status=0' : ' AND status=0';
        $total_num = TaskTemplate::model()->count($condition, $params); //总记录数

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
        $rows = TaskTemplate::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertTemplate($rs) {

        if ($rs['template_name'] == '') {
            $r['msg'] = 'Template Nmae is not Null';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = new TaskTemplate('create');
        $trans = $model->dbConnection->beginTransaction();
        try {
            $record_time = date('Y-m-d H:i:s', time());
            $status= '0';
            $model->template_name = $rs['template_name'];
            $model->project_id = $rs['program_id'];
//            $model->clt_type = $rs['clt_type'];
            $model->status = $status;
            $model->record_time = $record_time;
            $result = $model->save();//var_dump($result);exit;
            $id = $model->template_id;

            if ($result) {
                $trans->commit();
                $r['template_id'] = $id;
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;

            }
            else {
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
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

    //修改数据
    public static function editTemplate($rs) {

        if ($rs['template_name'] == '') {
            $r['msg'] = 'Template Nmae is not Null';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = TaskTemplate::model()->findByPk($rs['template_id']);
        $trans = $model->dbConnection->beginTransaction();
        try {
            $record_time = date('Y-m-d H:i:s', time());
            $model->template_name = $rs['template_name'];
            $model->project_id = $root_proid;
//            $model->clt_type = $rs['clt_type'];
            $result = $model->save();//var_dump($result);exit;

            if ($result) {
                $trans->commit();
                $r['template_id'] = $rs['template_id'];
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;

            }
            else {
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
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

    //启用
    public static function startTemplate($id) {

        $model = TaskTemplate::model()->findByPk($id);

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
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Start Type'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_start');
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
    //详情
    public static function  detailList($template_id){
        $sql = "SELECT * FROM task_stage WHERE template_id = '".$template_id."' and status = '0'";
        $command = Yii::app()->db->createCommand($sql);
        $detaillist = $command->queryAll();
        return $detaillist;
    }
    //停用
    public static function stopTemplate($id) {

        $model = TaskTemplate::model()->findByPk($id);

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
                $sql = "update task_stage set status = '9' where template_id = '".$id."'";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute();

                $sql = "update task_list set status = '9' where template_id = '".$id."'";
                $command = Yii::app()->db->createCommand($sql);
                $command->execute();

//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Stop Type'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_logout');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_logout');
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

    //按项目查找模版
    public static function templateByProgram($project_id){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT template_id,template_name FROM task_template WHERE status=0 and project_id='".$root_proid."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['template_id']] = $row['template_name'];
            }
        }else{
            $sql = "SELECT template_id,template_name FROM task_template WHERE status=0 and project_id='0'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            foreach ($rows as $key => $row) {
                $rs[$row['template_id']] = $row['template_name'];
            }
        }

        return $rs;
    }

}
