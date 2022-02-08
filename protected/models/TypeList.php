<?php

/**
 * 许可证类型表
 * @author LiuXiaoyuan
 */
class TypeList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ptw_type_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_id, type_name, type_name_en, record_time', 'required'),
			array('type_id', 'length', 'max'=>64),
			array('type_name, type_name_en', 'length', 'max'=>1024),
			array('status', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('type_id, type_name, type_name_en, status, record_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ptwConditionLists' => array(self::MANY_MANY, 'PtwConditionList', 'ptw_type_condition(type_id, condition_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'type_id' => 'Type',
			'type_name' => 'Type Name',
			'type_name_en' => 'Type Name En',
			'status' => 'Status',
			'record_time' => 'Record Time',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TypeList the static model class
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
        //Type Name
        if ($args['type_name'] != '') {
            $condition.= ( $condition == '') ? ' type_name=:type_name' : ' AND type_name=:type_name';
            $params['type_name'] = $args['type_name'];
        }
        //Type Name En
        if ($args['type_name_en'] != '') {
            $condition.= ( $condition == '') ? ' type_name_en=:type_name_en' : ' AND type_name_en=:type_name_en';
            $params['type_name_en'] = $args['type_name_en'];
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
        

        $total_num = TypeList::model()->count($condition, $params); //总记录数
        
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
        $rows = TypeList::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    //插入数据
    public static function insertTypeList($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['type_id'] == '') {
            $r['msg'] = '编号不能为空。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = TypeList::model()->count('type_id=:type_id', array('type_id' => $args['type_id']));
        if ($exist_data != 0) {
            $r['msg'] = '该记录已存在。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {
            $model = new TypeList('create');
            $model->type_id = $args['type_id']; 
            $model->type_name = $args['type_name']; 
            $model->type_name_en = $args['type_name_en']; 
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
    public static function updateTypeList($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['type_id'] == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = TypeList::model()->findByPk($args['type_id']);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
       

        try {

            $model->type_id = $args['type_id']; 
            $model->type_name = $args['type_name']; 
            $model->type_name_en = $args['type_name_en']; 
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
    public static function deleteTypeList($id) {

        if ($id == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = TypeList::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        
        $sql = 'DELETE FROM ptw_type_list WHERE type_id=:type_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":type_id", $id, PDO::PARAM_INT);
        
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
