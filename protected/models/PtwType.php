<?php

/**
 * This is the model class for table "ptw_type_list".
 *
 * The followings are the available columns in table 'ptw_type_list':
 * @property string $type_id
 * @property string $type_name
 * @property string $type_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwConditionList[] $ptwConditionLists
 * @author LiuXiaoyuan
 */
class PtwType extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'ptw_type_list';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type_id, type_name, type_name_en, record_time', 'required'),
            array('type_id', 'length', 'max' => 64),
            array('type_name, type_name_en', 'length', 'max' => 1024),
            array('status', 'length', 'max' => 2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('type_id, type_name, type_name_en, status, record_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ptwConditionLists' => array(self::MANY_MANY, 'PtwConditionList', 'ptw_type_condition(type_id, condition_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'type_id' => Yii::t('license_type', 'type_id'),
            'type_name' => Yii::t('license_type', 'type_name'),
            'type_name_en' => Yii::t('license_type', 'type_name_en'),
            'status' => Yii::t('license_type', 'status'),
            'record_time' => Yii::t('license_type', 'record_time'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PtwType the static model class
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
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
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

        //Type
        $operator_type   =  Yii::app()->user->getState('operator_type');
        if($operator_type == '00'){
            if ($args['type_id'] == '1') {
                $condition.= ( $condition == '') ? ' contractor_id=0' : ' AND contractor_id=0';
            }
        }else{
            $contractor_id = Yii::app()->user->contractor_id;
            if ($args['type_id'] == '1') {
                $condition.= ( $condition == '') ? ' contractor_id=0' : ' AND contractor_id=0';
            }else if($args['type_id'] == '2') {
                $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
                $params['contractor_id'] = $contractor_id;
            }else{
                $condition.= ( $condition == '') ? ' contractor_id=:contractor_id OR contractor_id=0' : ' AND contractor_id=:contractor_id OR contractor_id=0';
                $params['contractor_id'] = $contractor_id;
            }
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


        $total_num = PtwType::model()->count($condition, $params); //总记录数

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
        $rows = PtwType::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertPtwType($args) {

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

        $exist_data = PtwType::model()->count('type_id=:type_id', array('type_id' => $args['type_id']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('common','error_record_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new PtwType('create');
            $model->type_id = $args['type_id'];
            $model->contractor_id = $args['contractor_id'];
            $model->type_name = $args['type_name'];
            $model->type_name_en = $args['type_name_en'];
            $model->status = self::STATUS_NORMAL;
            $model->record_time = date('Y-m-d H:i:s');
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
    public static function updatePtwType($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['type_id'] == '') {
            $r['msg'] = Yii::t('license_type','error_type_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = PtwType::model()->findByPk($args['type_id']);

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
                OperatorLog::savelog(OperatorLog::MODULE_ID_LICENSE, Yii::t('licensse_type', 'Edit Type'), self::updateLog($model));
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

        $model = PtwType::model()->findByPk($id);

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

        $model = PtwType::model()->findByPk($id);

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

    //PTW按企业选择类型
    public static function typeByContractor($program_id) {
        $sql = " SELECT
                    a.type_id, a.type_name, a.type_name_en
                FROM
                    ptw_type_list a, bac_program b
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
            $sql = "select * from ptw_type_list WHERE status = '00' and contractor_id ='0' order by type_id ";
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

    //PTW类型列表
    public static function levelText() {
        $sql = "select * from ptw_type_list ";
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
        return $rs;
    }
}
