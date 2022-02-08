<?php

/**
 * 化学物品类别
 * @author LiuMinchao
 */
class Chemical extends CActiveRecord {

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
        return 'bac_chemical';
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
            'chemical_id' => Yii::t('chemical', 'chemical_id'),
            'chemical_name' => Yii::t('chemical', 'chemical_name'),
            'type_no' => Yii::t('chemical', 'chemical_type'),
            'chemical_img'=>Yii::t('chemical','chemical_img'),
            'permit_img'=>Yii::t('chemical','permit_img'),
            'chemical_content'=>Yii::t('chemical','chemical_content'),
            'record_time' =>Yii::t('chemical','record_time'),
            'compound' => Yii::t('chemical', 'compound'),
            'usage' => Yii::t('chemical', 'usage'),
            'properties' => Yii::t('chemical', 'properties'),
            'storage_require' => Yii::t('chemical', 'storage_require'),
            'personal_protection' => Yii::t('chemical', 'personal_protection'),
            'first_aid_measures' => Yii::t('chemical', 'first_aid_measures'),
            'other_measures' => Yii::t('chemical', 'other_measures'),
            'person_in_charge' => Yii::t('chemical', 'person_in_charge'),
        );
    }
     //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('chemical', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('chemical', 'STATUS_DISABLE'),
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
        if ($args['chemical_id'] != '') {
            $condition.= ( $condition == '') ? ' chemical_id like :chemical_id' : ' AND chemical_id like :chemical_id';
            $params['chemical_id'] = '%'.str_replace(' ','', $args['chemical_id']).'%';
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
        $total_num = Chemical::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
            $order = 'primary_id DESC';
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

        $rows = Chemical::model()->findAll($criteria);
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
        if ($args['chemical_id'] != '') {
            $condition.= ( $condition == '') ? ' t.chemical_id like :chemical_id' : ' AND t.chemical_id like :chemical_id';
            $params['chemical_id'] = '%'.str_replace(' ','', $args['chemical_id']).'%';
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
            $order = 'primary_id DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $program_id = $args['program_id'];

        if($args['tag'] == 'out'){
            $criteria->join = "RIGHT JOIN bac_program_chemical b ON b.program_id = '$program_id' and t.primary_id = b.device_id ";
        }else{
            $criteria->join = "RIGHT JOIN bac_program_chemical b ON b.program_id = '$program_id' and t.primary_id = b.device_id ";
        }
        $criteria->params = $params;
        $criteria->order = $order;
//        $pages = new CPagination($total_num);
//        $pages->pageSize = $pageSize;
//        $pages->setCurrentPage($page);
//        $pages->applyLimit($criteria);

        $rows = Chemical::model()->findAll($criteria);
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
            $model->getAttributeLabel('chemical_id') => $model->chemical_id,
            $model->getAttributeLabel('chemical_name') => $model->chemical_name,
            $model->getAttributeLabel('type_no') => $model->type_no,
            $model->getAttributeLabel('compound') => $model->compound,
            $model->getAttributeLabel('usage') => $model->usage,
            $model->getAttributeLabel('properties') => $model->properties,
            $model->getAttributeLabel('storage_require') => $model->storage_require,
            $model->getAttributeLabel('personal_protection') => $model->personal_protection,
            $model->getAttributeLabel('first_aid_measures') => $model->first_aid_measures,
            $model->getAttributeLabel('other_measures') => $model->other_measures,
            $model->getAttributeLabel('person_in_charge') => $model->person_in_charge,
            $model->getAttributeLabel('chemical_img') => $model->chemical_img,
            $model->getAttributeLabel('chemical_content') => $model->chemical_content,
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
            $model->getAttributeLabel('chemical_id') => $model->chemical_id,
            $model->getAttributeLabel('chemical_name') => $model->chemical_name,
            $model->getAttributeLabel('type_no') => $model->type_no,
            $model->getAttributeLabel('compound') => $model->compound,
            $model->getAttributeLabel('usage') => $model->usage,
            $model->getAttributeLabel('properties') => $model->properties,
            $model->getAttributeLabel('storage_require') => $model->storage_require,
            $model->getAttributeLabel('personal_protection') => $model->personal_protection,
            $model->getAttributeLabel('first_aid_measures') => $model->first_aid_measures,
            $model->getAttributeLabel('other_measures') => $model->other_measures,
            $model->getAttributeLabel('person_in_charge') => $model->person_in_charge,
            $model->getAttributeLabel('chemical_img') => $model->chemical_img,
            $model->getAttributeLabel('chemical_content') => $model->chemical_content,
        );
        return $log;
    }
    public static function logoutLog($model) {
        $log = array(
            $model->getAttributeLabel('chemical_id') => $model->chemical_id,
            $model->getAttributeLabel('chemical_name') => $model->chemical_name,
            $model->getAttributeLabel('type_no') => $model->type_no,
            $model->getAttributeLabel('compound') => $model->compound,
            $model->getAttributeLabel('usage') => $model->usage,
            $model->getAttributeLabel('properties') => $model->properties,
            $model->getAttributeLabel('storage_require') => $model->storage_require,
            $model->getAttributeLabel('personal_protection') => $model->personal_protection,
            $model->getAttributeLabel('first_aid_measures') => $model->first_aid_measures,
            $model->getAttributeLabel('other_measures') => $model->other_measures,
            $model->getAttributeLabel('person_in_charge') => $model->person_in_charge,
            $model->getAttributeLabel('chemical_img') => $model->chemical_img,
            $model->getAttributeLabel('chemical_content') => $model->chemical_content,
        );
        return $log;
    }
    //添加
    public static function insertChemical($args){
        //form id　注意为model的数据库字段
//        var_dump($args);
//        exit;
        $exist_data = Chemical::model()->count('chemical_id=:chemical_id', array('chemical_id' => $args['chemical_id']));
        if ($exist_data != 0) {
            $sql = "SELECT a.chemical_name,b.contractor_name FROM bac_chemical a,bac_contractor b WHERE a.contractor_id=b.contractor_id AND a.chemical_id = :chemical_id ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":chemical_id", $args['chemical_id'], PDO::PARAM_INT);
            $s = $command->queryAll();
            $r['msg'] = Yii::t('chemical', 'Error Chemical is exist').'  '.$s[0]['contractor_name'].'.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('chemical', 'Error Chemical_id is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }  
        $model = new Chemical('create');
        $args['status'] =self::STATUS_NORMAL;
//        $trans = $model->dbConnection->beginTransaction();
        try{
            $model->chemical_id=$args['chemical_id'];
            $model->chemical_name = $args['chemical_name'];
            $model->chemical_content = $args['chemical_content'];
            $model->type_no = $args['type_no'];
            $model->chemical_img = $args['chemical_img'];
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
            $model->status = $args['status'];
            $model->compound = $args['compound'];
            $model->usage = $args['usage'];
            $model->properties = $args['properties'];
            $model->storage_require = $args['storage_require'];
            $model->personal_protection = $args['personal_protection'];
            $model->first_aid_measures = $args['first_aid_measures'];
            $model->other_measures = $args['other_measures'];
            $model->person_in_charge = $args['person_in_charge'];
            $result = $model->save();//var_dump($result);exit;

            $chemical_id = $model->chemical_id;
            $primary_id = $model->primary_id;
            $contractor_id = Yii::app()->user->getState('contractor_id');

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('chemical', 'Add Chemical'), self::insertLog($model));
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

    //修改
    public static function updateChemical($args,$chemical_id){
        if($chemical_id!=$args['chemical_id']){
            $exist_data = Chemical::model()->count('chemical_id=:chemical_id', array('chemical_id' => $args['chemical_id']));
            if ($exist_data != 0) {
                $r['msg'] = Yii::t('chemical', 'Error Chemical_id is exist');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }
        $model = Chemical::model()->find('chemical_id=:chemical_id',array(':chemical_id'=>$chemical_id));
//        $model = Device::model()->findByPk($args['device_id']);
//        $trans = $model->dbConnection->beginTransaction();
        try{
            $chemical_id = $model->chemical_id;//老的设备id
            $model->chemical_id=$args['chemical_id'];
            $model->chemical_name = $args['chemical_name'];
            $model->chemical_content = $args['chemical_content'];
            $model->type_no = $args['type_no'];
            $model->compound = $args['compound'];
            $model->usage = $args['usage'];
            $model->properties = $args['properties'];
            $model->storage_require = $args['storage_require'];
            $model->personal_protection = $args['personal_protection'];
            $model->first_aid_measures = $args['first_aid_measures'];
            $model->other_measures = $args['other_measures'];
            $model->person_in_charge = $args['person_in_charge'];
            if($args['chemical_img']<>'')
                $model->chemical_img = $args['chemical_img'];
            
            $model->contractor_id = Yii::app()->user->getState('contractor_id');
//            $model->status = self::STATUS_NORMAL;
            $result = $model->save();//var_dump($result);exit;
            
            if ($result) {
//                $trans->commit();
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('chemical', 'Edit Chemical'), self::updateLog($model));
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
    public static function logoutChemical($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('chemical', 'Error Chemical_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //var_dump($id);
        $model = Chemical::model()->find('primary_id=:primary_id', array(':primary_id' => $id));
        if ($model == null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        var_dump($model);
//        exit;
        //查询设备是否在项目组中
        $t = ProgramChemical::ChemicalProgramName($id);
//        var_dump($t);exit;
        if($t > 0){
            $content = '';
            foreach($t as $cnt => $list) {
                $content.= $list['program_name'].',';
            }
            $r['msg'] = Yii::t('chemical', 'Error chemical is in program').$content.Yii::t('chemical', 'Error chemical do not');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
//            $model->status = self::STATUS_DISABLE;
//            $result = $model->save();
            $chemical_name = $model->chemical_name.'[del]';
            $status = self::STATUS_DISABLE;
            $sql = 'UPDATE bac_chemical set status=:status,chemical_name=:chemical_name WHERE primary_id=:primary_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":chemical_name", $chemical_name, PDO::PARAM_STR);
            $command->bindParam(":primary_id", $id, PDO::PARAM_STR);
            $result = $command->execute();
//            $sql = 'DELETE FROM bac_device WHERE device_id=:device_id';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":device_id", $id, PDO::PARAM_INT);
//            $result = $command->execute();
            if ($result>0) {

//                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('device', 'Logout Device'), self::logoutLog($model));
                $del_status = self::STATUS_DELETE;
                $del_sql = 'UPDATE bac_chemical_info set status=:status WHERE primary_id=:primary_id';
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
    public static function chemicalList($contractor_id){
         
        if (Yii::app()->language == 'zh_CN'){
            $chemical_type = "chemical_type_en";
        }
        else{
            $chemical_type = "chemical_type_en";
        }
        
        $sql = 'select a.*, b.'.$chemical_type.' as chemical_type, b.type_no
                from (
                SELECT primary_id, chemical_name,type_no
                  FROM bac_chemical a
                WHERE a.contractor_id=:contractor_id AND a.status=00) a
                  LEFT JOIN bac_chemical_type b
                    on a.type_no = b.type_no
                 order by a.primary_id';
                 
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();

        foreach((array)$rows as $key => $row){
            $type[$row['type_no']] = $row['chemical_type'];
            $chemical[$row['type_no']][$row['primary_id']] = $row['chemical_name'];
        }
        
        
        $rs = array(
            'type'  =>  $type,
            'chemical'  =>  $chemical,
        );
        return $rs;
    }
    
    /**
     * 返回所有的设备
     * @return type
     */
    public static function chemicalAllList() {
        $sql = "SELECT chemical_id,chemical_name FROM bac_chemical";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['chemical_id']] = $row['chemical_name'];
            }
        }

        return $rs;
    }
    
    /**
     * 返回设备的类型编号
     * @return type
     */
    public static function typeAllList() {
        $sql = "SELECT chemical_id,type_no FROM bac_chemical";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['chemical_id']] = $row['type_no'];
            }
        }

        return $rs;
    }

    /**
     * 返回设备的主键编号
     * @return type
     */
    public static function primaryAllList() {
        $sql = "SELECT chemical_id,primary_id FROM bac_chemical";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['primary_id']] = $row['chemical_id'];
            }
        }

        return $rs;
    }

    /**
     * 根据条件选择导出的设备
     */
    public static function chemicalExport($args) {
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
        if ($args['chemical_id'] != '') {
            $condition.= ( $condition == '') ? ' chemical_id like :chemical_id' : ' AND chemical_id like :chemical_id';
            $params['chemical_id'] = '%'.str_replace(' ','', $args['chemical_id']).'%';
        }

        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }

        $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
        $params['status'] = '00';
        //var_dump($condition);
        $total_num = Chemical::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $order = 'chemical_id DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = $order;

        $rows = Chemical::model()->findAll($criteria);

        return $rows;
    }
    /**
     * 查询某设备具体信息
     */
    public static function deviceInfo(){
        $sql = "SELECT * FROM bac_chemical WHERE  status=00";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['primary_id']][] = $row;
            }
        }
        return $rs;
    }
}
