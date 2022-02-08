<?php

/**
 * Issues
 * @author LiuMinchao
 */
class QaDefect extends CActiveRecord {

    //0 发起；1 已修正；2驳回；3 关闭；4超时；发起或驳回后超过时间为超时
    const STATUS_ONGOING = '0';//发起
    const STATUS_REVISE = '1';//修正
    const STATUS_REVOKED = '2';//驳回
    const STATUS_CLOSE = '3';//关闭
    const STATUS_TIMEOUT = '4';//超时

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_defect_record';
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
            self::STATUS_ONGOING => 'Submitted',
            self::STATUS_REVISE => 'Rectified',
            self::STATUS_REVOKED => 'Replied',
            self::STATUS_CLOSE => 'Closed',
            self::STATUS_TIMEOUT => 'Delayed',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_ONGOING => 'bg-info',
            self::STATUS_REVISE => 'bg-primary',
            self::STATUS_REVOKED => 'bg-warning',
            self::STATUS_CLOSE => 'bg-success',
            self::STATUS_TIMEOUT => 'bg-danger',
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
    public static function queryInspectionList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        $sql = "SELECT a.* FROM `qa_defect_record` a ";
        $pro_model =Program::model()->findByPk($args['project_id']);
        $root_proid = $pro_model->root_proid;
        //Program
        if ($args['program_id'] != '') {
            $condition.= " WHERE a.project_id=:project_id and a.source = 'INSPECTION' ";
        }
        //Description
        if ($args['title'] != '') {
            $condition.= " and a.title like %:description% ";
        }
        //block
        if ($args['block'] != '') {
            $condition.= " and a.blook=:blook";
        }
        //secondary_region
        if ($args['secondary_region'] != '') {
            $condition.= " and a.secondary_region=:secondary_region";
        }

        //CS
        if ($args['discipline'] == '01') {
            $sql = "SELECT a.* FROM `qa_defect_record` a left join qa_checklist_record b on a.source_id = b.check_id ";
            $condition.= " and a.source_id <> '' and b.clt_type=:clt_type";
        }
        //AR
        if ($args['discipline'] == '02') {
            $sql = "SELECT a.* FROM `qa_defect_record` a left join qa_checklist_record b on a.source_id = b.check_id ";
            $condition.= " and a.source_id <> '' and b.clt_type=:clt_type";
        }
        //ME
        if ($args['discipline'] == '03') {
            $sql = "SELECT a.* FROM `qa_defect_record` a left join qa_checklist_record b on a.source_id = b.check_id ";
            $condition.= " and a.source_id <> '' and b.clt_type=:clt_type";
        }
        //general
        if ($args['discipline'] == '04') {
            $sql = "SELECT a.* FROM `qa_defect_record` a left join qa_checklist_record b on a.source_id = b.check_id ";
            $condition.= " and a.source_id <> '' and b.clt_type=:clt_type";
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

        //NA
        if ($args['discipline'] == '00') {
            $condition.= " and a.source_id = ''";
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

        //Discipline
        if ($args['discipline'] != '' and $args['discipline'] != '00') {
            $command->bindParam(":clt_type", $args['discipline'], PDO::PARAM_STR);
        }

        //blook
        if ($args['blook'] != '') {
            $command->bindParam(":blook", $args['blook'], PDO::PARAM_STR);
        }

        //secondary_region
        if ($args['secondary_region'] != '') {
            $command->bindParam(":secondary_region", $args['secondary_region'], PDO::PARAM_STR);
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


    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryDfmaList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        $sql = "SELECT a.* FROM `qa_defect_record` a ";
        $pro_model =Program::model()->findByPk($args['project_id']);
        $root_proid = $pro_model->root_proid;
        //Program
        if ($args['program_id'] != '') {
            $condition.= " WHERE a.project_id=:project_id and a.source = 'DFMA' ";
        }
        //Description
        if ($args['title'] != '') {
            $condition.= " and a.title like %:description% ";
        }
        //block
        if ($args['block'] != '') {
            $condition.= " and a.blook=:blook";
        }
        //secondary_region
        if ($args['secondary_region'] != '') {
            $condition.= " and a.secondary_region=:secondary_region";
        }

        //On-site
        if ($args['discipline'] == 'A') {
            $sql = "SELECT a.* FROM `qa_defect_record` a left join task_record b on a.source_id = b.check_id ";
            $condition.= " and a.source_id <> '' and b.clt_type=:clt_type";
        }
        //Fitting Out
        if ($args['discipline'] == 'B') {
            $sql = "SELECT a.* FROM `qa_defect_record` a left join task_record b on a.source_id = b.check_id ";
            $condition.= " and a.source_id <> '' and b.clt_type=:clt_type";
        }
        //Carcass
        if ($args['discipline'] == 'C') {
            $sql = "SELECT a.* FROM `qa_defect_record` a left join task_record b on a.source_id = b.check_id ";
            $condition.= " and a.source_id <> '' and b.clt_type=:clt_type";
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

        //NA
        if ($args['discipline'] == '00') {
            $condition.= " and a.source_id = ''";
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

        //Discipline
        if ($args['discipline'] != '' and $args['discipline'] != '00') {
            $command->bindParam(":clt_type", $args['discipline'], PDO::PARAM_STR);
        }

        //blook
        if ($args['blook'] != '') {
            $command->bindParam(":blook", $args['blook'], PDO::PARAM_STR);
        }

        //secondary_region
        if ($args['secondary_region'] != '') {
            $command->bindParam(":secondary_region", $args['secondary_region'], PDO::PARAM_STR);
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

    //通过各个模块记录id查询发起的Issues次数
    public static function cntBySource($check_id) {

        $sql = "SELECT * FROM qa_defect_record WHERE source_id=:source_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":source_id", $check_id, PDO::PARAM_STR);
        $data = $command->queryAll();

        $cnt = count($data);

        return $cnt;
    }

    //通过各个模块记录id查询发起的Issues
    public static function queryBySource($page, $pageSize, $args = array()) {

        $sql = "SELECT * FROM qa_defect_record WHERE source_id=:source_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":source_id", $args['check_id'], PDO::PARAM_STR);
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
