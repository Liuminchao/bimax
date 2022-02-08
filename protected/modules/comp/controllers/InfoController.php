<?php

class InfoController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const CONTRACTOR_PREFIX = 'CT';  //承包商编号前缀
    
    public function init() {
        parent::init(); 
        $this->contentHeader = Yii::t('comp_contractor', 'contentHeader');
        $this->bigMenu = Yii::t('comp_contractor', 'bigMenu');
        if(Yii::app()->user->getState('operator_role') == '00'){
            $this->layout = '//layouts/main_user';
        }else{
            $this->layout = '//layouts/main_new';
        }
        if(Yii::app()->user->getState('operator_role') == '00'){
            $this->bigMenu = Yii::t('dboard', 'Menu Comp');
        }else{
            $this->bigMenu = Yii::t('comp_company', 'bigMenu');
        }
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/info/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('comp_contractor', 'Contractor_id'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'Contractor_name'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'Contractor_type'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'company_sn'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'link_person'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'link_phone'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'Status'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'Record Time'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'Action'), '20%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        //$args['status'] = '0';
        $t = $this->genDataGrid();
        $this->saveUrl();
        //$args['contractor_type'] = Contractor::CONTRACTOR_TYPE_MC;
        $list = Contractor::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
//        $user_list = Staff::userInfo();
//        foreach($user_list as $user_id => $list){
//            $c_model = Company::model()->findByPk($list[0]['company_id']);
//            $rs['operator_type'] = Operator::TYPE_PLATFORM;
//            $rs['operator_role'] = Operator::operator;
//            $rs['operator_id'] = $list[0]['user_phone'];
//            $rs['company_id'] = $list[0]['company_id'];
//            $rs['name'] = $list[0]['user_name'];
//            $rs['phone'] = $list[0]['user_phone'];
//            $rs['email'] = $list[0]['primary_email'];
//            $rs['passwd'] = '123456';
////            var_dump($rs);
////            exit;
//            Operator::insertOperator($rs);
//
//        }
//        $sql = "SELECT operator_id,contractor_id FROM bac_operator WHERE status=0 and operator_type = '01' and operator_role ='00' ";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//
//        $status = '00';
//        foreach($rows as $k => $v){
//            $c_model = Contractor::model()->findByPk($v['contractor_id']);
//            $menu_list = Menu::appMenuList();
//            foreach($menu_list as $menu_id => $name){
//                $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
//                $command = Yii::app()->db->createCommand($sql);
//                $command->bindParam(":operator_id", $v['operator_id'], PDO::PARAM_STR);
//                $command->bindParam(":menu_id", $menu_id, PDO::PARAM_STR);
//                $command->bindParam(":status", $status, PDO::PARAM_STR);
//                $rs = $command->execute();
//            }
//            $sql = "insert into bac_operator_menu_q (operator_id,menu_id,status) values (:operator_id,:menu_id,:status)";
//            $id = '105';
//            $command = Yii::app()->db->createCommand($sql);
//            $command->bindParam(":operator_id", $v['operator_id'], PDO::PARAM_STR);
//            $command->bindParam(":menu_id", $id, PDO::PARAM_STR);
//            $command->bindParam(":status", $status, PDO::PARAM_STR);
//            $command->execute();
//        }
//        $operator_list = Operator::OperatorAllList();
//        foreach($operator_list as $i => $j){
//            $re['contractor_id'] = $j['contractor_id'];
//            $project_list = Program::programList($re);
//            foreach($project_list as $project_id => $project_name){
//                //企业普通操作员
//                if($j['operator_role'] == '01'){
//                    $s['operator_id'] = $j['operator_id'];
//                    $s['value'] = '2';
//                    $s['program_id'] = $project_id;
//                    OperatorProject::SetAuthority($s);
//                }else{
//                    $s['operator_id'] = $j['operator_id'];
//                    $s['value'] = '1';
//                    $s['program_id'] = $project_id;
//                    OperatorProject::SetAuthority($s);
//                }
//            }
//        }
//
//        if ($rs) {
//            var_dump('执行成功');
//        }
        $this->smallHeader = Yii::t('comp_contractor', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 详细
     */
    public function actionDetail() {

        $id = $_POST['id'];
        $msg['status'] = true;

        $model = Contractor::model()->findByPk($id);
        $operator = Operator::model()->find("contractor_id=:contractor_id", array("contractor_id" => $id));

        if ($model) {

            $msg['detail'] .= "<table class='detailtab'>";
            $msg['detail'] .= "<tr class='form-name'>";
            $msg['detail'] .= "<td colspan='4'>" . Yii::t('comp_contractor', 'Base Info') . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('contractor_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->contractor_id) ? $model->contractor_id : "") . "</td>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('contractor_name') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->contractor_name) ? $model->contractor_name : "") . "</td>";
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('company_sn') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->company_sn) ? $model->company_sn : "") . "</td>";
            
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('link_person') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->link_person) ? $model->link_person : "") . "</td>";
            
            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('link_phone') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->link_phone) ? $model->link_phone : "") . "</td>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('company_adr') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($model->company_adr) ? $model->company_adr : "") . "</td>";

            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('project') . "</td>";
            $args['status'] = '00';
            $args['contractor_id'] = $id;
            $project_list = Program::programList($args);
            $program = '';
            foreach($project_list as $i => $name){
                $program .= $name.' ';
            }
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($program) ? $program : "") . "</td>";

            $msg['detail'] .= "</tr>";


            $msg['detail'] .= "<tr class='form-name'>";
            $msg['detail'] .= "<td colspan='4'>" . Yii::t('comp_contractor', 'Login Info') . "</td>";
            $msg['detail'] .= "</tr>";

            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $model->getAttributeLabel('operator_id') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($operator->operator_id) ? $operator->operator_id : "") . "</td>";

            $msg['detail'] .= "<td class='tname-2'>" . $operator->getAttributeLabel('name') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($operator->name) ? $operator->name : "") . "</td>";

            $msg['detail'] .= "</tr>";
            $msg['detail'] .= "<tr>";
            $msg['detail'] .= "<td class='tname-2'>" . $operator->getAttributeLabel('phone') . "</td>";
            $msg['detail'] .= "<td class='tvalue-4'>" . (isset($operator->phone) ? $operator->phone : "") . "</td>";

            $msg['detail'] .= "</tr>";


            $msg['detail'] .= "</table>";
            print_r(json_encode($msg));
        }
    }

    /**
     * 添加
     */
    public function actionNew() {

        $this->smallHeader = Yii::t('comp_contractor', 'smallHeader New');
        $model = new Contractor('create');
        $r = array();

        //echo Contractor['contractor_name'];

        if (isset($_POST['Contractor'])) {

            $args = $_POST['Contractor'];

            $r = Contractor::insertContractor($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Operator'];
            }
//            if($rs['status']=1){
//                $r['msg'] .='同时建立默认考勤项目';
//            }
        }
        $this->render('new', array('model' => $model, 'msg' => $r));
    }

    /**
     * 修改
     */
    public function actionEdit() {

        $this->smallHeader = Yii::t('comp_contractor', 'smallHeader Edit');
        $model = new Contractor('modify');
        $id = trim($_REQUEST['id']);
        $model->_attributes = Contractor::model()->findByPk($id);
        //$seq_id = sprintf('%05s', $id);         
        //$operator_id = self::CONTRACTOR_PREFIX . $seq_id;
//        var_dump($args['operator_id']);
//        exit;
        $r = array();
        if (isset($_POST['Contractor'])) {
            $args = $_POST['Contractor'];
            $args['contractor_id'] = $id;
            if($args['tmp_src']){
                $model = Contractor::model()->findByPk($id);
                $remark = $model->remark;
                if($remark){
                    $path = '/opt/www-nginx/web'.$remark;
                    unlink($path);
                }
                $re = Contractor::movePic($args['tmp_src'],$id);
                $args['remark'] = $re['src'];

            }
            //var_dump($model->company_sn.'--'.$args['company_sn']);
            
            if($model->company_sn <> $args['company_sn']){
                //$result = Operator::updateFaceOperator($args);
                $exist_data = Contractor::model()->count('company_sn=:company_sn', array('company_sn' => $args['company_sn']));
                if ($exist_data != 0) {
                    $r['msg'] = Yii::t('comp_contractor', 'Error company_sn is exist');
                    $r['status'] = -1;
                    $r['refresh'] = false;

                }else{
                    Operator::model()->updateAll(array('operator_id'=>$args['company_sn']), 'contractor_id=:contractor_id and operator_type=:operator_type', array(':contractor_id'=>$id, ':operator_type'=>Operator::TYPE_PLATFORM));
                }
            }
            
            $r = Contractor::updateContractor($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['Contractor'];
            }else{
                $model->_attributes = Contractor::model()->findByPk($id);
            }
        }
        
        $this->render('edit', array('model' => $model, 'msg' => $r));
    }

    /**
     * 列表
     */
    public function actionElectronicList() {
//        $sql = "select pic from bac_check_apply_detail where  apply_id ='1512982179451'";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;

        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        if($id == 'contractor_id' && $name == 'contractor_name'){
            $id = Yii::app()->user->contractor_id;
//            $name = Yii::app()->user->contractor_name;
        }
        $this->smallHeader = Yii::t('electronic_contract', 'smallHeader List');
        $this->render('electroniclist',array('id' => $id,'name' => $name));
    }

    /**
     * 电子合约表头
     * @return SimpleGrid
     */
    private function genElectronicGrid($id,$name) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/info/electronicgrid&name='.$name.'&id='.$id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('electronic_contract', 'title'), '', '');
