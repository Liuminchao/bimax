<?php

/**
 * 质量检查
 * @author LiuMinchao
 */
class QaCheck extends CActiveRecord {

    //状态：0-进行中，1－已接收，2-已拒绝。
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_DRAFT = '-1'; //已接收
    const STATUS_FINISH = '1'; //结束
    const RESULT_YES = 0;
    const RESULT_NO = 2;
    const RESULT_NA = 1;

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_checklist_record';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_ONGOING => Yii::t('comp_qa', 'STATUS_ONGOING'),
            self::STATUS_DRAFT => Yii::t('comp_qa', 'STATUS_ONGOING'),
            self::STATUS_FINISH => Yii::t('comp_qa','STATUS_ASSESS'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_ONGOING => 'bg-info', //进行中
            self::STATUS_DRAFT => 'bg-info', //已接收
            self::STATUS_FINISH => 'bg-success', //已审批
        );
        return $key === null ? $rs : $rs[$key];
    }
    //检查条件
    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => '√',
            self::RESULT_NO => '×',
            self::RESULT_NA => 'N/A',
        );
        return $key == null ? $rs : $rs[$key];
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
//        $condition = 'root_proid != "" AND root_proname !="" ';
//        $condition = 'root_proid != "" ';
        $condition = '';
        $params = array();

        //申请人
        if($args['apply_name'] !=''){
            $model = Staff::model()->find('user_name=:user_name',array(':user_name'=>$args['apply_name']));
            if($model) {
                $initiator = $model->user_id;
                $condition.= ( $condition == '') ? ' t.apply_user_id =:apply_user_id ' : ' AND t.apply_user_id =:apply_user_id';
                $params['apply_user_id'] = $initiator;
            }else{
                $condition.= ( $condition == '') ? ' t.apply_user_id =:apply_user_id ' : ' AND t.apply_user_id =:apply_user_id';
                $params['apply_user_id'] = '';
            }
        }
        //Apply
        if ($args['check_id'] != '') {
            $condition.= ( $condition == '') ? ' t.check_id=:check_id ' : ' AND t.check_id=:check_id';
            $params['check_id'] = $args['check_id'];
        }
        if ($args['program_id'] != '') {
            $pro_model =Program::model()->findByPk($args['program_id']);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' t.project_id =:program_id' : ' AND t.project_id =:program_id';
                $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id ' : ' AND t.contractor_id =:contractor_id ';
                $root_proid = $pro_model->root_proid;
                $params['program_id'] = $root_proid;
//                $params['program_id'] = $args['program_id'];
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' t.project_id =:program_id' : ' AND t.project_id =:program_id';
                $params['program_id'] = $args['program_id'];
            }
        }else{
            $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $program_list = Program::McProgramList($args);
            $key_list = array_keys($program_list);
            $program_id = $key_list[0];
            $pro_model =Program::model()->findByPk($program_id);
            //分包项目
            if($pro_model->main_conid != $args['contractor_id']){
                $condition.= ( $condition == '') ? ' t.project_id =:program_id' : ' AND t.project_id =:program_id';
                $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id ' : ' AND t.contractor_id =:contractor_id ';
                $params['program_id'] = $program_id;
                $params['contractor_id'] = $args['contractor_id'];
            }else{
                //总包项目
                $condition.= ( $condition == '') ? ' t.project_id =:program_id' : ' AND t.project_id =:program_id';
                $params['program_id'] = $program_id;
            }
        }
        //clt_type
        if ($args['clt_type'] != ''){
            $condition.= ( $condition == '') ? ' t.clt_type =:clt_type ' : ' AND t.clt_type =:clt_type';
            $params['clt_type'] = $args['clt_type'];
        }
        //Contractor
        if ($args['con_id'] != ''){
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id ' : ' AND t.contractor_id =:contractor_id';
            $params['contractor_id'] = $args['con_id'];
        }
        //Apply Time
//        if ($args['record_time'] != '') {
//            $args['record_time'] = Utils::DateToCn($args['record_time']);
//            $condition.= ( $condition == '') ? ' apply_time LIKE :record_time' : ' AND apply_time LIKE :record_time';
//            $params['record_time'] = '%'.$args['record_time'].'%';
//        }
        //操作开始时间
        if ($args['start_date'] != '') {
            $condition.= ( $condition == '') ? ' t.apply_time >=:start_date' : ' AND t.apply_time >=:start_date';
            $params['start_date'] = Utils::DateToCn($args['start_date']);
        }
        //操作结束时间
        if ($args['end_date'] != '') {
            $condition.= ( $condition == '') ? ' t.apply_time <=:end_date' : ' AND t.apply_time <=:end_date';
            $params['end_date'] = Utils::DateToCn($args['end_date']) . " 23:59:59";
        }

        $condition.= " AND t.status not in ('-1','9') ";
