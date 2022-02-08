<?php

class RegisterController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('device', 'bigMenu');
        $this->bigMenu = Yii::t('device', 'contentHeader');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genScaffoldGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=device/register/scaffoldgrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('tbm_meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('device', 'scaffold_type'), '', 'center');
        $t->set_header(Yii::t('device', 'height'), '', 'center');
        $t->set_header(Yii::t('device', 'week_1'), '', 'center');
        $t->set_header(Yii::t('device', 'week_2'), '', 'center');
        $t->set_header(Yii::t('device', 'week_3'), '', 'center');
        $t->set_header(Yii::t('device', 'week_4'), '', 'center');
        $t->set_header(Yii::t('device', 'week_5'), '', 'center');
        $t->set_header(Yii::t('device', 'dismantle_date'), '', 'center');
        $t->set_header(Yii::t('device', 'location'), '', 'center');
        $t->set_header(Yii::t('device', 'inspected_by'), '', 'center');
        $t->set_header(Yii::t('device', 'remarks'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionScaffoldGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        if(count($fields) == 3 && $fields[0] != null ) {
//            $args['program_id'] = $fields[0];
//            $args['user_id'] = $fields[1];
//            $args['deal_type'] = $fields[2];
//        }
        $t = $this->genScaffoldGrid();

        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = RegisterScaffold::queryList($page, $this->pageSize, $args);
        $this->renderPartial('scaffold_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionScaffoldList() {
        $this->smallHeader = Yii::t('device', 'Scaffold register');
        $this->render('scaffoldlist');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionScaffold() {
        $this->render('scaffold');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionAddScaffold() {
        $json = $_REQUEST['json_data'];
        $program_id = $_REQUEST['program_id'];
        $data = json_decode($json);
        $r = RegisterScaffold::InsertList($data,$program_id);
        echo json_encode($r);
    }

    /**
     * 修改
     */
    public function actionEditScaffold() {
        $this->smallHeader = Yii::t('device', 'Scaffold register');
        $model = new RegisterScaffold('modify');
        $r = array();
        $id = $_REQUEST['id'];
        $scaffold_model = RegisterScaffold::model()->findByPk($id);
        if (isset($_POST['RegisterScaffold'])) {
            $args = $_POST['RegisterScaffold'];

            $r = RegisterScaffold::UpdateList($id,$args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['RegisterScaffold'];
            }
        }

        $model->_attributes = RegisterScaffold::model()->findByPk($id);

        $this->render('scaffold_form', array('id'=>$id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionDelScaffold() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = RegisterScaffold::DeleteList($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    //导出PDF
    //导出员工信息表
    public function actionExportScaffold(){
        $args = $_GET['q'];
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $list = RegisterScaffold::exportList($args);

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:L1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','Scaffold register'.'('.$program_name.')');
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);

        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A2','No.');

        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B2','Scaffold Type');

        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','Height');

        $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2','Week-1');

        $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2','Week-2');

        $objectPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2','Week-3');

        $objectPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2','Week-4');

        $objectPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2','Week-5');

        $objectPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2','Dismantled on(Date)');

        $objectPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2','Location');

        $objectPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2','Inspected by');

        $objectPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L2','remarks');

//            $objectPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(20);
        $t =3;
        //写入数据
        foreach ($list as $k => $v) {
            $k = $k+1;
            $objectPHPExcel->getActiveSheet()->setCellValue(A .$t, $k);
            $objectPHPExcel->getActiveSheet()->setCellValue(B .$t, $v->scaffold_type);
            $objectPHPExcel->getActiveSheet()->setCellValue(C .$t, $v->height);
            $objectPHPExcel->getActiveSheet()->setCellValue(D .$t, $v->week_1);
            $objectPHPExcel->getActiveSheet()->setCellValue(E .$t, $v->week_2);
            $objectPHPExcel->getActiveSheet()->setCellValue(F .$t, $v->week_3);
            $objectPHPExcel->getActiveSheet()->setCellValue(G .$t, $v->week_4);
            $objectPHPExcel->getActiveSheet()->setCellValue(H .$t, $v->week_5);
            $objectPHPExcel->getActiveSheet()->setCellValue(I .$t, $v->dismantle_date);
            $objectPHPExcel->getActiveSheet()->setCellValue(J .$t, $v->location);
            $objectPHPExcel->getActiveSheet()->setCellValue(K .$t, $v->inspected_by);
            $objectPHPExcel->getActiveSheet()->setCellValue(L .$t, $v->remarks);
            $t=$t+1;
        }


        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Scaffold Register-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genLadderGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=device/register/laddergrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('tbm_meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('device', 'ladder_type'), '', 'center');
        $t->set_header(Yii::t('device', 'height'), '', 'center');
        $t->set_header(Yii::t('device', 'using_by'), '', 'center');
        $t->set_header(Yii::t('device', 'location'), '', 'center');
        $t->set_header(Yii::t('device', 'inspected_by'), '', 'center');
        $t->set_header(Yii::t('device', 'remarks'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionLadderGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        if(count($fields) == 3 && $fields[0] != null ) {
//            $args['program_id'] = $fields[0];
//            $args['user_id'] = $fields[1];
//            $args['deal_type'] = $fields[2];
//        }
        $t = $this->genLadderGrid();

        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = RegisterLadder::queryList($page, $this->pageSize, $args);
        $this->renderPartial('ladder_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionLadderList() {

        $this->smallHeader = Yii::t('device', 'Ladder register');
        $this->render('ladderlist');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionLadder() {
        $this->render('ladder');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionAddLadder() {
        $json = $_REQUEST['json_data'];
        $program_id = $_REQUEST['program_id'];
        $data = json_decode($json);
        $r = RegisterLadder::InsertList($data,$program_id);
        echo json_encode($r);
    }

    /**
     * 修改
     */
    public function actionEditLadder() {
        $this->smallHeader = Yii::t('device', 'Ladder register');
        $model = new RegisterLadder('modify');
        $r = array();
        $id = $_REQUEST['id'];
        $scaffold_model = RegisterLadder::model()->findByPk($id);
        if (isset($_POST['RegisterLadder'])) {
            $args = $_POST['RegisterLadder'];

            $r = RegisterScaffold::UpdateList($id,$args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['RegisterLadder'];
            }
        }

        $model->_attributes = RegisterScaffold::model()->findByPk($id);

        $this->render('ladder_form', array('id'=>$id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionDelLadder() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = RegisterScaffold::DeleteList($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    //导出信息表
    public function actionExportLadder(){
        $args = $_GET['q'];
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $list = RegisterScaffold::exportList($args);
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:L1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','Ladder Register'.'('.$program_name.')');
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);

        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A2','No.');

        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B2','Ladder Type');

        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','Height');

        $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2','Using by(Company)');

        $objectPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2','Location');

        $objectPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2','Inspected by');

        $objectPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('L2','remarks');

//            $objectPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(20);
        $t =3;
        //写入数据
        foreach ($list as $k => $v) {
            $k = $k+1;
            $objectPHPExcel->getActiveSheet()->setCellValue(A .$t, $k);
            $objectPHPExcel->getActiveSheet()->setCellValue(B .$t, $v->ladder_type);
            $objectPHPExcel->getActiveSheet()->setCellValue(C .$t, $v->height);
            $objectPHPExcel->getActiveSheet()->setCellValue(D .$t, $v->using_by);
            $objectPHPExcel->getActiveSheet()->setCellValue(J .$t, $v->location);
            $objectPHPExcel->getActiveSheet()->setCellValue(K .$t, $v->inspected_by);
            $objectPHPExcel->getActiveSheet()->setCellValue(L .$t, $v->remarks);
            $t=$t+1;
        }


        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Ladder Register-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genCraneGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=device/register/cranegrid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('tbm_meeting', 'seq'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'program_name'), '', 'center');
        $t->set_header(Yii::t('device', 'distinctive_no'), '', 'center');
        $t->set_header(Yii::t('device', 'serial_no'), '', 'center');
        $t->set_header(Yii::t('device', 'type'), '', 'center');
        $t->set_header(Yii::t('device', 'swl'), '', 'center');
        $t->set_header(Yii::t('device', 'lm_no'), '', 'center');
        $t->set_header(Yii::t('device', 'bypass_times'), '', 'center');
        $t->set_header(Yii::t('device', 'max_time'), '', 'center');
        $t->set_header(Yii::t('device', 'min_time'), '', 'center');
        $t->set_header(Yii::t('device', 'reason'), '', 'center');
        $t->set_header(Yii::t('device', 'owner'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'apply_date'), '', 'center');
        $t->set_header(Yii::t('tbm_meeting', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '15%', 'center');
        return $t;
    }

    /**
     * 查询
     */
    public function actionCraneGrid() {
        $fields = func_get_args();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

//        if(count($fields) == 3 && $fields[0] != null ) {
//            $args['program_id'] = $fields[0];
//            $args['user_id'] = $fields[1];
//            $args['deal_type'] = $fields[2];
//        }
        $t = $this->genCraneGrid();

        $args['contractor_id'] = Yii::app()->user->contractor_id;
        $list = RegisterCrane::queryList($page, $this->pageSize, $args);
        $this->renderPartial('crane_list', array('t' => $t, 'rows' => $list['rows'],'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionCraneList() {

        $this->smallHeader = Yii::t('device', 'Ladder crane');
        $this->render('cranelist');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionCrane() {
        $this->render('crane');
    }

    /**
     * Method Statement with Risk Assessment
     */
    public function actionAddCrane() {
        $json = $_REQUEST['json_data'];
        $program_id = $_REQUEST['program_id'];
        $data = json_decode($json);
        $r = RegisterCrane::InsertList($data,$program_id);
        echo json_encode($r);
    }

    /**
     * 修改
     */
    public function actionEditCrane() {
        $this->smallHeader = Yii::t('device', 'Ladder crane');
        $model = new RegisterCrane('modify');
        $r = array();
        $id = $_REQUEST['id'];
        $scaffold_model = RegisterCrane::model()->findByPk($id);
        if (isset($_POST['RegisterCrane'])) {
            $args = $_POST['RegisterCrane'];

            $r = RegisterCrane::UpdateList($id,$args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['RegisterCrane'];
            }
        }

        $model->_attributes = RegisterCrane::model()->findByPk($id);

        $this->render('crane_form', array('id'=>$id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionDelCrane() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = RegisterCrane::DeleteList($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    //导出信息表
    public function actionExportCrane(){
        $args = $_GET['q'];
        $pro_model = Program::model()->findByPk($args['program_id']);
        $program_name = $pro_model->program_name;
        $list = RegisterCrane::exportList($args);
        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭YII的自动加载功能
        $phpExcelPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'PHPExcel'.DIRECTORY_SEPARATOR.'PHPExcel.php';
        require_once($phpExcelPath);
        spl_autoload_register(array('YiiBase', 'autoload'));
        $objectPHPExcel = new PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objectPHPExcel->getActiveSheet();
        $objActSheet->setTitle('Sheet1');

        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:L1');
        $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A1','Crane data logger report summary'.'('.$program_name.')');
        $objStyleA1 = $objActSheet->getStyle('A1');
        //字体及颜色
        $objFontA1 = $objStyleA1->getFont();
        $objFontA1->setSize(20);

        $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('A2','No.');

        $objectPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('B2','Distinctive No.');

        $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('C2','Serial No.');

        $objectPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('D2','Type/Description');

        $objectPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('E2','SWL');

        $objectPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('F2','LM No.');

        $objectPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('G2','No. of bapass times');

        $objectPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('H2','Maximum Overload Time');

        $objectPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('I2','Minimum Overload Time');

        $objectPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('J2','Reason for Overload');

        $objectPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->getActiveSheet()->setCellValue('K2','Crane Owner');

//            $objectPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth(20);
        $t =3;
        //写入数据
        foreach ($list as $k => $v) {
            $k = $k+1;
            $objectPHPExcel->getActiveSheet()->setCellValue(A .$t, $k);
            $objectPHPExcel->getActiveSheet()->setCellValue(B .$t, $v->distinctive_no);
            $objectPHPExcel->getActiveSheet()->setCellValue(C .$t, $v->serial_no);
            $objectPHPExcel->getActiveSheet()->setCellValue(D .$t, $v->type);
            $objectPHPExcel->getActiveSheet()->setCellValue(E .$t, $v->swl);
            $objectPHPExcel->getActiveSheet()->setCellValue(F .$t, $v->lm_no);
            $objectPHPExcel->getActiveSheet()->setCellValue(G .$t, $v->bypass_times);
            $objectPHPExcel->getActiveSheet()->setCellValue(H .$t, $v->max_time);
            $objectPHPExcel->getActiveSheet()->setCellValue(I .$t, $v->min_time);
            $objectPHPExcel->getActiveSheet()->setCellValue(J .$t, $v->reason);
            $objectPHPExcel->getActiveSheet()->setCellValue(K .$t, $v->owner);
            $t=$t+1;
        }


        //下载输出
        ob_end_clean();
        //ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'Crane data logger report summary-'.date("d M Y").'.xls"');
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
}

