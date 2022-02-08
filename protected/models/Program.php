<?php

/**
 * 项目信息管理
 * @author LiuXiaoyuan
 */
class Program extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用
    const STATUS_DELETE = '99';//删除

    public $subcomp_name; //指派分包公司名
    public $father_model;   //上级节点类
    public $subcomp_sn; //指派分包注册编号
    public $TYPE;   //项目类型
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('proj_project', 'id'),
            'program_id' => Yii::t('proj_project', 'program_id'),
            'program_name' => Yii::t('proj_project', 'program_name'),
            'program_content' => Yii::t('proj_project', 'program_content'),
            'program_address' => Yii::t('proj_project', 'program_address'),
            'program_amount' => Yii::t('proj_project', 'program_amount'),
            'program_bp_no' => Yii::t('proj_project', 'program_bp_no'),
            'program_construction_no' => Yii::t('proj_project', 'program_construction_no'),
            'construction_start' => Yii::t('proj_project', 'construction_start'),
            'construction_end' => Yii::t('proj_project', 'construction_end'),
            'contractor_id' => Yii::t('proj_project', 'contractor_id'),
            'add_operator' => Yii::t('proj_project', 'add_operator'),
            'status' => Yii::t('proj_project', 'status'),
            'record_time' => Yii::t('proj_project', 'record_time'),
            'subcomp_name' => Yii::t('proj_project', 'sub_contractor_name'),
            'subcon_type' => Yii::t('proj_project', 'subcon_type'),
            'subcomp_sn'  => Yii::t('comp_contractor', 'company_sn'),
            'way_attendance'  => Yii::t('proj_project','way_attendance'),
            'start_sign'  => Yii::t('proj_project','start_sign'),
            'start_face'  => Yii::t('proj_project','start_face'),
            'close_face'  => Yii::t('proj_project','close_face'),
            'start_app'  => Yii::t('proj_project','start_app'),
            'start_attendance'  => Yii::t('proj_project','start_attendance'),
            'independent'  => Yii::t('proj_project','independent'),
            'independent_no'  => Yii::t('proj_project','independent_no'),
            'independent_yes'  => Yii::t('proj_project','independent_yes'),
            'ptw_mode'  => Yii::t('proj_project','ptw_mode'),
            'tbm_mode'  => Yii::t('proj_project','tbm_mode'),
            'acci_mode'  => Yii::t('proj_project','acci_mode'),
            'wsh_mode'  => Yii::t('proj_project','wsh_mode'),
            'train_mode'  => Yii::t('proj_project','train_mode'),
            'location_require' => Yii::t('proj_project','location_requires'),
            'location_require_no'  => Yii::t('proj_project','location_require_no'),
            'location_require_yes'  => Yii::t('proj_project','location_require_yes'),
            'struct_progress'  => Yii::t('proj_project','struct_progress'),
            'arch_progress'  => Yii::t('proj_project','arch_progress'),
            'me_progress' => Yii::t('proj_project','me_progress'),
            'program_gfa'  => Yii::t('proj_project','program_gfa'),
            'developer_client'  => Yii::t('proj_project','developer_client'),
            'consultant'  => Yii::t('proj_project','consultant'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Program the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('proj_project', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('proj_project', 'STATUS_STOP'),
            self::STATUS_DELETE => Yii::t('proj_project', 'STATUS_DELETE'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    
    //项目角色
    public static function typeText($key = null) {
        $rs = array(
            'MC' => Yii::t('dboard', 'Menu Project MC'), //总包项目
            'SC' => Yii::t('dboard', 'Menu Project SC'), //分包项目
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            //'bg-primary',
            //    'bg-warning',
            //    'bg-info',
            //    'bg-danger',
            //    'bg-success',
            //    'bg-indigo',
            //    'bg-lightblue',
            //    'bg-navy',
            //    'bg-purple',
            //    'bg-fuchsia',
            //    'bg-pink',
            //    'bg-maroon',
            //    'bg-orange',
            //    'bg-lime',
            //    'bg-teal',
            //    'bg-olive'
            self::STATUS_NORMAL => 'bg-success', //正常
            self::STATUS_STOP => 'bg-info', //停用
            self::STATUS_DELETE=>'bg-danger',//删除
        );
        return $key === null ? $rs : $rs[$key];
    }

    //所分配的分包公司
    public static function subcompText($program_id) {

        if ($program_id == '') {
            return Yii::t('proj_project', 'null');
        }

        $sql = "SELECT contractor_name FROM bac_contractor c,bac_program_contractor pc WHERE c.contractor_id=pc.contractor_id AND pc.program_id=:program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();

        if (count($rows) == 0)
            return Yii::t('proj_project', 'null');

        $contractor_name = '';
        foreach ($rows as $key => $row) {
            $contractor_name .= $row['contractor_name'] . ';<br>';
        }

        $contractor_name = substr($contractor_name, 0, strlen($contractor_name) - 1);
        return $contractor_name;
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

        //Program
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' t.program_id=:program_id' : ' AND t.program_id=:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Program Name
        if ($args['program_name'] != '') {
            $condition.= ( $condition == '') ? ' t.program_name LIKE :program_name' : ' AND t.program_name LIKE :program_name';
            $params['program_name'] = '%' . $args['program_name'] . '%';
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id=:contractor_id' : ' AND t.contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Add Operator
        if ($args['add_operator'] != '') {
            $condition.= ( $condition == '') ? ' t.add_operator=:add_operator' : ' AND t.add_operator=:add_operator';
            $params['add_operator'] = $args['add_operator'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' t.status=:status' : ' AND t.status=:status';
            $params['status'] = $args['status'];
        }else{
            $condition.= ( $condition == '') ? ' t.status<>99' : ' AND t.status<>99';
        }

        //father_proid
        if ($args['father_proid'] != '') {
            $condition.= ( $condition == '') ? ' t.father_proid=:father_proid' : ' AND t.father_proid=:father_proid';
            $params['father_proid'] = $args['father_proid'];
        }

        //project_type
        if ($args['project_type'] != '') {
            if($args['project_type'] == 'MC')
                $condition.= ( $condition == '') ? ' t.father_proid=0' : ' AND t.father_proid=0';
            elseif($args['project_type'] == 'SC')
                $condition.= ( $condition == '') ? ' t.father_proid<>0' : ' AND t.father_proid<>0';
        }

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 't.program_id desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        //Subcon Name
        if ($args['subcon_name'] != '') {
            $criteria->join = "RIGHT JOIN bac_contractor b ON b.contractor_id=t.contractor_id and b.contractor_name like '%".$args['subcon_name']."%'";
        }

        $criteria->select = 't.*';
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $total_num = Program::model()->count($criteria); //总记录数
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = Program::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByOperator($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        //Program Name
        if ($args['program_name'] != '') {
            $condition.= " and program_name LIKE '".$args['program_name']."'";
        }
        //Program Id
        if ($args['program_id'] != '') {
            $condition.= " and program_id = '".$args['program_id']."'";
        }
        //Status
//        if ($args['status'] != '') {
//            $condition.= " and status='".$args['status']."'";
//        }


        $order = " order by program_id asc";
        $select = "select *
                  from bac_program 
                 where  contractor_id='".$args['contractor_id']."' and status in ('00','01') 
                 ";
        $sql = $select.$condition.$order;
        $command = Yii::app()->db->createCommand($sql);
        $retdata = $command->queryAll();
//        var_dump($sql);
//        exit;
        $operator_id = Yii::app()->user->id;
        $authority_list = OperatorProject::authorityList($operator_id);
        foreach($retdata as $j => $r){
            $value = $authority_list[$r['program_id']];
            if($value != '2' && $value != ''){
                $re[] = $r;
            }
        }

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($re);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($re,$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $re;

        return $rs;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryMainList($page, $pageSize, $args = array()) {

        $condition = ' ';
        $params = array();
        //Program Name
        if ($args['program_name'] != '') {
            $condition.= " and program_name LIKE '".$args['program_name']."' ";
        }
        if ($args['contractor_id'] != '') {
            $condition.= " and contractor_id LIKE '".$args['contractor_id']."' ";
        }

//        $sql = "select a.root_proid,date_format(a.date, '%Y-%m') as date,count(a.ptw_cnt) as ptw_cnt,count(a.tbm_cnt) as tbm_cnt,count(a.ins_cnt) as ins_cnt from stats_date_app a,bac_program b where $condition group by a.root_proid,date_format(a.date, '%Y-%m') HAVING count(a.ptw_cnt) >120 or count(a.tbm_cnt) >120 or count(a.ins_cnt) >30 ORDER BY a.root_proid asc";
        $sql = "select program_id as root_proid from bac_program where contractor_id in (select contractor_id from bac_contractor where contractor_type = 'MC') and status = '00' and default_program_type = '0' and program_id = root_proid $condition ORDER BY `bac_program`.`record_time` DESC";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $data = array();
        foreach($rows as $i => $j){
            $data[$j['root_proid']]['status'] = '0';
            $pro_model =Program::model()->findByPk($j['root_proid']);
            $pro_name = $pro_model->program_name;
            $params = $pro_model->params;
            $contractor_id = $pro_model->contractor_id;
            $con_model =Contractor::model()->findByPk($contractor_id);
            if($contractor_id != '143'){
                $con_model->contractor_type = 'MC';
                $con_model->save();
            }
            if($params != '0'){
                $params = json_decode($params,true);
                $record_time = date('Y-m-d');
                if(array_key_exists('end_date',$params)){
                    $end_date = substr($params['end_date'],0,10);
                    $tow_month_date = date("Y-m-d",strtotime("+2 month",strtotime($end_date)));
                    if(strtotime($record_time)>strtotime($end_date)){
                        $data[$j['root_proid']]['status'] = '2';
                    }else{
                        if(strtotime($record_time)<strtotime($tow_month_date)){
                            $data[$j['root_proid']]['status'] = '1';
                        }
                    }
                }
            }

            $contractor_id = $pro_model->contractor_id;
            $con_model =Contractor::model()->findByPk($contractor_id);
            $con_name = $con_model->contractor_name;
            $data[$j['root_proid']]['root_proid'] = $j['root_proid'];
            $data[$j['root_proid']]['pro_name'] = $pro_name;
            $data[$j['root_proid']]['con_name'] = $con_name;
        }

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
    public static function queryMainCount() {

//        $sql = "select root_proid,date_format(date, '%Y-%m') as date,count(ptw_cnt) as ptw_cnt,count(tbm_cnt) as tbm_cnt,count(ins_cnt) as ins_cnt from stats_date_app group by root_proid,date_format(date, '%Y-%m') HAVING count(ptw_cnt) >30 or count(tbm_cnt) >30 or count(ins_cnt) >20 ORDER BY root_proid asc";
        $sql = "select program_id as root_proid from bac_program where contractor_id in (select contractor_id from bac_contractor where contractor_type = 'MC') and status = '00' and default_program_type = '0' and program_id = root_proid  ORDER BY `bac_program`.`record_time` DESC";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $data = array();
        foreach($rows as $i => $j){
            $pro_model =Program::model()->findByPk($j['root_proid']);
            $pro_name = $pro_model->program_name;
            $contractor_id = $pro_model->contractor_id;
            $con_model =Contractor::model()->findByPk($contractor_id);
            $con_name = $con_model->contractor_name;
            $data[$j['root_proid']]['pro_name'] = $pro_name;
            $data[$j['root_proid']]['con_name'] = $con_name;
        }
        $count = count($data);
        return $count;
    }

    public static function queryListBySc($page, $pageSize, $args = array()) {

        $condition = '1=1';
        $params = array();


        //Program Name
        if ($args['program_name'] != '') {
            $condition.= ( $condition == '') ? ' program_name LIKE :program_name' : ' AND program_name LIKE :program_name';
            $params['program_name'] = '%' . $args['program_name'] . '%';
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id :contractor_id' : ' AND program_id in (select program_id from bac_program_contractor where contractor_id=:contractor_id)';
            $params['contractor_id'] = $args['contractor_id'];
        }
        $total_num = Program::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'program_id';
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
        $pages->applyLimit($criteria);
        $rows = Program::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //添加日志
    public static function insertLog($model) {
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('program_id') => Yii::app()->db->lastInsertID,
            $model->getAttributeLabel('program_name') => $model->program_name,
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('add_operator') => $model->add_operator,
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('record_time') => $model->record_time,
            Yii::t('proj_project', 'Assign SC') => self::subcompText(Yii::app()->db->lastInsertID),
        );
    }

    //修改日志
    public static function updateLog($model) {
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('program_id') => $model->program_id,
            $model->getAttributeLabel('program_name') => $model->program_name,
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('add_operator') => $model->add_operator,
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('record_time') => $model->record_time,
            Yii::t('proj_project', 'Assign SC') => self::subcompText($model->program_id),
        );
    }

    //插入数据(项目)
    public static function insertProgram($args) {

        /*$exist_data = Program::model()->count('program_name=:program_name', array('program_name' => $args['program_name']));
        if ($exist_data != 0) {
            $r['msg'] = Yii::t('proj_project', 'error_projname_is_exists');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }*/

        try {
            $model = new Program('create');
//            var_dump($args);
//            exit;
            if (is_array($args))
            foreach ($args as $item_name => $item_value) {
//                if ($item_name == 'ptw_mode') {
//                    $params[$item_name] = $item_value;
//                    $json_params = json_encode($params);
//                    $model->params = $json_params;
//                }else if($item_name == 'location_require'){
//                    $params[$item_name] = $item_value;
//                    $json_params = json_encode($params);
//                    $model->params = $json_params;
//                }else {
                    $model->$item_name = $item_value;
//                }
            }
//            $model->add_conid = Yii::app()->user->getState('contractor_id');
//            $model->add_operator = Yii::app()->user->id;
            
            $model->status = self::STATUS_NORMAL;
            
            if($args['TYPE'] == 'MC'){    //总包项目
                $model->contractor_id = $model->add_conid;
                $model->main_conid = $model->add_conid;
                $model->father_proid = 0;
                $model->node_level = 1;
            }
            else{   //分包项目
                //父节点的孩子数量+1
                $father_model = $args['father_model'];
                $father_model->child_cnt = $father_model->child_cnt +1;

                $father_model->save();
                
                $model->father_proid = $father_model->program_id;
                $model->main_conid = $father_model->main_conid;
                $model->root_proid = $father_model->root_proid;
                $model->node_level = $father_model->node_level + 1;
                $model->program_name = $father_model->program_name; //分包项目名称跟随总包项目名
                //父节点项目如果开启飞搜,子节点项目继承父节点的faceset_id
//                if($father_model->start_sign == '1') {
//                    $model->faceset_id = $father_model->faceset_id;
//                    $model->start_sign = $father_model->start_sign;
//                }else{
//                    $model->start_sign = $father_model->start_sign;
//                }
//                var_dump($model->program_id);
//                exit;
            }
            
            $result = $model->save();
            
            if ($result) {
                $program_id = Yii::app()->db->lastInsertID;
                $pro_model = Program::model()->findByPk($program_id);
                $program_name = $pro_model->program_name;
                $root_proid = $pro_model->root_proid;
                $result = Dms::NewProgram($program_id,$program_name);
                if($result['code'] == '100'){
                    $pro_model =Program::model()->findByPk($root_proid);
                    $params = $pro_model->params;
                    if($params != '0' || params != ''){
                        $params  = json_decode($params,true);
                    }else{
                        $params = array();
                    }
                    $params['dms'] = '1';
                    $json_params = json_encode($params);
                    $pro_model->params = $json_params;
                    $pro_model->save();
                }
//                var_dump($program_id);
//                exit;
                if($args['TYPE'] == 'MC'){        //总包项目
                    //$model->updateByPk($program_id, array('root_proid'=>$program_id));
                    $MC_model = Program::model()->findByPk($program_id);
                    $model->updateByPk($program_id, array('root_proid' => $program_id)); //填写root_proid
                    //创建faceset_id(创建的总包已经开启了飞搜标识)
//                    if($MC_model->start_sign == '1') {
//                        $faceModel = new Face();
//                        $rs = $faceModel->FacesetCreate($program_id);
//                        $model->updateByPk($program_id, array('faceset_id' => $rs['faceset_id'], 'root_proid' => $program_id));
//                    }
                    $app_list = App::appAllList();
                    $nor_status = ProgramApp::STATUS_NORMAL;
                    foreach($app_list as $app_id => $value){
                        $sql = 'INSERT INTO bac_program_app(program_id,app_id,status) VALUES(:program_id,:app_id,:status)';
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                        $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
                        $command->bindParam(":status", $nor_status, PDO::PARAM_STR);
                        $rs = $command->execute();
                    }
                }
                elseif($model->node_level == 2){    //一级分包，将一级分包编号存入
                    $model->updateByPk($program_id, array('sub_conid'=>$program_id));
                }
                else{   //二级分包，将一级分包编号存入
                    $model->updateByPk($program_id, array('sub_conid'=>$father_model->sub_conid));
                }

                //添加操作员和项目的权限
//                $operator_list = Operator::OperatorList($model->add_conid,$model->contractor_id);
                $operator_list = Operator::OperatorList($model->contractor_id);
                foreach($operator_list as $k => $v){
                    $res['program_id'] = $program_id;
                    $res['operator_id'] = $v['operator_id'];
                    if($v['operator_role'] == '00'){
                        $res['value'] = '1';
                    }else{
                        $res['value'] = '2';
                    }

                    OperatorProject::SetAuthority($res);
                }
                
                //var_dump($rs);
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Add Sub Proj'), self::insertLog($model));

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['id'] = $program_id;
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //修改数据
    public static function  updateProgram($args) {
//        var_dump($args);
//        exit;
        if ($args['program_id'] == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Program::model()->findByPk($args['program_id']);

        $sql = "SELECT * FROM bac_program WHERE  root_proid = '".$args['program_id']."' ";
        $command = Yii::app()->db->createCommand($sql);
        $program_list = $command->queryAll();
        foreach ($program_list as $i => $j){
            $sub_model = Program::model()->findByPk($j['program_id']);
            $sub_model->program_name = $args['program_name'];
            $sub_model->save();
        }

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

//            $model->program_name = $args['program_name'];
//            $model->program_content = $args['program_content'];
//            $model->contractor_id =$args['contractor_id'];
            foreach ($args as $item_name => $item_value) {
//                if ($item_name == 'ptw_mode') {
//                    $params[$item_name] = $item_value;
//                    $json_params = json_encode($params);
//                    $model->params = $json_params;
//                }else if($item_name == 'location_require'){
//                    $params[$item_name] = $item_value;
//                    $json_params = json_encode($params);
//                    $model->params = $json_params;
//                }else {
                    $model->$item_name = $item_value;
//                }
            }
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Edit Proj'), '');
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //修改模块报告
    public static function updateProgramReport($args) {
//        var_dump($args);
//        exit;
        if ($args['program_id'] == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Program::model()->findByPk($args['program_id']);
        $params = json_decode($model->params,true);
        if($params == 0){
            $params = array();
        }
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            foreach ($args as $item_name => $item_value) {
                if($item_name != 'program_id'){
                    $params[$item_name] = $item_value;
                }
            }
            $json_params = json_encode($params);
            $model->params = $json_params;
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Edit Proj'), '');
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //修改模块类型
    public static function updateProgramParams($args) {
//        var_dump($args);
//        exit;
        if ($args['program_id'] == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Program::model()->findByPk($args['program_id']);
        $params = json_decode($model->params,true);
        if($params == 0){
            $params = array();
        }
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            foreach ($args as $item_name => $item_value) {
                if($item_name != 'program_id'){
                    $params[$item_name] = $item_value;
                }
            }
            $json_params = json_encode($params);
            $model->params = $json_params;
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Edit Proj'), '');
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //Faceset添加大量人脸
    public static function addFaceset($args) {
        $cnt = 3;
        $fa_method = new Face();
        $result = $fa_method::GetFacesetInfo($args['faceset_id']);
        //老的face_id集合
        foreach($result->faces as $n => $face){
            $face_old[] = $face;
        }
//        var_dump($face_old);

        if(!is_array($face_old)){
            $r['msg'] = Yii::t('common', 'attendance_error');
            $r['status'] = -2;
            $r['refresh'] = false;
            return $r;
        }
        //新的face_id集合
        $mc_face = ProgramUser::ProgramFaceid($args['program_id']);
        $mc_face_list = array_diff($mc_face,$face_old);//要添加的
//        $face_id = implode(",", $mc_face_list);
//        var_dump($mc_face_list);
//        exit;
        if(empty($mc_face_list)){
//            $mc_user_list = array();
        }else{
            $t = 0;
            foreach($mc_face_list as $k => $id){
//                var_dump($k);
//                exit;
                if($id) {
                    $sql = "SELECT user_id FROM bac_staff WHERE  face_id = '" . $id . "' ";
                    $command = Yii::app()->db->createCommand($sql);
                    $user_list = $command->queryAll();
                    $mc_user_list[$t]['user_id'] = $user_list[0]['user_id'];

                }else{
                    $mc_user_list[$t]['user_id'] = $k;
                }
                $t++;
            }

        }

        $total_count = count($mc_user_list);
        $user_list = array_slice($mc_user_list,$args['start_cnt'],$cnt);

        foreach($user_list as $t=>$arr){
            $add_list[] = $arr['user_id'];
        }
        $del_list = array();
//        var_dump($args['start_cnt']);
//        var_dump($cnt);
//         var_dump($add_list);
//            exit;
        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);

        $t = $faceModel->FacesetEditFace($args['faceset_id'], $del_list, $add_list);//将总包下人员的faceid送给faceset
//        var_dump($t);
//        exit;
        if ($t['errno'] == '0') {
            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['start_cnt'] = (int)$args['start_cnt'];
            $r['cnt'] = $total_count;
            $r['faceset_id'] = $args['faceset_id'];
            $r['program_id'] = $args['program_id'];
            $r['refresh'] = true;
        }else {
            $r['msg'] = Yii::t('common', 'error_update');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //Faceset删除大量人脸
    public static function deleteFaceset($args) {
        $cnt = 3;
        $fa_method = new Face();
        $result = $fa_method::GetFacesetInfo($args['faceset_id']);
        //老的face_id集合
        foreach($result->faces as $cnt => $face){
            $face_old[] = $face;
        }
        if(!is_array($face_old)){
            $r['msg'] = Yii::t('common', 'attendance_error');
            $r['status'] = -2;
            $r['refresh'] = false;
            return $r;
        }
        //新的face_id集合
        $mc_face = ProgramUser::ProgramFaceid($args['program_id']);
        $mc_face_list = array_diff($face_old,$mc_face);//要删去的
        $face_id = implode(",", $mc_face_list);
        if($face_id ==""){
            $mc_user_list =array();
        }else{
            foreach($mc_face_list as $k => $id){
                $sql = "SELECT user_id FROM bac_staff WHERE  face_id = '".$id."' ";
                $command = Yii::app()->db->createCommand($sql);
                $user_list = $command->queryAll();
                $mc_user_list[$k]['user_id'] = $user_list[0]['user_id'];
            }
//            $sql = "SELECT user_id FROM bac_staff WHERE  face_id in ('".$face_id."')";
//            $command = Yii::app()->db->createCommand($sql);
//            $mc_user_list = $command->queryAll();
        }

        $total_count = count($mc_user_list);
        $user_list = array_slice($mc_user_list,$args['start_cnt'],$cnt);

        foreach($user_list as $cnt=>$arr){
            $del_list[] = $arr['user_id'];
        }
        $add_list = array();
        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
        $t = $faceModel->FacesetEditFace($args['faceset_id'], $del_list, $add_list);//将总包下人员的faceid送给faceset
        if ($t['errno'] == '0') {
            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['start_cnt'] = (int)$args['start_cnt'];
            $r['cnt'] = $total_count;
            $r['faceset_id'] = $args['faceset_id'];
            $r['program_id'] = $args['program_id'];
            $r['refresh'] = true;
        }else {
            $r['msg'] = Yii::t('common', 'error_update');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    //Factset编辑大量人脸
    public static function editFaceset($args) {
        $cnt = 3;
        //总包以及其下分包人员集合
        $mc_user_list = self::Mc_ScUserList($args['program_id']);
        $user_list = array_slice($mc_user_list,$args['start_cnt'],$cnt);
//        if($args['start_cnt'] == 15){
//            error_log(print_r($user_list));
//        }

        foreach($user_list as $cnt=>$arr){
            $ad_list[] = $arr['user_id'];
        }
//        $end_cnt = $args['start_cnt']+$cnt;
        $del_list = array();
        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
        $t = $faceModel->FacesetEditFace($args['faceset_id'], $del_list, $ad_list);//将总包下人员的faceid送给faceset
        if ($t['errno'] == '0') {
            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['start_cnt'] = $args['start_cnt'];
            $r['cnt'] = $args['cnt'];
            $r['faceset_id'] = $args['faceset_id'];
            $r['program_id'] = $args['program_id'];
            $r['refresh'] = true;
        }else {
            $r['msg'] = Yii::t('common', 'error_update');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    //中途设置飞搜
    public static function setFaceset($args) {
        $model = Program::model()->findByPk($args['program_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //如果总包项目进行修改，修改标识，faceset保留
//        var_dump($model->start_sign);
//        var_dump($args['start_sign']);
        //总包项目中途开启飞搜
        if($model->start_sign == '1'){
            //中途开启飞搜
            if(!$model->faceset_id) {
//                var_dump(1111);
                //创建facesetID
                $faceModel = new Face();
                $rs = $faceModel->FacesetCreate($args['program_id']);
                $model->updateByPk($args['program_id'], array('faceset_id' => $rs['faceset_id'], 'start_sign' => $args['start_sign']));
                //总包以及其下分包人员集合
                $mc_user_list = self::Mc_ScUserList($args['program_id']);
//                var_dump(count($mc_user_list));
//                exit;
                $count = count($mc_user_list);
//                var_dump($count);
//                exit;
//                foreach($mc_user_list as $cnt=>$arr){
//                    $ad_list[] = $arr['user_id'];
//                }
//                $del_list = array();
//                $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
//                $t = $faceModel->FacesetEditFace($rs['faceset_id'], $del_list, $ad_list);//将总包下人员的faceid送给faceset
                //查找总包下的分包&&循环加上facesetID以及修改飞搜标识
                $list = self::Mc_ScProgramList($args['program_id']);
                if (count($list) > 0) {
                    foreach ($list as $key => $row) {
                        $model->updateByPk($row['program_id'], array('faceset_id' => $rs['faceset_id'], 'start_sign' => $args['start_sign']));
//                        $user_list = self::programUser($row['program_id']);//得到分包的人员列表
//                        $del_list = array();
//                        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
////                        var_dump($user_list);
//                        foreach($user_list as $cnt=>$array){
//                            $add_list[] = $array['user_id'];
//                        }
//                        $t = $faceModel->FacesetEditFace($rs['faceset_id'], $del_list, $add_list);//将人员的face_id依次送给faceset
                    }
                }
//                if ($t['errno'] == '0') {
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['faceset_id'] = $rs['faceset_id'];
                $r['program_id'] = $args['program_id'];
                $r['cnt'] = $count;
                $r['refresh'] = true;
//                }
//                else {
//                    $r['msg'] = Yii::t('common', 'error_update');
//                    $r['status'] = -1;
//                    $r['status'] = -1;
//                    $r['refresh'] = false;
//                }
            }else{
//                var_dump(2222);
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 2;
                $r['refresh'] = true;
            }
        }
            //总包项目中途关闭飞搜
            if($model->start_sign != '1') {
                if($model->faceset_id) {
//                    var_dump(3333);
                    //faceset置空，修改标识，删除总包的faceset_id
                    $program_model = Program::model()->findByPk($args['program_id']);
                    $faceModel = new Face();
                    $t = $faceModel->FacesetDelete($program_model->faceset_id);
//                    var_dump($t);
                    $model->updateByPk($args['program_id'], array('faceset_id' => '', 'start_sign' => $args['start_sign']));
                    //查找总包下的分包&&循环加上facesetID以及修改飞搜标识
                    $list = self::Mc_ScProgramList($args['program_id']);
                    if (count($list) > 0) {
                        foreach ($list as $key => $row) {
                            $model->updateByPk($row['program_id'], array('faceset_id' => '', 'start_sign' => $args['start_sign']));
//                            $t = $faceModel->FacesetDelete($row['faceset_id']);
                        }
                    }
//                    var_dump($t);
//                    exit;
                    if ($t['errno'] == '0') {
                        $r['msg'] = Yii::t('common', 'success_update');
                        $r['status'] = 1;
                        $r['refresh'] = true;
                    } else {
                        $r['msg'] = Yii::t('common', 'error_update');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                    }
                }else{
//                    var_dump(4444);
                    //APP和云端考勤切换
                    $program_model = Program::model()->findByPk($args['program_id']);
                    $faceModel = new Face();
                    $t = $faceModel->FacesetDelete($program_model->faceset_id);
                    $model->updateByPk($args['program_id'], array('faceset_id' => '','start_sign' => $args['start_sign']));
                    //查找总包下的分包&&循环加上facesetID以及修改飞搜标识
                    $list = self::Mc_ScProgramList($args['program_id']);
                    if (count($list) > 0) {
                        foreach($list as $key => $row){
                            $model->updateByPk($row['program_id'], array('faceset_id' => '','start_sign' => $args['start_sign']));
                        }
                    }
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['status'] = 1;
                    $r['refresh'] = true;
                }
            }
            //没有做变化
//            if($model->start_sign == $args['start_sign']) {
//                $r['msg'] = Yii::t('common', 'success_update');
//                $r['status'] = 1;
//                $r['refresh'] = true;
//            }
            return $r;

    }
    //同步更新飞搜
    public static function updateFaceSet($ptype,$program_id){
        $model = Program::model()->findByPk($program_id);
        if($ptype == 'MC') {
            //private $FACESET_GET_INFO = "faceset/get_info";获取faceset先前的face_id  删除之后 如果是总包 添加自己项目下员工的face_id 再循环分包下各员工的face_id
            //总包项目删除faceset,新建faceset,循环加入faceid
                //创建facesetID
                $faceModel = new Face();
                $rs = $faceModel->FacesetCreate($program_id);
                $model->updateByPk($program_id, array('faceset_id' => $rs['faceset_id']));
                $mc_user_list = self::entranceUser($program_id);
                $del_list = array();
                $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
                $t = $faceModel->FacesetEditFace($rs['faceset_id'], $del_list, $mc_user_list);//将总包下人员的faceid送给faceset
                //查找总包下的分包&&循环加上facesetID以及修改飞搜标识
                $list = self::Mc_ScProgramList($program_id);
                if (count($list) > 0) {
                    foreach($list as $key => $row){
                        $model->updateByPk($row['program_id'], array('faceset_id' => $rs['faceset_id']));
                        $user_list = self::programUser($row['program_id']);//得到分包的人员列表
                        $del_list = array();
                        $faceModel = new Face();//var_dump($model->faceset_id); //var_dump($del_list);var_dump($add_list);
                        $t = $faceModel->FacesetEditFace($rs['faceset_id'], $del_list, $user_list);//将人员的face_id依次送给faceset
                    }
                }
                if ($t['errno'] == '0') {
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['status'] = 1;
                    $r['refresh'] = true;
                } else {
                    $r['msg'] = Yii::t('common', 'error_update');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                }
            return $r;
        }else{
            $root_proid = $model->root_proid;
            $mc_model = Program::model()->findByPk($root_proid);
            $faceset_id = $mc_model->faceset_id;
        }
    }
    //启用
    public static function startProgram($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Program::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {

            $model->status = self::STATUS_NORMAL;
            $result = $model->save();

            if ($result) {
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Start Proj'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_start');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //停用：结项
    public static function stopProgram($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = Program::model()->findByPk($id);
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $cnt = Program::model()->count('status='.self::STATUS_NORMAL.' and father_proid='.$id);
        //判断其下是否有未结项的子项目，有的话先提示将人员出场
        $sc_list = self::Mc_ScProgramList($id);
        foreach($sc_list as $i => $j){
            $sc_user_cnt =  ProgramUser::model()->count("program_id='".$j['program_id']."' and check_status=11");
            if ($sc_user_cnt <> 0) {
                $r['msg'] = Yii::t('proj_project', 'end_sc_projuser_is_exist');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }
        //子项目结项
        foreach($sc_list as $i => $j){
            $sc_model = Program::model()->findByPk($j['program_id']);
            $sc_model->status = self::STATUS_STOP;
            $re = $sc_model->save();
        }
        if ($cnt <> 0) {
            $r['msg'] = Yii::t('proj_project', 'error_subproj_is_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;

        }
        $user_cnt =  ProgramUser::model()->count("program_id='".$id."' and check_status=11");
        //判断项目下是否有已入场的员工，有的话不能结项
        if ($user_cnt <> 0) {
            $r['msg'] = Yii::t('proj_project', 'end_projuser_is_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {

            $model->status = self::STATUS_STOP;
            $result = $model->save();

            if ($result) {
                //项目成员打结项标记：
                ProgramUser::model()->updateAll(array('status'=>'01'), 'program_id=:program_id', array(':program_id'=>$id));
                if($model->father_proid == 0){  //总包项目
                    //faceset删除
                    if($model->start_sign == '1') {
                        $faceModel = new Face();
                        $rs = $faceModel->FacesetDelete($model->faceset_id);
                    }
                }
                else{   //分包项目
                    //faceset中删掉分包项目成员
                    if($model->start_sign == '1') {
                        $rows = ProgramUser::model()->findAll(array(
                            'select' => array('user_id'),
                            'condition' => 'program_id=:program_id',
                            'params' => array(':program_id' => $id),
                        ));
                        $del_list = array();
                        foreach (($rows) as $key => $row) {
                            $del_list[] = $row['user_id'];
                        }
                        FACE::FacesetEditFace($model->faceset_id, $del_list);
                    }
                }
                
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Stop Proj'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_stop');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_stop');
                $r['status'] = -11;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    
        //删除
    public static function deleteProgram($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('proj_project', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        $model = Program::model()->findByPk($id);
        //有孩子节点，不能删除
        if ($model->child_cnt <> 0) {
            $r['msg'] = Yii::t('proj_project', 'error_subproj_is_exist1');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //项目组成员是否为空     
        $cnt = ProgramUser::model()->count("program_id=:program_id AND check_status = '11'",array('program_id'=>$id));
        if($cnt > 0){
            $r['msg'] = Yii::t('proj_project', 'delete_projuser_is_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        
        try {
            $model->status = self::STATUS_DELETE;
            $result = $model->save();

            //操作员项目权限置状态
            $sql = "UPDATE bac_operator_program SET status = '1' WHERE program_id = '".$id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
//            $result = $model->delete();
            //删除项目组成员
//            $sql = "Delete From bac_program_user Where program_id = '".$id."'";
//            $command = $connection->createCommand($sql);
//            $command->execute();
            if ($result) {
                //分包项目：父节点的孩子数量-1
                if($model->father_proid <> 0){
                    $father_model = Program::model()->findByPk($model->father_proid);
                    $father_model->child_cnt = $father_model->child_cnt - 1;
                    $father_model->save();
                }
                else{   //总包项目
                    //faceset删除
                    if($model->start_sign == '1') {
                        $faceModel = new Face();
                        $rs = $faceModel->FacesetDelete($model->faceset_id);
                    }
                    //var_dump($rs);
                }
                
                OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project', 'Delete Proj'), self::updateLog($model));
                $r['msg'] = Yii::t('common', 'success_delete');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_delete');
                $r['status'] = -11;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    
    /** 得到承包商的项目id
     * @return type
     */
    public static function getProgramId() {

        $program_id = '';

        $sql = "SELECT program_id FROM bac_program WHERE contractor_id=:contractor_id";
        $command = Yii::app()->db->createCommand($sql);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $program_id .= $row['program_id'] . ',';
            }
        }
        if ($program_id != '')
            $program_id = substr($program_id, 0, strlen($program_id) - 1);

        return $program_id;
    }
     /** 得到承包商的总包项目id
     * @return type
     */
    public static function getMProgramId() {

        $program_id = '';

        $sql = "SELECT program_id FROM bac_program WHERE contractor_id=:contractor_id and father_proid=0";
        $command = Yii::app()->db->createCommand($sql);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $program_id .= $row['program_id'] . ',';
            }
        }
        if ($program_id != '')
            $program_id = substr($program_id, 0, strlen($program_id) - 1);

        return $program_id;
    }
    
    /**
     * 根据项目id得到总包商和根节点项目
     */
    public static function getProgramDetail($program_id) {

        $sql = "SELECT main_conid,root_proid FROM bac_program WHERE contractor_id=:contractor_id and program_id=:program_id";
        $command = Yii::app()->db->createCommand($sql);
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $command->bindParam(":program_id",$program_id,PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $list[$program_id]['main_conid'] = $row['main_conid'];
                $list[$program_id]['root_proid'] = $row['root_proid'];
            }
        }else{
            $list = array();
        }

        return $list;
    }
    /**
     * 所有项目列表
     * @return type
     */
    public static function programList($args=array()) {
        //var_dump($args);
        $condition = '';
        if($args['contractor_id'] != ''){
            $condition .= ($condition!=''?' AND ':'')."contractor_id='".$args['contractor_id']."'";
        }
        if($args['project_type'] == 'MC'){
            $condition .= ($condition!=''?' AND ':'')."father_proid=0";
        }
       
         if($args['status'] == ''){
            $condition .= ($condition!=''?' AND ':'')."status=00";
        }
        $list = array();
        $sql = "SELECT program_id,program_name FROM bac_program ";
        if($condition != '')
            $sql .= 'where '.$condition;
        $sql .= " order by status, program_id"; //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $list[trim($row['program_id'])] = trim($row['program_name']);
            }
        }
        return $list;
    }

    
    /**
     * 承包商项目所属的总包项目列表
     * @return type
     */
    public static function McProgramList($args=array()) {
        //var_dump($args);
        //if($args['contractor_id'] == ''){
            //return array();
        //}

        $list = array();
        if($args['program_id']){
            $sql = "select program_id, program_name
                  from bac_program 
                 where status in (00,01) and program_id = '".$args['program_id']."'";
        }else{
            $sql = "select program_id, program_name
                  from bac_program 
                 where status in (00,01) and program_id in (select root_proid from bac_program where contractor_id=".$args['contractor_id']." and status in (00,01))";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $list[trim($row['program_id'])] = trim($row['program_name']);
            }
        }
        return $list;
    }
    /**
     * 获取承包商所有项目
     */
    public static function programAllList($args=array()) {
        //var_dump($args);
        $condition = '';
        if($args['contractor_id'] != ''){
            $condition .= ($condition!=''?' AND ':'')."contractor_id='".$args['contractor_id']."'";
        }

        $condition .= ($condition!=''?' AND ':'')."status=00";

        $list = array();
        $sql = "SELECT program_id,program_name FROM bac_program ";
        if($condition != '')
            $sql .= 'where '.$condition;
        $sql .= " order by status, program_id"; //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $list[trim($row['program_id'])] = trim($row['program_name']);
            }
        }
        return $list;
    }
    /**
     * 根据总包项目ID获取其下的分包项目ID
     */
    public static function Mc_ScProgramList($program_id) {

        $list = array();
        $sql = "select program_id,faceset_id,program_name,contractor_id
                  from bac_program
                 where status=00 and father_proid =".$program_id." ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    /**
     * 根据总包项目ID获取其下的分包项目ID
     */
    public static function McScProgramList($program_id) {
        $pro_model =Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $list = array();
        if($root_proid != $program_id){
            $sql = "select program_id,program_name,contractor_id
                  from bac_program
                 where status=00 and program_id =".$program_id." ";
        }else{
            $sql = "select program_id,program_name,contractor_id
                  from bac_program
                 where status=00 and root_proid =".$program_id." ";
        }

        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    /**
     * 根据承包商获取分包项目所在公司ID
     */
    public static function Con_ScProgramList() {
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $list = array();
        $sql = "select distinct add_conid
                  from bac_program
                 where status=00 and contractor_id =".$contractor_id." and add_conid <> ".$contractor_id." ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    /**
     * 查询项目下所有人员（不区分状态）
     */
    public static function programUser($program_id) {

        $list = array();
        $sql = "select user_id
                  from bac_program_user_q
                 where  program_id =".$program_id." and check_status = 11 ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    /**
     * 查询总包项目以及其下分包项目人员集合
     */
    public static function Mc_ScUserList($program_id){
        $sql = "select a.user_id
                  from bac_program_user_q a,bac_program b,bac_staff_info c
                 where  a.check_status =11 and b.status=00 and b.root_proid =".$program_id." and a.program_id = b.program_id and a.user_id = c.user_id and c.face_img!=''";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    /**
     * 查询项目下已入场人员
     */
    public static function entranceUser($program_id){
        $sql = "select user_id
                  from bac_program_user_q
                 where  program_id =".$program_id." and check_status =11 ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    /**
     * 查询项目下已出场人员
     */
    public static function appearanceUser($program_id){
        $sql = "select user_id
                  from bac_program_user_q
                 where  program_id =".$program_id." and check_status =21 ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
    /**
     * 承包商项目所属的总包项目列表
     * @return type
     */
    public static function McProgramAddress($args=array()) {
        //var_dump($args);
        if($args['contractor_id'] == ''){
            return array();
        }

        $list = array();
        $sql = "select a.program_id,a.program_name,a.program_address,count(b.user_id) as count
                  from bac_program a,bac_program_user_q b
                 where a.status=00 and a.program_id in (select root_proid from bac_program where contractor_id=".$args['contractor_id'].") and a.program_id = b.program_id and b.check_status ='11' group by b.program_id";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if($row['program_address'] != '') {
                    $latlng = explode(',',$row['program_address']);
                    $list[trim($row['program_id'])]['pron_ame'] = trim($row['program_name']);
                    $list[trim($row['program_id'])]['lng'] = $latlng[0];
                    $list[trim($row['program_id'])]['lat'] = $latlng[1];
                    $list[trim($row['program_id'])]['count'] = $row['count'];
                }
            }
        }
        return $list;
    }

    /**
     * 承包商项目所属的总包项目列表
     * @return type
     */
    public static function ProgramCompany() {

        $list = array();
        $sql = "select a.program_id,a.program_name,b.contractor_name
                  from bac_program a,bac_contractor b
                 where a.status=00  and a.contractor_id = b.contractor_id ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $list[trim($row['program_id'])] = trim($row['contractor_name']);
            }
        }
        return $list;
    }

    /**
     * 一个项目下的所有承包商
     * @return type
     */
    public static function ProgramAllCompany($program_id) {

        $list = array();
        $sql = "select a.contractor_id,b.contractor_name
                  from bac_program a,bac_contractor b
                 where a.status=00 and root_proid = '$program_id' and a.contractor_id = b.contractor_id ";
        //var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $list[trim($row['contractor_id'])] = trim($row['contractor_name']);
            }
        }
        return $list;
    }
}