//        //关闭时间(起始)
//        if ($args['start_date'] != ''){
//            $condition.= ( $condition == '') ? ' apply_time >=:start_date ' : ' AND apply_time >=:start_date';
//            $params['start_date'] = $args['start_date'];
//        }
//        //关闭时间(截止)
//        if ($args['end_date'] != ''){
//            $condition.= ( $condition == '') ? ' apply_time <=:end_date ' : ' AND apply_time <=:end_date';
//            $params['end_date'] = $args['end_date'];
//        }

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 't.apply_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        //Type_id
        if($args['type_id'] != '' && $args['form_id'] == ''){
            $criteria->join = 'RIGHT JOIN qa_form_data b ON b.check_id=t.check_id and b.type_id = "'.$args['type_id'].'" ';
        }
        if($args['form_id'] != ''){
            $criteria->join = 'RIGHT JOIN qa_form_data b ON t.check_id=b.check_id and b.type_id = "'.$args['type_id'].'" and b.form_id = "'.$args['form_id'].'"';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $total_num = QaCheck::model()->count($criteria); //总记录数
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
//        var_dump($criteria);
        $rows = QaCheck::model()->findAll($criteria);

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
    public static function queryListByTask($page, $pageSize, $args = array()) {
//        $condition = 'root_proid != "" AND root_proname !="" ';
//        $condition = 'root_proid != "" ';
        $condition = '';
        $params = array();

        //Apply
        if ($args['check_id'] != '') {
            $condition.= ( $condition == '') ? ' t.check_id=:check_id ' : ' AND t.check_id=:check_id';
            $params['check_id'] = $args['check_id'];
        }

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 't.apply_time desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;

        $criteria->condition = $condition;
        $criteria->params = $params;
        $total_num = QaCheck::model()->count($criteria); //总记录数
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
//        var_dump($criteria);
        $rows = QaCheck::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 个人违规查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByUser($page, $pageSize, $args = array()) {
        //var_dump($args);
        $condition = '';
        $params = array();

        //Apply
        if ($args['check_id'] != '') {
            $condition .= 'check_id IN ('.$args['check_id'].')';
            //$params['program_id'] = '('.$args['program_id'].')';
        }

        //Contractor
        if ($args['contractor_id'] != ''){
            $condition.= ( $condition == '') ? ' contractor_id =:contractor_id ' : ' AND contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        $total_num = SafetyCheck::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'apply_time desc';
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
        //var_dump($criteria);
        $rows = SafetyCheck::model()->findAll($criteria);
//        var_dump($rows);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //查询安全单
    public static function detailList($check_id){

        $sql = "SELECT * FROM qa_checklist_record WHERE  check_id = '".$check_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }


    //修改路径
    public static function updatePath($check_id,$save_path) {
        $save_path = substr($save_path,18);
        $sql = "update qa_checklist_record set save_path = '".$save_path."' where check_id = '".$check_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();
    }

    //下载PDF
    public static function downloadPdf($params,$app_id){
        $check_id = $params['check_id'];
        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $block = $record[0]['block']; //一级区域
        $secondary_region = $record[0]['secondary_region']; //二级区域
        $project_name = $record[0]['project_name']; //项目名称
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $data_list = QaFormData::detailList($check_id);
        $record_detail = QaCheckDetail::detailRecord($check_id);//详情
        $staff_list = Staff::userAllList();

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objStyleA1 = $objActSheet->getStyle('A1');
        $objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        $objectPHPExcel->getActiveSheet()->getStyle('A1'.':'.'I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        $objectPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1',Yii::t('proj_project_user','program_name'));
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('B1',$project_name);
        $objectPHPExcel->getActiveSheet()->getStyle('B1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->mergeCells('A2'.':'.'B2');
        $objectPHPExcel->getActiveSheet()->getStyle('A2'.':'.'B2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->setCellValue('A2',$record[0]['title']);
        $objectPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A3','Work Locaion');
        $objectPHPExcel->getActiveSheet()->setCellValue('B3',$record[0]['block'].' '.$record[0]['secondary_region']);
        $objectPHPExcel->getActiveSheet()->mergeCells('A4'.':'.'A8');
        $objectPHPExcel->getActiveSheet()->getStyle('A4'.':'.'A8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//        $objectPHPExcel->getActiveSheet()->getStyle('B4'.':'.'B8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A4','Drawing Location');

        if($record[0]['drawing_pic']){
            $pic = explode('|', $record[0]['drawing_pic']);
            $num=1;
            $x_tag = 30;
            foreach ($pic as $key => $content) {
                if(file_exists($content)) {
                    $a = chr(65+$num);
                    $objectPHPExcel->getActiveSheet()->mergeCells($a.'4'.':'.$a.'8');
//                    $num++;
                    /*实例化excel图片处理类*/
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
//                        var_dump($content);
//                        exit;
                    $objDrawing->setPath($content);

                    /*设置图片高度*/
                    $objDrawing->setHeight(130);
                    /*设置图片宽度*/
                    $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                    /*设置图片要插入的单元格*/
                    $objDrawing->setCoordinates($a .'4');
                    /*设置图片所在单元格的格式*/
                    $objDrawing->setOffsetX($x_tag);//30
                    $x_tag = $x_tag+100;
                    $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                    $objDrawing->getShadow()->setVisible(true);
                    $objDrawing->getShadow()->setDirection(20);//20
                    $objDrawing->setWorksheet($objActSheet);
                }
            }
        }
        //写入数据
        $count = count($data_list);
        $checklist_total_count = $count+9-1;
        if($count > 0){
            $objectPHPExcel->getActiveSheet()->mergeCells('A9'.':'.'A'.$checklist_total_count);
        }

        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A9','Checklist');
        $start = 9;
        foreach($data_list as $k => $v){
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$start,$v['form_title']);
            $start++;
        }
        $remark_tag = $checklist_total_count+1;
        $objectPHPExcel->getActiveSheet()->mergeCells('A'.$remark_tag.':'.'B'.$remark_tag);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$remark_tag,'Photos & Remarks');

        $normal_tag = $remark_tag + 1;
        foreach($record_detail as $i => $j){
//            var_dump($j['pic']);
            $record_time = Utils::DateToEn($j['record_time']);
            $apply_user_name = $staff_list[$j['user_id']];
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . $normal_tag,'Date：'.$record_time);
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($normal_tag+1),'Name：'.$apply_user_name);
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($normal_tag+2),'Remarks：'.$j['remarks']);
            $objectPHPExcel->getActiveSheet()->mergeCells('A'.($normal_tag+3).':'.'A'.($normal_tag+6));
            $x_tag = 30;
            if($j['pic']){
                $pic = explode('|', $j['pic']);
                $num=1;
                foreach ($pic as $key => $content) {
                    if(file_exists($content)) {
                        $a = chr(65+$num);
                        $objectPHPExcel->getActiveSheet()->mergeCells($a.$normal_tag.':'.$a.($normal_tag + 6));
//                        $num++;
                        /*实例化excel图片处理类*/
                        $objDrawing = new PHPExcel_Worksheet_Drawing();
                        /*设置图片路径:只能是本地图片*/
//                        var_dump($value['field']);
//                        var_dump($content);
//                        exit;
                        $objDrawing->setPath($content);

                        /*设置图片高度*/
                        $objDrawing->setHeight(130);
                        /*设置图片宽度*/
                        $objDrawing->setWidth(80);
//                        //自适应
//                        $objDrawing->setResizeProportional(true);
                        /*设置图片要插入的单元格*/
                        $objDrawing->setCoordinates('B' . $normal_tag);
                        /*设置图片所在单元格的格式*/
                        $objDrawing->setOffsetX($x_tag);//30
                        $x_tag=$x_tag+100;
                        $objDrawing->setOffsetY(5);
//                                $objDrawing->setRotation(40);//40
                        $objDrawing->getShadow()->setVisible(true);
                        $objDrawing->getShadow()->setDirection(20);//20
                        $objDrawing->setWorksheet($objActSheet);
                    }
                }
                $normal_tag++;
            }
            $normal_tag = $normal_tag+6;
        }
        //        foreach($detail as $key => $val){
//            $user_model = Staff::model()->findByPk($val['user_id']);
//            $contractor_id = $user_model->contractor_id;
//            $con_model = Contractor::model()->findByPk($contractor_id);
//
//            if($val['deal_type'] == '1'){
//
//            }else if ($val['deal_type'] == '2'){
//
//            }else if($val['deal_type'] == '3'){
//
//            }
//        }
        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Report Overview -'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    //下载PDF
    public static function downloaddefaultPDF($params,$app_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $pbu_name = TaskRecordModel::QaByModel($check_id);
        $item = QaForm::itemAry($form_id);
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $comment_detail = QaFormDataReal::detailList($check_id,$data_id); //步骤
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        $pic_count = 1;
        $index = 0;

        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_signature_path = $signature_path;
                    if(file_exists($prepare_signature_path)) {
                        $prepare_signature = '<img src="'.$prepare_signature_path.'" height="85" width="95"  />';
                    }else{
                        $prepare_signature = '';
                    }
                    $prepare_user_name =$user_name;
                    $prepare_contractor_name = $contractor_name;
                    $prepare_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                }
            }
            $checked_tag = 0;
            if($v['deal_type'] == '91'){
                $checked_signature_path =$signature_path;
                if(file_exists($checked_signature_path)) {
                    $checked_signature = '<img src="'.$checked_signature_path.'" height="85" width="95"  />';
                }else{
                    $checked_signature = '';
                }
                $checked_user_name =$user_name;
                $checked_contractor_name = $contractor_name;
                $checked_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
            }
//            if($v['deal_type'] == '93'){
//                $rto_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
//            }
            if($v['deal_type'] == '7'){
                $re_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            $approved_tag = 0;
            if($v['deal_type'] == '4'){//93
                $approved_signature_path =$signature_path;
                if(file_exists($approved_signature_path)) {
                    $approved_signature = '<img src="'.$approved_signature_path.'" height="85" width="95"  />';
                }else{
                    $approved_signature = '';
                }
                $approved_user_name =$user_name;
                $approved_contractor_name = $contractor_name;
                $approved_record_time = substr(Utils::DateToEn($v['record_time']),0,11);

            }
//            if($v['form_data']){
//                var_dump($v['form_data']);
//                var_dump($v['record_time']);
//            }

            if($v['deal_type'] == '4' && $v['data_id'] == $data_id){
                if($index == 0 && $v['form_data'] != NULL){
                    $approved_data[1]['data'] = json_decode($v['form_data'],true);
                }
                if($index == 1 && $v['form_data'] != NULL){
                    $approved_data[2]['data'] = json_decode($v['form_data'],true);
                }
                if($index >1 && $v['form_data'] != NULL){
                    $approved_data[3]['data'] = json_decode($v['form_data'],true);
                }
                $index++;
            }
        }
//        var_dump($approved_data);
//        exit;
        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }

        foreach($item as $item_id => $data_info){
            $group_name = $data_info['group_name'];
            $item_list['item_id'] = $item_id;
            $item_list['item_title'] = $data_info['item_title'];
            $item_data[$group_name][] = $item_list;
        }

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $program_id = $record[0]['project_id'];
        $main_conid = $record[0]['contractor_id'];
        $project_name = $record[0]['project_name']; //项目名称
        $contractor_name = $record[0]['contractor_name'];
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $con_logo = 'img/1661.png';

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($record[0]['apply_time'], 0, 4);//年
        $month = substr($record[0]['apply_time'], 5, 2);//月
        $day = substr($record[0]['apply_time'],8,2);//日
        $hours = substr($record[0]['apply_time'],11,2);//小时
        $minute = substr($record[0]['apply_time'],14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid.'/' . $check_id  . $data_id .'.pdf';

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 5, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $logo_img= '<img src="'.$con_logo.'" height="70" width="100"  />';
        $unchecked_img = 'img/wrong.png';
        $checked_img = 'img/right_1.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $ref_no = $form_data[1]['item_value'];
        $form_no = $form_data[0]['item_value'];
        $header = "<table width=\"100%\" >
                    <tr>
  	                    <td rowspan=\"4\" align=\"left\" style=\"height: 50px;\"></td>
  	                    <td align=\"right\" style=\"height: 11px;\">$contractor_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">$program_name</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">Ref No: $ref_no</td>
                    </tr>
                    <tr>
                        <td align=\"right\" style=\"height: 11px;\">Form No: $form_no</td>
                    </tr>
                </table>";
        $storey = $form_data[2]['item_value'];
        $element = $form_data[3]['item_value'];
        $draw = $form_data[4]['item_value'];
        $notice_date = substr(Utils::DateToEn($form_data[5]['item_value']),0,11);
        $inspection_date = substr(Utils::DateToEn($form_data[6]['item_value']),0,11);
        $info_html ="<h2 align=\"center\">$data_title</h2><br><table>";
        foreach($item_data as $group_name => $res) {
            if ($group_name == 'Information') {
                foreach ($res as $i => $v) {
                    foreach($data['Information'] as $l => $list){
                        if($v['item_id'] == $list['item_id']){
                            $item_title = $v['item_title'];
                            $item_value = $list['item_value'];
                            $info_html.="<tr><td   width='35%' >$item_title </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$item_value</td></tr>";
                        }
                    }
                }
            }
        }
        $info_html.="<tr><td   width='35%' >Element Name: </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$pbu_name</td></tr>";
        $info_html.="</table>";

        $detail_html ="<br><table border=\"1\">";
        $detail_html.="<tr><td   width=\"10%\" align=\"center\">Item </td><td colspan=\"2\" width=\"60%\" >Details</td><td  width=\"10%\" align=\"center\">1st </td><td  width=\"10%\" align=\"center\">2nd</td><td  width=\"10%\" align=\"center\">3rd</td></tr>";

        $str = 65;
        $tmp = '';
        foreach($item_data as $group_name => $res){

            if($group_name != 'Information' && !strpos($group_name,'emarks')){
                $tag = chr($str);
                $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$tag</td><td colspan=\"2\" width=\"60%\" >$group_name</td><td  colspan=\"3\"  align=\"center\"> </td></tr>";
                $str++;
                $u = 0;
                foreach ($res as $i => $v) {


                    $pic_1 = '';
                    $pic_2 = '';
                    $pic_3 = '';

                    $title = $v['item_title'];
                    //第一次检查
                    if($approved_data[1]['data']){
                        foreach($approved_data[1]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_1 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_1 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_1 = '';
                                }else{
                                    $pic_1 = '';
                                }
                                $item_value_1 = $w['item_value'];
                            }
                        }
                    }

                    //第二次检查
                    if($approved_data[2]['data']){
                        foreach($approved_data[2]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_2 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_2 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_2 = '';
                                }else{
                                    $pic_2 = '';
                                }
                            }
                        }
                    }

                    //第三次检查
                    if($approved_data[3]['data']){
                        foreach($approved_data[3]['data'] as $q => $w){
                            if($w['item_id'] == $v['item_id']){
                                if($w['rs_approval'] == 'Y'){
                                    $pic_3 = $checked_img_html;
                                }else if($w['rs_approval'] == 'N'){
                                    $pic_3 = $unchecked_img_html;
                                }else if($w['rs_approval'] == 'NA'){
                                    $pic_3 = '';
                                }else{
                                    $pic_3 = '';
                                }
                            }
                        }
                    }
                    $o = 0;
                    $x = 0;
                    for($t=0;$t<strlen($title);$t++){
                        $c=substr($title,$t,1);
                        if($o == 0){
                            if (!preg_match('/[a-zA-Z]/',$c)){
                                $x++;
                            }else{
                                $o = 1;
                            }
                        }
                    }
                    $title = substr($title,$x);
                    $u++;
                    $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"30%\" >$title  </td><td  width=\"30% \" align=\"center\">$item_value_1</td><td  width=\"10%\" align=\"center\">$pic_1 </td><td  width=\"10%\" align=\"center\">$pic_2</td><td  width=\"10%\" align=\"center\">$pic_3</td></tr>";
                }
            }
            if(strpos($group_name,'emarks')){
                foreach ($res as $i => $v) {
                    $remarks = $v['item_value'].'<br>';
                }
            }
        }

        $detail_html.="</table>";

