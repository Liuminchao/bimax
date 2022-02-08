<?php

/**
 * 分包项目区域
 * @author LiuMinchao
 */
class ProgramRegion extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_block_q';
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
            self::STATUS_NORMAL => 'label-success', //已启用
            self::STATUS_DISABLE => ' label-danger', //未启用
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
    //查询项目区域
    public static function regionList($program_id){
         
        $sql = "SELECT * FROM bac_program_block_q WHERE status=0 and program_id = '".$program_id."' order by block,secondary_region+0 asc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
//                    $rs[$row['location']][] = $row['secondary_region'];
//                    $rs[$row['location']]['block'] = $row['block'];
                    $rs[$row['block']][$row['secondary_region']]['drawing_id'] = $row['drawing_id'];
                    $rs[$row['block']][$row['secondary_region']]['type'] = $row['type'];
                }
            }
        return $rs;

    }
    //展示项目区域
    public static function regionShow($program_id){

        $sql = "SELECT location,block,secondary_region FROM bac_program_block_q WHERE status=0 and program_id = '".$program_id."'order by location asc ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['block']][] = $row['secondary_region'];
            }
        }
        return $rs;

    }

    //展示项目区域固定位置
    public static function locationShow($program_id){

        $sql = "SELECT location,block,secondary_region FROM bac_program_block_q WHERE status=0 and program_id = '".$program_id."' order by location asc ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['location']][] = $row['secondary_region'];
            }
        }
        return $rs;

    }

    //展示项目区域固定位置
    public static function locationBlock($program_id){

        $sql = "SELECT location,block FROM bac_program_block_q WHERE status=0 and program_id = '".$program_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['location']]['block'] = $row['block'];
            }
        }
        return $rs;

    }
    /**
     * 查询总包项目下的分包项目
     */
    public static function ScProgram($program_id){
        $sql = "select program_id
                  from bac_program
                 where  root_proid =".$program_id." and status=00 ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }

    //插入区域数据
    public static function InsertRegion_New($args){
        $status = self::STATUS_NORMAL;
        $program = $args['program'];
        $block = $args['block'];
        $level = $args['level'];
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = 'DELETE FROM bac_program_block_q WHERE program_id=:program_id ';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program[0], PDO::PARAM_INT);
            $rs = $command->execute();

            foreach($block as $i => $j){
//                var_dump($block);
//                var_dump($i);
//                var_dump($j);

                foreach($level[$i] as $x => $y){
//                    var_dump($level[$i]);
//                    var_dump($x);
//                    var_dump($y);
//                    exit;
                    if(array_key_exists('region_1',$y)){
                        $tag = '';
                        if($y['region_1'] != '' && $y['region_2'] != ''){
                            if(substr($y['region_1'],0,1) == 'L'){
                                $tag = 'L';
                                $region_1 = substr($y['region_1'],1);
                            }else{
                                $region_1 = $y['region_1'];
                            }
                            if(substr($y['region_2'],0,1) == 'L'){
                                $region_2 = substr($y['region_2'],1);
                            }else{
                                $region_2 = $y['region_2'];
                            }
                            $region_1 = (int)$region_1;
                            $region_2 = (int)$region_2;
                            $type = '1';
                            for($region_1;$region_1<=$region_2;$region_1++){
                                $region = $tag.$region_1;
                                $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,secondary_region,drawing_id,type,status) VALUES(:program_id,:block,:secondary_region,:drawing_id,:type,:status)';
                                $command = Yii::app()->db->createCommand($sub_sql);
                                $command->bindParam(":program_id", $program[0], PDO::PARAM_INT);
                                $command->bindParam(":block", $j[0], PDO::PARAM_INT);
                                $command->bindParam(":secondary_region", $region, PDO::PARAM_INT);
                                $command->bindParam(":drawing_id", $y['file'], PDO::PARAM_INT);
                                $command->bindParam(":type", $type, PDO::PARAM_INT);
                                $command->bindParam(":status", $status, PDO::PARAM_INT);
                                $rs = $command->execute();
                            }
                        }
                    }
                    if(array_key_exists('region',$y)){
                        $type = '0';
                        $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,secondary_region,drawing_id,type,status) VALUES(:program_id,:block,:secondary_region,:drawing_id,:type,:status)';
                        $command = Yii::app()->db->createCommand($sub_sql);
                        $command->bindParam(":program_id", $program[0], PDO::PARAM_INT);
                        $command->bindParam(":block", $j[0], PDO::PARAM_INT);
                        $command->bindParam(":secondary_region", $y['region'], PDO::PARAM_INT);
                        $command->bindParam(":drawing_id", $y['file'], PDO::PARAM_INT);
                        $command->bindParam(":type", $type, PDO::PARAM_INT);
                        $command->bindParam(":status", $status, PDO::PARAM_INT);
                        $rs = $command->execute();
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


    //插入区域数据
    public static function InsertRegion($region,$arr){
        $status = self::STATUS_NORMAL;
        $exist_data = ProgramRegion::model()->count('program_id=:program_id and location=:location', array('program_id' => $region['program_id'],'location' => $region['location']));
        if ($exist_data != 0) {
            $sql = 'DELETE FROM bac_program_block_q WHERE program_id=:program_id and location=:location';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $region['program_id'], PDO::PARAM_INT);
            $command->bindParam(":location", $region['location'], PDO::PARAM_STR);
            $rs = $command->execute();
        }
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach($arr as $item => $data){
                if($data != '') {
                    $region['tag'] = trim($region['tag']);
                    $region['location'] = trim($region['location']);
                    $data = trim($data);
                    $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":program_id", $region['program_id'], PDO::PARAM_INT);
                    $command->bindParam(":block", $region['tag'], PDO::PARAM_INT);
                    $command->bindParam(":location", $region['location'], PDO::PARAM_INT);
                    $command->bindParam(":secondary_region", $data, PDO::PARAM_INT);
                    $command->bindParam(":status", $status, PDO::PARAM_INT);
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

    //插入总包区域数据
    public static function InsertMcRegion($args,$program_id) {

        if ($program_id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        if (empty($args)) {
//            $r['msg'] = Yii::t('proj_project', 'block is null');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        $exist_data = ProgramRegion::model()->count('program_id=:program_id', array('program_id' => $program_id));
//        var_dump($exist_data);
//        exit;
        if ($exist_data != 0) {
            $sql = 'DELETE FROM bac_program_block_q WHERE program_id=:program_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
            $rs = $command->execute();
        }
        $sc_program = self::ScProgram($program_id);
        $trans = Yii::app()->db->beginTransaction();

        try {

            $status = self::STATUS_NORMAL;
//            $cnt = count($args['block']);

            foreach($args as $block => $region) {
//                var_dump($block);
//                var_dump($region);
//                exit;
                $block = trim($block);
                $region = trim($region);
                foreach($region as $num => $content){
                    $content = trim($content);
                    if($region['tag'] != ''){
                        $tag = $region['tag'];
                    }else{
                        $r['msg'] = Yii::t('proj_project', 'error_block_is_null');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    if(is_numeric($num)) {
                        if ($content != '') {
                            $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,location,secondary_region,drawing_id,status) VALUES(:program_id,:block,:location,:secondary_region,:drawing_id,:status)';
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                            $command->bindParam(":block", $tag, PDO::PARAM_INT);
                            $command->bindParam(":location", $block, PDO::PARAM_INT);
                            $command->bindParam(":secondary_region", $content, PDO::PARAM_STR);
                            $command->bindParam(":drawing_id", $content, PDO::PARAM_STR);
                            $command->bindParam(":status", $status, PDO::PARAM_INT);
                            $rs = $command->execute();
                        }
                    }
                    //分包也添加相同区域
//                    foreach($sc_program as $cnt => $id){
//                        $exist_data = ProgramRegion::model()->count('program_id=:program_id', array('program_id' => $id));
//                        if ($exist_data != 0) {
//                            $sql = 'DELETE FROM bac_program_block WHERE program_id=:program_id';
//                            $command = Yii::app()->db->createCommand($sql);
//                            $command->bindParam(":program_id", $id, PDO::PARAM_INT);
//                            $rs = $command->execute();
//                        }
//                        if(is_numeric($num)) {
//                            if ($content != '') {
//                                $sub_sql = 'INSERT INTO bac_program_block(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
//                                $command = Yii::app()->db->createCommand($sub_sql);
//                                $command->bindParam(":program_id", $id, PDO::PARAM_INT);
//                                $command->bindParam(":block", $tag, PDO::PARAM_INT);
//                                $command->bindParam(":location", $block, PDO::PARAM_INT);
//                                $command->bindParam(":secondary_region", $content, PDO::PARAM_INT);
//                                $command->bindParam(":status", $status, PDO::PARAM_INT);
//                                $rs = $command->execute();
//                            }
//                        }
//                    }
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
    //插入分包区域数据
    public static function InsertScRegion($args,$program_id) {

        if ($program_id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        if (empty($args)) {
//            $r['msg'] = Yii::t('proj_project', 'block is null');
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        $exist_data = ProgramRegion::model()->count('program_id=:program_id', array('program_id' => $program_id));
//        var_dump($exist_data);
//        exit;
        if ($exist_data != 0) {
            $sql = 'DELETE FROM bac_program_block_q WHERE program_id=:program_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
            $rs = $command->execute();
        }

        $trans = Yii::app()->db->beginTransaction();

        try {

            $status = self::STATUS_NORMAL;
//            $cnt = count($args['block']);

            foreach($args as $block => $region) {
//                var_dump($block);
//                var_dump($region);
//                exit;
                $block = trim($block);
                $region = trim($region);
                foreach($region as $num => $content){
                    $content = trim($content);
                    if($region['block'] != ''){
                        $tag = $region['block'];
                    }
                    if(is_numeric($num)) {
                        if ($content != '') {
                            $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,location,secondary_region,status) VALUES(:program_id,:block,:location,:secondary_region,:status)';
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                            $command->bindParam(":block", $tag, PDO::PARAM_INT);
                            $command->bindParam(":location", $block, PDO::PARAM_INT);
                            $command->bindParam(":secondary_region", $content, PDO::PARAM_INT);
                            $command->bindParam(":status", $status, PDO::PARAM_INT);
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

    public static function saveLevelDraw($project_id,$block,$level){

        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = 'UPDATE bac_program_block_q  SET drawing_id= "" WHERE program_id=:program_id and block=:block';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":block", $block, PDO::PARAM_STR);
            $rs = $command->execute();

            foreach($level as $level_index => $level_list){
                $from = (int)$level_list['level_from'];
                $to = (int)$level_list['level_to'];
                $drawing_id = $level_list['file_path'];
                $drawing_id_1 = '|'.$drawing_id;
                for($from;$i=$to;$from++){
                    $sql = "update bac_program_block_q set drawing_id = ( case when ( drawing_id ='') then :drawing_id else CONCAT(drawing_id,:drawing_id_1) end ) where program_id=:program_id and block=:block and secondary_region=:secondary_region ";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":drawing_id", $drawing_id, PDO::PARAM_STR);
                    $command->bindParam(":drawing_id_1", $drawing_id_1, PDO::PARAM_STR);
                    $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
                    $command->bindParam(":block", $block, PDO::PARAM_STR);
                    $command->bindParam(":secondary_region", $from, PDO::PARAM_STR);
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

    public static function levelDrawList($project_id,$block){
        $sql = "SELECT * FROM bac_program_block_q WHERE status=0 and program_id = :program_id and block = :block order by secondary_region asc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
        $command->bindParam(":block", $block, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if($row['drawing_id']){
                    $rs[$row['secondary_region']] = $row['drawing_id'];
                    $drawing_list = explode('|',$row['drawing_id']);
                    foreach ($drawing_list as $index => $drawing_id){
                        $drawing[$drawing_id] = $drawing_id;
                    }
                }
            }
        }

        if(count($drawing)>0){
            foreach($drawing as $id => $drawing_id){
                $drawing_model =ProgramDrawing::model()->findByPk($drawing_id);
                $drawing_name = $drawing_model->drawing_name;
                $drawing_name = substr($drawing_name,8);
                $from = '';
                $to = '';
                foreach ($rs as $level => $drawing_str){
                    if(strpos($drawing_str,$drawing_id) !== false){
                        if($from == ''){
                            $from = $level;
                        }
                        $to = $level;
                    }
                }
                $r[$drawing_id]['drawing_name'] = $drawing_name;
                $r[$drawing_id]['from'] = $from;
                $r[$drawing_id]['to'] = $to;
            }
        }else{
            $r = array();
        }

        return $r;
    }

    public static function saveUnitDraw($project_id,$block,$unit){
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = 'UPDATE bac_program_location_q  SET doc_id= "" WHERE project_id=:project_id and block=:block';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":block", $block, PDO::PARAM_STR);
            $rs = $command->execute();

            foreach($unit as $unit_index => $unit_list){
                $level = $unit_list['level'];
                $unit = $unit_list['unit'];
                $drawing_id = $unit_list['file_path'];
                $drawing_id_1 = '|'.$drawing_id;
                $sql = "update bac_program_location_q set doc_id = ( case when ( doc_id ='') then :doc_id else CONCAT(doc_id,:doc_id_1) end ) where project_id=:project_id and block=:block and level=:level and unit=:unit ";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":doc_id", $drawing_id, PDO::PARAM_STR);
                $command->bindParam(":doc_id_1", $drawing_id_1, PDO::PARAM_STR);
                $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                $command->bindParam(":block", $block, PDO::PARAM_STR);
                $command->bindParam(":level", $level, PDO::PARAM_STR);
                $command->bindParam(":unit", $unit, PDO::PARAM_STR);
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

    public static function unitDrawList($project_id,$block){
        $sql = "SELECT * FROM bac_program_location_q WHERE status=0 and project_id = :project_id and block = :block order by level asc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
        $command->bindParam(":block", $block, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if($row['doc_id']){
                    $rs[$row['level']] = $row['doc_id'];
                    $drawing_list = explode('|',$row['doc_id']);
                    foreach ($drawing_list as $index => $drawing_id){
                        $drawing_model =ProgramDrawing::model()->findByPk($drawing_id);
                        $drawing_name = $drawing_model->drawing_name;
                        $drawing_name = substr($drawing_name,8);
                        $drawing = array();
                        $drawing['drawing_id'] = $drawing_id;
                        $drawing['level'] = $row['level'];
                        $drawing['unit'] = $row['unit'];
                        $drawing['drawing_name'] = $drawing_name;
                        $res[] = $drawing;
                    }
                }
            }
        }else{
            $res = array();
        }

        return $res;
    }
    //bac_program_block_q+bac_program_location_q
    public static function saveLocation($location){
        $trans = Yii::app()->db->beginTransaction();
        try{
            $status = '0';
            $sql = "SELECT * FROM bac_program_block_q WHERE status=0 and program_id = :program_id and block = :block and secondary_region=:secondary_region";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $location['project_id'], PDO::PARAM_STR);
            $command->bindParam(":block", $location['block'], PDO::PARAM_STR);
            $command->bindParam(":secondary_region", $location['level'], PDO::PARAM_STR);
            $rows = $command->queryAll();
            if(count($rows) == 0){
                $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,type,secondary_region,drawing_id,status) VALUES(:program_id,:block,:type,:secondary_region,:drawing_id,:status)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":program_id", $location['project_id'], PDO::PARAM_INT);
                $command->bindParam(":block", $location['block'], PDO::PARAM_INT);
                $command->bindParam(":type", $location['type'], PDO::PARAM_INT);
                $command->bindParam(":secondary_region", $location['level'], PDO::PARAM_INT);
                $command->bindParam(":drawing_id", $location['drawing_id'], PDO::PARAM_INT);
                $command->bindParam(":status", $status, PDO::PARAM_INT);
                $rs = $command->execute();
            }
            if($location['unit']){
                $sql = "SELECT * FROM bac_program_location_q WHERE status=0 and project_id = :project_id and block = :block and level=:level and unit=:unit";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":project_id", $location['project_id'], PDO::PARAM_STR);
                $command->bindParam(":block", $location['block'], PDO::PARAM_STR);
                $command->bindParam(":level", $location['level'], PDO::PARAM_STR);
                $command->bindParam(":unit", $location['unit'], PDO::PARAM_STR);
                $rows = $command->queryAll();
                if(count($rows) == 0){
                    $sql = 'INSERT INTO bac_program_location_q (project_id,block,level,unit,doc_id,status) VALUES(:project_id,:block,:level,:unit,:doc_id,:status)';
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":project_id", $location['project_id'], PDO::PARAM_STR);
                    $command->bindParam(":block", $location['block'], PDO::PARAM_STR);
                    $command->bindParam(":level", $location['level'], PDO::PARAM_STR);
                    $command->bindParam(":unit", $location['unit'], PDO::PARAM_STR);
                    $command->bindParam(":doc_id", $location['drawing_id'], PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $rs = $command->execute();
                }
            }

            $model_id = '0';
            $type_list = RevitComponent::typeList();
            $pbu_name = '';
            if($location['block']){
                $pbu_name.=$location['block'];
            }
            if($location['level']){
                $pbu_name.='-'.$location['level'];
            }
            if($location['unit']){
                $pbu_name.='-'.$location['unit'];
            }
            $pbu_type = '--';
            //location这边没有构件标签  修改block level  unit 怎么修改pbu_info那边
            foreach ($type_list as $type_id => $type_name){
                $pbu_id = RevitComponent::CreateIndex($location['project_id'],$location['block']);
                $sub_sql = 'INSERT INTO pbu_info(project_id,model_id,pbu_id,pbu_type,type,block,level,unit_nos,pbu_name) VALUES(:project_id,:model_id,:pbu_id,:pbu_type,:type,:block,:level,:unit_nos,:pbu_name)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":project_id", $location['project_id'], PDO::PARAM_INT);
                $command->bindParam(":model_id", $model_id, PDO::PARAM_INT);
                $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_INT);
                $command->bindParam(":pbu_type", $pbu_type, PDO::PARAM_INT);
                $command->bindParam(":type", $type_id, PDO::PARAM_INT);
                $command->bindParam(":block", $location['block'], PDO::PARAM_INT);
                $command->bindParam(":level", $location['level'], PDO::PARAM_INT);
                $command->bindParam(":unit_nos", $location['unit'], PDO::PARAM_INT);
                $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_INT);
                $rs = $command->execute();
            }

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

    //展示unit
    public static function locationUnit($project_id,$block){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT unit FROM bac_program_location_q WHERE  project_id = '".$root_proid."' and block='$block' and unit <> '' group by unit order by unit+0 asc ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //展示level
    public static function locationLevel($project_id,$block){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT a.secondary_region FROM bac_program_block_q a left join pbu_level b on a.secondary_region = b.level WHERE a.program_id = '".$root_proid."' and  a.block='$block' group by a.secondary_region order by b.level_index asc  ";//var_dump($sql);
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
                    $sub_sql = 'UPDATE bac_program_location_q SET unit=:unit WHERE unit=:unit_old and project_id=:project_id and block=:block ';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
                    $command->bindParam(":block", $block, PDO::PARAM_INT);
                    $command->bindParam(":unit", $j, PDO::PARAM_INT);
                    $command->bindParam(":unit_old", $unit_old_list[$i], PDO::PARAM_INT);
                    $rs = $command->execute();

                    $sql = "SELECT * FROM pbu_info WHERE status=0 and project_id = :project_id and block =:block and unit_nos=:unit_nos ";//var_dump($sql);
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
                    $command->bindParam(":block", $block, PDO::PARAM_INT);
                    $command->bindParam(":unit_nos", $unit_old_list[$i], PDO::PARAM_INT);
                    $rows = $command->queryAll();

                    if(count($rows)>0){
                        foreach($rows as $x => $y){
                            $pbu_name = '';
                            if($y['block']){
                                $pbu_name.=$y['block'];
                            }
                            if($y['level']){
                                $pbu_name.='-'.$y['level'];
                            }
                            if($y['part']){
                                $pbu_name.='-'.$y['part'];
                            }
                            if($y['unit']){
                                $pbu_name.='-'.$y['unit'];
                            }
                            if($y['pbu_type'] != '' && $y['pbu_type'] != '--'){
                                $pbu_name.='-'.$y['pbu_type'];
                            }
                            $sub_sql = 'UPDATE pbu_info SET unit_nos=:unit,pbu_name=:pbu_name WHERE id=:id ';
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_INT);
                            $command->bindParam(":unit", $j, PDO::PARAM_INT);
                            $command->bindParam(":id", $y['id'], PDO::PARAM_INT);
                            $rs = $command->execute();
                        }
                    }
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

    //展示block
    public static function locationAllBlock($project_id){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT block FROM bac_program_block_q WHERE program_id = '".$root_proid."' and block <> '' group by block";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $r = array();
        if(count($rows)>0){
            foreach ($rows as $i => $j){
                $r[$i] = $j['block'];
            }
        }
        return $r;
    }

    //同步bac_program_block_q+bac_program_location_q 数据
    public static function SyncData($pbu){
        $project_id = $pbu['project_id'];
        $model_id = $pbu['model_id'];
        $pbu_id = $pbu['pbu_id'];
        $block = $pbu['block'];
        $level = $pbu['level'];
        $unit_nos = $pbu['unit_nos'];

        $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and model_id =:model_id and pbu_id=:pbu_id  ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
        $command->bindParam(":model_id", $model_id, PDO::PARAM_STR);
        $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_STR);
        $detaillist = $command->queryAll();

//        $trans = Yii::app()->db->beginTransaction();
        try {

            $status = '0';
            $sql = "SELECT * FROM bac_program_block_q WHERE status=0 and program_id = :program_id and block = :block and secondary_region=:secondary_region";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":block", $block, PDO::PARAM_STR);
            $command->bindParam(":secondary_region", $level, PDO::PARAM_STR);
            $rows = $command->queryAll();
            if(count($detaillist)>0){
                $sub_sql = "UPDATE bac_program_block_q SET secondary_region=:secondary_region WHERE status=0 and program_id = :program_id and block=:block and secondary_region=:level ";
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":secondary_region", $level, PDO::PARAM_STR);
                $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
                $command->bindParam(":block", $block, PDO::PARAM_STR);
                $command->bindParam(":level", $detaillist[0]['level'], PDO::PARAM_STR);
                $rs = $command->execute();
            }
            if(count($rows) == 0){
                $type = '1';
                $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,type,secondary_region,status) VALUES(:program_id,:block,:type,:secondary_region,:status)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":program_id", $project_id, PDO::PARAM_INT);
                $command->bindParam(":block", $block, PDO::PARAM_INT);
                $command->bindParam(":type", $type, PDO::PARAM_INT);
                $command->bindParam(":secondary_region", $level, PDO::PARAM_INT);
                $command->bindParam(":status", $status, PDO::PARAM_INT);
                $rs = $command->execute();
            }
            if($unit_nos){
                $sql = "SELECT * FROM bac_program_location_q WHERE status=0 and project_id = :project_id and block = :block and level=:level and unit=:unit";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                $command->bindParam(":block", $block, PDO::PARAM_STR);
                $command->bindParam(":level", $level, PDO::PARAM_STR);
                $command->bindParam(":unit", $unit_nos, PDO::PARAM_STR);
                $rows = $command->queryAll();
                if(count($detaillist)>0){
                    $sub_sql = "UPDATE bac_program_location_q SET level=:level,unit=:unit WHERE status =0 and level=:level_old and unit=:unit_old and project_id=:project_id and block=:block ";
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":level", $level, PDO::PARAM_INT);
                    $command->bindParam(":unit", $unit_nos, PDO::PARAM_INT);
                    $command->bindParam(":level_old", $detaillist[0]['level'], PDO::PARAM_INT);
                    $command->bindParam(":unit_old", $detaillist[0]['unit_nos'], PDO::PARAM_INT);
                    $command->bindParam(":project_id", $project_id, PDO::PARAM_INT);
                    $command->bindParam(":block", $block, PDO::PARAM_INT);
                    $rs = $command->execute();
                }
                if(count($rows) == 0){
                    $sql = 'INSERT INTO bac_program_location_q (project_id,block,level,unit,status) VALUES(:project_id,:block,:level,:unit,:status)';
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                    $command->bindParam(":block", $block, PDO::PARAM_STR);
                    $command->bindParam(":level", $level, PDO::PARAM_STR);
                    $command->bindParam(":unit", $unit_nos, PDO::PARAM_STR);
                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                    $rs = $command->execute();
                }
            }

            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

//            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
//            $trans->rollback();
        }
        return $r;
    }
}
