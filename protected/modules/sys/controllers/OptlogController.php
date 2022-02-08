<?php
/**
 * 系统日志
 * @author LiuXiaoyuan
 */
class OptlogController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('sys_optlog', 'contentHeader');
        $this->bigMenu = Yii::t('sys_optlog', 'bigMenu');
    }

    /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=sys/optlog/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('sys_optlog', 'Log Sn'), '', '');
        $t->set_header(Yii::t('sys_optlog', 'Module'), '', '');
        $t->set_header(Yii::t('sys_optlog', 'Operator'), '', '');
        $t->set_header(Yii::t('sys_optlog', 'Opt Field'), '', '');
        $t->set_header(Yii::t('sys_optlog', 'Opt Result'), '', '');
        $t->set_header(Yii::t('sys_optlog', 'Opt Host Ip'), '', '');
        $t->set_header(Yii::t('sys_optlog', 'Opt Time'), '', '');
        return $t;
    }

    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件

        $t = $this->genDataGrid();
        $this->saveUrl();

        $list = OperatorLog::queryList($page, $this->pageSize, $args);
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num']));
    }

    /**
     * 列表
     */
    public function actionList() {

        $this->smallHeader = Yii::t('sys_optlog', 'smallHeader');
        $this->render('list');
    }

    /**
     * 详细
     */
    public function actionDetail() {

        $id = $_POST['id'];
        $msg['status'] = true;

        $model = OperatorLog::model()->findByPk($id);

        if ($model) {

             $opt_desc = json_decode($model->opt_desc);

            if ($opt_desc) {
                $msg['detail'] .= "<table class='detailtab'>";
                $msg['detail'] .= "<tr class='form-name'>";
                $msg['detail'] .= "<td colspan='4'>".Yii::t('sys_optlog', 'Opt Desc')."</td>";
                $msg['detail'] .= "</tr>";

                foreach ($opt_desc as $title => $value) {
                    $msg['detail'] .= "<tr>";
                    $msg['detail'] .= "<td class='tname-2'>" . $title . "：</td>";
                    $msg['detail'] .= "<td class='tvalue-4'>" . (isset($value) ? $value : Yii::t('common', 'empty')) . "</td>";
                    $msg['detail'] .= "</tr>";
                }
                $msg['detail'] .= "</table>";
            } else {
                $msg['detail'] = Yii::t('common', 'error_info_is_null');
            }
        } else {
            $msg['status'] = false;
            $msg['detail'] = Yii::t('common', 'error_info');
        }
        print_r(json_encode($msg));
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['optlog/list'] = str_replace("r=sys/optlog/grid", "r=sys/optlog/list", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }

}