//        foreach($detail as $x => $y){
//            if($y['data_id'] == $data_id){
//                if($y['remark']){
//                    $remarks = $y['remark'].'<br>';
//                }
//            }
//        }
        $remark_html = "<h3 align=\"left\">Remarks:</h3><br><table border=\"1\">";
        $remark_html.="<tr><td style=\"height: 60px;\">$remarks</td></tr>";
        $remark_html.="</table>";

        $small_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="80px" width="80px" />';
        $img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="120px" width="120px" />';
        $big_img = '<img src="https://pics2.baidu.com/feed/359b033b5bb5c9eaaab3b4d1ef506a063af3b334.jpeg?token=1a7bca72167ff5ee78826f3302ecc094" height="160px" width="160px" />';
        $progress_list = "<table width=\"100%\"  cellpadding=\"8\" >";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">Prepared By: </td><td style=\"border-width: 1px;border-color:white white gray white\">Reviewed By: </td><td style=\"border-width: 1px;border-color:white white gray white\">Approved By: </td></tr>";
//        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"border-width: 1px;border-color:white white gray white\">China Construction</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_user_name</td></tr>";
        $progress_list.="<tr><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray;height:80px;\"><br>$prepare_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br>$checked_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\"><br>$approved_signature</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_record_time</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td></tr>";
        $progress_list.="</table>";

        $pdf->writeHTML($header.$info_html, true, true, true, false, '');
        $pdf->writeHTML($detail_html, true, true, true, false, '');
        $pdf->writeHTML($remark_html, true, true, true, false, '');
        $pdf->writeHTML($progress_list, true, true, true, false, '');
        $detail_header = "<h3 align=\"center\">Comments && Photos</h3>";
        $pdf->writeHTML($detail_header, true, true, true, false, '');
        if($comment_detail){
            foreach($comment_detail as $i => $j){
                $comment_data[$j['item_id']][] = $j;
            }
            foreach($comment_data as $item_id => $item_data){
                $pic_before = '';
                $pic_after = '';

                $item_model = QaForm::model()->findByPk($item_id);
                $item_no = $item_model->item_title;
                foreach($item_data as $i => $j){
                    //判断 根据图片的张数拼接  1张 big_img   2张 img  3张或者4张 small_img
                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==0){
                        $before = explode('|',$j['pic']);
                        $before_count = count($before);
                        $before_content = '';
                        $remark_before = $j['remark'];
                        $user_before = Staff::model()->findByPk($j['user_id']);
                        $user_name_before = $user_before->user_name;
                        $before_time = substr($j['record_time'],0,10);
                        if($before_count == 1){
                            $before_img_1 = '<img src="'.$before[0].'" height="160px" width="160px" />';
                            $before_content = $before_img_1;
                        }else if($before_count == 2){
                            $before_img_1 = '<img src="'.$before[0].'" height="120px" width="120px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="120px" width="120px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2;
                        }else if($before_count == 3){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80px" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;';
                        }else if($before_count >= 4){
                            $before_img_1 = '<img src="'.$before[0].'" height="80px" width="80spx" />';
                            $before_img_2 = '<img src="'.$before[1].'" height="80px" width="80px" />';
                            $before_img_3 = '<img src="'.$before[2].'" height="80px" width="80px" />';
                            $before_img_4 = '<img src="'.$before[3].'" height="80px" width="80px" />';
                            $before_content = $before_img_1.'&nbsp;'.$before_img_2.'<br><br>'.$before_img_3.'&nbsp;'.$before_img_4;
                        }
                    }

                    if($j['pic'] != '' && $j['pic'] != '-1' && $i%2==1){
                        $after = explode('|',$j['pic']);
                        $after_count = count($after);
                        $after_content = '';
                        $remark_after = $j['remark'];
                        $user_after = Staff::model()->findByPk($j['user_id']);
                        $user_name_after = $user_after->user_name;
                        $after_time = substr($j['record_time'],0,10);
                        if($after_count == 1){
                            $after_img_1 = '<img src="'.$after[0].'" height="160px" width="160px" />';
                            $after_content = $after_img_1;
                        }else if($after_count == 2){
                            $after_img_1 = '<img src="'.$after[0].'" height="120px" width="120px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="120px" width="120px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2;
                        }else if($after_count == 3){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80px" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;';
                        }else if($after_count >= 4){
                            $after_img_1 = '<img src="'.$after[0].'" height="80px" width="80spx" />';
                            $after_img_2 = '<img src="'.$after[1].'" height="80px" width="80px" />';
                            $after_img_3 = '<img src="'.$after[2].'" height="80px" width="80px" />';
                            $after_img_4 = '<img src="'.$after[3].'" height="80px" width="80px" />';
                            $after_content = $after_img_1.'&nbsp;'.$after_img_2.'<br><br>'.$after_img_3.'&nbsp;'.$after_img_4;
                        }
                    }

                    if($i%2==1){
                        $detail_list="<table width=\"100%\" border=\"1\"><tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\">$item_no</td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
                        $detail_list.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$before_content</td><td align=\"center\"  style=\"width: 50%\">$after_content </td></tr>";
                        $detail_list.="<tr><td style=\"width: 50%\">$remark_before</td><td style=\"width: 50%\">$remark_after</td></tr>";
                        $detail_list.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\">$user_name_before  $before_time</td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\">$user_name_after  $after_time</td></tr>";
                        $detail_list.="</table>";
                        $pdf->writeHTML($detail_list, true, true, true, false, '');
                    }
                }
            }
        }


