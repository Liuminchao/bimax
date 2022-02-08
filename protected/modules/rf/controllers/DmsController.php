<?php

/**
 * RFA 图纸
 * @author LiuMinchao
 */
class DmsController extends AuthBaseController {

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


    /**
     * 添加公司
     */
    public function actionNewContractor() {
        $contractor_id = $_REQUEST['id'];
        $contractor_name = $_REQUEST['name'];
        $result = Dms::NewContractor($contractor_id);
        if($result['code'] == '100'){
            $con_model =Contractor::model()->findByPk($contractor_id);
            $params = $con_model->params;
            $params  = json_decode($params,true);
            $params['dms'] = '1';
            $json_params = json_encode($params);
            $con_model->params = $json_params;
            $con_model->save();
        }
        print_r(json_encode($result));
    }

    /**
     * 编辑公司
     */
    public function actionEditContractor() {
        $contractor_id = $_REQUEST['id'];
        $contractor_name = $_REQUEST['name'];
        $result = Dms::EditContractor($contractor_id,$contractor_name);
        print_r(json_encode($result));
    }

    /**
     * 添加项目
     */
    public function actionNewProgram() {
        $program_id = $_REQUEST['id'];
        $program_name = $_REQUEST['name'];
        $result = Dms::NewProgram($program_id,$program_name);
        if($result['code'] == '100'){
            $pro_model =Program::model()->findByPk($program_id);
            $params = $pro_model->params;
            if($params != '0' || params != ''){
                $params  = json_decode($params,true);
            }else{
                $params = array();
            }
            $params['dms'] = '1';
            $json_params = json_encode($params);
            $pro_model->params = $json_params;
            $pro_model->save();
        }
        print_r(json_encode($result));
    }

    /**
     * 添加人员
     */
    public function actionNewUser() {
        $user_id = $_REQUEST['id'];
        $result = Dms::NewUser($user_id);
        if($result['code'] == '100'){
            $staff_model =Staff::model()->findByPk($user_id);
            $staff_model->dms_tag = '1';
            $staff_model->save();
        }
        print_r(json_encode($result));
    }

    /**
     * 将人员加入组
     */
    public function actionAddGroup() {
        $program_id = $_REQUEST['program_id'];
        $pro_model =Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $user_id = $_REQUEST['user_id'];
        $result = Dms::NewUser($user_id);
        if($result['code'] == '100'){
            $staff_model =Staff::model()->findByPk($user_id);
            $staff_model->dms_tag = '1';
            $staff_model->save();
        }
//        $result = Dms::AddGroup($root_proid,$user_id);
        $result = Dms::NewUser($user_id,$root_proid);
        if($result['code'] == '100'){
            $sql = "UPDATE bac_program_user_q SET dms_tag = '1' WHERE user_id = '".$user_id."' and program_id ='".$program_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->execute();
        }
        print_r(json_encode($result));
    }

    /**
     * 将人员移除组
     */
    public function actionRemoveGroup() {
        $program_id = $_REQUEST['program_id'];
        $pro_model =Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $user_id = $_REQUEST['user_id'];
        $result = Dms::RemoveGroup($root_proid,$user_id);
        if($result['code'] == '100'){
            $sql = "UPDATE bac_program_user_q SET dms_tag = '0' WHERE user_id = '".$user_id."' and program_id ='".$program_id."' ";
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->execute();
        }
        print_r(json_encode($result));
    }

    /**
     * 列表
     */
    public function actionLogin() {
        $id = $_REQUEST['id'];
        $tag = $_REQUEST['tag'];
//        $id = '1261';
        $pro_model =Program::model()->findByPk($id);
        $new_robox = $pro_model->new_robox;
        $program_name = $pro_model->program_name;
        if($pro_model->root_proid == $id){
            $program_id = $pro_model->root_proid;
        }else{
            $program_id = $id;
        }

        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_id = $user[0]['user_id'];
        if($tag == 'admin'){
            if($new_robox != ''){
                $user_id = $new_robox.'_admin';
            }else{
                $user_id = $id.'_admin';
            }
        }
//        $user_id = '52489';
        $staff_model =Staff::model()->findByPk($user_id);
        $contractor_id = $staff_model->contractor_id;
        $con_model =Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
//        $result = Dms::NewContractor($contractor_id);
//        $result = Dms::NewProgram($contractor_id,$contractor_name);
//        var_dump($result);
//        $result = Dms::NewUser($user_id);
//        var_dump($result);
//        var_dump($result);
//        exit;
//        if($result['code'] == '100'){
//            $staff_model =Staff::model()->findByPk($user_id);
//            $staff_model->dms_tag = '1';
//            $staff_model->save();
//        }

//        var_dump($program_id);
//        if($result['code'] == '100'){
//            $sql = "UPDATE bac_program_user_q SET dms_tag = '1' WHERE user_id = '".$user_id."' and program_id ='".$program_id."' ";
//            $command = Yii::app()->db->createCommand($sql);
//            $rows = $command->execute();
//        }
        $result = Dms::login($user_id);
        $rs['path'] = $result;
//        exit;
        print_r(json_encode($rs));
    }
}
