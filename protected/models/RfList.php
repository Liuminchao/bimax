<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfList extends CActiveRecord {

    //状态
    const STATUS_DRAFT = '-1'; //草稿
    const STATUS_PENDING = '0'; //进行中
    const STATUS_CLOSE = '1'; //关闭
    const STATUS_TIMEOUT = '2';//超时
    const BUTTON_DRAFT = '0';//草稿按钮
    const BUTTON_SUBMIT = '1';//提交按钮
    const BUTTON_CLOSE = '2';//关闭按钮
    const BUTTON_REPLY = '3';//回复按钮
    const BUTTON_FORWARD = '4';//转发按钮
    const BUTTON_COMMENT = '5';//转发按钮
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_record';
    }

    //状态
    public static function statusRfaText($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Ongoing',
            self::STATUS_CLOSE =>  'Replied',
            self::STATUS_TIMEOUT => 'Overdue'
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态
    public static function statusRfiText($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Ongoing',
            self::STATUS_CLOSE =>  'Closed',
            self::STATUS_TIMEOUT => 'Overdue'
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态
    public static function statusColor($key = null) {
        $rs = array(
            self::STATUS_PENDING => '#5bc0de',
            self::STATUS_CLOSE =>  '#5cb85c',
            self::STATUS_TIMEOUT => '#d9534f'
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'bg-info',
            self::STATUS_PENDING => 'bg-info',
            self::STATUS_CLOSE =>  'bg-success',
            self::STATUS_TIMEOUT => 'bg-danger'
        );
        return $key === null ? $rs : $rs[$key];
    }


    public static function rfaType($key = null) {
        $rs = array(
            0=> 'Others',
            1 => 'Archi',
            2 =>  'M&E',
            3=>  'C&S',
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function typeList($key = null) {
        $rs = array(
            1 => 'CS',
            2 =>  'AR',
            3=>  'ME',
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function rvoList($key = null) {
        $rs = array(
            ''=> '',
            1 => 'Yes',
            2 =>  'No',
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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
        $operator_id = Yii::app()->user->id;
        $operator_model = Operator::model()->findByPk($operator_id);
        $operator_role = $operator_model->operator_role;
        $user = Staff::userByPhone($operator_id);
        $user_id = $user[0]['user_id'];
        if(!array_key_exists('select_type',$args)){
            $args['select_type'] = '0';
        }
        $select_type = $args['select_type'];
        $status = $args['status'];
        if($status == '-1'){
            $select_type = '0';
        }
        if ($args['dash_discipline'] != '') {
            $select_type = '0';
        }

        //我发起的
        if($select_type == '0'){
            // and a.current_step = b.step
            $sql = "select distinct a.* from rf_record a ";
        }else if($select_type == '1'){//cc
            $sql = "select distinct a.* from rf_record a ";
            $sql.= " join rf_record_user b on a.check_id = b.check_id ";
        }else if($select_type == '2'){//to+我发起的
            $sql = "select distinct a.* from rf_record a ";
            $sql.= " join rf_record_user b on a.check_id = b.check_id ";
        }else if($select_type == '3'){//全部的
            $sql = "select distinct a.* from rf_record a ";
        }else if($select_type == '-1'){//cc,to,create
            $sql = "select distinct a.* from rf_record a ";
            $sql.= " left join rf_record_user b on a.check_id = b.check_id ";
        }

        //setcookie('rf_select_type', $args['select_type']);
        //setcookie('rf_outcome', $args['outcome']);
        if($args['outcome'] != ''){
            $sql.= " join rf_record_detail c on a.check_id = c.check_id and c.deal_type= '".$args['outcome']."' ";
        }

        $program_id = $args['program_id'];
        $pro_model =Program::model()->findByPk($args['program_id']);
        $program_id = $pro_model->root_proid;
        $type_id = $args['type_id'];

        if($type_id != ''){
            $condition.= " where a.type = '$type_id'";
        }else{
            $condition.= " where a.type in ('1','2') ";
        }

        if($status != ''){
            $condition.= " and a.status = '$status'";
        }

        if(array_key_exists('rvo',$args)){
            setcookie('rf_rvo', $args['rvo']);
            $rvo = $args['rvo'];
            if($rvo != ''){
                $condition.= " and a.rvo = '$rvo'";
            }
        }

        if($args['time_tag'] != ''){
            $time_tag = $args['time_tag'];
            if($time_tag == '0'){
                $condition.= " and a.time_tag = '$time_tag'";
            }else if($time_tag == '1'){
                $condition.= " and a.time_tag = '$time_tag'";
            }else if($time_tag == '2'){
                $condition.= " and a.time_tag = '$time_tag'";
            }
        }

        if ($args['dash_discipline'] != '') {
            $select_type = '';
        }

        if ($args['con_id'] != '') {
            $con_id = $args['con_id'];
            $condition.= " and a.contractor_id = '$con_id'";
        }

        if ($args['dash_discipline'] != '' && $args['dash_discipline'] != '0') {
            $discipline = $args['dash_discipline'];
            $condition.= " and a.discipline = '$discipline'";
        }


        $condition.= " and a.project_id = '$program_id'";

        $condition.= " and a.forward_status <> '1'";

        setcookie('rf_start_date', $args['start_date']);
        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= " and a.apply_time >='$start_date'";
        }

        setcookie('rf_end_date', $args['end_date']);
        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= " and a.apply_time <='$end_date 23:59:59'";
        }

        setcookie('rf_subject', $args['subject']);
        if ($args['subject'] != '') {
            $subject = $args['subject'];
            $condition.= " and a.subject like '%$subject%'";
        }

        setcookie('rf_check_no', $args['check_no']);
        if ($args['check_no'] != '') {
            $check_no = $args['check_no'];
            $condition.= " and a.check_no like '%$check_no%'";
        }

        $dash_group_sql = "select check_id from (select * from
        (select '1' as count,ru.check_id,ru.user_id,rg.group_name as contractor_name, c.short_name as cname,ri.discipline,r.status,r.form_id from rf_record_user ru
        LEFT JOIN bac_staff s on ru.user_id = s.user_id
        LEFT JOIN bac_contractor c  on s.contractor_id = c.contractor_id
        LEFT JOIN rf_record r on ru.check_id=r.check_id 
        LEFT JOIN rf_record_item ri on ru.check_id = ri.check_id
        LEFT JOIN rf_group_user rgu on rgu.user_id=ru.user_id
        LEFT JOIN rf_group rg on rgu.group_id=rg.group_id
        where r.project_id='$program_id' and ru.user_id <> 'null' and r.type='$type_id' and ru.type='1'
        group by ru.check_id,rg.group_id
        order by ru.step asc) ss
        group by ss.check_id) rf_statics where ";

//        var_dump($select_type);
        //我发起的
        if($select_type == '0'){
            $condition.= " and a.apply_user_id = '$user_id' ";
        }else if($select_type == '1'){//cc
            $condition.= " and b.user_id = '$user_id' and b.type = '2' and a.status != '-1' ";
        }else if($select_type == '2'){//to+我发起的
            $condition.= " and b.user_id = '$user_id' and b.type = '1' and a.status != '-1' ";
        }else if($select_type == '3'){//全部的
            $condition.= " and a.project_id = '$program_id' ";
        }else if($select_type == '-1'){//cc,to,create
            $condition.= " and (a.apply_user_id = '$user_id' or b.user_id = '$user_id' and b.type = '2' or b.user_id = '$user_id' and b.type = '1') and a.status != '-1' ";
        }

        if ($status != '') {
            $dash_group_sql.= " status = '$status'";
        }

        if ($args['dash_form_id'] != '' && $args['dash_form_id'] != '0') {
            $form_id = $args['dash_form_id'];
            $dash_group_sql.= " and form_id = '$form_id'";
            $condition.= " and a.form_id = '$form_id'";
        }

        if ($args['dash_group_name'] != '') {
            $group_name = urldecode($args['dash_group_name']);
            $group_list = RfGroup::model()->find('group_name=:group_name',array(':group_name'=>$group_name));
            $group_name = $group_list->group_name;
            $dash_group_sql.= " and contractor_name = '$group_name'";
            $condition.=" and  a.check_id in (".$dash_group_sql.") ";
        }

        $order = ' order by a.apply_time desc';
        $sql = $sql.$condition.$order;
//        var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();

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

    //提交
    public static function Submit($args,$item){
        $json_args = json_encode($args);
        $json_item = json_encode($item);
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'RF Send'.' args: '.$json_args;
        self::write_log($txt);
        $txt = '['.$record_time.']'.'  '.'RF Send'.' item: '.$json_item;
        self::write_log($txt);

        if($args['no_co'] == '' || $args['no_site'] == '' || $args['no_discipline'] == '' || $args['no'] == ''){
            $r['msg'] = 'Please fill Ref no.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        if($args['to'] == '' || $args['to'] == 'null'){
            $r['msg'] = 'Please select To.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        if($args['status'] != '-1'){
            if($args['subject'] == ''){
                $r['msg'] = 'Please select Subject.';
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            if($args['discipline'] == ''){
                $r['msg'] = 'Please select Discipline.';
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            if($args['valid_time'] == ''){
                $r['msg'] = 'Please select Latest Date to Reply.';
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            if(array_key_exists('submission',$args)){
                if($args['submission'] == ''){
                    $r['msg'] = 'Please select Submission.';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
            }
            if(array_key_exists('submission_for',$item)){
                if($item['submission_for'] == ''){
                    $r['msg'] = 'Please select Submission for.';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
            }
            if(array_key_exists('action_req',$item)){
                if($item['action_req'] == ''){
                    $r['msg'] = 'Please select Actions Required.';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
            }
            $rvo = '';
            if(array_key_exists('rvo',$item)){
                if($item['rvo'] == ''){
                    $r['msg'] = 'Please select Rvo.';
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $rvo = $item['rvo'];
            }
        }
        
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $id = date('Ymd').rand(01,99).date('His');
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $check_no_type =self::typeList();
        if(count($user)>0){
            $args['add_user'] = $user[0]['user_id'];
        }else{
            $args['add_user'] = $operator_id;
        }
        $status = $args['status'];
        $record_time = date("Y-m-d H:i:s");
        $args['valid_time'] = Utils::DateToCn($args['valid_time']);
        if($args['to'] != 'null'){
            $to_user = explode(',',$args['to']);
            //归类到group下面
            $group_list = array();
            foreach($to_user as $x => $to_user_id){
                $group_id = RfGroupUser::findGroup($to_user_id);
                if($group_id != 0){
                    $group_list[$group_id][] = $to_user_id;
                }
            }
            if(count($group_list)>1){
                $r['msg'] = 'You can only select personnel from one group.';
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
        }else{
            $to_user = '';
            $r['msg'] = 'Please select to user';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if($args['cc'] != 'null'){
            $cc_user = explode(',',$args['cc']);
        }else{
            $cc_user = array();
        }
        $step = 1;
        $link_check_id = '';

        if($args['type_id'] == '1'){
            $item['spec_ref'] = '';
        }

        if($args['type_id'] == '1'){
            $args['deal_type'] = '9';
        }

//        $index =  str_pad((String)$record_id, 5, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        $no_type = $check_no_type[$args['discipline']];
        $no_co = $args['no_co'];
        $no_site = $args['no_site'];
        $no_discipline = $args['no_discipline'];
        $no = $args['no'];
        if($args['type_id'] == '1'){
//                $args['check_no'] = 'CMS-RFI-'.$date.'-'.$no_type.'-'.$index;
            $args['check_no'] = $no_co.'-'.$no_site.'-'.$no_discipline.'-'.'RFI'.'-'.$no;
        }else{
//                $args['check_no'] = 'CMS-RFA-'.$date.'-'.$no_type.'-'.$index;
            $args['check_no'] = $no_co.'-'.$no_site.'-'.$no_discipline.'-'.'RFA'.'-'.$no;
        }
        $exist_data = RfList::model()->count('check_no=:check_no and project_id=:project_id and status <>:status', array('check_no' => $args['check_no'],'project_id' => $args['program_id'],'status' => '-1'));
        if ($exist_data != 0) {
            $r['msg'] = 'The Ref No. has already exists';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        foreach($group_list as $group_id => $group_user){
            list($s1, $s2) = explode(' ', microtime());
            $check_id = (float)sprintf('%.0f',(floatval($s1) + floatval($s2)) * 1000);
            $txt = '['.$record_time.']'.'  '.'RF Send'.' check_id: '.$check_id;
            self::write_log($txt);
            if($args['send_type'] == '1'){
                if(!$link_check_id){
                    //关联类型的生成相同的一个批次号
//                    $link_check_id = time().rand(100, 999);
                    $link_check_id = '';
                }
            }
            $record_id = self::queryIndex();
            $args['record_id'] = $record_id;

            //添加事务
            $trans = Yii::app()->db->beginTransaction();
            try{
                $sql = "insert into rf_record (check_id,form_id,type,check_no,record_id,subject,rvo,discipline,submission,current_step,project_id,project_name,contractor_id,contractor_name,status,apply_user_id,valid_time,apply_time,link_check_id,group_id) values (:check_id,:form_id,:type,:check_no,:record_id,:subject,:rvo,:discipline,:submission,:current_step,:project_id,:project_name,:contractor_id,:contractor_name,:status,:apply_user_id,:valid_time,:apply_time,:link_check_id,:group_id)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":form_id", $args['template_type'], PDO::PARAM_STR);
                $command->bindParam(":type", $args['type_id'], PDO::PARAM_STR);
                $command->bindParam(":check_no", $args['check_no'], PDO::PARAM_STR);
                $command->bindParam(":record_id", $record_id, PDO::PARAM_STR);
                $command->bindParam(":subject", $args['subject'], PDO::PARAM_STR);
                $command->bindParam(":rvo", $rvo, PDO::PARAM_STR);
                $command->bindParam(":discipline", $args['discipline'], PDO::PARAM_STR);
                $command->bindParam(":submission", $args['submission'], PDO::PARAM_STR);
                $command->bindParam(":current_step", $step, PDO::PARAM_INT);
                $command->bindParam(":project_id", $args['program_id'], PDO::PARAM_INT);
                $command->bindParam(":project_name", $program_name, PDO::PARAM_INT);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                $command->bindParam(":contractor_name", $contractor_name, PDO::PARAM_INT);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":apply_user_id", $args['add_user'], PDO::PARAM_STR);
                $command->bindParam(":valid_time", $args['valid_time'], PDO::PARAM_STR);
                $command->bindParam(":apply_time", $record_time, PDO::PARAM_STR);
                $command->bindParam(":link_check_id", $args['link_check_id'], PDO::PARAM_STR);
                $command->bindParam(":group_id", $group_id, PDO::PARAM_STR);
                $rs = $command->execute();
                if ($rs) {
                    $args['step'] = 1;
                    $args['remarks'] = '';
                    $args['check_id'] = $check_id;
                    //转发如果选择隐藏记录，就执行这一步
                    if($args['link_check_id']){
                        if(array_key_exists('forward_status',$args)){
                            $forward_check = explode(',',$args['link_check_id']);
                            foreach($forward_check as $forward_index => $forward_id){
                                $forward_model = RfList::model()->findByPk($forward_id);
                                $forward_model->forward_status = $args['forward_status'];
                                $forward_model->save();
                            }
                        }
                    }
                    //Item
                    $r = RfRecordItem::insertItem($args,$item);
                    $txt = '['.$record_time.']'.'  '.'RF Item'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }

                    if($args['deal_type'] == '0'){
                        $args['button_type'] = self::BUTTON_DRAFT;
                    }else{
                        $args['button_type'] = self::BUTTON_SUBMIT;
                    }
                    if($args['link_check_id']){
                        $args['button_type'] = self::BUTTON_FORWARD;
                    }
                    //Detail
                    $r = RfDetail::insertList($args);
                    $txt = '['.$record_time.']'.'  '.'RF Detail'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    //User
                    $r = RfUser::insertList($args,$group_user);
                    $txt = '['.$record_time.']'.'  '.'RF User'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    if($status == '-1'){
                        if(count($args['attachment'])>0){
                            $r = RfRecordAttachment::insertList($args);
                            $txt = '['.$record_time.']'.'  '.'RF Attach'.': '.json_encode($r);
                            self::write_log($txt);
                        }
                    }
                    if($status == '0'){
                        if(count($args['attachment'])>0){
                            $r = RfRecordAttachment::movePic($args);
                            $txt = '['.$record_time.']'.'  '.'RF Attach'.': '.json_encode($r);
                            self::write_log($txt);
                        }
                    }
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }

                    $trans->commit();//提交事务会真正的执行数据库操作

                    if($status == '0'){
                        //后台执行 非阻塞 异步
                        exec('php /opt/www-nginx/web/test/bimax/protected/yiic mail send --param1='.$check_id.'  >/dev/null  &');
                    }

                    $r['msg'] = Yii::t('common', 'success_insert');
                    $r['status'] = 1;
                    $r['refresh'] = true;

                }else{
                    $trans->rollBack();
                    $r['msg'] = Yii::t('common', 'error_insert');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                }
            }
            catch(Exception $e){
                $txt = '['.$record_time.']'.'  '.'RF Exception'.': '.$e->getmessage();
                self::write_log($txt);
                $trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        }

        return $r;
    }
    //提交草稿
    public static function SubmitDraft($args,$item){
        $rs = self::Submit($args,$item);
        if($rs['status'] == '1'){
            //添加事务
            $trans = Yii::app()->db->beginTransaction();
            $record_time = date('Y-m-d H:i:s');
            try{
                $sql = "DELETE FROM rf_record WHERE check_id =:check_id";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->execute();

                $sql = "DELETE FROM rf_record_attach WHERE check_id =:check_id";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->execute();
                $sql = "DELETE FROM rf_record_detail WHERE check_id =:check_id";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->execute();
                $sql = "DELETE FROM rf_record_user WHERE check_id =:check_id";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->execute();
                $sql = "DELETE FROM rf_record_item WHERE check_id =:check_id";//var_dump($sql);
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->execute();
                $trans->commit();//提交事务会真正的执行数据库操作
            }catch(Exception $e){
                $txt = '['.$record_time.']'.'  '.'RF SubmitDraft Exception'.': '.$e->getmessage();
                self::write_log($txt);
                $trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        }

        return $rs;
    }
    //回复
    public static function replyList($args){
        $json_args = json_encode($args);
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'RF Reply'.' args: '.$json_args;
        self::write_log($txt);
        if($args['to']){
            $to_user = explode(',',$args['to']);
        }else{
            $to_user = '';
            $r['msg'] = 'Please select to user';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if($args['cc'] != 'null'){
            $cc_user = explode(',',$args['cc']);
        }else{
            $cc_user = array();
        }
        if($args['type_id'] == '1'){
            $args['deal_type'] = '10';
        }
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->current_step;
        $args['step'] = $step+1;
        $trans = Yii::app()->db->beginTransaction();
        try{
            $sql = "update rf_record set current_step=:current_step ";
            if(array_key_exists('valid_time',$args)){
                $sql.= " ,valid_time=:valid_time ";
            }
            if(array_key_exists('rvo',$args)){
                $sql.= " ,rvo=:rvo ";
            }
            $sql.= "where check_id = :check_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":current_step", $args['step'], PDO::PARAM_STR);
            if(array_key_exists('valid_time',$args)){
                $params['valid_time'] = Utils::DateToCn($args['valid_time']);
                $args['params'] = json_encode($params);
                $args['valid_time'] = Utils::DateToCn($args['valid_time']);
                $command->bindParam(":valid_time", $args['valid_time'], PDO::PARAM_STR);
            }
            if(array_key_exists('rvo',$args)){
                $params['rvo'] = $args['rvo'];
                $args['params'] = json_encode($params);
                $command->bindParam(":rvo", $args['rvo'], PDO::PARAM_STR);
            }
            $rs = $command->execute();
            if ($rs) {
                $user_phone = Yii::app()->user->id;
                $user = Staff::userByPhone($user_phone);
                if(count($user)>0){
                    $user_model = Staff::model()->findByPk($user[0]['user_id']);
                    $args['add_user'] = $user_model->user_id;
                }else{
                    $args['add_user'] = Yii::app()->user->id;
                }
                $args['button_type'] = self::BUTTON_REPLY;
                //Detail
                $r = RfDetail::insertList($args);
                $txt = '['.$record_time.']'.'  '.'RF Detail'.': '.json_encode($r);
                self::write_log($txt);
                if($r['status'] == '-1'){
                    $trans->rollBack();
                    $r['msg'] = Yii::t('common', 'error_insert');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }//User
                $r = RfUser::insertList($args,$to_user);
                $txt = '['.$record_time.']'.'  '.'RF User'.': '.json_encode($r);
                self::write_log($txt);
                if($r['status'] == '-1'){
                    $trans->rollBack();
                    $r['msg'] = Yii::t('common', 'error_insert');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                //Attach
                if($args['attachment']){
                    $r = RfRecordAttachment::movePic($args);
                    $txt = '['.$record_time.']'.'  '.'RF Attach'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                }

                $trans->commit();//提交事务会真正的执行数据库操作
                $check_id = $args['check_id'];
                exec('php /opt/www-nginx/web/test/bimax/protected/yiic mail send --param1='.$check_id.'  >/dev/null  &');

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;

            }else{
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }
        catch(Exception $e){
            $txt = '['.$record_time.']'.'  '.'RF Exception'.': '.$e->getmessage();
            self::write_log($txt);
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }

        return $r;
    }
    //评论
    public static function commentList($args){
        $json_args = json_encode($args);
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'RF Comment'.' args: '.$json_args;
        self::write_log($txt);

        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->current_step;
        $args['step'] = $step+1;
        $trans = Yii::app()->db->beginTransaction();
        try{
            $sql = "update rf_record set current_step=:current_step ";
            if(array_key_exists('valid_time',$args)){
                $sql.= " ,valid_time=:valid_time ";
            }
            $sql.= "where check_id = :check_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":current_step", $args['step'], PDO::PARAM_STR);
            $rs = $command->execute();
            if ($rs) {
                $user_phone = Yii::app()->user->id;
                $user = Staff::userByPhone($user_phone);
                if(count($user)>0){
                    $user_model = Staff::model()->findByPk($user[0]['user_id']);
                    $args['add_user'] = $user_model->user_id;
                }else{
                    $args['add_user'] = Yii::app()->user->id;
                }
                $args['button_type'] = self::BUTTON_COMMENT;
                $args['deal_type'] = '13';
                //Detail
                $r = RfDetail::insertList($args);
                $txt = '['.$record_time.']'.'  '.'RF Detail'.': '.json_encode($r);
                self::write_log($txt);
                if($r['status'] == '-1'){
                    $trans->rollBack();
                    $r['msg'] = Yii::t('common', 'error_insert');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                //Attach
                if($args['attachment']){
                    $r = RfRecordAttachment::movePic($args);
                    $txt = '['.$record_time.']'.'  '.'RF Attach'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                }

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;

//                $operator_id = Yii::app()->user->id;
//                $user = Staff::userByPhone($operator_id);
//                $to_list = RfUser::userAllList($args['check_id'],'1');
//                $cc_list = RfUser::userAllList($args['check_id'],'2');
//                $apply_user_id = $rf_model->apply_user_id;
//                foreach ($to_list as $x => $y){
//                    $to_user[] = $y['user_id'];
//                }
////                $to_user = $apply_user_id;
//                array_push($to_user,$apply_user_id);
//                $to_user = array_unique($to_user);
//                foreach ($cc_list as $i => $j){
//                    if($user[0]['user_id'] != $j['user_id']){
//                        $cc_user[] = $j['user_id'];
//                    }
//                }
//                if(is_array($cc_user)){
//                    $cc_user = array_unique($cc_user);
//                }

                $trans->commit();//提交事务会真正的执行数据库操作
                $check_id = $args['check_id'];
                $add_user = $user[0]['user_id'];
                exec('php /opt/www-nginx/web/test/bimax/protected/yiic mail comment --param1='.$check_id.' --param2='.$add_user.'  >/dev/null  &');

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;

            }else{
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }
        catch(Exception $e){
            $txt = '['.$record_time.']'.'  '.'RF Exception'.': '.$e->getmessage();
            self::write_log($txt);
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }

        return $r;
    }
    //转发
    public static function forwardList($args){
        $json_args = json_encode($args);
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'RF Forward'.' args: '.$json_args;
        self::write_log($txt);
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $id = date('Ymd').rand(01,99).date('His');
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $check_no_type =self::typeList();
        if(count($user)>0){
            $args['add_user'] = $user[0]['user_id'];
        }else{
            $args['add_user'] = $operator_id;
        }
        $status = '-1';
        $record_time = date("Y-m-d H:i:s");
        $valid_time = Utils::DateToCn($args['valid_time']);
        if($args['to'] != 'null'){
            $to_user = explode(',',$args['to']);
            //归类到group下面
            $group_list = array();
            foreach($to_user as $x => $to_user_id){
                $group_id = RfGroupUser::findGroup($to_user_id);
                if($group_id != 0){
                    $group_list[$group_id][] = $to_user_id;
                }
            }
        }else{
            $to_user = '';
            $r['msg'] = 'Please select to user';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if($args['cc']){
            $cc_user = explode(',',$args['cc']);
        }else{
            $cc_user = '';
        }
        $step = 1;
        $link_check_id = '';

        if($args['type_id'] == '1'){
            $args['spec_ref'] = '';
        }

        if($args['type_id'] == '1'){
            $args['deal_type'] = '2';
        }

        //        $index =  str_pad((String)$record_id, 5, '0', STR_PAD_LEFT);
        $date = date("Ymd");
        $no_type = $check_no_type[$args['discipline']];
        $no_co = $args['no_co'];
        $no_site = $args['no_site'];
        $no_discipline = $args['no_discipline'];
        $no = $args['no'];
        if($args['type_id'] == '1'){
//                $args['check_no'] = 'CMS-RFI-'.$date.'-'.$no_type.'-'.$index;
            $args['check_no'] = $no_co.'-'.$no_site.'-'.$no_discipline.'-'.'RFI'.'-'.$no;
        }else{
//                $args['check_no'] = 'CMS-RFA-'.$date.'-'.$no_type.'-'.$index;
            $args['check_no'] = $no_co.'-'.$no_site.'-'.$no_discipline.'-'.'RFA'.'-'.$no;
        }
        $exist_data = RfList::model()->count('check_no=:check_no and project_id=:project_id and status <>:status', array('check_no' => $args['check_no'],'project_id' => $args['program_id'],'status' => '-1'));
        if ($exist_data != 0) {
            $r['msg'] = 'The Ref No. has already exists';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        foreach($group_list as $group_id => $group_user){
            list($s1, $s2) = explode(' ', microtime());
            $check_id = (float)sprintf('%.0f',(floatval($s1) + floatval($s2)) * 1000);
            if($args['send_type'] == '1'){
                if(!$link_check_id){
                    //关联类型的生成相同的一个批次号
//                    $link_check_id = time().rand(100, 999);
                    $link_check_id = $args['link_check_id'];
                }
            }
            $record_id = self::queryIndex();
            $args['record_id'] = $record_id;
            $index =  str_pad((String)$record_id, 5, '0', STR_PAD_LEFT);
            $date = date("Ymd");
            if($args['type_id'] == '1'){
                $args['check_no'] = 'CMS-RFI-'.$date.'-'.$index;
            }else{
                $args['check_no'] = 'CMS-RFA-'.$date.'-'.$index;
            }
            $trans = Yii::app()->db->beginTransaction();
            try {
                $sql = "insert into rf_record (check_id,form_id,type,check_no,record_id,subject,discipline,submission,current_step,project_id,project_name,contractor_id,contractor_name,status,apply_user_id,valid_time,apply_time,link_check_id,group_id) values (:check_id,:form_id,:type,:check_no,:record_id,:subject,:discipline,:submission,:current_step,:project_id,:project_name,:contractor_id,:contractor_name,:status,:apply_user_id,:valid_time,:apply_time,:link_check_id,:group_id)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":type", $args['type_id'], PDO::PARAM_STR);
                $command->bindParam(":check_no", $args['check_no'], PDO::PARAM_STR);
                $command->bindParam(":record_id", $record_id, PDO::PARAM_STR);
                $command->bindParam(":subject", $args['subject'], PDO::PARAM_STR);
                $command->bindParam(":current_step", $step, PDO::PARAM_INT);
                $command->bindParam(":project_id", $args['program_id'], PDO::PARAM_INT);
                $command->bindParam(":project_name", $program_name, PDO::PARAM_INT);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                $command->bindParam(":contractor_name", $contractor_name, PDO::PARAM_INT);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":apply_user_id", $args['add_user'], PDO::PARAM_STR);
                $command->bindParam(":apply_time", $record_time, PDO::PARAM_STR);
                $command->bindParam(":link_check_id", $link_check_id, PDO::PARAM_STR);
                $command->bindParam(":group_id", $group_id, PDO::PARAM_STR);
                $rs = $command->execute();
                if ($rs) {
                    $args['step'] = 1;
                    $args['remarks'] = '';
                    $args['check_id'] = $check_id;
                    //Item
                    $r = RfRecordItem::insertItem($args);
                    $txt = '['.$record_time.']'.'  '.'RF Item'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    $args['button_type'] = self::BUTTON_FORWARD;
                    //Detail
                    $r = RfDetail::insertList($args);
                    $txt = '['.$record_time.']'.'  '.'RF Detail'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    //User
                    $r = RfUser::insertList($args,$group_user);
                    $txt = '['.$record_time.']'.'  '.'RF User'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    //Attach
                    if(count($args['attachment'])>0){
                        $r = RfRecordAttachment::insertList($args);
                        $txt = '['.$record_time.']'.'  '.'RF Attach'.': '.json_encode($r);
                        self::write_log($txt);
                        if($r['status'] == '-1'){
                            $trans->rollBack();
                            $r['msg'] = Yii::t('common', 'error_insert');
                            $r['status'] = -1;
                            $r['refresh'] = false;
                            return $r;
                        }
                    }
                    $trans->commit();//提交事务会真正的执行数据库操作
                    exec('php /opt/www-nginx/web/test/bimax/protected/yiic mail send --param1='.$check_id.'  >/dev/null  &');

                    $r['msg'] = Yii::t('common', 'success_insert');
                    $r['status'] = 1;
                    $r['refresh'] = true;

                }else{
                    $trans->rollBack();
                    $r['msg'] = Yii::t('common', 'error_insert');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                }
            }
            catch(Exception $e){
                $txt = '['.$record_time.']'.'  '.'RF Exception'.': '.$e->getmessage();
                self::write_log($txt);
                $trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        }

        return $r;
    }

    //关闭
    public static function closeList($args){
        $json_args = json_encode($args);
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'RF Close'.' args: '.$json_args;
        self::write_log($txt);
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $step = $rf_model->current_step;
        $args['step'] = $step+1;
        $status = RfList::STATUS_CLOSE;
        $trans = Yii::app()->db->beginTransaction();
        try{
            $sql = "update rf_record set current_step=:current_step,status=:status where check_id = :check_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
            $command->bindParam(":current_step", $args['step'], PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $rs = $command->execute();
            if ($rs) {
                $user_phone = Yii::app()->user->id;
                $user = Staff::userByPhone($user_phone);
                if(count($user)>0){
                    $user_model = Staff::model()->findByPk($user[0]['user_id']);
                    $args['add_user'] = $user_model->user_id;
                }else{
                    $args['add_user'] = Yii::app()->user->id;
                }
                $args['deal_type'] = RfDetail::STATUS_CLOSE;
                $args['message'] = '';
                $args['button_type'] = '2';
                //Detail
                $r = RfDetail::insertList($args);
                $txt = '['.$record_time.']'.'  '.'RF Detail'.': '.json_encode($r);
                self::write_log($txt);
                if($r['status'] == '-1'){
                    $trans->rollBack();
                    $r['msg'] = Yii::t('common', 'error_insert');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $r['msg'] = 'Close successfully!';
                $r['status'] = 1;
                $r['refresh'] = true;
                $trans->commit();//提交事务会真正的执行数据库操作
            }else{
                $trans->rollBack();
                $r['msg'] = 'Close failed!';
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }
        catch(Exception $e){
            $txt = '['.$record_time.']'.'  '.'RF Exception'.': '.$e->getmessage();
            self::write_log($txt);
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }

        return $r;
    }

    //查询数据表索引
    public static function queryIndex(){
        $sql = "select max(record_id) from rf_record ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if($row['max(record_id)'] != 'NULL'){
                    $record_id = $row['max(record_id)']+1;
                }else{
                    $record_id = 1;
                }
            }
        }

        return $record_id;
    }
    //结束
    public static function endList($args){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $status = self::STATUS_FINISH;
        $sql = "UPDATE bac_rfi_list SET status = '".$status."' WHERE check_id = '".$args['check_id']."' and program_id ='".$args['program_id']."'  ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->execute();
        if ($rows) {
            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_update');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($check_id) {
        $sql = "select * from rf_record
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 查询人员操作的权限
     *
     */
    public static function permissionsInfo($check_id,$operator_id) {
        $rf_model = RfList::model()->findByPk($check_id);
        $confirm_user = $rf_model->confirm_user;
        $status = $rf_model->status;
        $step = $rf_model->step;
        if($status == '5'){
            $step = $step -1;
        }
        $type = $rf_model->type;
        if(is_numeric($operator_id)){
            $info = Staff::userByPhone($operator_id);
            $user_id = $info[0]['user_id'];
        }else{
            $user_id = $operator_id;
        }
        if($type == '1'){
            $sql = "select * from rf_record_user
                 where check_id=:check_id and user_id=:user_id and type = '1' order by step desc";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
            $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $rows = $command->queryAll();
            foreach($rows as $i =>$j){
                $tag = $j['tag'];
            }
            if (count($rows) > 0) {
                $r['rf'] = $type;
                $r['tag'] = $tag;
                $r['type'] = '1';
            }else{
                $r['rf'] = '0';
                $r['tag'] = '0';
                $r['type'] = '0';
            }
        }else{
            if($confirm_user == $user_id){
                $sql = "select * from rf_record_user
                 where check_id=:check_id  and user_id=:user_id and type = '3' order by step desc";
            }else{
                $sql = "select * from rf_record_user
                 where check_id=:check_id  and user_id=:user_id and type = '1' order by step desc";
            }
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
            $command->bindParam(":user_id", $user_id, PDO::PARAM_STR);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                $r['rf'] = $type;
                $r['tag'] = $rows[0]['tag'];
                $r['type'] = $rows[0]['type'];
            }else{
                $r['rf'] = '0';
                $r['tag'] = '0';
                $r['type'] = '0';
            }
        }
        return $r;
    }

    //查询公司下所有项目的记录数
    public static function RecordCntList(){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = "select count(a.check_id) as cnt,a.program_id,b.program_name from rf_record a,bac_program b where a.contractor_id = :contractor_id and a.program_id = b.program_id and a.type = '2' GROUP BY program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$list['program_id']]['cnt'] = $list['cnt'];
                $r[$list['program_id']]['program_name'] = $list['program_name'];
            }
        }
        return $r;
//        var_dump($r);
//        exit;
    }

    //生成压缩包
    public static function createZip($check_id,$pdf){
        $apply = RfList::model()->findByPk($check_id);
        $check_no = $apply->check_no;
        $filename = "/opt/www-nginx/web/filebase/tmp/".$check_no.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            $attach_list = RfRecordAttachment::dealList($check_id);
            foreach($attach_list as $i=>$j){
                $path = '/opt/www-nginx/web'.$j['doc_path'];
                if (file_exists($path)) {
                    $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                }
            }
            $zip->addFile($pdf, basename($pdf));
            $zip->close();
        }

        return $filename;
    }

    //下载PDF
    public static function downloadPDF($params,$app_id){
        $form_id = $params['form_id'];
        $id = $params['id'];
        $rf_model = RfList::model()->findByPk($id);
        $type = $rf_model->type;
        if($form_id == 'RF00003'){
            $filepath = self::downloaddwgPDF($params,$app_id);//dwg
        }else if($form_id == 'RF00002'){
            $filepath = self::downloadmePDF($params,$app_id);//me
        }else{
            if($type == '1'){
                $filepath = self::downloadrfiPDF($params,$app_id);//默认
            }else if($type == '2'){
                $filepath = self::downloaddefaultPDF($params,$app_id);//默认
            }
        }
        $zip_file = self::createZip($params['id'],$filepath);
        return $zip_file;
    }

    public static function updatePath($check_id,$save_path) {
        $save_path = substr($save_path,18);
        $model = RfList::model()->findByPk($check_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }

    //下载默认PDF
    public static function downloaddefaultPDF($params,$app_id){

        $id = $params['id'];
        $rf_model = RfList::model()->findByPk($id);
        $check_no = $rf_model->check_no;
        $program_id = $rf_model->project_id;
        $contractor_id = $rf_model->contractor_id;
        $item_list_1 = RfRecordItem::dealListBystep($id,'1');
        $rfa_type = $item_list_1[0]['discipline'];
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $company_address = $con_model->company_adr;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($rf_model->apply_time,0,4);//年
        $month = substr($rf_model->apply_time,5,2);//月
        $day = substr($rf_model->apply_time,8,2);//日
        $hours = substr($rf_model->apply_time,11,2);//小时
        $minute = substr($rf_model->apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id.'/' . $check_no .'.pdf';
        RfList::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($id);
        $pdf->SetSubject($id);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'RF No.:  ' . $id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

//        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
        $user_step_1 = RfUser::userListByStep($id,'1');
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '1'){
                $to_user = $y['user_id'];
            }
        }
        $add_user_id = $rf_model->apply_user_id;
        $add_user = Staff::model()->findByPk($add_user_id);
        $add_user_name = $add_user->user_name;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $con_name = $con_model->contractor_name;
        $con_adr = $con_model->company_adr;
        $link_tel = $con_model->link_tel;
        $con_logo = $con_model->remark;
        if($con_logo != '') {
            $con_logo = '/opt/www-nginx/web' . $con_model->remark;
        }else{
            $con_logo = 'img/RF.jpg';
        }
        $to_user_model = Staff::model()->findByPk($to_user);
        $to_user_con = $to_user_model->contractor_id;
        $to_user_name = $to_user_model->user_name;
        $record_time = $rf_model->apply_time;
        $date = Utils::DateToEn(substr($record_time,0,10));
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $deal_model_1 = Staff::model()->findByPk($add_user);
        $deal_signature_1 = $deal_model_1->signature_path;
        $signature_html_1= '<img src="'.$deal_signature_1.'" height="30" width="30" />';
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '1'){
                $cc_user[] = $y['user_id'];
            }
        }
        $subject = $rf_model->subject;
        $valid_time = $rf_model->valid_time;
        $valid_time = Utils::DateToEn(substr($valid_time,0,10));
        $spc_ref = $item_list_1[0]['spec_ref'];
        $related_to = $item_list_1[0]['related_to'];
        $location_ref = $item_list_1[0]['location_ref'];
        $others = $item_list_1[0]['others'];
        $type = $rf_model->type;
        $end_step = $rf_model->current_step;
        $user_step_2 = RfUser::userListByStep($id,$end_step);
        foreach($user_step_2 as $x => $y){
            if($y['type'] == '2'){
                $end_cc_user[] = $y['user_id'];
            }
        }
        $end_list = RfDetail::dealListByStep($id,$end_step);
        $deal_model_2 = Staff::model()->findByPk($end_list[0]['user_id']);
        $deal_signature_2 = $deal_model_2->signature_path;
        $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
        $end_date = $end_list[0]['record_time'];
        $end_user_id = $end_list[0]['user_id'];
        $end_user = Staff::model()->findByPk($end_user_id);
        $end_user_name = $end_user->user_name;
        $end_date = Utils::DateToEn(substr($end_date,0,10));

        $info2_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info2_html.="<tr><td height=\"30px\" colspan=\"4\"><h3>PROJECT: &nbsp;{$pro_name}</h3></td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\" width=\"10%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">To:</td><td  nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$con_model->contractor_name}</td><td  nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">From :</td><td nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$add_user_name}</td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray white gray\">Attn:</td><td  nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray white gray\">{$to_user_name}</td><td  nowrap=\"nowrap\" width=\"10%\" style=\"border-width: 1px;border-color:gray gray white gray\">Date :</td><td nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray white gray\">{$date}</td></tr>";
        $info2_html.="</table>";

        $info_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info_html.="<tr><td colspan=\"4\">Copy to: </td></tr>";
        $cc_cnt_1 = 0;
        $cc_tag['3'] = 'YES';
        $cc_tag['4'] = 'NO';
        $cc_tag['0'] = 'Y/N';
        foreach($cc_user as $i => $j){
            $user = Staff::model()->findByPk($j);
            $user_name = $user->user_name;
            $cc_cnt_1++;
            if($cc_cnt_1 % 2 == 0){
                $info_html.="<td width='20%'>{$unchecked_img_html}{$user_name}</td> <td width='30%' align='right'></td></tr>";
            }else{
                $info_html.="<tr><td width='20%'>{$unchecked_img_html}{$user_name}</td> <td width='30%' align='right'></td>";
            }
        }
        if($cc_cnt_1 % 2 == 1){
            $info_html.="<td width='20%'></td><td width='30%' align='right'></td></tr>";
        }
        $info_html.="<tr><td height=\"30px\" colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Subject: &nbsp;{$subject}</h3></td><td colspan=\"2\" height=\"30px\"  width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Latest Date to Reply : &nbsp;<br>{$valid_time}</h3></td></tr>";
        $info_html.="<tr><td height=\"30px\" colspan=\"4\">Description: </td></tr>";
        $info_html.="<tr><td  colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Particulars of Information (Related to): </td><td  colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$related_to}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Location, Drawing Ref No: </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$location_ref}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Specification Ref (Clause): </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$spc_ref}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Others (Email): </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$others}</td></tr>";
        if($type == '1'){
            $info_html.="<tr><td height=\"30px\" colspan=\"4\"><h3>Reason (S) for RFI: </h3></td></tr>";
        }
        $info_html.="<tr><td height=\"60px\" colspan=\"4\"></td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='60%'  ></td><td colspan=\"2\" width='40%' align=\"center\">{$signature_html_1}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='60%'  style=\"border-width: 1px;border-color:white white gray gray\"></td><td colspan=\"2\" width='40%' style=\"border-width: 1px;border-color:gray gray gray white\">Coordinator Name / PM Name <br> Name & Signature of Contractor’s Representative</td></tr>";
        $info_html.="<tr><td height=\"30px\" colspan=\"4\" style=\"border-width: 1px;border-color:white gray white gray\">Consultant’s Reply : (Enclosure Y / N) </td></tr>";
//        $info_html.="<tr><td height=\"30px\" colspan=\"4\" style=\"border-width: 1px;border-color:white gray white gray\">{$rfa_type_name} </td></tr>";
//        $info_html.="<tr><td height=\"30px\" colspan=\"4\" style=\"border-width: 1px;border-color:white gray white gray\">{$contractor_name} </td></tr>";
//        $info_html.="<tr><td height=\"30px\" colspan=\"4\" style=\"border-width: 1px;border-color:white gray white gray\">{$company_address} </td></tr>";
        if($end_list[0]['deal_type'] == '3'){
            $info_html.="<tr><td  width=\"33%\" >{$checked_img_html} Approved</td><td  width=\"34%\" >{$unchecked_img_html} Approved with Comments</td><td  width=\"33%\" colspan=\"2\">{$unchecked_img_html}Revise & Resubmit</td></tr>";
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Rejected</td><td  width=\"34%\" >{$unchecked_img_html}For Record Purposes</td><td  width=\"33%\" colspan=\"2\"></td></tr>";
        }else if($end_list[0]['deal_type'] == '4'){
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Approved</td><td  width=\"34%\" >{$checked_img_html} Approved with Comments</td><td  width=\"33%\"  colspan=\"2\">{$unchecked_img_html}Revise & Resubmit</td></tr>";
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Rejected</td><td  width=\"34%\" >{$unchecked_img_html}For Record Purposes</td><td  width=\"33%\"  colspan=\"2\"></td></tr>";
        }else if($end_list[0]['deal_type'] == '7'){
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Approved</td><td  width=\"34%\" >{$unchecked_img_html} Approved with Comments</td><td  width=\"33%\" colspan=\"2\">{$unchecked_img_html}Revise & Resubmit</td></tr>";
            $info_html.="<tr><td  width=\"33%\" >{$checked_img_html} Rejected</td><td  width=\"34%\" >{$unchecked_img_html}For Record Purposes</td><td  width=\"33%\"  colspan=\"2\"></td></tr>";
        }else if($end_list[0]['deal_type'] == '6'){
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Approved</td><td  width=width=\"34%\" >{$unchecked_img_html} Approved with Comments</td><td  width=\"33%\"  colspan=\"2\">{$checked_img_html}Revise & Resubmit</td></tr>";
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Rejected</td><td  width=width=\"34%\" >{$unchecked_img_html}For Record Purposes</td><td  width=\"33%\"  colspan=\"2\"></td></tr>";
        }else if($end_list[0]['deal_type'] == '5'){
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Approved</td><td  width=\"34%\" >{$unchecked_img_html} Approved with Comments</td><td  width=\"33%\" colspan=\"2\">{$unchecked_img_html}Revise & Resubmit</td></tr>";
            $info_html.="<tr><td  width=\"33%\" >{$unchecked_img_html} Rejected</td><td  width=\"34%\" >{$checked_img_html}For Record Purposes</td><td  width=\"33%\" colspan=\"2\"></td></tr>";
        }
        $state_list = RfState::dealList($to_user_con);
        $info_html.="<tr><td height=\"60px\" colspan=\"4\">{$end_list[0]['remark']}</td></tr>";
        foreach($state_list as $i => $k){
            $info_html.="<tr><td  colspan=\"4\">{$k['state']}</td></tr>";
        }
        $info_html.="<tr><td colspan=\"2\" width='60%'  ></td><td colspan=\"2\" width='40%' align=\"center\">{$signature_html_2}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='60%'  style=\"border-width: 1px;border-color:white white gray gray\"></td><td colspan=\"2\" width='40%' style=\"border-width: 1px;border-color:gray gray gray white\"></td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Consultant Rep’s Signature & Date Received <br> {$confirm_user_name} {$confirm_date} </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">Consultant Rep’s Signature & Date Replied <br> {$end_user_name} {$end_date}</td></tr>";
        $cc_cnt_2 = 0;
        if(count($end_cc_user)>0){
            foreach($end_cc_user as $i => $j){
                $user = Staff::model()->findByPk($j);
                $user_name = $user->user_name;
                $cc_cnt_2++;
                if($cc_cnt_2 % 2 == 0){
                    $info_html.="<td width='20%'>{$unchecked_img_html}{$user_name}</td> <td width='30%' align='right'></td></tr>";
                }else{
                    $info_html.="<tr><td width='20%'>{$unchecked_img_html}{$user_name}</td> <td width='30%' align='right'></td>";
                }
            }
        }
        if($cc_cnt_2 % 2 == 1){
            $info_html.="<td width='20%'></td><td width='30%' align='right'></td></tr>";
        }
        $info_html.="</table>";

        if($type == '1'){
            $title = '<h3>Request For</h3> <br> <h3>Information</h3> <br> (XXX)';
        }else{
            $title = '<h3>Shopdrawing</h3>  <h3>For Approval</h3> <br> (XXX)';
        }
        $ref_no = 'Reff No: '.$rf_model->check_no;
        $con_info = '<h4>'.'  '.$con_name . '<br>' .'  '. $con_adr. '<br>' .'  '.$link_tel.'</h4>';
        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $header = "<table><tr ><td width='30%' style=\"border-width: 1px;border-color:gray gray gray gray;height:50px\" align=\"center\">$title</td><td rowspan='2' align=\"left\" width='45%'>$con_info</td><td rowspan='2' align=\"cnter\">$logo_img</td></tr><tr><td style=\"border-width: 1px;border-color:gray gray gray gray;height:20px\">$ref_no</td></tr></table>";
//        $pdf->writeHTML($header, true, true, true, false, '');
        $pdf->writeHTML($header.$info2_html.$info_html, true, true, true, false, '');
        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
    }

    //下载默认PDF
    public static function downloadmePDF($params,$app_id){

        $id = $params['id'];
        $role_list = Role::roleList();
        $rf_model = RfList::model()->findByPk($id);
        $check_no = $rf_model->check_no;
        $program_id = $rf_model->project_id;
        $contractor_id = $rf_model->contractor_id;
        $item_list_1 = RfRecordItem::dealListBystep($id,'1');
        $item_data = json_decode($item_list_1[0]['item_data'],true);
        $rfa_type = $item_list_1[0]['discipline'];
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $company_address = $con_model->company_adr;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($rf_model->apply_time,0,4);//年
        $month = substr($rf_model->apply_time,5,2);//月
        $day = substr($rf_model->apply_time,8,2);//日
        $hours = substr($rf_model->apply_time,11,2);//小时
        $minute = substr($rf_model->apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id.'/' . $check_no .'.pdf';
        RfList::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($id);
        $pdf->SetSubject($id);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'RF No.:  ' . $id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

//        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
        $user_step_1 = RfUser::userListByStep($id,'1');
        $to_user_name = '';
        $to_role_name = '';
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '1'){
                $t = array();
                $to_user_model = Staff::model()->findByPk($y['user_id']);
                $deal_signature_2 = $to_user_model->signature_path;
                $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
                $to_role_id = $to_user_model->role_id;
                $t['user_name'] = $y['user_name'];
                $t['role_name'] = $role_list[$to_role_id];
                $t['signature'] = $signature_html_2;
                $to_contractor_list[$y['contractor_name']][] = $t;
            }
        }
        $add_user_id = $rf_model->apply_user_id;
        $add_user = Staff::model()->findByPk($add_user_id);
        $add_contractor_id =$add_user->contractor_id;
        $add_role_id = $add_user->role_id;
        $add_role_name = $role_list[$add_role_id];
        $add_user_name = $add_user->user_name;
        $con_model = Contractor::model()->findByPk($add_contractor_id);
        $con_name = $con_model->contractor_name;
        $con_adr = $con_model->company_adr;
        $link_tel = $con_model->link_tel;
        $con_logo = $con_model->remark;
        if($con_logo != '') {
            $con_logo = '/opt/www-nginx/web' . $con_model->remark;
        }else{
            $con_logo = 'img/RF.jpg';
        }
        $con_logo = 'img/1661.png';
        $deal_signature_2 = $to_user_model->signature_path;
        $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
        $record_time = $rf_model->apply_time;
        $date = Utils::DateToEn(substr($record_time,0,10));
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $radio_unchecked_img = 'img/radio_unchecked.png';
        $radio_checked_img = 'img/radio_checked.png';
        $right_img = 'img/right_1.png';
        $right_img_html= '<img src="'.$right_img.'" height="10" width="10" /> ';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $checked_radio_html= '<img src="'.$radio_checked_img.'" height="10" width="10" /> ';
        $unchecked_radio_html= '<img src="'.$radio_unchecked_img.'" height="10" width="10" /> ';
        $deal_signature_1 = $add_user->signature_path;
        $signature_html_1= '<img src="'.$deal_signature_1.'" height="30" width="30" />';
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '1'){
                $cc_user[] = $y['user_id'];
            }
        }
        $subject = $rf_model->subject;
        $valid_time = $rf_model->valid_time;
        $valid_time = Utils::DateToEn(substr($valid_time,0,10));
        $end_step = $rf_model->current_step;
        $user_step_2 = RfUser::userListByStep($id,$end_step);
        foreach($user_step_2 as $x => $y){
            if($y['type'] == '2'){
                $end_cc_user[] = $y['user_id'];
            }
        }
        $end_list = RfDetail::dealListByStep($id,$end_step);
        $deal_model_2 = Staff::model()->findByPk($end_list[0]['user_id']);
//        $deal_signature_2 = $deal_model_2->signature_path;
//        $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
        $end_date = $end_list[0]['record_time'];
        $end_user_id = $end_list[0]['user_id'];
        $end_user = Staff::model()->findByPk($end_user_id);
        $end_user_name = $end_user->user_name;
        $end_date = Utils::DateToEn(substr($end_date,0,10));
        $info2_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info2_html.="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\" align=\"center\"><h3>PROJECT</h3></td><td colspan=\"3\" align=\"center\">&nbsp;{$pro_name}</td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\" width=\"10%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">To:</td><td  nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$con_model->contractor_name}</td><td  nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">From :</td><td nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$add_user_name}</td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Attn:</td><td  nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$to_user_name}</td><td  nowrap=\"nowrap\" width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date :</td><td nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$date}</td></tr>";
        $info2_html.="</table>";

        $info_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info_html.="<tr><td colspan=\"4\">Copy to: </td></tr>";
        $cc_cnt_1 = 0;
        $cc_tag['3'] = 'YES';
        $cc_tag['4'] = 'NO';
        $cc_tag['0'] = 'Y/N';
        $cc_user_str = '';
        foreach($cc_user as $i => $j){
            $user = Staff::model()->findByPk($j);
            $user_name = $user->user_name;
            $cc_user_str.=$user_name.';';
        }
        $info_html.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$cc_user_str}</td></tr>";
        $info_html.="<tr><td height=\"30px\" colspan=\"2\" width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Subject: &nbsp;{$subject}</h3></td><td colspan=\"2\" height=\"30px\"  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Latest Date to Reply : &nbsp;<br>{$valid_time}</h3></td></tr>";

        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Type of service</td><td  colspan=\"2\" width=\"80%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['service']}</td></tr>";
        $submission = $rf_model->submission;
        if($submission == '1'){
            $submission_txt = '1st Submission';
        }else if($submission == '2'){
            $submission_txt = '2nd Submission';
        }else if($submission == '3'){
            $submission_txt = '3nd Submission';
        }
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Submission</td><td  colspan=\"2\" width=\"80%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$submission_txt}</td></tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Discipline</td>";
        if($rf_model->discipline == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}M&E</td>";
        }else if($rf_model->discipline == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}M&E</td>";
        }else if($rf_model->discipline == '3'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}M&E</td>";
        }
        $info_html.="</tr>";

        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Rvo</td>";
        if($rf_model->rvo == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Yes</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}No</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else if($rf_model->rvo == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Yes</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}No</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }
        $info_html.="</tr>";

        $trade_list = RfGroup::tradeList();
        $trade_name = $trade_list[$item_data['trade']];
        $info_html.="<tr><td width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Masterial/Equipment Specified</td><td  colspan=\"3\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['mas_spc']}</td></tr>";
        $info_html.="<tr><td width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Location To Be Installed</td><td  colspan=\"3\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['loc_ins']}</td></tr>";
        $info_html.="<tr><td width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Masterial/Equipment Submitted</td><td  colspan=\"3\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['mas_sub']}</td></tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Supplier</td><td  colspan=\"3\" width=\"80%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['supplier']}</td></tr>";
        $info_html.="<tr><td width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\">HDB List of Approved Supplier</td><td  colspan=\"3\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['hdb_sup']}</td></tr>";
        $info_html.="<tr><td width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Manufacturer/Brand</td><td  colspan=\"3\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['brand']}</td></tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Model / Type</td><td  colspan=\"3\" width=\"80%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['model']}</td></tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Country of Origin</td><td  colspan=\"3\" width=\"80%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['origin']}</td></tr>";
        $info_html.="<tr><td width=\"100%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Complaince With Specifications/standard:{$item_data['comp_spec']}</td></tr>";
        $info_html.="<tr><td width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Technical Brochures Submitted</td><td  colspan=\"3\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['tech_sub']}</td></tr>";
        $info_html.="<tr><td width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Test Certificate Submitted</td><td  colspan=\"3\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$item_data['cert_sub']}</td></tr>";
        $info_html.="<tr><td style=\"border-width: 1px;border-color:gray gray white gray\" colspan=\"4\"><u>Message:</u></td></tr>";
        $submit_list = RfDetail::dealListByStep($id,'1');
        $info_html.="<tr><td height=\"40px\" style=\"border-width: 1px;border-color:white gray gray gray\" colspan=\"4\">{$submit_list[0]['remark']}</td></tr>";

        $info_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\">Submitted by: </td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$con_name}</td></tr>";
        $info_html.="<tr><td style=\"border-width: 1px;border-color:white white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white white white\" width=\"20%\">{$add_user_name}</td><td style=\"border-width: 1px;border-color:white gray gray white\" width=\"60%\" rowspan=\"2\">{$signature_html_1}</td></tr>";
        $info_html.="<tr><td style=\"border-width: 1px;border-color:white white gray gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white gray white\" width=\"20%\">{$add_role_name}</td></tr>";

        $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:gray white white gray\"><u>Consultant REP’s Reply:</u></td></tr>";
        $reply_list = RfDetail::dealListByStep($id,'2');
        if($reply_list[0]['deal_type'] == '11'){
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white gray gray\">{$checked_img_html}Approved</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray gray white\">{$unchecked_img_html}Not Approved</td></tr>";
        }else if($reply_list[0]['deal_type'] == '12'){
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white gray gray\">{$unchecked_img_html}Approved</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray gray white\">{$checked_img_html}Not Approved</td></tr>";
        }

        $detail_params = $reply_list[0]['params'];
        if($detail_params != '') {
            $params_arr = json_decode($detail_params, true);
            if (array_key_exists('rvo', $params_arr)) {
                $rvo = $params_arr['rvo'];
            }
        }
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Rvo</td>";
        if($rvo == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Approve</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Reject</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else if($rvo == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Approve</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}Reject</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else{
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Approve</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Reject</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }
        $info_html.="</tr>";

        $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:gray white white gray\"><u>Comments:</u></td></tr>";
        $info_html.="<tr><td height=\"60px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$reply_list[0]['remark']}</td></tr>";

        $tag = 0;
        foreach($to_contractor_list as $contractor_name => $to_list){
            $tag++;
            if($tag == 1){
                $info_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\">Replied by: </td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$contractor_name}</td></tr>";
            }else{
                $info_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$contractor_name}</td></tr>";
            }
            $to_index = 0;
            $to_cnt = count($to_list);
            foreach ($to_list as $to_index => $to_user){
                $to_index++;
                if($to_index == $to_cnt){
                    $info_html.="<tr><td style=\"border-width: 1px;border-color:white white gray gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white gray white\" width=\"40%\">{$to_user['user_name']} - {$to_user['role_name']}</td><td style=\"border-width: 1px;border-color:white gray gray white\" width=\"40%\" >{$to_user['signature']}</td></tr>";
                }else{
                    $info_html.="<tr><td style=\"border-width: 1px;border-color:white white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white white white\" width=\"40%\">{$to_user['user_name']} - {$to_user['role_name']}</td><td style=\"border-width: 1px;border-color:white gray white white\" width=\"40%\" >{$to_user['signature']}</td></tr>";
                }
            }
        }

        $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:gray white white gray\">Copy to:</td></tr>";
        $cc_str= '';
        if(count($end_cc_user)>0){
            foreach($end_cc_user as $i => $j){
                $user = Staff::model()->findByPk($j);
                $user_name = $user->user_name;
                $cc_str.=$user_name.';';
            }
        }
        $info_html.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$cc_str}</td></tr>";
        $info_html.="</table>";

        $title = '<h3>RFA-M&E</h3><br>';

        $ref_no = 'Ref No: '.$rf_model->check_no;
        $con_info = '<h4>'.'  '.$con_name . '<br>' .'  '. $con_adr. '<br>' .'  '.$link_tel.'</h4>';
        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $header = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr ><td rowspan='2' align=\"cnter\">$logo_img</td><td rowspan='2' align=\"left\" width='45%'>$con_info</td><td width='30%' style=\"border-width: 1px;border-color:gray gray gray gray;height:50px\" align=\"center\">$title<br>$ref_no</td></tr></table>";
//        $pdf->writeHTML($header, true, true, true, false, '');
        $pdf->writeHTML($header.$info2_html.$info_html, true, true, true, false, '');
        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
    }

    //RFA-DWG
    public static function downloaddwgPDF($params,$app_id){

        $id = $params['id'];
        $rf_model = RfList::model()->findByPk($id);
        $check_no = $rf_model->check_no;
        $program_id = $rf_model->project_id;
        $contractor_id = $rf_model->contractor_id;
        $item_list_1 = RfRecordItem::dealListBystep($id,'1');
        $item_data = json_decode($item_list_1[0]['item_data'],true);
        $rfa_type = $item_list_1[0]['discipline'];
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $company_address = $con_model->company_adr;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($rf_model->apply_time,0,4);//年
        $month = substr($rf_model->apply_time,5,2);//月
        $day = substr($rf_model->apply_time,8,2);//日
        $hours = substr($rf_model->apply_time,11,2);//小时
        $minute = substr($rf_model->apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id.'/' . $check_no .'.pdf';
        RfList::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($id);
        $pdf->SetSubject($id);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'RF No.:  ' . $id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

//        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
        $user_step_1 = RfUser::userListByStep($id,'1');

        $to_contractor_list = array();
        $role_list = Role::roleList();
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '1'){
                $t = array();
                $to_user_model = Staff::model()->findByPk($y['user_id']);
                $deal_signature_2 = $to_user_model->signature_path;
                $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
                $to_role_id = $to_user_model->role_id;
                $t['user_name'] = $y['user_name'];
                $t['role_name'] = $role_list[$to_role_id];
                $t['signature'] = $signature_html_2;
                $to_contractor_list[$y['contractor_name']][] = $t;
            }
        }
//        var_dump($to_contractor_list);
//        exit;
        $add_user_id = $rf_model->apply_user_id;
        $add_user = Staff::model()->findByPk($add_user_id);
        $add_contractor_id =$add_user->contractor_id;
        $add_role_id = $add_user->role_id;
        $add_role_name = $role_list[$add_role_id];
        $add_user_name = $add_user->user_name;
        $con_model = Contractor::model()->findByPk($add_contractor_id);
        $con_name = $con_model->contractor_name;
        $con_adr = $con_model->company_adr;
        $link_tel = $con_model->link_tel;
        $con_logo = $con_model->remark;
        if($con_logo != '') {
            $con_logo = '/opt/www-nginx/web' . $con_model->remark;
        }else{
            $con_logo = 'img/RF.jpg';
        }
        $con_logo = 'img/1661.png';
        $record_time = $rf_model->apply_time;
        $date = Utils::DateToEn(substr($record_time,0,10));
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $radio_unchecked_img = 'img/radio_unchecked.png';
        $radio_checked_img = 'img/radio_checked.png';
        $right_img = 'img/right_1.png';
        $right_img_html= '<img src="'.$right_img.'" height="10" width="10" /> ';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $checked_radio_html= '<img src="'.$radio_checked_img.'" height="10" width="10" /> ';
        $unchecked_radio_html= '<img src="'.$radio_unchecked_img.'" height="10" width="10" /> ';
        $deal_signature_1 = $add_user->signature_path;
        $signature_html_1= '<img src="'.$deal_signature_1.'" height="30" width="30" />';
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '2'){
                $cc_user[] = $y['user_id'];
            }
        }
        $subject = $rf_model->subject;
        $valid_time = $rf_model->valid_time;
        $valid_time = Utils::DateToEn(substr($valid_time,0,10));
        $spc_ref = $item_list_1[0]['spec_ref'];
        $related_to = $item_list_1[0]['related_to'];
        $location_ref = $item_list_1[0]['location_ref'];
        $others = $item_list_1[0]['others'];
        $type = $rf_model->type;
        $end_step = $rf_model->current_step;
        $user_step_2 = RfUser::userListByStep($id,$end_step);
        foreach($user_step_2 as $x => $y){
            if($y['type'] == '2'){
                $end_cc_user[] = $y['user_id'];
            }
        }
        $end_list = RfDetail::dealListByStep($id,$end_step);
        $deal_model_2 = Staff::model()->findByPk($end_list[0]['user_id']);
//        $deal_signature_2 = $deal_model_2->signature_path;
//        $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
        $end_date = $end_list[0]['record_time'];
        $end_user_id = $end_list[0]['user_id'];
        $end_user = Staff::model()->findByPk($end_user_id);
        $end_user_name = $end_user->user_name;
        $end_date = Utils::DateToEn(substr($end_date,0,10));
        $info2_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info2_html.="<tr><td height=\"30px\" style=\"border-width: 1px;border-color:gray gray gray gray\" align=\"center\"><h3>PROJECT</h3></td><td colspan=\"3\" align=\"center\">&nbsp;{$pro_name}</td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\" width=\"10%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">To:</td><td  nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$con_model->contractor_name}</td><td  nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">From :</td><td nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$add_user_name}</td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Attn:</td><td  nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$to_user_name}</td><td  nowrap=\"nowrap\" width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date :</td><td nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$date}</td></tr>";
        $info2_html.="</table>";

        $info_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info_html.="<tr><td colspan=\"4\">Copy to: </td></tr>";
        $cc_cnt_1 = 0;
        $cc_tag['3'] = 'YES';
        $cc_tag['4'] = 'NO';
        $cc_tag['0'] = 'Y/N';
        $cc_user_str = '';
        if(count($cc_user)>0) {
            foreach ($cc_user as $i => $j) {
                $user = Staff::model()->findByPk($j);
                $user_name = $user->user_name;
                $cc_user_str .= $user_name . ';';
            }
        }
        $info_html.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$cc_user_str}</td></tr>";
        $submission = $rf_model->submission;
        if($submission == '1'){
            $submission_txt = '1st Submission';
        }else if($submission == '2'){
            $submission_txt = '2nd Submission';
        }else if($submission == '3'){
            $submission_txt = '3nd Submission';
        }
        $info_html.="<tr><td height=\"30px\" colspan=\"2\" width=\"70%\" style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Subject: &nbsp;{$subject}</h3></td><td colspan=\"2\" height=\"30px\"  width=\"30%\" style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Latest Date to Reply : &nbsp;<br>{$valid_time}</h3></td></tr>";
        $info_html.="<tr><td   width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Submission</td><td  colspan=\"3\" width=\"80%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$submission_txt}</td></tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Submission for</td>";
        if($item_data['submission_for'] == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\" >{$checked_radio_html}Material / Sample / Data</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Document</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Shop Drawings</td>";
        }else if($item_data['submission_for'] == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Material / Sample / Data</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Document</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Shop Drawings</td>";
        }else if($item_data['submission_for'] == '3'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Material / Sample / Data</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Document</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}Shop Drawings</td>";
        }
        $info_html.="</tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Actions Required</td>";
        if($item_data['action_req'] == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}For Record</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}For Approval</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}For Endorsement</td>";
        }else if($item_data['action_req'] == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}For Record</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}For Approval</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}For Endorsement</td>";
        }else if($item_data['action_req'] == '3'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}For Record</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}For Approval</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}For Endorsement</td>";
        }
        $info_html.="</tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Discipline</td>";
        if($rf_model->discipline == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}M&E</td>";
        }else if($rf_model->discipline == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}M&E</td>";
        }else if($rf_model->discipline == '3'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}M&E</td>";
        }
        $info_html.="</tr>";
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">RVO</td>";
        if($rf_model->rvo == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Yes</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}No</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else if($rf_model->rvo == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Yes</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}No</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }
        $info_html.="</tr>";
        $trade_list = RfGroup::tradeList();
        $trade_name = $trade_list[$item_data['trade']];
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Trade</td><td  colspan=\"3\" width=\"80%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$trade_name}</td></tr>";
        $info_html.="<tr><td style=\"border-width: 1px;border-color:gray gray white gray\" colspan=\"4\"><u>a.Item Submitted</u></td></tr>";
        $a_item = $item_data['a_item'];
        $info_html.="<tr><td height=\"40px\" style=\"border-width: 1px;border-color:white gray gray gray\" colspan=\"4\">{$a_item}</td></tr>";
        $info_html.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\" colspan=\"4\"><u>b.Item Submitted</u></td></tr>";

        $spec_clause = $item_data['spec_clause'];
        if($spec_clause){
            $check = $right_img_html;
            $value = $spec_clause;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Specification Clause(e)</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $contract_draw = $item_data['contract_draw'];
        if($contract_draw){
            $check = $right_img_html;
            $value = $contract_draw;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Contract Drawing No.</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $product_spec = $item_data['product_spec'];
        if($product_spec){
            $check = $right_img_html;
            $value = $product_spec;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Product Specifications</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $meth_state = $item_data['meth_state'];
        if($meth_state){
            $check = $right_img_html;
            $value = $meth_state;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Method Statement</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $pe = $item_data['pe'];
        if($pe){
            $check = $right_img_html;
            $value = $pe;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">PE calculations (endsorsed)</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $color_chart = $item_data['color_chart'];
        if($color_chart){
            $check = $right_img_html;
            $value = $color_chart;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Color chart</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $hdb_letter = $item_data['hdb_letter'];
        if($hdb_letter){
            $check = $right_img_html;
            $value = $hdb_letter;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">HDB Approved Letter</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $test_report = $item_data['test_report'];
        if($test_report){
            $check = $right_img_html;
            $value = $test_report;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Document</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $others = $item_data['others'];
        if($others){
            $check = $right_img_html;
            $value = $others;
        }else{
            $check = '';
            $value = '';
        }
        $info_html.="<tr><td align=\"center\" width=\"5%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$check}</td><td  width=\"45%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Others</td><td  width=\"50%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$value}</td></tr>";

        $info_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\">Submitted by: </td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$con_name}</td></tr>";
        $info_html.="<tr><td style=\"border-width: 1px;border-color:white white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white white white\" width=\"20%\">{$add_user_name}</td><td style=\"border-width: 1px;border-color:white gray gray white\" width=\"60%\" rowspan=\"2\">{$signature_html_1}</td></tr>";
        $info_html.="<tr><td style=\"border-width: 1px;border-color:white white gray gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white gray white\" width=\"20%\">{$add_role_name}</td></tr>";

        $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:gray white white gray\"><u>Consultant REP’s Reply:</u></td></tr>";
        $reply_list = RfDetail::dealListByStep($id,'2');
        if($reply_list[0]['deal_type'] == '3'){
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white white gray\">{$checked_img_html}In-Principal No Objection</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray white white\">{$unchecked_img_html}Accepted with Comments</td></tr>";
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white gray gray\">{$unchecked_img_html}Rejectd</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray gray white\">{$unchecked_img_html}Re-test / Revise & Resubmit</td></tr>";
        }else if($reply_list[0]['deal_type'] == '4'){
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white white gray\">{$unchecked_img_html}In-Principal No Objection</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray white white\">{$checked_img_html}Accepted with Comments</td></tr>";
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white gray gray\">{$unchecked_img_html}Rejectd</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray gray white\">{$unchecked_img_html}Re-test / Revise & Resubmit</td></tr>";
        }else if($reply_list[0]['deal_type'] == '6'){
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white white gray\">{$unchecked_img_html}In-Principal No Objection</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray white white\">{$unchecked_img_html}Accepted with Comments</td></tr>";
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white gray gray\">{$checked_img_html}Rejectd</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray gray white\">{$unchecked_img_html}Re-test / Revise & Resubmit</td></tr>";
        }else if($reply_list[0]['deal_type'] == '7'){
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white white gray\">{$unchecked_img_html}In-Principal No Objection</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray white white\">{$unchecked_img_html}Accepted with Comments</td></tr>";
            $info_html.="<tr><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white white gray gray\">{$unchecked_img_html}Rejectd</td><td height=\"30px\" width=\"50%\" style=\"border-width: 1px;border-color:white gray gray white\">{$checked_img_html}Re-test / Revise & Resubmit</td></tr>";
        }

        $detail_params = $reply_list[0]['params'];
        if($detail_params != '') {
            $params_arr = json_decode($detail_params, true);
            if (array_key_exists('rvo', $params_arr)) {
                $rvo = $params_arr['rvo'];
            }
        }
        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">RVO</td>";
        if($rvo == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Approve</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Reject</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else if($rvo == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Approve</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}Reject</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else{
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Approve</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Reject</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }
        $info_html.="</tr>";

        $info_html.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:gray gray white gray\"><u>Comments:</u></td></tr>";
        $info_html.="<tr><td height=\"60px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$reply_list[0]['remark']}</td></tr>";

        $tag = 0;
        foreach($to_contractor_list as $contractor_name => $to_list){
            $tag++;
            if($tag == 1){
                $info_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\">Replied by: </td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$contractor_name}</td></tr>";
            }else{
                $info_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$contractor_name}</td></tr>";
            }
            $to_index = 0;
            $to_cnt = count($to_list);
            foreach ($to_list as $to_index => $to_user){
                $to_index++;
                if($to_index == $to_cnt){
                    $info_html.="<tr><td style=\"border-width: 1px;border-color:white white gray gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white gray white\" width=\"40%\">{$to_user['user_name']} - {$to_user['role_name']}</td><td style=\"border-width: 1px;border-color:white gray gray white\" width=\"40%\" >{$to_user['signature']}</td></tr>";
                }else{
                    $info_html.="<tr><td style=\"border-width: 1px;border-color:white white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white white white\" width=\"40%\">{$to_user['user_name']} - {$to_user['role_name']}</td><td style=\"border-width: 1px;border-color:white gray white white\" width=\"40%\" >{$to_user['signature']}</td></tr>";
                }
            }
        }

//        $state_list = RfState::dealList($to_user_con);
//        $info_html.="<tr><td height=\"60px\" colspan=\"4\">{$end_list[0]['remark']}</td></tr>";
//        foreach($state_list as $i => $k){
//            $info_html.="<tr><td  colspan=\"4\">{$k['state']}</td></tr>";
//        }
//        $info_html.="<tr><td colspan=\"2\" width='60%'  ></td><td colspan=\"2\" width='40%' align=\"center\">{$signature_html_2}</td></tr>";
//        $info_html.="<tr><td colspan=\"2\" width='60%'  style=\"border-width: 1px;border-color:white white gray gray\"></td><td colspan=\"2\" width='40%' style=\"border-width: 1px;border-color:gray gray gray white\"></td></tr>";
//        $info_html.="<tr><td colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\">Consultant Rep’s Signature & Date Received <br> </td><td colspan=\"2\" width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\">Consultant Rep’s Signature & Date Replied <br> {$end_user_name} {$end_date}</td></tr>";
        $info_html.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:gray gray white gray\">Copy to:</td></tr>";
        $cc_str= '';
        if(count($end_cc_user)>0){
            foreach($end_cc_user as $i => $j){
                $user = Staff::model()->findByPk($j);
                $user_name = $user->user_name;
                $cc_str.=$user_name.';';
            }
        }
        $info_html.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$cc_str}</td></tr>";
        $info_html.="</table>";
//        var_dump($info_html);
        $title = '<h3>RFA-DWG</h3><br>';

        $ref_no = 'Ref No: '.$rf_model->check_no;
        $con_info = '<h4>'.'  '.$con_name . '<br>' .'  '. $con_adr. '<br>' .'  '.$link_tel.'</h4>';
        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $header = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr ><td rowspan='2' align=\"cnter\">$logo_img</td><td rowspan='2' align=\"left\" width='45%'>$con_info</td><td width='30%' style=\"border-width: 1px;border-color:gray gray gray gray;height:50px\" align=\"center\">$title<br>$ref_no</td></tr></table>";
//        $pdf->writeHTML($header, true, true, true, false, '');
        $pdf->writeHTML($header.$info2_html.$info_html, true, true, true, false, '');
        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
    }

    //下载默认PDF(RFI)
    public static function downloadrfiPDF($params,$app_id){

        $id = $params['id'];
        $rf_model = RfList::model()->findByPk($id);
        $check_no = $rf_model->check_no;
        $program_id = $rf_model->project_id;
        $contractor_id = $rf_model->contractor_id;
        $item_list_1 = RfRecordItem::dealListBystep($id,'1');
        $item_data = json_decode($item_list_1[0]['item_data'],true);
        $rfa_type = $item_list_1[0]['discipline'];
        $prepared_by = $item_data['prepared_by'];
        $subcon = $item_data['subcon'];
        $verified_by = $item_data['verified_by'];
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $company_address = $con_model->company_adr;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($rf_model->apply_time,0,4);//年
        $month = substr($rf_model->apply_time,5,2);//月
        $day = substr($rf_model->apply_time,8,2);//日
        $hours = substr($rf_model->apply_time,11,2);//小时
        $minute = substr($rf_model->apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id.'/' . $check_no .'.pdf';
        RfList::updatepath($id,$filepath);

        //$full_dir = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw';
        $full_dir = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
        //Yii::import('application.extensions.tcpdf.TCPDF');
        //$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($id);
        $pdf->SetSubject($id);
        //$pdf->SetKeywords('PDF, LICEN');
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        $main_model = Contractor::model()->findByPk($contractor_id);
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

        $_SESSION['title'] = 'RF No.:  ' . $id;

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // 设置页眉和页脚字体
        $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文

//        $pdf->Header($logo_pic);
        $pdf->setFooterFont(Array('helvetica', '', '10'));
        $pdf->setCellPaddings(1,1,1,1);

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);
        $user_step_1 = RfUser::userListByStep($id,'1');
        $to_user_name = '';
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '1'){
                $to_user_model = Staff::model()->findByPk($y['user_id']);
                $to_user_name.= $to_user_model->user_name.';';
            }
        }
        $add_user_id = $rf_model->apply_user_id;
        $add_user = Staff::model()->findByPk($add_user_id);
        $add_user_name = $add_user->user_name;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $con_name = $con_model->contractor_name;
        $con_adr = $con_model->company_adr;
        $link_tel = $con_model->link_tel;
        $con_logo = $con_model->remark;
        if($con_logo != '') {
            $con_logo = '/opt/www-nginx/web' . $con_model->remark;
        }else{
            $con_logo = 'img/RF.jpg';
        }
        $con_logo = 'img/1661.png';
        $record_time = $rf_model->apply_time;
        $date = Utils::DateToEn(substr($record_time,0,10));
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $radio_unchecked_img = 'img/radio_unchecked.png';
        $radio_checked_img = 'img/radio_checked.png';
        $checked_radio_html= '<img src="'.$radio_checked_img.'" height="10" width="10" /> ';
        $unchecked_radio_html= '<img src="'.$radio_unchecked_img.'" height="10" width="10" /> ';
        $deal_signature_1 = $add_user->signature_path;
        $signature_html_1= '<img src="'.$deal_signature_1.'" height="30" width="30" />';
        foreach($user_step_1 as $x => $y){
            if($y['type'] == '2'){
                $cc_user[] = $y['user_id'];
            }
        }
        $subject = $rf_model->subject;
//        $valid_time = $rf_model->valid_time;
        $valid_time = Utils::DateToEn($item_list_1[0]['valid_time']);
        $spc_ref = $item_list_1[0]['spec_ref'];
        $related_to = $item_list_1[0]['related_to'];
        $location_ref = $item_list_1[0]['location_ref'];
        $others = $item_list_1[0]['others'];
        $type = $rf_model->type;
        $end_step = $rf_model->current_step;
        $user_step_2 = RfUser::userListByStep($id,$end_step);
        foreach($user_step_2 as $x => $y){
            if($y['type'] == '2'){
                $end_cc_user[] = $y['user_id'];
            }
        }
        $end_list = RfDetail::dealListByStep($id,$end_step);
        $deal_model_2 = Staff::model()->findByPk($end_list[0]['user_id']);
        $deal_signature_2 = $deal_model_2->signature_path;
        $signature_html_2= '<img src="'.$deal_signature_2.'" height="30" width="30" />';
        $end_date = $end_list[0]['record_time'];
        $end_user_id = $end_list[0]['user_id'];
        $end_user = Staff::model()->findByPk($end_user_id);
        $end_user_name = $end_user->user_name;
        $end_date = Utils::DateToEn(substr($end_date,0,10));

        $info2_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info2_html.="<tr><td height=\"30px\" colspan=\"4\"><h3>PROJECT: &nbsp;{$pro_name}</h3></td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\" width=\"10%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">To:</td><td  nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$con_model->contractor_name}</td><td  nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">From :</td><td nowrap=\"nowrap\" width=\"40%\"  style=\"border-width: 1px;border-color:gray gray gray gray\">{$add_user_name}</td></tr>";
        $info2_html.="<tr><td nowrap=\"nowrap\"  width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Attn:</td><td  nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$to_user_name}</td><td  nowrap=\"nowrap\" width=\"10%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Date :</td><td nowrap=\"nowrap\" width=\"40%\" style=\"border-width: 1px;border-color:gray gray gray gray\">{$date}</td></tr>";
        $info2_html.="</table>";

        $info_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
        $info_html.="<tr><td colspan=\"4\">Copy to: </td></tr>";
        $cc_cnt_1 = 0;
        $cc_tag['3'] = 'YES';
        $cc_tag['4'] = 'NO';
        $cc_tag['0'] = 'Y/N';
        $cc_user_str = '';
        if(count($cc_user)>0){
            foreach($cc_user as $i => $j){
                $user = Staff::model()->findByPk($j);
                $user_name = $user->user_name;
                $cc_user_str.=$user_name.',';
                $cc_cnt_1++;
            }
        }
        $info_html.= "<tr><td colspan=\"4\">$cc_user_str</td></tr>";
        $info_html.="<tr><td height=\"30px\" colspan=\"2\" width='70%' style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Subject: &nbsp;{$subject}</h3></td><td colspan=\"2\" height=\"30px\"  width='30%' style=\"border-width: 1px;border-color:gray gray gray gray\"><h3>Latest Date to Reply : &nbsp;<br>{$valid_time}</h3></td></tr>";

        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Discipline</td>";
        if($rf_model->discipline == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}M&E</td>";
        }else if($rf_model->discipline == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}M&E</td>";
        }else if($rf_model->discipline == '3'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Architecture</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Structural</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}M&E</td>";
        }
        $info_html.="</tr>";

        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Regarding to</td>";
        if($item_data['regarding_to'] == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\" >{$checked_radio_html}Material / Sample / Data</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Document</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Shop Drawings</td>";
        }else if($item_data['regarding_to'] == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Material / Sample / Data</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Document</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$unchecked_radio_html}Shop Drawings</td>";
        }else if($item_data['regarding_to'] == '3'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Material / Sample / Data</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Document</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray gray gray white\">{$checked_radio_html}Shop Drawings</td>";
        }
        $info_html.="</tr>";


        $info_html.="<tr><td width=\"20%\" style=\"border-width: 1px;border-color:gray gray gray gray\">Rvo</td>";
        if($rf_model->rvo == '1'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}Yes</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}No</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else if($rf_model->rvo == '2'){
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Yes</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$checked_radio_html}No</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }else{
            $info_html.="<td  width=\"30%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}Yes</td><td  width=\"25%\" style=\"border-width: 1px;border-color:gray white gray white\">{$unchecked_radio_html}No</td><td  width=\"25%\" style=\"border-width:1px;border-color:gray gray gray white\"></td>";
        }
        $info_html.="</tr>";

        $info_html.="<tr><td  style=\"border-width: 1px;border-color:gray gray gray gray\" colspan=\"4\">Attached Dwg Ref. No.   {$item_data['ref_no']}</td></tr>";
        $info_html.="<tr><td  style=\"border-width: 1px;border-color:gray gray gray gray\" colspan=\"4\">Specifications Clause  {$item_data['clause']}</td></tr>";
        $info_html.="<tr><td height=\"30px\" colspan=\"4\" style=\"border-width: 1px;border-color:gray gray gray gray\">Description:  <br>{$item_data['description']}</td></tr>";
        $attach_list = RfRecordAttachment::dealListBystep($id,'1');
        $attach_str = '';
        $attach_cnt = '';
        if(count($attach_list)>0){
            $attach_cnt = '('.count($attach_list).')';
            foreach($attach_list as $i=>$j){
                $attach_str.=$j['doc_name'].';';
            }
        }
        $info_html.="<tr><td height=\"30px\" colspan=\"4\">Attachemnt{$attach_cnt}:  <br>{$attach_str}</td></tr>";
        $info_html.="<tr><td  colspan=\"2\" width='50%' style=\"border-width: 1px;border-color:gray gray gray gray\">Prepared By:  {$prepared_by}</td><td  colspan=\"2\" width='50%' style=\"border-width: 1px;border-color:gray gray gray gray\">Subcon:  {$subcon}</td></tr>";
        $info_html.="<tr><td colspan=\"2\" width='50%' style=\"border-width: 1px;border-color:gray gray gray gray\">{$signature_html_1}</td><td colspan=\"2\" width='50%' style=\"border-width: 1px;border-color:gray gray gray gray\">Verified By:  {$verified_by}</td></tr>";
        $info_html.="</table>";

        $title = '<h3>HDB-RFI</h3><br>';
        $ref_no = 'Ref No: '.$rf_model->check_no;
        $con_info = '<h4>'.'  '.$con_name . '<br>' .'  '. $con_adr. '<br>' .'  '.$link_tel.'</h4>';
        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $header = "<table style=\"border-width: 1px;border-color:gray gray gray gray\"><tr ><td rowspan='2' align=\"cnter\">$logo_img</td><td rowspan='2' align=\"left\" width='45%'>$con_info</td><td width='30%' style=\"border-width: 1px;border-color:gray gray gray gray;height:50px\" align=\"center\">$title<br>$ref_no</td></tr></table>";
