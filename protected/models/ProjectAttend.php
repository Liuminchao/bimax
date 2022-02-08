<?php

/**
 * 项目考勤
 * @author 
 */
class ProjectAttend extends CActiveRecord {
    
	public static function getRusult(){
		$array = array(
			'00'	=>	Yii::t('sys_attend', 'result_success'),
			'01'	=>	Yii::t('sys_attend', 'result_error'),
		);
		return $array;
	}
	
    public function tableName() {
        $table = "atd_records_" . date("Ym");
        //$sql = 'create table  if not exists `' . $table . '` like atd_records';
        //Yii::app()->db->createCommand($sql)->execute();

        return $table;
    }
    
    //考勤记录查询
    public static function record($page, $pageSize, $args = array()){
        $condition = '';
        $params = array();
        //承包商编号
         /* if ($args['contractor_id'] != ''){
              $condition.= ( $condition == '') ? ' org_id=:contractor_id' : ' AND org_id=:contractor_id';
              $params['contractor_id'] = $args['contractor_id'];
          }*/
        //项目编号
       /* if ($args['program_id'] != '') {
            $pid_str = str_replace('"', '', $args['program_id']);//'100','101',102
            $condition.= ( $condition == '') ? ' device_id in (:program_id)': ' AND device_id in (:program_id)';
            $params['program_id'] = $pid_str;
        }*/
		
        //开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' card_time >=:start_date' : ' AND card_time >=:start_date';
            $params['start_date'] = $args['start_date'];
            $date1 = str_replace("-", "", $args["start_date"]);
            $table = "atd_records_".substr($date1, 0,6);
           
        }else{
            $table = "atd_records_".date("Ym");
        }
        
        
        //结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' card_time <=:end_date' : ' AND card_time <=:end_date';
            $params['end_date'] = $args['end_date'] . " 23:59:59";
        }
		
        //结果
        if ($args['record_status'] != '') {
            $condition.= ( $condition == '') ? ' record_status =:record_status' : ' AND record_status =:record_status';
            $params['record_status'] = $args['record_status'];
        }   
        
        if ($_REQUEST['q_order'] == '') {

            $order = 'card_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        
        $total_nums = Yii::app()->db->createCommand()
                ->select("count(1) cnt")
                ->from("$table T")
                ->where($condition, $params)
                ->queryRow();

        $total_num = $total_nums["cnt"];

        $rows = Yii::app()->db->createCommand()
                ->select("*")
                ->from("$table T")
                ->where($condition, $params)
                ->order($order)
                ->limit($pageSize)
                ->offset($page * $pageSize)
                ->queryAll();

         
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;

    }
    
    //失败刷脸记录查询
    public static function failrecord($page, $pageSize, $args = array()){
		if($args['program_id'] == ''){
			return;
		}
         $condition = '';
        $params = array();
        //承包商编号
         /* if ($args['contractor_id'] != ''){
              $condition.= ( $condition == '') ? ' org_id=:contractor_id' : ' AND org_id=:contractor_id';
              $params['contractor_id'] = $args['contractor_id'];
          }*/
        //项目编号
       // var_dump($args['program_id']);
        /*if ($args['program_id'] != '') {
            $pid_str =str_replace('"', '', $args['program_id']);//
            $condition.= ( $condition == '') ? ' device_id = '.$pid_str: ' AND device_id ='.$pid_str;
        }else{
            $pid_str =str_replace('"', '', $args['programid']);//'100','101',102
            $condition.= ( $condition == '') ? ' device_id in ( '.$pid_str.')': ' AND device_id in ('.$pid_str.')';
        }*/
		
		if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' device_id=:device_id': ' AND device_id=:device_id';
            $params['device_id'] = $args['program_id'];
		}
		
        //var_dump($args['start_date']);
        //开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' card_time >=:start_date' : ' AND card_time >=:start_date';
            $params['start_date'] = Utils::DateToCn($args['start_date']);
            $date1 = str_replace("-", "", Utils::DateToCn($args["start_date"]));
            $table = "atd_records_".substr($date1, 0,6);
           
        }else{
            $table = "atd_records_".date("Ym");
        }
        
        
        //结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' card_time <=:end_date' : ' AND card_time <=:end_date';
            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        }

        //结果
        
            $condition.= ( $condition == '') ? ' record_status =:record_status' : ' AND record_status =:record_status';
            $params['record_status'] = '01';
            //var_dump($params['record_status']);   
        //var_dump($condition);
        if ($_REQUEST['q_order'] == '') {

            $order = 'card_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        
        $total_nums = Yii::app()->db->createCommand()
                ->select("count(1) cnt")
                ->from("$table T")
                ->where($condition, $params)
                ->queryRow();
        
        $total_num = $total_nums["cnt"];

        $rows = Yii::app()->db->createCommand()
                ->select("*")
                ->from("$table T")
                ->where($condition, $params)
                ->order($order)
                ->limit($pageSize)
                ->offset($page * $pageSize)
                ->queryAll();
        //var_dump($rows); 
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
        
    }

    public static function createReportTable($month){
		if($month == '')
				$month = date('Ym');
        $table_prefix = 'atd_program_attend';
		$table_name = $table_prefix.'_'.$month;
		$sql = 'create table  if not exists `' . $table_name . '` like '.$table_prefix;
        Yii::app()->db->createCommand($sql)->execute();
        return $table_name;
	}

	public static function queryProgramAttend($month,$program_id,$type_id){
        $contractor_id = Yii::app()->user->contractor_id;
        $table_prefix = 'atd_program_attend';
        $table_name = $table_prefix.'_'.$month;
        $sql_1 = "select * from $table_name where node_id ='".$program_id."' and org_id='".$contractor_id."' ";
        $command_1 = Yii::app()->db->createCommand($sql_1);
        $rows_1 = $command_1->queryAll();
//        var_dump($rows_1);
//        exit;
        $sql_2 = "select * from bac_program_user_q where program_id ='".$program_id."' and contractor_id='".$contractor_id."' and check_status in(11,20)";
        $command_2 = Yii::app()->db->createCommand($sql_2);
        $rows_2 = $command_2->queryAll();
        foreach($rows_2 as $k => $v){
            if($type_id == '1'){
                if($v['build_role_id']){
                    $list[$v['build_role_id']][] = $v['user_id'];
                }
            }else if($type_id == '2'){
                if($v['rail_role_id']){
                    $list[$v['rail_role_id']][] = $v['user_id'];
                }
            }else{
                if($v['road_role_id']){
                    $list[$v['road_role_id']][] = $v['user_id'];
                }
            }
        }
//        var_dump($rows_1);
//        exit;
        foreach($list as $x => $user_list){
            $user_array = array();
            $rs[$x]['act_hour'] = 0;
            foreach($rows_1 as $i =>$j){
//                var_dump($j['user_id']);
//                var_dump($j['act_hour']);
                if($j['act_hour']>0){
                    if(in_array($j['user_id'],$user_list)){
//                        var_dump($j['user_id']);
//                        var_dump($j['act_hour']);
                        $user_array[] = $j['user_id'];
                        $rs[$x]['user_list'] = $user_array;
                        $rs[$x]['act_hour']+=round($j['act_hour'])/8;
                    }
                }
            }
        }
//        var_dump($rs);
//        exit;
        return $rs;
    }
	
    //考勤报表查询
    public static function report($args){
        if($args['program_id'] == ''){
			return;
		}
		
        if($args['start_date'] == '')
			$args['start_date'] = date('Y-m-d',strtotime('-1 day'));
		
		$start = str_replace('-', '', Utils::DateToCn($args['start_date']));
		
		$month = substr($start, 0, 6);
		$table = self::createReportTable($month);
		
		if($args['end_date'] == ''){
				$end = $start;
		}else{
			$end = str_replace('-', '', Utils::DateToCn($args['end_date']));
		}
        
        $condition = "cal_day between '".$start."' and '".$end."'";
        if($args['program_id'] != '')
            $condition .= " AND node_id = '".$args['program_id']."'";
        if($args['role_id']!='')
            $condition .= "AND node_name = '".$args['role_id']."'";
		
		//act_hour为实际出勤小时
        $sql = "SELECT node_id, node_name, cast(sum(act_hour)/60 as decimal(8,2)) as hours
                  from ".$table."
                 where ".$condition."
                 group by node_id, node_name"; //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
		//var_dump($rows);
        return $rows;
        
    }
}