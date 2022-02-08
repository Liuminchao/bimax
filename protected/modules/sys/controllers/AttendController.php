<?php

/**
 * 失败考勤记录
 *
 * @author liuxiaoyuan
 */
class AttendController extends BaseController {

    public $defaultAction = 'record';
    public $gridId = 'example2';

    public function init() {
        parent::init();

        $this->bigMenu = Yii::t('dboard', 'Menu Attend');
        $this->contentHeader = Yii::t('dboard', 'Menu Attend2');
        $this->smallHeader = Yii::t('dboard', 'Menu Attend record');
    }
    
     /**
     * 表头
     * @return SimpleGrid
     */
    private function genDataGrid() {
        $t = new DataGrid($this->gridId);
        $t->url = 'index.php?r=sys/attend/grid';
        $t->updateDom = 'datagrid';
        $t->set_header(Yii::t('sys_attend', 'proj_name'), '', '');//项目名称
        $t->set_header(Yii::t('sys_attend', 'contractor_name'), '', '');//承包商名称
        $t->set_header(Yii::t('sys_attend', 'user_name'), '', '');//用户
        $t->set_header(Yii::t('sys_attend', 'user_isdn'), '', '');//手机号码
        $t->set_header(Yii::t('sys_attend', 'card_time'), '', '');//考勤时间
        $t->set_header(Yii::t('sys_attend', 'card_result'), '', '');//考勤结果
        $t->set_header(Yii::t('sys_attend', 'img'), '', '');//失败考勤照片
        return $t;
    }
    
    /**
     * 查询
     */
    public function actionGrid() {
        $page = $_GET['page'] == '' ? 0 : $_GET['page']; //当前页码
        $_GET['page'] = $_GET['page'] + 1;
        $args = $_GET['q']; //查询条件
        
        //var_dump($args);
        
        $t = $this->genDataGrid();
        $this->saveUrl();
        $args['role_id'] = Yii::app()->user->role_id;
        
        //if($args['role_id']=="cmanager")
        //$args['contractor_id'] = Yii::app()->user->contractor_id;
        
       //$args['program_id'] = Yii::app()->session['program_id'];
        
        $list = ProjectAttend::record($page, $this->pageSize, $args);
        
        //var_dump($args['role_id']);
        
        $program_list = Program::programList($args);
        $contractor_list = Contractor::compAllList();
        $attend_result = ProjectAttend::getRusult();
        
        $this->renderPartial('_list', array('t' => $t, 'rows' => $list['rows'], 'cnt' => $list['total_num'], 'curpage' => $list['page_num'], 'program_list'=>$program_list, 'contractor_list'=>$contractor_list, 'attend_result'=>$attend_result));
    }

    /**
     * 列表
     */
    public function actionRecord() {

        $this->render('list');
    }

    /**
     * 保存查询链接
     */
    private function saveUrl() {

        $a = Yii::app()->session['list_url'];
        $a['attendrecord/list'] = str_replace("r=sys/attend/grid", "r=sys/attend/record", $_SERVER["QUERY_STRING"]);
        Yii::app()->session['list_url'] = $a;
    }
    
    /**
     * img src的路径
     */
    public function actionViewImg() {
        $_file = $_GET['img_url'];
        
        if(!file_exists($_file) || ! @fopen( $_file, 'r' ) ) {
            $_file = Yii::app()->params['basePath'] . 'images/default_no_img.jpg'; //刷卡记录图片没有时，显示默认图片
        }
        //echo $_file;exit;
        Header("Content-type: image/jpeg");
        //Header("Accept-Ranges: bytes");
        print_r(file_get_contents($_file));
        exit;
    }
}
