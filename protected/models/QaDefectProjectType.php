<?php

/**
 * Defect Type
 * @author LiuMinchao
 */
class QaDefectProjectType extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 9; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_defect2_type_project';
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
            self::STATUS_NORMAL => 'bg-success', //已启用
            self::STATUS_DISABLE => ' bg-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'region' => Yii::t('proj_project', 'region'),
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
//        var_dump($args);
        $condition = '';
        $params = array();
//        var_dump($args);
        //program_id
        if ($args['project_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $args['project_id'];
        }

        if ($args['type_1'] != '') {
            $condition.= ( $condition == '') ? ' type_1 =:type_1' : ' AND type_1 =:type_1';
            $params['type_1'] = $args['type_1'];
        }

        if ($args['type_2'] != '') {
            $condition.= ( $condition == '') ? ' type_2 like :type_2' : ' AND type_2 like :type_2';
            $params['type_2'] = '%'.$args['type_2'].'%';
        }

        if ($args['type_3'] != '') {
            $condition.= ( $condition == '') ? ' type_3 like :type_3' : ' AND type_3 like :type_3';
            $params['type_3'] = '%'.$args['type_3'].'%';
        }

        //Status
        $status = self::STATUS_NORMAL;
        $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
        $params['status'] = $status;

        $total_num = QaDefectProjectType::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'type_id ASC';
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
        $rows = QaDefectProjectType::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }


    //插入区域数据
    public static function InsertType($defect,$project_id){
//        var_dump($location);
//        var_dump($project_id);
//        exit;
        $status = self::STATUS_NORMAL;
        $trans = Yii::app()->db->beginTransaction();
        try {
            $exist_data = QaDefectType::model()->count('type_1=:type_1 and type_2=:type_2 and type_3=:type_3', array('type_1' => $defect['type_1'],'type_2' => $defect['type_2'],'type_3' => $defect['type_3']));
            if ($exist_data == 0) {
                $sub_sql = 'INSERT INTO qa_defect2_type(type_1,type_2,type_3,status) VALUES(:type_1,:type_2,:type_3,:status)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":type_1", $defect['type_1'], PDO::PARAM_STR);
                $command->bindParam(":type_2", $defect['type_2'], PDO::PARAM_STR);
                $command->bindParam(":type_3", $defect['type_3'], PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $rs = $command->execute();
            }

            $exist_data = QaDefectProjectType::model()->count('type_1=:type_1 and type_2=:type_2 and type_3=:type_3 and project_id=:project_id', array('type_1' => $defect['type_1'],'type_2' => $defect['type_2'],'type_3' => $defect['type_3'],'project_id'=>$project_id));
            if ($exist_data == 0) {
//                if($defect['user_id']){
//                    $user_list = explode('|',$defect['user_id']);
//                    $user_str = '';
//                    foreach($user_list as $i => $user_phone){
//                        $user = Staff::userByPhone($user_phone);
//                        $user_id = $user[0]['user_id'];
//                        $user_str.=$user_id.',';
//                    }
//                    $user_str = substr($user_str, 0, strlen($user_str) - 1);
//                }
                $sub_sql = 'INSERT INTO qa_defect2_type_project(project_id,type_1,type_2,type_3,status) VALUES(:project_id,:type_1,:type_2,:type_3,:status)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                $command->bindParam(":type_1", $defect['type_1'], PDO::PARAM_STR);
                $command->bindParam(":type_2", $defect['type_2'], PDO::PARAM_STR);
                $command->bindParam(":type_3", $defect['type_3'], PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
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

    //编辑区域数据
    public static function EditType($defect){
//        var_dump($location);
//        var_dump($project_id);
//        exit;

//        var_dump($location);
//        exit;
        $status = self::STATUS_NORMAL;
//        $exist_data = ProgramLocation::model()->count('project_id=:project_id and type=:type and value=:value and status=:status and id <> :id', array('project_id' => $location['project_id'],'type'=>$location['type'],'value'=>$location['value'],'status'=>$status,'id'=>$location['id']));
//        if ($exist_data != 0) {
//            $r['msg'] = Yii::t('proj_project', 'error_proj_location_is_exists');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        $trans = Yii::app()->db->beginTransaction();
        try {
            $defect_model = QaDefectProjectType::model()->findByPk($defect['id']);
            $type_1 = $defect_model->type_1;
            $type_2 = $defect_model->type_2;
            $type_3 = $defect_model->type_3;
            $exist_data = QaDefectType::model()->count('type_1=:type_1 and type_2=:type_2 and type_3=:type_3', array('type_1' => $type_1,'type_2' => $type_2,'type_3' => $type_3));
            if ($exist_data != 0) {
                $sub_sql = 'UPDATE qa_defect2_type SET type_1=:type_1,type_2=:type_2,type_3=:type_3 WHERE type_1=:type_1_1 and type_2=:type_2_2 and type_3=:type_3_3';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":type_1", $defect['type_1'], PDO::PARAM_STR);
                $command->bindParam(":type_2", $defect['type_2'], PDO::PARAM_STR);
                $command->bindParam(":type_3", $defect['type_3'], PDO::PARAM_STR);
                $command->bindParam(":type_1_1", $type_1, PDO::PARAM_STR);
                $command->bindParam(":type_2_2", $type_2, PDO::PARAM_STR);
                $command->bindParam(":type_3_3", $type_3, PDO::PARAM_STR);
                $rs = $command->execute();
            }

            $exist_data = QaDefectProjectType::model()->count('type_1=:type_1 and type_2=:type_2 and type_3=:type_3', array('type_1' => $type_1,'type_2' => $type_2,'type_3' => $type_3));
            if ($exist_data != 0) {
                $sub_sql = 'UPDATE qa_defect2_type_project SET type_1=:type_1,type_2=:type_2,type_3=:type_3 WHERE type_1=:type_1_1 and type_2=:type_2_2 and type_3=:type_3_3';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":type_1", $defect['type_1'], PDO::PARAM_STR);
                $command->bindParam(":type_2", $defect['type_2'], PDO::PARAM_STR);
                $command->bindParam(":type_3", $defect['type_3'], PDO::PARAM_STR);
                $command->bindParam(":type_1_1", $type_1, PDO::PARAM_STR);
                $command->bindParam(":type_2_2", $type_2, PDO::PARAM_STR);
                $command->bindParam(":type_3_3", $type_3, PDO::PARAM_STR);
                $rs = $command->execute();
            }


            $sub_sql = 'UPDATE qa_defect2_type_project SET type_1=:type_1,type_2=:type_2,type_3=:type_3 WHERE type_id=:type_id';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":type_1", $defect['type_1'], PDO::PARAM_STR);
            $command->bindParam(":type_2", $defect['type_2'], PDO::PARAM_STR);
            $command->bindParam(":type_3", $defect['type_3'], PDO::PARAM_STR);
            $command->bindParam(":type_id", $defect['id'], PDO::PARAM_INT);
            $rs = $command->execute();

            $r['msg'] = Yii::t('common','success_update');
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
    //注销区域数据
    public static function DelType($id){
        $status = self::STATUS_DISABLE;
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sub_sql = 'UPDATE qa_defect2_type_project SET status=:status WHERE type_id=:type_id';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":type_id", $id, PDO::PARAM_INT);
            $rs = $command->execute();

            $r['msg'] = Yii::t('common','success_logout');
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


    //编辑区域数据
    public static function EditUser($id,$json){

        $list = explode('@',$json);
        $user = '';
        foreach($list as $i => $j){
            $data = explode(',',$j);
            $args[$i]['contractor_id'] = $data[0];
            $con_model =Contractor::model()->findByPk($data[0]);
            $args[$i]['contractor_name'] = $con_model->contractor_name;
            $args[$i]['user_id'] = $data[1];
            $user.= $data[1].',';
            $user_model =Staff::model()->findByPk($data[1]);
            $args[$i]['user_name'] = $user_model->user_name;
        }
        $user = substr($user, 0, strlen($user) - 1);

        $trans = Yii::app()->db->beginTransaction();
        try {
            $sub_sql = 'UPDATE qa_defect2_type_project  SET user_id=:user_id WHERE type_id=:type_id';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":user_id", $user, PDO::PARAM_STR);
            $command->bindParam(":type_id", $id, PDO::PARAM_INT);
            $rs = $command->execute();

            $r['msg'] = Yii::t('common','success_update');
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

    public static function QueryType($program_id,$phase){
        $sql = "SELECT * FROM qa_defect2_type_project WHERE type_1 = :type_1 AND project_id = :program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":type_1", $phase, PDO::PARAM_INT);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['type_2']][$row['type_3']] = $row['type_id'];
            }
        }
        return $rs;
    }
}
