<?php

/**
 * Rf Group
 * @author LiuMinchao
 */
class RfGroup extends CActiveRecord {


    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '9';

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'rf_group';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

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
            self::STATUS_NORMAL => Yii::t('common', 'STATUS_NORMAL'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
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

        $condition = '';
        $params = array();

        //Program
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $pro_model = Program::model()->findByPk($args['program_id']);
            $root_proid = $pro_model->root_proid;
            $params['project_id'] = $root_proid;
        }

        if ($args['group_name'] != '') {
            $condition.= ( $condition == '') ? ' group_name LIKE :group_name' : ' AND group_name LIKE :group_name';
            $params['group_name'] = '%'.$args['group_name'].'%';
        }

        //Status
        $args['status'] = '0';
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Record Time
        if ($args['record_time'] != '') {
            $condition.= ( $condition == '') ? ' record_time=:record_time' : ' AND record_time=:record_time';
            $params['record_time'] = $args['record_time'];
        }

        $condition.= ( $condition == '') ? ' project_id=0' : ' OR project_id=0';

        $condition.= ( $condition == '') ? ' status=0' : ' AND status=0';
        $total_num = RfGroup::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'group_id';
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
        $rows = RfGroup::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertGroup($rs) {

        if ($rs['group_name'] == '') {
            $r['msg'] = 'Group Nmae is not Null';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = new RfGroup('create');
        $trans = $model->dbConnection->beginTransaction();
        try {
            $record_time = date('Y-m-d H:i:s', time());
            $status= '0';
            $model->group_name = $rs['group_name'];
            $model->project_id = $rs['program_id'];
//            $model->clt_type = $rs['clt_type'];
            $model->status = $status;
            $model->record_time = $record_time;
            $result = $model->save();//var_dump($result);exit;
            $group_id = $model->group_id;

            if ($result) {
                $trans->commit();
                $r['group_id'] = $group_id;
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;

            }
            else {
                $trans->rollBack();
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
    public static function editGroup($rs) {


        if ($rs['group_name'] == '') {
            $r['msg'] = 'Group Nmae is not Null';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = RfGroup::model()->findByPk($rs['group_id']);
        $trans = $model->dbConnection->beginTransaction();
        try {
            $record_time = date('Y-m-d H:i:s', time());
            $model->group_name = $rs['group_name'];
            $model->project_id = $rs['program_id'];
//            $model->clt_type = $rs['clt_type'];
            $result = $model->save();//var_dump($result);exit;

            if ($result) {
                $trans->commit();
                $r['group_id'] = $rs['group_id'];
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;

            }
            else {
                $trans->rollBack();
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

    //详情
    public static function  detailList($group_id){
        $sql = "SELECT * FROM rf_group WHERE group_id = '".$group_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $detaillist = $command->queryAll();
        return $detaillist;
    }

    //停用
    public static function stopGroup($id) {

        $model = RfGroup::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $del_sql = 'delete from rf_group where group_id = :group_id';
            $del_command = Yii::app()->db->createCommand($del_sql);
            $del_command->bindParam(":group_id", $id, PDO::PARAM_STR);
            $del_command->execute();

            $del_sql = 'delete from rf_group_user where group_id = :group_id';
            $del_command = Yii::app()->db->createCommand($del_sql);
            $del_command->bindParam(":group_id", $id, PDO::PARAM_STR);
            $del_command->execute();

            $r['msg'] = Yii::t('common', 'success_delete');
            $r['status'] = 1;
            $r['refresh'] = true;

        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //按项目查找模版
    public static function groupByProgram($project_id){
        $model = Program::model()->findByPk($project_id);
        $root_proid = $model->root_proid;
        $sql = "SELECT group_id,group_name FROM rf_group WHERE status=0 and project_id='".$root_proid."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['group_id']] = $row['group_name'];
            }
        }else{
            $rs = array();
        }

        return $rs;
    }

    //状态
    public static function tradeList($key = null) {
        $rs = array(
            '1' => 'ACMV',
            '2' => 'AGV',
            '3' => 'SE(Security)',
            '4' => 'AV',
            '5' => 'CSD',
            '6' => 'CSM',
            '7' => 'CBW',
            '8' => 'EL',
            '9' => 'ELV',
            '10' => 'FP',
            '11' => 'ELPS',
            '12' => 'LP(Lightning)',
            '13' => 'MG',
            '14' => 'PSG',
            '15' => 'MOTL',
            '16' => 'PLUM',
            '17' => 'SAN(General)',
            '18' => 'PWCS',
            '19' => 'PTS',
            '20' => 'VTS',
            '21' => 'IRG',
            '22' => 'NA',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //按项目查找模版
    public static function queryGroupByRecord($check_id){
        $sql = "SELECT b.group_id FROM rf_record_detail a left join rf_group_user b on a.user_id=b.user_id WHERE a.check_id='".$check_id."' and a.deal_type not in ('8','13') and a.status != '1' order by a.step DESC LIMIT 1";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $group_name = '';
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $model = RfGroup::model()->findByPk($row['group_id']);
                if($model->group_name){
                    $group_name.= $model->group_name.' ';
                }
            }
        }else{
            $group_name = '---';
        }

        if($group_name == ''){
            $group_name = '---';
        }

        return $group_name;
    }

    /**
     * 返回所有可用的组
     * @return type
     */
    public static function groupList($program_id) {
        $sql = "SELECT * FROM rf_group WHERE project_id=:project_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if(count($rows)>0){
            foreach($rows as $i => $j){
                $rs[$j['group_id']][] = '';
            }
        }else{
            $rs = array();
        }
        return $rs;
    }
}