//        $detail_list_1 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_1.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\"><br>$img &nbsp; $img </td><td align=\"center\"  style=\"width: 50%\">$img &nbsp; $img </td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_1.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_1.="</table>";
//
//        $detail_list_2 = "<h3 align=\"center\">Comments && Photos</h3><table width=\"100%\" border=\"1\">";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Item No: </td><td style=\"width: 80%\"></td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\" align=\"center\">Before</td><td style=\"width: 50%\" align=\"center\">After</td></tr>";
//        $detail_list_2.="<tr><td align=\"center\"  style=\"height:200px;width: 50%\">$big_img </td><td align=\"center\"  style=\"width: 50%\">$big_img </td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 50%\">this is content...</td><td style=\"width: 50%\">this is content...</td></tr>";
//        $detail_list_2.="<tr><td style=\"width: 20%\" align=\"center\">Issued By: </td><td style=\"width: 30%\"></td><td style=\"width: 20%\" align=\"center\">Rectified By: </td><td style=\"width: 30%\"></td></tr>";
//        $detail_list_2.="</table>";

//        $pdf->writeHTML($detail_list_1, true, true, true, false, '');
//        $pdf->writeHTML($detail_list_2, true, true, true, false, '');
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == '1'){
                $pdf->Output($filepath, 'I');
            }
            if($params['tag'] == '2'){
                $pdf->Output($filepath, 'F');
                Utils::Download($filepath, $data_title, 'pdf');
            }
        }else{
            $pdf->Output($filepath, 'I');
        }


