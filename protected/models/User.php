<?php

class User extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_user';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => Yii::t('comp_user', 'User_id'),
            'user_name' => Yii::t('comp_user', 'User_name'),
            'user_phone' => Yii::t('comp_user', 'User_phone'),
            'role_id' => Yii::t('comp_user', 'Role_id'),
            'title_id' => Yii::t('comp_user', 'Title_id'),
            'primary_email' => Yii::t('comp_user', 'Primary_email'),
            'team_id' => Yii::t('comp_user', 'Team_id'),
            'status' => Yii::t('comp_user', 'Status'));
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
            self::STATUS_NORMAL => Yii::t('comp_user', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('comp_user', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //部门
    public static function teamText($key = null) {

        $rs = array('Management ' => '管理层',
            'Safety Team' => '安全团队',
            'Sturcuture Team' => '结构团队',
            'M&E Team' => '机电团队',
            'Architectural' => '土木团队');
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
    public static function insertLog($model) {
        $log = array(
            $model->getAttributeLabel('user_name') => $model->user_name,
            $model->getAttributeLabel('user_phone') => $model->user_phone,
            $model->getAttributeLabel('role_id') => $model->role_id,
            $model->getAttributeLabel('title_id') => $model->title_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
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
            $model->getAttributeLabel('user_name') => $model->user_name,
            $model->getAttributeLabel('user_phone') => $model->user_phone,
            $model->getAttributeLabel('role_id') => $model->role_id,
            $model->getAttributeLabel('title_id') => $model->title_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
        );
        return $log;
    }

    public static function logoutLog($model) {
        $log = array(
            $model->getAttributeLabel('user_name') => $model->user_name,
            $model->getAttributeLabel('user_phone') => $model->user_phone,
            $model->getAttributeLabel('role_id') => $model->role_id,
            $model->getAttributeLabel('title_id') => $model->title_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
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

        $condition = '1=1 ';
        $params = array();
        //User_name
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' user_name LIKE :user_name' : ' AND user_name LIKE :user_name';
            $params['user_name'] = '%' . $args['user_name'] . '%';
        }
        //user_phone
        if ($args['user_phone'] != '') {
            $condition.= ( $condition == '') ? ' user_phone=:user_phone' : ' AND user_phone=:user_phone';
            $params['user_phone'] = $args['user_phone'];
        }

        //User_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        if ($args['contractor_type'] != '') {
            $condition.= ( $condition == '') ? ' 1=1 ' : ' AND contractor_id in (select contractor_id from bac_contractor  where contractor_type=:contractor_type)';
            $params['contractor_type'] = $args['contractor_type'];
        }
        $total_num = User::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'user_id';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = User::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertUser($args) {
        //form id　注意为model的数据库字段
        if ($args['user_phone'] == '') {
            $r['msg'] = Yii::t('comp_user', 'Error User_name is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $exist_data = User::model()->count('user_phone=:user_phone', array('user_phone' => $args['user_phone']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('comp_user', 'Error User_phone is exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            $model = new User('create');

            if (count($args['role_id']) != 0) {
                $args['role_id'] = implode(",", $args['role_id']);
            }

            $model->user_name = $args['user_name'];
            $model->user_phone = $args['user_phone'];

            $model->primary_email = $args['primary_email'];
            $model->team_id = $args['team_id'];
            $model->role_id = $args['role_id'];
            $model->login_passwd = md5('123456');

            //var_dump($args);
            $model->contractor_id = Yii::app()->user->getState('contractor_id');

            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_user', 'Add User'), self::insertLog($model));
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
    public static function updateUser($args) {

//         foreach ($args as $key => $value) {
//             $args[$key] = trim($value);
//         }
        if ($args['user_id'] == '') {
            $r['msg'] = Yii::t('com_User', 'Error user_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = User::model()->find('user_id=:user_id', array(':user_id' => $id));

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            if (count($args['role_id']) != 0) {
                $args['role_id'] = implode(",", $args['role_id']);
            }
            $model->user_name = $args['user_name'];
            $model->primary_email = $args['primary_email'];
            $model->team_id = $args['team_id'];
            $model->role_id = $args['role_id'];
            $result = $model->save();

            //记录日志
            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_user', 'Edit User'), self::updateLog($model));

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

    public static function logoutUser($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('comp_user', 'Error User_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //var_dump($id);
        $model = User::model()->find('user_id=:user_id', array(':user_id' => $id));
        if ($model == null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            // $model->status = self::STATUS_DISABLE;
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                //$result = $model->save();
                $sql = "delete from bac_user where user_id=:user_id";
                $command = $connection->createCommand($sql);
                $command->bindParam(":user_id", $id, PDO::PARAM_STR);
                $command->execute();
            } catch (Exception $e) {
                $transaction->rollback();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }


            OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_user', 'Logout User'), self::logoutLog($model));

            $r['msg'] = Yii::t('common', 'success_logout');
            $r['status'] = 1;
            $r['refresh'] = true;
            $transaction->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 返回所有可用的人员
     * @return type
     */
    public static function userList($contractor_id, $type='K-V') {
        $sql = "SELECT user_id,user_name,role_id FROM bac_staff WHERE contractor_id=:contractor_id AND status=0";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        
        if($type <> 'K-V')
            return $rows;
        
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }

        return $rs;
    }

    /**
     * 返回所有的人员
     * @return type
     */
    public static function userAllList() {
        $sql = "SELECT user_id,user_name FROM bac_user";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }

        return $rs;
    }
    
    public static function userListByTypeRole($contractor_id,$subcon_type){
        if (Yii::app()->language == 'zh_CN'){
            $role_name = "role_name";
            $team_name = "team_name";
        }
        else{
            $role_name = "role_name_en";
            $team_name = "team_name_en";
        }
        $Team_name = Subcon::subconByTypeList($subcon_type);

        $Team = $Team_name[0]['team'];
//                var_dump($team);
//        exit;
        $sql = 'select a.*, b.'.$role_name.' as role_name, b.team_name_en as team_id, b.'.$team_name.' as team_name
                from (
                SELECT user_id, user_name, role_id, nation_type
                  FROM bac_staff a
                WHERE a.contractor_id=:contractor_id AND a.status=0) a
                  LEFT JOIN bac_role b
                    on a.role_id = b.role_id
                  WHERE b.status = 00 and b.team_name LIKE "%'.$Team.'%" OR b.team_name_en = "Main-Con Management" OR b.team_name_en = "Sub-Con Management"
                 order by b.sort_id, a.user_id';
                 
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
//        $command->bindParam(":team", $team, PDO::PARAM_INT);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit
        foreach((array)$rows as $key => $row){
            $team[$row['team_id']] = $row['team_name'];
            $role[$row['team_id']][$row['role_id']] = $row['role_name'];
            $staff[$row['role_id']][$row['user_id']] = $row['user_name'];
            $nation[$row['user_id']] = $row['nation_type'];
        }
        
        
        $rs = array(
            'team'  =>  $team,
            'role'  =>  $role,
            'staff' =>  $staff,
            'nation' => $nation,
        );
        return $rs;
    }
    public static function userListByRole($contractor_id,$root_proid){

        if (Yii::app()->language == 'zh_CN'){
            $role_name = "role_name";
            $team_name = "team_name";
        }
        else{
            $role_name = "role_name_en";
            $team_name = "team_name_en";
        }
        //总包或分包中只能有一个已入场的人员
//        $sql = 'select a.*, b.'.$role_name.' as role_name, b.team_name_en as team_id, b.'.$team_name.' as team_name
//                from (
//                SELECT user_id, user_name, role_id, nation_type
//                  FROM bac_staff a
//                WHERE a.contractor_id=:contractor_id AND a.status=0 AND a.user_id NOT IN (SELECT
//                distinct(user_id)
//            FROM
//                bac_program_user
//            WHERE
//                root_proid = :root_proid and contractor_id = :contractor_id and check_status <> 21
//            )) a
//                  LEFT JOIN bac_role b
//                    on a.role_id = b.role_id
//                 order by b.sort_id, a.user_id';
        
                $sql = 'select a.*, b.'.$role_name.' as role_name, b.team_name_en as team_id, b.'.$team_name.' as team_name
                from (
                SELECT user_id, user_name, role_id, nation_type
                  FROM bac_staff a
                WHERE a.contractor_id=:contractor_id AND a.status=0 ) a
                  LEFT JOIN bac_role b
                    on a.role_id = b.role_id
                WHERE b.status = 00    
                 order by b.sort_id, a.user_id';
                 
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
//        var_dump($sql);
//        var_dump($rows);
//        exit;
        foreach((array)$rows as $key => $row){
            $team[$row['team_id']] = $row['team_name'];
            $role[$row['team_id']][$row['role_id']] = $row['role_name'];
            $staff[$row['role_id']][$row['user_id']] = $row['user_name'];
            $nation[$row['user_id']] = $row['nation_type'];
        }
        
        
        $rs = array(
            'team'  =>  $team,
            'role'  =>  $role,
            'staff' =>  $staff,
            'nation' => $nation,
        );
        return $rs;
    }

    public static function operatorListByRole($contractor_id){
        $cond = "a.contractor_id = '".$contractor_id."' ";
        if (Yii::app()->language == 'zh_CN'){
            $role_name = "role_name";
            $team_name = "team_name";
        }
        else{
            $role_name = "role_name_en";
            $team_name = "team_name_en";
        }

        $sql = 'select a.*, b.'.$role_name.' as role_name, b.team_name_en as team_id, b.'.$team_name.' as team_name,c.short_name
                from (
                SELECT user_id, user_name, role_id, nation_type, contractor_id
                  FROM bac_staff a
                WHERE '.$cond.' AND a.status=0 ) a
                  LEFT JOIN bac_role b
                    on a.role_id = b.role_id
                  LEFT JOIN bac_contractor c
                    on a.contractor_id = c.contractor_id
                WHERE b.status = 00    
                 order by b.sort_id, a.user_id';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
//        var_dump($sql);
//        var_dump($rows);
//        exit;
        foreach((array)$rows as $key => $row){
            $team[$row['team_id']] = $row['team_name'];
            $role[$row['team_id']][$row['role_id']] = $row['role_name'];
            $staff[$row['role_id']][$row['user_id']] = $row['user_name'];
            $nation[$row['user_id']] = $row['nation_type'];
            $short[$row['user_id']] = $row['short_name'];
        }


        $rs = array(
            'team'  =>  $team,
            'role'  =>  $role,
            'staff' =>  $staff,
            'nation' => $nation,
            'short' => $short,
        );
        return $rs;
    }
}
