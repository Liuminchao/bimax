<?php
class UserReportCommand extends CConsoleCommand
{
    //php /opt/www-nginx/web/test/ctmgr/protected/yiic userreport bach
    //0,10 3-4 * * * php /opt/www-nginx/web/test/ctmgr/protected/yiic modulereport bach
    public function actionSendMail() {
        $add_user = '861';
        $args['cc'] = array('0'=>'859','1'=>'862');
        MailType::sendMail($add_user,$args['cc']);
    }

    public function actionBach()
    {
        ini_set('memory_limit','512M');
        $sql = "select * from bac_program_user_q where program_id = '863' and root_proid = '863'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        foreach($rows as $cnt => $list) {
            if($list['user_id'] != '') {
//        $user_id = $_REQUEST['user_id'];
                $program_id = $list['program_id'];
                $arry['contractor_id'] = $list['contractor_id'];
                $arry['status'] = '';
                $program_list = Program::programAllList($arry);//项目列表
                $program_name = $program_list[$list['program_id']];//项目名称
                $stuff_model = Staff::model()->findByPk($list['user_id']);//员工信息
                $stuffinfo_model = StaffInfo::model()->findByPk($list['user_id']);//员工资质信息
                $roleList = Role::roleList();//岗位列表
                $roleList['null'] = 'No';
                $qrcode =  $stuff_model->qrcode;
                $home_id_photo = $stuffinfo_model->home_id_photo;
                $bca_photo = $stuffinfo_model->bca_photo;
                $csoc_photo = $stuffinfo_model->csoc_photo;
                $ppt_photo = $stuffinfo_model->ppt_photo;
                $face_img = $stuffinfo_model->face_img;
                $programuser_list = ProgramUser::PersonelAuthority($list['user_id'], $list['program_id']);//项目成员信息
                $authority_list = ProgramUser::AllRoleList();
//                var_dump($programuser_model);
//                exit;
                $user_list = Staff::userAllList();//员工姓名
                $photo_list = StaffInfo::staffinfoPhoto($list['user_id']);
                $contractor_list = Contractor::compList();//承包商名称
                $lang = "_en";
                $showtime = Utils::DateToEn(date("Y-m-d"));//当前时间
                if (Yii::app()->language == 'zh_CN') {
                    $lang = "_zh"; //中文
                }
                //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
                $filepath = Yii::app()->params['upload_tmp_path'] . '/USER' . $list['user_id'] . $lang . '.pdf';
                $file_res[] = $filepath;
                $pdf_title = 'User' . $list['user_id'] . $lang . '.pdf';
                $title = Yii::t('proj_project_user', 'pdf_title');

                $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
                require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
                $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//        var_dump($pdf);
//        exit;
                // 设置文档信息
                $pdf->SetCreator(Yii::t('login', 'Website Name'));
                $pdf->SetAuthor(Yii::t('login', 'Website Name'));
                $pdf->SetTitle($title);
                $pdf->SetSubject($title);
                //$pdf->SetKeywords('PDF, LICEN');
                // 设置页眉和页脚信息
                $main_model = Contractor::model()->findByPk($list['contractor_id']);
                $contractor_name = $main_model->contractor_name;
                $header_title = Yii::t('proj_project_user','header_title');
                $logo_pic = $main_model->remark;
                if($logo_pic){
                    $logo = '/opt/www-nginx/web'.$logo_pic;
                    $pdf->SetHeaderData($logo, 20, $header_title, $contractor_name, array(0, 64, 255), array(0, 64, 128));
                }else{
                    $pdf->SetHeaderData('', 0, $header_title, $contractor_name, array(0, 64, 255), array(0, 64, 128));
                }
                $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

                // 设置页眉和页脚字体

                if (Yii::app()->language == 'zh_CN') {
                    $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
                } else if (Yii::app()->language == 'en_US') {
                    $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
                }

                $fitbox = false;

                $pdf->setFooterFont(Array('helvetica', '', '8'));

                //设置默认等宽字体
                $pdf->SetDefaultMonospacedFont('courier');

                //设置间距
                $pdf->SetMargins(15, 27, 15);
                $pdf->SetHeaderMargin(5);
                $pdf->SetFooterMargin(10);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                //设置分页
                $pdf->SetAutoPageBreak(TRUE, 25);
                //set image scale factor
                $pdf->setImageScale(1.25);
                //set default font subsetting mode
                $pdf->setFontSubsetting(true);
                //设置字体
                if (Yii::app()->language == 'zh_CN') {
                    $pdf->SetFont('droidsansfallback', '', 14, '', true); //中文
                } else if (Yii::app()->language == 'en_US') {
                    $pdf->SetFont('droidsansfallback', '', 14, '', true); //英文
                }

                $pdf->AddPage();
                //员工信息
                $stuff_html =
                    '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td colspan="2"><h5 align="center">' . $title . '</h5></td></tr><tr><td width="25%">' . Yii::t('proj_project_user', 'personel_name') . '</td><td width="75%">' . $user_list[$list['user_id']] . '</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project_user', 'company_name') . '</td><td width="75%">' . $contractor_list[$list['contractor_id']] . '</td></tr><tr><td width="25%">'
                    . Yii::t('proj_project', 'program_name') . '</td><td width="75%">' . $program_name . '</td></tr><tr><td width="25%">'
                    . Yii::t('comp_staff', 'bca_pass_no') . '</td><td width="75%">' . $stuff_model->work_no . '</td></tr><tr><td width="25%">'
                    . Yii::t('comp_staff', 'Role_id') . '</td><td width="75%">' . $roleList[$stuff_model->role_id] . '</td></tr>';

                //拍照记录
                $personel_html = '<tr><td width="25%" height="120px">'
                    . Yii::t('proj_project_user', 'personel_photo') . '</td><td width="75%"></td></tr>';
                $personel_x = 75;
                if ($face_img) {
                    $pdf->Image($face_img, $personel_x, 90, 30, 30, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
                }
                if($qrcode){
                    $pdf->Image('/opt/www-nginx/web'.$qrcode, $personel_x+35, 90, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 0, false, false, false);
                }
                //风险评估职责
                $ra_role = $programuser_list[0]['ra_role'];
                $rarole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'ra_role') . '</td><td width="75%">'
                    . $authority_list['ra_role'][$ra_role] . '</td></tr>';
                //许可证成员
                $ptw_role = $programuser_list[0]['ptw_role'];
                $ptwrole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'ptw_role') . '</td><td width="75%">'
                    . $authority_list['ptw_role'][$ptw_role] . '</td></tr>';
                //安全委员会委员
                $wsh_mbr_flag = $programuser_list[0]['wsh_mbr_flag'];
                $wsh_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'wsh_mbr_flag') . '</td><td width="75%">'
                    . $authority_list['wsh_mbr_flag'][$wsh_mbr_flag] . '</td></tr>';
                //举行会议人
                $meeting_flag = $programuser_list[0]['meeting_flag'];
                $meeting_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'meeting_flag') . '</td><td width="75%">'
                    . $authority_list['meeting_flag'][$meeting_flag] . '</td></tr>';
                //举行培训人
                $training_flag = $programuser_list[0]['training_flag'];
                $training_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'training_flag') . '</td><td width="75%">'
                    . $authority_list['training_flag'][$training_flag] . '</td></tr>';
                //项目角色
                $program_role = explode('|', $programuser_list[0]['program_role']);
