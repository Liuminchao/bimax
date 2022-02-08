<?php

/**
 * 审批表
 * @author LiuXiaoyuan
 */
class ApproveDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ptw_approve_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('approve_id, operator_id, record_time, flow_id', 'required'),
			array('approve_id, operator_id, flow_id', 'length', 'max'=>64),
			array('operator_name', 'length', 'max'=>4000),
			array('sinnature, contractor_name', 'length', 'max'=>1024),
			array('approve_date, position_name', 'length', 'max'=>32),
			array('status', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('seq_id, approve_id, operator_id, operator_name, sinnature, approve_date, position_name, contractor_name, status, record_time, flow_id', 'safe', 'on'=>'search'),
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
			'seq_id' => 'Seq',
			'approve_id' => 'Approve',
			'operator_id' => 'Operator',
			'operator_name' => 'Operator Name',
			'sinnature' => 'Sinnature',
			'approve_date' => 'Approve Date',
			'position_name' => 'Position Name',
			'contractor_name' => 'Contractor Name',
			'status' => 'Status',
			'record_time' => 'Record Time',
			'flow_id' => 'Flow',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApproveDetail the static model class
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

        //Seq
        if ($args['seq_id'] != '') {
            $condition.= ( $condition == '') ? ' seq_id=:seq_id' : ' AND seq_id=:seq_id';
            $params['seq_id'] = $args['seq_id'];
        }
        //Approve
        if ($args['approve_id'] != '') {
            $condition.= ( $condition == '') ? ' approve_id=:approve_id' : ' AND approve_id=:approve_id';
            $params['approve_id'] = $args['approve_id'];
        }
        //Operator
        if ($args['operator_id'] != '') {
            $condition.= ( $condition == '') ? ' operator_id=:operator_id' : ' AND operator_id=:operator_id';
            $params['operator_id'] = $args['operator_id'];
        }
        //Operator Name
        if ($args['operator_name'] != '') {
            $condition.= ( $condition == '') ? ' operator_name=:operator_name' : ' AND operator_name=:operator_name';
            $params['operator_name'] = $args['operator_name'];
        }
        //Sinnature
        if ($args['sinnature'] != '') {
            $condition.= ( $condition == '') ? ' sinnature=:sinnature' : ' AND sinnature=:sinnature';
            $params['sinnature'] = $args['sinnature'];
        }
        //Approve Date
        if ($args['approve_date'] != '') {
            $condition.= ( $condition == '') ? ' approve_date=:approve_date' : ' AND approve_date=:approve_date';
            $params['approve_date'] = $args['approve_date'];
        }
        //Position Name
        if ($args['position_name'] != '') {
            $condition.= ( $condition == '') ? ' position_name=:position_name' : ' AND position_name=:position_name';
            $params['position_name'] = $args['position_name'];
        }
        //Contractor Name
        if ($args['contractor_name'] != '') {
            $condition.= ( $condition == '') ? ' contractor_name=:contractor_name' : ' AND contractor_name=:contractor_name';
            $params['contractor_name'] = $args['contractor_name'];
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
        //Flow
        if ($args['flow_id'] != '') {
            $condition.= ( $condition == '') ? ' flow_id=:flow_id' : ' AND flow_id=:flow_id';
            $params['flow_id'] = $args['flow_id'];
        }
        

        $total_num = ApproveDetail::model()->count($condition, $params); //总记录数
        
        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
        
            $order = 'seq_id';
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
        $rows = ApproveDetail::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    //插入数据
    public static function insertApproveDetail($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['seq_id'] == '') {
            $r['msg'] = '编号不能为空。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = ApproveDetail::model()->count('seq_id=:seq_id', array('seq_id' => $args['seq_id']));
        if ($exist_data != 0) {
            $r['msg'] = '该记录已存在。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {
            $model = new ApproveDetail('create');
            $model->seq_id = $args['seq_id']; 
            $model->approve_id = $args['approve_id']; 
            $model->operator_id = $args['operator_id']; 
            $model->operator_name = $args['operator_name']; 
            $model->sinnature = $args['sinnature']; 
            $model->approve_date = $args['approve_date']; 
            $model->position_name = $args['position_name']; 
            $model->contractor_name = $args['contractor_name']; 
            $model->status = $args['status']; 
            $model->record_time = $args['record_time']; 
            $model->flow_id = $args['flow_id']; 
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
    public static function updateApproveDetail($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['seq_id'] == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ApproveDetail::model()->findByPk($args['seq_id']);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
       

        try {

            $model->seq_id = $args['seq_id']; 
            $model->approve_id = $args['approve_id']; 
            $model->operator_id = $args['operator_id']; 
            $model->operator_name = $args['operator_name']; 
            $model->sinnature = $args['sinnature']; 
            $model->approve_date = $args['approve_date']; 
            $model->position_name = $args['position_name']; 
            $model->contractor_name = $args['contractor_name']; 
            $model->status = $args['status']; 
            $model->record_time = $args['record_time']; 
            $model->flow_id = $args['flow_id']; 
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
    public static function deleteApproveDetail($id) {

        if ($id == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = ApproveDetail::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        
        $sql = 'DELETE FROM ptw_approve_detail WHERE seq_id=:seq_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":seq_id", $id, PDO::PARAM_INT);
        
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
    public static function progressList($apply_id) {
        $sql = "SELECT operator_name,approve_date FROM ptw_approve_detail WHERE apply_id=:apply_id ORDER BY record_time ASC";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['operator_name']] = $row['approve_date'];
            }
        }

        return $rs;
    }
    
}
