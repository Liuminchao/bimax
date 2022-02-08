<?php

/**
 * 项目区域
 * @author LiuMinchao
 */
class ProgramLocation extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_location_q';
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

    //Type
    public static function TagList(){
        $type = array(
            'A' => 'Block',
            'B' => 'Level',
            'C' => 'Unit',
        );
        return $type;
    }

    //Type
    public static function TypeList(){
        $type = array(
            'block' => 'A',
            'level' => 'B',
            'unit' => 'C',
        );
        return $type;
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
//        var_dump($args);

        if ($args['project_id'] != '') {
            $project_id = $args['project_id'];
            $condition.= " a.program_id='$project_id' ";
        }

        if ($args['block'] != '') {
            $block = $args['block'];
            $condition.= " AND a.block='$block' ";
        }

        if ($args['level'] != '') {
            $level = $args['level'];
            $condition.= " AND a.secondary_region like '%$level%' ";
        }

        if ($args['unit'] != '') {
            $unit = $args['unit'];
            $condition.= " AND b.unit like '%$unit%' ";
        }

        if ($condition) {
            $condition.= " AND a.status = '0' AND b.status='0' ";
        }

        $sql = "select b.id,a.block,a.secondary_region,a.type,b.project_id,b.unit,b.doc_id from 
                    bac_program_block_q a
                left join
                    bac_program_location_q b
                on 
                    a.block = b.block and a.secondary_region = b.level and a.program_id = b.project_id
                left join 
                    pbu_level c
                on a.secondary_region = c.level              
                where $condition  order by a.block,c.level_index,b.unit+0 asc";

//        var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

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
     * 按 项目,block
     */
    public static function detailTypeList($project_id,$block,$tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "select a.block,a.secondary_region,a.type,b.unit from 
                    bac_program_block_q a
                left join
                    bac_program_location_q b 
                on 
                    a.block = b.block and a.secondary_region = b.level      
                where type = '".$tag."' and project_id = '".$root_proid."' and block='$block'  group by a.secondary_region+0 desc,b.unit ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $r = array();
        if(count($rows)>0){
            foreach($rows as $i => $j){
//                $r[$j['level']][$j['unit_nos']]['id'] = $j['id'];
                $r[$j['secondary_region']][$j['unit']]['type'] = $j['type'];
            }
        }

        return $r;
    }

    //设置part
    public static function SetType($project_id,$block,$level){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach($level as $i => $j){
                $sub_sql = "UPDATE bac_program_block_q SET type=:type WHERE  program_id = :program_id and block=:block and secondary_region=:secondary_region ";
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":type", $j, PDO::PARAM_STR);
                $command->bindParam(":program_id", $root_proid, PDO::PARAM_STR);
                $command->bindParam(":block", $block, PDO::PARAM_STR);
                $command->bindParam(":secondary_region", $i, PDO::PARAM_STR);
                $rs = $command->execute();
            }
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

    //查询项目区域
    public static function regionList($project_id){

        $sql = "SELECT * FROM bac_program_location WHERE status=0 and program_id = '".$project_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        if (count($rows) > 0) {
//            foreach ($rows as $key => $row) {
//                $rs[$row['type']][] = $row['value'];
//            }
//        }
        return $rows;

    }

    //查询项目区域
    public static function regionListByType($project_id,$type){

        $sql = "SELECT type,value,id FROM dev_project_position WHERE status=0 and project_id = '".$project_id."' and type = '".$type."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['value']] = $row['value'];
            }
        }
        return $rs;

    }

    //查询某一具体位置信息
    public static function detailList($id){

        $sql = "SELECT * FROM bac_program_location WHERE  id = '".$id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }

    //插入区域数据
    public static function InsertLocation($location,$project_id){
//        var_dump($location);
//        var_dump($project_id);
//        exit;
        $status = self::STATUS_NORMAL;
        $type_list = self::TypeList();
        $trans = Yii::app()->db->beginTransaction();
        try {
            $doc_id = '';
            $doc_name = '';
            if($location['doc_id']){
                $doc_list = explode('/',$location['doc_id']);
                $doc_cnt = count($doc_list);
                $doc_id = $doc_list[$doc_cnt-1];
            }
            if($location['doc_name']){
                $doc_name = $location['doc_name'];
            }
            $sub_sql = 'INSERT INTO bac_program_location_q (project_id,block,level,unit,doc_name,doc_id,status) VALUES(:project_id,:block,:level,:unit,:doc_name,:doc_id,:status)';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":block", $location['block'], PDO::PARAM_STR);
            $command->bindParam(":level", $location['level'], PDO::PARAM_STR);
            $command->bindParam(":unit", $location['unit'], PDO::PARAM_STR);
            $command->bindParam(":doc_name", $doc_name, PDO::PARAM_STR);
            $command->bindParam(":doc_id", $doc_id, PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
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
        return $r;
    }

    //编辑区域数据
    public static function EditLocation($location){
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
            $location_model = ProgramLocation::model()->findByPk($location['id']);
            $block_old = $location_model->block;
            $level_old = $location_model->level;
            $unit_old = $location_model->unit;
            $project_id = $location_model->project_id;

            $sub_sql = 'UPDATE bac_program_location_q SET block=:block,level=:level,unit=:unit WHERE id=:id';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":block", $location['block'], PDO::PARAM_STR);
            $command->bindParam(":level", $location['level'], PDO::PARAM_STR);
            $command->bindParam(":unit", $location['unit'], PDO::PARAM_STR);
            $command->bindParam(":id", $location['id'], PDO::PARAM_INT);
            $rs = $command->execute();

            $sql = "SELECT * FROM pbu_info WHERE status=0 and project_id = :project_id and block =:block and level=:level and unit_nos=:unit_nos ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":block", $block_old, PDO::PARAM_STR);
            $command->bindParam(":level", $level_old, PDO::PARAM_STR);
            $command->bindParam(":unit_nos", $unit_old, PDO::PARAM_STR);
            $rows = $command->queryAll();

            if(count($rows)>0){
                foreach($rows as $x => $y){
                    $pbu_name = '';
                    if($y['block']){
                        $pbu_name.=$y['block'];
                    }
                    if($location['level']){
                        $pbu_name.='-'.$location['level'];
                    }
                    if($y['part']){
                        $pbu_name.='-'.$y['part'];
                    }
                    if($location['unit']){
                        $pbu_name.='-'.$location['unit'];
                    }
                    if($y['pbu_type'] != '' && $y['pbu_type'] != '--'){
                        $pbu_name.='-'.$y['pbu_type'];
                    }
                    $sub_sql = 'UPDATE pbu_info SET level=:level,unit_nos=:unit,pbu_name=:pbu_name WHERE id=:id';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":level", $location['level'], PDO::PARAM_STR);
                    $command->bindParam(":unit", $location['unit'], PDO::PARAM_STR);
                    $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_STR);
                    $command->bindParam(":id", $y['id'], PDO::PARAM_INT);
                    $rs = $command->execute();
                }
            }

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
    public static function DelLocation($id){
        $status = self::STATUS_DISABLE;
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sub_sql = 'UPDATE bac_program_location_q SET status=:status WHERE id=:id';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":id", $id, PDO::PARAM_INT);
            $rs = $command->execute();
            //删除location的同时，遍历pbu_info 删除对应的构件
            $location_model = ProgramLocation::model()->findByPk($id);
            $block_old = $location_model->block;
            $level_old = $location_model->level;
            $unit_old = $location_model->unit;
            $project_id = $location_model->project_id;

            $sql = "SELECT * FROM pbu_info WHERE status=0 and project_id = :project_id and block =:block and level=:level and unit_nos=:unit_nos ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":block", $block_old, PDO::PARAM_STR);
            $command->bindParam(":level", $level_old, PDO::PARAM_STR);
            $command->bindParam(":unit_nos", $unit_old, PDO::PARAM_STR);
            $rows = $command->queryAll();

            if(count($rows)>0){
                foreach($rows as $x => $y){
                    $sql = 'DELETE FROM pbu_info WHERE id=:id';
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":id", $y['id'], PDO::PARAM_INT);
                    $rs = $command->execute();
                }
            }

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
    public static function EditDoc($id,$doc_id,$doc_name){
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sub_sql = 'UPDATE bac_program_location_q  SET doc_id=:doc_id,doc_name=:doc_name WHERE id=:id';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":doc_id", $doc_id, PDO::PARAM_STR);
            $command->bindParam(":doc_name", $doc_name, PDO::PARAM_STR);

            $command->bindParam(":id", $id, PDO::PARAM_INT);
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

    //同步区域数据
    public static function SyncData($project_id){
        $status = self::STATUS_NORMAL;
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "SELECT * FROM bac_program_block_q WHERE status=0 and program_id = :program_id order by block,secondary_region asc ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $sql = "SELECT * FROM pbu_info WHERE project_id = :project_id and block = :block and level=:level and unit_nos<> '' ";//var_dump($sql);
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                    $command->bindParam(":block", $row['block'], PDO::PARAM_STR);
                    $command->bindParam(":level", $row['secondary_region'], PDO::PARAM_STR);
                    $rs = $command->queryAll();
                    if(count($rs)>0){
                        foreach ($rs as $i => $j){
                            if($j['unit_nos']){
                                $doc_id = '';
                            }else{
                                $doc_id = $row['drawing_id'];
                            }
                            $sub_sql = 'INSERT INTO bac_program_location_q (project_id,block,level,unit,doc_id,status) VALUES(:project_id,:block,:level,:unit,:doc_id,:status)';
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                            $command->bindParam(":block", $j['block'], PDO::PARAM_STR);
                            $command->bindParam(":level", $j['level'], PDO::PARAM_STR);
                            $command->bindParam(":unit", $j['unit_nos'], PDO::PARAM_STR);
                            $command->bindParam(":doc_id", $doc_id, PDO::PARAM_STR);
                            $command->bindParam(":status", $status, PDO::PARAM_STR);
                            $rs = $command->execute();
                        }
                    }
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

    public static function SaveDrawing($id,$drawing_id){
        $trans = Yii::app()->db->beginTransaction();
        try {
        $location_model = ProgramLocation::model()->findByPk($id);
        $location_model->doc_id = $drawing_id;
        $location_model->save();

        $r['msg'] = Yii::t('common','success_set');
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

    //展示level
    public static function locationLevel($project_id,$block){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT secondary_region,type FROM bac_program_block_q WHERE program_id = '".$root_proid."' and  block='$block'  group by secondary_region order by secondary_region+0 desc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //展示unit
    public static function locationUnit($project_id,$block){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT unit FROM bac_program_location_q WHERE project_id = '".$root_proid."' and block='$block' group by unit order by unit+0 asc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //更新unit
    public static function SetUnit($project_id,$block,$unit_list,$unit_old_list){
        $trans = Yii::app()->db->beginTransaction();
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        try {
            foreach($unit_list as $i => $j){
                if($j){
                    $sub_sql = 'UPDATE bac_program_location_q SET unit=:unit WHERE unit=:unit_old and project_id=:project_id and block=:block';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
                    $command->bindParam(":block", $block, PDO::PARAM_INT);
                    $command->bindParam(":unit", $j, PDO::PARAM_INT);
                    $command->bindParam(":unit_old", $unit_old_list[$i], PDO::PARAM_INT);
                    $rs = $command->execute();
                }
            }
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

}
