<?php

/**
 * 设备类别
 * @author LiuMinchao
 */
class Device extends CActiveRecord {

    const STATUS_NORMAL = '00'; //已启用
    const STATUS_DISABLE = '01'; //未启用
    const STATUS_DELETE = '1';//已删除
    const ENTRANCE_APPLY = '-1'; //进入申请名单
    const ENTRANCE_PENDING = '10'; //入场待审批
    const ENTRANCE_SUCCESS = '11'; //入场审批成功
    const LEAVE_PENDING = '20'; //出场待审批
    const LEAVE_SUCCESS = '21'; //出场审批成功

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_device';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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
            'test_load' => Yii::t('device', 'test_load'),
            'safe_working_load' => Yii::t('device', 'safe_working_load'),
            'next_service_date' => Yii::t('device','next_service_date'),
        );
    }
     //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('device', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('device', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //已启用
            self::STATUS_DISABLE => ' label-danger', //未启用
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
        //var_dump($args);
        $condition = '';
        $params = array();
//        var_dump($args);
//        exit;
        //user_phone
        if ($args['type_no'] != '') {
            $condition.= ( $condition == '') ? ' type_no=:type_no' : ' AND type_no=:type_no';
            $params['type_no'] = str_replace(' ','', $args['type_no']);
        }
       //work_no
        if ($args['device_id'] != '') {
            $condition.= ( $condition == '') ? ' device_id like :device_id' : ' AND device_id like :device_id';
            $params['device_id'] = '%'.str_replace(' ','', $args['device_id']).'%';
        }

        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }

        //var_dump($condition);
        $total_num = Device::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
            $order = 'record_time DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = $order;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = Device::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByProgram($page, $pageSize, $args = array()) {
        //var_dump($args);
        $condition = '';
        $params = array();
//        var_dump($args);
//        exit;
        //user_phone
        if ($args['type_no'] != '') {
            $condition.= ( $condition == '') ? ' t.type_no=:type_no' : ' AND t.type_no=:type_no';
            $params['type_no'] = str_replace(' ','', $args['type_no']);
        }
        //work_no
        if ($args['device_id'] != '') {
            $condition.= ( $condition == '') ? ' t.device_id like :device_id' : ' AND t.device_id like :device_id';
            $params['device_id'] = '%'.str_replace(' ','', $args['device_id']).'%';
        }

        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' t.status=:status' : ' AND t.status=:status';
            $params['status'] = $args['status'];
        }
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id ' : ' AND t.contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }

        //var_dump($condition);
//        $total_num = Device::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $order = 'record_time DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $program_id = $args['program_id'];

        if($args['tag'] == 'out'){
            $criteria->join = "RIGHT JOIN bac_program_device b ON b.program_id = '$program_id' and t.primary_id = b.device_id and b.check_status in (10)";
        }else{
            $criteria->join = "RIGHT JOIN bac_program_device b ON b.program_id = '$program_id' and t.primary_id = b.device_id and b.check_status in (11,20)";
        }
        $criteria->params = $params;
        $criteria->order = $order;
//        $pages = new CPagination($total_num);
//        $pages->pageSize = $pageSize;
//        $pages->setCurrentPage($page);
//        $pages->applyLimit($criteria);

        $rows = Device::model()->findAll($criteria);
        $start=$page*$pageSize; #计算每次分页的开始位置
