<?php

/**
 * 审批步骤表
 * @author LiuXiaoyuan
 */
class ApproveProgress extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ptw_approve_progress';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('approve_id, start_flow, record_time', 'required'),
			array('approve_id', 'length', 'max'=>64),
			array('start_flow, next_flow, add_user, last_update_user', 'length', 'max'=>128),
			array('current_status, status', 'length', 'max'=>2),
			array('add_time, last_update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('record_id, approve_id, start_flow, next_flow, current_status, add_user, add_time, last_update_user, last_update_time, status, record_time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'record_id' => 'Record',
			'approve_id' => 'Approve',
			'start_flow' => 'Start Flow',
			'next_flow' => 'Next Flow',
			'current_status' => 'Current Status',
			'add_user' => 'Add User',
			'add_time' => 'Add Time',
			'last_update_user' => 'Last Update User',
			'last_update_time' => 'Last Update Time',
			'status' => 'Status',
			'record_time' => 'Record Time',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApproveProgress the static model class
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

        //Record
        if ($args['record_id'] != '') {
            $condition.= ( $condition == '') ? ' record_id=:record_id' : ' AND record_id=:record_id';
            $params['record_id'] = $args['record_id'];
        }
        //Approve
        if ($args['approve_id'] != '') {
            $condition.= ( $condition == '') ? ' approve_id=:approve_id' : ' AND approve_id=:approve_id';
            $params['approve_id'] = $args['approve_id'];
        }
        //Start Flow
        if ($args['start_flow'] != '') {
            $condition.= ( $condition == '') ? ' start_flow=:start_flow' : ' AND start_flow=:start_flow';
            $params['start_flow'] = $args['start_flow'];
        }
        //Next Flow
        if ($args['next_flow'] != '') {
            $condition.= ( $condition == '') ? ' next_flow=:next_flow' : ' AND next_flow=:next_flow';
            $params['next_flow'] = $args['next_flow'];
        }
        //Current Status
        if ($args['current_status'] != '') {
            $condition.= ( $condition == '') ? ' current_status=:current_status' : ' AND current_status=:current_status';
            $params['current_status'] = $args['current_status'];
        }
        //Add User
        if ($args['add_user'] != '') {
            $condition.= ( $condition == '') ? ' add_user=:add_user' : ' AND add_user=:add_user';
            $params['add_user'] = $args['add_user'];
        }
        //Add Time
        if ($args['add_time'] != '') {
            $condition.= ( $condition == '') ? ' add_time=:add_time' : ' AND add_time=:add_time';
            $params['add_time'] = $args['add_time'];
        }
        //Last Update User
        if ($args['last_update_user'] != '') {
            $condition.= ( $condition == '') ? ' last_update_user=:last_update_user' : ' AND last_update_user=:last_update_user';
            $params['last_update_user'] = $args['last_update_user'];
        }
        //Last Update Time
        if ($args['last_update_time'] != '') {
            $condition.= ( $condition == '') ? ' last_update_time=:last_update_time' : ' AND last_update_time=:last_update_time';
            $params['last_update_time'] = $args['last_update_time'];
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
        

        $total_num = ApproveProgress::model()->count($condition, $params); //总记录数
        
        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
        
            $order = 'record_id';
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
        $rows = ApproveProgress::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    //插入数据
    public static function insertApproveProgress($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['record_id'] == '') {
            $r['msg'] = '编号不能为空。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = ApproveProgress::model()->count('record_id=:record_id', array('record_id' => $args['record_id']));
        if ($exist_data != 0) {
            $r['msg'] = '该记录已存在。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {
            $model = new ApproveProgress('create');
            $model->record_id = $args['record_id']; 
            $model->approve_id = $args['approve_id']; 
            $model->start_flow = $args['start_flow']; 
            $model->next_flow = $args['next_flow']; 
            $model->current_status = $args['current_status']; 
            $model->add_user = $args['add_user']; 
            $model->add_time = $args['add_time']; 
            $model->last_update_user = $args['last_update_user']; 
            $model->last_update_time = $args['last_update_time']; 
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
    public static function updateApproveProgress($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['record_id'] == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ApproveProgress::model()->findByPk($args['record_id']);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
       

        try {

            $model->record_id = $args['record_id']; 
            $model->approve_id = $args['approve_id']; 
            $model->start_flow = $args['start_flow']; 
            $model->next_flow = $args['next_flow']; 
            $model->current_status = $args['current_status']; 
            $model->add_user = $args['add_user']; 
            $model->add_time = $args['add_time']; 
            $model->last_update_user = $args['last_update_user']; 
            $model->last_update_time = $args['last_update_time']; 
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
    public static function deleteApproveProgress($id) {

        if ($id == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = ApproveProgress::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        
        $sql = 'DELETE FROM ptw_approve_progress WHERE record_id=:record_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":record_id", $id, PDO::PARAM_INT);
        
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
    
    /**
     * 审批步骤
     * @param type $approve_id
     * @return type
     */
    public static function progressList($approve_id) {
        $sql = "SELECT add_user,add_time FROM ptw_approve_progress WHERE approve_id=:approve_id ORDER BY record_id ASC";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":approve_id", $approve_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['add_user']] = $row['add_time'];
            }
        }

        return $rs;
    }
}
