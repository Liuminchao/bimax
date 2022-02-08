<?php

/**
 * 分包指定项目到设备
 * @author LiuMinChao
 */
class ProgramDevice extends CActiveRecord {
  
    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用
    const STATUS_INVISIBLE = '99';//不可见
    const ENTRANCE_APPLY = '-1'; //进入申请名单
    const ENTRANCE_PENDING = '10'; //入场待审批
    const ENTRANCE_SUCCESS = '11'; //入场审批成功
    const LEAVE_PENDING = '20'; //出场待审批
    const LEAVE_SUCCESS = '21'; //出场审批成功

    public $subcomp_name; //指派分包公司名

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_device';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'device_id' => Yii::t('device', 'device_id'),
            'device_name' => Yii::t('device', 'device_name'),
            'type_no' => Yii::t('device', 'device_type'),
            'device_img'=>Yii::t('device','device_img'),
            'permit_img'=>Yii::t('device','permit_img'),
            'device_content'=>Yii::t('device','device_content'),
            'record_time' =>Yii::t('device','record_time'),
            'permit_startdate' => Yii::t('device', 'permit_startdate'),
            'permit_enddate' => Yii::t('device', 'permit_enddate'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Program the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_INVISIBLE => Yii::t('proj_project_device','Inactive'),
            self::ENTRANCE_APPLY => Yii::t('proj_project_user', 'entrance_apply'),
            self::ENTRANCE_PENDING => Yii::t('proj_project_user', 'entrance_pending'),
            self::ENTRANCE_SUCCESS => Yii::t('proj_project_device', 'Active'),
            self::LEAVE_PENDING => Yii::t('proj_project_user', 'leave_pending'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态
    public static function statusSubText($key = null) {
        $rs = array(
            self::STATUS_INVISIBLE => Yii::t('proj_project_device','Inactive'),
            self::ENTRANCE_PENDING => Yii::t('proj_project_user', 'entrance_pending'),
            self::ENTRANCE_SUCCESS => Yii::t('proj_project_device', 'Active'),
            self::LEAVE_PENDING => Yii::t('proj_project_user', 'leave_pending'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_INVISIBLE => 'label-danger',
            self::ENTRANCE_APPLY => 'label-warning',
            self::ENTRANCE_PENDING => 'label-default',
            self::ENTRANCE_SUCCESS => 'label-success',
            self::LEAVE_PENDING => 'label-danger',
        );
        return $key === null ? $rs : $rs[$key];
    }
    //查询某项目下人员(审核状态是-1，10，11，20)
    public static function queryByDevice($args = array()){
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        $exist_data = ProgramDevice::model()->findAll(
                        array(
                                'select'=>array('*'),
                                'condition' => 'program_id=:program_id and check_status in (:entrance_apply,:entrance_pending,:entrance_success,:leave_pending) and contractor_id=:contractor_id',
                                'params' => array(':program_id'=>$args['program_id'],':contractor_id'=>$args['contractor_id'],':entrance_apply'=>$entrance_apply,'entrance_pending'=>$entrance_pending,
                                    ':entrance_success'=>$entrance_success,':leave_pending'=>$leave_pending
                                ),
                            )
                    );
        return $exist_data;
    }
    //分页查询某项目下设备(审核状态是10，11，20)
    public static function querySubListByDevice($page, $pageSize, $args = array()) {

        $condition = '1=1';
        $params = array();
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;

        //Program Id
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' t.program_id =:program_id' : ' AND t.program_id =:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id' : ' AND t.contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //DeviceId
        if($args['device_id'] != ''){
            $sql = "select primary_id from bac_device where device_id like '%".$args['device_id']."%' ";
//            var_dump($sql);
//            exit;
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $i = '';
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $i.=$row['primary_id'].',';
                }
            }
            if ($i != '')
                $primary_id = substr($i, 0, strlen($i) - 1);
            $condition.= ( $condition == '') ? ' t.device_id IN ('.$primary_id.') ' : ' AND t.device_id IN ('.$primary_id.')';

        }
        //Check Status
        //Check Status
        if($args['status'] != ''){
            $condition.= ( $condition == '') ? ' t.check_status=:check_status' : ' AND t.check_status=:check_status';
            $params['check_status'] = $args['status'];
        }else {
            $condition .= ($condition == '') ? ' t.check_status in (:entrance_pending,:entrance_success,:leave_pending)' : ' AND t.check_status in (:entrance_pending,:entrance_success,:leave_pending)';
            $params['entrance_pending'] = $entrance_pending;
            $params['entrance_success'] = $entrance_success;
            $params['leave_pending'] = $leave_pending;
        }
        $total_num = ProgramDevice::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($args['device_id'] != '') {
            $order = 'field(t.check_status,-1,10,20,11)';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $order = 'field(t.check_status,-1,10,20,11)';
        if($args['type_no'] != ''){
            $type_no = $args['type_no'];
            $criteria->join = "RIGHT JOIN bac_device b ON b.primary_id = t.device_id and b.type_no = '$type_no' ";
        }

        $criteria->select = 't.*';
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
//        var_dump($criteria);
        $rows = ProgramDevice::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //分页查询某项目下设备(审核状态是-1，10，11，20)
    public static function queryListByDevice($page, $pageSize, $args = array()) {

        $condition = '1=1';
        $params = array();
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;

        //Program Id
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' t.program_id =:program_id' : ' AND t.program_id =:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id' : ' AND t.contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //DeviceId
        if($args['device_id'] != ''){
            $sql = "select primary_id from bac_device where device_id like '%".$args['device_id']."%' ";
//            var_dump($sql);
//            exit;
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $i = '';
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $i.=$row['primary_id'].',';
                }
            }
            if ($i != '')
                $primary_id = substr($i, 0, strlen($i) - 1);
            $condition.= ( $condition == '') ? ' t.device_id IN ('.$primary_id.') ' : ' AND t.device_id IN ('.$primary_id.')';

        }
        //Check Status
        //Check Status
        if($args['status'] != ''){
            $condition.= ( $condition == '') ? ' t.check_status=:check_status' : ' AND t.check_status=:check_status';
            $params['check_status'] = $args['status'];
        }else {
            $condition .= ($condition == '') ? ' t.check_status in (:entrance_apply,:entrance_pending,:entrance_success,:leave_pending)' : ' AND t.check_status in (:entrance_apply,:entrance_pending,:entrance_success,:leave_pending)';
            $params['entrance_apply'] = $entrance_apply;
            $params['entrance_pending'] = $entrance_pending;
            $params['entrance_success'] = $entrance_success;
            $params['leave_pending'] = $leave_pending;
        }
        $total_num = ProgramDevice::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $order = 'field(t.check_status,-1,10,20,11)';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $order = 'field(t.check_status,-1,10,20,11)';
        if($args['type_no'] != ''){
            $type_no = $args['type_no'];
            $criteria->join = "RIGHT JOIN bac_device b ON b.primary_id = t.device_id and b.type_no = '$type_no' ";
        }
        $criteria->select = 't.*';
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = ProgramDevice::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //修改日志
    public static function updateLog($model) {
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('program_id') => Yii::app()->db->lastInsertID,
            $model->getAttributeLabel('program_name') => $model->program_name,
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('add_operator') => $model->add_operator,
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('record_time') => $model->record_time,
           // Yii::t('proj_project_user', 'Assign SC') => self::subcompText($model->program_id),
        );
    }
    
    //提交设备申请
    public static function SubmitApplicationsDevice($args) {
//        var_dump($args);
//        exit;
        if ($args['program_id'] == '') {
            $r['msg'] = Yii::t('proj_project_user', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = Program::model()->findByPk($args['program_id']);
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {

            $program_id = $args['program_id'];
            $apply_user_id = Yii::app()->user->getState('contractor_id');
            $time = date('Y-m-d H:i:s');
            //勾选设备数组
            $new_list = (array)$args['sc_list'];
            $entrance_apply = self::ENTRANCE_APPLY;
            $entrance_pending = self::ENTRANCE_PENDING;
            $entrance_success = self::ENTRANCE_SUCCESS;
            $leave_pending = self::LEAVE_PENDING;
            $leave_success = self::LEAVE_SUCCESS;
            $status_stop = self::STATUS_NORMAL;
            //将新增设备置为入场待审批
            if(!empty($new_list)){
                foreach ($new_list as $key => $id) {
                    $programdevice_model = ProgramDevice::model()->find('program_id=:program_id AND device_id=:device_id', array(':program_id' => $program_id,':device_id'=>$id));
                    $check_status = $programdevice_model->check_status;
                    switch ($check_status)
                    {
                        case "21":
                            $sql = "UPDATE bac_program_device SET check_status = '".$entrance_apply."',status = '".$status_stop."',apply_date = '".$time."' WHERE device_id='".$id."'and program_id = '".$program_id."'";
                            $command = Yii::app()->db->createCommand($sql);
                            $rows = $command->execute();
                            break;
                        case "-1":
                            break;
                        case "10":
                            break;
                        case "11":
                            break;
                        case "20":
                            break;
                        default:
                            $sql = 'INSERT INTO bac_program_device(program_id,contractor_id,device_id,primary_id,root_proid,check_status,status,apply_user_id,apply_date) VALUES(:program_id,:contractor_id,:device_id,:primary_id,:root_proid,:check_status,:status,:apply_user_id,:apply_date)';
                            $command = Yii::app()->db->createCommand($sql);
                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                            $command->bindParam(":contractor_id", $args['contractor_id'], PDO::PARAM_INT);
                            $command->bindParam(":device_id", $id, PDO::PARAM_STR);
                            $command->bindParam(":primary_id",$id,PDO::PARAM_STR);
                            $command->bindParam(":root_proid", $model->root_proid, PDO::PARAM_STR);
                            $command->bindParam(":check_status", $entrance_apply, PDO::PARAM_STR);
                            $command->bindParam(":status", $status_stop, PDO::PARAM_STR);
                            $command->bindParam(":apply_user_id", $apply_user_id, PDO::PARAM_STR);
                            $command->bindParam(":apply_date", $time, PDO::PARAM_STR);

                            $rs = $command->execute();
                    }
//                    $exist_data = ProgramDevice::model()->findAll(
//                        array(
//                                'select'=>array('*'),
//                                'condition' => 'program_id=:program_id and check_status=:check_status and device_id=:device_id',
//                                'params' => array(':program_id'=>$program_id,':check_status'=>$leave_success,':device_id'=>$id),
//                            )
//                    );
//                    if($exist_data){
//                        $sql = "UPDATE bac_program_device SET check_status = '".$entrance_apply."',status = '".$status_stop."',apply_date = '".$time."' WHERE device_id='".$id."'and program_id = '".$program_id."'";
//                        $command = Yii::app()->db->createCommand($sql);
//                        $rows = $command->execute();
//                    }else{
//                        $device_data = ProgramDevice::model()->findAll(
//                            array(
//                                'select'=>array('*'),
//                                'condition' => 'program_id=:program_id and  device_id=:device_id',
//                                'params' => array(':program_id'=>$program_id,':device_id'=>$id),
//                            )
//                        );
//                        if(!$device_data){
//                            $sql = 'INSERT INTO bac_program_device(program_id,contractor_id,device_id,primary_id,root_proid,check_status,status,apply_user_id,apply_date) VALUES(:program_id,:contractor_id,:device_id,:primary_id,:root_proid,:check_status,:status,:apply_user_id,:apply_date)';
//                            $command = Yii::app()->db->createCommand($sql);
//                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
//                            $command->bindParam(":contractor_id", $args['contractor_id'], PDO::PARAM_INT);
//                            $command->bindParam(":device_id", $id, PDO::PARAM_STR);
//                            $command->bindParam(":primary_id",$id,PDO::PARAM_STR);
//                            $command->bindParam(":root_proid", $model->root_proid, PDO::PARAM_STR);
//                            $command->bindParam(":check_status", $entrance_apply, PDO::PARAM_STR);
//                            $command->bindParam(":status", $status_stop, PDO::PARAM_STR);
//                            $command->bindParam(":apply_user_id", $apply_user_id, PDO::PARAM_STR);
//                            $command->bindParam(":apply_date", $time, PDO::PARAM_STR);
//
//                            $rs = $command->execute();
//                        }
//
//                    }
                }
            }
            
            
            OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project_user', 'Edit Proj'), self::updateLog($model));
            $r['msg'] = Yii::t('common', 'success_apply');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
//        var_dump($r);
//        exit;
        return $r;
    }
    
    
    
//查询项目中状态为(-1,10,11,20)的设备
    public static function myDeviceListBySuccess($program_id, $contractor_id) {
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        $sql = "SELECT a.device_id,b.device_name FROM bac_program_device a,bac_device b WHERE  a.program_id=:program_id and a.contractor_id=:contractor_id and a.check_status in(:entrance_apply,:entrance_pending,:entrance_success,:leave_pending) and a.device_id = b.primary_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $command->bindParam(":entrance_apply", $entrance_apply, PDO::PARAM_INT);
        $command->bindParam(":entrance_pending",$entrance_pending,PDO::PARAM_INT);
        $command->bindParam(":entrance_success", $entrance_success, PDO::PARAM_INT);
        $command->bindParam(":leave_pending",$leave_pending,PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['device_id']] = $row['device_name'];
            }
        }
        return $rs;
    }


    

    public static function DeleteDevice($program_id,$primary_id) {
        $sql = "DELETE FROM bac_program_device WHERE program_id =:program_id and primary_id =:primary_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":primary_id", $primary_id, PDO::PARAM_STR);
                    
        $rs = $command->execute();
        
        if($rs ==2 || $rs == 1){
            $r['msg'] = '删除成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '删除失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function BatchLeaveDevice($program_id,$device_list) {
        $leave_pending = self::LEAVE_PENDING;
        $status = self::STATUS_NORMAL;
        foreach($device_list as $k => $primary_id){
            $sql = "UPDATE bac_program_device SET check_status=:check_status,status=:status WHERE program_id =:program_id and device_id =:device_id";//var_dump($sql);;;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_status", $leave_pending, PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
            $command->bindParam(":device_id", $primary_id, PDO::PARAM_STR);

            $rs = $command->execute();
        }

        if($rs ==2 || $rs == 1){
            $r['msg'] = '申请成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '申请失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function LeaveDevice($program_id,$primary_id) {
        $leave_pending = self::LEAVE_PENDING;
        $status = self::STATUS_NORMAL;
        $sql = "UPDATE bac_program_device SET check_status=:check_status,status=:status WHERE program_id =:program_id and device_id =:device_id";//var_dump($sql);;;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_status", $leave_pending, PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":device_id", $primary_id, PDO::PARAM_STR);
                    
        $rs = $command->execute();
        
        if($rs ==2 || $rs == 1){
            $r['msg'] = '申请成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '申请失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function VisibleDevice($program_id,$primary_id) {
        $status = self::STATUS_NORMAL;
        $sql = "UPDATE bac_program_device SET status=:status WHERE program_id =:program_id and primary_id =:primary_id";//var_dump($sql);;;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":primary_id", $primary_id, PDO::PARAM_STR);

        $rs = $command->execute();

        if($rs ==2 || $rs == 1){
            $r['msg'] = '申请成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '申请失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function InvisibleDevice($program_id,$primary_id) {
        $status = self::STATUS_INVISIBLE;
//        var_dump($program_id);
//        var_dump($primary_id);
//        exit;
        $sql = "UPDATE bac_program_device SET status=:status WHERE program_id =:program_id and primary_id =:primary_id";//var_dump($sql);;;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":primary_id", $primary_id, PDO::PARAM_STR);

        $rs = $command->execute();

        if($rs ==2 || $rs == 1){
            $r['msg'] = '申请成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '申请失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function EntranceDevice($program_id,$row) {
        $entrance_pending = self::ENTRANCE_PENDING;
        $status = self::STATUS_NORMAL;
        foreach($row as $n => $id){
            $sql = "UPDATE bac_program_device SET check_status=:check_status,status=:status WHERE program_id =:program_id and device_id =:device_id";//var_dump($sql);;;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_status", $entrance_pending, PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
            $command->bindParam(":device_id", $id, PDO::PARAM_STR);

            $rs = $command->execute();
        }
        if($rs ==2 || $rs == 1){
            $r['msg'] = '申请成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '申请失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //查询某项目下入场设备信息
    public static function PersonelDevice($device_id,$program_id){
        $sql = "SELECT * FROM bac_program_device WHERE device_id = :device_id AND program_id = :program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":device_id", $device_id, PDO::PARAM_INT);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
       
        return $rows; 
    }

    //查询设备在哪个项目组中
    public static function DeviceProgramName($device_id){
        if($device_id == ''){
            return '';
        }
        $cnt = ProgramDevice::model()->count("device_id='".$device_id."' and check_status in (-1,10,11,20)");
        if($cnt > 0){
            $sql = "select b.program_name from bac_program_device a ,bac_program b where a.primary_id = '".$device_id."' and a.check_status in (-1,10,11,20) and a.program_id = b.program_id and b.status = '00'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
        }
        return $rows;
    }

    //根据承包商ID和device_id获取所在项目的各项信息
    public static function SelfInfo($contractor_id,$primary_id){
        $sql = "select a.*,b.program_name,b.program_id from bac_program_device a,bac_program b where a.contractor_id = '".$contractor_id."' and a.device_id = '".$primary_id."' and a.check_status='11' and a.program_id = b.program_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //根据承包商ID和user_id以及program_id获取所在项目的各项信息
    public static function SelfByPro($contractor_id,$device_id,$program_id){
        $sql = "select * from bac_program_device  where contractor_id = '".$contractor_id."' and device_id = '".$device_id."' and program_id = '".$program_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计设备在某个项目中进出场日期
    public static function SelfByDate($device_id,$program_id){
        $sql = "select b.apply_time as entrance_time,c.apply_time as leave_time from bac_program_device a
        inner join bac_check_apply_detail b on a.entrance_apply_id = b.apply_id
        inner join bac_check_apply_detail c on a.leave_apply_id = c.apply_id
        where a.program_id = '".$program_id."' and  a.device_id = '".$device_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    //下载PDF
    public static function downloadPdf($params,$app_id){
//        var_dump($params);
//        exit;
        $program_id = $params['program_id'];
        $primary_id = $params['primary_id'];


        $device_model = Device::model()->findByPk($primary_id);
        $device_id = $device_model->device_id;
        $pro_model = Program::model()->findByPk($program_id);
        $pro_params = $pro_model->params;//项目参数
        if($pro_params != '0') {
            $pro_params = json_decode($pro_params, true);
            //判断是否是迁移的
            if (array_key_exists('transfer_con', $pro_params)) {
                $contractor_id = $pro_params['transfer_con'];
            } else {
                $contractor_id = $device_model->contractor_id;
            }
        }else{
            $contractor_id = $device_model->contractor_id;
        }
        $arry['contractor_id'] = $contractor_id;
        $program_list = Program::programAllList($arry);//项目列表
        $program_name = $program_list[$program_id];//项目名称
//        $device_model = Device::model()->findByPk($device_id);//设备信息
//        $device_model = Device::model()->find('device_id=:device_id', array(':device_id' => $device_id));
        $type_no = $device_model->type_no;
        $devicetype_model = DeviceType::model()->findByPk($type_no);//设备类型信息
        $device_img = $device_model->device_img;//设备图片
        $qrcode = $device_model->qrcode;//设备二维码

        if(!$qrcode){
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'phpqrcode'.DIRECTORY_SEPARATOR.'qrlib.php';
            require_once($tcpdfPath);
            $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] .'/qrcode/'.$contractor_id.'/device/';
            $errorCorrectionLevel = 'L';
            if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
                $errorCorrectionLevel = $_REQUEST['level'];

            $matrixPointSize = 4;
            if (isset($_REQUEST['size']))
                $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

            $filename = $PNG_TEMP_DIR. $primary_id.'_new.png';
            $content = 'did|'.$primary_id;
            QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
            Device::insertQrcode($primary_id,$filename);
            $qrcode = $device_model->qrcode;//设备二维码
        }


        $device_list = ProgramDevice::PersonelDevice($device_id, $program_id);//项目设备信息
        //var_dump($programuser_model);
        $device_name = $device_model->device_name;//设备名称
        $device_id = $device_model->device_id;//设备编号
        $device_type = $devicetype_model->device_type_en;//设备型号
        $device_startdate = $device_model->permit_startdate;//设备许可证开始日期
        $device_enddate = $device_model->permit_enddate;//设备许可证结束日期
        $contractor_list = Contractor::compList();//承包商名称列表
        $lang = "_en";
        $showtime=Utils::DateToEn(date("Y-m-d"));//当前时间
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_file_path'] . '/Device' . $device_id . $lang . '.pdf';
        $pdf_title = 'Device' . $device_id . $lang . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads' . '/PTW' . $id . $lang . '.pdf';
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
//        var_dump($filepath);
//        exit;
        $title = Yii::t('proj_project_device', 'pdf_title');
        ///opt/www-nginx/web/test/ctmgr

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new ReportPdf('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        $device_detail = Yii::t('proj_project_device','device_detail');
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $_SESSION['title'] = $device_detail;
        $pdf->Header();
//        $pdf->SetHeaderData('', 0, '', $device_detail, array(0, 64, 255), array(0, 64, 128));
//        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 14, '', true); //英文
        }

        $pdf->AddPage();
        //设备照片
        $y = $pdf->GetY();
        $x = $pdf->GetX();
        $next_x = $x+75;
        $last_x = $x+150;
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic =$main_model->remark;
        if($device_img){
            $pdf->Image($device_img, $x, $y, 30, 30, 'JPG', '', '',  false, 300, '', false, false, 1, false, false, false);
        }
        if($logo_pic){
            $pdf->Image($logo_pic, $next_x, $y, 30, 30, 'JPG', '', '',  false, 300, '', false, false, 1, false, false, false);
        }
        if($qrcode){
            $pdf->Image($qrcode, $last_x, $y, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 1, false, false, false);
        }
        $pic_html = '<table ><tr ><td height="105px"></td></tr></table>';
        $pdf->writeHTML($pic_html, true, false, true, false, '');
        //设备信息
        $device_html =
            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border-color:gray gray gray gray;border-width: 0 1px 0 1px;"><tr><td colspan="2" style="border-width: 1px;border-color:gray gray gray gray"><h5 align="center">'.$title.'</h5></td></tr><tr><td width="25%" style="border-width: 1px;border-color:gray gray gray gray">'.Yii::t('proj_project_device', 'device_id').'</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray">'.$device_id.'</td></tr><tr><td width="25%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_device', 'device_name') . '</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray">'. $device_name.'</td></tr><tr><td width="25%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_device', 'device_type') . '</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray">'. $device_type.'</td></tr><tr><td width="25%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_user', 'company_name') . '</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray">'. $contractor_list[$contractor_id].'</td></tr><tr><td width="25%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project', 'program_name') . '</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray">'. $program_name.'</td></tr></table>';

        //许可证开始日期
        $startdate_html = '<tr><td width="25%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_device', 'start_date') .'</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray">'
            .Utils::DateToEn($device_startdate).'</td></tr>' ;

        //许可证结束日期
        $enddate_html = '<tr><td width="25%" style="border-width: 1px;border-color:gray gray gray gray">'
            . Yii::t('proj_project_device', 'end_date') . '</td><td width="75%" style="border-width: 1px;border-color:gray gray gray gray">'
            .Utils::DateToEn($device_enddate).'</td></tr>' ;


        $html = $device_html;

        $pdf->writeHTML($html, true, false, true, false, '');
        $img_num = 0;//检验页码标志
        $x = 30;
        $y_1 = 30;//第一张y的位置
        $y_2 = 150;//第二张y的位置
        $aptitude_list =DeviceInfo::queryAll($device_id);//人员证书
        if($aptitude_list){
            foreach($aptitude_list as $cnt => $list){
                $aptitude = explode('|',$list['certificate_photo']);
                foreach($aptitude as $i => $photo){
                    $file = explode('.',$photo);
                    if($file[1] != 'pdf') {
                        if ($img_num % 2 == 0) {
                            $pdf->AddPage();//再加一页
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        } else {
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        }
                    }
                }
            }
        }
        //输出PDF
//         $pdf->Output($filepath, 'I');
        $pdf->Output($pdf_title,'D');
        //$pdf->Output($filepath, 'F'); //保存到指定目录
        //Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }

    //导出Excel
    public static function deviceinfo($args){
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        if($args['status'] != ''){
            $sql = "select a.device_name,a.primary_id,a.device_id,a.type_no,a.device_img,a.qrcode,b.apply_date
                  from (bac_device a inner join bac_program_device b
                  on a.primary_id = b.device_id and b.program_id = '".$args['program_id']."' and b.check_status='".$args['status']."')";
        }else{
            $sql = "select a.device_name,a.primary_id,a.device_id,a.type_no,a.device_img,a.qrcode,b.apply_date
                  from (bac_device a inner join bac_program_device b
                  on a.primary_id = b.device_id and b.program_id = '".$args['program_id']."' and b.check_status in ('".$entrance_apply."','".$entrance_pending."','".$entrance_success."','".$leave_pending."'))";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
}
