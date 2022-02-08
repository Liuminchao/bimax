<?php

/**
 * 质量表单类型
 * @author LiuMinchao
 */
class StatisticChecklist extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_checklist';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

        );
    }

    //按区域，类型查询checklist记录数量
    public static function queryBlockList($page, $pageSize, $args = array()){
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = '860';
        }
        $data = array(
            'project_id' => $args['project_id'],
            'token' => 'lalala',
            'uid' => $user_id
        );

        $data = array(
            'project_id' => '1661',
            'token' => 'lalala',
            'uid' => '860'
        );

        $post_data = json_encode($data);

        $url = "https://shell.cmstech.sg/cms_qa/dbapi?cmd=CMSC9901";
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

        $sql = "SELECT a.block, b.form_id, count(b.data_id) as cnt
                FROM qa_checklist_record a 
                join qa_form_data b ON a.check_id = b.check_id
                where a.project_id = '1661' AND a.status = '1' and b.status <> '9'
                group by a.block, b.form_id
                ORDER BY a.block, b.form_id";

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            foreach($rs['result'] as $k => $v){
                if($j['block'] == $v['block']){
                    foreach($v['checklist'] as $x => $y){
                        if($v['form_id'] == $y['form_id']){
                            $y['cnt'] = $v['cnt'];
                        }
                    }
                }
            }
        }

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($rs['result']);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($rs['result'],$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['page_num'] = ($page + 1);
        $res['total_num'] = $count;
        $res['num_of_page'] = $pageSize;
        $res['rows'] = $pagedata;

        return $res;

    }

    //按区域，类型查询checklist记录数量
    public static function queryBlockList2($page, $pageSize, $args = array()){

        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = '860';
        }
        $template_id = $args['template_id'];
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
        $block_list = array();
        $res = array();
        $color_list = array();
        $data = $rs['result']['data'];
        $i = 0;
        $block_list = array();
        if(count($data)>0){
            foreach($data as $u => $v){
                $i++;
                $stage = $v['stage'];
                $self = array();
                foreach($stage as $index => $j){
                    if($j['stage_id'] != ''){
                        $self[$j['stage_id']] = $j['cnt'];
                    }
                }
                $block_list[$i]['block'] = 'Block '.$v['block'];
                $block_list[$i]['stage'] = $self;
                $block_list[$i]['total'] = $v['total'];
            }
        }

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($block_list);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($block_list,$start,$pageSize);
        }else{
            $pagedata = array();
        }
        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['page_num'] = ($page + 1);
        $res['total_num'] = $count;
        $res['num_of_page'] = $pageSize;
        $res['rows'] = $pagedata;

        return $res;

    }
    //按Module Type区分
    public static function queryModuletypeList($page, $pageSize, $args = array()){

        $project_id = $args['program_id'];

        $sql = "SELECT block,count(1) as cnt
                FROM pbu_info 
                where project_id = :project_id AND status = '0'
                group by block";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
        $s = $command->queryAll();
        if(count($s)>0){
            foreach ($s as $i => $j){
                $rs[$j['block']]['total'] = $j['cnt'];
                $rs[$j['block']]['vertical'] = 0;
                $rs[$j['block']]['horizontal'] = 0;
            }
        }

        $sql = "SELECT block,module_type,count(1) as cnt
                FROM pbu_info 
                where project_id = :project_id AND status = '0' AND (module_type='Vertical' or module_type='Horizontal')
                group by block,module_type";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":project_id", $project_id, PDO::PARAM_STR);
        $rows = $command->queryAll();

        if(count($rows)>0){
            foreach ($rows as $x => $y){
                if($y['module_type'] == 'Vertical' || $y['module_type'] == 'VERTICAL'){
                    $rs[$y['block']]['vertical'] = $y['cnt'];
                }
                if($y['module_type'] == 'Horizontal' || $y['module_type'] == 'HORIZONTAL'){
                    $rs[$y['block']]['horizontal'] = $y['cnt'];
                }
            }
        }
        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($rs);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($rs,$start,$pageSize);
        }else{
            $pagedata = array();
        }
        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['page_num'] = ($page + 1);
        $res['total_num'] = $count;
        $res['num_of_page'] = $pageSize;
        $res['rows'] = $rs;

        return $res;

    }

    //按区域，类型查询checklist记录数量
    public static function queryAllBlockList($project_id){

        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = '860';
        }
        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'uid' => $user_id
        );
        $data = array(
            'project_id' => '1661',
            'token' => 'lalala',
            'uid' => '860'
        );
        $post_data = json_encode($data);

        $url = "https://shell.cmstech.sg/cms_qa/dbapi?cmd=CMSC9901";
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

        $sql = "SELECT a.block, b.form_id, count(b.data_id) as cnt
                FROM qa_checklist_record a 
                join qa_form_data b ON a.check_id = b.check_id
                where a.project_id = '1661' AND a.status = '1' and b.status <> '9'
                group by a.block, b.form_id
                ORDER BY a.block, b.form_id";

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $i => $j){
            foreach($rs['result'] as $k => $v){
                if($j['block'] == $v['block']){
                    foreach($v['checklist'] as $x => $y){
                        if($v['form_id'] == $y['form_id']){
                            $y['cnt'] = $v['cnt'];
                        }
                    }
                }
            }
        }


        return $rs['result'];

    }

    //按区域，类型查询checklist记录数量
    public static function queryAllBlockList2($args){

        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = '860';
        }

        $program_id = $args['program_id'];
        $template_id = $args['template_id'];
        $program_id = '1661';
        $template_id = '10';

        $data = array(
            'start_date' => '',
            'end_date' => '',
            'project_id' => $program_id,
            'template_id'=> $template_id,
            'token' => 'lalala',
            'uid' => '5927',
        );
        $post_data = json_encode($data);
        $url = "https://shell.cmstech.sg/cms_qa/dbapi?cmd=CMSC9903";
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

        return $rs['result'];

    }

    //checklist表头
    public static function headerCheckList($args){

//        $sql = "SELECT form_id, form_type, form_name
//                FROM qa_checklist
//                where project_id = '1661' and form_id like '%PBU-%' and report_flag = '1'
//                order by form_type desc, form_id";
        $program_id = $args['program_id'];
        $template_id = $args['template_id'];
        $data = array(
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $args['template_id']
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjMilestones";
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

        $data = $rs['result']['data'];
        $stage_list = array();
        foreach($data as $index => $value){
            if($index == 0){
                $stage = $value['stage'];
                foreach($stage as $i => $j){
                    if($j['stage_id'] != ''){
                        $stage_list[$j['stage_id']] = $j['stage_name'];
                    }
                }
            }
        }

        return $stage_list;
    }

    public static function DataByDay($args){


//        $data = array(
//            'project_id' => '1661',
//            'token' => 'lalala',
//            'uid' => '860',
//            'percent_only' => '1'
//        );
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = '33955';
        }
        $data = array(
            'template_id' => $args['template_id'],
            'stage_id' => $args['stage_id'],
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'uid' => $user_id,
            'start_date' => $args['start_date'],
            'end_date' => $args['end_date'],
        );

        $post_data = json_encode($data);

//        $url = "https://shell.cmstech.sg/cms_qa/dbapi?cmd=CMSC9901";
        $url = "https://shell.cmstech.sg/cms_qa/dbapi?cmd=CMSC9902";
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
//        $x= 0;
//        foreach($rs['result'] as $i => $j){
//            if($module == 'A'){
//                $r[$x]['total'] = $j['total'];
//                $r[$x]['percent'] = $j['percent_a'];
//                $r[$x]['cnt'] = $j['total_a'];
//                $r[$x]['block'] = $j['block'];
//            }else if($module == 'B'){
//                $r[$x]['total'] = $j['total'];
//                $r[$x]['percent'] = $j['percent_b'];
//                $r[$x]['cnt'] = $j['total_b'];
//                $r[$x]['block'] = $j['block'];
//            }else if($module == 'C'){
//                $r[$x]['total'] = $j['total'];
//                $r[$x]['percent'] = $j['percent_c'];
//                $r[$x]['cnt'] = $j['total_c'];
//                $r[$x]['block'] = $j['block'];
//            }
//            $x++;
//        }
        return $rs;
    }

    public static function DataByDay2($args,$module){

        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = '860';
        }
        $data = array(
            'start_date' => '',
            'end_date' => '',
            'project_id' => $args['program_id'],
            'template_id'=> $args['template_id'],
            'token' => 'lalala',
            'uid' => '5927',
        );

        $post_data = json_encode($data);
