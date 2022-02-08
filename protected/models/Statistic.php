<?php

/**
 * 各模块统计信息
 * @author
 */
class Statistic extends CActiveRecord {

    public function tableName() {
        return 'bac_statistic';
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
    //按照日期，承包商，项目来查询各模块使用频率
    public static function queryList($page, $pageSize, $args = array()){
        $condition = '';
        $params = array();
        $mc_program = Program::getMProgramId();
//        var_dump($mc_program);
//        exit;
//        //承包商编号
//        if ($args['contractor_id'] != ''){
//             $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
//             $params['contractor_id'] = $args['contractor_id'];
//         }
        //项目编号
//        $args['program_id'] = '232';
//        var_dump($args['program_id']);
//        exit;
        if ($args['program_id'] != '') {
            $pro_model = Program::model()->findByPk($args['program_id']);
            $main_conid = $pro_model->main_conid;
//            var_dump($main_conid);
//            var_dump($args['contractor_id']);
            if($main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
                $params['contractor_id'] = $args['contractor_id'];
            }
            $condition.= ( $condition == '') ? ' program_id=:program_id': ' AND program_id=:program_id';
            $params['program_id'] = $args['program_id'];
         }else{
             $condition.= ( $condition == '') ? ' program_id IN (:mc_program)': ' AND program_id IN (:mc_program)';
             $params['mc_program'] = $mc_program;
        }

        //操作开始时间
        if ($args['start_date'] != '') {
//            $args['start_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['start_date']))));
            $args['start_date'] = Utils::DateToCn($args['start_date']);
            $condition.= ( $condition == '') ? ' record_time >=:start_date' : ' AND record_time >=:start_date';
            $params['start_date'] = $args['start_date'];
        }
        //操作结束时间
        if ($args['end_date'] != '') {
//            $args['end_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['end_date']))));
            $args['end_date'] = Utils::DateToCn($args['end_date']);
            $condition.= ( $condition == '') ? ' record_time <=:end_date' : ' AND record_time <=:end_date';
            $params['end_date'] = $args['end_date'] . " 23:59:59";
        }
//        $first_date = date('Y-m-d',strtotime('-1 day',strtotime('2017-10-01')));
//        $last_date = date('Y-m-d',strtotime('+1 day',strtotime('2017-09-30')));
//        var_dump($first_date);
//        var_dump($last_date);

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $total_num = Statistic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
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
//        $pages->applyLimit($criteria);
        $row = Statistic::model()->findAll($criteria);


//        var_dump($row);


        $retdata = array();
        $i = 0;
        foreach($row as $cnt => $rows){
//            var_dump($rows[i]);

            $exist = 0;
//            var_dump(111);
                foreach ($retdata as $num => $rets) {
//                    var_dump(222);
                    if ($rets['con_id'] == $rows['contractor_id']) {
                        if ($rets['date'] == $rows['record_time']) {
//                    var_dump(1111);
                            $exist = 1;
                            if ($rows['type'] == 'PTW') {
//                            var_dump($num);
                                $retdata[$num]['ptw_cnt'] = $rows['sum'];
//                            var_dump($rets['ptw_sum']);
                                $retdata[$num]['ptw_pcnt'] = $rows['staffs'];
//                            var_dump($rets['ptw_staffs']);
                            } elseif ($rows['type'] == 'TBM') {
                                $retdata[$num]['tbm_cnt'] = $rows['sum'];
                                $retdata[$num]['tbm_pcnt'] = $rows['staffs'];
                            } elseif ($rows['type'] == 'SAFETY') {
                                $retdata[$num]['ins_cnt'] = $rows['sum'];
                                $retdata[$num]['ins_pcnt'] = $rows['staffs'];
                            } elseif ($rows['type'] == 'MEETING') {
                                $retdata[$num]['mee_cnt'] = $rows['sum'];
                                $retdata[$num]['mee_pcnt'] = $rows['staffs'];
                            } elseif ($rows['type'] == 'TRAIN') {
                                $retdata[$num]['tra_cnt'] = $rows['sum'];
                                $retdata[$num]['tra_pcnt'] = $rows['staffs'];
                            } elseif ($rows['type'] == 'RA') {
                                $retdata[$num]['ra_cnt'] = $rows['sum'];
                                $retdata[$num]['ra_pcnt'] = $rows['staffs'];
                            } elseif ($rows['type'] == 'CHECKLIST') {
                                $retdata[$num]['che_cnt'] = $rows['sum'];
                                $retdata[$num]['che_pcnt'] = $rows['staffs'];
                            } elseif ($rows['type'] == 'Incident') {
                                $retdata[$num]['inc_cnt'] = $rows['sum'];
                                $retdata[$num]['inc_pcnt'] = $rows['staffs'];
                            } elseif ($rows['type'] == 'QA') {
                                $retdata[$num]['qa_cnt'] = $rows['sum'];
                                $retdata[$num]['qa_pcnt'] = $rows['staffs'];
                            }
                        }
                    }
                }
//            var_dump(3);
            if($exist == 0){
//                var_dump(333);
//                var_dump(2);
//                $retdata[count($retdata)] = array("contractor_id"=>$rows[i]["contractor_id"], "record_time"=>$rows[i]["record_time"], "ptw_sum"=>0,  "tbm_sum"=>0, "ptw_staffs"=>0,"tbm_staffs"=>0);
                $retdata[count($retdata)]['con_id'] = $rows["contractor_id"];
//                var_dump($retdata[count($retdata)-1]['contractor_id']);
                $retdata[count($retdata)-1]['date'] = $rows["record_time"];
                $retdata[count($retdata)-1]['ptw_cnt'] = '0';
                $retdata[count($retdata)-1]['tbm_cnt'] = '0';
                $retdata[count($retdata)-1]['ins_cnt'] = '0';
                $retdata[count($retdata)-1]['mee_cnt'] = '0';
                $retdata[count($retdata)-1]['tra_cnt'] = '0';
                $retdata[count($retdata)-1]['ra_cnt'] = '0';
                $retdata[count($retdata)-1]['che_cnt'] = '0';
                $retdata[count($retdata)-1]['inc_cnt'] = '0';
                $retdata[count($retdata)-1]['qa_cnt'] = '0';
                $retdata[count($retdata)-1]['ptw_pcnt'] ='0';
                $retdata[count($retdata)-1]['tbm_pcnt'] = '0';
                $retdata[count($retdata)-1]['ins_pcnt'] = '0';
                $retdata[count($retdata)-1]['mee_pcnt'] = '0';
                $retdata[count($retdata)-1]['tra_pcnt'] = '0';
                $retdata[count($retdata)-1]['ra_pcnt'] = '0';
                $retdata[count($retdata)-1]['che_pcnt'] = '0';
                $retdata[count($retdata)-1]['inc_pcnt'] = '0';
                $retdata[count($retdata)-1]['tra_pcnt'] = '0';
                $retdata[count($retdata)-1]['qa_pcnt'] = '0';
                $retdata[count($retdata)-1]['con_name'] = $rows["contractor_name"];

                if ($rows['type'] == 'PTW'){
                    $retdata[count($retdata )- 1]['ptw_cnt'] = $rows['sum'];
//                    var_dump($rows['sum']);
//                    var_dump($rows['staffs']);
                    $retdata[count($retdata )- 1]['ptw_pcnt'] = $rows['staffs'];
			    }elseif($rows['type'] == 'TBM'){
                    $retdata[count($retdata )- 1]['tbm_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tbm_pcnt'] = $rows['staffs'];
			    }elseif($rows['type'] == 'SAFETY'){
                    $retdata[count($retdata )- 1]['ins_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ins_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'MEETING'){
                    $retdata[count($retdata )- 1]['mee_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['mee_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'TRAIN'){
                    $retdata[count($retdata )- 1]['tra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'RA'){
                    $retdata[count($retdata )- 1]['ra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'CHECKLIST'){
                    $retdata[count($retdata )- 1]['che_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['che_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'Incident'){
                    $retdata[count($retdata )- 1]['inc_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['inc_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'QA'){
                    $retdata[count($retdata )- 1]['qa_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['qa_pcnt'] = $rows['staffs'];
                }
	        }
            $i++;
//            var_dump($retdata);
        }
//        var_dump($retdata);


//        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
//        var_dump($page);
        $start=$page*$pageSize; #计算每次分页的开始位置
//        var_dump($start);
//        var_dump($pageSize);
        $count = count($retdata);
        $pagedata=array();
        $pagedata=array_slice($retdata,$start,$pageSize);
//        var_dump($pagedata);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;
        $rs['data'] = $retdata;


        return $rs;

    }


    //按照日期，承包商，项目来查询各模块使用频率
    public static function queryMonthList($page, $pageSize, $args = array()){
        $condition = '';
        $params = array();
        $mc_program = Program::getMProgramId();
//        var_dump($mc_program);
//        exit;
//        //承包商编号
//        if ($args['contractor_id'] != ''){
//             $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
//             $params['contractor_id'] = $args['contractor_id'];
//         }
        //项目编号
//        $args['program_id'] = '232';
//        var_dump($args['program_id']);
//        exit;
        if ($args['program_id'] != '') {
            $pro_model = Program::model()->findByPk($args['program_id']);
            $main_conid = $pro_model->main_conid;
//            var_dump($main_conid);
//            var_dump($args['contractor_id']);
            if($main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
                $params['contractor_id'] = $args['contractor_id'];
            }
            $condition.= ( $condition == '') ? ' program_id=:program_id': ' AND program_id=:program_id';
            $params['program_id'] = $args['program_id'];
        }else{
            $condition.= ( $condition == '') ? ' program_id IN (:mc_program)': ' AND program_id IN (:mc_program)';
            $params['mc_program'] = $mc_program;
        }

        //开始时间
        if ($args['month'] != '') {
//            $args['month'] = date('Y-m');
            $month = Utils::MonthToCn($args['month']);
//            var_dump($args['start_date']);
            $condition.= ( $condition == '') ? ' record_time like :start_date' : ' AND record_time like :start_date';
            $params['start_date'] = '%'.$month.'%';
        }else{
            $condition.= ( $condition == '') ? ' record_time like :start_date' : ' AND record_time like :start_date';
            $month = date('Y-m');
            $params['start_date'] = '%'.$month.'%';
        }

//        //操作开始时间
//        if ($args['start_date'] != '') {
//            $condition.= ( $condition == '') ? ' record_time >=:start_date' : ' AND record_time >=:start_date';
//            $params['start_date'] = Utils::DateToCn($args['start_date']);
//        }
//        //操作结束时间
//        if ($args['end_date'] != '') {
//            $condition.= ( $condition == '') ? ' record_time <=:end_date' : ' AND record_time <=:end_date';
//            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
//        }
//        var_dump($condition);
//        var_dump($params);

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $total_num = Statistic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
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
//        $pages->applyLimit($criteria);
        $row = Statistic::model()->findAll($criteria);


//        var_dump($row);
//        exit;

        $retdata = array();
        $i = 0;
        foreach($row as $cnt => $rows){
//            var_dump($rows[i]);

            $exist = 0;
//            var_dump(111);
                foreach ($retdata as $num => $rets) {
//                    var_dump(222);
                if ($rets['contractor_id'] == $rows['contractor_id']) {

//                    if ($rets['record_time'] == $rows['record_time']) {
//                    var_dump(1111);
                        $exist = 1;
                    if ($rows['type'] == 'PTW') {
//                            var_dump($num);
                        $retdata[$num]['ptw_cnt'] = $rows['sum'];
//                            var_dump($rets['ptw_sum']);
                        $retdata[$num]['ptw_pcnt'] = $rows['staffs'];
//                            var_dump($rets['ptw_staffs']);
                    } elseif ($rows['type'] == 'TBM') {
                        $retdata[$num]['tbm_cnt'] = $rows['sum'];
                        $retdata[$num]['tbm_pcnt'] = $rows['staffs'];
                    } elseif ($rows['type'] == 'SAFETY') {
                        $retdata[$num]['ins_cnt'] = $rows['sum'];
                        $retdata[$num]['ins_pcnt'] = $rows['staffs'];
                    } elseif ($rows['type'] == 'MEETING') {
                        $retdata[$num]['mee_cnt'] = $rows['sum'];
                        $retdata[$num]['mee_pcnt'] = $rows['staffs'];
                    } elseif ($rows['type'] == 'TRAIN') {
                        $retdata[$num]['tra_cnt'] = $rows['sum'];
                        $retdata[$num]['tra_pcnt'] = $rows['staffs'];
                    } elseif ($rows['type'] == 'RA') {
                        $retdata[$num]['ra_cnt'] = $rows['sum'];
                        $retdata[$num]['ra_pcnt'] = $rows['staffs'];
                    } elseif ($rows['type'] == 'CHECKLIST') {
                        $retdata[$num]['che_cnt'] = $rows['sum'];
                        $retdata[$num]['che_pcnt'] = $rows['staffs'];
                    } elseif ($rows['type'] == 'Incident') {
                        $retdata[$num]['inc_cnt'] = $rows['sum'];
                        $retdata[$num]['inc_pcnt'] = $rows['staffs'];
                    } elseif ($rows['type'] == 'QA') {
                        $retdata[$num]['qa_cnt'] = $rows['sum'];
                        $retdata[$num]['qa_pcnt'] = $rows['staffs'];
                    }
//                    }
                }
            }
//            var_dump(3);
            if($exist == 0){
//                var_dump(333);
//                var_dump(2);
//                $retdata[count($retdata)] = array("contractor_id"=>$rows[i]["contractor_id"], "record_time"=>$rows[i]["record_time"], "ptw_sum"=>0,  "tbm_sum"=>0, "ptw_staffs"=>0,"tbm_staffs"=>0);
                $retdata[count($retdata)]['con_id'] = $rows["contractor_id"];
//                var_dump($retdata[count($retdata)-1]['contractor_id']);
                $retdata[count($retdata)-1]['date'] = $month;
                $retdata[count($retdata)-1]['ptw_cnt'] = '0';
                $retdata[count($retdata)-1]['tbm_cnt'] = '0';
                $retdata[count($retdata)-1]['ins_cnt'] = '0';
                $retdata[count($retdata)-1]['mee_cnt'] = '0';
                $retdata[count($retdata)-1]['tra_cnt'] = '0';
                $retdata[count($retdata)-1]['ra_cnt'] = '0';
                $retdata[count($retdata)-1]['che_cnt'] = '0';
                $retdata[count($retdata)-1]['inc_cnt'] = '0';
                $retdata[count($retdata)-1]['qa_cnt'] = '0';
                $retdata[count($retdata)-1]['ptw_pcnt'] ='0';
                $retdata[count($retdata)-1]['tbm_pcnt'] = '0';
                $retdata[count($retdata)-1]['ins_pcnt'] = '0';
                $retdata[count($retdata)-1]['mee_pcnt'] = '0';
                $retdata[count($retdata)-1]['tra_pcnt'] = '0';
                $retdata[count($retdata)-1]['ra_pcnt'] = '0';
                $retdata[count($retdata)-1]['che_pcnt'] = '0';
                $retdata[count($retdata)-1]['inc_pcnt'] = '0';
                $retdata[count($retdata)-1]['tra_pcnt'] = '0';
                $retdata[count($retdata)-1]['qa_pcnt'] = '0';
                $retdata[count($retdata)-1]['con_name'] = $rows["contractor_name"];

                if ($rows['type'] == 'PTW'){
                    $retdata[count($retdata )- 1]['ptw_cnt'] = $rows['sum'];
//                    var_dump($rows['sum']);
//                    var_dump($rows['staffs']);
                    $retdata[count($retdata )- 1]['ptw_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'TBM'){
                    $retdata[count($retdata )- 1]['tbm_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tbm_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'SAFETY'){
                    $retdata[count($retdata )- 1]['ins_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ins_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'MEETING'){
                    $retdata[count($retdata )- 1]['mee_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['mee_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'TRAIN'){
                    $retdata[count($retdata )- 1]['tra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'RA'){
                    $retdata[count($retdata )- 1]['ra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'CHECKLIST'){
                    $retdata[count($retdata )- 1]['che_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['che_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'Incident'){
                    $retdata[count($retdata )- 1]['inc_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['inc_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'QA'){
                    $retdata[count($retdata )- 1]['qa_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['qa_pcnt'] = $rows['staffs'];
                }
            }
            $i++;
//            var_dump($retdata);
        }
//        var_dump($retdata);
//        exit;

//        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
//        var_dump($page);
        $start=$page*$pageSize; #计算每次分页的开始位置
//        var_dump($page);
//        var_dump($pageSize);
        $count = count($retdata);
        $pagedata=array();
        $pagedata=array_slice($retdata,$start,$pageSize);
//        var_dump($pagedata);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;
        $rs['data'] = $retdata;

        return $rs;

    }

    //新统计页面查询老数据（按天）
    public static function oldDayList($args){
        $condition = '';
        $params = array();

        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:root_proid' : ' AND program_id=:root_proid';
            $params['root_proid'] = $args['program_id'];
        }else {
            $condition .= ($condition == '') ? ' program_id=:root_proid' : ' AND program_id=:root_proid';
            $params['root_proid'] = '';
        }
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:con_id' : ' AND contractor_id=:con_id';
            $params['con_id'] = $args['contractor_id'];
        }
        //操作开始时间
        if ($args['start_date'] != '') {
//            $args['start_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['start_date']))));
            $args['start_date'] = Utils::DateToCn($args['start_date']);
            $condition.= ( $condition == '') ? ' record_time >=:start_date' : ' AND record_time >=:start_date';
            $params['start_date'] = $args['start_date'];
        }
        //操作结束时间
        if ($args['end_date'] != '') {
//            $args['end_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['end_date']))));
            $args['end_date'] = Utils::DateToCn($args['end_date']);
            $condition.= ( $condition == '') ? ' record_time <=:end_date' : ' AND record_time <=:end_date';
            $params['end_date'] = $args['end_date'] . " 23:59:59";
        }

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $total_num = Statistic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $row = Statistic::model()->findAll($criteria);

        if ($row) {
            $data = array();
            $data['ptw_cnt'] = 0;
            $data['tbm_cnt'] = 0;
            $data['tbm_pcnt'] = 0;
            $data['che_cnt'] = 0;
            $data['ins_cnt'] = 0;
            $data['mee_cnt'] = 0;
            $data['mee_pcnt'] = 0;
            $data['tra_cnt'] = 0;
            $data['tra_pcnt'] = 0;
            $data['ra_cnt'] = 0;
            $data['inc_cnt'] = 0;
            foreach ($row as $cnt => $rows) {
                switch ($rows['type']) {
                    case "PTW":
                        $data['ptw_cnt'] += $rows['sum'];
                        break;
                    case "TBM":
                        $data['tbm_cnt'] += $rows['sum'];
                        $data['tbm_pcnt'] += $rows['staffs'];
                        break;
                    case "MEETING":
                        $data['mee_cnt'] += $rows['sum'];
                        $data['mee_pcnt'] += $rows['staffs'];
                        break;
                    case "TRAIN":
                        $data['tra_cnt'] += $rows['sum'];
                        $data['tra_pcnt'] += $rows['staffs'];
                        break;
                    case "SAFETY":
                        $data['ins_cnt'] += $rows['sum'];
                        break;
                    default:
                        break;
                }
            }
            $i = 0;
            foreach ($data as $key => $cnt) {
                switch ($key) {
                    case "ptw_cnt":
                        $rdata[$i]['label'] = 'Ptw Cnt';
                        $rdata[$i]['label_id'] = 'ptw_cnt_label';
                        $rdata[$i]['data_id'] = 'ptw_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_cnt":
                        $rdata[$i]['label'] = 'Tbm Cnt';
                        $rdata[$i]['label_id'] = 'tbm_cnt_label';
                        $rdata[$i]['data_id'] = 'tbm_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_pcnt":
                        $rdata[$i]['label'] = 'Tbm Participants';
                        $rdata[$i]['label_id'] = 'tbm_pcnt_label';
                        $rdata[$i]['data_id'] = 'tbm_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "che_cnt":
                        $rdata[$i]['label'] = 'Checklist Cnt';
                        $rdata[$i]['label_id'] = 'che_cnt_label';
                        $rdata[$i]['data_id'] = 'che_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ins_cnt":
                        $rdata[$i]['label'] = 'Inspection Cnt';
                        $rdata[$i]['label_id'] = 'ins_cnt_label';
                        $rdata[$i]['data_id'] = 'ins_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_cnt":
                        $rdata[$i]['label'] = 'Meeting Cnt';
                        $rdata[$i]['label_id'] = 'mee_cnt_label';
                        $rdata[$i]['data_id'] = 'mee_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_pcnt":
                        $rdata[$i]['label'] = 'Meeting Participants';
                        $rdata[$i]['label_id'] = 'mee_pcnt_label';
                        $rdata[$i]['data_id'] = 'mee_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_cnt":
                        $rdata[$i]['label'] = 'Training Cnt';
                        $rdata[$i]['label_id'] = 'tra_cnt_label';
                        $rdata[$i]['data_id'] = 'tra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_pcnt":
                        $rdata[$i]['label'] = 'Training Participants';
                        $rdata[$i]['label_id'] = 'tra_pcnt_label';
                        $rdata[$i]['data_id'] = 'tra_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ra_cnt":
                        $rdata[$i]['label'] = 'Ra Cnt';
                        $rdata[$i]['label_id'] = 'ra_cnt_label';
                        $rdata[$i]['data_id'] = 'ra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "inc_cnt":
                        $rdata[$i]['label'] = 'Incident Cnt';
                        $rdata[$i]['label_id'] = 'inc_cnt_label';
                        $rdata[$i]['data_id'] = 'inc_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
//                    case "file_cnt":
//                        $rdata[$i]['label'] = 'File Cnt';
//                        $rdata[$i]['data'] = $cnt;
//                        break;
                    default:
                        break;
                }
                $i++;
            }
        }
//        var_dump($row);
//        exit;
        return $rdata;
    }

    //按照旧数据进行查询(按日)
    public static function queryOldDay($page, $pageSize, $args = array()){
        $condition = '';
        $params = array();

        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:root_proid' : ' AND program_id=:root_proid';
            $params['root_proid'] = $args['program_id'];
        }else {
            $condition .= ($condition == '') ? ' program_id=:root_proid' : ' AND program_id=:root_proid';
            $params['root_proid'] = '';
        }
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:con_id' : ' AND contractor_id=:con_id';
            $params['con_id'] = $args['contractor_id'];
        }
        //操作开始时间
        if ($args['start_date'] != '') {
//            $args['start_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['start_date']))));
            $args['start_date'] = Utils::DateToCn($args['start_date']);
            $condition.= ( $condition == '') ? ' record_time >=:start_date' : ' AND record_time >=:start_date';
            $params['start_date'] = $args['start_date'];
        }
        //操作结束时间
        if ($args['end_date'] != '') {
//            $args['end_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['end_date']))));
            $args['end_date'] = Utils::DateToCn($args['end_date']);
            $condition.= ( $condition == '') ? ' record_time <=:end_date' : ' AND record_time <=:end_date';
            $params['end_date'] = $args['end_date'] . " 23:59:59";
        }

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $total_num = Statistic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
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
//        $pages->applyLimit($criteria);
        $row = Statistic::model()->findAll($criteria);


//        var_dump($row);
//        exit;

        $retdata = array();
        $i = 0;
        foreach($row as $cnt => $rows){
//            var_dump($rows[i]);

            $exist = 0;
//            var_dump(111);
            foreach ($retdata as $num => $rets) {
//                    var_dump(222);
                if ($rets['contractor_id'] == $rows['contractor_id']) {

//                    if ($rets['record_time'] == $rows['record_time']) {
//                    var_dump(1111);
                    $exist = 1;
                    if ($rows['type'] == 'PTW') {
//                            var_dump($num);
                        $retdata[$num]['ptw_cnt']+= $rows['sum'];
//                            var_dump($rets['ptw_sum']);
                        $retdata[$num]['ptw_pcnt']+= $rows['staffs'];
//                            var_dump($rets['ptw_staffs']);
                    } elseif ($rows['type'] == 'TBM') {
                        $retdata[$num]['tbm_cnt']+= $rows['sum'];
                        $retdata[$num]['tbm_pcnt']+= $rows['staffs'];
                    } elseif ($rows['type'] == 'SAFETY') {
                        $retdata[$num]['ins_cnt']+= $rows['sum'];
                        $retdata[$num]['ins_pcnt']+= $rows['staffs'];
                    } elseif ($rows['type'] == 'MEETING') {
                        $retdata[$num]['mee_cnt']+= $rows['sum'];
                        $retdata[$num]['mee_pcnt']+= $rows['staffs'];
                    } elseif ($rows['type'] == 'TRAIN') {
                        $retdata[$num]['tra_cnt']+= $rows['sum'];
                        $retdata[$num]['tra_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'RA') {
                        $retdata[$num]['ra_cnt']+= $rows['sum'];
                        $retdata[$num]['ra_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'CHECKLIST') {
                        $retdata[$num]['che_cnt']+= $rows['sum'];
                        $retdata[$num]['che_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'Incident') {
                        $retdata[$num]['inc_cnt']+= $rows['sum'];
                        $retdata[$num]['inc_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'QA') {
                        $retdata[$num]['qa_cnt']+= $rows['sum'];
                        $retdata[$num]['qa_pcnt']+= $rows['staffs'];
                    }
//                    }
                }
            }
//            var_dump(3);
            if($exist == 0){
//                var_dump(333);
//                var_dump(2);
//                $retdata[count($retdata)] = array("contractor_id"=>$rows[i]["contractor_id"], "record_time"=>$rows[i]["record_time"], "ptw_sum"=>0,  "tbm_sum"=>0, "ptw_staffs"=>0,"tbm_staffs"=>0);
                $retdata[count($retdata)]['con_id'] = $rows["contractor_id"];
//                var_dump($retdata[count($retdata)-1]['contractor_id']);
                $retdata[count($retdata)-1]['date'] = $rows['record_time'];
                $retdata[count($retdata)-1]['ptw_cnt'] = '0';
                $retdata[count($retdata)-1]['tbm_cnt'] = '0';
                $retdata[count($retdata)-1]['ins_cnt'] = '0';
                $retdata[count($retdata)-1]['mee_cnt'] = '0';
                $retdata[count($retdata)-1]['tra_cnt'] = '0';
                $retdata[count($retdata)-1]['ra_cnt'] = '0';
                $retdata[count($retdata)-1]['che_cnt'] = '0';
                $retdata[count($retdata)-1]['inc_cnt'] = '0';
                $retdata[count($retdata)-1]['qa_cnt'] = '0';
                $retdata[count($retdata)-1]['ptw_pcnt'] ='0';
                $retdata[count($retdata)-1]['tbm_pcnt'] = '0';
                $retdata[count($retdata)-1]['ins_pcnt'] = '0';
                $retdata[count($retdata)-1]['mee_pcnt'] = '0';
                $retdata[count($retdata)-1]['tra_pcnt'] = '0';
                $retdata[count($retdata)-1]['ra_pcnt'] = '0';
                $retdata[count($retdata)-1]['che_pcnt'] = '0';
                $retdata[count($retdata)-1]['inc_pcnt'] = '0';
                $retdata[count($retdata)-1]['tra_pcnt'] = '0';
                $retdata[count($retdata)-1]['qa_pcnt'] = '0';
                $retdata[count($retdata)-1]['contractor_name'] = $rows["contractor_name"];

                if ($rows['type'] == 'PTW'){
                    $retdata[count($retdata )- 1]['ptw_cnt'] = $rows['sum'];
//                    var_dump($rows['sum']);
//                    var_dump($rows['staffs']);
                    $retdata[count($retdata )- 1]['ptw_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'TBM'){
                    $retdata[count($retdata )- 1]['tbm_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tbm_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'SAFETY'){
                    $retdata[count($retdata )- 1]['ins_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ins_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'MEETING'){
                    $retdata[count($retdata )- 1]['mee_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['mee_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'TRAIN'){
                    $retdata[count($retdata )- 1]['tra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'RA'){
                    $retdata[count($retdata )- 1]['ra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'CHECKLIST'){
                    $retdata[count($retdata )- 1]['che_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['che_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'Incident'){
                    $retdata[count($retdata )- 1]['inc_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['inc_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'QA'){
                    $retdata[count($retdata )- 1]['qa_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['qa_pcnt'] = $rows['staffs'];
                }
            }
            $i++;
//            var_dump($retdata);
        }
//        var_dump($retdata);
//        exit;

//        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
//        var_dump($page);
        $start=$page*$pageSize; #计算每次分页的开始位置
//        var_dump($page);
//        var_dump($pageSize);
        $count = count($retdata);
        $pagedata=array();
        $pagedata=array_slice($retdata,$start,$pageSize);
//        var_dump($pagedata);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;
        $rs['data'] = $pagedata;

        return $rs;

    }

    //新统计页面查询老数据（按月）
    public static function oldMonthList($args){
        $condition = '';
        $params = array();

        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:root_proid' : ' AND program_id=:root_proid';
            $params['root_proid'] = $args['program_id'];
        }
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:con_id' : ' AND contractor_id=:con_id';
            $params['con_id'] = $args['contractor_id'];
        }
        if ($args['month'] != '') {
            $month = Utils::MonthToCn($args['month']);
            $condition.= ( $condition == '') ? ' record_time like :start_date' : ' AND record_time like :start_date';
            $params['start_date'] = $month.'%';
        }

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $total_num = Statistic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $row = Statistic::model()->findAll($criteria);

        if ($row) {
            $data = array();
            $data['ptw_cnt'] = 0;
            $data['tbm_cnt'] = 0;
            $data['tbm_pcnt'] = 0;
            $data['che_cnt'] = 0;
            $data['ins_cnt'] = 0;
            $data['mee_cnt'] = 0;
            $data['mee_pcnt'] = 0;
            $data['tra_cnt'] = 0;
            $data['tra_pcnt'] = 0;
            $data['ra_cnt'] = 0;
            $data['inc_cnt'] = 0;
            foreach ($row as $cnt => $rows) {
                switch ($rows['type']) {
                    case "PTW":
                        $data['ptw_cnt'] += $rows['sum'];
                        break;
                    case "TBM":
                        $data['tbm_cnt'] += $rows['sum'];
                        $data['tbm_pcnt'] += $rows['staffs'];
                        break;
                    case "MEETING":
                        $data['mee_cnt'] += $rows['sum'];
                        $data['mee_pcnt'] += $rows['staffs'];
                        break;
                    case "TRAIN":
                        $data['tra_cnt'] += $rows['sum'];
                        $data['tra_pcnt'] += $rows['staffs'];
                        break;
                    case "SAFETY":
                        $data['ins_cnt'] += $rows['sum'];
                        break;
                    default:
                        break;
                }
            }
            $i = 0;
            foreach ($data as $key => $cnt) {
                switch ($key) {
                    case "ptw_cnt":
                        $rdata[$i]['label'] = 'Ptw Cnt';
                        $rdata[$i]['label_id'] = 'ptw_cnt_label';
                        $rdata[$i]['data_id'] = 'ptw_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_cnt":
                        $rdata[$i]['label'] = 'Tbm Cnt';
                        $rdata[$i]['label_id'] = 'tbm_cnt_label';
                        $rdata[$i]['data_id'] = 'tbm_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_pcnt":
                        $rdata[$i]['label'] = 'Tbm Participants';
                        $rdata[$i]['label_id'] = 'tbm_pcnt_label';
                        $rdata[$i]['data_id'] = 'tbm_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "che_cnt":
                        $rdata[$i]['label'] = 'Checklist Cnt';
                        $rdata[$i]['label_id'] = 'che_cnt_label';
                        $rdata[$i]['data_id'] = 'che_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ins_cnt":
                        $rdata[$i]['label'] = 'Inspection Cnt';
                        $rdata[$i]['label_id'] = 'ins_cnt_label';
                        $rdata[$i]['data_id'] = 'ins_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_cnt":
                        $rdata[$i]['label'] = 'Meeting Cnt';
                        $rdata[$i]['label_id'] = 'mee_cnt_label';
                        $rdata[$i]['data_id'] = 'mee_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_pcnt":
                        $rdata[$i]['label'] = 'Meeting Participants';
                        $rdata[$i]['label_id'] = 'mee_pcnt_label';
                        $rdata[$i]['data_id'] = 'mee_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_cnt":
                        $rdata[$i]['label'] = 'Training Cnt';
                        $rdata[$i]['label_id'] = 'tra_cnt_label';
                        $rdata[$i]['data_id'] = 'tra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_pcnt":
                        $rdata[$i]['label'] = 'Training Participants';
                        $rdata[$i]['label_id'] = 'tra_pcnt_label';
                        $rdata[$i]['data_id'] = 'tra_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ra_cnt":
                        $rdata[$i]['label'] = 'Ra Cnt';
                        $rdata[$i]['label_id'] = 'ra_cnt_label';
                        $rdata[$i]['data_id'] = 'ra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "inc_cnt":
                        $rdata[$i]['label'] = 'Incident Cnt';
                        $rdata[$i]['label_id'] = 'inc_cnt_label';
                        $rdata[$i]['data_id'] = 'inc_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
//                    case "file_cnt":
//                        $rdata[$i]['label'] = 'File Cnt';
//                        $rdata[$i]['data'] = $cnt;
//                        break;
                    default:
                        break;
                }
                $i++;
            }
        }
//        var_dump($row);
//        exit;
        return $rdata;
    }

    //按照旧数据进行查询(按月)
    public static function queryOldMonth($page, $pageSize, $args = array()){
        $condition = '';
        $params = array();

        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:root_proid' : ' AND program_id=:root_proid';
            $params['root_proid'] = $args['program_id'];
        }else {
            $condition .= ($condition == '') ? ' program_id=:root_proid' : ' AND program_id=:root_proid';
            $params['root_proid'] = '';
        }
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:con_id' : ' AND contractor_id=:con_id';
            $params['con_id'] = $args['contractor_id'];
        }
        if ($args['month'] != '') {
            $month = Utils::MonthToCn($args['month']);
            $condition.= ( $condition == '') ? ' record_time like :start_date' : ' AND record_time like :start_date';
            $params['start_date'] = $month.'%';
        }

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $total_num = Statistic::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time desc';
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
//        $pages->applyLimit($criteria);
        $row = Statistic::model()->findAll($criteria);


//        var_dump($row);
//        exit;

        $retdata = array();
        $i = 0;
        foreach($row as $cnt => $rows){
//            var_dump($rows[i]);

            $exist = 0;
//            var_dump(111);
            foreach ($retdata as $num => $rets) {
//                    var_dump(222);
                if ($rets['con_id'] == $rows['contractor_id']) {

//                    var_dump(1111);
                    $exist = 1;
                    if ($rows['type'] == 'PTW') {
//                            var_dump($num);
                        $retdata[$num]['ptw_cnt']+= $rows['sum'];
//                            var_dump($rets['ptw_sum']);
                        $retdata[$num]['ptw_pcnt']+= $rows['staffs'];
//                            var_dump($rets['ptw_staffs']);
                    } elseif ($rows['type'] == 'TBM') {
                        $retdata[$num]['tbm_cnt']+= $rows['sum'];
                        $retdata[$num]['tbm_pcnt']+= $rows['staffs'];
                    } elseif ($rows['type'] == 'SAFETY') {
                        $retdata[$num]['ins_cnt']+= $rows['sum'];
                        $retdata[$num]['ins_pcnt']+= $rows['staffs'];
                    } elseif ($rows['type'] == 'MEETING') {
                        $retdata[$num]['mee_cnt']+= $rows['sum'];
                        $retdata[$num]['mee_pcnt']+= $rows['staffs'];
                    } elseif ($rows['type'] == 'TRAIN') {
                        $retdata[$num]['tra_cnt']+= $rows['sum'];
                        $retdata[$num]['tra_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'RA') {
                        $retdata[$num]['ra_cnt']+= $rows['sum'];
                        $retdata[$num]['ra_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'CHECKLIST') {
                        $retdata[$num]['che_cnt']+= $rows['sum'];
                        $retdata[$num]['che_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'Incident') {
                        $retdata[$num]['inc_cnt']+= $rows['sum'];
                        $retdata[$num]['inc_pcnt']+= $rows['staffs'];
                    }elseif ($rows['type'] == 'QA') {
                        $retdata[$num]['qa_cnt']+= $rows['sum'];
                        $retdata[$num]['qa_pcnt']+= $rows['staffs'];
                    }
                }
            }
//            var_dump(3);
            if($exist == 0){
//                var_dump(333);
//                var_dump(2);
//                $retdata[count($retdata)] = array("contractor_id"=>$rows[i]["contractor_id"], "record_time"=>$rows[i]["record_time"], "ptw_sum"=>0,  "tbm_sum"=>0, "ptw_staffs"=>0,"tbm_staffs"=>0);
                $retdata[count($retdata)]['con_id'] = $rows["contractor_id"];
//                var_dump($retdata[count($retdata)-1]['contractor_id']);
                $retdata[count($retdata)-1]['date'] = $month;
                $retdata[count($retdata)-1]['ptw_cnt'] = '0';
                $retdata[count($retdata)-1]['tbm_cnt'] = '0';
                $retdata[count($retdata)-1]['ins_cnt'] = '0';
                $retdata[count($retdata)-1]['mee_cnt'] = '0';
                $retdata[count($retdata)-1]['tra_cnt'] = '0';
                $retdata[count($retdata)-1]['ra_cnt'] = '0';
                $retdata[count($retdata)-1]['che_cnt'] = '0';
                $retdata[count($retdata)-1]['inc_cnt'] = '0';
                $retdata[count($retdata)-1]['qa_cnt'] = '0';
                $retdata[count($retdata)-1]['ptw_pcnt'] ='0';
                $retdata[count($retdata)-1]['tbm_pcnt'] = '0';
                $retdata[count($retdata)-1]['ins_pcnt'] = '0';
                $retdata[count($retdata)-1]['mee_pcnt'] = '0';
                $retdata[count($retdata)-1]['tra_pcnt'] = '0';
                $retdata[count($retdata)-1]['ra_pcnt'] = '0';
                $retdata[count($retdata)-1]['che_pcnt'] = '0';
                $retdata[count($retdata)-1]['inc_pcnt'] = '0';
                $retdata[count($retdata)-1]['qa_pcnt'] = '0';
                $retdata[count($retdata)-1]['contractor_name'] = $rows["contractor_name"];

                if ($rows['type'] == 'PTW'){
                    $retdata[count($retdata )- 1]['ptw_cnt'] = $rows['sum'];
//                    var_dump($rows['sum']);
//                    var_dump($rows['staffs']);
                    $retdata[count($retdata )- 1]['ptw_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'TBM'){
                    $retdata[count($retdata )- 1]['tbm_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tbm_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'SAFETY'){
                    $retdata[count($retdata )- 1]['ins_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ins_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'MEETING'){
                    $retdata[count($retdata )- 1]['mee_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['mee_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'TRAIN'){
                    $retdata[count($retdata )- 1]['tra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['tra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'RA'){
                    $retdata[count($retdata )- 1]['ra_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['ra_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'CHECKLIST'){
                    $retdata[count($retdata )- 1]['che_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['che_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'Incident'){
                    $retdata[count($retdata )- 1]['inc_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['inc_pcnt'] = $rows['staffs'];
                }elseif($rows['type'] == 'QA'){
                    $retdata[count($retdata )- 1]['qa_cnt'] = $rows['sum'];
                    $retdata[count($retdata )- 1]['qa_pcnt'] = $rows['staffs'];
                }
            }
            $i++;
//            var_dump($retdata);
        }
//        var_dump($retdata);
//        exit;

//        $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面
//        var_dump($page);
        $start=$page*$pageSize; #计算每次分页的开始位置
//        var_dump($page);
//        var_dump($pageSize);
        $count = count($retdata);
        $pagedata=array();
        $pagedata=array_slice($retdata,$start,$pageSize);
//        var_dump($pagedata);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;
        $rs['data'] = $pagedata;

        return $rs;

    }
}