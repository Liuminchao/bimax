<?php
class LocationCommand extends CConsoleCommand
{
    //0,10 3-4 * * * php /opt/www-nginx/web/test/idd/protected/yiic model exportpbu
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    //php /opt/www-nginx/web/test/idd/protected/yiic model exportpbu --param1='25' --param2='2310'
    //php /opt/www-nginx/web/test/bimax/protected/yiic model exportallpbu --param1='612c8bd063f9e300118f757a_1'
    //导出带模型的构件excel
    public static function actionSaveLevelDraw($param1,$param2){
        $project_id = $param1;
//        $block = $param2;
        $block = urldecode($param2);
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
        $level_json = $redis->get('level_list');
        $level = json_decode($level_json,'true');
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = 'UPDATE bac_program_block_q  SET drawing_id= "" WHERE program_id=:program_id and block=:block';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
            $command->bindParam(":block", $block, PDO::PARAM_STR);
            $rs = $command->execute();
            $l_tag = 0;//是否是L开头的level
            foreach($level as $level_index => $level_list){
                $from = $level_list['level_from'];
                $to = $level_list['level_to'];
                $drawing_id = $level_list['file_path'];
                $drawing_id_1 = '|'.$drawing_id;
                if($from == $to){
                    $sql = "update bac_program_block_q set drawing_id = ( case when ( drawing_id = '') then :drawing_id else CONCAT(drawing_id,:drawing_id_1) end ) where program_id=:program_id and block=:block and secondary_region=:secondary_region ";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(":drawing_id", $drawing_id, PDO::PARAM_STR);
                    $command->bindParam(":drawing_id_1", $drawing_id_1, PDO::PARAM_STR);
                    $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
                    $command->bindParam(":block", $block, PDO::PARAM_STR);
                    $command->bindParam(":secondary_region", $from, PDO::PARAM_STR);
                    $rs = $command->execute();
                }else{
                    if(is_numeric($from)){
                        $from = (int)$from;
                        $to = (int)$to;
                        $l_tag = 0;
                    }else{
                        $from = substr($from,1);
                        $to = substr($to,1);
                        $from = (int)$from;
                        $to = (int)$to;
                        $l_tag = 1;
                    }
                    for($from;$from<=$to;$from++){
                        if($l_tag == 1){
                            $from_str = 'L'.$from;
                        }else{
                            $from_str = $from;
                        }
                        $sql = "update bac_program_block_q set drawing_id = ( case when ( drawing_id = '') then :drawing_id else CONCAT(drawing_id,:drawing_id_1) end ) where program_id=:program_id and block=:block and secondary_region=:secondary_region ";
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":drawing_id", $drawing_id, PDO::PARAM_STR);
                        $command->bindParam(":drawing_id_1", $drawing_id_1, PDO::PARAM_STR);
                        $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
                        $command->bindParam(":block", $block, PDO::PARAM_STR);
                        $command->bindParam(":secondary_region", $from_str, PDO::PARAM_STR);
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
        var_dump($r);
    }

    public static function actionSyncData(){
        $project_id = '3341';
        $status = '0';
        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "SELECT * FROM bac_program_block_q WHERE status=0 and program_id = :program_id order by block,secondary_region asc ";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $project_id, PDO::PARAM_STR);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
//                    $sql = "SELECT * FROM pbu_info WHERE project_id = :project_id and block = :block and level=:level and unit_nos<> '' ";//var_dump($sql);
//                    $command = Yii::app()->db->createCommand($sql);
//                    $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
//                    $command->bindParam(":block", $row['block'], PDO::PARAM_STR);
//                    $command->bindParam(":level", $row['secondary_region'], PDO::PARAM_STR);
//                    $rs = $command->queryAll();
//                    if(count($rs)>0){
//                        foreach ($rs as $i => $j){
                            if($row['type'] == '0'){
                                $doc_id = '';
                            }else{
                                $doc_id = $row['drawing_id'];
                            }
                            if($row['type'] == '1'){
                                for($i=1;$i<=10;$i++){
                                    $sub_sql = 'INSERT INTO bac_program_location_q (project_id,block,level,unit,doc_id,status) VALUES(:project_id,:block,:level,:unit,:doc_id,:status)';
                                    $command = Yii::app()->db->createCommand($sub_sql);
                                    $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                                    $command->bindParam(":block", $row['block'], PDO::PARAM_STR);
                                    $command->bindParam(":level", $row['secondary_region'], PDO::PARAM_STR);
                                    $command->bindParam(":unit", $i, PDO::PARAM_STR);
                                    $command->bindParam(":doc_id", $doc_id, PDO::PARAM_STR);
                                    $command->bindParam(":status", $status, PDO::PARAM_STR);
                                    $rs = $command->execute();
                                }
                            }else{
                                $unit = '';
                                $sub_sql = 'INSERT INTO bac_program_location_q (project_id,block,level,unit,doc_id,status) VALUES(:project_id,:block,:level,:unit,:doc_id,:status)';
                                $command = Yii::app()->db->createCommand($sub_sql);
                                $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
                                $command->bindParam(":block", $row['block'], PDO::PARAM_STR);
                                $command->bindParam(":level", $row['secondary_region'], PDO::PARAM_STR);
                                $command->bindParam(":unit", $unit, PDO::PARAM_STR);
                                $command->bindParam(":doc_id", $doc_id, PDO::PARAM_STR);
                                $command->bindParam(":status", $status, PDO::PARAM_STR);
                                $rs = $command->execute();
                            }
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
        var_dump($r);
    }
}