<?php

class Worker extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_worker';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'worker_id' => Yii::t('comp_worker', 'Worker_id'),
            'worker_name' => Yii::t('comp_worker', 'Worker_name'),
            'wp_no' => Yii::t('comp_worker', 'Wp_no'),
            'worker_phone' => Yii::t('comp_worker', 'Worker_phone'),
            'title_id' => Yii::t('comp_worker', 'Title_id'),
            'primary_email' => Yii::t('comp_worker', 'Primary_email'),
            'team_id' => Yii::t('comp_worker', 'Team_id'),
            'status' => Yii::t('comp_worker', 'Status'));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Operator the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('comp_worker', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('comp_worker', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_DISABLE => ' label-danger', //已注销
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 添加日志详细
     * @param type $model
     * @return array
     */
    public static function insertLog($model) {
        $log = array(
            $model->getAttributeLabel('worker_name') => $model->worker_name,
            $model->getAttributeLabel('worker_phone') => $model->worker_phone,
            $model->getAttributeLabel('title_id') => $model->title_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
        );
        return $log;
    }

    /**
     * 修改日志详细
     * @param type $model
     * @return array
     */
    public static function updateLog($model) {
        $log = array(
            $model->getAttributeLabel('worker_name') => $model->worker_name,
            $model->getAttributeLabel('worker_phone') => $model->worker_phone,
            $model->getAttributeLabel('title_id') => $model->title_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
        );
        return $log;
    }

    public static function logoutLog($model) {
        $log = array(
            $model->getAttributeLabel('worker_name') => $model->worker_name,
            $model->getAttributeLabel('worker_phone') => $model->worker_phone,
            $model->getAttributeLabel('title_id') => $model->title_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
        );
        return $log;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '1=1 ';
        $params = array();
   
        //Worker_name
        if ($args['worker_name'] != '') {
            $condition.= ( $condition == '') ? ' worker_name LIKE :worker_name' : ' AND worker_name LIKE :worker_name';
            $params['worker_name'] = '%' . $args['worker_name'] . '%';
        }
        //worker_phone
        if ($args['worker_phone'] != '') {
            $condition.= ( $condition == '') ? ' worker_phone=:worker_phone' : ' AND worker_phone=:worker_phone';
            $params['worker_phone'] = $args['worker_phone'];
        }

        //Worker_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        $total_num = Worker::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'worker_id';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = Worker::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertWorker($args) {
        //form id　注意为model的数据库字段
        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        if ($args['worker_phone'] == '') {
            $r['msg'] = Yii::t('comp_worker', 'Error Worker_name is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $exist_data = Worker::model()->count('worker_phone=:worker_phone', array('worker_phone' => $args['worker_phone']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('comp_worker', 'Error Worker_phone is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            $model = new Worker('create');
            $model->worker_name = $args['worker_name'];
            $model->worker_phone = $args['worker_phone'];

            $model->primary_email = $args['primary_email'];
            $model->team_id = $args['team_id'];
            $model->wp_no=$args['wp_no'];

            $model->contractor_id = Yii::app()->User->getState('contractor_id');

            //var_dump(Yii::app()->User->getState('contractor_id'));
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_worker', 'Add Worker'), self::insertLog($model));
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

    //修改数据
    public static function updateWorker($args) {

//         foreach ($args as $key => $value) {
//             $args[$key] = trim($value);
//         }
        if ($args['worker_id'] == '') {
            $r['msg'] = Yii::t('com_Worker', 'Error worker_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Worker::model()->findByPk($args['worker_id']);
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            if (count($args['role_id']) != 0) {
                $args['role_id'] = implode(",", $args['role_id']);
            }
            $model->worker_name = $args['worker_name'];
            $model->primary_email = $args['primary_email'];
            $model->team_id = $args['team_id'];
            $result = $model->save();

            //记录日志
            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_worker', 'Edit Worker'), self::updateLog($model));

                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
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

    public static function logoutWorker($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('comp_worker', 'Error Worker_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Worker::model()->findByPk($id);

        if ($model == null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            // $model->status = self::STATUS_DISABLE;
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                //$result = $model->save();
                $sql = "delete from bac_worker where worker_id=:worker_id";
                $command = $connection->createCommand($sql);
                $command->bindParam(":worker_id", $id, PDO::PARAM_INT);
                $command->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
            OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_worker', 'Logout Worker'), self::logoutLog($model));

            $r['msg'] = Yii::t('common', 'success_logout');
            $r['status'] = 1;
            $r['refresh'] = true;
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = '123'; // $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

}