//        var_dump($post_data);
        $url = "https://shell.cmstech.sg/cms_qa/dbapi?cmd=CMSC9903";
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
        return $rs['result'];
    }

    //按区域，类型查询checklist记录数量
    public static function queryDateList($page, $pageSize, $args = array()){
        $sql = "SELECT form_id, form_type, form_name 
                FROM qa_checklist
                where project_id = '1661' and form_id like '%PBU%'
                order by form_type desc, form_id";
        $command = Yii::app()->db->createCommand($sql);
        $res = $command->queryAll();

        $header_list = self::headerDate('1661');

        foreach($res as $i => $j){
            $ar[$j['form_id']]['form_name'] = $j['form_name'];
            $ar[$j['form_id']]['form_type'] = $j['form_type'];
            foreach($header_list as $o => $u){
                $ar[$j['form_id']][$u['date']] = 0;
            }
        }

        $sql = "SELECT
                    DATE_FORMAT(a.apply_time, '%Y-%m') as date,
                    b.form_id,
                    COUNT(b.data_id) AS cnt
                FROM
                    qa_checklist_record a
                JOIN qa_form_data b ON
                    a.check_id = b.check_id
                WHERE
                    a.project_id = '1661' AND a.status = '1' AND b.status <> '9'
                GROUP BY
                    DATE_FORMAT(a.apply_time, '%Y-%m')
                ORDER BY
                DATE_FORMAT(a.apply_time, '%Y-%m') asc ";


        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($ar as $i => $j){
            foreach($rows as $x => $y){
                if($i == $y['form_id']){
                    $ar[$i][$y['date']] = (int)$y['cnt'];
                }
            }
        }

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($ar);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($ar,$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['page_num'] = ($page + 1);
        $res['total_num'] = $count;
        $res['num_of_page'] = $pageSize;
        $res['rows'] = $pagedata;

        return $res;
    }

    //按区域，类型查询checklist记录数量
    public static function queryDateList2($page, $pageSize, $args = array()){
        $project_id = $args['program_id'];
        $template_id = $args['template_id'];

//        $sql = "SELECT stage_id, clt_type, stage_name
//                FROM task_stage
//                where project_id = '".$project_id."' and template_id = '".$template_id."' and status = '0'
//                order by clt_type desc, stage_id";
//        $command = Yii::app()->db->createCommand($sql);
//        $res = $command->queryAll();
//
//        $header_list = self::headerDate($args);
//
//        if(count($res)>0){
//            foreach($res as $i => $j){
//                $ar[$j['stage_id']]['stage_name'] = $j['stage_name'];
//                $ar[$j['stage_id']]['clt_type'] = $j['clt_type'];
//                foreach($header_list as $o => $u){
//                    $ar[$j['stage_id']][$u['date']] = 0;
//                }
//            }
//        }
//
//
//        if($template_id){
////            $clt_type = $args['clt_type'];
//            $type_condi = " and a.template_id = '$template_id'";
//        }else{
//            $type_condi = "";
//        }
//
//        $sql = "SELECT
//                    DATE_FORMAT(a.record_time, '%Y-%m') as date,
//                    a.stage_id,
//                    COUNT(a.check_id) AS cnt
//                FROM
//                    task_record a
//                WHERE
//                    a.project_id = '".$project_id."' AND a.status = '1' $type_condi
//                GROUP BY
//                    DATE_FORMAT(a.record_time, '%Y-%m')
//                ORDER BY
//                DATE_FORMAT(a.record_time, '%Y-%m') asc ";
//
//
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();

        $data = array(
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $args['template_id']
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
        if(count($data)>0){
            foreach($data as $index => $value){
                $stage_list[$value['stage_id']]['stage_name'] = $value['stage_name'];
                foreach($value['month_cnt'] as $x => $y){
                    $stage_list[$value['stage_id']][$y['month']] =0;
                }
            }
        }
//        sort($stage_list);
//        var_dump($stage_list);
        if(count($data)>0) {
            foreach ($data as $index => $value) {
                foreach ($value['month_cnt'] as $x => $y) {
                    $stage_list[$value['stage_id']][$y['month']] += (int)$y['cnt'];
                }
            }
        }

        $count = count($stage_list);

        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['page_num'] = ($page + 1);
        $res['total_num'] = $count;
        $res['num_of_page'] = $pageSize;
        $res['rows'] = $stage_list;

        return $res;
    }

    //按区域，类型查询checklist记录数量
    public static function queryAllDateList($args){
        $project_id = $args['program_id'];
        $template_id = $args['template_id'];
//        $project_id = '1661';
//        $template_id = '10';
        $sql = "SELECT stage_id, clt_type, stage_name 
                FROM task_stage
                where project_id = '".$project_id."' and template_id = '".$template_id."' and status = '0'
                order by clt_type desc, stage_id";
        $command = Yii::app()->db->createCommand($sql);
        $res = $command->queryAll();

        $header_list = self::headerDate($args);

        if(count($res)>0){
            foreach($res as $i => $j){
                $ar[$j['stage_id']]['stage_name'] = $j['stage_name'];
                $ar[$j['stage_id']]['clt_type'] = $j['clt_type'];
                foreach($header_list as $o => $u){
                    $ar[$j['stage_id']][$u['date']] = 0;
                }
            }
        }


        if($template_id){
//            $clt_type = $args['clt_type'];
            $type_condi = " and a.template_id = '$template_id'";
        }else{
            $type_condi = "";
        }

        $sql = "SELECT
                    DATE_FORMAT(a.record_time, '%Y-%m') as date,
                    a.stage_id,
                    COUNT(a.check_id) AS cnt
                FROM
                    task_record a
                WHERE
                    a.project_id = '".$project_id."' AND a.status = '1' $type_condi
                GROUP BY
                    DATE_FORMAT(a.record_time, '%Y-%m')
                ORDER BY
                DATE_FORMAT(a.record_time, '%Y-%m') asc ";


        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        if(count($ar)>0){
            foreach($ar as $i => $j){
                foreach($rows as $x => $y){
                    if($i == $y['stage_id']){
                        $ar[$i][$y['date']] = (int)$y['cnt'];
                    }
                }
            }
        }else{
            $ar = array();
        }

        return $ar;
    }

    //checklist表头
    public static function headerDate($args){

//        $project_id = $args['program_id'];
//        $template_id = $args['template_id'];
////        $project_id = '1661';
////        $template_id = '10';
//        $clt_type = '';
//        if($template_id){
////            $clt_type = $clt_type;
//            $type_condi = " and a.template_id = '$template_id'";
//        }else{
//            $type_condi = "";
//        }
//
//        $sql = "SELECT
//                    DATE_FORMAT(a.record_time, '%Y-%m') as date
//                FROM
//                    task_record a
//                WHERE
//                    a.project_id = '".$project_id."' AND a.status = '1' $type_condi
//                GROUP BY
//                    DATE_FORMAT(a.record_time, '%Y-%m')
//                ORDER BY
//                DATE_FORMAT(a.record_time, '%Y-%m') asc ";
//
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();

        $data = array(
            'project_id' => $args['program_id'],
            'token' => 'lalala',
            'user' => '860',
            'template_id' => $args['template_id']
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
        $month_list = array();
        if(count($data)>0){
            foreach($data as $index => $value){
                if($index == 0){
                    foreach($value['month_cnt'] as $x => $y){
                        $month_list[] = $y['month'];
                    }
                }
            }
        }
        return $month_list;
    }
}
