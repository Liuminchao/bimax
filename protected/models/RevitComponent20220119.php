<?php

/**
 * RevitModel
 *
 * @author liuxy
 */
class RevitComponent extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'pbu_info';
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
            '0' => Yii::t('common', 'normal'),
            '1' => Yii::t('common', 'expiring'),
            '2' => Yii::t('common', 'expired'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            '0'=> 'label-info',
            '1' => 'label-success',
            '2' => 'label-danger',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //类型
    public static function typeList($key = null) {
        $rs = array(
            '1'=> 'PPVC',
            '2' => 'PBU',
            '3' => 'Precast',
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
//        var_dump($args);
        $condition = '';
        $params = array();
        if(count($args)<= 0){
            $args['project_id'] = '0';
        }

        //project id
        if ($args['project_id'] != '') {
            $model = Program::model()->findByPk($args['project_id']);
            if($model->father_proid == 0) {  //总包项目
                $project_id = $args['project_id'];
            }else{
                $project_id = $model->root_proid;
            }
            $condition.= " a.project_id= '$project_id' ";
        }

        //model id
        if ($args['modellist'] != '') {
            if($args['modellist'] == '0'){
                $model_id = '0';
            }else{
                $entity = explode('_',$args['modellist']);
                $model_id = $entity[0];
            }
            $condition.= " AND a.model_id='$model_id' ";
        }
        //block
        if ($args['block'] != '') {
            $block = $args['block'];
            $condition.= " AND a.block='$block' ";
        }
        //level
        if ($args['level'] != '') {
            $level = $args['level'];
            $condition.= " AND a.level= '$level' ";
        }
        //part
        if ($args['part'] != '') {
            $part = $args['part'];
            $condition.= " AND a.part='$part' ";
        }
        //type
        if ($args['pbu_tag'] != '') {
            $pbu_tag = $args['pbu_tag'];
            if($project_id == '1453'){
                if($pbu_tag == '1'){
                    $condition.= " AND a.pbu_type LIKE '%PBU%' ";
                }else if($pbu_tag == '2'){
                    $condition.= " AND a.pbu_type LIKE '%PPVC%' ";
                }else if($pbu_tag == '3'){
                    $condition.= " AND a.pbu_type not LIKE '%PBU%' ";
                }
            }else{
                $condition.= " AND a.type='$pbu_tag' ";
            }
        }
        //property
        if ($args['property'] != '') {
            $property = $args['property'];
            $property_name = urldecode($args['property_name']);
            $condition.= " AND a.$property LIKE '%".$property_name."%' ";
        }
        $status = '0';
        //Status
        $condition.= " AND a.status= '$status' ";

//        $sql.= " limit $page,$pageSize";

        //template_id
        if ($args['template_id'] != '') {
            $template_id = $args['template_id'];

            $sql = "select a.*,b.template_id,b.stage_id,'' as stage_name from 
                        pbu_info a
                    join task_component_stats b on a.model_id = b.model_id and a.pbu_id = b.guid and a.project_id = b.project_id 
                    where $condition and b.template_id = '$template_id' and b.latest_flag = '1'";
            if($args['stage_id'] != ''){
                $stage_id = $args['stage_id'];
                $sql.=" and b.stage_id = '$stage_id'";
            }
        }else{
            $sql = "select a.*,b.template_id,b.stage_id,'' as stage_name from 
                        pbu_info a
                    left join task_component_stats b on a.model_id = b.model_id and a.pbu_id = b.guid and a.project_id = b.project_id  and b.latest_flag = '1'
                    where $condition  order by b.stage_id desc";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach ($rows as $index => $value){
            $rs[$value['id']]['id'] = $value['id'];
            $rs[$value['id']]['pbu_id'] = $value['pbu_id'];
            $rs[$value['id']]['block'] = $value['block'];
            $rs[$value['id']]['part'] = $value['part'];
            $rs[$value['id']]['level'] = $value['level'];
            $rs[$value['id']]['unit_nos'] = $value['unit_nos'];
            $rs[$value['id']]['unit_type'] = $value['unit_type'];
            $rs[$value['id']]['pbu_name'] = $value['pbu_name'];
            $rs[$value['id']]['pbu_type'] = $value['pbu_type'];
            if(array_key_exists('template_id',$rs[$value['id']])){
                $rs[$value['id']]['template_id'].='|'.$value['template_id'];
            }else{
                $rs[$value['id']]['template_id'] = $value['template_id'];
            }
            if(array_key_exists('stage_id',$rs[$value['id']])){
                $rs[$value['id']]['stage_id'].='|'.$value['stage_id'];
            }else{
                $rs[$value['id']]['stage_id'] = $value['stage_id'];
            }
        }

//        var_dump($sql);
        $count = count($rows);
        $pagedata=array();
        $start=$page*$pageSize; #计算每次分页的开始位置
        if($count>0){
            $pagedata=array_slice($rows,$start,$pageSize);
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
    public static function queryStatistics($page, $pageSize, $args = array()) {
//        var_dump($args);
        $condition = '';
        $params = array();
        if(count($args)<= 0){
            $args['project_id'] = '0';
        }

        //project id
        if ($args['project_id'] != '') {
            $model = Program::model()->findByPk($args['project_id']);
            if($model->father_proid == 0) {  //总包项目
                $project_id = $args['project_id'];
            }else{
                $project_id = $model->root_proid;
            }
            $condition.= " a.project_id= '$project_id' ";
        }


        //block
        if ($args['block'] != '') {
            $block = $args['block'];
            $condition.= " AND a.block='$block' ";
        }

        //type
        if ($args['pbu_tag'] != '') {
            $pbu_tag = $args['pbu_tag'];
            $condition.= " AND a.type='$pbu_tag' ";
        }

        $status = '0';
        //Status
        $condition.= " AND a.status= '$status' ";

        $pbu_sql = "SELECT
                            a.level,count(*) as cnt
                        FROM
                            pbu_info a
                        LEFT JOIN
                            pbu_level b
                        ON
                            a.level = b.level       
                        WHERE 
                            $condition
                        GROUP BY 
                            a.level
                        ORDER BY
                            b.level_index desc";

        $command = Yii::app()->db->createCommand($pbu_sql);
        $r = $command->queryAll();

        $t = array();

        //template_id
        if ($args['template_id'] != '') {
            $template_id = $args['template_id'];
            $condition.= " AND b.template_id= '$template_id' ";
        }
        //stage_id
        if ($args['stage_id'] != '') {
            $stage_id = $args['stage_id'];
            $condition.= " AND b.stage_id= '$stage_id' ";

            $sql = "select a.level,count(*) as cnt from 
                        pbu_info a
                    join task_component_stats b on a.model_id = b.model_id and a.pbu_id = b.guid and a.project_id = b.project_id 
                    where $condition and b.status = '1'  group by a.level";

            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if(count($rows)>0){
                foreach($rows as $x => $y){
                    $complete[$y['level']] = $y['cnt'];
                }
                if(count($r)>0){
                    $index = 0;
                    foreach($r as $i => $j){
                        $t[$j['level']]['total'] = $j['cnt'];
                        if(array_key_exists($j['level'],$complete)){
                            $t[$j['level']]['complete'] = $complete[$j['level']];
                            $t[$j['level']]['balance'] = (int)$j['cnt']-(int)$complete[$j['level']];
                            $percentage = (int)$complete[$j['level']]/(int)$j['cnt'];
                            $percentage = round($percentage*100,2).'%';
                            $t[$j['level']]['percentage'] = $percentage;
                        }else{
                            $t[$j['level']]['complete'] = 0;
                            $t[$j['level']]['balance'] = $j['cnt'];
                            $t[$j['level']]['percentage'] = '0%';
                        }
                        $index++;
                    }
                }else{
                    $t = array();
                }
            }
        }



//        var_dump($sql);
        $count = count($t);
        $pagedata=array();
        $start=$page*$pageSize; #计算每次分页的开始位置
//        if($count>0){
//            $pagedata=array_slice($rows,$start,$pageSize);
//        }else{
//            $pagedata = array();
//        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $t;

        return $rs;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryPbuList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        if(count($args)<= 0){
            $args['project_id'] = '0';
        }

        //project id
        if ($args['project_id'] != '') {
            $model = Program::model()->findByPk($args['project_id']);
            if($model->father_proid == 0) {  //总包项目
                $project_id = $args['project_id'];
            }else{
                $project_id = $model->root_proid;
            }
            $condition.= " a.project_id= '$project_id' ";
        }

        //model id
        if ($args['modellist'] != '') {
            if($args['modellist'] == '0'){
                $model_id = '0';
            }else{
                $entity = explode('_',$args['modellist']);
                $model_id = $entity[0];
            }
            $condition.= " AND a.model_id='$model_id' ";
        }
        //block
        if ($args['block'] != '') {
            $block = $args['block'];
            $condition.= " AND a.block='$block' ";
        }
        //level
        if ($args['level'] != '') {
            $level = $args['level'];
            $condition.= " AND a.level= '$level' ";
        }
        //part
        if ($args['part'] != '') {
            $part = $args['part'];
            $condition.= " AND a.part='$part' ";
        }
        //type
        if ($args['pbu_tag'] != '') {
            $pbu_tag = $args['pbu_tag'];
            if($project_id == '1453'){
                if($pbu_tag == '1'){
                    $condition.= " AND a.pbu_type LIKE '%PBU%' ";
                }else if($pbu_tag == '2'){
                    $condition.= " AND a.pbu_type LIKE '%PPVC%' ";
                }else if($pbu_tag == '3'){
                    $condition.= " AND a.pbu_type not LIKE '%PBU%' ";
                }
            }else{
                $condition.= " AND a.type='$pbu_tag' ";
            }
        }
        //property
        if ($args['property'] != '') {
            $property = $args['property'];
            $property_name = urldecode($args['property_name']);
            $condition.= " AND a.$property LIKE '%".$property_name."%' ";
        }
        $status = '0';
        //Status
        $condition.= " AND a.status= '$status' ";

//        $sql.= " limit $page,$pageSize";

        $sql = "select a.* from 
                        pbu_info a
                where $condition  order by a.block asc,a.level+0 asc ";

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach ($rows as $index => $value){
            $rs[$value['id']]['id'] = $value['id'];
            $rs[$value['id']]['pbu_id'] = $value['pbu_id'];
            $rs[$value['id']]['block'] = $value['block'];
            $rs[$value['id']]['part'] = $value['part'];
            $rs[$value['id']]['level'] = $value['level'];
            $rs[$value['id']]['unit_nos'] = $value['unit_nos'];
            $rs[$value['id']]['unit_type'] = $value['unit_type'];
            $rs[$value['id']]['pbu_name'] = $value['pbu_name'];
            $rs[$value['id']]['pbu_type'] = $value['pbu_type'];
        }

//        var_dump($sql);
        $count = count($rows);
        $pagedata=array();
        $start=$page*$pageSize; #计算每次分页的开始位置
        if($count>0){
            $pagedata=array_slice($rows,$start,$pageSize);
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
    public static function queryAllList($args = array()) {

        $condition = '';
        $params = array();
        if(count($args)<= 0){
            $args['project_id'] = '0';
        }
        //Contractor Type
        if ($args['project_id'] != '') {
            $model = Program::model()->findByPk($args['project_id']);
            if($model->father_proid == 0) {  //总包项目
                $project_id = $args['project_id'];
            }else{
                $project_id = $model->root_proid;
            }
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $project_id;
        }
        //Teamid
        if ($args['modellist'] != '') {
            if($args['modellist'] == '0'){
                $condition.= ( $condition == '') ? ' model_id=:model_id' : ' AND model_id=:model_id';
                $params['model_id'] = '0';
            }else{
                $entity = explode('_',$args['modellist']);
                $condition.= ( $condition == '') ? ' model_id=:model_id' : ' AND model_id=:model_id';
//                $condition.= ( $condition == '') ? ' version=:version' : ' AND version=:version';
                $params['model_id'] = $entity[0];
//                $params['version'] = $entity[1];
            }

        }
        if ($args['block'] != '') {
            $condition.= ( $condition == '') ? ' block=:block' : ' AND block=:block';
            $params['block'] = $args['block'];
        }
        if ($args['level'] != '') {
            $condition.= ( $condition == '') ? ' level=:level' : ' AND level=:level';
            $params['level'] = $args['level'];
        }
        if ($args['pbu_tag'] != '') {
            $condition.= ( $condition == '') ? ' type=:type' : ' AND type=:type';
            $params['type'] = $args['pbu_tag'];
        }
        if ($args['part'] != '') {
            $condition.= ( $condition == '') ? ' part=:part' : ' AND part=:part';
            $params['part'] = $args['part'];
        }
        if ($args['property'] != '') {
            $condition.= ( $condition == '') ? $args['property'].' LIKE :'.$args['property'] : ' AND '.$args['property'].' LIKE :'.$args['property'];
            $params[$args['property']] = '%' . $args['property_name'] . '%';
        }
        $args['status'] = '0';
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'id DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $rows = RevitComponent::model()->findAll($criteria);

        return $rows;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryStatusList($args = array()) {
//        var_dump($args);
        $condition = '';
        $params = array();
        if(count($args)<= 0){
            $args['project_id'] = '0';
        }

        //project id
        if ($args['project_id'] != '') {
            $model = Program::model()->findByPk($args['project_id']);
            if($model->father_proid == 0) {  //总包项目
                $project_id = $args['project_id'];
            }else{
                $project_id = $model->root_proid;
            }
            $condition.= " a.project_id= '$project_id' ";
        }

        //model id
        if ($args['modellist'] != '') {
            if($args['modellist'] == '0'){
                $model_id = '0';
            }else{
                $entity = explode('_',$args['modellist']);
                $model_id = $entity[0];
            }
            $condition.= " AND a.model_id='$model_id' ";
        }
        //block
        if ($args['block'] != '') {
            $block = $args['block'];
            $condition.= " AND a.block='$block' ";
        }
        //level
        if ($args['level'] != '') {
            $level = $args['level'];
            $condition.= " AND a.level= '$level' ";
        }
        //part
        if ($args['part'] != '') {
            $part = $args['part'];
            $condition.= " AND a.part='$part' ";
        }
        //type
        if ($args['pbu_tag'] != '') {
            $pbu_tag = $args['pbu_tag'];
            $condition.= " AND a.type='$pbu_tag' ";
        }
        //property
        if ($args['property'] != '') {
            $property = $args['property'];
            $property_name = $args['property_name'];
            $condition.= " AND a.$property LIKE '%".$property_name."%' ";
        }
        $status = '0';
        //Status
        $condition.= " AND a.status= '$status' ";

//        $sql.= " limit $page,$pageSize";

        //template_id
        if ($args['template_id'] != '') {
            $template_id = $args['template_id'];

            $sql = "select a.*,b.template_id,b.stage_id,'' as stage_name from 
                        pbu_info a
                    join task_component_stats b on a.model_id = b.model_id and a.pbu_id = b.guid and a.project_id = b.project_id 
                    where $condition and b.template_id = '$template_id' and b.latest_flag = '1'";
            if($args['stage_id'] != ''){
                $stage_id = $args['stage_id'];
                $sql.=" and b.stage_id = '$stage_id'";
            }
        }else{
            $sql = "select a.*,b.template_id,b.stage_id,'' as stage_name from 
                        pbu_info a
                    left join task_component_stats b on a.model_id = b.model_id and a.pbu_id = b.guid and a.project_id = b.project_id  and b.latest_flag = '1'
                    where $condition  order by b.stage_id desc";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    public static function CreateIndex($project_id,$block){
        $sql = "SELECT count(*) as cnt FROM pbu_info  WHERE project_id=:project_id and block=:block";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
        $command->bindParam(":block", $block, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if(count($rows)>0){
            $cnt = $rows[0]['cnt'] +1;
        }else{
            $cnt = 1;
        }
        $index_1 = 'blk'.$block;
        $index_2 = str_pad((String)$cnt, 5, '0', STR_PAD_LEFT);
        $index = $index_1.$index_2;
        return $index;
    }

    //插入区域数据
    public static function InsertForm($pbu,$pbu_plan){
//        $exist_data = QaForm::model()->count('item_id=:item_id and form_id=:form_id ', array('item_id' => $pbu['item_id'],'form_id'=>$pbu['form_id']));
//        if ($exist_data != 0) {
//            $r['msg'] = 'Already Exist';
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        $trans = Yii::app()->db->beginTransaction();
        $is_required = '0';
        $status = '0';
        $record_time = date('Y-m-d H:i:s');
        $guid = $pbu['guid'];
        $pbu_name = '';
        if($pbu['pbu_name']){
            $pbu_name = $pbu['pbu_name'];
        }else{//Block-Level-Part/Zone-Unit-PBU Type/PPVC Type/Element Type
            if($pbu['block']){
                $pbu_name.=$pbu['block'];
            }
            if($pbu['level']){
                $pbu_name.='-'.$pbu['level'];
            }
            if($pbu['part']){
                $pbu_name.='-'.$pbu['part'];
            }
            if($pbu['unit_nos']){
                $pbu_name.='-'.$pbu['unit_nos'];
            }
            if($pbu['pbu_type']){
                $pbu_name.='-'.$pbu['pbu_type'];
            }
        }
        if($guid != ''){
            $pbu_id = $guid;
        }else{
//            $pbu_id = RevitComponent::CreateIndex($pbu['project_id'],$pbu['block']);
            $pbu_id = $pbu_name;
        }
        try {
            $exist_data = RevitComponent::model()->count('pbu_id=:pbu_id and model_id=:model_id and project_id=:project_id', array('pbu_id' => $pbu_id,'model_id'=>$pbu['model_id'],'project_id'=>$pbu['project_id']));
            if($exist_data != 0){
                if($pbu['model_id'] != '0'){
                    $sub_sql = 'UPDATE pbu_info SET block=:block,level=:level,unit_nos=:unit_nos,part=:part,unit_type=:unit_type,serial_number=:serial_number,pbu_type=:pbu_type,pbu_name=:pbu_name,module_type=:module_type,precast_plant=:precast_plant,version=:version,user_id=:user_id,status=:status,record_time=:record_time,blockchart=:blockchart WHERE project_id=:project_id and model_id=:model_id and pbu_id=:pbu_id and type=:type';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":block", $pbu['block'], PDO::PARAM_STR);
                    $command->bindParam(":level", $pbu['level'], PDO::PARAM_STR);
                    $command->bindParam(":unit_nos", $pbu['unit_nos'], PDO::PARAM_STR);
                    $command->bindParam(":part", $pbu['part'], PDO::PARAM_STR);
                    $command->bindParam(":unit_type", $pbu['unit_type'], PDO::PARAM_STR);
                    $command->bindParam(":serial_number", $pbu['serial_number'], PDO::PARAM_STR);
                    $command->bindParam(":pbu_type", $pbu['pbu_type'], PDO::PARAM_STR);
                    $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_STR);
                    $command->bindParam(":module_type", $pbu['module_type'], PDO::PARAM_STR);
                    $command->bindParam(":precast_plant", $pbu['precast_plant'], PDO::PARAM_STR);
                    $command->bindParsam(":version", $pbu['version'], PDO::PARAM_STR);
                    $command->bindParam(":user_id", $pbu['user_id'], PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                    $command->bindParam(":blockchart", $pbu['blockchart'], PDO::PARAM_STR);
                    $command->bindParam(":project_id", $pbu['project_id'], PDO::PARAM_STR);
                    $command->bindParam(":model_id", $pbu['model_id'], PDO::PARAM_STR);
                    $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_STR);
                    $command->bindParam(":type", $pbu['pbu_tag'], PDO::PARAM_STR);
                    $rs = $command->execute();
                }else{
                    $r['msg'] = 'Duplicated QR Code ID - '.$pbu_id;
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
            }else{
                $sub_sql = 'INSERT INTO pbu_info(project_id,model_id,pbu_id,block,level,unit_nos,part,unit_type,serial_number,pbu_type,pbu_name,module_type,precast_plant,type,version,user_id,status,record_time,blockchart) VALUES(:project_id,:model_id,:pbu_id,:block,:level,:unit_nos,:part,:unit_type,:serial_number,:pbu_type,:pbu_name,:module_type,:precast_plant,:type,:version,:user_id,:status,:record_time,:blockchart)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":project_id", $pbu['project_id'], PDO::PARAM_STR);
                $command->bindParam(":model_id", $pbu['model_id'], PDO::PARAM_STR);
                $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_STR);
                $command->bindParam(":block", $pbu['block'], PDO::PARAM_STR);
                $command->bindParam(":level", $pbu['level'], PDO::PARAM_STR);
                $command->bindParam(":unit_nos", $pbu['unit_nos'], PDO::PARAM_STR);
                $command->bindParam(":part", $pbu['part'], PDO::PARAM_STR);
                $command->bindParam(":unit_type", $pbu['unit_type'], PDO::PARAM_STR);
                $command->bindParam(":serial_number", $pbu['serial_number'], PDO::PARAM_STR);
                $command->bindParam(":pbu_type", $pbu['pbu_type'], PDO::PARAM_STR);
                $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_STR);
                $command->bindParam(":module_type", $pbu['module_type'], PDO::PARAM_STR);
                $command->bindParam(":precast_plant", $pbu['precast_plant'], PDO::PARAM_STR);
                $command->bindParam(":type", $pbu['pbu_tag'], PDO::PARAM_STR);
                $command->bindParam(":version", $pbu['version'], PDO::PARAM_STR);
                $command->bindParam(":user_id", $pbu['user_id'], PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $command->bindParam(":blockchart", $pbu['blockchart'], PDO::PARAM_STR);
                $rs = $command->execute();
            }
//            $exist_data = PbuPlan::model()->count('pbu_id=:pbu_id and model_id=:model_id and project_id=:project_id and template_id=:template_id', array('pbu_id' => $pbu_id,'model_id'=>$pbu['model_id'],'project_id'=>$pbu['project_id'],'template_id'=>$pbu['template_id']));
//            if($exist_data != 0){
//                $sub_sql = 'DELETE from pbu_info_plan  WHERE project_id=:project_id and model_id=:model_id and pbu_id=:pbu_id and template_id=:template_id';
//                $command = Yii::app()->db->createCommand($sub_sql);
//                $command->bindParam(":project_id", $pbu['project_id'], PDO::PARAM_STR);
//                $command->bindParam(":model_id", $pbu['model_id'], PDO::PARAM_STR);
//                $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_STR);
//                $command->bindParam(":template_id", $pbu['template_id'], PDO::PARAM_STR);
//                $rs = $command->execute();
//            }
//            foreach($pbu_plan as $stage_id => $y){
//                $model = new PbuPlan('create');
//                $model->project_id = $pbu['project_id'];
//                $model->model_id = $pbu['model_id'];
//                $model->pbu_id = $pbu_id;
//                $model->template_id = $pbu['template_id'];
//                $model->stage_id = $stage_id;
//                $model->plan_start_date = $y['start_date'];
//                $model->plan_end_date = $y['end_date'];
//                $model->save();
//            }
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


    //批量设置房间号
    public static function batchSetInfo($data,$info_str,$res){

        $list = explode('@',$info_str);
        $unit_nos = '';
        $block = '';
        $pbu_type = '';
        foreach($list as $i => $j){
            $info_array = explode(',',$j);
            if($info_array[0] == 'Unit No.'){
                $unit_nos = $info_array[1];
            }else if($info_array[0] == 'Block'){
                $block = $info_array[1];
            }else if($info_array[0] == 'Type'){
                $pbu_type = $info_array[1];
            }else if($info_array[0] == 'Part'){
                $part = $info_array[1];
            }
        }
        foreach($data as $i => $params){
            $trans = Yii::app()->db->beginTransaction();
            $model_id = $params['modelId'];
            $uuid = $params['uuid'];
            $entityId = $params['entityId'];
            $type = $params['type'];
            $version = $params['version'];
            $name = $params['name'];
            $level = $params['floor'];
//            $block = '';
            $unit_type = '';
            $status = '0';
            $record_time = date('Y-m-d H:i:s');
            foreach ($params['properties'] as $m => $n){
                if($n['group'] == 'Constraints'){
                    if($n['key'] == 'Reference Level' || $n['key'] == '参照标高'){
                        $level = $n['value'];
                    }else if($n['key'] == 'Level' || $n['key'] == '标高'){
                        $level = $n['value'];
                    }else if($n['key'] == 'Base Constraint' || $n['key'] == '基本压缩'){
                        $level = $n['value'];
                    }
                }
            }
            $pbu_name = $block.'-'.$level.'-'.$unit_nos.'-'.$name;
//            $exist_data = RevitComponent::model()->count('pbu_id=:pbu_id and model_id=:model_id and project_id=:project_id and version=:version', array('pbu_id' => $uuid,'model_id'=>$res['model_id'],'project_id'=>$res['project_id'],'version'=>$res['version']));
            $exist_data = RevitComponent::model()->count('pbu_id=:pbu_id and model_id=:model_id and project_id=:project_id', array('pbu_id' => $uuid,'model_id'=>$model_id,'project_id'=>$res['project_id']));
            if ($exist_data != 0) {
                try {
                    $sub_sql = 'update pbu_info set user_id = :user_id ';
                    if($unit_nos){
                        $sub_sql.=',unit_nos =:unit_nos';
                    }
                    if($block){
                        $sub_sql.=',block = :block';
                    }
                    if($pbu_type){
                        $sub_sql.=',pbu_type =:pbu_type';
                    }
                    if($part){
                        $sub_sql.=',part =:part';
                    }
                    $sub_sql.=' where project_id = :project_id and model_id = :model_id and pbu_id =:pbu_id';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":project_id", $res['project_id'], PDO::PARAM_STR);
//                    $command->bindParam(":model_id", $res['model_id'], PDO::PARAM_STR);
                    $command->bindParam(":model_id", $model_id, PDO::PARAM_STR);
                    $command->bindParam(":pbu_id", $uuid, PDO::PARAM_STR);
                    if($unit_nos){
                        $command->bindParam(":unit_nos", $unit_nos, PDO::PARAM_STR);
                    }
                    if($block){
                        $command->bindParam(":block", $block, PDO::PARAM_STR);
                    }
                    if($pbu_type){
                        $command->bindParam(":pbu_type", $pbu_type, PDO::PARAM_STR);
                    }
                    if($part){
                        $command->bindParam(":part", $part, PDO::PARAM_STR);
                    }
//                    $command->bindParam(":level", $level, PDO::PARAM_STR);
//                    $command->bindParam(":unit_type", $unit_type, PDO::PARAM_STR);
//                    $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_STR);
//                    $command->bindParam(":version", $res['version'], PDO::PARAM_STR);
                    $command->bindParam(":user_id", $res['user_id'], PDO::PARAM_STR);
                    $rs = $command->execute();
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
            }else{
                try {
                    $plan_date = '';
                    $sub_sql = 'INSERT INTO pbu_info(project_id,model_id,pbu_id,block,level,unit_nos,unit_type,pbu_type,pbu_name,part,version,user_id,status,record_time,start_a,finish_a,start_b,finish_b,start_c,finish_c) VALUES(:project_id,:model_id,:pbu_id,:block,:level,:unit_nos,:unit_type,:pbu_type,:pbu_name,:part,:version,:user_id,:status,:record_time,:start_a,:finish_a,:start_b,:finish_b,:start_c,:finish_c)';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":project_id", $res['project_id'], PDO::PARAM_STR);
//                    $command->bindParam(":model_id", $res['model_id'], PDO::PARAM_STR);
                    $command->bindParam(":model_id", $model_id, PDO::PARAM_STR);
                    $command->bindParam(":pbu_id", $uuid, PDO::PARAM_STR);
                    $command->bindParam(":block", $block, PDO::PARAM_STR);
                    $command->bindParam(":level", $level, PDO::PARAM_STR);
                    $command->bindParam(":unit_nos", $unit_nos, PDO::PARAM_STR);
                    $command->bindParam(":unit_type", $unit_type, PDO::PARAM_STR);
                    $command->bindParam(":pbu_type", $pbu_type, PDO::PARAM_STR);
                    $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_STR);
                    $command->bindParam(":part", $part, PDO::PARAM_STR);
                    $command->bindParam(":version", $version, PDO::PARAM_STR);
                    $command->bindParam(":user_id", $res['user_id'], PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                    $command->bindParam(":start_a", $plan_date, PDO::PARAM_STR);
                    $command->bindParam(":finish_a", $plan_date, PDO::PARAM_STR);
                    $command->bindParam(":start_b", $plan_date, PDO::PARAM_STR);
                    $command->bindParam(":finish_b", $plan_date, PDO::PARAM_STR);
                    $command->bindParam(":start_c", $plan_date, PDO::PARAM_STR);
                    $command->bindParam(":finish_c", $plan_date, PDO::PARAM_STR);
                    $rs = $command->execute();
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
            }
        }
        return $r;
    }

    //详情
    public static function  detailList($program_id,$model_id,$version){
        $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and model_id=:model_id and version=:version";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":model_id", $model_id, PDO::PARAM_STR);
        $command->bindParam(":version", $version, PDO::PARAM_STR);
        $detaillist = $command->queryAll();
        return $detaillist;
    }

    //详情
    public static function  pbuInfo($program_id,$uuid){
        $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and pbu_id=:pbu_id and status='0' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":pbu_id", $uuid, PDO::PARAM_STR);
//        $command->bindParam(":version", $version, PDO::PARAM_STR);
        $detaillist = $command->queryAll();
        return $detaillist;
    }

    //根据模型或者没有模型的选择block
    public static function blockByModel($modellist,$program_id,$pbu_tag){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        if ($modellist != '') {
            if($project_id == '1453'){
                $condition = '';
                if($pbu_tag == '1'){
                    $condition.= " AND pbu_type LIKE '%PBU%' ";
                }else if($pbu_tag == '2'){
                    $condition.= " AND pbu_type LIKE '%PPVC%' ";
                }else if($pbu_tag == '3'){
                    $condition.= " AND pbu_type not LIKE '%PBU%' ";
                }
                if($modellist == '0'){
                    $sql = "SELECT
                            block
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '0' and status = '0' and project_id = '".$project_id."' $condition
                        GROUP BY 
                            block";
                }else{
                    $entity = explode('_',$modellist);
                    $model_id = $entity[0];
                    $sql = "SELECT
                            block
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '".$model_id."' and status = '0' and project_id = '".$project_id."'  $condition
                        GROUP BY 
                            block";
                }
            }else{
                if($modellist == '0'){
                    $sql = "SELECT
                            block
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '0' and status = '0' and project_id = '".$project_id."' and type = '".$pbu_tag."'
                        GROUP BY 
                            block";
                }else{
                    $entity = explode('_',$modellist);
                    $model_id = $entity[0];
                    $sql = "SELECT
                            block
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '".$model_id."' and status = '0' and project_id = '".$project_id."' and type = '".$pbu_tag."'
                        GROUP BY 
                            block";
                }
            }
        }
        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['block'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择block
    public static function blockByModelList($modellist,$program_id){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        if ($modellist != '') {
            $model_str = '(';
            $model_ar = explode(',',$modellist);
            foreach ($model_ar as $x => $y){
                $model = explode('_',$y);
                $model_str.="'".$model[0]."'".",";
            }
            $model_str = substr($model_str, 0, strlen($model_str) - 1);
            $model_str.= ')';
            $sql = "SELECT
                            block
                        FROM
                            pbu_info
                        WHERE 
                            model_id in $model_str and status = '0' and project_id = '".$project_id."'
                        GROUP BY 
                            block";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['block'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择level
    public static function levelByModel($modellist,$program_id,$pbu_tag){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        if ($modellist != '') {
            if($project_id == '1453'){
                $condition = '';
                if($pbu_tag == '1'){
                    $condition.= " AND a.pbu_type LIKE '%PBU%' ";
                }else if($pbu_tag == '2'){
                    $condition.= " AND a.pbu_type LIKE '%PPVC%' ";
                }else if($pbu_tag == '3'){
                    $condition.= " AND a.pbu_type not LIKE '%PBU%' ";
                }
                if($modellist == '0'){
                    $sql = "SELECT
                            a.level
                        FROM
                            pbu_info a
                        LEFT JOIN
                            pbu_level b
                        ON
                            a.level = b.level       
                        WHERE 
                            a.model_id = '0' and a.status = '0' and a.project_id = '".$project_id."' $condition
                        GROUP BY 
                            a.level
                        ORDER BY
                            b.level_index desc";
                }else{
                    $entity = explode('_',$modellist);
                    $model_id = $entity[0];
                    $sql = "SELECT
                            a.level
                        FROM
                            pbu_info a
                        LEFT JOIN
                            pbu_level b
                        ON
                            a.level = b.level
                        WHERE 
                            a.model_id = '".$model_id."' and a.status = '0' and a.project_id = '".$project_id."' $condition
                        GROUP BY 
                            a.level
                        ORDER BY
                            b.level_index desc";
                }
            }else{
                if($modellist == '0'){
                    $sql = "SELECT
                            a.level
                        FROM
                            pbu_info a
                        LEFT JOIN
                            pbu_level b
                        ON
                            a.level = b.level       
                        WHERE 
                            a.model_id = '0' and a.status = '0' and a.project_id = '".$project_id."' and a.type = '".$pbu_tag."'
                        GROUP BY 
                            a.level
                        ORDER BY
                            b.level_index desc";
                }else{
                    $entity = explode('_',$modellist);
                    $model_id = $entity[0];
                    $sql = "SELECT
                            a.level
                        FROM
                            pbu_info a
                        LEFT JOIN
                            pbu_level b
                        ON
                            a.level = b.level
                        WHERE 
                            a.model_id = '".$model_id."' and a.status = '0' and a.project_id = '".$project_id."' and a.type = '".$pbu_tag."'
                        GROUP BY 
                            a.level
                        ORDER BY
                            b.level_index desc";
                }
            }
        }

        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['level'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择part
    public static function partByModel($modellist,$program_id,$pbu_tag){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        if ($modellist != '') {
            if($project_id == '1453'){
                $condition = '';
                if($pbu_tag == '1'){
                    $condition.= " AND pbu_type LIKE '%PBU%' ";
                }else if($pbu_tag == '2'){
                    $condition.= " AND pbu_type LIKE '%PPVC%' ";
                }else if($pbu_tag == '3'){
                    $condition.= " AND pbu_type not LIKE '%PBU%' ";
                }
                if($modellist == '0'){
                    $sql = "SELECT
                            part
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '0' and status = '0' and part <> '' and project_id = '".$project_id."' $condition
                        GROUP BY 
                            part
                        ORDER BY
                            part+0";
                }else{
                    $entity = explode('_',$modellist);
                    $model_id = $entity[0];
                    $sql = "SELECT
                            part
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '".$model_id."' and status = '0' and part <> '' and project_id = '".$project_id."' $condition
                        GROUP BY 
                            part
                        ORDER BY
                            part+0";
                }
            }else{
                if($modellist == '0'){
                    $sql = "SELECT
                            part
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '0' and status = '0' and part <> '' and project_id = '".$project_id."' and type = '".$pbu_tag."'
                        GROUP BY 
                            part
                        ORDER BY
                            part+0";
                }else{
                    $entity = explode('_',$modellist);
                    $model_id = $entity[0];
                    $sql = "SELECT
                            part
                        FROM
                            pbu_info
                        WHERE 
                            model_id = '".$model_id."' and status = '0' and part <> '' and project_id = '".$project_id."' and type = '".$pbu_tag."'
                        GROUP BY 
                            part
                        ORDER BY
                            part+0";
                }
            }
        }

        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['part'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择level
    public static function levelByModelList($block,$program_id){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        $sql = "SELECT
                    level
                FROM
                    pbu_info
                WHERE 
                    block = '".$block."' and status = '0' and project_id = '".$project_id."'
                GROUP BY 
                    level
                ORDER BY
                    level+0";

        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['level'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择part
    public static function partByModelList($block,$level,$program_id){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        $sql = "SELECT
                    part
                FROM
                    pbu_info
                WHERE 
                    block = '".$block."' and level = '".$level."'  and status = '0' and project_id = '".$project_id."'
                GROUP BY 
                    part
                ORDER BY
                    part+0";

        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['part'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择unit
    public static function unitByModelList($block,$level,$part,$program_id){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        $sql = "SELECT
                    unit_nos
                FROM
                    pbu_info
                WHERE 
                    block = '".$block."' and level = '".$level."' and part = '".$part."' and status = '0' and project_id = '".$project_id."'
                GROUP BY 
                    unit_nos
                ORDER BY
                    unit_nos+0";

        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['unit_nos'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择name
    public static function nameByModelList($block,$level,$part,$unit,$program_id){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        $sql = "SELECT
                    pbu_name
                FROM
                    pbu_info
                WHERE 
                    block = '".$block."' and level = '".$level."' and part = '".$part."' and unit_nos = '".$unit."' and status = '0' and project_id = '".$project_id."'
                GROUP BY 
                    pbu_name
                ORDER BY
                    pbu_name+0";

        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['pbu_name'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    //根据模型或者没有模型的选择level
    public static function guidByModelList($block,$level,$part,$name,$program_id){
        $model = Program::model()->findByPk($program_id);
        if($model->father_proid == 0) {  //总包项目
            $project_id = $program_id;
        }else{
            $project_id = $model->root_proid;
        }
        $sql = "SELECT
                    pbu_id
                FROM
                    pbu_info
                WHERE 
                    block = '".$block."' and level = '".$level."' and part = '".$part."'  and pbu_name = '".$name."' and status = '0' and project_id = '".$project_id."'
               ";
        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();
        if(count($rs)>0){
            foreach($rs as $i => $j){
                $r[] = $j['pbu_id'];
            }
        }else{
            $r = array();
        }
        return $r;
    }

    public static function DataByMix($args){
        $data = array(
            "actual" => array(0,0,0,0),
            "plan" => array(0,0,0,0),
        );

        $date = $args['date'];
        $project_id = $args['program_id'];

        $args['clt_type'] = 'B';
        $rows_1 = self::AllBlockByType($args);

        $data['plan'][0] = $rows_1['plan'];
        $data['actual'][0] = $rows_1['actual'];

        $args['clt_type'] = 'D';
        $rows_2 = self::AllBlockByType($args);
        $data['plan'][1] = $rows_2['plan'];
        $data['actual'][1] = $rows_2['actual'];

        $args['clt_type'] = 'E';
        $rows_3 = self::AllBlockByType($args);
        $data['plan'][2] = $rows_3['plan'];
        $data['actual'][2] = $rows_3['actual'];

        $args['clt_type'] = 'F';
        $rows_4 = self::AllBlockByType($args);
        $data['plan'][3] = $rows_4['plan'];
        $data['actual'][3] = $rows_4['actual'];

        return $data;
    }

    public static function DataByPie($args){
        $type = $args['clt_type'];
        $template_id = $args['template_id'];
        $template_id = '49';
        $project_id = $args['program_id'];
        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $template_id
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones_T2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        $data = $rs['result']['stage_list'];
        $completed = 0;
        $pending = 0;
        if(count($data)>0) {
            if($type == 'C'){
                foreach ($data as $u => $v) {
                    if($v['stage_id']){
                        $stage_model = TaskStage::model()->findByPk($v['stage_id']);
                        $clt_type = $stage_model->clt_type;
                        if($clt_type == 'C'){
                            $pending+=$v['total'];
                        }
                        if($clt_type == 'B'){
                            $completed+=$v['total'];
                        }
                        if($clt_type == 'A'){
                            $completed+=$v['total'];
                        }
                    }
                    if($v['stage_id'] == ''){
                        $pending+=$v['total'];
                    }
                }
            }
            if($type == 'B'){
                foreach ($data as $u => $v) {
                    if($v['stage_id']){
                        $stage_model = TaskStage::model()->findByPk($v['stage_id']);
                        $clt_type = $stage_model->clt_type;
                        if($clt_type == 'C'){
                            $pending+=$v['total'];
                        }
                        if($clt_type == 'B'){
                            $pending+=$v['total'];
                        }
                        if($clt_type == 'A'){
                            $completed+=$v['total'];
                        }
                    }
                    if($v['stage_id'] == ''){
                        $pending+=$v['total'];
                    }
                }
            }
            if($type == 'A'){
                foreach ($data as $u => $v) {
                    if($v['stage_id']){
                        $stage_model = TaskStage::model()->findByPk($v['stage_id']);
                        $clt_type = $stage_model->clt_type;
                        if($clt_type == 'C'){
                            $pending+=$v['total'];
                        }
                        if($clt_type == 'B'){
                            $pending+=$v['total'];
                        }
                        if($clt_type == 'A'){
                            $pending+=$v['total'];
                        }
                    }
                    if($v['stage_id'] == ''){
                        $pending+=$v['total'];
                    }
                }
            }
            if($type == '0'){
                $pending = $rs['result']['pbu_count'];
            }
        }


        if($type == 'C'){
            $color = array('#EE00EE','#9C9C9C');
            $title = 'CARCASS';
        }else if($type == 'B'){
            $color = array('#FFA500','#9C9C9C');
            $title = 'FITTING OUT';
        }else if($type == 'A'){
            $color = array('#90EE90','#9C9C9C');
            $title = 'SITE';
        }else if($type == '0'){
            $color = array('#008B8B','#9C9C9C');
            $title = 'INSTALLED';
        }

        $r['completed'] = $completed;
        $r['pending'] = $pending;
        $r['color'] = $color;
        $r['title'] = $title;
        return $r;
    }

    public static function colorList(){
        $color = array(
            'B' => 'FFFF00FF',
            'D' => 'FFFF7F00',
            'E' => 'FF00FFFF',
            'F' => 'FF00FF00',
        );
        return $color;
    }

    public static function statusexcel($args){
        $clt_type = $args['clt_type'];
        $project_id = $args['project_id'];
        $model_id = $args['model_id'];
        $version = $args['version'];

        $color_list = self::colorList();
        $sql = "SELECT block FROM pbu_info where project_id = '".$project_id."' and model_id = '".$model_id."' and version = '".$version."' group by block order by block+0";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $block = $rows[0]['block'];
//        $sql_5 = "SELECT * FROM task_report where project_id = '1261' and block = '".$block."' ";
        //根据模板先循环task_stage   找到 report_plan 对应得stage_id
        if($clt_type != ''){
            $sql_5 = "SELECT * FROM task_stage where project_id = '".$project_id."' and report_flag ='1' and report_plan = '".$clt_type."' order by report_plan";
            $command_5 = Yii::app()->db->createCommand($sql_5);
            $rows_5 = $command_5->queryAll();
        }else{
            $sql_5 = "SELECT * FROM task_stage where project_id = '".$project_id."' and report_flag ='1' and report_plan <> '0' order by report_plan";
            $command_5 = Yii::app()->db->createCommand($sql_5);
            $rows_5 = $command_5->queryAll();
        }

        $sql_1 = "SELECT level FROM pbu_info where project_id = '".$project_id."' and model_id = '".$model_id."'  and version = '".$version."'  group by level order by level+0";
        $command_1 = Yii::app()->db->createCommand($sql_1);
        $rows_1 = $command_1->queryAll();

        $sql_2 = "SELECT unit_nos FROM pbu_info where project_id = '".$project_id."' and model_id = '".$model_id."'  and version = '".$version."'  group by unit_nos order by unit_nos+0";
        $command_2 = Yii::app()->db->createCommand($sql_2);
        $rows_2 = $command_2->queryAll();
        $r = array();
        foreach($rows_1 as $i => $j){
            $level = $j['level'];
            $r[$i]['level'] = $level;
            $r[$i]['data'] = '';
            foreach($rows_2 as $m => $n){
                $unit_nos = $n['unit_nos'];
                $s[$m]['unit'] = $unit_nos;
                $s[$m]['data'] = '';
                $sql_3 = "SELECT pbu_type FROM pbu_info where project_id = '".$project_id."' and model_id = '".$model_id."'  and version = '".$version."'  and level = '".$level."' and unit_nos = '".$unit_nos."' group by pbu_type ";
                $command_3 = Yii::app()->db->createCommand($sql_3);
                $rows_3 = $command_3->queryAll();
                foreach($rows_3 as $x => $y){
                    $pbu_type = $y['pbu_type'];
                    $o[$x]['pbu_type'] = $pbu_type;
                    $o[$x]['data'] = '';
                    $sql_4 = "SELECT pbu_id,pbu_name FROM pbu_info where project_id = '".$project_id."' and model_id = '".$model_id."'  and version = '".$version."'  and level = '".$level."' and unit_nos = '".$unit_nos."' and pbu_type ='".$pbu_type."' ";
                    $command_4 = Yii::app()->db->createCommand($sql_4);
                    $rows_4 = $command_4->queryAll();
                    $rows_4[0]['tag'] = '0';
                    $rows_4[0]['color'] = '0';
                    $pbu_id = $rows_4[0]['pbu_id'];

                    //用project_id+block判断如果task_report有值循环task_report
                    foreach($rows_5 as $aa => $stage_list){
                        $task_total = 0;
                        //已完成得任务且与阶段下得任务能够对应起来的数量
                        $task_record_cnt = 0;
                        $stage_id = $stage_list['stage_id'];
                        $report_plan = $stage_list['report_plan'];
                        //获取某阶段下的所有任务
//                        $sql_6 = "SELECT * FROM task_list  where stage_id = '".$stage_id."' and a.status= '0'";
//                        $command_6 = Yii::app()->db->createCommand($sql_6);
//                        $rows_6 = $command_6->queryAll();
                        //累加此阶段下得任务数
//                        $task_total = $task_total+count($rows_6);
                        //根据阶段关联模型记录表获取已经完成得任务
                        $sql_7 = "select count(1) as cnt
                                    from task_record a
                                    join task_record_model b ON a.check_id = b.check_id
                                    where a.project_id = '".$project_id."' and a.stage_id = '".$stage_id."' and a.status = '1' and b.guid ='".$pbu_id."' 
                                    group by a.task_id ";
                        $command_7 = Yii::app()->db->createCommand($sql_7);
                        $rows_7 = $command_7->queryAll();
                        if($rows_7[0]['cnt']>0){
                            $rows_4[0]['tag'] = '1';
                            $rows_4[0]['color'] = $color_list[$report_plan];
                        }
//                        foreach($rows_7 as $cc => $dd){
//                            foreach($rows_6 as $ee => $ff){
//                                if($dd['task_id'] == $ff['task_id']){
//                                    $task_record_cnt++;
//                                }
//                            }
//                        }
//                        if($task_total == $task_record_cnt){
//                            $rows_4[0]['tag'] = '1';
//                            $rows_4[0]['color'] = $color_list[$report_plan];
//                        }
                    }

                    $o[$x]['data'] = $rows_4;
                    $s[$m]['data'] = $o;
                }
                $r[$i]['data'] = $s;
            }
        }
        return $r;
    }

    public static function AllBlockByType($args){
        $clt_type = $args['clt_type'];
        $project_id = $args['program_id'];
        $date = Utils::DateToCn($args['date']);
        $sql_pbu = "SELECT * FROM pbu_info where project_id = '".$project_id."' and status ='0' ";
        $command_pbu = Yii::app()->db->createCommand($sql_pbu);
        $rows_pbu = $command_pbu->queryAll();
        if($clt_type != ''){
            $sql = "SELECT * FROM task_stage where project_id = '".$project_id."' and report_plan = '".$clt_type."' order by report_plan";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
        }else{
            $sql = "SELECT * FROM task_stage where project_id = '".$project_id."' and report_plan <> '0' order by report_plan";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
        }
        $task_record_cnt = 0;
        foreach($rows_pbu as $w => $v){
            foreach($rows as $i => $j){
                $stage_id = $j['stage_id'];
                //获取某阶段下的所有任务
                $sql_1 = "SELECT * FROM task_list  where stage_id = '".$stage_id."' and status= '0'";
                $command_1 = Yii::app()->db->createCommand($sql_1);
                $rows_1 = $command_1->queryAll();
                $task_cnt = count($rows_1);
                $cnt = 0;
                $sql_2 = "select a.task_id,count(1) as cnt,b.guid
                        from task_record a
                        join task_record_model b ON a.check_id = b.check_id
                        where a.project_id = '".$project_id."' and a.stage_id = '".$stage_id."' and a.status = '1' 
                        group by b.block, b.guid, a.task_id ";
                $command_2 = Yii::app()->db->createCommand($sql_2);
                $rows_2 = $command_2->queryAll();
                //循环阶段下的任务
                foreach($rows_2 as $m => $n){
                    if($v['pbu_id'] == $n['pbu_id']){
                        foreach($rows_1 as $x => $y){
                            if($n['task_id'] == $y['task_id']){
                                $cnt++;
                            }
                        }
                        $task_record_cnt++;
                    }
                    if($task_cnt == $cnt and  $task_cnt > 0){
//                        $task_record_cnt++;
                    }
                }
            }
        }

        $plan_cnt = 0;
        if($clt_type == 'B'){
            $sql_3 = "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and finish_b <= '".$date."'";
            $command_3 = Yii::app()->db->createCommand($sql_3);
            $rows_3 = $command_3->queryAll();
            $plan_cnt = (int)$rows_3[0]['cnt'];
        }else if($clt_type == 'D'){
            $sql_3= "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and finish_d <= '".$date."'";
            $command_3 = Yii::app()->db->createCommand($sql_3);
            $rows_3 = $command_3->queryAll();
            $plan_cnt = (int)$rows_3[0]['cnt'];
        }else if($clt_type == 'E'){
            $sql_3 = "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and start_e <= '".$date."'";
            $command_3 = Yii::app()->db->createCommand($sql_3);
            $rows_3 = $command_3->queryAll();
            $plan_cnt = (int)$rows_3[0]['cnt'];
        }else if($clt_type == 'F'){
            $sql_d = "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and finish_f <= '".$date."'";
            $command_3 = Yii::app()->db->createCommand($sql_d);
            $rows_3 = $command_3->queryAll();
            $plan_cnt = (int)$rows_3[0]['cnt'];
        }
        $rs['plan'] = $plan_cnt;
        $rs['actual'] = $task_record_cnt;
        return $rs;
    }

    //根据模板 对每栋楼统计
    public static function BlockData($args){
        $project_id = $args['project_id'];
        $date = $args['date'];
        //统计pbu_info得block
        $sql = "SELECT block FROM pbu_info where project_id = '".$project_id."' and status = '0' group by block order by block";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        foreach($rows as $o => $p){
            $rs[$p['block']]['total_b'] = 0;
            $rs[$p['block']]['total_d'] = 0;
            $rs[$p['block']]['total_e'] = 0;
            $rs[$p['block']]['total_f'] = 0;
            $rs[$p['block']]['plan_b'] = 0;
            $rs[$p['block']]['plan_d'] = 0;
            $rs[$p['block']]['plan_e'] = 0;
            $rs[$p['block']]['plan_f'] = 0;
        }
        //task_stage 根据模板id 找到对应得stage_id
        $sql_0 = "SELECT * FROM task_stage where project_id = '".$project_id."' and report_flag ='1' and report_plan <> '0' order by report_plan";
        $command_0 = Yii::app()->db->createCommand($sql_0);
        $rows_0 = $command_0->queryAll();
        //循环block
        foreach($rs as $block => $data){
            //循环阶段
            foreach($rows_0 as $i => $j){
                $stage_id = $j['stage_id'];
                //获取某阶段下的所有任务
                $sql_1 = "SELECT * FROM task_list  where stage_id = '".$stage_id."' and status= '0'";
                $command_1 = Yii::app()->db->createCommand($sql_1);
                $rows_1 = $command_1->queryAll();
                $task_cnt = count($rows_1);
                $task_record_cnt = 0;
                $sql_2 = "select a.task_id, b.block, b.guid, count(1) as cnt
                        from task_record a
                        join task_record_model b ON a.check_id = b.check_id
                        where a.project_id = '".$project_id."' and a.stage_id = '".$stage_id."' and a.status = '1' 
                        group by b.block, b.guid, a.task_id ";
                $command_2 = Yii::app()->db->createCommand($sql_2);
                $rows_2 = $command_2->queryAll();
                //循环任务记录
                foreach($rows_2 as $m => $n){
                    if($block == $n['block']){
                        $cnt = 0;
                        foreach($rows_1 as $x => $y){
                            if($n['task_id'] == $y['task_id']){
                                $cnt++;
                            }
                        }
                        if($task_cnt == $cnt and  $task_cnt > 0){
                            if($j['report_plan'] == 'B'){
                                $rs[$block]['total_b']++;
                            }else if($j['report_plan'] == 'D'){
                                $rs[$block]['total_d']++;
                            }else if($j['report_plan'] == 'E'){
                                $rs[$block]['total_e']++;
                            }else if($j['report_plan'] == 'F'){
                                $rs[$block]['total_f']++;
                            }
                        }
                    }
                }
            }
            $sql_b = "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and block='".$block."' and finish_b <= '".$date."'";
            $command_b = Yii::app()->db->createCommand($sql_b);
            $rows_b = $command_b->queryAll();
            $rs[$block]['plan_b'] = $rows_b[0]['cnt'];
            $sql_d= "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and block='".$block."' and finish_d <= '".$date."'";
            $command_d = Yii::app()->db->createCommand($sql_d);
            $rows_d = $command_d->queryAll();
            $rs[$block]['plan_d'] = $rows_d[0]['cnt'];
            $sql_e = "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and block='".$block."' and start_e <= '".$date."'";
            $command_e = Yii::app()->db->createCommand($sql_e);
            $rows_e = $command_e->queryAll();
            $rs[$block]['plan_e'] = $rows_e[0]['cnt'];
            $sql_f = "SELECT count(*) as cnt FROM pbu_info where project_id = '".$project_id."' and block='".$block."' and finish_f <= '".$date."'";
            $command_f = Yii::app()->db->createCommand($sql_f);
            $rows_f = $command_f->queryAll();
            $rs[$block]['plan_f'] = $rows_f[0]['cnt'];
        }
        foreach($rs as $a => $b){
            $actual[] = $b['total_b'];
            $actual[] = $b['total_d'];
            $actual[] = $b['total_e'];
            $actual[] = $b['total_f'];
            $plan[] = (int)$b['plan_b'];
            $plan[] = (int)$b['plan_d'];
            $plan[] = (int)$b['plan_e'];
            $plan[] = (int)$b['plan_f'];
            $block_list[] = $a.'-Carcass';
            $block_list[] = $a.'-Fitting out';
            $block_list[] = $a.'-Site';
            $block_list[] = $a.'-Installed';
        }
        $r['actual'] = $actual;
        $r['plan'] = $plan;
        $r['block'] = $block_list;
        return $r;
    }

    //删除数据
    public static function deletePbu($tag) {

        $model_list = explode('|', $tag);

        foreach($model_list as $i => $id){
            $model = RevitComponent::model()->findByPk($id);
            if ($model === null) {
                $r['msg'] = 'Invalid records！';
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            $sql = 'DELETE FROM pbu_info WHERE id=:id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":id", $id, PDO::PARAM_INT);

            $rs = $command->execute();

            if($rs ==2 || $rs == 1){
                $r['msg'] = Yii::t('common', 'success_delete');
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
                $r['msg'] = Yii::t('common', 'error_delete');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }

        return $r;
    }

    public static function deleteProjectPbu($pbu) {

        $status_sql = 'SELECT * FROM task_component_stats WHERE guid=:guid and project_id=:project_id';
        $command = Yii::app()->db->createCommand($status_sql);
        $command->bindParam(":guid", $pbu['guid'], PDO::PARAM_INT);
        $command->bindParam(":project_id", $pbu['project_id'], PDO::PARAM_INT);
        $rows = $command->queryAll();
        if(count($rows)>0){
            $r['msg'] = $pbu['guid'].' '.'有task记录';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }else{
            $sql = 'DELETE FROM pbu_info WHERE pbu_id=:pbu_id and project_id=:project_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":pbu_id", $pbu['guid'], PDO::PARAM_INT);
            $command->bindParam(":project_id", $pbu['project_id'], PDO::PARAM_INT);
            $rs = $command->execute();
            if($rs ==2 || $rs == 1){
                $r['msg'] = $pbu['guid'].' '.Yii::t('common', 'success_delete');
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
                $r['msg'] = $pbu['guid'].' '.Yii::t('common', 'error_delete');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
            return $r;
        }
    }

    public static function transferPbu($args){
        $guid_1 = $args['guid_1'];
        $guid_2 = $args['guid_2'];
        $entityid_1 = $args['entityid_1'];
        $entityid_2 = $args['entityid_2'];
        $program_id = $args['program_id'];
        //1.查询 两个guid 所属哪个模型
        //2.根据 guid,program_id 查询  task_record_model,task_record 得记录
        //3.循环记录进行修改 每修改一次 加入历史表
        //4.内循环  qa_checklist_record(link_check_id) qa_checklist_record_model
        $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and pbu_id=:pbu_id and status='0' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":pbu_id", $guid_1, PDO::PARAM_STR);
        $guid1_detail = $command->queryAll();
        $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and pbu_id=:pbu_id and status='0' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":pbu_id", $guid_2, PDO::PARAM_STR);
        $guid2_detail = $command->queryAll();
        //A构件任务记录
        $sql_1 = "select a.*,b.model_id,b.guid
                        from task_record a
                        join task_record_model b ON a.check_id = b.check_id
                        where a.project_id = '".$program_id."' and b.guid = '".$guid_1."' and a.status in ('0','1') ";
        $command_1 = Yii::app()->db->createCommand($sql_1);
        $rows_1 = $command_1->queryAll();
        //B构件任务记录
        $sql_2 = "select a.*,b.model_id,b.guid
                        from task_record a
                        join task_record_model b ON a.check_id = b.check_id
                        where a.project_id = '".$program_id."' and b.guid = '".$guid_2."' and a.status in ('0','1') ";
        $command_2 = Yii::app()->db->createCommand($sql_2);
        $rows_2 = $command_2->queryAll();

        $operator_id = Yii::app()->user->id;
//        $trans = Yii::app()->db->beginTransaction();
//        try {
//
//        }catch (Exception $e) {
//            $r['status'] = -1;
//            $r['msg'] = $e->getMessage();
//            $r['refresh'] = false;
//            $trans->rollback();
//        }

        if(count($rows_1) > 0){
            foreach($rows_1 as $i => $j){
                //B构件替换A构件任务表
                $sql = 'update task_record_model set model_id=:model_id,guid=:guid_1,name=:name,block=:block,level=:level,unit=:unit,id=:id,version=:version ';
                $sql.=' where check_id = :check_id and guid = :guid_2 ';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":model_id", $guid2_detail[0]['model_id'], PDO::PARAM_STR);
                $command->bindParam(":guid_1", $guid2_detail[0]['pbu_id'], PDO::PARAM_STR);
                $command->bindParam(":name", $guid2_detail[0]['pbu_name'], PDO::PARAM_STR);
                $command->bindParam(":block", $guid2_detail[0]['block'], PDO::PARAM_STR);
                $command->bindParam(":level", $guid2_detail[0]['level'], PDO::PARAM_STR);
                $command->bindParam(":unit", $guid2_detail[0]['unit'], PDO::PARAM_STR);
                $command->bindParam(":id", $entityid_2, PDO::PARAM_STR);
                $command->bindParam(":version", $guid2_detail[0]['version'], PDO::PARAM_STR);
                $command->bindParam(":check_id", $j['check_id'], PDO::PARAM_STR);
                $command->bindParam(":guid_2", $j['guid'], PDO::PARAM_STR);
                $rs = $command->execute();

                $record_time = date("Y-m-d H:i:s");
                //记录原A构件记录 以及B构件
                $sql = "insert into task_model_transfer (s_check_id,s_link_check_id,s_model_id,s_guid,t_model_id,t_guid,operator,record_time) values (:s_check_id,:s_link_check_id,:s_model_id,:s_guid,:t_model_id,:t_guid,:operator,:record_time)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":s_check_id", $j['check_id'], PDO::PARAM_STR);
                $command->bindParam(":s_link_check_id", $j['link_check_id'], PDO::PARAM_STR);
                $command->bindParam(":s_model_id", $j['model_id'], PDO::PARAM_STR);
                $command->bindParam(":s_guid", $j['guid'], PDO::PARAM_STR);
                $command->bindParam(":t_model_id", $guid2_detail[0]['model_id'], PDO::PARAM_STR);
                $command->bindParam(":t_guid", $guid2_detail[0]['pbu_id'], PDO::PARAM_STR);
                $command->bindParam(":operator", $operator_id, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $rs = $command->execute();

                //查询 qa_hecklist_record_model 关于A构件得相关记录
                if($j['link_check_id']){
                    $sql = "SELECT * FROM qa_checklist_record_model WHERE check_id=:check_id and guid=:guid ";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":check_id", $j['link_check_id'], PDO::PARAM_STR);
                    $command->bindParam(":guid", $j['guid'], PDO::PARAM_STR);
                    $qa_model = $command->queryAll();
                    if(count(!$qa_model)>0){
                        //修改 qa_checklist_record_model 相关记录
                        $sql = 'update qa_checklist_record_model set model_id=:model_id,guid=:guid_1,name=:name ';
                        $sql.=' where check_id = :check_id and guid = :guid_2 ';
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":model_id", $guid2_detail[0]['model_id'], PDO::PARAM_STR);
                        $command->bindParam(":guid_1", $guid2_detail[0]['pbu_id'], PDO::PARAM_STR);
                        $command->bindParam(":name", $guid2_detail[0]['pbu_name'], PDO::PARAM_STR);
                        $command->bindParam(":check_id", $j['check_id'], PDO::PARAM_STR);
                        $command->bindParam(":guid_2", $j['guid'], PDO::PARAM_STR);
                        $rs = $command->execute();
                    }
                }
            }
        }
        if(count($rows_2)>0){
            foreach($rows_2 as $x => $y){
                //A构件替换B构件任务表
                $sql = 'update task_record_model set model_id=:model_id,guid=:guid_2,name=:name,block=:block,level=:level,unit=:unit,id=:id,version=:version ';
                $sql.=' where check_id = :check_id and guid = :guid_1 ';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":model_id", $guid1_detail[0]['model_id'], PDO::PARAM_STR);
                $command->bindParam(":guid_2", $guid1_detail[0]['pbu_id'], PDO::PARAM_STR);
                $command->bindParam(":name", $guid1_detail[0]['pbu_name'], PDO::PARAM_STR);
                $command->bindParam(":block", $guid1_detail[0]['block'], PDO::PARAM_STR);
                $command->bindParam(":level", $guid1_detail[0]['level'], PDO::PARAM_STR);
                $command->bindParam(":unit", $guid1_detail[0]['unit'], PDO::PARAM_STR);
                $command->bindParam(":id", $entityid_1, PDO::PARAM_STR);
                $command->bindParam(":version", $guid1_detail[0]['version'], PDO::PARAM_STR);
                $command->bindParam(":check_id", $y['check_id'], PDO::PARAM_STR);
                $command->bindParam(":guid_1", $y['guid'], PDO::PARAM_STR);
                $rs = $command->execute();

                $record_time = date("Y-m-d H:i:s");
                //记录原A构件记录 以及B构件
                $sql = "insert into task_model_transfer (s_check_id,s_link_check_id,s_model_id,s_guid,t_model_id,t_guid,operator,record_time) values (:s_check_id,:s_link_check_id,:s_model_id,:s_guid,:t_model_id,:t_guid,:operator,:record_time)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":s_check_id", $y['check_id'], PDO::PARAM_STR);
                $command->bindParam(":s_link_check_id", $y['link_check_id'], PDO::PARAM_STR);
                $command->bindParam(":s_model_id", $y['model_id'], PDO::PARAM_STR);
                $command->bindParam(":s_guid", $y['guid'], PDO::PARAM_STR);
                $command->bindParam(":t_model_id", $guid1_detail[0]['model_id'], PDO::PARAM_STR);
                $command->bindParam(":t_guid", $guid1_detail[0]['pbu_id'], PDO::PARAM_STR);
                $command->bindParam(":operator", $operator_id, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $rs = $command->execute();

                //查询 qa_hecklist_record_model 关于A构件得相关记录
                if($j['link_check_id']){
                    $sql = "SELECT * FROM qa_checklist_record_model WHERE check_id=:check_id and guid=:guid ";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":check_id", $y['link_check_id'], PDO::PARAM_STR);
                    $command->bindParam(":guid", $y['guid'], PDO::PARAM_STR);
                    $qa_model = $command->queryAll();
                    if(count(!$qa_model)>0){
                        //修改 qa_checklist_record_model 相关记录
                        $sql = 'update qa_checklist_record_model set model_id=:model_id,guid=:guid_2,name=:name ';
                        $sql.=' where check_id = :check_id and guid = :guid_1 ';
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":model_id", $guid1_detail[0]['model_id'], PDO::PARAM_STR);
                        $command->bindParam(":guid_2", $guid1_detail[0]['pbu_id'], PDO::PARAM_STR);
                        $command->bindParam(":name", $guid1_detail[0]['pbu_name'], PDO::PARAM_STR);
                        $command->bindParam(":check_id", $y['check_id'], PDO::PARAM_STR);
                        $command->bindParam(":guid_1", $y['guid'], PDO::PARAM_STR);
                        $rs = $command->execute();
                    }
                }
            }
        }
        $r['msg'] = Yii::t('common', 'success_set');
        $r['status'] = 1;
        $r['refresh'] = true;
        
        return $r;
     }

    public static function GetOriginal($id,$type){
        $list = explode('|',$id);
        $val = '';
        foreach ($list as $i => $j){
            $model = RevitComponent::model()->findByPk($j);
            $value = $model->$type;
            if($val == ''){
                $val = $value;
            }
            if($val != $value){
                $r['status'] = '-1';
                return $r;
            }
        }
        $r['value'] = $value;
        $r['status'] = '1';
        return $r;
    }

    public static function SetOriginal($id,$type,$replace){
        $list = explode('|',$id);
        foreach ($list as $i => $j){
            $model = RevitComponent::model()->findByPk($j);
            $version = $model->version;
            $model->$type = $replace;
            $model->version = (int)$version+1;
            $model->save();
            $block = $model->block;
            $level = $model->level;
            $part = $model->part;
            $unit_nos = $model->unit_nos;
            $unit_type = $model->unit_type;
            $pbu_type = $model->pbu_type;
            $pbu_name = '';
            if($block){
                $pbu_name.= $block;
            }
            if($level){
                $pbu_name.= '-'.$level;
            }
            if($part){
                $pbu_name.= '-'.$part;
            }
            if($unit_nos){
                $pbu_name.= '-'.$unit_nos;
            }
            if($pbu_type){
                $pbu_name.= '-'.$pbu_type;
            }
            $model->pbu_name = $pbu_name;
            $model->save();
        }

        $r['msg'] = Yii::t('common', 'success_set');
        $r['status'] = 1;
        $r['refresh'] = true;
        return $r;
    }

    public static function CreateNoModel($args){
        $module_type_name = $args['modules_type_name'];
        $len = strlen($args['quantity']);
        $quantity = (int)$args['quantity'];
        $project_id = $args['project_id'];
        $model_id = '0';
        try {
            $block = '';
            $level = '';
            $status = '0';
            $version = '';
            $trans = Yii::app()->db->beginTransaction();
            for($i=1;$i<=$quantity;$i++){
                $model_index = str_pad($i,$len,"0",STR_PAD_LEFT);
                $unit_type = $module_type_name;
                $element_name = $unit_type.' '.$model_index;
                $record_time = date('Y-m-d H:i:s');
                $time = strtotime($record_time);
                $pbu_id = 'MID'.$model_index.$time;
                $user_id = Yii::app()->user->id;
                $sub_sql = 'INSERT INTO pbu_info(project_id,model_id,pbu_id,block,level,pbu_type,pbu_name,version,user_id,status,record_time) VALUES(:project_id,:model_id,:pbu_id,:block,:level,:pbu_type,:pbu_name,:version,:user_id,:status,:record_time)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                $command->bindParam(":model_id", $model_id, PDO::PARAM_STR);
                $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_STR);
                $command->bindParam(":block", $block, PDO::PARAM_STR);
                $command->bindParam(":level", $level, PDO::PARAM_STR);
                $command->bindParam(":pbu_type", $unit_type, PDO::PARAM_STR);
                $command->bindParam(":pbu_name", $element_name, PDO::PARAM_STR);
                $command->bindParam(":version", $version, PDO::PARAM_STR);
                $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
                $rs = $command->execute();
                $r['msg'] = Yii::t('common','success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }
}