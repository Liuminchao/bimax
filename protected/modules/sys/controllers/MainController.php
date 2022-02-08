<?php

class MainController extends AuthBaseController {

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
            $this->layout = '//layouts/main_1';
        }else{
            $this->layout = '//layouts/main';
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
        $t->url = 'index.php?r=sys/main/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('proj_project', 'program_name'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'Contractor_name'), '', '');
        $t->set_header(Yii::t('electronic_contract', 'start_date'), '', '');
        $t->set_header(Yii::t('electronic_contract', 'end_date'), '', '');
        $t->set_header(Yii::t('comp_contractor', 'Status'), '', '');
//        $t->set_header(Yii::t('comp_contractor', 'Record Time'), '', '');
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
        $list = Program::queryMainList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {
        $this->smallHeader = Yii::t('comp_contractor', 'smallHeader List');
        $this->render('list');
    }

    /**
     * 参数设置
     */
    public function actionSetParams() {

        $program_id = $_REQUEST['program_id'];
        $model = new Program('modify');
        $model->_attributes = Program::model()->findByPk($program_id);
        $this->renderPartial('params',array('pro_model'=>$model,'program_id'=>$program_id));
    }

    /*
     * 更新模块设置
     */
    public function actionUpdateParams(){
        $args = $_POST['Program'];
        $args['start_date'] = Utils::DateToCn($args['start_date']);
        $args['end_date'] = Utils::DateToCn($args['end_date']);
        $r = Program::updateProgramParams($args);
        echo json_encode($r);
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
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['info/list'] = str_replace("r=comp/info/grid", "r=comp/info/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
