<?php

/**
 * 角色管理
 * @author LiuXiaoyuan
 */
class Role extends CActiveRecord {

    //承包商类型
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包
    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_role';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role_id' => Yii::t('sys_role', 'role_id'),
            'contractor_type' => Yii::t('sys_role', 'contractor_type'),
            'role_name' => Yii::t('sys_role', 'role_name'),
            'role_name_en' => Yii::t('sys_role', 'role_name_en'),
            'team_name' => Yii::t('sys_role', 'team_name'),
            'team_name_en' => Yii::t('sys_role', 'team_name_en'),
            'sort_id' => Yii::t('sys_role', 'order'),
            'status' => Yii::t('sys_role', 'status'),
            'record_time' => Yii::t('sys_role', 'record_time'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }

    //承包商类型
    public static function contractorTypeText($key = null) {
        $rs = array(
            self::CONTRACTOR_TYPE_MC => Yii::t('sys_role', 'CONTRACTOR_TYPE_MC'),
            self::CONTRACTOR_TYPE_SC => Yii::t('sys_role', 'CONTRACTOR_TYPE_SC'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 添加角色日志描述
     * @param type $model
     * @return type
     */
    public static function insertLog($model) {
        return array(
            Yii::t('sys_role', 'role_id') => $model->role_id,
            Yii::t('sys_role', 'contractor_type') => self::contractorTypeText($model->contractor_type),
            Yii::t('sys_role', 'role_name') => $model->role_name,
            Yii::t('sys_role', 'role_name_en') => $model->role_name_en,
            Yii::t('sys_role', 'team_name') => $model->team_name,
            Yii::t('sys_role', 'team_name_en') => $model->team_name_en,
            Yii::t('sys_role', 'order') => $model->sort_id,
            Yii::t('sys_role', 'status') => self::statusText($model->status),
            Yii::t('sys_role', 'record_time') => $model->record_time,
        );
    }

    /**
     * 修改角色日志描述
     * @param type $model
     * @return type
     */
    public static function updateLog($model) {
        return array(
            Yii::t('sys_role', 'role_id') => $model->role_id,
            Yii::t('sys_role', 'contractor_type') => self::contractorTypeText($model->contractor_type),
            Yii::t('sys_role', 'role_name') => $model->role_name,
            Yii::t('sys_role', 'role_name_en') => $model->role_name_en,
            Yii::t('sys_role', 'team_name') => $model->team_name,
            Yii::t('sys_role', 'team_name_en') => $model->team_name_en,
            Yii::t('sys_role', 'order') => $model->sort_id,
            Yii::t('sys_role', 'status') => self::statusText($model->status),
        );
    }

    /**
     * 删除角色日志描述
     * @param type $model
     * @return type
     */
    public static function deleteLog($model) {
        return array(
            Yii::t('sys_role', 'role_id') => $model->role_id,
            Yii::t('sys_role', 'contractor_type') => self::contractorTypeText($model->contractor_type),
            Yii::t('sys_role', 'role_name') => $model->role_name,
            Yii::t('sys_role', 'role_name_en') => $model->role_name_en,
            Yii::t('sys_role', 'team_name') => $model->team_name,
            Yii::t('sys_role', 'team_name_en') => $model->team_name_en,
        );
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

        //Role
        if ($args['role_id'] != '') {
            $condition.= ( $condition == '') ? ' role_id=:role_id' : ' AND role_id=:role_id';
            $params['role_id'] = $args['role_id'];
        }
        //Contractor Type
       if ($args['contractor_type'] != '') {
      //      $condition.= ( $condition == '') ? ' contractor_type=:contractor_type' : ' AND contractor_type=:contractor_type';
            $params['contractor_type'] = $args['contractor_type'];
        }
        if (Yii::app()->language == 'zh_CN') {
            //Role Name
            if ($args['role_name'] != '') {
                $condition.= ( $condition == '') ? ' role_name LIKE :role_name' : ' AND role_name LIKE :role_name';
                $params['role_name'] = '%' . $args['role_name'] . '%';
            }
        } else if (Yii::app()->language == 'en_US') {
            //Role Name En
            if ($args['role_name'] != '') {
                $condition.= ( $condition == '') ? ' role_name_en LIKE :role_name_en' : ' AND role_name_en LIKE :role_name_en';
                $params['role_name_en'] = '%' . $args['role_name'] . '%';
            }
        }
        //Teamid
        if ($args['teamid'] != '') {
            $condition.= ( $condition == '') ? ' team_name_en=:teamid' : ' AND team_name_en=:teamid';
            $params['teamid'] = $args['teamid'];
        }
        
        //Team Name En
        if ($args['team_name_en'] != '') {
            $condition.= ( $condition == '') ? ' team_name_en=:team_name_en' : ' AND team_name_en=:team_name_en';
            $params['team_name_en'] = $args['team_name_en'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = Role::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'sort_id ASC';
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
        $rows = Role::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    //查询角色分组
    public static function roleTeamList(){
        if (Yii::app()->language == 'zh_CN')
            $field = "team_name";
        else
            $field = "team_name_en";
            
        $sql = "SELECT team_name_en as teamid, ".$field." as teamname FROM bac_role WHERE status=00 group by ".$field." order by sort_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['teamid']] = $row['teamname'];
            }
        }
//        $rs['No'] = '-'.Yii::t('sys_role', 'team_name').'-';
        return $rs;
        
    }
    
    public static function roleListByTeam($team=''){
        if (Yii::app()->language == 'zh_CN')
            $field = "role_name";
        else
            $field = "role_name_en";
            
        $sql = "SELECT role_id, ".$field." as role_name FROM bac_role WHERE status=00";
        if($team <> '')
            $sql .= " and team_name_en='".$team."'";
        $sql .= "  order by sort_id";//var_dump($sql);
        
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']] = $row['role_name'];
            }
        }
        return $rs;
    }
    
    //角色列表
    public static function roleList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT role_id,role_name FROM bac_role WHERE status=00 order by sort_id";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT role_id,role_name_en FROM bac_role WHERE status=00  order by team_name";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name_en'];
                }
            }
        }else{
            $sql = "SELECT role_id,role_name_en FROM bac_role WHERE status=00  order by team_name";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name_en'];
                }
            }
        }

        return $rs;
    }

    //团队列表
    public static function teamList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT role_id,team_name FROM bac_role WHERE status=00 order by sort_id";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['team_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT role_id,team_name_en FROM bac_role WHERE status=00  order by sort_id";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['team_name_en'];
                }
            }
        }



        return $rs;
    }


    //角色列表(中英文换行)
    public static function roleallList() {

        $sql = "SELECT role_id,role_name,role_name_en FROM bac_role WHERE status=00 order by sort_id";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name_en'].'<br>'.$row['role_name'];
                }
            }

        return $rs;
    }

    //角色列表(中英文换行)
    public static function rolelnList() {

        $sql = "SELECT role_id,role_name,role_name_en FROM bac_role WHERE status=00 order by sort_id";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']]['role_name_en'] = $row['role_name_en'];
                $rs[$row['role_id']]['role_name'] = $row['role_name'];
            }
        }

        return $rs;
    }

    //角色列表(中英文不换行)
    public static function rolebrList() {

        $sql = "SELECT role_id,role_name,role_name_en FROM bac_role WHERE status=00 order by sort_id";
        $command = Yii::app()->db->createCommand($sql);

        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_id']] = $row['role_name_en'].' '.$row['role_name'];
            }
        }

        return $rs;
    }
    //select2角色列表
    public static function roleselectList() {

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT role_id,role_name FROM bac_role WHERE status=00 order by sort_id";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            $i = 1;
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$i]['id'] = $row['role_id'];
                    $rs[$i]['name'] = $row['role_name'];
                    $i++;
                }
            }
            $rs[0]['id'] = 'null';
            $rs[0]['name'] = '无';
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT role_id,role_name_en FROM bac_role WHERE status=00  order by sort_id";
            $command = Yii::app()->db->createCommand($sql);

            $rows = $command->queryAll();
            $i = 1;
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$i]['id'] = $row['role_id'];
                    $rs[$i]['name'] = $row['role_name_en'];
                    $i++;
                }
            }
            $rs[0]['id'] = 'null';
            $rs[0]['name'] = 'No';
        }



        return $rs;
    }
    
    
    //角色列表
    public static function roleByTypeList($type) {
        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT role_id,role_name FROM bac_role WHERE status='00' ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":contractor_type", $type, PDO::PARAM_STR);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT role_id,role_name_en FROM bac_role WHERE status='00' AND contractor_type=:contractor_type";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":contractor_type", $type, PDO::PARAM_STR);

            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['role_id']] = $row['role_name_en'];
                }
            }
        }


        return $rs;
    }
    
    //角色列表
    public static function roleidListByTeam(){
        if (Yii::app()->language == 'zh_CN')
            $field = "role_name";
        else
            $field = "role_name_en";
            
        $sql = "SELECT role_id, ".$field." as role_name FROM bac_role WHERE status=00";
        
        $sql .= "  order by sort_id";//var_dump($sql);
        
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['role_name']] = $row['role_id'];
            }
        }
        return $rs;
    }

    //插入数据
    public static function insertRole($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['role_id'] == '') {
            $r['msg'] = Yii::t('sys_role', 'role_id is not null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = Role::model()->count('role_id=:role_id', array('role_id' => $args['role_id']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('sys_role', 'role_id is not exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new Role('create');
            $model->role_id = $args['role_id'];
            $model->contractor_type = $args['contractor_type'];
            $model->role_name = $args['role_name'];
            $model->role_name_en = $args['role_name_en'];
            $model->team_name = $args['team_name'];
            $model->team_name_en = $args['team_name_en'];
            $model->sort_id = $args['sort_id'];
            $model->status = Role::STATUS_STOP;
            $model->record_time = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result) {

                OperatorLog::savelog(OperatorLog::MODULE_ID_ROLE, Yii::t('sys_role', 'AddRole'), self::insertLog($model));
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
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
    public static function updateRole($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['role_id'] == '') {
            $r['msg'] = Yii::t('common', 'error_role_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Role::model()->findByPk($args['role_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->role_name = $args['role_name'];
            $model->role_name_en = $args['role_name_en'];
            $model->team_name = $args['team_name'];
            $model->team_name_en = $args['team_name_en'];
            $model->sort_id = $args['sort_id'];
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_ROLE, Yii::t('sys_role', 'EditRole'), self::updateLog($model));
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

    //启用角色
    public static function startRole($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('common', 'error_role_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Role::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = Role::STATUS_NORMAL;
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_ROLE, Yii::t('sys_role', 'StartRole'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_start');
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

    //停用角色
    public static function stopRole($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('common', 'error_role_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Role::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = Role::STATUS_STOP;
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_ROLE, Yii::t('sys_role', 'StopRole'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_stop');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_stop');
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
    public static function deleteRole($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('common', 'error_role_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Role::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        $sql = 'DELETE FROM bac_role WHERE role_id=:role_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":role_id", $id, PDO::PARAM_INT);

        $rs = $command->execute();

        if ($rs == 0) {
            OperatorLog::savelog(OperatorLog::MODULE_ID_ROLE, Yii::t('sys_role', 'DeleteRole'), self::deleteLog($model));
            $r['msg'] = Yii::t('common', 'error_record_is_not_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
        } else {
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

}
