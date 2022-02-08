<?php

/**
 * 月度报告
 * @author LiuMinchao
 */
class Report extends CActiveRecord {

    const STATUS_NORMAL = '0'; //已启用
    const STATUS_DISABLE = '1'; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'report_monthly';
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
            'executive_summary' => Yii::t('report', 'executive_summary'),
            'accident_statistics' => Yii::t('report', 'accident_statistics'),
            'safety_policy' => Yii::t('report', 'safety_policy'),
            'external_training_scheduled'=>Yii::t('report','external_training_scheduled'),
            'group_meetings'=>Yii::t('report','group_meetings'),
            'ehs_station'=>Yii::t('report','ehs_station'),
            'security_station' =>Yii::t('report','security_station'),
            'ehs_tunnel' => Yii::t('report', 'ehs_tunnel'),
            'signboards_posters' => Yii::t('report', 'signboards_posters'),
            'safety_conscious_workers' => Yii::t('report', 'safety_conscious_workers'),
            'newspaper_articles' => Yii::t('report', 'newspaper_articles'),
            'safety_committee_inspection' => Yii::t('report', 'safety_committee_inspection'),
            'lta_planned_inspection' => Yii::t('report', 'lta_planned_inspection'),
            'safety_personnel_inspection' => Yii::t('report', 'safety_personnel_inspection'),
            'electrical_inspection' => Yii::t('report', 'electrical_inspection'),
            'ra_description' => Yii::t('report', 'ra_description'),
            'occupational_health' => Yii::t('report', 'occupational_health'),
        );
    }
     //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('report', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('report', 'STATUS_DISABLE'),
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
       //work_no
        //contractor_id
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id =:program_id ' : ' AND program_id =:program_id ';
            $params['program_id'] = $args['program_id'];
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

        //report_date
        if ($args['report_date'] != '') {
            $condition.= ( $condition == '') ? ' report_date =:report_date ' : ' AND report_date =:report_date ';
            $params['report_date'] = $args['report_date'];
        }

        //var_dump($condition);
        $total_num = Report::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();
        
        if ($_REQUEST['q_order'] == '') {
            $order = 'id DESC';
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

        $rows = Report::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
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
            $r['msg'] = Yii::t('report', 'Error Chemical is exist').'  '.$s[0]['contractor_name'].'.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('report', 'Error Chemical_id is exist');
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
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('report', 'Add Chemical'), self::insertLog($model));
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
                $r['msg'] = Yii::t('report', 'Error Chemical_id is exist');
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
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('report', 'Edit Chemical'), self::updateLog($model));
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
            $r['msg'] = Yii::t('report', 'Error Chemical_id is null');
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
            $r['msg'] = Yii::t('report', 'Error chemical is in program').$content.Yii::t('report', 'Error chemical do not');
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
            'report'  =>  $chemical,
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
            $order = 'id DESC';
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
