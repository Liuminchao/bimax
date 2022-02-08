<?php

/**
 * 分包指定项目到化学物品
 * @author LiuMinChao
 */
class ProgramChemical extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    public $subcomp_name; //指派分包公司名

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_chemical';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'chemical_id' => Yii::t('chemical', 'chemical_id'),
            'chemical_name' => Yii::t('chemical', 'chemical_name'),
            'type_no' => Yii::t('chemical', 'chemical_type'),
            'chemical_img'=>Yii::t('chemical','chemical_img'),
            'permit_img'=>Yii::t('chemical','permit_img'),
            'chemical_content'=>Yii::t('chemical','chemical_content'),
            'record_time' =>Yii::t('chemical','record_time'),
            'permit_startdate' => Yii::t('chemical', 'permit_startdate'),
            'permit_enddate' => Yii::t('chemical', 'permit_enddate'),
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
            self::ENTRANCE_APPLY => Yii::t('proj_project_user', 'entrance_apply'),
            self::ENTRANCE_PENDING => Yii::t('proj_project_user', 'entrance_pending'),
            self::ENTRANCE_SUCCESS => Yii::t('proj_project_user', 'entrance_success'),
            self::LEAVE_PENDING => Yii::t('proj_project_user', 'leave_pending'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态
    public static function statusSubText($key = null) {
        $rs = array(
            self::ENTRANCE_PENDING => Yii::t('proj_project_user', 'entrance_pending'),
            self::ENTRANCE_SUCCESS => Yii::t('proj_project_user', 'entrance_success'),
            self::LEAVE_PENDING => Yii::t('proj_project_user', 'leave_pending'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::ENTRANCE_APPLY => 'label-warning',
            self::ENTRANCE_PENDING => 'label-default',
            self::ENTRANCE_SUCCESS => 'label-success',
            self::LEAVE_PENDING => 'label-danger',
        );
        return $key === null ? $rs : $rs[$key];
    }
    //查询某项目下人员(审核状态是-1，10，11，20)
    public static function queryByChemical($args = array()){
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        $exist_data = ProgramChemical::model()->findAll(
            array(
                'select'=>array('*'),
                'condition' => 'program_id=:program_id and check_status in (:entrance_apply,:entrance_pending,:entrance_success,:leave_pending) and contractor_id=:contractor_id',
                'params' => array(':program_id'=>$args['program_id'],':contractor_id'=>$args['contractor_id'],':entrance_apply'=>$entrance_apply,'entrance_pending'=>$entrance_pending,
                    ':entrance_success'=>$entrance_success,':leave_pending'=>$leave_pending
                ),
            )
        );
        return $exist_data;
    }
    //分页查询某项目下化学物品(审核状态是10，11，20)
    public static function querySubListByChemical($page, $pageSize, $args = array()) {

        $condition = '1=1';
        $params = array();

        //Program Id
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' t.program_id =:program_id' : ' AND t.program_id =:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id' : ' AND t.contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //ChemicalId
        if($args['chemical_id'] != ''){
            $sql = "select primary_id from bac_chemical where chemical_id like '%".$args['chemical_id']."%' ";
//            var_dump($sql);
//            exit;
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $i = '';
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $i.=$row['primary_id'].',';
                }
            }
            if ($i != '')
                $primary_id = substr($i, 0, strlen($i) - 1);
            $condition.= ( $condition == '') ? ' t.chemical_id IN ('.$primary_id.') ' : ' AND t.chemical_id IN ('.$primary_id.')';

        }
        //Check Status
        //Check Status
        if($args['status'] != ''){
            $condition.= ( $condition == '') ? ' t.check_status=:check_status' : ' AND t.check_status=:check_status';
            $params['check_status'] = $args['status'];
        }
        $total_num = ProgramChemical::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($args['chemical_id'] != '') {
            $order = 'field(t.check_status,-1,10,20,11)';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $order = 'chemical_id ASC';
        if($args['type_no'] != ''){
            $type_no = $args['type_no'];
            $criteria->join = "RIGHT JOIN bac_chemical b ON b.primary_id = t.chemical_id and b.type_no = '$type_no' ";
        }

        $criteria->select = 't.*';
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
//        var_dump($criteria);
        $rows = ProgramChemical::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //分页查询某项目下化学物品(审核状态是-1，10，11，20)
    public static function queryListByChemical($page, $pageSize, $args = array()) {

        $condition = '1=1';
        $params = array();

        //Program Id
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' t.program_id =:program_id' : ' AND t.program_id =:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' t.contractor_id =:contractor_id' : ' AND t.contractor_id =:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //ChemicalId
        if($args['chemical_id'] != ''){
            $sql = "select primary_id from bac_chemical where chemical_id like '%".$args['chemical_id']."%' ";
//            var_dump($sql);
//            exit;
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $i = '';
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $i.=$row['primary_id'].',';
                }
            }
            if ($i != '')
                $primary_id = substr($i, 0, strlen($i) - 1);
            $condition.= ( $condition == '') ? ' t.chemical_id IN ('.$primary_id.') ' : ' AND t.chemical_id IN ('.$primary_id.')';

        }
        //Check Status
        //Check Status
        if($args['status'] != ''){
            $condition.= ( $condition == '') ? ' t.check_status=:check_status' : ' AND t.check_status=:check_status';
            $params['check_status'] = $args['status'];
        }
        $total_num = ProgramChemical::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $order = 'chemical_id ASC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $order = 'field(t.check_status,-1,10,20,11)';
        if($args['type_no'] != ''){
            $type_no = $args['type_no'];
            $criteria->join = "RIGHT JOIN bac_chemical b ON b.primary_id = t.chemical_id and b.type_no = '$type_no' ";
        }
        $criteria->select = 't.*';
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = ProgramChemical::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //修改日志
    public static function updateLog($model) {
        $statusList = self::statusText();
        return array(
            $model->getAttributeLabel('program_id') => Yii::app()->db->lastInsertID,
            $model->getAttributeLabel('program_name') => $model->program_name,
            $model->getAttributeLabel('contractor_id') => $model->contractor_id,
            $model->getAttributeLabel('add_operator') => $model->add_operator,
            $model->getAttributeLabel('status') => $statusList[$model->status],
            $model->getAttributeLabel('record_time') => $model->record_time,
            // Yii::t('proj_project_user', 'Assign SC') => self::subcompText($model->program_id),
        );
    }

    //提交化学物品申请
    public static function SubmitApplicationsChemical($args) {
//        var_dump($args);
//        exit;
        if ($args['program_id'] == '') {
            $r['msg'] = Yii::t('proj_project_user', 'error_projid_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = Program::model()->findByPk($args['program_id']);
        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $program_id = $args['program_id'];
            $apply_user_id = Yii::app()->user->getState('contractor_id');
            $time = date('Y-m-d H:i:s');
            $status = self::STATUS_NORMAL;
            //勾选化学物品数组
            $new_list = (array)$args['sc_list'];
            //将新增化学物品置为入场待审批
            if(!empty($new_list)){
                foreach ($new_list as $key => $id) {
                    $programchemical_model = ProgramChemical::model()->find('program_id=:program_id AND chemical_id=:chemical_id', array(':program_id' => $program_id,':chemical_id'=>$id));
                    if($programchemical_model){
                        $sql = 'INSERT INTO bac_program_chemical(program_id,contractor_id,chemical_id,primary_id,root_proid,status,apply_user_id,apply_date) VALUES(:program_id,:contractor_id,:chemical_id,:primary_id,:root_proid,:status,:apply_user_id,:apply_date)';
                        $command = Yii::app()->db->createCommand($sql);
                        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
                        $command->bindParam(":contractor_id", $args['contractor_id'], PDO::PARAM_INT);
                        $command->bindParam(":chemical_id", $id, PDO::PARAM_STR);
                        $command->bindParam(":primary_id",$id,PDO::PARAM_STR);
                        $command->bindParam(":root_proid", $model->root_proid, PDO::PARAM_STR);
                        $command->bindParam(":status", $status, PDO::PARAM_STR);
                        $command->bindParam(":apply_user_id", $apply_user_id, PDO::PARAM_STR);
                        $command->bindParam(":apply_date", $time, PDO::PARAM_STR);

                        $rs = $command->execute();
                    }
                }
            }


            OperatorLog::savelog(OperatorLog::MODULE_ID_PROJ, Yii::t('proj_project_user', 'Edit Proj'), self::updateLog($model));
            $r['msg'] = Yii::t('common', 'success_apply');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
//        var_dump($r);
//        exit;
        return $r;
    }



//查询项目中状态为(-1,10,11,20)的化学物品
    public static function myChemicalListBySuccess($program_id, $contractor_id) {
        $sql = "SELECT a.chemical_id,b.chemical_name FROM bac_program_chemical a,bac_chemical b WHERE  a.program_id=:program_id and a.contractor_id=:contractor_id and  a.chemical_id = b.primary_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['chemical_id']] = $row['chemical_name'];
            }
        }
        return $rs;
    }




    public static function DeleteChemical($program_id,$primary_id) {
        $sql = "DELETE FROM bac_program_chemical WHERE program_id =:program_id and primary_id =:primary_id";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":primary_id", $primary_id, PDO::PARAM_STR);

        $rs = $command->execute();

        if($rs ==2 || $rs == 1){
            $r['msg'] = '删除成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '删除失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function BatchLeaveChemical($program_id,$chemical_list) {
        foreach($chemical_list as $k => $primary_id){
            $sql = "DELETE FORM bac_program_chemical WHERE program_id =:program_id and chemical_id =:chemical_id";//var_dump($sql);;;
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
            $command->bindParam(":chemical_id", $primary_id, PDO::PARAM_STR);
            $rs = $command->execute();
        }

        if($rs ==2 || $rs == 1){
            $r['msg'] = '申请成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '申请失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function LeaveChemical($program_id,$primary_id) {
        $leave_pending = self::LEAVE_PENDING;
        $status = self::STATUS_STOP;
        $sql = "DELETE FROM bac_program_chemical  WHERE program_id =:program_id and chemical_id =:chemical_id";//var_dump($sql);;;
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_status", $leave_pending, PDO::PARAM_STR);
        $command->bindParam(":status", $status, PDO::PARAM_STR);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":chemical_id", $primary_id, PDO::PARAM_STR);

        $rs = $command->execute();

        if($rs ==2 || $rs == 1){
            $r['msg'] = '申请成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '申请失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //查询某项目下入场化学物品信息
    public static function PersonelChemical($chemical_id,$program_id){
        $sql = "SELECT * FROM bac_program_chemical WHERE chemical_id = :chemical_id AND program_id = :program_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":chemical_id", $chemical_id, PDO::PARAM_INT);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_INT);
        $rows = $command->queryAll();

        return $rows;
    }

    //查询化学物品在哪个项目组中
    public static function ChemicalProgramName($chemical_id){
        if($chemical_id == ''){
            return '';
        }
        $cnt = ProgramChemical::model()->count("chemical_id='".$chemical_id."' ");
        if($cnt > 0){
            $sql = "select b.program_name from bac_program_chemical a ,bac_program b where a.primary_id = '".$chemical_id."' and  a.program_id = b.program_id and b.status = '00'";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
        }
        return $rows;
    }

    //根据承包商ID和chemical_id获取所在项目的各项信息
    public static function SelfInfo($contractor_id,$chemical_id){
        $sql = "select a.*,b.program_name,b.program_id from bac_program_chemical a,bac_program b where a.contractor_id = '".$contractor_id."' and a.chemical_id = '".$chemical_id."' and a.program_id = b.program_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //根据承包商ID和user_id以及program_id获取所在项目的各项信息
    public static function SelfByPro($contractor_id,$chemical_id,$program_id){
        $sql = "select * from bac_program_chemical  where contractor_id = '".$contractor_id."' and chemical_id = '".$chemical_id."' and program_id = '".$program_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //统计一个人在某个项目中进出场日期
    public static function SelfByDate($chemical_id,$program_id){
        $sql = "select * from bac_program_chemical where program_id = '".$program_id."' and  chemical_id = '".$chemical_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $newsql = "select * from bac_check_apply_detail";
        $newcommand = Yii::app()->db->createCommand($newsql);
        $newrows = $newcommand->queryAll();
        foreach($newrows as $n => $list){
            $r[$list['apply_id']]['apply_time'] = $list['apply_time'];
        }
        foreach($rows as $n => $list){
            $rs[$n]['entrance_time'] = $r[$list['entrance_apply_id']]['apply_time'];
            $rs[$n]['leave_time'] = $r[$list['leave_apply_id']]['apply_time'];
        }
        return $rs;
    }

    //下载PDF
    public static function downloadPdf($params,$app_id){
//        var_dump($params);
//        exit;
        $program_id = $params['program_id'];
        $primary_id = $params['primary_id'];

        $chemical_model = Chemical::model()->findByPk($primary_id);
        $chemical_id = $chemical_model->chemical_id;
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $arry['contractor_id'] = $contractor_id;
        $program_list = Program::programAllList($arry);//项目列表
        $program_name = $program_list[$program_id];//项目名称
//        $chemical_model = Chemical::model()->findByPk($chemical_id);//化学物品信息
//        $chemical_model = Chemical::model()->find('chemical_id=:chemical_id', array(':chemical_id' => $chemical_id));
        $type_no = $chemical_model->type_no;
        $chemicaltype_model = ChemicalType::model()->findByPk($type_no);//化学物品类型信息
        $chemical_img = $chemical_model->chemical_img;//化学物品图片
        $qrcode = $chemical_model->qrcode;//化学物品二维码

        if(!$qrcode){
            $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'phpqrcode'.DIRECTORY_SEPARATOR.'qrlib.php';
            require_once($tcpdfPath);
            $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] .'/qrcode/'.$contractor_id.'/chemical/';
            $errorCorrectionLevel = 'L';
            if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
                $errorCorrectionLevel = $_REQUEST['level'];

            $matrixPointSize = 4;
            if (isset($_REQUEST['size']))
                $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

            $filename = $PNG_TEMP_DIR. $primary_id.'_new.png';
            $content = 'did|'.$primary_id;
            QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
            Chemical::insertQrcode($primary_id,$filename);
            $qrcode = $chemical_model->qrcode;//化学物品二维码
        }


        $chemical_list = ProgramChemical::PersonelChemical($chemical_id, $program_id);//项目化学物品信息
        //var_dump($programuser_model);
        $chemical_name = $chemical_model->chemical_name;//化学物品名称
        $chemical_id = $chemical_model->chemical_id;//化学物品编号
        $chemical_type = $chemicaltype_model->chemical_type_en;//化学物品型号
        $chemical_startdate = $chemical_model->permit_startdate;//化学物品许可证开始日期
        $chemical_enddate = $chemical_model->permit_enddate;//化学物品许可证结束日期
        $contractor_list = Contractor::compList();//承包商名称列表
        $lang = "_en";
        $showtime=Utils::DateToEn(date("Y-m-d"));//当前时间
        if (Yii::app()->language == 'zh_CN') {
            $lang = "_zh"; //中文
        }
        //$filepath = './attachment' . '/USER' . $user_id . $lang . '.pdf';
        $filepath = Yii::app()->params['upload_file_path'] . '/Chemical' . $chemical_id . $lang . '.pdf';
        $pdf_title = 'Chemical' . $chemical_id . $lang . '.pdf';
//        $filepath = '/opt/www-nginx/web/ctmgr/webuploads' . '/PTW' . $id . $lang . '.pdf';
        //$filepath = '/opt/www-nginx/web/test/ctmgr/attachment' . '/PTW' . $id . $lang . '.pdf';
//        var_dump($filepath);
//        exit;
        $title = Yii::t('proj_project_chemical', 'pdf_title');
        ///opt/www-nginx/web/test/ctmgr

        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
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
        $chemical_detail = Yii::t('proj_project_chemical','chemical_detail');
        //$pdf->SetKeywords('PDF, LICEN');
        // 设置页眉和页脚信息
        $pdf->SetHeaderData('', 0, '', $chemical_detail, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

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
        //信息翻译
        if (Yii::app()->language == 'zh_CN') {

        } else if (Yii::app()->language == 'en_US') {

        }
        //化学物品信息
        $chemical_html =
            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; border:solid #999;border-width: 0 1px 0 1px;"><tr><td colspan="2"><h5 align="center">'.$title.'</h5></td></tr><tr><td width="25%">'.Yii::t('proj_project_chemical', 'chemical_id').'</td><td width="75%">'.$chemical_id.'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project_chemical', 'chemical_name') . '</td><td width="75%">'. $chemical_name.'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project_chemical', 'chemical_type') . '</td><td width="75%">'. $chemical_type.'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project_user', 'company_name') . '</td><td width="75%">'. $contractor_list[$contractor_id].'</td></tr><tr><td width="25%">'
            . Yii::t('proj_project', 'program_name') . '</td><td width="75%">'. $program_name.'</td></tr>';
        //化学物品照片
        $photo_html = '<tr><td width="25%" height="120px">'
            . Yii::t('proj_project_chemical', 'chemical_img') .'</td><td width="75%"></td></tr></table>';
        $info_x = 75;
        if($chemical_img){
            $pdf->Image($chemical_img, $info_x, 89, 30, 30, 'JPG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
            $info_x+=35;
        }
        if($qrcode){
            $pdf->Image($qrcode, $info_x+10, 83, 30, 30, 'PNG', '', '',  false, 300, '', false, false, 0, $fitbox, false, false);
        }
        //许可证开始日期
        $startdate_html = '<tr><td width="25%">'
            . Yii::t('proj_project_chemical', 'start_date') .'</td><td width="75%">'
            .Utils::DateToEn($chemical_startdate).'</td></tr>' ;

        //许可证结束日期
        $enddate_html = '<tr><td width="25%">'
            . Yii::t('proj_project_chemical', 'end_date') . '</td><td width="75%">'
            .Utils::DateToEn($chemical_enddate).'</td></tr>' ;


        $html = $chemical_html . $photo_html;

        $pdf->writeHTML($html, true, false, true, false, '');
        $img_num = 0;//检验页码标志
        $x = 30;
        $y_1 = 30;//第一张y的位置
        $y_2 = 150;//第二张y的位置
        $aptitude_list =ChemicalInfo::queryAll($chemical_id);//人员证书
        if($aptitude_list){
            foreach($aptitude_list as $cnt => $list){
                $aptitude = explode('|',$list['certificate_photo']);
                foreach($aptitude as $i => $photo){
                    $file = explode('.',$photo);
                    if($file[1] != 'pdf') {
                        if ($img_num % 2 == 0) {
                            $pdf->AddPage();//再加一页
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_1, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        } else {
                            $img_num = $img_num + 1;
                            $pdf->Image($photo, $x, $y_2, 150, 100, 'JPG', '', '', false, 300, '', false, false, 0, $fitbox, false, false);
                        }
                    }
                }
            }
        }
        //输出PDF
        // $pdf->Output($filepath, 'I');
        $pdf->Output($pdf_title,'D');
        //$pdf->Output($filepath, 'F'); //保存到指定目录
        //Utils::Download($filepath, $title, 'pdf'); //下载pdf
//============================================================+
// END OF FILE
//============================================================+
    }

    //导出Excel
    public static function chemicalinfo($args){
        $entrance_apply = self::ENTRANCE_APPLY;
        $entrance_pending = self::ENTRANCE_PENDING;
        $entrance_success = self::ENTRANCE_SUCCESS;
        $leave_pending = self::LEAVE_PENDING;
        if($args['status'] != ''){
            $sql = "select a.chemical_name,a.primary_id,a.chemical_id,a.type_no,a.chemical_img,a.qrcode,b.apply_date
                  from (bac_chemical a inner join bac_program_chemical b
                  on a.primary_id = b.chemical_id and b.program_id = '".$args['program_id']."' and b.check_status='".$args['status']."')";
        }else{
            $sql = "select a.chemical_name,a.primary_id,a.chemical_id,a.type_no,a.chemical_img,a.qrcode,b.apply_date
                  from (bac_chemical a inner join bac_program_chemical b
                  on a.primary_id = b.chemical_id and b.program_id = '".$args['program_id']."' and b.check_status in ('".$entrance_apply."','".$entrance_pending."','".$entrance_success."','".$leave_pending."'))";
        }
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
}
