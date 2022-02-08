<?php

/**
 * This is the model class for table "ptw_condition_list".
 *
 * The followings are the available columns in table 'ptw_condition_list':
 * @property string $condition_id
 * @property string $condition_name
 * @property string $condition_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwTypeList[] $ptwTypeLists
 * @author LiuXiaoyuan
 */
class RoutineCondition extends CActiveRecord
{
    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'bac_routine_condition';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('condition_id, condition_name, condition_name_en', 'required'),
            array('condition_id', 'length', 'max'=>64),
            array('condition_name, condition_name_en', 'length', 'max'=>4000),
            array('status', 'length', 'max'=>2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('condition_id, condition_name, condition_name_en, status', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'condition_id' => 'Condition',
            'condition_name' => 'Condition Name',
            'condition_name_en' => 'Condition Name En',
            'status' => 'Status',
            'record_time' => 'Record Time',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PtwCondition the static model class
     */
    public static function model($className=__CLASS__)
    {
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
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' type_id=:type_id' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }

        //Condition
        if ($args['condition_id'] != '') {
            $condition.= ( $condition == '') ? ' condition_id=:condition_id' : ' AND condition_id=:condition_id';
            $params['condition_id'] = $args['condition_id'];
        }
        //Condition Name
        if ($args['condition_name'] != '') {
            $condition.= ( $condition == '') ? ' condition_name=:condition_name' : ' AND condition_name=:condition_name';
            $params['condition_name'] = $args['condition_name'];
        }
        //Condition Name En
        if ($args['condition_name_en'] != '') {
            $condition.= ( $condition == '') ? ' condition_name_en=:condition_name_en' : ' AND condition_name_en=:condition_name_en';
            $params['condition_name_en'] = $args['condition_name_en'];
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


        $total_num = RoutineCondition::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'condition_id';
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
        $rows = RoutineCondition::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertRoutineCondition($args,$data) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        $exist_data = RoutineCondition::model()->count('condition_id=:condition_id', array('condition_id' => $args['condition_id']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('common','error_record_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $r = RoutineCheckType::insertRoutineType($args);
        $record_time = date('Y-m-d H:i:s');
        $trans = Yii::app()->db->beginTransaction();

        if($r['status'] == 1){
            try {

                $sub_sql = 'INSERT INTO bac_routine_condition(type_id,condition_name,condition_name_en,status) VALUES(:type_id,:condition_name,:condition_name_en,:status);';

                foreach($data as $k => $v) {

                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":type_id", $args['type_id'], PDO::PARAM_INT);
                    $command->bindParam(":condition_name",$v->condition_name, PDO::PARAM_INT);
                    $command->bindParam(":condition_name_en",$v->condition_name_en, PDO::PARAM_INT);
                    $command->bindParam(":status",$args['status'], PDO::PARAM_STR);
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
        }

        return $r;
    }

    //添加数据
    public static function setRoutineCondition($args) {
//        var_dump($args);
//        exit;
        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['type_id'] == '') {
            $r['msg'] = Yii::t('license_licensepdf','type_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $args['status'] = self::STATUS_NORMAL;
        $r = RoutineCheckType::insertRoutineType($args);

        $sub_sql = 'INSERT INTO bac_routine_condition(type_id,condition_name,condition_name_en,status) VALUES(:type_id,:condition_name,:condition_name_en,:status);';

        $command = Yii::app()->db->createCommand($sub_sql);
        $command->bindParam(":type_id", $args['type_id'], PDO::PARAM_INT);
        $command->bindParam(":condition_name",$args['condition_name'], PDO::PARAM_INT);
        $command->bindParam(":condition_name_en",$args['condition_name_en'], PDO::PARAM_INT);
        $command->bindParam(":status",$args['status'], PDO::PARAM_STR);
        $rs = $command->execute();

        if ($rs) {
            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common','error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }

        return $r;
    }

    //修改数据
    public static function updateRoutineCondition($args) {
//        var_dump($args);
//        exit;
        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['condition_id'] == '') {
            $r['msg'] = Yii::t('license_licensepdf','condition_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = RoutineCondition::model()->findByPk($args['condition_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('license_licensepdf','condition_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {
            $model->condition_name = $args['condition_name'];
            $model->condition_name_en = $args['condition_name_en'];
            $result = $model->save();

            if ($result) {
                $r['msg'] = Yii::t('common','success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
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

    //删除数据
    public static function deleteRoutineCondition($condition_id) {

        $status = self::STATUS_STOP;
        if ($condition_id == '') {
            $r['msg'] = Yii::t('license_licensepdf','condition_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = RoutineCondition::model()->findByPk($condition_id);

        if ($model === null) {
            $r['msg'] = Yii::t('common','error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        $sql = 'UPDATE bac_routine_condition SET status=:status  WHERE condition_id=:condition_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":condition_id", $condition_id, PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);

        $rs = $command->execute();

        if ($rs) {
            $r['msg'] = Yii::t('common','success_delete');
            $r['status'] = -1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common','error_delete');
            $r['status'] = 1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //PTW条件列表
    public static function conditionList() {
        $sql = "select * from bac_routine_condition where status = '00'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['condition_id']]['condition_name'] = $row['condition_name'];
                $rs[$row['condition_id']]['condition_name_en'] = $row['condition_name_en'];
            }
        }
        return $rs;
    }

    //详情
    public static function detailList($type_id){

        $sql = "SELECT * FROM bac_routine_condition WHERE  type_id = '".$type_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $i => $j){
                $rs[$i]['condition_name'] = $j['condition_name'];
                $rs[$i]['condition_name_en'] = $j['condition_name_en'];
                $rs[$i]['tempId'] = time().rand(001,999);
            }
        }
        return $rs;
    }

}