//                $mainrole_html = '<tr><td width="25%">'
//                    . Yii::t('proj_project_user', 'main_role') . '</td><td width="75%">'
//                    . $roleList[$program_role[0]] . '</td></tr>';
                //第一角色
                $firstrole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'first_role') . '</td><td width="75%">'
                    . $roleList[$program_role[1]] . '</td></tr>';
                //第二角色
                $secondrole_html = '<tr><td width="25%">'
                    . Yii::t('proj_project_user', 'second_role') . '</td><td width="75%">'
                    . $roleList[$program_role[2]] . '</td></tr></table>';

                $html = $stuff_html . $personel_html . $rarole_html . $ptwrole_html . $wsh_html . $meeting_html . $training_html  . $firstrole_html . $secondrole_html;

                $pdf->writeHTML($html, true, false, true, false, '');

                $img_num = 0;//检验页码标志

                //身份证照片
                $home_html = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'hpme_id_photo') .'</td><td width="75%"></td></tr>';
                $x = 30;
                $y_1 = 30;//第一张y的位置
                $y_2 = 150;//第二张y的位置
                //$home_id_photo
                if($home_id_photo){
                    $pdf->AddPage();//再加一页
                    $img_num = $img_num +1;
                    $pdf->Image($home_id_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                }
                //护照照片
                $ppt_html = '<tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'ppt_photo') .'</td><td width="75%"></td></tr>';
                //$ppt_photo
                if($ppt_photo){
                    if($img_num%2  == 0 ) {
                        $pdf->AddPage();//再加一页
                        $img_num = $img_num + 1;
                        $pdf->Image($ppt_photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                    }else{
                        $img_num = $img_num +1;
                        $pdf->Image($ppt_photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                    }
                }
                //安全证照片
                $csoc_html = '<tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'csoc_photo') .'</td><td width="75%"></td></tr>';
                //$csoc_photo
                if($csoc_photo && file_exists('/opt/www-nginx/web'.$csoc_photo)){
                    if($img_num%2  == 0 ){
                        $pdf->AddPage();//再加一页
                        $img_num = $img_num +1;
                        $pdf->Image('/opt/www-nginx/web'.$csoc_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }else{
                        $img_num = $img_num +1;
                        $pdf->Image('/opt/www-nginx/web'.$csoc_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }

                }
                //准证照片
                $bca_html = '<tr><td width="25%" height="150px">'
                    . Yii::t('proj_project_user', 'bca_photo') .'</td><td width="75%"></td></tr></table>';
                //$bca_photo
                if($bca_photo && file_exists('/opt/www-nginx/web'.$bca_photo)){
                    if($img_num%2  == 0 ){
                        $pdf->AddPage();//再加一页
                        $img_num = $img_num +1;
                        $pdf->Image('/opt/www-nginx/web'.$bca_photo, $x, $y_1, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }else{
                        $img_num = $img_num +1;
                        $pdf->Image('/opt/www-nginx/web'.$bca_photo, $x, $y_2, 150, 100, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
                    }
                }
                $aptitude_list =UserAptitude::queryAll($list['user_id']);//人员证书
                if($aptitude_list){
                    foreach($aptitude_list as $cnt => $list){
                        $aptitude = explode('|',$list['aptitude_photo']);
                        foreach($aptitude as $i => $photo){
                            $file = explode('.',$photo);
                            if($file[1] != 'pdf') {
                                if ($img_num % 2 == 0) {
                                    $pdf->AddPage();//再加一页
                                    $img_num = $img_num + 1;
                                    $pdf->Image('/opt/www-nginx/web'.$photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                                } else {
                                    $img_num = $img_num + 1;
                                    $pdf->Image('/opt/www-nginx/web'.$photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                                }
                            }
                        }
                    }
                }
                $html_2 = $home_html . $ppt_html . $csoc_html  .  $bca_html;

                //输出PDF
                $pdf->Output($filepath, 'F'); //保存到指定目录

            }
        }
        $filename = "/opt/www-nginx/web/filebase/tmp/bak".$program_id.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            foreach ($file_res as $cnt => $path) {
                $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            }
            $zip->close();
        }
        foreach ($file_res as $cnt => $path) {
            unlink($path);
        }

        echo 'over';
    }
}