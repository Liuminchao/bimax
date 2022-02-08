<?php

/**
 * 例行检查类型
 * @author LiuMinchao
 */
class RoutineCheckType extends CActiveRecord {


    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_routine_check_type';
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

        //Type
        if ($args['type_id'] == '1') {
            $condition.= ( $condition == '') ? ' contractor_id=0' : ' AND contractor_id=0';
        }else if($args['type_id'] == '2') {
            $contractor_id = Yii::app()->user->contractor_id;
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $contractor_id;
        }else{
            $contractor_id = Yii::app()->user->contractor_id;
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id OR contractor_id=0' : ' AND contractor_id=:contractor_id OR contractor_id=0';
            $params['contractor_id'] = $contractor_id;
        }
        //Type Name
        if ($args['type_name'] != '') {
            $condition.= ( $condition == '') ? ' type_name LIKE :type_name' : ' AND type_name LIKE :type_name';
            $params['type_name'] = '%' . $args['type_name'] . '%';
        }
        //Type Name En
        if ($args['type_name_en'] != '') {
            $condition.= ( $condition == '') ? ' type_name_en LIKE :type_name_en' : ' AND type_name_en LIKE :type_name_en';
            $params['type_name_en'] = '%' . $args['type_name_en'] . '%';
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


        $total_num = RoutineCheckType::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'type_id';
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
        $rows = RoutineCheckType::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //检查类型列表
    public static function checkType() {
        $sql = "select * from bac_routine_check_type ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT type_id,type_name FROM bac_routine_check_type WHERE status=00 ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT type_id,type_name_en FROM bac_routine_check_type WHERE status=00 ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }else{
            $sql = "SELECT type_id,type_name_en FROM bac_routine_check_type WHERE status=00 ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }

        return $rs;
    }

    //检查类型列表
    public static function checkTypeByReport() {
        $sql = "select * from bac_routine_check_type ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT type_id,type_name FROM bac_routine_check_type ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT type_id,type_name_en FROM bac_routine_check_type  ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }else{
            $sql = "SELECT type_id,type_name_en FROM bac_routine_check_type ";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }

        return $rs;
    }

    //CHECKLIST按企业选择类型
    public static function typeByContractor($program_id) {
        $sql = " SELECT
                    a.type_id, a.type_name, a.type_name_en
                FROM
                    bac_routine_check_type a, bac_program b
                WHERE
                    a.contractor_id = b.main_conid and a.status = '00' and b.root_proid =".$program_id." and b.status='00'
                order by a.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) > 0){
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['type_id']] = $row['type_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['type_id']] = $row['type_name_en'];
                }
            }
        }else{
            $sql = "select * from bac_routine_check_type WHERE status = '00' and contractor_id ='0' order by type_id ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    if (Yii::app()->language == 'zh_CN') {
                        $rs[$row['type_id']] = $row['type_name'];
                    }else if (Yii::app()->language == 'en_US') {
                        $rs[$row['type_id']] = $row['type_name_en'];
                    }
                }
            }
        }
        return $rs;
    }
    //检查单类别
    public static function checkModule() {

        $sql = "SELECT type_id,module FROM bac_routine_check_type WHERE status=00 ";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['type_id']] = $row['module'];
            }
        }
        return $rs;
    }
    //检查种类
    public static function checkKind() {
        if (Yii::app()->language == 'zh_CN') {
            $r['1'] = '设备';
            $r['2'] = '环境';
        } else if (Yii::app()->language == 'en_US') {
            $r['1'] = 'Device';
            $r['2'] = 'Environment';
        }else{
            $r['1'] = 'Device';
            $r['2'] = 'Environment';
        }
        return $r;
    }

    //插入数据
    public static function insertRoutineType($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['type_name'] == '') {
            $r['msg'] = Yii::t('license_type','error_type_name_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        if ($args['type_name_en'] == '') {
            $r['msg'] = Yii::t('license_type','error_type_name_en_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = RoutineCheckType::model()->count('type_id=:type_id', array('type_id' => $args['type_id']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('common','error_record_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new RoutineCheckType('create');
            $model->type_id = $args['type_id'];
            $model->contractor_id = $args['contractor_id'];
            $model->device_type = $args['device_type'];
            $model->module = $args['module'];
            $model->type_name = $args['type_name'];
            $model->type_name_en = $args['type_name_en'];
            $model->status = self::STATUS_NORMAL;
//            $model->record_time = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result) {
                $r['msg'] = Yii::t('common','success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common','error_insert');
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
    public static function updateRoutineType($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['type_id'] == '') {
            $r['msg'] = Yii::t('license_type','error_type_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = RoutineCheckType::model()->findByPk($args['type_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {

            $model->type_name = $args['type_name'];
            $model->type_name_en = $args['type_name_en'];
            $result = $model->save();

            if ($result) {
                $r['msg'] = Yii::t('common','success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
//                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Edit Type'), self::updateLog($model));
            } else {
                $r['msg'] = Yii::t('common','error_update');
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

    //启用
    public static function startType($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('license_type', 'error_type_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = RoutineCheckType::model()->findByPk($id);

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
                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Start Type'), self::updateLog($model));
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

    //停用
    public static function stopType($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('license_type', 'error_type_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = RoutineCheckType::model()->findByPk($id);

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

}
