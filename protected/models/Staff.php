<?php

Yii::import('application.models.Face');
Yii::import('application.extensions.faceall.FaMethod');
class Staff extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    const STATUS_DELETE = '1';//已删除
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包

    const LOANED_STATUS_NO = 0;  //未借调
    const LOANED_STATUS_YES = 1; //借调
    const WHITE_LIST_TYPE = 1;//白名单
    public $contractor_sn;
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_staff';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => Yii::t('comp_staff', 'User_id'),
            'user_name' => Yii::t('comp_staff', 'User_name'),
            'user_phone' => Yii::t('comp_staff', 'User_phone'),
            'role_id' => Yii::t('comp_staff', 'Role_id'),
            'primary_email' => Yii::t('comp_staff', 'Primary_email'),
            'working_life' => Yii::t('comp_staff','Working_life'),
            'work_pass_type'=> Yii::t('comp_staff', 'Work_pass_type'),
            'nation_type'=> Yii::t('comp_staff', 'Nation_type'),
            'work_no' => Yii::t('comp_staff', 'Work_no'),
//            'cert_no' => Yii::t('comp_staff', 'Cert_no'),
            'face_img' => Yii::t('comp_staff', 'Face_img'),
//            'team_id' => Yii::t('comp_staff', 'Team_id'),
            'status' => Yii::t('comp_staff', 'Status'),
            'category' => Yii::t('comp_staff','category'),
            'skill' => Yii::t('comp_staff','skill'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Operator the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //规则
    public function rules() {

        return array(
            array('face_img', 'file','types'=>'jpg, gif, png', 'allowEmpty'=>true, 'on'=>'edit'),
        );
    }
    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('comp_staff', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('comp_staff', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //work_pass_type工作准证类型
    public static function WorkPassType(){
        $type = array(
            'WP'    =>  'WP',
            'SP'    =>  'SP',
            'EP'    =>  'EP',
            'DP'    =>  'DP',
            'PR'    =>  'PR',
            'IC'    =>  'IC');
        return $type;
    }
    //性别
    public static function Gender(){
        if (Yii::app()->language == 'zh_CN') {
            $gender = array(
                'Male' => '男',
                'Female' => '女',
            );
        }else{
            $gender = array(
                'Male' => 'Male',
                'Female' => 'Female',
            );
        }
        return $gender;
    }
    //Race
    public static function Race(){
        if (Yii::app()->language == 'zh_CN') {
            $race = array(
                'Chinese' => 'Chinese',
                'Malays' => 'Malay',
                'Indians' => 'Indian',
                'Others' => 'Others',
            );
        }else{
            $race = array(
                'Chinese' => 'Chinese',
                'Malays' => 'Malay',
                'Indians' => 'Indian',
                'Others' => 'Others',
            );
        }
        return $race;
    }
    //Marital
    public static function Marital(){
        if (Yii::app()->language == 'zh_CN') {
            $marital = array(
                'Single' => 'Single',
                'Married' => 'Married',
                'Divorced' => 'Divorced',
            );
        }else{
            $marital = array(
                'Single' => 'Single',
                'Married' => 'Married',
                'Divorced' => 'Divorced',
            );
        }
        return $marital;
    }
    //技能
    public static function Skill(){
        if (Yii::app()->language == 'zh_CN') {
            $skill = array(
                '0' => '不适用',
                '1' => 'Higher-Skilled (R1)',
                '2' => 'Basic-Skilled (R2)',
            );
        }else{
            $skill = array(
                '0' => 'N/A',
                '1' => 'Higher-Skilled (R1)',
                '2' => 'Basic-Skilled (R2)',
            );
        }

        return $skill;
    }
    //人员类型
    public static function Category(){
        if (Yii::app()->language == 'zh_CN') {
            $category = array(
                '0' => '工人',
                '1' => '员工',
            );
        }else{
            $category = array(
                '0' => 'Worker',
                '1' => 'Staff',
            );
        }
        return $category;
    }
    //nation_type国籍类型:华人、黑人。work_pass_type为sp、wp时选择；
    public static function NationType($workpasstype=''){
        $type = array();
        if(in_array($workpasstype, array('WP'))){
            $type = array(
                'Chinese'   =>  'Chinese (PRC)',
                'Bangladeshi'   =>  'Bangladeshi (BAN)',
                'Indian' => 'Indian (IND)',
                'Malaysian' => 'Malaysian (MY)',
                'Indonesian' => 'Indonesian (INDO)',
                'Myanmar' => 'Myanmar (MM)',
                'Thai' => 'Thai (THA)',
                'Others' => 'Others'
            );
        }
        if(in_array($workpasstype, array('','SP'))){
            $type = array(
                'Chinese'   =>  'Chinese (PRC)',
                'Bangladeshi'   =>  'Bangladeshi (BAN)',
                'Indian' => 'Indian (IND)',
                'Malaysian' => 'Malaysian (MY)',
                'Indonesian' => 'Indonesian (INDO)',
                'Myanmar' => 'Myanmar (MM)',
                'Thai' => 'Thai (THA)',
                'Others' => 'Others',
            );
        }
        if(in_array($workpasstype, array('EP'))){
            $type = array(
                'Chinese'   =>  'Chinese (PRC)',
                'Bangladeshi'   =>  'Bangladeshi (BAN)',
                'Indian' => 'Indian (IND)',
                'Malaysian' => 'Malaysian (MY)',
                'Indonesian' => 'Indonesian (INDO)',
                'Myanmar' => 'Myanmar (MM)',
                'Thai' => 'Thai (THA)',
                'Others' => 'Others',
            );
        }
        if(in_array($workpasstype, array('DP'))){
            $type = array(
                ''   =>  '',
            );
        }
        if(in_array($workpasstype, array('PR'))){
            $type = array(
                'Chinese'   =>  'Chinese (PRC)',
                'Bangladeshi'   =>  'Bangladeshi (BAN)',
                'Indian' => 'Indian (IND)',
                'Malaysian' => 'Malaysian (MY)',
                'Indonesian' => 'Indonesian (INDO)',
                'Myanmar' => 'Myanmar (MM)',
                'Thai' => 'Thai (THA)',
                'Others' => 'Others',
            );
        }
        if(in_array($workpasstype, array('IC'))){
            $type = array(
                'Singaporean' => 'Singaporean (SG)',
            );
        }
        return $type;
    }

    //借调状态
    public static function LoanedStatus($status=''){
        $rs = array(
            self::LOANED_STATUS_NO  => 'N',
            self::LOANED_STATUS_YES  => 'Y',
        );
        return $status === null ? $rs : $rs[$status];
    }

    //部门
    public static function teamText($key = null) {
        $rs = array('Management ' => '管理层',
            'Safety Team' => '安全团队',
            'Sturcuture Team' => '结构团队',
            'M&E Team' => '机电团队',
            'Architectural' => '土木团队');
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_DISABLE => ' label-danger', //已注销
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 添加日志详细
     * @param type $model
     * @return array
     */
    public static function insertLog($model) {
        $log = array(
            $model->getAttributeLabel('user_name') => $model->user_name,
            $model->getAttributeLabel('user_phone') => $model->user_phone,
            $model->getAttributeLabel('role_id') => $model->role_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
            $model->getAttributeLabel('work_pass_type') => $model->work_pass_type,
            $model->getAttributeLabel('nation_type') => $model->nation_type,
            $model->getAttributeLabel('work_no') => $model->work_no,
            //$model->getAttributeLabel('face_img') => $model->face_img,
            $model->getAttributeLabel('face_id') => $model->face_id,
        );
        return $log;
    }

    /**
     * 修改日志详细
     * @param type $model
     * @return array
     */
    public static function updateLog($model) {
        $log = array(
            $model->getAttributeLabel('user_name') => $model->user_name,
            $model->getAttributeLabel('user_phone') => $model->user_phone,
            $model->getAttributeLabel('role_id') => $model->role_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
            $model->getAttributeLabel('work_pass_type') => $model->work_pass_type,
            $model->getAttributeLabel('nation_type') => $model->nation_type,
            $model->getAttributeLabel('work_no') => $model->work_no,
            //$model->getAttributeLabel('face_img') => $model->face_img,
            $model->getAttributeLabel('face_id') => $model->face_id,
        );
        return $log;
    }

    public static function logoutLog($model) {
        $log = array(
            $model->getAttributeLabel('user_name') => $model->user_name,
            $model->getAttributeLabel('user_phone') => $model->user_phone,
            $model->getAttributeLabel('role_id') => $model->role_id,
            $model->getAttributeLabel('primary_email') => $model->primary_email,
            $model->getAttributeLabel('work_no') => $model->work_no,
            //$model->getAttributeLabel('face_img') => $model->face_img,
        );
        return $log;
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
//        exit;
        $condition = '';
        $params = array();
//        var_dump($args);
//        exit;
        //user_phone
        if ($args['user_phone'] != '') {
            $condition.= ( $condition == '') ? ' user_phone LIKE :user_phone' : ' AND user_phone LIKE :user_phone';
            $params['user_phone'] = '%' . str_replace(' ','', $args['user_phone']) .'%';
        }
       //work_no
        if ($args['work_no'] != '') {
            $condition.= ( $condition == '') ? ' work_no LIKE :work_no' : ' AND work_no LIKE :work_no';
            $params['work_no'] = '%' . str_replace(' ','', $args['work_no']) .'%' ;
        }
       //work_pass_type
        if ($args['work_pass_type'] != '') {
            $condition.= ( $condition == '') ? ' work_pass_type=:work_pass_type' : ' AND work_pass_type=:work_pass_type';
            $params['work_pass_type'] = str_replace(' ','', $args['work_pass_type']) ;
        }
        //nation_type
        if ($args['nation_type'] != '') {
//            var_dump($args['nation_type']);
            $condition.= ( $condition == '') ? ' nation_type=:nation_type' : ' AND nation_type=:nation_type';
            $params['nation_type'] = str_replace(' ','', $args['nation_type']) ;
        }
         //white_list_type
//        if ($args['white_list_type'] != '') {
//            $condition.= ( $condition == '') ? ' white_list_type=:white_list_type' : ' AND white_list_type=:white_list_type';
//            $params['white_list_type'] = str_replace(' ','', $args['white_list_type']) ;
//        }
        //User_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //category
        if ($args['category'] != '') {
            $condition.= ( $condition == '') ? ' category=:category' : ' AND category=:category';
            $params['category'] = $args['category'];
        }

        //role
        if ($args['role_id'] != '') {
            $condition.= ( $condition == '') ? ' role_id=:role_id' : ' AND role_id=:role_id';
            $params['role_id'] = $args['role_id'];
        }

        //User_name
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' user_name LIKE :user_name' : ' AND user_name LIKE :user_name';
            $params['user_name'] = '%' . $args['user_name'] . '%';
        }
        //loane_type
        if ($args['loane_type'] != '') {
            if($args['loane_type'] == '1'){  //调入
                $condition.= ( $condition == '') ? " loaned_status='1'" : " AND loaned_status='1'";
                $condition.= ( $condition == '') ? ' contractor_id <> original_conid and contractor_id=:contractor_id' : ' AND contractor_id <> original_conid and contractor_id=:contractor_id';
            }
            if($args['loane_type'] == '2'){  //调出
                $condition.= ( $condition == '') ? " loaned_status='1' " : " AND loaned_status='1'";
                $condition.= ( $condition == '') ? ' contractor_id <> original_conid and original_conid=:contractor_id' : ' AND contractor_id <> original_conid and original_conid=:contractor_id';
            }
        }

        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' (contractor_id =:contractor_id or original_conid=:contractor_id) ' : ' AND (contractor_id =:contractor_id or original_conid=:contractor_id) ';
            $params['contractor_id'] = $args['contractor_id'];
        }

        $total_num = Staff::model()->count($condition, $params); //总记录数
//        var_dump($total_num);
        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $order = 'user_name ASC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = $order;
//        var_dump($criteria);
//        exit;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = Staff::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 按项目查询员工信息
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByProgram($page, $pageSize, $args = array()) {
//        var_dump($args);
//        exit;
        $condition = '';
        $params = array();
//        var_dump($args);
//        exit;
        //user_phone
        if ($args['user_phone'] != '') {
            $condition.= ( $condition == '') ? ' t.user_phone=:user_phone' : ' AND t.user_phone=:user_phone';
            $params['user_phone'] = str_replace(' ','', $args['user_phone']);
        }
        //work_no
        if ($args['work_no'] != '') {
            $condition.= ( $condition == '') ? ' t.work_no=:work_no' : ' AND t.work_no=:work_no';
            $params['work_no'] = str_replace(' ','', $args['work_no']) ;
        }
        //work_pass_type
        if ($args['work_pass_type'] != '') {
            $condition.= ( $condition == '') ? ' t.work_pass_type=:work_pass_type' : ' AND t.work_pass_type=:work_pass_type';
            $params['work_pass_type'] = str_replace(' ','', $args['work_pass_type']) ;
        }
        //nation_type
        if ($args['nation_type'] != '') {
//            var_dump($args['nation_type']);
            $condition.= ( $condition == '') ? ' t.nation_type=:nation_type' : ' AND t.nation_type=:nation_type';
            $params['nation_type'] = str_replace(' ','', $args['nation_type']) ;
        }
        //white_list_type
//        if ($args['white_list_type'] != '') {
//            $condition.= ( $condition == '') ? ' white_list_type=:white_list_type' : ' AND white_list_type=:white_list_type';
//            $params['white_list_type'] = str_replace(' ','', $args['white_list_type']) ;
//        }
        //User_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' t.status=:status' : ' AND t.status=:status';
            $params['status'] = $args['status'];
        }
        //category
        if ($args['category'] != '') {
            $condition.= ( $condition == '') ? ' t.category=:category' : ' AND t.category=:category';
            $params['category'] = $args['category'];
        }
        //User_name
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' t.user_name LIKE :user_name' : ' AND t.user_name LIKE :user_name';
            $params['user_name'] = '%' . $args['user_name'] . '%';
        }
        //loane_type
        if ($args['loane_type'] != '') {
            if($args['loane_type'] == '1'){  //外借进来的
                $condition.= ( $condition == '') ? " t.loaned_status='1'" : " AND t.loaned_status='1'";
            }
            if($args['loane_type'] == '2'){  //借调出去的
                $condition.= ( $condition == '') ? " t.loaned_status='2' AND t.original_conid=:original_conid" : " AND t.loaned_status='2' AND t.original_conid=:original_conid";
                $params['original_conid'] = $args['contractor_id'];
                $args['contractor_id'] = '';
            }
        }

        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id ' : ' AND t.contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }

//        var_dump($condition);
//        $total_num = Staff::model()->count($condition, $params); //总记录数
//        var_dump($total_num);
        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $order = 'user_name ASC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $root_proid = $args['root_proid'];
        $contractor_id = $args['contractor_id'];

        if($args['tag'] == 'out'){
            $criteria->join = "RIGHT JOIN bac_program_user_q b ON b.root_proid = '$root_proid' and b.contractor_id ='$contractor_id' and t.user_id = b.user_id and b.check_status in (10)";
        }else{
            $criteria->join = "RIGHT JOIN bac_program_user_q b ON b.root_proid = '$root_proid' and b.contractor_id ='$contractor_id' and t.user_id = b.user_id and b.check_status in (11,20)";
        }

        $criteria->params = $params;
        $criteria->order = $order;
//        var_dump($criteria);
//        $pages = new CPagination($total_num);
//        $pages->pageSize = $pageSize;
//        $pages->setCurrentPage($page);
//        $pages->applyLimit($criteria);

        $rows = Staff::model()->findAll($criteria);
        $start=$page*$pageSize; #计算每次分页的开始位置
//        var_dump($start);
//        var_dump($pageSize);
        $total_num = count($rows);
        $pagedata=array();
        $pagedata=array_slice($rows,$start,$pageSize);


        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;

        return $rs;
    }

     public static function queryByName($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();
        //contractor_name
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' user_name = :contractor_name' : ' AND user_name = :user_name';
            $params['user_name'] = $args['user_name'];
        }

        $total_num = Contractor::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();


        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = 3;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = Contractor::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //插入数据
    public static function insertStaff($args,$infoargs) {

        //form id　注意为model的数据库字段
        if(empty($args)){
            $r['msg'] = Yii::t('comp_staff', 'Error User_name is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $args['user_phone'] = str_replace(' ','',$args['user_phone']);
        $args['work_no'] = str_replace(' ','',$args['work_no']);

        if ($args['user_phone'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error User_name is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['work_no']==''){
            $r['msg']=Yii::t('comp_staff', 'Error Work_no is null');
            $r['status']= -1;
            $r['refersh'] = false;
            return $r;
        }
        if ($args['role_id']==''){
            $r['msg']=Yii::t('comp_staff', 'Error role_id is null');
            $r['status']= -1;
            $r['refersh'] = false;
            return $r;
        }
//        if ($args['working_life']==''){
//            $r['msg']=Yii::t('comp_staff', 'Error Working_life is null');
//            $r['status']= -1;
//            $r['refersh'] = false;
//            return $r;
//        }

        $exist_data = Staff::model()->count('user_phone=:user_phone', array('user_phone' => $args['user_phone']));
        if ($exist_data != 0) {
            $sql = "SELECT a.user_name,b.contractor_name FROM bac_staff a,bac_contractor b WHERE a.contractor_id=b.contractor_id AND a.user_phone = :user_phone ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":user_phone", $args['user_phone'], PDO::PARAM_INT);
            $s = $command->queryAll();
            $r['msg'] = Yii::t('comp_staff', 'Error User_phone is exist').'  '.$s[0]['contractor_name'].'.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $contractor_id = $args['contractor_id'];
        $exist_data = Staff::model()->count('work_no=:work_no', array('work_no' => $args['work_no']));
        if ($exist_data != 0) {
            $sql = "SELECT a.user_name,b.contractor_name FROM bac_staff a,bac_contractor b WHERE a.work_no = :work_no and a.contractor_id = b.contractor_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":work_no", $args['work_no'], PDO::PARAM_INT);
            $s = $command->queryAll();
            $r['msg'] = Yii::t('comp_staff', 'Error Work_no is exist').'  '.$s[0]['contractor_name'].'.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
//        $exist_data = Staff::model()->count('user_phone=:user_phone', array('user_phone' => $args['user_phone']));
//        if ($exist_data != 0) {
//            $sql = "SELECT a.user_name,b.contractor_name FROM bac_staff a,bac_contractor b WHERE a.contractor_id=b.contractor_id AND a.user_phone = :user_phone ";
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":user_phone", $args['user_phone'], PDO::PARAM_INT);
//            $s = $command->queryAll();
//            $r['msg'] = Yii::t('comp_staff', 'Error User_phone is exist').'  '.$s[0]['contractor_name'].'.';
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
//        $face_rs=Face::face_id($infoargs['face_img']);
//        var_dump($face_rs);
//        var_dump($infoargs['face_img']);
//        exit;
//        if($infoargs['face_img']){
//            $face_rs=Face::face_id($infoargs['face_img']);
//            $infoargs['face_img'] = substr($infoargs['face_img'],18);
//            if($face_rs['errno'] == -1){
//                $r['msg'] = Yii::t('comp_staff', 'Error no face');
//                $r['status'] = -1;
//                $r['refresh'] = false;
//                return $r;
//            }
//        }


        $model = new Staff('create');
        $trans = $model->dbConnection->beginTransaction();
        try{
//            $model->face_id=$face_rs['face_id'];
            $model->user_name = addslashes($args['user_name']);
            $model->user_phone = $args['user_phone'];
            $model->primary_email = $args['primary_email'];
            $model->working_life = $args['working_life'];
            $model->work_pass_type = $args['work_pass_type'];
            $model->nation_type = $args['nation_type'];
            $model->work_no = $args['work_no'];
            $model->role_id = $args['role_id'];
            if(array_key_exists('app',$args)){
                if($args['app'] == '1'){
                    $model->login_passwd = md5('123456');
                }else{
                    $model->login_passwd = md5('123456');
                }
            }else{
                $model->login_passwd = md5('123456');
            }

            $model->contractor_id = $args['contractor_id'];
            $model->original_conid = $model->contractor_id;
            $model->category = $args['category'];
//            $model->status = self::STATUS_NORMAL;
            $result = $model->save();//var_dump($result);exit;

            $id = $model->user_id;
            //var_dump($id);
            //var_dump($infoargs);
            // 添加资质信息
            if ($result) {

                $staff_model =Staff::model()->findByPk($id);
                $contractor_id = $staff_model->contractor_id;
                $result = Dms::NewContractor($contractor_id);

                $result = Dms::NewUser($id);

                if($result['code'] == '100'){
                    $staff_model =Staff::model()->findByPk($id);
                    $staff_model->dms_tag = '1';
                    $staff_model->save();
                }

                if($id){
                    //创建web端操作员
                    if($args['web'] == '1'){
                        Operator::setOperator($id);
                    }

                    if(array_key_exists('tag',$infoargs)){
//                        var_dump(111);
                        $r = UserAptitude::insertBach($id,$args,$infoargs);
                    }
                    if(!array_key_exists('skill',$infoargs)){
                        $infoargs['skill'] = '0';
                    }
                    if($r['status'] == '-1'){
                        return $r;
                    }
                    $r = StaffInfo::insertStaffInfo($id,$infoargs);//var_dump($r);
                    if($r['status'] == '-1'){
                        return $r;
                    }
                    $result = Dms::NewUser($id);
                    if($result['code'] == '100'){
                        $staff_model =Staff::model()->findByPk($id);
                        $staff_model->dms_tag = '1';
                        $staff_model->save();
                    }
                }

                $program_id = $args['program_id'];
                if($program_id){
                    $pro_model = Program::model()->findByPk($program_id);
                    $root_proid = $pro_model->root_proid;
                    $program_name = $pro_model->program_name;
                    $pro_conid = $pro_model->contractor_id;
                    $sc_program_id = $program_id;
                    //总包
                    if($program_id == $root_proid){
                        $exist_data = Program::model()->count('root_proid=:root_proid and contractor_id=:contractor_id', array('root_proid' => $program_id,'contractor_id'=>$contractor_id));
                        //如果该项目下不存在此分包，创建分包
                        if ($exist_data == 0) {
                            $pro_args['program_content'] = 'auto create';
                            $pro_args['program_name'] = $program_name;
                            $pro_args['contractor_id'] = $contractor_id;
                            $pro_args['add_conid'] = $pro_conid;
                            $pro_args['status'] = '00';
                            $pro_args['record_time'] = date("Y-m-d H:i:s");
                            $pro_args['root_proid'] = $program_id;
                            $father_model = Program::model()->findByPk($program_id);
                            $pro_args['father_model'] = $father_model;
                            $pro_args['father_proid'] = 0;
                            $pro_args['main_conid'] = $pro_conid;
                            //创建分包
                            $r = Program::insertProgram($pro_args);
                            $sc_program_id = $r['id'];
                        }
                        //入场操作
                        $prouser_args['program_id'] = $sc_program_id;
                        $prouser_args['user_id'] = $id;
                        $prouser_args['contractor_id'] = $contractor_id;
                        ProgramUser::SubmitProgramUser($prouser_args);
                    }else{
                        //分包情况
                        //入场操作
                        $prouser_args['program_id'] = $sc_program_id;
                        $prouser_args['user_id'] = $id;
                        $prouser_args['contractor_id'] = $contractor_id;
                        ProgramUser::SubmitProgramUser($prouser_args);
                    }
                }


                $trans->commit();
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_staff', 'Add Staff'), self::insertLog($model));
                $r['user_id'] = $id;
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
            else {
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }
        catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }


        return $r;
    }

    //修改数据
    public static function updateStaff($args,$infoargs) {
        $args['user_phone'] = str_replace(' ','',$args['user_phone']);
        $args['work_no'] = str_replace(' ','',$args['work_no']);
        $connection = Yii::app()->db;
       // 判断uer_id
        if ($args['user_id'] == '') {
            $r['msg'] = Yii::t('com_staff', 'Error user_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
         if ($args['work_no'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error Work_no is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['role_id']==''){
            $r['msg']=Yii::t('comp_staff', 'Error role_id is null');
            $r['status']= -1;
            $r['refersh'] = false;
            return $r;
        }
        //var_dump($args['user_id']);
        $model = Staff::model()->findByPk($args['user_id']);
        $orig_phone = $model->user_phone;
        $transaction=$model->dbConnection->beginTransaction();

        //判断一条记录
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //手机号+工号+公司编号确定唯一
        $contractor_id = Yii::app()->user->getState('contractor_id');
        //验证work no 是否存在
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = "select count(*) from bac_staff where work_no = :work_no and  user_id <> :user_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_STR);
        $command->bindParam(":work_no", $args['work_no'], PDO::PARAM_STR);
        $exist_data = $command->queryAll();
        $data = $exist_data[0]["count(*)"];
        if ($data !=0){
            $sql = "SELECT a.user_name,b.contractor_name FROM bac_staff a,bac_contractor b WHERE  a.work_no = :work_no and a.contractor_id = b.contractor_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":work_no", $args['work_no'], PDO::PARAM_STR);
            $rows = $command->queryAll();
            $r['msg'] = Yii::t('comp_staff', 'Error Work_no is exist').'  '.$rows[0]['contractor_name'].'.';
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }

        //验证手机号是否存在
        $sql = "select count(*) from bac_staff where user_phone = :user_phone and user_id != :user_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_STR);
        $command->bindParam(":user_phone", $args['user_phone'], PDO::PARAM_STR);
        $exist_data = $command->queryAll();
        $data = $exist_data[0]["count(*)"];
        if ($data !=0) {
            $sql = "SELECT a.user_name,b.contractor_name FROM bac_staff a,bac_contractor b WHERE a.contractor_id=b.contractor_id AND a.user_phone = :user_phone ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":user_phone", $args['user_phone'], PDO::PARAM_INT);
            $s = $command->queryAll();
            $r['msg'] = Yii::t('comp_staff', 'Error User_phone is exist').'  '.$s[0]['contractor_name'].'.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $sql = "select * from bac_operator where operator_id = :operator_id ";
        $command = $connection->createCommand($sql);
        $command->bindParam(":operator_id", $args['user_phone'], PDO::PARAM_STR);
        $operator_list = $command->queryAll();
        if(count($operator_list)>0){
            $sql = "update bac_operator set operator_id = :operator_id where operator_id = '".$orig_phone."'";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":operator_id", $args['user_phone'], PDO::PARAM_STR);
            $command->execute();

            $sql = "update bac_operator_app set operator_id = :operator_id where operator_id = '".$orig_phone."'";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":operator_id", $args['user_phone'], PDO::PARAM_STR);
            $command->execute();

            $sql = "update bac_operator_menu_q set operator_id = :operator_id where operator_id = '".$orig_phone."'";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":operator_id", $args['user_phone'], PDO::PARAM_STR);
            $command->execute();

            $sql = "update bac_operator_program set operator_id = :operator_id where operator_id = '".$orig_phone."'";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":operator_id", $args['user_phone'], PDO::PARAM_STR);
            $command->execute();
        }
        try {
            //用户更换新的头像
            if ($infoargs['face_img'] <> '') {
                //已经有faceid
//                if($model->face_id) {
//                    $old_face_id = $model->face_id;
//
//                    $face_rs = Face::face_id($infoargs['face_img']);
////                var_dump( $face_rs);
////                exit;
//                    if ($face_rs['errno'] == -1) {
//                        $r['msg'] = Yii::t('comp_staff', 'Error no face');
//                        $r['status'] = -1;
//                        $r['refresh'] = false;
//                        return $r;
//                    }
//                    $model->face_id = $face_rs['face_id'];
//
//                    $new_face_id = $face_rs['face_id'];
//
//
//                }
                $infoargs['face_img'] = substr($infoargs['face_img'], 18);
            }
//            else {
//                //没有face_id更新id
//                if (!$model->face_id) {
//                    $old_face_id = $model->face_id;
//                    $staffinfo_model = StaffInfo::model()->findByPk($args['user_id']);
//                    if ($staffinfo_model->face_img) {
//                        $face_img = '/opt/www-nginx/web' . $staffinfo_model->face_img;
//                        if(file_exists($face_img)){
//                            $face_rs = Face::face_id($face_img);
//                            if ($face_rs['errno'] == -1) {
//                                $r['msg'] = Yii::t('comp_staff', 'Error no face');
//                                $r['status'] = -1;
//                                $r['refresh'] = false;
//                                return $r;
//                            }
//                            $model->face_id = $face_rs['face_id'];
//                            $new_face_id = $face_rs['face_id'];
//                        }
//                    }
//                }
//            }
            /*if (count($args['role_id']) != 0) {
                $args['role_id'] = implode(",", $args['role_id']);
            }*/
             /* 编辑用户的头像:更新含有此用户的faceset中的脸  */
//            if($new_face_id){
//                Face::EditUserFace($model->user_id, $old_face_id, $new_face_id);
//            }
            if(array_key_exists('app',$args)){
                if($args['app'] == '1'){
                    $model->login_passwd = md5('123456');
                }else{
                    $model->login_passwd = md5('123456');
                }
            }else{
                $model->login_passwd = md5('123456');
            }
            $model->user_name = addslashes($args['user_name']);
            $model->user_phone = str_replace(' ','',$args['user_phone']);
            $model->primary_email = $args['primary_email'];
            $model->working_life = $args['working_life'];
            $model->work_pass_type = $args['work_pass_type'];
            $model->nation_type = $args['nation_type'];
            $model->work_no = str_replace(' ','',$args['work_no']);
            $model->role_id = $args['role_id'];
            $program_role = $args['role_id'].'|null|null';
            $sql = "UPDATE bac_program_user_q SET program_role='".$program_role."' WHERE user_id='".$args['user_id']."'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->execute();
            $model->category = $args['category'];
            $result = $model->save();
            //记录日志 修改资质信息
            if ($result) {
                $result = Dms::NewUser($args['user_id']);
//        var_dump($result);
//        var_dump($result);
//        exit;
                if($result['code'] == '100'){
                    $staff_model =Staff::model()->findByPk($args['user_id']);
                    $staff_model->dms_tag = '1';
                    $staff_model->save();
                }
                if($args['user_id']){
                    //对员工资质信息进行修改
//                    var_dump($infoargs);
//                    exit;
                    $r = StaffInfo::updateStaffInfo($args,$infoargs);
                    if($r['status'] == '-1'){
                        return $r;
                    }
                }
                $transaction->commit();
                OperatorLog::savelog(OperatorLog::MODULE_ID_USER, Yii::t('comp_staff', 'Edit Staff'), self::updateLog($model));

                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $transaction->rollBack();
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //添加人员二维码
    public static function insertQrcode($user_id,$PNG_TEMP_DIR) {
        $path = substr($PNG_TEMP_DIR.$user_id.'.png',18);
        $sql = "update bac_staff set qrcode = '".$path."' where user_id = '".$user_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }

    //借调
    public static function LoaneStaff($args, $type=0) {
        $user_id = $args['user_id'];
        $original_conid = $args['original_conid'];
        $contractor_id = $args['contractor_id'];
        if($user_id == ''){
            $r['msg'] = Yii::t('comp_staff', 'Error user_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //查询用户是否在项目组中
        $cnt = ProgramUser::UserProgram($user_id);//var_dump($cnt);exit;
        if($cnt > 0){
            $r['msg'] = Yii::t('comp_staff', 'Error user is in program');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Staff::model()->findByPk($user_id);

        if($type == Staff::LOANED_STATUS_NO){     //借调
            //var_dump('aa');
            if($contractor_id == ''){
                $r['msg'] = Yii::t('comp_contractor', 'Error company_sn is null');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            //不能借调到本企业
            if($model->original_conid == $contractor_id){
                $r['msg'] = Yii::t('comp_staff', 'Error loane original comp');
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            $model->original_conid = $model->contractor_id;
            $model->contractor_id = $contractor_id;
            $model->loaned_status = 1;
            $model->loaned_time = date('Y-m-d h:m:s');
            $model->save();

            $r['msg'] = Yii::t('comp_staff', 'success_loane');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        if($type == Staff::LOANED_STATUS_YES){     //调回
            //var_dump('bb');
            $model->contractor_id = $model->original_conid;
            $model->loaned_status = 0;
            $model->loaned_back_time = date('Y-m-d h:m:s');
            $model->save();

            $r['msg'] = Yii::t('comp_staff', 'success_loane_back');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }
    /**
     *
     * 添加白名单
     */
    public static function addWhite($args) {
        if ($args['user_id'] == '') {
            $r['msg'] = Yii::t('comp_staff','Error User_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = Staff::model()->find('user_id=:user_id',array(':user_id' => $args['user_id']));
        if ($model == null) {
            $r['msg'] = Yii::t('common','error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try{
            $model->white_list_type = $args['white_list_type'];
            $result = $model->save();

            if ($result) {
                $r['msg'] = Yii::t('common', 'success_set');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_set');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function logoutStaff($id) {
        if ($id == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error User_id is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //var_dump($id);
        $model = Staff::model()->find('user_id=:user_id', array(':user_id' => $id));
        if ($model == null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //用户处于借调中，不能注销
        if ($model->loaned_status == Staff::LOANED_STATUS_YES) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        //查询用户是否在项目组中
        $t = ProgramUser::UserProgramName($id);
//        var_dump($t);exit;
        if($t > 0){
            $content = '';
            foreach($t as $cnt => $list) {
                $content.= $list['program_name'].',';
            }
            $content = substr($content, 0, strlen($content) - 1);
            $r['msg'] = $id . Yii::t('comp_staff', 'Error user is in program').$content.'.'.Yii::t('comp_staff', 'Error user do not');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try {
            $model->user_phone .= '[del]';
            $model->work_no .= '[del]';
            $model->status = self::STATUS_DISABLE;
            $result = $model->save();

            if ($result) {
                $del_status = self::STATUS_DELETE;
                $del_sql = 'UPDATE bac_aptitude set status=:status WHERE user_id=:user_id';
                $del_command = Yii::app()->db->createCommand($del_sql);
                $del_command->bindParam(":status", $del_status, PDO::PARAM_STR);
                $del_command->bindParam(":user_id", $id, PDO::PARAM_STR);
                $del_command->execute();
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_staff', 'Logout Staff'), self::logoutLog($model));

                $r['msg'] = Yii::t('common', 'success_logout');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error _logout');
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
    /**
     * 根据条件选择导出的员工
     */
    public static function staffExport($args) {
        $condition = '';
        $params = array();
        //array(9) { ["user_name"]=> string(0) "" ["user_phone"]=> string(0) "" ["work_no"]=> string(0) "" ["work_pass_type"]=> string(0) "" ["ation_type"]=> string(0) "" ["loane_type"]=> string(0) "" ["status"]=> string(1) "0" ["contractor_type"]=> string(2) "SC" ["contractor_id"]=> string(3) "118" }
//        var_dump($args);
//        exit;
        //user_phone
        if ($args['user_phone'] != '') {
            $condition.= ( $condition == '') ? ' user_phone=:user_phone' : ' AND user_phone=:user_phone';
            $params['user_phone'] = str_replace(' ','', $args['user_phone']);
        }
       //work_no
        if ($args['work_no'] != '') {
            $condition.= ( $condition == '') ? ' work_no=:work_no' : ' AND work_no=:work_no';
            $params['work_no'] = str_replace(' ','', $args['work_no']) ;
        }
       //work_pass_type
        if ($args['work_pass_type'] != '') {
            $condition.= ( $condition == '') ? ' work_pass_type=:work_pass_type' : ' AND work_pass_type=:work_pass_type';
            $params['work_pass_type'] = str_replace(' ','', $args['work_pass_type']) ;
        }
         //white_list_type
//        if ($args['white_list_type'] != '') {
//            $condition.= ( $condition == '') ? ' white_list_type=:white_list_type' : ' AND white_list_type=:white_list_type';
//            $params['white_list_type'] = str_replace(' ','', $args['white_list_type']) ;
//        }
        //nation_type
        if ($args['nation_type'] != '') {
            $condition.= ( $condition == '') ? ' nation_type=:nation_type' : ' AND nation_type=:nation_type';
            $params['nation_type'] = str_replace(' ','', $args['nation_type']) ;
        }
        //User_type
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //User_name
        if ($args['user_name'] != '') {
            $condition.= ( $condition == '') ? ' user_name LIKE :user_name' : ' AND user_name LIKE :user_name';
            $params['user_name'] = '%' . $args['user_name'] . '%';
        }
        //loane_type
        if ($args['loane_type'] != '') {
            if($args['loane_type'] == '1'){  //外借进来的
                $condition.= ( $condition == '') ? " loaned_status='1'" : " AND loaned_status='1'";
            }
            if($args['loane_type'] == '2'){  //借调出去的
                $condition.= ( $condition == '') ? " loaned_status='1' AND original_conid=:original_conid" : " AND loaned_status='1' AND original_conid=:original_conid";
                $params['original_conid'] = $args['contractor_id'];
                $args['contractor_id'] = '';
            }
        }

        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id ';
            $params['contractor_id'] = $args['contractor_id'];
        }
        if ($_REQUEST['q_order'] == '') {
            $order = 'user_id DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria = new CDbCriteria();
        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = $order;

        $rows = Staff::model()->findAll($criteria);

        if($args['tag'] != 0){
            foreach($rows as $i => $j){
                $tmparray = explode($j['user_id'],$args['tag']);
                if(count($tmparray)>1){
                    $rs[] = $j;
                }
            }
        }else{
            $rs = $rows;
        }

        return $rs;
    }
    /**
     * 根据员工手机号码得到员工信息
     */
    public static function userByPhone($user_phone) {
        $sql = "SELECT user_id FROM bac_staff WHERE user_phone=:user_phone";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":user_phone", $user_phone, PDO::PARAM_INT);
        $rows = $command->queryAll();
        return $rows;
    }
    /**
     * 根据员工手机号码得到员工信息
     */
    public static function phoneList($user_phone) {
        $sql = "SELECT user_id,user_name FROM bac_staff WHERE user_phone=:user_phone";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":user_phone", $user_phone, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }

        return $rs;
    }
    /**
     * 返回所有可用的人员
     * @return type
     */
    public static function staffList($contractor_id) {
        $sql = "SELECT user_id,user_name FROM bac_staff WHERE contractor_id=:contractor_id AND status=00";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        $r = array();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs['id'] = $row['user_id'];
                $rs['text'] = $row['user_name'];
                $r[] = $rs;
            }
        }
//        $re['results'] = $r;
//        $re['pagination']['more'] = true;
        return $r;
    }

    /**
     * 返回所有的人员
     * @return type
     */
    public static function userAllList() {
        $sql = "SELECT user_id,user_name FROM bac_staff";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']] = $row['user_name'];
            }
        }

        return $rs;
    }
    /**
     * 返回所有的人员的contractor
     * @return type
     */
    public static function contractorAllList() {
        $sql = "select a.user_name,a.user_id,b.contractor_name from bac_staff a,bac_contractor b where a.status = '0' and a.contractor_id = b.contractor_id and a.contractor_id in (101,146)";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['contractor_name']][] = $row;
            }
        }

        return $rs;
    }
    /**
     * 查询承包商下所有岗位是办公室人员的员工编号
     */
    public static function userOfficeList($contractor_id){
        $sql = "SELECT user_id FROM bac_staff WHERE role_id='OFIC' AND contractor_id=:contractor_id AND status=00";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[] = $row['user_id'];
            }
        }
        return $rs;
    }

    public static function QueryUser($args){
        $condition = '1=1';

        if ($args['user_name'] != '') {
            $user_name = $args['user_name'];
            $condition .= " and a.user_name like '$user_name%'";
        }
        if ($args['user_phone'] != '') {
            $user_phone = $args['user_phone'];
            $condition .= " and a.user_phone = '$user_phone'";
        }
        if ($args['user_work_no'] != '') {
            $user_work_no = $args['user_work_no'];
            $condition .= " and a.user_work_no = '$user_work_no'";
        }

        if($condition != '1=1'){
            $condition .= " and a.status = '0' and a.contractor_id = b.contractor_id";
            $sql = "select a.*,b.contractor_name from bac_staff a,bac_contractor b where ".$condition;
            $command = Yii::app()->db->createCommand($sql);
            $data = $command->queryAll();

            foreach($data as $i => $j){
                $program_id = $args['program_id'];
                $user_id = $j['user_id'];
                $data[$i]['check_status'] = '';
                $sql = "select check_status from bac_program_user_q where root_proid= $program_id and user_id = $user_id";
                $command = Yii::app()->db->createCommand($sql);
                $r = $command->queryAll();
                $data[$i]['check_status'] = $r[0]['check_status'];
            }

        }else{
            $data = array();
        }

        return $data;
    }

    /**
     * 查询某项目下所有人员的角色ID
     */
    public static function userRoleList($contractor_id){
        $sql = "SELECT user_id,role_id FROM bac_staff WHERE  contractor_id=:contractor_id AND status=00";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']]['role_id'] = $row['role_id'];
            }
        }
        return $rs;
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryinfoList($page, $pageSize, $args = array()) {
        //var_dump($args);
        $condition = '';
        $datediff = '';
        $params = array();
        //user_phone
//        if($a != '') {
//            $y = implode(",",$a);
////            var_dump($y);
////            exit;
//            $condition.=( $condition == '') ? 'user_id IN ('.$y.')' : ' AND user_id IN ('.$y.')';
//        }
        if ($args['user_phone'] != '') {
            $user_phone = str_replace(' ','', $args['user_phone']);
            $condition.= "user_phone= '".$user_phone."'";
        }
        //User_type
        if ($args['status'] != '') {
            $condition.= " AND status= '".$args['status']."'";
        }
        //User_name
        if ($args['user_name'] != '') {
           $condition.= " AND user_name LIKE '".$args['user_name']."%'";
        }
        if($args['info'] == 'bca') {
            $datediff = "DATEDIFF(bca_expire_date,CURDATE()) as day,";
            $condition.= " AND DATEDIFF(bca_expire_date,CURDATE()) <= ".$args[days]." AND a.bca_expire_date <> '' AND a.bca_expire_date >= CURDATE()";
        }
        if($args['info'] == 'pass') {
            $datediff = "DATEDIFF(ppt_expire_date,CURDATE()) as day,";
            $condition.= " AND DATEDIFF(ppt_expire_date,CURDATE()) <= ".$args[days]." AND a.ppt_expire_date <> '' AND a.ppt_expire_date >= CURDATE()";
        }
        if($args['info'] == 'csoc') {
            $datediff = "DATEDIFF(csoc_expire_date,CURDATE()) as day,";
            $condition.= " AND DATEDIFF(csoc_expire_date,CURDATE()) <= ".$args[days]." AND a.csoc_expire_date <> '' AND a.csoc_expire_date >= CURDATE()";
        }
        if($args['info'] == 'ins_scy') {
            $datediff = " DATEDIFF(ins_scy_expire_date,CURDATE())as day,";
            $condition.= " AND DATEDIFF(ins_scy_expire_date,CURDATE()) <= ".$args[days]." AND a.ins_scy_expire_date <> '' AND a.ins_scy_expire_date >= CURDATE()";
        }
        if($args['info'] == 'ins_med') {
            $datediff = " DATEDIFF(ins_med_expire_date,CURDATE())as day,";
            $condition.= " AND DATEDIFF(ins_med_expire_date,CURDATE()) <= ".$args[days]." AND a.ins_med_expire_date <> '' AND a.ins_med_expire_date >= CURDATE()";
        }
        if($args['info'] == 'ins_adt') {
            $datediff = " DATEDIFF(ins_adt_expire_date,CURDATE())as day,";
            $condition.= " AND DATEDIFF(ins_adt_expire_date,CURDATE()) <= ".$args[days]." AND a.ins_adt_expire_date <> '' AND a.ins_adt_expire_date >= CURDATE()";
        }
        //contractor_id
        if ($args['contractor_id'] != '') {
            $condition.= "AND contractor_id = '".$args['contractor_id']."'";
        }
        if($args['info']!=''){
            $sql = "SELECT ".$datediff."a.*,b.* "
                . "FROM bac_staff_info a,bac_staff b "
                . "WHERE  a.user_id =b.user_id  ".$condition." order by b.user_id desc ";
//            $command = Yii::app()->db->createCommand($sql);
            $command =Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
            $pages = new CPagination($total_num);
            $pages->pageSize = $pageSize;
            $pages->setCurrentPage($page);
            $currentPage = (int)$page;
//            var_dump($pages->currentPage);
//            exit;
            $command->bindValue(':offset', $currentPage*$pages->pageSize);//$pages->getOffset();
            $command->bindValue(':limit', $pages->pageSize);//$pages->getLimit();
            $rows = $command->queryAll();
            $total_num = yii::app()->db->createCommand($sql)->query()->rowCount;
        }
        $criteria=new CDbCriteria();

//        $pages->applyLimit($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 查询某员工具体信息
     */
    public static function userInfo(){
        $sql = "SELECT * FROM bac_staff WHERE  status=00";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']][] = $row;
            }
        }
        return $rs;
    }

    /**
     * 查询某员工具体信息（包括已被删除的）
     */
    public static function allInfo(){
        $sql = "SELECT * FROM bac_staff ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']][] = $row;
            }
        }
        return $rs;
    }
    //生成人员二维码
    public static function buildQrCode($contractor_id,$user_id) {
        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $contractor_id . '/user/';
        //include "qrlib.php";
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);
        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


        //    $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        $filename = $PNG_TEMP_DIR . $user_id . '.png';
        $content = 'uid|' . $user_id;
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        Staff::insertQrcode($user_id,$PNG_TEMP_DIR);
    }
}