//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function downloadwsfPDF($params,$app_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $form_list = QaChecklist::formList();
        $form_title = $form_list[$form_id];
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_signature_path = $signature_path;
                    if(file_exists($prepare_signature_path)) {
                        $prepare_signature = '<img src="'.$prepare_signature_path.'" height="85" width="95"  />';
                    }else{
                        $prepare_signature = '';
                    }
                    $prepare_user_name =$user_name;
                    $prepare_contractor_name = $contractor_name;
                    $prepare_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                }
            }
            $checked_tag = 0;
            if($v['deal_type'] == '91'){
                $checked_signature_path =$signature_path;
                if(file_exists($checked_signature_path)) {
                    $checked_signature = '<img src="'.$checked_signature_path.'" height="85" width="95"  />';
                }else{
                    $checked_signature = '';
                }
                $checked_user_name =$user_name;
                $checked_contractor_name = $contractor_name;
                $checked_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            if($v['deal_type'] == '93'){
                $rto_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            if($v['deal_type'] == '7'){
                $re_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            $approved_tag = 0;
            if($v['deal_type'] == '4'){
                $approved_signature_path =$signature_path;
                if(file_exists($approved_signature_path)) {
                    $approved_signature = '<img src="'.$approved_signature_path.'" height="85" width="95"  />';
                }else{
                    $approved_signature = '';
                }
                $approved_user_name =$user_name;
                $approved_contractor_name = $contractor_name;
                $approved_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
            }
        }
        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = $record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $program_id = $record[0]['project_id'];
        $main_conid = $record[0]['contractor_id'];
        $project_name = $record[0]['project_name']; //项目名称
        $contractor_name = $record[0]['contractor_name'];
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $con_logo = 'img/wsf.png';

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($record[0]['apply_time'], 0, 4);//年
        $month = substr($record[0]['apply_time'], 5, 2);//月
        $day = substr($record[0]['apply_time'],8,2);//日
        $hours = substr($record[0]['apply_time'],11,2);//小时
        $minute = substr($record[0]['apply_time'],14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid.'/TBM' . $check_id  . $data_id .'.pdf';

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 5, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('droidsansfallback', '', 10, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $logo_img= '<img src="'.$con_logo.'" height="50" width="80"  />';
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $rev_no = $form_data[1]['item_value'];
        $form_no = $form_data[0]['item_value'];
        $date = $form_data[2]['item_value'];
        $location = $form_data[3]['item_value'];
        $doc_no = $form_data[4]['item_value'];
        $drawing = $form_data[5]['item_value'];
        $notice_date = substr(Utils::DateToEn($form_data[5]['item_value']),0,11);
        $inspection_date = substr(Utils::DateToEn($form_data[6]['item_value']),0,11);
        $header = "<table width=\"100%\" border=\"1\">
                    <tr>
  	                    <td rowspan=\"2\" align=\"center\"  style=\"height: 75px;width: 20%\"><br><br>$logo_img</td>
  	                    <td align=\"center\" rowspan=\"2\" style=\"height: 75px;width: 55%\"><br><br>$contractor_name<br>$form_title</td>
                        <td align=\"center\" colspan=\"2\" style=\"height: 40px;width: 25%\">$form_no</td>
                    </tr>
                    <tr>
                        <td align=\"center\" style=\"height: 35px;\">Rev.0</td>
                        <td align=\"center\" style=\"height: 35px;\">$rev_no</td>
                    </tr>
                    <tr>
                        <td align=\"left\" style=\"height: 25px;width: 65%\">Project:   $project_name</td>
                        <td align=\"left\" style=\"height: 25px;width: 35%\">Date:   $date</td>
                    </tr>
                    <tr>
                        <td align=\"left\" style=\"height: 25px;width: 65% \">Location/GL:   $location</td>
                        <td align=\"left\" style=\"height: 25px;width: 35% \">Doc. No.:   $doc_no</td>
                    </tr>
                    <tr>
                        <td align=\"left\" style=\"height: 25px;width: 100%\" >Drawing Reference:   <br>$drawing</td>
                    </tr>";

        $header.="<tr><td  align=\"left\" style=\"width:100%; \">The following items have been checked / verified: </td></tr>";

        $str = 65;
        $tmp = '';
        foreach($data as $group_name => $res){
            if($group_name != 'Form Information' && !strpos($group_name,'emarks') && $group_name != 'Information'){
                $tag = chr($str);
                $header.="<tr><td   width=\"10%\"  align=\"center\"  >$tag</td><td  width=\"60%\"  >$group_name</td><td  width=\"10% \" align=\"center\" >YES</td><td  width=\"10%\" align=\"center\"  >NO</td><td  width=\"10%\" align=\"center\"  >NA</td></tr>";
                $str++;
                $u = 0;
                foreach ($res as $i => $v) {
                    $pic_1 = $unchecked_img_html;
                    $pic_2 = $unchecked_img_html;
                    $pic_3 = $unchecked_img_html;

                    $title = $item[$v['item_id']]['item_title'];
                    $o = 0;
                    $x = 0;
                    for($t=0;$t<strlen($title);$t++){
                        $c=substr($title,$t,1);
                        if($o == 0){
                            if (!preg_match('/[a-zA-Z]/',$c)){
                                $x++;
                            }else{
                                $o = 1;
                            }
                        }
                    }
                    $title = substr($title,$x);
                    if($v['item_value'] == 'YES'){
                        $pic_1 = $checked_img_html;
                    }else if($v['item_value'] == 'NO'){
                        $pic_2 = $checked_img_html;
                    }else if($v['item_value'] == 'NA'){
                        $pic_3 = $checked_img_html;
                    }
                    $u++;
                    $header.="<tr><td   width=\"10%\"  align=\"center\" >$u</td><td  width=\"60%\"  >$title</td><td  width=\"10%\" align=\"center\"  >$pic_1 </td><td  width=\"10%\" align=\"center\"  >$pic_2</td><td  width=\"10%\" align=\"center\"  >$pic_3</td></tr>";
                }
            }
            if(strpos($group_name,'emarks')){
                foreach ($res as $i => $v) {
                    $remarks = $v['item_value'].'<br>';
                }
            }
        }
        $header.="<tr><td style=\"width: 100%\">Remarks:</td></tr>";
        $header.="<tr><td style=\"height: 60px;width: 100%\">$remarks</td></tr>";
        $header.="<tr><td style=\"width: 33%;border-width: 1px;border-color:white white white gray\">Prepared By: </td><td style=\"width: 34%;border-width: 1px;border-color:white white white white\">Reviewed By: </td><td style=\"width: 33%;border-width: 1px;border-color:white gray white white\">Approved By: </td></tr>";
        $header.="<tr><td style=\"width: 33%;border-width: 1px;border-color:white white gray gray\">China Construction</td><td style=\"width: 34%;border-width: 1px;border-color:white white gray white\">China Construction</td><td style=\"width: 33%;border-width: 1px;border-color:white gray gray white\">China Construction</td></tr>";
        $header.="<tr><td style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray\">$prepare_user_name</td><td style=\"width: 34%;border-width: 1px;border-color:gray gray gray gray\">$checked_user_name</td><td style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray\">$approved_user_name</td></tr>";
        $header.="<tr><td align=\"center\"  style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray;height:80px;\">$prepare_signature</td><td align=\"center\"  style=\"width: 34%;border-width: 1px;border-color:gray gray gray gray\">$checked_signature</td><td align=\"center\"  style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray\">$approved_signature</td></tr>";
        $header.="<tr><td style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray\">$prepare_record_time</td><td style=\"width: 34%;border-width: 1px;border-color:gray gray gray gray\">$checked_record_time</td><td style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray\">$approved_record_time</td></tr>";
        $header.="<tr><td style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"width: 34%;border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"width: 33%;border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td></tr>";
        $header.="</table>";
        $pdf->writeHTML($header, true, true, true, false, '');
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == '1'){
                $pdf->Output($filepath, 'I');
            }
            if($params['tag'] == '2'){
                $pdf->Output($filepath, 'F');
                Utils::Download($filepath, $data_title, 'pdf');
            }
        }else{
            $pdf->Output($filepath, 'I');
        }

//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //按项目查询安全检查次数（按类别分组）
    public static function AllCntList($args){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
//        var_dump($args['date']);
//        var_dump($month);
        //分包项目
        if($pro_model->main_conid != $args['contractor_id']){
            $sql = "select count(t.data_id) as cnt,t.project_id,t.form_id,li.form_name_en from (select * from qa_form_data where check_id in (select check_id from qa_checklist_record where project_id = '".$args['program_id']."' and contractor_id = '".$args['contractor_id']."')) as t LEFT JOIN qa_checklist as li ON li.form_id=t.form_id where t.record_time like '".$month."%' GROUP BY t.form_id ";
        }else{
            //总包项目
            $sql = "select count(t.data_id) as cnt,t.project_id,t.form_id,li.form_name_en from qa_form_data as t LEFT JOIN qa_checklist as li ON li.form_id=t.form_id where t.record_time like '".$month."%' and t.project_id ='".$args['program_id']."'  GROUP BY t.form_id";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['type_name'] = $list['form_name_en'];

            }
        }
        return $r;
//        var_dump($r);
//        exit;
    }

    //下载PDF
    public static function downloadflorencePDF_1($params,$app_id){
        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_id = $form_model->form_id;
        $checklist_model = QaChecklist::model()->findByPk($form_id);
        $form_title = $checklist_model->form_name_en;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);

        $pbu_name = TaskRecordModel::QaByModel($check_id);
        $item = QaForm::itemAry($form_id);
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_signature_path = $signature_path;
                    if(file_exists($prepare_signature_path)) {
                        $prepare_signature = '<img src="'.$prepare_signature_path.'" height="85" width="95"  />';
                    }else{
                        $prepare_signature = '';
                    }
                    $prepare_user_name =$user_name;
                    $prepare_contractor_name = $contractor_name;
                    $prepare_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                }
            }
            $checked_tag = 0;
            if($v['deal_type'] == '91'){
                $checked_signature_path =$signature_path;
                if(file_exists($checked_signature_path)) {
                    $checked_signature = '<img src="'.$checked_signature_path.'" height="85" width="95"  />';
                }else{
                    $checked_signature = '';
                }
                $checked_user_name =$user_name;
                $checked_contractor_name = $contractor_name;
                $checked_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            if($v['deal_type'] == '93'){
                $rto_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            if($v['deal_type'] == '7'){
                $re_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            $approved_tag = 0;
            if($v['deal_type'] == '4'){
                $approved_signature_path =$signature_path;
                if(file_exists($approved_signature_path)) {
                    $approved_signature = '<img src="'.$approved_signature_path.'" height="85" width="95"  />';
                }else{
                    $approved_signature = '';
                }
                $approved_user_name =$user_name;
                $approved_contractor_name = $contractor_name;
                $approved_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
            }
        }
        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = '  '.$record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $program_id = $record[0]['project_id'];
        $main_conid = $record[0]['contractor_id'];
        $project_name = $record[0]['project_name']; //项目名称
        $contractor_name = $record[0]['contractor_name'];
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $con_logo_1 = 'img/1261_1.png';
        $con_logo_2 = 'img/1261_2.png';

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($record[0]['apply_time'], 0, 4);//年
        $month = substr($record[0]['apply_time'], 5, 2);//月
        $day = substr($record[0]['apply_time'],8,2);//日
        $hours = substr($record[0]['apply_time'],11,2);//小时
        $minute = substr($record[0]['apply_time'],14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid.'/TBM' . $check_id  . $data_id .'.pdf';

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 5, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('times', '', 10, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $logo1_img= '<img src="'.$con_logo_1.'" height="60" width="120"  />';
        $logo2_img= '<img src="'.$con_logo_2.'" height="60" width="210"  />';
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $inspection_type_pub = $form_data[0]['item_value'];
        $request_no = $form_data[1]['item_value'];
        $block_floor = $form_data[2]['item_value'];
        $pbu_type = $form_data[3]['item_value'];
        $inspection_type = $form_data[4]['item_value'];
        $inspection_date = $form_data[5]['item_value'];
        $date = Utils::DateToEn(substr($inspection_date,0,11));
        $time = substr($inspection_date,11,8);
        $header = "<table width=\"100%\" border=\"1\">
                    <tr>
  	                    <td width=\"25%\"  align=\"left\" style=\"height: 70px;border-width: 1px;border-color:gray white white gray\"></td>
  	                    <td width=\"40%\"  align=\"left\" style=\"height: 70px;border-width: 1px;border-color:gray white white white\"></td>
  	                    <td width=\"35%\"  align=\"right\" style=\"height: 70px;border-width: 1px;border-color:gray gray white white\"></td>
                    </tr>
                    <tr>
                        <td style=\"border-width: 1px;border-color:white white gray gray\"></td>
                        <td style=\"border-width: 1px;border-color:white white gray white\" align=\"center\"><h2>$project_name</h2></td>
                        <td style=\"border-width: 1px;border-color:white gray gray white\"></td>
                    </tr>
                </table>";
        $info_html ="<table border=\"1\">";
        $info_html.="<tr><td colspan=\"4\" align=\"center\">Inspection Checklist For PBU Fit-out Workers</td></tr>";
        $info_html.="<tr><td colspan=\"4\" align=\"left\">REQUEST FOR INSPECTION FOR FIT-OUT WORKERS</td></tr>";
        $info_html.="<tr><td width=\"15%\">Inspection Type at PBU: </td><td width=\"45%\">$inspection_type_pub</td><td colspan=\"2\" width=\"40%\">Inspection Request No:  $request_no</td></tr>";
        $info_html.="<tr><td width=\"15%\">Element: </td><td colspan=\"3\" width=\"85%\">$pbu_name</td></tr>";
        $info_html.="<tr><td width=\"60%\" colspan=\"2\">BLOCK / FLOOR :  $block_floor<br>UNIT/ PBU TYPE:  $pbu_type</td>";
        if($inspection_type == '1st Inspection'){
            $info_html.="<td width=\"20%\" align=\"left\">$checked_img_html<br>1 st Inspection</td><td  width=\"20%\" align=\"left\">$unchecked_img_html<br>Re-Inspection</td></tr>";
        }else if($inspection_type == 'Re-inspection'){
            $info_html.="<td width=\"20%\" align=\"left\">$unchecked_img_html<br>1 st Inspection</td><td  width=\"20%\" align=\"left\">$checked_img_html<br>Re-Inspection</td></tr>";
        }else{
            $info_html.="<td width=\"20%\" align=\"left\">$unchecked_img_html<br>1 st Inspection</td><td  width=\"20%\" align=\"left\">$unchecked_img_html<br>Re-Inspection</td></tr>";
        }
        $info_html.="<tr><td colspan=\"4\" align=\"center\">The above is ready for inspection on $date at $time </td></tr>";
        $info_html.="</table>";

        $detail_html ="<br><table border=\"1\">";
        $detail_html.="<tr><td   width=\"5%\" align=\"center\">Item </td><td  width=\"50%\" >Details</td><td  width=\"5%\" style=\"border-color:gray white gray gray\" align=\"center\">YES </td><td  width=\"5%\" style=\"border-color:gray white gray white\" align=\"center\">NO</td><td  width=\"5%\" style=\"border-color:gray gray gray white\" align=\"center\">NA</td><td width=\"30%\" align=\"center\">Comment from<br>RE/RTO(if any)</td></tr>";

        $str = 65;
        $tmp = '';

        foreach($data as $group_name => $res){

            if($group_name != 'Information' && !strpos($group_name,'emarks' ) && !strpos($group_name,'OTHERS')){
                $tag = chr($str);
                $count = count($res)+1;
                $group_detail = QaForm::groupDetail($form_id,$group_name);
                $comment_list = QaFormItem::detailList($check_id,$data_id,$group_detail);
                if(count($comment_list)>0){
                    $comment = '';
                    foreach ($comment_list as $i => $j){
                        $comment.= $j['remark'].'<br>';
                    }
                }
                $detail_html.="<tr><td   width=\"5%\"  align=\"center\">$tag</td><td  width=\"50%\" ><b>$group_name</b></td><td  colspan=\"3\"  align=\"center\"> </td><td rowspan=\"$count\">$comment</td></tr>";
                $str++;
                $u = 0;
                foreach ($res as $i => $v) {
                    $item_list[] = $v['item_id'];
                    $pic_1 = $unchecked_img_html;
                    $pic_2 = $unchecked_img_html;
                    $pic_3 = $unchecked_img_html;

                    $title = $item[$v['item_id']]['item_title'];
                    $o = 0;
                    $x = 0;
                    for($t=0;$t<strlen($title);$t++){
                        $c=substr($title,$t,1);
                        if($o == 0){
                            if (!preg_match('/[a-zA-Z]/',$c)){
                                $x++;
                            }else{
                                $o = 1;
                            }
                        }
                    }
                    $title = '  '.substr($title,$x);
                    if($v['item_value'] == 'YES'){
                        $pic_1 = $checked_img_html;
                    }else if($v['item_value'] == 'NO'){
                        $pic_2 = $checked_img_html;
                    }else if($v['item_value'] == 'NA'){
                        $pic_3 = $checked_img_html;
                    }
                    $u++;
                    $detail_html.="<tr><td   width=\"5%\"  align=\"center\">$u</td><td  width=\"50%\" >$title</td><td  width=\"5%\" style=\"border-color:gray white gray gray\" align=\"center\">$pic_1 </td><td  width=\"5%\" style=\"border-color:gray white gray white\"  align=\"center\">$pic_2</td><td  width=\"5%\"  style=\"border-color:gray gray gray white\" align=\"center\">$pic_3</td></tr>";
                }
            }
            if(strpos($group_name,'emarks')){
                foreach ($res as $i => $v) {
                    $remarks = $v['item_value'].'<br>';
                }
            }
            if(strpos($group_name,'OTHERS')){
                foreach ($res as $i => $v) {
                    $others = $v['item_value'].'<br>';
                }
            }
        }

        $detail_html.="</table>";

//        foreach($detail as $x => $y){
//            if($y['data_id'] == $data_id){
//                if($y['remark']){
//                    $remarks = $y['remark'].'<br>';
//                }
//            }
//        }
        $remark_html = "<br><table border=\"1\">";
        $remark_html.="<tr><td style=\"height: 30px;border-color:gray gray white gray;\"  colspan=\"4\"></td></tr>";
        if($others == 'in order'){
            $remark_html.="<tr><td style=\"height: 40px;border-color:white white gray gray;\"  width=\"30%\">The above workers inspected are</td><td style=\"height: 60px;border-color:white white gray white;\" width=\"15%\" align=\"center\">$checked_img_html  in order</td><td style=\"height: 60px;border-color:white white gray white;\" width=\"15%\">$unchecked_img_html  not in order</td><td style=\"height:60px;border-color:white gray gray white;\" width=\"40%\">TAC or TKM to proceed to next stage of workers</td></tr>";
        }else if($others == 'not in order'){
            $remark_html.="<tr><td style=\"height: 40px;border-color:white white gray gray;\"  width=\"30%\">The above workers inspected are</td><td style=\"height: 60px;border-color:white white gray white;\" width=\"15%\" align=\"center\">$unchecked_img_html  in order</td><td style=\"height: 60px;border-color:white white gray white;\" width=\"15%\">$checked_img_html  not in order</td><td style=\"height:60px;border-color:white gray gray white;\" width=\"40%\">TAC or TKM to proceed to next stage of workers</td></tr>";
        }else{
            $remark_html.="<tr><td style=\"height: 40px;border-color:white white gray gray;\"  width=\"30%\">The above workers inspected are</td><td style=\"height: 60px;border-color:white white gray white;\" width=\"15%\" align=\"center\">$unchecked_img_html  in order</td><td style=\"height: 60px;border-color:white white gray white;\" width=\"15%\">$unchecked_img_html  not in order</td><td style=\"height:60px;border-color:white gray gray white;\" width=\"40%\">TAC or TKM to proceed to next stage of workers</td></tr>";
        }
        $remark_html.="</table>";
        $progress_list = "<table width=\"100%\" >";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white white gray\">Prepared By: </td><td style=\"border-width: 1px;border-color:white white white white\">Reviewed By: </td><td style=\"border-width: 1px;border-color:white gray white white\">Approved By: </td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_user_name</td></tr>";
        $progress_list.="<tr><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray;height:80px;\">$prepare_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_signature</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_record_time</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td></tr>";
        $progress_list.="</table>";
//        $pdf->writeHTML($info_html, true, true, true, false, '');
        $pdf->writeHTML($header.$info_html.$detail_html.$remark_html.$progress_list, true, true, true, false, '');
        $pdf->Image($con_logo_1, 17, 9, 35, '', 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Image($con_logo_2, 140, 9, 45, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->writeHTML($remark_html, true, true, true, false, '');
//        $pdf->writeHTML($progress_list, true, true, true, false, '');
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == '1'){
                $pdf->Output($filepath, 'I');
            }
            if($params['tag'] == '2'){
                $pdf->Output($filepath, 'F');
                Utils::Download($filepath, $data_title, 'pdf');
            }
        }else{
            $pdf->Output($filepath, 'I');
        }

//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //下载PDF
    public static function downloadflorencePDF_2($params,$app_id){

        $check_id = $params['check_id'];
        $data_id = $params['data_id'];
        $form_model = QaFormData::model()->findByPk($data_id);
        $form_model = QaFormData::model()->findByPk($data_id);
        $pbu_name = TaskRecordModel::QaByModel($check_id);
        $form_id = $form_model->form_id;
        $data_title = $form_model->form_title;
        $json_form_data = $form_model->form_data;
        $form_data = json_decode($json_form_data,true);
        $item = QaForm::itemAry($form_id);
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $prepare_count = 1;
        $checked_count = 1;
        $approved_count = 1;
        foreach($detail as $k => $v){
            $staff_info = Staff::model()->findByPk($v['user_id']);
            $contractor_id = $staff_info->contractor_id;
            $user_name = $staff_info->user_name;
            $signature_path = $staff_info->signature_path;
            $contractor_info = Contractor::model()->findByPk($contractor_id);
            $contractor_name = $contractor_info->contractor_name;
            $prepare_tag = 0;
            if($v['deal_type'] == '1'){
                if($prepare_count <= $v['step']){
                    $prepare_signature_path = $signature_path;
                    if(file_exists($prepare_signature_path)) {
                        $prepare_signature = '<img src="'.$prepare_signature_path.'" height="85" width="95"  />';
                    }else{
                        $prepare_signature = '';
                    }
                    $prepare_user_name =$user_name;
                    $prepare_contractor_name = $contractor_name;
                    $prepare_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
                }
            }
            $checked_tag = 0;
            if($v['deal_type'] == '91'){
                $checked_signature_path =$signature_path;
                if(file_exists($checked_signature_path)) {
                    $checked_signature = '<img src="'.$checked_signature_path.'" height="85" width="95"  />';
                }else{
                    $checked_signature = '';
                }
                $checked_user_name =$user_name;
                $checked_contractor_name = $contractor_name;
                $checked_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            if($v['deal_type'] == '93'){
                $rto_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            if($v['deal_type'] == '7'){
                $re_inspection_date = substr(Utils::DateToEn($v['record_time']),0,11);
            }
            $approved_tag = 0;
            if($v['deal_type'] == '4'){
                $approved_signature_path =$signature_path;
                if(file_exists($approved_signature_path)) {
                    $approved_signature = '<img src="'.$approved_signature_path.'" height="85" width="95"  />';
                }else{
                    $approved_signature = '';
                }
                $approved_user_name =$user_name;
                $approved_contractor_name = $contractor_name;
                $approved_record_time = substr(Utils::DateToEn($v['record_time']),0,11);
            }
        }
        foreach ($form_data as $i => $j){
            $group_name = $item[$j['item_id']]['group_name'];
            $data[$group_name][] = $j;
        }

        $args['contractor_id'] = Yii::app()->user->getState('contractor_id');
        $record = QaCheck::detailList($check_id); //记录
        $title = '  '.$record[0]['title']; //标题
        $block = $record[0]['block']; //一级区域
        $program_id = $record[0]['project_id'];
        $main_conid = $record[0]['contractor_id'];
        $project_name = $record[0]['project_name']; //项目名称
        $contractor_name = $record[0]['contractor_name'];
        $detail = QaCheckDetail::detailList($check_id); //步骤
        $record_detail = QaCheckDetail::detaildataRecord($check_id,$data_id);//详情
        $con_logo_1 = 'img/1261_1.png';
        $con_logo_2 = 'img/1261_2.png';

        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        $year = substr($record[0]['apply_time'], 0, 4);//年
        $month = substr($record[0]['apply_time'], 5, 2);//月
        $day = substr($record[0]['apply_time'],8,2);//日
        $hours = substr($record[0]['apply_time'],11,2);//小时
        $minute = substr($record[0]['apply_time'],14,2);//分钟
        $time = $day.$month.$year.$hours.$minute;

//        $filepath = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf' . '/TBM' . $id . '.pdf';
        $filepath = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid.'/TBM' . $check_id  . $data_id .'.pdf';

//        $full_dir = Yii::app()->params['upload_record_path'] . '/' . $year . '/' . $month . '/tbm/pdf';
        $full_dir = Yii::app()->params['upload_report_path'].'/pdf/'.$year.'/'.$month.'/'.$program_id.'/QA/'.$main_conid;
        if(!file_exists($full_dir))
        {
            umask(0000);
            @mkdir($full_dir, 0777, true);
        }
//        if (file_exists($filepath)) {
//            $show_name = $title;
//            $extend = 'pdf';
//            Utils::Download($filepath, $show_name, $extend);
//            return;
//        }
        $tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
        require_once($tcpdfPath);
//        Yii::import('application.extensions.tcpdf.TCPDF');
        $pdf = new RfPdf('P', 'mm', 'A4', true, 'UTF-8', false);
        // 设置文档信息
        $pdf->SetCreator(Yii::t('login', 'Website Name'));
        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);

//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $pdf->Image($logo_pic, 15, 0, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
//        $pdf->SetHeaderData($logo_pic, 20,  '',  '',array(0, 64, 255), array(0, 64, 128));

        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pro_model = Program::model()->findByPk($program_id);
        $program_name = $pro_model->program_name;
        $pro_params = $pro_model->params;//项目参数

        $main_model = Contractor::model()->findByPk($main_conid);
        $main_conid_name = $main_model->contractor_name;
        $logo_pic = '/opt/www-nginx/web'.$main_model->remark;

//        if($logo_pic){
//            $logo = '/opt/www-nginx/web'.$logo_pic;
//            $pdf->SetHeaderData($logo, 20, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }else{
//            $pdf->SetHeaderData('', 0, '', 'Meeting No.(会议编号): ' . $meeting->meeting_id,  array(0, 64, 255), array(0, 64, 128));
//        }

        //标题(许可证类型+项目)
//        $title_html = "<span style=\"font-size:40px\" align=\"center\">{$main_conid_name}</span><br/><h3 align=\"center\">Project (项目) : {$program_name}</h3><br/>";
        $pdf->Header($logo_pic);

        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->setCellPaddings(1,1,1,1);
        // 设置页眉和页脚字体

//        if (Yii::app()->language == 'zh_CN') {
//            $pdf->setHeaderFont(Array('stsongstdlight', '', '10')); //中文
//        } else if (Yii::app()->language == 'en_US') {
        $pdf->setHeaderFont(Array('droidsansfallback', '', '30')); //英文OR中文
//        }

        $pdf->setFooterFont(Array('helvetica', '', '10'));

        //设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');

        //设置间距
        $pdf->SetMargins(15, 5, 15);
        $pdf->SetFooterMargin(10);

        //设置分页
        $pdf->SetAutoPageBreak(TRUE, 25);
        //set image scale factor
        $pdf->setImageScale(1.25);
        //set default font subsetting mode
        $pdf->setFontSubsetting(true);
        //设置字体
        $pdf->SetFont('times', '', 10, '', true); //英文OR中文

        $pdf->AddPage();
        $pdf->SetLineWidth(0.1);

        $logo_img_1= '<img src="'.$con_logo_1.'" height="70" width="120"  />';
        $logo_img_2= '<img src="'.$con_logo_2.'" height="70" width="140"  />';
        $unchecked_img = 'img/checkbox_unchecked.png';
        $checked_img = 'img/checkbox_checked.png';
        $checked_img_html= '<img src="'.$checked_img.'" height="10" width="10" /> ';
        $unchecked_img_html= '<img src="'.$unchecked_img.'" height="10" width="10" /> ';
        $ref_no = $form_data[1]['item_value'];
        $form_no = $form_data[0]['item_value'];
        $header = "<table width=\"100%\" >
                    <tr>
  	                    <td rowspan=\"4\" align=\"left\" style=\"height: 50px;\">$logo_img_1</td>
  	                    <td rowspan=\"4\" align=\"right\" style=\"height: 50px;\">$logo_img_2</td>
                    </tr> 
                </table>";
        $storey = $form_data[2]['item_value'];
        $element = $form_data[3]['item_value'];
        $draw = $form_data[4]['item_value'];
        $notice_date = substr(Utils::DateToEn($form_data[5]['item_value']),0,11);
        $inspection_date = substr(Utils::DateToEn($form_data[6]['item_value']),0,11);
        $info_html ="<h2 align=\"center\">CHECKLIST FOR FINAL PPVC</h2><br><table>";
        $info_html.="<tr><td   width='35%' >Location / Storey: </td><td   width='65%' style=\"border-width: 1px;border-color:white white gray white\">$storey</td></tr>";
        $info_html.="<tr><td  width='35%' >Structural Element: </td><td  width='65%' style=\"border-width: 1px;border-color:white white gray white\">$element</td></tr>";
        $info_html.="<tr><td  width='35%' >Drawing No:  </td><td  width='65%' style=\"border-width: 1px;border-color:white white gray white\">$draw</td></tr>";
        $info_html.="<tr><td  width='35%' >Date/Time of Notification: </td><td  width='65%' style=\"border-width: 1px;border-color:white white gray white\">$notice_date</td></tr>";
        $info_html.="<tr><td  width='35%' >Date/Time Inspection Required: </td><td  width='65%' style=\"border-width: 1px;border-color:white white gray white\">$inspection_date</td></tr>";
        $info_html.="<tr><td  width='35%' >RE/RTO Inspection Date:  </td><td  width='65%' style=\"border-width: 1px;border-color:white white gray white\">$rto_inspection_date</td></tr>";
        $info_html.="<tr><td  width='35%' >Re-inspection Date: </td><td  width='65%' style=\"border-width: 1px;border-color:white white gray white\">$re_inspection_date</td></tr>";
        $info_html.="<tr><td  width='35%' >Element: </td><td  width='65%' style=\"border-width: 1px;border-color:white white gray white\">$pbu_name</td></tr>";
        $info_html.="</table>";

        $detail_html ="<br><table border=\"1\">";
        $detail_html.="<tr><td   width=\"10%\" align=\"center\">Item </td><td  width=\"60%\" >Details</td><td  width=\"10%\" align=\"center\">YES </td><td  width=\"10%\" align=\"center\">NO</td><td  width=\"10%\" align=\"center\">NA</td></tr>";

        $str = 65;
        $tmp = '';

        foreach($data as $group_name => $res){

            if($group_name != 'Information' && !strpos($group_name,'emarks')){
                $tag = chr($str);
                $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$tag</td><td  width=\"60%\" ><b>$group_name</b></td><td  colspan=\"3\"  align=\"center\"> </td></tr>";
                $str++;
                $u = 0;
                foreach ($res as $i => $v) {
                    $pic_1 = $unchecked_img_html;
                    $pic_2 = $unchecked_img_html;
                    $pic_3 = $unchecked_img_html;

                    $title = '  '.$item[$v['item_id']]['item_title'];
                    $o = 0;
                    $x = 0;
                    for($t=0;$t<strlen($title);$t++){
                        $c=substr($title,$t,1);
                        if($o == 0){
                            if (!preg_match('/[a-zA-Z]/',$c)){
                                $x++;
                            }else{
                                $o = 1;
                            }
                        }
                    }
                    $title = '  '.substr($title,$x);
                    if($v['item_value'] == 'YES'){
                        $pic_1 = $checked_img_html;
                        $u++;
                        $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"60%\" >$title</td><td  width=\"10%\" align=\"center\">$pic_1 </td><td  width=\"10%\" align=\"center\">$pic_2</td><td  width=\"10%\" align=\"center\">$pic_3</td></tr>";
                    }else if($v['item_value'] == 'NO'){
                        $pic_2 = $checked_img_html;
                        $u++;
                        $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"60%\" >$title</td><td  width=\"10%\" align=\"center\">$pic_1 </td><td  width=\"10%\" align=\"center\">$pic_2</td><td  width=\"10%\" align=\"center\">$pic_3</td></tr>";
                    }else if($v['item_value'] == 'NA'){
                        $pic_3 = $checked_img_html;
                        $u++;
                        $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"60%\" >$title</td><td  width=\"10%\" align=\"center\">$pic_1 </td><td  width=\"10%\" align=\"center\">$pic_2</td><td  width=\"10%\" align=\"center\">$pic_3</td></tr>";
                    }else{
                        $u++;
                        $item_value = $v['item_value'];
                        $detail_html.="<tr><td   width=\"10%\"  align=\"center\">$u</td><td  width=\"60%\" >$title</td><td  width=\"30%\" align=\"left\" colspan=\"3\">$item_value</td></tr>";
                    }
                }
            }
            if(strpos($group_name,'emarks')){
                foreach ($res as $i => $v) {
                    $remarks = $v['item_value'].'<br>';
                }
            }
        }

        $detail_html.="</table>";

//        foreach($detail as $x => $y){
//            if($y['data_id'] == $data_id){
//                if($y['remark']){
//                    $remarks = $y['remark'].'<br>';
//                }
//            }
//        }
        $remark_html = "<h3 align=\"left\">Remarks:</h3><br><table border=\"1\">";
        $remark_html.="<tr><td style=\"height: 60px;\">$remarks</td></tr>";
        $remark_html.="</table>";
        $progress_list = "<table width=\"100%\" >";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:white white white white\">Prepared By: </td><td style=\"border-width: 1px;border-color:white white white white\">Reviewed By: </td><td style=\"border-width: 1px;border-color:white white white white\">Approved By: </td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_user_name</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_user_name</td></tr>";
        $progress_list.="<tr><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray;height:80px;\">$prepare_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_signature</td><td align=\"center\"  style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_signature</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">$prepare_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$checked_record_time</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">$approved_record_time</td></tr>";
        $progress_list.="<tr><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td><td style=\"border-width: 1px;border-color:gray gray gray gray\">Name/Signature/Date</td></tr>";
        $progress_list.="</table>";
        $pdf->writeHTML($header.$info_html, true, true, true, false, '');
        $pdf->writeHTML($detail_html, true, true, true, false, '');
        $pdf->writeHTML($remark_html, true, true, true, false, '');
        $pdf->writeHTML($progress_list, true, true, true, false, '');
        //输出PDF
        if(array_key_exists('tag',$params)){
            if($params['tag'] == '1'){
                $pdf->Output($filepath, 'I');
            }
            if($params['tag'] == '2'){
                $pdf->Output($filepath, 'F');
                Utils::Download($filepath, $data_title, 'pdf');
            }
        }else{
            $pdf->Output($filepath, 'I');
        }

//        $pdf->Output($filepath, 'F');  //保存到指定目录
        return $filepath;
//============================================================+
// END OF FILE
//============================================================+
    }

    //按项目查询安全检查次数（按公司分组）
    public static function CompanyCntList($args){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        $month = Utils::MonthToCn($args['date']);
        $contractor_list = Contractor::compAllList();
//        var_dump($args['date']);
//        var_dump($month);

        //分包项目
        if($args['contractor_id'] != '' && $pro_model->main_conid != $args['contractor_id']){
            $root_proid = $pro_model->root_proid;
            $sql = "select count(check_id) as cnt,contractor_id from bac_routine_check  where  apply_time like '".$month."%' and root_proid ='".$root_proid."' and contractor_id = '".$args['contractor_id']."'  GROUP BY contractor_id";
        }else{
            //总包项目
            $sql = "select count(check_id) as cnt,contractor_id from bac_routine_check  where  apply_time like '".$month."%' and root_proid ='".$args['program_id']."'   GROUP BY contractor_id";
        }

        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;

        if(!empty($rows)){
            foreach($rows as $num => $list){
                $r[$num]['cnt'] = $list['cnt'];
                $r[$num]['contractor_name'] = $contractor_list[$list['contractor_id']];

            }
        }
        return $r;
//        var_dump($r);
//        exit;
    }
}
