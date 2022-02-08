<?php

/**
 * TransmittalRecord
 * @author LiuMinchao
 */
class TransmittalRecord extends CActiveRecord {

    //状态
    const STATUS_ISSUED = '0'; //发起
    const STATUS_RECEIVED = '1'; //接收
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'transmittal_record';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_ISSUED => 'Issued',
            self::STATUS_RECEIVED => 'Received'
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_ISSUED => 'bg-info',
            self::STATUS_RECEIVED => 'bg-success'
        );
        return $key === null ? $rs : $rs[$key];
    }

    public static function purposeList($key = null) {
        $rs = array(
            '1' => 'Working drawings',
            '2' =>  'For Information',
            '3' =>  'Approval drawings',
            '4' =>  'Construction drawings',
            '5' =>  'Others',
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

        $program_id = $args['program_id'];
        $pro_model =Program::model()->findByPk($args['program_id']);
        $program_id = $pro_model->root_proid;
        $sql = "select * from transmittal_record ";
        setcookie('trans_status', $args['status']);
        $status = $args['status'];
        $condition.= " where project_id = '$program_id'";

        if($status != ''){
            $condition.= " and status = '$status'";
        }

        if ($args['con_id'] != '') {
            $con_id = $args['con_id'];
            $condition.= " and contractor_id = '$con_id'";
        }

        if ($args['start_date'] != '') {
            $start_date = Utils::DateToCn($args['start_date']);
            $condition .= " and apply_time >='$start_date'";
        }

        if ($args['end_date'] != '') {
            $end_date = Utils::DateToCn($args['end_date']);
            $condition .= " and apply_time <='$end_date 23:59:59'";
        }

        if ($args['subject'] != '') {
            $subject = $args['subject'];
            $condition.= " and subject like '%$subject%'";
        }

        $order = ' order by apply_time desc';
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
    public static function Submit($args){
        $json_args = json_encode($args);
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'Transmittal Send'.' args: '.$json_args;
        self::write_log($txt);

        if($args['to'] == '' || $args['to'] == 'null'){
            $r['msg'] = 'Please select To.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $root_proid = $pro_model->root_proid;
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if(count($user)>0){
            $args['add_user'] = $user[0]['user_id'];
        }else{
            $args['add_user'] = $operator_id;
        }
        $status = '0';
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

        foreach($group_list as $group_id => $group_user){
            list($s1, $s2) = explode(' ', microtime());
            $check_id = (float)sprintf('%.0f',(floatval($s1) + floatval($s2)) * 1000);
            $txt = '['.$record_time.']'.'  '.'Transmittal Send'.' check_id: '.$check_id;
            self::write_log($txt);
            $record_id = self::queryIndex();
            $args['record_id'] = $record_id;

            //添加事务
            $trans = Yii::app()->db->beginTransaction();
            try{
                $sql = "insert into transmittal_record (check_id,form_id,subject,rvo,current_step,project_nos,project_id,project_name,contractor_id,contractor_name,status,apply_user_id,apply_time,group_id) values (:check_id,:form_id,:subject,:rvo,:current_step,:project_nos,:project_id,:project_name,:contractor_id,:contractor_name,:status,:apply_user_id,:apply_time,:group_id)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
                $command->bindParam(":form_id", $args['form_id'], PDO::PARAM_STR);
                $command->bindParam(":subject", $args['subject'], PDO::PARAM_STR);
                $command->bindParam(":rvo", $args['rvo'], PDO::PARAM_STR);
                $command->bindParam(":current_step", $step, PDO::PARAM_INT);
                $command->bindParam(":project_nos", $args['project_nos'], PDO::PARAM_INT);
                $command->bindParam(":project_id", $root_proid, PDO::PARAM_INT);
                $command->bindParam(":project_name", $program_name, PDO::PARAM_INT);
                $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
                $command->bindParam(":contractor_name", $contractor_name, PDO::PARAM_INT);
                $command->bindParam(":status", $status, PDO::PARAM_STR);
                $command->bindParam(":apply_user_id", $args['add_user'], PDO::PARAM_STR);
                $command->bindParam(":apply_time", $record_time, PDO::PARAM_STR);
                $command->bindParam(":group_id", $group_id, PDO::PARAM_STR);
                $rs = $command->execute();
                if ($rs) {
                    $args['step'] = 1;
                    $args['remarks'] = '';
                    $args['check_id'] = $check_id;
                    $args['deal_type'] = '1';
                    //Detail
                    $r = TransmittalDetail::insertList($args);
                    $txt = '['.$record_time.']'.'  '.'Transmittal Detail'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    //User
                    $r = TransmittalUser::insertList($args,$group_user);
                    $txt = '['.$record_time.']'.'  '.'Transmittal User'.': '.json_encode($r);
                    self::write_log($txt);
                    if($r['status'] == '-1'){
                        $trans->rollBack();
                        $r['msg'] = Yii::t('common', 'error_insert');
                        $r['status'] = -1;
                        $r['refresh'] = false;
                        return $r;
                    }
                    if(count($args['attachment'])>0){
                        $r = TransmittalRecordAttach::movePic($args);
                        $txt = '['.$record_time.']'.'  '.'Transmittal Attach'.': '.json_encode($r);
                        self::write_log($txt);
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
//                        //后台执行 非阻塞 异步
                        exec('php /opt/www-nginx/web/test/bimax/protected/yiic mail transsend --param1='.$check_id.' >/dev/null  &');
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
                $txt = '['.$record_time.']'.'  '.'Transmittal Exception'.': '.$e->getmessage();
                self::write_log($txt);
                $trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        }

        return $r;
    }

    //接收
    public static function receiveList($check_id){
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'Trans Receive'.' check_id: '.$check_id;
        self::write_log($txt);
        $args['check_id'] = $check_id;
        $trans_model = TransmittalRecord::model()->findByPk($check_id);
        $step = $trans_model->current_step;
        $args['step'] = $step+1;
        $trans = Yii::app()->db->beginTransaction();
        try{
            $sql = "update transmittal_record set current_step=:current_step,status = '1' ";
            $sql.= "where check_id = :check_id";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
            $command->bindParam(":current_step", $args['step'], PDO::PARAM_STR);
            $rs = $command->execute();
            $txt = '['.$record_time.']'.'  '.'Trans Detail'.': '.json_encode($rs);
            self::write_log($txt);
            if ($rs) {
                $user_phone = Yii::app()->user->id;
                $user = Staff::userByPhone($user_phone);
                if(count($user)>0){
                    $user_model = Staff::model()->findByPk($user[0]['user_id']);
                    $args['add_user'] = $user_model->user_id;
                }else{
                    $args['add_user'] = Yii::app()->user->id;
                }
                //Detail
                $args['deal_type'] = '2';
                $r = TransmittalDetail::insertList($args);
                $txt = '['.$record_time.']'.'  '.'Trans Detail'.': '.json_encode($r);
                self::write_log($txt);
                if($r['status'] == '-1'){
                    $trans->rollBack();
                    $r['msg'] = Yii::t('common', 'error_insert');
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }

                $trans->commit();//提交事务会真正的执行数据库操作
                $check_id = $args['check_id'];
                //                        //后台执行 非阻塞 异步
                exec('php /opt/www-nginx/web/test/bimax/protected/yiic mail transreceive --param1='.$check_id.' --param2='.$args['add_user'].' >/dev/null  &');
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
        $apply = TransmittalRecord::model()->findByPk($check_id);
        $check_id = $apply->check_id;
        $filename = "/opt/www-nginx/web/filebase/tmp/".$check_id.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            $attach_list = TransmittalRecordAttach::dealList($check_id);
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
        $trans_model = TransmittalRecord::model()->findByPk($id);
        $filepath = self::downloaddefaultPDF($params,$app_id);//默认
        $zip_file = self::createZip($params['id'],$filepath);
        return $zip_file;
    }

    public static function updatePath($check_id,$save_path) {
        $save_path = substr($save_path,18);
        $model = TransmittalRecord::model()->findByPk($check_id);
        $model->save_path = $save_path;
        $result = $model->save();
    }

    //下载默认PDF
    public static function downloaddefaultPDF($params,$app_id){
        $check_id = $params['id'];
        $trans_model = TransmittalRecord::model()->findByPk($check_id);
        $project_nos = $trans_model->project_nos;
        $program_id = $trans_model->project_id;
        if($trans_model->rvo == '1'){
            $rvo = 'Yes';
        }else if($trans_model->rvo == '2'){
            $rvo = 'No';
        }else{
            $rvo = '';
        }
        $contractor_id = $trans_model->contractor_id;
        $attach_list = TransmittalRecordAttach::dealListBystep($check_id,'1');
        $user_list = TransmittalUser::userListByStep($check_id,1);
        $purpose_list = TransmittalRecord::purposeList();
        $detail_list = TransmittalDetail::dealListByStep($check_id,1);
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_id;
        $con_model = Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $company_address = $con_model->company_adr;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($trans_model->apply_time,0,4);//年
        $month = substr($trans_model->apply_time,5,2);//月
        $day = substr($trans_model->apply_time,8,2);//日
        $hours = substr($trans_model->apply_time,11,2);//小时
        $minute = substr($trans_model->apply_time,14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;
        //报告路径存入数据库

        //$filepath = Yii::app()->params['upload_record_path'].'/'.$year.'/'.$month.'/ptw'.'/PTW' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/'.$year.'/'.$month.'/'.$program_id.'/rf/'.$contractor_id.'/' . $check_id .'.pdf';
//        RfList::updatepath($id,$filepath);

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
        $pdf->SetTitle('Transmittal');
        $pdf->SetSubject($check_id);

        $_SESSION['title'] = 'Transmittal No.:  ' . $check_id;

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


        $logo_1 = 'img/trans_logo1.png';
        $logo_2 = 'img/trans_logo2.png';
        $logo_img_1= '<img src="'.$logo_1.'" height="50" width="90"  />';
        $logo_img_2= '<img src="'.$logo_2.'" height="50" width="50"  />';
        $title = '<h1>TRANSMITTAL</h1>';
        $header = "<table><tr ><td width=\"60%\" style=\"height:50px\" align=\"left\">$title</td><td width=\"20%\" rowspan='2' align=\"right\">$logo_img_1</td><td width=\"20%\" rowspan='2' align=\"center\">$logo_img_2</td></tr></table>";
//        $pdf->writeHTML($header, true, true, true, false, '');
        $pdf->writeHTML($header, true, true, true, false, '');

        $to_user = '';
        foreach($user_list as $i => $j){
            if($j['type'] == '1'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $user_name = $user_model->user_name;
                $to_user.=$user_name.' ';
            }
        }
        $cc_group = array();
        foreach($user_list as $i => $j){
            if($j['type'] == '2'){
                $user_model = Staff::model()->findByPk($j['user_id']);
                $group_name = RfGroupUser::findGroupByProgram($j['user_id'],$program_id);
                if($group_name != ''){
                    $cc_group[] = $group_name;
                }
            }
        }
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $header_info = "<table width=\"100%\" >
                    <tr> 
  	                    <td width=\"40%\" align=\"left\" style=\"height: 20px;\">Project:</td>
  	                    <td width=\"60%\" colspan=\"3\">$program_name</td>
                    </tr>
                    <tr>
                        <td width=\"40%\" align=\"left\" style=\"height: 20px;\">Project Nos:</td>
                        <td width=\"60%\" colspan=\"3\">$project_nos</td>
                    </tr>
                    <tr>
                        <td width=\"40%\" align=\"left\" style=\"height: 20px;\">To:</td>
                        <td width=\"60%\" colspan=\"3\">$to_user</td>
                    </tr>
                    <tr>
                        <td width=\"40%\" align=\"left\" style=\"height: 20px;\">Rvo:</td>
                        <td width=\"60%\" colspan=\"3\">$rvo</td>
                    </tr>";
        if(count($cc_group)>0){
            $cc_group = array_unique($cc_group);
            $cc_group_cnt = count($cc_group);
            $cc_group_row = ceil($cc_group_cnt/3);
            $index = 0;
            foreach ($cc_group as $cc_group_index => $cc_group_name){
                $index++;
                if($index % 3 == 1){
                    if($index == 1){
                        $header_info.=  "<tr>
                        <td width=\"40%\" align=\"left\" style=\"height:20px;\" rowspan=\"$cc_group_row\">Copy To:(Please tick)</td>
                        <td width=\"20%\" align=\"left\">$checked_img_html $cc_group_name</td>";
                    }else{
                        $header_info.=  "<tr>
                        <td width=\"20%\" align=\"left\">$checked_img_html $cc_group_name</td>";
                    }
                }else if($index % 3 == 0){
                    $header_info.=  "<td width=\"20%\" align=\"left\">$checked_img_html $cc_group_name</td></tr>";
                }else{
                    $header_info.=  "<td width=\"20%\" align=\"left\">$checked_img_html $cc_group_name</td>";
                }
            }
            if($index % 3 == 1){
                $header_info.=  "<td width=\"20%\"></td><td width=\"20%\"></td></tr>";
            }
            if($index % 3 == 2){
                $header_info.=  "<td width=\"20%\"></td></tr>";
            }
        }
        $header_info.="</table>";

        $pdf->writeHTML($header_info, true, true, true, false, '');

        $attach_info = "<table width=\"100%\" border=\"1\">
                    <tr> 
  	                    <td width=\"10%\" align=\"center\" style=\"height: 20px;border-width: 1px;border-color:gray gray gray gray\" >S/N</td>
  	                    <td width=\"30%\" align=\"center\" style=\"height: 20px;border-width: 1px;border-color:gray gray gray gray\" >Drawing No. / Document</td>
  	                    <td width=\"10%\" align=\"center\" style=\"height: 20px;border-width: 1px;border-color:gray gray gray gray\" >File Type (**dwg, pdf, etc)</td>
  	                    <td width=\"50%\" align=\"center\" style=\"height: 20px;border-width: 1px;border-color:gray gray gray gray\" colspan=\"3\">Purpose of Issue (Please tick)></td>
                    </tr>";
        $cnt = 0;
        foreach ($attach_list as $i => $j){
            $doc_name = $j['doc_name'];
            $doc_list = explode('.',$doc_name);
            $doc_type = $doc_list[1];
            $purpose = $purpose_list[$j['purpose']];
            $doc_path = $j['doc_path'];
            $cnt++;
            if($j['purpose'] == '1'){
                $img_html_1= '<img src="'.$checked_img.'" height="10" width="10" /> ';
                $img_html_2= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_3= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_4= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_5= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
            }else if($j['purpose'] == '2'){
                $img_html_1= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_2= '<img src="'.$checked_img.'" height="10" width="10" /> ';
                $img_html_3= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_4= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_5= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
            }else if($j['purpose'] == '3'){
                $img_html_1= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_2= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_3= '<img src="'.$checked_img.'" height="10" width="10" /> ';
                $img_html_4= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_5= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
            }else if($j['purpose'] == '4'){
                $img_html_1= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_2= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_3= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_4= '<img src="'.$checked_img.'" height="10" width="10" /> ';
                $img_html_5= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
            }else if($j['purpose'] == '5'){
                $img_html_1= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_2= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_3= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_4= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_5= '<img src="'.$checked_img.'" height="10" width="10" /> ';
            }else{
                $img_html_1= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_2= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_3= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_4= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
                $img_html_5= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
            }
            $attach_info.="<tr>
                        <td width=\"10%\" align=\"center\" style=\"height: 20px;border-width: 1px;border-color:gray gray gray gray\" rowspan=\"2\">$cnt</td>
                        <td width=\"30%\" align=\"center\" style=\"height: 20px;border-width: 1px;border-color:gray gray gray gray\" rowspan=\"2\">$doc_name</td>
                        <td width=\"10%\" align=\"center\" style=\"height: 20px;border-width: 1px;border-color:gray gray gray gray\" rowspan=\"2\">$doc_type</td>
                        <td width=\"15%\" align=\"left\" style=\"height: 20px;border-width: 1px;border-color:gray white white gray\">$img_html_1 Working drawings</td>
                        <td width=\"20%\" align=\"left\" style=\"height: 20px;border-width: 1px;border-color:gray white white white\">$img_html_2 For Information</td>
                        <td width=\"15%\" align=\"left\" style=\"height: 20px;border-width: 1px;border-color:gray gray white white\">$img_html_3 Approval drawings</td>
                    </tr>
                    <tr>
                        <td width=\"15%\" align=\"left\" style=\"height: 20px;border-width: 1px;border-color:white white gray gray\">$img_html_4 Construction drawings</td>
                        <td width=\"20%\" align=\"left\" style=\"height: 20px;border-width: 1px;border-color:white white gray white\">$img_html_5 Others</td>
                    </tr>";
        }
        $remark = $detail_list[0]['remark'];
        $attach_info.="<tr><td height=\"30px\" width=\"100%\" style=\"border-width: 1px;border-color:gray gray white gray\"><u>Remarks:</u></td></tr>";
        $attach_info.="<tr><td height=\"60px\" width=\"100%\" style=\"border-width: 1px;border-color:white gray gray gray\">{$remark}</td></tr>";
        $attach_info.="</table>";
        $pdf->writeHTML($attach_info, true, true, true, false, '');

        $progress_list = TransmittalDetail::dealList($check_id);
        $role_list = Role::roleList();
        foreach($progress_list as $m =>$n){
            $staff_model = Staff::model()->findByPk($n['user_id']);
            if($n['deal_type'] == '1'){
                $submit_name = $staff_model->user_name;
                $submit_role = $role_list[$staff_model->role_id];
                $submit_phone = $staff_model->user_phone;
                $submit_contractor_id = $staff_model->contractor_id;
                $submit_contractor = Contractor::model()->findByPk($submit_contractor_id);
                $submit_contractor_name = $submit_contractor->contractor_name;
                $submit_date = Utils::DateToEn($n['record_time']);
            }
            if($n['deal_type'] == '2'){
                $receive_name = $staff_model->user_name;
                $receive_role = $role_list[$staff_model->role_id];
                $receive_phone = $staff_model->user_phone;
                $receive_contractor_id = $staff_model->contractor_id;
                $receive_contractor = Contractor::model()->findByPk($receive_contractor_id);
                $receive_contractor_name = $receive_contractor->contractor_name;
                $receive_date = Utils::DateToEn($n['record_time']);
            }
        }
        $progress_info = "<table width=\"100%\" border=\"1\">";
        $progress_info.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">Issued By </td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Received By </td></tr>";
        $progress_info.="<tr><td style=\"border-width: 1px;border-color:gray gray white gray\">Name:  $submit_name</td><td style=\"border-width: 1px;border-color:gray gray white gray\">Name:  $receive_name</td></tr>";
        $progress_info.="<tr><td style=\"border-width: 1px;border-color:white gray white gray\">Designation:  $submit_role</td><td style=\"border-width: 1px;border-color:white gray white gray\">Designation:  $receive_role</td></tr>";
        $progress_info.="<tr><td style=\"border-width: 1px;border-color:white gray white gray\">$submit_contractor_name</td><td style=\"border-width: 1px;border-color:white gray white gray\">$receive_contractor_name</td></tr>";
        $progress_info.="<tr><td style=\"border-width: 1px;border-color:white gray white gray\">Tel:  $submit_phone</td><td style=\"border-width: 1px;border-color:white gray white gray\">Designation:  $receive_phone</td></tr>";
        $progress_info.="<tr><td style=\"border-width: 1px;border-color:white gray gray gray\">Date:  $submit_date</td><td style=\"border-width: 1px;border-color:white gray gray gray\">Date:  $receive_date</td></tr>";
        $progress_info.="</table>";
        $pdf->writeHTML($progress_info, true, true, true, false, '');
        $pdf->Output($filepath, 'F');  //保存到指定目录
//        $pdf->Output($filepath, 'I');  //保存到指定目录
        return $filepath;
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
