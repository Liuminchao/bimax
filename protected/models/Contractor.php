<?php

/**
 * 总包公司管理
 * @author yaohaiyan
 */
class Contractor extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    
    /*const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包*/
    
    const CONTRACTOR_PREFIX = 'CT';   //承包商编号前缀
    
    const OPERATOR_PPRE = 'admin'; //创建企业操作员前缀

    public $operator_name; //管理员姓名
    public $operator_phone; //管理员联系电话
    public $operator_id; //管理员登录账号

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_contractor';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'contractor_id' => Yii::t('comp_contractor', 'Contractor_id'),
            'contractor_name' => Yii::t('comp_contractor', 'Contractor_name'),
            'company_sn'    =>  Yii::t('comp_contractor', 'company_sn'),
            'short_name' => Yii::t('comp_contractor','short_name'),
            'remark' => Yii::t('comp_contractor','remark'),
            'link_person' => Yii::t('comp_contractor', 'link_person'),
            'link_phone' => Yii::t('comp_contractor', 'link_phone'),
            'company_adr' => Yii::t('comp_contractor', 'Company_adr'),
            'status' => Yii::t('comp_contractor', 'Status'),
            'operator_id' => Yii::t('sys_operator', 'Operator'),
            'operator_name' => Yii::t('comp_contractor', 'operator_name'),
            'operator_phone' => Yii::t('comp_contractor', 'operator_phone'),
            'file_space' => Yii::t('comp_contractor', 'file_space'),
            'mc_pro_cnt' => Yii::t('comp_contractor', 'mc_pro_cnt'),
            'project' => Yii::t('proj_project', 'program_name'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Operator the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('comp_contractor', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('comp_contractor', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_DISABLE => ' label-danger', //已注销
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 添加日志详细
     * @param type $model
     * @return array
     */
    public static function insertLog($model, $contractor_id) {
        $log = array(
            $model->getAttributeLabel('contractor_id') => $contractor_id,
            $model->getAttributeLabel('contractor_name') => $model->contractor_name,
            $model->getAttributeLabel('company_sn') => $model->company_sn,
            $model->getAttributeLabel('link_person') => $model->link_person,
            $model->getAttributeLabel('link_phone') => $model->link_phone,
            $model->getAttributeLabel('company_adr') => $model->company_adr
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
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('contractor_name') => $model->contractor_name,
            $model->getAttributeLabel('company_sn') => $model->company_sn,
            $model->getAttributeLabel('link_person') => $model->link_person,
            $model->getAttributeLabel('link_phone') => $model->link_phone,
            $model->getAttributeLabel('company_adr') => $model->company_adr
        );
        return $log;
    }

    public static function logoutLog($model) {
        $log = array(
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('contractor_name') => $model->contractor_name,
        );
        return $log;
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
        //contractor_name
        if ($args['contractor_name'] != '') {
            $condition.= ( $condition == '') ? ' contractor_name LIKE :contractor_name' : ' AND contractor_name LIKE :contractor_name';
            $params['contractor_name'] = '%' . $args['contractor_name'] . '%';
        }
        //contractor_type
        if ($args['contractor_type'] != '') {
            $condition.= ( $condition == '') ? ' contractor_type=:contractor_type' : ' AND contractor_type=:contractor_type';
            $params['contractor_type'] = $args['contractor_type'];
        }

        //contractor_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        $total_num = Contractor::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'contractor_id desc';
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

        $rows = Contractor::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    public static function queryByName($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        //contractor_name
        if ($args['contractor_name'] != '') {
            $condition.= ( $condition == '') ? ' contractor_name = :contractor_name' : ' AND contractor_name = :contractor_name';
            $params['contractor_name'] = $args['contractor_name'];
        }

        $total_num = Contractor::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();


        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = Contractor::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertContractor($args) {
        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        //form id　注意为model的数据库字段
        //contractor_name
        if ($args['contractor_name'] == '') {
            $r['msg'] = Yii::t('comp_contractor', 'Error contractor_name is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $exist_data = Contractor::model()->count('contractor_name=:contractor_name', array('contractor_name' => $args['contractor_name']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('comp_contractor', 'Error contractor_name is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        //company_sn
        if ($args['company_sn'] == '') {
            $r['msg'] = Yii::t('comp_contractor', 'Error company_sn is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['short_name'] == '') {
            $r['msg'] = Yii::t('comp_contractor', 'Error short_name is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        if ($args['src'] == '') {
//            $r['msg'] = Yii::t('comp_contractor', 'Error company_logo is null');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }

        $exist_data = Contractor::model()->count('company_sn=:company_sn', array('company_sn' => $args['company_sn']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('comp_contractor', 'Error company_sn is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $trans = Yii::app()->db->beginTransaction();
        try {
            $model = new Contractor('create');
            $model->contractor_name = addslashes($args['contractor_name']);
            if($args['short_name']){
                $model->short_name = addslashes($args['short_name']);
            }else{
                $model->short_name = addslashes($args['contractor_name']);
            }
            $model->contractor_type = $args['contractor_type'];
            $model->company_sn = $args['company_sn'];
            $model->company_adr = $args['company_adr'];
            $model->link_person = $args['link_person'];
            $model->link_phone = $args['link_phone'];
            //参数设置
            $params['pro_cnt'] = $args['pro_cnt'];
            $json_params = json_encode($params);
            $model->params = $json_params;

            $result = $model->save();
            //添加FACE客户端管理员
            $contractor_id = Yii::app()->db->lastInsertID;         
            $seq_id = sprintf('%05s', $contractor_id);         
            $operator_id = self::CONTRACTOR_PREFIX . $seq_id;
            $face['operator_id'] = $operator_id;
            $face['operator_type'] = Operator::TYPE_FACEFORM;
            $face['name'] = $args['operator_name'];
            $face['phone'] = $args['operator_phone'];          
            $face['role_id'] = Operator::ROLE_COMP;
            $face['contractor_id'] = $contractor_id;        
            $r = Operator::insertOperator($face);

            //添加企业存储空间
            StatsContractorStore::saveMaxSzie($contractor_id,$args['max_size']);

            //添加建筑公司管理员
            if($r['status']==1){
                //加入公司
                $result = Dms::NewProgram($contractor_id,addslashes($args['contractor_name']));

                $params['operator_id'] = $args['company_sn']; 
                $params['operator_type'] = Operator::TYPE_PLATFORM;
                $params['operator_role'] = Operator::admin;
                $params['name'] = $args['operator_name'];
                $params['phone'] = $args['operator_phone'];          
                $params['role_id'] = Operator::ROLE_COMP;
                $params['contractor_id'] = $contractor_id;     
                Operator::insertOperator($params);

                $menu_list = Menu::appMenuList();
                $status = '00';

                foreach($menu_list as $menu_id => $menu_name){
                    $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":operator_id", $params['operator_id'], PDO::PARAM_STR);
                    $command->bindParam(":menu_id", $menu_id, PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $rs = $command->execute();
                }

                $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
                $id = '105';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":operator_id", $params['operator_id'], PDO::PARAM_STR);
                $command->bindParam(":menu_id", $id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->execute();

                $s['contractor_id'] = $contractor_id;
                $project_list = Program::programAllList($s);
                $val = '1';
                $normal_status = '0';
                $record_time = date('Y-m-d H:i:s');
                foreach ($project_list as $k => $v){
                    $sql = 'INSERT INTO bac_operator_program(project_id,operator_id,flag,status,record_time) VALUES(:project_id,:operator_id,:flag,:status,:record_time)';
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":project_id", $v['project_id'], PDO::PARAM_INT);
                    $command->bindParam(":operator_id", $params['operator_id'], PDO::PARAM_INT);
                    $command->bindParam(":flag", $val, PDO::PARAM_STR);
                    $command->bindParam(":status", $normal_status, PDO::PARAM_STR);
                    $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                    $command->execute();
                }


            }

            //把公司logo移到正式服务器 并将字段存入数据库
            if($args['tmp_src']){
                $res = self::movePic($args['tmp_src'],$contractor_id);
                $c_model = Contractor::model()->findByPk($contractor_id);
                $c_model->remark = $res['src'];
                $re = $c_model->save();
            }


            $r['operator_id'] = $args['company_sn'];
            $r['contractor_id'] = $contractor_id;
            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_contractor', 'Add Contractor'), self::insertLog($model, $contractor_id));

                $r['msg'] = Yii::t('common', 'success_insert') . Yii::t('comp_contractor', 'user_name') . $args['company_sn'] . Yii::t('comp_contractor', 'passwd') . '123456';
                $r['status'] = 1;
                $r['refresh'] = true;
                $trans->commit();
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }

    //修改数据
    public static function updateContractor($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        if ($args['contractor_id'] == '') {
            $r['msg'] = Yii::t('com_contractor', 'Error contractor_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['short_name'] == '') {
            $r['msg'] = Yii::t('comp_contractor', 'Error short_name is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Contractor::model()->findByPk($args['contractor_id']);
      
        
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            
            //contractor_name
            if ($args['contractor_name'] <> $model->contractor_name) {
                $exist_data = Contractor::model()->count('contractor_name=:contractor_name', array('contractor_name' => $args['contractor_name']));
                if ($exist_data != 0) {
                    $r['msg'] = Yii::t('comp_contractor', 'Error contractor_name is exist');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
            }
            //company_sn
            if ($args['company_sn'] <> $model->company_sn) {
                $exist_data = Contractor::model()->count('company_sn=:company_sn', array('company_sn' => $args['company_sn']));
                if ($exist_data != 0) {
                    $r['msg'] = Yii::t('comp_contractor', 'Error company_sn is exist');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
            }
            
            $model->contractor_name = addslashes($args['contractor_name']);
            $model->contractor_type = $args['contractor_type'];
            $model->company_sn = $args['company_sn'];
            $model->company_adr = $args['company_adr'];
            $model->link_person = $args['link_person'];
            $model->link_phone = $args['link_phone'];
            if($args['short_name']){
                $model->short_name = addslashes($args['short_name']);
            }else{
                $model->short_name = addslashes($args['contractor_name']);
            }
            //参数设置
            $params['pro_cnt'] = $args['pro_cnt'];
            $json_params = json_encode($params);
            $model->params = $json_params;

            if($args['remark']){
                $model->remark = $args['remark'];
            }
            $result = $model->save();
            
            /*$params['link_person'] = $args['link_person'];
            $params['link_phone'] = $args['link_phone'];
            Operator::updateOperator($params);*/

            //记录日志
            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_contractor', 'Edit Contractor'), self::updateLog($model));

                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
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

    //移到正式文件夹下
    public static function movePic($file_src,$contractor_id){
//        $name = substr($file_src,35);
        $name = substr($file_src,38);
//        $conid = Yii::app()->user->getState('contractor_id');
        $upload_path = Yii::app()->params['upload_company_path'];
        $upload = $upload_path . '/' . $contractor_id . '/';
        if (!file_exists($upload)) {
            umask(0000);
            @mkdir($upload, 0777, true);
        }
        $upload_file = $upload.$name;
//            var_dump($name);exit;
        $file_name = explode('.',$name);
        //移动文件到指定目录下
        if (rename($file_src,$upload_file)) {
            $r['src'] = substr($upload_file,18);
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = "Error moving";
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        return $r;
    }

    public static function logoutContractor($id) {
        //return('aa');
        if ($id == '') {
            $r['msg'] = Yii::t('comp_contractor', 'Error contractor_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Contractor::model()->findByPk($id);

        if ($model == null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $cnt = Program::model()->count('status='.self::STATUS_NORMAL.' and contractor_id='.$id);
        //判断其下是否有未结项的项目，有的话不能结项
        if ($cnt <> 0) {
            $r['msg'] = Yii::t('proj_project', 'error_subproj_is_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
		try {
            $model->contractor_name .= '[del]';
            $model->company_sn .= '[del]';
            $model->status = self::STATUS_DISABLE;
            $result = $model->save();
            
            if ($result) {
                //操作员注销
                $opt_sql = "update bac_operator set status=1 and operator_id=concat(operator_id,'[del]') where contractor_id=:contractor_id";
                $command = Yii::app()->db->createCommand($opt_sql);
                $command->bindParam(":contractor_id", $id);
                $rs = $command->execute();
                
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_contractor', 'Logout Contractor'), self::logoutLog($model));

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
/*
        try {
            //  $model->status = self::STATUS_DISABLE;
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                //  $result = $model->save();
                $opt_sql = "delete from bac_contractor where contractor_id=:contractor_id";
                $command = $connection->createCommand($opt_sql);
                $command->bindParam(":contractor_id", $id);
                $rows = $command->execute();
                $operator_id = self::OPERATOR_PPRE . $id;
                Operator::model()->deleteByPk($operator_id);

                $opt_sql = "delete from bac_operator where contractor_id=:contractor_id";
                $command = $connection->createCommand($opt_sql);
                $command->bindParam(":contractor_id", $id);
                $rows = $command->execute();

                //var_dump($login_id);
                //Operator::model()->deleteByPk($login_id);


                $connection->createCommand("delete from bac_user where contractor_id=:contractor_id")->execute(array(':contractor_id' => $id));

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
            OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_contractor', 'Logout Contractor'), self::logoutLog($model));

            $r['msg'] = Yii::t('common', 'success_logout');
            $r['status'] = 1;
            $r['refresh'] = true;
			
			
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
*/	
    /**
     * 返回所有可用的公司
     * @return type
     */
    public static function compList() {
        $sql = "SELECT contractor_id,contractor_name FROM bac_contractor WHERE status=0 ORDER BY contractor_name asc";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }

    /**
     * 返回所有承包商（含停用的）
     * @return type
     */
    public static function compAllList() {
        $sql = "SELECT contractor_id,contractor_name FROM bac_contractor";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }

    /**
     * 返回所有分包公司
     * @return type
     */
    public static function scCompList() {
        $sql = "SELECT contractor_id,contractor_name FROM bac_contractor WHERE status=0 AND contractor_type='SC'";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }

    /**
     * 按总包商ID返回所有分包公司
     * @return type
     */
    public static function Mc_scCompList($args=array()) {
        $pro_model =Program::model()->findByPk($args['program_id']);
        if($pro_model->main_conid != $args['contractor_id']){
            $sql = "SELECT distinct b.contractor_id,b.contractor_name FROM bac_program a,bac_contractor b WHERE a.status = '00' and a.program_id = '".$args['program_id']."' and a.contractor_id = b.contractor_id ";
        }else{
            if($pro_model->root_proid != $args['program_id']){
                $sql = "SELECT distinct b.contractor_id,b.contractor_name FROM bac_program a,bac_contractor b WHERE a.status = '00' and a.add_conid = '".$args['contractor_id']."' and a.program_id = '".$args['program_id']."' and a.contractor_id = b.contractor_id ";
            }else{
                $sql = "SELECT distinct b.contractor_id,b.contractor_name FROM bac_program a,bac_contractor b WHERE a.status = '00' and a.add_conid = '".$args['contractor_id']."' and a.root_proid = '".$args['program_id']."' and a.contractor_id = b.contractor_id ";
            }
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }


    /**
     * 返回所有总包公司
     * @return type
     */
    public static function mcCompList() {
        $sql = "SELECT contractor_id,contractor_name FROM bac_contractor WHERE status=00 AND contractor_type='MC'";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }

    /**
     * 返回项目下得公司
     * @return type
     */
    public static function CompanyListByProgram($program_id) {
        $pro_model =Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT a.contractor_id,a.contractor_name FROM bac_contractor a,bac_program b WHERE b.status=00 AND a.contractor_id = b.contractor_id and b.root_proid = :root_proid";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":root_proid", $root_proid);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_id']] = $row['contractor_name'];
            }
        }

        return $rs;
    }

}