//        var_dump($start);
//        var_dump($pageSize);
        $total_num = count($rows);
        $pagedata=array();
        $pagedata=array_slice($rows,$start,$pageSize);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    /**
     * 添加日志详细
     * @param type $model
     * @return array
     */
    public static function insertLog($model) {
        $log = array(
            $model->getAttributeLabel('device_id') => $model->device_id,
            $model->getAttributeLabel('device_name') => $model->device_name,
            $model->getAttributeLabel('type_no') => $model->type_no,
            $model->getAttributeLabel('device_img') => $model->device_img,
            $model->getAttributeLabel('permit_img') => $model->permit_img,
            $model->getAttributeLabel('device_content') => $model->device_content,
            $model->getAttributeLabel('permit_startdate') => $model->permit_startdate,
            $model->getAttributeLabel('permit_enddate') => $model->permit_enddate,
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
            $model->getAttributeLabel('device_id') => $model->device_id,
            $model->getAttributeLabel('device_name') => $model->device_name,
            $model->getAttributeLabel('type_no') => $model->type_no,
            $model->getAttributeLabel('device_img') => $model->device_img,
            $model->getAttributeLabel('permit_img') => $model->permit_img,
            $model->getAttributeLabel('device_content') => $model->device_content,
            $model->getAttributeLabel('permit_startdate') => $model->permit_startdate,
            $model->getAttributeLabel('permit_enddate') => $model->permit_enddate,
        );
        return $log;
    }
    public static function logoutLog($model) {
        $log = array(
            $model->getAttributeLabel('device_id') => $model->device_id,
            $model->getAttributeLabel('device_name') => $model->device_name,
            $model->getAttributeLabel('type_no') => $model->type_no,
            $model->getAttributeLabel('device_img') => $model->device_img,
            $model->getAttributeLabel('permit_img') => $model->permit_img,
            $model->getAttributeLabel('device_content') => $model->device_content,
            $model->getAttributeLabel('permit_startdate') => $model->permit_startdate,
            $model->getAttributeLabel('permit_enddate') => $model->permit_enddate,
        );
        return $log;
    }
    //添加
    public static function insertDevice($args){
        //form id　注意为model的数据库字段
//        var_dump($args);
//        exit;
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $exist_data = Device::model()->count('device_id=:device_id and contractor_id=:contractor_id', array('device_id' => $args['device_id'],'contractor_id' => $contractor_id));
        if ($exist_data != 0) {
            $sql = "SELECT a.device_name,b.contractor_name FROM bac_device a,bac_contractor b WHERE a.contractor_id=b.contractor_id AND a.device_id = :device_id AND a.contractor_id= :contractor_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":device_id", $args['device_id'], PDO::PARAM_STR);
            $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
            $s = $command->queryAll();
            $r['msg'] = Yii::t('device', 'Error Device is exist').'  '.$s[0]['contractor_name'].'.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('device', 'Error Equipment_id is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }  
        $model = new Device('create');
        $args['status'] =self::STATUS_NORMAL;
//        $trans = $model->dbConnection->beginTransaction();
        try{
            $model->device_id=addslashes($args['device_id']);
            $model->device_name = addslashes($args['device_name']);
            $model->device_content = addslashes($args['device_content']);
            $model->type_no = $args['type_no'];
            $model->permit_startdate = Utils::DateToCn($args['permit_startdate']);
            $model->permit_enddate = Utils::DateToCn($args['permit_startdate']);
            $model->device_img = $args['device_img'];
            $model->permit_img = $args['permit_img'];
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
//            $model->status = $args['status'];
//            var_dump($args);
//            exit;
            $result = $model->save();//var_dump($result);exit;

            $device_id = $model->device_id;
            $primary_id = $model->primary_id;
            $contractor_id = Yii::app()->user->getState('contractor_id');
            Device::buildQrCode($contractor_id,$primary_id);

            if ($result) {
//                $trans->commit();
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('device', 'Add Device'), self::insertLog($model));
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
//                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            } 
            
        }
        catch(Exception $e){
//            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //添加设备二维码
    public static function insertQrcode($device_id,$PNG_TEMP_DIR) {
        $path = substr($PNG_TEMP_DIR.$device_id.'_new.png',18);
        $sql = "update bac_device set qrcode = '".$path."' where primary_id = '".$device_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }
    //修改
    public static function updateDevice($args,$device_id){
        if($device_id!=$args['device_id']){
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $exist_data = Device::model()->count('device_id=:device_id and contractor_id=:contractor_id', array('device_id' => $args['device_id'],'contractor_id' => $contractor_id));
            if ($exist_data != 0) {
                $sql = "SELECT a.device_name,b.contractor_name FROM bac_device a,bac_contractor b WHERE a.contractor_id=b.contractor_id AND a.device_id = :device_id AND a.contractor_id= :contractor_id";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":device_id", $args['device_id'], PDO::PARAM_STR);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
                $s = $command->queryAll();
                $r['msg'] = Yii::t('device', 'Error Device is exist').'  '.$s[0]['contractor_name'].'.';
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }

        $model = Device::model()->find('device_id=:device_id',array(':device_id'=>$device_id));
//        $model = Device::model()->findByPk($args['device_id']);
//        $trans = $model->dbConnection->beginTransaction();
        try{
            $device_id = $model->device_id;//老的设备id
            $model->device_id=addslashes($args['device_id']);
            $model->device_name = addslashes($args['device_name']);
            $model->device_content = addslashes($args['device_content']);
            $model->type_no = $args['type_no'];
            $model->permit_startdate = Utils::DateToCn($args['permit_startdate']);
            $model->permit_enddate = Utils::DateToCn($args['permit_startdate']);
            $model->permit_img = $args['permit_img'];
            if($args['device_img']<>'')
                $model->device_img = $args['device_img'];
//            if($args['permit_img']<>'')
            
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
//            $model->status = self::STATUS_NORMAL;
            $result = $model->save();//var_dump($result);exit;
            
            if ($result) {
//                $trans->commit();
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('device', 'Edit Device'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
//                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            } 
        }
        catch(Exception $e){
//            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function logoutDevice($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('device', 'Error Equipment_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //var_dump($id);
        $model = Device::model()->find('primary_id=:primary_id', array(':primary_id' => $id));
        if ($model == null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        var_dump($model);
//        exit;
        //查询设备是否在项目组中
        $t = ProgramDevice::DeviceProgramName($id);
//        var_dump($t);exit;
        if($t > 0){
            $content = '';
            foreach($t as $cnt => $list) {
                $content.= $list['program_name'].',';
            }
            $r['msg'] = Yii::t('device', 'Error device is in program').$content.Yii::t('device', 'Error device do not');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
//            $model->status = self::STATUS_DISABLE;
//            $result = $model->save();
            $device_name = $model->device_name.'[del]';
            $status = self::STATUS_DISABLE;
            $sql = 'UPDATE bac_device set status=:status,device_name=:device_name WHERE primary_id=:primary_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":device_name", $device_name, PDO::PARAM_STR);
            $command->bindParam(":primary_id", $id, PDO::PARAM_STR);
            $result = $command->execute();
//            $sql = 'DELETE FROM bac_device WHERE device_id=:device_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":device_id", $id, PDO::PARAM_INT);
//            $result = $command->execute();
            if ($result>0) {

//                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('device', 'Logout Device'), self::logoutLog($model));
                $del_status = self::STATUS_DELETE;
                $del_sql = 'UPDATE bac_device_info set status=:status WHERE primary_id=:primary_id';
                $del_command = Yii::app()->db->createCommand($del_sql);
                $del_command->bindParam(":status", $del_status, PDO::PARAM_STR);
                $del_command->bindParam(":primary_id", $id, PDO::PARAM_STR);
                $del_command->execute();
                $r['msg'] = Yii::t('common', 'success_logout');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error _logout');
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
    
    
    //查询承包商所属设备
    public static function deviceList($contractor_id){
         
        if (Yii::app()->language == 'zh_CN'){
            $device_type = "device_type_en";
        }
        else{
            $device_type = "device_type_en";
        }
        
        $sql = 'select a.*, b.'.$device_type.' as device_type, b.type_no
                from (
                SELECT primary_id, device_name,type_no
                  FROM bac_device a
                WHERE a.contractor_id=:contractor_id AND a.status=00) a
                  LEFT JOIN bac_device_type b
                    on a.type_no = b.type_no
                 order by a.primary_id';
                 
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();

        foreach((array)$rows as $key => $row){
            $type[$row['type_no']] = $row['device_type'];
            $device[$row['type_no']][$row['primary_id']] = $row['device_name'];
        }
        
        
        $rs = array(
            'type'  =>  $type,
            'device'  =>  $device,
        );
        return $rs;
    }
    
    /**
     * 返回所有的设备
     * @return type
     */
    public static function deviceAllList() {
        $sql = "SELECT device_id,device_name FROM bac_device";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['device_id']] = $row['device_name'];
            }
        }

        return $rs;
    }
    
    /**
     * 返回设备的类型编号
     * @return type
     */
    public static function typeAllList() {
        $sql = "SELECT device_id,type_no FROM bac_device";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['device_id']] = $row['type_no'];
            }
        }

        return $rs;
    }

    /**
     * 返回设备的主键编号
     * @return type
     */
    public static function primaryAllList() {
        $sql = "SELECT device_id,primary_id FROM bac_device";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['primary_id']] = $row['device_id'];
            }
        }

        return $rs;
    }

    /**
     * 根据条件选择导出的设备
     */
    public static function deviceExport($args) {
        $condition = '';
        $params = array();
//        var_dump($args);
//        exit;
        //user_phone
        if ($args['type_no'] != '') {
            $condition.= ( $condition == '') ? ' type_no=:type_no' : ' AND type_no=:type_no';
            $params['type_no'] = str_replace(' ','', $args['type_no']);
        }
        //work_no
        if ($args['device_id'] != '') {
            $condition.= ( $condition == '') ? ' device_id like :device_id' : ' AND device_id like :device_id';
            $params['device_id'] = '%'.str_replace(' ','', $args['device_id']).'%';
        }

        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }

        $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
        $params['status'] = '00';
        //var_dump($condition);
        $total_num = Device::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $order = 'device_id DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = $order;

        $rows = Device::model()->findAll($criteria);

        return $rows;
    }
    /**
     * 查询某设备具体信息
     */
    public static function deviceInfo(){
        $sql = "SELECT * FROM bac_device WHERE  status=00";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['primary_id']][] = $row;
            }
        }
        return $rs;
    }
    /**
     * 生成设备二维码
     */
    public static function buildQrCode($contractor_id,$primary_id) {
        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] .'/qrcode/'.$contractor_id.'/device/';
        //include "qrlib.php";
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'phpqrcode'.DIRECTORY_SEPARATOR.'qrlib.php';
        require_once($tcpdfPath);
        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

        $filename = $PNG_TEMP_DIR. $primary_id.'_new.png';
        $content = 'did|'.$primary_id;
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        Device::insertQrcode($primary_id,$PNG_TEMP_DIR);
    }
}
