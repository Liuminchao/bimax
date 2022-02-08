<?php
class ModelCommand extends CConsoleCommand
{
    //0,10 3-4 * * * php /opt/www-nginx/web/test/idd/protected/yiic model exportpbu
    //yiic 自定义命令类名称 动作名称 --参数1=参数值 --参数2=参数值 --参数3=参数值……
    //php /opt/www-nginx/web/test/idd/protected/yiic model exportpbu --param1='25' --param2='2310'
    //导出带模型的构件excel
    public function actionExportPbu($param1,$param2){
        $param1 = (int)$param1;
        $program_id = $param2;
        $pro_model = Program::model()->findByPk($program_id);
        $pro_name = $pro_model->program_name;
        //查询pbu_info表中已经有的构件
//        $pbu_info = RevitComponent::detailList($program_id,$model_id,$version);

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

        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->select(7);
//        var_dump($redis->ping());
        $info_list = $redis->get('info_list');
//        $info_list = '"[{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type 2_R-WT : WP_Type_R-WT_4600x2820","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a453"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_1550x1760","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a62e"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x2395","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a680"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x3010","level":"CLUSTER A_15TH STOREY","uuid":"013ba723-f0e4-40b0-9c4d-e68313f748ba-0013a68a"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_PC-KGW_with Opening : K1F-3R-GW1-H","level":"CLUSTER A_14TH STOREY","uuid":"3bacf0a4-5050-4a27-80ce-a432715fa8e7-0012ae4b"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Roof_Canopy L shaped : WP_Roof_Canopy_3010&2545","level":"CLUSTER A_15TH STOREY","uuid":"a26374ad-ef75-47b3-ba25-f2a22bbf636b-0015acfd"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x2895","level":"CLUSTER A_15TH STOREY","uuid":"b9abea52-c0f2-4b3c-9048-20b3f1a8ba94-0013c61f"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x2895","level":"CLUSTER A_15TH STOREY","uuid":"b9abea52-c0f2-4b3c-9048-20b3f1a8ba94-0013c620"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x3875","level":"CLUSTER A_15TH STOREY","uuid":"bc9b118b-dff2-4fe9-8c7d-878a90288afd-0013c52a"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-ST-SG_4600x4865","level":"CLUSTER A_15TH STOREY","uuid":"bc9b118b-dff2-4fe9-8c7d-878a90288afd-0013c5c4"},{"model":"TMN6C17-18_WP_C_AR_A-641B_FACADE","model_id":"5f16657e63f9e300118f6751","version":1,"name":"WP_Type_R-WT_4600x3450","level":"CLUSTER A_15TH STOREY","uuid":"dd548d59-d00f-41a6-954c-e71488fc24cd-0013a770"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StCol-C_Rectangular_641B : LR Col (NO SCHEDULE)_250x1960","level":"LOWER ROOF LEVEL","uuid":"2791df73-2058-4a1a-94e4-12466d5a9ab2-000fa674"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WHB34_250x800","level":"LOWER ROOF LEVEL","uuid":"392a47b4-be5a-4c48-8bf3-d4840ce47dca-000c9760"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WHB34a_250x800","level":"LOWER ROOF LEVEL","uuid":"392a47b4-be5a-4c48-8bf3-d4840ce47dca-000c97da"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : RPT3_250x500","level":"LOWER ROOF LEVEL","uuid":"4437760a-1194-43c5-8f5c-39b2fe0dd7bd-00107c20"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WVB6_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c92e5"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WVB8_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c9329"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WHB38_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c9420"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : WVB8a_250x800","level":"LOWER ROOF LEVEL","uuid":"484090f9-2ff3-4e74-b99f-e4747dd35ad6-000c9631"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 3 : Roof_Stump_Type3","level":"LOWER ROOF LEVEL","uuid":"5f5e50b7-6c90-466e-bfa3-fab00d9c4dc2-000ce557"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : RPT5_250x500","level":"LOWER ROOF LEVEL","uuid":"76f92e70-4a27-48ab-b217-ff3470670ad8-000c307a"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_StBeam-CIS_Concrete - Rectangular_641B : RPT6_250x500","level":"LOWER ROOF LEVEL","uuid":"76f92e70-4a27-48ab-b217-ff3470670ad8-000c3237"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"9aae9287-3a12-409d-aaf6-eda8f57cb781-000ce767"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"9aae9287-3a12-409d-aaf6-eda8f57cb781-000ce779"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"bc2a9b05-1727-416b-9822-552e588b9112-000ce486"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"bc2a9b05-1727-416b-9822-552e588b9112-000ce496"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WP_PC_Roof_Stump_Type 1,2,4 & 7 : Roof Stump_Type1","level":"LOWER ROOF LEVEL","uuid":"bc2a9b05-1727-416b-9822-552e588b9112-000ce4c6"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"WATER TANK_PRECAST 1 RING : WP_PC_Water Tank","level":"LOWER ROOF LEVEL","uuid":"ca94b050-0c42-4753-9382-7de2cdae31ba-000cd1da"},{"model":"TMN6C17-18_WP_C_CS_A-641B_LR","model_id":"5f168c9d63f9e300118f67b1","version":1,"name":"Floor : CIS_250mm THK","level":"LOWER ROOF LEVEL","uuid":"df5e7891-d62a-47d6-8d2b-2d59683c86b8-001066fc"},{"model":"TMN6C17-18_WP_C_CS_A-641B_PBU","model_id":"5f168d0863f9e300118f67bc","version":1,"name":"HL_CS_PBU_3R","level":"CLUSTER A_14TH STOREY","uuid":"262b815b-6e94-43e5-ad9e-dd7dea468e03-00142e6e"}]"';

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','Submission Of Monthly Manpower Usage');
        $objectPHPExcel->getActiveSheet()->getStyle( 'A1')->getFont()->setBold(true);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('1')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('2')->setWidth(40);
        $objStyleA1 = $objActSheet->getStyle('A1');
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//        $objectPHPExcel->getActiveSheet()->getStyle('A1'.':'.'I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(11);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(110);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'A4');
        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A2','ModelId');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(110);
        $objectPHPExcel->getActiveSheet()->mergeCells('B2'.':'.'B4');
        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B2','Guid');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('C2'.':'.'C4');
        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','Block');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('D2'.':'.'D4');
        $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2','Level');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('E2'.':'.'E4');
        $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2','Unit nos.');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('F2'.':'.'E4');
        $objectPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2','Part');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('G2'.':'.'G4');
        $objectPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2','Unit Type');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('H2'.':'.'H4');
        $objectPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2','Serial Number');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('I2'.':'.'I4');
        $objectPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2','Element Type');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('J2'.':'.'J4');
        $objectPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2','Element Name');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('K2'.':'.'K4');
        $objectPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2','Module Type');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->mergeCells('L2'.':'.'L4');
        $objectPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L2','Precast Plant');

        $export_list = PbuExport::detailList($program_id);
        $export_id = $export_list[0]['export_id'];
        $export_detail = PbuExportDetail::detailList($export_id);
        $total = 1;
        foreach($export_detail as $i => $j){
            if($j['col'] > 'L'){
                if($total < 14){
                    $header = chr(77+$total);
                }else{
                    $y = $total / 14;
                    $header = chr(64 + $y).chr($total%14 + 65);
                }

                $objectPHPExcel->getActiveSheet()->getColumnDimension($header)->setWidth(50);
                $objectPHPExcel->getActiveSheet()->mergeCells($header.'2'.':'.$header.'4');
                $objectPHPExcel->getActiveSheet()->getStyle($header.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objectPHPExcel->getActiveSheet()->setCellValue($header.'2',$j['value']);
            }
        }

        $index = 5;
//        var_dump($info_list);
//        var_dump($redis->ttl('info_list'));
//        exit;
        $info = json_decode($info_list,'true');

        $detail_list = ModelQr::QueryQr($program_id);

        $detail['Block'] = 'C';
        $detail['Level'] = 'D';
        $detail['Unit nos.'] = 'E';
        $detail['Part'] = 'F';
        $detail['Unit Type'] = 'G';
        $detail['Serial Number'] = 'H';
        $detail['PBU Type'] = 'I';
        $detail['Element Name'] = 'J';
        $detail['Module Type'] = 'K';
        $detail['Precast Plant'] = 'L';
        $local = array(
            'Uuid' =>	'pbu_id',
            'Block' =>	'block',
            'Level' =>	'level',
            'Part' =>	'part',
            'Serial Number' =>	'serial_number',
            'Unit Nos' =>	'unit_nos',
            'Unit Type' =>	'unit_type',
            'Module Type' => 'module_type',
            'Pbu Type' =>	'pbu_type',
            'Pbu Name' =>	'pbu_name',
            'Element Type' =>	'pbu_type',
            'Element Name' =>	'pbu_name',
            'Level/Unit' => 'Level/Unit',
        );

        foreach($info as $i => $params){
            $tag = 0;
            $model_name = $params['model'];
            $model_id = $params['model_id'];
            $uuid = $params['uuid'];
            $version = $params['version'];
            $arr = array(
                'x-access-token:'.$rs['data']['token']
            );
            $url = "https://bim.cmstech.sg/api/v1/entity/$uuid?modelId=".$model_id."&version=".$version;

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

            $params_info = $r['data'];

            $name = $params['name'];
            $level = $params['level'];
            if($params['block']){
                $block = $params['block'];
            }else{
                $block = '';
            }
            if($params['unit_nos']){
                $unit_nos = $params['unit_nos'];
            }else{
                $unit_nos = '';
            }
            if($params['unit_type']){
                $unit_type = $params['unit_type'];
            }else{
                $unit_type = '';
            }
            if($params['element_type']){
                $name = $params['element_type'];
            }
            if($params['serial_no']){
                $serial_no = $params['serial_no'];
            }else{
                $serial_no = '';
            }
            if($params['element_name']){
                $pbu_name = $params['element_name'];
            }else{
                $pbu_name = '';
            }
            if($params['module_type']){
                $module_type = $params['module_type'];
            }else{
                $module_type = '';
            }
            if($params['precast_plant']){
                $precast_plant = $params['precast_plant'];
            }else{
                $precast_plant = '';
            }
            $model = RevitComponent::model()->find('project_id=:project_id and model_id=:model_id and version=:version and pbu_id=:pbu_id',array(':project_id'=>$program_id,':model_id'=>$model_id,':pbu_id'=>$uuid,':version'=>$version));
            if($model){
                $tag = 1;
                $objActSheet->setCellValue('A'.$index,$model->model_id.'_'.$model->version);
                $objActSheet->setCellValue('B'.$index,$model->pbu_id);
                $objActSheet->setCellValue('C'.$index,$model->block);
                $objActSheet->setCellValue('D'.$index,$model->level);
                $objActSheet->setCellValue('E'.$index,$model->unit_nos);
                $objActSheet->setCellValue('F'.$index,$model->part);
                $objActSheet->setCellValue('G'.$index,$model->unit_type);
                $objActSheet->setCellValue('H'.$index,$model->serial_number);
                if($model->pbu_type){
                    $objActSheet->setCellValue('I'.$index,$model->pbu_type);
                }else{
                    $objActSheet->setCellValue('I'.$index,$name);
                }
                $objActSheet->setCellValue('J'.$index,$model->pbu_name);
                $objActSheet->setCellValue('K'.$index,$model->module_type);
                $objActSheet->setCellValue('L'.$index,$model->precast_plant);
            }
            if($tag == 0){
                $objActSheet->setCellValue('A'.$index,$model_id.'_'.$version);
                $objActSheet->setCellValue('B'.$index,$uuid);
                $objActSheet->setCellValue('C'.$index,$block);
                $objActSheet->setCellValue('D'.$index,$level);
                $objActSheet->setCellValue('E'.$index,$unit_nos);
                $objActSheet->setCellValue('F'.$index,'');
                $objActSheet->setCellValue('G'.$index,$unit_type);
                $objActSheet->setCellValue('H'.$index,$serial_no);
                $objActSheet->setCellValue('I'.$index,$name);
                $objActSheet->setCellValue('J'.$index,$pbu_name);
                $objActSheet->setCellValue('K'.$index,$module_type);
                $objActSheet->setCellValue('L'.$index,$precast_plant);
            }
            foreach ($detail_list as $t => $j){
                if(array_key_exists($j['name'],$detail)){
                    $fixed_val = '';
                    $letter = $detail[$j['name']];
                    if($j['status'] == '1' && !array_key_exists($j['fixed'],$local)){
                        if(array_key_exists($j['fixed'],$params_info)){
                            $fixed_val = $params_info[$j['fixed']];
                        }else{
                            foreach ($params_info['properties'] as $m => $n){
                                if($n['key'] == $j['fixed']){
                                    $fixed_val = $n['value'];
                                }
                            }
                        }
                        $objActSheet->setCellValue($letter.$index,$fixed_val);
                    }else if($j['status'] == '0'){
                        $fixed_val = $j['fixed'];
                        $objActSheet->setCellValue($letter.$index,$fixed_val);
                    }

                }
            }
            $index++;
        }
//        $objActSheet->setCellValue('A3',$model_id);
//        $objActSheet->setCellValue('B3',$model_name);
//        $objActSheet->setCellValue('C3',$version);
        $redis->del('model_list');
        $redis->del('info_list');
        $redis->close();
        //导出
        $rand = mt_rand(10,100);
        $filename = $model_id.'_'.$rand;
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( 'Content-Disposition: attachment;filename="' . $filename . '.xls"' ); //"'.$filename.'.xls"
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' ); //在内存中准备一个excel2003文件
        $filepath = '/opt/www-nginx/web/filebase/tmp/'.$filename.'.xls';
        $objWriter->save($filepath);
        $sql = "update task_model_list set path='".$filepath."',status = '1' where id = '".$param1."' ";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();
        echo 'success';
    }
}