<?php

/*
 * 接收参数
 */

class ChartController extends CController{

    public function actionSend() {
        $app_id = $_REQUEST['app_id'];
        $program_id = $_REQUEST['program_id'];
//        $type = array(
//            'PTW_PROJ' => 'index.php?r=license/licensepdf/projectchart',
//            'PTW_COMP' => 'index.php?r=license/licensepdf/companychart',
//            'PTW_COND' => 'index.php?r=license/licensepdf/testchart',
//            'TBM_COMP' => 'index.php?r=tbm/meeting/companychart',
//            'CHECKLIST_PROJ' => 'index.php?r=routine/routineinspection/projectchart',
//            'CHECKLIST_COMP' => 'index.php?r=routine/routineinspection/companychart',
//            'WSH_PROJ' => 'index.php?r=wsh/wshinspection/projectchart',
//            'WSH_COMP' => 'index.php?r=wsh/wshinspection/companychart',
//            'RA_PROJ' => 'index.php?r=ra/raswp/projectchart',
//            'RA_COMP' => 'index.php?r=ra/raswp/companychart',
//            'TRAIN_PROJ' => 'index.php?r=train/training/projectchart',
//            'TRAIN_COMP' => 'index.php?r=train/training/companychart',
//            'MEET_PROJ' => 'index.php?r=meet/meeting/projectchart',
//            'MEET_COMP' => 'index.php?r=meet/meeting/companychart',
//            'ACCIDENT_COMP' => 'index.php?r=accidents/accident/companychart',
//            'QA' => 'index.php?r=qa/qainspection/projectchart',
//        );
        $type = array(
            'PTW' => 'index.php?r=license/licensepdf/projectchart',
            'TBM' => 'index.php?r=tbm/meeting/companychart',
            'CHECKLIST' => 'index.php?r=routine/routineinspection/projectchart',
            'WSH' => 'index.php?r=wsh/wshinspection/projectchart',
            'RA' => 'index.php?r=ra/raswp/projectchart',
            'TRAIN' => 'index.php?r=train/training/projectchart',
            'MEET' => 'index.php?r=meet/meeting/projectchart',
            'ACCIDENT' => 'index.php?r=accidents/accident/companychart',
            'QA' => 'index.php?r=qa/qainspection/projectchart',
        );
        $url = $type[$app_id];
        $url = $url.'&program_id='.$program_id.'&platform=dashboard';
        $this->redirect($url);
//        var_dump($url);
//        exit;
    }

    public function actionReport() {
        $data_id = $_REQUEST['data_id'];
        $check_id = $_REQUEST['check_id'];
        $url = 'index.php?r=qa/qainspection/qaexport';
        $url = $url.'&data_id='.$data_id.'&check_id='.$check_id;
        $this->redirect($url);
//        var_dump($url);
//        exit;
    }

    public function actionRf() {
        $check_id = $_REQUEST['check_id'];
        $url = 'index.php?r=rf/rf/info';
        $url = $url.'&check_id='.$check_id.'&platform=dashboard';
        $this->redirect($url);
//        var_dump($url);
//        exit;
    }

}
