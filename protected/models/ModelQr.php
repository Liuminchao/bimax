<?php

/**
 * 模型二维码
 *
 * @author liuxy
 */
class ModelQr extends CActiveRecord {

    //生成二维码（根据模型）
    public static function createQr($params,$program_id){
        $model_id = $params['modelId'];
        $uuid = $params['uuid'];
        $entityId = $params['entityId'];
        $type = $params['type'];
        $version = $params['version'];
        $name = $params['name'];
        if(array_key_exists('properties',$params)){
            foreach ($params['properties'] as $m => $n){
                if($n['group'] == 'Constraints'){
                    if($n['key'] == 'Reference Level'){
                        $model_level = $n['value'];
                    }else if($n['key'] == 'Level'){
                        $model_level = $n['value'];
                    }else if($n['key'] == 'Base Constraint'){
                        $model_level = $n['value'];
                    }
                }
            }
        }
        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $program_id . '/model/';

        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);

        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
        $filename = $PNG_TEMP_DIR . $entityId . '.png';

        $pbu_info = RevitComponent::pbuInfo($program_id,$uuid);
        $level = '';
        $block = '';
        if(count($pbu_info)>0){
            $level = $pbu_info[0]['level'];
            $block = $pbu_info[0]['block'];
        }

        $content = array();
        $content['model_id'] = $model_id;
        $content['version'] = $version;
        $content['uuid'] = $uuid;
        $content['level'] = $level;
        $content['model_level'] = $model_level;
        $content['block'] = $block;
        $content['program_id'] = $program_id;
        $content = json_encode($content);
        $content = base64_encode($content);
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        return $filename;
    }

    //生成二维码（根据模型）
    public static function createNomodelQr($id,$program_id){
        $pbu_model = RevitComponent::model()->findByPk($id);
        $model_id = $pbu_model->model_id;
        $uuid = $pbu_model->pbu_id;
        $entityId = '';
        $type = $pbu_model->pbu_type;
        $version = $pbu_model->version;
        $name = $pbu_model->pbu_name;
        $block = $pbu_model->block;
        $level = $pbu_model->level;
        $model_level = $model_id.'_'.$level;
        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $program_id . '/model/';

        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);

        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
        $filename = $PNG_TEMP_DIR . $id . '.png';


        $content = array();
        $content['model_id'] = $model_id;
        $content['version'] = $version;
        $content['uuid'] = $uuid;
        $content['level'] = $level;
        $content['model_level'] = $model_level;
        $content['block'] = $block;
        $content['program_id'] = $program_id;
        $content = json_encode($content);
        $content = base64_encode($content);
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        return $filename;
    }

    //生成二维码（根据列表）
    public static function createPbuQr($id){
        $pbu_model = RevitComponent::model()->findByPk($id);
        $model_id = $pbu_model->model_id;
        $version = $pbu_model->version;
        $program_id = $pbu_model->project_id;
        $uuid = $pbu_model->pbu_id;
        $level = $pbu_model->level;
        $block = $pbu_model->block;

        $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] . '/qrcode/' . $program_id . '/model/';
        if($model_id != '0'){
            $data = array(
                'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
                'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
            );
            foreach ($data as $key => $value) {
                $post_data[$key] = $value;
            }
            //        $data = json_encode($post_data);
            $url = "https://bim.cmstech.sg/api/v1/token";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //post提交
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $rs = json_decode($output,true);
            $data = array(
                'modelId' => $model_id,
                'version' => $version
            );

            $arr = array(
                'x-access-token:'.$rs['data']['token']
            );
            foreach ($data as $key => $value) {
                $post_data[$key] = $value;
            }

            $url = "https://bim.cmstech.sg/api/v1/entity/$uuid";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            //跳过SSL验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
            // 3. 执行并获取HTML文档内容
            $output = curl_exec($ch);
            $r = json_decode($output,true);
            foreach ($r['data']['properties'] as $m => $n){
                if($n['group'] == 'Constraints'){
                    if($n['key'] == 'Reference Level'){
                        $model_level = $n['value'];
                    }else if($n['key'] == 'Level'){
                        $model_level = $n['value'];
                    }else if($n['key'] == 'Base Constraint'){
                        $model_level = $n['value'];
                    }
                }
            }
        }else{
            $model_level = '';
        }

        if (!file_exists($PNG_TEMP_DIR))
            @mkdir($PNG_TEMP_DIR, 0777, true);

        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'phpqrcode' . DIRECTORY_SEPARATOR . 'qrlib.php';
        require_once($tcpdfPath);
        //processing form input
        //remember to sanitize user input in real-life solution !!!
        $errorCorrectionLevel = 'L';
        if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
            $errorCorrectionLevel = $_REQUEST['level'];

        $matrixPointSize = 6;
        if (isset($_REQUEST['size']))
            $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
        $filename = $PNG_TEMP_DIR . $id . '.png';


        $content = array();
        $content['model_id'] = $model_id;
        $content['version'] = $version;
        $content['uuid'] = $uuid;
        $content['level'] = $level;
        $content['model_level'] = $model_level;
        $content['block'] = $block;
        $content['program_id'] = $program_id;
        $content = json_encode($content);
        $content = base64_encode($content);
        QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        return $filename;
    }

    //下载PDF
    public static function downloadPdf($data,$program_id){
        $pro_model = Program::model()->findByPk($program_id);
        $program_id = $pro_model->root_proid;

        $program_name = $pro_model->program_name;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_tmp_path'] . '/' . $data[0]['entityId'] . '.pdf';
//        $full_dir = Yii::app()->params['upload_tmp_path'] . '/' .$data[0]['modelId'];
//        if(!file_exists($full_dir))
//        {
//            umask(0000);
//            @mkdir($full_dir, 0777, true);
//        }
        $pdf_title = $data[0]['name'] . $data[0]['entityId'] . '.pdf';
        $title = Yii::t('proj_project_user', 'pdf_title');
        $header_title = Yii::t('proj_project_user','header_title');

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A7', true, 'UTF-8', false);
        //        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $_SESSION['title'] = $header_title; // 把username存在$_SESSION['user'] 里面
        // 设置页眉和页脚信息
//        $pdf->SetHeaderData('', 0, '', $header_title, array(0, 64, 255), array(0, 64, 128));
//        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 设置页眉和页脚字体

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(1, 1, 1);
        $pdf->setCellPaddings(1,1,1,1);
        $pdf->SetHeaderMargin(1);
        $pdf->SetFooterMargin(1);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 1);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
        }
        $cms = 'img/RF.jpg';
        $cms_1 = 'https://shell.cmstech.sg/test/idd/img/RF.jpg';
        $content_list = ModelQr::QueryQr($program_id);
        $local = array(
            'Uuid' =>	'pbu_id',
            'Block' =>	'block',
            'Level' =>	'level',
            'Part' =>	'part',
            'Part/Zone' =>	'part',
            'Serial Number' =>	'serial_number',
            'Unit Nos' =>	'unit_nos',
            'Unit' =>	'unit_nos',
            'Unit Type' =>	'unit_type',
            'Module Type' => 'module_type',
            'PBU Type' =>	'pbu_type',
            'Pbu Name' =>	'pbu_name',
            'Element Type' =>	'pbu_type',
            'Element Name' =>	'pbu_name',
            'QR Code ID' =>	'pbu_name',
            'Level/Unit' => 'Level/Unit',
        );
        foreach($data as $i => $params){
            $html = "";
            $pdf->AddPage('L', 'A7');
            $model_id = $params['modelId'];
            $uuid = $params['uuid'];
            $entityId = $params['entityId'];
            $type = $params['type'];
            $version = $params['version'];
            $name = $params['name'];
            $block = $params['floor'];
            $detail_list = RevitComponent::pbuInfo($program_id,$uuid);
            if(count($detail_list)>0){
                $block = $detail_list[0]['block'];
                if(strlen($block) > 30){
                    $block = substr($block,0,30).'..';
                }
                $element_type = $detail_list[0]['pbu_type'];
                if(strlen($element_type) > 30){
                    $element_type = substr($element_type,0,30).'..';
                }
                $element_name = $detail_list[0]['pbu_name'];
                if(strlen($element_name) > 30){
                    $element_name = substr($element_name,0,30).'..';
                }
                $element_part = $detail_list[0]['part'];
                if(strlen($element_part) > 30){
                    $element_part = substr($element_part,0,30).'..';
                }
                $unit_type = $detail_list[0]['unit_type'];
                if(strlen($unit_type) > 30){
                    $unit_type = substr($unit_type,0,30).'..';
                }
                $unit_nos = $detail_list[0]['unit_nos'];
                if(strlen($unit_nos) > 30){
                    $unit_nos = substr($unit_nos,0,30).'..';
                }
                $pbu_type = $detail_list[0]['pbu_type'];
                if(strlen($pbu_type) > 30){
                    $pbu_type = substr($pbu_type,0,30).'..';
                }
                $level_unit = $detail_list[0]['level'];
//                if($detail_list[0]['unit_nos']){
//                    $level_unit.='-'.$detail_list[0]['unit_nos'];
//                }
                if(strlen($level_unit) > 30){
                    $level_unit = substr($level_unit,0,30).'..';
                }
            }else{
                $block = '';
                $element_type = '';
                $element_name = '';
                $element_part = '';
                $level_unit = '';
                $unit_type = '';
            }
            $filename = self::createQr($params,$program_id);
            foreach ($params['properties'] as $m => $n){
                if($n['group'] == 'Constraints'){
                    if($block == '' && $n['key'] == 'Reference Level'){
                        $block = $n['value'];
                    }else if($block == '' && $n['key'] == 'Level'){
                        $block = $n['value'];
                    }else if($block == '' && $n['key'] == 'Base Constraint'){
                        $block = $n['value'];
                    }
                }
            }
//            $pdf->Image($cms, 84, 60, 20, 5, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $title = "<h2 style=\"font-size: 200% \" align=\"center\">$program_name</h2>";
            //如果没有定制
            if(count($content_list)>0){
                $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">";
                $count = count($content_list);
                foreach($content_list as $i => $j){
                    $name = $j['name'];
                    $fixed = '';
                    //按照绑定属性
                    if($j['status'] == '1'){
                        //属性在数据库中
                        if($local[$j['fixed']]){
                            //构件存在数据库中
                            if(count($detail_list)>0){
                                if($local[$j['fixed']] == 'Level/Unit'){
                                    $fixed = $detail_list[0]['level'];
                                    if($detail_list[0]['unit_nos']){
                                        $fixed.='-'.$detail_list[0]['unit_nos'];
                                    }
                                }else{
                                    $fixed = $detail_list[0][$local[$j['fixed']]];
                                }
                            }else{
                                $fixed = '';
                            }
                        }else{
                            if($params[$j['fixed']]){
                                $fixed = $params[$j['fixed']];
                            }else{
                                foreach ($params['properties'] as $m => $n){
                                    if($n['key'] == $j['fixed']){
                                        $fixed = $n['value'];
                                    }
                                }
                            }
                        }
                    }else{
                        $name = $j['name'];
                        if($local[$j['name']]){
                            //数据库已存在该构件
                            if(count($detail_list)>0){
                                $fixed = $detail_list[0][$local[$j['name']]];
                                if(strlen($fixed) > 30){
                                    $fixed = substr($fixed,0,30).'..';
                                }
                            }else{
                                $fixed = $j['fixed'];
                            }

                        }
                    }
                    //每个属性最多显示30个字符
                    if(strlen($fixed) > 30){
                        $fixed = substr($fixed,0,30).'..';
                    }
                    if($i == 0){
                        $html.="<tr>
  	                            <td  align=\"left\" height=\"20px\" width=\"25%\" >$name</td>
  	                            <td  align=\"center\" width=\"42%\" >$fixed</td>
  	                            <td rowspan=\"$count\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
                            </tr>";
                    }else{
                        $html.="<tr>
  	                            <td  align=\"left\" height=\"20px\" width=\"25%\" >$name</td>
  	                            <td  align=\"center\" width=\"42%\" >$fixed</td>
                            </tr>";
                    }
                }
                $html.="</table><br><div style=\"text-align:right\"><img src=\"$cms_1\" height=\"20\" width=\"65\" /></div>";
            }else{
                $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">
                    <tr>
  	                    <td  align=\"left\" height=\"20px\" width=\"25%\" >Block</td>
  	                    <td  align=\"center\" width=\"42%\" >$block</td>
  	                    <td rowspan=\"6\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Level</td>
  	                    <td  align=\"center\" width=\"42%\" >$level_unit</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Part/Zone</td>
  	                    <td  align=\"center\" width=\"42%\" >$element_part</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Unit</td>
  	                    <td  align=\"center\" width=\"42%\" >$unit_nos</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >PBU Type</td>
  	                    <td  align=\"center\" width=\"42%\" >$pbu_type</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >QR Code ID</td>
  	                    <td  align=\"center\" width=\"42%\" >$element_name</td>
                    </tr>
                </table><br><div style=\"text-align:right\"><img src=\"$cms_1\" height=\"20\" width=\"65\" /></div>";
            }

            $pdf->writeHTML($title, true, false, true, false, '');
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        //输出PDF
//        $pdf->Output($pdf_title, 'D');
//        $pdf->Output($pdf_title, 'I');
        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function downloadNomodelPdf($data,$program_id,$pbu_tag){
        $pro_model = Program::model()->findByPk($program_id);
        $program_id = $pro_model->root_proid;
        $program_name = $pro_model->program_name;
        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_tmp_path'] . '/' . $data[0] . '.pdf';
//        $full_dir = Yii::app()->params['upload_tmp_path'] . '/' .$data[0]['modelId'];
//        if(!file_exists($full_dir))
//        {
//            umask(0000);
//            @mkdir($full_dir, 0777, true);
//        }
        $title = Yii::t('proj_project_user', 'pdf_title');
        $header_title = Yii::t('proj_project_user','header_title');

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A7', true, 'UTF-8', false);
        //        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $_SESSION['title'] = $header_title; // 把username存在$_SESSION['user'] 里面
        // 设置页眉和页脚信息
//        $pdf->SetHeaderData('', 0, '', $header_title, array(0, 64, 255), array(0, 64, 128));
//        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 设置页眉和页脚字体

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(1, 1, 1);
        $pdf->setCellPaddings(1,1,1,1);
        $pdf->SetHeaderMargin(1);
        $pdf->SetFooterMargin(1);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 1);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
        }
        $cms = 'img/RF.jpg';
        $cms_1 = 'https://shell.cmstech.sg/test/idd/img/RF.jpg';
        $content_list = ModelQr::QueryQr($program_id);
        $local = array(
            'Uuid' =>	'pbu_id',
            'Block' =>	'block',
            'Level' =>	'level',
            'Part/Zone' =>	'part',
            'Part' =>	'part',
            'Serial Number' =>	'serial_number',
            'Unit' =>	'unit_nos',
            'Unit Nos' =>	'unit_nos',
            'Unit Type' =>	'unit_type',
            'Module Type' => 'module_type',
            'Pbu Type' =>	'pbu_type',
            'Pbu Name' =>	'pbu_name',
            'Element Type' =>	'pbu_type',
            'Element Name' =>	'pbu_name',
            'Level/Unit' => 'Level/Unit',
        );
        if($pbu_tag == '1'){
            $pbu_type_str = '	PBU Type';
        }else if($pbu_tag == '2'){
            $pbu_type_str = 'PPVC Type';
        }else{
            $pbu_type_str = 'Precast Type';
        }
        foreach($data as $i => $id){
            $pbu_model = RevitComponent::model()->findByPk($id);
            $html = "";
            $pdf->AddPage('L', 'A7');
            $name = $pbu_model->pbu_name;
            if($pbu_model){
                $block = $pbu_model->block;
                if(strlen($block) > 30){
                    $block = substr($block,0,30).'..';
                }
                $element_type = $pbu_model->pbu_type;
                if(strlen($element_type) > 30){
                    $element_type = substr($element_type,0,30).'..';
                }
                $element_name = $pbu_model->pbu_name;
                if(strlen($element_name) > 30){
                    $element_name = substr($element_name,0,30).'..';
                }
                $element_part = $pbu_model->part;
                if(strlen($element_part) > 30){
                    $element_part = substr($element_part,0,30).'..';
                }
                if($element_part == ''){
                    $element_part = 'NA';
                }
                $level_unit = $pbu_model->level;
//                if($pbu_model->unit_nos){
//                    $level_unit.='-'.$pbu_model->unit_nos;
//                }
                $unit_nos = $pbu_model->unit_nos;
                if(strlen($unit_nos) > 30){
                    $unit_nos = substr($unit_nos,0,30).'..';
                }
                if($unit_nos == ''){
                    $unit_nos = 'NA';
                }
                $pbu_type = $pbu_model->pbu_type;
                if(strlen($pbu_type) > 30){
                    $pbu_type = substr($pbu_type,0,30).'..';
                }
                if(strlen($level_unit) > 30){
                    $level_unit = substr($level_unit,0,30).'..';
                }
            }else{
                $block = '';
                $element_type = '';
                $element_name = '';
                $element_part = '';
                $level_unit = '';
            }
            $filename = self::createNoModelQr($id,$program_id);
//            $pdf->Image($cms, 84, 60, 20, 5, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $title = "<h2 style=\"font-size: 200% \" align=\"center\">$program_name</h2>";
            //如果没有定制
            $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">
                    <tr>
  	                    <td  align=\"left\" height=\"20px\" width=\"25%\" >Block</td>
  	                    <td  align=\"center\" width=\"42%\" >$block</td>
  	                    <td rowspan=\"6\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Level</td>
  	                    <td  align=\"center\" width=\"42%\" >$level_unit</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Part/Zone</td>
  	                    <td  align=\"center\" width=\"42%\" >$element_part</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Unit</td>
  	                    <td  align=\"center\" width=\"42%\" >$unit_nos</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >$pbu_type_str</td>
  	                    <td  align=\"center\" width=\"42%\" >$pbu_type</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >QR Code ID</td>
  	                    <td  align=\"center\" width=\"42%\" >$element_name</td>
                    </tr>
                </table><br><div style=\"text-align:right\"><img src=\"$cms_1\" height=\"20\" width=\"65\" /></div>";
//            if(count($content_list)>0){
//                $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">";
//                $count = count($content_list);
//                foreach($content_list as $i => $j){
//                    $name = $j['name'];
//                    $fixed = '';
//                    //按照绑定属性
//                    if($j['status'] == '1'){
//                        //属性在数据库中
//                        if($local[$j['fixed']]){
//                            //构件存在数据库中
//                            if($pbu_model){
//                                if($local[$j['fixed']] == 'Level/Unit'){
//                                    $fixed = $pbu_model->level;
//                                    if($pbu_model->unit_nos){
//                                        $fixed.='-'.$pbu_model->unit_nos;
//                                    }
//                                }else{
//                                    $fixed = $pbu_model->$local[$j['fixed']];
//                                }
//                            }else{
//                                $fixed = '';
//                            }
//                        }
//                    }else{
//                        $name = $j['name'];
//                        $fixed = $j['fixed'];
//                    }
//                    //每个属性最多显示30个字符
//                    if(strlen($fixed) > 30){
//                        $fixed = substr($fixed,0,30).'..';
//                    }
//                    if($i == 0){
//                        $html.="<tr>
//  	                            <td  align=\"left\" height=\"20px\" width=\"25%\" >$name</td>
//  	                            <td  align=\"center\" width=\"42%\" >$fixed</td>
//  	                            <td rowspan=\"$count\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
//                            </tr>";
//                    }else{
//                        $html.="<tr>
//  	                            <td  align=\"left\" height=\"20px\" width=\"25%\" >$name</td>
//  	                            <td  align=\"center\" width=\"42%\" >$fixed</td>
//                            </tr>";
//                    }
//                }
//                $html.="</table><br><div style=\"text-align:right\"><img src=\"$cms_1\" height=\"20\" width=\"65\" /></div>";
//            }else{
//
//            }

            $pdf->writeHTML($title, true, false, true, false, '');
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        //输出PDF
//        $pdf->Output($pdf_title, 'D');
//        $pdf->Output($pdf_title, 'I');
        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }


    //下载PDF
    public static function downloadPdf1($data){

        $lang = "_en";
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_tmp_path'] . '/' . $data[0]. '.pdf';
//        $full_dir = Yii::app()->params['upload_tmp_path'] . '/' .$data[0]['modelId'];
//        if(!file_exists($full_dir))
//        {
//            umask(0000);
//            @mkdir($full_dir, 0777, true);
//        }
        $title = Yii::t('proj_project_user', 'pdf_title');
        $header_title = Yii::t('proj_project_user','header_title');

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
//        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf = new RfPdf('P', 'mm', 'A7', true, 'UTF-8', false);
        //        var_dump($pdf);
//        exit;
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        //$pdf->SetKeywords('PDF, LICEN');
        $_SESSION['title'] = $header_title; // 把username存在$_SESSION['user'] 里面
        // 设置页眉和页脚信息
//        $pdf->SetHeaderData('', 0, '', $header_title, array(0, 64, 255), array(0, 64, 128));
//        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 设置页眉和页脚字体

        if (Yii::app()->language == 'zh_CN') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->setHeaderFont(Array('droidsansfallback', '', '10')); //英文
        }

        $pdf->setFooterFont(Array('helvetica', '', '8'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(1, 1, 1);
        $pdf->setCellPaddings(1,1,1,1);
        $pdf->SetHeaderMargin(1);
        $pdf->SetFooterMargin(1);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 5);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        if (Yii::app()->language == 'zh_CN') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //中文
        } else if (Yii::app()->language == 'en_US') {
            $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文
        }
        $cms = 'img/RF.jpg';
        $cms_1 = 'https://shell.cmstech.sg/test/idd/img/RF.jpg';
        foreach($data as $i => $id){
            $pbu_model = RevitComponent::model()->findByPk($id);
            $html = "";
            $pdf->AddPage('L', 'A7');
            $model_id = $pbu_model->model_id;
            $program_id = $pbu_model->project_id;
            $pro_model = Program::model()->findByPk($program_id);
            $program_name = $pro_model->program_name;
            $element_type = $pbu_model->pbu_type;
            $element_name = $pbu_model->pbu_name;
            $element_part = $pbu_model->part;
            $version = $pbu_model->version;
            $unit_type = $pbu_model->unit_type;
            $level = $pbu_model->level;
            $block = $pbu_model->block;
            $level_unit = $pbu_model->level.'-'.$pbu_model->unit_nos;
            $filename = self::createPbuQr($id);
//            $pdf->Image($cms, 84, 60, 20, 5, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false);
            $title = "<h2 style=\"font-size: 200% \" align=\"center\">$program_name</h2>";
            $html.= "<table width=\"100%\" border=\"1\" cellpadding=\"4\">
                    <tr>
  	                    <td  align=\"left\" height=\"20px\" width=\"25%\" >Block</td>
  	                    <td  align=\"center\" width=\"42%\" >$block</td>
  	                    <td rowspan=\"6\" align=\"center\" width=\"33%\"><br><br><img src=\"$filename\" height=\"100\" width=\"100\" align=\"middle\"/></td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Part</td>
  	                    <td  align=\"center\" width=\"42%\" >$element_part</td>
                    </tr>
                   <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Level/Unit</td>
  	                    <td  align=\"center\" width=\"42%\" >$level_unit</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Unit Type</td>
  	                    <td  align=\"center\" width=\"42%\" >$unit_type</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Element Name</td>
  	                    <td  align=\"center\" width=\"42%\" >$element_name</td>
                    </tr>
                    <tr>
                        <td  align=\"left\" height=\"20px\" width=\"25%\" >Element Type</td>
  	                    <td  align=\"center\" width=\"42%\" >$element_type</td>
                    </tr>
                </table><br><div style=\"text-align:right\"><img src=\"$cms_1\" height=\"20\" width=\"65\" /></div>";
            $pdf->writeHTML($title, true, false, true, false, '');
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        //输出PDF
//        $pdf->Output($pdf_title, 'D');
//        $pdf->Output($pdf_title, 'I');
        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //生成压缩包
    public static function createZip($time_str){
        $filename = "/opt/www-nginx/web/filebase/tmp/".$time_str.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(7);
            $filepath_cnt = $redis->get('filepath_cnt');
            $x = 0;
            for($j=0;$j<=$filepath_cnt;$j++){
                $path = $redis->lPop('file-list');
                if (file_exists($path)) {
                    $file[$x] = $path;
                    $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $x++;
                }
            }

            $zip->close();
        }
        if(count($file) > 0){
            foreach ($file as $cnt => $path) {
                unlink($path);
            }
        }
        return $filename;
    }

    //生成压缩包
    public static function createPbuZip(){
        $time = time();
        $filename = "/opt/www-nginx/web/filebase/tmp/".$time.".zip";
        if (!file_exists($filename)) {
            $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
//                $zip->open($filename,ZipArchive::CREATE);//创建一个空的zip文件
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                //如果是Linux系统，需要保证服务器开放了文件写权限
                exit("文件打开失败!");
            }
            $redis = new Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->select(7);
            $filepath_cnt = $redis->get('pbuinfo_cnt');
            $x = 0;
            for($j=0;$j<=$filepath_cnt;$j++){
                $path = $redis->lPop('pbuinfo-list');
                if (file_exists($path)) {
                    $file[$x] = $path;
                    $zip->addFile($path, basename($path));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
                    $x++;
                }
            }

            $zip->close();
        }
        if(count($file) > 0){
            foreach ($file as $cnt => $path) {
                unlink($path);
            }
        }
        return $filename;
    }

    //查询二维码内容
    public static function QueryQr($program_id) {
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $sql = "SELECT * FROM bac_program_model_qr WHERE  program_id = '".$root_proid."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //保存二维码内容
    public static function SaveQr($json,$program_id) {
        $list = explode('@',$json);
        foreach($list as $i => $j){
            $data = explode(',',$j);
            $args[$i]['name'] = $data[0];
            $args[$i]['fixed'] = $data[1];
            $args[$i]['status'] = $data[2];
        }

        $trans = Yii::app()->db->beginTransaction();
        try {
            $sql = "delete from bac_program_model_qr where program_id = '".$program_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $re = $command->execute();
            foreach($args as $i => $j){
                if($j['name']){
                    $sub_sql = 'INSERT INTO bac_program_model_qr(name,fixed,program_id,status,record_time) VALUES(:name,:fixed,:program_id,:status,:record_time);';
                    $record_time = date('Y-m-d H:i:s', time());
                    $status= '0';
                    $command = Yii::app()->db->createCommand($sub_sql);
                    $command->bindParam(":name", $j['name'], PDO::PARAM_STR);
                    $command->bindParam(":fixed",$j['fixed'], PDO::PARAM_STR);
                    $command->bindParam(":program_id",$program_id, PDO::PARAM_INT);
                    $command->bindParam(":status",$j['status'], PDO::PARAM_STR);
                    $command->bindParam(":record_time",$record_time, PDO::PARAM_STR);
                    $rs = $command->execute();
                }
            }
            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }

        return $r;
    }
}
