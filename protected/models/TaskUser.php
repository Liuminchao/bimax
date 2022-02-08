<?php

/**
 * 项目信息管理
 * @author LiuMinchao
 */
class TaskUser extends CActiveRecord {
    
     public function tableName() {
        return 'bac_task_user';
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
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
           // 'work_date' => Yii::t('task', 'work_date'),
        );
    }
    
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();

        //Task_id
        if ($args['task_id'] != '') {
            $condition.= ( $condition == '') ? ' task_id=:task_id' : ' AND task_id=:task_id';
            $params['task_id'] = $args['task_id'];
        }
//        //Program Name
//        if ($args['program_name'] != '') {
//            $condition.= ( $condition == '') ? ' program_name LIKE :program_name' : ' AND program_name LIKE :program_name';
//            $params['program_name'] = '%' . $args['program_name'] . '%';
//        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
//        //Add Operator
//        if ($args['add_operator'] != '') {
//            $condition.= ( $condition == '') ? ' add_operator=:add_operator' : ' AND add_operator=:add_operator';
//            $params['add_operator'] = $args['add_operator'];
//        }
//        //Status
//        if ($args['status'] != '') {
//            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
//            $params['status'] = $args['status'];
//        }
//        //father_proid
//        if ($args['father_proid'] != '') {
//            $condition.= ( $condition == '') ? ' father_proid=:father_proid' : ' AND father_proid=:father_proid';
//            $params['father_proid'] = $args['father_proid'];
//        }
//        //project_type
//        if ($args['project_type'] != '') {
//            if($args['project_type'] == 'MC')
//                $condition.= ( $condition == '') ? ' father_proid=0' : ' AND father_proid=0';
//            elseif($args['project_type'] == 'SC')
//                $condition.= ( $condition == '') ? ' father_proid<>0' : ' AND father_proid<>0';
//        }

        $total_num = Program::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'task_id desc';
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
        $rows = Task::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //返回项目中的成员
    public static function userListByRole($program_id){
        if (Yii::app()->language == 'zh_CN'){
            $role_name = "role_name";
            $team_name = "team_name";
        }
        else{
            $role_name = "role_name_en";
            $team_name = "team_name_en";
        }
        
        $sql = 'select c.*, b.'.$role_name.' as role_name, b.team_name_en as team_id, b.'.$team_name.' as team_name
                from (
                SELECT a.user_id, a.user_name, a.role_id, a.nation_type
                  FROM  (select user_id from bac_program_user_q where program_id = :program_id and check_status= 11) puser
                LEFT JOIN bac_staff a ON a.user_id = puser.user_id) c
                  LEFT JOIN bac_role b
                    on c.role_id = b.role_id
                 order by b.sort_id, c.user_id';
                 
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
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
    
    //按日期查询任务中成员
    public static function myUserList($task_id) {
        $sql = "SELECT a.user_id,b.user_name FROM bac_task_user  a,bac_staff b  WHERE  a.user_id =b.user_id and a.task_id=:task_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":task_id", $task_id, PDO::PARAM_INT);
        //$command->bindParam(":work_date", $date, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }
        return $rs;
    }
    //根据开始和结束时间戳计算时间段
    public static function changeDay($args){
        $tmp=array();
         //获取当前日期
        $date= date('Y-m-d',time());
        $now_time = strtotime($date);
        $plan_start_time = $args['plan_start_time'];
        $plan_end_time = $args['plan_end_time'];
        var_dump($plan_end_time);
        var_dump($now_time);
        exit;
        if ($now_time > $plan_end_time) {
            $r['msg'] = Yii::t('task', 'error_task_date_is_end');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        if ($now_time < $plan_start_time) {
            $r['msg'] = Yii::t('task', 'error_task_date_is_start');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        if($now_time<=$plan_start_time){
            for($i=$plan_start_time;$i<=$plan_end_time;$i+=(24*3600)){
                //$tmp['timeStampList'][]=$i;
                $tmp[]=date('Y-m-d',$i);
            }
        }else if($now_time<=$plan_end_time){
            for($i=$now_time;$i<=$plan_end_time;$i+=(24*3600)){
                //$tmp['timeStampList'][]=$i;
                $tmp[]=date('Y-m-d',$i);
            }
        }else{
            $tmp[] = array();
        }
        return $tmp;

    }
    
    //设置任务成员
    public static function updateTaskUser($args) {

        //判断任务结束日期是否已经早于当天日期
//        if (empty($tmp[0])) {
//            $r['msg'] = Yii::t('task', 'error_task_date_is_end');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
                
        if (empty($args['sc_list'])) {
            //var_dump(11111111111111111);
            $r['msg'] = Yii::t('task', 'error_task_user_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        var_dump($args);
//        exit;
        try { 
            $task_id = $args['task_id'];
            $program_id = $args['program_id'];
            $contractor_id = $args['contractor_id'];
//            $work_date = Utils::DateToCn($args['work_date']);
//            
//            //将某一天的旧数据删除
//            $date= date('Y-m-d',time());
            $old_rs = TaskUser::model()->findAll(
                array(
                    'select'=>array('user_id'),
                    'condition' => 'task_id=:task_id',
                    'params' => array(':task_id'=>$task_id),
                )
            );

            $old_list = array();
            foreach((array)$old_rs as $key => $row){
                $old_list[] = $row['user_id'];
            }
            $new_list = (array)$args['sc_list'];
            
            $del_list = array_diff($old_list,$new_list);//取消勾选数据
            $add_list = array_diff($new_list,$old_list);//新添加数据
                        
                //删掉数据
            if(!empty($del_list)){

                $del_str = "'".implode("','", $del_list)."'";//var_dump($del_str);
                //$date_str = "'".implode("','", $tmp)."'";
                $sql = "DELETE FROM bac_task_user WHERE task_id =:task_id and user_id in (".$del_str.")";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":task_id", $task_id, PDO::PARAM_STR);
                $rs = $command->execute();
                    
            }
            
                //添加新数据
            if(!empty($add_list)){
            //foreach($tmp as $key => $date){ 
                foreach ($add_list as $key => $id) {
                    $sql = 'INSERT INTO bac_task_user(program_id,contractor_id,user_id,task_id) VALUES(:program_id,:contractor_id,:user_id,:task_id)';
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                    $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                    $command->bindParam(":user_id", $id, PDO::PARAM_STR);
                    $command->bindParam(":task_id", $task_id, PDO::PARAM_STR);
                    //$command->bindParam(":work_date", $date, PDO::PARAM_STR);
                    $rs = $command->execute();
                }
                   // }
                
//            }else{  //重新指定人员
//                    if(!empty($new_list)){
//                        foreach($tmp as $key => $date){ 
//                            foreach ($new_list as $key => $id) {
//                                $sql = 'INSERT INTO bac_task_user(program_id,contractor_id,user_id,task_id,work_date) VALUES(:program_id,:contractor_id,:user_id,:task_id,:work_date)';
//                                $command = Yii::app()->db->createCommand($sql);
//                                $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
//                                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
//                                $command->bindParam(":user_id", $id, PDO::PARAM_STR);
//                                $command->bindParam(":task_id", $task_id, PDO::PARAM_STR);
//                                $command->bindParam(":work_date", $date, PDO::PARAM_STR);
//    
//                                $rs = $command->execute();
//                            }
//                        }
//                    }
            }
            
            //OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project_user', 'Edit Proj'), self::updateLog($model));
            $r['msg'] = Yii::t('common', 'success_set');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //根据任务id查找成员
    public static function taskuserList($args){
        
        $sql = "SELECT a.user_id,a.task_id,b.user_name FROM bac_task_user a LEFT JOIN bac_staff b ON a.user_id=b.user_id WHERE a.program_id=:program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $args['program_id'], PDO::PARAM_INT);
        //$command->bindParam(":work_date", $date, PDO::PARAM_INT);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['task_id']][] = $row['user_name'];
            }
        }
        return $rs;
    }
    
    
    
    //根据任务id查找任务开始至结束日期
    public static function taskdateList($task_id,$date){
        
        $sql = "SELECT user_id,work_date FROM bac_task_user WHERE task_id='".$task_id."' and work_date = '".$date."'";
        $command = Yii::app()->db->createCommand($sql);
       
        $rows = $command->queryAll();
        
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                 $rs[$row['work_date']] = $row['work_date'];   
            }
        }
        return $rs;
    }
}