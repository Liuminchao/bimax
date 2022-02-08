<?php

/**
 * Issues
 * @author LiuMinchao
 */
class QaDefect2 extends CActiveRecord {

    //0: ongoing；3关闭；4超时；5挂起；	0包含了三种状态（0 发起；1 已修正；2驳回；）
    const STATUS_ONGOING = '0';//发起
    const STATUS_REVISE = '1';//修正
    const STATUS_REVOKED = '2';//驳回
    const STATUS_CLOSE = '3';//关闭
    const STATUS_TIMEOUT = '4';//超时
    const STATUS_HOLDON = '5';//挂起

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_defect2_record';
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
            self::STATUS_ONGOING => 'Active',
            self::STATUS_REVISE => 'Active',
            self::STATUS_REVOKED => 'Active',
            self::STATUS_CLOSE => 'Closed',
            self::STATUS_TIMEOUT => 'Delayed',
            self::STATUS_HOLDON => 'On Hold',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_ONGOING => 'bg-info',
            self::STATUS_REVISE => 'bg-info',
            self::STATUS_REVOKED => 'bg-info',
            self::STATUS_CLOSE => 'bg-success',
            self::STATUS_TIMEOUT => 'bg-danger',
            self::STATUS_HOLDON => 'bg-primary',
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
        $sql = "SELECT a.* FROM `qa_defect2_record` a ";
        $pro_model =Program::model()->findByPk($args['project_id']);
        $root_proid = $pro_model->root_proid;
        //Program
        if ($args['program_id'] != '') {
            $condition.= " WHERE a.project_id=:project_id ";
        }
        //Description
        if ($args['title'] != '') {
            $condition.= " and a.title like %:description% ";
        }
        //phase
        if ($args['phase'] != '') {
            $condition.= " and a.source=:source";
        }
        //block
        if ($args['block'] != '') {
            $condition.= " and a.block=:block";
        }
        //secondary_region
        if ($args['secondary_region'] != '') {
            $condition.= " and a.level=:level";
        }

        //type_id
        if ($args['type_id']) {
            $condition.= " and a.type_id=:type_id";
        }

        //apply user
        if($args['apply_user_name'] !=''){
            $condition.= " and a.apply_user_name=:apply_user_name";
        }

        //person_to_rectify
        if($args['rectify_name'] !=''){
            $condition.= " and a.person_to_rectify=:person_to_rectify";
        }

        //开始时间
        if ($args['start_date'] != '') {
            $condition.= " and a.record_time >=:start_date";
        }
        //结束时间
        if ($args['end_date'] != '') {
            $condition.= " and a.record_time <=:end_date";
        }


        $condition.=" order by a.apply_time desc";

        $sql.= $condition;

//        var_dump($sql);

        $command = Yii::app()->db->createCommand($sql);

        //Program
        if ($args['program_id'] != '') {
            $pro_model = Program::model()->findByPk($args['program_id']);
            $root_proid = $pro_model->root_proid;
            $command->bindParam(":project_id", $root_proid, PDO::PARAM_STR);
        }

        //Description
        if ($args['title'] != '') {
            $command->bindParam(":title", $args['title'], PDO::PARAM_STR);
        }

        //source
        if ($args['phase'] != '') {
            $command->bindParam(":source", $args['phase'], PDO::PARAM_STR);
        }

        //blook
        if ($args['block'] != '') {
            $command->bindParam(":block", $args['block'], PDO::PARAM_STR);
        }

        //secondary_region
        if ($args['secondary_region'] != '') {
            $command->bindParam(":level", $args['secondary_region'], PDO::PARAM_STR);
        }

        //Apply User
        if($args['apply_user_name'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['apply_user_name']));
            if($model) {
                $initiator = $model->user_id;
            }else{
                $initiator = '';
            }
            $command->bindParam(":apply_user_id", $initiator, PDO::PARAM_STR);
        }

        //person_to_rectify
        if($args['rectify_name'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['rectify_name']));
            if($model) {
                $rectify = $model->user_id;
            }else{
                $rectify = '';
            }
            $command->bindParam(":person_to_rectify", $rectify, PDO::PARAM_STR);
        }

        //开始时间
        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $command->bindParam(":start_date", $start_date, PDO::PARAM_STR);
        }
        //结束时间
        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $command->bindParam(":end_date", $end_date, PDO::PARAM_STR);
        }

        $data = $command->queryAll();

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($data);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($data,$start,$pageSize);
        }else{
            $pagedata = array();
        }
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;

        return $rs;
    }


}