//        $pdf->writeHTML($header, true, true, true, false, '');
        $reply_title = '<h2>----------------------------------------------Reply History----------------------------------------------</h2><br>';
        $pdf->writeHTML($header.$info2_html.$info_html.$reply_title, true, true, true, false, '');

        $sql = "select * from rf_record_detail
                 where check_id=:check_id order by step asc";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $id, PDO::PARAM_STR);
        $detail_list = $command->queryAll();

        foreach($detail_list as $i => $j){
            $reply_model = Staff::model()->findByPk($j['user_id']);
            $reply_name = $reply_model->user_name;
            $reply_signature = $reply_model->signature_path;
            $reply_role = Role::model()->findByPk($reply_model->role_id);
            $signature_html_reply= '<img src="'.$reply_signature.'" height="30" width="30" />';
            $reply_date = Utils::DateToEn($j['record_time']);
            $contractor_id = $reply_model->contractor_id;
            $con_model = Contractor::model()->findByPk($contractor_id);
            $con_name = $con_model->contractor_name;
            if($j['status'] == '3'){
                $reply_user = RfUser::userListByStep($id,$j['step']);
                $to_user = '';
                $cc_user = '';
                foreach($reply_user as $x => $y){
                    if($y['type'] == '1'){
                        $to_model = Staff::model()->findByPk($y['user_id']);
                        $to_user = $to_model->user_name;
                    }
                    if($y['type'] == '2'){
                        $cc_model = Staff::model()->findByPk($y['user_id']);
                        $cc_user.= $cc_model->user_name.';';
                    }
                }
                $reply_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
                $reply_html.="<tr><td  colspan=\"2\" width='50%' style=\"border-width: 1px;border-color:gray gray gray gray\">From:  {$reply_name}</td><td  colspan=\"2\" width='50%' style=\"border-width: 1px;border-color:gray gray gray gray\">Date:  {$reply_date}</td></tr>";
                $reply_html.="<tr><td  colspan=\"4\"  style=\"border-width: 1px;border-color:gray gray gray gray\">To:  {$to_user}</td></tr>";
                $reply_html.="<tr><td  colspan=\"4\"  style=\"border-width: 1px;border-color:gray gray gray gray\">Cc:  {$cc_user}</td></tr>";

                $detail_params = $j['params'];
                if($detail_params != '') {
                    $params_arr = json_decode($detail_params, true);
                    if (array_key_exists('valid_time', $params_arr)) {
                        $reply_html.="<tr><td  colspan=\"4\"  style=\"border-width: 1px;border-color:gray gray gray gray\">Valid Time:  ".  Utils::DateToEn($params_arr['valid_time'])."</td></tr>";
                    }
                }

                $attach_list = RfRecordAttachment::dealListBystep($id,$j['step']);
                $attach_str = '';
                $attach_cnt = '';
                if(count($attach_list)>0){
                    $attach_cnt = '('.count($attach_list).')';
                    foreach($attach_list as $o=>$p){
                        $attach_str.=$p['doc_name'].';';
                    }
                }
                $reply_html.="<tr><td height=\"30px\" colspan=\"4\">Attachemnt{$attach_cnt}:  <br>{$attach_str}</td></tr>";
                $reply_html.="<tr><td height=\"30px\" colspan=\"4\" style=\"border-width: 1px;border-color:gray gray gray gray\">Description:  <br>{$j['remark']}</td></tr>";
                $reply_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\">Replied by: </td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$con_name}</td></tr>";
                $reply_html.="<tr><td style=\"border-width: 1px;border-color:white white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white white white\" width=\"20%\">{$reply_name}</td><td style=\"border-width: 1px;border-color:white gray gray white\" width=\"60%\" rowspan=\"2\">{$signature_html_reply}</td></tr>";
                $reply_html.="<tr><td style=\"border-width: 1px;border-color:white white gray gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white gray white\" width=\"20%\">{$reply_role->role_name_en}</td></tr>";
                $reply_html.="</table>";
                $pdf->writeHTML($reply_html, true, true, true, false, '');
            }else if($j['status'] == '5'){
                $reply_html = "<table style=\"border-width: 1px;border-color:gray gray gray gray\">";
                $reply_html.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:gray white white gray\"><u>Comments:</u></td></tr>";
                $reply_html.="<tr><td height=\"60px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$j['remark']}</td></tr>";

                $reply_html.="<tr><td style=\"border-width: 1px;border-color:gray white white gray\" width=\"20%\">Replied by: </td><td style=\"border-width: 1px;border-color:gray gray white white\" width=\"80%\">{$con_name}</td></tr>";
                $reply_html.="<tr><td style=\"border-width: 1px;border-color:white white white gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white white white\" width=\"20%\">{$reply_name}</td><td style=\"border-width: 1px;border-color:white gray gray white\" width=\"60%\" rowspan=\"2\">{$signature_html_reply}</td></tr>";
                $reply_html.="<tr><td style=\"border-width: 1px;border-color:white white gray gray\" width=\"20%\"></td><td style=\"border-width: 1px;border-color:white white gray white\" width=\"20%\">{$reply_role->role_name_en}</td></tr>";
                $reply_html.="</table>";
                $pdf->writeHTML($reply_html, true, true, true, false, '');
            }
        }

        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
    }

    //按项目查询（按stutas把rf_record表里的数据分组）
    public static function StatusCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $color_list = self::statusColor();
        if($pro_model->main_conid != $args['contractor_id']){
            //SUBSTRING_INDEX(a.add_operator, '|', 1)
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,project_id,status from rf_record where program_id = '".$root_proid."' and apply_time like '".$month."%' and contractor_id = '".$args['contractor_id']."' GROUP BY status";
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where program_id = '".$args['program_id']."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY status";
        }else{
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY status";
            $sql = "select count(check_id) as cnt,project_id,status from rf_record where apply_time like '".$month."%' and project_id ='".$args['program_id']."'  GROUP BY status";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                if($list['status'] != '-1'){
                    $r[$num]['cnt'] = $list['cnt'];
                    $r[$num]['status'] =RfList::statusText($list['status']);
                }
            }
        }
        $rs['data'] = $r;
        $rs['color'] = $color_list;
        return $rs;
    }

    //按项目查询（按stutas把rf_record表里的数据分组）
    public static function StatusCntList2($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $color_list = self::statusColor();
        if($pro_model->main_conid != $args['contractor_id']){
            //SUBSTRING_INDEX(a.add_operator, '|', 1)
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,project_id,status from rf_record where project_id = '".$root_proid."' and apply_time like '".$month."%' and contractor_id = '".$args['contractor_id']."' GROUP BY status";
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where program_id = '".$args['program_id']."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY status";
        }else{
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY status";
            $sql = "select count(check_id) as cnt,project_id,status from  rf_record where apply_time like '".$month."%' and project_id ='".$args['program_id']."'  GROUP BY status";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            $r[0]['cnt'] = 0;
            $r[0]['status'] ='';
            $r[1]['cnt'] = 0;
            $r[1]['status'] ='';
            foreach($rows as $num => $list){
                if($list['status'] == '-1' || $list['status'] == '0' || $list['status'] == '1'){
                    $r[0]['cnt'] += $list['cnt'];
                    $r[0]['status'] ='Ongoing';
                }else{
                    $r[1]['cnt'] += $list['cnt'];
                    $r[1]['status'] ='Closed';
                }
            }
        }
        $rs['data'] = $r;
        $rs['color'] = $color_list;
        return $rs;
    }

    //按项目查询（按stutas把rf_record表里的数据分组）
    public static function TypeCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        if($pro_model->main_conid != $args['contractor_id']){
            //SUBSTRING_INDEX(a.add_operator, '|', 1)
            $root_proid = $pro_model->root_proid;
            $sql = "select a.status,b.discipline,count(a.check_id) as cnt FROM rf_record a, rf_record_item b where a.apply_time like '".$month."%' AND a.contractor_id = '".$args['contractor_id']."' AND a.project_id = '".$root_proid."' and a.check_id= b.check_id AND a.type = '2' and b.step= '1' group BY b.discipline, a.status";
        }else{
            $sql = "select a.status,b.discipline,count(a.check_id) as cnt FROM rf_record a, rf_record_item b where a.apply_time like '".$month."%' AND a.project_id = '".$args['program_id']."' and a.check_id= b.check_id AND a.type = '2' and b.step= '1' group BY b.discipline, a.status";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $status_list = self::statusText();
        $type_list = self::rfaType();
        $color_list = self::statusColor();
        foreach($status_list as $status_id => $status_name){
            if($status_id != '-1'){
                $r = array();
                $r['id'] = $status_id;
                $r['name'] = $status_name;
                foreach($type_list as $type_id => $type_name){
                    $r['data'][] = 0;
                }
                $s[] = $r;
            }
        }
        if(!empty($rows)){
            foreach($rows as $num => $list){
                foreach($s as $i => &$j){
                    if($j['id'] == $list['status']){
                        $t = 0;
                        foreach($type_list as $type_id => $type_name){
                            if($type_id == $list['discipline']){
                                $j['data'][$t] = (int)$list['cnt'];
                            }
                            $t++;
                        }
                    }
                }
            }
        }
        foreach($type_list as $type_id => $type_name){
            $e[] = $type_name;
        }

        foreach($color_list as $type_id => $color_name){
            $z[] = $color_name;
        }

        $data['x'] = $e;
        $data['y'] = $s;
        $data['color'] = $z;


        return $data;
    }

    //按项目查询（按stutas把rf_record表里的数据分组）
    public static function TypeCntList2($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $type_list = self::rfaType();
        $month = Utils::MonthToCn($args['date']);
        if($pro_model->main_conid != $args['contractor_id']){
            //SUBSTRING_INDEX(a.add_operator, '|', 1)
            $root_proid = $pro_model->root_proid;
            $sql = "select a.status,b.discipline,count(a.check_id) as cnt FROM rf_record a, rf_record_item b where a.apply_time like '".$month."%' AND a.contractor_id = '".$args['contractor_id']."' AND a.project_id = '".$root_proid."' and a.check_id= b.check_id AND a.type = '1' and b.step= '1' group BY b.discipline, a.status";
        }else{
            $sql = "select a.status,b.discipline,count(a.check_id) as cnt FROM rf_record a, rf_record_item b where a.apply_time like '".$month."%' AND a.project_id = '".$args['program_id']."' and a.check_id= b.check_id AND a.type = '1' and b.step= '1' group BY b.discipline, a.status";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $status_list = self::statusText();
        $type_list = self::rfaType();
        $color_list = self::statusColor();

        foreach($status_list as $status_id => $status_name){
            if($status_id != '-1'){
                $r = array();
                $r['id'] = $status_id;
                $r['name'] = $status_name;
                foreach($type_list as $type_id => $type_name){
                    $r['data'][] = 0;
                }
                $s[] = $r;
            }
        }
        if(!empty($rows)){
            foreach($rows as $num => $list){
                foreach($s as $i => &$j){
                    if($j['id'] == $list['status']){
                        $t = 0;
                        foreach($type_list as $type_id => $type_name){
                            if($type_id == $list['discipline']){
                                $j['data'][$t] = (int)$list['cnt'];
                            }
                            $t++;
                        }
                    }
                }
            }
        }
        foreach($type_list as $type_id => $type_name){
            $e[] = $type_name;
        }

        foreach($color_list as $type_id => $color_name){
            $z[] = $color_name;
        }

        $data['x'] = $e;
        $data['y'] = $s;
        $data['color'] = $z;

        return $data;
    }

    //按项目查询（按stutas把rf_record表里的数据分组）
    public static function AllCntList3($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        // $type_list = PtwType::typeByContractor($args['program_id']);

        if($pro_model->main_conid != $args['contractor_id']){
            //SUBSTRING_INDEX(a.add_operator, '|', 1)
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,project_id,status from rf_record where project_id = '".$root_proid."'  GROUP BY status";
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where program_id = '".$args['program_id']."' and record_time like '".$month."%' and apply_contractor_id = '".$args['contractor_id']."'  GROUP BY status";
        }else{
//            $sql = "select count(apply_id) as cnt,program_id,status from ptw_apply_basic where record_time like '".$month."%' and program_id ='".$args['program_id']."'  GROUP BY status";
            $sql = "select count(check_id) as cnt,project_id,status from  rf_record where project_id ='".$args['program_id']."'  GROUP BY status";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['status'] =RfList::statusRfiText($list['status']);
            }
        }else{
            $r = array();
        }
        return $r;
    }

    public static function write_log($data){
        $years = date('Y-m');
        //设置路径目录信息
        $url = '/tmp/'.'idd.log.'.date('Ymd');
        $dir_name=dirname($url);
        //目录不存在就创建
        if(!file_exists($dir_name))
        {
            //iconv防止中文名乱码
            $res = mkdir(iconv("UTF-8", "GBK", $dir_name),0777,true);
        }
        $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建
        fwrite($fp,var_export($data,true)."\r\n");//写入文件
        fclose($fp);//关闭资源通道
    }
}
