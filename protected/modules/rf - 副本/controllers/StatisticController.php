<?php

/**
 * RFA 统计
 * @author LiuMinchao
 */
class StatisticController extends AuthBaseController {

    public $defaultAction = 'list';
    public $gridId = 'example2';
    public $contentHeader = '';
    public $bigMenu = '';
    const STATUS_NORMAL = 0; //正常

    public function init() {
        parent::init();
        $this->contentHeader = 'Dms test';
        $this->bigMenu = 'Dms';
    }

    /*
     * rfa/rfi 根据状态统计
     */
    public function actionQueryStatus() {
        $project_id = $_REQUEST['project_id'];
        $type = $_REQUEST['type'];
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = Yii::app()->user->id;
        }
        $url = 'https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjRFstate';
        $post_data = array(
            'project_id' => $project_id,
            'user' => $user_id,
            'token'   => 'lalala',
            'type' => $type,
            'rtype' => '0'
        );
        $data = json_encode($post_data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        print_r($output);

    }

    /*
     * rfa/rfi 根据group统计
     */
    public function actionQueryGroup() {
        $project_id = $_REQUEST['project_id'];
        $type = $_REQUEST['type'];
        $major_id = $_REQUEST['major_id'];
        $form_id = $_REQUEST['form_id'];
        if($major_id == '0'){
            $major_id = (int)$major_id;
        }
        if($form_id == '0'){
            $form_id = (int)$form_id;
        }
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = Yii::app()->user->id;
        }

        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'type' => $type,
            'rtype' => '0',
            'user' => 'null'
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjRFstate";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $r = json_decode($output,true);

        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'type' => $type,
            'user' => 'null',
            'major_id' => $major_id,
            'form_id' => $form_id
        );
        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjRFComState";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        $contractor_list = array();
        $t = array();
        foreach ($rs['result'] as $i => $j) {
            if($j['contractor_name'] == NULL){
                $contractor_list[] = $j['cname'];
            }else{
                $contractor_list[] = $j['contractor_name'];
            }
        }
        $contractor_list = array_unique($contractor_list);

        $index = 0;
        foreach ($contractor_list as $d => $contractor_name){
            $l[$index]['0'] = 0;
            $l[$index]['1'] = 0;
            $l[$index]['2'] = 0;
            $l[$index]['contractor_name'] = $contractor_name;
            $index++;
        }
        $status_list = array(
            '0' => "On-going",
            '1' => "Closed",
            '2' => "WIP-Overdue",
        );
        foreach ($rs['result'] as $x => $y){
//            $t[$x]['name'] = $status_name;
            $p = array();
            $index = 0;
            foreach ($l as $i => $j) {
                if($y['status'] != '-1'){
                    if($y['contractor_name'] == NULL){
                        if($j['contractor_name'] == $y['cname']){
                            $l[$i][$y['status']] = (int)$y['count'];
                        }
                    }else{
                        if($j['contractor_name'] == $y['contractor_name']){
                            $l[$i][$y['status']] = (int)$y['count'];
                        }
                    }
                }
            }
        }
        $color_list = array(
            '0' => "#0000FF",
            '1' => "#008000",
            '2' => "#FF0000",
        );
        $p[] = array();
        $con_list = [];
        foreach($l as $i => $j){
            foreach($j as $o => $h){
                //索引0 等于 false  等于任何值 所以改用长度判断 区分contractor_name
                if(strlen($o)>1){
                    $con_list[] = $h;
                }else{
//                    if($h>0){
                        $f = array();
                        if($h>0){
                            $cnt = $h;
                        }else{
                            $cnt = null;
                        }
                        $f['y'] = $cnt;
                        $f['color'] = $color_list[$o];
                        $p[$o]['name'] = $status_list[$o];
                        $p[$o]['data'][] = $f;
//                    }
                }
            }
        }

        $g['x'] = $p;
        $g['y'] = $con_list;
        print_r(json_encode($g));

    }

    /*
     * rfa/rfi 根据discipline统计
     */
    public function actionQueryDiscipline() {
        $project_id = $_REQUEST['project_id'];
        $type = $_REQUEST['type'];
        $form_id = $_REQUEST['form_id'];
        if($form_id == '0'){
            $form_id = (int)$form_id;
        }
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
        }else{
            $user_id = Yii::app()->user->id;
        }
        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'type' => $type,
            'rtype' => '0',
            'user' => 'null'
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjRFstate";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $r = json_decode($output,true);

        $data = array(
            'project_id' => $project_id,
            'token' => 'lalala',
            'type' => $type,
            'user' => 'null',
            'form_id' => $form_id
        );

        $post_data = json_encode($data);

        $url = "https://www.beehives.sg/cms_dashb/dbapi?cmd=DBProjRFdiscipline";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $s = json_decode($output,true);

        $status_list = array(
            '0' => "On-going",
            '1' => "Closed",
            '2' => "WIP-Overdue",
        );
        $color_list = array(
            '0' => "#0000FF",
            '1' => "#008000",
            '2' => "#FF0000",
        );
        $discipline_list = array(
            '1' => "Structural",
            '2' => "Architecture",
            '3' => "MEP",
        );

        $index = 0;
        foreach ($discipline_list as $d => $discipline_name){
            $l[$index]['0'] = 0;
            $l[$index]['1'] = 0;
            $l[$index]['2'] = 0;
            $l[$index]['discipline_name'] = $discipline_name;
            $index++;
        }
        foreach ($s['result'] as $x => $y){
//            $t[$x]['name'] = $status_name;
            $p = array();
            $index = 0;
            foreach ($l as $i => $j) {
                if($y['discipline'] != 'null'){
                    if($j['discipline_name'] == $discipline_list[$y['discipline']]){
                        if($y['status'] != '-1'){
                            $l[$i][$y['status']] = (int)$y['count'];
                        }
                    }
                }
            }
        }
        $p[] = array();
        $discipline_arr = [];
        foreach($l as $i => $j){
            foreach($j as $o => $h){
                //索引0 等于 false  等于任何值 所以改用长度判断 区分contractor_name
                if(strlen($o)>1){
                    $discipline_arr[] = $h;
                }else{
                    $f = array();
                    if($h>0){
                        $cnt = $h;
                    }else{
                        $cnt = null;
                    }
                    $f['y'] = $cnt;
                    $f['color'] = $color_list[$o];
                    $p[$o]['name'] = $status_list[$o];
                    $p[$o]['data'][] = $f;
                }
            }
        }
        $g['x'] = $p;
        $g['y'] = $discipline_arr;
        print_r(json_encode($g));

    }
}