//        $t->set_header(Yii::t('electronic_contract', 'file_path'), '', '');
        $t->set_header(Yii::t('electronic_contract', 'content'), '', '');
        $t->set_header(Yii::t('electronic_contract', 'start_date'), '', '');
        $t->set_header(Yii::t('electronic_contract', 'end_date'), '', '');
        $t->set_header(Yii::t('electronic_contract', 'record_time'), '', '');
        $t->set_header(Yii::t('electronic_contract', 'Action'), '20%', '');
        return $t;
    }

    /**
     * 电子合约列表
     */
    public function actionElectronicGrid($id,$name) {
        $args = array();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        //$args = $_GET['q']; //查询条件
        //$args['status'] = '0';
        $t = $this->genElectronicGrid($id,$name);
        $this->saveUrl();
        $args['contractor_id'] = $id;
        $list = ElectronicContract::queryList($page, $this->pageSize, $args);
        $this->renderPartial('electronic_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }
    /*
     * 上传电子合约界面
     */
    public function actionUploadView() {
        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        $model = new ElectronicContract('create');
        $this->smallHeader = Yii::t('electronic_contract', 'smallHeader Upload');
        $this->render('electronic_upload',array('model'=> $model,'contractor_id'=>$id,'contractor_name'=>$name));
    }
    /*
     * 上传电子合约
     */
    public function actionUpload(){
        $args = $_REQUEST['ElectronicContract'];
        $files = $_REQUEST['File'];
        if ($files['contract_src'] <> ''){
                $rs = Compress::uploadPicture($files, $args['contractor_id']);
//		var_dump($rs);
//                exit;
                $r['status'] = '';
                $r['msg'] = '';
                foreach($rs as $key => $row){
                    if($row['code'] <> 0){
                        $r['status']  .= $row['code'];
                        $r['msg']  .= $row['msg'].' ';
                    }else{
                        if($key == 'contract_src') {
                            $args['file_path'] = substr($row['upload_file'],18);
                        }
                    }
                }//var_dump($r);
                if($r['status'] <> ''){
                    $r['refresh'] = false;
                    goto end;
//                    return $r;
                }
        }
        $r = ElectronicContract::insertContract($args);
        end:
            print_r(json_encode($r));
    }
    /**
     * 在线预览文件
     */
    public function actionPreview() {
        $file_path = $_REQUEST['file_path'];
        $id = $_REQUEST['id'];
//        var_dump($file_path);
//        exit;
        $this->renderPartial('preview',array('file_path'=>$file_path,'id'=>$id));
    }
    /**
     * 删除电子合约
     */
    public function actionDelete() {
        $id = trim($_REQUEST['id']);
        $path = trim($_REQUEST['file_path']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ElectronicContract::deleteContract($id,$path);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    
    /**
     * 下载电子合约
     */
    public function actionDownload() {
        $path = $_REQUEST['path'];
        $rows = ElectronicContract::queryContract($path);
        if(count($rows)>0){
            $show_name = $rows[0]['title'];
            $filepath = '/opt/www-nginx/web'.$rows[0]['file_path'];
            $extend = 'pdf';
            Utils::Download($filepath, $show_name, $extend);
            return;
        }
    }
    
    /**
     * 注销
     */
    public function actionLogout() {
        $id = trim($_REQUEST['id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = Contractor::logoutContractor($id);
        }
        //var_dump($r);
        echo json_encode($r);
    }

    /**
     * 重置登录密码
     */
//    public function actionResetpwd() {
//        $id = trim($_REQUEST['id']);
//        $r = array();
//        if ($_REQUEST['confirm']) {
//
//            $r = Operator::resetContractorPwd($id);
//        }
//        echo json_encode($r);
//    }
    public function actionResetpwd() {
        $model = new Operator('modify');
        $id = trim($_REQUEST['id']);
        $contractor = Contractor::model()->findByPk($id);
        $name = $contractor->contractor_name;
//        var_dump($name);
//        exit;
        $this->renderPartial('resetpwd', array('model'=>$model,'id'=>$id,'name'=>$name));
    }
    public function actionReset() {
        $args = $_REQUEST['Operator'];
        $r = Operator::resetContractorPwd($args);
        echo json_encode($r);
//        var_dump($args);
//        exit;
    }

    /**
     * 操作员列表
     */
    public function actionOperatorList() {
        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        if($id == 'contractor_id' && $name == 'contractor_name'){
            $id = Yii::app()->user->contractor_id;
//            $name = Yii::app()->user->contractor_name;
        }
        $this->bigMenu = Yii::t('sys_operator', 'smallHeader List');
        $this->contentHeader = Yii::t('sys_operator', 'contentHeader');
        $this->smallHeader = Yii::t('sys_operator', 'smallHeader List');
        $this->render('operatorlist',array('id' => $id,'name' => $name));
    }

    /**
     * 操作员表头
     * @return SimpleGrid
     */
    private function genOperatorGrid($id,$name) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/info/operatorgrid&name='.$name.'&id='.$id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('sys_operator','Operator'), '', 'center');
//        $t->set_header(Yii::t('electronic_contract', 'file_path'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Name'), '', 'center');
        $t->set_header(Yii::t('sys_operator','Operator Role'), '', 'center');
//        $t->set_header(Yii::t('como_user', 'User_phone'), '', '');
        $t->set_header(Yii::t('sys_operator', 'Record Time'), '', 'center');
        $t->set_header(Yii::t('electronic_contract', 'Action'), '20%', 'center');
        return $t;
    }

    /**
     * 操作员查询
     */
    public function actionOperatorGrid($id,$name) {
        $args = array();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $args['status'] = '0';
        $t = $this->genOperatorGrid($id,$name);
        $this->saveUrl();
        $args['contractor_id'] = $id;
        $args['operator_type'] = '01';
        $list = Operator::queryList($page, $this->pageSize, $args);
        $this->renderPartial('operator_list', array('t' => $t, 'rows' => $list['rows'], 'name'=> $name, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 操作员权限列表
     */
    public function actionSetApp() {

//        $sql = "SELECT operator_id FROM bac_operator WHERE status=0 and operator_type='01' ";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        $menu_list = App::appMenuList();
//        $status = '00';
//        foreach($rows as $k => $v){
//            foreach($menu_list as $app_id => $name){
//                $sql = "insert into bac_operator_app (operator_id,app_id,status) values (:operator_id,:app_id,:status)";
//                $command = Yii::app()->db->createCommand($sql);
//                $command->bindParam(":operator_id", $v['operator_id'], PDO::PARAM_STR);
//                $command->bindParam(":app_id", $app_id, PDO::PARAM_STR);
//                $command->bindParam(":status", $status, PDO::PARAM_STR);
//                $rs = $command->execute();
//            }
//        }
//        if ($rs) {
//            var_dump('执行成功');
//        }
        $operator_id = $_REQUEST['operator_id'];
        $contractor_id = $_REQUEST['contractor_id'];
        $contractor_name = $_REQUEST['contractor_name'];

        $this->render('operatorset',array('operator_id'=>$operator_id,'contractor_id'=>$contractor_id,'contractor_name'=>$contractor_name));
    }

    /**
     * 更新账户菜单
     */
    public function actionUpdateApp() {
        $args['operator_id'] = $_REQUEST['operator_id'];
        $args['company_id'] = $_REQUEST['company_id'];
        $args['menu_id'] = $_REQUEST['menu_id'];
//        var_dump($args);
//        exit;
        $rows = OperatorMenu::updateMenu($args);
        print_r(json_encode($rows));
    }

    /**
     * 添加账号
     */
    public function actionAddOperator() {

        $this->smallHeader = Yii::t('comp_company', 'add operator');
        $model = new Operator('create');
        $r = array();

        //echo Contractor['company_name'];

        //默认勾选人员数组
        $company_id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        $select_List = (array)Operator::myOperatorListBySuccess($company_id);
        $staff_List = (array)User::operatorListByRole($company_id);

        if (isset($_POST['Operator'])) {

            $args = $_POST['Operator'];
//            var_dump($args);
//            exit;
            $c_model = Contractor::model()->findByPk($args['company_id']);
            $args['operator_role'] = Operator::operator;
            $r = Operator::setBatchOperator($args);


            if ($r['status'] != '1') {
//                var_dump(111);
                $model->_attributes = $_POST['Operator'];
                Yii::app()->user->setFlash('error', '申请失败!');
            }else{
//                var_dump(222);
                Yii::app()->user->setFlash('success', '申请成功!');
                $this->refresh();
            }
        }
        $this->render('operator_form', array('model' => $model,'company_id'=>$company_id, 'name'=>$name,'msg' => $r,'staff_List' => $staff_List, 'select_List' => $select_List));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['info/list'] = str_replace("r=comp/info/grid", "r=comp/info/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

        
    /**
     * 根据sn查询企业编号，企业名称
     */
    public function actionQuerySn() {
        $sn = $_POST['compsn'];
        if($sn == ''){
            $data['status'] = -1;
            $data['msg'] = Yii::t('comp_contractor', 'Error company_sn is null');
            print_r(json_encode($data));
        }
        
        $rs = Contractor::model()->find('company_sn=:sn', array(':sn'=>$sn));//print_r($rs);
        if($rs['contractor_id'] <> ''){
            $data['status'] = 0;
            $data['id'] = $rs['contractor_id'];
            $data['name'] = $rs['contractor_name'];
        }else{
            $data['status'] = 1;
            $data['msg'] = Yii::t('comp_contractor', 'Error company_sn not exist');
        }
        print_r(json_encode($data));
    }

    /**
     * 根据企业名称查询企业编号
     */
    public function actionQueryName() {
        $name = $_POST['comp_name'];
        if($name == ''){
            $data['status'] = -1;
            $data['msg'] = Yii::t('comp_contractor', 'Error contractor_name is null');
            print_r(json_encode($data));
        }

        $rs = Contractor::model()->find('contractor_name=:name', array(':name'=>$name));//print_r($rs);
        if($rs['contractor_id'] <> ''){
            $data['status'] = 0;
            $data['id'] = $rs['contractor_id'];
            $data['name'] = $rs['contractor_name'];
        }else{
            $data['status'] = 1;
            $data['msg'] = Yii::t('comp_contractor', 'Error company_sn not exist');
        }
        print_r(json_encode($data));
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genAppDataGrid($contractor_id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/info/appgrid&contractor_id='.$contractor_id;
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('sys_app', 'app_id'), '', '');
        $t->set_header(Yii::t('sys_app', 'app_name'), '', '');
        $t->set_header(Yii::t('sys_app', 'open_time'), '', '');
        $t->set_header(Yii::t('sys_app', 'close_time'), '', '');
        $t->set_header(Yii::t('comp_company', 'Status'), '', '');
        $t->set_header(Yii::t('comp_company', 'Record Time'), '', '');
        $t->set_header(Yii::t('comp_company', 'Action'), '20%', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionAppGrid($contractor_id) {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        //$args['status'] = '0';
        $t = $this->genAppDataGrid($contractor_id);
        $this->saveUrl();
        $args['status'] = 0;
        $args['contractor_id'] = $contractor_id;
        $list = ContractorApp::queryList($page, $this->pageSize, $args);
        $this->renderPartial('app_list', array('t' => $t,'contractor_id'=>$contractor_id, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionAppList() {
        $contractor_id = $_REQUEST['id'];
        $this->smallHeader = Yii::t('sys_app', 'smallHeader List');
        $this->render('applist',array('contractor_id'=>$contractor_id));
    }

    /**
     * 添加
     */
    public function actionNewApp() {
        $contractor_id = $_REQUEST['contractor_id'];
        $this->smallHeader = Yii::t('comp_company', 'smallHeader New');
        $model = new ContractorApp('create');
        $r = array();

        //echo Contractor['company_name'];

        if (isset($_POST['ContractorApp'])) {

            $args = $_POST['ContractorApp'];

            $r = ContractorApp::insertContractorApp($args);

            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['ContractorApp'];
            }
        }
        $this->render('app_form', array('model' => $model,'contractor_id'=>$contractor_id,'_mode_' => 'insert','msg' => $r));
    }

    /**
     * 修改
     */
    public function actionEditApp() {
        $contractor_id = $_REQUEST['contractor_id'];
        $app_id = $_REQUEST['app_id'];
        $this->smallHeader = Yii::t('comp_company', 'smallHeader Edit');
        $model = new ContractorApp('modify');
        $id = trim($_REQUEST['id']);
        $model->_attributes = ContractorApp::model()->find("contractor_id=:contractor_id and app_id=:app_id", array("contractor_id" => $contractor_id,"app_id" => $app_id));
        //$seq_id = sprintf('%05s', $id);
        //$operator_id = self::CONTRACTOR_PREFIX . $seq_id;
//        var_dump($args['operator_id']);
//        exit;
        $r = array();
        if (isset($_POST['ContractorApp'])) {
            $args = $_POST['ContractorApp'];
            $args['contractor_id'] = $contractor_id;
            $args['id'] = $app_id;
            $args['status'] = 0;
            $model = ContractorApp::model()->find("contractor_id=:contractor_id and app_id=:app_id", array("contractor_id" => $contractor_id,"app_id" => $app_id));
//            var_dump($args);
//            exit;
            if($args['tmp_src']){
                $remark = $model->app_icon;
                if($remark){
                    $path = '/opt/www-nginx/web'.$remark;
                    unlink($path);
                }
                $re = ContractorApp::movePic($args['tmp_src'],$id);
                $args['app_icon'] = $re['src'];
            }
            //var_dump($model->company_sn.'--'.$args['company_sn']);

            $r = ContractorApp::updateContractorApp($args);
            if ($r['refresh'] == false) {
                $model->_attributes = $_POST['ContractorApp'];
            }else{
                $model = ContractorApp::model()->find("contractor_id=:contractor_id and app_id=:app_id", array("contractor_id" => $contractor_id,"app_id" => $app_id));
            }
        }

        $this->render('app_form', array('contractor_id' => $contractor_id,'model' => $model, 'msg' => $r,'_mode_'=>'edit'));
    }

    /**
     * 注销
     */
    public function actionLogoutApp() {
        $contractor_id = trim($_REQUEST['contractor_id']);
        $app_id = trim($_REQUEST['app_id']);
        $r = array();
        if ($_REQUEST['confirm']) {
            $r = ContractorApp::logoutApp($contractor_id,$app_id);
        }
        //var_dump($r);
        echo json_encode($r);
    }
    /**
     * 生成token
     */
    public function actionCreateToken() {
        $result = json_decode(Utils::createToken());
        $r['token'] = $result->result->token;

        print_r(json_encode($r));
    }
    /**
     * 调取接口获取公司信息
     */
    public function actionGetCompInfo() {
        $data['host'] = 'CSD';
        $data['company_sn'] = $_REQUEST['company_sn'];
        $data['token'] = $_REQUEST['token'];
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $data = json_encode($post_data);
        $module = 'CMSGetConInfo';
        $out = json_decode(ShellInterface::test($data,$module));
        $r['errno'] = $out->errno;
        $r['company_adr'] = $out->result[0]->company_adr;
        $r['link_person'] = $out->result[0]->link_person;
        $r['company_name'] = $out->result[0]->company_name;
        $r['link_phone'] = $out->result[0]->link_phone;
        $r['cms_conid'] = $out->result[0]->cms_conid;
        print_r(json_encode($r));
    }


    /**
     * 列表
     */
    public function actionPicList() {
        $id = $_REQUEST['id'];
        $this->smallHeader = Yii::t('common', 'app_pic');
        $this->render('piclist',array('id' => $id));
    }

    /**
     * 电子合约表头
     * @return SimpleGrid
     */
    private function genPicGrid($id) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/info/picgrid&id='.$id;
        $t->updateDom = 'datagrid';
//        $t->set_header(Yii::t('comp_safety', 'check_id'), '', '');
        $t->set_header(Yii::t('comp_ctc', 'document'), '', 'center');
//        $t->set_header(Yii::t('comp_obs', 'status'), '', 'center');
        $t->set_header(Yii::t('common', 'action'), '30%', 'center');
        return $t;
    }

    /**
     * 电子合约列表
     */
    public function actionPicGrid($id) {
        $args = array();
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        //$args = $_GET['q']; //查询条件
        //$args['status'] = '0';
        $t = $this->genPicGrid($id);
        $this->saveUrl();
        $args['contractor_id'] = $id;
        $list = Contractor::picList($page, $this->pageSize, $args);
        $this->renderPartial('pic_list', array('t' => $t, 'rows' => $list['rows'],'contractor_id'=>$id, 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 权限列表
     */
    public function actionAuthorityList() {
        $operator_id = $_REQUEST['operator_id'];
        $contractor_id = $_REQUEST['contractor_id'];
        $company_name = $_REQUEST['company_name'];
        $operator_model = Operator::model()->findByPk($operator_id);
        $operator_role = $operator_model->operator_role;
        if($operator_role == '00'){
            $this->smallHeader = $operator_id;
        }else{
            $user_list = Staff::phoneList($operator_id);
            foreach ($user_list as $user_id => $user_name){
                $this->smallHeader = $user_name;
            }
        }
        $this->bigMenu = Yii::t('sys_operator', 'smallHeader List');
        $this->contentHeader = Yii::t('proj_project', 'Project Set');
        //$this->smallHeader = Yii::t('proj_project_user', 'smallHeader List');
        $this->render('authoritylist', array('operator_id' => $operator_id,'contractor_id' => $contractor_id,'company_name'=>$company_name));
    }

    /**
     * 权限设置表头
     * @return SimpleGrid
     */
    private function genAuthorityGrid($operator_id,$contractor_id,$company_name) {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=comp/info/authoritygrid&operator_id='.$operator_id.'&contractor_id='.$contractor_id.'&company_name='.$company_name;
        $t->updateDom = 'datagrid';
//        $t->set_header('<label class="select_all"><input type="checkbox" id="checkAll" class="select_all" name="checkAll" onclick="test(this);"></label>', '', '');
        $t->set_header(Yii::t('proj_project','program_id'), '', 'center');
        $t->set_header(Yii::t('com_contractor','Contractor_name'), '', 'center');
        $t->set_header(Yii::t('sys_attend','proj_name'), '', 'center');
        $t->set_header(Yii::t('proj_project','Project Set'), '', 'center');
        return $t;
    }

    /**
     * 权限查询
     */
    public function actionAuthorityGrid($operator_id,$contractor_id,$company_name) {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        $args['operator_id'] = $operator_id;
        $args['contractor_id'] = $contractor_id;
        $args['company_name'] = $company_name;
        $args['status'] = '0';
        $t = $this->genAuthorityGrid($operator_id,$contractor_id,$company_name);
        $this->saveUrl();

        if($args['project_type'] == '')
            $args['project_type'] = Yii::app()->session['project_type'];

        if(!$contractor_id){
            $args['contractor_id'] = $contractor_id;
        }else{
            $args['contractor_id'] = Yii::app()->user->contractor_id;
        }

//        var_dump($args);
//        exit;
        $list = OperatorProject::queryList($page, $this->pageSize, $args);

        //var_dump($list['rows']);
        $this->renderPartial('authority_list', array('t' => $t, 'operator_id' => $operator_id,'contractor_id' => $contractor_id,'company_name'=>$company_name,'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 项目权限数据源
     */
    public function actionSetProSource() {
        if (Yii::app()->language == 'zh_CN') {
            $pro_flag = array("0" => "查看", "1" => "编辑","2" => "屏蔽");
        }else{
            $pro_flag = array("0" => "View","1" => "Edit","2" => "Hide");
        }
        print_r( json_encode($pro_flag,true));
    }

    /**
     * 设置项目权限
     */
    public function actionSetAuthority() {
        $args['operator_id'] = $_REQUEST['operator_id'];
        $args['program_id'] = $_REQUEST['program_id'];
        $args['value'] = $_REQUEST['value'];
//        var_dump($args);
//        exit;
        $rs = OperatorProject::SetAuthority($args);
        echo json_encode($rs);
    }

    /**
     * 删除操作员
     */
    public function actionDeleteOperator(){
        $operator_id = $_REQUEST['id'];
        $rs = Operator::logoutOperator($operator_id);
        echo json_encode($rs);
    }

    /**
     * 设置操作员
     */
    public function actionSetOperator(){
        $tag = $_REQUEST['tag'];
        $rs = Operator::setBatchOperator($tag);
        echo json_encode($rs);
    }
}
