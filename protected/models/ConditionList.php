<?php

/**
 * 许可证条件表
 * @author LiuXiaoyuan
 */
class ConditionList extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ptw_condition_list';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('condition_id, condition_name, condition_name_en, record_time', 'required'),
            array('condition_id', 'length', 'max' => 64),
            array('condition_name, condition_name_en', 'length', 'max' => 4000),
            array('status', 'length', 'max' => 2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('condition_id, condition_name, condition_name_en, status, record_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ptwTypeLists' => array(self::MANY_MANY, 'PtwTypeList', 'ptw_type_condition(condition_id, type_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
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
     * @return ConditionList the static model class
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


        $total_num = ConditionList::model()->count($condition, $params); //总记录数

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
        $rows = ConditionList::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertConditionList($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['condition_id'] == '') {
            $r['msg'] = '编号不能为空。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = ConditionList::model()->count('condition_id=:condition_id', array('condition_id' => $args['condition_id']));
        if ($exist_data != 0) {
            $r['msg'] = '该记录已存在。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new ConditionList('create');
            $model->condition_id = $args['condition_id'];
            $model->condition_name = $args['condition_name'];
            $model->condition_name_en = $args['condition_name_en'];
            $model->status = $args['status'];
            $model->record_time = $args['record_time'];
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
    public static function updateConditionList($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['condition_id'] == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ConditionList::model()->findByPk($args['condition_id']);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {

            $model->condition_id = $args['condition_id'];
            $model->condition_name = $args['condition_name'];
            $model->condition_name_en = $args['condition_name_en'];
            $model->status = $args['status'];
            $model->record_time = $args['record_time'];
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
    public static function deleteConditionList($id) {

        if ($id == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ConditionList::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        $sql = 'DELETE FROM ptw_condition_list WHERE condition_id=:condition_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":condition_id", $id, PDO::PARAM_INT);

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

    //安全条例列表
    public static function getConditionList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT condition_id,condition_name as name FROM ptw_condition_list WHERE status=00";
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT condition_id,condition_name_en as name FROM ptw_condition_list WHERE status=00";
        }

        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['condition_id']] = $row['name'];
            }
        }

        return $rs;
    }

}
