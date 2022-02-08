<?php

/**
 * 邮件发送
 * @author LiuMinchao
 */
class MailType extends CActiveRecord {

    const STATUS_EXPIRING = '1'; //即将过期
    const STATUS_EXPIRED = '2'; //已过期
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_mail';
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
            self::STATUS_EXPIRING => Yii::t('comp_aptitude', 'expiring'),
            self::STATUS_EXPIRED => Yii::t('comp_aptitude', 'expired'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_EXPIRING => 'label-success', //已启用
            self::STATUS_EXPIRED => ' label-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    //看当前用户所在项目的超期人员证书列表
    public static function queryAptitude($page, $pageSize, $args = array()){
        $args['date'] = date('Y-m-d', strtotime("+30 day"));
        $sql = "select
            a.user_id, d.user_name, e.certificate_name, e.certificate_name_en, a.permit_enddate,
            f.contractor_name, g.program_name
                from
             bac_aptitude a
        join bac_program_user_q b on a.user_id = b.user_id
        join bac_program_user_q c on b.root_proid = c.root_proid
        join bac_staff d on a.user_id = d.user_id
        join bac_certificate e on a.certificate_type = e.certificate_type
        join bac_contractor f on d.contractor_id = f.contractor_id
        join bac_program g on c.root_proid = g.program_id
        WHERE
              a.status = '0' and a.permit_enddate < '".$args['date']."'
              and b.check_status not in('12','21')
              and c.root_proid = '".$args['root_proid']."'
              and c.contractor_id = '".$args['contractor_id']."'
              and c.user_id = '".$args['user_id']."' and c.root_proid = c.program_id and c.check_status not in('12','21')
        order by
            g.program_id desc";
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($data);
        $pagedata=array();
        $pagedata=array_slice($data,$start,$pageSize);
//        var_dump($pagedata);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;
        $rs['data'] = $data;

        return $rs;
    }

    public static function sendMail($add_user,$to_user,$cc_user,$args){
        $add_user_model = Staff::model()->findByPk($add_user);
        $add_primary_email = $add_user_model->primary_email;
        $add_user_name = $add_user_model->user_name;
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $apply_user_id = $rf_model->apply_user_id;
        $apply_user_model = Staff::model()->findByPk($apply_user_id);
        $apply_user_name = $apply_user_model->user_name;
        if($rf_model->type == '1'){
            $type = 'RFI';
        }else if($rf_model->type == '2'){
            $type = 'RFA';
        }
        $subject = $rf_model->subject;
        $check_no = $rf_model->check_no;
        if($add_primary_email != ''){
            header("content-type:text/html;charset=utf-8");
            ini_set("magic_quotes_runtime",0);
//        require 'class.phpmailer.php';
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'class.phpmailer.php';
            require_once($tcpdfPath);
            try {
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
                $mail->SMTPAuth   = true;                  //开启认证
                $mail->Port       = 465;
                $mail->Host       = "hwsmtp.exmail.qq.com";
                $mail->Username   = "service@cmstech.sg";
                $mail->SMTPSecure = 'ssl';
                $mail->Password   = "Wj1109";
                //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
                //$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
                $mail->From       = "service@cmstech.sg";
                $mail->FromName   = "CMS";
                $mail->AddAddress($add_primary_email,$add_user_name);
                $cc_str = '';
                if($cc_user != '' && count($cc_user)>0){
                    foreach($cc_user as $i => $j){
                        $cc_user_model = Staff::model()->findByPk($j);
                        $cc_primary_email = $cc_user_model->primary_email;
                        $cc_str.=$cc_user_model->user_name.';';
//                        if($cc_primary_email != ''){
//                            $bool = filter_var($cc_primary_email, FILTER_VALIDATE_EMAIL);
//                            if($bool){
//                                $mail->addCC($cc_primary_email);
//                            }
//                        }
                    }
                }
                $mail->Subject  = $check_no;
                $to_str = '';
                foreach ($to_user as $i => $to_user_id){
                    $to_user_model = Staff::model()->findByPk($to_user_id);
                    $to_str.=$to_user_model->user_name.';';
                }

                $apply_user = Staff::model()->findByPk($rf_model->apply_user_id);
                $apply_user_name = $apply_user->user_name;
                $mail->Body = "<br><br><br>You have a new notification:<br><br>".'Type:'.$type.'<br>'.'Ref no:'.$check_no.'<br>'.'Subject:'.$subject."<br>From: ".$apply_user_name."<br>To: ".$to_str."<br>Cc: ".$cc_str."<br><br>Please kindly click the link below to take the necessary action.<br>https://w.beehives.sg/bimax/index.php<br><br><br>
            Thanks.<br><br>Yours sincerely,<br>CMS Data Technology Pte. Ltd<br>----------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
            Please do not reply to this email.<br>This email is confidential and privileged. If you are not the intended recipient, you must not view, disseminate, use or copy this email. Kindly notify the sender immediately, and delete this email from your system. Thank you.";
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
                $mail->WordWrap   = 80; // 设置每行字符串的长度
//                $mail->AddAttachment("./template/excel/Staff.xls");  //可以添加附件
                $mail->IsHTML(true);
                $result = $mail->Send();
                $record_time = date('Y-m-d H:i:s');
                $txt = '['.$record_time.']'.'  '.'RF Mail'.': '.$result;
                RfList::write_log($txt);
                $r['msg'] = 'Send Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } catch (phpmailerException $e) {
                $r['msg'] = $e->errorMessage();
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }else{
            $r['msg'] = 'No Email Address';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function sendMail2($add_user,$args){
        $add_user_model = Staff::model()->findByPk($add_user);
        $add_primary_email = $add_user_model->primary_email;
        $add_user_name = $add_user_model->user_name;
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $apply_user_id = $rf_model->apply_user_id;
        $apply_user_model = Staff::model()->findByPk($apply_user_id);
        $apply_user_name = $apply_user_model->user_name;
        if($add_primary_email != ''){
            header("content-type:text/html;charset=utf-8");
            ini_set("magic_quotes_runtime",0);
//        require 'class.phpmailer.php';
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'class.phpmailer.php';
            require_once($tcpdfPath);
            try {
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
                $mail->SMTPAuth   = true;                  //开启认证
                $mail->Port       = 465;
                $mail->Host       = "hwsmtp.exmail.qq.com";
                $mail->Username   = "service@cmstech.sg";
                $mail->SMTPSecure = 'ssl';
                $mail->Password   = "Wj1109";
                //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
                //$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
                $mail->From       = "service@cmstech.sg";
                $mail->FromName   = "CMS";
                $mail->AddAddress($add_primary_email,$add_user_name);
//                if($cc_user != '' && count($cc_user)>0){
//                    foreach($cc_user as $i => $j){
//                        $cc_user_model = Staff::model()->findByPk($j);
//                        $cc_primary_email = $cc_user_model->primary_email;
//                        if($cc_primary_email != ''){
//                            $bool = filter_var($cc_primary_email, FILTER_VALIDATE_EMAIL);
//                            if($bool){
//                                $mail->addCC($cc_primary_email);
//                            }
//                        }
//                    }
//                }
                $sql = "select step from rf_record_user
                 where check_id=:check_id group by step order by step desc limit 1";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $rows = $command->queryAll();
                $step = $rows[0]['step'];

                $to_list = RfUser::userList($args['check_id'],$step,'1');
                $to_str = '';
                foreach ($to_list as $i => $j){
                    $to_str.=$j['user_name'].';';
                }
                $cc_list = RfUser::userList($args['check_id'],$step,'2');
                $cc_str = '';
                foreach ($cc_list as $i => $j){
                    $cc_str.=$j['user_name'].';';
                }

                $mail->Subject  = $args['check_no'];
                $mail->Body = "<h1>Dear $add_user_name</h1><br><br><br><br><br>"."Ref no:<span style='font-weight:bold'>".$args['check_no']."</span><br>"."Subject:".$args['subject']."<br>From: ".$apply_user_name."<br>To: ".$to_str."<br>Cc: ".$cc_str."<br><p>will be overdue on  <span style='color:#FF0000;font-weight:bold;font-size: 200%'>".Utils::DateToEn($args['valid_time'])."</span></p><br><br>Please kindly click the link below to take the necessary action.<br>https://w.beehives.sg/bimax/index.php<br><br><br>
            Thanks.<br><br>Yours sincerely,<br>CMS Data Technology Pte. Ltd<br>----------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
            Please do not reply to this email.<br>This email is confidential and privileged. If you are not the intended recipient, you must not view, disseminate, use or copy this email. Kindly notify the sender immediately, and delete this email from your system. Thank you.";
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
                $mail->WordWrap   = 80; // 设置每行字符串的长度
//                $mail->AddAttachment("./template/excel/Staff.xls");  //可以添加附件
                $mail->IsHTML(true);
                $result = $mail->Send();
                $record_time = date('Y-m-d H:i:s');
                $txt = '['.$record_time.']'.'  '.'RF Command Mail'.': '.$result;
                RfList::write_log($txt);
                $r['msg'] = 'Send Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } catch (phpmailerException $e) {
                $r['msg'] = $e->errorMessage();
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }else{
            $r['msg'] = 'No Email Address';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function sendMail3($add_user,$args){
        $add_user_model = Staff::model()->findByPk($add_user);
        $add_primary_email = $add_user_model->primary_email;
        $add_user_name = $add_user_model->user_name;
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $apply_user_id = $rf_model->apply_user_id;
        $apply_user_model = Staff::model()->findByPk($apply_user_id);
        $apply_user_name = $apply_user_model->user_name;
        if($add_primary_email != ''){
            header("content-type:text/html;charset=utf-8");
            ini_set("magic_quotes_runtime",0);
//        require 'class.phpmailer.php';
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'class.phpmailer.php';
            require_once($tcpdfPath);
            try {
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
                $mail->SMTPAuth   = true;                  //开启认证
                $mail->Port       = 465;
                $mail->Host       = "hwsmtp.exmail.qq.com";
                $mail->Username   = "service@cmstech.sg";
                $mail->SMTPSecure = 'ssl';
                $mail->Password   = "Wj1109";
                //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
                //$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
                $mail->From       = "service@cmstech.sg";
                $mail->FromName   = "CMS";
                $mail->AddAddress($add_primary_email,$add_user_name);
//                if($cc_user != '' && count($cc_user)>0){
//                    foreach($cc_user as $i => $j){
//                        $cc_user_model = Staff::model()->findByPk($j);
//                        $cc_primary_email = $cc_user_model->primary_email;
//                        if($cc_primary_email != ''){
//                            $bool = filter_var($cc_primary_email, FILTER_VALIDATE_EMAIL);
//                            if($bool){
//                                $mail->addCC($cc_primary_email);
//                            }
//                        }
//                    }
//                }
                $mail->Subject  = $args['check_no'];
                $mail->Body = "<h1>Dear $add_user_name</h1><br><br><br><br><br>"."Ref. no: <span style='font-weight:bold'>".$args['check_no']."</span><br>"."Subject:".$args['subject']."<br><br><p>is <span style='color:#FF0000;font-weight:bold;font-size: 200%'>overdue.</span></p><br><br>Please kindly click the link below to take the necessary action.<br>https://w.beehives.sg/bimax/index.php<br><br><br>
            Thanks.<br><br>Yours sincerely,<br>CMS Data Technology Pte. Ltd<br>----------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
            Please do not reply to this email.<br>This email is confidential and privileged. If you are not the intended recipient, you must not view, disseminate, use or copy this email. Kindly notify the sender immediately, and delete this email from your system. Thank you.";
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
                $mail->WordWrap   = 80; // 设置每行字符串的长度
//                $mail->AddAttachment("./template/excel/Staff.xls");  //可以添加附件
                $mail->IsHTML(true);
                $result = $mail->Send();
                $record_time = date('Y-m-d H:i:s');
                $txt = '['.$record_time.']'.'  '.'RF Command Mail'.': '.$result;
                RfList::write_log($txt);
                $r['msg'] = 'Send Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } catch (phpmailerException $e) {
                $r['msg'] = $e->errorMessage();
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }else{
            $r['msg'] = 'No Email Address';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //cc后发送邮件
    public static function sendMail4($add_user,$to_user,$cc_user,$args){
        $add_user_model = Staff::model()->findByPk($add_user);
        $add_primary_email = $add_user_model->primary_email;
        $add_user_name = $add_user_model->user_name;
        $rf_model = RfList::model()->findByPk($args['check_id']);
        $apply_user_id = $rf_model->apply_user_id;
        $apply_user_model = Staff::model()->findByPk($args['add_user']);
        $apply_user_name = $apply_user_model->user_name;
        if($rf_model->type == '1'){
            $type = 'RFI';
        }else if($rf_model->type == '2'){
            $type = 'RFA';
        }
        $subject = $rf_model->subject;
        $check_no = $rf_model->check_no;
        if($add_primary_email != ''){
            header("content-type:text/html;charset=utf-8");
            ini_set("magic_quotes_runtime",0);
//        require 'class.phpmailer.php';
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'class.phpmailer.php';
            require_once($tcpdfPath);
            try {
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
                $mail->SMTPAuth   = true;                  //开启认证
                $mail->Port       = 465;
                $mail->Host       = "hwsmtp.exmail.qq.com";
                $mail->Username   = "service@cmstech.sg";
                $mail->SMTPSecure = 'ssl';
                $mail->Password   = "Wj1109";
                //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
                //$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
                $mail->From       = "service@cmstech.sg";
                $mail->FromName   = "CMS";
                $mail->AddAddress($add_primary_email,$add_user_name);
                $cc_str = '';
                if($cc_user != '' && count($cc_user)>0){
                    foreach($cc_user as $i => $j){
                        $cc_user_model = Staff::model()->findByPk($j);
                        $cc_primary_email = $cc_user_model->primary_email;
                        $cc_str.=$cc_user_model->user_name.';';
//                        if($cc_primary_email != ''){
//                            $bool = filter_var($cc_primary_email, FILTER_VALIDATE_EMAIL);
//                            if($bool){
//                                $mail->addCC($cc_primary_email);
//                            }
//                        }
                    }
                }
                $mail->Subject  = $check_no;
                $to_str = '';
                foreach ($to_user as $i => $to_user_id){
                    $to_user_model = Staff::model()->findByPk($to_user_id);
                    $to_str.=$to_user_model->user_name.';';
                }
                $mail->Body = "<br><br><br>You have a new comment:<br><br>".'Type:'.$type.'<br>'.'Ref no:'.$check_no.'<br>'.'Subject:'.$subject."<br>From: ".$apply_user_name."<br>To: ".$to_str."<br>Cc: ".$cc_str."<br><br>Please kindly click the link below to take the necessary action.<br>https://w.beehives.sg/bimax/index.php<br><br><br>
            Thanks.<br><br>Yours sincerely,<br>CMS Data Technology Pte. Ltd<br>----------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
            Please do not reply to this email.<br>This email is confidential and privileged. If you are not the intended recipient, you must not view, disseminate, use or copy this email. Kindly notify the sender immediately, and delete this email from your system. Thank you.";
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
                $mail->WordWrap   = 80; // 设置每行字符串的长度
//                $mail->AddAttachment("./template/excel/Staff.xls");  //可以添加附件
                $mail->IsHTML(true);
                $result = $mail->Send();
                $record_time = date('Y-m-d H:i:s');
                $txt = '['.$record_time.']'.'  '.'RF Mail'.': '.$result;
                RfList::write_log($txt);
                $r['msg'] = 'Send Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } catch (phpmailerException $e) {
                $r['msg'] = $e->errorMessage();
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }else{
            $r['msg'] = 'No Email Address';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function sendTransMail($add_user,$to_user,$cc_user,$args){
        $add_user_model = Staff::model()->findByPk($add_user);
        $add_primary_email = $add_user_model->primary_email;
        $add_user_name = $add_user_model->user_name;
        $trans_model = TransmittalRecord::model()->findByPk($args['check_id']);
        $apply_user_id = $trans_model->apply_user_id;
        $apply_user_model = Staff::model()->findByPk($apply_user_id);
        $apply_user_name = $apply_user_model->user_name;
        $program_id = $trans_model->project_id;
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $subject = $trans_model->subject;
        if($add_primary_email != ''){
            header("content-type:text/html;charset=utf-8");
            ini_set("magic_quotes_runtime",0);
//        require 'class.phpmailer.php';
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'class.phpmailer.php';
            require_once($tcpdfPath);
            try {
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
                $mail->SMTPAuth   = true;                  //开启认证
                $mail->Port       = 465;
                $mail->Host       = "hwsmtp.exmail.qq.com";
                $mail->Username   = "service@cmstech.sg";
                $mail->SMTPSecure = 'ssl';
                $mail->Password   = "Wj1109";
                //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
                //$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
                $mail->From       = "service@cmstech.sg";
                $mail->FromName   = "CMS";
                $mail->AddAddress($add_primary_email,$add_user_name);
                $cc_str = '';
                if($cc_user != '' && count($cc_user)>0){
                    foreach($cc_user as $i => $j){
                        $cc_user_model = Staff::model()->findByPk($j);
                        $cc_primary_email = $cc_user_model->primary_email;
                        $cc_str.=$cc_user_model->user_name.';';
                    }
                }
                $mail->Subject  = $subject;
                $to_str = '';
                foreach ($to_user as $i => $to_user_id){
                    $to_user_model = Staff::model()->findByPk($to_user_id);
                    $to_str.=$to_user_model->user_name.';';
                }

                $apply_user = Staff::model()->findByPk($trans_model->apply_user_id);
                $apply_user_name = $apply_user->user_name;
                $mail->Body = "<br><br><br>You have a new transmittal: <br><br>".'Project:'.$program_name.'<br>'.'Subject:'.$subject."<br>From: ".$apply_user_name."<br>To: ".$to_str."<br>Cc: ".$cc_str."<br><br>Please kindly click the link below to take the necessary action.<br>https://www.beehives.sg/bimax/index.php<br><br><br>
            Thanks.<br><br>Yours sincerely,<br>CMS Data Technology Pte. Ltd<br>----------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
            Please do not reply to this email.<br>This email is confidential and privileged. If you are not the intended recipient, you must not view, disseminate, use or copy this email. Kindly notify the sender immediately, and delete this email from your system. Thank you.";
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
                $mail->WordWrap   = 80; // 设置每行字符串的长度
//                $mail->AddAttachment("./template/excel/Staff.xls");  //可以添加附件
                $mail->IsHTML(true);
                $result = $mail->Send();
                $record_time = date('Y-m-d H:i:s');
                $txt = '['.$record_time.']'.'  '.'RF Mail'.': '.$result;
                RfList::write_log($txt);
                $r['msg'] = 'Send Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } catch (phpmailerException $e) {
                $r['msg'] = $e->errorMessage();
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }else{
            $r['msg'] = 'No Email Address';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function receiveTransMail($add_user,$to_user,$cc_user,$args){
        $add_user_model = Staff::model()->findByPk($add_user);
        $add_primary_email = $add_user_model->primary_email;
        $add_user_name = $add_user_model->user_name;
        $trans_model = TransmittalRecord::model()->findByPk($args['check_id']);
        $apply_user_id = $trans_model->apply_user_id;
        $apply_user_model = Staff::model()->findByPk($args['add_user']);
        $apply_user_name = $apply_user_model->user_name;
        $program_id = $trans_model->project_id;
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $subject = $trans_model->subject;
        if($add_primary_email != ''){
            header("content-type:text/html;charset=utf-8");
            ini_set("magic_quotes_runtime",0);
//        require 'class.phpmailer.php';
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'PHPMailer'.DIRECTORY_SEPARATOR.'class.phpmailer.php';
            require_once($tcpdfPath);
            try {
                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
                $mail->SMTPAuth   = true;                  //开启认证
                $mail->Port       = 465;
                $mail->Host       = "hwsmtp.exmail.qq.com";
                $mail->Username   = "service@cmstech.sg";
                $mail->SMTPSecure = 'ssl';
                $mail->Password   = "Wj1109";
                //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
                //$mail->AddReplyTo("648700755@qq.com","liumc");//回复地址
                $mail->From       = "service@cmstech.sg";
                $mail->FromName   = "CMS";
                $mail->AddAddress($add_primary_email,$add_user_name);
                $cc_str = '';
                if($cc_user != '' && count($cc_user)>0){
                    foreach($cc_user as $i => $j){
                        $cc_user_model = Staff::model()->findByPk($j);
                        $cc_primary_email = $cc_user_model->primary_email;
                        $cc_str.=$cc_user_model->user_name.';';
                    }
                }
                $mail->Subject  = $subject;
                $to_str = '';
                foreach ($to_user as $i => $to_user_id){
                    $to_user_model = Staff::model()->findByPk($to_user_id);
                    $to_str.=$to_user_model->user_name.';';
                }

                $mail->Body = "<br><br><br>The transmittal below has been received. <br><br>".'Project:'.$program_name.'<br>'.'Subject:'.$subject."<br>From: ".$apply_user_name."<br>To: ".$to_str."<br>Cc: ".$cc_str."<br><br>Please kindly click the link below to take the necessary action.<br>https://w.beehives.sg/bimax/index.php<br><br><br>
            Thanks.<br><br>Yours sincerely,<br>CMS Data Technology Pte. Ltd<br>----------------------------------------------------------------------------------------------------------------------------------------------------------------<br>
            Please do not reply to this email.<br>This email is confidential and privileged. If you are not the intended recipient, you must not view, disseminate, use or copy this email. Kindly notify the sender immediately, and delete this email from your system. Thank you.";
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
                $mail->WordWrap   = 80; // 设置每行字符串的长度
//                $mail->AddAttachment("./template/excel/Staff.xls");  //可以添加附件
                $mail->IsHTML(true);
                $result = $mail->Send();
                $record_time = date('Y-m-d H:i:s');
                $txt = '['.$record_time.']'.'  '.'RF Mail'.': '.$result;
                RfList::write_log($txt);
                $r['msg'] = 'Send Success';
                $r['status'] = 1;
                $r['refresh'] = true;
            } catch (phpmailerException $e) {
                $r['msg'] = $e->errorMessage();
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }else{
            $r['msg'] = 'No Email Address';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
}
