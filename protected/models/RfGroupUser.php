<?php

/**
 * This is the model class for table "ptw_condition_list".
 *
 * The followings are the available columns in table 'ptw_condition_list':
 * @property string $condition_id
 * @property string $condition_name
 * @property string $condition_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwTypeList[] $ptwTypeLists
 * @author LiuXiaoyuan
 */
class RfGroupUser extends CActiveRecord
{
    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '9'; //停用

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'rf_group_user';
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id' => 'User Id',
            'contractor_name' => 'Contractor Name',
            'user_name' => 'User Name',
            'status' => 'Status',
            'record_time' => 'Record Time',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PtwCondition the static model class
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

        //Template Id
        if ($args['group_id'] != '') {
            $condition.= ( $condition == '') ? ' group_id=:group_id' : ' AND group_id=:group_id';
            $params['group_id'] = $args['group_id'];
        }

        //Stage Name
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' user_name=:user_name' : ' AND user_name=:user_name';
            $params['user_name'] = $args['user_name'];
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


        $total_num = RfGroupUser::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'id';
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
        $rows = RfGroupUser::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertDetail($json,$group_name,$program_id) {

        $list = explode('@',$json);
        foreach($list as $i => $j){
            $data = explode(',',$j);
            $args[$i]['contractor_id'] = $data[0];
            $con_model =Contractor::model()->findByPk($data[0]);
            $args[$i]['contractor_name'] = $con_model->contractor_name;
            $args[$i]['user_id'] = $data[1];
            $user_model =Staff::model()->findByPk($data[1]);
            $args[$i]['user_name'] = $user_model->user_name;
        }
        $rs['group_name'] = $group_name;
        $rs['program_id'] = $program_id;

        $r = RfGroup::insertGroup($rs);
        $group_id = $r['group_id'];
        $trans = Yii::app()->db->beginTransaction();
        try {
            if(count($args)>0){
                foreach($args as $i => $j){
                    $sub_sql = 'INSERT INTO rf_group_user(group_id,contractor_id,contractor_name,user_id,user_name) VALUES(:group_id,:contractor_id,:contractor_name,:user_id,:user_name);';
                    $record_time = date('Y-m-d H:i:s', time());
                    $status= '0';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":group_id", $group_id, PDO::PARAM_STR);
                    $command->bindParam(":contractor_id",$j['contractor_id'], PDO::PARAM_STR);
                    $command->bindParam(":contractor_name",$j['contractor_name'], PDO::PARAM_INT);
                    $command->bindParam(":user_id",$j['user_id'], PDO::PARAM_STR);
                    $command->bindParam(":user_name",$j['user_name'], PDO::PARAM_STR);
                    $rs = $command->execute();
                }
            }

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }

        return $r;
    }

    //修改数据
    public static function EditDetail($json,$group_id,$group_name,$program_id) {
        $list = explode('@',$json);
        foreach($list as $i => $j){
            $data = explode(',',$j);
            $args[$i]['contractor_id'] = $data[0];
            $con_model =Contractor::model()->findByPk($data[0]);
            $args[$i]['contractor_name'] = $con_model->contractor_name;
            $args[$i]['user_id'] = $data[1];
            $user_model =Staff::model()->findByPk($data[1]);
            $args[$i]['user_name'] = $user_model->user_name;
        }

        $rs['group_name'] = $group_name;
        $rs['group_id'] = $group_id;
//        $r = RfGroup::editGroup($rs);
//        $group_id = $r['group_id'];
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "update rf_group set group_name ='".$group_name."' where group_id = '".$group_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $re = $command->execute();
            $sql = "delete from rf_group_user where group_id = '".$group_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $re = $command->execute();
            foreach($args as $i => $j){
                $sub_sql = 'INSERT INTO rf_group_user(group_id,contractor_id,contractor_name,user_id,user_name) VALUES(:group_id,:contractor_id,:contractor_name,:user_id,:user_name);';
                $record_time = date('Y-m-d H:i:s', time());
                $status= '0';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":group_id", $group_id, PDO::PARAM_STR);
                $command->bindParam(":contractor_id",$j['contractor_id'], PDO::PARAM_STR);
                $command->bindParam(":contractor_name",$j['contractor_name'], PDO::PARAM_INT);
                $command->bindParam(":user_id",$j['user_id'], PDO::PARAM_STR);
                $command->bindParam(":user_name",$j['user_name'], PDO::PARAM_STR);
                $rs = $command->execute();
            }
            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }

        return $r;
    }

    //详情
    public static function detailList($group_id){

        $sql = "SELECT * FROM rf_group_user WHERE group_id = '".$group_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $i => $j){
                $rs[$i]['contractor_id'] = $j['contractor_id'];
                $rs[$i]['contractor_name'] = $j['contractor_name'];
                $rs[$i]['user_id'] = $j['user_id'];
                $rs[$i]['user_name'] = $j['user_name'];
            }
        }
        return $rs;
    }

    /**
     * 返回所有可用的人员
     * @return type
     */
    public static function userList($group_id) {
        $sql = "SELECT user_id,user_name FROM rf_group_user WHERE group_id=:group_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":group_id", $group_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        $r = array();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs['id'] = $row['user_id'];
                $rs['text'] = $row['user_name'];
                $r[] = $rs;
            }
        }
        return $r;
    }

    /**
     * 返回所属组
     * @return type
     */
    public static function findGroup($user_id) {
        $sql = "SELECT group_id FROM rf_group_user WHERE user_id=:user_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        $r = array();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $group_id = $row['group_id'];
            }
        }else{
            $group_id = 0;
        }
        return $group_id;
    }

    /**
     * 返回所属组
     * @return type
     */
    public static function findGroupByProgram($user_id,$program_id) {
        $sql = "SELECT a.group_name FROM rf_group a,rf_group_user b WHERE a.group_id=b.group_id and b.user_id=:user_id and a.project_id=:project_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        $r = array();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $group_name = $row['group_name'];
            }
        }else{
            $group_name = '';
        }
        return $group_name;
    }
}
