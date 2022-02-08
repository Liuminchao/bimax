<?php
class ComponentStatusCommand extends CConsoleCommand
{
    public function actionBach()
    {
        $sql = "SELECT * FROM task_record a join (SELECT check_id FROM task_upload_log) b on a.check_id = b.check_id WHERE a.project_id = '1261' ";
        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->queryAll();

        if(count($rs)>0){
            foreach($rs as $i => $j){
                $sql = "SELECT * FROM task_record_model WHERE check_id = :check_id  ";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $j['check_id'], PDO::PARAM_STR);
                $r = $command->queryAll();
                if(count($r)>0){
                    foreach($r as $item => $value){
                        //查询这个构件在这个项目，阶段下有没有数据
                        $status_sql = "SELECT * FROM task_component_stats WHERE project_id = :project_id and model_id = :model_id and guid = :guid and stage_id = :stage_id ";
                        $command = Yii::app()->db->createCommand($status_sql);
                        $command->bindParam(":project_id", $j['project_id'], PDO::PARAM_STR);
                        $command->bindParam(":model_id", $value['model_id'], PDO::PARAM_STR);
                        $command->bindParam(":guid", $value['guid'], PDO::PARAM_STR);
                        $command->bindParam(":stage_id", $j['stage_id'], PDO::PARAM_STR);
                        $component_status = $command->queryAll();
                        if(count($component_status)>0){
                            $status = '0';//该stage是否完成：0未完成；1完成；
                            $latest_flag = '0';//该stage是否是最新阶段：0不是；1是；
                            $complete_num = 0;
                            $sql_stats = '';

                            if($component_status[0]['status'] == '1'){
                                $status = '1';
                            }else{
                                $status_arr = TaskRecord::is_stage_complete($j,$value['model_id']);
                                $status = $status_arr['status'];
                            }
                            //查询是否还有其他标有最新阶段标识得记录
                            $sql = "SELECT * FROM task_component_stats WHERE template_id = :template_id and model_id = :model_id and guid = :guid and latest_flag = '1' and id <> :id";
                            $command = Yii::app()->db->createCommand($status_sql);
                            $command->bindParam(":template_id", $j['template_id'], PDO::PARAM_STR);
                            $command->bindParam(":model_id", $value['model_id'], PDO::PARAM_STR);
                            $command->bindParam(":guid", $value['guid'], PDO::PARAM_STR);
                            $command->bindParam(":id", $component_status[0]['id'], PDO::PARAM_STR);
                            $s = $command->queryAll();
                            if(count($s) == 0){
                                $latest_flag = '1';
                            }else{
                                //如果有得话,把前一个阶段得latest_flag置为0
                                $front_stage_id = $s[0]['stage_id'];
                                $front_id = $s[0]['id'];
                                $sql = "SELECT * FROM task_stage WHERE stage_id in (:front_stage_id,:stage_id) and status = '0' ORDER BY clt_type desc, order_id";
                                $command->bindParam(":front_stage_id", $front_stage_id, PDO::PARAM_STR);
                                $command->bindParam(":stage_id", $j['stage_id'], PDO::PARAM_STR);
                                $ss = $command->queryAll();
                                //排前面，new_stage排后面为最新
                                if($ss[0]['stage_id'] == $front_stage_id){
                                    $sql = "UPDATE task_component_stats SET latest_flag = '0' WHERE id = :id";
                                    $command->bindParam(":id", $front_id, PDO::PARAM_STR);
                                    $command->execute();
                                }
                            }

                            //如果这个任务结束时间晚于这条记录得结束时间，就更新
                            if($j['end_date'] >= $component_status[0]['end_date']){
                                $sql = "UPDATE
                                task_component_stats
                            SET
                                complete_num = complete_num + 1, status = :status,
                                latest_task_id = :latest_task_id, latest_check_id = :latest_check_id, end_date = :end_date, latest_flag = :latest_flag, user_id = :user_id
                            WHERE
                                id = :id";
                                $command->bindParam(":status", $status_arr['status'], PDO::PARAM_STR);
                                $command->bindParam(":latest_task_id", $component_status[0]['latest_task_id'], PDO::PARAM_STR);
                                $command->bindParam(":latest_check_id", $component_status[0]['latest_check_id'], PDO::PARAM_STR);
                                $command->bindParam(":latest_flag", $latest_flag, PDO::PARAM_STR);
                                $command->bindParam(":user_id", $component_status[0]['user_id'], PDO::PARAM_STR);
                                $command->bindParam(":id", $component_status[0]['id'], PDO::PARAM_STR);
                                $command->execute();
                            }else{
                                $sql = "UPDATE
                                task_component_stats
                            SET
                                complete_num = complete_num + 1, status = :status, latest_flag = :latest_flag
                            WHERE
                                id = :id";
                                $command->bindParam(":status", $status_arr['status'], PDO::PARAM_STR);
                                $command->bindParam(":latest_flag", $latest_flag, PDO::PARAM_STR);
                                $command->bindParam(":id", $status_arr['id'], PDO::PARAM_STR);
                                $command->execute();
                            }
                        }else{
                            //不是NA得记录
                            if($j['na_flag'] != '1'){
                                $complete_num = 1;
                                $insert_status = '1';
                                $latest_flag = '1';
                                $insert_status_sql = "INSERT INTO
                        task_component_stats(project_id, model_id, guid, template_id, stage_id, start_date, start_task_id, start_check_id, end_date, latest_task_id, latest_check_id, user_id, complete_num, status, latest_flag)
                    VALUES
                        (:project_id, :model_id, :guid, :template_id, :stage_id, :start_date, :start_task_id, :start_check_id, :end_date, :latest_task_id, :latest_check_id, :user_id, :complete_num, :status, :latest_flag)";
                                $command = Yii::app()->db->createCommand($insert_status_sql);
                                $command->bindParam(":project_id", $j['project_id'], PDO::PARAM_STR);
                                $command->bindParam(":model_id", $value['model_id'][0]['model_id'], PDO::PARAM_STR);
                                $command->bindParam(":guid", $value['guid'], PDO::PARAM_STR);
                                $command->bindParam(":template_id", $j['template_id'], PDO::PARAM_STR);
                                $command->bindParam(":stage_id", $j['stage_id'], PDO::PARAM_STR);
                                $command->bindParam(":start_date", $j['start_date'], PDO::PARAM_STR);
                                $command->bindParam(":start_task_id", $j['task_id'], PDO::PARAM_STR);
                                $command->bindParam(":start_check_id", $j['check_id'], PDO::PARAM_STR);
                                $command->bindParam(":end_date", $j['end_date'], PDO::PARAM_STR);
                                $command->bindParam(":latest_task_id", $j['task_id'], PDO::PARAM_STR);
                                $command->bindParam(":latest_check_id", $j['check_id'], PDO::PARAM_STR);
                                $command->bindParam(":user_id", '', PDO::PARAM_STR);
                                $command->bindParam(":complete_num", $complete_num, PDO::PARAM_STR);
                                $command->bindParam(":status", $j['status'], PDO::PARAM_STR);
                                $command->bindParam(":latest_flag", $latest_flag, PDO::PARAM_STR);
                                $rs = $command->execute();
                            }
                        }
                    }
                }
            }
        }
    }
}