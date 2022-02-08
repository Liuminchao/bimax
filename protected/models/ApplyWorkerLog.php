<?php

/**
 * 许可证申请工人库
 * @author LiuXiaoyuan
 */
class ApplyWorkerLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ptw_apply_worker_log';
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'apply_id' => 'Apply',
			'contractor_id' => 'Contractor',
			'program_id' => 'Program',
			'wp_no' => 'Wp No',
			'worker_name' => 'Worker Name',
			'status' => 'Status',
			'record_time' => 'Record Time',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApplyWorkerLog the static model class
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

        //Apply
        if ($args['apply_id'] != '') {
            $condition.= ( $condition == '') ? ' apply_id=:apply_id' : ' AND apply_id=:apply_id';
            $params['apply_id'] = $args['apply_id'];
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Program
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:program_id' : ' AND program_id=:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Wp No
        if ($args['wp_no'] != '') {
            $condition.= ( $condition == '') ? ' wp_no=:wp_no' : ' AND wp_no=:wp_no';
            $params['wp_no'] = $args['wp_no'];
        }
        //Worker Name
        if ($args['worker_name'] != '') {
            $condition.= ( $condition == '') ? ' worker_name=:worker_name' : ' AND worker_name=:worker_name';
            $params['worker_name'] = $args['worker_name'];
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
        

        $total_num = ApplyWorkerLog::model()->count($condition, $params); //总记录数
        
        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
        
            $order = 'apply_id';
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
        $rows = ApplyWorkerLog::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    //插入数据
    public static function insertApplyWorkerLog($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['apply_id'] == '') {
            $r['msg'] = '编号不能为空。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = ApplyWorkerLog::model()->count('apply_id=:apply_id', array('apply_id' => $args['apply_id']));
        if ($exist_data != 0) {
            $r['msg'] = '该记录已存在。';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {
            $model = new ApplyWorkerLog('create');
            $model->apply_id = $args['apply_id']; 
            $model->contractor_id = $args['contractor_id']; 
            $model->program_id = $args['program_id']; 
            $model->wp_no = $args['wp_no']; 
            $model->worker_name = $args['worker_name']; 
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
    public static function updateApplyWorkerLog($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        
        if ($args['apply_id'] == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ApplyWorkerLog::model()->findByPk($args['apply_id']);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
       

        try {

            $model->apply_id = $args['apply_id']; 
            $model->contractor_id = $args['contractor_id']; 
            $model->program_id = $args['program_id']; 
            $model->wp_no = $args['wp_no']; 
            $model->worker_name = $args['worker_name']; 
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
    public static function deleteApplyWorkerLog($id) {

        if ($id == '') {
            $r['msg'] = '编号不能为空！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = ApplyWorkerLog::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = '无效的记录！';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        
        $sql = 'DELETE FROM ptw_apply_worker_log WHERE apply_id=:apply_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $id, PDO::PARAM_INT);
        
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
    
    //施工工人
    public static function getWorkerList($apply_id) {

        $sql = "SELECT wp_no,worker_name FROM ptw_apply_worker_log WHERE status=00 AND apply_id=:apply_id";
       
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":apply_id", $apply_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['wp_no']] = $row['worker_name'];
            }
        }

        return $rs;
    }
}
