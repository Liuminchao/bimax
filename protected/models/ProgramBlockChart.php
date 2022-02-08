<?php

/**
 * pbu_info中的pbu构件
 * @author LiuMinchao
 */
class ProgramBlockChart extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = -1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
//        return 'pbu_info';
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

    //展示block
    public static function locationAllBlock($project_id){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT block FROM pbu_info WHERE project_id = '".$root_proid."' and block <> '' group by block";//var_dump($sql);
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

    //展示block
    public static function locationBlock($project_id,$pbu_tag){
        if($pbu_tag == '1'){
            $type = '1';
        }else if($pbu_tag == '2'){
            $type = '2';
        }else if($pbu_tag == '3'){
            $type = '3';
        }
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT block FROM pbu_info WHERE type = '".$type."' and project_id = '".$root_proid."'  group by block";//var_dump($sql);
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

    //展示block
    public static function locationBlockbyType($project_id,$tag){
        if($tag == '1'){
            $type = '1';
        }else if($tag == '2'){
            $type = '2';
        }else if($tag == '3'){
            $type = '3';
        }
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT block FROM pbu_info WHERE type = '".$type."' and project_id = '".$root_proid."' and block <> ''  group by block";//var_dump($sql);
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

    //展示unit
    public static function locationUnit($project_id,$block,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT unit_nos FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."' and block='$block' and unit_nos <> '' group by unit_nos order by unit_nos+0 asc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //展示level
    public static function locationLevel($project_id,$block){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT level,count(1) as cnt FROM pbu_info WHERE project_id = '".$root_proid."' and  block='$block'  group by level";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //展示part
    public static function locationPart($project_id,$block,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT part FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."' and block='$block' and part <> '' group by part";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //获取最后得part
    public static function lastPart($project_id,$block){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT part FROM pbu_info WHERE project_id = '".$root_proid."' and block='$block' and part <> '' group by part asc";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //展示unit
    public static function locationPbutype($project_id,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT pbu_type FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."'  and pbu_type <> '' group by pbu_type";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 按 项目,block
     */
    public static function detailList($project_id,$block,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "select *
                from pbu_info
                where type = '".$pbu_tag."' and project_id = '".$root_proid."' and block='$block'  group by level+0 desc,unit_nos ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $r = array();
        if(count($rows)>0){
            foreach($rows as $i => $j){
//                $r[$j['level']][$j['unit_nos']]['id'] = $j['id'];
                $r[$j['level']][$j['unit_nos']]['part'] = $j['part'];
            }
        }

        return $r;
    }

    /**
     * 按 项目,block
     */
    public static function detailAllList($project_id,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "select *
                from pbu_info a
                left join 
                    pbu_level b
                on a.level = b.level
                where a.type = '".$pbu_tag."' and a.project_id = '".$root_proid."' group by a.block,b.level_index desc,a.unit_nos,a.pbu_type  ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $r = array();
        $rs = array();
        $block_bak = '';
        if(count($rows)>0){
            foreach($rows as $i => $j){
                if($block_bak == ''){
                    $block_bak = $j['block'];
                }
                if($block_bak != $j['block']){
                    $rs[$block_bak]['level'] = $r;
                    $block_bak = $j['block'];
                    $r = array();
                }
                $r[$j['level']][$j['unit_nos']]['id'] = $j['id'];
                if(array_key_exists('pbu_type',$r[$j['level']][$j['unit_nos']])){
                    $r[$j['level']][$j['unit_nos']]['pbu_type'].= '|'.$j['pbu_type'];
                }else{
                    $r[$j['level']][$j['unit_nos']]['pbu_type'] = $j['pbu_type'];
                }
            }
            $rs[$block_bak]['level'] = $r;
        }
        return $rs;
    }


    /**
     * 按 项目,block
     */
    public static function detailPartList($project_id,$block,$stage_id,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "select * from task_schedule where project_id = :project_id and block=:block and stage_id=:stage_id and type=:type";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $root_proid, PDO::PARAM_STR);
        $command->bindParam(":block", $block, PDO::PARAM_STR);
        $command->bindParam(":stage_id", $stage_id, PDO::PARAM_STR);
        $command->bindParam(":type", $pbu_tag, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if(count($rows)>0){
            foreach($rows as $i => $j){
                $re[$j['level']][$j['part']] = $j['plan_date'];
            }
        }
        $sql = "select c.* from (select a.*,b.end_date,b.status as end_status from pbu_info a left join task_component_stats b on a.pbu_id = b.guid and b.stage_id = :stage_id where a.project_id = :project_id and a.block = :block and type=:type and a.unit_nos <> '') as c left join pbu_level d on c.level =d.level order by d.level_index desc";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":stage_id", $stage_id, PDO::PARAM_STR);
        $command->bindParam(":project_id", $root_proid, PDO::PARAM_STR);
        $command->bindParam(":block", $block, PDO::PARAM_STR);
        $command->bindParam(":type", $pbu_tag, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $r = array();
        $record_date = date('Y-m-d');
        $total_cnt = 0;
        $act_cnt = 0;
        if(count($rows)>0){
            foreach($rows as $i => $j){
                $info = array();
                $total_cnt++;
                $info['part']= $j['part'];
                $info['pbu_type']= $j['pbu_type'];
                $info['pbu_id']= $j['pbu_id'];
                if($j['end_status'] == '0'){
                    $info['end_status']= 'Pending';
                }
                if($j['end_status'] == '1'){
                    $info['end_status']= 'Completed';
                }
                if($re[$j['level']][$j['part']]){
                    $info['plan_date']= Utils::DateToEn($re[$j['level']][$j['part']]);
                }else{
                    $info['plan_date']= '';
                }
                if($j['end_date']){
                    $info['end_date']= Utils::DateToEn($j['end_date']);
                    $act_cnt++;
                }else{
                    $info['end_date']= '';
                }
                //计划日期大于等于实际更新日期
                if($j['end_date'] != '' and $re[$j['level']][$j['part']] >= $j['end_date']){
                    $info['color'] = '#E2F0D9';
                }
                //没有计划日期 有实际日期
                if($re[$j['level']][$j['part']] == '' and $j['end_date'] != ''){
                    $info['color'] = '#E2F0D9';
                }
                //没有计划日期 没有实际日期
                if($re[$j['level']][$j['part']] == '' and $j['end_date'] == ''){
                    $info['color'] = '#FFFFFF';
                }
                //有计划日期 没有实际日期
                if($re[$j['level']][$j['part']] != '' and $j['end_date'] == ''){
                    $info['color'] = '#FFFFFF';
                }
                //没有实际更新日期
                if($j['end_date'] == ''){
                    $info['color'] = '#FFFFFF';
                    //当日日期小于计划日期
                    if($record_date < $re[$j['level']][$j['part']]){
                        $day_diff = Utils::diffBetweenTwoDays($re[$j['level']][$j['part']],$record_date);
                        if($day_diff == 3){
                            $info['color'] = '#FFE699';
                        }
                    }
                }
                //计划日期小于实际更新日期
                if($re[$j['level']][$j['part']] != '' and $re[$j['level']][$j['part']] < $j['end_date']){
                    $info['color'] = '#FF6969';
                }
                $r[$j['level']][$j['unit_nos']][] = $info;
            }
        }
        $rs['data'] = $r;
        $rs['total_cnt'] = $total_cnt;
        $rs['act_cnt'] = $act_cnt;
        return $rs;
    }


    /**
     * 查看unit下 pbu个数
     */
    public static function pbuByUnit($project_id,$block,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;

        $sql = "SELECT unit_nos FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."' and block='$block'  group by unit_nos";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $s = array();
        foreach ($rows as $x => $y){
            $s[$y['unit_nos']]['cnt'] = 0;
        }

        $sql = "select level,unit_nos,count(1) as cnt
                from pbu_info
                where type = '".$pbu_tag."' and project_id = '".$root_proid."' and  block='$block' group by level desc,unit_nos";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $r = array();
        $level = 0;
        if(count($rows)>0){
            foreach($rows as $i => $j){
                if($j['cnt']>$s[$j['unit_nos']]['cnt']){
                    $s[$j['unit_nos']]['cnt'] = $j['cnt'];
                }
            }
        }

        return $s;
    }

    //插入区域数据
    public static function SetBlock($args){
        $project_id = $args['blockchart']['program_id'];
//        $pbu_tag = $args['blockchart']['pbu_tag'];
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $block = $args['blockchart']['block'];
        $level = $args['blockchart']['level'];
        $unit = $args['blockchart']['unit'];
        $category = $args['blockchart']['category'];
        $model_id = '0';
        $type_list = RevitComponent::typeList();
        $trans = Yii::app()->db->beginTransaction();
        try {
//            $sql = "SELECT * FROM pbu_info WHERE project_id=:project_id and model_id=:model_id and type=:type and pbu_type<> '' ";
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":type", $pbu_tag, PDO::PARAM_INT);
//            $command->bindParam(":project_id", $project_id, PDO::PARAM_INT);
//            $command->bindParam(":model_id", $model_id, PDO::PARAM_INT);
//            $rows = $command->queryAll();

//            if(count($rows)<11){
//                $sql = 'DELETE FROM pbu_info WHERE project_id=:project_id and model_id=:model_id and  type=:type';
//                $command = Yii::app()->db->createCommand($sql);
//                $command->bindParam(":type", $pbu_tag, PDO::PARAM_INT);
//                $command->bindParam(":project_id", $project_id, PDO::PARAM_INT);
//                $command->bindParam(":model_id", $model_id, PDO::PARAM_INT);
//                $rs = $command->execute();
//            }

            foreach($block as $i => $j){
                $exist_data = RevitComponent::model()->count('block=:block and project_id=:project_id', array('block' => $j,'project_id'=>$project_id));
                if($exist_data>0){
                    $r['msg'] = 'Components:Block '.$j.' already exists.';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $exist_data = ProgramRegion::model()->count('block=:block and program_id=:program_id', array('block' => $j,'program_id'=>$project_id));
                if($exist_data>0){
                    $r['msg'] = 'Location:Block '.$j.' already exists.';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $status = '0';
                $type = '1';
                for($x=1;$x<=$level;$x++){
                    $sub_sql = 'INSERT INTO bac_program_block_q(program_id,block,secondary_region,type,status) VALUES(:program_id,:block,:secondary_region,:type,:status)';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":program_id", $root_proid, PDO::PARAM_INT);
                    $command->bindParam(":block", $j, PDO::PARAM_INT);
                    $command->bindParam(":secondary_region", $x, PDO::PARAM_INT);
                    $command->bindParam(":type", $type, PDO::PARAM_INT);
                    $command->bindParam(":status", $status, PDO::PARAM_INT);
                    $rs = $command->execute();
                    for($y=1;$y<=$unit;$y++){
//                        $pbu_id = $j.'-'.$x.'-'.$y;
                        foreach($type_list as $type_id => $type_name){
                            $pbu_id = RevitComponent::CreateIndex($root_proid,$j);
                            $pbu_type = '--';
                            $pbu_name = '';
                            if ($j) {
                                $pbu_name .= $j;
                            }
                            if ($x) {
                                $pbu_name .= '-' . $x;
                            }
                            if ($y) {
                                $pbu_name .= '-' . $y;
                            }
                            $sub_sql = 'INSERT INTO pbu_info(project_id,model_id,pbu_id,pbu_type,pbu_name,type,block,level,unit_nos) VALUES(:project_id,:model_id,:pbu_id,:pbu_type,:pbu_name,:type,:block,:level,:unit_nos)';
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
                            $command->bindParam(":model_id", $model_id, PDO::PARAM_INT);
                            $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_INT);
                            $command->bindParam(":pbu_type", $pbu_type, PDO::PARAM_INT);
                            $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_INT);
                            $command->bindParam(":type", $type_id, PDO::PARAM_INT);
                            $command->bindParam(":block", $j, PDO::PARAM_INT);
                            $command->bindParam(":level", $x, PDO::PARAM_INT);
                            $command->bindParam(":unit_nos", $y, PDO::PARAM_INT);
                            $rs = $command->execute();
                        }

                        $sql = 'INSERT INTO bac_program_location_q (project_id,block,level,unit,status) VALUES(:project_id,:block,:level,:unit,:status)';
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":project_id", $root_proid, PDO::PARAM_STR);
                        $command->bindParam(":block", $j, PDO::PARAM_STR);
                        $command->bindParam(":level", $x, PDO::PARAM_STR);
                        $command->bindParam(":unit", $y, PDO::PARAM_STR);
                        $command->bindParam(":status", $status, PDO::PARAM_STR);
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

    //更新unit
    public static function SetUnit($project_id,$block,$unit_list,$unit_old_list,$pbu_tag){
        $trans = Yii::app()->db->beginTransaction();
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        try {
            foreach($unit_list as $i => $j){
                if($j){
                    $sub_sql = 'UPDATE pbu_info SET unit_nos=:unit WHERE unit_nos=:unit_old and project_id=:project_id and block=:block and  type=:type';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
                    $command->bindParam(":type", $pbu_tag, PDO::PARAM_INT);
                    $command->bindParam(":block", $block, PDO::PARAM_INT);
                    $command->bindParam(":unit", $j, PDO::PARAM_INT);
                    $command->bindParam(":unit_old", $unit_old_list[$i], PDO::PARAM_INT);
                    $rs = $command->execute();

                    $sub_sql = 'UPDATE bac_program_location_q SET unit=:unit WHERE unit=:unit_old and project_id=:project_id and block=:block ';
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

    //设置part
    public static function SetPart($project_id,$block,$unit_list,$part,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach($unit_list as $i => $j){
                $info_list = explode('|',$j);
                $level = $info_list[0];
                $unit_nos = $info_list[1];

                $sql = "SELECT * FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."' and  block='$block' and level='$level' and unit_nos='$unit_nos' ";
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                if(count($rows)>0) {
                    foreach ($rows as $x => $y) {
                        $pbu_name = '';
                        if ($y['block']) {
                            $pbu_name .= $y['block'];
                        }
                        if ($y['level']) {
                            $pbu_name .= '-' . $y['level'];
                        }
                        if ($part) {
                            $pbu_name .= '-' . $part;
                        }
                        if ($y['unit_nos']) {
                            $pbu_name .= '-' . $y['unit_nos'];
                        }
                        if ($y['pbu_type'] != '' && $y['pbu_type'] != '--') {
                            $pbu_name .= '-' . $y['pbu_type'];
                        }

                        $sub_sql = "UPDATE pbu_info SET part=:part,pbu_name=:pbu_name WHERE id=:id ";
                        $command = Yii::app()->db->createCommand($sub_sql);
                        $command->bindParam(":part", $part, PDO::PARAM_INT);
                        $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_INT);
                        $command->bindParam(":id", $y['id'], PDO::PARAM_INT);
                        $rs = $command->execute();
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

    //更新pbu type 此处更新的是pbu_type不会影响到location
    public static function SetPbutype($project_id,$unit_list,$pbu_type,$pbu_tag){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $model_id = '0';
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach($unit_list as $i => $j){
                $info_list = explode('|',$j);
                $block = $info_list[0];
                $level = $info_list[1];
                $unit_nos = $info_list[2];

//                $pbu_name = $pbu_id;
                $sql = "SELECT * FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."' and block='$block' and level='$level' and unit_nos='$unit_nos' and pbu_type = '$pbu_type' ";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
//                var_dump($sql);
//                exit;
                if(count($rows)==0){
                    $sql = "SELECT * FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."' and  block='$block' and level='$level' and unit_nos='$unit_nos' and pbu_type = '--' ";
                    $command = Yii::app()->db->createCommand($sql);
                    $rows = $command->queryAll();
                    if(count($rows)>0){
                        foreach ($rows as $x => $y){
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
                            if($y['unit_nos']){
                                $pbu_name.='-'.$y['unit_nos'];
                            }
                            if($y['pbu_type'] !='' && $y['pbu_type'] != '--'){
                                $pbu_name.='-'.$y['pbu_type'];
                            }
                            $sub_sql = "UPDATE pbu_info SET pbu_type=:pbu_type,pbu_name=:pbu_name WHERE  id=:id and pbu_type = '--' ";
                            $command = Yii::app()->db->createCommand($sub_sql);
                            $command->bindParam(":pbu_type", $pbu_type, PDO::PARAM_INT);
//                        $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_INT);
                            $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_INT);
                            $command->bindParam(":id", $y['id'], PDO::PARAM_INT);
                            $rs = $command->execute();
                        }

                    }else{
                        $sql = "SELECT * FROM pbu_info WHERE type = '".$pbu_tag."' and project_id = '".$root_proid."' and  block='$block' and level='$level' and unit_nos='$unit_nos' and part <> '' ";
                        $command = Yii::app()->db->createCommand($sql);
                        $rows = $command->queryAll();
                        if(count($rows)>0){
                            $part = $rows[0]['part'];
                        }else{
                            $part = '';
                        }
                        $pbu_id = RevitComponent::CreateIndex($root_proid,$block);
                        $pbu_name = '';
                        if($block){
                            $pbu_name.=$block;
                        }
                        if($level){
                            $pbu_name.='-'.$level;
                        }
                        if($part){
                            $pbu_name.='-'.$part;
                        }
                        if($unit_nos){
                            $pbu_name.='-'.$unit_nos;
                        }
                        if($pbu_type !='' && $pbu_type != '--'){
                            $pbu_name.='-'.$pbu_type;
                        }
                        $sub_sql = 'INSERT INTO pbu_info(project_id,model_id,pbu_id,pbu_type,type,block,level,part,unit_nos,pbu_name) VALUES(:project_id,:model_id,:pbu_id,:pbu_type,:type,:block,:level,:part,:unit_nos,:pbu_name)';
                        $command = Yii::app()->db->createCommand($sub_sql);
                        $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
                        $command->bindParam(":model_id", $model_id, PDO::PARAM_INT);
                        $command->bindParam(":pbu_id", $pbu_id, PDO::PARAM_INT);
                        $command->bindParam(":pbu_type", $pbu_type, PDO::PARAM_INT);
                        $command->bindParam(":type", $pbu_tag, PDO::PARAM_INT);
                        $command->bindParam(":block", $block, PDO::PARAM_INT);
                        $command->bindParam(":level", $level, PDO::PARAM_INT);
                        $command->bindParam(":part", $part, PDO::PARAM_INT);
                        $command->bindParam(":unit_nos", $unit_nos, PDO::PARAM_INT);
                        $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_INT);
                        $rs = $command->execute();
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

    //更新pbu type
    public static function RePbutype($project_id,$unit_list,$pbu_type,$re_pbu_type){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $trans = Yii::app()->db->beginTransaction();
        try {
            foreach($unit_list as $i => $j){
                $pbu_list = explode('|',$j);
                $block = $pbu_list[0];
                $level = $pbu_list[1];
                $unit_nos = $pbu_list[2];
                $sql = "SELECT * FROM pbu_info WHERE project_id = '".$root_proid."' and block='$block' and level='$level' and unit_nos='$unit_nos' and pbu_type = '$pbu_type' ";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $rows = $command->queryAll();
                foreach ($rows as $x => $y){
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
                    if($y['unit_nos']){
                        $pbu_name.='-'.$y['unit_nos'];
                    }
                    if($re_pbu_type != '' && $re_pbu_type != '--'){
                        $pbu_name.='-'.$re_pbu_type;
                    }
                    $sub_sql = "UPDATE pbu_info SET pbu_type=:re_pbu_type,pbu_name=:pbu_name WHERE id=:id";
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":re_pbu_type", $re_pbu_type, PDO::PARAM_INT);
                    $command->bindParam(":pbu_name", $pbu_name, PDO::PARAM_INT);
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

    //状态置为0
    public static function SetFormal($id){
        $status = self::STATUS_NORMAL;
        $type = '1';
        $trans = Yii::app()->db->beginTransaction();
        $pbu_list = explode('|',$id);
        try {
            foreach ($pbu_list as $index => $pbu_id){
                $sub_sql = 'UPDATE pbu_info SET status=:status WHERE id=:id';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":status", $status, PDO::PARAM_INT);
                $command->bindParam(":id", $pbu_id, PDO::PARAM_INT);
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

    //更新构件实际完成时间
    public static function UpdateEnddate($pbu_info){
        $pro_model = Program::model()->findByPk($pbu_info['project_id']);
        $root_proid = $pro_model->root_proid;
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_id = $user[0]['user_id'];
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sub_sql = 'UPDATE task_component_stats SET end_date=:end_date,bak_end_date=:bak_end_date WHERE guid=:pbu_id and project_id=:project_id and stage_id=:stage_id  ';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":end_date", $pbu_info['end_date'], PDO::PARAM_INT);
            $command->bindParam(":bak_end_date", $pbu_info['bak_end_date'], PDO::PARAM_INT);
            $command->bindParam(":pbu_id", $pbu_info['pbu_id'], PDO::PARAM_INT);
            $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
            $command->bindParam(":stage_id", $pbu_info['stage_id'], PDO::PARAM_INT);
            $rs = $command->execute();
            $r['msg'] = Yii::t('common','success_update');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();

            $stage_model = TaskStage::model()->findByPk($pbu_info['stage_id']);
            $stage_name = $stage_model->stage_name;

            $data = array(
                'project_id' => $root_proid,
                'token' => 'lalala',
                'uid' => $user_id,
                'pbu_id' => $pbu_info['pbu_id'],
                'stage_name' => $stage_name,
                'act_date' => $pbu_info['end_date'],
                'bak_act_date' => $pbu_info['bak_end_date'],
                'type' => $pbu_info['pbu_tag'],
            );

            $post_data = json_encode($data);
            var_dump($post_data);
            $url = "https://shell.cmstech.sg/cms_qa/dbapi?cmd=BlockChartMsg";
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
            var_dump($rs);
            exit;
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }

    //更新构件实际完成时间
    public static function ShowTask($pbu_id,$project_id,$stage_id){
        $pro_model = Program::model()->findByPk($project_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT a.* FROM task_record a left join task_record_model b on a.check_id = b.check_id where a.project_id = :project_id and b.guid = :guid and a.stage_id=:stage_id and a.status = '1'";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":guid", $pbu_id, PDO::PARAM_INT);
        $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
        $command->bindParam(":stage_id", $stage_id, PDO::PARAM_INT);
        $rows = $command->queryAll();

        $task_sql = "SELECT task_id,task_name FROM task_list WHERE status=0 and stage_id=:stage_id";
        $command = Yii::app()->db->createCommand($task_sql);
        $command->bindParam(":stage_id", $stage_id, PDO::PARAM_INT);
        $task_rows = $command->queryAll();

        foreach($task_rows as $i => $j){
            $tag = '0';
            foreach ($rows as $x => $y){
                if($j['task_id'] == $y['task_id']){
                    $tag = '1';
                    $re[$j['task_name']] = Utils::DateToEn($y['update_time']);
                    $data['completed'][] = $re;
                }
            }
            if($tag == '0'){
                $r[$j['task_name']] = '';
                $data['pending'][] = $r;
            }
        }
        return $data;
    }
}
